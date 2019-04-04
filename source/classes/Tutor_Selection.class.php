<?php

Class Tutor_Selection
{
	private $tutorID;
	private $tutorQuery; 

	function selectTutorID($t)
	{
		$this->tutorID = $t;
	}
	
	function getAvailableTutors()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT TutorID, Subject, users.FirstName, users.LastName  from tutor inner join users on tutor.TutorID = users.StudentID;";
		   $result = $pdo->query($sql);
		   $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		$this->tutorQuery = $result;
	}

	function printAvailableTutors()
	{
		$returnstring = "<h3>Click a tutor to book:</h3><ul class='list-group'>";
		while ($row = $this->tutorQuery->fetch()) 
		{
			$returnstring = $returnstring."<li class='list-group-item'><a data-toggle='modal' href='#".$row['TutorID']."Modal'>".$row['FirstName']." ".$row['LastName']."</a></li>\n";
			$returnstring = $returnstring."	<div id='".$row['TutorID']."Modal' class='modal fade' role='dialog'>
											  <div class='modal-dialog'>

											    <!-- Modal content-->
											    <div class='modal-content'>
											      <div class='modal-header'>
											        <button type='button' class='close' data-dismiss='modal'>&times;</button>
											        <h4 class='modal-title'>Book this Tutor</h4>
											      </div>
											      <div class='modal-body'>
											        <p>Name: ".$row['FirstName']." ".$row['LastName']."</p><p> Subject: ".$row['Subject']."</p>
											      </div>
											      <div class='modal-footer'>
											      	<form action='enterTutor.php' method='get'>
											        	<button type='submit' class='btn btn-default' name='tutorID' value='".$row['TutorID']."'>Submit</button>
											        </form>
											        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
											      </div>
											    </div>

											  </div>
											</div>
										";
		}
		$returnstring .= "</ui>";

		return $returnstring;
	}

	function confirmTutorSelection()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   
			$sql = "UPDATE reservation
					SET TutorID = :tutorID
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
	
}

?>