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
        if($answer && ($answer->answer != "" || $answer->mentor_answer != "")){
            return $answer;
        }
        return false;
    }

    public static function getMentorAnswers($qid, $uid, $evaluator_id = 0, $eid)
    {
        $user = User::findOrFail($uid);
        $answer = Answer::where('question_id', $qid)
            ->where('user_id', $user->id)
            ->where('mentor_id', $evaluator_id)
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
        $sum = 0;
        if($group->ponderation > 0) {
            $grpQstsId = $group->questions->pluck('id')->toArray();
            $answers = Answer::whereIn('question_id', $grpQstsId)
              ->where('user_id', $user->id)
              ->where('mentor_id', $user->parent->id)
              ->where('entretien_id', $eid)
              ->get();
            if (empty($answers)) return 0;
            $sum = 0;
            foreach($answers as $answer) {
                $question = Question::find($answer->question_id);
                $sum += $answer->note * ($question->ponderation / 100);
            }
        }
        $sum = number_format($sum, '2') + 0;
        return $sum . ' %';
    }

    public static function getTotalNote($sid, $uid, $eid)
    {
        $survey = Survey::find($sid);
        if (!$survey || $survey->groupes->count() == 0) return 0;
        $groups = $survey->groupes;
        $sum = 0;
        foreach ($groups as $group) {
            $grpNote = floatval(self::getGrpNote($group->id, $uid, $eid));
            $sum += $grpNote * ($group->ponderation / 100);
        }

        return number_format($sum) + 0;
    }

    public static function formated($number)
    {
        return number_format($number, 1, ".", "");
    }

    public static function  cutNum($num, $precision = 1){
        return floor($num).substr($num-floor($num),1,$precision+1);
    }

    public static function getUserGlobaleNote($eid, $uid) {
        $evalSurveyId = Entretien_evaluation::getItemsId($eid, 1);
        $carrerSurveyId = Entretien_evaluation::getItemsId($eid, 2);
        $entretienSurveysId = array_merge($evalSurveyId, $carrerSurveyId);
        $sum = 0;
        $i = 0;
        foreach ($entretienSurveysId as $key => $sid) {
            $sum += self::getTotalNote($sid, $uid, $eid);
            $i ++;
        }

        return $i > 0 ? number_format($sum / $i) + 0 : 0;
    }

    public static function usersNotes($eid, $users) {
        $data = [];
        if (empty($users)) return $data;
        foreach ($users as $user) {
            $data[] = [
                "user_fullname" => $user->fullname(),
                "note" => self::getUserGlobaleNote($eid, $user->id)
            ];
        }
        $notes = array_column($data, 'note');
        array_multisort($notes, SORT_DESC, $data);
        return $data;
    }

    public static function getUsersNotesBy($modelName, $model_id, $users, $eid) {
        $sum = 0;
        if ($modelName == 'Fonction') {
            $i = 0;
            foreach ($users as $user) {
                if ($user->function != $model_id) continue;
                $sum += self::getUserGlobaleNote($eid, $user->id);
                $i ++;
            }
        }
        if ($modelName == 'Department') {
            $i = 0;
            foreach ($users as $user) {
                if ($user->service != $model_id) continue;
                $sum += self::getUserGlobaleNote($eid, $user->id);
                $i ++;
            }
        }

        $result = $i > 0 ? $sum / $i : 0;
        return $result;
    }

    public static function getGlobalNoteByTab($modelName, $modelUsers, $eid) {
        if (empty($modelUsers)) return 0;
        $sum = 0;
        $i = 0;
        foreach ($modelUsers as $model_id => $users) {
            $sum += self::getUsersNotesBy($modelName, $model_id, $users, $eid);
            $i ++;
        }

        $result = $i > 0 ? $sum / $i : 0;
        return $result;
    }

}
