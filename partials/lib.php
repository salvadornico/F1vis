<?php

	// Open SQL connection
	$host = 'localhost';
	$sql_username = 'root';
	$sql_password = '';
	$database = 'f1db';
	$conn = mysqli_connect($host, $sql_username, $sql_password, $database);

	// Set up query
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
			if ($classes != null) {
				echo " class='$classes'";
			}
			echo ">$link[2] $link[0]</a></li>";
		}
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


    // For reorganizing driver list array
	function searchForYear($id, $array) {
   		foreach ($array as $key => $val) {
       		if ($key == $id) {
           		return true;
       		}
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


	// user avatar in side nav
	function displayAvatar() {
		if(isset($_SESSION['user'])) { echo "avatars/avatar-" . $_SESSION['avatar'] . ".png"; }
		else { echo "avatars/avatar-default.png"; }		
	}

?>