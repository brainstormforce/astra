import HeadingComponent from './heading-component.js';

export const headingControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <HeadingComponent control={ control } />, control.container[0] );
	}
} );