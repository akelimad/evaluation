<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill_user extends Model
{
    public $table = "skill_user";
    public $timestamps = false;

    protected $fillable = [
      'skill_id', 'user_id', 'mentor_id', 'entretien_id', 'user_notes', 'mentor_notes', 'user_comment', 'mentor_comment'
    ];
}
