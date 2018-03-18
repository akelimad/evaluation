<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objectif_user extends Model
{
    public $table = "objectif_user";
    public $timestamps = false;

    protected $fillable = [
        'objectif_id',
        'user_id',
        'note',
        'appreciation',
        'objNplus1',
    ];
}
