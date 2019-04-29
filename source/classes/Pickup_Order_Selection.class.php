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
	//This function will use the items added and total amount placed into the object created by Add_Item to check out and pay for those items
	function selectItems($k, $v)
	{
		$this->items[$k] = $v;
	}

	function selectTotal($t)
	{
		$this->totalPrice = $t;
	}

	function selectStudent($s)
	{
		$this->studentID = $s;
	}


	function selectOrderID($i)
	{
		$this->orderID = $i;
	}

	function selectDateTime($d, $t)
	{
		$this->dateTime = $d." ".$t;
	}

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