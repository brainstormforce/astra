import CustomizerLinkComponent from './customizer-link-component.js';

export const customizerLinkControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <CustomizerLinkComponent control={ control } />, control.container[0] );
	}
} );