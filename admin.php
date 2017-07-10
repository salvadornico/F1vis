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

	// CHECK FOR UPDATED RACE DATA
	require_once "partials/databaseupdating.php";

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