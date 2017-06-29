$(document).ready( function() {
	// Setup
	var newsbox = document.getElementById("newsbox")

	// Open HTTP connection
	var xmlhttp = new XMLHttpRequest()
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        // Parse JSON to Object
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
	        		var timestamp = convertTimestamp(newsObj.response.results[i].webPublicationDate)
	        		var link = newsObj.response.results[i].webUrl

	        		newsbox.innerHTML += "<div class='col s12 m6 l4'><div class='card small yellow lighten-5'><div class='card-content'><span class='card-title'>" + title + "</span><span class='grey-text'>" + timestamp + "</span></div><div class='card-action'><a href='" + link + "' class='green-text text-darken-4' target='_blank'>Read More...</a></div></div></div>"
	        		articleCount++       
	        	}
	        }
	    } else {
	    	newsbox.innerHTML = "Problem loading news. Please reload to try again."
	    }
	}

	// Get JSON file
	var newsApiUrl = "http://content.guardianapis.com/search?order-by=newest&q=f1&api-key=4ba1d878-9a90-4e98-8554-de2a8a5300e7"
	xmlhttp.open("GET", newsApiUrl, true)
	xmlhttp.send()

	function convertTimestamp(rawString) {
		// run regex search
		var timestampRegex = /(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z/
		var result = timestampRegex.exec(rawString)

		var year = result[1]
		var month = parseInt(result[2])
		var day = parseInt(result[3])
		var hour = parseInt(result[4])
		var minute = result[5]
		var period = "AM"

		// convert numerical month to word
		monthsList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
		month = monthsList[month - 1]

		// convert 24-hour to 12-hour
		if (hour == 0) {
			hour = 12
		} else if (hour > 12) {
			hour -= 12
			period = "PM"
		}

		return day + " " + month + " " + year + ", " + hour + ":" + minute + period + " (BST)"
	}
})