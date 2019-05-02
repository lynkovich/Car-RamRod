<header>
<nav class="navbar navbar-expand-sm navbar-custom navbar-light">
  <a class="navbar-brand">
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
        <a class="nav-link" href="DeleteReservation.php">Remove Reservation</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="UpdateMenu.php">Modify Catering Menu</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="UpdatePickupMenu.php">Modify Pickup Menu</a>
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

