<?php

	$active_page = "Admin Section";

	require_once 'partials/header.php';

	// Get number of admin users
	$admin_result = querySQL("SELECT COUNT(userId) AS 'count' FROM users WHERE role = 'admin'");

	if (mysqli_num_rows($admin_result) > 0) {
		while ($row = mysqli_fetch_assoc($admin_result)) {
			extract($row);
			$count_admin = $count;
		}
	}

	// Get number of regular users
	$regular_result = querySQL("SELECT COUNT(userId) AS 'count' FROM users WHERE role = 'regular'");

	if (mysqli_num_rows($regular_result) > 0) {
		while ($row = mysqli_fetch_assoc($regular_result)) {
			extract($row);
			$count_regular = $count;
		}
	}


	// Check most recent race data in database
	$rounds_result = querySQL("SELECT DISTINCT round AS 'last_round', year AS 'last_year' FROM races WHERE raceId = (SELECT MAX(raceId) FROM results)");
	while ($row = mysqli_fetch_assoc($rounds_result)) {
		extract($row);
	}

	// CHECK FOR UPDATED RACE DATA
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

	// Array of files in temp folder
	$temp_dir = glob('js/temp/*');
	$num_temp_files = count($temp_dir);

	if (isset($_POST['cleanup_json'])) {
		foreach($temp_dir as $file) {
  			if(is_file($file)) { unlink($file); }
		}
		echo "<script> Materialize.toast('$num_temp_files files deleted', 4000) </script>";
		$num_temp_files = 0;
	}

?>

	<main>

		<div class="section container">
			
			<h1 class="center-align">Admin Only</h1>

			<div class="row">
				<form method="POST" class="admin-form">
					<div class="col s12 m5 offset-m2">
						<span>Database updated as of: </span>
						<span><?php echo "Round $last_round - $last_year season"; ?></span>
					</div>
					<div class="col s12 m5">
						<button class="waves-effect waves-light btn yellow darken-3" name="update_check" value="check">
							Check for updates
						</button>
					</div>
				</form>
			</div>

			<div class="row">
				<form method="POST" class="admin-form">
					<div class="col m5 offset-m2">
						<span>Files in Temp folder:</span>
						<span><?php echo $num_temp_files; ?></span>
					</div>
					<div class="col s12 m5">
						<button class="waves-effect waves-light btn yellow darken-3" name="cleanup_json" value="flush">
							Clean up
						</button>
					</div>						
				</form>													
			</div>

			<div class="row">

				<div class="col s12 m6">
					
					<h4 class="center-align">Most Favorited Drivers</h4>
					<table class="centered bordered">
						<tr>
							<th class="center-align">Driver</th>
							<th class="center-align">Users</th>
						</tr>

						<?php 

							// Tally favorite drivers
							$favorite_result = querySQL("SELECT CONCAT(drivers.forename, ' ', drivers.surname) AS 'driverName',
							 COUNT(favoritedrivers.userId) as 'userCount' 
							 FROM drivers JOIN favoritedrivers ON favoritedrivers.driverId = drivers.driverId 
							 GROUP BY driverName ORDER BY userCount DESC, driverName");

							if (mysqli_num_rows($favorite_result) > 0) {
								while ($row = mysqli_fetch_assoc($favorite_result)) {
									extract($row);
									echo "<tr><td>$driverName</td><td>$userCount</td></tr>";
								}
							}

						?>

					</table>

				</div> <!-- /favorites div -->			

				<div class="col s12 m6">
					
					<h4 class="center-align">Registered User Count</h4>
					<table class="centered bordered">
						<tr>
							<th class="center-align">Admin</th>
							<th class="center-align">Regular</th>
						</tr>
						<tr>
							<?php 

								echo "<td>$count_admin</td>";
								echo "<td>$count_regular</td>";

							?>
						</tr>
					</table>

				</div> <!-- /users div -->
				
			</div>

		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>