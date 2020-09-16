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
   * Display a listing of the resource for admin.
   *
   * @return \Illuminate\Http\Response
   */
  public function indexAdmin($oid)
  {
    $objectifs = Objectif::where('parent_id', 0)->where('entretienobjectif_id', $oid)->paginate(15);
    $count = Objectif::count();
    return view('objectifs.indexAdmin', compact('objectifs', 'count', 'oid'));
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
    return view('objectifs-user.index', [
      'evaluations' => $evaluations,
      'objectifsPersonnal' => $objectifsPersonnal,
      'objectifsTeam' => $objectifsTeam,
      'e' => $entretien,
      'user' => $user,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($oid)
  {
    ob_start();
    $objectif = ['' => ''];
    $objExtraFields = ['' => ''];
    echo view('objectifs.form', compact('oid', 'objectif', 'objExtraFields'));
    $content = ob_get_clean();
    return ['title' => 'Ajouter un objectif', 'content' => $content];
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function subObjectifForm($oid, $gid, $subObjId)
  {
    ob_start();
    $subObj = Objectif::find($subObjId);
    $objectifs = Objectif::where('parent_id', $subObjId)->get()->toArray();
    $objectifs = $objectifs + ['' => ''];
    echo view('objectifs.subObjectifForm', compact('oid', 'gid', 'subObj', 'objectifs'));
    $content = ob_get_clean();
    return ['title' => 'Gérer les sous objectifs', 'content' => $content];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $rules = [];
    $validator = Validator::make($request->all(), $rules);
    $messages = $validator->errors();
    $total = 0;
    $subObjArray = [];
    $saveSuccess = false;
    foreach ($request->objectifs as $obj) {
      if (empty($obj['ponderation'])) continue;
      $total += $obj['ponderation'];
    }
    if ($total != 100) {
      $messages->add('totaLFail', "Le total des pondération doit être égal à 100 !");
    }
    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }

    //dd($request->all());
    if (isset($request->form) && $request->form == 'storeSubObj') {
      if (!empty($request->objectifs)) {
        $subObj = Objectif::find($request->subObjId);
        $subObj->children()->delete();
        foreach ($request->objectifs as $key => $obj) {
          if (empty($obj['subTitle']) || empty($obj['ponderation'])) continue;
          $subObj = new Objectif();
          $subObj->title = $obj['subTitle'];
          $subObj->ponderation = $obj['ponderation'];
          $subObj->entretienobjectif_id = $request->oid;
          $subObj->parent_id = $request->subObjId;
          $subObj->save();
        }
        $saveSuccess = true;
      }
    }
    if (isset($request->form) && $request->form == 'storeSectionObj') {
      $extraFields = [];
      if (isset($request->objExtrFields) and !empty($request->objExtrFields)) {
        foreach ($request->objExtrFields as $key => $field) {
          if (empty($field['label'])) continue;
          $extraFields[$key] = $field;
        }
      }
      if ($request->gid) {
        $objectif = Objectif::findOrFail($request->gid);
        $objectif->children()->delete();
        $objectif->title = $request->title;
        $objectif->extra_fields = !empty($extraFields) ? json_encode($extraFields) : null;
        $objectif->save();
        foreach ($request->objectifs as $obj) {
          if (!isset($obj['subTitle']) || empty($obj['subTitle'])) continue;
          $subObj = new Objectif();
          $subObj->title = $obj['subTitle'];
          $subObj->ponderation = $obj['ponderation'];
          $subObj->parent_id = $objectif->id;
          $subObj->save();
        }
        $saveSuccess = true;
      } else {
        if ($request->objectifs) {
          $objectif = new Objectif();
          $objectif->title = $request->title;
          $objectif->entretienobjectif_id = $request->oid;
          $objectif->extra_fields = !empty($extraFields) ? json_encode($extraFields) : null;
          $objectif->save();
          foreach ($request->objectifs as $obj) {
            if (!isset($obj['subTitle']) || empty($obj['subTitle'])) continue;
            $subObj = new Objectif();
            $subObj->title = $obj['subTitle'];
            $subObj->ponderation = $obj['ponderation'];
            $subObj->parent_id = $objectif->id;
            $subObj->save();
          }
          $saveSuccess = true;
        }
      }
    }
    if ($saveSuccess) {
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
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($oid, $gid)
  {
    ob_start();
    $groupe = Objectif::findOrFail($gid);
    $objectif = $groupe->children;
    $objExtraFields = !empty($groupe->extra_fields) ? json_decode($groupe->extra_fields, true) : ['' => ''];
    echo view('objectifs.form', compact('objectif', 'oid', 'groupe', 'objExtraFields'));
    $content = ob_get_clean();
    return ['title' => 'Modifier un objectif', 'content' => $content];
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
   * @return \Illuminate\Http\Response
   */

  function cutNum($num, $precision = 2)
  {
    return floor($num) . substr($num - floor($num), 1, $precision + 1);
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


    // dump($user);
    // dump($mentor_id);
    // die();
    $user_extra_fields_data = isset($request->user_extra_fields_data) ? json_encode($request->user_extra_fields_data) : null;
    $mentor_extra_fields_data = isset($request->mentor_extra_fields_data) ? json_encode($request->mentor_extra_fields_data) : null;
    foreach ($request->objectifs as $key => $subObjectif) {
      // $sousTotal = 0;
      // $sumPonderation = 0;
      foreach ($subObjectif as $id => $array) {
        $userHasObjectif = Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id', $request->entretien_id)->where('userNote', '<>', 0)->first();
        $mentorHasObjectif = Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id', $request->entretien_id)->where('mentor_id', $mentor_id)->first();
        // dump($userHasObjectif);
        // dump($mentorHasObjectif);
        // die();
        if ($userHasObjectif) {
          Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id', $request->entretien_id)->where('userNote', '<>', 0)->update([
            'mentor_id' => $mentor_id,
            'mentorNote' => isset($array['mentorNote']) ? $array['mentorNote'] : null,
            'mentorAppreciation' => isset($array['mentorAppr']) ? $array['mentorAppr'] : null,
            'user_extra_fields_data' => $user_extra_fields_data,
            'mentor_extra_fields_data' => $mentor_extra_fields_data,
          ]);
        } else if ($mentorHasObjectif) {
          Objectif_user::where('objectif_id', $id)->where('user_id', $user->id)->where('entretien_id', $request->entretien_id)->where('mentor_id', $mentor_id)->update([
            'userNote' => isset($array['userNote']) ? $array['userNote'] : 0,
            'userAppreciation' => isset($array['userAppr']) ? $array['userAppr'] : "",
            'mentor_extra_fields_data' => $mentor_extra_fields_data,
          ]);
        } else {
          $user->objectifs()->attach([$id =>
            [
              'entretien_id' => $request->entretien_id,
              'userNote' => isset($array['userNote']) ? $array['userNote'] : 0,
              'realise' => isset($array['realise']) ? $array['realise'] : "",
              'ecart' => isset($array['ecart']) ? $array['ecart'] : "",
              'userAppreciation' => isset($array['userAppr']) ? $array['userAppr'] : "",
              'user_extra_fields_data' => $user_extra_fields_data,
              'objNplus1' => isset($array['objNplus1']) && $array['objNplus1'] == "on" ? 1 : 0,
              'mentor_id' => $mentor_id,
              'mentorNote' => isset($array['mentorNote']) ? $array['mentorNote'] : null,
              'mentorAppreciation' => isset($array['mentorAppr']) ? $array['mentorAppr'] : null,
              'mentor_extra_fields_data' => $mentor_extra_fields_data,
            ]
          ]);
        }
        // $sumPonderation += $array[3];
        // $sousTotal +=  ($array[0] * $array[3]);
      }
      // $objectif = Objectif::findOrFail($key);
      // $objectif->sousTotal = $this->cutNum($sousTotal/$sumPonderation, 2);
      // $objectif->save();
    }
    return redirect()->back();

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($oid, $gid)
  {
    $objectif = Objectif::findOrFail($gid);
    $objectif->children()->delete();
  }
}
