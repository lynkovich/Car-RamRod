<?php
session_start();
if(empty($_SESSION['login_user']))
{
	header("Location: login/sign.php");
}
include '../classes/Room_Selection.class.php';
include '../config.php';
$beenset = false;
if(!empty($_SESSION['login_user'])&&!empty($_COOKIE['startTime'])&&!empty($_COOKIE['finishTime']))
{
	$selection = new Room_Selection();
	$selection->SelectTimeWithCookie($_COOKIE['startTime'], $_COOKIE['finishTime']);
	$selection->SelectRoom($_GET['roomNumber']);
	$beenset = true;
}
if($beenset)
{
	$selection->ConfirmRoom();
	header("Location: ../additionalOptions.php");
}
?>