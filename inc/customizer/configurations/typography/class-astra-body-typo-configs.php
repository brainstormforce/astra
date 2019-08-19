<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Body_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Body_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Body Typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Body & Content Divider
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[divider-base-typo]',
					'type'      => 'control',
					'control'   => 'ast-heading',
					'section'   => 'section-body-typo',
					'priority'  => 4,
					'title'     => __( 'Body & Content', 'astra' ),
					'settings'  => array(),
					'separator' => false,
				),

				/**
				 * Option: Font Family
				 */

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[body-font-family]',
					'type'        => 'control',
					'control'     => 'ast-font',
					'font-type'   => 'ast-font-family',
					'ast_inherit' => __( 'Default System Font', 'astra' ),
					'default'     => astra_get_option( 'body-font-family' ),
					'section'     => 'section-body-typo',
					'priority'    => 5,
					'title'       => __( 'Family', 'astra' ),
					'connect'     => ASTRA_THEME_SETTINGS . '[body-font-weight]',
					'variant'     => ASTRA_THEME_SETTINGS . '[body-font-variant]',
				),
				/**
				 * Option: Font Variant
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[body-font-variant]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-variant',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_variant' ),
					'default'           => astra_get_option( 'body-font-variant' ),
					'ast_inherit'       => __( 'Default', 'astra' ),
					'section'           => 'section-body-typo',
					'priority'          => 10,
					'title'             => __( 'Variants', 'astra' ),
					'variant'           => ASTRA_THEME_SETTINGS . '[body-font-family]',
				),

				/**
				 * Option: Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[body-font-weight]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'body-font-weight' ),
					'ast_inherit'       => __( 'Default', 'astra' ),
					'section'           => 'section-body-typo',
					'priority'          => 15,
					'title'             => __( 'Weight', 'astra' ),
					'connect'           => ASTRA_THEME_SETTINGS . '[body-font-family]',
				),

				/**
				 * Option: Body Text Transform
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[body-text-transform]',
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-body-typo',
					'default'  => astra_get_option( 'body-text-transform' ),
					'priority' => 20,
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
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-body]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-body-typo',
					'default'     => astra_get_option( 'font-size-body' ),
					'priority'    => 10,
					'title'       => __( 'Size', 'astra' ),
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
					'name'              => ASTRA_THEME_SETTINGS . '[body-line-height]',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'section'           => 'section-body-typo',
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

				/**
				 * Option: Paragraph Margin Bottom
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[para-margin-bottom]',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'transport'         => 'postMessage',
					'section'           => 'section-body-typo',
					'priority'          => 25,
					'title'             => __( 'Paragraph Margin Bottom', 'astra' ),
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 0.5,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Body & Content Divider
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[divider-headings-typo]',
					'type'      => 'control',
					'control'   => 'ast-heading',
					'section'   => 'section-content-typo',
					'priority'  => 3,
					'title'     => __( 'Headings', 'astra' ),
					'settings'  => array(),
					'separator' => false,
				),

				/**
				 * Option: Headings Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[headings-font-family]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'headings-font-family' ),
					'title'     => __( 'Family', 'astra' ),
					'section'   => 'section-content-typo',
					'priority'  => 3,
					'connect'   => ASTRA_THEME_SETTINGS . '[headings-font-weight]',
					'variant'   => ASTRA_THEME_SETTINGS . '[headings-font-variant]',
				),

				/**
				 * Option: Font Variant
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[headings-font-variant]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-variant',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_variant' ),
					'default'           => astra_get_option( 'headings-font-variant' ),
					'ast_inherit'       => __( 'Default', 'astra' ),
					'section'           => 'section-content-typo',
					'priority'          => 3,
					'title'             => __( 'Variants', 'astra' ),
					'variant'           => ASTRA_THEME_SETTINGS . '[headings-font-family]',
				),

				/**
				 * Option: Headings Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[headings-font-weight]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'headings-font-weight' ),
					'title'             => __( 'Weight', 'astra' ),
					'section'           => 'section-content-typo',
					'priority'          => 3,
					'connect'           => ASTRA_THEME_SETTINGS . '[headings-font-family]',
				),

				/**
				 * Option: Headings Text Transform
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[headings-text-transform]',
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-content-typo',
					'title'    => __( 'Text Transform', 'astra' ),
					'default'  => astra_get_option( 'headings-text-transform' ),
					'priority' => 3,
					'choices'  => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Body_Typo_Configs;


