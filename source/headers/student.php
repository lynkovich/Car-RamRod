<header>
<nav class="navbar navbar-expand-sm navbar-custom navbar-light">
  <a class="navbar-brand" href="./home.php">
          <img src="images/navlogov1.png" alt="Comfort Studies">
		 <!--This line below is to change logo on smaller screens 
				need to make smaller logo.
		<img src="navlogov1.png" class="d-lg-inline-block d-none" alt="">
		 <img src="icon.png" class="d-inline-block d-lg-none" ></img>  -->
        </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon" style="color:black"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="roomReservation.php">Study Room Reservation</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="orderTakeOut.php">Order Takeout</a>
      </li>
	  </ul>
	  <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['login_username']; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="home.php">Account</a>
          
          <a class="dropdown-item" href="login/logout.php">Sign-out</a>
        </div>
      </li>
    </ul>
  </div>  
</nav>
</header>
