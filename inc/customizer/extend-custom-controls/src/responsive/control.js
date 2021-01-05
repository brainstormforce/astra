import ResponsiveComponent from './responsive-component.js';
import {astraGetResponsiveJs} from '../common/responsive-helper';

export const responsiveControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <ResponsiveComponent control={ control } />, control.container[0] );
	},
	ready: function() {
		astraGetResponsiveJs( this );
	},
} );
