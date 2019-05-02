<?php include 'sidedisplays.php'; ?>
  <div class="row"style="margin-top:30px; margin-left:10px;">
    <div  class="col-sm-4">
		<div id="liveroom"></div><?php if($_SESSION['istutor'] == true){ ?>
		 <button class='fa fa-plus-circle' style='border:none; background-color:white;' id='tclick'> View</button>
		<hr> <?php } ?>
     <h3>Room Reservations</h3><button class="fa fa-plus-circle" style="border:none; background-color:white;" id="rclick"> View</button>
	  <hr>
	  <h3>Pick Up Orders</h3><button class="fa fa-plus-circle" style="border:none; background-color:white;" id="fclick"> View</button>
		<hr>
		<h3>Study Room Check In</h3>
		<p>Click the button below or use a QR app to scan the QR Code to check in to a study room.</p>
		<button onclick="startscan()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
			  QR Scanner
			</button>
		<hr>
    </div>
    <div class="col-sm-7">
      <?php ShowUserDetails();?>
	  
      <hr>
      <div id="tutor" style="display:none;"><div id="div"><?php if($_SESSION['istutor'] == true){ $tutoring->selectTutorID($_SESSION['login_user']);
	  
        $tutoring->getTutorReservations();
        echo $tutoring->tutorConfirmationWidget();} ?></div></div>
       <div id="room" style="display:none;"><div id="div1"><?php $reservations->SelectStudentID($_SESSION['login_user']);
  			$reservations->getRoomReservations();
  			echo $reservations->roomReservationWidget(); ?></div></div>
  		<div id="food" style="display:none;"><div id="div2"><?php $pickuporders->selectStudent($_SESSION['login_user']);
  			$pickuporders->getPickUpOrders();
  			echo $pickuporders->pickupOrdersWidget(); ?></div></div>
      
		<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">
							
							  <!-- Modal content-->
							  <div class="modal-content" id="exit">
								<div class="modal-header">
								<h4 class="modal-title">Study Room QR Scanner</h4>
								  <button type="button" class="close" id="exit" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
								  <video id="preview" width="100%" height="50%"></video>
											
								</div>
								<div class="modal-footer">
								  <button type="button" class="btn btn-default" id="exit" data-dismiss="modal">Close</button>
								</div>
							  </div>
							  
							</div>
						  </div>
						<script>
											function startscan() {
											  let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false });
											  scanner.addListener('scan', function (content) {
												alert(content);
											  });
											  Instascan.Camera.getCameras().then(function (cameras) {
												if (cameras.length > 0) {
												  scanner.start(cameras[1]);
												} else {
												  console.error('No cameras found.');
												}
											  }).catch(function (e) {
												console.error(e);
											  });
											  scanner.addListener('scan', function (content) {
											  if (content.match(/^https?:\/\//i)) {
												window.open(content);
												scanner.stop();
											  }
											
											});
											$( "#exit" ).click(function() {
											  scanner.stop();
											});
											}
											</script>
      </div>
  </div>