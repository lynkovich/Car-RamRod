<?php 
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Additional Options</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<?php include 'headers/student.php'; ?>
	<h1> Reservation: <?php echo $_SESSION['reservationID']; ?> </h1>
    <table class="table">
    <thead>
      <tr>
        <th>Catering:</th>
        <th>Tutor:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><a href="cateringOrder.php">Add Catering Order</a></td>
        <td>
          <?php 
            if(!empty($_SESSION['tutorID']))
                echo $_SESSION['tutorID'];
              else
                echo "<a href='tutoring.php'>Add Tutor</a>";
          ?>
        </td>
      </tr>
    </tbody>
  </table>	
</body>
</html>