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
	 * Option: Heading 1 (H1) Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-h1]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-content-typo',
				'priority' => 4,
				'label'    => __( 'Heading 1 (H1)', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading 1 (H1) Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-h1]', array(
			'default'           => astra_get_option( 'font-size-h1' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-h1]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-content-typo',
				'priority'    => 5,
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
	 * Option: Heading 2 (H2) Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-h2]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-content-typo',
				'priority' => 9,
				'label'    => __( 'Heading 2 (H2)', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading 2 (H2) Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-h2]', array(
			'default'           => astra_get_option( 'font-size-h2' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-h2]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-content-typo',
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
	 * Option: Heading 3 (H3) Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-h3]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-content-typo',
				'priority' => 14,
				'label'    => __( 'Heading 3 (H3)', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading 3 (H3) Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-h3]', array(
			'default'           => astra_get_option( 'font-size-h3' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-h3]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-content-typo',
				'priority'    => 15,
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
	 * Option: Heading 4 (H4) Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-h4]', array(
				'label'    => __( 'Heading 4 (H4)', 'astra' ),
				'section'  => 'section-content-typo',
				'type'     => 'ast-divider',
				'priority' => 19,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading 4 (H4) Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-h4]', array(
			'default'           => astra_get_option( 'font-size-h4' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-h4]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-content-typo',
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

	/**
	 * Option: Heading 5 (H5) Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-h5]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-content-typo',
				'priority' => 24,
				'label'    => __( 'Heading 5 (H5)', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading 5 (H5) Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-h5]', array(
			'default'           => astra_get_option( 'font-size-h5' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-h5]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-content-typo',
				'priority'    => 25,
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
	 * Option: Heading 6 (H6) Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-h6]', array(
				'label'    => __( 'Heading 6 (H6)', 'astra' ),
				'section'  => 'section-content-typo',
				'type'     => 'ast-divider',
				'priority' => 29,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading 6 (H6) Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-h6]', array(
			'default'           => astra_get_option( 'font-size-h6' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-h6]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-content-typo',
				'priority'    => 30,
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
