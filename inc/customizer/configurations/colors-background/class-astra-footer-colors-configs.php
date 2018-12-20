<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        https://wpastra.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Footer_Colors_Configs' ) ) {

	/**
	 * Register Footer Color Configurations.
	 */
	class Astra_Footer_Colors_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Footer Color Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = array(

				/**
				 * Option: Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Text Color', 'astra' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'default'  => '',
					'section'  => 'section-colors-footer',
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-link-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'default'  => '',
					'title'    => __( 'Link Color', 'astra' ),
					'section'  => 'section-colors-footer',
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-link-h-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'title'    => __( 'Link Hover Color', 'astra' ),
					'default'  => '',
					'section'  => 'section-colors-footer',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-footer-image]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'section'  => 'section-colors-footer',
					'settings' => array(),
				),

				/**
				 * Option: Footer Background
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-bg-obj]',
					'type'     => 'control',
					'control'  => 'ast-background',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'default'  => astra_get_option( 'footer-bg-obj' ),
					'section'  => 'section-colors-footer',
					'title'    => __( 'Background', 'astra' ),
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Footer_Colors_Configs;


