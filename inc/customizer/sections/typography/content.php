<?php
/**
 * Typography Options for Astra Theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	/**
	 * Option: Heading <H1> Divider
	 */
	$wp_customize->add_control( new Ast_Control_Divider( $wp_customize, AST_THEME_SETTINGS . '[divider-section-h1]', array(
		'type'        => 'ast-divider',
		'section'     => 'section-content-typo',
		'priority'    => 4,
		'label'       => __( 'Heading <H1>', 'astra' ),
		'settings'    => array(),
	) ) );

	/**
	 * Option: Heading <H1> Font Size
	 */
	$wp_customize->add_setting( AST_THEME_SETTINGS . '[font-size-h1]', array(
		'default'           => $defaults['font-size-h1'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'AST_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, AST_THEME_SETTINGS . '[font-size-h1]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-content-typo',
		'priority' => 5,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
	) ) );

	/**
	 * Option: Heading <H2> Divider
	 */
	$wp_customize->add_control( new Ast_Control_Divider( $wp_customize, AST_THEME_SETTINGS . '[divider-section-h2]', array(
		'type'        => 'ast-divider',
		'section'     => 'section-content-typo',
		'priority'    => 9,
		'label'       => __( 'Heading <H2>', 'astra' ),
		'settings'    => array(),
	) ) );

	/**
	 * Option: Heading <H2> Font Size
	 */
	$wp_customize->add_setting( AST_THEME_SETTINGS . '[font-size-h2]', array(
		'default'           => $defaults['font-size-h2'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'AST_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, AST_THEME_SETTINGS . '[font-size-h2]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-content-typo',
		'priority' => 10,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
	) ) );

	/**
	 * Option: Heading <H3> Divider
	 */
	$wp_customize->add_control( new Ast_Control_Divider( $wp_customize, AST_THEME_SETTINGS . '[divider-section-h3]', array(
		'type'        => 'ast-divider',
		'section'     => 'section-content-typo',
		'priority'    => 14,
		'label'       => __( 'Heading <H3>', 'astra' ),
		'settings'    => array(),
	) ) );

	/**
	 * Option: Heading <H3> Font Size
	 */
	$wp_customize->add_setting( AST_THEME_SETTINGS . '[font-size-h3]', array(
		'default'           => $defaults['font-size-h3'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'AST_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, AST_THEME_SETTINGS . '[font-size-h3]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-content-typo',
		'priority' => 15,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
	) ) );

	/**
	 * Option: Heading <H4> Divider
	 */
	$wp_customize->add_control( new Ast_Control_Divider( $wp_customize, AST_THEME_SETTINGS . '[divider-section-h4]', array(
		'label'       => __( 'Heading <H4>', 'astra' ),
		'section'     => 'section-content-typo',
		'type'        => 'ast-divider',
		'priority'    => 19,
		'settings'    => array(),
	) ) );

	/**
	 * Option: Heading <H4> Font Size
	 */
	$wp_customize->add_setting( AST_THEME_SETTINGS . '[font-size-h4]', array(
		'default'           => $defaults['font-size-h4'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'AST_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, AST_THEME_SETTINGS . '[font-size-h4]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-content-typo',
		'priority' => 20,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
	) ) );

	/**
	 * Option: Heading <H5> Divider
	 */
	$wp_customize->add_control( new Ast_Control_Divider( $wp_customize, AST_THEME_SETTINGS . '[divider-section-h5]', array(
		'type'        => 'ast-divider',
		'section'     => 'section-content-typo',
		'priority'    => 24,
		'label'       => __( 'Heading <H5>', 'astra' ),
		'settings'    => array(),
	) ) );

	/**
	 * Option: Heading <H5> Font Size
	 */
	$wp_customize->add_setting( AST_THEME_SETTINGS . '[font-size-h5]', array(
		'default'           => $defaults['font-size-h5'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'AST_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, AST_THEME_SETTINGS . '[font-size-h5]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-content-typo',
		'priority' => 25,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
	) ) );

	/**
	 * Option: Heading <H6> Divider
	 */
	$wp_customize->add_control( new Ast_Control_Divider( $wp_customize, AST_THEME_SETTINGS . '[divider-section-h6]', array(
		'label'       => __( 'Heading <H6>', 'astra' ),
		'section'     => 'section-content-typo',
		'type'        => 'ast-divider',
		'priority'    => 29,
		'settings'    => array(),
	) ) );

	/**
	 * Option: Heading <H6> Font Size
	 */
	$wp_customize->add_setting( AST_THEME_SETTINGS . '[font-size-h6]', array(
		'default'           => $defaults['font-size-h6'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'AST_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, AST_THEME_SETTINGS . '[font-size-h6]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-content-typo',
		'priority' => 30,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
	) ) );
