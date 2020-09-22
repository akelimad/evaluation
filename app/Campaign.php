<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
  protected $fillable = [
    'entretien_id', 'email_id', 'receiver', 'shedule_type', 'sheduled_at', 'status', 'sent_at'
  ];

  public static function get($eid) {
    $campaign = Campaign::where('entretien_id', $eid)->first();

    return $campaign ? $campaign : false;
  }
}
