<?php

	// Open SQL connection
	$host = 'localhost';
	$sql_username = 'root';
	$sql_password = '';
	$database = 'f1db';
	$conn = mysqli_connect($host, $sql_username, $sql_password, $database);

	// Set up query
	function query_sql($query) {
		global $conn;
		$result = mysqli_query($conn, $query);
		return $result;
	}

?>

<!DOCTYPE html>
<html>

	<head>
		<title>Ergast Formula 1 database test - mySQL</title>
	</head>

	<body>

		<h1>Ergast Formula 1 database test</h1>

		<h2>Finishing Positions of Ayrton Senna</h2>

		<table>
			<tr>
				<th>Grand Prix</th>
				<th>Qualified</th>
				<th>Finish</th>
			</tr>

			<?php

				$result = query_sql("SELECT CONCAT(races.year, ' ', races.name) as grand_prix, results.grid, results.positionText FROM results join races on races.raceId = results.raceId WHERE results.driverId = 102 ORDER BY races.year, races.round");

				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						extract($row);

						echo "<tr>
								<td>$grand_prix</td>
								<td>$grid</td>
								<td>$positionText</td>
							</tr>";
					}
				}

			?>

		</table>

	</body>

</html>