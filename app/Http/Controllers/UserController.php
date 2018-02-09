<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class UserController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('users.index', compact('user'));
    }

    public function profile(){
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }
}
