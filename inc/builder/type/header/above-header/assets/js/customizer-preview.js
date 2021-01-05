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

	var tablet_break_point    = astraBuilderPreview.tablet_break_point || 768,
		mobile_break_point    = astraBuilderPreview.mobile_break_point || 544;

	wp.customize( 'astra-settings[hba-header-height]', function( value ) {
		value.bind( function( size ) {

			if( size.desktop != '' || size.tablet != '' || size.mobile != '' ) {
				var dynamicStyle = '';
				dynamicStyle += '.ast-above-header-bar .site-above-header-wrap, .ast-mobile-header-wrap .ast-above-header-bar .ast-builder-grid-row-container-inner {';
				dynamicStyle += 'min-height: ' + size.desktop + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '.ast-desktop .ast-above-header-bar .main-header-menu > .menu-item {';
				dynamicStyle += 'line-height: ' + size.desktop + 'px;';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
				dynamicStyle += '.ast-above-header-bar .site-above-header-wrap, .ast-mobile-header-wrap .ast-above-header-bar .ast-builder-grid-row-container-inner {';
				dynamicStyle += 'min-height: ' + size.tablet + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
				dynamicStyle += '.ast-above-header-bar .site-above-header-wrap, .ast-mobile-header-wrap .ast-above-header-bar .ast-builder-grid-row-container-inner {';
				dynamicStyle += 'min-height: ' + size.mobile + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';

				astra_add_dynamic_css( 'hba-header-height', dynamicStyle );
			}
		} );
	} );

	// Border Bottom width.
	astra_css(
		'astra-settings[hba-header-separator]',
		'border-bottom-width',
		'.ast-above-header.ast-above-header-bar, .ast-above-header-bar',
		'px'
	);

	// Border Color.
	astra_css(
		'astra-settings[hba-header-bottom-border-color]',
		'border-color',
		'.ast-above-header.ast-above-header-bar, .ast-above-header-bar',
	);

	astra_css(
		'astra-settings[hba-header-bottom-border-color]',
		'border-color',
		'.ast-above-header-bar',
	);

	var dynamicStyle = '.ast-above-header-bar {';
		dynamicStyle += 'border-bottom-style: solid';
	dynamicStyle += '} ';

	astra_add_dynamic_css( 'hba-header-bottom-border-color', dynamicStyle );

	// Responsive BG styles > Below Header Row.
	astra_apply_responsive_background_css( 'astra-settings[hba-header-bg-obj-responsive]', '.ast-above-header.ast-above-header-bar', 'desktop' );
	astra_apply_responsive_background_css( 'astra-settings[hba-header-bg-obj-responsive]', '.ast-above-header.ast-above-header-bar', 'tablet' );
	astra_apply_responsive_background_css( 'astra-settings[hba-header-bg-obj-responsive]', '.ast-above-header.ast-above-header-bar', 'mobile' );

	// Advanced CSS Generation.
	astra_builder_advanced_css( 'section-above-header-builder', '.ast-above-header.ast-above-header-bar' );
	
    // Advanced Visibility CSS Generation.
	astra_builder_visibility_css( 'section-above-header-builder', '.ast-above-header-bar', 'block' );

} )( jQuery );
