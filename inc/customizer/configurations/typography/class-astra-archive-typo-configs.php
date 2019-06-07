<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Archive_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Archive_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Archive Typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Blog Typography
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-typography-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-blog',
					'title'    => __( 'Typography', 'astra-addon' ),
					'priority' => 122,
					'settings' => array(),
				),

				/**
				 * Option: Blog / Archive Typography
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[blog-content-blog-post-title-typo]',
					'default'   => astra_get_option( 'blog-content-blog-post-title-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Blog Post Title', 'astra-addon' ),
					'section'   => 'section-blog',
					'transport' => 'postMessage',
					'priority'  => 122,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[blog-content-archive-summary-typo]',
					'default'   => astra_get_option( 'blog-content-archive-summary-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Archive Summary Box Title', 'astra-addon' ),
					'section'   => 'section-blog',
					'transport' => 'postMessage',
					'priority'  => 123,
				),

				/**
				 * Option: Blog - Post Title Font Size
				 */
				array(
					'name'        => 'font-size-page-title',
					'parent'      => ASTRA_THEME_SETTINGS . '[blog-content-blog-post-title-typo]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'transport'   => 'postMessage',
					'priority'    => 4,
					'default'     => astra_get_option( 'font-size-page-title' ),
					'title'       => __( 'Font Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Archive Summary Box Title Font Size
				 */
				array(
					'name'        => 'font-size-archive-summary-title',
					'parent'      => ASTRA_THEME_SETTINGS . '[blog-content-archive-summary-typo]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'font-size-archive-summary-title' ),
					'priority'    => 11,
					'title'       => __( 'Font Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Archive_Typo_Configs;


