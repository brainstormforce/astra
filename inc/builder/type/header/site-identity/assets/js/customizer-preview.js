/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra HF Builder.
 * @since 3.0.0
 */

( function( $ ) {

    wp.customize( 'astra-settings[different-mobile-logo]', function ( value ) {
        value.bind( function ( newval ) {

			if ( '1' == newval ) {
                jQuery('.site-header').addClass( 'ast-has-mobile-header-logo' );
            } else {
                jQuery('.site-header').removeClass( 'ast-has-mobile-header-logo' );
            }
        } );
	} );

	// Margin.
    wp.customize( 'astra-settings[title_tagline-margin]', function( value ) {
		value.bind( function( margin ) {
			if(
				margin.desktop.bottom != '' || margin.desktop.top != '' || margin.desktop.left != '' || margin.desktop.right != '' ||
                margin.tablet.bottom != '' || margin.tablet.top != '' || margin.tablet.left != '' || margin.tablet.right != '' ||
                margin.mobile.bottom != '' || margin.mobile.top != '' || margin.mobile.left != '' || margin.mobile.right != ''
            ) {
                var dynamicStyle = '',
                    selector = '.ast-builder-layout-element .ast-site-identity',
                    tablet_break_point    = astraBuilderPreview.tablet_break_point || 768,
                    mobile_break_point    = astraBuilderPreview.mobile_break_point || 544;

                dynamicStyle += selector + ' {';
                dynamicStyle += 'margin-left: ' + margin['desktop']['left'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['desktop']['right'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['desktop']['top'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['desktop']['bottom'] + margin['desktop-unit'] + ';';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'margin-left: ' + margin['tablet']['left'] + margin['tablet-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['tablet']['right'] + margin['tablet-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['tablet']['top'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['tablet']['bottom'] + margin['desktop-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'margin-left: ' + margin['mobile']['left'] + margin['mobile-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['mobile']['right'] + margin['mobile-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['mobile']['top'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['mobile']['bottom'] + margin['desktop-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';
                astra_add_dynamic_css( 'title_tagline-margin', dynamicStyle );
            }
        } );
    } );

    var selector = '.ast-builder-layout-element .ast-site-identity';
	var section = 'title_tagline';

    // Title Color.
    astra_css(
        'astra-settings[header-color-site-title]',
        'color',
        selector + ' .site-title a, ' + selector + ' .site-title'
    );

    // Title Hover Color.
    astra_css(
        'astra-settings[header-color-h-site-title]',
        'color',
        selector + ' .site-title a:hover, ' + selector + ' .site-title:hover'
    );

    // Tagline Color.
    astra_css(
        'astra-settings[header-color-site-tagline]',
        'color',
        selector + ' .site-description'
    );

    var visibility_selector = '.ast-builder-layout-element[data-section="title_tagline"]';

    // Advanced Visibility CSS Generation.
    astra_builder_visibility_css( section, visibility_selector );
    
} )( jQuery );
