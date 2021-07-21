<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes Initial setup
 */
class Astra_Headings_Typo_Configs extends Astra_Customizer_Config_Base {

	/**
	 * Register headings Typography Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since x.x.x
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$section = 'section-typography';

		$_configs = array(

			/**
			 * Option: Heading <H4> Font Family
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[font-family-h4]',
				'type'      => 'control',
				'control'   => 'ast-font',
				'font-type' => 'ast-font-family',
				'title'     => __( 'Family', 'astra-addon' ),
				'default'   => astra_get_option( 'font-family-h4' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h4]',
				'transport' => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h4',
					),
				),
			),

			/**
			 * Option: Heading <H4> Font Weight
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h4]',
				'type'              => 'control',
				'control'           => 'ast-font',
				'font-type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'title'             => __( 'Weight', 'astra-addon' ),
				'default'           => astra_get_option( 'font-weight-h4' ),
				'section'           => $section,
				'priority'          => 28,
				'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h4]',
				'transport'         => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h4',
					),
				),
			),

			/**
			 * Option: Heading <H4> Text Transform
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h4]',
				'section'   => $section,
				'type'      => 'control',
				'title'     => __( 'Text Transform', 'astra-addon' ),
				'default'   => astra_get_option( 'text-transform-h4' ),
				'transport' => 'postMessage',
				'control'   => 'ast-select',
				'lazy'      => true,
				'priority'  => 28,
				'choices'   => array(
					''           => __( 'Inherit', 'astra-addon' ),
					'none'       => __( 'None', 'astra-addon' ),
					'capitalize' => __( 'Capitalize', 'astra-addon' ),
					'uppercase'  => __( 'Uppercase', 'astra-addon' ),
					'lowercase'  => __( 'Lowercase', 'astra-addon' ),
				),
				'transport' => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h4',
					),
				),
			),

			/**
			 * Option: Heading 4 (H4) Font Size
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[font-size-h4]',
				'type'        => 'control',
				'control'     => 'ast-responsive',
				'section'     => 'section-typography',
				'default'     => astra_get_option( 'font-size-h4' ),
				'transport'   => 'postMessage',
				'lazy'        => true,
				'context'     => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h4',
					),
				),
				'priority'    => 28,
				'title'       => __( 'Size', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
			),

			/**
			 * Option: Heading <H4> Line Height
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[line-height-h4]',
				'type'              => 'control',
				'section'           => $section,
				'default'           => astra_get_option( 'line-height-h4' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'title'             => __( 'Line Height', 'astra-addon' ),
				'control'           => 'ast-slider',
				'priority'          => 28,
				'lazy'              => true,
				'transport'         => 'postMessage',
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h4',
					),
				),
			),

			/**
			 * Option: Heading <H5> Font Family
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[font-family-h5]',
				'type'      => 'control',
				'control'   => 'ast-font',
				'font-type' => 'ast-font-family',
				'default'   => astra_get_option( 'font-family-h5' ),
				'title'     => __( 'Family', 'astra-addon' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h5]',
				'transport' => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h5',
					),
				),
			),

			/**
			 * Option: Heading <H5> Font Weight
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h5]',
				'type'              => 'control',
				'control'           => 'ast-font',
				'font-type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'title'             => __( 'Weight', 'astra-addon' ),
				'section'           => $section,
				'default'           => astra_get_option( 'font-weight-h5' ),
				'priority'          => 28,
				'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h5]',
				'transport'         => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h5',
					),
				),
			),

			/**
			 * Option: Heading <H5> Text Transform
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h5]',
				'type'      => 'control',
				'section'   => $section,
				'lazy'      => true,
				'control'   => 'ast-select',
				'title'     => __( 'Text Transform', 'astra-addon' ),
				'transport' => 'postMessage',
				'default'   => astra_get_option( 'text-transform-h5' ),
				'priority'  => 28,
				'choices'   => array(
					''           => __( 'Inherit', 'astra-addon' ),
					'none'       => __( 'None', 'astra-addon' ),
					'capitalize' => __( 'Capitalize', 'astra-addon' ),
					'uppercase'  => __( 'Uppercase', 'astra-addon' ),
					'lowercase'  => __( 'Lowercase', 'astra-addon' ),
				),
				'transport' => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h5',
					),
				),
			),

			/**
			 * Option: Heading 5 (H5) Font Size
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[font-size-h5]',
				'type'        => 'control',
				'control'     => 'ast-responsive',
				'section'     => 'section-typography',
				'lazy'        => true,
				'default'     => astra_get_option( 'font-size-h5' ),
				'transport'   => 'postMessage',
				'context'     => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h5',
					),
				),
				'priority'    => 28,
				'title'       => __( 'Size', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
			),

			/**
			 * Option: Heading <H5> Line Height
			 */

			array(
				'name'              => ASTRA_THEME_SETTINGS . '[line-height-h5]',
				'type'              => 'control',
				'control'           => 'ast-slider',
				'lazy'      		=> true,
				'section'           => $section,
				'default'           => astra_get_option( 'line-height-h5' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'title'             => __( 'Line Height', 'astra-addon' ),
				'transport'         => 'postMessage',
				'priority'          => 28,
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h5',
					),
				),
			),

			/**
			 * Option: Heading <H6> Font Family
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[font-family-h6]',
				'type'      => 'control',
				'control'   => 'ast-font',
				'font-type' => 'ast-font-family',
				'default'   => astra_get_option( 'font-family-h6' ),
				'title'     => __( 'Family', 'astra-addon' ),
				'section'   => $section,
				'priority'  => 28,
				'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h6]',
				'transport' => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h6',
					),
				),
			),

			/**
			 * Option: Heading 6 (H6) Font Size
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[font-size-h6]',
				'type'        => 'control',
				'control'     => 'ast-responsive',
				'section'     => 'section-typography',
				'lazy'        => true,
				'default'     => astra_get_option( 'font-size-h6' ),
				'transport'   => 'postMessage',
				'context'     => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h6',
					),
				),
				'priority'    => 29,
				'title'       => __( 'Size', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
			),

			/**
			 * Option: Heading <H6> Font Weight
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h6]',
				'type'              => 'control',
				'control'           => 'ast-font',
				'font-type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => astra_get_option( 'font-weight-h6' ),
				'title'             => __( 'Weight', 'astra-addon' ),
				'section'           => $section,
				'priority'          => 28,
				'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h6]',
				'transport'         => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h6',
					),
				),
			),

			/**
			 * Option: Heading <H6> Text Transform
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h6]',
				'section'   => $section,
				'type'      => 'control',
				'control'   => 'ast-select',
				'lazy'      => true,
				'title'     => __( 'Text Transform', 'astra-addon' ),
				'transport' => 'postMessage',
				'priority'  => 28,
				'default'   => astra_get_option( 'text-transform-h6' ),
				'choices'   => array(
					''           => __( 'Inherit', 'astra-addon' ),
					'none'       => __( 'None', 'astra-addon' ),
					'capitalize' => __( 'Capitalize', 'astra-addon' ),
					'uppercase'  => __( 'Uppercase', 'astra-addon' ),
					'lowercase'  => __( 'Lowercase', 'astra-addon' ),
				),
				'transport' => 'postMessage',
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h6',
					),
				),
			),

			/**
			 * Option: Heading <H6> Line Height
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[line-height-h6]',
				'type'              => 'control',
				'section'           => $section,
				'lazy'      		=> true,
				'transport'         => 'postMessage',
				'default'           => astra_get_option( 'line-height-h6' ),
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				'title'             => __( 'Line Height', 'astra-addon' ),
				'control'           => 'ast-slider',
				'priority'          => 29,
				'suffix'            => 'em',
				'input_attrs'       => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
				'context'  => array(
					'',
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
						'operator' => '==',
						'value'    => 'h6',
					),
				),
			),
		);
		return array_merge( $configurations, $_configs );
	}
}

new Astra_Headings_Typo_Configs();
