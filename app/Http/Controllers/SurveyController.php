<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Survey;
use App\Evaluation;

use App\Http\Requests;

class SurveyController extends Controller
{

    public function index()
    {
        $surveys = Survey::getAll()->paginate(10);
        return view('surveys.index', compact('surveys'));
    }

    public function form(Request $request)
    {
        $id = $request->id;
        if (isset($id) && is_numeric($id)) {
            $survey = Survey::findOrFail($id);
            $pageTitle = "Modifier le questionnaire";
        } else {
            $survey = new Survey();
            $pageTitle = "Ajouter un questionnaire";
        }
        $evaluations = Evaluation::all();

        return view('surveys.form', compact('survey', 'pageTitle', 'evaluations'));
    }

    public function store(Request $request)
    {
        if($request->id == null ){
            $survey = new Survey();
        }else{
            $survey = Survey::findOrFail($request->id);
        }
        $survey->title = $request->title;
        $survey->description = $request->description;
        $survey->type = $request->type;
        $survey->user_id = User::getOwner()->id;
        $survey->evaluation_id = $request->evaluation_id;
        $survey->save();
        if($survey->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }
    }

    public function show($sid)
    {
        ob_start();
        $survey = Survey::findOrFail($sid);
        $groupes = $survey->groupes;
        $incompleteSurvey = Survey::icompleteSurvey($sid);
        echo view('surveys.preview', compact('groupes', 'sid', 'incompleteSurvey'));
        $content = ob_get_clean();
        return ['title' => 'Visualiser le questionnaire', 'content' => $content];
    }    

    public function destroy($sid)
    {
        $survey = Survey::findOrFail($sid);
        if($survey->groupes()->count() > 0) {
            foreach ($survey->groupes()->get() as $group) {
                $group->delete();
                $group->questions()->delete();
            }
        }
        $survey->delete();
    }
}
