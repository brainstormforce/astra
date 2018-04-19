<?php
/**
 * General Options for Astra Theme Mobile Header.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra x.x.x
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option: Mobile Header Breakpoint
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[mobile-header-breakpoint]', array(
		'default'           => astra_get_option( 'mobile-header-breakpoint' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Slider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-breakpoint]', array(
			'type'        => 'ast-slider',
			'section'     => 'section-mobile-header',
			'priority'    => 10,
			'label'       => __( 'Enter Breakpoint', 'astra' ),
			'suffix'      => '',
			'input_attrs' => array(
				'min'  => 100,
				'step' => 1,
				'max'  => 1920,
			),
		)
	)
);


/**
 * Option: Mobile header logo
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[mobile-header-logo]', array(
		'default'           => astra_get_option( 'mobile-header-logo' ),
		'type'              => 'option',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-logo]', array(
			'section'        => 'section-mobile-header',
			'priority'       => 20,
			'label'          => __( 'Logo (optional)', 'astra-addon' ),
			'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
		)
	)
);

/**
 * Option: Display Title for mobile
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[display-mobile-site-title]', array(
		'default'           => astra_get_option( 'display-mobile-site-title' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[display-mobile-site-title]', array(
		'type'     => 'checkbox',
		'section'  => 'section-mobile-header',
		'label'    => __( 'Display Site Title', 'astra' ),
		'priority' => 25,
	)
);

/**
 * Option: Display Tagline for mobile
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[display-mobile-site-tagline]', array(
		'default'           => astra_get_option( 'display-mobile-site-tagline' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[display-mobile-site-tagline]', array(
		'type'     => 'checkbox',
		'section'  => 'section-mobile-header',
		'label'    => __( 'Display Site Tagline', 'astra' ),
		'priority' => 30,
	)
);


/**
 * Option: Mobile Menu Label Divider
 */
$wp_customize->add_control(
	new Astra_Control_Divider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[header-main-menu-label-divider]', array(
			'type'     => 'ast-divider',
			'section'  => 'section-mobile-header',
			'priority' => 35,
			'settings' => array(),
		)
	)
);

/**
 * Option: Mobile Menu Label
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[header-main-menu-label]', array(
		'default'           => astra_get_option( 'header-main-menu-label' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[header-main-menu-label]', array(
		'section'  => 'section-mobile-header',
		'priority' => 40,
		'label'    => __( 'Menu Label on Small Devices', 'astra' ),
		'type'     => 'text',
	)
);

/**
 * Option: Mobile Menu Alignment
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[header-main-menu-align]', array(
		'default'           => astra_get_option( 'header-main-menu-align' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[header-main-menu-align]', array(
		'type'        => 'select',
		'section'     => 'section-mobile-header',
		'priority'    => 45,
		'label'       => __( 'Mobile Header Alignment', 'astra' ),
		'description' => __( 'This setting is only applied to the devices below 544px width ', 'astra' ),
		'choices'     => array(
			'inline' => __( 'Inline', 'astra' ),
			'stack'  => __( 'Stack', 'astra' ),
		),
	)
);
