import FontFamilyComponent from './ast-font-family.js';

export const astFontFamilyControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <FontFamilyComponent control={ control } />, control.container[0] );
	},
	ready: function() {
		// setTimeout( function )
		AstTypography.init();
	}
} );
