<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getMentor($uid)
    {
        $user = User::findOrFail($uid);
        return $user->parent;
    }

    public function parent()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function children()
    {
        return $this->hasMany('App\User', 'user_id');
    }

    /**
     * Get the interview for the given user.
     */
    public function entretiens()
    {
        return $this->belongsToMany('App\Entretien');
    }

    /**
     * Get the interview for the given user.
     */
    public function activites()
    {
        return $this->hasMany('App\Activite');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

}
