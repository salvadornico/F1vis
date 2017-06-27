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

	function searchForYear($id, $array) {
   		foreach ($array as $key => $val) {
       		if ($key == $id) {
           		return true;
       		}
   		}
   		return false;
	}

?>