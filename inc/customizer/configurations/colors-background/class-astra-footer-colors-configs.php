<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
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
					'name'     => 'footer-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => ASTRA_THEME_SETTINGS . '[footer-bar-content-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Text Color', 'astra' ),
					'default'  => astra_get_option( 'footer-color' ),
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'     => 'footer-link-color',
					'type'     => 'sub-control',
					'priority' => 6,
					'parent'   => ASTRA_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'default'  => astra_get_option( 'footer-link-color' ),
					'title'    => __( 'Normal', 'astra' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => 'footer-link-h-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => ASTRA_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Hover', 'astra' ),
					'default'  => astra_get_option( 'section-footer-small' ),
				),

				/**
				 * Option: Footer Background
				 */
				array(
					'name'              => 'footer-bg-obj',
					'type'              => 'sub-control',
					'priority'          => 7,
					'parent'            => ASTRA_THEME_SETTINGS . '[footer-bar-background-group]',
					'section'           => 'section-footer-small',
					'transport'         => 'postMessage',
					'control'           => 'ast-background',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_background_obj' ),
					'default'           => astra_get_option( 'footer-bg-obj' ),
					'label'             => __( 'Background', 'astra' ),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Footer_Colors_Configs();


