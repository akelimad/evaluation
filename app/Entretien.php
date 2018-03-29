<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Entretien extends Model
{

    public static function answered($eid, $uid)
    {
        $answer = Answer::where('entretien_id', $eid)->where('user_id', $uid)->where('mentor_id', NULL)->first();
        if($answer) {
            return true;
        }else{
            return false;
        }

    }
    public static function answeredMentor($eid, $uid, $mentor_id)
    {
        $answer = Answer::where('entretien_id', $eid)->where('user_id', $uid)->where('mentor_id', $mentor_id)->first();
        if($answer) {
            return true;
        }else{
            return false;
        }

    }
    
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function evaluations()
    {
        return $this->belongsToMany('App\Evaluation');
    }

    public function skills()
    {
        return $this->hasMany('App\Skill');
    }

    public function objectifs()
    {
        return $this->hasMany('App\Objectif');
    }

    public function formations()
    {
        return $this->hasMany('App\Formation');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function remunerations()
    {
        return $this->hasMany('App\Remuneration');
    }

    public function decisions()
    {
        return $this->hasMany('App\Decision');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
