<?php include 'sidedisplays.php'; ?>
  <div class="row"style="margin-top:30px; margin-left:10px;">
    <div  class="col-sm-4">
		<div id="liveroom"></div>
    </div>
    <div class="col-sm-8">
      <div><?php ShowUserDetails();?></div><br>
	  <div class="row">
		<div class="col-sm-5">
		<h6>Cancel Reservations</h6>
		<p>Click search to find and cancel reservations.</p>
		<a href="./DeleteReservation.php" class="btn btn-info" role="button">Search</a>
		</div>
		<div class="col-sm-5">
		<h6>Modify Menu</h6>
		<p>Click on the menu you wish to update.</p>
		<a href="./UpdateMenu.php" class="btn btn-info" role="button">Catering</a>
		<a href="./UpdatePickupMenu.php" class="btn btn-info" role="button">Pickup</a>
		</div>
		</div>
	</div>
	</div>