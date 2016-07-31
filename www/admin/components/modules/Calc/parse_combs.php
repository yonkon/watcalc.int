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

//Ph
// < 6, 6 - 7, 7 - 9 ,> 9
$ph = $_REQUEST['ph'];

//Запах сероводорода
// Да, Нет
$hs = $_REQUEST['hs'];

//Окисляемость перманганатная, мг/л О2
// < 3, 3 - 7, 7 - 15, > 15
$po = $_REQUEST['po'];

//Железо общее мг/л
// < 0.3, 0.3 - 1, 1 - 7, 7 - 15, > 15
$fe = $_REQUEST['fe'];

//Жесткость общая, мг-экв/л
// < 3, 3 - 15, > 15
$hd = $_REQUEST['hd'];

//Производительность
// 1 - 2 санузла (до 1,5 м3/час), 3 - 5 санузлов (до 2,5 м3/час)
$sn = $_REQUEST['sn'];

//Мутность
//Да, Нет
$mn = $_REQUEST['mn'];

//Обеззараживание воды
//Да, Нет
$uf= $_REQUEST['uf'];

$perf = $_REQUEST['perf']; //?

$result = $_REQUEST['result']; //Schema id

$res = $db->query("
INSERT INTO raw SET ?u", array(
'ph' => $ph,
'hs' => $hs,
'po' => $po,
'fe' => $fe,
'hd' => $hd,
'sn' => $sn,
'mn' => $mn,
'uf' => $uf,
'perf' => $perf,
'result' => $result
 )
);

if(!$res) {
  die($db->lastQuery());
} else {
  die('OK');
}

//m.compareResult = function(obj) {$.ajax({url: 'http://watcalc.int/parse_combs.php', data : obj}); }


/*
 serialize = function(obj, prefix) {
  var str = [];
  for(var p in obj) {
    if (obj.hasOwnProperty(p)) {
      var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
      str.push(typeof v == "object" ?
        serialize(v, k) :
        encodeURIComponent(k) + "=" + encodeURIComponent(v));
    }
  }
  return str.join("&");
}
 */
