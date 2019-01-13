<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Entretien_evaluation;

class Evaluation extends Model
{

    protected $fillable = [
        'survey_id'
    ];

    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }

    public function surveys()
    {
        return $this->hasMany('App\Survey');
    }

    public function entretiens()
    {
        return $this->belongsToMany('App\Entretien');
    }

    public static function unaccented($str) {
        $unwanted_array = [
            'é' => 'e',
            'è' => 'e',
            'â' => 'a',
            'ê' => 'e',
        ];
        return $str = strtolower(strtr( $str, $unwanted_array ));
    }

    public static function surveyId($eid, $evalId)
    {
        $result =  Entretien_evaluation::select('survey_id')
        ->where('entretien_id', $eid)->where('evaluation_id', $evalId)->first();
        return $result ? $result->survey_id : '';
    }

    public static function maxNote()
    {
        $user = User::getOwner();
        $settings = json_decode($user->settings);
        return isset($settings->max_note) && $settings->max_note>0 ? $settings->max_note : 0;
    }
}
