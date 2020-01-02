<?php
/**
 * Gutenberg Editor CSS
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Gutenberg_Editor_CSS' ) ) :

	/**
	 * Admin Helper
	 */
	class Gutenberg_Editor_CSS {

		/**
		 * Get dynamic CSS  required for the block editor to make editing experience similar to how it looks on frontend.
		 *
		 * @return String CSS to be loaded in the editor interface.
		 */
		public static function get_css() {
			global $pagenow;

			$site_content_width          = astra_get_option( 'site-content-width', 1200 ) + 56;
			$headings_font_family        = astra_get_option( 'headings-font-family' );
			$headings_font_weight        = astra_get_option( 'headings-font-weight' );
			$headings_text_transform     = astra_get_option( 'headings-text-transform' );
			$headings_line_height        = astra_get_option( 'headings-line-height' );
			$single_post_title_font_size = astra_get_option( 'font-size-entry-title' );
			$body_font_family            = astra_body_font_family();
			$para_margin_bottom          = astra_get_option( 'para-margin-bottom' );
			$theme_color                 = astra_get_option( 'theme-color' );
			$link_color                  = astra_get_option( 'link-color', $theme_color );
			$heading_base_color          = astra_get_option( 'heading-base-color' );

			$highlight_link_color  = astra_get_foreground_color( $link_color );
			$highlight_theme_color = astra_get_foreground_color( $theme_color );

			$body_font_weight    = astra_get_option( 'body-font-weight' );
			$body_font_size      = astra_get_option( 'font-size-body' );
			$body_line_height    = astra_get_option( 'body-line-height' );
			$body_text_transform = astra_get_option( 'body-text-transform' );
			$box_bg_obj          = astra_get_option( 'site-layout-outside-bg-obj' );
			$text_color          = astra_get_option( 'text-color' );

			$heading_h1_font_size = astra_get_option( 'font-size-h1' );
			$heading_h2_font_size = astra_get_option( 'font-size-h2' );
			$heading_h3_font_size = astra_get_option( 'font-size-h3' );
			$heading_h4_font_size = astra_get_option( 'font-size-h4' );
			$heading_h5_font_size = astra_get_option( 'font-size-h5' );
			$heading_h6_font_size = astra_get_option( 'font-size-h6' );

			/**
			 * WooCommerce Grid Products compatibility.
			 */
			$link_h_color      = astra_get_option( 'link-h-color' );
			$btn_color         = astra_get_option( 'button-color' );
			$btn_bg_color      = astra_get_option( 'button-bg-color', '', $theme_color );
			$btn_h_color       = astra_get_option( 'button-h-color' );
			$btn_bg_h_color    = astra_get_option( 'button-bg-h-color', '', $link_h_color );
			$btn_border_radius = astra_get_option( 'button-radius' );
			$theme_btn_padding = astra_get_option( 'theme-button-padding' );

			/**
			 * Button theme compatibility.
			 */
			$global_custom_button_border_size = astra_get_option( 'theme-button-border-group-border-size' );
			$btn_border_color                 = astra_get_option( 'theme-button-border-group-border-color' );
			$btn_border_h_color               = astra_get_option( 'theme-button-border-group-border-h-color' );

			/**
			 * Theme Button Typography
			 */
			$theme_btn_font_family    = astra_get_option( 'font-family-button' );
			$theme_btn_font_size      = astra_get_option( 'font-size-button' );
			$theme_btn_font_weight    = astra_get_option( 'font-weight-button' );
			$theme_btn_text_transform = astra_get_option( 'text-transform-button' );
			$theme_btn_line_height    = astra_get_option( 'theme-btn-line-height' );
			$theme_btn_letter_spacing = astra_get_option( 'theme-btn-letter-spacing' );

			$h1_font_family    = astra_get_option( 'font-family-h1' );
			$h1_font_weight    = astra_get_option( 'font-weight-h1' );
			$h1_line_height    = astra_get_option( 'line-height-h1' );
			$h1_text_transform = astra_get_option( 'text-transform-h1' );

			$h2_font_family    = astra_get_option( 'font-family-h2' );
			$h2_font_weight    = astra_get_option( 'font-weight-h2' );
			$h2_line_height    = astra_get_option( 'line-height-h2' );
			$h2_text_transform = astra_get_option( 'text-transform-h2' );

			$h3_font_family    = astra_get_option( 'font-family-h3' );
			$h3_font_weight    = astra_get_option( 'font-weight-h3' );
			$h3_line_height    = astra_get_option( 'line-height-h3' );
			$h3_text_transform = astra_get_option( 'text-transform-h3' );

			// Fallback for H1 - headings typography.
			if ( 'inherit' == $h1_font_family ) {
				$h1_font_family = $headings_font_family;
			}
			if ( 'normal' == $h1_font_weight ) {
				$h1_font_weight = $headings_font_weight;
			}
			if ( '' == $h1_text_transform ) {
				$h1_text_transform = $headings_text_transform;
			}
			if ( '' == $h1_line_height ) {
				$h1_line_height = $headings_line_height;
			}

			// Fallback for H2 - headings typography.
			if ( 'inherit' == $h2_font_family ) {
				$h2_font_family = $headings_font_family;
			}
			if ( 'normal' == $h2_font_weight ) {
				$h2_font_weight = $headings_font_weight;
			}
			if ( '' == $h2_text_transform ) {
				$h2_text_transform = $headings_text_transform;
			}
			if ( '' == $h2_line_height ) {
				$h2_line_height = $headings_line_height;
			}

			// Fallback for H3 - headings typography.
			if ( 'inherit' == $h3_font_family ) {
				$h3_font_family = $headings_font_family;
			}
			if ( 'normal' == $h3_font_weight ) {
				$h3_font_weight = $headings_font_weight;
			}
			if ( '' == $h3_text_transform ) {
				$h3_text_transform = $headings_text_transform;
			}
			if ( '' == $h3_line_height ) {
				$h3_line_height = $headings_line_height;
			}

			// Fallback for H4 - headings typography.
			$h4_line_height = $headings_line_height;
			$h4_line_height = $headings_line_height;

			// Fallback for H5 - headings typography.
			$h4_line_height = $headings_line_height;
			$h5_line_height = $headings_line_height;

			// Fallback for H6 - headings typography.
			$h4_line_height = $headings_line_height;
			$h6_line_height = $headings_line_height;

			if ( empty( $btn_color ) ) {
				$btn_color = astra_get_foreground_color( $theme_color );
			}

			if ( empty( $btn_h_color ) ) {
				$btn_h_color = astra_get_foreground_color( $link_h_color );
			}

			$container_layout = get_post_meta( get_the_id(), 'site-content-layout', true );

			if ( 'default' === $container_layout || '' === $container_layout ) {
				$container_layout = astra_get_option( 'single-' . get_post_type() . '-content-layout' );

				if ( 'default' === $container_layout ) {
					$container_layout = astra_get_option( 'site-content-layout' );
				}
			}

			if ( is_array( $body_font_size ) ) {
				$body_font_size_desktop = ( isset( $body_font_size['desktop'] ) && '' != $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 15;
			} else {
				$body_font_size_desktop = ( '' != $body_font_size ) ? $body_font_size : 15;
			}

			$css = '';

			$desktop_css = array(
				'html'                                    => array(
					'font-size' => astra_get_font_css_value( (int) $body_font_size_desktop * 6.25, '%' ),
				),
				'a'                                       => array(
					'color' => esc_attr( $link_color ),
				),

				// Global selection CSS.
				'.editor-block-list__layout .editor-block-list__block ::selection,.editor-block-list__layout .editor-block-list__block.is-multi-selected .editor-block-list__block-edit:before' => array(
					'background-color' => esc_attr( $theme_color ),
				),
				'.editor-block-list__layout .editor-block-list__block ::selection,.editor-block-list__layout .editor-block-list__block.is-multi-selected .editor-block-list__block-edit' => array(
					'color' => esc_attr( $highlight_theme_color ),
				),

				'.ast-separate-container .edit-post-visual-editor, .ast-page-builder-template .edit-post-visual-editor, .ast-plain-container .edit-post-visual-editor, .ast-separate-container #wpwrap #editor .edit-post-visual-editor' => astra_get_background_obj( $box_bg_obj ),
				'.editor-post-title__block,.editor-default-block-appender,.editor-block-list__block' => array(
					'max-width' => astra_get_css_value( $site_content_width, 'px' ),
				),
				'.editor-block-list__block[data-align=wide]' => array(
					'max-width' => astra_get_css_value( $site_content_width + 200, 'px' ),
				),
				'.editor-post-title__block .editor-post-title__input,  .edit-post-visual-editor h1, .edit-post-visual-editor h2, .edit-post-visual-editor h3, .edit-post-visual-editor h4, .edit-post-visual-editor h5, .edit-post-visual-editor h6' => array(
					'font-family'    => astra_get_css_value( $headings_font_family, 'font' ),
					'font-weight'    => astra_get_css_value( $headings_font_weight, 'font' ),
					'text-transform' => esc_attr( $headings_text_transform ),
				),
				'.edit-post-visual-editor h1, .edit-post-visual-editor h2, .edit-post-visual-editor h3, .edit-post-visual-editor h4, .edit-post-visual-editor h5, .edit-post-visual-editor h6' => array(
					'line-height' => esc_attr( $headings_line_height ),
				),
				'.edit-post-visual-editor.editor-styles-wrapper p,.editor-block-list__block p, .editor-block-list__layout, .editor-post-title' => array(
					'font-size' => astra_responsive_font( $body_font_size, 'desktop' ),
				),
				'.edit-post-visual-editor.editor-styles-wrapper p,.editor-block-list__block p, .wp-block-latest-posts a,.editor-default-block-appender textarea.editor-default-block-appender__content, .editor-block-list__block' => array(
					'font-family'    => astra_get_font_family( $body_font_family ),
					'font-weight'    => esc_attr( $body_font_weight ),
					'font-size'      => astra_responsive_font( $body_font_size, 'desktop' ),
					'line-height'    => esc_attr( $body_line_height ),
					'text-transform' => esc_attr( $body_text_transform ),
					'margin-bottom'  => astra_get_css_value( $para_margin_bottom, 'em' ),
				),
				'.editor-post-title__block .editor-post-title__input' => array(
					'font-family' => ( 'inherit' === $headings_font_family ) ? astra_get_font_family( $body_font_family ) : astra_get_font_family( $headings_font_family ),
					'font-size'   => astra_responsive_font( $single_post_title_font_size, 'desktop' ),
					'font-weight' => 'normal',
				),
				'.editor-block-list__block'               => array(
					'color' => esc_attr( $text_color ),
				),
				/**
				 * Content base heading color.
				 */
				'.editor-post-title__block .editor-post-title__input, .wc-block-grid__product-title, .edit-post-visual-editor h1, .edit-post-visual-editor h2, .edit-post-visual-editor h3, .edit-post-visual-editor h4, .edit-post-visual-editor h5, .edit-post-visual-editor h6, .edit-post-visual-editor .wp-block-heading, .edit-post-visual-editor .wp-block-uagb-advanced-heading h1, .edit-post-visual-editor .wp-block-uagb-advanced-heading h2, .edit-post-visual-editor .wp-block-uagb-advanced-heading h3, .edit-post-visual-editor .wp-block-uagb-advanced-heading h4, .edit-post-visual-editor .wp-block-uagb-advanced-heading h5, .edit-post-visual-editor .wp-block-uagb-advanced-heading h6' => array(
					'color' => esc_attr( $heading_base_color ),
				),
				// Blockquote Text Color.
				'blockquote'                              => array(
					'color' => astra_adjust_brightness( $text_color, 75, 'darken' ),
				),
				'blockquote .editor-rich-text__tinymce a' => array(
					'color' => astra_hex_to_rgba( $link_color, 1 ),
				),
				'blockquote'                              => array(
					'border-color' => astra_hex_to_rgba( $link_color, 0.05 ),
				),
				'.editor-block-list__block .wp-block-quote:not(.is-large):not(.is-style-large), .edit-post-visual-editor .wp-block-pullquote blockquote' => array(
					'border-color' => astra_hex_to_rgba( $link_color, 0.15 ),
				),
				// Heading H1 - H6 font size.
				'.edit-post-visual-editor h1, .wp-block-heading h1, .wp-block-freeform.block-library-rich-text__tinymce h1, .edit-post-visual-editor .wp-block-heading h1, .wp-block-heading h1.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h1' => array(
					'font-size'      => astra_responsive_font( $heading_h1_font_size, 'desktop' ),
					'font-family'    => astra_get_css_value( $h1_font_family, 'font' ),
					'font-weight'    => astra_get_css_value( $h1_font_weight, 'font' ),
					'line-height'    => esc_attr( $h1_line_height ),
					'text-transform' => esc_attr( $h1_text_transform ),
				),
				'.edit-post-visual-editor h2, .wp-block-heading h2, .wp-block-freeform.block-library-rich-text__tinymce h2, .edit-post-visual-editor .wp-block-heading h2, .wp-block-heading h2.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h2' => array(
					'font-size'      => astra_responsive_font( $heading_h2_font_size, 'desktop' ),
					'font-family'    => astra_get_css_value( $h2_font_family, 'font' ),
					'font-weight'    => astra_get_css_value( $h2_font_weight, 'font' ),
					'line-height'    => esc_attr( $h2_line_height ),
					'text-transform' => esc_attr( $h2_text_transform ),
				),
				'.edit-post-visual-editor h3, .wp-block-heading h3, .wp-block-freeform.block-library-rich-text__tinymce h3, .edit-post-visual-editor .wp-block-heading h3, .wp-block-heading h3.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h3' => array(
					'font-size'      => astra_responsive_font( $heading_h3_font_size, 'desktop' ),
					'font-family'    => astra_get_css_value( $h3_font_family, 'font' ),
					'font-weight'    => astra_get_css_value( $h3_font_weight, 'font' ),
					'line-height'    => esc_attr( $h3_line_height ),
					'text-transform' => esc_attr( $h3_text_transform ),
				),
				'.edit-post-visual-editor h4, .wp-block-heading h4, .wp-block-freeform.block-library-rich-text__tinymce h4, .edit-post-visual-editor .wp-block-heading h4, .wp-block-heading h4.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h4' => array(
					'font-size'   => astra_responsive_font( $heading_h4_font_size, 'desktop' ),
					'line-height' => esc_attr( $h4_line_height ),
				),
				'.edit-post-visual-editor h5, .wp-block-heading h5, .wp-block-freeform.block-library-rich-text__tinymce h5, .edit-post-visual-editor .wp-block-heading h5, .wp-block-heading h5.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h5' => array(
					'font-size'   => astra_responsive_font( $heading_h5_font_size, 'desktop' ),
					'line-height' => esc_attr( $h5_line_height ),
				),
				'.edit-post-visual-editor h6, .wp-block-heading h6, .wp-block-freeform.block-library-rich-text__tinymce h6, .edit-post-visual-editor .wp-block-heading h6, .wp-block-heading h6.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h6' => array(
					'font-size'   => astra_responsive_font( $heading_h6_font_size, 'desktop' ),
					'line-height' => esc_attr( $h6_line_height ),
				),
				/**
				 * WooCommerce Grid Products compatibility.
				 */
				'.wc-block-grid__product-title'           => array(
					'color' => esc_attr( $text_color ),
				),
				'.wc-block-grid__product .wc-block-grid__product-onsale' => array(
					'background-color' => $theme_color,
					'color'            => astra_get_foreground_color( $theme_color ),
				),
				'.editor-styles-wrapper .wc-block-grid__products .wc-block-grid__product .wp-block-button__link, .wc-block-grid__product-onsale' => array(
					'color'            => $btn_color,
					'border-color'     => $btn_bg_color,
					'background-color' => $btn_bg_color,
				),
				'.wc-block-grid__products .wc-block-grid__product .wp-block-button__link:hover' => array(
					'color'            => $btn_h_color,
					'border-color'     => $btn_bg_h_color,
					'background-color' => $btn_bg_h_color,
				),
				'.wc-block-grid__products .wc-block-grid__product .wp-block-button__link' => array(
					'border-radius'  => astra_get_css_value( $btn_border_radius, 'px' ),
					'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'desktop' ),
					'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'desktop' ),
					'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'desktop' ),
					'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'desktop' ),
				),
			);

			$css .= astra_parse_css( $desktop_css );

			/**
			 * Global button CSS - Tablet.
			 */
			$css_prod_button_tablet = array(
				'.wc-block-grid__products .wc-block-grid__product .wp-block-button__link' => array(
					'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'tablet' ),
					'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'tablet' ),
					'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'tablet' ),
					'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'tablet' ),
				),
			);

			$css .= astra_parse_css( $css_prod_button_tablet, '', '768' );

			/**
			 * Global button CSS - Mobile.
			 */
			$css_prod_button_mobile = array(
				'.wc-block-grid__products .wc-block-grid__product .wp-block-button__link' => array(
					'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'mobile' ),
					'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'mobile' ),
					'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'mobile' ),
					'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'mobile' ),
				),
			);

			$css .= astra_parse_css( $css_prod_button_mobile, '', '544' );

			if ( Astra_Dynamic_CSS::page_builder_button_style_css() ) {
				$button_desktop_css = array(
					/**
					 * Gutenberg button compatibility for default styling.
					 */
					'.wp-block-button .wp-block-button__link' => array(
						'border-style'        => 'solid',
						'border-top-width'    => ( isset( $global_custom_button_border_size['top'] ) && '' !== $global_custom_button_border_size['top'] ) ? astra_get_css_value( $global_custom_button_border_size['top'], 'px' ) : '1px',
						'border-right-width'  => ( isset( $global_custom_button_border_size['right'] ) && '' !== $global_custom_button_border_size['right'] ) ? astra_get_css_value( $global_custom_button_border_size['right'], 'px' ) : '1px',
						'border-left-width'   => ( isset( $global_custom_button_border_size['left'] ) && '' !== $global_custom_button_border_size['left'] ) ? astra_get_css_value( $global_custom_button_border_size['left'], 'px' ) : '1px',
						'border-bottom-width' => ( isset( $global_custom_button_border_size['bottom'] ) && '' !== $global_custom_button_border_size['bottom'] ) ? astra_get_css_value( $global_custom_button_border_size['bottom'], 'px' ) : '1px',
						'color'               => esc_attr( $btn_color ),
						'border-color'        => empty( $btn_border_color ) ? esc_attr( $btn_bg_color ) : esc_attr( $btn_border_color ),
						'background-color'    => esc_attr( $btn_bg_color ),
						'font-family'         => astra_get_font_family( $theme_btn_font_family ),
						'font-weight'         => esc_attr( $theme_btn_font_weight ),
						'line-height'         => esc_attr( $theme_btn_line_height ),
						'text-transform'      => esc_attr( $theme_btn_text_transform ),
						'letter-spacing'      => astra_get_css_value( $theme_btn_letter_spacing, 'px' ),
						'font-size'           => astra_responsive_font( $theme_btn_font_size, 'desktop' ),
						'border-radius'       => astra_get_css_value( $btn_border_radius, 'px' ),
						'padding-top'         => astra_responsive_spacing( $theme_btn_padding, 'top', 'desktop' ),
						'padding-right'       => astra_responsive_spacing( $theme_btn_padding, 'right', 'desktop' ),
						'padding-bottom'      => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'desktop' ),
						'padding-left'        => astra_responsive_spacing( $theme_btn_padding, 'left', 'desktop' ),
					),
					'.wp-block-button .wp-block-button__link:hover, .wp-block-button .wp-block-button__link:focus' => array(
						'color'            => esc_attr( $btn_h_color ),
						'background-color' => esc_attr( $btn_bg_h_color ),
						'border-color'     => empty( $btn_border_h_color ) ? esc_attr( $btn_bg_h_color ) : esc_attr( $btn_border_h_color ),

					),
				);

				$css .= astra_parse_css( $button_desktop_css );

				/**
				 * Global button CSS - Tablet.
				 */
				$css_global_button_tablet = array(
					'.wp-block-button .wp-block-button__link' => array(
						'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'tablet' ),
						'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'tablet' ),
						'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'tablet' ),
						'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'tablet' ),
					),
				);

				$css .= astra_parse_css( $css_global_button_tablet, '', '768' );

				/**
				 * Global button CSS - Mobile.
				 */
				$css_global_button_mobile = array(
					'.wp-block-button .wp-block-button__link' => array(
						'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'mobile' ),
						'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'mobile' ),
						'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'mobile' ),
						'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'mobile' ),
					),
				);

				$css .= astra_parse_css( $css_global_button_mobile, '', '544' );
			}

			$tablet_css = array(
				'.editor-post-title__block .editor-post-title__input' => array(
					'font-size' => astra_responsive_font( $single_post_title_font_size, 'tablet', 30 ),
				),
				// Heading H1 - H6 font size.
				'.edit-post-visual-editor h1, .wp-block-heading h1, .wp-block-freeform.block-library-rich-text__tinymce h1, .edit-post-visual-editor .wp-block-heading h1, .wp-block-heading h1.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'tablet', 30 ),
				),
				'.edit-post-visual-editor h2, .wp-block-heading h2, .wp-block-freeform.block-library-rich-text__tinymce h2, .edit-post-visual-editor .wp-block-heading h2, .wp-block-heading h2.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'tablet', 25 ),
				),
				'.edit-post-visual-editor h3, .wp-block-heading h3, .wp-block-freeform.block-library-rich-text__tinymce h3, .edit-post-visual-editor .wp-block-heading h3, .wp-block-heading h3.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'tablet', 20 ),
				),
				'.edit-post-visual-editor h4, .wp-block-heading h4, .wp-block-freeform.block-library-rich-text__tinymce h4, .edit-post-visual-editor .wp-block-heading h4, .wp-block-heading h4.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'tablet' ),
				),
				'.edit-post-visual-editor h5, .wp-block-heading h5, .wp-block-freeform.block-library-rich-text__tinymce h5, .edit-post-visual-editor .wp-block-heading h5, .wp-block-heading h5.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'tablet' ),
				),
				'.edit-post-visual-editor h6, .wp-block-heading h6, .wp-block-freeform.block-library-rich-text__tinymce h6, .edit-post-visual-editor .wp-block-heading h6, .wp-block-heading h6.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'tablet' ),
				),
			);

			$css .= astra_parse_css( $tablet_css, '', '768' );

			$mobile_css = array(
				'.editor-post-title__block .editor-post-title__input' => array(
					'font-size' => astra_responsive_font( $single_post_title_font_size, 'mobile', 30 ),
				),

				// Heading H1 - H6 font size.
				'.edit-post-visual-editor h1, .wp-block-heading h1, .wp-block-freeform.block-library-rich-text__tinymce h1, .edit-post-visual-editor .wp-block-heading h1, .wp-block-heading h1.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'mobile', 30 ),
				),
				'.edit-post-visual-editor h2, .wp-block-heading h2, .wp-block-freeform.block-library-rich-text__tinymce h2, .edit-post-visual-editor .wp-block-heading h2, .wp-block-heading h2.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'mobile', 25 ),
				),
				'.edit-post-visual-editor h3, .wp-block-heading h3, .wp-block-freeform.block-library-rich-text__tinymce h3, .edit-post-visual-editor .wp-block-heading h3, .wp-block-heading h3.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'mobile', 20 ),
				),
				'.edit-post-visual-editor h4, .wp-block-heading h4, .wp-block-freeform.block-library-rich-text__tinymce h4, .edit-post-visual-editor .wp-block-heading h4, .wp-block-heading h4.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'mobile' ),
				),
				'.edit-post-visual-editor h5, .wp-block-heading h5, .wp-block-freeform.block-library-rich-text__tinymce h5, .edit-post-visual-editor .wp-block-heading h5, .wp-block-heading h5.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'mobile' ),
				),
				'.edit-post-visual-editor h6, .wp-block-heading h6, .wp-block-freeform.block-library-rich-text__tinymce h6, .edit-post-visual-editor .wp-block-heading h6, .wp-block-heading h6.editor-rich-text__tinymce, .editor-styles-wrapper .wp-block-uagb-advanced-heading h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'mobile' ),
				),
			);

			$css .= astra_parse_css( $mobile_css, '', '544' );

			if ( 'page-builder' === $container_layout ) {
				$page_builder_css = array(
					'.editor-post-title__block, .editor-default-block-appender, .editor-block-list__block' => array(
						'width'     => '100%',
						'max-width' => '100%',
					),
					'.block-editor-block-list__layout' => array(
						'padding-left'  => 0,
						'padding-right' => 0,
					),
					'.editor-block-list__block-edit'   => array(
						'padding-left'  => '20px',
						'padding-right' => '20px',
					),
					'.editor-block-list__block-edit .editor-block-list__block-edit' => array(
						'padding-left'  => '0',
						'padding-right' => '0',
					),
				);

				$css .= astra_parse_css( $page_builder_css );
			}

			if ( 'page-builder' === $container_layout || 'plain-container' === $container_layout ) {
				$aligned_full_content_css = array(
					'.block-editor-block-list__layout .block-editor-block-list__block[data-align="full"] > .block-editor-block-list__block-edit' => array(
						'margin-left'  => '0',
						'margin-right' => '0',
					),
					'.block-editor-block-list__layout .block-editor-block-list__block[data-align="full"]' => array(
						'margin-left'  => '0',
						'margin-right' => '0',
					),
				);

				$css .= astra_parse_css( $aligned_full_content_css );
			}

			if ( 'page-builder' === $container_layout ) {
				$full_width_streched_css = array(
					'.block-editor-block-list__layout' => array(
						'margin-left'  => '60px',
						'margin-right' => '60px',
					),
				);

				$css .= astra_parse_css( $full_width_streched_css );
			}

			if ( ( in_array( $pagenow, array( 'post-new.php' ) ) && ! isset( $post ) ) || 'content-boxed-container' === $container_layout || 'boxed-container' === $container_layout ) {
				$boxed_container = array(
					'.editor-writing-flow'       => array(
						'max-width'        => astra_get_css_value( $site_content_width - 56, 'px' ),
						'margin'           => '0 auto',
						'background-color' => '#fff',
					),
					'.gutenberg__editor'         => array(
						'background-color' => '#f5f5f5',
					),
					'.editor-block-list__layout, .editor-post-title' => array(
						'padding-top'    => 'calc( 5.34em - 19px)',
						'padding-bottom' => '5.34em',
						'padding-left'   => 'calc( 6.67em - 28px )',
						'padding-right'  => 'calc( 6.67em - 28px )',
					),
					'.editor-block-list__layout' => array(
						'padding-top' => '0',
					),
					'.editor-post-title'         => array(
						'padding-bottom' => '0',
					),
					'.editor-block-list__block'  => array(
						'max-width' => 'calc(' . astra_get_css_value( $site_content_width, 'px' ) . ' - 6.67em)',
					),
					'.editor-block-list__block[data-align=wide]' => array(
						'margin-left'  => '-20px',
						'margin-right' => '-20px',
					),
					'.editor-block-list__block[data-align=full]' => array(
						'margin-left'  => '-6.67em',
						'margin-right' => '-6.67em',
					),
					'.editor-block-list__layout .editor-block-list__block[data-align="full"], .editor-block-list__layout .editor-block-list__block[data-align="full"] > .editor-block-list__block-edit' => array(
						'margin-left'  => '0',
						'margin-right' => '0',
					),
				);

				$css .= astra_parse_css( $boxed_container );

			}

			return $css;
		}

	}

endif;
