<?php

	$active_page = "Dashboard";

	require_once 'partials/header.php';

?>

	<!-- Default state for JS variable to handle errors when no user is signed in -->
	<script> var isUserLoggedIn = false </script>

	<main>
		
		<div class="parallax-container" id="dashboard-parallax">
      		<div class="parallax"><img src="images/main-header-alt.jpg" alt="2013 Formula 1 starting grid"></div>
    	</div>

		<div class="section container">
			
			<h1>Dashboard</h1>

			<?php

				require_once "partials/accounthandling.php"

			?>

			<div id="contentbox" class="row">
				
				<div class="col s12">
      				<ul class="tabs tabs-fixed-width">
        				<li class="tab"><a href="#drivers" class="black-text">Drivers</a></li>
        				<li class="tab"><a href="#updates" class="black-text">Updates</a></li>
        				<li class="tab"><a href="#settings" class="black-text">Account</a></li>
      				</ul>
    			</div>

    			<!-- Wrapper for content divs -->
    			<div>

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

	    			<div id="updates" class="col s12">

						<h3>Drivers' Championship Standings</h3>

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

	    			</div> <!-- /updates tab -->

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

    			</div>


			</div> <!-- /contentbox -->

		</div> <!-- /container -->
		
	</main>

<?php 

	require_once 'partials/footer.php'; 

?>