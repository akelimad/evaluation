<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entretien_evaluation extends Model
{
  public $table = "entretien_evaluation";
  public $timestamps = false;

  public function getItemsId() {
    $surveyids = json_decode($this->survey, true) ?: [];
    if (!empty($surveyids)) {
      if (count($surveyids) == 1) {
        return $surveyids[0];
      } else {
        return $surveyids;
      }
    }

    return 0;
  }
}
