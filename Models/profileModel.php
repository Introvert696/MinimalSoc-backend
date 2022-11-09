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
            $sth = $conn->prepare("Select post_id,post_text,CONCAT((Select name from users where posts.post_creater=users.id),' ',(Select lastname from users where posts.post_creater=users.id)) as creater_user,post_creater,create_at,(Select user_photo from users where posts.post_creater=users.id) as user_photo from posts where post_creater = '$id'");
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
            $result = $sth->fetch(PDO::FETCH_ASSOC);

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
            $sth = $conn->prepare("Select mgr_id,title,first_user as first_id,twelf_user as twelf_id,concat((select name from users where message_group.first_user = users.id),' ',(select lastname from users where message_group.first_user = users.id)) as first_user,concat((select name from users where message_group.twelf_user = users.id),' ',(select lastname from users where message_group.twelf_user = users.id)) as twelf_user,create_at from message_group where ((first_user='$id')||(twelf_user='$id'))");
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
    function createMessageGroup($first_user, $twelf_user)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("INSERT INTO message_group(title,first_user,twelf_user) VALUES('NONE','$first_user','$twelf_user')");
            $sth->execute();
            $result = $sth->fetch();
            return $result;
        } else {
            exit();
        }
    }
    function getSearchResult($query_string)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("SELECT id,name,lastname,user_photo  from users WHERE (name LIKE '%" . $query_string . "%')OR(lastname LIKE '%" . $query_string . "%')");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getFriendPair($first_user, $second_user)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("SELECT * from friend where ((first_user = '$first_user') AND (twelf_user = '$second_user')) OR ((first_user = '$second_user') AND (twelf_user = '$first_user'))");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function createFriendPair($first_user, $second_user)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("INSERT INTO friend(first_user,twelf_user,friend_status) VALUES('$first_user','$second_user',0)");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getUserGroup($userId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select (select gr_id from group_soc where subscribe_users_to_group.gr_id = group_soc.id)as gr_id,(select title from group_soc where subscribe_users_to_group.gr_id = group_soc.id)as groupname,(select gr_photo from group_soc where subscribe_users_to_group.gr_id = group_soc.id)as gr_photo,(select create_at from group_soc where subscribe_users_to_group.gr_id = group_soc.id)as create_at  from subscribe_users_to_group where user = '$userId'");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getGroupInfo($groupId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select * from group_soc where id = '$groupId'");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function getGroupPost($groupId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("Select * from group_post where creater = '$groupId'");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function deletePost($postId, $userId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("delete from posts where (post_id = '$postId') AND (post_creater = '$userId')");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function deleteMesGroup($mesGroupId, $userId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("DELETE FROM `message_group` WHERE (`message_group`.`mgr_id` = '$mesGroupId') AND ((first_user='$userId')OR(twelf_user='$userId'))");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function deleteFriend($user_id, $friend_id)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("DELETE FROM `friend` WHERE (`friend_id` = '$friend_id' AND ((twelf_user='$user_id')OR(first_user='$user_id')))");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function deleteMessage($userId, $messageId)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("DELETE FROM message WHERE message_id = '$messageId' AND ((user_from = '$userId')OR(user_to = '$userId'))");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function createGroup($userId, $title, $desk)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("INSERT INTO `group_soc`(`title`, `desk`,`group_creater`) VALUES ('$title','$desk','$userId')");
            $sth->execute();
            $sth = $conn->prepare("Select * from group_soc where title='$title' and desk='$desk'");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
    function createGroupPost($groupId, $content)
    {
        $dbcon = require 'Db/connDb.php';
        if ($dbcon != null) {
            $sth = $conn->prepare("INSERT INTO `group_post`(`post_text`, `creater`) VALUES ('$content','$groupId')");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            exit();
        }
    }
}
