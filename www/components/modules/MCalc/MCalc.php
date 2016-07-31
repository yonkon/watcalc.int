<?php

class MCalc extends Sibloma {
    
    function Runs(){
        self::GetMCalc();
    }
    
    function GetMCalc(){
      if(!empty($_REQUEST['action'])) {
        switch($_REQUEST['action']) {
          case 'scheme' :
            include __DIR__.'/process_calc.php';
            break;
        }
      }
    }
    
}

MCalc::Runs();

?>
