<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    public function entretien()
    {
        return $this->belongsTo('App\Entretien');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
