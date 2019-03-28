<?php
session_start();
include 'Room_Selection.class.php';
include 'config.php';
$beenset = false;
if(!empty($_SESSION['studentID'])&&!empty($_COOKIE['startTime'])&&!empty($_COOKIE['finishTime']))
{
	$selection = new Room_Selection();
	$selection->SelectTimeWithCookie($_COOKIE['startTime'], $_COOKIE['finishTime']);
	$selection->SelectRoom($_GET['roomNumber']);
	$beenset = true;
}
if($beenset)
{
	$selection->ConfirmRoom();
	header("Location: additionalOptions.php");
}
?>