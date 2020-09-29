<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\Modele;
use Illuminate\Http\Request;

class ModeleController extends Controller
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
    $query = Modele::orderBy('title', 'asc');

    $table->setPrimaryKey('id');
    $table->addColumn('title', 'Titre');
    $table->setBulkActions(true);

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'model.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"modelForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'Modele.delete',
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
    return view('models.index', [
      'active' => 'model',
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
    if ($id > 0) {
      $model = Modele::findOrFail($id);
      $title = __("Modifier la modèle");
    } else {
      $model = new Modele();
      $title = __("Ajouter un modèle");
    }
    echo view('models.form', compact('model'));
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
    try {
      $id = $request->id;
      if ($id) {
        $model = Modele::find($id);
      } else {
        $model = new Modele();
      }
      $model->ref = $request->ref;
      $model->title = $request->title;
      $model->save();
      return ["status" => "success", "message" => __("Les informations ont été sauvegardées avec succès")];
    } catch (\Exception $e) {
      return ["status" => "danger", "message" => __("Une erreur est survenue, réessayez plus tard")];
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
      $model = Modele::find($id);
      try {
        $model->delete();
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
