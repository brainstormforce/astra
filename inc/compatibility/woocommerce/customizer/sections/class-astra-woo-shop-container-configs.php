<?php
/**
 * Container Options for Astra theme.
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

if ( ! class_exists( 'Astra_Woo_Shop_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Woo_Shop_Container_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra-WooCommerce Shop Container Settings.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[woocommerce-content-divider]',
					'type'     => 'control',
					'section'  => 'section-container-layout',
					'control'  => 'ast-divider',
					'priority' => 85,
					'settings' => array(),
				),

				/**
				 * Option: Shop Page
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[woocommerce-content-layout]',
					'type'     => 'control',
					'control'  => 'select',
					'default'  => astra_get_option( 'woocommerce-content-layout' ),
					'section'  => 'section-container-layout',
					'priority' => 85,
					'title'    => __( 'WooCommerce Layout', 'astra' ),
					'choices'  => array(
						'default'                 => __( 'Default', 'astra' ),
						'boxed-container'         => __( 'Boxed', 'astra' ),
						'content-boxed-container' => __( 'Content Boxed', 'astra' ),
						'plain-container'         => __( 'Full Width / Contained', 'astra' ),
						'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
					),
				),
			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Woo_Shop_Container_Configs();

