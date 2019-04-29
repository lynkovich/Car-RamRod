<?php
include('login.php');

if(isset($_SESSION['login_user'])){
	if($_SESSION['login_type']=="Student"||$_SESSION['login_type']=="Admin"||$_SESSION['login_type']=="Faculty"){
		header("location: ../home.php");
	}
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/ico" href="../images/icon.png">

    <title>Comfort Studies Login</title>

    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="" method="post">
	<h1 class="h2 mb-3 font-weight-normal">Welcome to</h1>
      <img class="mb-4" src="../images/logo.png" alt="" width="300" height="100">
      <h3 class="h4 mb-3 font-weight-normal">Please sign in</h3>
      <label class="sr-only">Username</label>
      <input class="form-control" placeholder="Username" id="name" name="username" type="text" required autofocus>
      <label class="sr-only">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Login">Sign in</button>
    </form>
	<?php if($error != NULL){
		echo "<script type='text/javascript'>alert('$error');</script>";
	}		?>
  </body>
</html>