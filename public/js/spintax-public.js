(function ($) {
	"use strict";

	function random(str) {
		return str.replace(/{(.*?)}/g, function (match, p1) {
			var words = p1.split("|");
			return words[Math.floor(Math.random() * words.length)];
		});
	}

	function js_random(str) {
		return str.replace(/~(.*?)~/g, function (match, p1) {
			var words = p1.split("|");
			return (
				'<span class="spintax">' + words[0] + "<noscript>" + words.join("|") + "</noscript></span>"
			);
		});
	}

	function transformSpintax(element) {
		element.innerHTML = element.innerHTML
			.replace(/{.*?}/g, function (match) {
				return random(match);
			})
			.replace(/~.*?~/g, function (match) {
				return js_random(match);
			});
	}

	// Apply spintax transformations
	document.body.innerHTML = document.body.innerHTML.replace(/{.*?}|~.*?~/g, function (match) {
		if (match.startsWith("{") && match.endsWith("}")) {
			return random(match);
		} else if (match.startsWith("~") && match.endsWith("~")) {
			return js_random(match);
		}
	});

	function js_random(str) {
		return str.replace(/~(.*?)~/g, function (match, p1) {
			var words = p1.split("|");
			return (
				'<span class="spintax">' + words[0] + "<noscript>" + words.join("|") + "</noscript></span>"
			);
		});
	}

	jQuery(document).ready(function ($) {
		var fadeSpeed = 350;
		$(".spintax").each(function () {
			var spintaxElement = $(this);
			var fullSpintax = spintaxElement.find("noscript").text();
			var spintaxArr = fullSpintax.split("|");
			var i = 0;

			spintaxElement.html(spintaxArr[i]).fadeIn(fadeSpeed);

			setInterval(function () {
				i = (i + 1) % spintaxArr.length;
				spintaxElement.fadeOut(fadeSpeed, function () {
					spintaxElement.html(spintaxArr[i]).fadeIn(fadeSpeed);
				});
			}, 2500);
		});
	});

	// Apply js_random to elements that are not part of the editor
	document.body.innerHTML = document.body.innerHTML.replace(/~.*?~/g, function (match) {
		return js_random(match);
	});
})(jQuery);
