<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon; 

class Entretien extends Model
{

    public static function answered($eid, $uid)
    {
        $eu = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)
            ->where('user_submitted', 1)
            ->first();
        if($eu) {
            return true;
        } else {
            return false;
        }
    }
    public static function answeredMentor($eid, $uid, $mid)
    {
        $eu = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)->where('mentor_id', $mid)
            ->where('mentor_submitted', 1)
            ->first();
        if($eu) {
            return true;
        } else {
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

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public static function getAll()
    {
      $user = \Auth::user();
      if(!empty($user->society_id)){ // this user is not owner
        $entretiens = $user->owner->getEntretiens();
      } else {
        $entretiens = $user->getEntretiens();
      }
      return $entretiens;
    }

    public static function existInterview($start, $end){
        $existInterview = Entretien::where(function ($query) use ($start, $end) {
            $query->where(function ($q) use ($start, $end) {
                $q->where('date', '>', $start)
                   ->where('date', '<', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('date', '<', $start)
                   ->where('date_limit', '>', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('date_limit', '>', $start)
                   ->where('date_limit', '<', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('date', '>', $start)
                   ->where('date_limit', '<', $end);
            });
        })->count();
        return $existInterview;
    }

}
