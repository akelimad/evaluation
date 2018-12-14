<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
