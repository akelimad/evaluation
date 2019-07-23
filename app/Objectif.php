<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Objectif extends Model
{
    public function entretien()
    {
        return $this->belongsTo('App\Entretien');
    }

    public function parent()
    {
        return $this->belongsTo('App\Objectif', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Objectif', 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public static function getObjectif($eid, $uid, $oid){
        $auth = User::findOrFail($uid);
        if($auth->id == $uid){
            $objectif = Objectif_user::where('user_id', $uid)->where('entretien_id', $eid)->where('objectif_id', $oid)->first();
        }else{
            $objectif = Objectif_user::where('user_id', $uid)->where('entretien_id', $eid)->where('objectif_id', $oid)->where('mentor_id', Auth::user()->id)->first();
        }
        return $objectif;
    }

    public static function getExtraFieldData($eid, $uid, $oid, $fieldId, $isColl = true)
    {
        $data = self::getObjectif($eid, $uid, $oid);
        if (!empty($data)) {
            $data = $isColl ? $data->user_extra_fields_data : $data->mentor_extra_fields_data;
            $data = json_decode($data);
            $data = isset($data->$fieldId) ? $data->$fieldId : '';
        }

        return $data;
    }

    public static function getNmoins1Note($oid, $eid){
        $objectif = Objectif_user::where('user_id', Auth::user()->id)->where('objectif_id', $oid)->where('entretien_id', '<', $eid)->get()->last();
        //dd($objectif);
        if($objectif){
            return $objectif;
        }else{
            return null;
        }

    }

    public static function getRealise($oid, $eid){
        $objectif = Objectif_user::where('user_id', Auth::user()->id)->where('objectif_id', $oid)->where('entretien_id', $eid)->first();
        if($objectif){
            return $objectif;
        }

    }

    public static function userSentGoals($eid, $uid){
        $objectif = Objectif_user::where('user_id', $uid)->where('entretien_id', $eid)->where('userNote', '<>', 0)->first();
        if($objectif){
            return $objectif;
        }else{
            return null;
        }
    }
    public static function mentorSentGoals($eid, $uid, $mentor_id){
        $objectif = Objectif_user::where('user_id', $uid)->where('entretien_id', $eid)->where('mentor_id', $mentor_id)->where('mentorNote', '<>', 0)->first();
        if($objectif){
            return $objectif;
        }else{
            return null;
        }
    }

    public static function  cutNum($num, $precision = 2){
        return floor($num).substr($num-floor($num),1,$precision+1);
    }


}
