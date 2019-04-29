<?php include 'classes/Tutor_Selection.class.php';
include 'classes/Room_Selection.class.php';
include 'classes/Pickup_Order_Selection.class.php';
include 'config.php';
$tutoring = new Tutor_Selection();
$reservations = new Room_Selection();
$pickuporders = new Pickup_Order_Selection();?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>CS Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/ico" href="images/icon.png">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<link rel="stylesheet" href="navcss.css">
<script src="js/divupdate.js"></script>
<style>
  ul.twocol {
    list-style-type: none;
    -webkit-column-count: 2; /* Chrome, Safari, Opera */
  -moz-column-count: 2; /* Firefox */
  column-count: 2;
}
</style>
</head>
<body>

<?php session_start();
  if(empty($_SESSION['login_user']))
  {
    header("Location: login/sign.php");
  }
  
  unset($_SESSION['reservationID']);
  unset($_SESSION['tutorID']);
  unset($_SESSION['cateringID']);
  if($_SESSION['login_type'] == 'Admin'){
	 include 'headers/admin.php';
	 
	}
  else if($_SESSION['login_type'] == 'Student'){
	  include 'headers/student.php';
		include 'side/test.php';
	}
  else if($_SESSION['login_type'] == 'Faculty'){
	  include 'headers/faculty.php';
	if($_SESSION['istutor'] == true){ $tutoring->selectTutorID($_SESSION['login_user']);
        $tutoring->getTutorReservations();
        echo $tutoring->tutorConfirmationWidget();}  
	} 

  ?>
	

</body>
</html>