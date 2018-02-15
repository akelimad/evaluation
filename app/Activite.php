<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function entretien()
    {
        return $this->belongsTo('App\Entretien');
    }
}
