<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Skill extends Model
{
    protected $fillable = [
      'function_id', 'title', 'description', 'savoir', 'savoir_faire', 'savoir_etre', 'mobilite_pro', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getAll()
    {
        $user = \Auth::user();
        if(!empty($user->society_id)){ // this user is not owner
            $skills = $user->owner->skills();
        } else {
            $skills = $user->skills();
        }
        return $skills;
    }

    public function fonction()
    {
        return $this->belongsTo('App\Fonction');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public static function getSkill($sid, $uid, $eid){
        $skill = Skill_user::where('skill_id', $sid)->where('user_id', $uid)->where('entretien_id', $eid)->first();
        if($skill){
            return $skill;
        }else{
            return null;
        }
    }

    public static function filledSkills($eid, $uid, $mentor_id){
        $auth = Auth::user();
        if($auth->id == $uid){
            $skill = Skill_user::where('user_id', $uid)->where('mentor_id', $mentor_id)->where('entretien_id', $eid)->where('auto', '!=' ,0)->first();
        }else{
            $skill = Skill_user::where('user_id', $uid)->where('mentor_id', $mentor_id)->where('entretien_id', $eid)->where('objectif', '<>', 0)->first();
        }
        if($skill){
            return $skill;            
        }else{
            return null;
        }
    }

}
