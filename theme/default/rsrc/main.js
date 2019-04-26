//==============================================================================
// Elements which contains the location of the previous and next site
//==============================================================================
const prev = document.getElementById("prev-site");
const next = document.getElementById("next-site");

//==============================================================================
// Handle arrow keys and change the location to the desired direction
//==============================================================================
document.addEventListener("keyup", function(e) {
	if(!e.ctrlKey && !e.shiftKey && !e.altKey) {
		(e.keyCode === 37 && prev) && (window.location.href = prev.getAttribute("href"));
		(e.keyCode === 39 && next) && (window.location.href = next.getAttribute("href"));
	}
}, false);