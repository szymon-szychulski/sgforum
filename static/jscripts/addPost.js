window.addEventListener("load", function() {
	/* INITAL */

	var editor = document.getElementById("addPostTxt");

	var observe;

	if (window.attachEvent) {
		observe = function (element, event, handler) {
			element.attachEvent("on" + event, handler);
		};
	}
	else {
		observe = function (element, event, handler) {
			element.addEventListener(event, handler, false);
		};
	}

	function resize() {
		var clone = editor.cloneNode();
		clone.className = "clone";

		editor.parentNode.insertBefore(clone, editor);

		clone.style.height = "auto";
		clone.value = editor.value;

		editor.style.height = (clone.scrollTop + clone.scrollHeight + 20) + "px";

		editor.parentNode.removeChild(clone);
	}

	function delayedResize() {
		window.setTimeout(resize, 0);
	}

	observe(editor, "change",  resize);
	observe(editor, "cut",     delayedResize);
	observe(editor, "paste",   delayedResize);
	observe(editor, "drop",    delayedResize);
	observe(editor, "keydown", delayedResize);

	// editor.focus();
	// editor.select();

	resize();

	/* BUTTONS STAY WHILE SCROLLING */

	var a = document.getElementById("addPostEditor"),
		b = document.getElementById("addPostFormat"),
		c = document.getElementById("chooseColor"),
		d = document.getElementById("chooseSize"),
		e = document.getElementById("addPostAttach");

	window.addEventListener("scroll", function() {
		var stayhere = document.getElementById("stayHere").getBoundingClientRect().top - document.body.getBoundingClientRect().top;
		var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

		if (scrollTop > stayhere && editor.getBoundingClientRect().bottom > 125) {
			if (!a.classList.contains("scroll")) {
				a.classList.add("scroll");
				b.classList.add("scroll");
				c.classList.add("scroll");
				d.classList.add("scroll");
				e.classList.add("scroll");
			}
		}
		else {
			a.classList.remove("scroll");
			b.classList.remove("scroll");
			c.classList.remove("scroll");
			d.classList.remove("scroll");
			e.classList.remove("scroll");
		}
	}, false);

	/* BUTTONS PREVENT */

	var buttons = document.getElementsByTagName("button"),
		buttons_len = buttons.length;

	for (var i = 0; i < buttons_len; i++) {
		buttons[i].addEventListener("click", function(e) {
			e.preventDefault();
			editor.focus();
		});
	}

	/* RETURN SELECTION TEXT */

	function getSelectionText()	{
		if (window.getSelection) {
			return editor.value.substring(editor.selectionStart, editor.selectionEnd);
		}

		// for IE
		if (document.selection && document.selection.type != "Control") {
			return document.selection.createRange().text;
		}
	}

	/* SET CURSOR POSITION IN TEXT FIELD */

	function setCaretPosition(caretPos) {
		editor.value = editor.value;

		if (editor.createTextRange) {
			var range = editor.createTextRange();
			range.move("character", caretPos);
			range.select();
			return true;
		}
		else {
			if (editor.selectionStart || editor.selectionStart === 0) {
				editor.focus();
				editor.setSelectionRange(caretPos, caretPos);
				return true;
			}
			else {
				editor.focus();
				return false;
			}
		}
	}

	/* INSERT TAG */

	function insertTag(name, param, text) {
		if (typeof param !== "undefined") {
			param = "=\"" + param + "\"";
		}
		else {
			param = "";
		}

		if (typeof text === "undefined") {
			sText = getSelectionText();

			if (sText) {
				text = sText;
			}
			else {
				text = "";
			}
		}

		tag = "[" + name + param+"]" + text + "[/" + name + "]";

		if (document.selection) {
			// for IE
			editor.focus();
			sel = document.selection.createRange();
			sel.text = tag;
		}
		else if (editor.selectionStart || editor.selectionStart == '0') {
			var startPos = editor.selectionStart,
				endPos   = editor.selectionEnd;

			editor.value = editor.value.substring(0, startPos) + tag + editor.value.substring(endPos, editor.value.length);

			if (startPos == endPos) {
				setCaretPosition((editor.value.length - (name.length + 3)));
			}
		}
		else {
			editor.value += tag;
			setCaretPosition((editor.value.length - (name.length + 3)));
		}
	}

	/* STRONG */

	document.getElementById("formatStrong").addEventListener("click", function() {
		insertTag("b");
	});

	/* ITALIC */

	document.getElementById("formatItalic").addEventListener("click", function() {
		insertTag("i");
	});

	/* CHOOSE COLOR */

	document.getElementById("formatColor").addEventListener("click", function() {
		if (this.classList.contains("active")) {
			this.classList.remove("active");
			document.getElementById("chooseColor").style.display = "none";
		}
		else {
			if (document.getElementById("formatSize").classList.contains("active")) {
				document.getElementById("formatSize").classList.remove("active");
				document.getElementById("chooseSize").style.display = "none";
			}

			this.classList.add("active");
			document.getElementById("chooseColor").style.display = "block";
		}
	});

	var colors = document.getElementsByClassName("color"),
		colors_len = colors.length;

	for (var i = 0; i < colors_len; i++) {
		colors[i].addEventListener("click", function() {
			document.getElementById("formatColor").classList.remove("active");
			document.getElementById("chooseColor").style.display = "none";

			color = this.getAttribute("data-textcolor");

			/*if (color == "white") {
				return false;
			}*/

			insertTag("c", color);
		});
	}

	/* CHOOSE SIZE */

	document.getElementById("formatSize").addEventListener("click", function() {
		if (this.classList.contains("active")) {
			this.classList.remove("active");
			document.getElementById("chooseSize").style.display = "none";
		} 
		else {
			if (document.getElementById("formatColor").classList.contains("active")) {
				document.getElementById("formatColor").classList.remove("active");
				document.getElementById("chooseColor").style.display = "none";
			}

			this.classList.add("active");
			document.getElementById("chooseSize").style.display = "block";
		}
	});

	var sizes = document.getElementsByClassName("size"),
		sizes_len = sizes.length;

	for (var i = 0; i < sizes_len; i++) {
		sizes[i].addEventListener("click", function() {
			document.getElementById("formatSize").classList.remove("active");
			document.getElementById("chooseSize").style.display = "none";

			size = this.getAttribute("data-fontsize");

			if (size == 14) {
				return false;
			}

			insertTag("s", size);
		});
	}

	/* QUOTE */

	document.getElementById("formatQuote").addEventListener("click", function() {
		insertTag("quote");
	});

	/* IMAGE */

	document.getElementById("pickPicture").addEventListener("click", function() {
		var url = prompt("Enter a image URL:", "http://");

		if (url != "" && url != null) {
			insertTag("img", undefined, url);
		}
	});

	/* SCRIPT */

	document.getElementById("pickScript").addEventListener("click", function() {
		insertTag("code");
	});

	/* LINK */

	document.getElementById("pickLink").addEventListener("click", function() {
		var url = prompt("Enter a URL:", "http://");

		if (url != "" && url != null) {
			var sText = getSelectionText();

			if (sText == "") {
				var txt = prompt("Enter a text for link:", "click here");

				if (txt == "" || txt == null) {
					sText = "click here";
				}
				else {
					sText = txt;
				}
			}

			insertTag("a", url, txt);
		}
	});

	editor.addEventListener("keydown", function(e) {
		/* SHORTCUTS */

		if (e.ctrlKey) {
			if (e.keyCode == 66 || e.which == 66) {
				insertTag("b");
			}
			else if (e.keyCode == 73 || e.which == 73) {
				insertTag("i");
			}
		}

		if (e.keyCode == 27 || e.which == 27) {
			if (document.getElementById("formatColor").classList.contains("active")) {
				document.getElementById("formatColor").classList.remove("active");
				document.getElementById("chooseColor").style.display = "none";
			} 
			else if (document.getElementById("formatSize").classList.contains("active")) {
				document.getElementById("formatSize").classList.remove("active");
				document.getElementById("chooseSize").style.display = "none";
			}
		}
	}, false);

	editor.addEventListener("input", function(e) {
		/* COUNT LENGTH */

		document.getElementById("postTxtCharsNum").innerHTML = editor.value.length;

		if (editor.value.length > 5000) { 
			document.getElementById("postTxtChars").style.color = "rgb(204, 80, 80)";
		}
		else if (document.getElementById("postTxtChars").style.color == "rgb(204, 80, 80)") {
			document.getElementById("postTxtChars").style.color = "#fff";
		}
	}, false);

	editor.addEventListener("paste", function(e) {
		/* GET IMAGE FROM CLIPBOARD */

		/*var items = (e.clipboardData || e.originalEvent.clipboardData).items;

		console.log(JSON.stringify(items)); // will give you the mime types

		for (index in items) {
			var item = items[index];

			if (item.kind === 'file') {
				var blob = item.getAsFile();
				var reader = new FileReader();

				reader.onload = function(e) {
					console.log(e.target.result)
				}; // data url!
			
				reader.readAsDataURL(blob);
			}
		}*/
	});

	/* UPPERCASE FIRST LETTER OF TITLE */

	if (document.getElementById("sendPost").getAttribute("data-action") == "thread") {
		document.getElementById("title").addEventListener("input", function() {
			this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
		});
	}

	/* PREPARE DATA AND SEND */

	document.getElementById("sendPost").addEventListener("click", function() {
		if (this.getAttribute("data-action") == "thread") {
			var params = {title: document.getElementById("title").value, content: editor.value};
		}
		else if (this.getAttribute("data-action") == "post") {
			var params = {content: editor.value};
		}

		var form = document.createElement("form");

		form.setAttribute("method", "post");
		form.setAttribute("action", LINK);

		for (var key in params) {
			if (params.hasOwnProperty(key)) {
				var field = document.createElement("input");

				field.setAttribute("type", "hidden");
				field.setAttribute("name", key);
				field.setAttribute("value", params[key]);

				form.appendChild(field);
			}
		}

		document.body.appendChild(form);

		editor.disabled = true;

		this.innerHTML = "loading";
		this.style.width = "100px";
		this.style.padding = "6px 20px";
		this.style.backgroundColor = "#444";
		this.style.textAlign = "left";
		this.disabled = true;

		var button = this;

		setInterval(function() {
			if (button.innerHTML == "loading...") {
				button.innerHTML = "loading";
			}
			else {
				button.innerHTML += ".";
			}
		}, 250);

		form.submit();
	});
}, false);