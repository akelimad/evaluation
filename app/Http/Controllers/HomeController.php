<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Entretien;
use App\User;

class HomeController extends Controller
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
        $collaborateurs = Auth::user()->children;
        //dd($collaborateurs);
        return view('index', compact('user', 'mentor', 'entretiens', 'collaborateurs'));
    }


    public function home()
    {
        return view('home');
    }
}
