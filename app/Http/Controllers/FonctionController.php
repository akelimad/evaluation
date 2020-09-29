<?php

namespace App\Http\Controllers;

use App\Fonction;
use App\Http\Service\Table;
use Illuminate\Http\Request;
use Auth;

class FonctionController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Fonction::getAll()->orderBy('title', 'asc');

    $table->setPrimaryKey('id');
    $table->addColumn('title', 'Titre');
    $table->setBulkActions(true);

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'function.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"functionForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'Fonction.delete',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('functions.index', [
      'active' => 'func',
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function form(Request $request)
  {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    $id = $request->id;
    ob_start();
    if (isset($id) && is_numeric($id)) {
      $fonction = Fonction::findOrFail($id);
      $title = __("Modifier la fonction");
    } else {
      $fonction = new Fonction();
      $title = __("Ajouter une fonction");
    }
    echo view('functions.form', compact('fonction'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $id = $request->id;
    if ($id) {
      $fonction = Fonction::findOrFail($id);
      $fonction->title = $request->titles[0];
      $fonction->save();
    } else {
      foreach ($request->titles as $f) {
        $fonction = new Fonction();
        $fonction->title = $f;
        $fonction->user_id = Auth::user()->id;
        $fonction->save();
      }
    }
    if ($fonction->save()) {
      return ["status" => "success", "message" => __('Les informations ont été sauvegardées avec succès')];
    } else {
      return ["status" => "warning", "message" => __('Une erreur est survenue, réessayez plus tard')];
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      $func = Fonction::find($id);
      try {
        $func->delete();
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
