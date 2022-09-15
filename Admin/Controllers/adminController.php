<?php
class adminController
{
    function loginAction()
    {
        if(isset($_COOKIE['token'])){
            header('Location: /panel');
        }else{
            require_once 'Admin/Views/login/login.php';
        }
        
        
    }
}
