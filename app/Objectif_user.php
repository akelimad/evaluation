<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objectif_user extends Model
{
  public $table = "objectif_user";
  public $timestamps = false;

  protected $guarded = [];

  public static function getRealised($eid, $user_id, $oid, $indicator_id, $role = 'user') {
    $objectif_user = Objectif_user::where('objectif_id', $oid)->where('user_id', $user_id)->where('entretien_id', $eid)->first();
    if ($objectif_user) {
      $data = json_decode($objectif_user->indicators_data, true) ?: [];
      $data = isset($data[0]) ? $data[0] : [];
      if (!empty($data)) {
        $key = $role.'_realized';
        return isset($data[$indicator_id][$key]) ? $data[$indicator_id][$key] : 0;
      }
      return 0;
    }

    return 0;
  }

  public static function getValues($eid, $user_id, $oid) {
    $objectif_user = Objectif_user::where('objectif_id', $oid)->where('user_id', $user_id)->where('entretien_id', $eid)->first();

    if (!$objectif_user) return [];
    $collValues = $mentorValues = $teamValues = [];
    $indicators = json_decode($objectif_user->indicators_data, true) ?: [];
    $indicators = isset($indicators[0]) ? $indicators[0] : [];
    if (!empty($indicators)) {
      foreach($indicators as $key => $indicator) {
        $collValues[] = isset($indicator['user_realized']) ? $indicator['user_realized'] : 0;
        $mentorValues[] = isset($indicator['mentor_personnal_realized']) ? $indicator['mentor_personnal_realized'] : 0;
        $teamValues[] = isset($indicator['mentor_team_realized']) ? $indicator['mentor_team_realized'] : 0;
      }
    }
    return [
      'collValues' => $collValues,
      'mentorValues' => $mentorValues,
      'teamValues' => $teamValues,
    ];
  }
}
