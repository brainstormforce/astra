<?php
/**
 * Favicon Options for Astra Theme.
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[ast-site-logo-divider]', array(
				'type'     => 'ast-divider',
				'section'  => 'title_tagline',
				'priority' => 5,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Display Title
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[display-site-title]', array(
			'default'           => astra_get_option( 'display-site-title' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[display-site-title]', array(
			'type'        => 'checkbox',
			'section'     => 'title_tagline',
			'label'       => __( 'Display Site Title', 'astra' ),
			'priority'    => 6,
		)
	);

	/**
	 * Option: Display Tagline
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[display-site-tagline]', array(
			'default'           => astra_get_option( 'display-site-tagline' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
			'priority'          => 5,
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[display-site-tagline]', array(
			'type'        => 'checkbox',
			'section'     => 'title_tagline',
			'label'       => __( 'Display Site Tagline', 'astra' ),
		)
	);

	/**
	 * Option: Divider
	*/
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[ast-site-icon-divider]', array(
				'type'     => 'ast-divider',
				'section'  => 'title_tagline',
				'priority' => 50,
				'settings' => array(),
			)
		)
	);

