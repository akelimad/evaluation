<?php

namespace App\Http\Controllers;

use App\Fonction;
use App\Http\Service\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Entretien;
use App\Skill;
use App\Skill_user;
use App\User;
use Auth;

class SkillController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($e_id, $uid)
  {
    $e = Entretien::findOrFail($e_id);
    if (!$e->canBeFilledByUser($uid)) {
      return redirect()->route('home')->with("danger", Entretien::canBeFilledByUserMessage());
    }
    $evaluations = Entretien::findEvaluations($e);
    $user = $e->users()->where('entretien_user.user_id', $uid)->first();
    $skill = Skill::where('function_id', $user->function)->first();
    return view('campagne.anglets.skills.index', compact('e', 'evaluations', 'skill', 'user'));
  }

  public function getTable(Request $request) {
    $query = Skill::getAll()->orderBy('id', 'DESC');

    $table = new Table($request);
    $table->setPrimaryKey('id');
    $table->setBulkActions(true);
    $table->setDateFormat('d/m/Y H:i');

    $table->addColumn('title', 'Titre');
    $table->addColumn('function', 'Fonction', function ($entity) {
      $function = Fonction::find($entity->function_id);
      return $function ? $function->title : '---';
    });
    $table->addColumn('description', 'Description', function ($entity) {
      return str_limit($entity->description, 30);
    });
    $table->addColumn('created_at', 'Créée le');

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'skill.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"skillForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);
    $table->addAction('delete', [
      'icon' => 'fa fa-trash',
      'label' => 'Supprimer',
      'callback' => 'chmSkill.delete',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function indexAdmin()
  {
    return view('skills.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function form(Request $request)
  {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    $id = $request->id;
    if ($id > 0) {
      $skill = Skill::find($id);
      $title = "Modifier la fiche métier";
    } else {
      $skill = new Skill();
      $title = "Ajouter une fiche métier";
    }
    ob_start();
    echo view('skills.form', compact('skill'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $skillData = $request->all();
    $rules = [
      'function_id' => 'required',
      'title' => 'required',
    ];
    $validator = \Validator::make($skillData, $rules);
    $functionAlreayHasSkills = Skill::where('function_id', $skillData['function_id']);//->get()->count() > 0;
    if ($skillData['id'] > 0) {
      $functionAlreayHasSkills->where('id', '<>', $skillData['id']);
    }
    $functionAlreayHasSkills = $functionAlreayHasSkills->get()->count() > 0;
    if ($functionAlreayHasSkills) {
      $validator->getMessageBag()->add('funct_exists', "Cett fonction a déjà une fiche métier");
    }
    // validate ponderation
    foreach ($skillData['types'] as $tKey => $type) {
      if (empty($type['skills'])) continue;
      $sum = 0;
      foreach ($type['skills'] as $key => $skill) {
        $sum += $skill['ponderation'];
      }
      if ($sum != 100) {
        $validator->getMessageBag()->add('type-'.$tKey, "La somme de la pondération doit être égale à 100 pour le type de compétences : ". $type['title']);
      }
    }
    $messages = $validator->errors();
    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }
    $skillData['skills_json'] = json_encode($skillData['types']);
    $skillData['user_id'] = User::getOwner()->id;
    if ($skillData['id'] > 0) {
      $skill = Skill::find($skillData['id']);
      $skill->update($skillData);
    } else {
      $skill = Skill::create($skillData);
    }

    if ($skill->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function updateUserSkills(Request $request)
  {
    $data = $request->except(['_token']);
    $data['user_notes'] = json_encode($data['user_notes']);
    $data['mentor_notes'] = json_encode(isset($data['mentor_notes']) ? $data['mentor_notes'] : []);

    $model = $skill_user = Skill_user::where('skill_id', $data['skill_id'])
      ->where('entretien_id', $data['entretien_id'])
      ->where('user_id', $data['user_id']);

    $skill_user = $model->first();
    if (is_null($skill_user)) {
      $skill_user = Skill_user::create($data);
    } else {
      $model->update($data);
    }

    return redirect()->back()->with("note_update", "Les informations ont été sauvegardées avec succès !");
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request)
  {
    if (empty($request->ids)) return;

    foreach($request->ids as $id) {
      $skill = Skill::find($id);
      try {
        $skill->delete();
      } catch (\Exception $e) {
        return ["status" => "danger", "message" => "Une erreur est survenue, réessayez plus tard."];
      }
    }

    return response()->json([
      'status' => 'alert',
      'title' => 'Confirmation',
      'content' => '<i class="fa fa-check-circle text-green"></i> La suppression a été effectuée avec succès',
    ]);
  }

}
