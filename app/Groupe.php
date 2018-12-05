<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }

}
