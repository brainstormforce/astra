import BoxShadowComponent from './box-shadow-component.js';

export const boxShadowControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <BoxShadowComponent control={ control } />, control.container[0] );
	}
} );
