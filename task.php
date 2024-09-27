<?php 
 
 require_once "database/Connect.php";
session_start(); 
 
$title = isset($_POST["title"]) ? $_POST["title"] : false; 
$description = isset($_POST["description"]) ? $_POST["description"] : false; 
$user_id = $_SESSION["id_user"]; 
 
 
if ($title and $description) { 
    $sql = "INSERT INTO tasks(`user_id`, `title`, `description`) VALUES ('$user_id','$title','$description')"; 
    $result = mysqli_query($con, $sql); 
 
    if ($result) { 
        $_SESSION["message"] = "Успех!"; 
        header("Location: /user.php"); 
    } else { 
        $_SESSION["message"] = "Ошибка создания заметки!"; 
        header("Location: /user.php"); 
    } 
 
} else { 
    $_SESSION["message"] = "Заполните все поля!"; 
    header("Location: /user.php"); 
 
}

