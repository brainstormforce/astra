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

	var section = 'section-primary-footer-builder';
	var selector = '.site-primary-footer-wrap[data-section="section-primary-footer-builder"]';

	// Primary Header - Layout.
	wp.customize( 'astra-settings[hb-footer-layout-width]', function( setting ) {
		setting.bind( function( layout ) {

			var dynamicStyle = '';

			if ( 'content' == layout ) {
				dynamicStyle = selector + ' .ast-builder-grid-row {';
				dynamicStyle += 'max-width: ' + AstraBuilderPrimaryFooterData.footer_content_width + 'px;';
				dynamicStyle += 'margin-left: auto;';
				dynamicStyle += 'margin-right: auto;';
				dynamicStyle += '} ';
			}

			if ( 'full' == layout ) {
				dynamicStyle = selector + ' .ast-builder-grid-row {';
					dynamicStyle += 'max-width: 100%;';
					dynamicStyle += 'padding-right: 35px; padding-left: 35px;';
				dynamicStyle += '} ';
			}

			astra_add_dynamic_css( 'hb-footer-layout-width', dynamicStyle );

		} );
	} );

	// Footer Vertical Alignment.
    astra_css(
        'astra-settings[hb-footer-vertical-alignment]',
        'align-items',
        selector + ' .ast-builder-grid-row, ' + selector + ' .site-footer-section'
    );

	// Inner Space.
	wp.customize( 'astra-settings[hb-inner-spacing]', function( value ) {
		value.bind( function( spacing ) {
			var dynamicStyle = '';
			if ( spacing.desktop != '' ) {
				dynamicStyle += selector + ' .ast-builder-grid-row {';
				dynamicStyle += 'grid-column-gap: ' + spacing.desktop + 'px;';
				dynamicStyle += '} ';
			}
			
			if ( spacing.tablet != '' ) {
				dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
				dynamicStyle += selector + ' .ast-builder-grid-row {';
				dynamicStyle += 'grid-column-gap: ' + spacing.tablet + 'px;';
				dynamicStyle += 'grid-row-gap: ' + spacing.tablet + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';
			}

			if ( spacing.mobile != '' ) {
				dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
				dynamicStyle += selector + ' .ast-builder-grid-row {';
				dynamicStyle += 'grid-column-gap: ' + spacing.mobile + 'px;';
				dynamicStyle += 'grid-row-gap: ' + spacing.mobile + 'px;';
				dynamicStyle += '} ';
				dynamicStyle += '} ';
			}

			astra_add_dynamic_css( 'hb-inner-spacing-toggle-button', dynamicStyle );
		} );
	} );

	// Border Top width.
	astra_css(
		'astra-settings[hb-footer-main-sep]',
		'border-top-width',
		selector,
		'px'
	);

	// Border Color.
	astra_css(
		'astra-settings[hb-footer-main-sep-color]',
		'border-color',
		selector
	);

	var dynamicStyle = selector + ' {';
		dynamicStyle += 'border-top-style: solid';
	dynamicStyle += '} ';

	astra_add_dynamic_css( 'hb-footer-main-sep-color', dynamicStyle );

	// Responsive BG styles > Primary Footer Row.
	astra_apply_responsive_background_css( 'astra-settings[hb-footer-bg-obj-responsive]', selector, 'desktop' );
	astra_apply_responsive_background_css( 'astra-settings[hb-footer-bg-obj-responsive]', selector, 'tablet' );
	astra_apply_responsive_background_css( 'astra-settings[hb-footer-bg-obj-responsive]', selector, 'mobile' );

	// Responsive BG styles > Global Footer Row.
	astra_apply_responsive_background_css( 'astra-settings[footer-bg-obj-responsive]', '.site-footer', 'desktop' );
	astra_apply_responsive_background_css( 'astra-settings[footer-bg-obj-responsive]', '.site-footer', 'tablet' );
	astra_apply_responsive_background_css( 'astra-settings[footer-bg-obj-responsive]', '.site-footer', 'mobile' );

	// Advanced CSS Generation.
	astra_builder_advanced_css( section, selector );

	// Advanced CSS for Header Builder.
	astra_builder_advanced_css( 'section-footer-builder-layout', '.ast-hfb-header .site-footer' );

	// Advanced Visibility CSS Generation.
	astra_builder_visibility_css( section, selector, 'grid' );

} )( jQuery );
