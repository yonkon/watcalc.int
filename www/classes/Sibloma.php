<?php

/*
 * Sibloma CMS
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Functions.php'; //Дополнительные функции /* extends Db */

class Sibloma extends Functions{
    
    public $Massive = array(); // основной массив с данными
    public $MoreClasses = array( //Дополнительные классы необходимые для работы системы
        'EMail' => '/classes/EMail.php', //Работа с почтой
        'Session' => '/classes/Session.php', //Работа с сессиями
        'Install' => '/classes/Install.php', //Установлена ли система
        'Widget' => '/classes/Widget.php', //Подключение виджетов
        'MFilter' => '/components/modules/MFilter/MFilter.php', //Модуль фильтра товара
        'MRegister' => '/components/modules/MRegister/MRegister.php', //Модуль регистрации
        'MCart' => '/components/modules/MCart/MCart.php' //Модуль корзины
        );
    public $page = 1;
    public $image = '/images/nophoto.png';
    
    function __construct(){
        parent::__construct();
        $this->InstallationSettings(); // Определяем настройки системы - таблица settings
        $this->IncludePlagins(); // Подключаем плагины
        $this->InitLanguage(); // Подключаем язык
        $this->MoreClasses(); //Подключаем дополнительные классы
        Install::ActiveInstall(); // Установлена  ли система
    }    
    
    // Подключение дополнительных классов
    function MoreClasses(){
        foreach($this->MoreClasses as $val){
            require_once SITE_DIR."{$val}";
        }
    }

    // Подключение плагинов
    function IncludePlagins(){
        $ScanPlagins = $this->ScanFolder(SITE_DIR."/components/plagins/");
        if($ScanPlagins){
            foreach($ScanPlagins as $val){
                require_once SITE_DIR."/components/plagins/{$val}/{$val}.php";
            }
        }
    }
    
    // Подключение js плагинов
    function IncludePlaginsJs(){
        $ScanPlagins = $this->ScanFolder(SITE_DIR."/components/plagins/");
        if($ScanPlagins){
            foreach($ScanPlagins as $val){
                if(file_exists(SITE_DIR."/components/plagins/{$val}/Js.php")){
                    require_once SITE_DIR."/components/plagins/{$val}/Js.php";
                }
            }
        }
    }
        
    // Подключаем модули
    function InitModule($Module){
        // подключаем любой модуль
        if(file_exists($Module)){
            require_once $Module;
        }
    }
    
    // Подключение шаблона модуля
    function ViewModuleTemplate(){
        if(!file_exists(SITE_DIR."/design/{$this->theme}/html/{$this->TemplateModule}.php")){
          $this->Error = "Ошибка 404";
        }
//      Functions::lic();
        if(!$this->Error){
            echo $this->Template(SITE_DIR."/design/{$this->theme}/html/{$this->TemplateModule}.php",$this->Massive[$this->Module]);
        }else{
            echo $this->Template(SITE_DIR."/design/{$this->theme}/html/404.php");
        }
    }
    
    // Подключение основного шаблона
    function ViewTemplate(){
        if($this->Error){
            header('HTTP/1.0 404 Not Found'); header('Status: 404 Not Found'); 
            $this->Massive['MetaTitle'] = Lang::LangVal('Error404');
        }
        if($this->active == 1){
            echo $this->Template("design/".$this->theme."/Index.php",$this->Massive);
        }else{
            echo $this->reconstruction;
        }
    }
    
}

$Sibloma = new Sibloma();

?>
