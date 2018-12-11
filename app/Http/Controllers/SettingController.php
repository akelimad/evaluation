<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Setting;
use Auth;

class SettingController extends Controller
{
  
  public function index()
  {
    $settings = Setting::paginate(10);
    return view('setting.index', compact('settings'));
  }

  public function edit($id)
  {
    ob_start();
    $setting = Setting::find($id);
    echo view('setting.form', compact('setting'));
    $content = ob_get_clean();
    return ['title' => 'Modifier les options', 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->id;
    $setting = Setting::find($id);
    $setting->value = $request->value;
    $setting->user_id = Auth::user()->id;
    $setting->save();
    if($setting->save()) {
      return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
    } else {
      return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
    }
  }
}
