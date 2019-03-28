<?php
/**
 * Gutenberg Editor CSS
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0
 */

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
			$single_post_title_font_size = astra_get_option( 'font-size-entry-title' );
			$body_font_family            = astra_body_font_family();
			$para_margin_bottom          = astra_get_option( 'para-margin-bottom' );
			$theme_color                 = astra_get_option( 'theme-color' );
			$link_color                  = astra_get_option( 'link-color', $theme_color );

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
				'html'                     => array(
					'font-size' => astra_get_font_css_value( (int) $body_font_size_desktop * 6.25, '%' ),
				),
				'a'                        => array(
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
				'.editor-block-list__block, .editor-post-title__block .editor-post-title__input, .edit-post-visual-editor h1, .edit-post-visual-editor h2, .edit-post-visual-editor h3, .edit-post-visual-editor h4, .edit-post-visual-editor h5, .edit-post-visual-editor h6' => array(
					'color' => esc_attr( $text_color ),
				),
				// Blockquote Text Color.
				'blockquote, blockquote a' => array(
					'color' => astra_adjust_brightness( $text_color, 75, 'darken' ),
				),
				'blockquote'               => array(
					'border-color' => astra_hex_to_rgba( $link_color, 0.05 ),
				),
				'.editor-block-list__block .wp-block-quote:not(.is-large):not(.is-style-large), .edit-post-visual-editor .wp-block-pullquote blockquote' => array(
					'border-color' => astra_hex_to_rgba( $link_color, 0.15 ),
				),
				// Heading H1 - H6 font size.
				'.edit-post-visual-editor h1, .wp-block-heading h1, .wp-block-freeform.block-library-rich-text__tinymce h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'desktop' ),
				),
				'.edit-post-visual-editor h2, .wp-block-heading h2, .wp-block-freeform.block-library-rich-text__tinymce h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'desktop' ),
				),
				'.edit-post-visual-editor h3, .wp-block-heading h3, .wp-block-freeform.block-library-rich-text__tinymce h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'desktop' ),
				),
				'.edit-post-visual-editor h4, .wp-block-heading h4, .wp-block-freeform.block-library-rich-text__tinymce h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'desktop' ),
				),
				'.edit-post-visual-editor h5, .wp-block-heading h5, .wp-block-freeform.block-library-rich-text__tinymce h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'desktop' ),
				),
				'.edit-post-visual-editor h6, .wp-block-heading h6, .wp-block-freeform.block-library-rich-text__tinymce h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'desktop' ),
				),
			);

			$css .= astra_parse_css( $desktop_css );

			$tablet_css = array(
				'.editor-post-title__block .editor-post-title__input' => array(
					'font-size' => astra_responsive_font( $single_post_title_font_size, 'tablet', 30 ),
				),
				// Heading H1 - H6 font size.
				'.edit-post-visual-editor h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'tablet', 30 ),
				),
				'.edit-post-visual-editor h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'tablet', 25 ),
				),
				'.edit-post-visual-editor h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'tablet', 20 ),
				),
				'.edit-post-visual-editor h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'tablet' ),
				),
				'.edit-post-visual-editor h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'tablet' ),
				),
				'.edit-post-visual-editor h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'tablet' ),
				),
			);

			$css .= astra_parse_css( $tablet_css, '', '768' );

			$mobile_css = array(
				'.editor-post-title__block .editor-post-title__input' => array(
					'font-size' => astra_responsive_font( $single_post_title_font_size, 'mobile', 30 ),
				),
				// Heading H1 - H6 font size.
				'.edit-post-visual-editor h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'mobile', 30 ),
				),
				'.edit-post-visual-editor h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'mobile', 25 ),
				),
				'.edit-post-visual-editor h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'mobile', 20 ),
				),
				'.edit-post-visual-editor h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'mobile' ),
				),
				'.edit-post-visual-editor h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'mobile' ),
				),
				'.edit-post-visual-editor h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'mobile' ),
				),
			);

			$css .= astra_parse_css( $mobile_css, '', '768' );

			if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) || 'content-boxed-container' === $container_layout || 'boxed-container' === $container_layout ) {
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
