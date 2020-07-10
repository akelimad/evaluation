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
          ->where('user_submitted', 1)
          ->first();
    }

    public static function mentorHasSubmitedEval($eid, $uid, $mentor_id) {
        return Entretien_user::where('entretien_id', $eid)
          ->where('user_id', $uid)
          ->where('mentor_id', $mentor_id)
          ->where('mentor_submitted', 1)
          ->first();
    }

    public static function countResponse($entretienId, $profile, $status) {
        $results = Entretien_user::where('entretien_id', $entretienId)
          ->where($profile.'_submitted', $status)->get();

        return count($results);
    }

    public static function getStatus($userId, $entretienId, $profile) {
        $results = Entretien_user::where('user_id', $userId)
          ->where('entretien_id', $entretienId)->first();
        $property = $profile.'_submitted';
        $status = $results->$property;
        if ($status == 0) {
            $status = "Non commencé";
        } else if ($status == 1) {
            $status = "En cours";
        } else {
            $status = "Fini";
        }

        return $status;
    }
}
