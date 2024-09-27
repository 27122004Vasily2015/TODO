<?php 
    require_once "database/Connect.php";
    session_start();  
     
    $title = isset($_POST["title"]) ? $_POST["title"] : false;  
    $description = isset($_POST["description"]) ? $_POST["description"] : false;  
    $user_id = $_SESSION["id_user"];  
    $id_task = isset($_GET["id"]) ? ($_GET["id"]) : false;  
     
    if ($title && $description && $id_task) {  
        $sql = "UPDATE tasks SET user_id='$user_id', title='$title', description='$description' WHERE id='$id_task'";  
        $result = mysqli_query($con, $sql);  
     
        if ($result) { 
            $_SESSION["message"] = "Успех!"; 
        } else { 
            $_SESSION["message"] = "Ошибка: " . mysqli_error($con); 
        } 
        header("Location: /user.php");
    } else {  
        $_SESSION["message"] = "Ошибка!";  
    }