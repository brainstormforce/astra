<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Header_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Header_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			if ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'typography' ) ) {

				$_configs = array(

					/**
					 * Option: Site Title Font Size
					 */
					array(
						'name'        => 'font-size-site-title',
						'type'        => 'sub-control',
						'parent'      => ASTRA_THEME_SETTINGS . '[site-title-typography]',
						'section'     => 'title_tagline',
						'control'     => 'ast-responsive',
						'default'     => astra_get_option( 'font-size-site-title' ),
						'transport'   => 'postMessage',
						'priority'    => 9,
						'title'       => __( 'Size', 'astra' ),
						'input_attrs' => array(
							'min' => 0,
						),
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
					),

					/**
					 * Option: Site Tagline Font Size
					 */
					array(
						'name'        => 'font-size-site-tagline',
						'type'        => 'sub-control',
						'parent'      => ASTRA_THEME_SETTINGS . '[site-tagline-typography]',
						'section'     => 'title_tagline',
						'control'     => 'ast-responsive',
						'default'     => astra_get_option( 'font-size-site-tagline' ),
						'transport'   => 'postMessage',
						'priority'    => 14,
						'title'       => __( 'Size', 'astra' ),
						'input_attrs' => array(
							'min' => 0,
						),
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
					),
				);
			} else {

				$_configs = array(

					/**
					 * Option: Site Title Font Size
					 */
					array(
						'name'        => ASTRA_THEME_SETTINGS . '[font-size-site-title]',
						'type'        => 'control',
						'section'     => 'title_tagline',
						'control'     => 'ast-responsive',
						'default'     => astra_get_option( 'font-size-site-title' ),
						'transport'   => 'postMessage',
						'priority'    => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? 16 : 8,
						'title'       => __( 'Title Font Size', 'astra' ),
						'input_attrs' => array(
							'min' => 0,
						),
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
						'context'     => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? array(
							Astra_Builder_Helper::$design_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[display-site-title]',
								'operator' => '==',
								'value'    => true,
							),
						) : array(
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[display-site-title]',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					/**
					 * Option: Site Tagline Font Size
					 */
					array(
						'name'        => ASTRA_THEME_SETTINGS . '[font-size-site-tagline]',
						'type'        => 'control',
						'section'     => 'title_tagline',
						'control'     => 'ast-responsive',
						'default'     => astra_get_option( 'font-size-site-tagline' ),
						'transport'   => 'postMessage',
						'priority'    => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? 20 : 12,
						'title'       => __( 'Tagline Font Size', 'astra' ),
						'input_attrs' => array(
							'min' => 0,
						),
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
						'context'     => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? array(
							Astra_Builder_Helper::$design_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[display-site-tagline]',
								'operator' => '==',
								'value'    => true,
							),
						) : array(
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[display-site-tagline]',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
				);
			}

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Header_Typo_Configs();


