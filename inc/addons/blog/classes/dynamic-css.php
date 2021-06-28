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
	$blog_post_inside_spacing = astra_get_option( 'blog-post-inside-spacing' );
	$blog_grid                = astra_get_option( 'blog-grid' );
	$blog_space_bet_posts     = astra_get_option( 'blog-space-bet-posts' );
	$content_bg_obj           = astra_get_option( 'content-bg-obj-responsive' );

	$spacing_desktop = array(
		// Blog Grid Inside Spacing.
		'.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ),
		),
		'.ast-separate-container .ast-separate-posts.ast-article-post' => array(
			'margin-bottom' => '2em',
		),
	);
	$dynamic_css    .= astra_parse_css( $spacing_desktop );
	
	// Blog Pro Layout Colors.
	if ( Astra_Blog_Markup::is_blog_layout_1() ) {
		$blog_background_css = array(
			'.ast-separate-container .blog-layout-1' => array(
				'background-color' => '#ffffff',
			),
		);
		$dynamic_css        .= astra_parse_css( $blog_background_css );
	}

	if ( $blog_grid > 1 && $blog_space_bet_posts ) {
		$space_between_css = array(
			'.ast-separate-container .ast-grid-2 .ast-article-post, .ast-separate-container .ast-grid-3 .ast-article-post, .ast-separate-container .ast-grid-4 .ast-article-post' => array(
				'background' => 'transparent',
			),
		);
		$dynamic_css      .= astra_parse_css( $space_between_css );
	}
	$spacing_tablet = array(
		// Blog Grid Inside Spacing.
		'.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ),
		),
	);
	$dynamic_css   .= astra_parse_css( $spacing_tablet, '', astra_get_tablet_breakpoint() );

	$spacing_mobile = array(
		// Blog Grid Inside Spacing.
		'.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .ast-article-post .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .ast-article-post .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ),
		),
	);
	$dynamic_css   .= astra_parse_css( $spacing_mobile, '', astra_get_mobile_breakpoint() );

	$tablet_max_css = array(
		// Single Post author info.
		'.ast-grid-2 .ast-article-post, .ast-grid-3 .ast-article-post, .ast-grid-4 .ast-article-post' => array(
			'width' => '100%',
		),
	);

	/* Parse CSS from array() -> max-width: (tablet-breakpoint)px */
	$dynamic_css .= astra_parse_css( $tablet_max_css, '', astra_get_tablet_breakpoint() );

	$tablet_min_css = array(
		// Single Post author info.
		'.ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts, .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts, .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts' => array(
			'border-bottom' => 0,
		),
		'.ast-separate-container .ast-grid-2 > .site-main > .ast-row, .ast-separate-container .ast-grid-3 > .site-main > .ast-row, .ast-separate-container .ast-grid-4 > .site-main > .ast-row' => array(
			'margin-left'  => '-1em',
			'margin-right' => '-1em',
			'display'      => 'flex',
			'flex-flow'    => 'row wrap',
			'align-items'  => 'stretch',
		),
		'.ast-separate-container .ast-grid-2 > .site-main > .ast-row:before, .ast-separate-container .ast-grid-2 > .site-main > .ast-row:after, .ast-separate-container .ast-grid-3 > .site-main > .ast-row:before, .ast-separate-container .ast-grid-3 > .site-main > .ast-row:after, .ast-separate-container .ast-grid-4 > .site-main > .ast-row:before, .ast-separate-container .ast-grid-4 > .site-main > .ast-row:after,.ast-plain-container .ast-grid-2 > .site-main > .ast-row:before, .ast-plain-container .ast-grid-2 > .site-main > .ast-row:after, .ast-plain-container .ast-grid-3 > .site-main > .ast-row:before, .ast-plain-container .ast-grid-3 > .site-main > .ast-row:after, .ast-plain-container .ast-grid-4 > .site-main > .ast-row:before, .ast-plain-container .ast-grid-4 > .site-main > .ast-row:after, .ast-page-builder-template .ast-grid-2 > .site-main > .ast-row:before, .ast-page-builder-template .ast-grid-2 > .site-main > .ast-row:after, .ast-page-builder-template .ast-grid-3 > .site-main > .ast-row:before, .ast-page-builder-template .ast-grid-3 > .site-main > .ast-row:after, .ast-page-builder-template .ast-grid-4 > .site-main > .ast-row:before, .ast-page-builder-template .ast-grid-4 > .site-main > .ast-row:after' => array(
			'flex-basis' => 0,
			'width'      => 0,
		),
		'.ast-separate-container .ast-grid-2 .ast-article-post, .ast-separate-container .ast-grid-3 .ast-article-post, .ast-separate-container .ast-grid-4 .ast-article-post' => array(
			'display' => 'flex',
			'padding' => 0,
		),
		'.ast-plain-container .ast-grid-2 > .site-main > .ast-row, .ast-plain-container .ast-grid-3 > .site-main > .ast-row, .ast-plain-container .ast-grid-4 > .site-main > .ast-row, .ast-page-builder-template .ast-grid-2 > .site-main > .ast-row, .ast-page-builder-template .ast-grid-3 > .site-main > .ast-row, .ast-page-builder-template .ast-grid-4 > .site-main > .ast-row' => array(
			'margin-left'  => '-1em',
			'margin-right' => '-1em',
			'display'      => 'flex',
			'flex-flow'    => 'row wrap',
			'align-items'  => 'stretch',
		),
		'.ast-plain-container .ast-grid-2 .ast-article-post, .ast-plain-container .ast-grid-3 .ast-article-post, .ast-plain-container .ast-grid-4 .ast-article-post, .ast-page-builder-template .ast-grid-2 .ast-article-post, .ast-page-builder-template .ast-grid-3 .ast-article-post, .ast-page-builder-template .ast-grid-4 .ast-article-post' => array(
			'display' => 'flex',
		),
		'.ast-plain-container .ast-grid-2 .ast-article-post:last-child, .ast-plain-container .ast-grid-3 .ast-article-post:last-child, .ast-plain-container .ast-grid-4 .ast-article-post:last-child, .ast-page-builder-template .ast-grid-2 .ast-article-post:last-child, .ast-page-builder-template .ast-grid-3 .ast-article-post:last-child, .ast-page-builder-template .ast-grid-4 .ast-article-post:last-child' => array(
			'margin-bottom' => '2.5em',
		),
	);

	/* Parse CSS from array() -> min-width: (tablet-breakpoint + 1)px */
	$dynamic_css .= astra_parse_css( $tablet_min_css, astra_get_tablet_breakpoint( '', 1 ) );
	
	$tablet_min_lang_direction_css = array(
		'.ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+1)' => array(
			'padding' => '0 1em 0',
		),
	);
	/* Parse CSS from array() -> min-width: (tablet-breakpoint + 1)px */
	$dynamic_css .= astra_parse_css( $tablet_min_lang_direction_css, astra_get_tablet_breakpoint( '', 1 ) );

	$mobile_css = array(
		'.ast-separate-container .ast-grid-2 .ast-article-post .blog-layout-1,.ast-separate-container .ast-grid-3 .ast-article-post .blog-layout-1, .ast-separate-container .ast-grid-4 .ast-article-post .blog-layout-1' => array(
			'padding' => '1.33333em 1em',
		),
	);
	/* Parse CSS from array() -> max-width: (mobile-breakpoint)px */
	$dynamic_css .= astra_parse_css( $mobile_css, '', astra_get_mobile_breakpoint() );

	return $dynamic_css;
}
