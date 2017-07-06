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


	// For updating results
	function updateResults($race_array) {
		global $conn;

		foreach ($race_array as $race) {
			$year = $race[0];
			$round = $race[1];

			$string = file_get_contents("http://ergast.com/api/f1/$year/$round/results.json");
			$latest_result = json_decode($string, true);

			// check if race is already in database
			$race_result = querySQL("SELECT raceId FROM races WHERE year = $year AND round = $round");
			if (mysqli_num_rows($race_result) == 0) {
				//add race
				$circuitRef = $latest_result['MRData']['RaceTable']['Races'][0]['Circuit']['circuitId'];	
				// Find database circuit ID of track
				$circuit_result = querySQL("SELECT circuitId FROM circuits WHERE circuitRef = '$circuitRef'");
				if (mysqli_num_rows($circuit_result) > 0) {
					while ($row = mysqli_fetch_assoc($circuit_result)) {
						extract($row);
					}
				}

				$name = $latest_result['MRData']['RaceTable']['Races'][0]['raceName'];
				$date = $latest_result['MRData']['RaceTable']['Races'][0]['date'];
				$time = $latest_result['MRData']['RaceTable']['Races'][0]['time'];
				$url = $latest_result['MRData']['RaceTable']['Races'][0]['url'];

				$add_race_sql = "INSERT INTO races (year, round, circuitId, name, date, time, url) 
				VALUES ('$year', '$round', '$circuitId', '$name', '$date', '$time', '$url')";
				mysqli_query($conn, $add_race_sql);

				$race_result = querySQL("SELECT raceId FROM races WHERE year = $year AND round = $round");
			}
			while ($row = mysqli_fetch_assoc($race_result)) {
				extract($row);
			}

			$results_result = querySQL("SELECT * FROM results JOIN races ON results.raceId = races.raceId 
				WHERE races.year = $year AND races.round = $round");
			if (mysqli_num_rows($results_result) == 0) {
				// Proceed with adding results
				$results_arr = $latest_result['MRData']['RaceTable']['Races'][0]['Results'];

				foreach ($results_arr as $result) {
					$driverRef = $result['Driver']['driverId'];
					$constructorRef = $result['Constructor']['constructorId'];

					// Get database IDs for driver & constructor
					$driver_result = querySQL("SELECT driverId FROM drivers WHERE driverRef = '$driverRef'");
					if (mysqli_num_rows($driver_result) == 0) { 
						// add new driver
						$number = $result['Driver']['permanentNumber'];
						$code = $result['Driver']['code'];
						$forename = $result['Driver']['givenName'];
						$surname = $result['Driver']['familyName'];
						$dob = $result['Driver']['dateOfBirth'];
						$nationality = $result['Driver']['nationality'];
						$url = $result['Driver']['url'];

						$add_driver_sql = "INSERT INTO drivers (driverRef, number, code, forename, surname, dob, nationality, url) VALUES ('$driverRef', '$number', '$code', '$forename', '$surname', '$dob', '$nationality', '$url')";
						mysqli_query($conn, $add_driver_sql);

						$driver_result = querySQL("SELECT driverId FROM drivers WHERE driverRef = '$driverRef'");
					}
					while ($row = mysqli_fetch_assoc($driver_result)) {
						extract($row);
					}

					$constructor_result = querySQL("SELECT constructorId FROM constructors WHERE constructorRef = '$constructorRef'");
					if (mysqli_num_rows($constructor_result) == 0) { 
						// add new constructor
						$name = $result['Constructor']['name'];
						$nationality = $result['Constructor']['nationality'];
						$url = $result['Constructor']['url'];

						$add_constructor_sql = "INSERT INTO constructors (constructorRef, name, nationality, url) VALUES ('$constructorRef', '$name', '$nationality', '$url')";
						mysqli_query($conn, $add_constructor_sql);

						$constructor_result = querySQL("SELECT constructorId FROM constructors WHERE constructorRef = '$constructorRef'");
					}
					while ($row = mysqli_fetch_assoc($constructor_result)) {
						extract($row);
					}

					$grid = $result['grid'];
					$position = $result['position'];
					$positionText = $result['positionText'];

					$add_result_sql = "INSERT INTO results (raceId, driverId, constructorId, grid, position, positionText) VALUES ('$raceId', '$driverId', '$constructorId', '$grid', '$position', '$positionText')";
					mysqli_query($conn, $add_result_sql);
				}
			}

			// Delay to avoid API polling limit
			sleep(0.3);
		}
	}

?>