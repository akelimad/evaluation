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
        //  var_dump('qid = '. $qid ." uid= ".$uid." eid= ". $eid);
        $question = Question::find($qid);
        $answers_id = [];
        if(count($question->children)>0){
            $answers = Answer::select('answer')->where('question_id', $qid)->where('user_id', $uid)->where('entretien_id', $eid)->get()->toArray();
            foreach ($answers as $answer) {
                foreach ($answer as $a) {
                    $answers_id[] = $a;
                }
            }
            return $answers_id;
        }else{
            $answer = Answer::where('question_id', $qid)->where('user_id', $uid)->where('entretien_id', $eid)->first();
            return $answer;
        }
    }

    public static function getMentorAnswers($qid, $uid, $eid)
    {
        $user = User::find($uid);
        $question = Question::find($qid);
        $answers_id = [];
        if(count($question->children)>0){
            $answers = Answer::select('mentor_answer')->where('question_id', $qid)->where('user_id', $user->id)->where('mentor_id', $user->parent->id)->where('entretien_id', $eid)->get()->toArray();
            foreach ($answers as $answer) {
                foreach ($answer as $a) {
                    $answers_id[] = $a;
                }
            }
            return $answers_id;
        }else{
            $answer = Answer::where('question_id', $qid)->where('user_id', $user->id)->where('mentor_id', $user->parent->id)->where('entretien_id', $eid)->first();
            return $answer;
        } 
    }
}
