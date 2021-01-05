<?php
/**
 * Astra HTML Component Dynamic CSS.
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
class Astra_Html_Component_Dynamic_CSS {

	/**
	 * Dynamic CSS
	 *
	 * @param string $builder_type Builder Type.
	 * @return String Generated dynamic CSS for Heading Colors.
	 *
	 * @since 3.0.0
	 */
	public static function astra_html_dynamic_css( $builder_type = 'header' ) {

		$generated_css = '';

		$number_of_html = ( 'header' === $builder_type ) ? Astra_Builder_Helper::$num_of_header_html : Astra_Builder_Helper::$num_of_footer_html;

		for ( $index = 1; $index <= $number_of_html; $index++ ) {

			if ( ! Astra_Builder_Helper::is_component_loaded( 'html-' . $index, $builder_type ) ) {
				continue;
			}

			$_section = ( 'header' === $builder_type ) ? 'section-hb-html-' . $index : 'section-fb-html-' . $index;

			$margin    = astra_get_option( $_section . '-margin' );
			$font_size = astra_get_option( 'font-size-' . $_section );

			$text_color_desktop = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'color' ), 'desktop' );
			$text_color_tablet  = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'color' ), 'tablet' );
			$text_color_mobile  = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'color' ), 'mobile' );

			$link_color_desktop = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'desktop' );
			$link_color_tablet  = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'tablet' );
			$link_color_mobile  = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'mobile' );

			$link_h_color_desktop = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'desktop' );
			$link_h_color_tablet  = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'tablet' );
			$link_h_color_mobile  = astra_get_prop( astra_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'mobile' );

			$selector = ( 'header' === $builder_type ) ? '.ast-header-html-' . $index : '.footer-widget-area[data-section="section-fb-html-' . $index . '"]';

			$display_prop = ( 'header' === $builder_type ) ? 'flex' : 'block';

			$css_output_desktop = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_desktop,
					// Typography.
					'font-size' => astra_responsive_font( $font_size, 'desktop' ),
				),

				$selector                                => array(
					// Margin.
					'margin-top'    => astra_responsive_spacing( $margin, 'top', 'desktop' ),
					'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'desktop' ),
					'margin-left'   => astra_responsive_spacing( $margin, 'left', 'desktop' ),
					'margin-right'  => astra_responsive_spacing( $margin, 'right', 'desktop' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_desktop,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_desktop,
				),
			);

			/* Parse CSS from array() */
			$css_output = astra_parse_css( $css_output_desktop );

			// Tablet CSS.
			$css_output_tablet = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_tablet,
					// Typography.
					'font-size' => astra_responsive_font( $font_size, 'tablet' ),
				),

				$selector                                => array(
					// Margin CSS.
					'margin-top'    => astra_responsive_spacing( $margin, 'top', 'tablet' ),
					'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'tablet' ),
					'margin-left'   => astra_responsive_spacing( $margin, 'left', 'tablet' ),
					'margin-right'  => astra_responsive_spacing( $margin, 'right', 'tablet' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_tablet,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_tablet,
				),
			);
			$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );

			// Mobile CSS.
			$css_output_mobile = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_mobile,
					// Typography.
					'font-size' => astra_responsive_font( $font_size, 'mobile' ),
				),

				$selector                                => array(
					// Margin CSS.
					'margin-top'    => astra_responsive_spacing( $margin, 'top', 'mobile' ),
					'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'mobile' ),
					'margin-left'   => astra_responsive_spacing( $margin, 'left', 'mobile' ),
					'margin-right'  => astra_responsive_spacing( $margin, 'right', 'mobile' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_mobile,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_mobile,
				),
			);
			$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

			$generated_css .= $css_output;

			$generated_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_typography_css( $_section, $selector );

			$generated_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector, $display_prop );
		}

		return $generated_css;
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new Astra_Html_Component_Dynamic_CSS();
