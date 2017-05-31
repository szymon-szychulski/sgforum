window.addEventListener("load", function() {
	// double pagination
	document.getElementById("pagin2").innerHTML = document.getElementById("pagin1").innerHTML;

	// [code] settings
	var codeTags = document.getElementsByTagName("code");
	var codeTags_len = codeTags.length;

	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires=" + d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');

		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];

			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}

			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}

		return "";
	}

	function colorscheme(scheme) {
		document.getElementById("colorscheme").href = CSS_DIR + "/highlight/" + scheme + ".css";

		setCookie("colorscheme", scheme, 365);
	}

	function config() {
		var c = confirm("Click 'OK' to continue [code] configuration.");

		if (c) {
			var s = prompt("Choose syntax color scheme (or click 'Cancel'):\navailable: 1=ocean, 2=sublime, 3=tomorrow");

			if (s) {
				switch (s) {
					case "1":
					case "ocean":
						colorscheme("ocean");
						break;
					case "2":
					case "sublime":
						colorscheme("sublime");
						break;
					case "3":
					case "tomorrow":
						colorscheme("tomorrow");
				}
			}

			var h = confirm("Apply full height?")

			if (h) {
				for (var i = 0; i < codeTags_len; i++) {
					codeTags[i].style.maxHeight = "100%";
				}

				setCookie("fullheight", true, 365);
			}
			else {
				if (getCookie("fullheight")) {
					for (var i = 0; i < codeTags_len; i++) {
						codeTags[i].style.maxHeight = "650px";
					}

					setCookie("fullheight", false, 0);
				}
			}

			alert("Settings saved and assigned to your browser.");
		}
	}

	fullheight = getCookie("fullheight");

	for (var i = 0; i < codeTags_len; i++) {
		if (fullheight) {
			codeTags[i].style.maxHeight = "100%";
		}

		p = document.createElement("p");

		p.innerHTML = "<i class=\"fa fa-cog\"></i>";
		p.title = "config";
		p.style.float = "right";
		p.style.cursor = "pointer";

		p.addEventListener("click", function() {
			config();
		});

		codeTags[i].prepend(p);
	}

	// set [code] css
	scheme = getCookie("colorscheme") ? getCookie("colorscheme") : "ocean";

	document.head.innerHTML += "<link id=\"colorscheme\" rel=\"stylesheet\" href=\"" + CSS_DIR + "/highlight/" + scheme + ".css\">";
}, false);