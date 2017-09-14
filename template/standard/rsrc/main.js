//==============================================================================
// Elements which contains the location of the previous and next site
//==============================================================================
const prev = document.getElementById("prev-site");
const next = document.getElementById("next-site");

//==============================================================================
// Handle arrow keys and change the location to the desired direction
//==============================================================================
document.addEventListener("keyup", function(event) {
	if(!event.ctrlKey && !event.shiftKey) {
		(event.keyCode === 37 && prev) && (window.location.href = prev.getAttribute("href"));
		(event.keyCode === 39 && next) && (window.location.href = next.getAttribute("href"));
	}
}, false);