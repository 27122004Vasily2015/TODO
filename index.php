<?php
require_once "header.php";

session_start();    


if(isset($_SESSION["message"])){
    $message = $_SESSION["message"];
    echo "<script>alert('$message')</script>";
    unset( $_SESSION["message"]);
}
?>
<div class="container">
        <h1>Добро пожаловать в TodoList</h1>
        <form action="../database/signin-db.php" method="POST">
            <div class="mb-3">
                <label for="login" class="form-label">Логин</label>
                <input type="text" class="form-control" name = "login" id="login" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" name = "password" id="password"required >
            </div>
            <input type="submit" class="button" value="Войти">
            <div class="form-text">Нет аккаунта?<a href="/signup.php">Зарегистрироваться</a></div>
        </form>
    </div>
