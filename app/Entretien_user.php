<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entretien_user extends Model
{
    public $table = "entretien_user";
    public $timestamps = false;

    public static function userHasSubmitedEval($eid, $uid) {
        return Entretien_user::where('entretien_id', $eid)
          ->where('user_id', $uid)
          ->where('user_submitted', 2)
          ->first();
    }

    public static function mentorHasSubmitedEval($eid, $uid, $mentor_id) {
        return Entretien_user::where('entretien_id', $eid)
          ->where('user_id', $uid)
          ->where('mentor_id', $mentor_id)
          ->where('mentor_submitted', 2)
          ->first();
    }

    public static function countResponse($entretienId, $profile, $status) {
        $results = Entretien_user::where('entretien_id', $entretienId)
          ->where($profile.'_submitted', $status)->get();

        return count($results);
    }

    public static function getStatus($userId, $mentorId, $entretienId, $profile) {
        $results = Entretien_user::where('user_id', $userId)
          ->where('mentor_id', $mentorId)
          ->where('entretien_id', $entretienId)->first();
        $property = $profile.'_submitted';
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
}
