<?php
session_start();
if(empty($_SESSION['login_user']))
{
	header("Location: login/sign.php");
}
include '../classes/Tutor_Selection.class.php';
include '../config.php';
$beenset = false;
if(!empty($_SESSION['login_user'])&&!empty($_SESSION['reservationID'])&&!empty($_GET['tutorID']))
{
	$tutorReservation = new Tutor_Selection();
	$tutorReservation->selectTutorID($_GET['tutorID']);
	$beenset = true;
}
if($beenset)
{
	$tutorReservation->confirmTutorSelection();
	$_SESSION['tutorID'] = $_GET['tutorID'];
	header("Location: ../additionalOptions.php");
}
?>