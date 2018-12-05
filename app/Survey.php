<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    public function groupes()
    {
        return $this->hasMany('App\Groupe');
    }

    public function evaluations()
    {
        return $this->belongsToMany('App\Evaluation');
    }

    public static function icompleteSurvey($sid){
        $survey = Survey::find($sid);
        $groupes = $survey->groupes;
        $incompleteSurvey = false;
        foreach ($groupes as $groupe) {
            foreach ($groupe->questions as $question) {
                if( $question->type  == 'checkbox' || $question->type == 'radio' ){
                    if( count($question->children) <= 0 ){
                        $incompleteSurvey = true;
                    }else{
                        $incompleteSurvey = false;
                    }
                }
            }
        }
        return $incompleteSurvey;
    }

}
