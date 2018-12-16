<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;

use App\Http\Requests;

class SurveyController extends Controller
{

    public function index()
    {
        $surveys = Survey::getAll()->paginate(10);
        return view('surveys.index', compact('surveys'));
    }

    public function create()
    {
        ob_start();
        echo view('surveys.form');
        $content = ob_get_clean();
        return ['title' => 'Ajouter un questionnaire', 'content' => $content];
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
            return ['title' => 'Visualiser un questionnaire', 'content' => $content];
        // }
    }    

    public function edit($id)
    {
        ob_start();
        $s = Survey::find($id);
        echo view('surveys.form', compact('s'));
        $content = ob_get_clean();
        return ['title' => 'Modifier un questionnaire', 'content' => $content];
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($sid)
    {
        $survey = Survey::findOrFail($sid);
        $survey->delete();
        return redirect('config/surveys');
    }
}
