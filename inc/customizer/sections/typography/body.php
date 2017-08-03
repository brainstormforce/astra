<?php
/**
 * Body Typography Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[body-font-family]', array(
			'default'           => astra_get_option( 'body-font-family' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[body-font-family]', array(
				'type'        => 'ast-font-family',
				'ast_inherit' => __( 'Default', 'astra' ),
				'section'     => 'section-body-typo',
				'priority'    => 5,
				'label'       => __( 'Font Family', 'astra' ),
				'connect'     => ASTRA_THEME_SETTINGS . '[body-font-weight]',
			)
		)
	);

	/**
	 * Option: Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[body-font-weight]', array(
			'default'           => astra_get_option( 'body-font-weight' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[body-font-weight]', array(
				'type'        => 'ast-font-weight',
				'ast_inherit' => __( 'Default', 'astra' ),
				'section'     => 'section-body-typo',
				'priority'    => 10,
				'label'       => __( 'Font Weight', 'astra' ),
				'connect'     => ASTRA_THEME_SETTINGS . '[body-font-family]',
			)
		)
	);

	/**
	 * Option: Body Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[body-text-transform]', array(
			'default'           => astra_get_option( 'body-text-transform' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[body-text-transform]', array(
			'type'     => 'select',
			'section'  => 'section-body-typo',
			'priority' => 15,
			'label'    => __( 'Text Transform', 'astra' ),
			'choices'  => array(
				''           => __( 'Default', 'astra' ),
				'none'       => __( 'None', 'astra' ),
				'capitalize' => __( 'Capitalize', 'astra' ),
				'uppercase'  => __( 'Uppercase', 'astra' ),
				'lowercase'  => __( 'Lowercase', 'astra' ),
			),
		)
	);

	/**
	 * Option: Body Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-body]', array(
			'default'           => astra_get_option( 'font-size-body' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-body]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-body-typo',
				'priority'    => 20,
				'label'       => __( 'Font Size', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'units' => array(
					'px'  => 'px',
				),
			)
		)
	);

	/**
	 * Option: Body Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[body-line-height]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[body-line-height]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-body-typo',
				'priority'    => 25,
				'label'       => __( 'Line Height', 'astra' ),
				'suffix'      => '',
				'input_attrs' => array(
					'min'    => 1,
					'step'   => 0.01,
					'max'    => 5,
				),
			)
		)
	);
