import ButtonPresetsComponent from './button-presets-component';

export const ButtonPresetControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <ButtonPresetsComponent control={ control } customizer={ wp.customize }/>, control.container[0] );
	},
	ready : function() {
		'use strict';
		let control = this;
		window.cachedBtnBGColor = '';
	},
} );
