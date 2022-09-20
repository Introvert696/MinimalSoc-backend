<?php
class ProfileModel
{
    function __construct()
    {
    }
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
    function createPost($id, $post_text)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("INSERT INTO posts(post_text,post_creater) VALUES ('$post_text','$id')");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getUser($id)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select id, concat((select name from users where id = '$id'),' ',(select lastname from users where id = '$id')) as fio, birdhday,user_photo,status from users where id='$id'");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else {
            exit();
        }
    }
    function getUserFriends($id)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare(
                "Select friend_id,concat((select name from users where friend.first_user = users.id),' ',(select lastname from users where friend.first_user = users.id)) as one_user,concat((select name from users where friend.twelf_user = users.id),' ',(select lastname from users where friend.twelf_user = users.id)) as two_user,first_user,twelf_user from friend WHERE ((first_user='$id')||(twelf_user='$id'))"
            );
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getMessagesGroup($id)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select mgr_id,title,concat((select name from users where message_group.first_user = users.id),' ',(select lastname from users where message_group.first_user = users.id)) as first_user,concat((select name from users where message_group.twelf_user = users.id),' ',(select lastname from users where message_group.twelf_user = users.id)) as twelf_user,create_at from message_group where ((first_user='$id')||(twelf_user='$id'))");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getMessagesFromGroup($mesGroupId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select message_id,user_from,user_to,send_at,concat((select name from users where message.user_from = users.id),' ',(select lastname from users where message.user_from = users.id)) as from_fio, concat((select name from users where message.user_to = users.id),' ',(select lastname from users where message.user_to = users.id)) as to_fio,content from message where message_group=" . $mesGroupId);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getOneMesGroup($mesGroupId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select * from message_group where mgr_id =" . $mesGroupId);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function createMessage($user_from, $user_to, $message_group, $content)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Insert into message(user_from,user_to,message_group,content) VALUES ('$user_from','$user_to','$message_group','$content')");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getFeedPost()
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select * from message_group where mgr_id =" . $mesGroupId);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
}
