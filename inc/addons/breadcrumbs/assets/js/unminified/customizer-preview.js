/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra
 * @since 1.7.0
 */

( function( $ ) {

	/* Breadcrumb Typography */
	astra_responsive_font_size( 
		'astra-settings[breadcrumb-font-size]',
		'.ast-breadcrumbs-wrapper .trail-items span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span,  .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator'
	);
	astra_generate_outside_font_family_css(
		'astra-settings[breadcrumb-font-family]',
		'.ast-breadcrumbs-wrapper .trail-items span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span,  .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator'
	);
	astra_generate_font_weight_css( 'astra-settings[breadcrumb-font-family]', 'astra-settings[breadcrumb-font-weight]', 'font-weight', '.ast-breadcrumbs-wrapper .trail-items span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span,  .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator' );
	astra_css(
		'astra-settings[breadcrumb-text-transform]',
		'text-transform',
		'.ast-breadcrumbs-wrapper .trail-items span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span,  .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb - Line Height */
	astra_css( 
		'astra-settings[breadcrumb-line-height]',
		'line-height',
		'.ast-breadcrumbs-wrapper .ast-breadcrumbs-name, .ast-breadcrumbs-wrapper .ast-breadcrumbs-item, .ast-breadcrumbs-wrapper .ast-breadcrumbs .separator, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator'
	);
		
	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb - Text Color */
	astra_color_responsive_css(
		'breadcrumb', 
		'astra-settings[breadcrumb-active-color-responsive]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items .trail-end, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast .breadcrumb_last, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last'
	);
	
	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb - Link Color */
	astra_color_responsive_css(
		'breadcrumb',
		'astra-settings[breadcrumb-text-color-responsive]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items a, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast a, .ast-breadcrumbs-wrapper .breadcrumbs a, .ast-breadcrumbs-wrapper .rank-math-breadcrumb a'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb - Hover Color */
	astra_color_responsive_css(
		'breadcrumb',
		'astra-settings[breadcrumb-hover-color-responsive]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items a:hover, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast a:hover, .ast-breadcrumbs-wrapper .breadcrumbs a:hover, .ast-breadcrumbs-wrapper .rank-math-breadcrumb a:hover'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb - Separator Color */
	astra_color_responsive_css(
		'breadcrumb',
		'astra-settings[breadcrumb-separator-color]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items li::after, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .rank-math-breadcrumb .separator'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb - Background Color */
	astra_color_responsive_css(
		'breadcrumb',
		'astra-settings[breadcrumb-bg-color]',
		'background-color',
		'.ast-breadcrumbs-wrapper, .main-header-bar.ast-header-breadcrumb, .ast-primary-sticky-header-active .main-header-bar.ast-header-breadcrumb'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb - Alignment */
	astra_css(
		'astra-settings[breadcrumb-alignment]',
		'text-align',
		'.ast-breadcrumbs-wrapper'
	);

	/**
	 * Breadcrumb Spacing
	 */
	wp.customize( 'astra-settings[breadcrumb-spacing]', function( value ) {
		value.bind( function( padding ) {
			var spacing_value = wp.customize( 'astra-settings[breadcrumb-position]' ).get();
			if( 'astra_header_markup_after' == spacing_value || 'astra_header_after' == spacing_value ) {
				astra_responsive_spacing( 'astra-settings[breadcrumb-spacing]','.main-header-bar.ast-header-breadcrumb', 'padding',  ['top', 'right', 'bottom', 'left' ] );
			} else if( 'astra_masthead_content' == spacing_value ) {
				astra_responsive_spacing( 'astra-settings[breadcrumb-spacing]','.ast-breadcrumbs-wrapper .ast-breadcrumbs-inner #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner .breadcrumbs, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner .rank-math-breadcrumb, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner .ast-breadcrumbs', 'padding',  ['top', 'right', 'bottom', 'left' ] );
			} else {
				astra_responsive_spacing( 'astra-settings[breadcrumb-spacing]','.ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .rank-math-breadcrumb, .ast-breadcrumbs-wrapper .ast-breadcrumbs', 'padding',  ['top', 'right', 'bottom', 'left' ] );
			}
		} );
	} );

	/**
	 * Breadcrumb Separator.
	 */
	wp.customize( 'astra-settings[breadcrumb-separator]', function( value ) {
		value.bind( function( value ) {
			var dynamicStyle = '';
                dynamicStyle += '.trail-items li::after {';
                dynamicStyle += 'content: "' + value + '";';
                dynamicStyle += '} ';
				astra_add_dynamic_css( 'breadcrumb-separator', dynamicStyle );
		} );
	} );

} )( jQuery );
		