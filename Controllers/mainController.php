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
    }
    function authorizeAction()
    {

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $resultAuth = $this->checkLogPas($_POST['login'], $_POST['password']);
            if ($resultAuth) {
                print_r(json_encode($resultAuth));
            }
        } else {
            print_r(json_encode("NOT FOUND"));
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
            } catch (\Throwable $th) {
                print_r($th);
            }
        } else {
            http_response_code(501);
            print_r("Bad request");
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
                $myPosts = $profileM->getPosts($user['id']);

                print_r(json_encode($myPosts));
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
            }
        }
    }
    function friendAction()
    {
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            $friends = $profileM->getUserFriends($user["id"]);
            $current_friend = [];
            foreach ($friends as $friend) {
                if ($friend['first_user'] == $user['id']) {
                    $userprofile = $profileM->getUser($friend['twelf_user']);
                    array_push($current_friend, $userprofile);
                } else {
                    $userprofile = $profileM->getUser($friend['first_user']);
                    array_push($current_friend, $userprofile);
                }
            }

            print_r(json_encode($current_friend));
        }
    }
    function messagesAction()
    {
        $profileM = new ProfileModel();
        $user = $this->checkToken($_POST['token']);
        if ($user) {
            $mesGroup = $profileM->getMessagesGroup($user['id']);
            print_r(json_encode($mesGroup));
        }
    }
}
