<?php 
Class Room_Selection
{
	private $studentID;
	private $reservationID;
	private $roomNumber;
	private $resdate;
	private $startTime;
	private $finishTime;
	private $capacity;
	private $availableRoomQuery;
	private $reservationQuery;
	
	public function SelectStudentID($s)
	{
		$this->studentID = $s;
		//$_COOKIE['capacity'] = $this->capacity;
	}

	public function SelectReservationID($r)
	{
		$this->reservationID = $r;
		//$_COOKIE['capacity'] = $this->capacity;
	}

	public function SelectCapacity($c)
	{
		$this->capacity = $c;
		//$_COOKIE['capacity'] = $this->capacity;
	}
	
	public function SelectRoom($r)
	{
		$this->roomNumber = $r;
		$expiryTime = time()+60*60*24;
        setcookie("roomNumber", $this->roomNumber, $expiryTime);
	}	
	public function SelectTimeWithForm($d, $s,$f)
	{
		$this->startTime = $d." ".$s;
		$this->finishTime = $d." ".$f;
		$expiryTime = time()+60*60*24;
		setcookie("startTime", $this->startTime, $expiryTime);
		setcookie("finishTime", $this->finishTime, $expiryTime);
	}
	public function SelectTimeWithCookie($s,$f)
	{
		$this->startTime = $s;
		$this->finishTime = $f;
	}
	public function FindAvailableRooms()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT Room_ID, Building from studyroom 
					where :capacity <= Capacity 
					AND Room_ID not in
					(select Room_ID  from roomschedule where (startTime between :startTime AND :finishTime)
					 OR (endTime between :startTime AND :finishTime) 
					 OR (startTime < :startTime and endTime > :finishTime))";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':capacity', $this->capacity);
			$statement->bindParam(':startTime', $this->startTime);
			$statement->bindParam(':finishTime', $this->finishTime);		 
		   $statement->execute();
		   $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		$this->availableRoomQuery = $statement;
	}
	public function printAvailableRooms()
	{
		$returnstring = "<h4>Click a room to reserve:</h4>";
		while ($row = $this->availableRoomQuery->fetch()) 
		{
			$returnstring = $returnstring."<li class='list-group-item'><a data-toggle='modal' href='#my".$row['Room_ID']."Modal'>".$row['Building']." ".$row['Room_ID']."</a></li>\n";
			$returnstring = $returnstring."	<div id='my".$row['Room_ID']."Modal' class='modal fade'>
											  <div class='modal-dialog'>

											    <!-- Modal content-->
											    <div class='modal-content'>
											      <div class='modal-header'>
											        <h4 class='modal-title'>Book this room</h4>
											      </div>
											      <div class='modal-body'>
											        <b>Details:</b><br><p> Room: ".$row['Room_ID']." in the ".$row['Building']." building.<br>Reserved From: ".$this->startTime."<br>Until: ".$this->finishTime."</p>
											      </div>
											      <div class='modal-footer'>
											      	<form action='enter/enterReservation.php' method='get'>
											        	<button type='submit' class='btn btn-default' name='roomNumber' value='".$row['Room_ID']."'>Submit</button>
											        	<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
											        </form>
											        
											      </div>
											    </div>

											  </div>
											</div>
										";
		}
		

		return $returnstring;
	}
	public function ConfirmRoom()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "INSERT INTO reservation (StudentID)
					VALUES (:studentID);";
		$statement = $pdo->prepare($sql);
		$statement->bindParam(':studentID', $_SESSION['login_user']);
		 if($statement->execute() === false){
			 echo 'Error';
			 }else{
			 $_SESSION['reservationID'] = $pdo->lastInsertId();
		 }
		 $sql = "INSERT INTO roomschedule (Reserv_ID, Room_ID, startTime, endTime )
					values (LAST_INSERT_ID(), :roomNumber , :startTime , :finishTime );";
		$statement = $pdo->prepare($sql);
		$statement->bindParam(':roomNumber', $this->roomNumber);
		$statement->bindParam(':startTime', $this->startTime);
		$statement->bindParam(':finishTime', $this->finishTime);
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

	function getRoomReservations(){
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "select reservation.Reserv_ID, roomschedule.Room_ID, roomschedule.startTime , roomschedule.endTime from reservation left join roomschedule on reservation.Reserv_ID = roomschedule.Reserv_ID where roomschedule.startTime > CURRENT_TIMESTAMP() and StudentID = :studentid ORDER BY roomschedule.startTime ASC;";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':studentid', $this->studentID);
			$statement->execute();
			$this->reservationQuery = $statement;
			 $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	}

	function roomReservationWidget()
	{
		$returnstring =  "<div class='card'>
  			<div class='card-header'>Room Reservations</div>
  			<div class='card-body'>
  				<table class='table table-borderless'>
			    <thead>
			      <tr>
			        <th>Study Room</th>
			        <th>Start</th>
			        <th>End</th>
			        <th></th>
			      </tr>
			    </thead>
			    <tbody>";
			    while ($row = $this->reservationQuery->fetch())
			    {
			      $returnstring = $returnstring."<tr>
			        <td>".$row['Room_ID']."</td>
			        <td>".$row['startTime']."</td>
			        <td>".$row['endTime']."</td>
			        <td>
			        <a href='additionalOptions.php?reservation=".$row['Reserv_ID']."'>View</a></td>";
			      $returnstring = $returnstring."</tr>";
			    }  
		$returnstring = $returnstring."</tbody>
			  </table>
  			</div> 
		</div>";

		return $returnstring;
	}

	function setAdditionalOptionSessions()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "select TutorID, CateringOrderID from reservation where Reserv_ID = :resid;";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':resid', $this->reservationID);
			$statement->execute();
			$result = $statement->fetch();
			$pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		if(!empty($result['TutorID']))
		{
			$_SESSION['tutorID'] = $result['TutorID'];
		}
		if(!empty($result['CateringOrderID']))
		{
			$_SESSION['cateringID'] = $result['CateringOrderID'];
		}
	}
	
}
?>