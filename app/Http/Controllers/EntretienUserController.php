<?php

namespace App\Http\Controllers;

use App\Email;
use App\Entretien;
use App\Entretien_user;
use App\Http\Mail\MailerController;
use App\User;
use Auth;
use Session;
use DB;
use Excel;
use Symfony\Component\HttpFoundation\Request;

class EntretienUserController extends Controller
{
  public function reminder(Request $request) {
    $usersId = $request->params['usersId'];
    $eid = $request->params['eid'];
    $role = $request->params['role'];

    if (empty($usersId) || !is_numeric($eid)) {
      return ['status' => 'danger', 'message' => "Aucun utilisateur n'a été sélectionné ou entretien ID introuvable"];
    }

    if ($role == 'coll') {
      $template = Email::getAll()->where('ref', 'auto_eval')->first();
    } else {
      $template = Email::getAll()->where('ref', 'mentor_eval')->first();
    }

    $entretien = Entretien::find($eid);
    $i = 0;
    foreach ($usersId as $uid) {
      $canReceive = true;
      $user = User::findOrFail($uid);
      if ($role == 'mentor') {
        $user = $user->parent;
        $mentorHasSubmitted = Entretien_user::mentorHasSubmitedEval($eid, $uid, $user->id);
        if ($mentorHasSubmitted) $canReceive = false;
      } else {
        $userHasSubmitted = Entretien_user::userHasSubmitedEval($eid, $uid);
        if ($userHasSubmitted) $canReceive = false;
      }
      if (!$canReceive) continue;
      MailerController::send($user, $entretien, $template);
      $i++;
    }

    return ['status' => 'success', 'message' => "Un email de relance a bien été envoyé à(aux) <b>$i</b> utilisateur(s) sélectionné(s)"];
  }

  public function delete(Request $request) {
    $eid = $request->params['eid'];
    $usersId = $request->params['usersId'];
    \DB::table('entretien_user')->where('entretien_id', $eid)->whereIn('user_id', $usersId)->delete();

    return ['status' => 'success', 'message' => "La suppression a bien été effectutée"];
  }
}