<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntretienObjectif extends Model
{

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public static function getAll()
    {
      $user = \Auth::user();
      if(!empty($user->society_id)){ // this user is not owner
        $objectifs = $user->owner->entretiensObjectifs();
      } else {
        $objectifs = $user->entretiensObjectifs();
      }
      return $objectifs;
    }

}
