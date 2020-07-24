<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entretien_evaluation extends Model
{
  public $table = "entretien_evaluation";
  public $timestamps = false;

  public static function getItemsId($entretien_id, $evaluation_id) {
    $entretien_evaluation = Entretien_evaluation::where('entretien_id', $entretien_id)->where('evaluation_id', $evaluation_id)->first();
    if (!$entretien_evaluation) return [];
    $surveyids = json_decode($entretien_evaluation->survey_id, true) ?: [];
    if (!empty($surveyids)) {
      return $surveyids;
    }

    return [];
  }
}
