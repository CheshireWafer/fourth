<?php
    $host = "localhost";
    $user = "root";
    $password = "92hJt6Su2nc";
    $database = "user";

    $mysqli = new mysqli($host, $user, $password, $database);

    if($mysqli->connect_errno) {
        die('Соединение не удалось: Код ошибки: '.$mysqli->connect_errno.' - '.$mysqli->connect_error);
    }

    if(!$mysqli->set_charset("utf8")) {
        die('Ошибка при загрузке набора символов utf8: '.$mysqli->errno.' - '.$mysqli->error);
    }
?>