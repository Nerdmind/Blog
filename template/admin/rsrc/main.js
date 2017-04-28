//==============================================================================
// Elements which contains the location of the previous and next site
//==============================================================================
var prev = document.getElementById("prev-site");
var next = document.getElementById("next-site");

//==============================================================================
// Handle arrow keys and change the location to the desired direction
//==============================================================================
document.addEventListener("keyup", function(event) {
	if(!event.ctrlKey && !event.shiftKey) {
		(event.keyCode === 37 && prev) && (window.location.href = prev.getAttribute("href"));
		(event.keyCode === 39 && next) && (window.location.href = next.getAttribute("href"));
	}
}, false);

//==============================================================================
// Markdown tags to replace
//==============================================================================
var markdownTags = {
	"bold":    ["**", "**"],
	"italic":  ["*", "*"],
	"heading": ["## ", "\n"],
	"link":    ["[", "](href)"],
	"image":   ["![", "](href)"],
	"code":    ["\n~~~\n", "\n~~~\n"],
	"quote":   ["\n> ", ""],
	"list_ul": ["* ", ""],
	"list_ol": ["1. ", ""]
};

//==============================================================================
// Set caret position in editor
//==============================================================================
function setCaretPosition(position) {
	window.setTimeout(function() {
		document.getElementById("content-editor").focus();
		document.getElementById("content-editor").setSelectionRange(position, position);
	}, 50);

}

//==============================================================================
// Insert markdown around text in editor
//==============================================================================
function markdownReplace(tagname) {
	var element = document.activeElement;

	if(element.nodeName === 'TEXTAREA') {
		var selectionStart = element.selectionStart;
		var selectionEnd = element.selectionEnd;

		var selectedText = element.value.substring(selectionStart, selectionEnd);

		var content = element.value;
		element.value = content.slice(0, selectionStart) + markdownTags[tagname][0] + selectedText + markdownTags[tagname][1] + content.slice(selectionEnd);

		setCaretPosition(selectionStart + markdownTags[tagname][0].length + selectedText.length + markdownTags[tagname][1].length);
	}
}

//==============================================================================
// Insert emoticon after cursor in editor
//==============================================================================
function emoticonReplace(emoticon) {
	var element = document.activeElement;

	if(element.nodeName === 'TEXTAREA') {
		var selectionStart = element.selectionStart;
		var selectionEnd = element.selectionEnd;

		var content = element.value;
		element.value = content.slice(0, selectionStart) + emoticon + content.slice(selectionEnd);

		setCaretPosition(selectionStart + emoticon.length);
	}
}

//==============================================================================
// Keep server-side session active if the user is writing a long text
//==============================================================================
addEventListener("DOMContentLoaded", function() {
	setInterval(function() {
		var Request = new XMLHttpRequest();
		Request.open("HEAD", "", true);
		Request.send();
	}, 300000);
}, false);

//==============================================================================
// Insert tab indent into editor if <tab> is pressed
//==============================================================================
addEventListener("DOMContentLoaded", function() {
	if(document.getElementById("content-editor")) {
		var element = document.getElementById("content-editor");
		element.addEventListener('keydown', function(e) {
			if(e.keyCode === 9 && !e.ctrlKey && !e.shiftKey) {
				var selectionStart = element.selectionStart;
				var selectionEnd = element.selectionEnd;

				var content = element.value;

				element.value = content.substring(0, selectionStart) + "\t" + content.substring(selectionEnd);

				setCaretPosition(selectionStart + 1);
				e.preventDefault();
			}
		}, false);
	}
}, false);

//==============================================================================
// Confirmation message for delete buttons
//==============================================================================
addEventListener("DOMContentLoaded", function() {
	if(document.getElementById("delete-button")) {
		document.getElementById("delete-button").onclick = function(e) {
			return confirm(e.target.getAttribute('data-text'));
		};
	}
}, false);

//==============================================================================
// Emoticon listener
//==============================================================================
addEventListener("DOMContentLoaded", function() {
	if(document.getElementById("emoticon-list")) {
		var list = document.getElementById("emoticon-list");
		var items = list.getElementsByTagName("li");

		for(var i = 0; i < items.length; ++i) {
			items[i].onmousedown = function(e) {
				emoticonReplace(e.target.getAttribute('data-emoticon'));
			};
		}
	}
}, false);

//==============================================================================
// Markdown listener
//==============================================================================
addEventListener("DOMContentLoaded", function() {
	if(document.getElementById("markdown-list")) {
		var list = document.getElementById("markdown-list");
		var items = list.getElementsByTagName("li");

		for(var i = 0; i < items.length; ++i) {
			items[i].onmousedown = function(e) {
				markdownReplace(e.target.getAttribute('data-markdown'));
			};
		}
	}
}, false);