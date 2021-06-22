import ColorPaletteComponent from './color-palette.js';

export const colorPaletteControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render(  <ColorPaletteComponent control={ control }  customizer={ wp.customize }/>, control.container[0] );
	},
	ready : function() {
		'use strict';
		let control = this;
		jQuery(document).mouseup(function(e){
			var container = jQuery(control.container);
			var colorWrap = container.find('.astra-color-picker-wrap');
			// If the target of the click isn't the container nor a descendant of the container.
			if (!colorWrap.is(e.target) && colorWrap.has(e.target).length === 0){
				container.find('.components-button.astra-color-icon-indicate.open').click();
			}
		});

		document.dispatchEvent( new CustomEvent( 'AstUpdatePaletteVariables', {} ) );
	},
} );
