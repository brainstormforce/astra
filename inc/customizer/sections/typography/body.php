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
	 * Option: Body & Content Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-base-typo]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-body-typo',
				'priority' => 4,
				'label'    => __( 'Body & Content', 'astra' ),
				'settings' => array(),
			)
		)
	);

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
				'units'       => array(
					'px' => 'px',
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
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			)
		)
	);

	/**
	 * Option: Paragraph Margin Bottom
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[para-margin-bottom]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[para-margin-bottom]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-body-typo',
				'priority'    => 25,
				'label'       => __( 'Paragraph Margin Bottom', 'astra' ),
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 0.5,
					'step' => 0.01,
					'max'  => 5,
				),
			)
		)
	);

	/**
	 * Option: Body & Content Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-headings-typo]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-body-typo',
				'priority' => 30,
				'label'    => __( 'Headings', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Headings Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[headings-font-family]', array(
			'default'           => astra_get_option( 'headings-font-family' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[headings-font-family]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra' ),
				'section'  => 'section-body-typo',
				'priority' => 35,
				'connect'  => ASTRA_THEME_SETTINGS . '[headings-font-weight]',
			)
		)
	);

	/**
	 * Option: Headings Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[headings-font-weight]', array(
			'default'           => astra_get_option( 'headings-font-weight' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[headings-font-weight]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra' ),
				'section'  => 'section-body-typo',
				'priority' => 40,
				'connect'  => ASTRA_THEME_SETTINGS . '[headings-font-family]',
			)
		)
	);

	/**
	 * Option: Headings Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[headings-text-transform]', array(
			'default'           => astra_get_option( 'headings-text-transform' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[headings-text-transform]', array(
			'section'  => 'section-body-typo',
			'label'    => __( 'Text Transform', 'astra' ),
			'type'     => 'select',
			'priority' => 45,
			'choices'  => array(
				''           => __( 'Inherit', 'astra' ),
				'none'       => __( 'None', 'astra' ),
				'capitalize' => __( 'Capitalize', 'astra' ),
				'uppercase'  => __( 'Uppercase', 'astra' ),
				'lowercase'  => __( 'Lowercase', 'astra' ),
			),
		)
	);
