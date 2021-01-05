<?php
/**
 * Typography - Breadcrumbs Options for theme.
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

				/**
				 * Option: Divider
				 * Option: breadcrumb Typography Section divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-typography-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-breadcrumb',
					'title'    => __( 'Typography', 'astra' ),
					'priority' => 73,
					'settings' => array(),
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						Astra_Builder_Helper::$is_header_footer_builder_active ?
							Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
					),
				),

				/*
				 * Breadcrumb Typography
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'default'   => astra_get_option( 'section-breadcrumb-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Content', 'astra' ),
					'section'   => 'section-breadcrumb',
					'transport' => 'postMessage',
					'priority'  => 73,
					'context'   => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						Astra_Builder_Helper::$is_header_footer_builder_active ?
							Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
					),
				),

				/**
				 * Option: Font Family
				 */
				array(
					'name'      => 'breadcrumb-font-family',
					'default'   => astra_get_option( 'breadcrumb-font-family' ),
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'   => 'section-breadcrumb',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Family', 'astra' ),
					'connect'   => 'breadcrumb-font-weight',
					'priority'  => 5,
				),

				/**
				 * Option: Font Size
				 */
				array(
					'name'        => 'breadcrumb-font-size',
					'control'     => 'ast-responsive',
					'type'        => 'sub-control',
					'parent'      => ASTRA_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'     => 'section-breadcrumb',
					'default'     => astra_get_option( 'breadcrumb-font-size' ),
					'transport'   => 'postMessage',
					'title'       => __( 'Size', 'astra' ),
					'priority'    => 10,
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Font Weight
				 */
				array(
					'name'              => 'breadcrumb-font-weight',
					'control'           => 'ast-font',
					'type'              => 'sub-control',
					'parent'            => ASTRA_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'           => 'section-breadcrumb',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'breadcrumb-font-weight' ),
					'title'             => __( 'Weight', 'astra' ),
					'connect'           => 'breadcrumb-font-family',
					'priority'          => 15,
				),

				/**
				 * Option: Text Transform
				 */
				array(
					'name'      => 'breadcrumb-text-transform',
					'control'   => 'ast-select',
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'   => 'section-breadcrumb',
					'default'   => astra_get_option( 'breadcrumb-text-transform' ),
					'title'     => __( 'Text Transform', 'astra' ),
					'transport' => 'postMessage',
					'priority'  => 20,
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),

				/**
				 * Option: Line Height
				 */
				array(
					'name'              => 'breadcrumb-line-height',
					'control'           => 'ast-slider',
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'default'           => '',
					'parent'            => ASTRA_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'           => 'section-breadcrumb',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra' ),
					'suffix'            => '',
					'priority'          => 25,
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
new Astra_Breadcrumbs_Typo_Configs();
