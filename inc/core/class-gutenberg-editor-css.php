<?php
/**
 * Gutenberg Editor CSS
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
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

			$site_content_width          = astra_get_option( 'site-content-width', 1200 );
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
			if ( 'default' === $container_layout ) {
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
				'html'                                => array(
					'font-size' => astra_get_font_css_value( (int) $body_font_size_desktop * 6.25, '%' ),
				),
				'#wpwrap .edit-post-visual-editor a'  => array(
					'color' => esc_attr( $link_color ),
				),
				// Global selection CSS.
				'#wpwrap .edit-post-visual-editor ::selection,.editor-block-list__layout .editor-block-list__block.is-multi-selected .editor-block-list__block-edit:before' => array(
					'background-color' => esc_attr( $theme_color ),
				),
				'#wpwrap .edit-post-visual-editor ::selection,.editor-block-list__layout .editor-block-list__block.is-multi-selected .editor-block-list__block-edit' => array(
					'color' => esc_attr( $highlight_theme_color ),
				),

				'.ast-separate-container #wpwrap .edit-post-visual-editor' => astra_get_background_obj( $box_bg_obj ),
				'#wpwrap .edit-post-visual-editor .editor-post-title__block,#wpwrap .edit-post-visual-editor .editor-default-block-appender,#wpwrap .edit-post-visual-editor .editor-block-list__block' => array(
					'max-width' => astra_get_css_value( $site_content_width + 40, 'px' ),
				),
				'#wpwrap .edit-post-visual-editor .editor-block-list__block[data-align=wide]' => array(
					'max-width' => astra_get_css_value( $site_content_width + 40 + 200, 'px' ),
				),
				'#wpwrap .edit-post-visual-editor .editor-post-title__block .editor-post-title__input,  #wpwrap .edit-post-visual-editor h1, #wpwrap .edit-post-visual-editor h2, #wpwrap .edit-post-visual-editor h3, #wpwrap .edit-post-visual-editor h4, #wpwrap .edit-post-visual-editor h5, #wpwrap .edit-post-visual-editor h6' => array(
					'font-family'    => astra_get_css_value( $headings_font_family, 'font' ),
					'font-weight'    => astra_get_css_value( $headings_font_weight, 'font' ),
					'text-transform' => esc_attr( $headings_text_transform ),
				),
				'#wpwrap .edit-post-visual-editor p,#wpwrap .edit-post-visual-editor .editor-block-list__block p' => array(
					'font-size' => astra_responsive_font( $body_font_size, 'desktop' ),
				),
				'#wpwrap .edit-post-visual-editor p,#wpwrap .edit-post-visual-editor .editor-block-list__block p, #wpwrap .edit-post-visual-editor .wp-block-latest-posts a,#wpwrap .edit-post-visual-editor .editor-default-block-appender textarea.editor-default-block-appender__content, #wpwrap .edit-post-visual-editor .editor-block-list__block' => array(
					'font-family'    => astra_get_font_family( $body_font_family ),
					'font-weight'    => esc_attr( $body_font_weight ),
					'font-size'      => astra_responsive_font( $body_font_size, 'desktop' ),
					'line-height'    => esc_attr( $body_line_height ),
					'text-transform' => esc_attr( $body_text_transform ),
					'margin-bottom'  => astra_get_css_value( $para_margin_bottom, 'em' ),
				),
				'#wpwrap .edit-post-visual-editor .editor-post-title__block .editor-post-title__input' => array(
					'font-family' => ( 'inherit' === $headings_font_family ) ? astra_get_font_family( $body_font_family ) : astra_get_font_family( $headings_font_family ),
					'font-size'   => astra_responsive_font( $single_post_title_font_size, 'desktop' ),
				),
				'#wpwrap .edit-post-visual-editor .editor-block-list__block, #wpwrap .edit-post-visual-editor .editor-post-title__block .editor-post-title__input, #wpwrap .edit-post-visual-editor h1,#wpwrap .edit-post-visual-editor h2,#wpwrap .edit-post-visual-editor h3,#wpwrap .edit-post-visual-editor h4,#wpwrap .edit-post-visual-editor h5,#wpwrap .edit-post-visual-editor h6' => array(
					'color' => esc_attr( $text_color ),
				),
				// Blockquote Text Color.
				'#wpwrap .edit-post-visual-editor blockquote, #wpwrap .edit-post-visual-editor blockquote a' => array(
					'color' => astra_adjust_brightness( $text_color, 75, 'darken' ),
				),
				'#wpwrap .edit-post-visual-editor blockquote' => array(
					'border-color' => astra_hex_to_rgba( $link_color, 0.05 ),
				),
				// Heading H1 - H6 font size.
				'#wpwrap .edit-post-visual-editor h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'desktop' ),
				),
				'#wpwrap .edit-post-visual-editor h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'desktop' ),
				),
				'#wpwrap .edit-post-visual-editor h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'desktop' ),
				),
				'#wpwrap .edit-post-visual-editor h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'desktop' ),
				),
				'#wpwrap .edit-post-visual-editor h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'desktop' ),
				),
				'#wpwrap .edit-post-visual-editor h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'desktop' ),
				),
			);

			$css .= astra_parse_css( $desktop_css );

			$tablet_css = array(
				'#wpwrap .edit-post-visual-editor .editor-post-title__block .editor-post-title__input' => array(
					'font-size' => astra_responsive_font( $single_post_title_font_size, 'tablet', 30 ),
				),
				// Heading H1 - H6 font size.
				'#wpwrap .edit-post-visual-editor h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'tablet', 30 ),
				),
				'#wpwrap .edit-post-visual-editor h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'tablet', 25 ),
				),
				'#wpwrap .edit-post-visual-editor h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'tablet', 20 ),
				),
				'#wpwrap .edit-post-visual-editor h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'tablet' ),
				),
				'#wpwrap .edit-post-visual-editor h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'tablet' ),
				),
				'#wpwrap .edit-post-visual-editor h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'tablet' ),
				),
			);

			$css .= astra_parse_css( $tablet_css, '', '768' );

			$mobile_css = array(
				'#wpwrap .edit-post-visual-editor .editor-post-title__block .editor-post-title__input' => array(
					'font-size' => astra_responsive_font( $single_post_title_font_size, 'mobile', 30 ),
				),
				// Heading H1 - H6 font size.
				'#wpwrap .edit-post-visual-editor h1' => array(
					'font-size' => astra_responsive_font( $heading_h1_font_size, 'mobile', 30 ),
				),
				'#wpwrap .edit-post-visual-editor h2' => array(
					'font-size' => astra_responsive_font( $heading_h2_font_size, 'mobile', 25 ),
				),
				'#wpwrap .edit-post-visual-editor h3' => array(
					'font-size' => astra_responsive_font( $heading_h3_font_size, 'mobile', 20 ),
				),
				'#wpwrap .edit-post-visual-editor h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'mobile' ),
				),
				'#wpwrap .edit-post-visual-editor h4' => array(
					'font-size' => astra_responsive_font( $heading_h4_font_size, 'mobile' ),
				),
				'#wpwrap .edit-post-visual-editor h5' => array(
					'font-size' => astra_responsive_font( $heading_h5_font_size, 'mobile' ),
				),
				'#wpwrap .edit-post-visual-editor h6' => array(
					'font-size' => astra_responsive_font( $heading_h6_font_size, 'mobile' ),
				),
			);

			$css .= astra_parse_css( $mobile_css, '', '768' );

			if ( in_array( $pagenow, array( 'post-new.php' ) ) || 'content-boxed-container' === $container_layout || 'boxed-container' === $container_layout ) {
				$boxed_container = array(
					'#wpwrap .edit-post-visual-editor .editor-writing-flow' => array(
						'max-width'        => 'calc( ' . astra_get_css_value( $site_content_width, 'px' ) . ' - 40px )',
						'margin'           => '0 auto',
						'background-color' => '#fff',
						'overflow'         => 'hidden',
					),
					'#wpwrap .gutenberg__editor'         => array(
						'background-color' => '#f5f5f5',
					),
					'#wpwrap .editor-block-list__layout, #wpwrap .editor-post-title' => array(
						'padding-top'    => '5.34em',
						'padding-bottom' => '5.34em',
						'padding-left'   => 'calc( 6.67em - 14px )',
						'padding-right'  => 'calc( 6.67em - 14px )',
					),
					'#wpwrap .editor-block-list__layout' => array(
						'padding-top' => '0',
					),
					'#wpwrap .editor-post-title'         => array(
						'padding-bottom' => '0',
					),
					'#wpwrap .edit-post-visual-editor .editor-block-list__block' => array(
						'max-width' => 'calc(' . astra_get_css_value( $site_content_width, 'px' ) . ' - 6.67em)',
					),
					'#wpwrap .edit-post-visual-editor .editor-block-list__block[data-align=wide]' => array(
						'max-width'    => 'calc(' . astra_get_css_value( $site_content_width, 'px' ) . ' - 6.67em + 40px)',
						'margin-left'  => '-20px',
						'margin-right' => '-20px',
					),
					'#wpwrap .edit-post-visual-editor .editor-block-list__block[data-align=full]' => array(
						'margin-left'  => '-6.67em',
						'margin-right' => '-6.67em',
					),
				);

				$css .= astra_parse_css( $boxed_container );

			}

			return $css;
		}

	}

endif;
