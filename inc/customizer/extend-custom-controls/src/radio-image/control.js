import RadioImageComponent from './radio-image-component.js';

export const radioImageControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <RadioImageComponent control={ control } />, control.container[0] );
	}
} );