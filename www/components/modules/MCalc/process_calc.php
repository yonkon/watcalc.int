<?php

require_once(__DIR__.'/calc_functions.php');

$db = new SafeMySQL(array('user' => 'root', 'pass' => '103103103', 'db' => 'watcalc'));
$scheme = findSchemeByCriteria($db, $_REQUEST['criteria']);
$schcount = count($scheme);
if(empty($scheme) ) {
  $antwoord = jsonAnswer('OK', 'error', array('scheme' => '<p class="calc-alert">Невозможно найти схему по вашему запросу, проверьте все параметры или <a href="/callback"> свяжитесь со специалистом</a></p>'));
  die($antwoord);
} else {
  if($schcount > 1) {
    $antwoord = jsonAnswer('OK', 'error', array('scheme' => '<p class="calc-alert">Невозможно определить схему по вашему запросу, всего найдено '. $schcount . ' результатов. Уточните запрос или <a href="/callback"> свяжитесь со специалистом</a></p>')) ;
    die($antwoord);
  }
  $html = getSchemeFrontDetailsHtml($scheme[0]);
  if($html) {
    $antwoord = jsonAnswer('OK', 'OK', array('scheme' => $html)) ;
  } else {
    $antwoord = jsonAnswer('error', 'error');
  }
  die($antwoord);
}
?>
