<?php

Class Tutor_Selection
{
	private $userID;
	private $tutorID;
	private $tutorQuery = array(); 
	private $reservationID;
	private $reservationQuery;
	private $singleReservationArray;

	function selectUserID($u)
	{
		$this->userID = $u;
	}

	function selectTutorID($t)
	{
		$this->tutorID = $t;
	}

	function selectReservationID($r)
	{
		$this->reservationID = $r;
	}
	
	function getAvailableTutors()
	{

		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT distinct Subject from tutor;";
		   $subjects = $pdo->prepare($sql);
		   $subjects->execute();
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}



		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT TutorID, Subject, users.FirstName, users.LastName  from tutor inner join users on tutor.TutorID = users.StudentID where TutorID != :userid and Subject = :subject;";
		   
		   while($row = $subjects->fetch()){
		   		$result = $pdo->prepare($sql);
		   		$result->bindParam(':userid', $this->userID);
		   		$result->bindParam(':subject', $row['Subject']);
		   		$result->execute();
		   		$this->tutorQuery[$row['Subject']] = $result;
		   	};
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	}

	function printAvailableTutors()
	{

    	$returnstring = "<h3>Click a subject to browse tutors:</h3><div id='accordion'>";
    	foreach($this->tutorQuery as $key => $value){
		$returnstring = $returnstring." <div class='card'>
									      <div class='card-header'>
									        <a class='card-link' data-toggle='collapse' href='#".preg_replace('/\s/', '', $key)."'>
									          ".$key."
									        </a>
									      </div>
									      <div id='".preg_replace('/\s/', '', $key)."' class='collapse' data-parent='#accordion'>
									        <div class='card-body'>
									        <ul class='list-group'>";
		while ($row = $value->fetch()) 
		{
			$returnstring = $returnstring."<li class='list-group-item'><a data-toggle='modal' href='#my".$row['TutorID']."Modal'>".$row['FirstName']." ".$row['LastName']."</a></li>\n";
			$returnstring = $returnstring."	<div id='my".$row['TutorID']."Modal' class='modal fade' role='dialog'>
											  <div class='modal-dialog'>

											    <!-- Modal content-->
											    <div class='modal-content'>
											      <div class='modal-header'>
											        <h4 class='modal-title'>Book this Tutor</h4>
												<button type='button' class='close' data-dismiss='modal'>&times;</button>
											      </div>
											      <div class='modal-body'>
											        <p>Name: ".$row['FirstName']." ".$row['LastName']."</p><p> Subject: ".$row['Subject']."</p>
											      </div>
											      <div class='modal-footer'>
											      	<form action='enter/enterTutor.php' method='get'>
											        	<button type='submit' class='btn btn-default' name='tutorID' value='".$row['TutorID']."'>Submit</button>
											        </form>
											        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
											      </div>
											    </div>

											  </div>
											</div>
										";
		}
		$returnstring .= "</ul></div></div>";
		}
		return $returnstring."</div>";
	}

	function confirmTutorSelection()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "UPDATE reservation
					SET TutorID = :tutorID , TutorConfirmation = 3
					WHERE Reserv_ID = :reservID;";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':tutorID', $this->tutorID);
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

	function getTutorReservations(){
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "select reservation.Reserv_ID, TutorID, TutorConfirmation, roomschedule.Room_ID, roomschedule.startTime from reservation left join roomschedule on reservation.Reserv_ID = roomschedule.Reserv_ID where roomschedule.startTime > CURRENT_TIMESTAMP() and TutorID = :tutorid ORDER BY roomschedule.startTime ASC;";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':tutorid', $this->tutorID);
			$statement->execute();
			$this->reservationQuery = $statement;
			 $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	}

	function tutorConfirmationWidget()
	{
		$returnstring =  "<div class='card'>
  			<div class='card-header'>Tutoring Appointments</div>
  			<div class='card-body'>
  				<table class='table table-borderless'>
			    <thead>
			      <tr>
			        <th>Date and Time</th>
			        <th>Study Room</th>
			        <th></th>
			        <th></th>
			      </tr>
			    </thead>
			    <tbody>";
			    while ($row = $this->reservationQuery->fetch())
			    {
			      $returnstring = $returnstring."<tr>
			        <td>".$row['startTime']."</td>
			        <td>".$row['Room_ID']."</td>
			        <td>";
			        if($row['TutorConfirmation'] == 3)
			        	 $returnstring = $returnstring."<a href='enter/confirmTutorAppointment.php?reservation=".$row['Reserv_ID']."'>Confirm</a>";
			        else
			        	 $returnstring = $returnstring."<strong>CONFIRMED</strong>";
			        $returnstring = $returnstring."</td>
			        <td>";
			        if($row['TutorConfirmation'] == 3)
			        	$returnstring = $returnstring."<a href='enter/denyTutorAppointment.php?reservation=".$row['Reserv_ID']."'>Deny</a></td>";
			      $returnstring = $returnstring."</tr>";
			    }  
		$returnstring = $returnstring."</tbody>
			  </table>
  			</div> 
		</div>";

		return $returnstring;
	}

	function confirmAppointment()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "UPDATE reservation
					SET TutorConfirmation = 1
					WHERE Reserv_ID = :reservID AND TutorID = :tutorid;";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':reservID', $this->reservationID);
			$statement->bindParam(':tutorid', $this->tutorID);
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

	function denyAppointment()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "UPDATE reservation
					SET TutorConfirmation = 2, TutorID = NULL
					WHERE Reserv_ID = :reservID AND TutorID = :tutorid;";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':reservID', $this->reservationID);
			$statement->bindParam(':tutorid', $this->tutorID);
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

	function getTutorReservationInfo()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "select reservation.TutorID, TutorConfirmation, tutor.Subject, users.FirstName, users.LastName from reservation left join tutor on reservation.TutorID = tutor.TutorID left join users on reservation.TutorID = users.StudentID where Reserv_ID = :id";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':id', $this->reservationID);
			$statement->execute();
			$this->singleReservationArray = $statement->fetch();
			 $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	}
	
	function printTutorReservationStatus()
	{
		if($this->singleReservationArray['TutorConfirmation'] == 0)
		{
			echo "<a href='tutoring.php'>Add Tutor</a>";
		}
		else if($this->singleReservationArray['TutorConfirmation'] == 3)
		{
			echo "Waiting for ".$this->singleReservationArray['FirstName']." ".$this->singleReservationArray['LastName']." to confirm.";
		}
		else if($this->singleReservationArray['TutorConfirmation'] == 1)
		{
			echo $this->singleReservationArray['FirstName']." ".$this->singleReservationArray['LastName']." has confirmed";
		}
		else if($this->singleReservationArray['TutorConfirmation'] == 2)
		{
			echo "<a href='tutoring.php'>TUTOR CANCELLED - ADD A NEW ONE</a>";
		}
	}
}

?>