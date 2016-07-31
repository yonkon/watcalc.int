<?php

class Calc extends Controller {
    
    function __construct(){
      parent::__construct();
      $this->Request();

    }
    
    function GetCalc(){
      if(!empty($_REQUEST['action'])) {
        switch($_REQUEST['action']) {
          case 'scheme' :
            include __DIR__.'/process_calc.php';
            break;
          default:
            include __DIR__."/calc_main.php";
        }
      } else {
        include __DIR__."/calc_main.php";
      }
    }

  public function onInput(){
    parent::onInput();
  }

  public function onOutput(){
    parent::onOutput();
    $this->GetCalc();
  }
    
}

//Calc::Runs();

?>
