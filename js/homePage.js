$(document).ready( function() {
	// Setup
	var newsbox = document.getElementById("newsbox")


	// Open HTTP connection
	var xmlhttp = new XMLHttpRequest()
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        // Parse JSON to Object
	        newsObj = JSON.parse(this.responseText)

	        var articleCount = newsObj.response.results.length
	        
	        for (i = 0; i < 6; i++) {
	        	// filter out unrelated news
	        	if (newsObj.response.results[i].sectionId == "sport") {
	        		var title = newsObj.response.results[i].webTitle
	        		var timestamp = newsObj.response.results[i].webPublicationDate
	        		var link = newsObj.response.results[i].webUrl

	        		//TODO: organize into cards
	        		newsbox.innerHTML += timestamp + "<br>" + title + "<br>" + link + "<br><br>"
	        	}
	        }

	    }
	}

	// Get JSON file
	var apiUrl = "http://content.guardianapis.com/search?order-by=newest&q=f1&api-key=4ba1d878-9a90-4e98-8554-de2a8a5300e7"
	xmlhttp.open("GET", apiUrl, true)
	xmlhttp.send()
    
    
})