<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

  public function users()
  {
    return $this->belongsToMany(User::class);
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
