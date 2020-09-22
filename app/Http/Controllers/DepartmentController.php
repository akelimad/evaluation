<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Service\Table;
use Illuminate\Http\Request;
use Auth;

class DepartmentController extends Controller
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
    $query = Department::getAll()->orderBy('title', 'asc');

    $table->setPrimaryKey('id');
    $table->addColumn('title', 'Titre');
    $table->setBulkActions(true);

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'department.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"departmentForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'Department.delete',
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
    return view('departments.index', [
      'active' => 'dep',
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
      $department = Department::findOrFail($id);
      $title = "Modifier le département";
    } else {
      $department = new Department();
      $title = "Ajouter un département";
    }
    echo view('departments.form', compact('department'));
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
    if ($id > 0) {
      $department = Department::findOrFail($id);
      $department->title = $request->departments[0];
      $department->save();
    } else {
      foreach ($request->departments as $d) {
        $department = new Department();
        $department->title = $d;
        $department->user_id = Auth::user()->id;
        $department->save();
      }
    }
    if ($department->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
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
      $dept = Department::find($id);
      try {
        $dept->delete();
      } catch (\Exception $e) {
        return ["status" => "danger", "message" => "Une erreur est survenue, réessayez plus tard."];
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> La suppression a été effectuée avec succès',
    ]);
  }

}
