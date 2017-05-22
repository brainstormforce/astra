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
	 * Option: Divider
	 */
	$wp_customize->add_control( new Ast_Control_Divider( $wp_customize, AST_THEME_SETTINGS . '[divider-section-archive-typo-archive-title]', array(
		'type'        => 'ast-divider',
		'section'     => 'section-archive-typo',
		'priority'    => 5,
		'label'       => __( 'Blog Post Title', 'astra' ),
		'settings'    => array(),
	) ) );

	/**
	 * Option: Blog - Post Title Font Size
	 */
	$wp_customize->add_setting( AST_THEME_SETTINGS . '[font-size-page-title]', array(
		'default'           => $defaults['font-size-page-title'],
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'AST_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
	) );
	$wp_customize->add_control( new Ast_Control_Responsive( $wp_customize, AST_THEME_SETTINGS . '[font-size-page-title]', array(
		'type'     => 'ast-responsive',
		'section'  => 'section-archive-typo',
		'priority' => 10,
		'label'    => __( 'Font Size', 'astra' ),
		'input_attrs' => array(
			'min' => 0,
		),
	) ) );
