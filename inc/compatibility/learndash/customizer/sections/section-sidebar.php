<?php
/**
 * Content Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option: Divider
 */
$wp_customize->add_control(
	new Astra_Control_Divider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-sidebar-layout-divider]', array(
			'section'  => 'section-sidebars',
			'type'     => 'ast-divider',
			'priority' => 5,
			'settings' => array(),
		)
	)
);

/**
 * Option: LearnDash
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[learndash-sidebar-layout]', array(
		'default'           => astra_get_option( 'learndash-sidebar-layout' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[learndash-sidebar-layout]', array(
		'type'        => 'select',
		'section'     => 'section-sidebars',
		'priority'    => 5,
		'label'       => __( 'LearnDash', 'astra' ),
		'description' => __( 'This layout will apply on all single course, lesson, topic and quiz.', 'astra' ),
		'choices'     => array(
			'default'       => __( 'Default', 'astra' ),
			'no-sidebar'    => __( 'No Sidebar', 'astra' ),
			'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
			'right-sidebar' => __( 'Right Sidebar', 'astra' ),
		),
	)
);
