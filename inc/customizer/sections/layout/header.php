<?php
/**
 * Header Options for Astra Theme.
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

$header_rt_sections = array(
	'none'      => __( 'None', 'astra' ),
	'search'    => __( 'Search', 'astra' ),
	'text-html' => __( 'Text / HTML', 'astra' ),
	'widget'    => __( 'Widget', 'astra' ),
);


	/**
	 * Option: Header Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-layouts]', array(
			'default'           => astra_get_option( 'header-layouts' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-layouts]', array(
				'section'  => 'section-header',
				'priority' => 5,
				'label'    => __( 'Header', 'astra' ),
				'type'     => 'ast-radio-image',
				'choices'  => array(
					'header-main-layout-1' => array(
						'label' => __( 'Logo Left', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-1-60x60.png',
					),
					'header-main-layout-2' => array(
						'label' => __( 'Logo Center', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-2-60x60.png',
					),
					'header-main-layout-3' => array(
						'label' => __( 'Logo Right', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-3-60x60.png',
					),
				),
			)
		)
	);

	/**
	 * Option: Disable Menu
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[disable-primary-nav]', array(
			'default'           => astra_get_option( 'disable-primary-nav' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[disable-primary-nav]', array(
			'type'     => 'checkbox',
			'section'  => 'section-header',
			'label'    => __( 'Disable Menu', 'astra' ),
			'priority' => 5,
		)
	);

	/**
	 * Option: Custom Menu Item
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-rt-section]', array(
			'default'           => astra_get_option( 'header-main-rt-section' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-main-rt-section]', array(
			'type'     => 'select',
			'section'  => 'section-header',
			'priority' => 5,
			'label'    => __( 'Custom Menu Item', 'astra' ),
			'choices'  => $header_rt_sections,
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-rt-section]', array(
			'default'           => astra_get_option( 'header-main-rt-section' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-main-rt-section]', array(
			'type'     => 'select',
			'section'  => 'section-header',
			'priority' => 5,
			'label'    => __( 'Custom Menu Item', 'astra' ),
			'choices'  => apply_filters(
				'astra_header_section_elements',
				array(
					'none'      => __( 'None', 'astra' ),
					'search'    => __( 'Search', 'astra' ),
					'text-html' => __( 'Text / HTML', 'astra' ),
					'widget'    => __( 'Widget', 'astra' ),
				),
				'primary-header'
			),
		)
	);

	/**
	 * Option: Display outside menu
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-display-outside-menu]', array(
			'default'           => astra_get_option( 'header-display-outside-menu' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-display-outside-menu]', array(
			'type'     => 'checkbox',
			'section'  => 'section-header',
			'label'    => __( 'Take custom menu item outside', 'astra' ),
			'priority' => 5,
		)
	);


	/**
	 * Option: Right Section Text / HTML
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-rt-section-html]', array(
			'default'           => astra_get_option( 'header-main-rt-section-html' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_html' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-main-rt-section-html]', array(
			'type'     => 'textarea',
			'section'  => 'section-header',
			'priority' => 10,
			'label'    => __( 'Custom Menu Text / HTML', 'astra' ),
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[header-main-rt-section-html]', array(
				'selector'            => '.main-header-bar .ast-masthead-custom-menu-items .ast-custom-html',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Partials', '_render_header_main_rt_section_html' ),
			)
		);
	}

	/**
	 * Option: Bottom Border Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-sep]', array(
			'default'           => astra_get_option( 'header-main-sep' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-main-sep]', array(
			'type'        => 'number',
			'section'     => 'section-header',
			'priority'    => 25,
			'label'       => __( 'Bottom Border Size', 'astra' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
		)
	);

	/**
	 * Option: Bottom Border Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-sep-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-main-sep-color]', array(
				'section'  => 'section-header',
				'priority' => 30,
				'label'    => __( 'Bottom Border Color', 'astra' ),
			)
		)
	);

	/**
	 * Option: Header Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-layout-width]', array(
			'default'           => astra_get_option( 'header-main-layout-width' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-main-layout-width]', array(
			'type'     => 'select',
			'section'  => 'section-header',
			'priority' => 35,
			'label'    => __( 'Header Width', 'astra' ),
			'choices'  => array(
				'full'    => __( 'Full Width', 'astra' ),
				'content' => __( 'Content Width', 'astra' ),
			),
		)
	);
