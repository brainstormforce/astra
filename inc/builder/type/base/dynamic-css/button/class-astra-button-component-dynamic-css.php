<?php
/**
 * Astra Button Component Dynamic CSS.
 *
 * @package     astra-builder
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Builder Dynamic CSS.
 *
 * @since 3.0.0
 */
class Astra_Button_Component_Dynamic_CSS {

	/**
	 * Dynamic CSS
	 *
	 * @param string $builder_type Builder Type.
	 * @return String Generated dynamic CSS for Heading Colors.
	 *
	 * @since 3.0.0
	 */
	public static function astra_button_dynamic_css( $builder_type = 'header' ) {

		$generated_css = '';

		$number_of_button = ( 'header' === $builder_type ) ? Astra_Builder_Helper::$num_of_header_button : Astra_Builder_Helper::$num_of_footer_button;

		for ( $index = 1; $index <= $number_of_button; $index++ ) {

			if ( ! Astra_Builder_Helper::is_component_loaded( 'button-' . $index, $builder_type ) ) {
				continue;
			}

			$_section = ( 'header' === $builder_type ) ? 'section-hb-button-' . $index : 'section-fb-button-' . $index;
			$margin   = astra_get_option( $_section . '-margin' );
			$_prefix  = 'button' . $index;

			$selector             = '.ast-' . $builder_type . '-button-' . $index;
			$button_font_size     = astra_get_option( $builder_type . '-' . $_prefix . '-font-size' );
			$button_border_width  = astra_get_option( $builder_type . '-' . $_prefix . '-border-size' );
			$button_border_radius = astra_get_option( $builder_type . '-' . $_prefix . '-border-radius' );
			// Normal Responsive Colors.
			$button_bg_color_desktop = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-back-color' ), 'desktop' );
			$button_bg_color_tablet  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-back-color' ), 'tablet' );
			$button_bg_color_mobile  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-back-color' ), 'mobile' );
			$button_color_desktop    = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-text-color' ), 'desktop' );
			$button_color_tablet     = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-text-color' ), 'tablet' );
			$button_color_mobile     = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-text-color' ), 'mobile' );
			// Hover Responsive Colors.
			$button_bg_h_color_desktop = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-back-h-color' ), 'desktop' );
			$button_bg_h_color_tablet  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-back-h-color' ), 'tablet' );
			$button_bg_h_color_mobile  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-back-h-color' ), 'mobile' );
			$button_h_color_desktop    = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-text-h-color' ), 'desktop' );
			$button_h_color_tablet     = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-text-h-color' ), 'tablet' );
			$button_h_color_mobile     = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-text-h-color' ), 'mobile' );

			// Normal Responsive Colors.
			$button_border_color_desktop = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-border-color' ), 'desktop' );
			$button_border_color_tablet  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-border-color' ), 'tablet' );
			$button_border_color_mobile  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-border-color' ), 'mobile' );

			// Hover Responsive Colors.
			$button_border_h_color_desktop = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-border-h-color' ), 'desktop' );
			$button_border_h_color_tablet  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-border-h-color' ), 'tablet' );
			$button_border_h_color_mobile  = astra_get_prop( astra_get_option( $builder_type . '-' . $_prefix . '-border-h-color' ), 'mobile' );

			/**
			 * Button CSS.
			 */
			$css_output_desktop = array(

				/**
				 * Button Colors.
				 */
				$selector . ' .ast-builder-button-wrap .ast-custom-button'       => array(
					// Colors.
					'color'               => $button_color_desktop,
					'background'          => $button_bg_color_desktop,

					// Typography.
					'font-size'           => astra_responsive_font( $button_font_size, 'desktop' ),

					// Border.
					'border-color'        => $button_border_color_desktop,
					'border-top-width'    => astra_get_css_value( $button_border_width['top'], 'px' ),
					'border-bottom-width' => astra_get_css_value( $button_border_width['bottom'], 'px' ),
					'border-left-width'   => astra_get_css_value( $button_border_width['left'], 'px' ),
					'border-right-width'  => astra_get_css_value( $button_border_width['right'], 'px' ),
					'border-radius'       => astra_get_css_value( $button_border_radius, 'px' ),
				),

				// Hover Options.
				$selector . ' .ast-builder-button-wrap:hover .ast-custom-button' => array(
					'color'        => $button_h_color_desktop,
					'background'   => $button_bg_h_color_desktop,
					'border-color' => $button_border_h_color_desktop,
				),
			);

			/**
			 * Button CSS.
			 */
			$css_output_tablet = array(

				/**
				 * Button Colors.
				 */
				$selector . ' .ast-builder-button-wrap .ast-custom-button'       => array(
					// Typography.
					'font-size'    => astra_responsive_font( $button_font_size, 'tablet' ),

					// Colors.
					'color'        => $button_color_tablet,
					'background'   => $button_bg_color_tablet,
					'border-color' => $button_border_color_tablet,
				),
				// Hover Options.
				$selector . ' .ast-builder-button-wrap:hover .ast-custom-button' => array(
					'color'        => $button_h_color_tablet,
					'background'   => $button_bg_h_color_tablet,
					'border-color' => $button_border_h_color_tablet,
				),
			);

			/**
			 * Button CSS.
			 */
			$css_output_mobile = array(

				/**
				 * Button Colors.
				 */
				$selector . ' .ast-builder-button-wrap .ast-custom-button'       => array(
					// Typography.
					'font-size'    => astra_responsive_font( $button_font_size, 'mobile' ),

					// Colors.
					'color'        => $button_color_mobile,
					'background'   => $button_bg_color_mobile,
					'border-color' => $button_border_color_mobile,
				),
				// Hover Options.
				$selector . ' .ast-builder-button-wrap:hover .ast-custom-button' => array(
					'color'        => $button_h_color_mobile,
					'background'   => $button_bg_h_color_mobile,
					'border-color' => $button_border_h_color_mobile,
				),
			);

			/* Parse CSS from array() */
			$css_output  = astra_parse_css( $css_output_desktop );
			$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
			$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

			$generated_css .= $css_output;

			$generated_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_margin_padding_css( $_section, $selector . ' .ast-builder-button-wrap .ast-custom-button' );

			$visibility_selector = '.ast-' . $builder_type . '-button-' . $index . '[data-section="' . $_section . '"]';
			$generated_css      .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $visibility_selector );     
		}

		return $generated_css;
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new Astra_Button_Component_Dynamic_CSS();
