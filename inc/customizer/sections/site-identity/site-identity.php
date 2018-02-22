<?php
/**
 * Favicon Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Retina logo selector
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[ast-header-retina-logo]', array(
			'default'           => astra_get_option( 'ast-header-retina-logo' ),
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[ast-header-retina-logo]', array(
				'section'        => 'title_tagline',
				'priority'       => 5,
				'label'          => __( 'Retina Logo', 'astra' ),
				'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
			)
		)
	);

	/**
	 * Option: Logo Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[ast-header-responsive-logo-width]', array(
			'default'           => array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[ast-header-responsive-logo-width]', array(
				'type'        => 'ast-responsive-slider',
				'section'     => 'title_tagline',
				'priority'    => 5,
				'label'       => __( 'Logo Width', 'astra' ),
				'input_attrs' => array(
					'min'  => 50,
					'step' => 1,
					'max'  => 600,
				),
			)
		)
	);

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
			'type'     => 'checkbox',
			'section'  => 'title_tagline',
			'label'    => __( 'Display Site Title', 'astra' ),
			'priority' => 6,
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
			'type'    => 'checkbox',
			'section' => 'title_tagline',
			'label'   => __( 'Display Site Tagline', 'astra' ),
		)
	);
	
	/**
	 * Option: Enable Schema & Microdata
	 */
	$wp_customize->add_setting( 'astra_schema_markup', array(
			'default'           => true,
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
			'priority'          => 5,
		)
	);
	$wp_customize->add_control( 'astra_schema_markup', array(
			'type'    => 'checkbox',
			'section' => 'title_tagline',
			'settings'=> 'astra_schema_markup',
			'label'   => __( 'Enable Schema & Microdata', 'astra' ),
		)
	);


	/**
	 * Option: Disable Menu
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[logo-title-inline]', array(
			'default'           => astra_get_option( 'logo-title-inline' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[logo-title-inline]', array(
			'type'     => 'checkbox',
			'section'  => 'title_tagline',
			'label'    => __( 'Inline Logo & Site Title', 'astra' ),
			'priority' => 10,
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
