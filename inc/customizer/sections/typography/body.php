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
	$wp_customize->add_setting( ASTRA_THEME_SETTINGS . '[body-font-family]', array(
		'default'           => $defaults['body-font-family'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Astra_Control_Typography( $wp_customize, ASTRA_THEME_SETTINGS . '[body-font-family]', array(
		'type'     => 'ast-font-family',
		'section'  => 'section-body-typo',
		'priority' => 5,
		'label'    => __( 'Font Family', 'astra' ),
		'connect'  => ASTRA_THEME_SETTINGS . '[body-font-weight]',
	) ) );

	/**
	 * Option: Font Weight
	 */
	$wp_customize->add_setting( ASTRA_THEME_SETTINGS . '[body-font-weight]', array(
		'default'           => $defaults['body-font-weight'],
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
	) );
	$wp_customize->add_control( new Astra_Control_Typography( $wp_customize, ASTRA_THEME_SETTINGS . '[body-font-weight]', array(
		'type'     => 'ast-font-weight',
		'section'  => 'section-body-typo',
		'priority' => 10,
		'label'    => __( 'Font Weight', 'astra' ),
		'connect'  => ASTRA_THEME_SETTINGS . '[body-font-family]',
	) ) );

	/**
	 * Option: Body Text Transform
	 */
	$wp_customize->add_setting( ASTRA_THEME_SETTINGS . '[body-text-transform]', array(
		'default'           => $defaults['body-text-transform'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
	) );
	$wp_customize->add_control( ASTRA_THEME_SETTINGS . '[body-text-transform]', array(
		'type'     => 'select',
		'section'  => 'section-body-typo',
		'priority' => 15,
		'label'    => __( 'Text Transform', 'astra' ),
		'choices'  => array(
			''           => __( 'Inherit', 'astra' ),
			'none'       => __( 'None', 'astra' ),
			'capitalize' => __( 'Capitalize', 'astra' ),
			'uppercase'  => __( 'Uppercase', 'astra' ),
			'lowercase'  => __( 'Lowercase', 'astra' ),
		),
	) );

	/**
	 * Option: Body Font Size
	 */
	$wp_customize->add_setting( ASTRA_THEME_SETTINGS . '[font-size-body]', array(
		'default'           => $defaults['font-size-body'],
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Astra_Control_Responsive( $wp_customize, ASTRA_THEME_SETTINGS . '[font-size-body]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-body-typo',
		'priority' => 20,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
		'units'	=> array(
			'px'  => 'px',
		),
	) ) );

	/**
	 * Option: Body Line Height
	 */
	$wp_customize->add_setting( ASTRA_THEME_SETTINGS . '[body-line-height]', array(
		'default'           => $defaults['body-line-height'],
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Astra_Control_Responsive( $wp_customize, ASTRA_THEME_SETTINGS . '[body-line-height]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-body-typo',
		'priority' => 25,
		'label'    => __( 'Line Height', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
		'units'	=> array(
			''    => '',
			'px'  => 'px',
			'em'  => 'em',
		),
	) ) );
