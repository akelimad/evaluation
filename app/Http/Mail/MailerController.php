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

  public  static function send($user, $entretien, $template, $extra_var = [])
  {
    if(!$template) return;
    $password = self::rand_string(10);
    if(strpos($template->message, '{{password}}') !== false){
      $user->password = bcrypt($password);
      $user->save();
    }
    $fullname = '***** *****';
    if ($entretien->isFeedback360()) {
      $id = $entretien->getUsersIdToEvaluate();
      if ($id > 0) {
        $userToEvaluate = User::find($id);
        if ($userToEvaluate) $fullname = $userToEvaluate->fullname();
      }
    }
    $coll_fullname = isset($extra_var['coll_evaluated_fullname']) ? $extra_var['coll_evaluated_fullname'] : '';
    $variables = [
      'userToEvaluatefullname'=> $fullname,
      'user_fname'      => $user->name ? $user->name : 'coll_fname',
      'coll_fullname'   => !empty($coll_fullname) ? $coll_fullname : $user->fullname(),
      'mentor_fullname' => $user->parent != null ? $user->parent->fullname() : '',
      'title'           => isset($entretien->titre) ? $entretien->titre : '---',
      'date'            => Carbon::parse($entretien->date)->format('d-m-Y'),
      'date_limit'      => Carbon::parse($entretien->date_limit)->format('d-m-Y'),
      'site_url'        => url('/'),
      'email'           => $user->email,
      'password'        => $password,
    ];
    $body = Email::renderMessage($template->message, $variables);
    $subject = Email::renderMessage($template->subject, $variables);
    return Mail::send([], [], function ($m) use ($user, $template, $subject, $body) {
      $m->from($template->sender, $template->name);
      $m->to($user->email);
      $m->subject($subject);
      $m->setBody($body, 'text/html');
    });
  }

  public static function rand_string($length)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars), 0, $length);
  }

}
