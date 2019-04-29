<?php
session_start();
if(empty($_SESSION['login_user']))
{
	header("Location: login/sign.php");
}
include '../classes/Pickup_Order_Selection.class.php';
include '../config.php';
$beenset = false;
if(!empty($_SESSION['login_user'])&&!empty($_GET['total']))
{
	$order = new Pickup_Order_Selection();
	foreach($_GET as $key=>$value)
	{
		if($key == "total"){
			$order->selectTotal($value);
			$orderID = $value;
		}
		else if($key == "pickupdate")
		{
			$pickupdate = $value;
		}
		else if($key == "pickuptime")
		{
			$pickuptime = $value;
		}
		else if ($value > 0)
			$order->selectItems($key, $value);
	}
	$order->selectDateTime($pickupdate, $pickuptime);
	$order->selectStudent($_SESSION['login_user']);
	$beenset = true;
}
if($beenset)
{
	$order->confirmPurchase();
	header("Location: ../home.php");
}
?>