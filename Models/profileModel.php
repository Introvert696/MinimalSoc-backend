<?php
class ProfileModel
{
    function getAllUsers()
    {
        $dbcon = require_once 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select * from users");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function register($login, $password, $name, $lastname, $date, $token, $sool)
    {
        $dbcon = require_once 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("INSERT INTO users(name,lastname,birdhday,login,password,sool,token) 
            VALUES ('$name','$lastname','$date','$login','$password','$sool','$token')
            ");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
}
