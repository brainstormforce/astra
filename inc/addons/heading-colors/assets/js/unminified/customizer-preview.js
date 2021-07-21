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

	/**
	 * Content <h1> to <h6> headings
	 */
	astra_css( 'astra-settings[heading-base-color]', 'color', 'h1, .entry-content h1, h2, .entry-content h2, h3, .entry-content h3, h4, .entry-content h4, h5, .entry-content h5, h6, .entry-content h6' );

	var h1_widget_title, h2_widget_title, h3_widget_title = '';
	if( astraCustomizer.font_weights_widget_title_support ) {
		h1_widget_title = 'h1.widget-title';
		h2_widget_title = 'h2.widget-title';
		h3_widget_title = 'h3.widget-title';
		h4_widget_title = 'h4.widget-title';
		h5_widget_title = 'h5.widget-title';
		h6_widget_title = 'h6.widget-title';
	}

	astra_generate_outside_font_family_css( 'astra-settings[font-family-h1]', 'h1, .entry-content h1' );
	astra_generate_font_weight_css( 'astra-settings[font-family-h1]', 'astra-settings[font-weight-h1]', 'font-weight', 'h1, .entry-content h1, ' + h1_widget_title );
	astra_css('astra-settings[line-height-h1]', 'line-height', 'h1, .entry-content h1, .elementor-widget-heading h1.elementor-heading-title');
	astra_css('astra-settings[text-transform-h1]', 'text-transform', 'h1, .entry-content h1');

	astra_generate_outside_font_family_css( 'astra-settings[font-family-h2]', 'h2, .entry-content h2' );
	astra_generate_font_weight_css( 'astra-settings[font-family-h2]', 'astra-settings[font-weight-h2]', 'font-weight', 'h2, .entry-content h2, ' + h2_widget_title );
	astra_css('astra-settings[line-height-h2]', 'line-height', 'h2, .entry-content h2, .elementor-widget-heading h2.elementor-heading-title');
	astra_css('astra-settings[text-transform-h2]', 'text-transform', 'h2, .entry-content h2');

	astra_generate_outside_font_family_css( 'astra-settings[font-family-h3]', 'h3, .entry-content h3' );
	astra_generate_font_weight_css( 'astra-settings[font-family-h3]', 'astra-settings[font-weight-h3]', 'font-weight', 'h3, .entry-content h3, ' + h3_widget_title );
	astra_css('astra-settings[line-height-h3]', 'line-height', 'h3, .entry-content h3, .elementor-widget-heading h3.elementor-heading-title');
	astra_css('astra-settings[text-transform-h3]', 'text-transform', 'h3, .entry-content h3');

	astra_generate_outside_font_family_css( 'astra-settings[font-family-h4]', 'h4, .entry-content h4' );
	astra_generate_font_weight_css( 'astra-settings[font-family-h4]', 'astra-settings[font-weight-h4]', 'font-weight', 'h4, .entry-content h4, ' + h4_widget_title );
	astra_css('astra-settings[text-transform-h4]', 'text-transform', 'h4, .entry-content h4');

	astra_generate_outside_font_family_css( 'astra-settings[font-family-h5]', 'h5, .entry-content h5' );
	astra_generate_font_weight_css( 'astra-settings[font-family-h5]', 'astra-settings[font-weight-h5]', 'font-weight', 'h5, .entry-content h5, ' + h5_widget_title );
	astra_css('astra-settings[text-transform-h5]', 'text-transform', 'h5, .entry-content h5');

	astra_generate_outside_font_family_css( 'astra-settings[font-family-h6]', 'h6, .entry-content h6' );
	astra_generate_font_weight_css( 'astra-settings[font-family-h6]', 'astra-settings[font-weight-h6]', 'font-weight', 'h6, .entry-content h6, ' + h6_widget_title );
	astra_css('astra-settings[text-transform-h6]', 'text-transform', 'h6, .entry-content h6');

	if ( astraCustomizer.page_builder_button_style_css ) {

		var ele_btn_font_family = '';
		var ele_btn_font_weight = '';
		var ele_btn_font_size = '';
		var ele_btn_transform = '';
		var ele_btn_line_height = '';
		var ele_btn_letter_spacing = '';

		if ( 'color-typo' == astraCustomizer.elementor_default_color_font_setting || 'typo' == astraCustomizer.elementor_default_color_font_setting ) {
			// Button Typo
			ele_btn_font_family = ',.elementor-button-wrapper .elementor-button, .elementor-button-wrapper .elementor-button:visited';
			ele_btn_font_weight = ',.elementor-button-wrapper .elementor-button, .elementor-button-wrapper .elementor-button:visited';
			ele_btn_font_size = ',.elementor-button-wrapper .elementor-button.elementor-size-sm, .elementor-button-wrapper .elementor-button.elementor-size-xs, .elementor-button-wrapper .elementor-button.elementor-size-md, .elementor-button-wrapper .elementor-button.elementor-size-lg, .elementor-button-wrapper .elementor-button.elementor-size-xl, .elementor-button-wrapper .elementor-button';
			ele_btn_transform = ',.elementor-button-wrapper .elementor-button, .elementor-button-wrapper .elementor-button:visited';
			ele_btn_line_height = ',.elementor-button-wrapper .elementor-button, .elementor-button-wrapper .elementor-button:visited';
			ele_btn_letter_spacing = ',.elementor-button-wrapper .elementor-button, .elementor-button-wrapper .elementor-button:visited', 'px';
		}

		// Button Typo
		astra_generate_outside_font_family_css( 'astra-settings[font-family-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link' + ele_btn_font_family );
		astra_generate_font_weight_css( 'astra-settings[font-family-button]', 'astra-settings[font-weight-button]', 'font-weight', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link' + ele_btn_font_weight );
		astra_css( 'astra-settings[text-transform-button]', 'text-transform', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link' + ele_btn_transform );
		astra_responsive_font_size( 'astra-settings[font-size-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link' + ele_btn_font_size );
		astra_css( 'astra-settings[theme-btn-line-height]', 'line-height', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link' + ele_btn_line_height );
		astra_css( 'astra-settings[theme-btn-letter-spacing]', 'letter-spacing', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link' + ele_btn_letter_spacing, 'px' );

	} else {
		// Button Typo
		astra_generate_outside_font_family_css( 'astra-settings[font-family-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' );
		astra_generate_font_weight_css( 'astra-settings[font-family-button]', 'astra-settings[font-weight-button]', 'font-weight', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' );
		astra_css( 'astra-settings[text-transform-button]', 'text-transform', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' );
		astra_responsive_font_size( 'astra-settings[font-size-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' );
		astra_css( 'astra-settings[theme-btn-line-height]', 'line-height', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' );
		astra_css( 'astra-settings[theme-btn-letter-spacing]', 'letter-spacing', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'px' );
	}

} )( jQuery );
