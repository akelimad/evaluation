<?php

namespace App\Http\Controllers;

use App\Groupe;
use App\Http\Service\Table;
use App\Question;
use App\User;
use Illuminate\Http\Request;
use App\Survey;
use App\Evaluation;

use App\Http\Requests;

class SurveyController extends Controller
{

  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Survey::getAll()->orderBy('id', 'DESC');

    $table->setPrimaryKey('id');
    $table->setDateFormat('d/m/Y H:i');
    $table->setBulkActions(true);

    $table->addColumn('title', 'Titre', function ($entity) {
      return $entity->title;
    });
    $table->addColumn('type', 'Type', function ($entity) {
      return !empty($entity->model) ? $entity->model : '---';
    });
    $table->addColumn('section', 'Section', function ($entity) {
      $model = Evaluation::find($entity->evaluation_id);
      return $model ? $model->title : '---';
    });
    $table->addColumn('created_at', 'Créé le');

    // define table actions
    $table->addAction('show', [
      'icon' => 'fa fa-eye',
      'label' => 'Visualiser',
      'callback' => 'chmSurvey.show({id: [id]})',
      'bulk_action' => false,
    ]);
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'survey.form', 'args' => ['id' => '[id]']],
      'bulk_action' => false,
    ]);
    // define table actions
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'chmSurvey.delete',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }

  public function index()
  {
    return view('surveys.index');
  }

  public function form(Request $request)
  {
    $id = $request->id;
    if ($id > 0) {
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

    if (!empty($groups)) {
      foreach ($groups as $group) {
        if (empty($group['questions'])) {
          return ["status" => "error", "message" => 'Veuillez ajouter des questions au block : ' . $group['title']];
        } else {
          foreach ($group['questions'] as $question) {
            if (in_array($question['type'], ['radio', 'checkbox', 'select']) && empty($question['choices'])) {
              return ["status" => "error", "message" => 'Veuillez ajouter des choix de réponse pour la question : '. $question['title']];
            }
          }
        }
      }
    } else {
      return ["status" => "error", "message" => 'Veuillez ajouter des blocks au questionnaire'];
    }

    if ($survey_id > 0) {
      $survey = Survey::findOrFail($survey_id);
    } else {
      $survey = new Survey();
    }
    // save survey
    $survey->title = $request->title;
    $survey->description = $request->description;
    $survey->model = $request->model;
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
                $questionChild->groupe_id = $questionModel->id;
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
    echo view('surveys.preview', compact('survey'));
    $content = ob_get_clean();
    return ['title' => 'Visualiser le questionnaire', 'content' => $content];
  }

  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      try {
        $survey = Survey::find($id);
        if ($survey->groupes()->count() > 0) {
          foreach ($survey->groupes()->get() as $group) {
            $group->delete();
            $group->questions()->delete();
          }
        }
        $survey->delete();
      } catch (\Exception $e) {
        return response()->json([
          'status' => 'alert',
          'title' => 'Erreur survenue',
          'content' => '<i class="fa fa-exclamation text-danger"></i> '. $e->getMessage(),
        ]);
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> La suppression a été effectuée avec succès',
    ]);
  }
}
