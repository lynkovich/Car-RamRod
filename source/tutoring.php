<?php 
session_start();
include 'config.php';
include 'classes/Tutor_Selection.class.php';
$tutors = new Tutor_Selection();
$tutors->getAvailableTutors();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add a Tutor</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<?php include 'headers/student.php';
	echo $tutors->printAvailableTutors();  ?>
</body>
<script>
	
</script>
</html>