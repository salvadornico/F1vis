<?php

	require_once 'partials/lib.php';

	$race_results = [];

	$result = query_sql("SELECT CONCAT(races.year, ' ', races.name) as grand_prix, results.grid, results.positionText, constructors.name , results.position
		FROM results join races on races.raceId = results.raceId join constructors on constructors.constructorId = results.constructorId 
		WHERE results.driverId = 102 ORDER BY races.year, races.round");

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			extract($row);

			// convert null values to zero
			if ($position == null) {
				$position = 0;
			}

			$new_result = [
				'race' => $grand_prix, 
				'grid' => intval($grid), 
				'posText' => translate_finish($positionText), 
				'team' => $name, 
				'posNum' => intval($position)
				];
			$race_results[] = $new_result;
		}
	}

	// Export to JSON
	$fp = fopen('js/results.json', 'w');
	fwrite($fp, json_encode($race_results, JSON_PRETTY_PRINT));
	fclose($fp);

?>

<!DOCTYPE html>
<html>

	<head>

		<title>Ergast Formula 1 database test - mySQL</title>

		<!-- Materialize CSS -->
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">

  		<!-- Custom CSS -->
		<link rel="stylesheet" type="text/css" href="css/styles.css">

	</head>

	<body>

		<h1>Ergast Formula 1 database test</h1>

		<h2>Finishing Positions of Ayrton Senna</h2>

		<div id="graph" class="responsive-table clear">
			
			<!-- Content inserted by JS -->

		</div>

		<table id="resultsTable">
			<tr>
				<th>Grand Prix</th>
				<th>Qualified</th>
				<th>Finish</th>
				<th>Constructor</th>
			</tr>

			<!-- Content inserted by JS -->

		</table>

		<!-- jQuery -->
	    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

		<!-- Materialize JS -->
	  	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>

	  	<!-- Custom JS for driver info page -->
	  	<script src="js/driverPage.js"></script>

	</body>

</html>