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
    function getSafeUsers()
    {
        $dbcon = require_once 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select id,name,lastname,birdhday,user_photo from users");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getPosts($id)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select post_id,post_text,CONCAT((Select name from users where posts.post_creater=users.id),' ',(Select lastname from users where posts.post_creater=users.id)) as creater_user,post_creater,create_at,(Select user_photo from users where posts.post_creater=users.id) as user_photo from posts where post_creater =" . $id);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }

    function getProfile($token)
    {

        $dbcon = require_once 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select id,concat(name,' ',lastname) as fio,login,status,user_photo from users where token='$token'");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getMyposts($id)
    {

        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select post_id,post_text,CONCAT((Select name from users where posts.post_creater=users.id),' ',(Select lastname from users where posts.post_creater=users.id)) as creater_user,post_creater,create_at,(Select user_photo from users where posts.post_creater=users.id) as user_photo from posts where post_creater =" . $id);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
}
