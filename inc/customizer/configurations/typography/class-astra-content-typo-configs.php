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

if ( ! class_exists( 'Astra_Content_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Content_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Content Typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Heading Typography starts here - h1 - h3
				 */

				/**
				 * Option: Heading <H1> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h1]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-h1' ),
					'context'  => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h1',
						),
					),
					'title'     => __( 'Family', 'astra' ),
					'section'   => 'section-typography',
					'priority'  => 28,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h1]',
					'transport' => 'postMessage',
				),

				/**
				 * Option: Heading <H1> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h1]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'context'  => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h1',
						),
					),
					'title'             => __( 'Weight', 'astra' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-h1' ),
					'section'           => 'section-typography',
					'priority'          => 28,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h1]',
					'transport'         => 'postMessage',
				),

				/**
				 * Option: Heading <H1> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h1]',
					'section'   => 'section-typography',
					'default'   => astra_get_option( 'text-transform-h1' ),
					'title'     => __( 'Text Transform', 'astra' ),
					'type'      => 'control',
					'control'   => 'ast-select',
					'context'  => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h1',
						),
					),
					'priority'  => 28,
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
					'transport' => 'postMessage',
				),

				/**
				 * Option: Heading 1 (H1) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h1]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-typography',
					'default'     => astra_get_option( 'font-size-h1' ),
					'transport'   => 'postMessage',
					'context'  => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h1',
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
				 * Option: Heading <H1> Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-h1]',
					'section'           => 'section-typography',
					'default'           => astra_get_option( 'line-height-h1' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'type'              => 'control',
					'context'           => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h1',
						),
					),
					'control'           => 'ast-slider',
					'title'             => __( 'Line Height', 'astra' ),
					'transport'         => 'postMessage',
					'priority'          => 28,
					'suffix'            => 'em',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Heading <H2> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h2]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h2',
						),
					),
					'title'     => __( 'Family', 'astra' ),
					'default'   => astra_get_option( 'font-family-h2' ),
					'section'   => 'section-typography',
					'priority'  => 28,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h2]',
					'transport' => 'postMessage',
				),

				/**
				 * Option: Heading <H2> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h2]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h2',
						),
					),
					'title'             => __( 'Weight', 'astra' ),
					'section'           => 'section-typography',
					'default'           => astra_get_option( 'font-weight-h2' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'priority'          => 28,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h2]',
					'transport'         => 'postMessage',
				),

				/**
				 * Option: Heading <H2> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h2]',
					'section'   => 'section-typography',
					'default'   => astra_get_option( 'text-transform-h2' ),
					'title'     => __( 'Text Transform', 'astra' ),
					'type'      => 'control',
					'lazy'              => true,
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h2',
						),
					),
					'control'   => 'ast-select',
					'transport' => 'postMessage',
					'priority'  => 28,
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
					'transport' => 'postMessage',
				),

				/**
				 * Option: Heading 2 (H2) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h2]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-typography',
					'lazy'        => true,
					'default'     => astra_get_option( 'font-size-h2' ),
					'transport'   => 'postMessage',
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h2',
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
				 * Option: Heading <H2> Line Height
				 */

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-h2]',
					'section'           => 'section-typography',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'context'           => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h2',
						),
					),
					'default'           => astra_get_option( 'line-height-h2' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'transport'         => 'postMessage',
					'title'             => __( 'Line Height', 'astra' ),
					'priority'          => 30,
					'lazy'              => true,
					'suffix'            => 'em',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Heading <H3> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h3]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-h3' ),
					'title'     => __( 'Family', 'astra' ),
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h3',
						),
					),
					'section'   => 'section-typography',
					'priority'  => 28,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h3]',
					'transport' => 'postMessage',
				),

				/**
				 * Option: Heading <H3> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h3]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-h3' ),
					'title'             => __( 'Weight', 'astra' ),
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h3',
						),
					),
					'section'           => 'section-typography',
					'priority'          => 28,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h3]',
					'transport'         => 'postMessage',
				),

				/**
				 * Option: Heading <H3> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h3]',
					'type'      => 'control',
					'section'   => 'section-typography',
					'lazy'              => true,
					'title'     => __( 'Text Transform', 'astra' ),
					'default'   => astra_get_option( 'text-transform-h3' ),
					'transport' => 'postMessage',
					'control'   => 'ast-select',
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h3',
						),
					),
					'priority'  => 28,
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
					'transport' => 'postMessage',
				),
				/**
				 * Option: Heading 3 (H3) Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-h3]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-typography',
					'priority'    => 28,
					'lazy'        => true,
					'default'     => astra_get_option( 'font-size-h3' ),
					'transport'   => 'postMessage',
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h3',
						),
					),
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
				 * Option: Heading <H3> Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-h3]',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'section'           => 'section-typography',
					'lazy'              => true,
					'title'             => __( 'Line Height', 'astra' ),
					'context'   => array(
						'',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[heading-typo-selector]',
							'operator' => '==',
							'value'    => 'h3',
						),
					),
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'line-height-h3' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'priority'          => 28,
					'suffix'            => 'em',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$_configs = array(
					/**
					 * Option: Learn More about Contant Typography
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-content-typography-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-typography',
						'lazy'     => true,
						'priority' => 999,
						'title'    => '',
						'help'     => '<p>' . __( 'More Options Available in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-secondary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
						'settings' => array(),
					),
				);

				$configurations = array_merge( $configurations, $_configs );

			}

			return $configurations;
		}
	}
}

new Astra_Content_Typo_Configs();


