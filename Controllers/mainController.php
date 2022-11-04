<?php

class mainController
{
    //Корс которые делает мозг
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
    //проверка токена
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
    // Проверка логина и пароля для авторизации
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
            $token = md5($date . $password . $login);
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
    //вывод постов пользователя
    function feedpostAction()
    {
        // Корса, которая опять делает мозг -_-
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

                    $temp_post = $profileM->getPosts($f['id']);

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
    // Получение информации о профиле
    function profileinfoAction()
    {
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $profile = $profileM->getProfile($_POST['token']);
            print_r(json_encode($profile));
        }
    }
    // Получение "своих" постов
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
    // Создание поста
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
    // Получение информации выбранного профиля
    function userprofileAction()
    {
        if (isset($_POST['token']) && isset($_POST['user_id'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $userprofile = $profileM->getUser($_POST['user_id']);
                print_r(json_encode($userprofile));
            } else {
                //http_response_code(403);

            }
        }
    }
    // вспомогательная функция для получения друзей
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
    // Получение друзей
    function friendAction()
    {
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            $current_friend = $this->getFriend($user['id']);
            print_r(json_encode($current_friend));
        } else {
            http_response_code(403);
        }
    }
    // Получение сообщений выбранного диалога между пользователями
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
                        http_response_code(404);
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
                }
            }
        }
    }
    // Отправка сообщения 
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
                } else {
                    http_response_code(501);
                }
            }
        }
    }
    // Создание нового диалога
    function createdialogAction()
    {
        if (isset($_POST['token']) && isset($_POST['user_id'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                try {
                    $message_group = $profileM->getMessagesGroup($_POST['user_id']);


                    $createnew = false;
                    if (empty($message_group)) {
                        $createnew = true;
                    } else {
                        foreach ($message_group as $m) {

                            if ((($m['first_id'] == $user['id']) && ($m['twelf_id'] == $_POST['user_id'])) || (($m['first_id'] == $_POST['user_id']) && ($m['twelf_id'] == $user['id']))) {
                                print_r(json_encode($m));
                                $createnew = false;
                                break;
                            } else {
                                $createnew = true;
                            }
                        }
                    }
                    if ($createnew) {
                        $creater_message_group = $profileM->createMessageGroup($user['id'], $_POST['user_id']);
                        print_r(json_encode("created"));
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    print_r(json_encode($th));
                }
            }
        }
    }
    // Функция поиска (пока только пользователей)
    function searchAction()
    {
        if (isset($_POST['token']) && isset($_POST['query_string'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $resultQuery = $profileM->getSearchResult($_POST['query_string']);
                print_r(json_encode($resultQuery));
            }
        }
    }
    // Добавление в друзья
    function addfriendAction()
    {
        if (isset($_POST['token']) && isset($_POST['friend_id'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $result_friend = $profileM->getFriendPair($user['id'], $_POST['friend_id']);
                if (!empty($result_friend)) {
                    print_r(json_encode($result_friend));
                } else {
                    //если связи нету, то создаем новую
                    try {
                        $result_create = $profileM->createFriendPair($user['id'], $_POST['friend_id']);
                        print_r(json_encode($result_create));
                    } catch (\Throwable $th) {
                        print_r(json_encode("Error"));
                    }
                }

                //получаем и проверям есть ли такая пара в таблице
            }
        }
    }
    // Получение сообществ на которые подписан юзер
    // добавить аргумент, который если он равен нулль,
    // то выводятся группы свои, а если указан, то 
    // группы пользователя по id
    function getusergroupAction()
    {
        if (isset($_POST['token'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $userGroup = $profileM->getUserGroup($user['id']);
                print_r(json_encode($userGroup));
            }
        }
    }
    // Получение информации о группе по id
    function getgroupAction()
    {
        if (isset($_POST['token']) && isset($_POST['groupId'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $groupInfo = $profileM->getGroupInfo($_POST['groupId']);
                $groupPosts = $profileM->getGroupPost($_POST['groupId']);
                array_push($groupInfo, $groupPosts);
                if ($groupInfo != null) {
                    print_r(json_encode($groupInfo));
                } else {
                    http_response_code(404);
                    print_r(json_encode(404));
                }
            }
        }
    }
    // Удаление поста, который создал пользователь
    function deletepostAction()
    {
        if (isset($_POST['token']) && isset($_POST['postId'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                try {
                    $deleteResult = $profileM->deletePost($_POST['postId'], $user['id']);
                    print_r(json_encode($deleteResult));
                } catch (\Throwable $th) {
                    http_response_code(404);
                    print_r(json_encode("Error"));
                }
            }
        }
    }
    // Удаление Диалога
    function deletemessagegroupAction()
    {
        if (isset($_POST['token']) && isset($_POST['mesGroupId'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                //вызов модели на удаление группы
                try {
                    $resultdelte = $profileM->deleteMesGroup($_POST['mesGroupId'], $user['id']);
                    print_r(json_encode("Delete Succefuly"));
                    http_response_code(202);
                } catch (\Throwable $th) {
                    //throw $th;
                    http_response_code(502);
                }
            }
        }
    }
    // Удаление сообщений
    function deletefriendAction()
    {

        if (isset($_POST['token']) && isset($_POST['friend_id'])) {
            $profileM = new ProfileModel();
            $user = $this->checkToken($_POST['token']);
            if ($user) {
                $result = $profileM->deleteFriend($user['id'], $_POST['friend_id']);
                print_r(json_encode($result));
            }
        }
    }
}
