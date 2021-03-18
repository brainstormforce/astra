<?php
/**
 * Colors and Background - Header Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Colors_Transparent_Header_Configs' ) ) {

	/**
	 * Register Colors and Background - Header Options Customizer Configurations.
	 */
	class Astra_Customizer_Colors_Transparent_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Colors and Background - Header Options Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Header background overlay color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-header-bg-color-responsive]',
					'default'    => astra_get_option( 'transparent-header-bg-color-responsive' ),
					'section'    => 'section-transparent-header',
					'type'       => 'control',
					'priority'   => 34,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Overlay', 'astra' ),
					'context'    => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Site Title Color
				 */
				array(
					'name'       => 'transparent-header-color-site-title-responsive',
					'default'    => astra_get_option( 'transparent-header-color-site-title-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 1,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors]',
					'section'    => 'section-transparent-header',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Normal', 'astra' ),
					'tab'        => __( 'Normal', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Site Title Hover Color
				 */
				array(
					'name'       => 'transparent-header-color-h-site-title-responsive',
					'default'    => astra_get_option( 'transparent-header-color-h-site-title-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 1,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors]',
					'section'    => 'section-transparent-header',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Hover', 'astra' ),
					'tab'        => __( 'Hover', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Primary Menu Color
				 */
				array(
					'name'       => 'transparent-menu-color-responsive',
					'default'    => astra_get_option( 'transparent-menu-color-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 2,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors-menu]',
					'section'    => 'section-transparent-header',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'title'      => __( 'Normal', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Menu Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-menu-bg-color-responsive]',
					'default'    => astra_get_option( 'transparent-menu-bg-color-responsive' ),
					'type'       => 'control',
					'priority'   => 36,
					'section'    => 'section-transparent-header',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'tab'        => __( 'Normal', 'astra' ),
					'title'      => __( 'Background', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Menu Hover Color
				 */
				array(
					'name'       => 'transparent-menu-h-color-responsive',
					'default'    => astra_get_option( 'transparent-menu-h-color-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 3,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors-menu]',
					'section'    => 'section-transparent-header',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra' ),
					'title'      => __( 'Hover / Active', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Sub menu text color.
				 */
				array(
					'name'       => 'transparent-submenu-color-responsive',
					'default'    => astra_get_option( 'transparent-submenu-color-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 3,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors-submenu]',
					'section'    => 'section-transparent-header',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'title'      => __( 'Normal', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Sub menu background color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-submenu-bg-color-responsive]',
					'default'    => astra_get_option( 'transparent-submenu-bg-color-responsive' ),
					'type'       => 'control',
					'priority'   => 38,
					'section'    => 'section-transparent-header',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'title'      => __( 'Background', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Sub menu active hover color.
				 */
				array(
					'name'       => 'transparent-submenu-h-color-responsive',
					'default'    => astra_get_option( 'transparent-submenu-h-color-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 3,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors-submenu]',
					'section'    => 'section-transparent-header',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra' ),
					'title'      => __( 'Hover / Active', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Content Section Link color.
				 */
				array(
					'name'       => 'transparent-content-section-link-color-responsive',
					'default'    => astra_get_option( 'transparent-content-section-link-color-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 4,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors-content]',
					'section'    => 'section-transparent-header',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'tab'        => __( 'Normal', 'astra' ),
					'title'      => __( 'Normal', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Content Section Link Hover color.
				 */
				array(
					'name'       => 'transparent-content-section-link-h-color-responsive',
					'default'    => astra_get_option( 'transparent-content-section-link-h-color-responsive' ),
					'type'       => 'sub-control',
					'priority'   => 4,
					'parent'     => ASTRA_THEME_SETTINGS . '[transparent-header-colors-content]',
					'section'    => 'section-transparent-header',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'tab'        => __( 'Hover', 'astra' ),
					'title'      => __( 'Hover', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Colors_Transparent_Header_Configs();
