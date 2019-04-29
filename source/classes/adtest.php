<!DOCTYPE html>
<html>
<head>
<title></title>
<link href="style1.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	include 'config.php';
?>
<div class="maindiv">
<div class="divA">
<div class="title">
<h2>Update Data Using PHP</h2>
</div>
<div class="divB">
<div class="divD">
<p>Click On Menu</p>
<?php
$connection = mysql_connect("localhost", "lmaynar1", "Comfortstudies");
$db = mysql_select_db("lmaynar1", $connection);
if (isset($_GET['submit'])) {
$id = $_GET['did'];
$name = $_GET['Name'];
$category = $_GET['Category'];
$mobile = $_GET['Price'];
$address = $_GET['daddress'];
$query = mysql_query("update menucater set
Name='$name', Category='$category', Price='$mobile',
where Item_ID='$id'", $connection);
}
$query = mysql_query("select * from menucater", $connection);
while ($row = mysql_fetch_array($query)) {
echo "<a href='updatecater.php?id={$row['Item_ID']}'>{$row['Name']}</a>";
echo "<br />";
}
?>
</div>


<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
</div><?php
mysql_close($connection);
?>
</body>
</html>