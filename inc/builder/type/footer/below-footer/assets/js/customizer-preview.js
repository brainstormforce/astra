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

	var section = 'section-below-footer-builder';
	var selector = '.site-below-footer-wrap[data-section="section-below-footer-builder"]';

	// Footer Vertical Alignment.
    astra_css(
        'astra-settings[hbb-footer-vertical-alignment]',
        'align-items',
        selector + ' .ast-builder-grid-row'
    );


	// Header Height.
	astra_css(
		'astra-settings[hbb-footer-height]',
		'min-height',
		selector,
		'px'
	);

	// Inner Space.
	wp.customize( 'astra-settings[hbb-inner-spacing]', function( value ) {
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

			astra_add_dynamic_css( 'hbb-inner-spacing-toggle-button', dynamicStyle );
		} );
	} );

	// Border Top width.
	astra_css(
		'astra-settings[hbb-footer-separator]',
		'border-top-width',
		selector,
		'px'
	);

	// Border Color.

	astra_css(
		'astra-settings[hbb-footer-top-border-color]',
		'border-top-color',
		selector
	);

	var dynamicStyle = selector + ' {';
		dynamicStyle += 'border-top-style: solid';
	dynamicStyle += '} ';

	astra_add_dynamic_css( 'hbb-footer-top-border-color', dynamicStyle );

	// Primary Header - Layout.
	wp.customize( 'astra-settings[hbb-footer-layout-width]', function( setting ) {
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

			astra_add_dynamic_css( 'hbb-footer-layout-width', dynamicStyle );

		} );
	} );


	// Responsive BG styles > Below Footer Row.
	astra_apply_responsive_background_css( 'astra-settings[hbb-footer-bg-obj-responsive]', selector, 'desktop' );
	astra_apply_responsive_background_css( 'astra-settings[hbb-footer-bg-obj-responsive]', selector, 'tablet' );
	astra_apply_responsive_background_css( 'astra-settings[hbb-footer-bg-obj-responsive]', selector, 'mobile' );

	// Advanced CSS Generation.
	astra_builder_advanced_css( section, selector );

	// Advanced Visibility CSS Generation.
	astra_builder_visibility_css( section, selector, 'grid' );

} )( jQuery );
