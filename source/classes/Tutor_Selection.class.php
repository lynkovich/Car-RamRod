<?php

Class Tutor_Selection
{
	private $tutorID;
	private $tutorQuery; 
	
	function getAvailableTutors()
	{
		try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT * from tutor;";
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
		$returnstring = "<h3>Tutors:</h3>";
		while ($row = $this->tutorQuery->fetch()) 
		{
			$returnstring = $returnstring."<p>".$row['FirstName']." ".$row['LastName']."</p>";
		}
		return $returnstring;
	}

	function confirmTutorSelection()
	{
		
	}
	
}

?>