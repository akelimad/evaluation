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

    public static function getObjectif($id){
        $objectif = Objectif_user::where('user_id', Auth::user()->id)->where('objectif_id', $id)->first();
        return $objectif;
    }
}
