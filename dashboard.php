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

				// Default state for JS variable to handle errors when no user is signed in
				echo "<script> var isUserLoggedIn = false </script>";

				// Register new user
				if (isset($_POST['register_user'])) {

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

						echo '<META HTTP-EQUIV=REFRESH CONTENT="0; dashboard.php">';
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
									$new_password = $_POST['new_password'];
									$confirm_password = $_POST['confirm_password'];
									
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

			<div id="contentbox" class="row">
				
				<div class="col s12">
      				<ul class="tabs tabs-fixed-width">
        				<li class="tab"><a href="#drivers" class="black-text">Drivers</a></li>
        				<li class="tab"><a href="#news" class="black-text">News</a></li>
        				<li class="tab"><a href="#settings" class="black-text">Account</a></li>
      				</ul>
    			</div>

    			<div id="drivers" class="col s12">

					<h3>Favorite Drivers</h3>

					<div class="row">

						<div class="col s12 m6">
							
							<!-- Retrieving favorite drivers -->
							<?php

								$username = $_SESSION['username'];

								$drivers_result = querySQL("SELECT DISTINCT drivers.driverRef, CONCAT(drivers.forename, ' ', drivers.surname) AS 'driverName' FROM drivers JOIN favoritedrivers ON drivers.driverId = favoritedrivers.driverId JOIN users ON users.userId = favoritedrivers.userId WHERE users.username = '$username'");
								if (mysqli_num_rows($drivers_result) > 0) {
									echo "<table class='bordered highlight'>";
									while ($row = mysqli_fetch_assoc($drivers_result)) {
										extract($row);

										$driverName = utf8_encode($driverName);
										
										echo "<tr>
												<td>
													<a href='single-driver.php?id=$driverRef&name=$driverName'>
														$driverName
													</a>
												</td>
												<td>
													<a href='add-remove-driver.php?id=$driverRef&action=del' class='btn-flat red-text waves-red right-align'>
														Remove
													</a>
											</tr>";			
									}
									echo "</table>";
								} else {
									echo "No drivers added. Go to <a href='drivers.php'>the database</a> and add some!";
								}

							?>

						</div>
						
						<div class="col s12 m4 center-align">
							<a href="drivers.php" class="waves-effect waves-light btn-large yellow darken-3">
								Add
							</a>
						</div>

					</div>



    			</div> <!-- /drivers tab -->

    			<div id="news" class="col s12">

					<h3>Current Standings</h3>

					<div class='progress' id="standingsLoadingBar">
						<div class='indeterminate'></div>
					</div>

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

    			</div> <!-- /news tab -->

    			<div id="settings" class="col s12">

    				<h3>Account Settings</h3>

    				<ul class="collapsible" data-collapsible="accordion">

	    				<!-- Change avatar -->
    					<li>
      						<div class="collapsible-header">Change avatar</div>
      						<div class="collapsible-body">
			    				<div class="row">
			    					<form method="POST" class="col s12">

			    						<!-- Avatar select -->
			    						<div class="row">
				    						<h5>Change your avatar</h5>
			    						</div>

						      			<div class="row">
			    							<?php printAvatars(); ?>
						      			</div> <!-- /avatar row -->

						      			<div class="row">
						      				<div class="col s2">
						      					<button class="waves-effect waves-light btn blue darken-4" name="change_avatar" value="change">
													Save
												</button>
						      				</div>
						      			</div>

			    					</form>
			    				</div> <!-- /form row -->
      						</div> <!-- /collapsible body -->
    					</li> <!-- /change avatar -->

	    				<!-- Change password -->
    					<li>
      						<div class="collapsible-header">Change password</div>
      						<div class="collapsible-body">
			    				<div class="row">
			    					<form method="POST" class="col s12">

			    						<!-- Avatar select -->
			    						<div class="row">
				    						<h5>Change your password</h5>
			    						</div>

			    						<div class="row">
			    							<div class="input-field col s12 m6">
						          				<input id="old_password" name="old_password" type="password" class="validate" required>
						          				<label for="old_password">Current password</label>
						        			</div>
			    						</div>
						      			<div class="row">
						        			<div class="input-field col s12 m6">
						          				<input id="new_password" name="new_password" type="password" class="validate" required>
						          				<label for="new_password">New password</label>
						        			</div>
						        			<div class="input-field col s12 m6">
						          				<input id="confirm_password" name="confirm_password" type="password" class="validate" required>
						          				<label for="confirm_password">Confirm new password</label>
						        			</div>
						      			</div>

						      			<div class="row">
						      				<div class="col s2">
						      					<button class="waves-effect waves-light btn blue darken-4" name="change_password" value="change">
													Save
												</button>
						      				</div>
						      			</div>

			    					</form>
			    				</div> <!-- /form row -->
      						</div> <!-- /collapsible body -->
    					</li> <!-- /change password -->
    					
  					</ul>



    			</div> <!-- /settings tab -->



			</div> <!-- /contentbox -->

		</div> <!-- /container -->
		
	</main>

<?php require_once 'partials/footer.php'; ?>