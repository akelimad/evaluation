<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\Team;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class TeamController extends Controller
{
  public function getTable(Request $request) {
    $query = Team::getAll();

    $table = new Table($request);
    $table->setBulkActions(true);
    $table->setDateFormat('d/m/Y H:i');
    $table->setPrimaryKey('id');
    $table->addColumn('name', 'Nom');
    $table->addColumn('count_user', "Nbr. de collaboratuers", function ($entity) {
      return $entity->users->count();
    });
    $table->addColumn('description', "Description", function ($entity) {
      return $entity->description != '' ? $entity->description : '---';
    });
    $table->addColumn('created_at', "Créée le");

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'team.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"teamForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'Team.delete',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }


  public function index()
  {
    return view('teams.index', ['active' => 'teams']);
  }

  public function form(Request $request)
  {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    ob_start();
    $id = $request->id;
    if (isset($id) && is_numeric($id)) {
      $team = Team::findOrFail($id);
      $title = __("Modifier l'équipe");
    } else {
      $team = new Team();
      $title = __("Ajouter une équipe");
    }
    $collaborators = User::getUsers()->where('user_id', '<>', 0)->get();
    $teamUsers = $team->users()->get()->pluck('id')->toArray();
    echo view('teams.form', compact('team', 'title', 'collaborators', 'teamUsers'));
    $content = ob_get_clean();

    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request)
  {
    $teamUsersId = $request->usersId;

    $id = $request->id;
    if ($id > 0) {
      $team = Team::find($id);
    } else {
      $team = new Team();
    }
    $team->user_id = User::getOwner()->id;
    $team->name = $request->name;
    $team->description = $request->description;
    $team->save();
    if (!empty($teamUsersId)) {
      $team->users()->sync($teamUsersId);
    }

    return ["status" => "success", "message" => __('Les informations ont été sauvegardées avec succès')];
  }

  /**
   * Remove the specified resource from storage.
   */
  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      $team = Team::find($id);
      try {
        $team->delete();
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

  public function get(Request $request) {
    $team = Team::find($request->id);
    $users = $team->users;

    return response()->json($users);
  }

}
