<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use App\Http\Service\Table;

use App\Http\Requests;

class RoleController extends Controller
{
  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Role::where('name', '<>', 'ROOT')->where('name', '<>', 'ADMIN');

    $table->setPrimaryKey('id');
    $table->addColumn('name', 'Nom');
    $table->addColumn('display_name', "Nom d'affichage");
    $table->addColumn('description', "Description");

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'role.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"roleForm", "target-table":"[chm-table]"}}}',
      ]
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'chmRole.delete',
    ]);

    // render the table
    return $table->render($query);
  }

  public function index()
  {
    return view('users/roles.index');
  }

  public function form(Request $request)
  {
    if($request->method() == 'POST') {
      return $this->store($request);
    }
    ob_start();
    $permissions = Permission::all();
    if ($request->id > 0) {
      $role = Role::find($request->id);
    } else {
      $role = new Role();
    }
    echo view('users.roles.form', compact('role', 'permissions'));
    $content = ob_get_clean();
    return ['title' => 'Ajouter un rôle', 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->input('id', false);
    if ($id) {
      $role = Role::findOrFail($id);
      $role->name = $request->name;
      $role->display_name = $request->display_name;
      $role->description = $request->description;
      $role->save();
      if ($request->permissions) {
        $role_perms = [];
        foreach ($role->perms()->get() as $perm) {
          $role_perms[] = $perm->id;
        }
        $role->perms()->detach($role_perms);
        $role->perms()->attach($request->permissions);
      }

    } else {
      $role = new Role();
      $role->name = $request->name;
      $role->display_name = $request->display_name;
      $role->description = $request->description;
      $role->save();
      // $role->perms()->detach($request->permissions);
      if ($request->permissions) {
        $role->attachPermissions($request->permissions);
      }
    }
    if ($role->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }

  }

  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      $role = Role::find($id);
      try {
        $role->delete();
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