<?php include 'sidedisplays.php'; ?>
  <div class="row"style="margin-top:30px; margin-left:10px;">
    <div  class="col-sm-4">
		<div id="liveroom"></div><?php if($_SESSION['istutor'] == true){ ?>
		 <button class='fa fa-plus-circle' style='border:none; background-color:white;' id='tclick'> View</button>
		<hr> <?php } ?>
	  <h3>Pick Up Orders</h3><button class="fa fa-plus-circle" style="border:none; background-color:white;" id="fclick"> View</button>
		<hr>
    </div>
    <div class="col-sm-7">
      <?php ShowUserDetails();?>
	  
      <hr>
      <div id="tutor" style="display:none;"><div id="div"><?php if($_SESSION['istutor'] == true){ $tutoring->selectTutorID($_SESSION['login_user']);
        $tutoring->getTutorReservations();
        echo $tutoring->tutorConfirmationWidget();} ?></div></div>
  		<div id="food" style="display:none;"><div id="div2"><?php $pickuporders->selectStudent($_SESSION['login_user']);
  			$pickuporders->getPickUpOrders();
  			echo $pickuporders->pickupOrdersWidget(); ?></div></div>
			</div>
			</div>