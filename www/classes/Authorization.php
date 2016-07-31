<?php

/*
 * Класс проверки Авторизации админа
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Session.php'; new Session();

class Authorization{
    
    // Проверка авторизации
    function Admin(){
        if(isset($_POST['FormLogin'])){
            if(isset($_POST['Login']) && !empty($_POST['Login']) && isset($_POST['Password']) && !empty($_POST['Password'])){
                
                $Login = $this->CleanData($_POST['Login'],"string");
                $Password = $this->Password($this->CleanData($_POST['Password'],"string"));
                $RememberMe = $this->CleanData($_POST['RememberMe'],"checkbox");
                
                $myrow = self::UserBase($Login);
                
                if(isset($myrow['id'])){
                    if($Password == $myrow['password']){
                        if($RememberMe == 1){
                            if(Session::AuthorizationCookieAdminLogin($Login)){
                                if(Session::AuthorizationCookieAdminPassword($Password)){
                                    return TRUE;
                                }else{return FALSE;}
                            }else{return FALSE;}
                        }else{
                            if(Session::AuthorizationSessionAdminLogin($Login)){
                                if(Session::AuthorizationSessionAdminPassword($Password)){
                                    return TRUE;
                                }else{return FALSE;}
                            }else{return FALSE;}
                        }
                    }else{return FALSE;}
                }else{return FALSE;}
                
            }
        }elseif(Session::AuthorizationTrue()){
            if(isset($_GET['logout'])){
                Session::AuthorizationClose();
                return FALSE;
            }else{
                if(isset($_SESSION['UserAdmin']['Login'])){$LoginQuery = $_SESSION['UserAdmin']['Login'];}
                if(isset($_COOKIE["UserAdminLogin"])){$LoginQuery = $_COOKIE["UserAdminLogin"];}
                if(isset($_SESSION['UserAdmin']['Password'])){$PasswordQuery = $_SESSION['UserAdmin']['Password'];}
                if(isset($_COOKIE["UserAdminPassword"])){$PasswordQuery = $_COOKIE["UserAdminPassword"];}
                
                $myrow = self::UserBase($LoginQuery);
                
                if(isset($myrow['id'])){
                    if($PasswordQuery == $myrow['password']){
                        return TRUE;    
                    }else{return FALSE;}
                }else{return FALSE;}
            }
        }else{return FALSE;}
    }
    
    // Проверка доступности модуля модераторам 
    function AccessModule($Module){
        if(isset($_SESSION['UserAdmin']['Login'])){$LoginQuery = $_SESSION['UserAdmin']['Login'];}
        if(isset($_COOKIE["UserAdminLogin"])){$LoginQuery = $_COOKIE["UserAdminLogin"];}
        
        $myrow = self::UserBase($LoginQuery);

        if($myrow['access'] == "ALL"){
            return TRUE;
        }else{
            if(strstr($myrow['access'], "{$Module},")){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
    }
    
    // Выборка пользователя из базы
    function UserBase($Login){
        $query = "SELECT * FROM ".PREFIX."users_admin WHERE login LIKE '{$Login}'";
        $result = Functions::Query($query);
        return mysql_fetch_assoc($result);
    }
    
    function MassUserAdmin(){
        if(isset($_SESSION['UserAdmin']['Login'])){$LoginQuery = $_SESSION['UserAdmin']['Login'];}
        if(isset($_COOKIE["UserAdminLogin"])){$LoginQuery = $_COOKIE["UserAdminLogin"];}
        $admin = self::UserBase($LoginQuery);
        return $admin['name'];
    }
    
}

?>
