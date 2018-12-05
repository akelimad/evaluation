<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	public function parent()
    {
        return $this->belongsTo('App\Question', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Question', 'parent_id');
    }

    public function groupe()
    {
        return $this->belongsTo('App\Groupe');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }
}
