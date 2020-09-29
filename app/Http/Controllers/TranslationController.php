<?php

namespace App\Http\Controllers;

use App\Http\Service\Table;
use App\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
  public function getTable(Request $request) {
    $table = new Table($request);
    $query = Translation::groupBy('key');

    if ($q = $request->get('q', false)) {
      $query->where('key', 'LIKE', "%$q%")->orWhere('value', 'LIKE', "%$q%");
    }

    $table->setPrimaryKey('id');
    $table->setDateFormat('d/m/Y H:i');
    $table->setBulkActions(true);

    $table->addColumn('key', 'Clé', function ($entity) {
      return $entity->key;
    });
    foreach (['fr', 'en'] as $locale) {
      $table->addColumn($locale, ucfirst($locale), function ($entity) use ($locale) {
        $localTrans = Translation::where('key', $entity->key)->where('locale', $locale)->first();

        $status = $localTrans ? $localTrans->status : 0;
        $id = $localTrans ? $localTrans->id : 0;
        $value = $localTrans ? $localTrans->value : '';

        return '<textarea id="trans_'.$id.'_'.$locale.'" data-locale="'.$locale.'" data-key="'.$entity->key.'" style="min-height:40px; width:100%;" >'. $value .'</textarea>';
      });
    }

    $table->addAction('save', [
      'icon' => 'fa fa-save',
      'label' => 'Enregistrer',
      'callback' => 'Translation.store',
      'bulk_action' => true,
    ]);

    // render the table
    return $table->render($query);
  }

  public function index()
  {
    return view('translations.index');
  }

  public function store(Request $request) {
    if (empty($request->values)) {
      return [
        'status' => 'danger',
        'message' => __("Impossible de trouver les traductions"),
      ];
    }
    foreach ($request->values as $data) {
      if (empty($data)) continue;
      $key = $data['key'];
      $locales = isset($data['locales']) && !empty($data['locales']) ? $data['locales'] : [];
      if (empty($locales)) continue;
      foreach ($locales as $locale => $value) {
        $trans = Translation::where('key', $key)->where('locale', $locale)->first();
        if (!$trans) {
          $trans = new Translation();
          $trans->key = $key;
          $trans->locale = $locale;
          $trans->group = '_json';
          $trans->status = 0;
        }
        $trans->value = $value;
        $trans->save();
      }
    }

    return [
      'status' => 'success',
      'message' => __("Les informations ont été sauvegardées avec succès"),
    ];
  }
}
