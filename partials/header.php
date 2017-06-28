<?php

	session_start();

	require_once 'partials/lib.php';

	// Default setting to be overridden by driver page
	$is_driver_page = false;

?>


<!DOCTYPE html>
<html>

	<head>

		<title><?php echo "$active_page - Working Title"; ?></title>

		<!--Import Google Icon Font-->
      	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

      	<!-- Font Awesome -->
      	<script src="https://use.fontawesome.com/8a3d0f859b.js"></script>

		<!-- Materialize CSS -->
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">

  		<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="css/styles.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	</head>

	<body>

		<div class="navbar-fixed">			
			<nav>
			    <div class="nav-wrapper green darken-3">
			    	<a href="index.php" class="brand-logo center">
			    		<img src="images/Monoposto.png" alt="Monoposto logo">
			    	</a>
	      			<a href="#" data-activates="side-menu" class="button-collapse show-on-large"><i class="material-icons">menu</i></a>
				    <ul class="side-nav" id="side-menu">
				    	<li>
				    		<div class="user-view">
	      						<div class="background">
	        						<img src="images/side-header.jpg" alt="2011 Formula 1 grid">
	      						</div>
	      						<img class="circle" src="images/The-Stig.jpg" alt="User Avatar">
	      						<span class="white-text name">Welcome!</span>
	    					</div>
	    				</li>
				        <?php printNav(); ?>
				    </ul>
			    </div>
			</nav>
		</div>