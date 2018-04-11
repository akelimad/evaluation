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

class HomeController extends Controller
{
    public static function  cutNum($num, $precision = 2){
        return floor($num).substr($num-floor($num),1,$precision+1);
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->parent == null){
            $mentor = $user;
        }else{
            $mentor = $user->parent;
        }

        $entretiens = $user->entretiens;
        $formations = Formation::where('user_id', Auth::user()->id)->get();
        $collaborateurs = Auth::user()->children;
        return view('index', compact('user', 'mentor', 'entretiens', 'formations', 'collaborateurs'));
    }


    public function dashboard()
    {
        $auth = Auth::user();
        $finished = Answer::where('user_id', '<>', NULL)->where('mentor_id', '<>', NULL)->groupBy('user_id', 'entretien_id', 'mentor_id')->get()->count();
        $inProgress = Entretien_user::count();
        $nbColls = $auth->children->count();
        $nbMentors = $auth->children->count() ;
        $taux  = $this->cutNum(($finished / $inProgress) * 100);

        return view('dashboard', compact('nbColls', 'nbMentors', 'finished', 'inProgress', 'taux'));
    }
}
