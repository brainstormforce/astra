<?php
/**
 * Astra Builder Options default values.
 *
 * @package astra-builder
 */

add_filter( 'astra_theme_defaults', 'astra_hf_builder_customizer_defaults' );

/**
 * Return defaults for Builder Options.
 *
 * @param array $defaults exsiting options array.
 * @return array
 */
function astra_hf_builder_customizer_defaults( $defaults ) {

	/**
	 * Header Builder - Desktop Defaults.
	 */
	$defaults['header-desktop-items'] = array(
		'above'   =>
			array(
				'above_left'         => array(),
				'above_left_center'  => array(),
				'above_center'       => array(),
				'above_right_center' => array(),
				'above_right'        => array(),
			),
		'primary' =>
			array(
				'primary_left'         => array( 'logo' ),
				'primary_left_center'  => array(),
				'primary_center'       => array(),
				'primary_right_center' => array(),
				'primary_right'        => array( 'menu-1' ),
			),
		'below'   =>
			array(
				'below_left'         => array(),
				'below_left_center'  => array(),
				'below_center'       => array(),
				'below_right_center' => array(),
				'below_right'        => array(),
			),
	);

	/**
	 * Header Builder - Mobile Defaults.
	 */
	$defaults['header-mobile-items'] = array(
		'popup'   => array( 'popup_content' => array( 'menu-1' ) ),
		'above'   =>
			array(
				'above_left'   => array(),
				'above_center' => array(),
				'above_right'  => array(),
			),
		'primary' =>
			array(
				'primary_left'   => array( 'logo' ),
				'primary_center' => array(),
				'primary_right'  => array( 'mobile-trigger' ),
			),
		'below'   =>
			array(
				'below_left'   => array(),
				'below_center' => array(),
				'below_right'  => array(),
			),
	);

	/**
	 * Primary Header Defaults.
	 */
	$defaults['hb-header-main-layout-width'] = 'content';
	$defaults['hb-header-height']            = 50;
	$defaults['hb-header-main-sep']          = 1;
	$defaults['hb-header-main-sep-color']    = '#eaeaea';
	$defaults['hb-header-main-menu-align']   = 'inline';
	$defaults['hb-header-bg-obj-responsive'] = array(
		'desktop' => array(
			'background-color'      => '#ffffff',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);

	$defaults['hb-header-spacing'] = array(
		'desktop'      => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'tablet'       => array(
			'top'    => '1.5',
			'right'  => '',
			'bottom' => '1.5',
			'left'   => '',
		),
		'mobile'       => array(
			'top'    => '1',
			'right'  => '',
			'bottom' => '1',
			'left'   => '',
		),
		'desktop-unit' => 'px',
		'tablet-unit'  => 'em',
		'mobile-unit'  => 'em',
	);

	/**
	 * Above Header Defaults.
	 */
	$defaults['hba-header-layout']                  = 'above-header-layout-1';
	$defaults['hba-header-height']                  = 50;
	$defaults['hba-header-separator']               = 1;
	$defaults['hba-header-bottom-border-color']     = '#eaeaea';
	$defaults['hba-header-bg-obj-responsive']       = array(
		'desktop' => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);
	$defaults['hba-header-text-color-responsive']   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['hba-header-link-color-responsive']   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['hba-header-link-h-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['hba-header-spacing']                 = array(
		'desktop'      => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'tablet'       => array(
			'top'    => '0',
			'right'  => '',
			'bottom' => '0',
			'left'   => '',
		),
		'mobile'       => array(
			'top'    => '0.5',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'em',
	);

	/**
	 * Logo defaults.
	 */
	$defaults['ast-header-responsive-logo-width'] = array(
		'desktop' => 150,
		'tablet'  => 120,
		'mobile'  => 100,
	);

	/**
	 * Above Header Defaults.
	 */
	$defaults['hbb-header-layout']              = 'below-header-layout-1';
	$defaults['hbb-header-height']              = 60;
	$defaults['hbb-header-separator']           = 1;
	$defaults['hbb-header-bottom-border-color'] = '#eaeaea';
	$defaults['hbb-header-bg-obj-responsive']   = array(
		'desktop' => array(
			'background-color'      => '#eeeeee',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);
	$defaults['hbb-header-spacing']             = array(
		'desktop'      => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'tablet'       => array(
			'top'    => '1',
			'right'  => '',
			'bottom' => '1',
			'left'   => '',
		),
		'mobile'       => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'desktop-unit' => 'px',
		'tablet-unit'  => 'em',
		'mobile-unit'  => 'px',
	);

	for ( $index = 1; $index <= Astra_Constants::$num_of_header_button; $index++ ) {

		$_prefix = 'button' . $index;

		$defaults[ 'header-' . $_prefix . '-text' ]           = __( 'Button', 'astra' );
		$defaults[ 'header-' . $_prefix . '-link-option' ]    = array(
			'url'      => apply_filters( 'astra_site_url', 'https://www.wpastra.com' ),
			'new_tab'  => false,
			'link_rel' => '',
		);
		$defaults[ 'header-' . $_prefix . '-font-family' ]    = 'inherit';
		$defaults[ 'header-' . $_prefix . '-font-weight' ]    = 'inherit';
		$defaults[ 'header-' . $_prefix . '-text-transform' ] = '';
		$defaults[ 'header-' . $_prefix . '-line-height' ]    = '';
		$defaults[ 'header-' . $_prefix . '-font-size' ]      = array(
			'desktop'      => '',
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		$defaults[ 'header-' . $_prefix . '-text-color' ]     = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
		$defaults[ 'header-' . $_prefix . '-back-color' ]     = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
		$defaults[ 'header-' . $_prefix . '-text-h-color' ]   = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
		$defaults[ 'header-' . $_prefix . '-back-h-color' ]   = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
		$defaults[ 'header-' . $_prefix . '-padding' ]        = array(
			'desktop' => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'tablet'  => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'mobile'  => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
		);
		$defaults[ 'header-' . $_prefix . '-border-size' ]    = array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		);
		$defaults[ 'header-' . $_prefix . '-border-color' ]   = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
		$defaults[ 'header-' . $_prefix . '-border-radius' ]  = '';
	}

	for ( $index = 1; $index <= Astra_Constants::$num_of_header_html; $index++ ) {

		$_section = 'section-hb-html-' . $index;

		$defaults[ 'header-html-' . $index ] = __( 'Insert HTML text here.', 'astra' );

		/**
		 * HTML Components - Typography.
		 */
		$defaults[ 'font-size-' . $_section ]      = array(
			'desktop'      => 15,
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		$defaults[ 'font-weight-' . $_section ]    = 'inherit';
		$defaults[ 'font-family-' . $_section ]    = 'inherit';
		$defaults[ 'line-height-' . $_section ]    = '';
		$defaults[ 'text-transform-' . $_section ] = '';
	}

	for ( $index = 1; $index <= Astra_Constants::$num_of_header_menu; $index++ ) {
		$_prefix = 'menu' . $index;

		// Specify all the default values for Menu from here.
		$defaults[ 'header-' . $_prefix . '-bg-color' ]   = '';
		$defaults[ 'header-' . $_prefix . '-color' ]      = '';
		$defaults[ 'header-' . $_prefix . '-h-bg-color' ] = '';
		$defaults[ 'header-' . $_prefix . '-h-color' ]    = '';
		$defaults[ 'header-' . $_prefix . '-a-bg-color' ] = '';
		$defaults[ 'header-' . $_prefix . '-a-color' ]    = '';

		$defaults[ 'header-' . $_prefix . '-bg-obj-responsive' ] = array(
			'desktop' => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
			),
			'tablet'  => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
			),
			'mobile'  => array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
			),
		);

		$defaults[ 'header-' . $_prefix . '-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-h-bg-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-h-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-a-bg-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-a-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		/**
		 * Submenu
		 */
		$defaults[ 'header-' . $_prefix . '-submenu-bg-color' ]     = '';
		$defaults[ 'header-' . $_prefix . '-submenu-color' ]        = '';
		$defaults[ 'header-' . $_prefix . '-submenu-h-bg-color' ]   = '';
		$defaults[ 'header-' . $_prefix . '-submenu-h-color' ]      = '';
		$defaults[ 'header-' . $_prefix . '-submenu-a-bg-color' ]   = '';
		$defaults[ 'header-' . $_prefix . '-submenu-a-color' ]      = '';
		$defaults[ 'header-' . $_prefix . '-submenu-item-border' ]  = true;
		$defaults[ 'header-' . $_prefix . '-submenu-item-b-color' ] = '#eaeaea';

		$defaults[ 'header-' . $_prefix . '-submenu-bg-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-submenu-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-submenu-h-bg-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-submenu-h-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-submenu-a-bg-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$defaults[ 'header-' . $_prefix . '-submenu-a-color-responsive' ] = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		/**
		 * Mega Menu Color.
		 */
		$defaults[ 'header-' . $_prefix . '-header-megamenu-heading-color' ]   = '';
		$defaults[ 'header-' . $_prefix . '-header-megamenu-heading-h-color' ] = '';

		/**
		 * Menu - Typography.
		 */
		$defaults[ 'header-' . $_prefix . '-font-size' ]      = array(
			'desktop'      => '',
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		$defaults[ 'header-' . $_prefix . '-font-weight' ]    = 'inherit';
		$defaults[ 'header-' . $_prefix . '-font-family' ]    = 'inherit';
		$defaults[ 'header-' . $_prefix . '-text-transform' ] = '';
		$defaults[ 'header-' . $_prefix . '-line-height' ]    = '';

		/**
		 * Sub Menu - Typography.
		 */
		$defaults[ 'header-font-size-' . $_prefix . '-sub-menu' ]      = array(
			'desktop'      => '',
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		$defaults[ 'header-font-family-' . $_prefix . '-sub-menu' ]    = 'inherit';
		$defaults[ 'header-font-weight-' . $_prefix . '-sub-menu' ]    = 'inherit';
		$defaults[ 'header-text-transform-' . $_prefix . '-sub-menu' ] = '';
		$defaults[ 'header-line-height-' . $_prefix . '-sub-menu' ]    = '';

		/**
		 * Mega Menu Typography.
		 */
		$defaults[ 'header-' . $_prefix . '-header-megamenu-heading-font-family' ]    = 'inherit';
		$defaults[ 'header-' . $_prefix . '-header-megamenu-heading-font-weight' ]    = '700';
		$defaults[ 'header-' . $_prefix . '-header-megamenu-heading-text-transform' ] = '';
		$defaults[ 'header-' . $_prefix . '-header-megamenu-heading-font-size' ]      = array(
			'desktop'      => '',
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);

		/**
		 * Menu Spacing.
		 */
		$defaults[ 'header-' . $_prefix . '-spacing' ]                       = array(
			'desktop'      => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'tablet'       => array(
				'top'    => '0',
				'right'  => '20',
				'bottom' => '0',
				'left'   => '20',
			),
			'mobile'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		$defaults[ 'header-' . $_prefix . '-submenu-spacing' ]               = array(
			'desktop'      => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'tablet'       => array(
				'top'    => '0',
				'right'  => '20',
				'bottom' => '0',
				'left'   => '30',
			),
			'mobile'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);
		$defaults[ 'header-' . $_prefix . '-header-megamenu-heading-space' ] = array(
			'desktop'      => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'tablet'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'mobile'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);

		/**
		 * Header Types - Defaults
		 */
		$defaults['transparent-header-main-sep']       = '';
		$defaults['transparent-header-main-sep-color'] = '';

	}

	/**
	 * Header > Sticky Defaults.
	 */
	$defaults['sticky-header-on-devices'] = 'desktop';
	$defaults['sticky-header-style']      = 'none';

	/**
	 * Footer Builder - Desktop Defaults.
	 */
	$defaults['footer-desktop-items'] = array(
		'above'   =>
			array(
				'above_1' => array(),
				'above_2' => array(),
				'above_3' => array(),
				'above_4' => array(),
				'above_5' => array(),
			),
		'primary' =>
			array(
				'primary_1' => array(),
				'primary_2' => array(),
				'primary_3' => array(),
				'primary_4' => array(),
				'primary_5' => array(),
			),
		'below'   =>
			array(
				'below_1' => array( 'copyright' ),
				'below_2' => array(),
				'below_3' => array(),
				'below_4' => array(),
				'below_5' => array(),
			),
	);

	/**
	 * Above Footer Defaults.
	 */
	$defaults['hba-footer-height'] = 60;
	$defaults['hba-footer-column'] = '2';
	$defaults['hba-footer-layout'] = array(
		'desktop' => '2-equal',
		'tablet'  => '2-equal',
		'mobile'  => '2-equal',
	);

	/**
	 * Footer - Defaults
	 */
	$defaults['hba-footer-bg-obj-responsive'] = array(
		'desktop' => array(
			'background-color'      => '#eeeeee',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);
	$defaults['hbb-footer-bg-obj-responsive'] = array(
		'desktop' => array(
			'background-color'      => '#eeeeee',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);
	$defaults['hb-footer-bg-obj-responsive']  = array(
		'desktop' => array(
			'background-color'      => '#f9f9f9',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);

	/**
	 * Below Footer Defaults.
	 */
	$defaults['hbb-footer-height'] = 80;
	$defaults['hbb-footer-column'] = '1';
	$defaults['hbb-footer-layout'] = array(
		'desktop' => 'full',
		'tablet'  => 'full',
		'mobile'  => 'full',
	);

	$defaults['hba-footer-layout-width'] = 'content';
	$defaults['hb-footer-layout-width']  = 'content';
	$defaults['hbb-footer-layout-width'] = 'content';

	$defaults['hba-footer-vertical-alignment'] = 'center';
	$defaults['hb-footer-vertical-alignment']  = 'center';
	$defaults['hbb-footer-vertical-alignment'] = 'center';

	$defaults['footer-bg-obj-responsive'] = array(
		'desktop' => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);

	/**
	 * Primary Footer Defaults.
	 */
	$defaults['hb-footer-column']              = '3';
	$defaults['hb-footer-separator']           = 1;
	$defaults['hb-footer-bottom-border-color'] = '#e6e6e6';
	$defaults['hb-footer-layout']              = array(
		'desktop' => '3-equal',
		'tablet'  => '3-equal',
		'mobile'  => '3-equal',
	);

	$defaults['hb-footer-main-sep']       = 1;
	$defaults['hb-footer-main-sep-color'] = '#e6e6e6';

	/**
	 * Footer Copyright.
	 */
	$defaults['footer-copyright-editor']              = 'Copyright [copyright] [current_year] [site_title] | [theme_author]';
	$defaults['footer-copyright-color']               = '#3a3a3a';
	$defaults['line-height-section-footer-copyright'] = 2;
	$defaults['footer-copyright-alignment']           = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);

	$defaults['footer-menu-alignment'] = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);

	$defaults['footer-social-alignment'] = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);

	/**
	 * Footer Below Padding.
	 */
	$defaults['section-below-footer-builder-padding'] = array(
		'desktop' => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'tablet'  => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'mobile'  => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
	);

	/**
	 * Search.
	 */
	$defaults['header-search-box-type']   = 'slide-search';
	$defaults['header-search-icon-space'] = array(
		'desktop' => 20,
		'tablet'  => 20,
		'mobile'  => 20,
	);

	/**
	 * Header > Social Icon Defaults.
	 */
	$defaults['header-social-icons-icon-space']    = '';
	$defaults['header-social-icons-icon-bg-space'] = '';
	$defaults['header-social-icons-icon-size']     = 18;
	$defaults['header-social-icons-icon-radius']   = '';
	$defaults['header-social-icons-color']         = '';
	$defaults['header-social-icons-h-color']       = '';
	$defaults['header-social-icons-bg-color']      = '';
	$defaults['header-social-icons-bg-h-color']    = '';
	$defaults['header-social-label-toggle']        = false;
	$defaults['header-social-color-type']          = 'custom';
	$defaults['footer-social-color-type']          = 'custom';

	$defaults['header-social-icons'] = array(
		'items' =>
		array(
			array(
				'id'      => 'facebook',
				'enabled' => true,
				'source'  => 'icon',
				'url'     => '',
				'imageid' => '',
				'width'   => 24,
				'icon'    => 'facebook',
				'label'   => 'Facebook',
			),
			array(
				'id'      => 'twitter',
				'enabled' => true,
				'source'  => 'icon',
				'url'     => '',
				'imageid' => '',
				'width'   => 24,
				'icon'    => 'twitter',
				'label'   => 'Twitter',
			),
			array(
				'id'      => 'instagram',
				'enabled' => true,
				'source'  => 'icon',
				'url'     => '',
				'imageid' => '',
				'width'   => 24,
				'icon'    => 'instagram',
				'label'   => 'Instagram',
			),
		),
	);

	/**
	 * Footer > Social Icon Defaults.
	 */
	$defaults['footer-social-icons-icon-space']    = '';
	$defaults['footer-social-icons-icon-bg-space'] = '';
	$defaults['footer-social-icons-icon-size']     = 18;
	$defaults['footer-social-icons-icon-radius']   = '';
	$defaults['footer-social-icons-color']         = '';
	$defaults['footer-social-icons-h-color']       = '';
	$defaults['footer-social-icons-bg-color']      = '';
	$defaults['footer-social-icons-bg-h-color']    = '';

	$defaults['footer-social-icons'] = array(
		'items' =>
		array(
			array(
				'id'      => 'facebook',
				'enabled' => true,
				'source'  => 'icon',
				'url'     => '',
				'imageid' => '',
				'width'   => 24,
				'icon'    => 'facebook',
				'label'   => 'Facebook',
			),
			array(
				'id'      => 'twitter',
				'enabled' => true,
				'source'  => 'icon',
				'url'     => '',
				'imageid' => '',
				'width'   => 24,
				'icon'    => 'twitter',
				'label'   => 'Twitter',
			),
			array(
				'id'      => 'instagram',
				'enabled' => true,
				'source'  => 'icon',
				'url'     => '',
				'imageid' => '',
				'width'   => 24,
				'icon'    => 'instagram',
				'label'   => 'Instagram',
			),
		),
	);

	/**
	 * Off-Canvas defaults.
	 */
	$defaults['off-canvas-layout']      = 'side-panel';
	$defaults['off-canvas-slide']       = 'left';
	$defaults['off-canvas-background']  = array(
		'background-color'      => '',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'auto',
		'background-attachment' => 'scroll',
	);
	$defaults['off-canvas-close-color'] = '#3a3a3a';
	$defaults['mobile-header-type']     = 'dropdown';

	$defaults['footer-menu-layout'] = 'horizontal';

	$defaults['footer-menu-bg-obj-responsive'] = array(
		'desktop' => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'tablet'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
		'mobile'  => array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		),
	);

	$defaults['footer-menu-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['footer-menu-h-bg-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['footer-menu-h-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['footer-menu-a-bg-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['footer-menu-a-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['footer-menu-font-size']      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults['footer-menu-font-weight']    = 'inherit';
	$defaults['footer-menu-font-family']    = 'inherit';
	$defaults['footer-menu-text-transform'] = '';
	$defaults['footer-menu-line-height']    = '';

	$defaults['footer-menu-spacing'] = array(
		'desktop'      => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'tablet'       => array(
			'top'    => '0',
			'right'  => '20',
			'bottom' => '0',
			'left'   => '20',
		),
		'mobile'       => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		),
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);

	// Mobile Trigger defaults.

	$defaults['mobile-header-toggle-btn-color']       = '#0274be';
	$defaults['mobile-header-toggle-btn-bg-color']    = '#eeeeee';
	$defaults['header-trigger-icon']                  = 'menu';
	$defaults['mobile-header-toggle-icon-size']       = 20;
	$defaults['mobile-header-toggle-btn-style']       = 'minimal';
	$defaults['mobile-header-toggle-btn-border-size'] = array(
		'top'    => 1,
		'right'  => 1,
		'bottom' => 1,
		'left'   => 1,
	);
	$defaults['mobile-header-toggle-border-color']    = '#eeeeee';

	// HTML Footer defaults.
	for ( $index = 1; $index <= Astra_Constants::$num_of_footer_html; $index++ ) {

		$defaults[ 'footer-html-' . $index ] = __( 'Insert HTML text here.', 'astra' );

		$defaults[ 'font-size-section-fb-html-' . $index ] = array(
			'desktop'      => 15,
			'tablet'       => '',
			'mobile'       => '',
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);

		$defaults[ 'footer-html-' . $index . '-alignment' ] = array(
			'desktop' => 'center',
			'tablet'  => 'center',
			'mobile'  => 'center',
		);
	}

	return $defaults;
}
