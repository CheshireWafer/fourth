<?php
require_once 'validation/connect.php';
if (!isset($_COOKIE['cookie_token'])) {
    header("Location: login.php");
    die();
}
$token=$_COOKIE['cookie_token'];
$query="SELECT login FROM users WHERE token = '$token'";
$result=mysqli_query($link, $query);
$user=mysqli_fetch_row($result);
$user=$user[0];
$create_time=$_COOKIE['cookie_create_time'];
$re_time=time()-$create_time;
if ($re_time > 3600) {
    try {
        $token = bin2hex(random_bytes(32));
    } catch (Exception $e) {
    }
    $query="UPDATE users SET token = '$token' WHERE login = '$user'";
    $result=mysqli_query($link, $query);
    setcookie('cookie_create_time', time());
    setcookie('cookie_token', $token);
}
$query="SELECT status FROM games WHERE user1='$user' OR user2='$user'";
$result=mysqli_query($link, $query);
$status=mysqli_fetch_row($result);
if ($status[0]==1 OR $status[0]==2) {
    $query="UPDATE games SET status = 4 WHERE user1 = '$user'";
    $result=mysqli_query($link, $query);
    $query="UPDATE games SET status = 3 WHERE user2 = '$user'";
    $result=mysqli_query($link, $query);
    $query="SELECT games FROM players WHERE login = '$user'";
    $result=mysqli_query($link, $query);
    $games=mysqli_fetch_row($result);
    $games=$games[0];
    $query="SELECT wins FROM players WHERE login = '$user'";
    $result=mysqli_query($link, $query);
    $wins=mysqli_fetch_row($result);
    $wins=$wins[0];
    if ($games!=0){
        if ($wins!=0){
            $winrate=round(($wins[0]/$games[0])*100);
            $coef=$winrate;
        }
        else {
            $coef=50;
        }
    }
    else {
        $coef=100;
    }
    $query="UPDATE players SET rating = rating - '$coef' WHERE login = '$user'";
    $result=mysqli_query($link, $query);
    $query="UPDATE players SET games = games + 1 WHERE login = '$user'";
    $result=mysqli_query($link, $query);
    $query="UPDATE players SET loses = loses + 1 WHERE login = '$user'";
    $result=mysqli_query($link, $query);
}
else {
    $query="DELETE FROM games WHERE user1 = '$user' OR user2 = '$user'";
    $result=mysqli_query($link, $query);
}
$query="SELECT login FROM players";
$result=mysqli_query($link, $query);
for ($i=0; $i<mysqli_num_rows($result); ++$i){
    $acc = mysqli_fetch_row($result);
    if($user==$acc[0]){
        goto exist;
    }
}
$query = "INSERT INTO players (login) VALUE ('$user')";
$result=mysqli_query($link, $query);
exist:
$query = "UPDATE players SET time = NULL WHERE login = '$user'";
$result=mysqli_query($link, $query);
mysqli_close($link);
setcookie('cookie_time', 0);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>The pig game</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" href="css/mayo.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/beermoney/beermoney.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--===============================================================================================-->
</head>
<body>
<form method="post" action="awaiting.php">
    <div align="center">
        <br> <h3 style="font-family: 'Beer money',sans-serif">
            <a href="RateTable.php" , style="color: #151719"> Таблица рейтингов </a>
        </h3> <br>
        <br> <h1 style="font-family: 'Beer money',sans-serif">
            <?php
                     print "Привет, $user!";
                ?>
        </h1> <br>
        <br> <button class="login100-form-btn" style="font-family: Arial,sans-serif">
            Играть
        </button> <br>
        <br> <h3 style="font-family: 'Beer money',sans-serif">
            <br> <a href="validation/auth.php" , style="color: #151719"> Выйти из аккаунта </a>
        </h3> <br>
    </div>
</form>
</body>
</html>