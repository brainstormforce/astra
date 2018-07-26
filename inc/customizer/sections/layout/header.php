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
				'label'    => __( 'Header Layout', 'astra' ),
				'type'     => 'ast-radio-image',
				'choices'  => array(
					'header-main-layout-1' => array(
						'label' => __( 'Logo Left', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-1-76x48.png',
					),
					'header-main-layout-2' => array(
						'label' => __( 'Logo Center', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-2-76x48.png',
					),
					'header-main-layout-3' => array(
						'label' => __( 'Logo Right', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-3-76x48.png',
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
	 * Option: Last Item in Menu
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
			'label'    => __( 'Last Item in Menu', 'astra' ),
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
			'label'    => __( 'Last Item in Menu', 'astra' ),
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
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-main-sep-color]', array(
				'type'     => 'ast-color',
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

	/**
	 * Option: Mobile Menu Label Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Heading(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-main-menu-label-divider]', array(
				'type'     => 'ast-heading',
				'section'  => 'section-header',
				'priority' => 35,
				'label'    => __( 'Mobile Header', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Mobile Header Breakpoint
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-header-breakpoint]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-breakpoint]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-header',
				'priority'    => 40,
				'label'       => __( 'Menu Breakpoint', 'astra' ),
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 100,
					'step' => 1,
					'max'  => 1921,
				),
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
			'section'  => 'section-header',
			'priority' => 45,
			'label'    => __( 'Menu Label on Small Devices', 'astra' ),
			'type'     => 'text',
		)
	);

	/**
	 * Option: Hide Last item in Menu on mobile device
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]', array(
			'default'           => astra_get_option( 'hide-custom-menu-mobile' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]', array(
			'type'     => 'checkbox',
			'section'  => 'section-header',
			'label'    => __( 'Hide Last item in Menu on mobile', 'astra' ),
			'priority' => 45,
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
			'label'    => __( 'Take Last item in Menu outside menu', 'astra' ),
			'priority' => 45,
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
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-main-menu-align]', array(
				'type'     => 'ast-radio-image',
				'choices'  => array(
					'inline' => array(
						'label' => __( 'Inline', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/mobile-inline-layout-76x48.png',
					),
					'stack'  => array(
						'label' => __( 'Stack', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/mobile-stack-layout-76x48.png',
					),
				),
				'section'  => 'section-header',
				'priority' => 50,
				'label'    => __( 'Mobile Header Alignment', 'astra' ),
			)
		)
	);

	// Learn More link if Astra Pro is not activated.
	if ( ! defined( 'ASTRA_EXT_VER' ) ) {

		/**
		 * Option: Divider
		 */
		$wp_customize->add_control(
			new Astra_Control_Divider(
				$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-more-feature-divider]', array(
					'type'     => 'ast-divider',
					'section'  => 'section-header',
					'priority' => 999,
					'settings' => array(),
				)
			)
		);
		/**
		 * Option: Learn More about Mobile Header
		 */
		$wp_customize->add_control(
			new Astra_Control_Description(
				$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-more-feature-description]', array(
					'type'     => 'ast-description',
					'section'  => 'section-header',
					'priority' => 999,
					'label'    => '',
					'help'     => '<p>' . __( 'More Options Available for Mobile Header in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/docs/mobile-header-with-astra/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
					'settings' => array(),
				)
			)
		);
	}

	/**
	 * Option: Toggle Button Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]', array(
			'default'           => astra_get_option( 'mobile-header-toggle-btn-style' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]', array(
			'section'  => 'section-header',
			'label'    => __( 'Toggle Button Style', 'astra' ),
			'type'     => 'select',
			'priority' => 42,
			'choices'  => array(
				'fill'    => __( 'Fill', 'astra' ),
				'outline' => __( 'Outline', 'astra' ),
				'minimal' => __( 'Minimal', 'astra' ),
			),
		)
	);

	/**
	 * Option: Toggle Button Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style-color]', array(
			'default'           => astra_get_option( 'mobile-header-toggle-btn-style-color' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style-color]', array(
				'type'     => 'ast-color',
				'label'    => __( 'Toggle Button Color', 'astra' ),
				'section'  => 'section-header',
				'priority' => 42,
			)
		)
	);

		/**
	 * Option: Border Radius
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-border-radius]', array(
			'default'           => astra_get_option( 'mobile-header-toggle-btn-border-radius' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-border-radius]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-header',
				'label'       => __( 'Border Radius', 'astra' ),
				'priority'    => 42,
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
			)
		)
	);
