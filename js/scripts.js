driverFab = document.getElementById("driver-fab")
driverFab.addEventListener("click", scrollToTop)

var options = [
	{selector: '#driver-title', offset: 1000, callback: function(el) {
        driverFab.style.display = "block";
	} }
];

$(document).ready( function() {
	$(".button-collapse").sideNav({ draggable: true });
	$('.parallax').parallax();
	$('.modal').modal();
    Materialize.scrollFire(options);
})

function scrollToTop() {
	document.body.scrollTop = 0 // For Chrome, Safari and Opera 
    document.documentElement.scrollTop = 0 // For IE and Firefox
}