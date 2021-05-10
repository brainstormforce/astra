import BorderComponent from './border-component.js';

export const borderControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <BorderComponent control={ control } />, control.container[0] );
	}
} );