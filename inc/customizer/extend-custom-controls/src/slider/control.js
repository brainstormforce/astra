import SliderComponent from './slider-component.js';

export const sliderControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <SliderComponent control={ control } />, control.container[0] );
	}
} );