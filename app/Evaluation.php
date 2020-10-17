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
        $str = strtolower(strtr( $str, $unwanted_array ));
        return str_replace(' ', '-', $str);
    }

    public static function surveyId($eid, $evalId)
    {
        $result =  Entretien_evaluation::select('survey_id')
        ->where('entretien_id', $eid)->where('evaluation_id', $evalId)->first();
        $itemsData = $result ? json_decode($result->survey_id) : [];
        if (empty($itemsData)) return 0;
        return count($itemsData) > 1 ? $itemsData : $itemsData[0];
    }

    public static function maxNote()
    {
        $user = User::getOwner();
        $settings = json_decode($user->settings);
        return isset($settings->max_note) && $settings->max_note>0 ? $settings->max_note : 0;
    }
}
