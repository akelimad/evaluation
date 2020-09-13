<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;

class PermissionController extends Controller
{
  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Permission::all();

    $table->setPrimaryKey('id');
    $table->addColumn('name', 'Nom');
    $table->addColumn('display_name', "Nom d'affichage");
    $table->addColumn('description', "Description");

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'permission.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"permissionForm", "target-table":"[chm-table]"}}}',
      ]
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'chmPermission.delete',
    ]);

    // render the table
    return $table->render($query);
  }

  public function index()
  {
    return view('permissions.index');
  }

  public function form(Request $request)
  {
    if($request->method() == 'POST') {
      return $this->store($request);
    }
    ob_start();
    if ($request->id > 0) {
      $permission = Permission()::find($request->id);
    } else {
      $permission = new Permission();
    }
    echo view('permissions.form', compact('permission'));
    $content = ob_get_clean();
    return ['title' => 'Créer une permission', 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->input('id', false);
    if ($id) {
      $permission = Permission::find($id);
    } else {
      $permission = new Permission();
    }
    $permission->name = $request->name;
    $permission->display_name = $request->display_name;
    $permission->description = $request->description;
    $permission->save();
    if ($permission->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }
  }

}
