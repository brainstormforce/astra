import LinkComponent from './link-component.js';

export const linkControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <LinkComponent control={ control } />, control.container[0] );
	}
} );