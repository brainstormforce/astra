<?php
/**
 * Heading Colors Options for Astra theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Heading_Colors_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Heading_Colors_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra Heading Colors Settings.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 2.1.4
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				// Option: Base Heading Color.
				array(
					'default'           => astra_get_option( 'heading-base-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'priority'          => 18,
					'name'              => ASTRA_THEME_SETTINGS . '[heading-base-color]',
					'title'             => __( 'Heading Color ( H1 - H6 )', 'astra' ),
					'section'           => ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'colors-and-background' ) ) ? 'section-colors-body' : 'section-colors-background',
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Button Typography Heading
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[button-text-typography]',
					'default'   => astra_get_option( 'button-text-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Button Font', 'astra' ),
					'section'   => 'section-buttons',
					'transport' => 'postMessage',
					'priority'  => 25,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Button Font Family
				 */
				array(
					'name'      => 'font-family-button',
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[button-text-typography]',
					'section'   => 'section-buttons',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Family', 'astra' ),
					'default'   => astra_get_option( 'font-family-button' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-button]',
					'priority'  => 1,
				),

				/**
				 * Option: Button Font Size
				 */
				array(
					'name'        => 'font-size-button',
					'transport'   => 'postMessage',
					'title'       => __( 'Size', 'astra' ),
					'type'        => 'sub-control',
					'parent'      => ASTRA_THEME_SETTINGS . '[button-text-typography]',
					'section'     => 'section-buttons',
					'control'     => 'ast-responsive',
					'priority'    => 2,
					'default'     => astra_get_option( 'font-size-button' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Button Font Weight
				 */
				array(
					'name'              => 'font-weight-button',
					'type'              => 'sub-control',
					'parent'            => ASTRA_THEME_SETTINGS . '[button-text-typography]',
					'section'           => 'section-buttons',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'title'             => __( 'Weight', 'astra' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-button' ),
					'connect'           => 'font-family-button',
					'priority'          => 3,
				),

				/**
				 * Option: Button Text Transform
				 */
				array(
					'name'      => 'text-transform-button',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'text-transform-button' ),
					'title'     => __( 'Text Transform', 'astra' ),
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[button-text-typography]',
					'section'   => 'section-buttons',
					'control'   => 'ast-select',
					'priority'  => 4,
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),

				/**
				 * Option: Theme Button Line Height
				 */
				array(
					'name'              => 'theme-btn-line-height',
					'control'           => 'ast-slider',
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'default'           => astra_get_option( 'theme-btn-line-height' ),
					'parent'            => ASTRA_THEME_SETTINGS . '[button-text-typography]',
					'section'           => 'section-buttons',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra' ),
					'suffix'            => 'em',
					'priority'          => 5,
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Theme Button Line Height
				 */
				array(
					'name'              => 'theme-btn-letter-spacing',
					'control'           => 'ast-slider',
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'default'           => astra_get_option( 'theme-btn-letter-spacing' ),
					'parent'            => ASTRA_THEME_SETTINGS . '[button-text-typography]',
					'section'           => 'section-buttons',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Letter Spacing', 'astra' ),
					'suffix'            => 'px',
					'priority'          => 6,
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
				),

			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Heading_Colors_Configs();
