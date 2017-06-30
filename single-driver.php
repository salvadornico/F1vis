<?php

	$current_driver_id = $_GET['id'];
	$current_driver_name = $_GET['name'];
	$active_page = "$current_driver_name - Drivers";

	require_once 'partials/header.php';
	$is_driver_page = true;

	$races_result = querySQL("SELECT CONCAT(races.year, ' ', races.name) as grand_prix, results.grid, results.positionText, constructors.name , results.position
		FROM results join races on races.raceId = results.raceId 
		join constructors on constructors.constructorId = results.constructorId 
		join drivers on results.driverId = drivers.driverId
		WHERE drivers.driverRef = '$current_driver_id' ORDER BY races.year, races.round");

	$results = [];
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
			$results[] = $new_result;
		}
	}

	// Export to JSON
	$fp = fopen('js/results.json', 'w');
	fwrite($fp, json_encode($results, JSON_PRETTY_PRINT));
	fclose($fp);
	// TODO: create separate files/directories per user to avoid conflicts?

?>

	<main>
		
		<div class="container">

			<a class='waves-effect waves-light btn yellow darken-3 back-btn' href="drivers.php">Back to Drivers</a>

			<h3 id="driver-title">Finishing Positions of <?php echo $current_driver_name; ?></h3>

			<button class="btn-floating btn-large green tooltipped" data-position="left" data-delay="50" data-tooltip="Back to top" id="driver-fab">
  				<i class="fa fa-chevron-up" aria-hidden="true"></i>
			</button>

			<div id="graph" class="responsive-table clear">

				<div class='progress' id="graphLoadingBar">
					<div class='indeterminate'></div>
				</div>
				
				<!-- Content populated by printGraphRow() in driverPage.js -->

			</div>

			<table id="resultsTable">
				<tr>
					<th>Grand Prix</th>
					<th>Qualified</th>
					<th>Finish</th>
					<th>Constructor</th>
				</tr>

				<div class='progress' id="tableLoadingBar">
					<div class='indeterminate'></div>
				</div>

				<!-- Content populated by printTable() in driverPage.js -->

			</table>

		</div> <!-- /container -->

	</main>

<?php

	require_once 'partials/footer.php';

?>