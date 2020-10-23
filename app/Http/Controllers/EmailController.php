<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
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

  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Email::getAll()->orderBy('id', 'DESC');

    $table->setPrimaryKey('id');
    $table->setBulkActions(true);
    $table->addColumn('sender', 'Emetteur');
    $table->addColumn('name', 'Nom');
    $table->addColumn('subject', 'Objet', function ($entity) {
      return str_limit($entity->subject, 80);
    });

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'email.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"emailForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'Email.delete',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }

  public function index()
  {
    return view('emails.index', ['active' => 'emails']);
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
      return ["status" => "success", "message" => __("Les informations ont été sauvegardées avec succès")];
    } else {
      return ["status" => "warning", "message" => __("Une erreur est survenue, réessayez plus tard")];
    }
  }

  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      $email = Email::find($id);
      try {
        $email->delete();
      } catch (\Exception $e) {
        return ["status" => "danger", "message" => __("Une erreur est survenue, réessayez plus tard")];
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> '. __("La suppression a été effectuée avec succès"),
    ]);
  }

}
