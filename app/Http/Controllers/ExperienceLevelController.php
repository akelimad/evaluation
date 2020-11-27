<?php

namespace App\Http\Controllers;

use App\ExperienceLevel;
use App\Http\Service\Table;
use Illuminate\Http\Request;

class ExperienceLevelController extends Controller
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
    $query = ExperienceLevel::orderBy('sort_order', 'asc');

    $table->setPrimaryKey('id');
    $table->addColumn('name', 'Nom');
    $table->setBulkActions(true);

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'experience-levels.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"modelForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'ExperienceLevel.delete',
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
    return view('experience-levels.index');
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
      $model = ExperienceLevel::findOrFail($id);
      $title = __("Modifier le niveau d'expérience");
    } else {
      $model = new ExperienceLevel();
      $title = __("Ajouter un niveau d'expérience");
    }
    echo view('experience-levels.form', compact('model'));
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
        $model = ExperienceLevel::find($id);
      } else {
        $model = new ExperienceLevel();
      }
      $model->name = $request->name;
      $model->sort_order = is_null($model->sort_order) ? ExperienceLevel::all()->count() + 1 : $model->sort_order;
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

    foreach ($request->ids as $id) {
      $model = ExperienceLevel::find($id);
      try {
        $model->delete();
      } catch (\Exception $e) {
        return ["status" => "danger", "message" => __("Une erreur est survenue, réessayez plus tard")];
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> ' . __("La suppression a été effectuée avec succès"),
    ]);
  }
}
