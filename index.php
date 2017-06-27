<?php

	require_once 'lib.php';

	$debuts = [];

	$result = query_sql("SELECT CONCAT(drivers.forename, ' ', drivers.surname) AS 'driver_name', 
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

			$new_driver = [utf8_encode($driver_name) => $debut];
			$debuts[] = $new_driver;
		}
	}

	// merge drivers with same year debut in array
	$collated_debuts = [];
	foreach ($debuts as $debut) {
		foreach ($debut as $driver => $year) {
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

<!DOCTYPE html>
<html>

	<head>
		<title>Ergast Formula 1 database test - mySQL</title>
	</head>

	<body>

		<h1>Ergast Formula 1 database test</h1>

		<h2>Driver Debuts</h2>

		<table>
			<tr>
				<th>Year</th>
				<th>Drivers</th>
			</tr>

			<?php 
				foreach ($collated_debuts as $year => $drivers) {
					echo "<tr>
							<td>$year</td>
							<td>";
							foreach ($drivers as $driver) {
								echo "$driver<br>";
							}
					echo "</td></tr>";
				}
			?>

		</table>

	</body>

</html>

<?php

// Driver debut SQL query:


?>