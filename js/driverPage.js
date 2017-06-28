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
			var barLength = getLowestPos(racesObj)

			for (i = 0; i < Object.keys(racesObj).length; i++) {

				printTableRow(racesObj[i])
				printGraphRow(racesObj[i], i, barLength)
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

function printGraphRow(raceObj, index, barLength) {
	graph.innerHTML += "<div class='data-row left' id='row-" + index + "'></div>"

	var row = document.getElementById("row-" + index)
	row.innerHTML = "<span id='rowLabel-" + index + "'>" + raceObj.race + " (" + raceObj.team + ")</span>"
	row.innerHTML += "<div class='data-star' id='rowStar-" + index + "'></div>"
	row.innerHTML += "<div class='data-bar' id='rowBar-" + index + "'></div>"

	var rowLabel = document.getElementById("rowLabel-" + index)
	var rowStar = document.getElementById("rowStar-" + index)
	var rowBar = document.getElementById("rowBar-" + index)

	// If pole win, display star & exit
	if (raceObj.grid == 1 && raceObj.posNum == 1) {
		rowStar.style.display = "block"
		return
	}

	// Find min & max position values for the race, accounting for DNFs
	var highPos = getLower(raceObj.grid, raceObj.posNum)
	var lowPos = getGreater(raceObj.grid, raceObj.posNum)
	if (lowPos == highPos && raceObj.grid != raceObj.posNum || lowPos == 0) { lowPos = barLength }
	if (highPos == 0 ) { highPos = barLength }
	// Accounting for races where grid = final position
	if (raceObj.grid == raceObj.posNum) {
		lowPos += 0.5
		highPos -= 0.5
		// For non-qualifying results
		if (raceObj.grid == 0) {
			rowLabel.innerHTML += " - Failed to qualify"
			lowPos = barLength
			highPos = 0
		}
	}
	// Accounting for DNFs from lowest position
	if (raceObj.posNum == 0 && raceObj.grid == barLength) {
		highPos -= 0.5
	}

	// PRINTING
	// set left position of rowBar based on lower of grid & finish, as percentage of barLength
	rowBar.style.left = ((barLength - lowPos) / barLength * 100) + "%"
	// set length of bar based on delta of grid & pos
	rowBar.style.width = ((lowPos - highPos) / barLength * 100) + "%"
	// if finish < grid, set to green. Else, set to red
	if (raceObj.posNum == 0) { rowBar.style.backgroundColor = "#616161" }
	else if (raceObj. posNum > raceObj.grid) { rowBar.style.backgroundColor = "#d32f2f" }
	else { rowBar.style.backgroundColor = "#388e3c" }
	
}

// Find lowest race position across career to set bar length
function getLowestPos(raceObj) {
	var max = 0;
	for (i = 0; i < Object.keys(raceObj).length; i++) {
		if (raceObj[i].posNum > max) {
			max = raceObj[i].posNum
		}
		if (raceObj[i].grid > max) {
			max = raceObj[i].grid
		}
	}
	return max
}

function getGreater(num1, num2) {
	if (num1 > num2) { return num1 }
	else { return num2 }
}

function getLower(num1, num2) {
	if (num1 == 0) { return num2 }
	else if (num2 == 0) { return num1 }
	else if (num1 < num2) { return num1 }
	else { return num2 }
}