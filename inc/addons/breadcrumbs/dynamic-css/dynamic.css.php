<?php
/**
 * Breadcrumbs - Dynamic CSS
 *
 * @package Astra
 */

/**
 * Breadcrumbs
 */
add_filter( 'wp_enqueue_scripts', 'astra_breadcrumb_section_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Breadcrumb.
 *
 * @since 1.7.0
 */
function astra_breadcrumb_section_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$breadcrumb_position = astra_get_option( 'breadcrumb-position', 'none' );

	if ( 'none' === $breadcrumb_position ) {
		return $dynamic_css;
	}

	/**
	 * Set CSS Params
	 */

	$default_color_array = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$breadcrumb_text_color      = astra_get_option( 'breadcrumb-text-color-responsive', $default_color_array );
	$breadcrumb_active_color    = astra_get_option( 'breadcrumb-active-color-responsive', $default_color_array );
	$breadcrumb_hover_color     = astra_get_option( 'breadcrumb-hover-color-responsive', $default_color_array );
	$breadcrumb_separator_color = astra_get_option( 'breadcrumb-separator-color', $default_color_array );
	$breadcrumb_bg_color        = astra_get_option( 'breadcrumb-bg-color', $default_color_array );

	$breadcrumb_font_family    = astra_get_option( 'breadcrumb-font-family' );
	$breadcrumb_font_weight    = astra_get_option( 'breadcrumb-font-weight' );
	$breadcrumb_font_size      = astra_get_option( 'breadcrumb-font-size' );
	$breadcrumb_line_height    = astra_get_option( 'breadcrumb-line-height' );
	$breadcrumb_text_transform = astra_get_option( 'breadcrumb-text-transform' );

	$breadcrumb_spacing = astra_get_option( 'breadcrumb-spacing' );

	$breadcrumb_alignment = astra_get_option( 'breadcrumb-alignment' );

	/**
	 * Generate dynamic CSS based on the Breadcrumb Source option selected from the customizer.
	 */
	$breadcrumb_source = astra_get_option( 'select-breadcrumb-source' );

	/**
	 * Generate Dynamic CSS
	 */

	$css = '';

	$css .= astra_parse_css(
		array(
			'.trail-items li::after' => array(
				'content' => '"' . astra_get_option( 'breadcrumb-separator', '»' ) . '"',
			),
		),
		'',
		''
	);

	/**
	 * Breadcrumb Colors & Typography
	 */

	if ( $breadcrumb_source && 'yoast-seo-breadcrumbs' == $breadcrumb_source ) {

		/* Yoast SEO Breadcrumb CSS - Desktop */
		$breadcrumbs_desktop = array(
			'.ast-breadcrumbs-wrapper a'                => array(
				'color' => esc_attr( $breadcrumb_text_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .breadcrumb_last' => array(
				'color' => esc_attr( $breadcrumb_active_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'          => array(
				'color' => esc_attr( $breadcrumb_hover_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper span'             => array(
				'color' => esc_attr( $breadcrumb_separator_color['desktop'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span' => array(
				'font-family'    => astra_get_font_family( $breadcrumb_font_family ),
				'font-weight'    => esc_attr( $breadcrumb_font_weight ),
				'font-size'      => astra_responsive_font( $breadcrumb_font_size, 'desktop' ),
				'line-height'    => esc_attr( $breadcrumb_line_height ),
				'text-transform' => esc_attr( $breadcrumb_text_transform ),
			),
		);

		/* Yoast SEO Breadcrumb CSS - Tablet */
		$breadcrumbs_tablet = array(
			'.ast-breadcrumbs-wrapper a'                => array(
				'color' => esc_attr( $breadcrumb_text_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .breadcrumb_last' => array(
				'color' => esc_attr( $breadcrumb_active_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'          => array(
				'color' => esc_attr( $breadcrumb_hover_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper span'             => array(
				'color' => esc_attr( $breadcrumb_separator_color['tablet'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'tablet' ),
			),
		);

		/* Yoast SEO Breadcrumb CSS - Mobile */
		$breadcrumbs_mobile = array(
			'.ast-breadcrumbs-wrapper a'                => array(
				'color' => esc_attr( $breadcrumb_text_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper .breadcrumb_last' => array(
				'color' => esc_attr( $breadcrumb_active_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'          => array(
				'color' => esc_attr( $breadcrumb_hover_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper span'             => array(
				'color' => esc_attr( $breadcrumb_separator_color['mobile'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'mobile' ),
			),
		);
	} elseif ( $breadcrumb_source && 'breadcrumb-navxt' == $breadcrumb_source ) {

		/* Breadcrumb NavXT CSS - Desktop */
		$breadcrumbs_desktop = array(
			'.ast-breadcrumbs-wrapper a'             => array(
				'color' => esc_attr( $breadcrumb_text_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .current-item' => array(
				'color' => esc_attr( $breadcrumb_active_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'       => array(
				'color' => esc_attr( $breadcrumb_hover_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .breadcrumbs'  => array(
				'color' => esc_attr( $breadcrumb_separator_color['desktop'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item' => array(
				'font-family'    => astra_get_font_family( $breadcrumb_font_family ),
				'font-weight'    => esc_attr( $breadcrumb_font_weight ),
				'font-size'      => astra_responsive_font( $breadcrumb_font_size, 'desktop' ),
				'line-height'    => esc_attr( $breadcrumb_line_height ),
				'text-transform' => esc_attr( $breadcrumb_text_transform ),
			),
		);

		/* Breadcrumb NavXT CSS - Tablet */
		$breadcrumbs_tablet = array(
			'.ast-breadcrumbs-wrapper a'             => array(
				'color' => esc_attr( $breadcrumb_text_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .current-item' => array(
				'color' => esc_attr( $breadcrumb_active_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'       => array(
				'color' => esc_attr( $breadcrumb_hover_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .breadcrumbs'  => array(
				'color' => esc_attr( $breadcrumb_separator_color['tablet'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'tablet' ),
			),
		);

		/* Breadcrumb NavXT CSS - Mobile */
		$breadcrumbs_mobile = array(
			'.ast-breadcrumbs-wrapper a'             => array(
				'color' => esc_attr( $breadcrumb_text_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper .current-item' => array(
				'color' => esc_attr( $breadcrumb_active_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'       => array(
				'color' => esc_attr( $breadcrumb_hover_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper .breadcrumbs'  => array(
				'color' => esc_attr( $breadcrumb_separator_color['mobile'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'mobile' ),
			),
		);
	} elseif ( $breadcrumb_source && 'rank-math' == $breadcrumb_source ) {

		/* Rank Math CSS - Desktop */
		$breadcrumbs_desktop = array(
			'.ast-breadcrumbs-wrapper a'          => array(
				'color' => esc_attr( $breadcrumb_text_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .last'      => array(
				'color' => esc_attr( $breadcrumb_active_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'    => array(
				'color' => esc_attr( $breadcrumb_hover_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .separator' => array(
				'color' => esc_attr( $breadcrumb_separator_color['desktop'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator' => array(
				'font-family'    => astra_get_font_family( $breadcrumb_font_family ),
				'font-weight'    => esc_attr( $breadcrumb_font_weight ),
				'font-size'      => astra_responsive_font( $breadcrumb_font_size, 'desktop' ),
				'line-height'    => esc_attr( $breadcrumb_line_height ),
				'text-transform' => esc_attr( $breadcrumb_text_transform ),
			),
		);

		/* Rank Math CSS - Tablet */
		$breadcrumbs_tablet = array(
			'.ast-breadcrumbs-wrapper a'          => array(
				'color' => esc_attr( $breadcrumb_text_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .last'      => array(
				'color' => esc_attr( $breadcrumb_active_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'    => array(
				'color' => esc_attr( $breadcrumb_hover_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .separator' => array(
				'color' => esc_attr( $breadcrumb_separator_color['tablet'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'tablet' ),
			),
		);

		/* Rank Math CSS - Mobile */
		$breadcrumbs_mobile = array(
			'.ast-breadcrumbs-wrapper a'          => array(
				'color' => esc_attr( $breadcrumb_text_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper .last'      => array(
				'color' => esc_attr( $breadcrumb_active_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper a:hover'    => array(
				'color' => esc_attr( $breadcrumb_hover_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper .separator' => array(
				'color' => esc_attr( $breadcrumb_separator_color['mobile'] ),
			),

			'.ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'mobile' ),
			),
		);
	} else {

		/* Default Breadcrumb CSS - Desktop */
		$breadcrumbs_desktop = array(
			'.ast-breadcrumbs-wrapper .trail-items a' => array(
				'color' => esc_attr( $breadcrumb_text_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items .trail-end' => array(
				'color' => esc_attr( $breadcrumb_active_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items a:hover' => array(
				'color' => esc_attr( $breadcrumb_hover_color['desktop'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items li::after' => array(
				'color' => esc_attr( $breadcrumb_separator_color['desktop'] ),
			),

			'.ast-breadcrumbs-wrapper, .ast-breadcrumbs-wrapper a' => array(
				'font-family'    => astra_get_font_family( $breadcrumb_font_family ),
				'font-weight'    => esc_attr( $breadcrumb_font_weight ),
				'font-size'      => astra_responsive_font( $breadcrumb_font_size, 'desktop' ),
				'line-height'    => esc_attr( $breadcrumb_line_height ),
				'text-transform' => esc_attr( $breadcrumb_text_transform ),
			),
		);

		/* Default Breadcrumb CSS - Tablet */
		$breadcrumbs_tablet = array(
			'.ast-breadcrumbs-wrapper .trail-items a' => array(
				'color' => esc_attr( $breadcrumb_text_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items .trail-end' => array(
				'color' => esc_attr( $breadcrumb_active_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items a:hover' => array(
				'color' => esc_attr( $breadcrumb_hover_color['tablet'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items li::after' => array(
				'color' => esc_attr( $breadcrumb_separator_color['tablet'] ),
			),

			'.ast-breadcrumbs-wrapper, .ast-breadcrumbs-wrapper a' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'tablet' ),
			),
		);

		/* Default Breadcrumb CSS - Mobile */
		$breadcrumbs_mobile = array(
			'.ast-breadcrumbs-wrapper .trail-items a' => array(
				'color' => esc_attr( $breadcrumb_text_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items .trail-end' => array(
				'color' => esc_attr( $breadcrumb_active_color['mobile'] ),
			),
			'.ast-breadcrumbs-wrapper .trail-items a:hover' => array(
				'color' => esc_attr( $breadcrumb_hover_color['mobile'] ),
			),

			'.ast-breadcrumbs-wrapper .trail-items li::after' => array(
				'color' => esc_attr( $breadcrumb_separator_color['mobile'] ),
			),

			'.ast-breadcrumbs-wrapper, .ast-breadcrumbs-wrapper a' => array(
				'font-size' => astra_responsive_font( $breadcrumb_font_size, 'mobile' ),
			),
		);
	}

	/* Breadcrumb CSS for Background Color */
	$breadcrumbs_desktop['.ast-breadcrumbs-wrapper, .main-header-bar.ast-header-breadcrumb'] = array(
		'background-color' => esc_attr( $breadcrumb_bg_color['desktop'] ),
	);
	$breadcrumbs_tablet['.ast-breadcrumbs-wrapper, .main-header-bar.ast-header-breadcrumb']  = array(
		'background-color' => esc_attr( $breadcrumb_bg_color['tablet'] ),
	);
	$breadcrumbs_mobile['.ast-breadcrumbs-wrapper, .main-header-bar.ast-header-breadcrumb']  = array(
		'background-color' => esc_attr( $breadcrumb_bg_color['mobile'] ),
	);

	/* Breadcrumb CSS for Spacing */
	if ( 'astra_header_markup_after' === $breadcrumb_position ) {
		$breadcrumbs_desktop['.main-header-bar.ast-header-breadcrumb, .ast-header-break-point .main-header-bar.ast-header-breadcrumb, .ast-header-break-point .header-main-layout-2 .main-header-bar.ast-header-breadcrumb, .ast-header-break-point .ast-mobile-header-stack .main-header-bar.ast-header-breadcrumb, .ast-default-menu-enable.ast-main-header-nav-open.ast-header-break-point .main-header-bar-wrap .main-header-bar.ast-header-breadcrumb, .ast-main-header-nav-open .main-header-bar-wrap .main-header-bar.ast-header-breadcrumb'] = array(
			'padding-top'    => astra_responsive_spacing( $breadcrumb_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $breadcrumb_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $breadcrumb_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $breadcrumb_spacing, 'left', 'desktop' ),
		);
		$breadcrumbs_tablet['.main-header-bar.ast-header-breadcrumb, .ast-header-break-point .main-header-bar.ast-header-breadcrumb, .ast-header-break-point .header-main-layout-2 .main-header-bar.ast-header-breadcrumb, .ast-header-break-point .ast-mobile-header-stack .main-header-bar.ast-header-breadcrumb, .ast-default-menu-enable.ast-main-header-nav-open.ast-header-break-point .main-header-bar-wrap .main-header-bar.ast-header-breadcrumb, .ast-main-header-nav-open .main-header-bar-wrap .main-header-bar.ast-header-breadcrumb']  = array(
			'padding-top'    => astra_responsive_spacing( $breadcrumb_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $breadcrumb_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $breadcrumb_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $breadcrumb_spacing, 'left', 'tablet' ),
		);
		$breadcrumbs_mobile['.main-header-bar.ast-header-breadcrumb, .ast-header-break-point .main-header-bar.ast-header-breadcrumb, .ast-header-break-point .header-main-layout-2 .main-header-bar.ast-header-breadcrumb, .ast-header-break-point .ast-mobile-header-stack .main-header-bar.ast-header-breadcrumb, .ast-default-menu-enable.ast-main-header-nav-open.ast-header-break-point .main-header-bar-wrap .main-header-bar.ast-header-breadcrumb, .ast-main-header-nav-open .main-header-bar-wrap .main-header-bar.ast-header-breadcrumb']  = array(
			'padding-top'    => astra_responsive_spacing( $breadcrumb_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $breadcrumb_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $breadcrumb_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $breadcrumb_spacing, 'left', 'mobile' ),
		);
	} else {
		$breadcrumbs_desktop['.ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .rank-math-breadcrumb, .ast-breadcrumbs-wrapper .ast-breadcrumbs'] = array(
			'padding-top'    => astra_responsive_spacing( $breadcrumb_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $breadcrumb_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $breadcrumb_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $breadcrumb_spacing, 'left', 'desktop' ),
		);
		$breadcrumbs_tablet['.ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .rank-math-breadcrumb, .ast-breadcrumbs-wrapper .ast-breadcrumbs']  = array(
			'padding-top'    => astra_responsive_spacing( $breadcrumb_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $breadcrumb_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $breadcrumb_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $breadcrumb_spacing, 'left', 'tablet' ),
		);
		$breadcrumbs_mobile['.ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .rank-math-breadcrumb, .ast-breadcrumbs-wrapper .ast-breadcrumbs']  = array(
			'padding-top'    => astra_responsive_spacing( $breadcrumb_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $breadcrumb_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $breadcrumb_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $breadcrumb_spacing, 'left', 'mobile' ),
		);
	}

	/* Breadcrumb CSS for Alignment */
	$breadcrumbs_desktop['.ast-breadcrumbs-wrapper'] = array(
		'text-align' => esc_attr( $breadcrumb_alignment ),
	);

	$css .= astra_parse_css( $breadcrumbs_desktop );
	$css .= astra_parse_css( $breadcrumbs_tablet, '', '768' );
	$css .= astra_parse_css( $breadcrumbs_mobile, '', '544' );

	/* Breadcrumb default CSS */
	$css .= astra_parse_css(
		array(
			'.ast-default-menu-enable.ast-main-header-nav-open.ast-header-break-point .main-header-bar.ast-header-breadcrumb, .ast-main-header-nav-open .main-header-bar.ast-header-breadcrumb' => array(
				'padding-top'    => '1em',
				'padding-bottom' => '1em',
			),
		),
		'',
		''
	);

	$css .= astra_parse_css(
		array(
			'.ast-breadcrumbs-wrapper .separator' => array(
				'display'     => 'inline-flex',
				'align-items' => 'center',
			),
		),
		'',
		''
	);

	$css .= astra_parse_css(
		array(
			'.ast-breadcrumbs-wrapper' => array(
				'line-height' => '1.4',
			),
		),
		'',
		''
	);

	$css .= astra_parse_css(
		array(
			'.ast-breadcrumbs-wrapper .rank-math-breadcrumb p' => array(
				'margin-bottom' => '0px',
			),
		),
		'',
		''
	);

	$css .= astra_parse_css(
		array(
			'.ast-breadcrumbs-wrapper' => array(
				'display' => 'block',
				'width'   => '100%',
			),
		),
		'',
		''
	);

	$css .= astra_parse_css(
		array(
			'.breadcrumbs .trail-browse, .breadcrumbs .trail-items, .breadcrumbs .trail-items li' => array(
				'display'     => 'inline-block',
				'margin'      => '0',
				'padding'     => '0',
				'border'      => 'none',
				'background'  => 'inherit',
				'text-indent' => '0',
			),
			'.breadcrumbs .trail-browse'          => array(
				'font-size'   => 'inherit',
				'font-style'  => 'inherit',
				'font-weight' => 'inherit',
				'color'       => 'inherit',
			),
			'.breadcrumbs .trail-items'           => array(
				'list-style' => 'none',
			),
			'.trail-items li::after'              => array(
				'padding' => '0 0.3em',
			),
			'.trail-items li:last-of-type::after' => array(
				'display' => 'none',
			),
		),
		'',
		''
	);

	$dynamic_css .= $css;

	wp_add_inline_style( 'astra-theme-css', $dynamic_css );

}
