<!-- Citation for code used as a reference to create this code
 https://www.formget.com/update-data-in-database-using-php/ -->
<?php 
session_start();
if(empty($_SESSION['login_user']) || $_SESSION['login_type'] != 'Admin')
{
  header("Location: login/sign.php");
}
include 'config.php';
?>
<!DOCTYPE html>
<html>
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
	<link href="style1.css" rel="stylesheet"> 
	

</head>
<body>
<?php if($_SESSION['login_type'] == 'Admin') include 'headers/admin.php';
  else if($_SESSION['login_type'] == 'Student')  include 'headers/student.php';
  else if($_SESSION['login_type'] == 'Faculty')  include 'headers/faculty.php';

?>
<div id="responsive" class="container">
<div id="responsive" class="card bg-light">
<div id="responsive" class="divA">
<div class="title">
<h2>Update Catering Menu</h2>
</div>
<div class="divB">
<div class="divD">
<p style="color:black;">Click On Item</p>
<?php
$connection = mysql_connect("localhost", "lmaynar1", "Comfortstudies");
$db = mysql_select_db("lmaynar1", $connection);
if (isset($_GET['submit'])) {
$id = $_GET['did'];
$name = $_GET['Name'];
$category = $_GET['Category'];
$price = $_GET['Price'];
$query = mysql_query("update menucater set
Name='$name', Category='$category', Price='$price',
where Item_ID='$id'", $connection);
}
$query = mysql_query("select * from menucater", $connection);
while ($row = mysql_fetch_array($query)) {
echo "<a href='UpdateMenu.php?id={$row['Item_ID']}' class='upmen'>{$row['Name']}</a>";
echo "<br />";
}
?>
</div>

<?php
if (isset($_GET['id'])) {
$update = $_GET['id'];
$query1 = mysql_query("select * from menucater where Item_ID=$update", $connection);
while ($row1 = mysql_fetch_array($query1)) {
echo "<form class='form-group' action=UpdateMenu.php method='get'>";
echo "<br><br><h2>Update Form</h2>";
echo "<hr/>";
echo"<input type='hidden' name='did' value='{$row1['Item_ID']}' />";
echo "<br />";
echo "<label>" . "Name:" . "</label>" . "<br />";
echo"<input type='text' name='name' value='{$row1['Name']}' />";
echo "<br />";
echo "<label>" . "Category:" . "</label>" . "<br />";
echo"<input type='text' name='category' value='{$row1['Category']}' />";
echo "<br />";
echo "<label>" . "Price:" . "</label>" . "<br />";
echo"<input type='text' name='price' value='{$row1['Price']}' />";
echo "<br />";
echo "<input class='submit' type='submit' name='update' value='update' />";
echo "</form>"; 
}
}
if (isset($_GET['update'])) {
	
	$item = $_GET['did'];
	$name = $_GET['name'];
	$category = $_GET['category'];
	$price = $_GET['price'];
	$query = "UPDATE menucater SET Name='$name' , Category='$category' , Price='$price' WHERE Item_ID='$item'";
	$data = mysql_query($query, $connection);
	if ($data)
	{
		echo "<h4>  Record Updated Successfully</h4>";
	}
	else
	{
		echo "<h4>Record not updated</h4>";
	}
}

?>

<div class="clear"></div>

</div>
</div>
</div>
</div><?php
mysql_close($connection);
?>
</body>
</html>