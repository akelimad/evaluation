<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    const STATUS = [
        1 => "En attente",
        2 => "Accepté",
        3 => "Refusé",
    ];

    public function entretien()
    {
        return $this->belongsTo('App\Entretien');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getStatus() {
        if ($this->status == 1) {
            return "En attente";
        } else if ($this->status == 2) {
            return "Accepté";
        } else if ($this->status == 3) {
            return "Refusé";
        } else {
            return 'Default';
        }
    }
}
