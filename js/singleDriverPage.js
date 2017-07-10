$(document).ready( function() {

	// Setup
	var resultsTable = document.getElementById("resultsTable")
	var graph = document.getElementById("graph")
	var tableLoadingBar = document.getElementById("tableLoadingBar")
	var legend = document.getElementById("legend")
	var races = []

	// Open HTTP connection
	var xmlhttp = new XMLHttpRequest()
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        // Parse JSON to Object
	        racesObj = JSON.parse(this.responseText)

			// Hide loading bars
			tableLoadingBar.style.display = "none"
			graph.innerHTML = ""

	        // Process data
			var barLength = getLowestPos(racesObj)
			for (i = 0; i < racesObj.length; i++) {
				printTableRow(racesObj[i])
				printGraphRow(racesObj[i], i, barLength)
			}
	    }
	}

	// Get JSON file
	filePath = "js/temp/results-" + currentUser + ".json"
	xmlhttp.open("GET", filePath, true)
	xmlhttp.send()


	// FAB behavior
	if ($(window).width() < 600) {
		$("#legend").addClass("scale-out")
		$(window).scroll(function(){ $("#legend").addClass("scale-out") })
	} else {
		setTimeout(function(){
			$("#legend").addClass("scale-out")
			Materialize.toast("Click More Options to reopen legend", 3000)
		}, 5000)		
	}

	setTimeout(function(){
		$("a.btn-floating").removeClass("pulse")
	}, 20000)

	$("#backBtnFab").click( function() { scrollToTop() })
	$("#legendBtn").click( function() { toggleLegend() })	
	$("#legend").click( function() { toggleLegend() })	

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

	// Find min & max position values for the race
	var highPos = getLower(raceObj.grid, raceObj.posNum)
	var lowPos = getGreater(raceObj.grid, raceObj.posNum)
	// Accounting for DNFs
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
	// set length of bar based on delta of grid & finish
	rowBar.style.width = ((lowPos - highPos) / barLength * 100) + "%"
	// styling bars: win - blue
	if (raceObj.posNum == 1) { rowBar.style.backgroundColor = "#0d47a1" }
	// DNF - grey
	else if (raceObj.posNum == 0) { rowBar.style.backgroundColor = "#616161" }
	// worsened finish - red
	else if (raceObj. posNum > raceObj.grid) { rowBar.style.backgroundColor = "#d32f2f" }
	// improved finish vs qualifying - green
	else { rowBar.style.backgroundColor = "#388e3c" }
	
}

// Find lowest race position across career to set bar length
function getLowestPos(raceObj) {
	var max = 0;
	for (i = 0; i < Object.keys(raceObj).length; i++) {
		if (raceObj[i].posNum > max) { max = raceObj[i].posNum }
		if (raceObj[i].grid > max) { max = raceObj[i].grid }
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

// For driver FAB
function scrollToTop() {
	document.body.scrollTop = 0 // For Chrome, Safari and Opera 
    document.documentElement.scrollTop = 0 // For IE and Firefox
}

function toggleLegend() {
	if ($("#legend").hasClass("scale-out")) {
		$("#legend").addClass("scale-in")
		setTimeout(function(){
			$("#legend").removeClass("scale-out scale-in")
		}, 500)
	} else { $("#legend").addClass("scale-out") }
}