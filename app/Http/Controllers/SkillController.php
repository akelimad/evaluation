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
    $skills = $e->skills()->paginate(15);
    $user = $e->users()->where('entretien_user.user_id', $uid)->first();
    return view('skills.index', compact('e', 'evaluations', 'skills', 'user'));
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
    $rules = [
      'function_id' => 'required',
      'title' => 'required',
      'savoir' => 'required',
      'savoir_faire' => 'required',
      'savoir_etre' => 'required',
      'mobilite_pro' => 'required',
    ];
    $validator = \Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return ["status" => "danger", "message" => $validator->errors()->all()];
    }
    $skillData = $request->all();
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
    //dd($request->skills);
    foreach ($request->skills as $id => $value) {
      $user = User::findOrFail($request->user_id);
      $user->skills()->sync([$id =>
        [
          'mentor_id' => $request->mentor_id,
          'entretien_id' => $request->entretien_id,
          'objectif' => $value['objectif'] != "" ? $value['objectif'] : 0,
          'auto' => $value['auto'] != "" ? $value['auto'] : 0,
          'nplus1' => $value['nplus1'] != "" ? $value['nplus1'] : 0,
          'ecart' => $value['ecart'] != "" ? $value['ecart'] : 0,
        ]
      ], false);

    }
    return redirect()->back()->with("success_update", "Les informations ont été sauvegardées avec succès !");
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
}
