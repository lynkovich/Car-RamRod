<?php
session_start();
$error=''; 
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
$username=$_POST['username'];
$password=$_POST['password'];

$connection = mysql_connect("localhost", "lmaynar1", "Comfortstudies");
// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);

$db = mysql_select_db("lmaynar1", $connection);
$query = mysql_query("select * from users where Password='$password' AND Username='$username'", $connection);
$rows = mysql_num_rows($query);


if ($rows == 1) {
	while($row = mysql_fetch_assoc($query)){
		$type=$row['UserType'];
		$id=$row['StudentID'];
		if($type=="Student"||$type=="Faculty"||$type=="Admin"){
		$_SESSION['login_user']=$id;
		$_SESSION['login_username']=$username;
		$_SESSION['login_type']=$type;
		$tutorquery = mysql_query("select * from tutor where TutorID = $id;", $connection);
		$istutor = mysql_num_rows($tutorquery);
		if($istutor == 1)
		{
			$_SESSION['istutor'] = true;
		}

		header("location: ../home.php");
		}}}
else {
		$error = "Username or Password is invalid";
	}
mysql_close($connection);
}
}
?>