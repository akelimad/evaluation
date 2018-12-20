<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 300); //5 minutes

use App\Fonction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Entretien;
use App\Entretien_user;
use App\Question;
use App\Survey;
use App\Evaluation;
use Carbon\Carbon;
use Auth;
use Mail;
use Session;
use App\EntretienObjectif;
use App\Formation;
use App\Skill;
use App\Objectif;
use App\Carreer;
use App\Salary;
use App\Comment;
use DB;
use App\Action;
use App\Email;

class EntretienController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function rand_string($length)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars), 0, $length);
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
    $surveys = Survey::getAll()->get();
    // $objectifs = EntretienObjectif::select('id', 'title')->get();
    $entretiens = Entretien::getAll()->paginate(15);
    return view('entretiens.index', compact('entretiens', 'evaluations', 'surveys'));
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
      ->select('e.*', 'e.id as entretienId', 'u.*', 'u.id as userId')
      ->where('e.user_id', User::getOwner()->id);
    $params = [];
    if (!empty($dlimite)) {
      $dlimite = Carbon::createFromFormat('d-m-Y', $dlimite)->toDateString();
      $query->where('e.date_limit', '=', $dlimite);
      $params[] = $dlimite;
    }
    if (!empty($title)) {
      $query->where('e.id', '=', $title);
      $params[] = $title;
    }
    if (!empty($uname)) {
      $query->where('u.name', 'like', '%' . $uname . '%');
      $params[] = $uname;
    }
    if (!empty($ufunction)) {
      $query->where('u.function', '=', $ufunction);
      $params[] = $ufunction;
    }
    $results = $query->paginate(10);

    return view('entretiens.eval-in-progress', compact(
      'fonctions', 'entretiens', 'results', 'dlimite', 'title', 'uname', 'ufunction', 'params'
    ));
  }

  /**
   * Display a listing of the resource.
   */
  public function show($e_id, $uid)
  {
    $entretien = Entretien::find($e_id);
    $evaluations = $entretien->evaluations;
    // $evaluation = Evaluation::where('title', $type)->first();
    $user = $entretien->users()->where('entretien_user.user_id', $uid)->first();
    return view('entretiens.show', [
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
      $entretien = Entretien::find($id);
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
    $date = Carbon::createFromFormat('d-m-Y', $request->date);
    $date_limit = Carbon::createFromFormat('d-m-Y', $request->date_limit);
    if (isset($id) && is_numeric($id)) {
      $entretien = Entretien::find($id);
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
    $validator = \Validator::make($request->all(), $rules);
    $messages = $validator->errors();
    $survey = Survey::getAll()->where('title', 'like', '%standard%')->first();
    $objectif = EntretienObjectif::where('title', 'standard')->first();
    if ($survey == null || $objectif == null) {
      $messages->add('null_survey_obj', "Aucun questionnaire et/ou objectif standard n'a été trouvé ! il faut les créer tout d'abord.");
    }
    $hasAlreadyInt = [];
    foreach ($selectedUsers as $uid) {
      if (Entretien::existInterview($entretien->id, $uid, $date, $date_limit)) {
        $hasAlreadyInt[] = User::find($uid)->name;
      }
    }
    if (count($hasAlreadyInt) > 0) {
      $messages->add('existInterview', "Il ya déjà un entretien programmé dans la période choisie pour les collaborateurs sélectionnés (" . implode(', ', $hasAlreadyInt) . ") !!");
    }

    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }

    $evaluations = Evaluation::pluck('id')->toArray(); //to get ids of all object in one array
    $entretien->date = $date;
    $entretien->date_limit = $date_limit;
    $entretien->titre = $request->titre;
    $entretien->user_id = User::getOwner()->id;
    $entretien->survey_id = $survey ? $survey->id : 0;
    $entretien->objectif_id = $objectif ? $objectif->id : 0;
    $entretien->save();
    if (empty($id)) $entretien->evaluations()->attach($evaluations);

    $mentors_action = Action::where('slug', 'notify_mentors')->first();
    $colls_action = Action::where('slug', 'notify_collaborator')->first();
    $mentors_email = $mentors_action->emails()->first();
    $colls_email = $colls_action->emails()->first();

    $already_sent = [];

    foreach ($selectedUsers as $uid) {
      $user = User::find($uid);
      $entretien->users()->attach([$uid => ['mentor_id' => $user->parent->id]]);
      $this->mailSend($user, $entretien, $colls_email);
      if (!in_array($user->parent->id, $already_sent)) {
        //$this->mailSend($user->parent, $entretien, $mentors_email);
        $already_sent[] = $user->parent->id;
      }
    }

    // handle removed colls in edit action
    $remove_coll_action = Action::where('slug', 'remove_collaborator')->first();
    if($remove_coll_action) {
      $remove_coll_email = $remove_coll_action->emails()->first();
      foreach ($removedUsers as $uid) {
        $user = User::find($uid);
        $entretien->users()->detach($user);
        //$this->mailSend($user, $entretien, $remove_coll_email);
      }
    }


    return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
  }

  public function mailSend($user, $entretien, $msg_tpl)
  {
    $password = $this->rand_string(10);
    $user->password = bcrypt($password);
    $user->save();
    $body = Email::renderMessage($msg_tpl->message, [
      'user_fname' => $user->name ? $user->name : 'coll_fname',
      'mentor_fullname' => $user->parent ? $user->parent->name . ' ' . $user->parent->last_name : 'm_fullname',
      'title' => isset($entretien->titre) ? $entretien->titre : '---',
      'date_limit' => Carbon::parse($entretien->date_limit)->format('d-m-Y'),
      'email' => $user->email,
      'password' => $password,
    ]);
    Mail::send([], [], function ($m) use ($user, $msg_tpl, $body) {
      $m->from($msg_tpl->sender, $msg_tpl->name);
      $m->to($user->email);
      $m->subject($msg_tpl->subject);
      $m->setBody($body, 'text/html');
    });
  }

  public function storeCheckedUsers(Request $request)
  {
    $entretien = Entretien::find($request->entretien_id);
    $users_id = json_decode($request->ids);
    $mentors = [];
    foreach ($users_id as $user_id) {
      $user = User::find($user_id);
      if ($user->parent == null) {
        $mentors[] = $user;
      } else {
        $mentors[] = $user->parent;
      }
      $exist = $user->entretiens->contains($user_id);
    }
    $entretien->users()->syncWithoutDetaching($users_id);

    $user_mentors = array_unique($mentors);
    $action = Action::where('slug', 'notify_mentors')->first();
    $email = $action->emails()->first();
    foreach ($user_mentors as $mentor) {
      $password = $this->rand_string(10);
      $mentor->password = bcrypt($password);
      $mentor->save();
      $message = Email::renderMessage($email->message, [
        'user_name' => $mentor->name,
        'date_limit' => Carbon::parse($entretien->date_limit)->format('d-m-Y'),
        'email' => $mentor->email,
        'password' => $password,
      ]);
      $send = Mail::send([], [], function ($m) use ($mentor, $email, $message) {
        $m->from($email->sender, $email->name);
        $m->to($mentor->email);
        $m->subject($email->subject);
        $m->setBody($message, 'text/html');
      });
    }
    $url = url('entretiens/evaluations');
    $request->session()->flash('attach_users_entretien', "Les utilisateurs ont bien à été ajouté à l'entretien et un email est envoyé à leur mentor. <a href='{$url}'>cliquer ici pour les consulter</a>");
    return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];

  }

  public function storeEntretienEvals(Request $request)
  {
    $evaluationsIds = [];
    $entretien = Entretien::find($request->entretien_id);
    foreach ($request->choix as $key => $choix) {
      if (isset($choix['evaluation_id'])) {
        $evaluationsIds[] = $choix['evaluation_id'];
      }
    }
    $entretien->evaluations()->sync($evaluationsIds);
    foreach ($request->entretiens as $key => $value) {
      if (empty($value[0])) {  // no servey selected
        Session::flash("warning", "Veuillez choisir un questionnaire d'évaluation !");
      } else {
        $incompleteSurvey = Survey::icompleteSurvey($value[0]);
        if ($incompleteSurvey == true) {
          Session::flash('warning', "le questionnaire est incomplet, vous ne pouvez pas l'affecter à l'entreteien. veuillez attribuer les choix pour les questions multichoix !!");
        } else {
          if (isset($value[0])) $entretien->survey_id = $value[0];
          if (isset($value[1])) $entretien->objectif_id = $value[1];
          $entretien->save();
          Session::flash('success', "Les évaluations de l'entretien ont bien été mises à jour");
        }
      }
    }

    return redirect('entretiens/index');
  }

  public function notifyUserInterview($eid, $uid)
  {
    $action = Action::where('slug', 'notify_collaborator')->first();
    $email = $action->emails()->first();
    $user = User::findOrFail($uid);
    $entretien = Entretien::findOrFail($eid);
    $password = $this->rand_string(10);
    $user->password = bcrypt($password);
    $user->save();
    $message = Email::renderMessage($email->message, [
      'user_name' => $user->name,
      'date_limit' => Carbon::parse($entretien->date_limit)->format('d-m-Y'),
      'email' => $user->email,
      'password' => $password,
    ]);
    $send = Mail::send([], [], function ($m) use ($user, $email, $message) {
      $m->from($email->sender, $email->name);
      $m->to($user->email);
      $m->subject($email->subject);
      $m->setBody($message, 'text/html');
    });
    return redirect()->back()->with('message', 'Un email est envoyé avec succès à ' . $user->name . " " . $user->last_name);
  }

  public function notifyMentorInterview($eid, $uid)
  {
    $action = Action::where('slug', 'notify_mentor')->first();
    $email = $action->emails()->first();
    $user = User::findOrFail($uid);
    $mentor = $user->parent;
    $entretien = Entretien::findOrFail($eid);
    $password = $this->rand_string(10);
    $mentor->password = bcrypt($password);
    $mentor->save();
    $message = Email::renderMessage($email->message, [
      'user_name' => $mentor->name,
      'date_limit' => Carbon::parse($entretien->date_limit)->format('d-m-Y'),
      'email' => $mentor->email,
      'password' => $password,
    ]);
    $send = Mail::send([], [], function ($m) use ($mentor, $email, $message) {
      $m->from($email->sender, $email->name);
      $m->to($mentor->email);
      $m->subject($email->subject);
      $m->setBody($message, 'text/html');
    });
    return redirect()->back()->with('relanceMentor', 'Un email de relance est envoyé avec succès à ' . $mentor->name . " " . $mentor->last_name . " pour évaluer " . $user->name . " " . $user->last_name);
  }

  public function RemoveDuplicate($array, $key)
  {
    $temp_array = array();
    $i = 0;
    $key_array = array();
    foreach ($array as $val) {
      if (!in_array($val[$key], $key_array)) {
        $key_array[$i] = $val[$key];
        $temp_array[$i] = $val;
      }
      $i++;
    }
    return $temp_array;
  }

  public function notifyMentorsInterview(Request $request)
  {
    $action = Action::where('slug', 'notify_mentors')->first();
    $email = $action->emails()->first();
    $array = $this->RemoveDuplicate($request->data, 'mentorId');
    foreach ($array as $value) {
      if (count($value) > 1) {
        $entretien = Entretien::find($value['entretienId']);
        $mentor = User::find($value['mentorId']);
        $password = $this->rand_string(10);
        $mentor->password = bcrypt($password);
        $mentor->save();
        $message = Email::renderMessage($email->message, [
          'user_name' => $mentor->name,
          'date_limit' => Carbon::parse($entretien->date_limit)->format('d-m-Y'),
          'email' => $mentor->email,
          'password' => $password,
        ]);
        $send = Mail::send([], [], function ($m) use ($mentor, $email, $message) {
          $m->from($email->sender, $email->name);
          $m->to($mentor->email);
          $m->subject($email->subject);
          $m->setBody($message, 'text/html');
        });
      }
    }
    return redirect()->back()->with('relanceMentor', 'Un email de relance a été envoyé avec succès aux mentors. ');
  }

  public function updateMotif(Request $request, $eid, $uid)
  {
    $user = User::find($uid);
    $user->entretiens()->updateExistingPivot($eid, ['motif' => $request->motif]);
    Session::flash('success_motif_save', "Le motif d'abscence a bien été sauvegardé.");
    return redirect('entretiens/evaluations');
  }

  public function apercu($eid, $uid)
  {
    ob_start();
    $e = Entretien::find($eid);
    $user = User::findOrFail($uid);
    $evaluations = $e->evaluations;
    $evalTitle = [];
    $survey = Survey::find($e->survey_id);
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
    echo view('entretiens.apercu', compact('entreEvalsTitle', 'e', 'user', 'groupes', 'salaries', 'carreers', 'formations', 'skills', 'objectifs', 'comment', 'total'));
    $content = ob_get_clean();
    return ['title' => "Aperçu de l'entretien", 'content' => $content];
  }

  public function calendar()
  {
    $entretiens = Entretien::all();
    return view("entretiens.calendar", compact('entretiens'));
  }

  public function printPdf($eid, $uid)
  {
    $e = Entretien::find($eid);
    $user = User::findOrFail($uid);
    $evaluations = $e->evaluations;
    $survey = Survey::find($e->survey_id);
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
    $entretien = Entretien::findOrFail($eid);
    $entretien->delete();
    return redirect('entretiens/index');
  }

  public function submission(Request $request)
  {
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
    }
    $entretien = Entretien::find($request->eid);
    $submit_action = Action::where('slug', 'evaluation_submit')->first();
    if($submit_action) {
      $submit_email = $submit_action->emails()->first();
      //$this->mailSend(Auth::user(), $entretien, $submit_email);
    }
  }
}
