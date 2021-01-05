import HeaderTypeButtonComponent from './header-type-button-component';

export const HeaderTypeButtonControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <HeaderTypeButtonComponent control={ control } customizer={ wp.customize } />, control.container[0] );
	}
} );
