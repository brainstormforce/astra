import AstToggleControl from './toggle-control-component.js';

export const toggleControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <AstToggleControl control={ control } />, control.container[0] );
	}
} );