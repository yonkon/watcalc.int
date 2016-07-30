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

$cols = array(
  'ph' => 'Ph',
  'hs' => 'Запах сероводорода',
  'po' => 'Окисляемость перманганатная, мг/л О<sub>2</sub>sub>',
  'fe' => 'Железо общее мг/л',
  'hd' => 'Жесткость общая, мг-экв/л',
  'sn' => 'Производительность',
  'mn' => 'Мутность',
  'uf' => 'Обеззараживание воды',
  'perf' => '?'
);
foreach($cols as $col => $name) {
  $db->query("INSERT INTO `calc_criteria`(`column`, `name`) VALUES ('{$col}', '{$name}')");
}
die('OK');
