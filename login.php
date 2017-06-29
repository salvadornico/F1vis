<?php

	$active_page = "Login";

	require_once 'partials/header.php';

?>

	<main>

		<div class="section container">
			
			<h4 class="center-align">Please Log In</h4>

			<div class="row">
			    <form class="col s12" method="POST" action="dashboard.php">
			    	<div class="row">
			        	<div class="input-field col s6 offset-s3">
			    			<input id="username" name="username" type="text" class="validate">
			    			<label for="username">Username</label>
			        	</div>
			      	</div>
			      	<div class="row">
			        	<div class="input-field col s6 offset-s3">
			    			<input id="password" name="password" type="password" class="validate">
			    			<label for="password">Password</label>
			        	</div>
			      	</div>
			      	<div class="row">
			        	<div class="input-field col s2 offset-s5">
			    			<button class="waves-effect waves-light btn yellow darken-3" name="submit_login" value="Login">
								Login
							</button>
			        	</div>
			      	</div>			      	
			    </form>
		  	</div>

		  	<hr>

		  	<div class="row reg-box clear">

		  		<div class="col s12 m3 offset-m3">
		  			<h5 class="center-align">New to the site?</h5>
		  		</div>

		  		<div class="col s12 m3">
		  			<!-- Registration Modal Trigger -->
					<a class="waves-effect waves-light btn green center-align" href="#reg-modal" id="reg-btn">Register</a>
		  		</div>

		  	</div>		  	

			  <!-- Modal Structure -->
			<div id="reg-modal" class="modal">
		    	<div class="modal-content">

		      		<h4>Create an account</h4>

			    	<div class="row">
			    		<form class="col s12" method="POST" action="dashboard.php">

			      			<div class="row">
			        			<div class="input-field col s12 m6">
			          				<input placeholder="Type your name" id="name" name="name" type="text" class="validate">
			          				<label for="name">First Name</label>
			        			</div>

			        			<div class="input-field col s12 m6">
			          				<input placeholder="Choose a username" id="username" name="username" type="text" class="validate">
			          				<label for="username">Username</label>
			        			</div>
			      			</div>

			      			<div class="row">
			        			<div class="input-field col s12 m6">
			          				<input id="password" name="password" type="password" class="validate">
			          				<label for="password">Password</label>
			        			</div>
			        			<div class="input-field col s12 m6">
			          				<input id="confirm_password" name="confirm_password" type="password" class="validate">
			          				<label for="confirm_password">Confirm password</label>
			        			</div>
			      			</div>

			      			<!-- Avatar select -->
			      			<div class="row">
			      				<div class="col s12 m6 l3">
				      				<label for="avatar">Choose an avatar</label>
			      				</div>
			      			</div>
			      			<div class="row">
			      				<div class="avatar-select col s6 m4 l2">
			      					<input class="with-gap" name="avatar" value="red" type="radio" id="avatar1" />
      								<label for="avatar1">
      									<img src="images/avatars/avatar-red.png" class="avatar responsive-img" alt="Red user avatar">
      								</label>
			      				</div>
			      				<div class="avatar-select col s6 m4 l2">
			      					<input class="with-gap" name="avatar" value="blue" type="radio" id="avatar2" />
      								<label for="avatar2">
      									<img src="images/avatars/avatar-blue.png" class="avatar responsive-img" alt="Blue user avatar">
      								</label>
			      				</div>
			      				<div class="avatar-select col s6 m4 l2">
			      					<input class="with-gap" name="avatar" value="green" type="radio" id="avatar3" />
      								<label for="avatar3">
      									<img src="images/avatars/avatar-green.png" class="avatar responsive-img" alt="Green user avatar">
      								</label>
			      				</div>
			      				<div class="avatar-select col s6 m4 l2">
			      					<input class="with-gap" name="avatar" value="orange" type="radio" id="avatar4" />
      								<label for="avatar4">
      									<img src="images/avatars/avatar-orange.png" class="avatar responsive-img" alt="Orange user avatar">
      								</label>
			      				</div>
			      				<div class="avatar-select col s6 m4 l2">
			      					<input class="with-gap" name="avatar" value="purple" type="radio" id="avatar5" />
      								<label for="avatar5">
      									<img src="images/avatars/avatar-purple.png" class="avatar responsive-img" alt="Purple user avatar">
      								</label>
			      				</div>
			      				<div class="avatar-select col s6 m4 l2">
			      					<input class="with-gap" name="avatar" value="pink" type="radio" id="avatar6" />
      								<label for="avatar6">
      									<img src="images/avatars/avatar-pink.png" class="avatar responsive-img" alt="Pink user avatar">
      								</label>
			      				</div>
			      			</div> <!-- /avatar row -->

			      			<div class="row">
					        	<div class="input-field col s2 offset-s5">
					    			<button class="waves-effect waves-light btn-large yellow darken-3" name="register_user" value="register">
										Register
									</button>
					        	</div>
			      			</div>

			    		</form>
		  			</div> <!-- /form row -->
		  		</div> <!-- /modal content -->

			    <div class="modal-footer">
			      	<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
			    </div>

			</div> <!-- /registration modal -->

		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>