<?php

Class Tutor_Selection
{
	private $userID;
	private $tutorID;
	private $tutorQuery = array(); 
	private $reservationID;
	private $reservationQuery;
	private $singleReservationArray;


	/**
	 * This function is used for selecting the userid of the user so they cannot be listed as a available tutor for themselves.
	 * @param   $u The variable will be a id string that correlates with an id in the users table.
	 * @result $userID will be assigned as $u.
	 */
	public function selectUserID($u)
	{
		$this->userID = $u;
	}

	/**
	 * This function is used selecting a tutor for the various functions in this class
	 * @param   $t This is the id string that correlates with an id in the tutor table.
	 * @result $tutorID will be assigned as $t
	 */
	public function selectTutorID($t)
	{
		$this->tutorID = $t;
	}

	/**
	 * This function is used for selecting the reservationID to be used in various functions within the class
	 * @param   $r The variable will be an id string that correlates with an id in the reservation table
	 * @result $reservationID will be assigned as $r.
	 */
	public function selectReservationID($r)
	{
		$this->reservationID = $r;
	}
	
	/**
	 * This function is used to run a query for all available tutors each by subject.
	 * Must call selectTutorID() first to prevent the current user from appearing on the list
	 * @result $tutorQuery array will contain query results with the key being the subject.
	 */ 
	public function getAvailableTutors()
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

	/**
	 * This function will print a listgroup of all the available tutors by their subject.
	 * Must call getAvailableTutors() first
	 * @return  Will return an accordion list group of subjects containing the tutors who specialize in those subjects.
	 */ 
	public function printAvailableTutors()
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

	/**
	 * This function will insert the chosen tutor into the current reservation and make the status pending
	 * Must call selectTutorID() first.
	 * @result The function will insert $tutorID into the tutorid column in the reservation table and set TutorConfirmation to 3(pending)
	 */ 
	public function confirmTutorSelection()
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

	/**
	 * This function will run a query for all of the reservations a tutor is part of past the current time and date
	 * Must call selectTutorID() first
	 *  @result $reservationQuery will be assigned the resulting query
 	 */ 
	public function getTutorReservations()
	{
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

	/**
	 * This function will return an HTML string containing every tutor reservation the tutor is apart of. If it is confirmed, it will simply say CONFIRMED. If it is pending, it will allow the tutor to accept or deny the appointment.
	 * Must call getTutorReservations() first
	 * @return  A string containing html will be returned with reservations either confirmed or pending and in need of response.
	 */ 
	public function tutorConfirmationWidget()
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

	/**
	 * This function will update the tutor confirmation for a specific reservation to confirmed.
	 * Must call selectTutorID() and selectReservationID() first
	 * @result If the tutorID matches that of the reservation, TutorConfirmation of the specific reservation in the reservation table will be updated to 1(Confirmed).
	 */
	public function confirmAppointment()
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

	/**
	 * This function will update the tutor confirmation for a specific reservation to denied and delete the tutor from the reservation.
	 * Must call selectTutorID() and selectReservationID() first
	 * @result If the tutorID matches that of the reservation, TutorConfirmation of the specific reservation in the reservation table will be updated to 2(Denied) and the TutorID will be set to NULL.
	 */ 
	public function denyAppointment()
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

	/**
	 * This function will run a query to get confirmation status and tutor info for the specified reservation.
	 * Must call selectReservationID() first
	 * @result $singleReservationArray will be assigned the query result
	 */ 
	public function getTutorReservationInfo()
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
	
	/**
	 * This function will return a string containing the tutor confirmation information for a specified reservation.
	 * Must call getTutorReservationInfo() first
	 * @return A string of html will be returned depending on what TutorConfirmation is equal to. 0 results in a simple "Add Tutor" button. 1 results in "(Tutor Name) has confirmed" string. 2 results in "TUTOR CANCELLED - ADD A NEW ONE" button. 3 results in "Waiting for (Tutor's Name) to confirm" string. 
	 */ 
	public function printTutorReservationStatus()
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