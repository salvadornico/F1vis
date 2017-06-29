<?php

	$active_page = "Dashboard";

	require_once 'partials/header.php';

?>

	<main>
		
		<div class="parallax-container" id="dashboard-parallax">
      		<div class="parallax"><img src="images/main-header-alt.jpg" alt="2013 Formula 1 starting grid"></div>
    	</div>

		<div class="section container">
			
			<h1>Dashboard</h1>

			<?php

				if (isset($_POST['register_user'])) {

					// Register new user
					$name = $_POST['name'];
					$username = $_POST['username'];
					$password = $_POST['password'];
					$confirm_password = $_POST['confirm_password'];
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

					// TODO: display discovery tooltips
				}

				// Login processing
				if (isset($_POST['submit_login'])) {
					$username = $_POST['username'];
					$password = sha1($_POST['password']);

					$result = querySQL("SELECT * FROM users WHERE username = '$username'
							and password = '$password'");
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							extract($row);
							$_SESSION['user'] = $name;
							$_SESSION['role'] = $role;			
							$_SESSION['avatar'] = $avatar;			
						}

						echo '<META HTTP-EQUIV=REFRESH CONTENT="0; dashboard.php">';
					} else {
						echo "Login not found. Please try again.";
					}
				}

				// TODO: favorite drivers
				// TODO: account settings

			?>


		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>