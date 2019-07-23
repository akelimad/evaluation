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
}
