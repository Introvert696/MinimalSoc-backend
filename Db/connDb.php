<?php 
    $setting = require 'config/dbsetting.php';
    //print_r($setting);
    
    try {
        $conn = new PDO('mysql:host='.$setting['server'].';dbname='.$setting['dbname'], $setting['user'], $setting['password']);
        return $conn;
    } catch (\Throwable $th) { 
        print_r($th);
        return null;
    }
?>