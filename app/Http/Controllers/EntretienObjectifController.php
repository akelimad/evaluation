<?php

namespace App\Http\Controllers;

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
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $objectifs = Auth::user()->entretiensObjectifs()->paginate(10);
    return view('entretienObjectif.index', compact('objectifs'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function form(Request $request)
  {
    $id = $request->id;
    if (isset($id) && is_numeric($id)) {
      $objectif = EntretienObjectif::findOrFail($id);
    } else {
      $objectif = new EntretienObjectif();
    }
    $teams = Team::getAll()->get();

    return view('entretienObjectif.form', compact('objectif', 'teams'));
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
      if (isset($objectif['indicators']) && count($objectif['indicators']) <= 2) {
        return response()->json(['status' => 'error', 'message' => 'Veuillez ajouter au moins 3 indicateurs pour chaque objectif']);
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
    echo view('entretienObjectif.show', compact('objectif'));
    $content = ob_get_clean();
    return ['title' => "Détails de l'objectif", 'content' => $content];
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $objectif = EntretienObjectif::findOrFail($id)->delete();
    $sub = Objectif::where('entretienobjectif_id', $id)->delete();

    return ['status' => 'success', 'message' => "La suppression a bien été effectuée"];
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
