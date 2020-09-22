<?php

namespace App\Http\Controllers;

use App\Entretien_evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Entretien;
use App\Objectif;
use Auth;
use App\EntretienObjectif;
use App\Objectif_user;
use App\User;

class ObjectifController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource for user
   *
   * @return \Illuminate\Http\Response
   */
  public function index($eid, $uid)
  {
    $entretien = Entretien::findOrFail($eid);
    if (!$entretien->canBeFilledByUser($uid)) {
      return redirect()->route('home')->with("danger", "Désolé, vous avez dépassé la date limite");
    }
    $evaluations = Entretien::findEvaluations($entretien);

    $itemsId = Entretien_evaluation::getItemsId($eid, 9);
    $objectifsPersonnal = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Personnel')->get();
    $objectifsTeam = EntretienObjectif::whereIn('id', $itemsId)->where('type', 'Equipe')->get();


    $user = $entretien->users()->where('entretien_user.user_id', $uid)->first();
    return view('campagne.anglets.objectifs.index', [
      'evaluations' => $evaluations,
      'objectifsPersonnal' => $objectifsPersonnal,
      'objectifsTeam' => $objectifsTeam,
      'e' => $entretien,
      'user' => $user,
    ]);
  }


  public function updateNoteObjectifs(Request $request)
  {
    $auth = Auth::user();
    $user_id = $request->user_id;
    if ($auth->id == $user_id) {
      $user = $auth;
      $mentor_id = $auth->parent->id;
    } else {
      $user = User::findOrFail($user_id);
      $mentor_id = $auth->id;
    }

    if (!empty($request->objectifs)) {
      foreach ($request->objectifs as $objectid_id => $indicatorData) {
        $object = Objectif_user::where('objectif_id', $objectid_id)->where('user_id', $user->id)->where('entretien_id', $request->entretien_id);
        $objectif_user = $object->first();

        $data['objectif_id'] = $objectid_id;
        $data['user_id'] = $user_id;
        $data['entretien_id'] = $request->entretien_id;
        $data['mentor_id'] = $mentor_id;
        $data['indicators_data'] = json_encode([$indicatorData]);

        if (!$objectif_user) {
          $objectif_user = new Objectif_user();
          $objectif_user->create($data);
        } else {
          $object->update($data);
        }
      }
    }

    return redirect()->back();
  }

}
