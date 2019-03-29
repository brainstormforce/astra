<?php
/**
 * Colors - Breadcrumbs Options for theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2019, Brainstorm Force
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

				/*
				 * Breadcrumb Color
				 */
				array(
					'name'     => 'section-breadcrumb-color',
					'type'     => 'section',
					'title'    => __( 'Breadcrumb', 'astra' ),
					'panel'    => 'panel-colors-background',
					'priority' => 10,
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-active-color-responsive]',
					'default'    => $defaults['breadcrumb-active-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'astra' ),
					'section'    => 'section-breadcrumb-color',
					'responsive' => true,
					'rgba'       => true,
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-text-color-responsive]',
					'default'    => $defaults['breadcrumb-text-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Color', 'astra' ),
					'section'    => 'section-breadcrumb-color',
					'responsive' => true,
					'rgba'       => true,
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-hover-color-responsive]',
					'default'    => $defaults['breadcrumb-hover-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Hover Color', 'astra' ),
					'section'    => 'section-breadcrumb-color',
					'responsive' => true,
					'rgba'       => true,
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-separator-color]',
					'default'    => $defaults['breadcrumb-separator-color'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Separator Color', 'astra' ),
					'section'    => 'section-breadcrumb-color',
					'responsive' => true,
					'rgba'       => true,
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-bg-color]',
					'default'    => $defaults['breadcrumb-bg-color'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra' ),
					'section'    => 'section-breadcrumb-color',
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
new Astra_Breadcrumbs_Color_Configs;
