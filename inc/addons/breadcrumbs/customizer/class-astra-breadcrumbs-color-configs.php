<?php
/**
 * Colors - Breadcrumbs Options for theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 1.7.0
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
 * @since 1.7.0
 */
if ( ! class_exists( 'Astra_Breadcrumbs_Color_Configs' ) ) {

	/**
	 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
	 */
	class Astra_Breadcrumbs_Color_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.7.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$defaults = Astra_Theme_Options::defaults();

			$_configs = array(

				/**
				 * Option: Divider
				 * Option: breadcrumb color Section divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-breadcrumb',
					'title'    => __( 'Colors', 'astra' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[breadcrumb-position]', '!=', 'none' ),
					'priority' => 72,
					'settings' => array(),
				),

				/*
				 * Breadcrumb Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color]',
					'default'   => astra_get_option( 'section-breadcrumb-color' ),
					'type'      => 'control',
					'required'  => array( ASTRA_THEME_SETTINGS . '[breadcrumb-position]', '!=', 'none' ),
					'control'   => 'ast-settings-group',
					'title'     => __( 'Content', 'astra' ),
					'section'   => 'section-breadcrumb',
					'transport' => 'postMessage',
					'priority'  => 72,
				),

				array(
					'name'       => 'breadcrumb-bg-color',
					'type'       => 'sub-control',
					'default'    => astra_get_option( 'breadcrumb-bg-color' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 5,
				),

				array(
					'name'       => 'breadcrumb-active-color-responsive',
					'default'    => astra_get_option( 'breadcrumb-active-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 10,
				),

				array(
					'name'       => 'breadcrumb-text-color-responsive',
					'default'    => astra_get_option( 'breadcrumb-text-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 15,
				),

				array(
					'name'       => 'breadcrumb-hover-color-responsive',
					'default'    => astra_get_option( 'breadcrumb-hover-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 20,
				),

				array(
					'name'       => 'breadcrumb-separator-color',
					'default'    => astra_get_option( 'breadcrumb-separator-color' ),
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Separator Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 25,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Breadcrumbs_Color_Configs;
