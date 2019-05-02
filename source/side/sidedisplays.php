<?php
session_start();
function ShowOpenStudyRooms()
{
	echo "<h3>Open Study Rooms</h3><ul class='twocol'>";
try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT Room_ID, Building from studyroom 
					where Room_ID not in
					(select Room_ID  from roomschedule where (startTime between LOCALTIME AND LOCALTIME)
					 OR (endTime between LOCALTIME AND LOCALTIME) 
					 OR (startTime < LOCALTIME and endTime > LOCALTIME))";
			$result = $pdo->query($sql);
		   while ($row = $result->fetch()) 
		{
			echo "
				<li>".$row['Building']." ".$row['Room_ID']."</li>
			";
		}
		   
		   $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
	echo "</ul>";
	echo "<hr>";
}
function ShowTutorAppointments()
{
	echo "<h3>Tutoring Appointments  ";
	try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT COUNT(*) FROM reservation WHERE TutorID ='".$_SESSION['login_user']."' AND TutorConfirmation = 3";
			$result = $pdo->query($sql);
		   $num_rows = $result->fetchColumn();
		   if ($num_rows > 0)
		   {
			   echo "<span class='badge'> ".$num_rows." New</span>";
		   }
		   $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
		echo "</h3>";

}
function ShowUserDetails()
{
	try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql = "SELECT * FROM users WHERE StudentID='".$_SESSION['login_user']."'";
		   $sql2 = "SELECT * FROM tutor WHERE TutorID ='".$_SESSION['login_user']."'";
			$result = $pdo->query($sql);
			$result2 = $pdo->query($sql2);
		   while ($row = $result->fetch()) 
			{
			   echo "<h3 style='text-align:center'>Welcome ".$row['FirstName']." ".$row['LastName']."</h3>";
			   echo "<h5>User Info</h5>";
			   echo "<p>Username: ".$row['Username']."</p>";
			   echo "<p>".$row['UserType']."</p>";
			   while ($row2 = $result2->fetch()) 
				{
					echo "<p>Tutor: ".$row2['Subject']."</p>";
				}	
			}
		   $pdo = null;
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}
}
?>