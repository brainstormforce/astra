import ColorComponent from './color-component.js';

export const colorControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		console.log(wp.customize);
		ReactDOM.render(
			<ColorComponent control={control} customizer={ wp.customize }/>,
			control.container[0]
		);
	}
} );
