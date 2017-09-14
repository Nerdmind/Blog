//==============================================================================
// Elements which contains the location of the previous and next site
//==============================================================================
const prev = document.getElementById("prev-site");
const next = document.getElementById("next-site");

//==============================================================================
// Handle arrow keys and change the location to the desired direction
//==============================================================================
document.addEventListener("keyup", function(e) {
	if(!e.ctrlKey && !e.shiftKey) {
		(e.keyCode === 37 && prev) && (window.location.href = prev.getAttribute("href"));
		(e.keyCode === 39 && next) && (window.location.href = next.getAttribute("href"));
	}
}, false);

//==============================================================================
// Markdown tags to replace
//==============================================================================
const markdownTags = {
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
// Timeout function for delayed execution of code
//==============================================================================
function delayed(callback) {
	window.setTimeout(callback, 20);
}

//==============================================================================
// Set caret position in editor
//==============================================================================
function setCaretPosition(position) {
	document.getElementById("content-editor").setSelectionRange(position, position);
	document.getElementById("content-editor").focus();
}

//==============================================================================
// Insert emoticon after cursor in editor
//==============================================================================
function insertEmoticon(target, emoticon) {
	const selectionStart = target.selectionStart;
	const selectionEnd = target.selectionEnd;

	const content = target.value;
	target.value = content.slice(0, selectionStart) + emoticon + content.slice(selectionEnd);

	delayed(function() {
		setCaretPosition(selectionStart + emoticon.length);
	});
}

//==============================================================================
// Insert markdown around text in editor
//==============================================================================
function insertMarkdown(target, markdown) {
	const selectionStart = target.selectionStart;
	const selectionEnd = target.selectionEnd;

	const selectedText = target.value.substring(selectionStart, selectionEnd);

	const content = target.value;
	target.value = content.slice(0, selectionStart) + markdownTags[markdown][0] + selectedText + markdownTags[markdown][1] + content.slice(selectionEnd);

	delayed(function() {
		setCaretPosition(selectionStart + markdownTags[markdown][0].length + selectedText.length + markdownTags[markdown][1].length);
	});
}

//==============================================================================
// Keep server-side session active if the user is writing a long text
//==============================================================================
setInterval(function() {
	const Request = new XMLHttpRequest();
	Request.open("HEAD", "", true);
	Request.send();
}, 300000);

//==============================================================================
// Confirmation message for delete button
//==============================================================================
if(document.getElementById("delete-button")) {
	document.getElementById("delete-button").onclick = function(e) {
		return confirm(e.target.getAttribute("data-text"));
	};
}

//==============================================================================
// Insert or remove tab indent in editor if [<shift>+]<tab> is pressed
//==============================================================================
(function() {
	if(document.getElementById("content-editor")) {
		const element = document.getElementById("content-editor");
		element.addEventListener("keydown", function(e) {
			if(e.keyCode === 9 && !e.ctrlKey) {
				const selectionStart = element.selectionStart;
				const selectionEnd = element.selectionEnd;

				const content = element.value;

				if(e.shiftKey) {
					if(content.substring(selectionStart, selectionStart -1) === "\t") {
						element.value = content.substring(0, selectionStart - 1) + content.substring(selectionEnd);
						setCaretPosition(selectionStart - 1);
					}
				}

				else {
					element.value = content.substring(0, selectionStart) + "\t" + content.substring(selectionEnd);
					setCaretPosition(selectionStart + 1);
				}

				e.preventDefault();
			}
		}, false);
	}
})();

//==============================================================================
// Emoticon button list
//==============================================================================
(function() {
	if(document.getElementById("emoticon-list")) {
		const list = document.getElementById("emoticon-list");
		const node = document.getElementById("content-editor");
		const items = list.getElementsByTagName("li");

		for(let i = 0; i < items.length; ++i) {
			items[i].onmousedown = function(e) {
				insertEmoticon(node, e.target.getAttribute("data-emoticon"));
			};
		}
	}
})();

//==============================================================================
// Markdown button list
//==============================================================================
(function() {
	if(document.getElementById("markdown-list")) {
		const list = document.getElementById("markdown-list");
		const node = document.getElementById("content-editor");
		const items = list.getElementsByTagName("li");

		for(let i = 0; i < items.length; ++i) {
			items[i].onmousedown = function(e) {
				insertMarkdown(node, e.target.getAttribute("data-markdown"));
			};
		}
	}
})();