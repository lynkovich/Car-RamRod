<?php 
session_start();
if(empty($_SESSION['login_user']))
{
  header("Location: login/sign.php");
}
include 'config.php';
include 'classes/Tutor_Selection.class.php';
$tutors = new Tutor_Selection();
$tutors->selectUserID($_SESSION['login_user']);
$tutors->getAvailableTutors();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add a Tutor</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/ico" href="images/icon.png">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="navcss.css">
</head>
<body>
	<?php if($_SESSION['login_type'] == 'Admin') include 'headers/admin.php';
	else if($_SESSION['login_type'] == 'Student')  include 'headers/student.php';
	else if($_SESSION['login_type'] == 'Faculty')  include 'headers/faculty.php';?>
 <div class = 'container'>
  <?php echo $tutors->printAvailableTutors();  ?>
</div>
</body>
<script>
	
</script>
</html>