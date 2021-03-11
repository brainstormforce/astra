<?php
/**
 * Astra Theme Customizer Configuration Builder.
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

if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class Astra_Header_Search_Component_Configs extends Astra_Customizer_Config_Base {


	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_section = 'section-header-search';

		$_configs = array(

			/*
			* Header Builder section
			*/
			array(
				'name'     => $_section,
				'type'     => 'section',
				'priority' => 80,
				'title'    => __( 'Search', 'astra' ),
				'panel'    => 'panel-header-builder-group',
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

			/**
			 * Option: Search Color.
			 */
			array(
				'name'       => ASTRA_THEME_SETTINGS . '[header-search-icon-color]',
				'default'    => astra_get_option( 'header-search-icon-color' ),
				'type'       => 'control',
				'section'    => $_section,
				'priority'   => 8,
				'transport'  => 'postMessage',
				'control'    => 'ast-responsive-color',
				'responsive' => true,
				'rgba'       => true,
				'title'      => __( 'Icon Color', 'astra' ),
				'context'    => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Search Size
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[header-search-icon-space]',
				'section'           => $_section,
				'priority'          => 4,
				'transport'         => 'postMessage',
				'default'           => astra_get_option( 'header-search-icon-space' ),
				'title'             => __( 'Icon Size', 'astra' ),
				'suffix'            => 'px',
				'type'              => 'control',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'input_attrs'       => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 50,
				),
				'context'           => Astra_Builder_Helper::$general_tab,
			),

			/**
			 * Option: Divider
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[' . $_section . '-margin-divider]',
				'type'     => 'control',
				'section'  => $_section,
				'control'  => 'ast-divider',
				'priority' => 200,
				'settings' => array(),
				'context'  => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Margin Space
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[' . $_section . '-margin]',
				'default'           => astra_get_option( $_section . '-margin' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'section'           => $_section,
				'priority'          => 220,
				'title'             => __( 'Margin', 'astra' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'astra' ),
					'right'  => __( 'Right', 'astra' ),
					'bottom' => __( 'Bottom', 'astra' ),
					'left'   => __( 'Left', 'astra' ),
				),
				'context'           => Astra_Builder_Helper::$design_tab,
			),

		);

		if ( defined( 'ASTRA_EXT_VER' ) ) {

			$addon_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-search-icon-space-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 2.5,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),
			);

			$_configs = array_merge( $_configs, $addon_configs );
		}

		$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

		return array_merge( $configurations, $_configs );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new Astra_Header_Search_Component_Configs();

