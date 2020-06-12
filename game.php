<?php
require_once 'validation/connect.php';
$token=$_COOKIE['cookie_token'];
$query="SELECT login FROM users WHERE token = '$token'";
$result=mysqli_query($link, $query);
$user=mysqli_fetch_row($result);
$user=$user[0];
$query="SELECT id FROM games WHERE user1 = '$user' OR user2 = '$user'";
$result=mysqli_query($link, $query);
$id=mysqli_fetch_row($result);
$id=$id[0];
$query="SELECT status FROM games WHERE id = '$id'";
$result=mysqli_query($link, $query);
$status=mysqli_fetch_row($result);
if ($status[0]==0){
    $coin=rand(1,2);
    $query="UPDATE games SET status = '$coin' WHERE id = '$id'";
    $result=mysqli_query($link, $query);
}
$query="SELECT user1 FROM games WHERE id = '$id'";
$result=mysqli_query($link, $query);
$user1=mysqli_fetch_row($result);
$user1=$user1[0];
?>
<!DOCTYPE html>
<html lang="ru">
<title>Свин</title>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css" rel="stylesheet" integrity="sha384-D/7uAka7uwterkSxa2LwZR7RJqH2X6jfmhkJ0vFPGUtPyBMF2WMq9S+f9Ik5jJu1" crossorigin="anonymous">
    <link rel="stylesheet" href="css/mayo.css">
</head>
<style>
    div1 {
        position: absolute;
        top: 20%;
        left: 30%;
        transform: translate(-50%, -50%);
    }
    div2 {
        position: absolute;
        top: 20%;
        left: 70%;
        transform: translate(-50%, -50%);
    }
    div3 {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<body>
<form method="post">
    <div1>
        <h2>
            <?php
                print "$user";
            ?>
        </h2>
        <br>
        <h3 align = "center">
            <?php
            if ($user==$user1){
                $query="SELECT score1 FROM games WHERE id = '$id'";
                $result=mysqli_query($link, $query);
                $your_score=mysqli_fetch_row($result);
                print "$your_score[0]";
            }
            else {
                $query="SELECT score2 FROM games WHERE id = '$id'";
                $result=mysqli_query($link, $query);
                $your_score=mysqli_fetch_row($result);
                print "$your_score[0]";
            }
            ?>
        </h3>
    </div1>
    <br>
    <div2>
        <h2>
            <?php
            if ($user==$user1){
                $query="SELECT user2 FROM games WHERE id='$id'";
                $result=mysqli_query($link, $query);
                $opp_name=mysqli_fetch_row($result);
                print "$opp_name[0]";
            }
            else {
                $query="SELECT user1 FROM games WHERE id='$id'";
                $result=mysqli_query($link, $query);
                $opp_name=mysqli_fetch_row($result);
                print "$opp_name[0]";
            }
            ?>
        </h2>
        <br>
        <h3 align = "center">
            <?php
            if ($user==$user1){
                $query="SELECT score2 FROM games WHERE id='$id'";
                $result=mysqli_query($link, $query);
                $your_opps_score=mysqli_fetch_row($result);
                print "$your_opps_score[0]";
            }
            else {
                $query="SELECT score1 FROM games WHERE id='$id'";
                $result=mysqli_query($link, $query);
                $your_opps_score=mysqli_fetch_row($result);
                print "$your_opps_score[0]";
            }
            ?>
        </h3>
    </div2>
    <div3 align="center" >
        <h2>
            <?php
            $query="SELECT status FROM games WHERE id='$id'";
            $result=mysqli_query($link, $query);
            $status=mysqli_fetch_row($result);
            if (($status[0]==1 AND $user==$user1) OR ($status[0]==2 AND $user!=$user1)) {
                print "Ваша очередь";
            }
            if (($status[0]==1 AND $user!=$user1) OR ($status[0]==2 AND $user==$user1)) {
                print "Очередь соперника, ждите";
                header("Refresh:10");
                die();
            }
            if (($status[0]==3 AND $user==$user1) OR ($status[0]==4 AND $user!=$user1)) {
                print "Вы выиграли<br/>";
                $query="SELECT games FROM players WHERE login = '$user'";
                $result=mysqli_query($link, $query);
                $games=mysqli_fetch_row($result);
                $games=$games[0];
                $query="SELECT wins FROM players WHERE login = '$user'";
                $result=mysqli_query($link, $query);
                $wins=mysqli_fetch_row($result);
                $wins=$wins[0];
                if ($games!=0){
                    $winrate=round(($wins[0]/$games[0])*100);
                    if ($winrate==100){
                        $coef=50;
                    }
                    else {
                        $coef=100-$winrate;
                    }
                }
                else {
                    $coef=1;
                }
                $query="UPDATE players SET rating = rating + '$coef' WHERE login = '$user'";
                $result=mysqli_query($link, $query);
                $query="UPDATE players SET games = games + 1 WHERE login = '$user'";
                $result=mysqli_query($link, $query);
                $query="UPDATE players SET wins = wins + 1 WHERE login = '$user'";
                $result=mysqli_query($link, $query);
                print "Возвращаемся к началу...";
                mysqli_close($link);
                header ('Refresh:10; URL = index.php');
                die();
            }
            if (($status[0]==3 AND $user!=$user1) OR ($status[0]==4 AND $user==$user1)) {
                print "Вы проиграли<br/>";
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
                print "Возвращаемся к началу...";
                mysqli_close($link);
                header ('Refresh:10; URL = index.php');
                die();
            }
            ?>
        </h2>
        <label><input type="submit" name="drop" class="btn" value="Бросить кубик"></label>
        <label><input type="submit" name="pass" class="btn" value="Спасовать"></label> <br>
        <h2>
            <?php
            if(array_key_exists('drop',$_POST)){
                $query="SELECT total FROM games WHERE id='$id'";
                $result=mysqli_query($link, $query);
                $total=mysqli_fetch_row($result);
                $bet=rand(1,6);
                $total[0]=$total[0]+$bet;
                print "Счёт: $total[0]|";
                print "Результат броска: $bet<br/>";
                if ($bet==1){
                    print "Выпала единица :(";
                    $query="UPDATE games SET bet='0' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $query="UPDATE games SET total='0' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    if ($user==$user1){
                        $query="UPDATE games SET status='2' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        header("Refresh:5");
                        die();
                    }
                    else {
                        $query="UPDATE games SET status='1' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        header("Refresh:5");
                        die();
                    }
                }
                $query="UPDATE games SET bet='$bet' WHERE id='$id'";
                $result=mysqli_query($link, $query);
                $query="SELECT total FROM games WHERE id='$id'";
                $result=mysqli_query($link, $query);
                $total=mysqli_fetch_row($result);
                $total[0]=$total[0]+$bet;
                $query="UPDATE games SET total='$total[0]' WHERE id='$id'";
                $result=mysqli_query($link, $query);
            }
            if(array_key_exists('pass',$_POST)){
                if ($user==$user1){
                    $query="SELECT score1 FROM games WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $score1=mysqli_fetch_row($result);
                    $query="SELECT total FROM games WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $total=mysqli_fetch_row($result);
                    $score1[0]=$score1[0]+$total[0];
                    $query="UPDATE games SET score1='$score1[0]' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    if ($score1[0]>=100) {
                        $query="UPDATE games SET status='3' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        $query="UPDATE games SET bet='0' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        $query="UPDATE games SET total='0' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        header("Refresh:0");
                        die();
                    }
                    $query="UPDATE games SET status='2' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $query="UPDATE games SET bet='0' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $query="UPDATE games SET total='0' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    header("Refresh:0");
                    die();
                }
                else {
                    $query="SELECT score2 FROM games WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $score2=mysqli_fetch_row($result);
                    $query="SELECT total FROM games WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $total=mysqli_fetch_row($result);
                    $score2[0]=$score2[0]+$total[0];
                    $query="UPDATE games SET score2='$score2[0]' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    if ($score2[0]>=100) {
                        $query="UPDATE games SET status='4' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        $query="UPDATE games SET bet='0' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        $query="UPDATE games SET total='0' WHERE id='$id'";
                        $result=mysqli_query($link, $query);
                        header("Refresh:0");
                        die();
                    }
                    $query="UPDATE games SET status='1' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $query="UPDATE games SET bet='0' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    $query="UPDATE games SET total='0' WHERE id='$id'";
                    $result=mysqli_query($link, $query);
                    header("Refresh:0");
                    die();
                }
            }
            ?>
        </h2>
        <br>
        <h2>
            <br> <a href="index.php"> Сдаться и признать поражение </a>
        </h2>
    </div3>
</form>
</body>
</html>