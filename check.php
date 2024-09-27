<?php
    header(heasder: 'Content-Type: application/json');
    require_once "Connect.php";

    $id_task = isset($POST['id_task']) ? $POST['id_task']: false ;
    $update_time = date(format: "Y-m-d H:i:s");

    $sql = mysqli_query(mysql: $con, query: "UPDATE `tasks` SET `is_completed` = 1, `updated_at` = '$update_time' WHERE id = $id_task");

    if($sql){
        $response = [
            `id_task` => $id_task,
            `is_complited` => 1,
            `status` => 'ok'
        ];
    } else {
        $response = [`status` => `error`];
    }
    echo json_encode(value: $response);

?>