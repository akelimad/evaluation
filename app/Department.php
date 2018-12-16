<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  public function user()
  {
      return $this->belongsTo('App\User');
  }

  public static function getAll()
  {
    $user = \Auth::user();
    if(!empty($user->society_id)){ // this user is not owner
      $departments = $user->owner->departments();
    } else {
      $departments = $user->departments();
    }
    return $departments;
  }

}
