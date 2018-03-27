<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public function entretien()
    {
        return $this->belongsTo('App\Entretien');
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
        $auth = User::find($uid);
        if($auth->id == $uid){
            $skill = Skill_user::where('user_id', $uid)->where('mentor_id', $mentor_id)->where('entretien_id', $eid)->where('auto', '!=' ,0)->first();
        }else{
            $skill = Skill_user::where('user_id', $uid)->where('mentor_id', $mentor_id)->where('entretien_id', $eid)->first();
        }
        if($skill){
            return $skill;            
        }else{
            return null;
        }
    }

}
