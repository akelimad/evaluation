<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 300); //5 minutes

use App\Fonction;
use Illuminate\Http\Request;
use App\Http\Mail\MailerController;
use Auth;
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
    return ['title' => 'Liste des entretiens', 'content' => $content];
  }

  /**
   * Display a listing of the resource.
   */
  public function indexEntretien()
  {
    $evaluations = Evaluation::all();
    // $objectifs = EntretienObjectif::select('id', 'title')->get();
    $entretiens = Entretien::getAll()->paginate(15);
    return view('entretiens.index', compact('entretiens', 'evaluations', 'surveys'));
  }

  public function show($id)
  {
    ob_start();
    $e = Entretien::findOrFail($id);
    echo view('entretiens.show', compact('e'));
    $content = ob_get_clean();
    return ['title' => "Détails de l'entretien", 'content' => $content];
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
    $evaluations = $entretien->evaluations;
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
    ob_start();
    if (isset($id) && is_numeric($id)) {
      $entretien = Entretien::findOrFail($id);
      $title = "Modifier l'entretien";
    } else {
      $entretien = new Entretien();
      $title = "Ajouter un entretien";
    }
    $e_users = [];
    foreach ($entretien->users as $user) {
      $e_users[] = $user->id;
    }
    $users = User::getUsers()->where('user_id', '<>', 0)->get();
    echo view('entretiens.form', compact('users', 'e_users', 'entretien'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }


  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $id = $request->id;
    $selectedUsers = $request->usersId;
    $entretienUsers = $removedUsers = [];
    $url=url('config/settings/general');
    if(Evaluation::maxNote() == 0) {
      return ["status" => "danger", "message" => "Veuillez définir tout d'abord la note maximale dans <a href='$url' target='_blank'>Paramétres</a> !"];
    }
    
    if (isset($id) && is_numeric($id)) {
      $entretien = Entretien::findOrFail($id);
      $entretienUsers = $entretien->users()->pluck('id')->toArray();
      $removedUsers = array_diff($entretienUsers, $selectedUsers);
      $selectedUsers = array_diff($selectedUsers, $entretienUsers);
    } else {
      $entretien = new Entretien();
    }

    $rules = [
      'date' => 'required',
      'date_limit' => 'required|after:date',
      'titre' => 'required|min:3|max:50|regex:/^[0-9a-zÀ-ú\s\-_"°^\'’.,:]*$/i',
    ];
    $messages = [
      'date.required' => "La date de l'entretien est obligatoire",
      'date_limit.required' => "La date de clôture de l'entretien est obligatoire",
      'date_limit.after' => "La date de clôture doit être une date postérieure à la date de l'entretien",
      'titre.required' => "Le titre est obligatoire",
      'titre.min' => "Le titre ne peut pas contenir moins de :min lettres",
      'titre.max' => "Le titre ne peut pas contenir plus de :max lettres",
      'titre.regex' => "Le titre ne peut contenir que les caractères :regex",
    ];
    $validator = \Validator::make($request->all(), $rules, $messages);
    $messages = $validator->errors();
    $surveyEval = Survey::getAll()
    ->where('evaluation_id', 1)->where('type', 0)->first();
    $surveyCarreer = Survey::getAll()
    ->where('evaluation_id', 2)->where('type', 0)->first();
    if (!$surveyEval || !$surveyCarreer) {
      $url_survey = url('config/surveys');
      $messages->add('null_survey_obj', "Aucun questionnaire standard de l'évaluation et/ou de carrière n'a été trouvé ! il faut le/les créer tout d'abord dans <a href='$url_survey' target='_blank'>Questionnaires</a>");
    }
    $hasAlreadyInt = [];
    if(!empty($request->date) && !empty($request->date_limit)) {
      $date = Carbon::createFromFormat('d-m-Y', $request->date);
      $date_limit = Carbon::createFromFormat('d-m-Y', $request->date_limit);
      foreach ($selectedUsers as $uid) {
        if (Entretien::existInterview($entretien->id, $uid, $date, $date_limit)) {
          $hasAlreadyInt[] = User::findOrFail($uid)->name;
        }
      }
    }
    if (count($hasAlreadyInt) > 0) {
      $messages->add('existInterview', "Il ya déjà un entretien programmé pour les collaborateurs sélectionnés (" . implode(', ', $hasAlreadyInt) . ") !!");
    }

    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }

    $evaluations = Evaluation::where('title', '<>', 'Compétences')->pluck('id')->toArray(); //to get ids of all object in one array
    $entretien->date = $date;
    $entretien->date_limit = $date_limit;
    $entretien->titre = $request->titre;
    $entretien->user_id = User::getOwner()->id;
    $entretien->save();
    if (empty($id)) {
      $entretien->evaluations()->attach($evaluations);
      $surveyId = null;
      foreach ($evaluations as $evalId) {
        if($evalId == 1) $surveyId = $surveyEval->id;
        if($evalId == 2) $surveyId = $surveyCarreer->id;
        Entretien_evaluation::where('entretien_id', $entretien->id)
          ->where('evaluation_id', $evalId)->update(['survey_id'=>$surveyId]);
      }
    }

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

    // handle removed colls in edit action
    $deleteEvalEmail = Email::getAll()->where('ref', 'delete_eval')->first();
    if($deleteEvalEmail) {
      foreach ($removedUsers as $uid) {
        $user = User::findOrFail($uid);
        $entretien->users()->detach($user);
        MailerController::send($user, $entretien, $deleteEvalEmail);
      }
    }

    return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
  }

  public function storeCheckedUsers(Request $request)
  {
    $entretien = Entretien::findOrFail($request->entretien_id);
    $selectedUsers = json_decode($request->ids);
    $date = Carbon::createFromFormat('Y-m-d', $entretien->date);
    $date_limit = Carbon::createFromFormat('Y-m-d', $entretien->date_limit);

    $hasAlreadyInt = [];
    // dd($selectedUsers);
    foreach ($selectedUsers as $uid) {
      if (Entretien::existInterview($entretien->id, $uid, $date, $date_limit)) {
        $hasAlreadyInt[] = User::findOrFail($uid)->name;
      }
    }
    if (count($hasAlreadyInt) > 0) {
      // $messages->add('existInterview', "Il ya déjà un entretien programmé dans la période choisie pour les collaborateurs sélectionnés (" . implode(', ', $hasAlreadyInt) . ") !!");
      return ["status" => "danger", "message" => "Il ya déjà un entretien programmé dans la période choisie pour les collaborateurs sélectionnés (" . implode(', ', $hasAlreadyInt) . ") !!"];
    }

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
    $request->session()->flash('attach_users_entretien', "Les utilisateurs ont bien été ajoutés à l'entretien et un email a été envoyé à leurs mentors. <a href='{$url}'>cliquer ici pour les consulter</a>");
    return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];

  }

  public function storeEntretienEvals(Request $request)
  {
    // dd($request->all());
    $evaluationsIds = [];
    $entretien = Entretien::findOrFail($request->entretien_id);
    $entretienSkills = $entretien->skills->count();
    $skillsChecked = false;
    if(count($request->choix) > 0) {
      foreach ($request->choix as $key => $choix) {
        if (isset($choix['evaluation_id'])) {
          if($choix['evaluation_id'] == 4 && $entretienSkills == 0){
            $skillsChecked = true;
          } else {
            $evaluationsIds[] = $choix['evaluation_id'];
          }
        }
      }
      $entretien->evaluations()->sync($evaluationsIds);
      foreach ($request->choix as $key => $choix) {
        $survey_id = isset($choix['survey_id']) ? $choix['survey_id'] : null;
        $evaluation_id = isset($choix['evaluation_id']) ? $choix['evaluation_id'] : null;
        if (is_numeric($survey_id)) {
          $incompleteSurvey = Survey::icompleteSurvey($survey_id);
          if($incompleteSurvey) {
            Session::flash('warning', "le questionnaire est incomplet, vous ne pouvez pas l'affecter à l'entreteien. veuillez attribuer les choix pour les questions multichoix !!");
          } else {
            if(is_numeric($evaluation_id) && is_numeric($survey_id)) {
              Entretien_evaluation::where('entretien_id', $entretien->id)->where('evaluation_id', $evaluation_id)->update(['survey_id'=>$survey_id]);
              Session::flash('success', "Les informations ont été sauvegardées avec succès.");
            }
            if($skillsChecked && $entretienSkills == 0){
              Session::flash('warning', "Aucune compétence trouvée liée à cet entretien. vous ne pouvez pas activer cette section. veuillez aller dans la configuration et en créer.");
            }
          }
        }
      }
    }

    return redirect('entretiens/index');
  }

  public function notifyUserInterview($eid, $uid)
  {
    $email = Email::getAll()->where('ref', 'auto_eval')->first();
    $user = User::findOrFail($uid);
    $entretien = Entretien::findOrFail($eid);
    MailerController::send($user, $entretien, $email);
    return redirect()->back()->with('message', 'Un email est envoyé avec succès à ' . $user->name . " " . $user->last_name);
  }

  public function notifyMentorInterview($eid, $uid)
  {
    $email = Email::getAll()->where('ref', 'mentor_eval')->first();
    $user = User::findOrFail($uid);
    $mentor = $user->parent;
    $entretien = Entretien::findOrFail($eid);
    MailerController::send($mentor, $entretien, $email);
    return redirect()->back()->with('relanceMentor', 'Un email de relance est envoyé avec succès à ' . $mentor->name . " " . $mentor->last_name . " pour évaluer " . $user->name . " " . $user->last_name);
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
      return redirect()->back()->with('relanceMentor', 'Un email de relance a été envoyé avec succès aux mentors. ');
    }
  }

  public function updateMotif(Request $request, $eid, $uid)
  {
    $user = User::findOrFail($uid);
    $user->entretiens()->updateExistingPivot($eid, ['motif' => $request->motif]);
    Session::flash('success_motif_save', "Le motif d'abscence a bien été sauvegardé.");
    return redirect('entretiens/evaluations');
  }

  public function apercu($eid, $uid)
  {
    ob_start();
    $e = Entretien::findOrFail($eid);
    $user = User::findOrFail($uid);
    $evaluations = $e->evaluations;
    $evalTitle = [];
    $formations = Formation::where('user_id', $user->id)->where('entretien_id', $e->id)->where('status', 2)->get();
    $salaries = Salary::where('mentor_id', $user->parent ? $user->parent->id : $user->id)->where('entretien_id', $e->id)->paginate(10);
    $skills = Skill::where('entretien_id', $eid)->get();
    $comment = Comment::where('entretien_id', $eid)->where('user_id', $uid)->first();
    $entreEvalsTitle = [];
    foreach ($evaluations as $eval) {
      $entreEvalsTitle[] = $eval->title;
    }
    echo view('entretiens.apercu', compact('entreEvalsTitle', 'e', 'user', 'salaries', 'formations', 'skills', 'comment'));
    $content = ob_get_clean();
    return ['title' => "Aperçu de l'entretien", 'content' => $content];
  }

  public function calendar()
  {
    $entretiens = Entretien::getAll()->get();
    return view("entretiens.calendar", compact('entretiens'));
  }

  public function printPdf($eid, $uid)
  {
    $e = Entretien::findOrFail($eid);
    $user = User::findOrFail($uid);
    $evaluations = $e->evaluations;
    $survey = Survey::findOrFail($e->survey_id);
    $groupes = $survey->groupes;
    $carreers = Carreer::where('entretien_id', $eid)->where('user_id', $uid)->get();
    $formations = Formation::where('user_id', $user->id)->where('status', 2)->get();
    $salaries = Salary::where('mentor_id', $user->parent ? $user->parent->id : $user->id)->paginate(10);
    $skills = Skill::all();
    $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $e->objectif_id)->paginate(10);
    $comment = Comment::where('entretien_id', $eid)->where('user_id', $uid)->first();
    $total = 0;
    foreach ($objectifs as $obj) {
      $total += $obj->sousTotal;
    }
    $entreEvalsTitle = [];
    foreach ($evaluations as $eval) {
      $entreEvalsTitle[] = $eval->title;
    }

    $pdf = \PDF::loadView('entretiens.apercu', compact('entreEvalsTitle', 'evaluations', 'e', 'user', 'groupes', 'salaries', 'carreers', 'formations', 'skills', 'objectifs', 'comment', 'total'));
    return $pdf->download('entretien-synthese.pdf');
  }


  /**
   * Remove the specified resource from storage.
   */
  public function destroy($eid)
  {
    $user = Auth::user();
    if($user->hasRole('ADMIN') OR $user->hasRole('RH')) {
      $entretien = Entretien::findOrFail($eid);
      $entretien->delete();
      $entretien->users()->detach();
      $entretien->skills()->delete();
      \DB::table('skill_user')->where('entretien_id', $eid)->delete();
      \DB::table('answers')->where('entretien_id', $eid)->delete();
      $entretien->formations()->delete();
      $entretien->salaries()->delete();
      $entretien->comments()->delete();
    } else {
      return ["status" => "danger", "message" => "Stop ! Vous n'avez pas la permission !"];
    }
  }

  public function submission(Request $request)
  {
    $entretien = Entretien::findOrFail($request->eid);
    if (Auth::user()->id == $request->user) { // this a collaborator
      \DB::table('entretien_user')
        ->where('entretien_id', $request->eid)->where('user_id', $request->user)
        ->update([
          'user_submitted' => 1,
          'user_updated_at' => date('Y-m-d H:i:s'),
        ]);
    } else { // this is a mentor
      \DB::table('entretien_user')
        ->where('entretien_id', $request->eid)->where('user_id', $request->user)
        ->update([
          'mentor_submitted' => 1,
          'mentor_updated_at' => date('Y-m-d H:i:s'),
        ]);
      $rh_validate = Email::getAll()->where('ref', 'rh_val')->first();
      $rhs = User::getUsers()->with('roles')->whereHas('roles', function ($query) {
        $query->where('name', '=', 'RH');
      })->get();
      if($rhs->count() > 0) {
        foreach ($rhs as $rh) {
          MailerController::send($rh, $entretien, $rh_validate);
        }
      }
    }
    $submit_email = Email::getAll()->where('ref', 'submit_eval')->first();
    MailerController::send(Auth::user(), $entretien, $submit_email);
  }
}
