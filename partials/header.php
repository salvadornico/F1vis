<?php

	session_start();

	require_once 'partials/lib.php';

	// Default setting to be overridden by driver page
	$is_driver_page = false;

?>


<!DOCTYPE html>
<html>

	<head>

		<title><?php echo "$active_page - monoposto"; ?></title>

		<!--Import Google Fonts-->
      	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:900" rel="stylesheet">

      	<!-- Font Awesome -->
      	<script src="https://use.fontawesome.com/8a3d0f859b.js"></script>

		<!-- Materialize CSS -->
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">

  		<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="css/styles.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	</head>

	<body>

		<div<?php ifHomeNav(); ?>>			
			<nav>
			    <div class="nav-wrapper green darken-3">

			    	<a href="index.php" class="brand-logo center">
			    		<img src="images/<?php ifHomeLogo(); ?>" alt="Monoposto logo">
			    	</a>

	      			<a href="#" data-activates="side-menu" class="button-collapse show-on-large">
	      				<i class="material-icons">menu</i>
	      			</a>

	      			<!-- Put back links, for discoverability? -->

				    <ul class="side-nav" id="side-menu">
				    	<li>
				    		<div class="user-view">

	      						<div class="background">
	        						<img src="images/side-header.jpg" alt="2011 Formula 1 grid">
	      						</div>

	      						<img class="circle" src="images/<?php displayAvatar(); ?>" alt="User Avatar">

	      						<span class="white-text name">
	      							<?php

	      								// user greeting
						    			if(isset($_SESSION['user'])) { echo $_SESSION['user']; }
						    			else { echo "Welcome!"; }

	      							?>
	      						</span>

	    					</div> <!-- /user-view card -->
	    				</li>

				        <?php 

				        	printNav(); 

							// logout button
			    			if(isset($_SESSION['user'])) { 
			    				echo "<li><a href='logout.php'><i class='material-icons'>perm_identity</i>Logout</a></li>";
			    				if ($_SESSION['role'] == 'admin') {
			    					// Admin section
				    				echo "<li><a href='admin.php'><i class='material-icons'>settings</i>Admin Section</a></li>";
			    				 } 
			    			} else { 
			    				echo "<li><a href='login.php'><i class='material-icons'>perm_identity</i>Login / Register</a></li>"; 
			    			}				        	

				        ?>

				    </ul>

			    </div> <!-- /nav-wrapper -->
			</nav>
		</div>