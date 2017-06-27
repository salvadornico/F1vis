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

?>