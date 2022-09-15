<?php
$routers = require_once 'config/url.php';
// массив с юрлами админа
$admin_path = require_once 'Admin/config/url.php';

// получаем массив с юрлами по которому перешел чел
function get_uri()
{
    $string_raw_uri = $_SERVER['REQUEST_URI'];
    $raw_uri = explode("/", $string_raw_uri);
    array_shift($raw_uri);
    return $raw_uri;
}
// строка в массив
function explode_string($raw_string)
{
    $string_arr = explode("/", $raw_string);
    return $string_arr;
}
// получаем массив с юрлами
$uri_arr = get_uri();


if ($uri_arr[0] != null) {

    foreach ($admin_path as $key => $value) {

        if (isset($uri_arr[1]) && (isset($uri_arr[0])) && ($uri_arr[0] . "/" . $uri_arr[1] == $key)) {
            $id = 0;
            if (isset($uri_arr[2])) {
                $id = $uri_arr[2];
            }
            $controller_and_action_arr = explode_string($value);
            //print_r($controller_and_action_arr);
            $controller_name = $controller_and_action_arr[0] . "Controller";
            $action_name = $controller_and_action_arr[1] . "Action";
            try {
                require_once "Admin/Controllers/" . $controller_name . ".php";
                $controller = new $controller_name;
                $controller->$action_name($id);
                http_response_code(202);
                exit;
            } catch (\Throwable $th) {
                echo $th;
            }
            exit;
        } else if ((isset($uri_arr[0])) && ($uri_arr[0] == $key)) {
            $id = 0;
            if (isset($uri_arr[2])) {
                $id = $uri_arr[2];
            }
            $controller_and_action_arr = explode_string($value);
            //print_r($controller_and_action_arr);
            $controller_name = $controller_and_action_arr[0] . "Controller";
            $action_name = $controller_and_action_arr[1] . "Action";
            try {
                require_once "Admin/Controllers/" . $controller_name . ".php";
                $controller = new $controller_name;
                $controller->$action_name($id);
                http_response_code(202);
                exit;
            } catch (\Throwable $th) {
                echo $th;
            }
            exit;
        } else {
        }
    }

    // проверка юрла на тот который есть
    foreach ($routers as $path => $controller_action_name) {
        // если есть 3 подуровня типо items/get/23
        if (isset($uri_arr[1]) && ($uri_arr[0] . "/" . $uri_arr[1] == $path) && isset($uri_arr[2])) {
            $id = $uri_arr[2];
            $controller_and_action_arr = explode_string($controller_action_name);
            $controller_name = $controller_and_action_arr[0] . "Controller";
            $action_name = $controller_and_action_arr[1] . "Action";
            try {
                require_once "Controllers/" . $controller_name . ".php";
                $controller = new $controller_name;
                $controller->$action_name($id);
                http_response_code(202);
                exit;
            } catch (\Throwable $th) {
                echo $th;
            }
            exit;
        } else if ($uri_arr[0] == $path) {
            if ($uri_arr[0]) {
                $controller_and_action_arr = explode_string($controller_action_name);
                $controller_name = $controller_and_action_arr[0] . "Controller";
                $action_name = $controller_and_action_arr[1] . "Action";
                // пробуем подключить контроллеры
                try {
                    require_once "Controllers/" . $controller_name . ".php";
                    $controller = new $controller_name;
                    $controller->$action_name();
                    http_response_code(202);
                    exit;
                } catch (\Throwable $th) {
                    echo $th;
                    http_response_code(501);
                }
            }
        } else {
            http_response_code(404);
        }
    }
} else {


    // mainPages
    require_once "Controllers/mainController.php";

    $controller = new mainController;
    $controller->indexAction();
    exit;
}
