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
            ->where('user_submitted', 2)
            ->first();
        if($eu) {
            return $eu;
        } else {
            return false;
        }
    }
    public static function answeredMentor($eid, $uid, $mid)
    {
        $eu = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)->where('mentor_id', $mid)
            ->where('mentor_submitted', 2)
            ->first();
        if($eu) {
            return $eu;
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

    public function salaries()
    {
        return $this->hasMany('App\Salary');
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

    public static function existInterview($eid, $user_id, $start, $end, $operator = "<>"){
        if(\Request::is('entretiens/storeCheckedUsers')){
            $operator = "=";
        }
        $existInterview = \DB::table('entretiens as e')
            ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
            ->select('e.*', 'e.id as entretienId', 'eu.*')
            ->where('eu.user_id', $user_id)
            ->where('e.id', $operator, $eid)
            ->where(function ($query) use ($start, $end) {
            $query->where(function ($q) use ($start, $end) {
                $q->where('e.date', '>', $start)
                   ->where('e.date', '<', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('e.date', '<', $start)
                   ->where('e.date_limit', '>', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('e.date_limit', '>', $start)
                   ->where('e.date_limit', '<', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('e.date', '>', $start)
                   ->where('e.date_limit', '<', $end);
            });
        })->count();
        return $existInterview;
    }

    public static function note($eid, $uid)
    {
        $eu = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)->first();
        return $eu ? $eu->note : 0;
    }

    public static function countUnanswered()
    {
        $society = User::getOwner();
        $countUnanswred = \DB::table('entretiens as e')
            ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
            ->select('e.id')
            ->where('e.user_id', $society->id)
            ->where('eu.user_submitted', 1)
            ->where('eu.mentor_submitted', 0)
            ->get();
        return count($countUnanswred);
    }

    public static function findEvaluations($entretien)
    {
        $evaluations = \DB::select("SELECT * FROM evaluations eval join entretien_evaluation ev on eval.id = ev.evaluation_id and ev.entretien_id = ". $entretien->id . " order by eval.sort_order");

        return $evaluations;
    }

    public static function notationBySectionOrItem($entretien_id)
    {
        $entretienEvalSurveyId = Evaluation::surveyId($entretien_id, 1);
        $survey = Survey::find($entretienEvalSurveyId);
        if (!$survey) return false;
        $c = 0;
        foreach ($survey->groupes as $group) {
            if (!empty($group->notation_type)) {
                $c += 1;
            }
        }

        return $c >= 1;
    }


}
