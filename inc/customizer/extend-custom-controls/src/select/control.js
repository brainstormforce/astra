import SelectComponent from './select-component.js';

export const selectControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <SelectComponent control={ control } />, control.container[0] );
	}
} );