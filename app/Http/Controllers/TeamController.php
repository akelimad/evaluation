<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class TeamController extends Controller
{
  public function index()
  {
    $teams = Team::getAll()->paginate(10);
    return view('teams.index', compact('teams'));
  }

  public function form(Request $request)
  {
    ob_start();
    $id = $request->id;
    if (isset($id) && is_numeric($id)) {
      $team = Team::findOrFail($id);
      $title = "Modifier l'équipe";
    } else {
      $team = new Team();
      $title = "Ajouter une équipe";
    }

    echo view('teams.form', compact('team', 'pageTitle'));
    $content = ob_get_clean();

    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request)
  {
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

    return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
  }

  /**
   * Remove the specified resource from storage.
   */
  public function delete($id)
  {
    $team = Team::find($id);
    $team->delete();
    return ["status" => "success", "message" => "L'équipe a été supprimée avec succès !"];
  }
}
