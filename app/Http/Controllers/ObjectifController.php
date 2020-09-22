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
      foreach ($request->objectifs as $objectid_id => $objectifData) {
        $indicators = isset($objectifData['indicators']) ? $objectifData['indicators'] : [];
        $user_comment = isset($objectifData['user_comment']) ? $objectifData['user_comment'] : null;
        $mentor_comment = isset($objectifData['mentor_comment']) ? $objectifData['mentor_comment'] : null;
        //dd($mentor_comment);
        $object = Objectif_user::where('objectif_id', $objectid_id)->where('user_id', $user->id)->where('entretien_id', $request->entretien_id);
        $objectif_user = $object->first();

        //dd($indicators);

        $data['objectif_id'] = $objectid_id;
        $data['user_id'] = $user_id;
        $data['entretien_id'] = $request->entretien_id;
        $data['mentor_id'] = $mentor_id;
        $data['indicators_data'] = json_encode([$indicators]);
        $data['user_comment'] = empty($user_comment) && $objectif_user ? $objectif_user->user_comment : $user_comment;
        $data['mentor_comment'] = empty($mentor_comment) && $objectif_user ? $objectif_user->mentor_comment : $mentor_comment;

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
