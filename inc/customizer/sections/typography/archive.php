<?php
/**
 * Typography Options for Astra Theme.
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-archive-summary-box-typo]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-archive-typo',
				'priority' => 0,
				'label'    => __( 'Archive Summary Box Title', 'astra' ),
				'settings' => array(),
			)
		)
	);
	/**
	 * Option: Archive Summary Box Title Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-archive-summary-title]', array(
			'default'           => astra_get_option( 'font-size-archive-summary-title' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-archive-summary-title]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-archive-typo',
				'priority'    => 4,
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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-archive-typo-archive-title]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-archive-typo',
				'priority' => 5,
				'label'    => __( 'Blog Post Title', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Blog - Post Title Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-page-title]', array(
			'default'           => astra_get_option( 'font-size-page-title' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-page-title]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-archive-typo',
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
