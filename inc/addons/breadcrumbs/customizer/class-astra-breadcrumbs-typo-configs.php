<?php
/**
 * Typography - Breadcrumbs Options for theme.
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
if ( ! class_exists( 'Astra_Breadcrumbs_Typo_Configs' ) ) {

	/**
	 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
	 */
	class Astra_Breadcrumbs_Typo_Configs extends Astra_Customizer_Config_Base {

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
				 * Breadcrumb Typography
				 */
				array(
					'name'     => 'section-breadcrumb-typo',
					'type'     => 'section',
					'title'    => __( 'Breadcrumb', 'astra' ),
					'panel'    => 'panel-typography',
					'priority' => 20,
				),

				/**
				 * Option: Font Family
				 */

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[breadcrumb-font-family]',
					'type'        => 'control',
					'control'     => 'ast-font',
					'font-type'   => 'ast-font-family',
					'ast_inherit' => __( 'Default System Font', 'astra' ),
					'default'     => astra_get_option( 'breadcrumb-font-family' ),
					'section'     => 'section-breadcrumb-typo',
					'priority'    => 5,
					'title'       => __( 'Font Family', 'astra' ),
					'connect'     => ASTRA_THEME_SETTINGS . '[breadcrumb-font-weight]',
				),

				/**
				 * Option: Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[breadcrumb-font-weight]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'breadcrumb-font-weight' ),
					'ast_inherit'       => __( 'Default', 'astra' ),
					'section'           => 'section-breadcrumb-typo',
					'priority'          => 10,
					'title'             => __( 'Font Weight', 'astra' ),
					'connect'           => ASTRA_THEME_SETTINGS . '[breadcrumb-font-family]',
				),

				/**
				 * Option: Body Text Transform
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[breadcrumb-text-transform]',
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-breadcrumb-typo',
					'default'  => astra_get_option( 'breadcrumb-text-transform' ),
					'priority' => 15,
					'title'    => __( 'Text Transform', 'astra' ),
					'choices'  => array(
						''           => __( 'Default', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),

				/**
				 * Option: Body Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[breadcrumb-font-size]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-breadcrumb-typo',
					'default'     => astra_get_option( 'breadcrumb-font-size' ),
					'priority'    => 20,
					'title'       => __( 'Font Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
					),
				),

				/**
				 * Option: Body Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[breadcrumb-line-height]',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'section'           => 'section-breadcrumb-typo',
					'transport'         => 'postMessage',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'priority'          => 25,
					'title'             => __( 'Line Height', 'astra' ),
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Breadcrumbs_Typo_Configs;
