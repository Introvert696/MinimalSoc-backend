<?php 
 
class panelModel{

    //функция для обработки запросов
    function query($query_string,$args){
        $conn = require "Db/connDb.php";
        if ($conn != null) {
            $sth = $conn->prepare($query_string);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            exit();
        } else {
            return null;
            exit();
        }
    }
    //получаем всех юзеров
    function getUsers(){
        $result = $this->query("Select * from users",null);
        return $result;
        
    }
    //получаем все паредметы
    function getAllItems(){
        $result = $this->query("Select * from works",null);
        return $result;
    }
    // получаем одну статью
    function getOneItem($id){
        $id = intval($id);
        $result = $this->query("SELECT * FROM works WHERE id = ".$id,null);
        return $result;
    }
    //изменяем статью
    function editItem($id,$title,$desk,$image,$text){
        $id = intval($id);
        $result =$this->query("UPDATE `works` SET `title`='$title',`desk`='$desk',`image`='$image',`text`='$text' WHERE id=".$id,null);
        return $result;
    }
    //добавляем статью
    function addItem($title,$desk,$image,$text){
        $result =$this->query("INSERT INTO `works`(`title`, `desk`, `image`, `text`) VALUES ('$title','$desk','$image','$text')",null);
        return $result;
    }
    //удаляем статью
    function deleteItem($id){
        $id = intval($id);
        $result =$this->query("DELETE FROM `works` WHERE id=".$id,null);
        return $result;

    }
}
?>