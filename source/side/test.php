
  <div class="row"style="margin-top:30px; margin-left:10px;">
    <div class="col-sm-4" id="liveroom">
      
    </div>
    <div class="col-sm-8">
      <h2><?php echo $_SESSION['login_username']; ?></h2>
      <div><?php if($_SESSION['istutor'] == true){ $tutoring->selectTutorID($_SESSION['login_user']);
        $tutoring->getTutorReservations();
        echo $tutoring->tutorConfirmationWidget();} ?></div>
       <div><?php $reservations->SelectStudentID($_SESSION['login_user']);
  			$reservations->getRoomReservations();
  			echo $reservations->roomReservationWidget(); ?></div>
  		<div><?php $pickuporders->selectStudent($_SESSION['login_user']);
  			$pickuporders->getPickUpOrders();
  			echo $pickuporders->pickupOrdersWidget(); ?></div>
      <h5>Users info below</h5>
      <p>Some text..</p>
      <p>Some text...</p>
      <hr>
		<button onclick="startscan()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
			  QR Scanner
			</button>
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