<?php
require_once 'validation/connect.php';
$token=$_COOKIE['cookie_token'];
$query="SELECT login FROM users WHERE token = '$token'";
$result=mysqli_query($link, $query);
$user=mysqli_fetch_row($result);
$user=$user[0];
$query="SELECT user2 FROM games ORDER BY id DESC LIMIT 1";
$result=mysqli_query($link, $query);
$user2=mysqli_fetch_row($result);
$query="SELECT user1 FROM games ORDER BY id DESC LIMIT 1";
$result=mysqli_query($link, $query);
$user1 = mysqli_fetch_row($result);
if ($user1[0]==NULL){
    goto first;
}
if($user==$user1[0]){
    if ($user2[0]!=NULL){
        $query = "UPDATE players SET time = 0 WHERE login = '$user'";
        $result=mysqli_query($link, $query);
        header("Location: game.php");
        die();
    }
    goto exist;
}
if ($user2[0]==NULL){
    $query="UPDATE games SET user2 = '$user' ORDER BY id DESC LIMIT 1";
    $result=mysqli_query($link, $query);
    $query = "UPDATE players SET time = 0 WHERE login = '$user'";
    $result=mysqli_query($link, $query);
    header("Location: game.php");
    die();
}
else {
    first:
    $query="INSERT INTO games (user1) VALUE ('$user')";
    $result=mysqli_query($link, $query);
}
exist:
$time=time();
$time_on_site=time()-$_COOKIE['cookie_time'];
setcookie('cookie_time', $time);
$query = "UPDATE players SET time = $time_on_site WHERE login = '$user'";
$result=mysqli_query($link, $query);
mysqli_close($link);
?>

<!doctype html>
<html lang="ru">
<title>Ждём соперника</title>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css" rel="stylesheet" integrity="sha384-D/7uAka7uwterkSxa2LwZR7RJqH2X6jfmhkJ0vFPGUtPyBMF2WMq9S+f9Ik5jJu1" crossorigin="anonymous">
    <link rel="stylesheet" href="css/mayo.css">
</head>
<body>
<meta http-equiv="Refresh" content="5"/>
<form method="post" action="index.php">
    <div align = "center">
        <h1>Ждём соперника</h1> <br>
        <button><img src="images/icons/22skullnbones.png">Выйти из ожидания</button>
    </div>
</body>
</html>