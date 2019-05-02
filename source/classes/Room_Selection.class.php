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
	
	/**
	 * This function is used for selecting the Student ID
	 * @param   $s This variable should be a string that correlates with a user id from users table
	 * @result $studentID is assigned the value $s
	 */ 
	public function SelectStudentID($s)
	{
		$this->studentID = $s;
		//$_COOKIE['capacity'] = $this->capacity;
	}

	/**
	 * This function is used for selecting the ReservationID used to check the other options of the reservation.
	 * @param   $r This variable should be a string that correlates with an id in the reservation table
	 * @result $reservationID is assigned the value $r
	 */ 
	public function SelectReservationID($r)
	{
		$this->reservationID = $r;
		//$_COOKIE['capacity'] = $this->capacity;
	}

	/**
	 * This function is used for selecting the capacity of the room needed for a room reservation.
	 * @param   $c This value will need to be a int that is less than or equal to the capacity of the largest room
	 * @result $capacity will be assigned the value $c
	 */ 
	public function SelectCapacity($c)
	{
		$this->capacity = $c;
		//$_COOKIE['capacity'] = $this->capacity;
	}
	
	/**
	 * This function assigns the id value for the room that will be booked
	 * @param  $r The value is an int that correlates with one of the ids in the studyroom table
	 * @result $roomNumber will assigned as $r as well as a cookie.
	 */ 
	public function SelectRoom($r)
	{
		$this->roomNumber = $r;
		$expiryTime = time()+60*60*24;
        setcookie("roomNumber", $this->roomNumber, $expiryTime);
	}	

	/**
	 * This function assigns the startTime and finishTime where the date and time variables are separate.
	 * @param  $d This variable should be of the date string form correlating with the date you would like to book a room.
	 * @param $s This variable should be of time form and correlates with the preferred start time of the study session.
	 * @param  $f This variable should be of time form and correlates with the preferred finish time of the study session.
	 * @result $startTime and $finishTime will be assigned strings of $s and $f concatenated with $d. Cookies will also be assigned.
	 */ 
	public function SelectTimeWithForm($d, $s,$f)
	{
		$this->startTime = $d." ".$s;
		$this->finishTime = $d." ".$f;
		$expiryTime = time()+60*60*24;
		setcookie("startTime", $this->startTime, $expiryTime);
		setcookie("finishTime", $this->finishTime, $expiryTime);
	}

	/**
	 * This function assigns the startTime and finishTime from dateTime strings.
	 * @param  $s This variable should be string in dateTime form for the start time of the study room session
	 * @param  $f This variable should be string in dateTime form for the finish time of the study room session
	 * @result $startTime will be assigned $s and $finishTime will be assigned $f
	 */ 
	public function SelectTimeWithCookie($s,$f)
	{
		$this->startTime = $s;
		$this->finishTime = $f;
	}

	/**
	 * This function will run the query for rooms that match criteria of the start time, end time, and capacity of the room.
	 * Must call SelectCapacity(). Also must call either SelectTimeWithForm() or SelectTimeWithCookie().
	 * @result The function will see $availableRoomQuery to query result.
	 */ 
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

	/**
	 * This function will print a list of rooms given by $availableRoomQuery.
	 * Must call FindAvailableRooms() first.
	 * @return  A boostrap listgroup of the available rooms.
	 */ 
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

	/**
	 * This function will enter the room reservation into the database.
	 * Must call SelectRoom() and and SelectTimeWithCookie() first
	 * @result This will insert a entry into the reservation table and then used the autoincremented key to insert the roomNummber, startTime, and finishTime into the roomscheduletable.
	 */ 
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

	/**
	 * This function runs a query to see what reservations a user has made that haven't happened yet.
	 * Must call SelectStudentID() first
	 * @result The resulting query will be assigned to $reservationQuery
	 */ 
	public function getRoomReservations()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "select reservation.Reserv_ID, roomschedule.Room_ID, roomschedule.startTime , roomschedule.endTime from reservation left join roomschedule on reservation.Reserv_ID = roomschedule.Reserv_ID where roomschedule.endTime > CURRENT_TIMESTAMP() and StudentID = :studentid ORDER BY roomschedule.startTime ASC;";
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

	/**
	 * This functions prints info about every reservation that hasn't finished or started.
	 * Must call getRoomReservations() first
	 * @return  returns a widget with study room id, start time, finish time and a link to additional options for each reservation after the current time.
	 */ 
	public function roomReservationWidget()
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

	/**
	 * Will run a query for the current reservation and get the ids of the tutor and catering order.
	 * Must run SelectReservationID() first.
	 * @result $_SESSION['tutorID'] and $_SESSION['cateringID'] will be assigned the corresponding ids in the reservation table if they exist.
	 */ 
	public function setAdditionalOptionSessions()
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