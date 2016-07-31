<?php
header('Powered: test');
header('Content-Type: text/html; charset=utf-8');
//require_once('safemysql.class.php');
require_once('calc_functions.php');
//Standalone calc


$db = new SafeMySQL(array('user' => 'root', 'pass' => '103103103', 'db' => 'watcalc'));
$groups = getEnabledInputsGroups($db);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://code.jquery.com/jquery-1.12.4.js"   integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="   crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/calc.css">

</head>
<body>
<div class="watcalc">
  <div class="watcalc-header"><h2>Онлайн смета</h2><div class="clr"></div> </div>
  <table class="watcalc-inputs">
    <?php
    $group_count = count($groups) - 1 ;
    $gri = 0;
    $btn_rows = 4;
    $process_button_delta = 2;
    $button_flag = 0;
    foreach($groups as $g) {
      if(!is_array($g) ) {
        continue;
      }
      $button = false;
      if($group_count - $gri == $btn_rows) {
        $button = true;
        $button_flag = 1;
      }

      ?>
    <tr class="watcalc-row">
      <td class="watcalc-cell watcalc-label">
        <?= $g['name'] ?>
      </td>
      <?php foreach($g['values'] as $v) { ?>
<!--      <div class="watcalc-inputs-row">-->
        <td class="watcalc-cell watcalc-input"  data-col="<?= $v['col'] ?>">
          <?php echo $v['value'];
          if(!empty($v['redirect_url'])) { ?>
            <div class="tip hidden">
              <span class="red">Обратитесь к специалисту, пожалуйста.</span>
              <br>
              <a class="link-callback" href="<?= $v['redirect_url'] ?>"> Обратный звонок</a>
          </div>
          <? } ?>
        </td>
      <? } ?>
        <td class="watcalc-cell func" colspan="<?= (2 + $groups['max_inputs'] - $g['count']) - $process_button_delta*($button_flag)?>">
        </td>
      <? if($button) { ?><td class="watcalc-cell btn" rowspan="<?= $btn_rows+1 ?>" colspan="<?= $process_button_delta?>"><a href="/calc"><span>Рассчитать</span></a></td><? } ?>
    </tr>
    <?php
      $gri++;
    } ?>
    <tr  class="watcalc-row" >
      <td class="watcalc-cell interact">Печать</td>
      <td class="watcalc-cell interact">Сохранить PDF</td>
      <td class="watcalc-cell interact">Отправить Email</td>
      <td class="watcalc-cell reset" colspan="<?= 2 + $groups['max_inputs'] - 2 - $process_button_delta ?>">Сбросить</td>
    </tr>
  </table>
  <div class="watcalc-shadow">&nbsp;</div>
  <div id="scheme"></div>
</div>

<script type="text/javascript" src="/calc.standalone.js"></script>
</body>
</html>
