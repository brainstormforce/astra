<?php
/**
 * Easy Digital Downloads Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Edd_Single_Product_Layout_Configs' ) ) {


	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Edd_Single_Product_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra-Easy Digital Downloads Shop Cart Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Cart upsells
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[disable-edd-single-product-nav]',
					'section'  => 'section-edd-single',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => astra_get_option( 'disable-edd-single-product-nav' ),
					'title'    => __( 'Disable Product Navigation', 'astra' ),
					'priority' => 10,
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),

				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Edd_Single_Product_Layout_Configs();
