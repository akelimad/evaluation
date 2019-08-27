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

    public static function getObjectif($eid, $user, $mentor = null, $oid){
        if (is_null($mentor)) {
            $objectif = Objectif_user::where('user_id', $user->id)->where('entretien_id', $eid)->where('objectif_id', $oid)->first();
        } else {
            $objectif = Objectif_user::where('user_id', $user->id)->where('entretien_id', $eid)->where('objectif_id', $oid)->where('mentor_id', $mentor->id)->first();
        }
        return $objectif;
    }

    public static function getExtraFieldData($eid, $user, $mentor = null, $oid, $fieldId)
    {
        $data = self::getObjectif($eid, $user, $mentor, $oid);
        if (!empty($data)) {
            $data = is_null($mentor) ? $data->user_extra_fields_data : $data->mentor_extra_fields_data;
            $data = json_decode($data);
            $data = isset($data->$fieldId) ? $data->$fieldId : '';
        }

        return !empty($data) ? $data : '';
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

    public static function getObjectifs($e)
    {
        $evaluations = Entretien::findEvaluations($e);
        $obj_id = 0;
        foreach ($evaluations as $key => $evaluation) {
            if ($evaluation->title != "Objectifs") continue;
            $obj_id = $evaluation->survey_id;
        }
        $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $obj_id)->get();
        return $objectifs;
    }

    public static function getObjSubTotal($e, $user, $mentor, $profil, $subObjId)
    {
        $objSubTotal = $note = $ponderation = 0;
        $profilNote = $profil.'Note';
        $subObj = Objectif::find($subObjId);
        if (is_null($subObj)) return 0;
        if (count($subObj->children) > 0) {
            foreach ($subObj->children as $sub) {
                $note = self::getObjectif($e->id, $user, $mentor, $sub->id)->$profilNote;
                $ponderation = $sub->ponderation;
                $objSubTotal += $note * $ponderation;
            }
            $objSubTotal = $objSubTotal / 100;
            $objSubTotal = $objSubTotal * ($subObj->ponderation / 100);
        } else {
            $note = self::getObjectif($e->id, $user, $mentor, $subObjId)->$profilNote;
            $ponderation = $subObj->ponderation;
            $objSubTotal = $note * ($ponderation / 100);
        }
        $objSubTotal = round($objSubTotal);

        return $objSubTotal;
    }

    public static function getSectionSubTotal($e, $user, $mentor, $profil, $objectif_id)
    {
        $sectionsubTotal = 0;
        $sectionObjs = Objectif::find($objectif_id);
        $c = 0;
        foreach ($sectionObjs->children as $sub) {
            $c ++;
            $sectionsubTotal += self::getObjSubTotal($e, $user, $mentor, $profil, $sub->id);
        }
        $sectionsubTotal = round($sectionsubTotal / $c);

        return $sectionsubTotal;
    }

    public static function getTotalNote($e, $user, $mentor, $profil)
    {
        $total = 0;
        $objectifs = self::getObjectifs($e);
        $c = 0;
        foreach ($objectifs as $objectif) {
            $c ++;
            $total += self::getSectionSubTotal($e, $user, $mentor, $profil, $objectif->id);
        }

        return round($total / $c);
    }


}
