<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Answer extends Model
{
    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getCollAnswers($qid, $uid, $eid)
    {
        
        $answer = Answer::where('question_id', $qid)
            ->where('user_id', $uid)
            ->where('entretien_id', $eid)
            ->first();
        if($answer && $answer->answer != ""){
            return $answer;
        }
        return false;
    }

    public static function getMentorAnswers($qid, $uid, $eid)
    {
        $user = User::find($uid);
        $answer = Answer::where('question_id', $qid)
            ->where('user_id', $user->id)
            ->where('mentor_id', $user->parent->id)
            ->where('entretien_id', $eid)
            ->first();
        if($answer && $answer->mentor_answer != null){
            return $answer;
        }
        return false;
    }
}
