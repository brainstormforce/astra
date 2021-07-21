import TypoPresetControl from './typo-presets-component';

export const TypographyPresetControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <TypoPresetControl control={ control }  customizer={ wp.customize } />, control.container[0] );
	},
	ready : function() {
		'use strict';
		let control = this;
		jQuery(document).mouseup(function(e) {

		});
	},
} );
