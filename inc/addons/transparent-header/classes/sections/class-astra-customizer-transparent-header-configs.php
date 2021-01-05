<?php
/**
 * Transparent Header Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 1.4.3
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Transparent_Header_Configs' ) ) {

	/**
	 * Register Transparent Header Customizer Configurations.
	 */
	class Astra_Customizer_Transparent_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Transparent Header Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-transparent-header';

			$_configs = array(

				/**
				 * Option: Enable Transparent Header
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
					'default'  => astra_get_option( 'transparent-header-enable' ),
					'type'     => 'control',
					'section'  => $_section,
					'title'    => __( 'Enable on Complete Website', 'astra' ),
					'priority' => 20,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Disable Transparent Header on Archive Pages
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-disable-archive]',
					'default'     => astra_get_option( 'transparent-header-disable-archive' ),
					'type'        => 'control',
					'section'     => $_section,
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'title'       => __( 'Disable on 404, Search & Archives?', 'astra' ),
					'description' => __( 'This setting is generally not recommended on special pages such as archive, search, 404, etc. If you would like to enable it, uncheck this option', 'astra' ),
					'priority'    => 25,
					'control'     => 'checkbox',
				),

				/**
				 * Option: Disable Transparent Header on Archive Pages
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-disable-index]',
					'default'     => astra_get_option( 'transparent-header-disable-index' ),
					'type'        => 'control',
					'section'     => $_section,
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'title'       => __( 'Disable on Blog page?', 'astra' ),
					'description' => __( 'Blog Page is when Latest Posts are selected to be displayed on a particular page.', 'astra' ),
					'priority'    => 25,
					'control'     => 'checkbox',
				),

				/**
				 * Option: Disable Transparent Header on Your latest posts index Page
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-disable-latest-posts-index]',
					'default'     => astra_get_option( 'transparent-header-disable-latest-posts-index' ),
					'type'        => 'control',
					'section'     => $_section,
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'title'       => __( 'Disable on Latest Posts Page?', 'astra' ),
					'description' => __( "Latest Posts page is your site's front page when the latest posts are displayed on the home page.", 'astra' ),
					'priority'    => 25,
					'control'     => 'checkbox',
				),

				/**
				 * Option: Disable Transparent Header on Pages
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[transparent-header-disable-page]',
					'default'  => astra_get_option( 'transparent-header-disable-page' ),
					'type'     => 'control',
					'section'  => $_section,
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'title'    => __( 'Disable on Pages?', 'astra' ),
					'priority' => 25,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Disable Transparent Header on Posts
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[transparent-header-disable-posts]',
					'default'  => astra_get_option( 'transparent-header-disable-posts' ),
					'type'     => 'control',
					'section'  => $_section,
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
							'operator' => '==',
							'value'    => '1',
						),
					),
					'title'    => __( 'Disable on Posts?', 'astra' ),
					'priority' => 25,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Transparent Header Styling
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-transparent-display]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => $_section,
					'priority' => 26,
					'settings' => array(),
				),

				/**
				 * Option: Sticky Header Display On
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[transparent-header-on-devices]',
					'default'  => astra_get_option( 'transparent-header-on-devices' ),
					'type'     => 'control',
					'section'  => $_section,
					'priority' => 27,
					'title'    => __( 'Enable On', 'astra' ),
					'control'  => 'select',
					'choices'  => array(
						'desktop' => __( 'Desktop', 'astra' ),
						'mobile'  => __( 'Mobile', 'astra' ),
						'both'    => __( 'Desktop + Mobile', 'astra' ),
					),
				),

				/**
				 * Option: Transparent Header Styling
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-transparent-styling]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => $_section,
					'priority' => 28,
					'settings' => array(),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-transparent-logo]',
					'default'  => astra_get_option( 'different-transparent-logo', false ),
					'type'     => 'control',
					'section'  => $_section,
					'title'    => __( 'Different Logo for Transparent Header?', 'astra' ),
					'priority' => 30,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Transparent header logo selector
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[transparent-header-logo]',
					'default'        => astra_get_option( 'transparent-header-logo' ),
					'type'           => 'control',
					'control'        => 'image',
					'section'        => $_section,
					'context'        => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'       => 30.1,
					'title'          => __( 'Logo', 'astra' ),
					'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					'partial'        => array(
						'selector'            => '.ast-replace-site-logo-transparent .site-branding .site-logo-img',
						'container_inclusive' => false,
					),
				),

				/**
				 * Option: Different retina logo
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-transparent-retina-logo]',
					'default'  => false,
					'type'     => 'control',
					'section'  => $_section,
					'title'    => __( 'Different Logo For Retina Devices?', 'astra' ),
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority' => 30.2,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Transparent header logo selector
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[transparent-header-retina-logo]',
					'default'        => astra_get_option( 'transparent-header-retina-logo' ),
					'type'           => 'control',
					'control'        => 'image',
					'section'        => $_section,
					'context'        => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[different-transparent-retina-logo]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'       => 30.3,
					'title'          => __( 'Retina Logo', 'astra' ),
					'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
				),

				/**
				 * Option: Transparent header logo width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-logo-width]',
					'default'     => astra_get_option( 'transparent-header-logo-width' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive-slider',
					'section'     => $_section,
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[different-transparent-logo]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'    => 30.4,
					'title'       => __( 'Logo Width', 'astra' ),
					'input_attrs' => array(
						'min'  => 50,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Transparent Header Border Styling
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-transparent-border-styling]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => $_section,
					'priority' => 30.5,
					'settings' => array(),
				),

				/**
				 * Option: Bottom Border Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-main-sep]',
					'default'     => astra_get_option( 'transparent-header-main-sep' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'section'     => $_section,
					'priority'    => 32,
					'title'       => __( 'Bottom Border Size', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
					'context'     => ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Bottom Border Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-main-sep-color]',
					'default'   => '',
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'section'   => $_section,
					'priority'  => 32,
					'title'     => __( 'Bottom Border Color', 'astra' ),
					'context'   => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[transparent-header-main-sep]',
							'operator' => '>=',
							'value'    => 1,
						),
						Astra_Builder_Helper::$is_header_footer_builder_active ? Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
					),
				),

				/**
				 * Option: Transparent Header Styling
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-sec-transparent-styling]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => $_section,
					'title'    => __( 'Colors & Background', 'astra' ),
					'priority' => 32,
					'settings' => array(),
					'context'  => ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors]',
					'default'   => astra_get_option( 'transparent-header-colors' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Site Title', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 35,
					'context'   => ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors-menu]',
					'default'   => astra_get_option( 'transparent-header-colors-menu' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Menu', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 35,
					'context'   => ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors-submenu]',
					'default'   => astra_get_option( 'transparent-header-colors-submenu' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Submenu', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 35,
					'context'   => ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				),
			);


			if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {
				$_hfb_configs = array(
					/**
					 * Option: Header Builder Tabs
					 */
					array(
						'name'        => $_section . '-ast-context-tabs',
						'section'     => $_section,
						'type'        => 'control',
						'control'     => 'ast-builder-header-control',
						'priority'    => 0,
						'description' => '',
					),

					/**
					 * Option: Transparent Header Builder - Social Element configs.
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-social-colors-content]',
						'default'   => astra_get_option( 'transparent-header-social-colors-content' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Social', 'astra' ),
						'section'   => $_section,
						'transport' => 'postMessage',
						'priority'  => 35,
						'context'   => Astra_Builder_Helper::$design_tab,
					),

					/**
					* Option: Social Text Color
					*/
					array(
						'name'       => 'transparent-header-social-icons-color',
						'transport'  => 'postMessage',
						'default'    => astra_get_option( 'transparent-header-social-icons-color' ),
						'type'       => 'sub-control',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-social-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Normal', 'astra' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 5,
						'context'    => Astra_Builder_Helper::$design_tab,
						'title'      => __( 'Color', 'astra' ),
					),

					/**
					* Option: Social Text Hover Color
					*/
					array(
						'name'       => 'transparent-header-social-icons-h-color',
						'default'    => astra_get_option( 'transparent-header-social-icons-h-color' ),
						'transport'  => 'postMessage',
						'type'       => 'sub-control',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-social-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Hover', 'astra' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 7,
						'context'    => Astra_Builder_Helper::$design_tab,
						'title'      => __( 'Color', 'astra' ),
					),

					/**
					* Option: Social Background Color
					*/
					array(
						'name'       => 'transparent-header-social-icons-bg-color',
						'default'    => astra_get_option( 'transparent-header-social-icons-bg-color' ),
						'transport'  => 'postMessage',
						'type'       => 'sub-control',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-social-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Normal', 'astra' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 9,
						'context'    => Astra_Builder_Helper::$design_tab,
						'title'      => __( 'Background Color', 'astra' ),
					),

					/**
					* Option: Social Background Hover Color
					*/
					array(
						'name'       => 'transparent-header-social-icons-bg-h-color',
						'default'    => astra_get_option( 'transparent-header-social-icons-bg-h-color' ),
						'transport'  => 'postMessage',
						'type'       => 'sub-control',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-social-colors-content]',
						'section'    => 'section-transparent-header',
						'tab'        => __( 'Hover', 'astra' ),
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 11,
						'context'    => Astra_Builder_Helper::$design_tab,
						'title'      => __( 'Background Color', 'astra' ),
					),

					/**
					 * Option: Transparent Header Builder - HTML Elements configs.
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-html-colors-group]',
						'default'   => astra_get_option( 'transparent-header-html-colors-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'HTML', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 40,
						'context'   => Astra_Builder_Helper::$design_tab,
					),

					// Option: HTML Text Color.
					array(
						'name'      => 'transparent-header-html-text-color',
						'default'   => astra_get_option( 'transparent-header-html-text-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-html-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 5,
						'title'     => __( 'Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					// Option: HTML Link Color.
					array(
						'name'      => 'transparent-header-html-link-color',
						'default'   => astra_get_option( 'transparent-header-html-link-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-html-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 5,
						'title'     => __( 'Link Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					// Option: HTML Link Hover Color.
					array(
						'name'      => 'transparent-header-html-link-h-color',
						'default'   => astra_get_option( 'transparent-header-html-link-h-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-html-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 5,
						'title'     => __( 'Link Hover Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					/**
					 * Option: Transparent Header Builder - Search Elements configs.
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-search-colors-group]',
						'default'   => astra_get_option( 'transparent-header-search-colors-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Search', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 45,
						'context'   => Astra_Builder_Helper::$design_tab,
					),

					// Option: Search Color.
					array(
						'name'      => 'transparent-header-search-icon-color',
						'default'   => astra_get_option( 'transparent-header-search-icon-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-search-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 5,
						'title'     => __( 'Icon Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					/**
					 * Search Box Background Color
					 */
					array(
						'name'      => 'transparent-header-search-box-background-color',
						'default'   => astra_get_option( 'transparent-header-search-box-background-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-search-colors-group]',
						'type'      => 'sub-control',
						'section'   => 'section-transparent-header',
						'priority'  => 6,
						'transport' => 'postMessage',
						'control'   => 'ast-color',
						'title'     => __( 'Box Background Color', 'astra' ),
						'context'   => array(
							Astra_Builder_Helper::$general_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-search-box-type]',
								'operator' => 'in',
								'value'    => array( 'slide-search', 'search-box' ),
							),
						),
					),
					/**
					 * Option: Transparent Header Builder - Widget Elements configs.
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-widget-colors-group]',
						'default'   => astra_get_option( 'transparent-header-widget-colors-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Widget', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 50,
						'context'   => Astra_Builder_Helper::$design_tab,
					),

					// Option: Widget Title Color.
					array(
						'name'      => 'transparent-header-widget-title-color',
						'default'   => astra_get_option( 'transparent-header-widget-title-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-widget-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 5,
						'tab'       => __( 'Normal', 'astra' ),
						'title'     => __( 'Title Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					// Option: Widget Content Color.
					array(
						'name'      => 'transparent-header-widget-content-color',
						'default'   => astra_get_option( 'transparent-header-widget-content-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-widget-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 10,
						'tab'       => __( 'Normal', 'astra' ),
						'title'     => __( 'Content Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					// Option: Widget Link Color.
					array(
						'name'      => 'transparent-header-widget-link-color',
						'default'   => astra_get_option( 'transparent-header-widget-link-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-widget-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 15,
						'tab'       => __( 'Normal', 'astra' ),
						'title'     => __( 'Link Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					// Option: Widget Link Hover Color.
					array(
						'name'      => 'transparent-header-widget-link-h-color',
						'default'   => astra_get_option( 'transparent-header-widget-link-h-color' ),
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-widget-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'tab'       => __( 'Hover', 'astra' ),
						'priority'  => 20,
						'title'     => __( 'Link Hover Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$general_tab,
					),

					/**
					 * Group: Transparent Header Button Colors Group
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-buttons-group]',
						'default'   => astra_get_option( 'transparent-header-buttons-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Button', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 60,
						'context'   => Astra_Builder_Helper::$design_tab,
					),

					/**
					 * Option: Button Text Color
					 */
					array(
						'name'      => 'transparent-header-button-text-color',
						'transport' => 'postMessage',
						'default'   => astra_get_option( 'transparent-header-button-text-color' ),
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-buttons-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Normal', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 5,
						'title'     => __( 'Text Color', 'astra' ),
					),

					/**
					 * Option: Button Text Hover Color
					 */
					array(
						'name'      => 'transparent-header-button-text-h-color',
						'default'   => astra_get_option( 'transparent-header-button-text-h-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-buttons-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Hover', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 7,
						'title'     => __( 'Text Color', 'astra' ),
					),

					/**
					 * Option: Button Background Color
					 */
					array(
						'name'      => 'transparent-header-button-bg-color',
						'default'   => astra_get_option( 'transparent-header-button-bg-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-buttons-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Normal', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 9,
						'title'     => __( 'Background Color', 'astra' ),
					),

					/**
					 * Option: Button Button Hover Color
					 */
					array(
						'name'      => 'transparent-header-button-bg-h-color',
						'default'   => astra_get_option( 'transparent-header-button-bg-h-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-buttons-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Hover', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 11,
						'title'     => __( 'Background Color', 'astra' ),
					),

					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-account-colors]',
						'default'   => astra_get_option( 'transparent-account-colors' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Account', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 65,
						'context'   => array(
							Astra_Builder_Helper::$design_tab_config,
							array(
								'relation' => 'OR',
								array(
									'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
									'operator' => '==',
									'value'    => 'icon',
								),
								array(
									'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
									'operator' => '==',
									'value'    => 'text',
								),
								array(
									'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
									'operator' => '!=',
									'value'    => 'none',
								),
							),
						),
					),

					array(
						'name'      => 'transparent-account-icon-color',
						'default'   => '',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-account-colors]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 5,
						'title'     => __( 'Icon Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$design_tab_config,
					),

					array(
						'name'      => 'transparent-account-type-text-color',
						'default'   => '',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-account-colors]',
						'type'      => 'sub-control',
						'section'   => $_section,
						'priority'  => 6,
						'transport' => 'postMessage',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'title'     => __( 'Text Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$design_tab_config,
					),
				);

				$_configs = array_merge( $_configs, $_hfb_configs );
			} else {
				$_old_content_configs = array(
					/**
					 * Option: Header Builder Tabs
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors-content]',
						'default'   => astra_get_option( 'transparent-header-colors-content' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Content', 'astra' ),
						'section'   => $_section,
						'transport' => 'postMessage',
						'priority'  => 35,
						'context'   => ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
					),
				);

				$_configs = array_merge( $_configs, $_old_content_configs );
			}

			if ( defined( 'ASTRA_EXT_VER' ) && Astra_Builder_Helper::$is_header_footer_builder_active ) {

				$pro_elements_transparent_config = array(
					/**
					 * Option: Transparent Header Builder - Divider Elements configs.
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-divider-colors-group]',
						'default'   => '',
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Divider', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 64,
						'context'   => Astra_Builder_Helper::$design_tab,
					),

					// Option: Search Color.
					array(
						'name'      => 'transparent-header-divider-color',
						'default'   => '',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-divider-colors-group]',
						'type'      => 'sub-control',
						'control'   => 'ast-color',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 5,
						'title'     => __( 'Color', 'astra' ),
						'context'   => Astra_Builder_Helper::$design_tab,
					),

					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'default'   => astra_get_option( 'transparent-account-menu-colors' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Account Menu', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 66,
						'context'   => array(
							Astra_Builder_Helper::$design_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-action-type]',
								'operator' => '==',
								'value'    => 'menu',
							),
						),
					),

					// Option: Menu Color.
					array(
						'name'       => 'transparent-account-menu-color-responsive',
						'default'    => '',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'       => 'sub-control',
						'control'    => 'ast-responsive-color',
						'transport'  => 'postMessage',
						'tab'        => __( 'Normal', 'astra' ),
						'section'    => 'section-transparent-header',
						'title'      => __( 'Link / Text Color', 'astra' ),
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 7,
						'context'    => array(
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-action-type]',
								'operator' => '==',
								'value'    => 'menu',
							),
							Astra_Builder_Helper::$design_tab,
						),
					),

					// Option: Background Color.
					array(
						'name'       => 'transparent-account-menu-bg-obj-responsive',
						'default'    => '',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'       => 'sub-control',
						'control'    => 'ast-responsive-color',
						'transport'  => 'postMessage',
						'section'    => 'section-transparent-header',
						'title'      => __( 'Background Color', 'astra' ),
						'tab'        => __( 'Normal', 'astra' ),
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 8,
						'context'    => Astra_Builder_Helper::$design_tab,
					),

					// Option: Menu Hover Color.
					array(
						'name'       => 'transparent-account-menu-h-color-responsive',
						'default'    => '',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'tab'        => __( 'Hover', 'astra' ),
						'type'       => 'sub-control',
						'control'    => 'ast-responsive-color',
						'transport'  => 'postMessage',
						'title'      => __( 'Link Color', 'astra' ),
						'section'    => 'section-transparent-header',
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 19,
						'context'    => Astra_Builder_Helper::$design_tab,
					),

					// Option: Menu Hover Background Color.
					array(
						'name'       => 'transparent-account-menu-h-bg-color-responsive',
						'default'    => '',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'       => 'sub-control',
						'title'      => __( 'Background Color', 'astra' ),
						'section'    => 'section-transparent-header',
						'control'    => 'ast-responsive-color',
						'transport'  => 'postMessage',
						'tab'        => __( 'Hover', 'astra' ),
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 21,
						'context'    => Astra_Builder_Helper::$design_tab,
					),

					// Option: Active Menu Color.
					array(
						'name'       => 'transparent-account-menu-a-color-responsive',
						'default'    => '',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'       => 'sub-control',
						'section'    => 'section-transparent-header',
						'control'    => 'ast-responsive-color',
						'transport'  => 'postMessage',
						'tab'        => __( 'Active', 'astra' ),
						'title'      => __( 'Link Color', 'astra' ),
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 31,
						'context'    => Astra_Builder_Helper::$design_tab,
					),

					// Option: Active Menu Background Color.
					array(
						'name'       => 'transparent-account-menu-a-bg-color-responsive',
						'default'    => '',
						'parent'     => ASTRA_THEME_SETTINGS . '[transparent-account-menu-colors]',
						'type'       => 'sub-control',
						'control'    => 'ast-responsive-color',
						'transport'  => 'postMessage',
						'section'    => 'section-transparent-header',
						'title'      => __( 'Background Color', 'astra' ),
						'tab'        => __( 'Active', 'astra' ),
						'responsive' => true,
						'rgba'       => true,
						'priority'   => 33,
						'context'    => Astra_Builder_Helper::$design_tab,
					),
				);

				$_configs = array_merge( $_configs, $pro_elements_transparent_config );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Transparent_Header_Configs();
