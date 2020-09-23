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

    if ($user->parent == null) {
      $mentor = $user;
    } else {
      $mentor = $user->parent;
    }

    $entretiens = $user->entretiens;
    $formations = Formation::where('user_id', Auth::user()->id)->get();
    $collaborateurs = Auth::user()->children;
    if (in_array('ROOT', Auth::user()->getRoles())) return redirect('crm');
    return view('index', compact('user', 'mentor', 'entretiens', 'formations', 'collaborateurs'));
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
      $query->where('name', 'MENTOR');
    })->where('society_id', $society->id)->count();

    $nbColls = User::whereHas('roles', function ($query) use ($society) {
      $query->where('name', 'COLLABORATEUR');
    })->where('society_id', $society->id)->count();

    if ($inProgress > 0) $taux = $this->cutNum(($finished / $inProgress) * 100);

    return view('dashboard', compact('nbColls', 'nbMentors', 'finished', 'inProgress', 'taux'));
  }
}
