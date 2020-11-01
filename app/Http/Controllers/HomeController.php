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
use App\Helpers\Base;

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
    $total = Entretien::getAll()->count();

    $nbMentors = User::countUsersByRole('MANAGER');
    $nbRHs = User::countUsersByRole('RH');
    $nbColls = User::countUsersByRole('COLLABORATEUR');
    $nbrAdmins = User::countUsersByRole('ADMIN');

    $countCurrentCampaigns = Entretien::where('status', Entretien::CURRENT_STATUS)->where('user_id', $society->id)->count();
    $countFinishedCampaigns = Entretien::where('status', Entretien::FINISHED_STATUS)->where('user_id', $society->id)->count();
    $countExpiredCampaigns = Entretien::where('status', Entretien::EXPIRED_STATUS)->where('user_id', $society->id)->count();

    if ($total > 0) $taux = Base::cutNum((100 * $countFinishedCampaigns) / $total);

    return view('dashboard', compact('nbColls', 'nbMentors', 'nbRHs', 'nbrAdmins', 'countCurrentCampaigns', 'countFinishedCampaigns', 'countExpiredCampaigns', 'taux'));
  }
}
