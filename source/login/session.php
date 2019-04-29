<?php
$connection = mysql_connect("localhost", "lmaynar1", "Comfortstudies");

$db = mysql_select_db("lmaynar1", $connection);
session_start();
$user_check=$_SESSION['login_user'];
$sql=mysql_query("select Username from users where Username='$user_check'", $connection);
$row = mysql_fetch_assoc($sql);
$login_session =$row['Username'];
if(!isset($login_session)){
mysql_close($connection);
header('Location: sign.php');
}
?>