<?php
    session_start();
    require '../config.php';

    if(!isset($_SESSION['name'])) {
        http_response_code(401);
        echo('Вы не вошли в аккаунт!');
        exit;
    }

    $login = $_SESSION['login'];
    $operation = $_REQUEST['operation'];
    $x = $_REQUEST['x'];
    $y = $_REQUEST['y'];

    $allowed = ['plus', 'minus', 'mult'];

    if(!in_array($operation, $allowed, true)) {
        http_response_code(400);
        echo('Операция не содержится в списке допустимых!');
        exit;
    }

    if(!is_numeric($x) || !is_numeric($y)) {
        http_response_code(400);
        echo('Операнды x и y должны быть числами!');
        exit;
    }

    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

    if($mysqli->connect_error) {
        die('Ошибка подключения к БД');
    }

    $query = "INSERT INTO operation (login, operation, x, y, z) VALUES(?, ?, ?, ?, ?)";

    $z = 0;

    if($operation === 'plus') {
        $z = $x + $y;
    }
    else if($operation === 'minus') {
        $z = $x - $y;
    }
    else {
        $z = $x * $y;
    }

    $result = $mysqli->prepare($query);
    $result->bind_param('ssddd', $login, $operation, $x, $y, $z);
    $result->execute();

    $result->close();
    $mysqli->close();

    echo($z);
