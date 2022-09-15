<?php

class mainController
{
    function __construct()
    {
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
        $profileM = new ProfileModel();
        $users = $profileM->getAllUsers();
        foreach ($users as $user) {
            if ($user['login'] == $login && $user['password'] == $password) {
                return $user;
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
        $result = $profileM->getAllUsers();
        print_r(json_encode($result));
    }
    function authorizeAction()
    {

        if (isset($_POST['login']) && isset($_POST['password']) && $_POST['password'] != "" && $_POST['login'] != "") {
            $resultAuth = $this->checkLogPas($_POST['login'], $_POST['password']);
            if ($resultAuth) {
                print_r(json_encode($resultAuth));
            }
        } else {
            print_r(json_encode("NOT FOUND"));
        }
    }
    function registerAction()
    {
        // login, password, name, lastname, date
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
                print_r(false);
            }
        } else {
            // header('Content-Type: text/html; charset=utf-8');
            // require_once "Views/regist.html";
            http_response_code(501);
            print_r("Bad request");
        }
    }
}
