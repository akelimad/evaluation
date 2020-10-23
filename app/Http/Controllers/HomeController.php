<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Entretien;
use App\Answer;
use App\Formation;
use App\User;
use App\Entretien_user;
use App\Survey;

class HomeController extends Controller
{
  public static function  cutNum($num, $precision = 2)
  {
    return floor($num) . substr($num - floor($num), 1, $precision + 1);
  }

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
   * Show the application index.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = Auth::user();
    $mentor = is_null($user->parent) ? $user : $user->parent;
    $nMoins2Entretiens = [];
    $authUserChildrensId = $user->children->pluck('id')->toArray();
    if (!empty($authUserChildrensId)) {
      $nMoins2Entretiens = Entretien_user::whereIn('mentor_id', $authUserChildrensId)->get();
    }
    $managerCollsEntretiens = Entretien_user::select('entretien_user.*')
      ->join('entretiens as e', 'e.id', '=', 'entretien_user.entretien_id')
      ->join('models as m', 'm.id', '=', 'e.model_id')
      ->where('m.ref', 'ENT')
      ->where('mentor_id', Auth::user()->id)->paginate(10);

    if (in_array('ROOT', Auth::user()->getRoles())) return redirect('companies');
    return view('index', compact('user', 'mentor', 'managerCollsEntretiens', 'nMoins2Entretiens'));
  }


  public function dashboard()
  {
    $society = User::getOwner();
    $taux = 0;
    $entretien_user_query = \DB::table('entretiens as e')
      ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
      ->select('e.*', 'e.id as entretienId')
      ->where('e.user_id', $society->id);
    $inProgress = $entretien_user_query
      ->groupBy('id')
      ->where('mentor_submitted', '<>', 2)->where('user_submitted', '<>', 2)
      ->get();
    $inProgress = count($inProgress);

    $entretien_user_query->where('mentor_submitted', 2)->where('user_submitted', 2);
    $finished = count($entretien_user_query->get());

    $nbMentors = User::whereHas('roles', function ($query) use ($society) {
      $query->where('name', 'MANAGER');
    })->where('society_id', $society->id)->count();

    $nbColls = User::whereHas('roles', function ($query) use ($society) {
      $query->where('name', 'COLLABORATEUR');
    })->where('society_id', $society->id)->count();

    if ($inProgress > 0) $taux = $this->cutNum(($finished / $inProgress) * 100);

    return view('dashboard', compact('nbColls', 'nbMentors', 'finished', 'inProgress', 'taux'));
  }
}
