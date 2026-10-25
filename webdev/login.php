<?php
session_start();

$host = 'localhost';
$username = 'root';
$password_db = '';
$db = 'calc';

$mysqli = new mysqli($host, $username, $password_db, $db);

$login = $_POST['login'];
$pwd = $_POST['pwd'];

$query = "SELECT name, pwd_hash, login FROM user WHERE login = ?";

$result = $mysqli->prepare($query);
$result->bind_param("s", $login);
$result->execute();

$data = $result->get_result();
$user_data = $data->fetch_assoc();

$password = isset($user_data['pwd_hash']) ? $user_data['pwd_hash'] : '';

if(password_verify($pwd, $password)) {
    $_SESSION['name'] = $user_data['name'];
    $_SESSION['login'] = $user_data['login'];
    echo "<h1>Добро пожаловать, " . $_SESSION['name'] . "!</h1>";
    echo("<meta http-equiv='refresh' content='3; url=calc.php' />");
}
else {
    echo "<h1>Неправильный логин или пароль!</h1>";
    echo("<meta http-equiv='refresh' content='3; url=login.html'></meta>");
}

$result->close();
$mysqli->close();
