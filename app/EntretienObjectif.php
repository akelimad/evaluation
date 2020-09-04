<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntretienObjectif extends Model
{

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function getType() {
    if ($this->type == 'Personnel') {
      return "Individuel";
    }
    return "Collectif";
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

  public function getIndicators() {
    $indicators = json_decode($this->indicators, true) ?: ['' => ''];
    return $indicators;
  }

  public function getIndicatorsTitle() {
    $indicatorsTitle = [];
    if (!empty($this->getIndicators())) {
      foreach ($this->getIndicators() as $key => $indicator) {
        $indicatorsTitle[] = $indicator['title'];
      }
    }
    return $indicatorsTitle;
  }

  public function setIndicators($indicators = []) {
    $this->indicators = json_encode($indicators);
    return $this;
  }

}
