<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

	/**
	 * Layout Panel
	 */
	$wp_customize->add_panel(
		new Astra_WP_Customize_Panel(
			$wp_customize, 'panel-layout',
			array(
				'priority' => 10,
				'title'    => __( 'Layout', 'astra' ),
			)
		)
	);

	$wp_customize->add_section(
		'section-site-layout', array(
			'priority' => 5,
			'panel'    => 'panel-layout',
			'title'    => __( 'Site Layout', 'astra' ),
		)
	);

	$wp_customize->add_section(
		'section-container-layout', array(
			'priority' => 10,
			'panel'    => 'panel-layout',
			'title'    => __( 'Container', 'astra' ),
		)
	);

	/*
	 * Header section
	 *
	 * @since x.x.x
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-header-group',
			array(
				'priority' => 20,
				'title'    => __( 'Header', 'astra' ),
				'panel'    => 'panel-layout',
			)
		)
	);

	/*
	 * Update the Primary Header section
	 *
	 * @since x.x.x
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-header',
			array(
				'priority' => 20,
				'title'    => __( 'Primary Header', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-header-group',
			)
		)
	);

	/*
	 * Mobile Header section
	 *
	 * @since x.x.x
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-mobile-header',
			array(
				'priority' => 40,
				'title'    => __( 'Mobile Header', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-header-group',
			)
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-footer-group',
			array(
				'title'    => __( 'Footer', 'astra' ),
				'panel'    => 'panel-layout',
				'priority' => 55,
			)
		)
	);

	/**
	 * WooCommerce
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-group',
			array(
				'title'    => __( 'WooCommerce', 'astra' ),
				'panel'    => 'panel-layout',
				'priority' => 60,
			)
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-general',
			array(
				'title'    => __( 'General', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-woo-group',
				'priority' => 5,
			)
		)
	);
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-shop',
			array(
				'title'    => __( 'Shop', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-woo-group',
				'priority' => 10,
			)
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-shop-single',
			array(
				'title'    => __( 'Single Product', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-woo-group',
				'priority' => 15,
			)
		)
	);

	/**
	 * Footer Widgets Section
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-footer-adv',
			array(
				'title'    => __( 'Footer Widgets', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-footer-group',
				'priority' => 5,
			)
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-footer-small',
			array(
				'title'    => __( 'Footer Bar', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-footer-group',
				'priority' => 10,
			)
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-blog-group',
			array(
				'priority' => 40,
				'title'    => __( 'Blog', 'astra' ),
				'panel'    => 'panel-layout',
			)
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-blog',
			array(
				'priority' => 5,
				'title'    => __( 'Blog / Archive', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-blog-group',
			)
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-blog-single',
			array(
				'priority' => 10,
				'title'    => __( 'Single Post', 'astra' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-blog-group',
			)
		)
	);

	$wp_customize->add_section(
		'section-sidebars', array(
			'title'    => __( 'Sidebar', 'astra' ),
			'panel'    => 'panel-layout',
			'priority' => 50,
		)
	);

	/**
	 * Colors Panel
	 */
	$wp_customize->add_panel(
		'panel-colors-background', array(
			'priority' => 15,
			'title'    => __( 'Colors & Background', 'astra' ),
		)
	);

	$wp_customize->add_section(
		'section-colors-body', array(
			'title'    => __( 'Base Colors', 'astra' ),
			'panel'    => 'panel-colors-background',
			'priority' => 1,
		)
	);

	$wp_customize->add_section(
		'section-colors-footer', array(
			'title'    => __( 'Footer Bar', 'astra' ),
			'panel'    => 'panel-colors-background',
			'priority' => 60,
		)
	);

	$wp_customize->add_section(
		'section-footer-adv-color-bg', array(
			'title'    => __( 'Footer Widgets', 'astra' ),
			'panel'    => 'panel-colors-background',
			'priority' => 55,
		)
	);

	/**
	 * Typography Panel
	 */
	$wp_customize->add_panel(
		'panel-typography', array(
			'priority' => 20,
			'title'    => __( 'Typography', 'astra' ),
		)
	);

	$wp_customize->add_section(
		'section-body-typo', array(
			'title'    => __( 'Base Typography', 'astra' ),
			'panel'    => 'panel-typography',
			'priority' => 1,
		)
	);

	$wp_customize->add_section(
		'section-content-typo', array(
			'title'    => __( 'Content', 'astra' ),
			'panel'    => 'panel-typography',
			'priority' => 35,
		)
	);

	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-header-typo',
			apply_filters(
				'astra_customizer_primary_header_typo',
				array(
					'title'    => __( 'Header', 'astra' ),
					'panel'    => 'panel-typography',
					'priority' => 20,
				)
			)
		)
	);

	$wp_customize->add_section(
		'section-archive-typo', array(
			'title'    => __( 'Blog / Archive', 'astra' ),
			'panel'    => 'panel-typography',
			'priority' => 40,
		)
	);

	$wp_customize->add_section(
		'section-single-typo', array(
			'title'    => __( 'Single Page / Post', 'astra' ),
			'panel'    => 'panel-typography',
			'priority' => 45,
		)
	);

	/**
	 * Buttons Section
	 */
	$wp_customize->add_section(
		'section-buttons', array(
			'priority' => 50,
			'title'    => __( 'Buttons', 'astra' ),
		)
	);

	/**
	 * Widget Areas Section
	 */
	$wp_customize->add_section(
		'section-widget-areas', array(
			'priority' => 55,
			'title'    => __( 'Widget Areas', 'astra' ),
		)
	);
