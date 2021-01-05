import DraggableComponent from './draggable-component.js';

export const DraggableControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <DraggableComponent control={ control } customizer={ wp.customize } />, control.container[0] );
	}
} );
