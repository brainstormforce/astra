<?php
/**
 * Bottom Footer Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.12
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	
	/**
	 * Option: Footer Widget Tabs
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-widget-tabs]', array(
			'type' => 'option',
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Radio_Tabs(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-widget-tabs]', array(
				'type'     => 'ast-radio-tabs',
				'label'    => __( 'Footer Widget Tabs', 'astra' ),
				'section'  => 'section-footer-adv',
				'priority' => 0,
				'choices'  => apply_filters( 'astra_customizer_footer_widget_tabs', array(
					'layout'     => array(
						ASTRA_THEME_SETTINGS . '[footer-adv]'
					),
					'colors'     => array(
						ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-text-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-text-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-link-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-link-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-link-h-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-link-h-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-background-divider]',
						ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]',
						ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]',
					),
					'typography' => array(),
				) ),
			)
		)
	);

	/**
	 * Option: Footer Widgets Layout Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv]', array(
			'default'           => astra_get_option( 'footer-adv' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv]', array(
				'type'    => 'ast-radio-image',
				'label'   => __( 'Footer Widgets Layout', 'astra' ),
				'section' => 'section-footer-adv',
				'choices' => array(
					'disabled' => array(
						'label' => __( 'Disable', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/no-adv-footer-115x48.png',
					),
					'layout-4' => array(
						'label' => __( 'Layout 4', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/layout-4-115x48.png',
					),
				),
			)
		)
	);

	/**
	 * Option: Widget Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-color]', array(
				'label'   => __( 'Widget Title Color', 'astra' ),
				'section' => 'section-footer-adv',
			)
		)
	);

	/**
	 * Option: Text Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-text-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-text-color]', array(
				'label'   => __( 'Text Color', 'astra' ),
				'section' => 'section-footer-adv',
			)
		)
	);

	/**
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-link-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-link-color]', array(
				'label'   => __( 'Link Color', 'astra' ),
				'section' => 'section-footer-adv',
			)
		)
	);

	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-link-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-link-h-color]', array(
				'label'   => __( 'Link Hover Color', 'astra' ),
				'section' => 'section-footer-adv',
			)
		)
	);


	/**
	 * Option: Background Color
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-background-divider]', array(
				'section' => 'section-footer-adv',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]', array(
				'type'    => 'ast-color',
				'label'   => __( 'Background Color', 'astra' ),
				'section' => 'section-footer-adv',
			)
		)
	);
