<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Email;
use App\Action;
use App\User;

class EmailController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $emails = Email::getAll()->paginate(10);
    return view('emails.index', compact('emails'));
  }

  public function form(Request $request)
  {
    if ($request->method() == "POST") {
      return $this->store($request);
    }
    $id = $request->id;
    ob_start();
    if (isset($id) && is_numeric($id)) {
      $email = Email::findOrFail($id);
      $title = "Mettre à jour l'email";
    } else {
      $email = new Email();
      $title = "Ajouter un email";;
    }
    echo view('emails.form', compact('email'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->id;
    $rules = [
      'ref' => 'required',
      'sender' => 'required',
      'subject' => 'required',
      'message' => "required",
    ];

    $query = Email::where('ref', $request->ref)->where('user_id', User::getOwner()->id);
    if (isset($id) && is_numeric($id)) {
      $email = Email::findOrFail($id);
      $exist = $query->where('id', '<>', $id)->count();
    } else {
      $email = new Email();
      $exist = $query->count();
    }

    $validator = Validator::make($request->all(), $rules);
    $messages = $validator->errors();

    if ($exist > 0) $messages->add('exist_ref', 'La réference existe déjà !');

    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }

    $email->ref = $request->ref;
    $email->user_id = User::getOwner()->id;
    $email->sender = $request->sender;
    $email->name = $request->name;
    $email->subject = $request->subject;
    $email->message = $request->message;
    $email->save();
    if ($email->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }
  }

  public function delete(Request $request)
  {
    $email = Email::find($request->id);
    $email->delete();
    return ["status" => "success", "message" => "L'email a été supprimé avec succès !"];
  }

}
