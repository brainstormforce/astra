import DescriptionComponent from './description-component';

export const descriptionControl = wp.customize.Control.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <DescriptionComponent control={ control } />, control.container[0] );
	}
} );
