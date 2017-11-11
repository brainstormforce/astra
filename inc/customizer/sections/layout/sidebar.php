<?php
/**
 * Sidebar Options for Astra Theme.
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
	 * Option: Default Sidebar Position
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-sidebar-layout]', array(
			'default'           => astra_get_option( 'site-sidebar-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-sidebar-layout]', array(
			'type'     => 'select',
			'section'  => 'section-sidebars',
			'priority' => 5,
			'label'    => __( 'Default Layout', 'astra' ),
			'choices'  => array(
				'no-sidebar'    => __( 'No Sidebar', 'astra' ),
				'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
				'right-sidebar' => __( 'Right Sidebar', 'astra' ),
			),
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-page-sidebar-layout-divider]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-sidebars',
				'priority' => 5,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Page
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-page-sidebar-layout]', array(
			'default'           => astra_get_option( 'single-page-sidebar-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-page-sidebar-layout]', array(
			'type'     => 'select',
			'section'  => 'section-sidebars',
			'priority' => 5,
			'label'    => __( 'Pages', 'astra' ),
			'choices'  => array(
				'default'       => __( 'Default', 'astra' ),
				'no-sidebar'    => __( 'No Sidebar', 'astra' ),
				'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
				'right-sidebar' => __( 'Right Sidebar', 'astra' ),
			),
		)
	);

	/**
	 * Option: Blog Post
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-post-sidebar-layout]', array(
			'default'           => astra_get_option( 'single-post-sidebar-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-post-sidebar-layout]', array(
			'type'     => 'select',
			'section'  => 'section-sidebars',
			'priority' => 5,
			'label'    => __( 'Blog Posts', 'astra' ),
			'choices'  => array(
				'default'       => __( 'Default', 'astra' ),
				'no-sidebar'    => __( 'No Sidebar', 'astra' ),
				'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
				'right-sidebar' => __( 'Right Sidebar', 'astra' ),
			),
		)
	);

	/**
	 * Option: Blog Post Archive
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[archive-post-sidebar-layout]', array(
			'default'           => astra_get_option( 'archive-post-sidebar-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[archive-post-sidebar-layout]', array(
			'type'     => 'select',
			'section'  => 'section-sidebars',
			'priority' => 5,
			'label'    => __( 'Blog Post Archives', 'astra' ),
			'choices'  => array(
				'default'       => __( 'Default', 'astra' ),
				'no-sidebar'    => __( 'No Sidebar', 'astra' ),
				'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
				'right-sidebar' => __( 'Right Sidebar', 'astra' ),
			),
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-sidebar-width]', array(
				'section'  => 'section-sidebars',
				'type'     => 'ast-divider',
				'priority' => 10,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Primary Content Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-sidebar-width]', array(
			'default'           => 30,
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-sidebar-width]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-sidebars',
				'priority'    => 15,
				'label'       => __( 'Sidebar Width', 'astra' ),
				'suffix'      => '%',
				'input_attrs' => array(
					'min'  => 15,
					'step' => 1,
					'max'  => 50,
				),
			)
		)
	);
