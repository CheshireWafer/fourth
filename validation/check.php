<?php
require_once "connect.php";

$username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

$salt = "wvwu75bwk0m";
$password = md5($password.$salt);

$result = $mysqli->query("SELECT * FROM `user` WHERE `login` = '$username' AND `password` = '$password'");
$user = $result->fetch_assoc();
if (count($user) == 0){
    echo "Пользователь не найден";
    exit();
}

setcookie('user', $user['login'], time() + 86400, "/");

$mysqli->close();

header('Location: ../search_game.php');

?>
