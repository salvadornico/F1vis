<?php

	$active_page = "Home";

	require_once 'partials/header.php';

?>

	<main>
		
		<div class="parallax-container" id="home-parallax">
      		<div class="parallax"><img src="images/main-header.jpg" alt="60's Lotus Formula 1 car"></div>
            <div>
                <h1 class="center-align white-text flow-text">monoposto</h1>
                <h5 class="center-align white-text">Formula 1 Data Visualisation</h5>
            </div>
    	</div>

		<div class="section container" id="intro">
			
			<div class="row">
				
				<ul id="cyclelist">
					<?php

						$greetings = ['Welcome', 'Benvenuto', 'ようこそ', 'Willkommen', 'Bienvenue', 'स्वागत हे', 'Velkommen', 'Bienvenidos', 'أهلا بك', 'Välkommen', 'добро пожаловать', 'Tervetuloa', 'Welkom', 'Bem vinda', '欢迎'];

						foreach ($greetings as $greeting) {
							echo "<li>".htmlentities($greeting)."</li>";
						}

					?>
				</ul>


				<p class="flow-text">
					An exercise in visualisation of historical driver finish data from every season of the FIA Formula 1 World Championship from 1950 to date.
					<br><br>
					Data is courtesy of the <a href="http://ergast.com/mrd/">Ergast Developer API</a> and <a href="http://open-platform.theguardian.com/">The Guardian Open Platform</a>.
					<br>
					Built with <a href="http://materializecss.com">Materialize</a>.
				</p>

			</div>

		</div> <!-- /container -->

		<div class="parallax-container">
      		<div class="parallax"><img src="images/home-header-2.jpg" alt="Jarno Trulli, 2011 Lotus Formula 1 car"></div>
    	</div>

		<div class="section container">

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

		<div class="parallax-container">
      		<div class="parallax"><img src="images/home-header-3.jpg" alt="Nigel Mansell, 1982 Lotus Formula 1 car"></div>
    	</div>
		
	</main>

<?php

	require_once 'partials/footer.php';

?>