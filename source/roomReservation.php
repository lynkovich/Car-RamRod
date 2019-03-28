<?php 
session_start();
$_SESSION['studentID'] = "2254";
include 'config.php';
include 'Room_Selection.class.php';
$beenset = false;
if(!empty($_GET['date'])&&!empty($_GET['capacity'])&&!empty($_GET['start'])&&!empty($_GET['finish']))
{
	$selection = new Room_Selection();
	$selection->SelectTimeWithForm($_GET['date'], $_GET['start'], $_GET['finish']);
	$selection->SelectCapacity($_GET['capacity']);
	$beenset = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>CS Student Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<?php include 'student.php'; ?>
	<div class="form-group">
	<form action="roomReservation.php" method="get">
	  <label for="capacity">Quantity of People:</label>
	  <select class="form-control" id="capacity" name="capacity">
		<option>1</option>
		<option>2</option>
		<option>3</option>
		<option>4</option>
		<option>5</option>
		<option>6</option>
		<option>7</option>
	  </select>
		Birthday:
	  <input type="date" name="date">
	  Select a starting time:
	  <input type="time" name="start">
	  Select an ending time:
	  <input type="time" name="finish">
	  <input type="submit" value="Submit">
	</form>
	</div>
	<?php if($beenset){$selection->FindAvailableRooms(); echo $selection->printAvailableRooms();} ?>
</body>
</html>