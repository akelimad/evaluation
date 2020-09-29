<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\Permission;
use App\Role;
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

  public function index(Request $request)
  {
    if ($request->method() == "POST") {
      $roles = $request->get('roles', false);
      foreach (Role::all() as $role) {
        $roleOldPerms = $role->perms()->pluck('id')->toArray();
        $role->perms()->detach($roleOldPerms);
      }
      $allPermissionsId = Permission::all()->pluck('id')->toArray();
      $roleAdmin = Role::where('name', 'ADMIN')->first();
      // this is just to give admin all permissions
      if ($roleAdmin) $roleAdmin->perms()->sync($allPermissionsId);
      if (!empty($roles)) {
        foreach ($roles as $role_id => $permissions) {
          $role = Role::find($role_id);
          if (!$role) continue;
          $role->perms()->sync($permissions);
        }
      }

      return response()->json([
        'status' => 'success',
        'message' => __("Les droits d'accès ont bien été mis à jour")
      ]);
    }
    return view('permissions.index');
  }


}
