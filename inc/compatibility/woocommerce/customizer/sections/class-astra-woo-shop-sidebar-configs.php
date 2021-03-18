<?php
/**
 * Content Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Woo_Shop_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Woo_Shop_Sidebar_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra-WooCommerce Shop Sidebar Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Shop Page
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[woocommerce-sidebar-layout]',
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-sidebars',
					'default'  => astra_get_option( 'woocommerce-sidebar-layout' ),
					'priority' => 5,
					'title'    => __( 'WooCommerce', 'astra' ),
					'choices'  => array(
						'default'       => __( 'Default', 'astra' ),
						'no-sidebar'    => __( 'No Sidebar', 'astra' ),
						'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
						'right-sidebar' => __( 'Right Sidebar', 'astra' ),
					),
				),

				/**
				 * Option: Single Product
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-sidebar-layout]',
					'type'     => 'control',
					'control'  => 'ast-select',
					'default'  => astra_get_option( 'single-product-sidebar-layout' ),
					'section'  => 'section-sidebars',
					'priority' => 5,
					'title'    => __( 'Single Product', 'astra' ),
					'choices'  => array(
						'default'       => __( 'Default', 'astra' ),
						'no-sidebar'    => __( 'No Sidebar', 'astra' ),
						'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
						'right-sidebar' => __( 'Right Sidebar', 'astra' ),
					),
				),
			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Woo_Shop_Sidebar_Configs();



