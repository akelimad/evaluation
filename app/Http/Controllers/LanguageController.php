<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
  public function index()
  {
    return view('languages.index');
  }

  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Language::orderBy('id', 'DESC');

    $table->setPrimaryKey('id');
    $table->setDateFormat('d/m/Y H:i');
    $table->setBulkActions(true);

    $table->addColumn('name', 'Langue');
    $table->addColumn('iso_code', 'Code ISO');

    // define table actions
    $table->addAction('edit', [
      'icon' => 'fa fa-pencil',
      'label' => 'Modifier',
      'route' => ['name' => 'languages.form', 'args' => ['id' => '[id]']],
      'attrs' => [
        'chm-modal'=> '',
        'chm-modal-options'=> '{"form":{"attributes":{"id":"languageForm", "target-table":"[chm-table]"}}}',
      ],
      'bulk_action' => false,
    ]);

    // render the table
    return $table->render($query);
  }

  public function form(Request $request) {
    if ($request->method() == 'POST') {
      return $this->store($request);
    }
    $id = $request->id;
    ob_start();
    if (isset($id) && is_numeric($id)) {
      $language = Language::findOrFail($id);
      $title = "Modifier la langue";
    } else {
      $language = new Language();
      $title = "Ajouter une langue";
    }
    echo view('languages.form', compact('language'));
    $content = ob_get_clean();
    return ['title' => $title, 'content' => $content];
  }

  public function store(Request $request) {
    $id = $request->id;
    if ($id > 0) {
      $language = Language::findOrFail($id);
    } else {
      $language = new Language();
    }
    $language->name = $request->name;
    $language->iso_code = $request->iso_code;
    $language->direction = $request->direction;
    $language->save();
    if ($language->save()) {
      return ["status" => "success", "message" => __("Les informations ont été sauvegardées avec succès")];
    } else {
      return ["status" => "warning", "message" => __("Une erreur est survenue, réessayez plus tard")];
    }
  }

}
