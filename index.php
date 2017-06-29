<?php

	$active_page = "Home";

	require_once 'partials/header.php';

?>

	<main>
		
		<div class="parallax-container" id="home-parallax">
      		<div class="parallax"><img src="images/main-header.jpg" alt="60's Lotus Formula 1 car"></div>
            <div>
                <h1 class="center-align white-text">monoposto</h1>
                <h5 class="center-align white-text">Formula 1 Data Visualization</h5>
            </div>
    	</div>

		<div class="section container">
			
			<h1>Home :)</h1>
			<!-- Hello API? https://www.fourtonfish.com/hellosalut/hello/ -->

			<div id="newsbox">
				
				<!-- Guardian API query: http://content.guardianapis.com/search?order-by=newest&q=f1&api-key=4ba1d878-9a90-4e98-8554-de2a8a5300e7 -->

			</div>

		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>