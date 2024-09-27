<?php 
require_once "Connect.php";  
session_start();  
 
$login = isset($_POST["login"]) ? $_POST["login"] : false;  
$password = isset($_POST["password"]) ? $_POST["password"] : false;  
 
if ($login && $password) {  
    $checkUser = mysqli_query($con, "SELECT * FROM users WHERE username='$login'"); 
     
    if (mysqli_num_rows($checkUser) > 0) { 
        $_SESSION["message"] = "Пользователь с таким логином уже существует!"; 
        header("Location: /"); 
    } else { 
        $passHash = password_hash($password, PASSWORD_DEFAULT); 
        $sql = mysqli_query($con, "INSERT INTO users (username, password_hash) VALUES ('$login', '$passHash')"); 
         
        if ($sql) { 
            $_SESSION["message"] = "Успех!";  
        } else { 
            $_SESSION["message"] = "Ошибка при регистрации!"; 
        } 
        header("Location: /"); 
    } 
} else {  
    $_SESSION["message"] = "Заполните все поля!";  
    // header("Location: /");  
    var_dump ($login, $pass);

}