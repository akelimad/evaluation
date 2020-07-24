<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objectif_user extends Model
{
  public $table = "objectif_user";
  public $timestamps = false;

  protected $fillable = [
    'objectif_id',
    'user_id',
    'note',
    'appreciation',
    'objNplus1',
  ];

  public static function getRealised($eid, $user_id, $oid, $indicator_id) {
    $objectif_user = Objectif_user::where('objectif_id', $oid)->where('user_id', $user_id)->where('entretien_id', $eid)->first();
    if ($objectif_user) {
      $data = json_decode($objectif_user->indicators_data, true) ?: [];
      $data = isset($data[0]) ? $data[0] : [];
      if (!empty($data)) {
        return isset($data[$indicator_id]['realized']) ? $data[$indicator_id]['realized'] : 0;
      }
      return 0;
    }

    return 0;
  }
}
