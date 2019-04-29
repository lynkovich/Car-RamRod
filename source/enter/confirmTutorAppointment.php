<?php 
session_start();
include '../classes/Tutor_Selection.class.php';
include '../config.php';
$tutoring = new Tutor_Selection();
$tutoring->selectTutorID($_SESSION['login_user']);
$tutoring->selectReservationID($_GET['reservation']);
$tutoring->confirmAppointment();
header("location: ../home.php");
?>