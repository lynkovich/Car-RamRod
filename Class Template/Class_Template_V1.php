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
	public function Login
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
	public int $Tutor_ID;
	//constructor for Room_Number and Tutor_ID
	__construct($Room_Number, $Tutor_ID)
	{
		$this->Room_Number = $Room_Number;
		$this->Tutor_ID = $Tutor_ID;
	}
	//This function will take the Room_Number and reserve it in the database. It will send a sql statement making that particular room a true bool value
	public function Reserve_Room
	{
		
	}
	//This function will take the Tutor_ID and send a sql statement to that particular Tutor_ID and give that tutor a true bool value in the database
	public function Reserve_Tutor
	{
		
	}	
}

Class Order_Selection
{
	public string Food_Item;
	public string Drink_Item;
	//constructor for Food_Item and Drink_Item
	__construct($Food_Item, $Drink_Item)
	{
		$this->Food_Item = $Food_Item;
		$this->Drink_Item = $Drink_Item;
	}
	//This function will add a Food_Item or Drink_Item to the users total and returns that total amount as well as the items in the list
	function Add_Item
	{
		
	}
	//This function will use the items added and total amount placed into the object created by Add_Item to check out and pay for those items
	function Check_Out
	{
		
	}
}
//This is the database used to store all the data for the CS System
Class Food_Selection_and_Study_Room_Database
{
	
}
	
	
?>