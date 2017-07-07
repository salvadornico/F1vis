<?php

	session_start();

	require_once 'partials/lib.php';


	$current_driver_ref = $_GET['id'];

	// Get database user & driver IDs
	$result = querySQL("SELECT drivers.driverId, users.userId FROM drivers JOIN users WHERE drivers.driverRef = '$current_driver_ref' AND users.username = '".$_SESSION['username']."'");
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			extract($row);
		}
	}

	if ($_GET['action'] == 'add') {
		// save favorite to database
		$sql = "INSERT INTO favoritedrivers (userId, driverId) VALUES ('$userId', '$driverId')";
	} else if ($_GET['action'] == 'del') {
		// remove favorite from database
		$sql = "DELETE FROM favoritedrivers WHERE userId = '$userId' AND driverId = '$driverId'";
	}

	mysqli_query($conn, $sql);

	header('location:dashboard.php');

?>