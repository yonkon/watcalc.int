<?php
ini_set('display_errors', 1);
error_reporting(-1);
/**
 * Created by PhpStorm.
 * User: X-iLeR
 * Date: 27.07.2016
 * Time: 20:26
 */
header('Access-Control-Allow-Origin: *');
die('closed');

require_once('safemysql.class.php');

$db = new SafeMySQL(array('user' => 'root', 'pass' => '103103103', 'db' => 'watcalc'));

$combs = $db->getAll('SELECT * FROM raw');
$critvals = array();
foreach($combs as $comb) {
  foreach($comb as $col => $val) {
    if(is_null($val) ) {
      continue;
    }
    $critvals[$col][$val] = $val;
  }
}
unset($critvals['id']);
unset($critvals['result']);

foreach($critvals as $col => $vals) {
  foreach($vals as $val) {
    $db->query("INSERT INTO calc_criteria_value(col, `value`) VALUES ('{$col}', '{$val}' )");
  }
}

