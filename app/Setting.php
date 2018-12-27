<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

  public static $models = [
    [
      'label' => 'Préférences',
      'route' => 'config/settings',
      'icon'  => 'fa fa-long-arrow-right',
      'model' => 'App\Setting',
      'active' => 'set',
    ],
    [
      'label' => 'Départements',
      'route' => 'config/setting/departments',
      'icon'  => 'fa fa-long-arrow-right',
      'model' => 'App\Department',
      'active' => 'dep',
    ],
    [
      'label' => 'Fonctions',
      'route' => 'config/setting/functions',
      'icon'  => 'fa fa-long-arrow-right',
      'model' => 'App\Service',
      'active' => 'func',
    ]
  ];

  /**
   * Get setting by name
   *
   * @param string $name
   * @param string|array $dafault
   *
   * @return string
   **/
  public static function get($name = null, $dafault = null) {
    // setting is a file in config directory
    $settings = config('settings');
    if( empty($settings) ) {
      foreach (Setting::all() as $key => $s) :
        $settings[$s->name] = $s->value;
      endforeach;
      config('settings', $settings);
    }

    if( is_null($name) ) {
      return $settings;
    } elseif ( isset($settings[$name]) ) {
      return $settings[$name];
    }
    return $dafault;
  }


  /**
   * Trasform value to an array of elements
   *
   * @param string $name
   * @param bool   $start_from_1
   * @param bool   $with_empty
   * @param string $delimiter
   * @param array  $dafault
   *
   * @return array $items
   */
  public static function asList(
    $name, 
    $start_from_1 = false, 
    $with_empty = false, 
    $delimiter = "\r\n", 
    $dafault = [],
    $options = []
  ) {
    if ($value = self::get($name, false)) {
      $options = array_merge([
        'values_as_keys' => false
      ], $options);

      $items = explode($delimiter, $value);
      if (!empty($items)) {
        $firstItem = explode('=>', $items[0]);
        if (isset($firstItem[1])) {
          $newItems = [];
          foreach ($items as $key => $item) {
            $parts = explode('=>', $item);
            $item_key = trim($parts[0]);
            $item_value = trim($parts[1]);
            $newItems[$item_key] = $item_value;
          }
          $items = $newItems;
        }

        if ($start_from_1) {
          array_unshift($items, "");
          unset($items[0]);
        }

        if ($with_empty) {
          $items = ['' => ''] + $items;
        }

        // set values as keys
        if ($options['values_as_keys']) {
          foreach ($items as $key => $item) {
            $items[$item] = $item;
            unset($items[$key]);
          }
        }

        return $items;
      }
    }
    return $dafault;
  }

  public static function findOne($name)
  {
    return Setting::where('name', $name)->first();
  }
}
