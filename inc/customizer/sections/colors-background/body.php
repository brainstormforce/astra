<?php
/**
 * Styling Options for Astra Theme.
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
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[link-color]', array(
			'default'           => '#0274be',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[link-color]', array(
				'section'  => 'section-colors-body',
				'priority' => 5,
				'label'    => __( 'Theme Color / Link Color', 'astra' ),
			)
		)
	);

	/**
	 * Option: Text Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-color]', array(
			'default'           => '#3a3a3a',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[text-color]', array(
				'section'  => 'section-colors-body',
				'priority' => 10,
				'label'    => __( 'Text Color', 'astra' ),
			)
		)
	);


	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[link-h-color]', array(
			'default'           => '#3a3a3a',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[link-h-color]', array(
				'section'  => 'section-colors-body',
				'priority' => 15,
				'label'    => __( 'Link Hover Color', 'astra' ),
			)
		)
	);


	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-outside-bg-color]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-colors-body',
				'priority' => 20,
				'settings' => array(),
			)
		)
	);
