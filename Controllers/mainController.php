<?php

class mainController
{
    function cors()
    {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }
    function __construct()
    {
        $this->cors();
        header('Content-Type: application/json; charset=utf-8');
        require_once "Models/profileModel.php";
    }
    function checkToken($token)
    {
        $profileM = new ProfileModel();
        $result = $profileM->getAllUsers();
        foreach ($result as $user) {
            if ($user['token'] == $token) {
                return $user;
            }
        }
        return false;
    }
    function checkLogPas($login, $password)
    {
        $password = md5(md5($password));
        $profileM = new ProfileModel();
        $users = $profileM->getAllUsers();
        foreach ($users as $user) {
            if ($user['login'] == $login) {
                if ($user['password'] == $password) {
                    return $user;
                }
            }
        }
        return false;
    }

    function indexAction()
    {
    }
    function usersAction()
    {

        $profileM = new ProfileModel();
        $result = $profileM->getSafeUsers();
        print_r(json_encode($result));
        exit();
    }
    function authorizeAction()
    {

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $resultAuth = $this->checkLogPas($_POST['login'], $_POST['password']);
            if ($resultAuth) {
                print_r(json_encode($resultAuth));
                exit();
            }
        } else {
            print_r(json_encode("NOT FOUND"));
            exit();
            //
        }
    }
    function registerAction()
    {
        if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['date'])) {
            $login = $_POST['login'];
            $password = md5(md5($_POST['password']));
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $date = $_POST['date'];
            $token = md5($date . $password);
            $sool = $date;
            try {
                $profileM = new ProfileModel();
                $regResult = $profileM->register($login, $password, $name, $lastname, $date, $token, $sool);
                print_r(json_encode($regResult));
                http_response_code(202);
                exit();
            } catch (\Throwable $th) {
                print_r($th);
                exit();
            }
        } else {
            http_response_code(501);
            print_r("Bad request");
            exit();
        }
    }
    function feedpostAction()
    {

        $this->cors();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        //print_r($_POST);
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);

            if ($user) {
                $current_friend = $this->getFriend($user['id']);
                $myPosts = $profileM->getPosts($user['id']);
                $postarr = [];
                foreach ($current_friend as $f) {
                    $temp_post = $profileM->getPosts($f[0]['id']);
                    foreach ($temp_post as $p) {
                        array_push($postarr, $p);
                    }
                }
                foreach ($myPosts as $p) {
                    array_push($postarr, $p);
                }

                print_r(json_encode($postarr));
            }
        }
    }

    function profileinfoAction()
    {
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $profile = $profileM->getProfile($_POST['token']);

            print_r(json_encode($profile));
        }
    }
    function mypostsAction()
    {
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                if (isset($_POST['user_id'])) {
                    $myPosts = $profileM->getMyposts($_POST['user_id']);
                } else {
                    $myPosts = $profileM->getMyposts($user['id']);
                }


                print_r(json_encode($myPosts));
            }
        }
    }
    function createpostAction()
    {
        if (isset($_POST['token']) && isset($_POST['post_text'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $myPosts = $profileM->createPost($user['id'], $_POST['post_text']);

                print_r(json_encode($myPosts));
                exit();
            }
        }
    }
    function userprofileAction()
    {
        if (isset($_POST['token']) && isset($_POST['user_id'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $userprofile = $profileM->getUser($_POST['user_id']);

                print_r(json_encode($userprofile));
                exit();
            } else {
                http_response_code(403);
                exit();
            }
        }
    }
    //получение друзей
    function getFriend($userId)
    {
        $profileM = new ProfileModel();
        $friends = $profileM->getUserFriends($userId);
        $current_friend = [];
        foreach ($friends as $friend) {
            if ($friend['first_user'] != $userId) {
                $userprofileone = $profileM->getUser($friend['first_user']);
                array_push($current_friend, $userprofileone);
            }
            if ($friend['twelf_user'] != $userId) {
                $userprofiletwo = $profileM->getUser($friend['twelf_user']);
                array_push($current_friend, $userprofiletwo);
            }
        }
        return $current_friend;
    }
    function friendAction()
    {
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            $current_friend = $this->getFriend($user['id']);
            print_r(json_encode($current_friend));
        } else {
            http_response_code(403);
            exit();
        }
    }
    function messagesAction()
    {
        if (isset($_POST['token'])) {
            if (isset($_POST['selected_mail_group'])) {
                $profileM = new ProfileModel();
                $user = $this->checkToken($_POST['token']);
                if ($user) {
                    $messages = $profileM->getMessagesFromGroup($_POST['selected_mail_group']);
                    $current_message = [];
                    foreach ($messages as $m) {
                        if (($m['user_from'] == $user['id']) || ($m['user_to'] == $user['id'])) {
                            array_push($current_message, $m);
                        }
                    }
                    if (empty($current_message)) {
                        http_response_code(403);
                        exit();
                    }
                    print_r(json_encode($current_message));
                }
            } else {
                $profileM = new ProfileModel();
                $user = $this->checkToken($_POST['token']);
                if ($user) {
                    $mesGroup = $profileM->getMessagesGroup($user['id']);
                    print_r(json_encode($mesGroup));
                } else {
                    http_response_code(403);
                    exit();
                }
            }
        }
    }
    function sendmessageAction()
    {
        if (isset($_POST['token']) && isset($_POST['selected_mail_group']) && isset($_POST['content'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $message_group = $profileM->getOneMesGroup($_POST['selected_mail_group'])[0];

                if (($message_group['first_user'] == $user['id']) || ($message_group['twelf_user'] == $user['id'])) {
                    $to_user = 0;
                    if ($message_group['first_user'] == $user['id']) {
                        $to_user = $message_group['twelf_user'];
                    } else {
                        $to_user = $message_group['first_user'];
                    }
                    $message = $profileM->createMessage($user['id'], $to_user, $message_group["mgr_id"], $_POST['content']);
                    print_r(json_encode($message));
                    http_response_code(202);

                    exit();
                } else {
                    http_response_code(501);
                    exit();
                }
            }
        }
    }
    function createdialogAction()
    {
    }
}
