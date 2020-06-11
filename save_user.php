<?php
require_once "connect.php";

$username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

if (mb_strlen($username) < 4 || mb_strlen($username) > 20){
    echo "Недопустимая длина логина";
    exit();
} elseif (mb_strlen($password) < 8 || mb_strlen($password) > 30){
    echo "Недопустимая длина пароля (от 8 до 20 символов)";
    exit();
}

$salt = "wvwu75bwk0m";
$password = md5($password.$salt);

$mysqli->query("INSERT INTO `user` (`login`, `password`) VALUES ('$username', '$password')");

$mysqli->close();

header('Location: /');

?>
