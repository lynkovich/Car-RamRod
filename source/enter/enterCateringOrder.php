<?php
session_start();
if(empty($_SESSION['login_user']))
{
	header("Location: login/sign.php");
}
include '../classes/Catering_Order_Selection.class.php';
include '../config.php';
$beenset = false;
if(!empty($_SESSION['login_user'])&&!empty($_SESSION['reservationID'])&&!empty($_GET['total']))
{
	$order = new Catering_Order_Selection();
	foreach($_GET as $key=>$value)
	{
		if($key == "total"){
			$order->selectTotal($value);
			$orderID = $value;
		}
		else if ($value > 0)
			$order->selectItems($key, $value);
	}
	$beenset = true;
}
if($beenset)
{
	$order->confirmPurchase();
	header("Location: ../additionalOptions.php");
}
?>