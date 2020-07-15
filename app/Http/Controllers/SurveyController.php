<?php

namespace App\Http\Controllers;

use App\Groupe;
use App\Question;
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
    $groups = $request->groups;
    $survey_id = $request->id;

    if (empty($survey_id)) {
      $survey = new Survey();
    } else {
      $survey = Survey::findOrFail($survey_id);
    }
    // save survey
    $survey->title = $request->title;
    $survey->description = $request->description;
    $survey->evaluation_id = $request->section;
    $survey->user_id = User::getOwner()->id;
    $survey->save();

    // save groups
    if (!empty($groups)) {
      foreach ($groups as $group) {
        $groupModel = Groupe::find($group['id']);
        if (!$groupModel) $groupModel = new Groupe();
        $groupModel->name = $group['title'];
        $groupModel->survey_id = $survey->id;
        $groupModel->save();

        // save questions
        if (!empty($group['questions'])) {
          foreach ($group['questions'] as $question) {
            $questionModel = Question::find($question['id']);
            if (!$questionModel) $questionModel = new Question();
            $questionModel->titre = $question['title'];
            $questionModel->type = $question['type'];
            $questionModel->parent_id =  0;
            $questionModel->groupe_id = $groupModel->id;
            $questionModel->save();

            // save choices if exists
            if (!empty($question['choices'])) {
              $questionModel->children()->delete();
              foreach ($question['choices'] as $choice) {
                if (empty($choice['title'])) continue;
                $questionChild = new Question();
                $questionChild->titre = $choice['title'];
                $questionChild->parent_id = $questionModel->id;
                $questionChild->groupe_id = $groupModel->id;
                $questionChild->save();
              }
            }
          }
        }
      }
    }

    if ($survey->save()) {
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
    if ($survey->groupes()->count() > 0) {
      foreach ($survey->groupes()->get() as $group) {
        $group->delete();
        $group->questions()->delete();
      }
    }
    $survey->delete();
  }
}
