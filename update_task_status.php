<?php
require_once "database/Connect.php";
session_start();

if (isset($_POST['id']) && isset($_POST['is_completed'])) {
    $taskId = (int)$_POST['id'];  
    $isCompleted = (int)$_POST['is_completed'];  

    $query = "UPDATE tasks SET is_completed = ? WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($query);
    
    $stmt->bind_param("iis", $isCompleted, $taskId, $_SESSION["id_user"]);

    if ($stmt->execute()) {
        echo 'принята';  
    } else {
        echo 'ошибка';  
    }

    $stmt->close();
}
?>
