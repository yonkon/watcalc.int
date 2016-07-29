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

$combs = $db->getAll('SELECT * FROM raw');
foreach($combs as $comb) {
  //http://www.wisewater.ru/js/jquery.ekodar.extended.podbor_shemy/images/7.jpg
  $fname = $comb['result'].'.jpg';
  if(file_exists($fname)) {
    continue;
  }
  $url = 'http://www.wisewater.ru/js/jquery.ekodar.extended.podbor_shemy/images/' . $fname;
  grab_image($url, $fname);
}

function grab_image($url,$saveto){
  $ch = curl_init ($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
  $raw=curl_exec($ch);
  curl_close ($ch);
  if(file_exists($saveto)){
    unlink($saveto);
  }
  $fp = fopen($saveto,'x');
  fwrite($fp, $raw);
  fclose($fp);
}
