<?php 
session_start();
if(empty($_SESSION['login_user']))
{
	header("Location: login/sign.php");
}
include 'config.php';
include 'classes/Ad_Menu.class.php';
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
<link rel="icon" type="image/ico" href="images/icon.png">

  <link href="style.css" rel="stylesheet"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="navcss.css">

</head>
<body>
	<?php include 'headers/admin.php'; ?>
	<div class="container">
	<h1>Study Room Reservation</h1>
	<div class="card bg-light">
		<div class = "card-body">
			<form action="roomReservation.php" method="get">
			<div class="center">
				<div class="form-group">
					<label>Reservation Date:</label>
					<input type="date" name="date">
				</div>
				<div class="form-group">
					<label>Select a starting time:</label>
					<input type="time" name="start">
				</div>
				<div class="form-group">
				  <label>Select an ending time:</label>
				  <input type="time" name="finish">
				</div>
				<div class="form-group">
				  <label for="capacity">Quantity of People:</label>
				  
				</div>
				  <input type="submit" value="Submit">
			</div>
			</form>
		</div>
	</div>
		<?php if($beenset){$selection->FindAvailableRooms(); echo $selection->printAvailableRooms();} ?>
	</div>
</body>
</html>