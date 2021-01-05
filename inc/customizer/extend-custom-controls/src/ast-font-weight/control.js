import FontWeightComponent from './ast-font-weight.js';

export const astFontWeightControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <FontWeightComponent control={ control } />, control.container[0] );
	},
	ready: function() {
		AstTypography.init();
	}
} );