<?php
namespace App\Helpers;

class Base {

  const INITIAL_YEAR = 2017;

  /**
   * Hyphens and dashes to camelcase
   *
   * @param string $string
   * @param boolean $capitalizeFirstCharacter
   * @return boolean
   *
   * @author Mhamed Chanchaf
   */
  public static function dashesToCamelCase($string, $capitalizeFirstCharacter = true)
  {
    $str = str_replace(' ', '', ucwords(preg_replace('/[-_]/', ' ', $string)));
    if (!$capitalizeFirstCharacter) {
      $str[0] = strtolower($str[0]);
    }
    return $str;
  }

  /**
   * Generate random string
   *
   * @param int $length
   * @param bool $lowercases
   * @param bool $capitals
   * @param bool $numbers
   *
   * @return string $string
   *
   * @author mchanchaf
   */
  public static function getRandomString($length = 8, $lowercases = true, $capitals = true, $numbers = true ) {
    $string = '';

    if ($lowercases) $string .= 'abcdefghijklmnopqrstuvwxyz';
    if ($capitals) $string .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($numbers) $string .= '0123456789';

    if (empty($string)) $string .= uniqid();

    return substr(str_shuffle($string), 0, $length);
  }

  public static function getParamsAndClausesFromKeywords($keywords, $columns = [])
  {
    $result = [];

    $i = 0;
    foreach (explode(' ', trim($keywords)) as $keyword) {
      $result['params'][':param'. $i] = '%'. $keyword .'%';

      $clause_parts = [];
      foreach ($columns as $column) {
        $clause_parts[] = "{$column} LIKE :param{$i}";
      }

      $clauses[] = '(' . implode(' OR ', $clause_parts) .')';

      $i++;
    }

    $result['clauses'] = '(' . implode(' AND ', $clauses) .')';

    return $result;
  }

  /**
   * Random floating-point values from 0 to 10 with two decimals
   */
  public static function getRandomFloatNumber($min, $max, $decimals = 2) {
    $scale = pow(10, $decimals);
    return mt_rand($min * $scale, $max * $scale) / $scale;
  }

  public static function arrayToExcel($rows = [], $filename, $download = false)
  {
    if ($download) {
      header('Content-Encoding: UTF-8');
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header("Content-Disposition: attachment; filename=\"{$filename}.xlsx\"");
      header("Pragma: no-cache");
      header("Expires: 0");
    }

    $writer = new \App\Helpers\XLSXWriter();
    $writer->setAuthor('BPTW');
    $writer->writeSheet($rows);
    $output = $writer->writeToString();

    if ($download) {
      echo $output;exit;
    } else {
      return $output;
    }
  }

  public static function getYearsList()
  {
    $years = [];

    for ($i=date('Y'); $i >= self::INITIAL_YEAR; $i--) {
      $years[$i] = $i;
    }

    return $years;
  }

  public static function getAverage($array, $round = false)
  {
    $a = array_filter($array);
    if (count($a) <= 0) {
      return 0;
    }
    $average = array_sum($a) / count($a);
    return is_numeric($round) ? round($average, $round) : $average;
  }

  public static function getRandomColor($with_hash = true)
  {
    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    return $with_hash ? $color : ltrim($color, '#');
  }

  public static function getPercentage($total, $number, $round = 0)
  {
    if ( $total > 0 ) {
      return round(($number / ($total / 100)), $round);
    } else {
      return 0;
    }
  }

  /**
   * Generete google chart data
   *
   * @param array $cols
   * @param array $rows
   * @param array $results
   *
   * @return string $data
   *
   * @author Mhamed Chanchaf
   */
  public static function getGoogleChartData($cols, $rows, $results)
  {
    $data = ['cols' => $cols, 'rows' => []];

    foreach ($results as $key => $r) :
      $row = [];
      for ($i=0; $i < count($rows); $i++) {
        $colName = $rows[$i];
        $value = is_array($r) ? $r[$colName] : $r->{$colName};
        $row[$i]['v'] = (is_numeric($value)) ? intval($value) : $value;
      }
      $data['rows'][]['c'] = $row;
    endforeach;

    return $data;
  }

  /**
   * Content Word Limit
   *
   * @param string $content
   * @param int $limit
   * @return $content
   */
  public static function wordLimit($content, $limit) {
    $limit += 1;
    $content = explode(' ', $content, $limit);
    if (count($content)>=$limit) {
      array_pop($content);
      $content = implode(' ', $content) . '...';
    } else {
      $content = implode(' ', $content);
    }
    $content = preg_replace('/\[.+\]/', '', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
  }


  /**
   * Limit text length
   *
   * @param string $content
   * @param int $limit
   * @return $content
   */
  public static function lettersLimit($content, $limit) {
    // strip tags to avoid breaking any html
    $content = strip_tags($content);
    if (strlen($content) > $limit) {
      // truncate content
      $content = mb_substr($content, 0, $limit) . '...';
      // make sure it ends in a word so assassinate doesn't become ass...
      //$content = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
    }
    return $content;
  }

  public static function captchaverify($response, $settingService)
  {
    $secret = $settingService->getParameter('CAPTCHA_SECRET_KEY');
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=". $secret ."&response=". $response);
    $results = json_decode($response, true);
    return ($results["success"] === true);
  }

  public static function dateDiff($datetime1, $datetime2, $format = '%a')
  {
    $datetime1 = is_string($datetime1) ? new \DateTime($datetime1) : $datetime1;
    $datetime2 = is_string($datetime2) ? new \DateTime($datetime2) : $datetime2;
    $interval = $datetime1->diff($datetime2)->format($format);

    return $interval;
  }

} // END Class