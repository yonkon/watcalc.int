<?php
/**
 * Created by PhpStorm.
 * User: X-iLeR
 * Date: 30.07.2016
 * Time: 21:06
 */
require_once('safemysql.class.php');
define('_SCHEMES_URL_', '/');
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
  $scheme_url = _SCHEMES_URL_;
  return "
<div id='scheme_id'>Вам подходит схема <span class='scheme-value'>{$scheme['result']}</span></div>
<img class=\"scheme\" src=\"{$scheme_url}{$scheme['result']}.jpg\">
";
}

function getSchemeFrontDetailsHtml($scheme) {
  if(empty($scheme) || !is_array($scheme)) {
    return false;
  }
  $image = getSchemeFrontHtml($scheme);
  $id = $scheme['result'];
  $sql = "SELECT d.*, p.price_v
FROM calc_scheme_details d
LEFT JOIN d_products_variants p
  ON p.product_id = d.id_detail
WHERE d.id_scheme = '{$id}'
  AND d.amount > 0
GROUP BY d.id_detail";
  $details = Db2::i()->getAll($sql);
  if(empty($details)) {
    return '<p class="calc-alert">Сожалеем, но спецификациядля данной схемы не найдена</p>';
  }
  $detailsHtml = '
<div id="scheme_details">
  <div class="watcalc-header">Спецификация и цены</div>
  <div class="actions">
    <div class="action">Print</div>
    <div class="action">Email</div>
    <div class="action">PDF</div>
    <div class="complect"><a href="#">КНР</a></div>
    <div class="complect"><a href="#">USA</a></div>
  </div>
  <table>
  <thead>
    <tr>
      <th>№ п/п</th>
      <th>Наименование оборудования</th>
      <th>Кол-во</th>
      <th>Цена (USD)</th>
    </tr>
  </thead>
  <tbody>';
  foreach($details as $d) {
    if(!empty($d['price_v'])) {
      $d['price'] = $d['amount'] * $d['price_v'];
    }
    $detailsHtml .=     "
    <tr>
      <td>{$d['position']}</td>
      <td>{$d['name']}</td>
      <td>{$d['amount']}</td>
      <td>{$d['price']}</td>
    </tr>";
  }
  $detailsHtml .=   '
  </tbody>
  </table>
</div>
  ';
  return $image.$detailsHtml;
}

function jsonAnswer($status  = 'OK', $msg = 'OK', $data = array()) {
  $answer = array('status' => $status, 'msg' => $msg);
  foreach($data as $k=>$v) {
    $answer[$k] = $v;
  }
  return json_encode($answer);
}

/**
 * Class Db2
 * @property SafeMySQL $db
 */
class Db2 {
  public static $db;
  public static function init() {
    self::$db = new SafeMySQL(array('user' => 'root', 'pass' => '103103103', 'db' => 'watcalc'));
  }

  public static function i()
  {
   return self::$db;
  }
}

Db2::init();
