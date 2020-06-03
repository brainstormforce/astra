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

			var section;
			var linked = this.getAttribute('data-customizer-linked');
			var linkType = this.getAttribute('data-ast-customizer-link-type');
			switch (linkType) {
				case 'section':
					section = wp.customize.section(linked);
					section.expand();
					break;

				case 'control':
					wp.customize.control(linked).focus();
					break;
			
				default:
					break;
			}
		});
	},

});
