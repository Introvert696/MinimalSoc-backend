<?php
$setting = require 'config/dbsetting.php';
//print_r($setting);

try {
    $conn = new PDO('mysql:host=' . $setting['server'] . ';dbname=' . $setting['dbname'], $setting['user'], $setting['password']);
    $conn->query("SET NAMES utf8");
    $conn->query("SET CHARACTER SET utf8");
    $conn->query("SET character_set_connection=utf8");
    return $conn;
} catch (\Throwable $th) {
    print_r($th);
    return null;
}
