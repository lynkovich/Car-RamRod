<?php
Class Catering_Order_Selection
{
	private $items = array();
	private $pickupTime;
	private $totalPrice;
	private $caterMenuQuery;

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
		$returnstring = "<h3>Catering Menu:</h3><form action='enterCateringOrder.php' method='get'>";
		while ($row = $this->caterMenuQuery->fetch()) 
		{
			$returnstring = $returnstring."<div><p>".$row['Name']." ".$row['Price']."</p>
											<select name='".$row['Item_ID']."' class = 'cateringOption' data-price= ".$row['Price'].">
											  <option value = '0'>0</option>
											  <option value='1'>1</option>
											  <option value='2'>2</option>
											  <option value='3'>3</option>
											</select><p class='sub'>0.00</p></div>";
		}
		$returnstring = $returnstring."<input type='checkbox' name ='total' class = 'total' value ='0'><span>0.00</span><br><button type='submit'>Boom</button></form>";
		return $returnstring;
	}
	//This function will use the items added and total amount placed into the object created by Add_Item to check out and pay for those items
	function selectItems($k, $v)
	{
		$this->items[$k] = $v;
	}

	function selectTotal($t)
	{
		$this->totalPrice = $t;
	}

	function confirmPurchase()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "INSERT INTO cateringorder (TotalPrice)
					VALUES (:totalPrice);";
		$statement = $pdo->prepare($sql);
		$statement->bindParam(':totalPrice', $this->totalPrice);
		 if($statement->execute() === false){
			 echo 'Error';
			 }else{
		 }
		 $sql = "INSERT INTO caterorderitems (CateringOrderID, ItemID, Quantity )
					values (LAST_INSERT_ID(), :itemID , :quantity );";
		$statement = $pdo->prepare($sql);
		$statement->bindParam(':itemID', $itemIDParam);
		$statement->bindParam(':quantity', $quantityParam);
		foreach($this->items as $key=>$value){
			$itemIDParam = $key;
			$quantityParam = $value;
			 if($statement->execute() === false){
				 echo 'Error';
				 }else{
				 
			 }
		}
		$sql = "UPDATE reservation
				SET CateringOrderID = LAST_INSERT_ID()
				WHERE Reserv_ID = :reservID;";
		$statement = $pdo->prepare($sql);
		$statement->bindParam(':reservID', $_SESSION['reservationID']);
		 if($statement->execute() === false){
			 echo 'Error';
			 }else{
		 }
		 $pdo = null;


		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	}
}
?>