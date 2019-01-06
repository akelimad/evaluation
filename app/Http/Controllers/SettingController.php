<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Setting;
use Auth;

class SettingController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }
  
  public function index()
  {
    $settings = json_decode(Auth::user()->settings);
    $active = "set";
    return view('setting.index', compact('settings', 'active'));
  }
  public function services()
  {
    $settings = Setting::paginate(10);
    return view('setting.index', compact('settings'));
  }
  public function functions()
  {
    $settings = Setting::paginate(10);
    return view('setting.index', compact('settings'));
  }

  // public function edit($id)
  // {
  //   ob_start();
  //   $setting = Setting::find($id);
  //   echo view('setting.form', compact('setting'));
  //   $content = ob_get_clean();
  //   return ['title' => 'Modifier les options', 'content' => $content];
  // }

  public function store(Request $request)
  {
    $user = Auth::user();
    $settings =  $request->settings;
    $user->settings = isset($settings) ? json_encode($settings) : '';
    $user->save();
    if($user->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }
  }
}
