<?php

	$current_driver_id = $_GET['id'];
	$current_driver_name = $_GET['name'];
	$active_page = "$current_driver_name - Drivers";

	require_once 'partials/header.php';
	$is_driver_page = true;

	$races_result = querySQL("SELECT CONCAT(races.year, ' ', races.name) as grand_prix, results.grid, results.positionText, constructors.name , results.position
		FROM results join races on races.raceId = results.raceId join constructors on constructors.constructorId = results.constructorId 
		WHERE results.driverId = $current_driver_id ORDER BY races.year, races.round");

	$race_results = [];
	if (mysqli_num_rows($races_result) > 0) {
		while ($row = mysqli_fetch_assoc($races_result)) {
			extract($row);

			// convert null values to zero
			if ($position == null) {
				$position = 0;
			}

			$new_result = [
				'race' => $grand_prix, 
				'grid' => intval($grid), 
				'posText' => translateFinish($positionText), 
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

	<main>
		
		<div class="container">

			<a class='waves-effect waves-light btn yellow darken-3 back-btn' href="drivers.php">Back</a>

			<h3>Finishing Positions of <?php echo $current_driver_name; ?></h3>

			<div id="graph" class="responsive-table clear">
				
				<!-- Content populated by JS -->

			</div>

			<table id="resultsTable">
				<tr>
					<th>Grand Prix</th>
					<th>Qualified</th>
					<th>Finish</th>
					<th>Constructor</th>
				</tr>

				<!-- Content populated by JS -->

			</table>

		</div> <!-- /container -->

	</main>

<?php

	require_once 'partials/footer.php';

?>