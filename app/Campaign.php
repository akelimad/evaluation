<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
  protected $fillable = [
    'entretien_id', 'email_id', 'receiver', 'sheduled_at', 'status', 'sent_at'
  ];
}
