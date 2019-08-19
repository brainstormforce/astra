/**
 * File spacing.js
 *
 * Handles the spacing
 *
 * @package Astra
 */

wp.customize.controlConstructor['ast-customizer-link'] = wp.customize.Control.extend({

	ready: function () {
		'use strict';

		// Add event listener for click action.
		this.container.on('click', '.customizer-link', function (e) {
			e.preventDefault();

			var sectionName = this.getAttribute('data-customizer-linked');
			var section = wp.customize.section(sectionName);
			section.expand();
		});
	},

});
