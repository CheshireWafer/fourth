<?php
require_once 'validation/connect.php';
$token=$_COOKIE['cookie_token'];
$query="SELECT login FROM users WHERE token = '$token'";
$result=mysqli_query($link, $query);
$user=mysqli_fetch_row($result);
$user=$user[0];
$query="SELECT login, rating, games, wins, loses FROM players ORDER BY rating DESC";
$result=mysqli_query($link, $query);
$rows=mysqli_num_rows($result);
$table='';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Таблица рейтингов</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css" rel="stylesheet" integrity="sha384-D/7uAka7uwterkSxa2LwZR7RJqH2X6jfmhkJ0vFPGUtPyBMF2WMq9S+f9Ik5jJu1" crossorigin="anonymous">
    <link rel="stylesheet" href="css/mayo.css">
</head>
<body>
<div align="center">
    <form action="index.php">
        <table>
            <br>
            <h5 style="font-family: 'Beer Money', sans-serif">
                <tr><th>Таблица рейтингов</th></tr>
            </h5>
            <tr>
                <td> Place </td>
                <td> Login </td>
                <td> Rating </td>
                <td> Games </td>
                <td> Wins </td>
                <td> Loses </td>
            </tr>
            <?php
            for ($tr=0; $tr<$rows; $tr++){
                $r=mysqli_fetch_row($result);
                if ($r[0]==$user){
                    $table .= '<tr style="color:red;">';
                }
                else {
                    $table .= '<tr>';
                }
                $table .= '<td>'.($tr+1).'</td>';
                for ($col=0; $col<5; $col++){
                    $table .= '<td>'.$r[$col].'</td>';
                }
                $table .= '</tr>';
            }
            echo $table;
            mysqli_close($link);
            ?>
            </tr>
        </table>
        <br>
        <button>Назад</button>
    </form>
</div>
</form>
</body>
</html>