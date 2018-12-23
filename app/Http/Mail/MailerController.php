<?php

namespace App\Http\Mail;
ini_set('max_execution_time', 300); //5 minutes

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Email;
use App\Action;
use App\User;
use App\Http\Requests;
use Mail;

class MailerController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public  static function send($user, $entretien, $template)
  {
    if(!$template) return;
    $password = self::rand_string(10);
    if(strpos($template->message, '{{password}}') !== false){
      $user->password = bcrypt($password);
      $user->save();
    }
    $body = Email::renderMessage($template->message, [
      'user_fname'      => $user->name ? $user->name : 'coll_fname',
      'coll_fullname'   => $user ? $user->name .' '. $user->last_name : '',
      'mentor_fullname' => $user->parent ? $user->parent->name .' '. $user->parent->last_name : '',
      'title'           => isset($entretien->titre) ? $entretien->titre : '---',
      'date'            => Carbon::parse($entretien->date)->format('d-m-Y'),
      'date_limit'    => Carbon::parse($entretien->date_limit)->format('d-m-Y'),
      'site_url'        => url('/'),
      'email'           => $user->email,
      'password'        => $password,
    ]);
    Mail::send([], [], function ($m) use ($user, $template, $body) {
      $m->from($template->sender, $template->name);
      $m->to($user->email);
      $m->subject($template->subject);
      $m->setBody($body, 'text/html');
    });
  }

  public static function rand_string($length)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars), 0, $length);
  }

}
