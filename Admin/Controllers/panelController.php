<?php
require_once "Admin/Models/panelModel.php";
class panelController
{
    private function checkUser($login, $password)
    {
        $panelModel = new panelModel();
        $resultUser = $panelModel->getUsers();
        foreach ($resultUser as $key => $value) {

            if (($value['login'] == $login) && ($value['password'] == md5($password))) {
                return $value['token'];
            } else {
                return null;
            }
        }
    }
    private function checkToken($token)
    {
        $panelModel = new panelModel();
        $resultUser = $panelModel->getUsers();
        foreach ($resultUser as $key => $value) {

            if ($value['token'] == $token) {
                return true;
            } else {
                return false;
            }
        }
    }
    function indexAction()
    {
        if (isset($_POST["login"]) && isset($_POST["password"]) && $_POST["login"] != null && $_POST["password"] != null) {
            $result = $this->checkUser($_POST["login"], $_POST["password"]);
            print_r($result);
            if ($result != null) {
                $_POST = null;
                echo 222222222222222222;
                setcookie("token", $result);
                header('Location: /panel');
                exit();
            } else {
                header('Location: /admin');
                exit();
            }
            exit();
        }

        if (isset($_COOKIE['token'])) {
            //reque
            $result_check_token = $this->checkToken($_COOKIE['token']);
            if ($result_check_token) {
                //get info about
                $panelModel = new panelModel();
                $items_arr = $panelModel->getAllItems();
                //get amount
                $amout_items = count($items_arr);
                //last item
                $last_item = $items_arr[$amout_items - 1];
                require_once 'Admin/Views/panelViews.php';
            } else {
                header('Location: /admin');
                exit();
            }
        } else {
            header('Location: /admin');
            exit();
        }
    }
    // отображение всех статей
    function itemsAction()
    {
        if (isset($_COOKIE['token'])) {
            $result_check_token = $this->checkToken($_COOKIE['token']);
            if ($result_check_token) {
                $panelModel = new panelModel();
                $items_arr = $panelModel->getAllItems();
                //print_r($items_arr);
                require_once 'Admin/Views/itemsViews.php';
            }
        } else {
            header('Location: /admin');
            exit();
        }
    }
    // получение и редактирование одной стать
    function editAction($id)
    {
        if (isset($_COOKIE['token']) && $id != null) {
            //reque
            $result_check_token = $this->checkToken($_COOKIE['token']);
            if ($result_check_token) {
                $panelModel = new panelModel();
                if ($_SERVER['REQUEST_METHOD'] == "GET") {
                    $item = $panelModel->getOneItem($id)[0];
                    require_once 'Admin/Views/editViews.php';
                } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    print_r($_POST);
                    if (isset($_POST['title']) && isset($_POST['desk']) && isset($_POST['image']) && isset($_POST['text'])) {
                        $result = $panelModel->editItem($id, $_POST['title'], $_POST['desk'], $_POST['image'], $_POST['text']);
                        header('Location: /panel/items');
                        exit();
                    }
                    exit();
                }
            }
        } else {
            header('Location: /panel');
            exit();
        }
    }
    // удаление предмета
    function deleteAction($id)
    {
        if (isset($_COOKIE['token'])) {
            //reque
            $result_check_token = $this->checkToken($_COOKIE['token']);
            if ($result_check_token) {
                $panelModel = new panelModel();
                $panelModel->deleteItem($id);
                header('Location: /panel/items');
                exit();
            }
        } else {
            header('Location: /admin');
            exit();
        }
    }
    function createAction()
    {
        if (isset($_COOKIE['token'])) {
            //reque
            $result_check_token = $this->checkToken($_COOKIE['token']);
            if ($result_check_token) {
                $panelModel = new panelModel();
                if ($_SERVER['REQUEST_METHOD'] == "GET") {
                    require_once 'Admin/Views/createViews.php';
                } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    if (isset($_POST['title']) && isset($_POST['desk']) && isset($_POST['image']) && isset($_POST['text'])) {
                        $result = $panelModel->addItem($_POST['title'], $_POST['desk'], $_POST['image'], $_POST['text']);
                        header('Location: /panel/items');
                        exit();
                    }
                }
            }
        } else {
            header('Location: /admin');
            exit();
        }
    }
    // выход из акка
    function exitAction()
    {
        if (isset($_COOKIE['token'])) {
            unset($_COOKIE['token']); 
        setcookie('token', null);
            header('Location: /admin');
            exit();
            
        } else {
            header('Location: /admin');
            exit();
        }
    }
}
