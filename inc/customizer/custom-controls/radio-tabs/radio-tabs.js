/**
 * File radio-tabs.js
 *
 * Handles toggling the radio images button
 *
 * @package Astra
 */

	wp.customize.controlConstructor['ast-radio-tabs'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this;

			// Change the value.
			this.container.on( 'click', '.radio-tabs-wrapper label', function() {				
				astraCustomizerRadioTabsToggle( jQuery( this ) );
			});

			setTimeout(function() {
				var label = control.container.find( '.radio-tabs-wrapper label.activated' );
				astraCustomizerRadioTabsToggle( label );
			}, 100);

		},

	});

	function astraCustomizerRadioTabsToggle( label )
	{
		var parent          = label.parent(),
			control_section = label.closest('.control-section');

		parent.find(' > label').removeClass('activated');
		label.addClass('activated');
		
		var dependentControls = [];
		if( 'undefined' != typeof label.data('dependent-control') ) {
			dependentControls = label.data('dependent-control').split(',')
		}
		control_section.find('.customize-control:not(.customize-control-ast-radio-tabs)').css( 'display', 'none' );

		if( dependentControls.length > 0 ) {
			$.each( dependentControls, function( index, val ) {
				var control_name = val.replace( '[', '-' );
				control_name = control_name.replace( ']', '' );
				control_section.find( '#customize-control-' + control_name ).css( 'display', 'list-item' );
			});
		}

		// @todo Remove `dependentControls` from `ASTCustomizerToggles[ toggleParentControls[j] ]`.
		var CustomCustomizerToggle = {};
		for ( var i = 0; i < dependentControls.length; i++ ) {
			var ToggleKey = dependentControls[i];
			var toggleParentControls = 'undefined' != ASTCustomizer.toogle_parent_map[ ToggleKey ] ? ASTCustomizer.toogle_parent_map[ ToggleKey ] : ASTCustomizerToggles[ ToggleKey ];
			if( 'undefined' != typeof toggleParentControls && toggleParentControls.length > 0 ) {
				for ( var j = 0; j < toggleParentControls.length; j++ ) {
					CustomCustomizerToggle[toggleParentControls[j]] = ASTCustomizerToggles[ toggleParentControls[j] ];
				}
			}
		}


		if( Object.keys( CustomCustomizerToggle ).length > 0 ) {
			ASTCustomizer._initToggles( CustomCustomizerToggle );
		}
	}