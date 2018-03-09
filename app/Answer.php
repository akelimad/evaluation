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

    public static function getAnswers($qid, $uid){
        $question = Question::find($qid);
        $answers_id = [];
        if(count($question->children)>0){
            $answers = Answer::select('answer')->where('question_id', $qid)->where('user_id', $uid)->get()->toArray();
            foreach ($answers as $answer) {
                foreach ($answer as $a) {
                    $answers_id[] = $a;
                }
            }
            return $answers_id;
        }else{
            $answer = Answer::where('question_id', $qid)->where('user_id', $uid)->first();
            return $answer;
        }
    }
}
