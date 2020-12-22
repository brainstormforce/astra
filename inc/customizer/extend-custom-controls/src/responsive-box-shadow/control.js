import ResponsiveBoxShadowComponent from './responsive-box-shadow-component.js';
import {astraGetResponsiveBoxShadowJs} from '../common/responsive-helper';

export const responsiveBoxShadowControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		console.log( 'Its control.js file' );
		let control = this;
	ReactDOM.render( <ResponsiveBoxShadowComponent control={ control } />, control.container[0] );
	},
	ready: function() {
		astraGetResponsiveBoxShadowJs( this );
	},
} );
