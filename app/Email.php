<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /**
     * Get the action record associated with the email.
     */
    public function action()
    {
        return $this->hasOne('App\Action');
    }
}
