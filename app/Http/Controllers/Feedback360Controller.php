<?php

namespace App\Http\Controllers;

use App\Entretien;
use App\Entretien_evaluation;
use App\Survey;
use App\User;
use Illuminate\Http\Request;

class Feedback360Controller extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($eid, $uid, $evaluator_id)
  {
    $entretien = Entretien::findOrFail($eid);
    if (!$entretien->canBeFilledByUser($uid)) {
      return redirect()->route('home')->with("danger", Entretien::canBeFilledByUserMessage());
    }
    $evaluations = Entretien::findEvaluations($entretien);
    $sid = Entretien_evaluation::getItemsId($eid, 1);
    $sid = isset($sid[0]) ? $sid[0] : 0;
    $survey = Survey::find($sid);
    $groupes = $survey->groupes;
    $user = User::find($uid);
    return view('feedback360.index', [
      'evaluations' => $evaluations,
      'survey' => $survey,
      'e'=> $entretien,
      'groupes' => $groupes,
      'user' => $user,
      'evaluator_id' => $evaluator_id
    ]);
  }
}
