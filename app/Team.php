<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public static function getAll()
  {
    $user = \Auth::user();
    if(!empty($user->society_id)){ // this user is not owner
      $fonctions = $user->owner->getTeams();
    } else {
      $fonctions = $user->getTeams();
    }
    return $fonctions;
  }
}
