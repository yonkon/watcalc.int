<?php

/*
 * Выбор товаров
 */
 
class Products extends Sibloma{

    public $page = 1;
    public $start = 0;
    
    function Runs(){
        self::GetProducts();
    }

    function GetProducts(){
        $this->tbl1 = PREFIX."products"; // продукты
        $this->tbl2 = PREFIX."products_variants"; // варианты продуктов
                
        // сортировка
        $this->order = "{$this->tbl1}.position DESC";
        
        // вывод на главной
        if(!isset($_SESSION["type_sort"]) && !isset($_GET['module'])){
            // отмеченные на главной
            if($this->output_product_main == 1){
                $this->where .= " && {$this->tbl1}.on_main = '1'";
                $this->order = "{$this->tbl1}.position_on_main DESC";
            }
            // беспорядочный вывод
            if($this->output_product_main == 2){
                $this->order = "RAND()";
            }
        }
        
        // Выводить товары которых нетна складе
        if($this->type_stock == 0){
            $stock = " && {$this->tbl2}.stock_v != '0'";
        }
        
        // Условие
        $this->where = "{$this->tbl1}.active = '1' && {$this->tbl2}.price_v != '0' {$stock}";
        if($_GET['module'] == "products" && $_GET['type'] == "new"){
            $this->where .= " && {$this->tbl1}.new = '1'"; 
            $this->order = "{$this->tbl1}.position_new DESC";
        }
        if($_GET['module'] == "products" && $_GET['type'] == "hit"){
            $this->where .= " && {$this->tbl1}.hit = '1'";
            $this->order = "{$this->tbl1}.position_hit DESC";
        }
        if($_GET['module'] == "products" && $_GET['type'] == "sale"){
            $this->where .= " && {$this->tbl1}.sale = '1'";
            $this->order = "{$this->tbl1}.position_sale DESC";
        }
        if($_GET['module'] == "products" && $_GET['type'] == "recommended"){
            $this->where .= " && {$this->tbl1}.recommended = '1'";
            $this->order = "{$this->tbl1}.position_recommended DESC";
        }
        
        // Количество выводимого товара
        if(!isset($_GET['module'])){
            $this->number = $this->number_of_index;
            if($this->output_product_main == 1){$this->where .= " && {$this->tbl1}.on_main = '1'";}
        }else{
            $this->number = $this->number_of_catalog;
        }
        if(isset($_SESSION["number_of_product"])){
            $this->number = $_SESSION["number_of_product"];
        }
        
        // Категория
        
        if($_GET['module'] == 'topMenu'){
            
            $id_cat = $this->ReturnFieldAny("top_menu", "url", $_GET['page'], "id");
            
            $this->where .= " && {$this->tbl1}.category like '%/".$id_cat."/%'";
            $this->order = "{$this->tbl1}.position_category DESC";

        }
                
        
        
        //=========== ТИП СОРТИРОВКИ ===========//
        if($_SESSION["sort"] == 2 || $_SESSION["sort"] == 4){ // порядок сортировки
            $sort_order = "DESC";
        }else{
            $sort_order = "ASC";
        }
        if($_SESSION["sort"] == 1 || $_SESSION["sort"] == 2){$this->order = "{$this->tbl1}.name {$sort_order}";} // имени
        if($_SESSION["sort"] == 3 || $_SESSION["sort"] == 4){$this->order = "{$this->tbl2}.price_v {$sort_order}";} // цене
        
        //======================================//
        
        $this->where_to_price = $this->where; // максимальная цена для фильтра вообще
        
        // Проверили фильтр
        //self::Filter();
        
        // Проверили поиск
        //self::Search();
        
        // Проверили теги
        //self::Tags();

        // Навигация
        if($_GET['page']){$this->page = $_GET['page'];}
        PNavigation::VariablesNavigation($this->number, $this->page, "SELECT COUNT(DISTINCT {$this->tbl1}.id) FROM {$this->tbl1} JOIN {$this->tbl2} ON {$this->tbl1}.id={$this->tbl2}.product_id WHERE {$this->where}");
        
        $where = "WHERE {$this->where}
                  GROUP BY {$this->tbl1}.id
                  ORDER BY {$this->order}
                  LIMIT {$this->start}, {$this->num}";

        $this->Massive['MProducts']['MProducts'] = $this->GetProducts($where);
        
        $this->Massive['MProducts']['CurrencySign'] = $this->ChosenCurrencySign(); // валюта
        
    }
    
    // Поиск
    function Search(){
        if($_GET['module'] == 'search'){
            if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
                
                $ExplSearch = explode(' ', $_GET['keywords']);
                foreach($ExplSearch as $key1=>$val1){
                    $newWhere = $this->where;
                    $newWhereM[] = $newWhere." && {$this->tbl1}.name like '%{$val1}%'";
                    $newWhereM[] = $newWhere." && {$this->tbl2}.articul_v like '%{$val1}%'";
                }
                if($newWhereM){
                    foreach($newWhereM as $key2=>$val2){
                        if($key2 == 0){
                            $newWhere = $val2;
                        }else{
                            $newWhere = $newWhere." || ".$val2;
                        }
                    }
                    $this->where = $newWhere;
                }
                
            }
        }
    }
    
    // Теги
    function Tags(){
        if($_GET['module'] == 'tags'){
            if(isset($_GET['tag']) && !empty($_GET['tag'])){
                $this->where .= " && {$this->tbl1}.tags like '%{$_GET['tag']}%'";
            }
        }
    }
    
    // Фильтры
    function Filter(){
        
        // Выборка по цене
        if(isset($_GET['price']['min']) && !empty($_GET['price']['min'])){
            $this->where .= " && {$this->tbl2}.price_v >= '{$_GET['price']['min']}'";
        }
        if(isset($_GET['price']['max']) && !empty($_GET['price']['max']) && $_GET['price']['max'] != 0){
            $this->where .= " && {$this->tbl2}.price_v <= '{$_GET['price']['max']}'";
        }
        
        // Select
        if($_GET['s']){
            foreach($_GET['s'] as $key=>$val){
                if(!empty($val)){
                    $this->where .= " && {$this->tbl1}.property_values_str like '%/{$val}/%'";
                }
            }
        }
        
        // Radio
        if($_GET['r']){
            foreach($_GET['r'] as $key=>$val){
                if(!empty($val)){
                    $this->where .= " && {$this->tbl1}.property_values_str like '%/{$val}/%'";
                }
            }
        }
        
        // От и До
        if($_GET['ft']){
            foreach($_GET['ft'] as $key=>$val){
                unset($arr_ft);
                if(!empty($val['min']) || !empty($val['max'])){
                    
                    if(empty($val['min'])){$val['min'] = 0;}
                    if(empty($val['max'])){$val['max'] = 1000000;}

                    $query = "SELECT id,name FROM ".PREFIX."properties_values WHERE properties_id='{$key}' && name+0 >= '{$val['min']}' && name+0 <= '{$val['max']}'";
                    $result = $this->IssetID($this->Query($query));
                    if($result){
                        while($myrow = mysql_fetch_array($result)){
                            if($myrow['id']){
                                $arr_ft[] = "{$this->tbl1}.property_values_str LIKE '%/{$myrow['id']}/%'";
                            }
                        }
                    }
                }
                if($arr_ft){
                    $line_ft = implode(' or ',$arr_ft);
                    if($line_ft){
                        $this->where = $this->where." && ({$line_ft})";
                    }
                }
            }
        }
        
        if($_GET['b']){
            foreach($_GET['b'] as $key=>$val){
                $b[] = "{$this->tbl1}.brand = '".$val."'";    
            }
            if($b){
                $line_b = implode(' || ', $b);
                if($line_b){
                    $this->where = $this->where." && ({$line_b})";
                }
            }
        }
        
        // Checkbox
        if($_GET['c']){
            $arr_c = self::Combinations($_GET['c']);
            if($arr_c){
                foreach($arr_c as $key=>$val){
                    if($val){
                        foreach($val as $key_v=>$val_v){
                            $arr_variant[$key][$key_v] = "{$this->tbl1}.property_values_str LIKE '%/{$val_v}/%'";    
                        }
                    }
                }
                if($arr_variant){
                    foreach($arr_variant as $key_s=>$val_s){
                        $arr_str[] = implode(' && ',$val_s);
                    }
                    if($arr_str){
                        $line_c = implode(' || ', $arr_str);
                        if($line_c){
                            $this->where .= " && ({$line_c})";
                        }
                    }
                }
            }
        }
        
    }
    
    // Комбинации массивов
    function Combinations(&$arr, $idx = 0) {
        static $line = array();
        static $keys;
        static $max;
        static $results;
        if ($idx == 0) {
            $keys = array_keys($arr);
            $max = count($arr);
            $results = array();
        }
        if ($idx < $max) {
            $values = $arr[$keys[$idx]];
            foreach ($values as $value) {
                array_push($line, $value);
                self::Combinations($arr, $idx+1);
                array_pop($line);
            } 
        }else{
            $results[] = $line;
        }
        if ($idx == 0) return $results;
    }
    
}

Products::Runs();

?>
