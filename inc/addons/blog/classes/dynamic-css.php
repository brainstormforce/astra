<?php
/**
 * Blog - Dynamic CSS
 *
 * @package Astra
 */

add_filter( 'astra_dynamic_theme_css', 'astra_blog_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_blog_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {
	/**
	 * - Variable Declaration.
	 */
	$blog_post_inside_spacing    = astra_get_option( 'blog-post-inside-spacing' );
	$blog_featured_image_padding = astra_get_option( 'blog-featured-image-padding' );
	$container_layout            = astra_get_option( 'site-content-layout' );
	$blog_grid                   = astra_get_option( 'blog-grid' );
	$blog_layout                 = astra_get_option( 'blog-layout' );
	$margin_space                = astra_get_option( 'blog-space-bet-posts' ) ? '-1em' : '0';
	$spacing_desktop             = array();


	if ( astra_apply_blog_grid_css() ) {
		$spacing_desktop = array(
			'.ast-separate-container .ast-blog-grid.ast-separate-posts' => array(
				'--astPostRowGap'    => '29px',
				'--astGridColumnGap' => '29px',
			),
		);
	}

	if ( ! defined( 'ASTRA_EXT_VER' ) ) {
		$spacing_desktop['.ast-page-builder-template .ast-article-post .entry-header'] = array(
			'margin'  => 'auto auto 1em auto',
			'padding' => '0',
		);
	}

	if ( Astra_Blog_Markup::is_blog_layout_1() ) {
		$blog_background_css = array(
			'.ast-separate-container .blog-layout-1' => array(
				'background-color' => '#ffffff',
			),
		);
		$dynamic_css        .= astra_parse_css( $blog_background_css );
	}

	if ( $blog_grid > 1 ) {
		if ( ! defined( 'ASTRA_EXT_VER' ) ) {
			$addon_compatibility_css = array(
				'.ast-plain-container .ast-grid-' . $blog_grid . ' > .site-main > .ast-blog-grid, .ast-page-builder-template .ast-grid-' . $blog_grid . ' > .site-main > .ast-blog-grid' => array(
					'margin-left'  => '-1em',
					'margin-right' => '-1em',
				),
			);
			$dynamic_css            .= astra_parse_css( $addon_compatibility_css );
		}
		// Blog Grid Inside Spacing.
		$spacing_desktop[ '.ast-separate-container .ast-grid-' . $blog_grid . ' .blog-layout-1' ] = array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ),
		);
		$spacing_desktop['.ast-separate-container .ast-grid-2 .ast-article-post, .ast-separate-container .ast-grid-3 .ast-article-post, .ast-separate-container .ast-grid-4 .ast-article-post'] = array(
			'background' => 'transparent',   
		);
		$spacing_desktop[ 'ast-separate-container .ast-grid-' . $blog_grid . ' .blog-layout-1' ] = array(
			'padding' => '1.5em',
		);

		$dynamic_css .= Astra_Enqueue_Scripts::trim_css( 
			'@media (max-width: 1200px) {
				.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post:nth-child(2n+0),
				.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post:nth-child(2n+1){
					padding: 0;  
			} }' 
		);
	} elseif ( ! defined( 'ASTRA_EXT_VER' ) ) {
		$spacing_desktop['.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post'] = array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ),
		);
	}
	$dynamic_css .= astra_parse_css( $spacing_desktop );

	/**
	 * Blog Pro Featured Image padding
	 */

	if ( $blog_featured_image_padding && ! Astra_Ext_Extension::is_active( 'spacing' ) ) {
		$remove_featured_image_margin_top = array(
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on,.ast-desktop.ast-separate-container .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on' => array(
				'margin-top' => ( isset( $blog_post_inside_spacing['desktop']['top'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'desktop' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left'  => ( isset( $blog_post_inside_spacing['desktop']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ) ) : '' ),
				'margin-right' => ( isset( $blog_post_inside_spacing['desktop']['right'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'desktop' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left' => ( isset( $blog_post_inside_spacing['desktop']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ) ) : '' ),
			),
		);
		$dynamic_css                     .= astra_parse_css( $remove_featured_image_margin_top );

		$remove_featured_image_margin_top_tablet = array(
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb' => array(
				'margin-top' => ( isset( $blog_post_inside_spacing['tablet']['top'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'tablet' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left'  => ( isset( $blog_post_inside_spacing['tablet']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ) ) : '' ),
				'margin-right' => ( isset( $blog_post_inside_spacing['tablet']['right'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'tablet' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left' => ( isset( $blog_post_inside_spacing['tablet']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ) ) : '' ),
			),
		);
		$dynamic_css                            .= astra_parse_css( $remove_featured_image_margin_top_tablet, '', astra_get_tablet_breakpoint() );

		$remove_featured_image_margin_top_mobile = array(
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content' => array(
				'margin-top' => ( isset( $blog_post_inside_spacing['mobile']['top'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'mobile' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left'  => ( isset( $blog_post_inside_spacing['mobile']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ) ) : '' ),
				'margin-right' => ( isset( $blog_post_inside_spacing['mobile']['right'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'mobile' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left' => ( isset( $blog_post_inside_spacing['mobile']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ) ) : '' ),
			),
		);
		$dynamic_css                            .= astra_parse_css( $remove_featured_image_margin_top_mobile, '', astra_get_mobile_breakpoint() );
	}

	if ( $blog_grid > 1 ) {
		$spacing_tablet = array(
			// Blog Grid Inside Spacing.
			'.ast-separate-container .ast-grid-' . $blog_grid . ' .blog-layout-1' => array(
				'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'tablet' ),
				'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'tablet' ),
				'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'tablet' ),
				'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ),
			),
		);
		$dynamic_css   .= astra_parse_css( $spacing_tablet, '', astra_get_tablet_breakpoint() );

		$spacing_mobile = array(
			// Blog Grid Inside Spacing.
			'.ast-separate-container .ast-grid-' . $blog_grid . ' .blog-layout-1' => array(
				'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'mobile' ),
				'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'mobile' ),
				'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'mobile' ),
				'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ),
			),
		);
		$dynamic_css   .= astra_parse_css( $spacing_mobile, '', astra_get_mobile_breakpoint() );

		$tablet_max_css = array(
			'.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post' => array(
				'width' => '100%',
			),
			'.ast-blog-grid' => array(
				'--astGridColumns' => '1',
			),
		);
		/* Parse CSS from array() -> max-width: (tablet-breakpoint)px */
		$dynamic_css .= astra_parse_css( $tablet_max_css, '', astra_get_tablet_breakpoint() );

		if ( ! astra_apply_blog_grid_css() ) {
			$space_between_post = array(
				'.ast-separate-container .ast-separate-posts.ast-article-post' => array(
					'margin-bottom' => '2em',
				),
			);
			$dynamic_css       .= astra_parse_css( $space_between_post );

			$tablet_min_lang_direction_css = array(
				'.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post.ast-separate-posts:nth-child(2n+0),.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post.ast-separate-posts:nth-child(2n+1)' => array(
					'padding' => '0 1em 0',
				),
				'.ast-separate-container .ast-grid-' . $blog_grid . ' > .site-main > .ast-row' => array(
					'margin-left'  => esc_attr( $margin_space ),
					'margin-right' => esc_attr( $margin_space ),
					'display'      => 'flex',
					'flex-flow'    => 'row wrap',
					'align-items'  => 'stretch',
				),
			);

			/* Parse CSS from array() -> min-width: (tablet-breakpoint + 1)px */
			$dynamic_css .= astra_parse_css( $tablet_min_lang_direction_css, astra_get_tablet_breakpoint( '', 1 ) );
		}

		$tablet_min_css = array(
			// Single Post author info.
			'.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post.ast-separate-posts' => array(
				'border-bottom' => 0,
			),

			'.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post' => array(
				'display' => 'flex',
				'padding' => 0,
			),
		);

		if ( ! astra_apply_blog_grid_css() ) {
			$tablet_min_css[ '.ast-plain-container .ast-grid-' . $blog_grid . ' > .site-main > .ast-row' ] = array(
				'margin-left'  => '-1em',
				'margin-right' => '-1em',
				'display'      => 'flex',
				'flex-flow'    => 'row wrap',
				'align-items'  => 'stretch',
			);
			$tablet_min_css[ '.ast-separate-container .ast-grid-' . $blog_grid . ' > .site-main > .ast-row:before, .ast-separate-container .ast-grid-' . $blog_grid . ' > .site-main > .ast-row:after,.ast-plain-container .ast-grid-' . $blog_grid . ' > .site-main > .ast-row:before, .ast-plain-container .ast-grid-' . $blog_grid . ' > .site-main > .ast-row:after, .ast-page-builder-template .ast-grid-' . $blog_grid . ' > .site-main > .ast-row:before, .ast-page-builder-template .ast-grid-' . $blog_grid . ' > .site-main > .ast-row:after' ] = array(
				'flex-basis' => 0,
				'width'      => 0,
			);
		}

		if ( 'plain-container' === $container_layout ) {

			$tablet_min_css[ '.ast-plain-container .ast-grid-' . $blog_grid . ' .ast-article-post' ]            = array(
				'display' => 'flex',
			);
			$tablet_min_css[ '.ast-plain-container .ast-grid-' . $blog_grid . ' .ast-article-post:last-child' ] = array(
				'margin-bottom' => '2.5em',
			);
		} elseif ( 'page-builder' == $container_layout ) {
			$tablet_min_css[ '.ast-page-builder-template .ast-grid-' . $blog_grid . ' .ast-article-post' ]            = array(
				'display' => 'flex',
			);
			$tablet_min_css[ '.ast-page-builder-template .ast-grid-' . $blog_grid . ' .ast-article-post:last-child' ] = array(
				'margin-bottom' => '2.5em',
			);
		}

		/* Parse CSS from array() -> min-width: (tablet-breakpoint + 1)px */
		$dynamic_css .= astra_parse_css( $tablet_min_css, astra_get_tablet_breakpoint( '', 1 ) );

		$mobile_css = array(
			'.ast-separate-container .ast-grid-' . $blog_grid . ' .ast-article-post .blog-layout-1' => array(
				'padding' => '1.33333em 1em',
			),
			'.ast-blog-grid' => array(
				'--astGridColumns' => '1',
			),
		);
		/* Parse CSS from array() -> max-width: (mobile-breakpoint)px */
		$dynamic_css .= astra_parse_css( $mobile_css, '', astra_get_mobile_breakpoint() );
	} else {
		if ( ! defined( 'ASTRA_EXT_VER' ) ) {
			$single_column_margin_blog_tablet = array(
				'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post' => array(
					'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'tablet' ),
					'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'tablet' ),
					'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'tablet' ),
					'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ),
				),
			);
			$dynamic_css                     .= astra_parse_css( $single_column_margin_blog_tablet, '', astra_get_tablet_breakpoint() );
			
			$single_column_margin_blog_mobile = array(
				'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post' => array(
					'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'mobile' ),
					'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'mobile' ),
					'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'mobile' ),
					'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ),
				),
			);
			$dynamic_css                     .= astra_parse_css( $single_column_margin_blog_mobile, '', astra_get_mobile_breakpoint() );
		}
		$dynamic_css .= astra_parse_css(
			array(
				'.ast-separate-container .ast-grid-1 .blog-layout-1' => array(
					'padding' => '0',
				),
			)
		);
	}
	return $dynamic_css;
}
