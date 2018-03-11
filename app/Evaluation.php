<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }

    public function entretiens()
    {
        return $this->belongsToMany('App\Entretien');
    }
}
