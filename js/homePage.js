$(document).ready( function() {

	// set intro box to fill remaining window space
	var intro = document.getElementById("intro")
	var introHeight = ($(window).height() - ($("#home-parallax").height() + $("nav").height()))
	intro.style.height = introHeight + "px"

	// cycle through welcome greetings
	var j = 0
	var delay = 2000 //millisecond delay between cycles
	function cycleThru(){
	    var jmax = $("ul#cyclelist li").length -1
	    $("ul#cyclelist li:eq(" + j + ")")
            .animate({"opacity" : "1"} ,400)
            .animate({"opacity" : "1"}, delay)
            .animate({"opacity" : "0"}, 400, function(){
                (j == jmax) ? j=0 : j++
                cycleThru()
	        })
    }
	cycleThru()

	// Setup Next Race section
	var nextRace = document.getElementById("next-race")

	// Open HTTP connection
	var raceXmlhttp = new XMLHttpRequest()
	raceXmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        // Parse JSON to Object
	        raceObj = JSON.parse(this.responseText)
	        
	        if (raceObj.MRData.RaceTable.Races.length > 0) {
				var gp = raceObj.MRData.RaceTable.Races[0].season + " " + raceObj.MRData.RaceTable.Races[0].raceName
				var date = convertTimestamp(raceObj.MRData.RaceTable.Races[0].date)
				var location = raceObj.MRData.RaceTable.Races[0].Circuit.circuitName

				nextRace.innerHTML = "<h5>" + gp + "</h5>"
				nextRace.innerHTML += "<span>" + date + " - " + location + "</span>"
			} else {
				nextRace.innerHTML = "No next race found."
			}
	    } else if (this.readyState == 4 && this.status != 200) {
	    	nextRace.innerHTML = "Problem loading next race. Please reload to try again."
	    }
	}

	// Get JSON file
	raceXmlhttp.open("GET", "http://ergast.com/api/f1/current/next.json", true)
	raceXmlhttp.send()


	// Setup News section
	var newsbox = document.getElementById("newsbox")

	var newsXmlhttp = new XMLHttpRequest()
	newsXmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        newsObj = JSON.parse(this.responseText)

    		// clear failure message
    		newsbox.innerHTML = ""
    		// start counter for sport articles
	        var articleCount = 0
	        
	        for (i = 0; i < newsObj.response.results.length; i++) {
	        	// filter out unrelated news
	        	if (newsObj.response.results[i].sectionId == "sport") {
	        		// exit after 6 articles
	        		if (articleCount == 6) { return }

	        		var title = newsObj.response.results[i].webTitle
	        		var timestamp = convertTimestamp(newsObj.response.results[i].webPublicationDate, "timestamp")
	        		var link = newsObj.response.results[i].webUrl

	        		newsbox.innerHTML += "<div class='col s12 m6 l4'><div class='card small yellow lighten-5'><div class='card-content'><span class='card-title'>" + title + "</span><span class='grey-text'>" + timestamp + "</span></div><div class='card-action'><a href='" + link + "' class='green-text text-darken-4' target='_blank'>Read More...</a></div></div></div>"
	        		articleCount++
	        	}
	        }
	    } else if (this.readyState == 4 && this.status != 200) {
	    	newsbox.innerHTML = "Problem loading news. Please reload to try again."
	    }
	}

	var newsApiUrl = "http://content.guardianapis.com/search?order-by=newest&q=f1&api-key=4ba1d878-9a90-4e98-8554-de2a8a5300e7"
	newsXmlhttp.open("GET", newsApiUrl, true)
	newsXmlhttp.send()
})

monthsList = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]

function convertTimestamp(rawString, mode = "date") {
	// run regex search
	var timestampRegex = /(\d{4})-(\d{2})-(\d{2})(T(\d{2}):(\d{2}):(\d{2})Z)*/
	var result = timestampRegex.exec(rawString)

	var year = result[1]
	var month = parseInt(result[2])
	var day = parseInt(result[3])

	// convert numerical month to word
	month = monthsList[month - 1]

	if (mode == "timestamp") {
		var hour = parseInt(result[5])
		var minute = result[6]
		var period = "AM"
		// convert 24-hour to 12-hour
		if (hour == 0) {
			hour = 12
		} else if (hour > 12) {
			hour -= 12
			period = "PM"
		}

		return day + " " + month + " " + year + ", " + hour + ":" + minute + period
	} else {
		return day + " " + month + " " + year
	}
}