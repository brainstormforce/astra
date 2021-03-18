<?php
/**
 * Astra Theme Customizer Configuration Below Header.
 *
 * @package     astra-builder
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

	/**
	 * Register Below Header Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class Astra_Customizer_Below_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Below Header Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-below-header-builder';

			$_configs = array(

				// Section: Below Header.
				array(
					'name'     => $_section,
					'type'     => 'section',
					'title'    => __( 'Below Header', 'astra' ),
					'panel'    => 'panel-header-builder-group',
					'priority' => 30,
				),

				/**
				 * Option: Header Builder Tabs
				 */
				array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				// Section: Below Header Height.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[hbb-header-height]',
					'section'           => $_section,
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'hbb-header-height' ),
					'priority'          => 30,
					'title'             => __( 'Height', 'astra' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 30,
						'step' => 1,
						'max'  => 600,
					),
					'context'           => Astra_Builder_Helper::$general_tab,
				),

				// Section: Below Header Border.
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[hbb-header-separator]',
					'section'     => $_section,
					'priority'    => 40,
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'hbb-header-separator' ),
					'title'       => __( 'Bottom Border Size', 'astra' ),
					'type'        => 'control',
					'control'     => 'ast-slider',
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 10,
					),
					'context'     => Astra_Builder_Helper::$design_tab,
				),

				// Section: Below Header Border Color.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[hbb-header-bottom-border-color]',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'hbb-header-bottom-border-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => $_section,
					'priority'          => 50,
					'title'             => __( 'Bottom Border Color', 'astra' ),
					'context'           => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[hbb-header-separator]',
							'operator' => '>=',
							'value'    => 1,
						),
						Astra_Builder_Helper::$design_tab_config,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				// Option: Below Header Background styling.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[hbb-header-bg-obj-responsive]',
					'type'      => 'control',
					'section'   => $_section,
					'control'   => 'ast-responsive-background',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'hbb-header-bg-obj-responsive' ),
					'title'     => __( 'Background', 'astra' ),
					'priority'  => 70,
					'context'   => Astra_Builder_Helper::$design_tab,
				),
			);

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_advanced_tab( $_section ) );

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new Astra_Customizer_Below_Header_Configs();
}
