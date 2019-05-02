<?php
Class Pickup_Order_Selection
{
	private $items = array();
	private $orderID;
	private $pickupTime;
	private $totalPrice;
	private $dateTime;
	private $pickupMenuQuery = array();
	private $totalPriceQuery;
	private $itemsQuery;
	private $studentID;
	private $orderQuery;


	/**
	 * Function runs two SQL queries used for producing an order receipt.
	 * Must call selectOrderID() first
	 * @result $itemsQuery and $totalPriceQuery are assigned the according query results
	 */
	function getReceiptQueries()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT menupickup.Name, Price, Quantity from orderitems left join menupickup on orderitems.ItemID = menupickup.Item_ID where OrderID = :id;";
		   $statementItems = $pdo->prepare($sql);
		   $statementItems->bindParam(':id', $this->orderID);
		   $statementItems->execute();
		   $sql = "SELECT OrderTotal from pickuporders where OrderID = :id;";
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
	function printReceipt()
	{
		$returnstring = "<a data-toggle='modal' href='#my".$this->orderID."receiptModal'>View #".$this->orderID." Receipt</a>";
		$returnstring = $returnstring."	<div id='my".$this->orderID."receiptModal' class='modal fade' role='dialog'>
											  <div class='modal-dialog'>

											    <!-- Modal content-->
											    <div class='modal-content'>
											      <div class='modal-header'>
											        <h4 class='modal-title'>Receipt</h4>
											      </div>
											      <div class='modal-body'>";
											      while ($row = $this->itemsQuery->fetch()) 
											        $returnstring = $returnstring."<b>".$row['Name']."</b><br><p>Quantity: ".$row['Quantity']."</p><p>Price per Item: $".$row['Price']."</p><p>Price: ".number_format($row['Price']*$row['Quantity'],2)."</p><hr>";
											    while($row = $this->totalPriceQuery->fetch())
											    	$returnstring = $returnstring."<b> Total Price: $".$row['OrderTotal']."</b>";
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
	 * Function runs two SQL queries used for producing the pickup menu.
	 * @result pickupMenuQuery array will be assigned the results of the multiple queries by catergory
	 */
	function getPickUpMenu()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT distinct Category from menupickup;";
		   $categories = $pdo->prepare($sql);
		   $categories->execute();
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}

		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT Item_ID, Name, Price from menupickup where Category = :category;";

		   while($row = $categories->fetch()){
		   		$result = $pdo->prepare($sql);
		   		$result->bindParam(':category', $row['Category']);
		   		$result->execute();
		   		$this->pickupMenuQuery[$row['Category']] = $result;
		   	}
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		$pdo = NULL;
	}

	/**
	 * This function will produce the HTML for the Pickup Menu using the $pickupMenuQuery array.
	 * Must call getPickupMenu() first.
	 * @return string of html for the Pickup Menu
	 */
	function printPickupMenu()
	{
		$returnstring = "<h4>Menu: </h4><div id='accordion'>";
		foreach($this->pickupMenuQuery as $key => $value){
		$returnstring = $returnstring." <div class='card'>
									      <div class='card-header'>
									        <a class='card-link' data-toggle='collapse' href='#".substr($key, 0, 4)."'>
									          ".$key."
									        </a>
									      </div>
									      <div id='".substr($key,0, 4)."' class='collapse' data-parent='#accordion'>
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

		}
		

		return $returnstring."</div>";
	}
	
	/**
	 * This function is used for adding items and quantites to the order.
	 * @param   $k This is used as the key for the array. It should correlate with the ID of an item from the menu.
	 * @param   $v This is used as the value for the array. It should correlate with the quantity of the item.
	 * @result An item id and its quantity will be added to the $items array.
	 */
	function selectItems($k, $v)
	{
		$this->items[$k] = $v;
	}

	/**
	 * This function is used for adding the total price to an order.
	 * @param   $t This is the value to be used as the total price of an order.
	 * @result $totalPrice will be assigned the value of $t
	 */
	function selectTotal($t)
	{
		$this->totalPrice = $t;
	}

	/**
	 * This function is used for assigning
	 * @param   $t This is the value to be used as the total price of an order.
	 * @result $totalPrice will be assigned the value of $t
	 */
	function selectStudent($s)
	{
		$this->studentID = $s;
	}

	/**
	 * This function is used for selecting the Order ID to be used with the getReceiptQueries
	 * @param   $i This is the value to be used as the order id for producing a receipt.
	 * @result $orderID will be assigned the value of $i
	 *  
	 */
	function selectOrderID($i)
	{
		$this->orderID = $i;
	}

	/**
	 * This function is used for selecting the dateTime to be used as the Order pickup time with confirmPurchase() function.
	 * @param  $d This is the value for the date of the Pickup Order
	 * @param  $t This is the value for the time of the Pickup Order
	 * @result The $dateTime variable is assigned the dateTime of the Pickup Order 
	 */
	function selectDateTime($d, $t)
	{
		$this->dateTime = $d." ".$t;
	}

	/**
	 * The function is used to submit the pickup order into the database.
	 * Must call selectStudent(), selectTotal(), selectDateTime(), and selectItems() first.
	 * @result The pickup order will be submitted into pickuporders and orderitems.
	 */
	function confirmPurchase()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "INSERT INTO pickuporders (StudentID, OrderTotal, PickUpTime)
					VALUES (:studentid, :totalPrice, :pickuptime);";
		$statement = $pdo->prepare($sql);
		$statement->bindParam(':studentid', $this->studentID);
		$statement->bindParam(':totalPrice', $this->totalPrice);
		$statement->bindParam(':pickuptime', $this->dateTime);
		 if($statement->execute() === false){
			 echo 'Error';
			 }else{
			 	//$_SESSION['pickupID'] = $pdo->lastInsertId();
		 }
		 $sql = "INSERT INTO orderitems (OrderID, ItemID, Quantity )
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
		 $pdo = null;


		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	}

	/**
	 * This function will perform the query for the pickup orders of the current user.
	 * Must call selectStudent() first
	 * @result The $orderQuery will be assigned the query result array.
	 */ 
	function getPickUpOrders(){
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "select OrderID, PickUpTime from pickuporders where StudentID = :id and PickUpTime > CURRENT_TIMESTAMP();";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':id', $this->studentID);
			$statement->execute();
			$this->orderQuery = $statement;
			 $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	}

	/**
	 * This function will return a widget that displays future order for a person and modal that displays the receipt
	 * Must call getPickUpOrders() first
	 * @return   Return HTML with each future food pickup order and a receipt within a modal for each
	 */ 
	function pickupOrdersWidget()
	{
		$returnstring =  "<div class='card'>
  			<div class='card-header'>Pick Up Orders</div>
  			<div class='card-body'>
  				<table class='table table-borderless'>
			    <thead>
			      <tr>
			        <th>Order</th>
			        <th>Pick Up Time</th>
			        <th>Receipt</th>
			      </tr>
			    </thead>
			    <tbody>";
			    while ($row = $this->orderQuery->fetch())
			    {
			    	$this->selectOrderID($row['OrderID']);
			    	$this->getReceiptQueries();
			    	$receiptstring = $this->printReceipt();
			      $returnstring = $returnstring."<tr>
			        <td>".$row['OrderID']."</td>
			        <td>".$row['PickUpTime']."</td>
			        <td>".$receiptstring."</td>";
			       
			      $returnstring = $returnstring."</tr>";
			    }  
		$returnstring = $returnstring."</tbody>
			  </table>
  			</div> 
		</div>";

		return $returnstring;
	}
}
?>