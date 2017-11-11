<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-color]', array(
				'label'   => __( 'Text Color', 'astra' ),
				'section' => 'section-colors-footer',
			)
		)
	);

	/**
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-link-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-link-color]', array(
				'label'   => __( 'Link Color', 'astra' ),
				'section' => 'section-colors-footer',
			)
		)
	);

	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-link-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-link-h-color]', array(
				'label'   => __( 'Link Hover Color', 'astra' ),
				'section' => 'section-colors-footer',
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-footer-image]', array(
				'section'  => 'section-colors-footer',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-bg-color]', array(
				'type'    => 'ast-color',
				'label'   => __( 'Background Color', 'astra' ),
				'section' => 'section-colors-footer',
			)
		)
	);
