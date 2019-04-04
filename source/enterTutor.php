<?php
session_start();
include 'classes/Tutor_Selection.class.php';
include 'config.php';
$beenset = false;
if(!empty($_SESSION['studentID'])&&!empty($_SESSION['reservationID'])&&!empty($_GET['tutorID']))
{
	$tutorReservation = new Tutor_Selection();
	$tutorReservation->selectTutorID($_GET['tutorID']);
	$beenset = true;
}
if($beenset)
{
	$tutorReservation->confirmTutorSelection();
	$_SESSION['tutorID'] = $_GET['tutorID'];
	header("Location: additionalOptions.php");
}
?>