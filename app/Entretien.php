<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{
    /**
     * Get the user that owns the interview.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function activites()
    {
        return $this->hasMany('App\Activite');
    }
}
