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

    public static function getAnswers($qid){
        $question = Question::find($qid);
        $answers_id = [];
        if(count($question->children)>0){
            $answers = Answer::select('answer')->where('question_id', $qid)->where('user_id', Auth::user()->id)->get()->toArray();
            foreach ($answers as $answer) {
                foreach ($answer as $a) {
                    $answers_id[] = $a;
                }
            }
            return $answers_id;
        }else{
            return Answer::where('question_id', $qid)->where('user_id', Auth::user()->id)->first();
        }
    }
}
