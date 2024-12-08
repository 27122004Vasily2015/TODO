<?php
require_once "database/Connect.php";
require_once "header.php";
session_start();

// Получаем параметры поиска и фильтра из запроса
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['taskFilter']) ? $_GET['taskFilter'] : '';  // Получаем фильтр из GET

$query = "SELECT * FROM tasks WHERE user_id = ?";  // Используем параметризированный запрос

if (!empty($search)) {
    // Добавляем условие для поиска по названию или описанию
    $search = "%" . $search . "%";
    $query .= " AND (title LIKE ? OR description LIKE ?)";
}

if ($filter === '1') {
    $query .= " AND is_completed = 1";  // Фильтруем по выполненным
} elseif ($filter === '0') {
    $query .= " AND is_completed = 0";  // Фильтруем по не выполненным
}

// Подготавливаем запрос
$stmt = $con->prepare($query);
if (!empty($search)) {
    // Привязываем параметры для поиска
    $stmt->bind_param("sss", $_SESSION["id_user"], $search, $search);
} else {
    $stmt->bind_param("s", $_SESSION["id_user"]);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/index.css">
    <title>TodoList</title>
</head>
<body>

<div class="filter">
    <!-- Форма для фильтрации задач -->
    <form method="get" id="filterForm">
        <select id="taskFilter" name="taskFilter" class="select">
            <option value="" <?= $filter === '' ? "selected" : '' ?>>Все</option>
            <option value="1" <?= $filter === '1' ? "selected" : '' ?>>Выполненные</option>
            <option value="0" <?= $filter === '0' ? "selected" : '' ?>>Не выполненные</option>
        </select>
        <input type="submit" value="Применить">
    </form>
</div>

<main>
    <?php if (mysqli_num_rows($result) != 0) {
        while ($task = mysqli_fetch_assoc($result)) {
            ?>
            <form action="edit.php?id=<?= $task["id"] ?>" method="POST">
                <div class="task">
                    <input type="checkbox" id="id_task" class="id_task" data-id="<?= $task["id"] ?>"
                        <?= $task["is_completed"] == '1' ? "checked disabled" : "" ?> />
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

<!-- Модальное окно -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Обработчик изменения фильтра
    $('#taskFilter').on('change', function() {
        var filterValue = $(this).val();  // Получаем значение фильтра

        $.ajax({
            url: 'index.php',  // Перезагружаем страницу с новым фильтром
            type: 'GET',
            data: {
                search: '<?= $_GET['search'] ?? '' ?>',  // Сохраняем параметры поиска
                taskFilter: filterValue  // Передаем выбранный фильтр
            },
            success: function(response) {
                // Если запрос успешен, обновляем содержимое страницы
                $('main').html($(response).find('main').html());
            },
            error: function() {
                alert('Ошибка при фильтрации задач');
            }
        });
    });

    // Обработчик для изменения состояния чекбокса
    $('.id_task').on('change', function() {
        var taskId = $(this).data('id');  // Получаем ID задачи
        var isChecked = $(this).is(':checked') ? 1 : 0;  // Получаем новое значение (1 - выполнено, 0 - не выполнено)

        // Отправляем AJAX-запрос на сервер для обновления состояния
        $.ajax({
            url: 'update_task_status.php',  // Путь к файлу, который обновляет статус задачи
            type: 'POST',
            data: {
                id: taskId,
                is_completed: isChecked
            },
            success: function(response) {
                if (response.trim() === 'success') {
                    console.log('Статус задачи успешно обновлен');
                } else {
                    alert('Ошибка при обновлении статуса задачи');
                }
            },
            error: function() {
                alert('Ошибка при выполнении запроса');
            }
        });
    });

    $('.delete-task').on('click', function(e) {
        e.preventDefault(); 
        var taskId = $(this).data('id'); 
        
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { id: taskId },
            success: function(response) {
                if (response.trim() === 'success') {
                    alert('Заметка успешно удалена');
                    $('a.delete-task[data-id="' + taskId + '"]').closest('.task').fadeOut(300, function() {
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

</body>
</html>
