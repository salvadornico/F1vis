<?php

	session_start();

	require_once 'partials/lib.php';


	$current_driver_ref = $_GET['id'];

	// Get database user & driver IDs
	$result = querySQL("SELECT drivers.driverId, users.userId FROM drivers JOIN users WHERE drivers.driverRef = '$current_driver_ref' AND users.username = '".$_SESSION['username']."'");
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			extract($row);
			
			$current_driver_id = $driverId;
			$current_user = $userId;
		}
	}

	// save favorite to database
	$sql = "INSERT INTO favoritedrivers (userId, driverId) 
		VALUES ('$current_user', '$current_driver_id')";
	mysqli_query($conn, $sql);
	

	header('location:dashboard.php');

?>