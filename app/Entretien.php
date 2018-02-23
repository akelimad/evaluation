<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function activites()
    {
        return $this->hasMany('App\Activite');
    }

    public function skills()
    {
        return $this->hasMany('App\Skill');
    }

    public function objectifs()
    {
        return $this->hasMany('App\Objectif');
    }

    public function formations()
    {
        return $this->hasMany('App\Formation');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function remunerations()
    {
        return $this->hasMany('App\Remuneration');
    }

    public function decisions()
    {
        return $this->hasMany('App\Decision');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
