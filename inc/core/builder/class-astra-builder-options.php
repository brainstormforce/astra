<?php
/**
 * Astra Builder Options default values.
 *
 * @package astra-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		'popup'   => array( 'popup_content' => array( 'mobile-menu' ) ),
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
		'popup'   => array( 'popup_content' => array( 'mobile-menu' ) ),
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
	$defaults['hb-header-height']            = array(
		'desktop' => 70,
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['hb-stack']                    = array(
		'desktop' => 'stack',
		'tablet'  => 'stack',
		'mobile'  => 'stack',
	);

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
	$defaults['hba-header-height']                  = array(
		'desktop' => 50,
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['hba-stack']                          = array(
		'desktop' => 'stack',
		'tablet'  => 'stack',
		'mobile'  => 'stack',
	);
	$defaults['hba-header-separator']               = 1;
	$defaults['hba-header-bottom-border-color']     = '#eaeaea';
	$defaults['hba-header-bg-obj-responsive']       = array(
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
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	/**
	 * Below Header Defaults.
	 */
	$defaults['hbb-header-layout'] = 'below-header-layout-1';
	$defaults['hbb-header-height'] = array(
		'desktop' => 60,
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['hbb-stack']         = array(
		'desktop' => 'stack',
		'tablet'  => 'stack',
		'mobile'  => 'stack',
	);

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


	$margin_defaults = array(
		'section-footer-builder-layout-padding',
		'section-footer-builder-layout-margin',
		'section-above-header-builder-padding',
		'section-above-header-builder-margin',
		'section-below-header-builder-padding',
		'section-below-header-builder-margin',
		'section-header-mobile-trigger-margin',
		'section-primary-header-builder-padding',
		'section-primary-header-builder-margin',
		'title_tagline-margin',
		'section-header-search-margin',
		'header-account-margin',
		'header-mobile-menu-menu-spacing',
		'section-header-mobile-menu-margin',
		'section-above-footer-builder-padding',
		'section-above-footer-builder-margin',
		'section-below-footer-builder-margin',
		'section-footer-copyright-margin',
		'section-footer-menu-margin',
		'section-primary-footer-builder-padding',
		'section-primary-footer-builder-margin',
	);

	foreach ( $margin_defaults as $margin_default ) {
		$defaults[ $margin_default ] = Astra_Builder_Helper::$default_responsive_spacing;
	}

	for ( $index = 1; $index <= Astra_Builder_Helper::$component_limit; $index++ ) {

		$defaults = prepare_button_defaults( $defaults, $index );
		$defaults = prepare_html_defaults( $defaults, $index );
		$defaults = prepare_social_icon_defaults( $defaults, $index );
		$defaults = prepare_widget_defaults( $defaults, $index );
		$defaults = prepare_menu_defaults( $defaults, $index );
		$defaults = prepare_divider_defaults( $defaults, $index );
	}

	/**
	 * Header Types - Defaults
	 */
	$defaults['transparent-header-main-sep']       = '';
	$defaults['transparent-header-main-sep-color'] = '';

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
		'mobile'  => 'full',
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
	 * Header Margin defaults.
	 */
	$defaults['section-header-builder-layout-margin'] = array(
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

	$defaults['hba-footer-vertical-alignment'] = 'flex-start';
	$defaults['hb-footer-vertical-alignment']  = 'flex-start';
	$defaults['hbb-footer-vertical-alignment'] = 'flex-start';

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
		'mobile'  => 'full',
	);

	$defaults['hb-footer-main-sep']       = 1;
	$defaults['hb-footer-main-sep-color'] = '#e6e6e6';

	/**
	 * Footer Copyright.
	 */
	$defaults['footer-copyright-editor']                 = 'Copyright [copyright] [current_year] [site_title] | Powered by [theme_author]';
	$defaults['footer-copyright-color']                  = '#3a3a3a';
	$defaults['line-height-section-footer-copyright']    = 2;
	$defaults['footer-copyright-alignment']              = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);
	$defaults['font-size-section-footer-copyright']      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults['font-weight-section-footer-copyright']    = 'inherit';
	$defaults['font-family-section-footer-copyright']    = 'inherit';
	$defaults['text-transform-section-footer-copyright'] = '';
	$defaults['line-height-section-footer-copyright']    = '';

	$defaults['footer-menu-alignment'] = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);

	/**
	 * Footer Below Padding.
	 */
	$defaults['section-below-footer-builder-padding'] = array(
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
	 * Search.
	 */
	$defaults['header-search-icon-space'] = array(
		'desktop' => 18,
		'tablet'  => 18,
		'mobile'  => 18,
	);

	$defaults['header-search-icon-color'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	/**
	 * Transparent Header > Component Configs
	 */
	$defaults['transparent-header-social-icons-color']      = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['transparent-header-social-icons-h-color']    = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['transparent-header-social-icons-bg-color']   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['transparent-header-social-icons-bg-h-color'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['transparent-header-html-text-color']   = '';
	$defaults['transparent-header-html-link-color']   = '';
	$defaults['transparent-header-html-link-h-color'] = '';

	$defaults['transparent-header-widget-title-color']   = '';
	$defaults['transparent-header-widget-content-color'] = '';
	$defaults['transparent-header-widget-link-color']    = '';
	$defaults['transparent-header-widget-link-h-color']  = '';

	$defaults['transparent-header-button-text-color']   = '';
	$defaults['transparent-header-button-text-h-color'] = '';
	$defaults['transparent-header-button-bg-color']     = '';
	$defaults['transparent-header-button-bg-h-color']   = '';

	/**
	 * Off-Canvas defaults.
	 */
	$defaults['off-canvas-layout']                  = 'side-panel';
	$defaults['off-canvas-slide']                   = 'right';
	$defaults['header-builder-menu-toggle-target']  = 'icon';
	$defaults['header-offcanvas-content-alignment'] = 'flex-start';
	$defaults['off-canvas-background']              = array(
		'background-color'      => '#ffffff',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'auto',
		'background-attachment' => 'scroll',
	);
	$defaults['off-canvas-close-color']             = '#3a3a3a';
	$defaults['mobile-header-type']                 = 'dropdown';
	$defaults['off-canvas-inner-spacing']           = '';
	$defaults['footer-menu-layout']                 = array(
		'desktop' => 'horizontal',
		'tablet'  => 'vertical',
		'mobile'  => 'vertical',
	);

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

	$defaults['footer-main-menu-spacing'] = array(
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
	$defaults['header-trigger-icon']                  = 'menu';
	$defaults['mobile-header-toggle-icon-size']       = 20;
	$defaults['mobile-header-toggle-btn-style']       = 'minimal';
	$defaults['mobile-header-toggle-btn-border-size'] = array(
		'top'    => 1,
		'right'  => 1,
		'bottom' => 1,
		'left'   => 1,
	);
	$defaults['mobile-header-toggle-border-radius']   = 2;

	/**
	 * Mobile trigger - Label Typography.
	 */
	$defaults['mobile-header-label-font-family']    = 'inherit';
	$defaults['mobile-header-label-font-weight']    = 'inherit';
	$defaults['mobile-header-label-text-transform'] = '';
	$defaults['mobile-header-label-line-height']    = '';
	$defaults['mobile-header-label-font-size']      = '';

	/**
	* Mobile Menu
	*/

	// Specify all the default values for Menu from here.
	$defaults['header-mobile-menu-bg-color']   = '';
	$defaults['header-mobile-menu-color']      = '';
	$defaults['header-mobile-menu-h-bg-color'] = '';
	$defaults['header-mobile-menu-h-color']    = '';
	$defaults['header-mobile-menu-a-bg-color'] = '';
	$defaults['header-mobile-menu-a-color']    = '';

	$defaults['header-mobile-menu-bg-obj-responsive'] = array(
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

	$defaults['header-mobile-menu-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['header-mobile-menu-h-bg-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['header-mobile-menu-h-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['header-mobile-menu-a-bg-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['header-mobile-menu-a-color-responsive'] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	$defaults['header-mobile-menu-submenu-container-animation'] = 'fade';

		/**
		 * Submenu
		*/
	$defaults['header-mobile-menu-submenu-item-border']  = false;
	$defaults['header-mobile-menu-submenu-item-b-size']  = '1';
	$defaults['header-mobile-menu-submenu-item-b-color'] = '#eaeaea';
	$defaults['header-mobile-menu-submenu-border']       = array(
		'top'    => 2,
		'bottom' => 0,
		'left'   => 0,
		'right'  => 0,
	);


		/**
		 * Menu - Typography.
		*/
	$defaults['header-mobile-menu-font-size']      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults['header-mobile-menu-font-weight']    = 'inherit';
	$defaults['header-mobile-menu-font-family']    = 'inherit';
	$defaults['header-mobile-menu-text-transform'] = '';
	$defaults['header-mobile-menu-line-height']    = '';

	/**
	 * Woo-Cart.
	 */
	$defaults['woo-header-cart-icon-style']             = 'outline';
	$defaults['header-woo-cart-icon-color']             = '';
	$defaults['transparent-header-woo-cart-icon-color'] = '';
	$defaults['woo-header-cart-icon-radius']            = 3;
	$defaults['woo-header-cart-total-display']          = true;
	$defaults['woo-header-cart-title-display']          = true;

	// Cart tray > General Color styles.
	$defaults['header-woo-cart-text-color']       = '';
	$defaults['header-woo-cart-link-color']       = '';
	$defaults['header-woo-cart-background-color'] = '';
	$defaults['header-woo-cart-separator-color']  = '';
	$defaults['header-woo-cart-link-hover-color'] = '';

	// Cart tray > Cart Button styles.
	$defaults['header-woo-cart-btn-text-color']       = '';
	$defaults['header-woo-cart-btn-background-color'] = '';
	$defaults['header-woo-cart-btn-text-hover-color'] = '';
	$defaults['header-woo-cart-btn-bg-hover-color']   = '';

	// Cart tray > Checkout Button styles.
	$defaults['header-woo-checkout-btn-text-color']       = '';
	$defaults['header-woo-checkout-btn-background-color'] = '';
	$defaults['header-woo-checkout-btn-text-hover-color'] = '';
	$defaults['header-woo-checkout-btn-bg-hover-color']   = '';

	/**
	 * EDD-Cart.
	*/
	$defaults['edd-header-cart-icon-style']             = 'outline';
	$defaults['edd-header-cart-icon-color']             = '';
	$defaults['edd-header-cart-icon-radius']            = 3;
	$defaults['transparent-header-edd-cart-icon-color'] = '';
	$defaults['edd-header-cart-total-display']          = true;
	$defaults['edd-header-cart-title-display']          = true;

	// Cart tray > General Color styles.
	$defaults['header-edd-cart-text-color']       = '';
	$defaults['header-edd-cart-link-color']       = '';
	$defaults['header-edd-cart-background-color'] = '';
	$defaults['header-edd-cart-separator-color']  = '';

	// Cart tray > Checkout Button styles.
	$defaults['header-edd-checkout-btn-text-color']       = '';
	$defaults['header-edd-checkout-btn-background-color'] = '';
	$defaults['header-edd-checkout-btn-text-hover-color'] = '';
	$defaults['header-edd-checkout-btn-bg-hover-color']   = '';

	/**
	 * Account element.
	*/
	$defaults['header-account-type']            = 'default';
	$defaults['header-account-login-style']     = 'icon';
	$defaults['header-account-action-type']     = 'link';
	$defaults['header-account-link-type']       = 'default';
	$defaults['header-account-logout-style']    = 'icon';
	$defaults['header-account-logged-out-text'] = __( 'Log In', 'astra' );
	$defaults['header-account-logged-in-text']  = __( 'My Account', 'astra' );
	$defaults['header-account-logout-action']   = 'link';
	$defaults['header-account-image-width']     = array(
		'desktop' => '40',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults['header-account-icon-size']       = array(
		'desktop' => 18,
		'tablet'  => 18,
		'mobile'  => 18,
	);

	$defaults['header-account-icon-color'] = '';

	$defaults['header-account-login-link'] = array(
		'url'      => '',
		'new_tab'  => false,
		'link_rel' => '',
	);

	$defaults['header-account-logout-link'] = array(
		'url'      => esc_url( wp_login_url() ),
		'new_tab'  => false,
		'link_rel' => '',
	);

	$defaults['font-size-section-header-account'] = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);

	$defaults['header-account-type-text-color'] = '';
	$defaults['header-account-woo-menu']        = false;

	$defaults['cloned-component-track'] = Astra_Builder_Helper::$component_count_array;

	return $defaults;
}

/**
 * Prepare Divider Defaults.
 *
 * @param array   $defaults defaults.
 * @param integer $index index.
 */
function prepare_divider_defaults( $defaults, $index ) {

	$defaults[ 'section-hb-divider-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;
	$defaults[ 'section-fb-divider-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;


	return $defaults;
}

/**
 * Prepare Button Defaults.
 *
 * @param array   $defaults defaults.
 * @param integer $index index.
 */
function prepare_button_defaults( $defaults, $index ) {

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

	$defaults[ 'section-hb-button-' . $index . '-padding' ]   = Astra_Builder_Helper::$default_responsive_spacing;
	$defaults[ 'section-hb-button-' . $index . '-margin' ]    = Astra_Builder_Helper::$default_responsive_spacing;
	$defaults[ 'sticky-header-button' . $index . '-padding' ] = Astra_Builder_Helper::$default_responsive_spacing;


	$_prefix = 'button' . $index;

	$defaults[ 'footer-' . $_prefix . '-text' ]           = __( 'Button', 'astra' );
	$defaults[ 'footer-' . $_prefix . '-link-option' ]    = array(
		'url'      => apply_filters( 'astra_site_url', 'https://www.wpastra.com' ),
		'new_tab'  => false,
		'link_rel' => '',
	);
	$defaults[ 'footer-' . $_prefix . '-font-family' ]    = 'inherit';
	$defaults[ 'footer-' . $_prefix . '-font-weight' ]    = 'inherit';
	$defaults[ 'footer-' . $_prefix . '-text-transform' ] = '';
	$defaults[ 'footer-' . $_prefix . '-line-height' ]    = '';
	$defaults[ 'footer-' . $_prefix . '-font-size' ]      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults[ 'footer-' . $_prefix . '-text-color' ]     = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-' . $_prefix . '-back-color' ]     = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-' . $_prefix . '-text-h-color' ]   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-' . $_prefix . '-back-h-color' ]   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-' . $_prefix . '-padding' ]        = array(
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
	$defaults[ 'footer-' . $_prefix . '-border-size' ]    = array(
		'top'    => '',
		'right'  => '',
		'bottom' => '',
		'left'   => '',
	);
	$defaults[ 'footer-' . $_prefix . '-border-color' ]   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-' . $_prefix . '-border-radius' ]  = '';
	$defaults[ 'footer-button-' . $index . '-alignment' ] = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);

	$defaults[ 'section-fb-button-' . $index . '-padding' ] = Astra_Builder_Helper::$default_responsive_spacing;
	$defaults[ 'section-fb-button-' . $index . '-margin' ]  = Astra_Builder_Helper::$default_responsive_spacing;

	return $defaults;
}

/**
 * Prepare HTML Defaults.
 *
 * @param array   $defaults defaults.
 * @param integer $index index.
 */
function prepare_html_defaults( $defaults, $index ) {

	$_section = 'section-hb-html-' . $index;

	$defaults[ 'header-html-' . $index ]                  = __( 'Insert HTML text here.', 'astra' );
	$defaults[ 'header-html-' . $index . 'color' ]        = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'header-html-' . $index . 'link-color' ]   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'header-html-' . $index . 'link-h-color' ] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'font-size-' . $_section ]                 = array(
		'desktop'      => 15,
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults[ 'font-weight-' . $_section ]               = 'inherit';
	$defaults[ 'font-family-' . $_section ]               = 'inherit';
	$defaults[ 'line-height-' . $_section ]               = '';
	$defaults[ 'text-transform-' . $_section ]            = '';

	$defaults[ 'section-hb-html-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;



	$_section = 'section-fb-html-' . $index;

	$defaults[ 'footer-html-' . $index ]                  = __( 'Insert HTML text here.', 'astra' );
	$defaults[ 'footer-html-' . $index . 'color' ]        = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-html-' . $index . 'link-color' ]   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-html-' . $index . 'link-h-color' ] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'font-size-' . $_section ]                 = array(
		'desktop'      => 15,
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults[ 'footer-html-' . $index . '-alignment' ]   = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);
	$defaults[ 'font-size-' . $_section ]                 = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults[ 'font-weight-' . $_section ]               = 'inherit';
	$defaults[ 'font-family-' . $_section ]               = 'inherit';
	$defaults[ 'text-transform-' . $_section ]            = '';
	$defaults[ 'line-height-' . $_section ]               = '';

	$defaults[ 'section-fb-html-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;

	return $defaults;
}

/**
 * Prepare Social Icon Defaults.
 *
 * @param array   $defaults defaults.
 * @param integer $index index.
 */
function prepare_social_icon_defaults( $defaults, $index ) {

	$defaults[ 'header-social-' . $index . '-space' ]          = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'header-social-' . $index . '-bg-space' ]       = '';
	$defaults[ 'header-social-' . $index . '-size' ]           = array(
		'desktop' => 18,
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'header-social-' . $index . '-radius' ]         = '';
	$defaults[ 'header-social-' . $index . '-color' ]          = '';
	$defaults[ 'header-social-' . $index . '-h-color' ]        = '';
	$defaults[ 'header-social-' . $index . '-bg-color' ]       = '';
	$defaults[ 'header-social-' . $index . '-bg-h-color' ]     = '';
	$defaults[ 'header-social-' . $index . '-label-toggle' ]   = false;
	$defaults[ 'header-social-' . $index . '-color-type' ]     = 'custom';
	$defaults[ 'font-size-section-hb-social-icons-' . $index ] = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults[ 'header-social-icons-' . $index ]               = array(
		'items' =>
			array(
				array(
					'id'         => 'facebook',
					'enabled'    => true,
					'source'     => 'icon',
					'url'        => '',
					'color'      => '#557dbc',
					'background' => 'transparent',
					'icon'       => 'facebook',
					'label'      => 'Facebook',
				),
				array(
					'id'         => 'twitter',
					'enabled'    => true,
					'source'     => 'icon',
					'url'        => '',
					'color'      => '#7acdee',
					'background' => 'transparent',
					'icon'       => 'twitter',
					'label'      => 'Twitter',
				),
				array(
					'id'         => 'instagram',
					'enabled'    => true,
					'source'     => 'icon',
					'url'        => '',
					'color'      => '#8a3ab9',
					'background' => 'transparent',
					'icon'       => 'instagram',
					'label'      => 'Instagram',
				),
			),
	);

	$defaults[ 'section-hb-social-icons-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;


	$defaults[ 'footer-social-' . $index . '-space' ]          = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-social-' . $index . '-bg-space' ]       = '';
	$defaults[ 'footer-social-' . $index . '-size' ]           = array(
		'desktop' => 18,
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-social-' . $index . '-radius' ]         = '';
	$defaults[ 'footer-social-' . $index . '-color' ]          = '';
	$defaults[ 'footer-social-' . $index . '-h-color' ]        = '';
	$defaults[ 'footer-social-' . $index . '-bg-color' ]       = '';
	$defaults[ 'footer-social-' . $index . '-bg-h-color' ]     = '';
	$defaults[ 'footer-social-' . $index . '-label-toggle' ]   = false;
	$defaults[ 'footer-social-' . $index . '-color-type' ]     = 'custom';
	$defaults[ 'font-size-section-fb-social-icons-' . $index ] = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);
	$defaults[ 'footer-social-icons-' . $index ]               = array(
		'items' =>
			array(
				array(
					'id'         => 'facebook',
					'enabled'    => true,
					'source'     => 'icon',
					'url'        => '',
					'color'      => '#557dbc',
					'background' => 'transparent',
					'icon'       => 'facebook',
					'label'      => 'Facebook',
				),
				array(
					'id'         => 'twitter',
					'enabled'    => true,
					'source'     => 'icon',
					'url'        => '',
					'color'      => '#7acdee',
					'background' => 'transparent',
					'icon'       => 'twitter',
					'label'      => 'Twitter',
				),
				array(
					'id'         => 'instagram',
					'enabled'    => true,
					'source'     => 'icon',
					'url'        => '',
					'color'      => '#8a3ab9',
					'background' => 'transparent',
					'icon'       => 'instagram',
					'label'      => 'Instagram',
				),
			),
	);
	$defaults[ 'footer-social-' . $index . '-alignment' ]      = array(
		'desktop' => 'center',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);

	$defaults[ 'section-fb-social-icons-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;

	return $defaults;
}

/**
 * Prepare Widget Defaults.
 *
 * @param array   $defaults defaults.
 * @param integer $index index.
 */
function prepare_widget_defaults( $defaults, $index ) {

	// Widget Header defaults.

	// Colors.
	$defaults[ 'header-widget-' . $index . '-title-color' ]  = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'header-widget-' . $index . '-color' ]        = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'header-widget-' . $index . '-link-color' ]   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'header-widget-' . $index . '-link-h-color' ] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	// Title Typography.
	$defaults[ 'header-widget-' . $index . '-font-family' ]    = 'inherit';
	$defaults[ 'header-widget-' . $index . '-font-weight' ]    = 'inherit';
	$defaults[ 'header-widget-' . $index . '-text-transform' ] = '';
	$defaults[ 'header-widget-' . $index . '-line-height' ]    = '';
	$defaults[ 'header-widget-' . $index . '-font-size' ]      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);


	// Content Typography.
	$defaults[ 'header-widget-' . $index . '-content-font-family' ]    = 'inherit';
	$defaults[ 'header-widget-' . $index . '-content-font-weight' ]    = 'inherit';
	$defaults[ 'header-widget-' . $index . '-content-text-transform' ] = '';
	$defaults[ 'header-widget-' . $index . '-content-line-height' ]    = '';
	$defaults[ 'header-widget-' . $index . '-content-font-size' ]      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);

	$defaults[ 'sidebar-widgets-header-widget-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;

	// Widget Footer defaults.

	// Colors.
	$defaults[ 'footer-widget-' . $index . '-title-color' ]  = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-widget-' . $index . '-color' ]        = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-widget-' . $index . '-link-color' ]   = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);
	$defaults[ 'footer-widget-' . $index . '-link-h-color' ] = array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	);

	// Title Typography.
	$defaults[ 'footer-widget-' . $index . '-font-family' ]    = 'inherit';
	$defaults[ 'footer-widget-' . $index . '-font-weight' ]    = 'inherit';
	$defaults[ 'footer-widget-' . $index . '-text-transform' ] = '';
	$defaults[ 'footer-widget-' . $index . '-line-height' ]    = '';
	$defaults[ 'footer-widget-' . $index . '-font-size' ]      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);

	// Content Typography.
	$defaults[ 'footer-widget-' . $index . '-content-font-family' ]    = 'inherit';
	$defaults[ 'footer-widget-' . $index . '-content-font-weight' ]    = 'inherit';
	$defaults[ 'footer-widget-' . $index . '-content-text-transform' ] = '';
	$defaults[ 'footer-widget-' . $index . '-content-line-height' ]    = '';
	$defaults[ 'footer-widget-' . $index . '-content-font-size' ]      = array(
		'desktop'      => '',
		'tablet'       => '',
		'mobile'       => '',
		'desktop-unit' => 'px',
		'tablet-unit'  => 'px',
		'mobile-unit'  => 'px',
	);

	$defaults[ 'footer-widget-alignment-' . $index ] = array(
		'desktop' => 'left',
		'tablet'  => 'center',
		'mobile'  => 'center',
	);

	$defaults[ 'sidebar-widgets-footer-widget-' . $index . '-margin' ] = Astra_Builder_Helper::$default_responsive_spacing;


	return $defaults;
}

/**
 * Prepare menu Defaults.
 *
 * @param array   $defaults defaults.
 * @param integer $index index.
 */
function prepare_menu_defaults( $defaults, $index ) {

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

	$defaults[ 'header-' . $_prefix . '-menu-hover-animation' ]        = '';
	$defaults[ 'header-' . $_prefix . '-submenu-container-animation' ] = '';

	$defaults[ 'section-hb-menu-' . $index . '-margin' ]  = Astra_Builder_Helper::$default_responsive_spacing;
	$defaults[ 'header-menu' . $index . '-menu-spacing' ] = Astra_Builder_Helper::$default_responsive_spacing;



	/**
	 * Submenu
	 */
	$defaults[ 'header-' . $_prefix . '-submenu-item-border' ]   = false;
	$defaults[ 'header-' . $_prefix . '-submenu-item-b-size' ]   = '1';
	$defaults[ 'header-' . $_prefix . '-submenu-item-b-color' ]  = '#eaeaea';
	$defaults[ 'header-' . $_prefix . '-submenu-border-radius' ] = '';
	$defaults[ 'header-' . $_prefix . '-submenu-top-offset' ]    = '';
	$defaults[ 'header-' . $_prefix . '-submenu-width' ]         = '';
	$defaults[ 'header-' . $_prefix . '-submenu-border' ]        = array(
		'top'    => 2,
		'bottom' => 0,
		'left'   => 0,
		'right'  => 0,
	);

	/**
	 * Menu Stack on Mobile.
	 */
	$defaults[ 'header-' . $_prefix . '-menu-stack-on-mobile' ] = true;

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
	 * Header Types - Defaults
	 */
	$defaults['transparent-header-main-sep']       = '';
	$defaults['transparent-header-main-sep-color'] = '';

	return $defaults;
}

