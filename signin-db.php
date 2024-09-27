<?php 
require_once "../header.php"; 
require_once "Connect.php"; 
session_start(); 
 
$login = isset($_POST["login"]) ? $_POST["login"] : false; 
 
$password = isset($_POST["password"]) ? $_POST["password"] : false; 

if ($login and $password) { 
    $sql = "SELECT * FROM users WHERE username = '$login'"; 
    $result = mysqli_query($con, $sql); 
 
    if (mysqli_num_rows($result) != 0) { 
        $user = mysqli_fetch_assoc($result); 
        if (password_verify($password, $user["password_hash"])) { 
            $_SESSION["id_user"] = $user["id"];  
            $_SESSION["message"] = "Успех!"; 
            header("Location: /user.php"); 
        } else { 
            $_SESSION["message"] = "Неверный пароль"; 
            header("Location: /"); 
        } 
    } else { 
        $_SESSION["message"] = "Неверный логин"; 
        header("Location: /"); 
    } 
} else { 
    $_SESSION["message"] = "Заполните все поля!"; 
    header("Location: /"); 
}