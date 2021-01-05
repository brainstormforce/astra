import ColorComponent from './color-component';

export const colorControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render(
			<ColorComponent control={control} customizer={ wp.customize }/>,
			control.container[0]
		);
	},
	ready : function() {
		'use strict';
		let control = this;
		jQuery(document).mouseup(function(e){
			var container = jQuery(control.container);
			var colorWrap = container.find('.astra-color-picker-wrap');
			var resetBtnWrap = container.find('.ast-color-btn-reset-wrap');
			
			// If the target of the click isn't the container nor a descendant of the container.
			if (!colorWrap.is(e.target) && !resetBtnWrap.is(e.target) && colorWrap.has(e.target).length === 0 && resetBtnWrap.has(e.target).length === 0 ){
				container.find('.components-button.astra-color-icon-indicate.open').click();
			}
		});
	}
} );
