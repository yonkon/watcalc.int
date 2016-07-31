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

$ids = $_REQUEST['id_scheme'];
$details = $_REQUEST['details']['result_content'];
foreach($details as $det) {
  $id =   empty($det['id']) ? 0 : $det['id'];
  $name = empty($det['name']) ? 0 : $det['name'];
  $count = empty($det['count']) ? 0 : $det['count'];
  $price = empty($det['price']) ? 0 : $det['price'];
  $summ = empty($det['summ']) ? 0 : $det['summ'];
  $count = empty($det['count']) ? 1 : $det['count'];
  $vol = empty($det['vol']) ? $count : $det['vol'];
  $res = $db->query("INSERT INTO `calc_scheme_details`
(`id_scheme`,   `id_detail`,  `name`,      `amount`, `price`,  `vol`,    `summ`) VALUES
('{$ids}',         {$id},       '{$name}',   {$count}, {$price}, '{$vol}', '{$summ}' )"
  );
/*
count 60
id 23866
name "Фильтрующие материалы"
price "543.12"
summ 2
vol 60
*/
}



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

/*
$.each( r, function(i, el){
        $.ajax({
            url: 'http://watcalc.int/parse_details.php',
            type: 'post',
            data : {
                id_scheme : i,
                details : el
            }
        });
    }
);
*/
