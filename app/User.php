<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Auth;

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
        if($user){
            return $user->parent;
        }else{
            return $user;
        }
    }

    public function parent()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function children()
    {
        return $this->hasMany('App\User', 'user_id');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'society_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'society_id');
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
    public function objectifs()
    {
        return $this->belongsToMany('App\Objectif');
    }

    /**
     * Get the skills for the given user.
     */
    public function skills()
    {
        return $this->belongsToMany('App\Skill');
    }

    public function formations()
    {
        return $this->hasMany('App\Formation');
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

    public function departments()
    {
        return $this->hasMany('App\Department');
    }

    public function fonctions()
    {
        return $this->hasMany('App\Fonction');
    }

    public static function hasMotif($eid, $uid){
        $hasMotif = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)->first();
        if( $hasMotif->motif ){
            return $hasMotif->motif;
        }
        return null;
    }

    public function getRoles()
    {
        $userRoles = [];
        foreach (Auth::user()->roles as $key => $role) {
            $userRoles[] = $role->name;
        }
        return $userRoles;
    }


    public function entretiensObjectifs()
    {
        return $this->hasMany('App\EntretienObjectif');
    }

    public static function logo($id)
    {
        $user = User::find($id);
        if(!empty($user->society_id)){ // this user is not owner
            $path = $user->owner->id .'/'. $user->owner->logo;
        } else {
            $path = $user->id .'/'. $user->logo;
        }

        $path = 'uploads/logos/'. $path;
        if (file_exists( public_path($path) )) {
            return asset($path);
        } else {
            return asset('img/logo.png');
        }
    }

    public static function getUsers()
    {
        $user = Auth::user();
        if(!empty($user->society_id)){ // this user is not owner
            $users = $user->owner->users();
        } else {
            $users = $user->users();
        }
        return $users;
    }


}
