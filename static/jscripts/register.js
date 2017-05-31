window.addEventListener("load", function() {
	/* SIGNATURE GET USERNAME FIRST CHAR */

	var chars = "_#0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz".split("");

	document.getElementById("username").addEventListener("input", function() {
		char = this.value.substr(0, 1);

		if (!chars.includes(char)) {
			char = "?";
		}

		document.getElementById("userSignature").innerHTML = char;
	});

	/* SIGNATURE COLOR CHANGE */

	var colors = document.getElementsByClassName("color");
	var colors_len = colors.length;

	for (var i = 0; i < colors_len; i++) {
		colors[i].addEventListener("click", function() {
			colorId = this.className.split(" ")[1];

			/* hidden input */
			document.getElementById("color").value = colorId;

			/* signature box color */
			document.getElementById("userSignature").className = colorId;

			/* unactive active color */
			var activeColor = document.getElementsByClassName("activeColor")[0];
			activeColor.classList.remove("activeColor");
			activeColor.innerHTML = "";

			/* active selected color */
			this.classList.add("activeColor");
			this.innerHTML = "<i class=\"fa fa-check\"></i>";
		});
	}

	/* USERNAME INFO ABOUT ALLOWED CHARACTERS */

	document.getElementById("usernameInfo").addEventListener("click", function() {
		alert("List of allowed characters: " + chars.join(", ") + ".");
	});

	/* WHEN SUBMIT */

	document.getElementById("regform").addEventListener("submit", function() {
		var loading = document.getElementById("loading");

		loading.style.display = "inline";

		setInterval(function() {
			if (loading.innerHTML == "loading...") {
				loading.innerHTML = "loading";
			}
			else {
				loading.innerHTML += ".";
			}
		}, 250);
	});
}, false);