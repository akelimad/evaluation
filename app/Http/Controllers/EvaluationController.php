<?php

namespace App\Http\Controllers;

use App\Entretien_evaluation;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Entretien;
use App\Evaluation;
use App\Groupe;
use App\Survey;
use App\User;

class EvaluationController extends Controller
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
    public function index($eid, $uid)
    {
        $entretien = Entretien::findOrFail($eid);
        if (!$entretien->canBeFilledByUser($uid)) {
            return redirect()->route('home')->with("danger", "Désolé, vous avez dépassé la date limite");
        }
        $evaluations = Entretien::findEvaluations($entretien);
        $sid = Entretien_evaluation::getItemsId($eid, 1);
        $sid = isset($sid[0]) ? $sid[0] : 0;
        $survey = Survey::find($sid);
        $groupes = $survey->groupes;
        $user = User::find($uid);
        return view('evaluations.index', [
            'evaluations' => $evaluations, 
            'survey' => $survey, 
            'e'=> $entretien,  
            'groupes' => $groupes, 
            'user' => $user
        ]);
    }

}
