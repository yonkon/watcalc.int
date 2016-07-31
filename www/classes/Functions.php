<?php

/*
 * Дополнительные функции системы
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/system/db/Db.php'; // Подключение к базе данных

class Functions extends Db{
    
    function __construct(){
        parent::__construct();
    }
   
    //Функция взаимодействия с базой данных
    function Query($query){
        $result = mysql_query($query);
        if(!$result){
            $result = FALSE;
        }
        return $result;
    }
    
    function ProductUroven($id){
        $query0 = "SELECT * FROM ".PREFIX."top_menu WHERE id='{$id}'";
        $result0 = $this->IssetID($this->Query($query0));
        $i = 0;
        if($result0){
            $myrow0 = mysql_fetch_array($result0);
            if($myrow0['parent_id'] != 0){
                $i = $i + 1;
                $query1 = "SELECT * FROM ".PREFIX."top_menu WHERE id='{$myrow0['parent_id']}'";
                $result1 = $this->IssetID($this->Query($query1));
                if($result1){
                    $myrow1 = mysql_fetch_array($result1);
                    if($myrow1['parent_id'] != 0){
                        $i = $i + 1;
                    }
                }
            }
        }
        return $i;
    }
    
    
    
    function CatMass($page){
        $id = $this->ReturnFieldAny("top_menu", "url", $page, "id");
        if($id){
            $query = "SELECT * FROM ".PREFIX."top_menu WHERE id = '{$id}'";
            $result = $this->IssetID($this->Query($query));
            if($result){
                $myrow = mysql_fetch_array($result);
                if($myrow['parent_id'] == 98){
                    return $myrow['id'];
                }else{
                    $query2 = "SELECT * FROM ".PREFIX."top_menu WHERE id = '{$myrow['parent_id']}'";
                    $result2 = $this->IssetID($this->Query($query2));
                    if($result2){
                        $myrow2 = mysql_fetch_array($result2);
                        if($myrow2['parent_id'] == 98){
                            return $myrow2['id'];
                        }else{
                            
                            $query3 = "SELECT * FROM ".PREFIX."top_menu WHERE id = '{$myrow2['parent_id']}'";
                            $result3 = $this->IssetID($this->Query($query3));
                            if($result3){
                                $myrow3 = mysql_fetch_array($result3);
                                if($myrow3['parent_id'] == 98){
                                    return $myrow3['id'];
                                }else{
                                    // ??
                                }
                            }
                            
                        }
                    }
                }
            }
        }
        
    }
    
    
    
    function ProductUrl($id){
        $query0 = "SELECT * FROM ".PREFIX."top_menu WHERE id='{$id}'";
        $result0 = $this->IssetID($this->Query($query0));
        if($result0){
            $myrow0 = mysql_fetch_array($result0);
            $uri = "/".$myrow0['url']."/";
            if($myrow0['parent_id'] != 0){
                $query1 = "SELECT * FROM ".PREFIX."top_menu WHERE id='{$myrow0['parent_id']}'";
                $result1 = $this->IssetID($this->Query($query1));
                if($result1){
                    $myrow1 = mysql_fetch_array($result1);
                    if($myrow1['parent_id'] != 0){
                        $uri = "/".$myrow1['url'].$uri;
                    }
                }
            }
        }
        return $uri;
    }
    
    // Проверка на наличие записи в базе
    function IssetID($result){
        if($result){
            if(!mysql_num_rows($result)){
                $result = false;
            }
        }
        return $result;
    }
    
    // Подготовка массива POST
    function ForeachArray($array){
        foreach($array as $key=>$val){
            $array[$key] = $val;
        }
        return $array;
    }
    
    // Pаскладываем выборку в массив
    function MyrowArray($result){
        if(isset($result)){
            while($myrow = mysql_fetch_array($result)){
                $arr[$myrow['id']] = $myrow;
            }
            return $arr;
        }
    }
    
    // Строка через запятую из массива
    function LineComma($mass){
        if($mass){
            foreach($mass as $key=>$val){
                if($val != '/' && !empty($val)){
                    $line .= $val.",";
                }
            }
            if($line){
                $line = substr($line, 0, strlen($line)-1);
                return $line;    
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    // Колличество полей в таблице
    function CountMYSQL($from){
        $query = "SELECT COUNT(*) FROM {$from}";
        $result = $this->Query($query);
        $row = mysql_fetch_array($result);
        $posts = $row[0];
        return $posts; 
    }
    
    // Колличество полей в таблице для плагина Навигация
    function CountMYSQLNavigation($select){
        $query = "{$select}";
        $result = $this->Query($query);
        $row = mysql_fetch_array($result);
        $posts = $row[0];
        return $posts; 
    }
    
    // Определение последнего ID в таблице
    function LastID($table){
        $query = "SELECT id FROM ".PREFIX."{$table} ORDER BY id DESC";
        $result = $this->Query($query);
        $myrow = mysql_fetch_array($result);
        return $myrow['id'];
    }
    
    // Подключение языка
    function InitLanguage(){
        // подключаем язык
        if(isset($_SESSION['language'])){require_once SITE_DIR."/system/language/{$_SESSION['language']}/Lang.php";
        }else{require_once SITE_DIR."/system/language/ru/Lang.php";}
    }
    
    // Обновление Position
    function UPDATEPosition($table, $position = 'position'){
        $id = $this->LastID($table);
        $query = "UPDATE ".PREFIX."{$table} SET {$position}='{$id}' WHERE id='{$id}'";
        $this->Query($query);
    }
    
    //Проверка данных
    function CleanData($val = NULL, $type = NULL, $condition = '', $error = NULL){
        $this->Error($val, $condition, $error);
        switch ($type){
            case 'string':
                return str_replace("'","\'",trim(htmlspecialchars(stripslashes($val))));
                break;
            case 'integer':
                return intval($val);
                break;
            case 'boolean':
                if($val == 'on'){return 1;}else{return 0;}
                break;
            case 'float':
                return round($val, 2);
                break;
            case 'email':
                if(preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $val)){
                    return TRUE;
                }else{return FALSE;}
                break;
            case 'password':
            // Пароль должен состоять только из латинских букв и содержать не меньше 4 символов и не больше 20
                if(preg_match("/^[a-zA-Z0-9]{4,20}$/", $val)){
                    return TRUE;
                }else{return FALSE;}
                break;
            case 'login':
            // Логин может состоять из букв, цифр, дефисов и подчёркиваний. Длина от 3 до 16 символов.
                if(preg_match("/^[a-z0-9_-]{3,16}$/", $val)){
                    return TRUE;
                }else{return FALSE;}
                break;
            default:
                return str_replace("'","\'",$val);
        }
    }
    
    //Проверка формы на наличие ошибок
    function Error($val, $condition, $error){
        if($error != NULL){
            if($val == $condition){
                $this->Error .= " / ".$error;
            }
        }
    }
    
    // Сканирование папки
    function ScanFolder($way){
        $Scan = scandir($way);
        $Scan = array_slice($Scan, 2);
        $KeyDelete = array_search("Thumbs.db",$Scan);
        if(!empty($KeyDelete)){unset($Scan[$KeyDelete]);}
        return $Scan;
    }
    
    // Создание HTML-шаблонов в строку
    function Template($FileName, $array = NULL){
        //Установка переменных для шаблонов
        if($array){
            foreach ($array as $key => $val){
                $$key = $val;
            }
        }
        // Генерация HTML в строку
          include $FileName;
    }
    
    // Проверка URL на совпадение
    function CleanURL($url, $table, $id){
        $url = $this->NormalizeStringToURL($url);
        $query = PREFIX."{$table} WHERE url='{$url}'";
        $count = $this->CountMYSQL($query);
        if($count >= 1){
            if($id == ''){
                $url .= rand(1,1000);
                if(Session::SessionGet($this->Massive['SpecialNumber'])){
                    $this->Information = NoteMessage::Information('URLIsset')." Новый url <b>{$url}</b>";
                }  
            }else{
                $query = "SELECT id FROM ".PREFIX."{$table} WHERE url='{$url}'";
                $myrow = mysql_fetch_array($this->Query($query));
                if($myrow['id'] != $id){
                    $url .= rand(1,1000);
                    if(Session::SessionGet($this->Massive['SpecialNumber'])){
                        $this->Information = NoteMessage::Information('URLIsset')." Новый url <b>{$url}</b>";
                    }
                }
            }  
        }
        if($url == ''){
            $url .= rand(1,1000000);
        }
        return $url;
    }
    
    function lic(){
        $s = base64_decode($this->ReturnField('settings', 31, 'value')); 
        $k = substr($s, -8, 8);
        $s = substr($s, 0, -8);
        for($i=0; $i<strlen($s); $i++){
            $c = substr($s, $i, 1); 
            $d = substr($k, ($i % strlen($k))-1, 1);
            $r .= chr(ord($c)-ord($d));
        }
      return getenv("HTTP_HOST");
        if($r != getenv("HTTP_HOST")){$this->Error = "Лицензия недействительна";}
        return $r;
    }
    
    // Определяем поле по ID
    function ReturnField2($table, $id, $field){
        $query = "SELECT * FROM s_{$table} WHERE id='{$id}'";
        $result = $this->IssetID($this->Query($query));
        if($result){
            $myrow = mysql_fetch_array($result);
            return $myrow[$field];
        }else{
            return FALSE;
        }
    }
    
    // ----------------------------------------------------------------------------------------
    // Товары по заданному WHERE
    function GetProducts2($where){
        
        // -----------------------------------------------
        $query3 = "SELECT * FROM s_currency";
        $result3 = $this->IssetID($this->Query($query3));
        if($result3){
            while($myrow3 = mysql_fetch_array($result3)){
                if($myrow3['main'] == 1){$currency_main = $myrow3['id'];}
                $mass[$myrow3['id']] = $myrow3;
            }
        }
        // -----------------------------------------------
        
        $tbl1 = "s_products"; // продукты
        $tbl2 = "s_products_variants"; // варианты продуктов
        $query = "SELECT {$tbl1}.*, GROUP_CONCAT({$tbl2}.id) as variants_id
                  FROM {$tbl1} JOIN {$tbl2} ON {$tbl1}.id={$tbl2}.product_id
                  {$where}";
        $result = $this->IssetID($this->Query($query));
        if($result){
            while($myrow = mysql_fetch_array($result)){
                $myrow['image'] = explode(',', $myrow['image']);
                //if($myrow['image'][0] == '' || !file_exists(SITE_DIR.$myrow['image'][0])){$myrow['image'][0] = '/images/nophoto.png';}
                $myrow['tags'] = explode(',', $myrow['tags']);
                
                $cat = '';
                $cat = explode('/', $myrow['category']);
                $url_cat = $this->ReturnField2('categories', $cat[1], 'url');
                
                $myrow['id_cat'] = $cat[1];
                
                if($url_cat){
                    $myrow['uri'] = 'http://shop.ruswater.com/'.$url_cat.'/'.$myrow['url'].'/';
                }else{
                    $myrow['uri'] = 'http://shop.ruswater.com/product/'.$myrow['url'].'/';
                }
                
                //echo $myrow['uri'];
                
                $Massive[$myrow['id']] = $myrow; // основные данные продукта
                $ArrVariantsId = explode(",", $myrow['variants_id']);
                foreach($ArrVariantsId as $key=>$val){
                    if($key == 0){$whereVariants = "id = '{$val}'";}
                    if($key != 0){$whereVariants .= " || id = '{$val}'";}
                }
                $query2 = "SELECT * FROM {$tbl2} WHERE {$whereVariants} ORDER BY position";
                $result2 = $this->IssetID($this->Query($query2));
                if($result2){
                    while($myrow2 = mysql_fetch_array($result2)){
                        
                        $myrow2['price_v_main'] = $myrow2['price_v'];
                        
                        $myrow2['price_v'] = $this->Price2($myrow2['price_v'], $myrow2['weight_v'], $mass, $currency_main);
                        
                        $myrow2['price_v_s'] = ($myrow2['price_v']*(100-$myrow2['sale_v']))/100; // цена со скидкой
                        $myrow2['price_v_p'] = $this->Price($myrow2['price_v'], $myrow2['sale_v']); // цена в выбранной валюте
                        $myrow2['old_price_v_p'] = $this->Price($myrow2['old_price_v']);
                        
                        $Massive[$myrow['id']]['variants'][] = $myrow2; // Варианты продукта
                    }
                }
                
            }
            //$this->Debug($this->Tags);
            return $Massive;
        }else{
            return false;
        }
    }
    
    function Price2($price, $sign_id=NULL, $mass=NULL, $sign_id_main=NULL){
        if(isset($sign_id) && isset($mass) && isset($sign_id_main)){

            $price_dollar = ($price*1)/($mass[$sign_id]['rate']*1);
            $price_itog = $price_dollar*$mass[$sign_id_main]['rate']*1;
            
            return ceil($price_itog);
        
        }else{
            return $price;
        }
    }
    
    // Обрабатываем цену
    function Price($price, $sale=NULL){
        if(isset($_SESSION['currency'])){
            $query1 = "SELECT * FROM ".PREFIX."currency WHERE main='1'";// Главная
            $result1 = $this->IssetID($this->Query($query1));
            if($result1){
                $myrow1 = mysql_fetch_array($result1);
                if($_SESSION['currency'] == $myrow1['id']){
                    $currency = "ok";
                }else{$currency = "none";}
            }else{$currency = "ok";}
        }else{$currency = "ok";}
        
        // Если есть скидка
        if($sale){
            $price = ($price*(100 - $sale))/100;
        }
        
        if($currency == "none"){
            $query2 = "SELECT * FROM ".PREFIX."currency WHERE id='{$_SESSION['currency']}'";// Сессия
            $result2 = $this->IssetID($this->Query($query2));
            if($result2){
                $myrow2 = mysql_fetch_array($result2);
                $query3 = "SELECT * FROM ".PREFIX."currency WHERE id='1'";// Доллар
                $result3 = $this->IssetID($this->Query($query3));
                if($result3){
                    $myrow3 = mysql_fetch_array($result3);
                    $NewPrice = (($price*$myrow3['rate'])/$myrow1['rate'])*$myrow2['rate'];
                    return $NewPrice;
                }else{return $price;}
            }else{return $price;}
        }elseif($currency == "ok"){
            $query4 = "SELECT * FROM ".PREFIX."currency WHERE id='1'";// Доллар
            $result4 = $this->IssetID($this->Query($query4));
            if($result4){
                $myrow4 = mysql_fetch_array($result4);
                $query5 = "SELECT * FROM ".PREFIX."currency WHERE main='1'";// Главная
                $result5 = $this->IssetID($this->Query($query5));
                if($result5){
                    $myrow5 = mysql_fetch_array($result5);
                    $NewPrice = (($price*$myrow4['rate'])/$myrow5['rate'])*$myrow5['rate'];
                    return $NewPrice;
                }else{return $price;}
            }else{return $price;}
        }else{return $price;}
    }
    
    // ---------------------------------------------------------------
    /*
    // Товары по заданному WHERE
    function GetProducts2($where){
        $tbl1 = "s_products"; // продукты
        $tbl2 = "s_products_variants"; // варианты продуктов
        $query = "SELECT {$tbl1}.*, GROUP_CONCAT({$tbl2}.id) as variants_id
                  FROM {$tbl1} JOIN {$tbl2} ON {$tbl1}.id={$tbl2}.product_id
                  {$where}";
        $result = $this->IssetID($this->Query($query));
        if($result){
            while($myrow = mysql_fetch_array($result)){
                $myrow['image'] = explode(',', $myrow['image']);
                //if($myrow['image'][0] == '' || !file_exists(SITE_DIR.$myrow['image'][0])){$myrow['image'][0] = '/images/nophoto.png';}
                $myrow['tags'] = explode(',', $myrow['tags']);
                
                $cat = '';
                $cat = explode('/', $myrow['category']);
                $url_cat = $this->ReturnField2('categories', $cat[1], 'url');
                
                $myrow['id_cat'] = $cat[1];
                
                if($url_cat){
                    $myrow['uri'] = 'http://shop.ruswater.com/'.$url_cat.'/'.$myrow['url'].'/';
                }else{
                    $myrow['uri'] = 'http://shop.ruswater.com/product/'.$myrow['url'].'/';
                }
                
                $Massive[$myrow['id']] = $myrow; // основные данные продукта
                $ArrVariantsId = explode(",", $myrow['variants_id']);
                foreach($ArrVariantsId as $key=>$val){
                    if($key == 0){$whereVariants = "id = '{$val}'";}
                    if($key != 0){$whereVariants .= " || id = '{$val}'";}
                }
                $query2 = "SELECT * FROM {$tbl2} WHERE {$whereVariants} ORDER BY position";
                $result2 = $this->IssetID($this->Query($query2));
                if($result2){
                    while($myrow2 = mysql_fetch_array($result2)){
                        $myrow2['price_v_s'] = ($myrow2['price_v']*(100-$myrow2['sale_v']))/100; // цена со скидкой в default валюте
                        $myrow2['price_v_p'] = $this->Price($myrow2['price_v'], $myrow2['sale_v']); // цена в выбранной валюте

                        if(!empty($myrow2['sale_v']) && $myrow2['sale_v'] != 0){
                            $myrow2['old_price_v_p'] = $this->Price($myrow2['price_v']);
                        }elseif($myrow2['old_price_v']){
                            $myrow2['old_price_v_p'] = $this->Price($myrow2['old_price_v']);    
                        }else{
                            $myrow2['old_price_v_p'] = '';
                        }
                        
                        $Massive[$myrow['id']]['variants'][] = $myrow2; // Варианты продукта
                    }
                }
                
                // Характеристики
                if($Massive[$myrow['id']]['property_str'] != "/" && !empty($Massive[$myrow['id']]['property_str'])){
                    $property = explode("/", $Massive[$myrow['id']]['property_str']);
                    if($property){
                        foreach($property as $key=>$val){
                            if(!empty($val)){
                                $query_p = "SELECT * FROM ".PREFIX."properties WHERE id='{$val}'";
                                $result_p = $this->IssetID($this->Query($query_p));
                                if($result_p){
                                    $myrow_p = mysql_fetch_array($result_p);
                                    $query_p_v = "SELECT * FROM ".PREFIX."properties_values WHERE properties_id='{$val}' ORDER BY position";
                                    $result_p_v = $this->IssetID($this->Query($query_p_v));
                                    if($result_p_v){
                                        $values_p_v = "";
                                        while($myrow_p_v = mysql_fetch_array($result_p_v)){
                                            if(strstr($Massive[$myrow['id']]['property_values_str'], "/{$myrow_p_v['id']}/")){
                                                $values_p_v .= $myrow_p_v['name'].", ";
                                            }
                                        }
                                        $values_p_v = substr($values_p_v, 0, strlen($values_p_v)-2);
                                        if($values_p_v){
                                            $Massive[$myrow['id']]['properties'][$myrow_p['id']]['name'] = $myrow_p['name'];
                                            $Massive[$myrow['id']]['properties'][$myrow_p['id']]['values'] = $values_p_v; 
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
            }
            return $Massive;
        }else{
            return false;
        }
    }
    */
    // Товары по заданному WHERE
    function GetProducts($where){
        $tbl1 = PREFIX."products"; // продукты
        $tbl2 = PREFIX."products_variants"; // варианты продуктов
        $query = "SELECT {$tbl1}.*, GROUP_CONCAT({$tbl2}.id) as variants_id
                  FROM {$tbl1} JOIN {$tbl2} ON {$tbl1}.id={$tbl2}.product_id
                  {$where}";
        $result = $this->IssetID($this->Query($query));
        if($result){
            while($myrow = mysql_fetch_array($result)){
                $myrow['image'] = explode(',', $myrow['image']);
                if($myrow['image'][0] == '' || !file_exists(SITE_DIR.$myrow['image'][0])){$myrow['image'][0] = '/images/nophoto.png';}
                $myrow['tags'] = explode(',', $myrow['tags']);
                $Massive[$myrow['id']] = $myrow; // основные данные продукта
                $ArrVariantsId = explode(",", $myrow['variants_id']);
                foreach($ArrVariantsId as $key=>$val){
                    if($key == 0){$whereVariants = "id = '{$val}'";}
                    if($key != 0){$whereVariants .= " || id = '{$val}'";}
                }
                $query2 = "SELECT * FROM {$tbl2} WHERE {$whereVariants} ORDER BY position";
                $result2 = $this->IssetID($this->Query($query2));
                if($result2){
                    while($myrow2 = mysql_fetch_array($result2)){
                        $myrow2['price_v_s'] = ($myrow2['price_v']*(100-$myrow2['sale_v']))/100; // цена со скидкой в default валюте
                        $myrow2['price_v_p'] = $this->Price($myrow2['price_v'], $myrow2['sale_v']); // цена в выбранной валюте

                        if(!empty($myrow2['sale_v']) && $myrow2['sale_v'] != 0){
                            $myrow2['old_price_v_p'] = $this->Price($myrow2['price_v']);
                        }elseif($myrow2['old_price_v']){
                            $myrow2['old_price_v_p'] = $this->Price($myrow2['old_price_v']);    
                        }else{
                            $myrow2['old_price_v_p'] = '';
                        }
                        
                        $Massive[$myrow['id']]['variants'][] = $myrow2; // Варианты продукта
                    }
                }
                
                // Характеристики
                if($Massive[$myrow['id']]['property_str'] != "/" && !empty($Massive[$myrow['id']]['property_str'])){
                    $property = explode("/", $Massive[$myrow['id']]['property_str']);
                    if($property){
                        foreach($property as $key=>$val){
                            if(!empty($val)){
                                $query_p = "SELECT * FROM ".PREFIX."properties WHERE id='{$val}'";
                                $result_p = $this->IssetID($this->Query($query_p));
                                if($result_p){
                                    $myrow_p = mysql_fetch_array($result_p);
                                    $query_p_v = "SELECT * FROM ".PREFIX."properties_values WHERE properties_id='{$val}' ORDER BY position";
                                    $result_p_v = $this->IssetID($this->Query($query_p_v));
                                    if($result_p_v){
                                        $values_p_v = "";
                                        while($myrow_p_v = mysql_fetch_array($result_p_v)){
                                            if(strstr($Massive[$myrow['id']]['property_values_str'], "/{$myrow_p_v['id']}/")){
                                                $values_p_v .= $myrow_p_v['name'].", ";
                                            }
                                        }
                                        $values_p_v = substr($values_p_v, 0, strlen($values_p_v)-2);
                                        if($values_p_v){
                                            $Massive[$myrow['id']]['properties'][$myrow_p['id']]['name'] = $myrow_p['name'];
                                            $Massive[$myrow['id']]['properties'][$myrow_p['id']]['values'] = $values_p_v; 
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
            }
            return $Massive;
        }else{
            return false;
        }
    }
    
    // Заказ по id
    function GetOrder($id){
        $query1 = "SELECT *, DATE_FORMAT(time,'%H:%i') as time FROM ".PREFIX."order WHERE id='{$id}'";
        $result1 = $this->IssetID($this->Query($query1));
        if($result1){
            $myrow1 = mysql_fetch_array($result1);
            $Massive['Order'] = $myrow1;
            $Massive['Order']['delivery_name'] = $this->ReturnField('delivery', $myrow1['delivery_id'], 'name');
            
            $query2 = "SELECT * FROM ".PREFIX."order_meta WHERE order_id='{$id}' ORDER BY id";
            $result2 = $this->IssetID($this->Query($query2));
            if($result2){
                while($myrow2 = mysql_fetch_array($result2)){
                    $Massive['Order']['meta'][] = array('meta_key'=> $myrow2['meta_key'], 'meta_value'=> $myrow2['meta_value'], 'meta_name' => $this->ReturnFieldAny('order_form', 'name_index', $myrow2['meta_key'], 'name'));
                }
            }
            
            $query3 = "SELECT * FROM ".PREFIX."order_products WHERE order_id='{$id}' ORDER BY id";
            $result3 = $this->IssetID($this->Query($query3));
            if($result3){
                while($myrow3 = mysql_fetch_array($result3)){
                    $Massive['Order']['products'][$myrow3['id']] = $myrow3;
                    /*
                    $arr = $this->GetProducts(" WHERE ".PREFIX."products_variants.id='{$myrow3['variant_id']}' GROUP BY ".PREFIX."products.id");
                    $Massive['Order']['products'][$myrow3['id']]['image'] = $arr[$myrow3['product_id']]['image'][0];
                    $Massive['Order']['products'][$myrow3['id']]['variants'] = $arr[$myrow3['product_id']]['variants'];
                    */
                }
            }
            
            return $Massive;
        }
    }
    
    /* Знак валюты по умолчанию */
    function SignCurrency(){
        $query = "SELECT * FROM ".PREFIX."currency WHERE main='1'";
        $result = $this->IssetID($this->Query($query));
        if($result){
            $myrow = mysql_fetch_assoc($result);
            return $myrow['sign'];
        }else{
            return FALSE;
        }
    }
    
    // Форматирование даты
    function DateFormat($value, $n = null){
        $montharray = array('01' => 'Января','02' => 'Февраля','03' => 'Марта','04' => 'Апреля','05' => 'Мая','06' => 'Июня','07' => 'Июля','08' => 'Августа','09' => 'Сентября','10' => 'Октября','11' => 'Ноября','12' => 'Декабря');
        $time = explode(' ',$value);
        $date = $time[0];
        $dateconvert = explode('-',$date);
        $year = $dateconvert[0];
        $month = $montharray[($dateconvert[1])];
        $day = $dateconvert[2];
        if($n == 1){
            return $day." ".$month;
        }elseif($n == 2){
            return $day;
        }elseif($n == 3){
            return $day."/".$dateconvert[1]."/".$year;
        }else{
            return $day." ".$month." ".$year;
        }
    } 
        
    /* Тема сайта */
    function SiteTheme(){
        $query = "SELECT * FROM ".PREFIX."settings WHERE id='1'";
        $result = $this->IssetID($this->Query($query));
        if($result){
            $myrow = mysql_fetch_assoc($result);
            return $myrow['value'];
        }else{
            return FALSE;
        }
    }
    
    /* Выбранный знак валюты */
    function ChosenCurrencySign(){
        if(isset($_SESSION['currency'])){
            $query1 = "SELECT * FROM ".PREFIX."currency WHERE id='{$_SESSION['currency']}'";// Сессия
            $result1 = $this->IssetID($this->Query($query1));
            if($result1){
                $myrow1 = mysql_fetch_array($result1);
                return $myrow1['sign'];
            }
        }else{
            $query2 = "SELECT * FROM ".PREFIX."currency WHERE main='1'";// Главная
            $result2 = $this->IssetID($this->Query($query2));
            if($result2){
                $myrow2 = mysql_fetch_array($result2);
                return $myrow2['sign'];
            }
        }
    }
    
    /* Шифрование пароля */
    function Password($password){
        return md5($password);
    }
    
    /* ID по урлу */
    function IDonURL($table, $url){
        $query = "SELECT id FROM ".PREFIX."{$table} WHERE url='{$url}'";
        $result = $this->Query($query);
        if($result){
            $myrow = mysql_fetch_array($result);
            return $myrow['id'];
        }else{
            return FALSE;
        }
    }
    
    // Определяем поле по ID
    function ReturnField($table, $id, $field){
        $query = "SELECT * FROM ".PREFIX."{$table} WHERE id='{$id}'";
        $result = $this->IssetID($this->Query($query));
        if($result){
            $myrow = mysql_fetch_array($result);
            return $myrow[$field];
        }else{
            return FALSE;
        }
    }
    
    // Определяем поле по параметру
    function ReturnFieldAny($table, $field, $value, $val){
        $query = "SELECT * FROM ".PREFIX."{$table} WHERE {$field}='{$value}'";
        $result = $this->IssetID($this->Query($query));
        if($result){
            $myrow = mysql_fetch_array($result);
            return $myrow[$val];
        }else{
            return false;
        }
    }
    
    // Определяем поле по параметрам
    function ReturnFieldWhere($table, $where, $val){
        $query = "SELECT * FROM ".PREFIX."{$table} WHERE {$where}";
        $result = $this->IssetID($this->Query($query));
        if($result){
            $myrow = mysql_fetch_array($result);
            return $myrow[$val];
        }else{
            return FALSE;
        }
    }
    
    // Обнавляем поле по ID
    function UpdateField($table, $field, $val, $id){
        $query = "UPDATE ".PREFIX."{$table} SET {$field}='{$val}' WHERE id='{$id}'";
        $result = $this->Query($query);
        if($result){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    // Определяем настройки системы - таблица settings
    function InstallationSettings(){
        $query = "SELECT * FROM ".PREFIX."settings";
        $result = $this->Query($query);
        if($result){
            while($myrow = mysql_fetch_array($result)){
                $this->$myrow['name'] = $myrow['value'];
            }
        }
        if(isset($_SESSION['display_product'])){$this->display_product = $_SESSION['display_product'];}
    }
    
    // Проверяем таблицу на существование определенных данных
    function DataTableField($data, $table, $field){
        $query = "SELECT * FROM ".PREFIX."{$table} WHERE {$field}='{$data}'";
        $result = $this->Query($query);
        if($result){
            $myrow = mysql_fetch_array($result);
            if(isset($myrow['id'])){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    
    
    
    // Формат даты
    function ReturnTypePrice($price){
        if($this->penny == 1){$penny = 2;}else{$penny = 0;}
        return number_format($price, $penny, $this->separator_penny, $this->separator_thousand);
    }
    
    // Древовидная функция
    function MapTree($dataset) {
    	$tree = array();
    	foreach ($dataset as $id=>&$node) {    
    		if (!$node['parent_id']) {
    			$tree[$id] = &$node;
    		}else{
                $dataset[$node['parent_id']]['childs'][$id] = &$node;
    		}
    	}
    	return $tree;
    }
    
    // Генерация пароля
    function GeneratePassword($number){
        $arr = array('a','b','c','d','e','f',  
                     'g','h','i','j','k','l',  
                     'm','n','o','p','r','s',  
                     't','u','v','x','y','z',  
                     'A','B','C','D','E','F',  
                     'G','H','I','J','K','L',  
                     'M','N','O','P','R','S',  
                     'T','U','V','X','Y','Z',  
                     '1','2','3','4','5','6',  
                     '7','8','9','0');  
        // Генерируем пароль  
        $pass = "";  
        for($i = 0; $i < $number; $i++){
            // Вычисляем случайный индекс массива  
            $index = rand(0, count($arr) - 1);  
            $pass .= $arr[$index];  
        }  
        return $pass;  
    }
    
    // Нормальный URL
    function NormalizeStringToURL($string){
    	static $lang2tr = array(

    		'й'=>'j','ц'=>'c','у'=>'u','к'=>'k','е'=>'e','н'=>'n','г'=>'g','ш'=>'sh',
    		'щ'=>'sh','з'=>'z','х'=>'h','ъ'=>'','ф'=>'f','ы'=>'y','в'=>'v','а'=>'a',
    		'п'=>'p','р'=>'r','о'=>'o','л'=>'l','д'=>'d','ж'=>'zh','э'=>'e','я'=>'ja',
    		'ч'=>'ch','с'=>'s','м'=>'m','и'=>'i','т'=>'t','ь'=>'','б'=>'b','ю'=>'ju','ё'=>'e','и'=>'i',
    
    		'Й'=>'j','Ц'=>'c','У'=>'u','К'=>'k','Е'=>'e','Н'=>'n','Г'=>'g','Ш'=>'sh',
    		'Щ'=>'sh','З'=>'z','Х'=>'h','Ъ'=>'','Ф'=>'f','Ы'=>'y','В'=>'v','А'=>'a',
    		'П'=>'p','Р'=>'r','О'=>'o','Л'=>'l','Д'=>'d','Ж'=>'zh','Э'=>'e','Я'=>'ja',
    		'Ч'=>'ch','С'=>'s','М'=>'m','И'=>'i','Т'=>'t','Ь'=>'','Б'=>'b','Ю'=>'ju','Ё'=>'e','И'=>'i',
            
            'a'=>'a', 'b'=>'b', 'c'=>'c', 'd'=>'d', 'e'=>'e',
            'f'=>'f', 'g'=>'g', 'h'=>'h', 'i'=>'i', 'j'=>'j',
            'k'=>'k', 'l'=>'l', 'm'=>'m', 'n'=>'n', 'o'=>'o',
            'p'=>'p', 'q'=>'q', 'r'=>'r', 's'=>'s', 't'=>'t',
            'u'=>'u', 'v'=>'v', 'w'=>'w', 'x'=>'x', 'y'=>'y', 'z'=>'z',
            
            'A'=>'a', 'B'=>'b', 'C'=>'c', 'D'=>'d', 'E'=>'e',
            'F'=>'f', 'G'=>'g', 'H'=>'h', 'I'=>'i', 'J'=>'j', 'K'=>'k',
            'L'=>'l', 'M'=>'m', 'N'=>'n', 'O'=>'o', 'P'=>'p',
            'Q'=>'q', 'R'=>'r', 'S'=>'s', 'T'=>'t', 'U'=>'u', 'V'=>'v',
            'W'=>'w', 'X'=>'x', 'Y'=>'y', 'Z'=>'z',
            
    		'á'=>'a', 'ä'=>'a', 'ć'=>'c', 'č'=>'c', 'ď'=>'d', 'é'=>'e', 'ě'=>'e',
    		'ë'=>'e', 'í'=>'i', 'ň'=>'n', 'ń'=>'n', 'ó'=>'o', 'ö'=>'o', 'ŕ'=>'r',
    		'ř'=>'r', 'š'=>'s', 'Š'=>'S', 'ť'=>'t', 'ú'=>'u', 'ů'=>'u', 'ü'=>'u',
    		'ý'=>'y', 'ź'=>'z', 'ž'=>'z',
    
    		'і'=>'i', 'ї' => 'i', 'b' => 'b', 'І' => 'i',

    		' '=>'-', '\''=>'', '"'=>'', '\t'=>'', '«'=>'', '»'=>'', '?'=>'', '!'=>'', '*'=>''
            
    	);
    	$url = preg_replace( '/[\-]+/', '-', preg_replace( '/[^\w\-\*]/', '', strtr( $string, $lang2tr ) ) );
    	return  $url;
    }
    
    // Debug
    function Debug($arr){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
    
}

?>
