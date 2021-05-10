/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra Builder
 * @since 3.0.0
 */

( function( $ ) {

    var tablet_break_point    = AstraBuilderHTMLData.tablet_break_point || 768,
        mobile_break_point    = AstraBuilderHTMLData.mobile_break_point || 544;

    astra_builder_html_css( 'footer', AstraBuilderHTMLData.component_limit );

    for( var index = 1; index <= AstraBuilderHTMLData.component_limit ; index++ ) {
		(function( index ) {
			wp.customize( 'astra-settings[footer-html-'+ index +'-alignment]', function( value ) {
				value.bind( function( alignment ) {
					if( alignment.desktop != '' || alignment.tablet != '' || alignment.mobile != '' ) {
						var dynamicStyle = '';
						dynamicStyle += '.footer-widget-area[data-section="section-fb-html-'+ index +'"] .ast-builder-html-element {';
						dynamicStyle += 'text-align: ' + alignment['desktop'] + ';';
						dynamicStyle += '} ';

						dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
						dynamicStyle += '.footer-widget-area[data-section="section-fb-html-'+ index +'"] .ast-builder-html-element {';
						dynamicStyle += 'text-align: ' + alignment['tablet'] + ';';
						dynamicStyle += '} ';
						dynamicStyle += '} ';

						dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
						dynamicStyle += '.footer-widget-area[data-section="section-fb-html-'+ index +'"] .ast-builder-html-element {';
						dynamicStyle += 'text-align: ' + alignment['mobile'] + ';';
						dynamicStyle += '} ';
						dynamicStyle += '} ';

						astra_add_dynamic_css( 'footer-html-'+ index +'-alignment', dynamicStyle );
					}
				} );
			} );
		})( index );
	}

} )( jQuery );
