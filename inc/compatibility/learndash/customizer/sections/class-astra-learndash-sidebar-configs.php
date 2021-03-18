<?php
/**
 * Content Spacing Options for our theme.
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

if ( ! class_exists( 'Astra_Learndash_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Learndash_Sidebar_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register LearnDash Sidebar settings.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: LearnDash
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[learndash-sidebar-layout]',
					'type'        => 'control',
					'control'     => 'ast-select',
					'section'     => 'section-sidebars',
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
					'default'     => astra_get_option( 'learndash-sidebar-layout' ),
					'priority'    => 5,
					'title'       => __( 'LearnDash', 'astra' ),
					'description' => __( 'This layout will apply on all single course, lesson, topic and quiz.', 'astra' ),
					'choices'     => array(
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

new Astra_Learndash_Sidebar_Configs();
