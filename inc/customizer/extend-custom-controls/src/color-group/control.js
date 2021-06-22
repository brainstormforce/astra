import ColorGroupComponent from './color-group-component';
import {astraGetResponsiveColorGroupJs} from '../common/responsive-helper';

export const colorGroupControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <ColorGroupComponent control={ control } />, control.container[0] );
	},
	ready : function() {
		astraGetResponsiveColorGroupJs( this );
		'use strict';
		let control = this;
		jQuery(document).mouseup(function(e) {
			let container = jQuery(control.container),
				colorWrap = container.find('.astra-color-picker-wrap'),
				resetBtnWrap = container.find('.ast-color-btn-reset-wrap');

			// If the target of the click isn't the container nor a descendant of the container.
			if (!colorWrap.is(e.target) && !resetBtnWrap.is(e.target) && colorWrap.has(e.target).length === 0 && resetBtnWrap.has(e.target).length === 0 ){
				container.find('.components-button.astra-color-icon-indicate.open').click();
			}
		});
	},
} );
