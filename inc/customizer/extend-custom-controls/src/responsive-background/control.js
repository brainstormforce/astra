import ResponsiveBackground from './responsive-background.js';
import {astraGetResponsiveBgJs} from '../common/responsive-helper';

export const responsiveBackgroundControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
	ReactDOM.render( <ResponsiveBackground control={ control } />, control.container[0] );
	},
	ready: function() {
		astraGetResponsiveBgJs( this, '' );
		let control = this;
		jQuery(document).mouseup(function(e){
			var container = jQuery(control.container);
			var bgWrap = container.find('.background-wrapper');
			var resetBtnWrap = container.find('.ast-color-btn-reset-wrap');

			// If the target of the click isn't the container nor a descendant of the container.
			if (!bgWrap.is(e.target) && !resetBtnWrap.is(e.target) && bgWrap.has(e.target).length === 0 && resetBtnWrap.has(e.target).length === 0 ){
				container.find('.components-button.astra-color-icon-indicate.open').click();
			}
		});
	},
} );
