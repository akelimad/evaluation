<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\User;
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

  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Setting::where('editable', 1)->orderBy('updated_at', 'DESC');

    $table->setPrimaryKey('id');
    $table->setDateFormat('d/m/Y H:i');
    $table->setBulkActions(true);

    $table->addColumn('value', 'Valeur');
    $table->addColumn('description', 'Description');

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'general.settings.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"settingForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);

    // render the table
    return $table->render($query);
  }
  
  public function general()
  {
    $settings = json_decode(Auth::user()->settings);
    $active = "gen";
    return view('settings.index', compact('settings', 'active'));
  }

  public function form(Request $request)
  {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    $id = $request->id;
    ob_start();
    if (isset($id) && is_numeric($id)) {
      $setting = Setting::findOrFail($id);
      $title = "Modifier le paramètre ";
    } else {
      $setting = new Setting();
      $title = "Ajouter un paramètre ";
    }
    echo view('settings.form', compact('setting'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request)
  {
    $id = $request->id;
    $setting = new Setting();
    if ($id > 0) {
      $setting = Setting::find($id);
    }
    $setting->value = $request->value;
    $setting->user_id = User::getOwner()->id;
    $setting->save();

    if ($setting->save()) {
      return ["status" => "success", "message" => __("Les informations ont été sauvegardées avec succès")];
    } else {
      return ["status" => "warning", "message" => __("Une erreur est survenue, réessayez plus tard")];
    }
  }
  
}
