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

					// TODO: echo script tags with jQuery instructions to open discovery tooltip
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
							$_SESSION['username'] = $username;
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

			<h3>Latest News</h3>
			<div class="row" id="newsbox">

				<!-- News populated by dashboard.js -->

			</div> <!-- /newsbox -->
			<span>
				News stories courtesy of <a href="https://www.theguardian.com">The Guardian</a>
			</span>

			<h3>Current Standings</h3>
			<span>
				As of Round <span id="roundLabel"></span>, <span id="seasonLabel"></span> season
			</span>
			<div class="row" id="standingsbox">

				<table id="standingsTable" class="highlight">
					<tr>
						<th>Pos</th>
						<th>Driver</th>
						<th>Points</th>
						<th>Constructor</th>
					</tr>
					
					<!-- Data populated by dashboard.js -->

				</table>

				<span id="standingsMessage"></span>

			</div> <!-- /standingsbox -->

		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>