<?php 
session_start();
include 'config.php';
include 'Catering_Order_Selection.class.php';
$order = new Catering_Order_Selection();
$order->getCateringMenu();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Catering Order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<?php include 'student.php';
			echo $order->printCateringMenu(); ?>
</body>
</html>