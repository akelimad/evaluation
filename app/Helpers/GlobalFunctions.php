<?php

use App\Helpers\Base;

function chm_table_column_attrs($column) {
  $collAttrs = [];
  if (isset($column['attr']['attr']) && !is_null($column['attr']['attr']) && !empty($column['attr']['attr'])) {
    foreach ($column['attr']['attr'] as $k => $v) {
      $collAttrs[] = (is_numeric($k)) ? $v : $k .'="'. $v .'"';;
    }
  }

  return implode(' ', $collAttrs);
}

function chm_table_exec($closure, $entity) {
  if (is_callable($closure)) {
    return call_user_func($closure, $entity);
  }
  return $closure;
}

function chm_table_action_url($action, $entity) {
  $url = 'javascript:void(0)';
  if (!isset($action['route']) || $action['route'] == '#') return $url;

  if (is_callable($action['route'])) {
    $url = chm_table_exec($action['route'], $entity);
  } else if (isset($action['route']['name'])) {
    $route_args = [];
    if (isset($action['route']['args'])) {
      $route_args = replaceVars($action['route']['args'], $entity);
    }
    $url = route(replaceVars($action['route']['name'], $entity), $route_args);
  } else if (is_string($action['route'])) {
    $url = replaceVars($action['route'], $entity);
  }

  return $url;
}

function chm_table_action_attrs($action, $entity, $table) {
  $attrs = '';
  $message = "Etes-vous sûr de vouloir supprimer cet élément ?";
  $action['attrs'] = chm_table_exec($action['attrs'], $entity);
  if (isset($action['callback']) && !empty($action['callback'])) {
    $primaryKey = $table->getPrimaryKey();
    $attribute = chm_table_attribute($primaryKey, $entity, $table);
    $params = json_encode($action['callback_params']);
    if ($action['name'] == 'delete' || $action['name'] == 'exit') {
      $action['attrs']['onclick'] = "chmModal.confirm(this, '', '". addslashes($message) ."', '". $action['callback'] ."', ['". $attribute ."'], ". $params ."); return false;";
    } else {
      $action['attrs']['onclick'] = 'return '. $action['callback'] .'(event, ['. $attribute .'], '. $params .')';
    }
  } else if ($action['name'] == 'delete' && !isset($action['attrs']['onclick'])) {
    $action['attrs']['onclick'] = "chmModal.confirm(this, '', '". addslashes($message) ."'); return false;";
  }

  if (!isset($action['attrs']['class'])) {
    $action['attrs']['class'] = $action['name'];
  } else {
    $action['attrs']['class'] .= ' '. $action['name'];
  }

  if (isset($action['attrs']) && !empty($action['attrs'])) {
    foreach ($action['attrs'] as $key => $value) {
      if (isset($value['route']['name'])) {
        $route_args = isset($value['route']['args']) ? replaceVars($value['route']['args'], $entity) : [];
        $value = $this->generator->generate(replaceVars($action['route']['name'], $entity), $route_args);
      } else {
        $value = htmlentities($value);
      }
      $attrs .= ' ' . $key;
      if (!empty($value)) {
        $attrs .= '="'. replaceVars($value, $entity, false) .'"';
      }
    }
  }

  return $attrs;
}

function chm_table_attribute($attribute, $entity, $table = null) {
  $attribute = ltrim($attribute, '[');
  $attribute = rtrim($attribute, ']');
  $parts = explode('.', $attribute);
  if (count($parts) > 1) {
    if (property_exists($entity, $parts[0])) {
      $getter = getGetter($parts[1]);
      if (method_exists($entity->{$parts[0]}, $getter)) {
        return $entity->{$parts[0]}->$getter();
      }
    }
    return '';
  } else if (is_array($entity)) {
    return isset($entity[$attribute]) ? $entity[$attribute] : $attribute;
  } else {
    $getter = getGetter($attribute);
    if ($entity->$getter instanceof \DateTime) {
      if (empty($table)) {
        return '';
      }
      return $entity->$getter->format($table->getDateFormat());
    }
    return $entity->$getter;
  }
}

function getGetter($string)
{
  return $string;
}

function replaceVars($input, $entity, $htmlentities = true) {
  if (is_array($input)) {
    foreach ($input as $k => $v) {
      $input[$k] = preg_replace_callback('/\[[a-z_.]+\]/', function($m) use ($entity) {
        if (isset($m[0])) {
          return chm_table_attribute($m[0], $entity);
        }
      }, $v);
    }
  } else {
    $input = preg_replace_callback('/route\[(.*)\]/', function($m) use ($entity) {
      if (isset($m[1])) {
        $route_name = '';
        $route_params = [];
        $parts = explode('|', $m[1]);
        foreach ($parts as $part) {
          $param = explode('=', $part);
          if ($param[0] == 'name') {
            $route_name = $param[1];
          } else if ($param[0] == 'params') {
            $paramsStr = replaceVars($param[1], $entity);
            foreach (explode(',', $paramsStr) as $value) {
              $vParts = explode(':', $value);
              $route_params[$vParts[0]] = $vParts[1];
            }
          }
        }
        if (!empty($route_name)) {
          return $this->generator->generate($route_name, $route_params);
        }
        return $m[1];
      }
    }, $input);

    $input = preg_replace_callback('/\[[a-z_.]+\]/', function($m) use ($entity) {
      if (isset($m[0])) {
        return chm_table_attribute($m[0], $entity);
      }
    }, $input);

    $input = preg_replace_callback('/trans\[(.*)\]/', function($m) use ($entity) {
      if (isset($m[1])) {
        return $this->trans->trans($m[1]);
      }
    }, $input);

    if ($htmlentities) {
      $input = htmlentities($input);
    }
  }
  return $input;
}