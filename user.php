<?php
require_once "database/Connect.php";
require_once "header.php";
session_start();    
if(isset($_SESSION["message"])){
    $message = $_SESSION["message"];
    echo "<script>alert('$message')</script>";
    unset( $_SESSION["message"]);
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_POST['taskFilter']) ? $_POST['taskFilter'] : '';
$query = "SELECT * FROM tasks WHERE user_id = " . $_SESSION["id_user"];


if (!empty($search)) {  
    $query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";  
}


if ($filter === '1') {     
    $query .= " AND is_completed = 1";     
} elseif ($filter === '0') {     
    $query .= " AND is_completed = 0";     
}
$sql = mysqli_query($con, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel = "stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/index.css">
    <title>TodoList</title>
</head>
<body>

<div class="filter"> 
        <div class="filter"> 
    <form action="" method="post">  
        <select id="taskFilter" name="taskFilter" class="form-select" onchange="this.form.submit()">  
            <option value="" <?= $filter === '' ? "selected": '' ?>>Все</option>  
            <option value="1" <?= $filter === '1' ? "selected": '' ?>>Выполненные</option>  
            <option value="0" <?= $filter === '0' ? "selected": '' ?>>Не выполненные</option>  
        </select>  
    </form>  
    </div> 
 <!-- <?php var_dump($_SESSION['id_user'])?> -->
    </div>
    <main>
        <?php if (mysqli_num_rows($sql) != 0) {   
        while ($task = mysqli_fetch_assoc($sql)) {   
            ?>   
        <!-- <div class="tasks"> -->
            <form action="edit.php?id=<?= $task["id"] ?>" method="POST">
            <div class="task">
                <input type="checkbox" id="id_task" class="id_task" data-id="<?= $task["id"] ?>" <?= $task["is_completed"]=='1' ? "checked disabled": ""?> />
                <input type="text" required class="todo_form" name="title" value="<?= $task["title"] ?>" />
                <input type="text" required class="todo_form" name="description" value="<?= $task["description"] ?>" />
                <button type='submit'><img src="images/pencil.php" alt="">Редактировать</button> 
            </form>
                <a href="#" class="delete-task" data-id="<?= $task["id"] ?>"><button><img src="images/trash.php" alt="">Удалить</button></a>
            </div> 
           
            <?php } 
        } else {
            echo '<img src="images/Detective-check-footprint 1.png" alt="">';  
        }
        ?> 
        </main>
        
        <button type='button' class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="button">+</button>   


        <!-- модалка -->

 <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">  
     <div class="modal-dialog">  
         <div class="modal-content">  
             <div class="modal-header">  
                 <h1 class="modal-title fs-5" id="exampleModalLabel">Новая заметка</h1>  
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>  
             </div>  
             <div class="modal-body">  
                 <form method="post" action="task.php">  
                     <div class="mb-3">  
                         <label for="recipient-name" class="col-form-label">Название заметки:</label>  
                         <input type="text" name="title" class="form-control" id="recipient-name" placeholder="Название">  
                     </div>  
                     <div class="mb-3">  
                         <label for="recipient-name" class="col-form-label">Описание:</label>  
                         <input type="text" name="description" class="form-control" id="recipient-name" placeholder="Новая заметка">  
                     </div>  
             </div>  
             <div class="modal-footer">  
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button> 
                 <button type="submit" class="btn btn-primary" id="button">Создать</button>  
             </div> 
             </form> 
         </div>  
     </div>  
 </div>

 <form action="edit.php?id=<?= $task["id"] ?>" method="POST">
</form>

<script 
src="https://code.jquery.com/jquery-3.6.0.min.js">
</script>

<script> 
function CheckTask(){
    $('.id_task').change(function () {
        const taskId = $(this).data('id');
        confirm('Вы завершили это задание?');
        if(confirm) {
            $.ajax({
                url: '/check.php',
                type: 'Post',
                data: {id_task: taskId},
                success: function(response) {
                    let idd = '#' + response.id_task;
                    console. log(idd);
                    $(idd).prop('disabled', true);
                    $(idd).siblings('p.contains("Completed")').text('Completed: ' + response.is_completed);
                },
                error: function (xhr, status, error) {
                    console.error('Произошла ошибка: ' + status);
                }
            })
        }
    }) 
}


$(document).ready(function() {  
    $('.delete-task').on('click', function(e) {  
        e.preventDefault(); // Предотвращаем переход по ссылке  
        var taskId = $(this).data('id'); // Получаем ID задачи  
         
        $.ajax({  
            url: 'delete.php',  
            type: 'POST',  
            data: { id: taskId },  
            success: function(response) {  
                console.log(response); // Для отладки 
                if (response.trim() === 'success') {  
                    alert('Заметка успешно удалена');  
                    $('a.delete-task[data-id="' + taskId + '"]').closest('.card').fadeOut(300, function() {  
                        $(this).remove();  
                    });  
                } else {  
                    alert('Ошибка при удалении заметки: ' + response);  
                }  
            },  
            error: function() {  
                alert('Ошибка при выполнении запроса');  
            }  
        });  
    });  
});  
</script>
<!--            
 
            <div class="task">
                <input type="checkbox" id=""/>
                <label> NOTE #1 
                <button><img src="images/pencil.php" alt="">Редактирвоать</button>
                <button><img src="images/trash.php" alt="">Удалить</button>
            </div>
            <div class="task">
                <input type="checkbox" id=""/>
                <label> NOTE #1 
                <button><img src="images/pencil.php" alt="">Редактирвоать</button>
                <button><img src="images/trash.php" alt="">Удалить</button>
            </div>
            </ul>
        </div>
         -->
        <!-- <div class="button_plus">
            +
        </div>
    </main>

</body>
</html>