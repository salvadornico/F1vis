<?php

	$active_page = "Home";

	require_once 'partials/header.php';

?>

	<main>
		
		<div class="parallax-container">
      		<div class="parallax"><img src="images/main-header.jpg" alt="60's Lotus Formula 1 car"></div>
    	</div>

		<div class="section container">
			
			<h1>Home :)</h1>

			<!-- Guardian API query: http://content.guardianapis.com/search?order-by=newest&q=f1&api-key=4ba1d878-9a90-4e98-8554-de2a8a5300e7 -->
			<!-- TODO: Filter out when sectionId != sport -->


		</div> <!-- /container -->
		
	</main>

<?php

	require_once 'partials/footer.php';

?>