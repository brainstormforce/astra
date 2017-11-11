<?php
/**
 * Bottom Footer Options for Astra Theme.
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
	 * Option: Footer Bar Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-sml-layout]', array(
			'default'           => astra_get_option( 'footer-sml-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-sml-layout]', array(
				'type'     => 'ast-radio-image',
				'section'  => 'section-footer-small',
				'priority' => 5,
				'label'    => __( 'Footer Bar Layout', 'astra' ),
				'choices'  => array(
					'disabled'            => array(
						'label' => __( 'Disabled', 'astra' ),
						'path'  => ASTRA_THEME_URI . 'assets/images/disabled-footer-76x48.png',
					),
					'footer-sml-layout-1' => array(
						'label' => __( 'Footer Bar Layout 1', 'astra' ),
						'path'  => ASTRA_THEME_URI . 'assets/images/footer-layout-1-76x48.png',
					),
					'footer-sml-layout-2' => array(
						'label' => __( 'Footer Bar Layout 2', 'astra' ),
						'path'  => ASTRA_THEME_URI . 'assets/images/footer-layout-2-76x48.png',
					),
				),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[section-ast-small-footer-layout-info]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-footer-small',
				'priority' => 10,
				'settings' => array(),
			)
		)
	);


	/**
	 *  Section: Section 1
	 */
	/**
	 * Option: Section 1
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-1]', array(
			'default'           => astra_get_option( 'footer-sml-section-1' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-1]', array(
			'type'     => 'select',
			'section'  => 'section-footer-small',
			'priority' => 15,
			'label'    => __( 'Section 1', 'astra' ),
			'choices'  => array(
				''       => __( 'None', 'astra' ),
				'menu'   => __( 'Footer Menu', 'astra' ),
				'custom' => __( 'Custom Text', 'astra' ),
				'widget' => __( 'Widget', 'astra' ),
			),
		)
	);

	/**
	 * Option: Section 1 Custom Text
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-1-credit]', array(
			'default'           => astra_get_option( 'footer-sml-section-1-credit' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_html' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-1-credit]', array(
			'type'     => 'textarea',
			'section'  => 'section-footer-small',
			'priority' => 20,
			'label'    => __( 'Section 1 Custom Text', 'astra' ),
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[footer-sml-section-1-credit]', array(
				'selector'            => '.ast-small-footer-section-1',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Partials', '_render_footer_sml_section_1_credit' ),
			)
		);
	}

	/**
	 * Option: Section 2
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-2]', array(
			'default'           => astra_get_option( 'footer-sml-section-2' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-2]', array(
			'type'     => 'select',
			'section'  => 'section-footer-small',
			'priority' => 25,
			'label'    => __( 'Section 2', 'astra' ),
			'choices'  => array(
				''       => __( 'None', 'astra' ),
				'menu'   => __( 'Footer Menu', 'astra' ),
				'custom' => __( 'Custom Text', 'astra' ),
				'widget' => __( 'Widget', 'astra' ),
			),
		)
	);

	/**
	 * Option: Section 2 Custom Text
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-2-credit]', array(
			'default'           => astra_get_option( 'footer-sml-section-2-credit' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_html' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-sml-section-2-credit]', array(
			'type'     => 'textarea',
			'section'  => 'section-footer-small',
			'priority' => 30,
			'label'    => __( 'Section 2 Custom Text', 'astra' ),
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[footer-sml-section-2-credit]', array(
				'selector'            => '.ast-small-footer-section-2',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Partials', '_render_footer_sml_section_2_credit' ),
			)
		);
	}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[section-ast-small-footer-typography]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-footer-small',
				'priority' => 35,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Footer Top Border
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-sml-divider]', array(
			'default'           => astra_get_option( 'footer-sml-divider' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-sml-divider]', array(
			'type'        => 'number',
			'section'     => 'section-footer-small',
			'priority'    => 40,
			'label'       => __( 'Footer Bar Top Border', 'astra' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
		)
	);

	/**
	 * Option: Footer Top Border Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-sml-divider-color]', array(
			'default'           => '#7a7a7a',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-sml-divider-color]', array(
				'section'  => 'section-footer-small',
				'priority' => 45,
				'label'    => __( 'Footer Bar Top Border Color', 'astra' ),
			)
		)
	);

	/**
	 * Option: Header Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-layout-width]', array(
			'default'           => astra_get_option( 'footer-layout-width' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-layout-width]', array(
			'type'     => 'select',
			'section'  => 'section-footer-small',
			'priority' => 35,
			'label'    => __( 'Footer Bar Width', 'astra' ),
			'choices'  => array(
				'full'    => __( 'Full Width', 'astra' ),
				'content' => __( 'Content Width', 'astra' ),
			),
		)
	);
