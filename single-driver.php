<?php

	// handles search from form
	if (isset($_POST['submit_driver_search'])) {
		$search_term = $_POST['driver-search'];

		$conn = mysqli_connect('localhost', 'salvado8_nico', 'password', 'salvado8_f1db');
		mysqli_set_charset($conn, "UTF8");
		$driver_query = "SELECT driverRef AS driverId, CONCAT(forename, ' ', surname) AS 'driverName' 
			FROM drivers WHERE CONCAT(forename, ' ', surname) LIKE '$search_term'";
		$driver_result = mysqli_query($conn, $driver_query);
		if (mysqli_num_rows($driver_result) > 0) {
			while ($row = mysqli_fetch_assoc($driver_result)) {
				extract($row);
				$current_driver_id = $driverId;
				$current_driver_name = htmlentities($driverName);
			}
		} else {
			header("location: driver404.php");
			exit();
		}
	} else if (isset($_GET['id'])) { // from direct link
		$current_driver_id = $_GET['id'];
		$current_driver_name = $_GET['name'];
	} else {
		header("location: driver404.php");
		exit();
	}

	$active_page = "$current_driver_name | Drivers";

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
			if ($position == null) { $position = 0; }

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

	// Export to separate JSON files per user to prevent conflicts with concurrent users
	if (!isset($_SESSION['username'])) { $_SESSION['username'] = time(); } // Create temporary username
	$user = $_SESSION['username'];
	// create directory if not yet there
	if (!file_exists('js/temp')) { mkdir('js/temp'); }

	$fp = fopen('js/temp/results-'.$user.'.json', 'w');
	// Pass username to JS
	echo "<script> var currentUser = '$user' </script>";
	fwrite($fp, json_encode($results, JSON_PRETTY_PRINT));
	fclose($fp);

?>

	<main>
		
		<div class="container">

			<h3 id="driver-title">Finishing Positions of <?php echo $current_driver_name; ?></h3>


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
		
		<!-- Floating action button -->
		<div class="fixed-action-btn toolbar" id="driver-fab">
			<a class="btn-floating btn-large yellow darken-3 pulse">
  				<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
			</a>
			<ul>
				<?php

      				if (isset($_SESSION['user'])) {
	      				echo "<li class='waves-effect waves-light'>
	      						<a href='add-remove-driver.php?id=$current_driver_id&action=add' id='scrollUpBtn'>
	      							<i class='fa fa-plus' aria-hidden='true'></i>
	      							<span class='hide-on-small-only'>&nbsp;&nbsp;Add to favorites</span>
	      						</a
	      					</li>";
      				}

				?>
  				<li class="waves-effect waves-light">
  					<a id="backBtnFab">
  						<i class="fa fa-chevron-up" aria-hidden="true"></i>
  						<span class="hide-on-small-only">&nbsp;&nbsp;Back to top</span>
  					</a>
					</li>
  				<li class="waves-effect waves-light">
  					<a href="drivers.php">
  						<i class="fa fa-address-card" aria-hidden="true"></i>
  						<span class="hide-on-small-only">&nbsp;&nbsp;Back to Drivers List</span>
  					</a>
					</li>
					<li class="waves-effect waves-light">
  					<a id="legendBtn">
  						<i class="fa fa-info-circle" aria-hidden="true"></i>
  						<span class="hide-on-small-only">&nbsp;&nbsp;Toggle legend</span>
  					</a>
					</li>
  			</ul>				
		</div> <!-- /FAB -->

		<div id="legend" class="scale-transition">
			<img src="images/legend.png" alt="Legend for race position graph">
		</div>

	</main>

<?php

	require_once 'partials/footer.php';

?>