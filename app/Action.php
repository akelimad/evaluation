<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{

    /**
     * Get the email that owns the action.
     */
    public function emails()
    {
        return $this->belongsToMany('App\Email');
    }

}
