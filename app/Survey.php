<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    public function groupes()
    {
        return $this->hasMany('App\Groupe');
    }
}
