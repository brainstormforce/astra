<?php
/**
 * Site Identity Typography Options for Astra Theme.
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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-header-typo-title]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-header-typo',
				'priority' => 5,
				'label'    => __( 'Site Title', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Site Title Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-site-title]', array(
			'default'           => astra_get_option( 'font-size-site-title' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-site-title]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-header-typo',
				'priority'    => 10,
				'label'       => __( 'Font Size', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
			)
		)
	);

	/**
	 * Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-header-typo-tagline]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-header-typo',
				'priority' => 15,
				'label'    => __( 'Site Tagline', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Site Tagline Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-site-tagline]', array(
			'default'           => astra_get_option( 'font-size-site-tagline' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-site-tagline]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-header-typo',
				'priority'    => 20,
				'label'       => __( 'Font Size', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
			)
		)
	);
