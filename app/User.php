<?php

namespace App;

use App\Notifications\PasswordReset;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Auth;
use Mail;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
  use EntrustUserTrait, Notifiable;

  const SENIORITY = [
    '<2'  => '0 - 2 ans',
    '2-4' => '2 - 4 ans',
    '4-6' => '4 - 6 ans',
    '6-8' => '6 - 8 ans',
    '>8'  => '> 8 ans',
  ];

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
    if ($user) {
      return $user->parent;
    } else {
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

  public function skills()
  {
    return $this->hasMany('App\Skill');
  }

  public function getEntretiens()
  {
    return $this->hasMany('App\Entretien');
  }

  public function getSurveys()
  {
    return $this->hasMany('App\Survey');
  }

  public function getTeams()
  {
    return $this->hasMany('App\Team');
  }

  public function teams()
  {
    return $this->belongsToMany(Team::class);
  }

  public function getEmails()
  {
    return $this->hasMany('App\Email');
  }

  public static function hasMotif($eid, $uid)
  {
    $hasMotif = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)->first();
    if ($hasMotif->motif) {
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
    $user = User::findOrFail($id);
    if (!empty($user->society_id)) { // this user is not owner
      $path = $user->owner->id . '/' . $user->owner->logo;
    } else {
      $path = $user->id . '/' . $user->logo;
    }

    $path = 'uploads/logos/' . $path;
    if (file_exists(public_path($path))) {
      return asset($path);
    }
    return "img/logo.png";
  }

  public static function avatar($id)
  {
    $user = User::findOrFail($id);
    if (!empty($user->avatar)) {
      $path = $user->id . '/' . $user->avatar;
      $path = 'uploads/avatars/' . $path;
      if (file_exists(public_path($path))) {
        return asset($path);
      }
    }

    return asset("img/avatar.png");
  }

  public static function getUsers()
  {
    $user = Auth::user();
    if (!empty($user->society_id)) { // this user is not owner
      $users = $user->owner->users();
    } else {
      $users = $user->users();
    }
    return $users;
  }

  public static function getOwner()
  {
    $user = Auth::user();
    if (!empty($user->society_id)) {
      return $user->owner;
    } else {
      return $user;
    }
  }

  public static function displayName()
  {
    $user = Auth::user();
    if($user->hasRole('ADMIN')) {
      return $user->name;
    }else{
      return $user->name.' '.$user->last_name;
    }
    return "anonymous";
  }

  public function fullname() {
    return $this->name . " " . $this->last_name;
  }

  /**
   * @return Bool Whether the user is active or not
   */
  public function isOnline()
  {
    // Delay during which the user will be considered as still active
    $delay = (new \DateTime('1 minutes ago'))->format('Y-m-d H:i:s');

    return $this->last_activity_at > $delay;
  }

  public function getTeamsMembers() {
    $teamsUsers = [];
    if (!empty($this->teams)) {
      foreach ($this->teams as $team) {
        $teamsUsers[] = $team->users;
      }
    }

    return $teamsUsers[0] ? $teamsUsers[0] : [];
  }

  public static function getManagers() {
    $users = self::getUsers()->get();
    $managers = [];
    foreach ($users as $user) {
      if ($user->children->count() <= 0) continue;
      $managers[] = $user;
    }

    return $managers;
  }

  public function sendPasswordResetNotification($token){
    Mail::send('auth.emails.password', [
      'firstname'      => $this->name,
      'reset_url'     => route('password.reset', ['token' => $token, 'email' => $this->email]),
    ], function($message) {
      $message->subject(__("Réinitialiser votre mot de passe"));
      $message->to($this->email);
    });
  }


}
