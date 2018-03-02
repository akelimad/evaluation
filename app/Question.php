<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function groupe()
    {
        return $this->belongsTo('App\Groupe');
    }
}
