<?php

	// Check most recent race data in database
	$rounds_result = querySQL("SELECT DISTINCT round AS 'last_round', year AS 'last_year' FROM races WHERE raceId = (SELECT MAX(raceId) FROM results)");
	while ($row = mysqli_fetch_assoc($rounds_result)) {
		extract($row);
	}

	if (isset($_POST['update_check'])) {
		// Get latest year & round of results
		$string = file_get_contents("http://ergast.com/api/f1/current/last/results.json");
		$latest_result = json_decode($string, true);
		$year = $latest_result['MRData']['RaceTable']['Races'][0]['season'];
		$round = $latest_result['MRData']['RaceTable']['Races'][0]['round'];

		if ($year > $last_year) {
			// Get remaining rounds of $last_year season
			$string = file_get_contents("http://ergast.com/api/f1/$last_year.json");
			$latest_result = json_decode($string, true);
			$last_year_max = $latest_result['MRData']['total'];		
			$missing_rounds = $last_year_max - $last_round;

			// Add to array of races to poll data for later
			$rounds_to_retrieve = [];
			for ($i = 1; $i <= $missing_rounds; $i++) { 
				$rounds_to_retrieve[] = [$last_year, $last_round + $i];
			}

			// Get elapsed rounds of $year season
			$missing_rounds = $round;
			for ($i = 1; $i <= $missing_rounds; $i++) { 
				$rounds_to_retrieve[] = [$year, $i];
			}

			// Get all rounds of anything in between
			$year_gap = $year - $last_year;
			if ($year_gap > 1) {
				for ($i = 1; $i <= ($year_gap - 1); $i++) {
					$current_year = $last_year + $i;
					$string = file_get_contents("http://ergast.com/api/f1/$current_year.json");
					$latest_result = json_decode($string, true);
					$current_year_max = $latest_result['MRData']['total'];

					for ($i = 1; $i <= $current_year_max; $i++) { 
						$rounds_to_retrieve[] = [$year, $i];
					}

					// Delay to avoid API polling limit
					sleep(0.3);
				}
			}

			$missing_rounds = count($rounds_to_retrieve);

			updateResults($rounds_to_retrieve);
			echo "<script> Materialize.toast('Database updated by $missing_rounds rounds', 4000) </script>";

		} else if ($year == $last_year && $round > $last_round) {
			$missing_rounds = $round - $last_round;

			$rounds_to_retrieve = [];
			for ($i = 1; $i <= $missing_rounds; $i++) { 
				$rounds_to_retrieve[] = [$year, $last_round + $i];
			}

			updateResults($rounds_to_retrieve);
			echo "<script> Materialize.toast('Database updated by $missing_rounds rounds', 4000) </script>";

		} else {
			echo "<script> Materialize.toast('Database up to date', 4000) </script>";
		}
	}

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