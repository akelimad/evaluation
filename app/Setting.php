<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

  public static $models = [
    [
      'label' => 'Générales',
      'route' => 'config/settings/general',
      'icon'  => 'fa fa-cog',
      'model' => 'App\Setting',
      'active' => 'gen',
    ],
    [
      'label' => 'Départements',
      'route' => 'config/settings/departments',
      'icon'  => 'fa fa-pie-chart',
      'model' => 'App\Department',
      'active' => 'dep',
    ],
    [
      'label' => 'Fonctions',
      'route' => 'config/settings/functions',
      'icon'  => 'fa fa-briefcase',
      'model' => 'App\Service',
      'active' => 'func',
    ],
    [
      'label' => 'Equipes',
      'route' => 'config/teams',
      'icon'  => 'fa fa-users',
      'model' => 'App\Service',
      'active' => 'teams',
    ],
    [
      'label' => 'Courriers automatiques',
      'route' => 'config/emails',
      'icon'  => 'fa fa-envelope',
      'model' => 'App\Service',
      'active' => 'emails',
    ],
  ];

  /**
   * Get setting by name
   *
   * @param string $name
   * @param string|array $dafault
   *
   * @return string
   **/
  public static function get($name = null, $default = 10) {
    $user = User::getOwner();
    $settings = json_decode($user->settings);
    return isset($settings->$name) ? $settings->$name : $default;
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
