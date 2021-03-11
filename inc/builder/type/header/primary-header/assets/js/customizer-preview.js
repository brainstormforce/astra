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

	var tablet_break_point    = AstraBuilderPrimaryHeaderData.tablet_break_point || 768,
		mobile_break_point    = AstraBuilderPrimaryHeaderData.mobile_break_point || 544;

	wp.customize( 'astra-settings[hb-header-height]', function( value ) {
		value.bind( function( size ) {

			if( size.desktop != '' || size.tablet != '' || size.mobile != '' ) {
				var dynamicStyle = '';
				dynamicStyle += '.ast-mobile-header-wrap .ast-primary-header-bar , .ast-primary-header-bar .site-primary-header-wrap {';
				dynamicStyle += 'min-height: ' + size.desktop + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '.ast-desktop .ast-primary-header-bar .main-header-menu > .menu-item {';
				dynamicStyle += 'line-height: ' + size.desktop + 'px;';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
				dynamicStyle += '.ast-mobile-header-wrap .ast-primary-header-bar , .ast-primary-header-bar .site-primary-header-wrap {';
				dynamicStyle += 'min-height: ' + size.tablet + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
				dynamicStyle += '.ast-mobile-header-wrap .ast-primary-header-bar , .ast-primary-header-bar .site-primary-header-wrap {';
				dynamicStyle += 'min-height: ' + size.mobile + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';

				astra_add_dynamic_css( 'hb-header-height', dynamicStyle );
			}
		} );
	} );

	// Primary Header - Layout > Content Width.
	wp.customize( 'astra-settings[hb-header-main-layout-width]', function( setting ) {
		setting.bind( function( layout ) {

			var dynamicStyle = '';

			if ( 'content' !== layout ) {
				dynamicStyle += '#masthead .ast-container, .site-header-focus-item + .ast-breadcrumbs-wrapper {';
				dynamicStyle += 'max-width: unset;';
				dynamicStyle += 'padding-left: 35px;';
				dynamicStyle += 'padding-right: 35px;';
				dynamicStyle += '} ';

			} else {
				dynamicStyle = '#masthead .ast-container, .site-header-focus-item + .ast-breadcrumbs-wrapper {';
				dynamicStyle += 'max-width: 100%';
				dynamicStyle += 'padding-left: 20px;';
				dynamicStyle += 'padding-right: 20px;';
				dynamicStyle += '} ';
			}

			dynamicStyle +=  '@media (max-width: ' + AstraBuilderPrimaryHeaderData.header_break_point + 'px) {';
			dynamicStyle += '#masthead .ast-mobile-header-wrap .ast-above-header-bar, #masthead .ast-mobile-header-wrap .ast-primary-header-bar, #masthead .ast-mobile-header-wrap .ast-below-header-bar {';
			dynamicStyle += 'padding-left: 20px;';
			dynamicStyle += 'padding-right: 20px;';
			dynamicStyle += '} ';
			dynamicStyle += '} ';

			astra_add_dynamic_css( 'hb-header-main-layout-width', dynamicStyle );
		} );
	} );

	// Border Bottom width.
	astra_css(
		'astra-settings[hb-header-main-sep]',
		'border-bottom-width',
		'.ast-header-break-point .ast-primary-header-bar, .ast-primary-header-bar',
		'px'
	);

	// Border Color.

	astra_css(
		'astra-settings[hb-header-main-sep-color]',
		'border-color',
		'.ast-primary-header-bar',
	);
	
	astra_css(
		'astra-settings[hb-header-main-sep-color]',
		'border-color',
		'.ast-header-break-point .ast-primary-header-bar, .ast-primary-header-bar',
	);

	

	var dynamicStyle = '.ast-primary-header-bar {';
		dynamicStyle += 'border-bottom-style: solid';
	dynamicStyle += '} ';

	astra_add_dynamic_css( 'hb-header-main-sep-color', dynamicStyle );

	// Responsive BG styles > Primary Header Row.
	astra_apply_responsive_background_css( 'astra-settings[hb-header-bg-obj-responsive]', '.main-header-bar', 'desktop' );
	astra_apply_responsive_background_css( 'astra-settings[hb-header-bg-obj-responsive]', '.ast-primary-header.main-header-bar', 'tablet' );
	astra_apply_responsive_background_css( 'astra-settings[hb-header-bg-obj-responsive]', '.ast-primary-header.main-header-bar', 'mobile' );

	// Advanced CSS Generation.
	astra_builder_advanced_css( 'section-primary-header-builder', '.ast-desktop .ast-primary-header-bar, .ast-header-break-point .ast-primary-header-bar' );

	// Advanced Visibility CSS Generation.
	astra_builder_visibility_css( 'section-primary-header-builder', '.ast-primary-header-bar', 'grid' );

	// Advanced CSS for Header Builder - Margin.
	wp.customize( 'astra-settings[section-header-builder-layout-margin]', function( value ) {
        value.bind( function( margin ) {
            if(
                margin.desktop.bottom != '' || margin.desktop.top != '' || margin.desktop.left != '' || margin.desktop.right != '' ||
                margin.tablet.bottom != '' || margin.tablet.top != '' || margin.tablet.left != '' || margin.tablet.right != '' ||
                margin.mobile.bottom != '' || margin.mobile.top != '' || margin.mobile.left != '' || margin.mobile.right != ''
            ) {
                var dynamicStyle = '';
                dynamicStyle += '.astra-hfb-header .site-header {';
                dynamicStyle += 'margin-left: ' + margin['desktop']['left'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['desktop']['right'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['desktop']['top'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['desktop']['bottom'] + margin['desktop-unit'] + ';';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
                dynamicStyle += '.astra-hfb-header .site-header {';
                dynamicStyle += 'margin-left: ' + margin['tablet']['left'] + margin['tablet-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['tablet']['right'] + margin['tablet-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['tablet']['top'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['tablet']['bottom'] + margin['desktop-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
                dynamicStyle += '.astra-hfb-header .site-header {';
                dynamicStyle += 'margin-left: ' + margin['mobile']['left'] + margin['mobile-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['mobile']['right'] + margin['mobile-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['mobile']['top'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['mobile']['bottom'] + margin['desktop-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';
                astra_add_dynamic_css( 'section-header-builder-layout-margin-toggle-button', dynamicStyle );
            }
        } );
    } );


} )( jQuery );
