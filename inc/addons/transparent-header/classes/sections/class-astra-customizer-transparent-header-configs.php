<?php
/**
 * Transparent Header Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2019, Brainstorm Force
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

			$defaults = Astra_Theme_Options::defaults();

			$_configs = array(

				/**
				 * Option: Enable Transparent Header
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
					'default'  => astra_get_option( 'transparent-header-enable' ),
					'type'     => 'control',
					'section'  => 'section-transparent-header',
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
					'section'     => 'section-transparent-header',
					'required'    => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', '1' ),
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
					'section'     => 'section-transparent-header',
					'required'    => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', '1' ),
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
					'section'     => 'section-transparent-header',
					'required'    => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', '1' ),
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
					'section'  => 'section-transparent-header',
					'required' => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', '1' ),
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
					'section'  => 'section-transparent-header',
					'required' => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', '1' ),
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
					'section'  => 'section-transparent-header',
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
					'section'  => 'section-transparent-header',
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
					'section'  => 'section-transparent-header',
					'priority' => 28,
					'settings' => array(),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-transparent-logo]',
					'default'  => astra_get_option( 'different-transparent-logo', false ),
					'type'     => 'control',
					'section'  => 'section-transparent-header',
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
					'section'        => 'section-transparent-header',
					'required'       => array( ASTRA_THEME_SETTINGS . '[different-transparent-logo]', '==', true ),
					'priority'       => 30,
					'title'          => __( 'Logo', 'astra' ),
					'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
				),

				/**
				 * Option: Different retina logo
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-transparent-retina-logo]',
					'default'  => false,
					'type'     => 'control',
					'section'  => 'section-transparent-header',
					'title'    => __( 'Different Logo For Retina Devices?', 'astra' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[different-transparent-logo]', '==', true ),
					'priority' => 30,
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
					'section'        => 'section-transparent-header',
					'required'       => array( ASTRA_THEME_SETTINGS . '[different-transparent-retina-logo]', '==', true ),
					'priority'       => 30,
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
					'section'     => 'section-transparent-header',
					'required'    => array( ASTRA_THEME_SETTINGS . '[different-transparent-logo]', '==', true ),
					'priority'    => 30,
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
					'section'  => 'section-transparent-header',
					'priority' => 30,
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
					'section'     => 'section-transparent-header',
					'priority'    => 30,
					'title'       => __( 'Bottom Border Size', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
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
					'section'   => 'section-transparent-header',
					'priority'  => 30,
					'title'     => __( 'Bottom Border Color', 'astra' ),
				),

				/**
				 * Option: Transparent Header Styling
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-sec-transparent-styling]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-transparent-header',
					'title'    => __( 'Colors & Background', 'astra' ),
					'priority' => 35,
					'settings' => array(),
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-background-colors]',
					'default'   => astra_get_option( 'transparent-header-background-colors' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Background', 'astra' ),
					'section'   => 'section-transparent-header',
					'transport' => 'postMessage',
					'priority'  => 35,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors]',
					'default'   => astra_get_option( 'transparent-header-colors' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Site Title', 'astra' ),
					'section'   => 'section-transparent-header',
					'transport' => 'postMessage',
					'priority'  => 35,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors-menu]',
					'default'   => astra_get_option( 'transparent-header-colors-menu' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Menu', 'astra' ),
					'section'   => 'section-transparent-header',
					'transport' => 'postMessage',
					'priority'  => 35,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors-submenu]',
					'default'   => astra_get_option( 'transparent-header-colors-submenu' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Submenu', 'astra' ),
					'section'   => 'section-transparent-header',
					'transport' => 'postMessage',
					'priority'  => 35,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-colors-content]',
					'default'   => astra_get_option( 'transparent-header-colors-content' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Content', 'astra' ),
					'section'   => 'section-transparent-header',
					'transport' => 'postMessage',
					'priority'  => 35,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Transparent_Header_Configs();
