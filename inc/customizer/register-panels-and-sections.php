<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

	/**
	 * Layout Panel
	 */
	$wp_customize->add_panel(
		'panel-layout', array(
			'priority' => 10,
			'title'    => __( 'Layout', 'astra' ),
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

		$wp_customize->add_section(
			'section-header', array(
				'title'    => __( 'Header', 'astra' ),
				'panel'    => 'panel-layout',
				'priority' => 20,
			)
		);

		$wp_customize->add_section(
			'section-footer-small', array(
				'title'    => __( 'Footer Bar', 'astra' ),
				'panel'    => 'panel-layout',
				'priority' => 60,
			)
		);

		$wp_customize->add_section(
			'section-blog', array(
				'title'    => __( 'Blog / Archive', 'astra' ),
				'panel'    => 'panel-layout',
				'priority' => 40,
			)
		);

		$wp_customize->add_section(
			'section-blog-single', array(
				'title'    => __( 'Single Post', 'astra' ),
				'panel'    => 'panel-layout',
				'priority' => 45,
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
			'section-header-typo', array(
				'title'    => __( 'Header', 'astra' ),
				'panel'    => 'panel-typography',
				'priority' => 20,
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

		/**
		 * Footer Widgets Section
		 */
		$wp_customize->add_section(
			'section-footer-adv', array(
				'title'    => __( 'Footer Widgets', 'astra' ),
				'panel'    => 'panel-layout',
				'priority' => 55,
			)
		);
