import ResponsiveSpacingComponent from './responsive-spacing-component.js';
import {astraGetResponsiveSpacingJs} from '../common/responsive-helper';

export const responsiveSpacingControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
	ReactDOM.render( <ResponsiveSpacingComponent control={ control } />, control.container[0] );
	},
	ready: function() {
		astraGetResponsiveSpacingJs( this );
	},
} );
