<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\EntretienObjectif;
use App\Objectif;
use Auth;

class EntretienObjectifController extends Controller
{
  public function getTable(Request $request) {
    $table = new Table($request);
    $query = EntretienObjectif::getAll();

    $table->setPrimaryKey('id');
    $table->setBulkActions(true);
    $table->addColumn('title', 'Titre', function ($entity) {
      return $entity->title ? str_limit($entity->title, 30) : '';
    });
    $table->addColumn('type', 'Type', function ($entity) {
      return $entity->getType();
    });
    $table->addColumn('team', 'Equipe', function ($entity) {
      $team = Team::find($entity->team);
      return $team ? $team->name : '---';
    });
    $table->addColumn('deadline', 'Echénace', function ($entity) {
      return $entity->deadline != null ? date('d/m/Y', strtotime($entity->deadline)) : '---';
    });
    $table->addColumn('created_at', 'Créé le');

    // define table actions
    $table->addAction('show', [
      'icon' => 'fa fa-eye',
      'label' => 'Visualiser',
      'route' => ['name' => 'objectif.show', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'objectif.form', 'args' => ['id' => '[id]']],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'Objectif.delete',
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
    return view('objectifs.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function form(Request $request)
  {
    $id = $request->id;
    if ($id > 0) {
      $objectif = EntretienObjectif::findOrFail($id);
    } else {
      $objectif = new EntretienObjectif();
    }
    $teams = Team::getAll()->get();

    return view('objectifs.form', compact('objectif', 'teams'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $objectifs = $request->objectifs;
    $user_id = User::getOwner()->id;

    foreach ($objectifs as $objectif) {
      if (isset($objectif['indicators']) && count($objectif['indicators']) < 1) {
        return response()->json(['status' => 'error', 'message' => 'Veuillez ajouter au moins 1 indicateur pour chaque objectif']);
      }
    }

    if (!empty($objectifs)) {
      foreach ($objectifs as $objectif) {
        $id = $objectif['id'];
        $entretienObjectif = EntretienObjectif::find($id);
        if (!$entretienObjectif) {
          $entretienObjectif = new EntretienObjectif();
        }
        $entretienObjectif->type = $objectif['type'];
        $entretienObjectif->team = $objectif['type'] == 'Equipe' ? $objectif['team'] : null;
        $entretienObjectif->title = $objectif['title'];
        $entretienObjectif->description = $objectif['description'];
        $entretienObjectif->deadline = date("Y-m-d", strtotime($objectif['deadline']));
        $entretienObjectif->setIndicators($this->validateIndicators($objectif['indicators']));
        $entretienObjectif->user_id = $user_id;
        $entretienObjectif->save();
      }
      return response()->json(['status' => 'success', 'message' => 'Les informations ont été sauvegardées avec succès.']);
    } else {
      return response()->json(['status' => 'error', 'message' => 'Une erreur est survenue, réessayez plus tard.']);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    ob_start();
    $objectif = EntretienObjectif::find($id);
    echo view('objectifs.show', compact('objectif'));
    $content = ob_get_clean();
    return ['title' => "Détails de l'objectif", 'content' => $content];
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
      $objectif = EntretienObjectif::find($id);
      try {
        $objectif->delete();
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

  public function validateIndicators($indicators) {
    $newArray = [];
    if (empty($indicators)) return $newArray;
    foreach ($indicators as $key => $indicator) {
      if (
        !isset($indicator['title']) || empty($indicator['title']) ||
        !isset($indicator['fixed']) || empty($indicator['fixed']) ||
        !isset($indicator['ponderation']) || empty($indicator['ponderation'])
      ) {
        continue;
      }
      $indicator['id'] = $key + 1;
      $newArray[] = $indicator;
    }

    return $newArray;
  }
}
