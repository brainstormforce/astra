<?php
/**
 * General Options for Astra Theme.
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
	 * Option: Site Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-content-layout]', array(
			'default'           => astra_get_option( 'site-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-content-layout]', array(
			'type'    => 'select',
			'section' => 'section-container-layout',
			'label'   => __( 'Default Layout', 'astra' ),
			'choices' => array(
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-page-content-layout-divider]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-container-layout',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Single Page Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-page-content-layout]', array(
			'default'           => astra_get_option( 'single-page-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-page-content-layout]', array(
			'type'    => 'select',
			'section' => 'section-container-layout',
			'label'   => __( 'Pages', 'astra' ),
			'choices' => array(
				'default'                 => __( 'Default', 'astra' ),
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	/**
	 * Option: Single Post Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-post-content-layout]', array(
			'default'           => astra_get_option( 'single-post-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-post-content-layout]', array(
			'type'    => 'select',
			'section' => 'section-container-layout',
			'label'   => __( 'Blog Posts', 'astra' ),
			'choices' => array(
				'default'                 => __( 'Default', 'astra' ),
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	/**
	 * Option: Archive Post Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[archive-post-content-layout]', array(
			'default'           => astra_get_option( 'archive-post-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[archive-post-content-layout]', array(
			'type'    => 'select',
			'section' => 'section-container-layout',
			'label'   => __( 'Blog Post Archives', 'astra' ),
			'choices' => array(
				'default'                 => __( 'Default', 'astra' ),
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	/**
	 * Option: Body Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-outside-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-outside-bg-color]', array(
				'type'     => 'ast-color',
				'section'  => 'section-colors-body',
				'priority' => 25,
				'label'    => __( 'Background Color', 'astra' ),
			)
		)
	);
