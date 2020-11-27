<?php

namespace App;

use App\Helpers\Base;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Skill extends Model
{
    protected $fillable = [
      'function_id', 'hierarchy_function_id', 'functional_function_id', 'formationlevel_id', 'experiencelevel_id', 'functionnel_relation', 'title', 'description', 'skills_json', 'mobilite_pro', 'user_id'
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

        return $skill ? $skill : null;
    }

    public static function filledSkills($eid, $uid, $mentor_id){
        $auth = Auth::user();
        if($auth->id == $uid){
            $skill = Skill_user::where('user_id', $uid)->where('mentor_id', $mentor_id)->where('entretien_id', $eid)->where('auto', '!=' ,0)->first();
        }else{
            $skill = Skill_user::where('user_id', $uid)->where('mentor_id', $mentor_id)->where('entretien_id', $eid)->where('objectif', '<>', 0)->first();
        }
        return $skill ? $skill : null;
    }

    public function getDataAsArray($type) {
        $skill = json_decode($this->skills_json, true) ?: [];

        if (!isset($skill[$type]['skills']) || empty($skill[$type]['skills'])) return [];
        $labels = [];
        foreach ($skill[$type]['skills'] as $item) {
            $labels[] = $item['title'];
        }
        return $labels;
    }

    public function getDataAsStr($field) {
        $array = implode(',', $this->getDataAsArray($field));

        return $array;
    }

    public static function getNote($entretien_id, $user_id, $mentor_id, $field, $skill_item_id, $profile) {
        $skill_user = Skill_user::where('entretien_id', $entretien_id)->where('user_id', $user_id)->where('mentor_id', $mentor_id)->first();
        $profile = $profile.'_notes';
        $notes = $skill_user ? $skill_user->$profile : "";
        $notes = json_decode($notes, true) ?: [];

        return isset($notes[$field][$skill_item_id]) ? $notes[$field][$skill_item_id] : 0;
    }

    public static function getFieldNotes($entretien_id, $user_id, $mentor_id, $field, $profile) {
        $skill_user = Skill_user::where('entretien_id', $entretien_id)->where('user_id', $user_id)->where('mentor_id', $mentor_id)->first();
        $profile = $profile.'_notes';
        $notes = $skill_user ? $skill_user->$profile : "";
        $notes = json_decode($notes, true) ?: [];

        return isset($notes[$field]) && !empty($notes[$field]) ? $notes[$field] : [];
    }

    public function getSkillsTypes() {
        return json_decode($this->skills_json, true) ?: [];
    }

    public function getFunctionnelRelations() {
        $data = json_decode($this->functionnel_relation, true) ?: [];
        $data = count($data) <= 1 ? $data + ['' => ''] : $data;

        return $data;
    }

    public function getSkillTypeNote($eid, $uid, $mentor_id, $field, $type_id, $profile) {
        $skills_json = json_decode($this->skills_json, true) ?: [];
        $typeData = isset($skills_json[$type_id]) ? $skills_json[$type_id] : [];
        if (empty($typeData) || !isset($typeData['skills']) || empty($typeData['skills'])) return 0;
        $sum = 0;
        foreach ($typeData['skills'] as $key => $item) {
            $ponderation = isset($item['ponderation']) ? $item['ponderation'] : 0;
            $note = self::getNote($eid, $uid, $mentor_id, $field, $key, $profile);
            $sum += $note * ($ponderation / 100);
        }

        return Base::cutNum($sum, 1);
    }

}
