<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entretien_user extends Model
{
  public $table = "entretien_user";
  public $timestamps = false;

  public static function userHasSubmitedEval($eid, $uid)
  {
    return Entretien_user::where('entretien_id', $eid)
      ->where('user_id', $uid)
      ->where('user_submitted', 2)
      ->first();
  }

  public static function mentorHasSubmitedEval($eid, $uid, $mentor_id)
  {
    return Entretien_user::where('entretien_id', $eid)
      ->where('user_id', $uid)
      ->where('mentor_id', $mentor_id)
      ->where('mentor_submitted', 2)
      ->first();
  }

  public static function countResponse($entretienId, $profile, $status, $object_id = null, $modelName = null)
  {
    $query = Entretien_user::where('entretien_id', $entretienId)
      ->where($profile . '_submitted', $status);
    if ($modelName == 'Department') {
      $query->join('users as u', 'u.id', '=', 'entretien_user.user_id');
      $query->where('u.service', $object_id);
    }
    if ($modelName == 'Fonction') {
      $query->join('users as u', 'u.id', '=', 'entretien_user.user_id');
      $query->where('u.function', $object_id);
    }
    $results = $query->get();

    return count($results);
  }

  public static function getStatus($userId, $mentorId, $entretienId, $profile)
  {
    $results = Entretien_user::where('user_id', $userId)
      ->where('mentor_id', $mentorId)
      ->where('entretien_id', $entretienId)->first();
    $property = $profile . '_submitted';
    if (!$results) {
      $status = 0;
    } else {
      $status = $results->$property;
    }

    if ($status == 0) {
      $statusInfo['name'] = "Non commencé";
      $statusInfo['labelClass'] = 'bg-gray';
    } else if ($status == 1) {
      $statusInfo['name'] = "En cours";
      $statusInfo['labelClass'] = 'bg-yellow';
    } else {
      $statusInfo['name'] = "Fini";
      $statusInfo['labelClass'] = 'bg-green';
    }

    return $statusInfo;
  }

  public static function isFinished($entretien) {
    $total = Entretien_user::where('entretien_id', $entretien->id)->count();

    $submittedByCollAndManager = Entretien_user::where('entretien_id', $entretien->id);
    $submittedByCollAndManager->where('mentor_submitted', 2);
    if (!$entretien->isFeedback360()) {
      $submittedByCollAndManager->where('user_submitted', 2);
    }
    $countSubmit = $submittedByCollAndManager->count();

    return $countSubmit == $total;
  }

}
