<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function entretien()
    {
        return $this->belongsTo('App\Entretien');
    }
}
