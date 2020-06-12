<?php
    $host = "localhost";
    $user = "root";
    $password = "92hJt6Su2nc";
    $database = "pig";

    $link = new mysqli($host, $user, $password, $database);

    if($link->connect_errno) {
        die('Соединение не удалось: Код ошибки: '.$link->connect_errno.' - '.$link->connect_error);
    }

    if(!$link->set_charset("utf8")) {
        die('Ошибка при загрузке набора символов utf8: '.$link->errno.' - '.$link->error);
    }
?>