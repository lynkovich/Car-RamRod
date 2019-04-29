<?php
include '../config.php';
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
?>
<h3>Some Links</h3>
	<ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
      </ul>
      
      <hr>

      <div><?php if($_SESSION['istutor'] == true){ $tutoring->selectTutorID($_SESSION['login_user']);
        $tutoring->getTutorReservations();
        echo $tutoring->tutorConfirmationWidget();} ?></div>