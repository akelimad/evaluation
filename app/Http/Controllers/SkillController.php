<?php

namespace App\Http\Controllers;

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
    $evaluations = Entretien::findEvaluations($e);
    $user = $e->users()->where('entretien_user.user_id', $uid)->first();
    $skill = Skill::where('function_id', $user->function)->first();
    return view('skills.index', compact('e', 'evaluations', 'skill', 'user'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function indexAdmin()
  {
    $count = 1;
    $skills = Skill::getAll()->paginate(10);
    return view('skills.indexAdmin', compact('skills', 'count'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    $skill = new Skill();
    ob_start();
    echo view('skills.form', compact('skill'));
    $content = ob_get_clean();
    return ['title' => 'Ajouter une fiche métier', 'content' => $content];
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
      'savoir' => 'required',
      'savoir_faire' => 'required',
      'savoir_etre' => 'required',
      'mobilite_pro' => 'required',
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
    $messages = $validator->errors();
    if (count($messages) > 0) {
      return ["status" => "danger", "message" => $messages];
    }

    $skillData['user_id'] = User::getOwner()->id;
    $skillData['savoir'] = json_encode($this->convertStringToArray($skillData['savoir']));
    $skillData['savoir_faire'] = json_encode($this->convertStringToArray($skillData['savoir_faire']));
    $skillData['savoir_etre'] = json_encode($this->convertStringToArray($skillData['savoir_etre']));
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
  public function edit($id, Request $request)
  {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    ob_start();
    $skill = Skill::findOrFail($id);
    echo view('skills.form', compact('skill'));
    $content = ob_get_clean();
    return ['title' => 'Modifier la fiche métier', 'content' => $content];
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
  public function destroy(Request $request)
  {
    $skill = Skill::findOrFail($request['params']['id']);
    $skill->delete();
    return ["status" => "success", "message" => "La fiche métier a été supprimée avec succès !"];
  }

  public function convertStringToArray($str, $delimeter = ',') {
    $values = explode($delimeter, $str);
    $newarray = [];
    if (!empty($values)) {
      foreach ($values as $key => $value) {
        $newarray[$key+1] = $value;
      }
    }

    return $newarray;
  }

}
