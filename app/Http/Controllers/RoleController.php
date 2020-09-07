<?php

namespace App\Http\Controllers;

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
      'callback' => 'chmRole.edit({id: [id]})',
    ]);

    // render the table
    return $table->render($query);
  }
}