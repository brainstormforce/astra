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

	var section = 'section-above-footer-builder';
	var selector = '.site-above-footer-wrap[data-section="section-above-footer-builder"]';

	// Footer Height.
	astra_css(
		'astra-settings[hba-footer-height]',
		'min-height',
		selector,
		'px'
	);

	// Footer Vertical Alignment.
    astra_css(
        'astra-settings[hba-footer-vertical-alignment]',
        'align-items',
        selector + ' .ast-builder-grid-row'
    );

	// Border Bottom width.
	astra_css(
		'astra-settings[hba-footer-separator]',
		'border-top-width',
		selector,
		'px'
	);

	// Inner Space.
	wp.customize( 'astra-settings[hba-inner-spacing]', function( value ) {
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

			astra_add_dynamic_css( 'hba-inner-spacing-toggle-button', dynamicStyle );
		} );
	} );

	// Border Color.

	astra_css(
		'astra-settings[hba-footer-top-border-color]',
		'border-top-color',
		selector
	);

	var dynamicStyle = selector + ' {';
		dynamicStyle += 'border-top-style: solid';
	dynamicStyle += '} ';

	astra_add_dynamic_css( 'hba-footer-top-border-color', dynamicStyle );

	// Primary Header - Layout.
	wp.customize( 'astra-settings[hba-footer-layout-width]', function( setting ) {
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

			astra_add_dynamic_css( 'hba-footer-layout-width', dynamicStyle );

		} );
	} );

	// Responsive BG styles > Above Footer Row.
	astra_apply_responsive_background_css( 'astra-settings[hba-footer-bg-obj-responsive]', selector, 'desktop' );
	astra_apply_responsive_background_css( 'astra-settings[hba-footer-bg-obj-responsive]', selector, 'tablet' );
	astra_apply_responsive_background_css( 'astra-settings[hba-footer-bg-obj-responsive]', selector, 'mobile' );

	// Advanced CSS Generation.
	astra_builder_advanced_css( section, selector );

	// Advanced Visibility CSS Generation.
	astra_builder_visibility_css( section, selector, 'grid' );

} )( jQuery );
