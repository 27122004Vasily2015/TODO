<?php 
require_once "database/Connect.php";

if (isset($_POST['id'])) { 
    $id = $_POST['id']; 
     
    // Выполняем запрос на удаление 
    $result = mysqli_query($con, "DELETE FROM tasks WHERE id = $id"); 
 
    if ($result) { 
        echo 'success'; // Возвращаем текстовый ответ 
    } else { 
        echo 'error'; // Возвращаем текст ошибки 
    } 
}
?>