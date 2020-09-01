<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entretien;
use App\Salary;
use App\User;
use Auth;

class SalarieController extends Controller
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
  public function index($eid, $uid)
  {
    $e = Entretien::findOrFail($eid);
    $evaluations = Entretien::findEvaluations($e);
    $user = User::findOrFail($uid);
    if ($user->id == Auth::user()->id) {
      $salaries = Salary::where('user_id', $uid)->where('entretien_id', $eid)->paginate(10);
    } else {
      $salaries = Salary::where('mentor_id', $user->parent->id)->where('entretien_id', $eid)->paginate(10);
    }
    return view('salaries.index', compact('e', 'user', 'salaries', 'evaluations'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($eid, $uid, Request $request)
  {
    if ($request->method() == "POST") {
      return $this->store($request);
    }
    ob_start();
    $e = Entretien::findOrFail($eid);
    $user = User::findOrFail($uid);
    echo view('salaries.form', compact('e', 'user'));
    $content = ob_get_clean();
    return ['title' => 'Ajouter une prime', 'content' => $content];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if ($request->id == null) {
      $salary = new Salary();
    } else {
      $salary = Salary::findOrFail($request->id);
    }
    $salary->brut = $request->brut;
    $salary->prime = $request->prime;
    $salary->comment = $request->comment;
    $salary->user_id = $request->uid;
    $salary->mentor_id = Auth::user()->id;
    $salary->entretien_id = $request->eid;
    $salary->save();
    if ($salary->save()) {
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
  public function edit($eid, $uid, $sid, Request $request)
  {
    if ($request->method() == "POST") {
      return $this->store($request);
    }
    ob_start();
    $e = Entretien::findOrFail($eid);
    $user = User::findOrFail($uid);
    $s = Salary::findOrFail($sid);
    echo view('salaries.form', compact('e', 'user', 's'));
    $content = ob_get_clean();
    return ['title' => 'Modifier une prime', 'content' => $content];
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
