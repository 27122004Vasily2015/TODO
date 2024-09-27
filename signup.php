<?php
session_start();    
require_once "header.php";

if(isset($_SESSION["message"])){
    $message = $_SESSION["message"];
    echo "<script>alert('$message')</script>";
    unset( $_SESSION["message"]);
}
?>
<div class="container">
        <h1>Регистрации</h1>
        <form action="database/signup_db.php" method="POST">
            <div class="mb-3">
                <label for="login" class="form-label">Логин</label>
                <input type="text" class="form-control" name = "login" id="login" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" name = "password" id="password"required >
            </div>
            <input type="submit" class="button" value="Зарегистрироваться">
            <div class="form-text">Есть аккаунт?<a href="/index.php">Войти</a></div>
        </form>
    </div>