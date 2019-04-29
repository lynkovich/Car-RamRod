<?php
include '../config.php';
date_default_timezone_set("America/New_York");
$starttime= date('Y-m-d H:i');
$endtime = date('Y-m-d H:i', strtotime("+30 min"));
$ID = $_GET['id'];
try {
		   $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   $sql="SELECT Room_ID, Building from studyroom 
					where Room_ID not in
					(select Room_ID  from roomschedule where (startTime between '".$starttime."' AND '".$endtime."')
					 OR (endTime between '".$starttime."' AND '".$endtime."') 
					 OR (startTime < '".$starttime."' and endTime > '".$endtime."'))
                     AND Room_ID ='".$ID."'";
			$result = $pdo->query($sql);
			$sql1 = "INSERT INTO roomschedule (Room_ID, startTime, endTime)
			VALUES ('{$ID}', '{$starttime}', '{$endtime}')";
		$num_rows = $result->fetchColumn();
		   if ($num_rows > 0) 
		{
			$result = $pdo->query($sql1);
			echo '<script language="javascript">';
			echo 'if(confirm("Checked In.")) document.location = "https://dbdev-stark.cs.kent.edu/~lmaynar1/Capstone/linkstest2/home.php"';
			echo '</script>';
		
		}
		else{
			echo '<script language="javascript">';
			echo 'if(confirm("Cannot check in, room is reserved within the next 30 minutes.")) document.location = "https://dbdev-stark.cs.kent.edu/~lmaynar1/Capstone/linkstest2/home.php"';
			echo '</script>';
			
		}
		  
		   $pdo = null;
		   
		}
		catch (PDOException $e) {
		   die( $e->getMessage() );
		}

