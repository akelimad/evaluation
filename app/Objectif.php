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

    public static function getObjectif($eid, $oid){
        $objectif = Objectif_user::where('user_id', Auth::user()->id)->where('entretien_id', $eid)->where('objectif_id', $oid)->first();
        return $objectif;
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

    public static function filledObjectifs($eid, $uid){
        $objectif = Objectif_user::where('user_id', $uid)->where('entretien_id', $eid)->first();
        if($objectif){
            return $objectif;            
        }else{
            return null;
        }
    }


}
