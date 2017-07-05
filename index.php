<?php

	$active_page = "Home";

	require_once 'partials/header.php';

	// CHECK FOR UPDATED RACES
	// Get latest year & round of results
	$string = file_get_contents("http://ergast.com/api/f1/current/last/results.json");
	$latest_result = json_decode($string, true);
	$year = $latest_result['MRData']['RaceTable']['Races'][0]['season'];
	$round = $latest_result['MRData']['RaceTable']['Races'][0]['round'];

	// Retrieves latest year & round from database
	$rounds_result = querySQL("SELECT DISTINCT round AS 'last_round', year AS 'last_year' FROM races WHERE raceId = (SELECT MAX(raceId) FROM results)");
	while ($row = mysqli_fetch_assoc($rounds_result)) {
		extract($row);
	}

	if ($year > $last_year) {
		// TODO:
		// Get elapsed rounds of $year season
		// Get remaining rounds of $last_year season
		// Get all rounds of anythin in between
		// compile to $rounds_to_retrieve
	} else if ($year == $last_year && $round > $last_round) {
		$missing_rounds = $round - $last_round;

		$rounds_to_retrieve = [];
		for ($i = 1; $i <= $missing_rounds; $i++) { 
			$rounds_to_retrieve[] = [$year, $last_round + $i];
		}

		echo "<script> Materialize.toast('Database updated by $missing_rounds rounds', 4000) </script>";

		updateResults($rounds_to_retrieve);

	} else {
		echo "<script> Materialize.toast('Database up to date', 4000) </script>";
	}

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

						$greetings = ['Welcome', 'Benvenuto', 'ようこそ', 'Willkommen', 'Bienvenue', 'स्वागत हे', 'Velkommen', 'Bienvenidos', 'أهلا بك', 'Välkommen', 'добро пожаловать', 'Tervetuloa', 'Welkom', 'Bem-vindo', '欢迎'];

						foreach ($greetings as $greeting) {
							echo "<li>".htmlentities($greeting, ENT_QUOTES, 'UTF-8')."</li>";
						}

					?>
				</ul>


				<p class="flow-text">
					An exercise in visualisation of historical driver finish data from every season of the FIA Formula 1 World Championship, spanning from 1950 to the present day.
					<br><br>
					Data is courtesy of the <a href="http://ergast.com/mrd/" target="_blank">Ergast Developer API</a> and <a href="http://open-platform.theguardian.com/" target="_blank">The Guardian Open Platform</a>.
					<br>
					Built with <a href="http://materializecss.com" target="_blank">Materialize</a>.
				</p>

			</div>

		</div> <!-- /container -->

		<div class="parallax-container">
      		<div class="parallax"><img src="images/home-header-2.jpg" alt="Jarno Trulli, 2011 Lotus Formula 1 car"></div>
    	</div>

		<div class="section container">

			<div class="row">
				
				<h3>Next Race</h3>

				<blockquote id="next-race">

					<?php

						$next_round = $round + 1;

						$string = file_get_contents("http://ergast.com/api/f1/$year/$next_round.json");
						$next_result = json_decode($string, true);

						$gp = $next_result['MRData']['RaceTable']['Races'][0]['season']." ".$next_result['MRData']['RaceTable']['Races'][0]['raceName'];
						$date = convertDate($next_result['MRData']['RaceTable']['Races'][0]['date']);
						$location = $next_result['MRData']['RaceTable']['Races'][0]['Circuit']['circuitName'];

						echo "<h5>$gp</h5>";
						echo "<span>$date - $location</span>";

					?>
					
				</blockquote>

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

		<div class="parallax-container">
      		<div class="parallax"><img src="images/home-header-3.jpg" alt="Nigel Mansell, 1982 Lotus Formula 1 car"></div>
    	</div>
		
	</main>

<?php

	require_once 'partials/footer.php';

?>