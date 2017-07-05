<?php

	$active_page = "Admin Section";

	require_once 'partials/header.php';

	// Get number of admin users
	$admin_result = querySQL("SELECT COUNT(userId) AS 'count' FROM `users` WHERE role = 'admin'");

	if (mysqli_num_rows($admin_result) > 0) {
		while ($row = mysqli_fetch_assoc($admin_result)) {
			extract($row);

			$count_admin = $count;
		}
	}

	// Get number of regular users
	$regular_result = querySQL("SELECT COUNT(userId) AS 'count' FROM `users` WHERE role = 'regular'");

	if (mysqli_num_rows($regular_result) > 0) {
		while ($row = mysqli_fetch_assoc($regular_result)) {
			extract($row);

			$count_regular = $count;
		}
	}

?>

	<main>

		<div class="section container">
			
			<h1 class="center-align">Admin Only</h1>

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
							$regular_result = querySQL("SELECT CONCAT(drivers.forename, ' ', drivers.surname) AS 'driverName',
							 COUNT(favoritedrivers.userId) as 'userCount' 
							 FROM drivers JOIN favoritedrivers ON favoritedrivers.driverId = drivers.driverId 
							 GROUP BY driverName ORDER BY userCount DESC, driverName");

							if (mysqli_num_rows($regular_result) > 0) {
								while ($row = mysqli_fetch_assoc($regular_result)) {
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