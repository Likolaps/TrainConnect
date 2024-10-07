import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

// close accordion when clicking on another accordion
document.addEventListener("DOMContentLoaded", (event) => {
	let accordions = document.querySelectorAll(".collapse");

	accordions.forEach((accordion) => {
		accordion.addEventListener("show.bs.collapse", function () {
			accordions.forEach((otherAccordion) => {
				if (otherAccordion !== accordion) {
					let bsCollapse = new bootstrap.Collapse(otherAccordion, {
						toggle: false,
					});
					bsCollapse.hide();
				}
			});
		});
	});
});
