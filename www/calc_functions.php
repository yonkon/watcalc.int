<?php
/**
 * Created by PhpStorm.
 * User: X-iLeR
 * Date: 30.07.2016
 * Time: 21:06
 */
require_once('safemysql.class.php');

function getEnabledInputs(SafeMySQL $db) {
  $inputs = $db->getAll("
SELECT v.*, c.name, c.status as row_status, c.description FROM `calc_criteria_value` v
JOIN calc_criteria c
	ON c.`column` = v.col
	AND c.status = 'enabled'
WHERE v.status = 'enabled'
ORDER BY v.`position` ASC
");
  return $inputs;
}

function getEnabledInputsGroups(SafeMySQL $db) {
  $inputs = getEnabledInputs($db);
  $groups = array();
  $max_cols  = 1;
  foreach($inputs as $input) {
    $groups[$input['col']]['name'] = $input['name'];
    $groups[$input['col']]['description'] = $input['description'];
    $groups[$input['col']]['status'] = $input['status'];
    $groups[$input['col']]['col'] = $input['col'];
    $groups[$input['col']]['values'][] = $input;
  }
  foreach($groups as &$group){
    $count = count($group['values']);
    $group['count'] = $count;
    if($count > $max_cols) {
      $max_cols = $count;
    }
  }
  unset($group);
  $groups['max_inputs'] = $max_cols;
  return $groups;
}

function findSchemeByCriteria(SafeMySQL $db, $criteria) {
  if(empty($criteria) || !is_array($criteria)) {
    return false;
  }
  $where = array();
  if(empty($criteria['perf'])) {
    $criteria['perf'] = 'N';
  }
  foreach($criteria as $col => $val) {
    $where[]= "`{$col}` = '{$val}'";
  }
  $where = join(' AND ', $where);
  $scheme = $db->getAll("SELECT * FROM raw WHERE {$where} GROUP BY result");
  return $scheme;
}

function getSchemeFrontHtml($scheme) {
  if(empty($scheme) || !is_array($scheme)) {
    return false;
  }
  return "
<div id='scheme_id'>Схема {$scheme['result']}</div>
<img class=\"scheme\" src=\"/{$scheme['result']}.jpg\">
";
}

function jsonAnswer($status  = 'OK', $msg = 'OK', $data = array()) {
  $answer = array('status' => $status, 'msg' => $msg);
  foreach($data as $k=>$v) {
    $answer[$k] = $v;
  }
  return json_encode($answer);
}
