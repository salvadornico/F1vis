$(document).ready( function() {

	var contentBox = document.getElementById("contentbox")
	
	if (isUserLoggedIn) {

		// ------------------ Setup standings section -------------------------
		var standingsMessage = document.getElementById("standingsMessage")
		var standingsTable = document.getElementById("standingsTable")
		var roundLabel = document.getElementById("roundLabel")
		var seasonLabel = document.getElementById("seasonLabel")
		var loadingBar = document.getElementById("standingsLoadingBar")

		var standingsXmlhttp = new XMLHttpRequest()
		standingsXmlhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		        standingsObj = JSON.parse(this.responseText)

		        // get season & latest round
		        var season = standingsObj.MRData.StandingsTable.season
		        var round = standingsObj.MRData.StandingsTable.StandingsLists[0].round

		        var driverCount = standingsObj.MRData.StandingsTable.StandingsLists[0].DriverStandings.length

	    		// clear failure message
	    		standingsMessage.innerHTML = ""

	    		seasonLabel.innerHTML = season
	    		roundLabel.innerHTML = round
	    		loadingBar.style.display = "none"

	    		for (i = 0; i < driverCount; i++) {
	    			var position = standingsObj.MRData.StandingsTable.StandingsLists[0].DriverStandings[i].positionText
	    			var driverName = standingsObj.MRData.StandingsTable.StandingsLists[0].DriverStandings[i].Driver.givenName + " " + standingsObj.MRData.StandingsTable.StandingsLists[0].DriverStandings[i].Driver.familyName
	    			var points = standingsObj.MRData.StandingsTable.StandingsLists[0].DriverStandings[i].points
	    			var constructor = standingsObj.MRData.StandingsTable.StandingsLists[0].DriverStandings[i].Constructors[0].name
	    			var driverRef = standingsObj.MRData.StandingsTable.StandingsLists[0].DriverStandings[i].Driver.driverId

	    			standingsTable.innerHTML += "<tr><td>" + position + "</td><td><a href='single-driver.php?id=" + driverRef + "&name=" + driverName + "'>" + driverName + "</a></td><td>" + points + "</td><td>" + constructor + "</td></tr>"
	    		}
		    } else if (this.readyState == 4 && this.status != 200) {
		    	standingsMessage.innerHTML = "Problem loading standings. Please reload to try again."
		    }
		}

		var standingsApiUrl = "http://ergast.com/api/f1/current/driverStandings.json"
		standingsXmlhttp.open("GET", standingsApiUrl, true)
		standingsXmlhttp.send()

	} else {
		contentBox.innerHTML = "Please <a href='login.php'>log in</a> to use the dashboard."
	}

})