<?php 
session_start();
if(empty($_SESSION['login_user']))
{
  header("Location: login/sign.php");
}
include 'config.php';
include 'classes/Room_Selection.class.php';
include 'classes/Catering_Order_Selection.class.php';
include 'classes/Tutor_Selection.class.php';
if(!empty($_GET['reservation']))
  $_SESSION['reservationID'] = $_GET['reservation'];
$reservation = new Room_Selection();
$reservation->SelectReservationID($_SESSION['reservationID']);
$reservation->setAdditionalOptionSessions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Additional Options</title>
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
	<?php include 'headers/student.php'; ?>
  <div class = 'container'>
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
        <td>
          <?php 
           if(!empty($_SESSION['cateringID']))
          {
            $order = new Catering_Order_Selection();
            $order->selectOrderID($_SESSION['cateringID']);
            $order->getReceiptQueries();
            echo $order->printReceipt();
          }
          else
            echo "<a href='cateringOrder.php'>Add Catering Order</a>";
          ?>
        </td>
        <td>
          <?php 
            $tutor = new Tutor_Selection();
            $tutor->selectReservationID($_SESSION['reservationID']);
            $tutor->getTutorReservationInfo();
            $tutor->printTutorReservationStatus();
          ?>
        </td>
      </tr>
    </tbody>
  </table>	
</div>
</body>
</html>