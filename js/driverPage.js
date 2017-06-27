$(document).ready( function() {
	// Setup
	var resultsTable = document.getElementById("resultsTable")
	var graph = document.getElementById("graph")
	var races = []

	// Open HTTP connection
	var xmlhttp = new XMLHttpRequest()
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        // Parse JSON to Object
	        racesObj = JSON.parse(this.responseText)

	        // Process data
			var count = Object.keys(racesObj).length
			for (i = 0; i < count; i++) {
				var race = racesObj[i].race
				var grid = racesObj[i].grid
				var posText = racesObj[i].posText
				var team = racesObj[i].team
				var posNum = racesObj[i].posNum


				printTableRow(racesObj[i])
				printGraphRow(racesObj[i], i)
			}
	    }
	}

	// Get JSON file
	xmlhttp.open("GET", "js/results.json", true)
	xmlhttp.send()

	
})

function printTableRow(raceObj) {
	resultsTable.innerHTML += "<tr><td>" + raceObj.race + 
			"</td><td>" + raceObj.grid + 
			"</td><td>" + raceObj.posText + 
			"</td><td>" + raceObj.team + 
			"</td></tr>"	
}

function printGraphRow(raceObj, i) {
	graph.innerHTML += "<div class='data-row left' id='row-" + i + "'></div>"

	var row = document.getElementById("row-" + i)
	row.innerHTML = raceObj.race
	row.innerHTML += "<div class='data-bar' id='rowBar-" + i + "'></div>"

	var rowBar = document.getElementById("rowBar-" + i)
	rowBar.style.backgroundColor = "green"
	// set left & right position of rowBar based on getMaxPos
}

function getMaxPos(raceArr) {
	var max = 0;
	for (i = 0; i < raceArr.length; i++) {
		if (raceArr[i].posNum > max) {
			max = raceArr[i].posNum
		}
	}
	return max
}
