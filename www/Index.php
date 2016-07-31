<?php

/**
 * Sibloma CMS
 *
 * @copyright 	2013 Dmitry Potapovich
 * @link 		http://sibloma.com
 * @author 		Dmitry Potapovich
 *
 */
error_reporting(0);
ini_set('display_errors', 0);
class Controller{
    
    function __construct(){
        $this->DefinitionModule();
    }
    
    // Определяем модуль
    function DefinitionModule(){
        
        if($_GET['page'] == "sistemi-ochistki-vodi-dom" && !isset($_GET['url'])){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "sistemi-ochistki-vodi-dom";
        }
        
        if($_GET['page'] == "himvodopodgotovka" && !isset($_GET['url'])){
            $_GET['url'] = "vodopodgotovka";
            $_GET['page'] = "himvodopodgotovka";
        }
        
        if($_GET['url'] == "vodoochistka" && $_GET['page'] == "grubaya"){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "grubaya";
        }
        if($_GET['url'] == "vodoochistka" && $_GET['page'] == "sorbcionnaya"){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "sorbcionnaya";
        }
        if($_GET['url'] == "vodoochistka" && $_GET['page'] == "obezzhelezivanie"){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "obezzhelezivanie";
        }
        if($_GET['url'] == "vodoochistka" && $_GET['page'] == "umyagchenie"){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "umyagchenie";
        }
        if($_GET['url'] == "vodoochistka" && $_GET['page'] == "aeraciya"){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "aeraciya";
        }
        if($_GET['url'] == "vodoochistka" && $_GET['page'] == "obezzarazhivanie"){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "obezzarazhivanie";
        }
        if($_GET['url'] == "vodoochistka" && $_GET['page'] == "obratnyi-osmos"){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "obratnyi-osmos";
        }
        if($_GET['page'] == "sistemi-ochistki-vodi-ofis" && !isset($_GET['url'])){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "sistemi-ochistki-vodi-ofis";
        }
        if($_GET['page'] == "sistemi-ochistki-vodi-kottedj" && !isset($_GET['url'])){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "sistemi-ochistki-vodi-kottedj";
        }
        if($_GET['page'] == "sistemi-ochistki-vodi-promyshlennost" && !isset($_GET['url'])){
            $_GET['url'] = "sistemy-ochistki";
            $_GET['page'] = "sistemi-ochistki-vodi-promyshlennost";
        }
        
        if($_GET['url'] == "montazhnye-shemy" && $_GET['page'] == "skvazhinnyi-nasos-kesson-avtomatika"){
            //$_GET['url'] = "poleznaya-informaciya";
            //$_GET['page'] = "skvazhinnyi-nasos-kesson-avtomatika";
        }
        if($_GET['url'] == "montazhnye-shemy" && $_GET['page'] == "malyi-debet"){
            //$_GET['url'] = "poleznaya-informaciya";
            //$_GET['page'] = "malyi-debet";
        }
        if($_GET['url'] == "montazhnye-shemy" && $_GET['page'] == "kolodec-pogruzhnoi-nasos"){
            //$_GET['url'] = "poleznaya-informaciya";
            //$_GET['page'] = "kolodec-pogruzhnoi-nasos";
        }
        
        if($_GET['page'] == "montazhnye-shemy" && !isset($_GET['url'])){
            $_GET['url'] = "poleznaya-informaciya";
            $_GET['page'] = "montazhnye-shemy";
        }
        if($_GET['page'] == "nashi-preimushchestva" && !isset($_GET['url'])){
            $_GET['url'] = "o-kompanii";
            $_GET['page'] = "nashi-preimushchestva";
        }
        
        
        switch($_GET['module']){
            case('portfolio'): $this->Template = "MPortfolio"; $this->IncludeModule("MPortfolio"); break;
            case('catalogs'): $this->Template = "MCatalogs"; $this->IncludeModule("MCatalogs"); break;
            case('catalog'): $this->Template = "MCatalog"; $this->IncludeModule("MCatalog",1); break;
            case('products'): $this->Template = "MProductsType"; $this->IncludeModule("MProducts",1); break;
            case('product'): $this->Template = "MProduct"; $this->IncludeModule("MProduct"); break;
            case('brand'): $this->Template = "MBrand"; $this->IncludeModule("MBrand",1); break;
            case('brands'): $this->Template = "MBrands"; $this->IncludeModule("MBrands"); break;
            case('faq'): $this->Template = "MFaq"; $this->IncludeModule("MFaq"); break;
            case('calc'): $this->Template = "MCalc"; $this->IncludeModule("MCalc"); break;
            case('contacts'): $this->Template = "MContacts"; $this->IncludeModule("MContacts"); break;
            case('topMenu'): $this->Template = "MPage"; $this->IncludeModule("MTopMenu",1); break;
            case('articleMenu'): $this->Template = "MPage"; $this->IncludeModule("MArticleMenu"); break;
            case('register'): $this->Template = "MRegister"; $this->IncludeModule("MRegister"); break;
            case('login'): $this->Template = "MLogin"; $this->IncludeModule("MRegister"); break;
            case('logout'): $this->Template = "MLogin"; $this->IncludeModule("MRegister"); break;
            case('account'): $this->Template = "MAccount"; $this->IncludeModule("MRegister"); break;
            case('password'): $this->Template = "MPassword"; $this->IncludeModule("MRegister"); break;
            case('otherPage'): $this->Template = "MPage"; $this->IncludeModule("MOtherPage"); break;
            case('blog'): $this->Template = "MBlog"; $this->IncludeModule("MBlog"); break;
            case('search'): $this->Template = "MSearch"; $this->IncludeModule("MSearch",1); break;
            case('tags'): $this->Template = "MTags"; $this->IncludeModule("MTags",1); break;
            case('reviews'): $this->Template = "MReviews"; $this->IncludeModule("MReviews"); break;
            case('articles'): $this->Template = "MArticles"; $this->IncludeModule("MArticles"); break;
            case('cart'): $this->Template = "MCart"; $this->IncludeModule("MCart"); break;
            case('order'): $this->Template = "MOrder"; $this->IncludeModule("MOrder"); break;
            case('filter'): $this->Template = "MFilter"; $this->IncludeModule("MFilter",1); break;
            case('compare'): $this->Template = "MCompare"; $this->IncludeModule("MCompare"); break;
            default: $this->Template = "MIndex"; $this->IncludeModule("MIndex",1);
        }
        
    }
    
    // Подключаем модуль
    function IncludeModule($ModuleName, $GetProducts = NULL){
        require_once $_SERVER['DOCUMENT_ROOT']."/classes/Sibloma.php";
        
        // подключаем модуль
        $Sibloma->InitModule(SITE_DIR."/components/modules/{$ModuleName}/{$ModuleName}.php");
        
        $Sibloma->Module = $ModuleName; // имя модуля
        $Sibloma->TemplateModule = $this->Template; // определяем шаблон модуля
        
        if($GetProducts){// модуль продуктов
            $Sibloma->InitModule(SITE_DIR."/classes/Products.php");
        }
        
        $Sibloma->ViewTemplate(); // подключаем основной шаблон
    }        
    
}

$Controller = new Controller();

?>
