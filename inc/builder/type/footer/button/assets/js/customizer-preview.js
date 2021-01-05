/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra
 * @since 3.0.0
 */

( function( $ ) {

	var tablet_break_point    = AstraBuilderFooterButtonData.tablet_break_point || 768,
		mobile_break_point    = AstraBuilderFooterButtonData.mobile_break_point || 544;

	astra_builder_button_css( 'footer', AstraBuilderFooterButtonData.footer_button_count );

	for( var index = 1; index <= AstraBuilderFooterButtonData.footer_button_count ; index++ ) {
		(function( index ) {

			wp.customize( 'astra-settings[footer-button-'+ index +'-alignment]', function( value ) {
				value.bind( function( alignment ) {

					if( alignment.desktop != '' || alignment.tablet != '' || alignment.mobile != '' ) {
						var dynamicStyle = '';
						dynamicStyle += '.ast-footer-button-'+ index +'[data-section="section-fb-button-'+ index +'"] {';
						dynamicStyle += 'justify-content: ' + alignment['desktop'] + ';';
						dynamicStyle += '} ';

						dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
						dynamicStyle += '.ast-footer-button-'+ index +'[data-section="section-fb-button-'+ index +'"] {';
						dynamicStyle += 'justify-content: ' + alignment['tablet'] + ';';
						dynamicStyle += '} ';
						dynamicStyle += '} ';

						dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
						dynamicStyle += '.ast-footer-button-'+ index +'[data-section="section-fb-button-'+ index +'"] {';
						dynamicStyle += 'justify-content: ' + alignment['mobile'] + ';';
						dynamicStyle += '} ';
						dynamicStyle += '} ';

						astra_add_dynamic_css( 'footer-button-'+ index +'-alignment', dynamicStyle );
					}
				} );
			} );

		})( index );
	}


} )( jQuery );
