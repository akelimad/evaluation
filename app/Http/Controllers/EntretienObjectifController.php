<?php

namespace App\Http\Controllers;

use App\Team;
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
    $isEditMode = $request->isEditMode;
    $objectifs = $request->objectifs;
    if ($isEditMode) {
      $id = $objectifs[0]['id'];
      $objectif = EntretienObjectif::findOrFail($id);
    } else {
      if (!empty($objectifs)) {
        foreach ($objectifs as $objectif) {}
      }
      $objectif = new EntretienObjectif();
    }
    $objectif->title = $request->title;
    $objectif->description = $request->description;
    $objectif->user_id = Auth::user()->id;
    $objectif->save();
    if ($objectif->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
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
    $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $id)->paginate(10);
    echo view('entretienObjectif.show', compact('objectifs'));
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
  }
}
