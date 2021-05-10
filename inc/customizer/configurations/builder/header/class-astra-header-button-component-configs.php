<?php
/**
 * [Header] options for astra theme.
 *
 * @package     Astra Header Footer Builder
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Header_Button_Component_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Button control for Header/Footer Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configurations = Astra_Button_Component_Configs::register_configuration( $configurations, 'header', 'section-hb-button-' );

			return $configurations;
		}
	}

	new Astra_Header_Button_Component_Configs();
}
