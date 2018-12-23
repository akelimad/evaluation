<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Groupe;

class Answer extends Model
{
    const NOTE_DEGREE = [
        1 => ['ref' => 'I', 'title' => "Insuffisant"],
        2 => ['ref' => 'ED', 'title' => "En dessous des attentes"],
        3 => ['ref' => 'EL', 'title' => "En ligne avec les attentes"],
        4 => ['ref' => 'AD', 'title' => "Au-dessus des attentes"],
        5 => ['ref' => 'R', 'title' => "Remarquable"],
    ]; 

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

    public static function getGrpNote($gid, $uid, $eid)
    {
        $group = Groupe::find($gid);
        $user = User::find($uid);
        if($group->notation_type == 'section') {
            $question = $group->questions()->first();
            $answer = Answer::where('question_id', $question->id)
                ->where('user_id', $user->id)
                ->where('mentor_id', $user->parent->id)
                ->where('entretien_id', $eid)
                ->first();
            return $answer->note;
        }
        return '';
    }

    public static function formated($number)
    {
        return number_format($number, 1, ".", "");
    }

    public static function  cutNum($num, $precision = 1){
        return floor($num).substr($num-floor($num),1,$precision+1);
    }

}
