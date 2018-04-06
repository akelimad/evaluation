<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /**
     * Get the action record associated with the email.
     */
    public function actions()
    {
        return $this->belongsToMany('App\Action');
    }

    public static function renderMessage($message, $variables)  {       
        return preg_replace_callback('#{{([^}]+)}}#', function($m) use ($message, $variables) {          
            if( isset($variables[$m[1]]) ){            
                return $variables[$m[1]];           
            }else{            
                return $m[0];           
            }    
        }, $message);   
    }
}
