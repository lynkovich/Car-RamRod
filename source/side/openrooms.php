<?php
include '../config.php';
include 'sidedisplays.php';
session_start();
?>
<?php ShowOpenStudyRooms();
if($_SESSION['istutor'] == true){
ShowTutorAppointments(); }?>

