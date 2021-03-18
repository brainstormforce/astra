<?php
/**
 * Easy Digital Downloads Sidebar Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Edd_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Edd_Sidebar_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Astra Easy Digital Downloads Sidebar Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Shop Page
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[edd-sidebar-layout]',
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-sidebars',
					'divider'  => array( 'ast_class' => 'ast-top-divider' ),
					'default'  => astra_get_option( 'edd-sidebar-layout' ),
					'priority' => 5,
					'title'    => __( 'Easy Digital Downloads', 'astra' ),
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
					'name'     => ASTRA_THEME_SETTINGS . '[edd-single-product-sidebar-layout]',
					'type'     => 'control',
					'control'  => 'ast-select',
					'default'  => astra_get_option( 'edd-single-product-sidebar-layout' ),
					'section'  => 'section-sidebars',
					'priority' => 5,
					'title'    => __( 'EDD Single Product', 'astra' ),
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

new Astra_Edd_Sidebar_Configs();



