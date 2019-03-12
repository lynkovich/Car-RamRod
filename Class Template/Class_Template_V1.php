<?php
/*
This document is the Class Template. 

This class will be used to get the user name and password from the user. It will take this information and it will use the Web_User class to determine which type of user they are as well as pull up the appropriate options for that user type.*/
Class Main_Web_Page
{
	private string $User_Name;//stores user name
	private string $Password;//stores users password
	//constructor for the variables
	__construct ($User_Name, $Password)
	{
		$this->User_Name = $User_Name;
		$this->Password = $Password;
	}
	/*This function will take the User_Name and Password and check if they match in the database
	This function will also return what type of user this is after checking the database*/
	public function login
	{
		
	}
	public function logoff
	{
		
	}
}

//This class will be used to call the correct content depending on the user type. The Login class will return the correct user type so the Web_User class can chose the right function	
Class Web_User
{
	public string $User_Type;
	//constructor for User_Type
	__construct ($User_Type)
	{
		$this->User_Type = $User_Type;
	}
	//This function will be used to call the proper content for a Student
	public function Student
	{
		
	}
	//This function will be used to call the proper content for a Faculty Member
	public function Faculty
	{
		
	}
	//This function will be used to call the proper content for a Tutor
	public function Tutor
	{
		
	}
}
	
//This class will be used to reserve a study room and to request a tutor
Class Room_Selection
{
	public int $Room_Number;
	public int $Date;
	public string $time;
	//constructor for Room_Number and Tutor_ID
	__construct($Room_Number)
	{
		$this->Room_Number = $Room_Number;
	}
	//This function will take the Room_Number and reserve it in the database. It will send a sql statement making that particular room a true bool value
	public function SelectDate()
	{
		
	}
	//This function will take the Tutor_ID and send a sql statement to that particular Tutor_ID and give that tutor a true bool value in the database
	public function SelectRoom()
	{
		
	}	
	public function SelectTime()
	{
		
	}
	public function ConfirmRoom()
	{
		
	}
}

Class Catering_Order_Selection
{
	private string $items[];
	private string $pickupTime;
	private string $phoneNumber;
	private string $firstName;
	private string $lastName;
	//constructor for Food_Item and Drink_Item
	__construct($Food_Item, $Drink_Item)
	{
		$this->Food_Item = $Food_Item;
		$this->Drink_Item = $Drink_Item;
	}
	//This function will add a Food_Item or Drink_Item to the users total and returns that total amount as well as the items in the list
	function viewCateringMenu()
	{
		
	}
	//This function will use the items added and total amount placed into the object created by Add_Item to check out and pay for those items
	function selectItems()
	{
		
	}
	function confirmItems()
	{
		
	}
	function confirmPurchase()
	{
		
	}
}
//This is the database used to store all the data for the CS System
Class Tutor_Selection
{
	private int $tutorID;
	
	function viewAvailableTutors()
	{
		
	}
	function confirmTutorSelection()
	{
		
	}
	
}

Class Pickup_Order_Selection
{
	private string $items[];
	private string $pickupTime;
	private string $phoneNumber;
	private string $firstName;
	private string $lastName;

	function selectItems()
	{
		
	}
	function selectTime()
	{
		
	}
	function confirmItems()
	{
		
	}
	function confirmPurchase()
	{
		
	}
	
}
	
	
?>
