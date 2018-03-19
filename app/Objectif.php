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

    public static function getNmoins1Note($oid){
        $objectifs = Objectif_user::where('user_id', Auth::user()->id)->where('objectif_id', $oid)->get();
        if(count($objectifs)>1){
            $objectif = Objectif_user::where('user_id', Auth::user()->id)->where('objectif_id', $oid)->skip(1)->first();
        }else{
            $objectif = Objectif_user::where('user_id', Auth::user()->id)->where('objectif_id', $oid)->first();
        }
        return $objectif;

    }

    public static function respondObjectifs($eid, $uid){
        $objectif = Objectif_user::where('user_id', $uid)->where('entretien_id', $eid)->first();
        if($objectif){
            return $objectif;            
        }else{
            return null;
        }
    }


}
