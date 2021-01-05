<?php
/**
 * Admin functions - Functions that add some functionality to WordPress admin panel
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register menus
 */
if ( ! function_exists( 'astra_register_menu_locations' ) ) {

	/**
	 * Register menus
	 *
	 * @since 1.0.0
	 */
	function astra_register_menu_locations() {

		/**
		 * Primary Menus
		 */
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'astra' ),
			)
		);

		if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {

			/**
			 * Register the Secondary & Mobile menus.
			 */
			register_nav_menus(
				array(
					'secondary_menu' => __( 'Secondary Menu', 'astra' ),
					'mobile_menu'    => __( 'Mobile Menu', 'astra' ),
				)
			);


			for ( $index = 3; $index <= Astra_Builder_Helper::$num_of_header_menu; $index++ ) {
				register_nav_menus(
					array(
						'menu_' . $index => __( 'Menu ', 'astra' ) . $index,
					)
				);
			}
				
			/**
			 * Register the Account menus.
			 */
			register_nav_menus(
				array(
					'loggedin_account_menu' => __( 'Logged In Account Menu', 'astra' ),
				)
			);

		}

		/**
		 * Footer Menus
		 */
		register_nav_menus(
			array(
				'footer_menu' => __( 'Footer Menu', 'astra' ),
			)
		);

	}
}

add_action( 'init', 'astra_register_menu_locations' );
