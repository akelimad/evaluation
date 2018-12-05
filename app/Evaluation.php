<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{

    protected $fillable = [
        'survey_id'
    ];

    public function survey()
    {
        return $this->belongsTo('App\Survey');
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
}
