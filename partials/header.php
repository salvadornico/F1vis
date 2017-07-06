<?php

	session_start();

	require_once 'partials/lib.php';

	// Default setting to be overridden by driver pages
	$is_driver_page = false;

?>


<!DOCTYPE html>
<html>

	<head>

		<title><?php echo "$active_page | monoposto"; ?></title>
		<link rel="icon" type="image/png" href="images/icon.png" />

		<!-- Google Fonts-->
      	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:900" rel="stylesheet">

      	<!-- Font Awesome -->
      	<script src="https://use.fontawesome.com/8a3d0f859b.js"></script>

		<!-- Materialize CSS -->
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">

  		<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="css/styles.css">

		<!-- jQuery -->
	    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

		<!-- Materialize JS -->
	  	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>

		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta charset="UTF-8">

	</head>

	<body>

		<!-- Google Analytics script -->
		<?php require_once 'partials/analyticstracking.php'; ?>

		<div<?php ifHomeNav(); ?>>			
			<nav>
			    <div class="nav-wrapper green darken-3">

			    	<a href="index.php" class="brand-logo center">
			    		<img src="images/<?php ifHomeLogo(); ?>" alt="Monoposto logo">
			    	</a>

	      			<a href="#" data-activates="side-menu" class="button-collapse show-on-large">
	      				<i class="material-icons">menu</i>
	      			</a>

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

				        <?php printNav(); ?>

				    </ul>

			    </div> <!-- /nav-wrapper -->
			</nav>
		</div>