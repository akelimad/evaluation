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
        ob_start();
        if(isset($id) && is_numeric($id)) {
            $survey = Survey::find($id);
            $title = "Modifier le questionnaire";
        } else {
            $survey = new Survey();
            $title = "Ajouter un questionnaire";
        }
        $evaluations = Evaluation::all();
        echo view('surveys.form', compact('survey', 'evaluations'));
        $content = ob_get_clean();
        return ['title' => $title, 'content' => $content];
    }

    public function store(Request $request)
    {
        if($request->id == null ){
            $survey = new Survey();
        }else{
            $survey = Survey::find($request->id);
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
        $survey = Survey::find($sid);
        $groupes = $survey->groupes;
        $incompleteSurvey = Survey::icompleteSurvey($sid);
        echo view('surveys.preview', compact('groupes', 'sid', 'incompleteSurvey'));
        $content = ob_get_clean();
        // if($incompleteSurvey == true){
        //     return ['title' => 'Visualiser un questionnaire', 'content' => "<i class='fa fa-info-circle'></i>le questionnaire est incomplet, vous ne pouvez pas le vésualiser. veuillez attribuer les choix pour les questions multichoix."];
        // }else{
            return ['title' => 'Visualiser le questionnaire', 'content' => $content];
        // }
    }    

    public function destroy($sid)
    {
        $survey = Survey::findOrFail($sid);
        $survey->delete();
        return redirect('config/surveys');
    }
}
