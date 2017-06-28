<?php

	$active_page = "Drivers";

	require_once 'partials/header.php';

	$debuts = [];

	$result = querySQL("SELECT drivers.driverId AS driverId, CONCAT(drivers.forename, ' ', drivers.surname) AS 'driver_name', 
		min(races.date) AS 'debut'
		FROM driverstandings JOIN drivers ON drivers.driverId = driverstandings.driverId
		JOIN races ON races.raceId = driverstandings.raceId
		GROUP BY driver_name ORDER BY debut DESC");

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			extract($row);

			// extract year from debut
			$date_arr = explode('-', $debut);
			$debut = $date_arr[0];

			$new_driver = [$debut => [$driverId, utf8_encode($driver_name)]];
			$debuts[] = $new_driver;
		}
	}

	// merge drivers with same year debut in array
	$collated_debuts = [];
	foreach ($debuts as $debut) {
		foreach ($debut as $year => $driver) {
			// if year is in array
			if (searchForYear($year, $collated_debuts)) {
				// append driver to correct year
				array_push($collated_debuts[$year], $driver);
			} else {
				// create new year entry for array with driver
				$collated_debuts[$year] = [$driver];			
			}
		}
	}
?>

	<main>

		<div class="container">

			<h2>Formula 1 drivers across history</h2>

			<table>

				<?php 
					foreach ($collated_debuts as $year => $drivers) {
						echo "<tr>
								<td>$year</td>
								<td>";
								foreach ($drivers as $driver) {
									echo "<a class='driver-btn waves-effect waves-light btn yellow darken-3' href='single-driver.php?id=$driver[0]&name=$driver[1]'>$driver[1]</a>";
								}
						echo "</td></tr>";
					}
				?>

			</table>

		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>