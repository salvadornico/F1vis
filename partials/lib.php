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

	$nav_links = [
		['Home', 'index.php'],
		['Register / Login', 'login.php'],
		['Dashboard', '#'],
		['Drivers Database', 'drivers.php']
	];

	function printNav($classes = null) {
		global $nav_links;
		foreach ($nav_links as $link) {
			echo "<li><a href='$link[1]'";
			if ($classes != null) {
				echo " class='$classes'";
			}
			echo ">$link[0]</a></li>";
		}
	}

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

?>