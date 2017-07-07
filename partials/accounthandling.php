<?php

	// Register new user
	if (isset($_POST['register_user'])) {

		$name = htmlspecialchars($_POST['name']);
		$username = htmlspecialchars($_POST['username']);
		$password = htmlspecialchars($_POST['password']);
		$confirm_password = htmlspecialchars($_POST['confirm_password']);
		$avatar = $_POST['avatar'];

		if ($password == $confirm_password && $username != '') {
			$password = sha1($password);

			$sql = "INSERT INTO users (name, username, password, avatar, role)
					VALUES ('$name', '$username', '$password', '$avatar', 'regular')";

			mysqli_query($conn, $sql);

			echo "Registration successful!";
		} else {
			echo "Registration failed. Please try again.";
		}
	}

	// Login processing
	if (isset($_POST['submit_login'])) {
		$username = htmlspecialchars($_POST['username']);
		$password = sha1($_POST['password']);

		$result = querySQL("SELECT * FROM users WHERE username = '$username'
				and password = '$password'");
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				extract($row);
				$_SESSION['user'] = $name;
				$_SESSION['username'] = $username;
				$_SESSION['role'] = $role;			
				$_SESSION['avatar'] = $avatar;			
			}

			header('location:dashboard.php');
		} else {
			echo "Login not found. Please try again.";
		}
	}

	if (isset($_SESSION['user'])) {
		// Set JS value to allow feed scripts to run
		echo "<script> var isUserLoggedIn = true </script>";

		// changing avatar
		if (isset($_POST['change_avatar'])) {
			$new_avatar = $_POST['avatar'];
			$username = $_SESSION['username'];

			$sql = "UPDATE users SET avatar = '$new_avatar' WHERE username = '$username'";

			mysqli_query($conn, $sql);
			$_SESSION['avatar'] = $new_avatar;

			header('location:dashboard.php');
		}

		// changing password
		if (isset($_POST['change_password'])) {
			$current_username = $_SESSION['username'];
			$old_password = sha1($_POST['old_password']);

			// Check if old password is correct
			$result = querySQL("SELECT * FROM users WHERE username = '$current_username'
				and password = '$old_password'");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					extract($row);

					// if match
					if ($password == $old_password) {
						$new_password = htmlspecialchars($_POST['new_password']);
						$confirm_password = htmlspecialchars($_POST['confirm_password']);
						
						if ($new_password == $confirm_password) {
							$new_password = sha1($new_password);

							$sql = "UPDATE users SET password = '$new_password' WHERE username = '$current_username'";

							mysqli_query($conn, $sql);

							echo "Password change successful!";
						} else {
							echo "New passwords didn't match. Please try again.";
						}								
					}											
				}
			} else {
				echo "Wrong password. Please try again.";
			}
		}
	}

?>