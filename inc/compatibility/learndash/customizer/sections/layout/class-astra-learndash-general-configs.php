<?php
/**
 * LifterLMS General Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Learndash_General_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Learndash_General_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register LearnDash General Layout settings.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Display Serial Number
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-lesson-serial-number]',
					'section'  => 'section-learndash',
					'type'     => 'control',
					'control'  => 'checkbox',
					'default'  => astra_get_option( 'learndash-lesson-serial-number' ),
					'title'    => __( 'Display Serial Number', 'astra' ),
					'priority' => 25,
				),

				/**
				 * Option: Differentiate Rows
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-differentiate-rows]',
					'default'  => astra_get_option( 'learndash-differentiate-rows' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'section'  => 'section-learndash',
					'title'    => __( 'Differentiate Rows', 'astra' ),
					'priority' => 30,
				),
			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Learndash_General_Configs;
