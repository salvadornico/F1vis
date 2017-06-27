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

	function translate_finish($positionText) {
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

	$race_results = [];

	$result = query_sql("SELECT CONCAT(races.year, ' ', races.name) as grand_prix, results.grid, results.positionText, constructors.name 
		FROM results join races on races.raceId = results.raceId join constructors on constructors.constructorId = results.constructorId 
		WHERE results.driverId = 102 ORDER BY races.year, races.round");

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			extract($row);

			$new_result = [$grand_prix, $grid, translate_finish($positionText), $name];
			$race_results[] = $new_result;
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

		<h2>Finishing Positions of Ayrton Senna</h2>

		<div id="graph_test">
			
			<?php 



			?>

		</div>

		<table>
			<tr>
				<th>Grand Prix</th>
				<th>Qualified</th>
				<th>Finish</th>
				<th>Constructor</th>
			</tr>

			<?php

				foreach ($race_results as $result) {
					echo "<tr>
								<td>$result[0]</td>
								<td>$result[1]</td>
								<td>$result[2]</td>
								<td>$result[3]</td>
							</tr>";
				}

			?>

		</table>

	</body>

</html>