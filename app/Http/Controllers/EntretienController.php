<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 300); //5 minutes

use App\Campaign;
use App\Console\Commands\CampaignEmailing;
use App\Fonction;
use App\Http\Service\Table;
use App\Team;
use Illuminate\Http\Request;
use App\Http\Mail\MailerController;
use Auth;
use Illuminate\Support\Facades\Input;
use Session;
use DB;
use App\User;
use App\Entretien;
use App\Entretien_user;
use App\Skill_user;
use App\Question;
use App\Survey;
use App\Evaluation;
use Carbon\Carbon;
use App\EntretienObjectif;
use App\Formation;
use App\Skill;
use App\Objectif;
use App\Carreer;
use App\Salary;
use App\Comment;
use App\Action;
use App\Email;
use App\Entretien_evaluation;
use App\Answer;
use Excel;
class EntretienController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }


  /**
   * Display a listing of the resource.
   */
  public function entretiensList(Request $request)
  {
    ob_start();
    $ids = json_encode($request->ids);
    $entretiens = Entretien::getAll()->get();
    echo view('entretiens.list', compact('entretiens', 'ids'));
    $content = ob_get_clean();
    return ['title' => __("Liste des entretiens"), 'content' => $content];
  }

  /**
   * Display a listing of the resource.
   */
  public function indexEntretien(Request $request)
  {
    $per_page = $selected = 6;
    if (isset($request->per_page) && $request->per_page != "all") {
      $per_page = $request->per_page;
      $selected = $per_page;
    } else if (isset($request->per_page) && $request->per_page == "all" || $request->status == 'all') {
      $per_page = 500;
      $selected = "all";
    }
    $query = Entretien::getAll();
    if ($status = $request->get('status', Entretien::CURRENT_STATUS)) {
      if ($status == Entretien::CURRENT_STATUS) {
        $query->where('date_limit', '>=', date('Y-m-d'));
      } else if ($status == Entretien::EXPIRED_STATUS) {
        $query->where('date_limit', '<', date('Y-m-d'));
      } else if ($status == Entretien::FINISHED_STATUS) {
        $query->where('status', $status);
      }
    }
    $query->orderBy('id', 'DESC');

    $evaluations = Evaluation::all()->sortBy('sort_order');
    $objectifs = EntretienObjectif::getAll()->get();
    $results = $query->paginate($per_page);
    $results->appends(Input::except('page'));
    return view('entretiens.index', compact('results', 'selected', 'evaluations', 'objectifs'));
  }

  public function show($id)
  {
    $e = Entretien::findOrFail($id);
    if ($e->user_id != User::getOwner()->id) {
      abort(403);
    }
    if ($e->isFeedback360()) {
      $teamsUsers = [];
      if (!empty($e->users[0]->teams)) {
        foreach ($e->users[0]->teams as $team) {
          $teamsUsers[] = $team->users;
        }
      }
      $entrentiensList = isset($teamsUsers[0]) ? $teamsUsers[0] : [];
    } else {
      $entrentiensList = $e->users;
    }

    return view('entretiens.show', compact('e', 'entrentiensList'));
  }

  /**
   * Display a listing of the resource.
   */
  public function entretiensEval(Request $request)
  {
    $dlimite = $request->dlimite;
    $title = $request->title;
    $uname = $request->uname;
    $ufunction = $request->ufunction;

    $entretiens = Entretien::getAll()->paginate(10);
    $fonctions = Fonction::getAll()->get();
    $query = DB::table('entretiens as e')
      ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
      ->join('users as u', 'u.id', '=', 'eu.user_id')
      ->select('e.*', 'e.id as entretienId', 'u.*', 'u.id as userId', 'eu.note')
      ->where('e.user_id', User::getOwner()->id);
    $params = false;
    if (!empty($dlimite)) {
      $dlimite = Carbon::createFromFormat('d-m-Y', $dlimite)->toDateString();
      $query->where('e.date_limit', '=', $dlimite);
      $params = true;
    }
    if (!empty($title)) {
      $query->where('e.id', '=', $title);
      $params = true;
    }
    if (!empty($uname)) {
      $query->where('u.name', 'like', '%' . $uname . '%');
      $params = true;
    }
    if (!empty($ufunction)) {
      $query->where('u.function', '=', $ufunction);
      $params = true;
    }
    $results = $query->paginate(10);

    return view('entretiens.eval-in-progress', compact(
      'fonctions', 'entretiens', 'results', 'dlimite', 'title', 'uname', 'ufunction', 'params'
    ));
  }

  /**
   * Display a listing of the resource.
   */
  public function synthese($e_id, $uid)
  {
    $entretien = Entretien::findOrFail($e_id);
    if (!$entretien->canBeFilledByUser($uid)) {
      return redirect()->route('home')->with("danger", Entretien::canBeFilledByUserMessage());
    }
    $evaluations = Entretien::findEvaluations($entretien);
    // $evaluation = Evaluation::where('title', $type)->first();
    $user = $entretien->users()->where('entretien_user.user_id', $uid)->first();
    return view('entretiens.synthese', [
      'e' => $entretien,
      'user' => $user,
      'evaluations' => $evaluations,
      // 'evaluation' => $evaluation
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function form(Request $request)
  {
    $id = $request->id;
    $evaluations = Evaluation::all()->sortBy('sort_order');
    ob_start();
    if (isset($id) && is_numeric($id)) {
      $entretien = Entretien::findOrFail($id);
      if ($entretien->user_id != User::getOwner()->id) {
        abort(403);
      }
      $title = __("Modifier la campagne");
    } else {
      $entretien = new Entretien();
      $title = __("Ajouter une campagne");
    }
    $e_users = $entretien->getUsersEvaluators();

    $users = User::getUsers()->where('user_id', '<>', 0)->get(); // get just users having their managers
    $entretienEvalIds = $entretien->evaluations()->pluck('evaluation_id')->toArray();
    $objectifs = EntretienObjectif::getAll()->get();
    echo view('entretiens.form', compact('users', 'e_users', 'entretien', 'evaluations', 'entretienEvalIds', 'objectifs'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }


  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $model = $request->model;
    $id = $request->id;
    $selectedUsers = [];

    if (!empty($request->usersIdToEvaluates)) {
      foreach ($request->usersIdToEvaluates as $user_id) {
        $selectedUsers[] = $user_id;
      }
    }

    $selectedUsers = array_unique($selectedUsers);

    $entretienUsers = $removedUsers = [];
    $evaluationsId = $request->items;
    
    if (isset($id) && is_numeric($id)) {
      $entretien = Entretien::findOrFail($id);
      if ($entretien->isFeedback360()) {
        $entretienUsers = $entretien->users()->pluck('mentor_id')->toArray();
      } else {
        $entretienUsers = $entretien->users()->pluck('users.id')->toArray();
      }

      $removedUsers = array_diff($entretienUsers, $selectedUsers);
      $selectedUsers = array_diff($selectedUsers, $entretienUsers);
    } else {
      $entretien = new Entretien();
    }

    $rules = [
      'date' => 'required',
      'date_limit' => 'required|after:date',
      'end_periode' => 'after:start_date',
      'titre' => 'required|min:3|max:50|regex:/^[0-9a-zÀ-ú\s\-_"°^\'’.,:]*$/i',
    ];
    $messages = [
      'date.required' => "La date limite de l'évalué est obligatoire",
      'date_limit.required' => "La date de limite de l'évaluateur est obligatoire",
      'date_limit.after' => "La date limite de l'évaluateur doit être une date supérieure à la date limite de l'évalué",
      'titre.required' => "Le titre de la campagne est obligatoire",
      'titre.min' => "Le titre de la campagne ne peut pas contenir moins de :min caractères",
      'titre.max' => "Le titre de la campagne ne peut pas contenir plus de :max caractères",
      'titre.regex' => "Le titre de la campagne ne peut contenir que les caractères :regex",
    ];
    $validator = \Validator::make($request->all(), $rules, $messages);
    if (empty($selectedUsers) && $id <= 0) {
      $validator->getMessageBag()->add('users_empty', __("Aucun utilisateur trouvé, veuillez sélectionner les personnes à évaluer"));
    }
    if ($model == "Feedback 360") {
      if (count($selectedUsers) > 1) {
        $validator->getMessageBag()->add('user', __("Vous ne pouvez pas sélectionner plus que 1 participant pour le feedback 360"));
      }
      foreach ($selectedUsers as $key => $uid) {
        $user = User::find($uid);
        $userTeams = $user->teams;
        if ($userTeams->count() <= 0) {
          $validator->getMessageBag()->add('teams_'.$uid, __("Le collaborateur (trice) (:user_flname) n'est affecté(e) à aucune équipe", ['user_flname' => $user->fullname()]));
        }
        $countCollaboratorInTeams = 0;
        foreach ($userTeams as $team) {
          $countCollaboratorInTeams += $team->users->count();
        }
        if ($countCollaboratorInTeams < 2) {
          $validator->getMessageBag()->add('team_coll'.$uid, __("Le collaborateur (trice) (:user_flname) n'a pas des collègues dans ses équipes", [':user_flname' => $user->fullname()]));
        }
      }
    }

    $messages = $validator->errors();

    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }
    $date = date('Y-m-d', strtotime($request->date));
    $date_limit = date('Y-m-d', strtotime($request->date_limit));

    $entretien->date = $date;
    $entretien->date_limit = $date_limit;
    $entretien->titre = $request->titre;
    $entretien->model_id = $request->model;
    $entretien->options = json_encode($request->options);
    $entretien->user_id = User::getOwner()->id;
    $entretien->freq_reminder = $request->freq_reminder;
    $entretien->enabled = true;

    // update status
    if (date('Y-m-d', strtotime('now')) < $date_limit) {
      $entretien->status = Entretien::CURRENT_STATUS;
    }

    $entretien->save();

    // save cron job


    // attach evaluations ID
    if (!empty($request->items)) {
      $clearedArrayKeys = [];
      foreach ($request->items as $evaluationId => $value) {
        if (isset($value['object_id']) && isset($value['object_id'][0]) && empty($value['object_id'][0])) continue;
        $clearedArrayKeys[] = $evaluationId;
      }
      $entretien->evaluations()->sync($clearedArrayKeys);
      foreach ($request->items as $evaluationId => $value) {
        if (isset($value['object_id']) && isset($value['object_id'][0]) && empty($value['object_id'][0])) continue;
        $objectsId = isset($value['object_id']) && !empty($value['object_id']) ? $value['object_id'] : [];
        Entretien_evaluation::where('entretien_id', $entretien->id)->where('evaluation_id', $evaluationId)->update(['survey_id'=> json_encode($objectsId)]);
      }
    }

    $mentorEmail = Email::getAll()->where('ref', 'mentor_eval')->first();
    $collEmail = Email::getAll()->where('ref', 'auto_eval')->first();

    $already_sent = [];

    $fb360_user_id = $request->get('fb30_userid_to_evaluate', 0);
    foreach ($selectedUsers as $uid) {
      if ($uid <= 0 || $fb360_user_id <= 0) continue;
      $user = User::findOrFail($uid);

      if ($entretien->isFeedback360()) {
        $euExist = Entretien_user::where('entretien_id', $entretien->id)->where('user_id', $fb360_user_id)->where('mentor_id', $uid)->count() > 0;
        if (!$euExist) {
          $entretien->users()->attach([$fb360_user_id => ['mentor_id' =>$uid]]);
          $campaignData = [
            'entretien_id' => $entretien->id,
            'email_id' => $collEmail->id,
            'receiver' => $user->email,
            'shedule_type' =>  $request->shedule_type,
            'sheduled_at' => $request->shedule_type == 'now' ? date('Y-m-d H:i') : date('Y-m-d H:i', strtotime($request->sheduled_at)),
          ];
          Campaign::create($campaignData);
        }
      } else {
        $campaignData = [
          'entretien_id' => $entretien->id,
          'email_id' => $collEmail->id,
          'receiver' => $user->email,
          'shedule_type' =>  $request->shedule_type,
          'sheduled_at' => $request->shedule_type == 'now' ? date('Y-m-d H:i') : date('Y-m-d H:i', strtotime($request->sheduled_at)),
        ];
        Campaign::create($campaignData);
        $entretien->users()->attach([$uid => ['mentor_id' => $user->parent->id]]);
        //MailerController::send($user, $entretien, $collEmail);
        if (!in_array($user->parent->id, $already_sent)) {
          $campaignData['email_id'] = $mentorEmail->id;
          $campaignData['receiver'] = $user->parent->email;
          Campaign::create($campaignData);
          //MailerController::send($user->parent, $entretien, $mentorEmail);
          $already_sent[] = $user->parent->id;
        }
      }
    }

    // handle removed colls in edit action
    $deleteEvalEmail = Email::getAll()->where('ref', 'delete_eval')->first();

    if($deleteEvalEmail) {
      foreach ($removedUsers as $uid) {
        $user = User::findOrFail($uid);
        if ($entretien->isFeedback360()) {
          Entretien_user::where('entretien_id', $entretien->id)->where('user_id', $fb360_user_id)->where('mentor_id', $uid)->delete();
        } else {
          $entretien->users()->detach($user);
        }
        $campaignData = [
          'entretien_id' => $entretien->id,
          'email_id' => $deleteEvalEmail->id,
          'receiver' => $user->email,
          'shedule_type' => 'now',
          'sheduled_at' => date('Y-m-d H:i'),
        ];
        Campaign::create($campaignData);
      }
    }

    return ["status" => "success", "message" => __("Les informations ont été sauvegardées avec succès")];
  }

  public function storeCheckedUsers(Request $request)
  {
    $entretien = Entretien::findOrFail($request->entretien_id);
    $selectedUsers = json_decode($request->ids);
    $date = Carbon::createFromFormat('Y-m-d', $entretien->date);
    $date_limit = Carbon::createFromFormat('Y-m-d', $entretien->date_limit);

    // if (count($messages) > 0) {
    //   return ["status" => "danger", "message" => $messages];
    // }

    $mentorEmail = Email::getAll()->where('ref', 'mentor_eval')->first();
    $collEmail = Email::getAll()->where('ref', 'auto_eval')->first();
    
    $already_sent = [];
    foreach ($selectedUsers as $uid) {
      $user = User::findOrFail($uid);
      $entretien->users()->attach([$uid => ['mentor_id' => $user->parent->id]]);
      MailerController::send($user, $entretien, $collEmail);
      if (!in_array($user->parent->id, $already_sent)) {
        MailerController::send($user->parent, $entretien, $mentorEmail);
        $already_sent[] = $user->parent->id;
      }
    }
    $url = url('entretiens/evaluations');
    $request->session()->flash('success', "Les utilisateurs ont bien été ajoutés à l'entretien et un email a été envoyé à leurs mentors. <a href='{$url}'>cliquer ici pour les consulter</a>");
    return ["status" => "success", "message" => __("Les informations ont été sauvegardées avec succès")];

  }

  public function notifyUserInterview($eid, $uid)
  {
    $email = Email::getAll()->where('ref', 'auto_eval')->first();
    $user = User::findOrFail($uid);
    $entretien = Entretien::findOrFail($eid);
    MailerController::send($user, $entretien, $email);
    return redirect()->back()->with('success', __("Un email est envoyé avec succès à :user_flname", ['user_flname' => $user->fullname()]));
  }

  public function notifyMentorInterview($eid, $uid)
  {
    $email = Email::getAll()->where('ref', 'mentor_eval')->first();
    $user = User::findOrFail($uid);
    $mentor = $user->parent;
    $entretien = Entretien::findOrFail($eid);
    MailerController::send($mentor, $entretien, $email);
    return redirect()->back()->with('relanceMentor', "Un email de relance est envoyé avec succès à :manager_flname pour évaluer :user_flname", ['manager_flname' => $mentor->fullname(), 'user_flname' => $user->fullname()]);
  }

  public function RemoveDuplicate($array, $key)
  {
    $temp_array = array();
    $i = 0;
    $key_array = array();
    foreach ($array as $val) {
      if(isset($val[$key])) {
        if (!in_array($val[$key], $key_array)) {
          $key_array[$i] = $val[$key];
          $temp_array[$i] = $val;
        }
        $i++;
      }
    }
    return $temp_array;
  }

  public function notifyMentorsInterview(Request $request)
  {
    $mentors = [];
    $email = Email::getAll()->where('ref', 'mentor_eval')->first();
    if(!$request->data) return  redirect()->back();
    $mentors = $this->RemoveDuplicate($request->data, 'mentorId');
    if(count($mentors) > 0){
      foreach ($mentors as $value) {
        if (count($value) > 1) {
          $entretien = Entretien::findOrFail($value['entretienId']);
          $mentor = User::findOrFail($value['mentorId']);
          MailerController::send($mentor, $entretien, $email);
        }
      }
      return redirect()->back()->with('relanceMentor', __('Un email de relance a été envoyé avec succès aux mentors'));
    }
  }

  public function updateMotif(Request $request, $eid, $uid)
  {
    $user = User::findOrFail($uid);
    $user->entretiens()->updateExistingPivot($eid, ['motif' => $request->motif]);
    Session::flash('success_motif_save', __("Le motif d'abscence a bien été sauvegardé"));
    return redirect('entretiens/evaluations');
  }


  public function calendar()
  {
    $entretiens = Entretien::getAll()->get();
    return view("entretiens.calendar", compact('entretiens'));
  }


  /**
   * Remove the specified resource from storage.
   */
  public function destroy($eid)
  {
    $user = Auth::user();
    if($user->hasRole('ADMIN') OR $user->hasRole('RH')) {
      $entretien = Entretien::findOrFail($eid);
      if ($entretien->user_id != User::getOwner()->id) {
        abort(403);
      }
      $entretien->users()->detach();
      $entretien->evaluations()->detach();
      \DB::table('skill_user')->where('entretien_id', $eid)->delete();
      \DB::table('answers')->where('entretien_id', $eid)->delete();
      \DB::table('objectif_user')->where('entretien_id', $eid)->delete();
      \DB::table('campaigns')->where('entretien_id', $eid)->delete();
      $entretien->formations()->delete();
      $entretien->salaries()->delete();
      $entretien->comments()->delete();
      $entretien->delete();
      \Session::flash('success', __("Campagne a été supprimée avec succès !"));
      return [
        "status" => "success",
        "message" => __("Campagne a été supprimée avec succès !"),
        "redirectUrl" => route('entretiens', [])
      ];
    } else {
      return ["status" => "danger", "message" => __("Stop ! Vous n'avez pas la permission !")];
    }
  }

  public function submission(Request $request)
  {
    $entretien = Entretien::findOrFail($request->eid);
    $alertmsg = "";
    if (Auth::user()->id == $request->user) { // this a collaborator
      \DB::table('entretien_user')
        ->where('entretien_id', $request->eid)->where('user_id', $request->user)
        ->update([
          'user_submitted' => 2,
          'user_updated_at' => date('Y-m-d H:i:s'),
        ]);
    } else { // this is a mentor
      $query = \DB::table('entretien_user')->where('entretien_id', $request->eid)->where('user_id', $request->user);
      if ($entretien->isFeedback360()) {
        $query->where('mentor_id', Auth::user()->id);
      }
      $query->update([
        'mentor_submitted' => 2,
        'mentor_updated_at' => date('Y-m-d H:i:s'),
      ]);
      $rh_validate = Email::getAll()->where('ref', 'rh_val')->first();
      $rhs = User::getUsers()->with('roles')->whereHas('roles', function ($query) {
        $query->where('name', '=', 'RH');
      })->get();
      if($rhs->count() > 0) {
        foreach ($rhs as $rh) {
          MailerController::send($rh, $entretien, $rh_validate);
          $alertmsg = __(", Un email a bien été envoyé aux responsables RH");
        }
      }
    }
    $submit_email = Email::getAll()->where('ref', 'submit_eval')->first();
    MailerController::send(Auth::user(), $entretien, $submit_email);

    \Session::flash('success', __("Les informations ont bien été soumises") . $alertmsg);

    return [
      'status' => "success",
      'redirectUrl' => route('home')
    ];
  }

  public function downloadNotation(Entretien $id)
  {
    $entretiens = Entretien::all();
    Excel::create('Export Data',function($excel) use ($entretiens){
      $excel->sheet('Sheet 1',function($sheet) use ($entretiens){
        $sheet->loadView('xls.entretiens', [
          'entretiens' => $entretiens
        ]);
      });
    })->export('xlsx');
  }


  public function changeStatus(Request $request) {
    $ids = $request->ids;
    $isChecked = $request->isChecked;
    if (empty($ids)) {
      return [
        'status' => "danger",
        'message' => __("Impossible de trouver l'ID de la campagne"),
      ];
    }
    foreach ($ids as $id) {
      $entretien = Entretien::find($id);
      $entretien->enabled = $isChecked === 'true';
      $entretien->save();
    }
    return [
      'status' => "success",
      'message' => __("Le changement du statut a bien été effectué"),
    ];
  }

  public function copier($id) {
    $e = Entretien::findOrFail($id);
    if ($e->user_id != User::getOwner()->id) {
      abort(403);
    }
    try {
      // clone model
      $new_entretien = $e->replicate();
      $new_entretien->titre = 'Copie - ' . $e->titre;
      $new_entretien->push();

      // clone participants
      foreach ($e->users as $user) {
        $new_entretien->users()->attach([$user->id => ['mentor_id' => $user->parent->id]]);
      }

      // clone sections
      $e_evaluations = Entretien_evaluation::where('entretien_id', $id)->get();
      foreach ($e_evaluations as $e_evaluation) {
        $new_e_evaluation = new Entretien_evaluation();
        $new_e_evaluation->entretien_id = $new_entretien->id;
        $new_e_evaluation->evaluation_id = $e_evaluation->evaluation_id;
        $new_e_evaluation->survey_id = $e_evaluation->survey_id;
        $new_e_evaluation->push();
      }
    } catch (\Exception $e) {
      return back()->with("danger", $e->getMessage());
    }

    return back()->with("success", __("La campagne a bien été copiée"));
  }


}
