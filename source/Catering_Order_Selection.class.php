<?php
Class Catering_Order_Selection
{
	private $items;
	private $pickupTime;
	private $phoneNumber;
	private $caterMenuQuery;
	//constructor for Food_Item and Drink_Item
	//This function will add a Food_Item or Drink_Item to the users total and returns that total amount as well as the items in the list
	
	function getCateringMenu()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT * from menucater;";
		   $result = $pdo->query($sql);
		   $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		$this->caterMenuQuery = $result;
	}
	function printCateringMenu()
	{
		$returnstring = "<h3>Catering Menu:</h3><form action='#' method='get'>";
		while ($row = $this->caterMenuQuery->fetch()) 
		{
			$returnstring = $returnstring."<p>".$row['Name']." ".$row['Price']."</p>
											<select name='".$row['Item_ID']."'>
											  <option value='0'>0</option>
											  <option value='1'>1</option>
											  <option value='2'>2</option>
											  <option value='3'>3</option>
											</select>";
		}
		$returnstring = $returnstring."</form>";
		return $returnstring;
	}
	//This function will use the items added and total amount placed into the object created by Add_Item to check out and pay for those items
	function selectItems($i)
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