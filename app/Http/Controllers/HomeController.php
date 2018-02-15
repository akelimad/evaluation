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
        $entretiens = $user->entretiens;
        $collaborateurs = User::with('entretiens')->where('user_id', '=', $user->id)->get();
        //dd($collaborateurs);
        return view('index', compact('user', 'entretiens', 'collaborateurs'));
    }


    public function home()
    {
        return view('home');
    }
}
