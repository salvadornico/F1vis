<?php

	$active_page = "Login";

	require_once 'partials/header.php';

?>

	<main>

		<div class="section container">
			
			<h4 class="center-align">Please Log In</h4>

			<div class="row">
			    <form class="col s12">
			    	<div class="row">
			        	<div class="input-field col s6 offset-s3">
			    			<input id="username" type="text" class="validate">
			    			<label for="username">Username</label>
			        	</div>
			      	</div>
			      	<div class="row">
			        	<div class="input-field col s6 offset-s3">
			    			<input id="password" type="password" class="validate">
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
		      		<h4>Modal Header</h4>
			    	<div class="row">
			    		<form class="col s12">
			      			<div class="row">
			        			<div class="input-field col s6">
			          				<input placeholder="Placeholder" id="first_name" type="text" class="validate">
			          				<label for="first_name">First Name</label>
			        			</div>
			        			<div class="input-field col s6">
			          				<input id="last_name" type="text" class="validate">
			          				<label for="last_name">Last Name</label>
			        			</div>
			      			</div>
			      			<div class="row">
			        			<div class="input-field col s12">
			          				<input id="password" type="password" class="validate">
			          				<label for="password">Password</label>
			        			</div>
			      			</div>
			    		</form>
		  			</div>
		  		</div>
			    <div class="modal-footer">
			      	<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
			    </div>
			</div>

		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>