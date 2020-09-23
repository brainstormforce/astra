<?php
/**
 * Astra Theme Customizer Configuration Off Canvas.
 *
 * @package     astra-builder
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       x.x.x
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

	/**
	 * Register Off Canvas Customizer Configurations.
	 *
	 * @since x.x.x
	 */
	class Astra_Customizer_Off_Canvas_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Above Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since x.x.x
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-popup-header-builder';

			$_configs = array(

				// Section: Off-Canvas.
				array(
					'name'     => $_section,
					'type'     => 'section',
					'title'    => __( 'Off-Canvas', 'astra' ),
					'panel'    => 'panel-header-builder-group',
					'priority' => 30,
				),

				/**
				 * Option: Header Builder Tabs
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[builder-header-off-canvas-tabs]',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				/**
				 * Option: Mobile Header Type.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-type]',
					'default'   => astra_get_option( 'mobile-header-type' ),
					'type'      => 'control',
					'control'   => 'select',
					'section'   => $_section,
					'priority'  => 25,
					'title'     => __( 'Header Type', 'astra' ),
					'choices'   => array(
						'off-canvas' => __( 'Off-Canvas', 'astra' ),
						'dropdown'   => __( 'Dropdown', 'astra' ),
					),
					'transport' => 'postMessage',
					'context'   => Astra_Constants::$general_tab,
				),

				/**
				 * Option: Off-Canvas Layout
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[off-canvas-layout]',
					'default'   => astra_get_option( 'off-canvas-layout' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'select',
					'section'   => $_section,
					'priority'  => 30,
					'title'     => __( 'Layout', 'astra' ),
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[mobile-header-type]',
						'==',
						'off-canvas',
					),
					'choices'   => array(
						'full-width' => __( 'Full Width', 'astra' ),
						'side-panel' => __( 'Flyout', 'astra' ),
					),
					'context'   => Astra_Constants::$general_tab,
				),

				/**
				 * Option: Off-Canvas Slide-Out.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[off-canvas-slide]',
					'default'   => astra_get_option( 'off-canvas-slide' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'select',
					'section'   => $_section,
					'priority'  => 30,
					'title'     => __( 'Position', 'astra' ),
					'choices'   => array(
						'left'  => __( 'Left', 'astra' ),
						'right' => __( 'Right', 'astra' ),
					),
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[off-canvas-layout]',
						'==',
						'side-panel',
					),
					'context'   => Astra_Constants::$general_tab,
				),

				// Option Group: Off-Canvas Colors Group.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[off-canvas-colors-group]',
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Background', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 30,
					'context'   => Astra_Constants::$design_tab,
				),

				/**
				 * Option: Off-Canvas Background.
				 */
				array(
					'name'      => 'off-canvas-background',
					'transport' => 'postMessage',
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[off-canvas-colors-group]',
					'section'   => $_section,
					'title'     => '',
					'control'   => 'ast-background',
					'default'   => astra_get_option( 'off-canvas-background' ),
					'priority'  => 35,
					'context'   => Astra_Constants::$design_tab,
				),

				// Option: Off-Canvas Close Icon Color.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[off-canvas-close-color]',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'off-canvas-close-color' ),
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $_section,
					'priority'  => 30,
					'title'     => __( 'Close Icon Color', 'astra' ),
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[mobile-header-type]',
						'==',
						'off-canvas',
					),
					'context'   => Astra_Constants::$design_tab,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new Astra_Customizer_Off_Canvas_Configs();
}
