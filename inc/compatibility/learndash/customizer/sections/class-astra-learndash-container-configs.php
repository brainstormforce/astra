<?php
/**
 * Container Options for Astra theme.
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

if ( ! class_exists( 'Astra_Learndash_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Learndash_Container_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register LearnDash Container settings.
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
					'name'        => ASTRA_THEME_SETTINGS . '[learndash-content-layout]',
					'type'        => 'control',
					'control'     => 'ast-select',
					'section'     => 'section-container-layout',
					'default'     => astra_get_option( 'learndash-content-layout' ),
					'priority'    => 68,
					'title'       => __( 'LearnDash Layout', 'astra' ),
					'description' => __( 'Will be applied to All Single Courses, Topics, Lessons and Quizzes. Does not work on pages created with LearnDash shortcodes.', 'astra' ),
					'choices'     => array(
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

new Astra_Learndash_Container_Configs();
