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
    $table->addColumn('type', 'Catégorie', function ($entity) {
      return $entity->getModele() ? $entity->getModele()->title : '---';
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
      'route' => ['name' => 'survey.show', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"width": "1000px"}',
      ],
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
      if ($survey->user_id != User::getOwner()->id) {
        abort(403);
      }
      $pageTitle = __("Modifier le questionnaire");
    } else {
      $survey = new Survey();
      $pageTitle = __("Ajouter un questionnaire");
    }
    $evaluations = Evaluation::all();

    return view('surveys.form', compact('survey', 'pageTitle', 'evaluations'));
  }

  public function store(Request $request)
  {
    $groups = $request->groups;
    $survey_id = $request->id;
    $selectedModelRef = $request->selectedModelRef;

    if (!empty($groups)) {
      $sumpGrpsPonderations = 0;
      foreach ($groups as $group) {
        $sumpGrpsPonderations += floatval($group['ponderation']);
        if (empty($group['questions'])) {
          return [
            "status" => "error",
            "message" => __("Veuillez ajouter des questions au thème (:theme)", ['theme' => $group['title']])
          ];
        } else {
          $sumpQstsPonderations = 0;
          foreach ($group['questions'] as $question) {
            $sumpQstsPonderations += floatval($question['ponderation']);
            if (in_array($question['type'], ['radio', 'checkbox', 'select']) && empty($question['choices'])) {
              return [
                "status" => "error",
                "message" => __("Veuillez ajouter des choix de réponse pour la question (:qst)", ['qst' => $question['title']])
              ];
            }
            if ($question['type'] == 'array' and (empty($question['options']['answers']) || empty($question['options']['subquestions']))) {
              return [
                "status" => "error",
                "message" => __("Veuillez ajouter des options de réponse et les sous questions pour la question (:qst)", ['qst' => $question['title']])
              ];
            }
          }
          if ($sumpQstsPonderations != 100 && $selectedModelRef == 'ENT') {
            return [
              "status" => "error",
              "message" => __("La somme (:sum)  de la pondération des questions doit être égale à 100 pour le thème (:theme) ", ['sum' => $sumpQstsPonderations, 'theme' => $group['title']])
            ];
          }
        }
      }
      if ($sumpGrpsPonderations != 100 && $selectedModelRef == 'ENT') {
        return [
          "status" => "error",
          "message" => __("La somme (:sum)  de la pondération des thèmes doit être égale à 100", ['sum' => $sumpGrpsPonderations])
        ];
      }
    } else {
      return ["status" => "error", "message" => __('Veuillez ajouter des thèmes au questionnaire')];
    }

    if ($survey_id > 0) {
      $survey = Survey::findOrFail($survey_id);
    } else {
      $survey = new Survey();
    }
    // save survey
    $survey->title = $request->title;
    $survey->description = $request->description;
    $survey->model_id = $request->model;
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
        $groupModel->ponderation = $selectedModelRef == 'ENT' ? $group['ponderation'] : null;
        $groupModel->save();

        // save questions
        if (!empty($group['questions'])) {
          foreach ($group['questions'] as $question) {
            $questionModel = Question::find($question['id']);
            if (!$questionModel) $questionModel = new Question();
            $questionModel->titre = $question['title'];
            $questionModel->ponderation = $selectedModelRef == 'ENT' ? $question['ponderation'] : null;
            $questionModel->type = $question['type'];
            $questionModel->parent_id =  0;
            $questionModel->groupe_id = $groupModel->id;
            $questionModel->options = json_encode($this->constructOptions($question['options']));
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
      return ["status" => "success", "message" => __('Les informations ont été sauvegardées avec succès')];
    } else {
      return ["status" => "warning", "message" => __('Une erreur est survenue, réessayez plus tard')];
    }
  }

  public function show($sid)
  {
    ob_start();
    $survey = Survey::findOrFail($sid);
    echo view('surveys.preview', compact('survey'));
    $content = ob_get_clean();
    return ['title' => __('Visualiser le questionnaire'), 'content' => $content];
  }

  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      try {
        $survey = Survey::find($id);
        if ($survey->user_id != User::getOwner()->id) {
          return response()->json([
            'status' => 'alert',
            'title' => __('Erreur survenue'),
            'content' => __("Vous n'avez pas les autorisations pour effectuer cette action"),
          ]);
        }
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
          'title' => __('Erreur survenue'),
          'content' => '<i class="fa fa-exclamation text-danger"></i> '. $e->getMessage(),
        ]);
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> '. __("La suppression a été effectuée avec succès"),
    ]);
  }

  public function constructOptions($options) {
    if (empty($options)) return [];
    if (!isset($options['answers']) || empty($options['answers']) || !isset($options['subquestions']) || empty($options['subquestions'])) return [];
    $array = [];
    foreach ($options['answers'] as $key => $answer) {
      $array['answers'][] = [
        'id' => $key+1,
        'title' => $answer['title']
      ];
    }
    foreach ($options['subquestions'] as $key => $subquestion) {
      $array['subquestions'][] = [
        'id' => $key+1,
        'title' => $subquestion['title']
      ];
    }

    return $array;
  }

}
