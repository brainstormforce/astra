<?php
/**
 * WooCommerce Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Shop Columns
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-grids]', array(
			'default'           => array(
				'desktop' => 4,
				'tablet'  => 3,
				'mobile'  => 2,
			),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-grids]', array(
				'type'        => 'ast-responsive-slider',
				'section'     => 'section-woo-shop',
				'priority'    => 0,
				'label'       => __( 'Shop Columns', 'astra' ),
				'input_attrs' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 6,
				),
			)
		)
	);

	/**
	 * Option: Products Per Page
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-no-of-products]', array(
			'default' => astra_get_option( 'shop-no-of-products' ),
			'type'    => 'option',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-no-of-products]', array(
			'section'     => 'section-woo-shop',
			'label'       => __( 'Products Per Page', 'astra' ),
			'type'        => 'number',
			'priority'    => 20,
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
				'max'  => 50,
			),
		)
	);

	/**
	 * Option: Product Hover Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-hover-style]', array(
			'default'           => astra_get_option( 'shop-hover-style' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-hover-style]', array(
			'type'     => 'select',
			'section'  => 'section-woo-shop',
			'priority' => 24,
			'label'    => __( 'Hover Style', 'astra' ),
			'choices'  => apply_filters(
				'astra_woo_shop_hover_style',
				array(
					''    	=> __( 'None', 'astra' ),
					'swap'  => __( 'Swap Images', 'astra' ),
				)
			)
		)
	);

	/**
	 * Option: Single Post Meta
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-product-structure]', array(
			'default'           => astra_get_option( 'shop-product-structure' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Sortable(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-product-structure]', array(
				'type'     => 'ast-sortable',
				'section'  => 'section-woo-shop',
				'priority' => 70,
				'label'    => __( 'Shop Product Structure', 'astra' ),
				'choices'  => array(
					'title'      => __( 'Title', 'astra' ),
					'price'      => __( 'Price', 'astra' ),
					'ratings'    => __( 'Ratings', 'astra' ),
					'short_desc' => __( 'Short Description', 'astra' ),
					'add_cart'   => __( 'Add To Cart', 'astra' ),
					'category'   => __( 'Category', 'astra' ),
				),
			)
		)
	);
