<!DOCTYPE html>
<?php 
session_start();
if(empty($_SESSION['login_user']) || $_SESSION['login_type'] != 'Admin')
{
  header("Location: home.php");
}
include 'config.php';

$Res = $_GET['Res'];
$Del = $_GET['Del'];
$yes = $_GET['yes'];
$no = $_GET['no'];

$ResBool = false;
if (isset($_GET['Res'])) {
	$ResBool = true;
}

$DelBool = false;
if(isset($_GET['Del'])){
	$DelBool = true;
}

$yesBool = false;
if(isset($_GET['yes'])){
	$yesBool = true;
}

?>

<?php
		function getRoom($ResID){
					echo '<label>To delete reservation click the link of the reservation.</label><br>';
			try {
					  $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
					  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							   
					  $sql = "select * from roomschedule where Reserv_ID = '$ResID'";
					  $result = $pdo->query($sql);
						if ($result->rowCount() === 0) {
						echo '<br>That reservation does not exist.';
						}
					  while ($row = $result->fetch()) {
									echo '<a href="DeleteReservation.php?Del='.$row['Reserv_ID'].'"> Reservation ID:'.$row[Reserv_ID].', Room ID:'.$row['Room_ID'].',  Start time:'.$row['startTime'].', End time:'.$row['endTime'];

							}


							
								$pdo = null;
				}
					  catch (PDOException $e) {
					  die( $e->getMessage() );
					}
		}
		
		function delRoom($Delete){
			try {
					  $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
					  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							   
					  $sql = "DELETE FROM roomschedule WHERE Reserv_ID= '$Delete'; DELETE FROM reservation WHERE Reserv_ID= '$Delete'";					  
					  $result = $pdo->query($sql);

								$pdo = null;
								if($pdo == null){

								}
							}
					  catch (PDOException $e) {
					  die( $e->getMessage() );
				}
				
		}
		
		


?>
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
	<?php include 'headers/admin.php'; 
	include 'config.php';
	?>
	<div class="container">
	<h1>Delete Study Room</h1>
	<div class="card bg-light">
		<div class = "card-body">
			<form action="DeleteReservation.php" method="get">
			<div class="center">
				<div class="form-group">
					<label>Enter Reservation Number:</label>
					<input type="text" name="Res" id="Res">
				  <input type="submit" value="Submit">
				</div>
			</form>
		</div>
		
	<div class="card bg-light">
		<div class = "card-body">
		<?php 
				if($ResBool == true){
				getRoom($Res);
				}
				if($DelBool == true){
				echo '<label>Are you sure you want to delete reservation '.$Del.'? Check Yes to confirm  or No to go back to the previous page and press submit.</label>';
				echo '<form action="DeleteReservation.php" method="get">
					  <input type="radio" name="yes" value="'.$Del.'">Yes <br>
					  <input type="radio" name="yes" value="no" checked>No <br>
					  <input type="submit" value="Submit">
					  </form>';

				}
				if($yesBool == true){
					if($yes == "no"){
						echo 'The reservation has not been deleted!';
					}
					else{
					echo delRoom($yes);
					echo '<labe>Reservation with ID number: '.$yes.' has been deleted!</label>';
					}
				}

		?>
	
		</div>
	</div>
		
	</div>

	</div>
</body>
</html>