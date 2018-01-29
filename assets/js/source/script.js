(function ($, root, undefined) {

	$(function () {

		'use strict';

		var index, today, current;

		$(window).load(function() {
			init();
		});

		function init() {

			// Cookies.remove("index");
			today = new Date().getDate();
			index = Cookies.get("index");
			if(!index) {
				index = today;
				Cookies.set("index", "0");
			}
			setCurrentDay(index);
			stickybits(".sticky");

			$(".calendar-day").not(".np").on("click", function() {
				setCurrentDay($(this).data("date"));
			});

			$(".go-to-day").on("click", function() {
				setCurrentDay(today);
			});

			$(document).keydown(function(e) {
				switch((e.keyCode ? e.keyCode : e.which)){
	        case 13: // Enter
						console.log("enter");
						break;
	        case 27: // Esc
						console.log("esc");
						break;
	        case 32: // Space
						console.log("space");
						break;
	        case 37: // Left Arrow
						console.log("left");
						current--;
						setCurrentDay(current);
		        break;
	        case 38: // Up Arrow
						console.log("up");
						current = current - 7;
						setCurrentDay(current);
						break;
	        case 39: // Right Arrow
						console.log("right");
						current++;
						setCurrentDay(current);
		        break;
	        case 40: // Down Arrow
						console.log("down");
						current = current + 7;
						setCurrentDay(current);
						break;
					case 84: // T
						setCurrentDay(today);
						break;
					default:
					break;
		    }
			});

			setTimeout(function(){
				$(".calendar-container").addClass("transform");
				$("body").addClass("show");
			}, 440);

		}

		function setCurrentDay(index) {
			// Set position
			var day = $(".calendar-day").eq(index);
			var width = 420;
			var height = 600;
			var dx = $(day).data("x");
			dx = (dx * width) + (width / 2);
			var dy = $(day).closest(".calendar-row").data("y");
			dy = (dy * height) + (height / 2);
			$(".active").removeClass("active");
			$(day).addClass("active");
			$(".calendar-container").css("transform", "translate3d(-"+dx+"px, -"+dy+"px, 0)");

			// Set color
			var delta = 360 / 7;
			var spin = $(day).data("x") * delta;
			var color = tinycolor("blue").spin(spin).toString();
			$(".content").css("border-color", color);

			// Set history
			Cookies.set("index", index);
			console.log(index);
			current = index;
		}

	});

})(jQuery, this);
