<?php
Class Catering_Order_Selection
{
	private $items = array();
	private $orderID;
	private $pickupTime;
	private $totalPrice;
	private $caterMenuQuery = array();
	private $totalPriceQuery;
	private $itemsQuery;


	/**
	 * Function runs two SQL queries used for producing an order receipt.
	 * Must call selectOrderID() first
	 * @result $itemsQuery and $totalPriceQuery are assigned the according query results
	 */
	public function getReceiptQueries()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT menucater.Name, Price, Quantity from caterorderitems left join menucater on caterorderitems.ItemID = menucater.Item_ID where CateringOrderID = :id;";
		   $statementItems = $pdo->prepare($sql);
		   $statementItems->bindParam(':id', $this->orderID);
		   $statementItems->execute();
		   $sql = "SELECT TotalPrice from cateringorder where CateringOrderID = :id;";
		   $statementPrice = $pdo->prepare($sql);
		   $statementPrice->bindParam(':id', $this->orderID);
		   $statementPrice->execute();
		   $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		$this->itemsQuery = $statementItems;
		$this->totalPriceQuery = $statementPrice;

	}

	/**
	 * This function will produce the HTML for a receipt using $itemsQuery and $totalPriceQuery.
	 * Must call getReceiptQueries() first
	 * @return string of html for the receipt modal
	 */
	public function printReceipt()
	{
		$returnstring = "<a data-toggle='modal' href='#receiptModal'>View #".$this->orderID." Receipt</a>";
		$returnstring = $returnstring."	<div id='receiptModal' class='modal fade' role='dialog'>
											  <div class='modal-dialog'>

											    <!-- Modal content-->
											    <div class='modal-content'>
											      <div class='modal-header'>
											        <button type='button' class='close' data-dismiss='modal'>&times;</button>
											        <h4 class='modal-title'>Receipt</h4>
											      </div>
											      <div class='modal-body'>";
											      while ($row = $this->itemsQuery->fetch()) 
											        $returnstring = $returnstring."<b>".$row['Name']."</b><br><p>Quantity: ".$row['Quantity']."</p><p>Price per Item: $".$row['Price']."</p><p>Price: ".number_format($row['Price']*$row['Quantity'],2)."</p><hr>";
											    while($row = $this->totalPriceQuery->fetch())
											    	$returnstring = $returnstring."<b> Total Price: $".$row['TotalPrice']."</b>";
											      $returnstring = $returnstring."</div>
											      <div class='modal-footer'>
											        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
											      </div>
											    </div>

											  </div>
											</div>
										";
		return $returnstring;

	}

	/**
	 * Function runs two SQL queries used for producing the catering menu.
	 * @result caterMenuQuery array will be assigned the results of the multiple queries by catergory
	 */
	public function getCateringMenu()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT distinct Category from menucater;";
		   $categories = $pdo->prepare($sql);
		   $categories->execute();
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}

		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT Item_ID, Name, Price from menucater where Category = :category;";

		   while($row = $categories->fetch()){
		   		$result = $pdo->prepare($sql);
		   		$result->bindParam(':category', $row['Category']);
		   		$result->execute();
		   		$this->caterMenuQuery[$row['Category']] = $result;
		   	}
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		$pdo = NULL;
	}

	/**
	 * This function will produce the HTML for the Catering Menu using the $caterMenuQuery array.
	 * Must call getCateringMenu() first.
	 * @return string of html for the Catering Menu
	 */
	public function printCateringMenu()
	{
		$i = 1000;
		$returnstring = "<h4>Menu: </h4><div id='accordion'>";
		foreach($this->caterMenuQuery as $key => $value){
		$returnstring = $returnstring." <div class='card'>
									      <div class='card-header'>
									        <a class='card-link' data-toggle='collapse' href='#collapse".$i."'>
									          ".$key."
									        </a>
									      </div>
									      <div id='collapse".$i."' class='collapse' data-parent='#accordion'>
									        <div class='card-body'>
									        <ul class='list-group'>";
		while ($row = $value->fetch()) 
		{
			$returnstring = $returnstring."<li class='list-group-item'><a data-toggle='modal' href='#my".$row['Item_ID']."Modal'>".$row['Name']."</a></li>\n";
			$returnstring = $returnstring."	<div id='my".$row['Item_ID']."Modal' class='modal fade'>
											  <div class='modal-dialog'>

											    <!-- Modal content-->
											    <div class='modal-content'>
											      <div class='modal-header'>
											        <h4 class='modal-title'>".$row['Name']."</h4>
											      </div>
											      <div class='modal-body'>
											       <p>Price ".$row['Price']."</p>
											        <p>Category ".$key."</p>
											      </div>
											      <div class='modal-footer'>
											      		<input type='number' value = 1 min = 1 max= 10 class = 'cateringOption'>
											        	<button type='button' class='btn btn-default addItem' data-dismiss='modal' data-name = '".$row['Name']."' data-id = '".$row['Item_ID']."' data-price = ".$row['Price']." >Add Item</button>
											        	<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>					
											       </div>
											    </div>
											  </div>
											</div>
										";
		}
		$returnstring .= "</ul></div></div></div>";
		$i++;
		}
		

		return $returnstring .= "</div>";
	}

	/**
	 * This function is used for adding items and quantites to the order.
	 * @param   $k This is used as the key for the array. It should correlate with the ID of an item from the menu.
	 * @param   $v This is used as the value for the array. It should correlate with the quantity of the item.
	 * @result An item id and its quantity will be added to the $items array.
	 */
	public function selectItems($k, $v)
	{
		$this->items[$k] = $v;
	}

	/**
	 * This function is used for adding the total price to an order.
	 * @param   $t This is the value to be used as the total price of an order.
	 * @result $totalPrice will be assigned the value of $t
	 */
	public function selectTotal($t)
	{
		$this->totalPrice = $t;
	}

	/**
	 * This function is used for selecting the Order ID to be used with the getReceiptQueries
	 * @param   $i This is the value to be used as the order id for producing a receipt.
	 * @result $orderID will be assigned the value of $i
	 *  
	 */
	public function selectOrderID($i)
	{
		$this->orderID = $i;
	}

	/**
	 * This function is used for inserting an order into the database.
	 * Must call selectItems() and selectTotal() first.
	 * @result Each item from the $items array is in inserted into caterorderitems table and the $totalPrice is inserted into cateringorder. The order id is inserted into the reservation table.
	 */
	public function confirmPurchase()
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
			 	$_SESSION['cateringID'] = $pdo->lastInsertId();
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