<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

  // /**
  //  * Get setting by name
  //  *
  //  * @param string $name
  //  * @param string|array $dafault
  //  *
  //  * @return string
  //  **/
  // public static function getSetting($name = null, $dafault = null) {
  //   $settings = [];
  //   $settings = $this::all();
  //   dd(self::all());
  //   if(!empty($settings)) : foreach ($settings as $key => $s) :
  //     $settings[$s->name] = $s->value;
  //   endforeach; endif;

  //   if( is_null($name) ) {
  //     return $settings;
  //   } elseif ( isset($settings[$name]) ) {
  //     return $settings[$name];
  //   }
  //   return $dafault;
  // }


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
  public static function getSettingAsArray(
    $name, 
    $start_from_1 = false, 
    $with_empty = false, 
    $delimiter = "\r\n", 
    $dafault = [],
    $options = []
    ) {
    if ($value = Setting::select('value')->where('name', $name)->get()) {
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
}
