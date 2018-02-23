<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    public function entretien()
    {
        return $this->belongsTo('App\Entretien');
    }
}
