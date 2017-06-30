<?php

	$active_page = "Home";

	require_once 'partials/header.php';

?>

	<main>
		
		<div class="parallax-container" id="home-parallax">
      		<div class="parallax"><img src="images/main-header.jpg" alt="60's Lotus Formula 1 car"></div>
            <div>
                <h1 class="center-align white-text flow-text">monoposto</h1>
                <h5 class="center-align white-text">Formula 1 Data Visualization</h5>
            </div>
    	</div>

		<div class="section container">
			
			<div class="row">
				
				<h1>Home :)</h1>

				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>

			</div>

			<div class="row">
				
				<h3>Latest F1 News</h3>
				<div class="row" id="newsbox">

					<div class='progress'>
						<div class='indeterminate'></div>
					</div>

					<!-- News populated by homePage.js -->

				</div> <!-- /newsbox -->
				<span>
					News stories courtesy of <a href="https://www.theguardian.com">The Guardian</a>
				</span>

			</div>


			
		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>