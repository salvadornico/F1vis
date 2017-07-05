<?php

	// Open SQL connection
	$host = 'localhost';
	$sql_username = 'salvado8_nico';
	$sql_password = 'password';
	$database = 'salvado8_f1db';
	$conn = mysqli_connect($host, $sql_username, $sql_password, $database);
	mysqli_set_charset($conn, "UTF8");

	// Set up query when a response is expected
	function querySQL($query) {
		global $conn;
		$result = mysqli_query($conn, $query);
		return $result;
	}


	// Navigation links across the site
	$nav_links = [
		['Home', 'index.php', '<i class="fa fa-home" aria-hidden="true"></i>'],
		['Dashboard', 'dashboard.php', '<i class="material-icons">dashboard</i>'],
		['Drivers Database', 'drivers.php', '<i class="material-icons">assignment_ind</i>']
	];

	function printNav($classes = null) {
		global $nav_links;

		foreach ($nav_links as $link) {
			echo "<li><a href='$link[1]'";
			if ($classes != null) { echo " class='$classes'"; }
			echo ">$link[2] $link[0]</a></li>";
		}

		if(isset($_SESSION['user'])) { 
			// logout button
			echo "<li><a href='logout.php'";
			if ($classes != null) { echo " class='$classes'"; }
			echo "><i class='material-icons'>perm_identity</i>Logout</a></li>";
			if ($_SESSION['role'] == 'admin') {
				// Admin section
				echo "<li><a href='admin.php'";
				if ($classes != null) { echo " class='$classes'"; }
				echo "><i class='material-icons'>settings</i>Admin Section</a></li>";
			 } 
		} else { 
			echo "<li><a href='login.php'";
			if ($classes != null) { echo " class='$classes'"; }
			echo "><i class='material-icons'>perm_identity</i>Login / Register</a></li>"; 
		}
	}

	// user avatar in side nav
	function displayAvatar() {
		if(isset($_SESSION['user'])) { echo "avatars/avatar-" . $_SESSION['avatar'] . ".png"; }
		else { echo "avatars/avatar-default.png"; }	
	}


	// Changes styling for homepage
    function ifHomeLogo() {
        global $active_page;
        if ($active_page == "Home") { echo "monoposto-helmet-yellow.png"; }
        else { echo "monoposto-logo-transparent.png"; }
    }

    function ifHomeNav() {
        global $active_page;
        if ($active_page == "Home") { echo ""; }
        else { echo " class='navbar-fixed'"; }
    }


    $avatars = ['red', 'blue', 'green', 'orange', 'purple', 'pink'];

	function printAvatars() {
		global $active_page;
		global $avatars;
		for ($i = 0; $i < sizeof($avatars); $i++) { 
			echo "<div class='avatar-select col s6 m4 l2'>
						<input class='with-gap' name='avatar' value='$avatars[$i]' type='radio' id='avatar".($i + 1)."' ";
						if ($active_page == "Dashboard") {
							if ($avatars[$i] == $_SESSION['avatar']) { echo "checked"; }
						}
				echo " required /><label for='avatar".($i + 1)."'>
							<img src='images/avatars/avatar-$avatars[$i].png' class='avatar responsive-img' alt='".ucfirst($avatars[$i])." user avatar'>
						</label>
					</div>";
		}    								
	}


    // For reorganizing driver list array
	function searchForYear($id, $array) {
   		foreach ($array as $key => $val) {
       		if ($key == $id) { return true; }
   		} 
   		return false;
	}

	function translateFinish($positionText) {
		switch ($positionText) {
			case 'R':
				return 'Retired';
				break;
			case 'D':
				return 'Disqualified';
				break;
			case 'E':
				return 'Excluded';
				break;
			case 'W':
				return 'Withdrawn';
				break;
			case 'F':
				return 'Failed to qualify';
				break;
			case 'N':
				return 'Not classified';
				break;
			default:
				return $positionText;
				break;
		}
	}

?>