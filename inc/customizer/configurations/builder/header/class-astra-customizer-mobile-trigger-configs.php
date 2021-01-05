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
class Astra_Customizer_Mobile_Trigger_Configs extends Astra_Customizer_Config_Base {


	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_section = 'section-header-mobile-trigger';

		$_configs = array(

			/*
			* Header Builder section
			*/
			array(
				'name'     => 'section-header-mobile-trigger',
				'type'     => 'section',
				'priority' => 70,
				'title'    => __( 'Toggle Button', 'astra' ),
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
			 * Option: Header Html Editor.
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-trigger-icon]',
				'type'        => 'control',
				'control'     => 'ast-radio-image',
				'default'     => astra_get_option( 'header-trigger-icon' ),
				'title'       => __( 'Icons', 'astra' ),
				'section'     => $_section,
				'input_attrs' => array(),
				'choices'     => array(
					'menu'  => array(
						'label' => __( 'menu', 'astra' ),
						'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu' ),
					),
					'menu2' => array(
						'label' => __( 'menu2', 'astra' ),
						'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu2' ),
					),
					'menu3' => array(
						'label' => __( 'menu3', 'astra' ),
						'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu3' ),
					),
				),
				'transport'   => 'postMessage',
				'partial'     => array(
					'selector'        => '.ast-button-wrap',
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_mobile_trigger' ),
				),
				'priority'    => 10,
				'context'     => Astra_Builder_Helper::$general_tab,
			),

			/**
			 * Option: Icon Size
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-icon-size]',
				'default'     => astra_get_option( 'mobile-header-toggle-icon-size' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'section'     => $_section,
				'title'       => __( 'Icon Size', 'astra' ),
				'priority'    => 20,
				'suffix'      => '',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'context'     => Astra_Builder_Helper::$general_tab,
			),



			/**
			 * Option: Mobile Menu Label
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-menu-label]',
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'        => '.ast-button-wrap',
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_mobile_trigger' ),
				),
				'default'   => astra_get_option( 'mobile-header-menu-label' ),
				'section'   => $_section,
				'priority'  => 20,
				'title'     => __( 'Menu Label', 'astra' ),
				'type'      => 'control',
				'control'   => 'text',
				'context'   => Astra_Builder_Helper::$general_tab,
			),

			/**
			 * Option: Toggle Button Style
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
				'default'   => astra_get_option( 'mobile-header-toggle-btn-style' ),
				'section'   => $_section,
				'title'     => __( 'Toggle Button Style', 'astra' ),
				'type'      => 'control',
				'control'   => 'select',
				'priority'  => 30,
				'choices'   => array(
					'fill'    => __( 'Fill', 'astra' ),
					'outline' => __( 'Outline', 'astra' ),
					'minimal' => __( 'Minimal', 'astra' ),
				),
				'context'   => Astra_Builder_Helper::$general_tab,
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'        => '.ast-button-wrap',
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_mobile_trigger' ),
				),
			),

			/**
			 * Option: Toggle Button Color
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-color]',
				'default'   => astra_get_option( 'mobile-header-toggle-btn-color' ),
				'type'      => 'control',
				'control'   => 'ast-color',
				'title'     => __( 'Color', 'astra' ),
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 50,
				'context'   => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Toggle Button Bg Color
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-bg-color]',
				'default'   => astra_get_option( 'mobile-header-toggle-btn-bg-color' ),
				'type'      => 'control',
				'control'   => 'ast-color',
				'title'     => __( 'Background Color', 'astra' ),
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 50,
				'context'   => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
						'operator' => '==',
						'value'    => 'fill',
					),
				),
			),

			/**
			 * Option: Toggle Button Border Size
			 */
			array(
				'name'           => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-border-size]',
				'default'        => astra_get_option( 'mobile-header-toggle-btn-border-size' ),
				'type'           => 'control',
				'section'        => $_section,
				'control'        => 'ast-border',
				'transport'      => 'postMessage',
				'linked_choices' => true,
				'priority'       => 60,
				'title'          => __( 'Border Width', 'astra' ),
				'choices'        => array(
					'top'    => __( 'Top', 'astra' ),
					'right'  => __( 'Right', 'astra' ),
					'bottom' => __( 'Bottom', 'astra' ),
					'left'   => __( 'Left', 'astra' ),
				),
				'context'        => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
						'operator' => '==',
						'value'    => 'outline',
					),
				),
			),

			/**
			 * Option: Toggle Button Border Color
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-border-color]',
				'default'   => astra_get_option( 'mobile-header-toggle-border-color' ),
				'type'      => 'control',
				'control'   => 'ast-color',
				'title'     => __( 'Border Color', 'astra' ),
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 65,
				'context'   => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
						'operator' => '==',
						'value'    => 'outline',
					),
				),
			),

			/**
			 * Option: Border Radius
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-border-radius]',
				'default'     => astra_get_option( 'mobile-header-toggle-border-radius' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'section'     => $_section,
				'title'       => __( 'Border Radius', 'astra' ),
				'priority'    => 70,
				'suffix'      => '',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'context'     => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
						'operator' => '!=',
						'value'    => 'minimal',
					),
				),
			),

			// Option Group: Trigger Typography.
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-label-typography]',
				'default'   => astra_get_option( 'mobile-header-label-typography' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'title'     => __( 'Typography', 'astra' ),
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 70,
				'context'   => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-menu-label]',
						'operator' => '!=',
						'value'    => '',
					),
				),
			),

			// Option: Trigger Font Size.
			array(
				'name'        => 'mobile-header-label-font-size',
				'default'     => astra_get_option( 'mobile-header-label-font-size' ),
				'parent'      => ASTRA_THEME_SETTINGS . '[mobile-header-label-typography]',
				'section'     => $_section,
				'type'        => 'sub-control',
				'priority'    => 23,
				'title'       => __( 'Size', 'astra' ),
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
				'context'     => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Margin heading
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[' . $_section . '-margin-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Spacing', 'astra' ),
				'priority' => 200,
				'settings' => array(),
				'context'  => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Margin Space
			 */
			array(
				'name'           => ASTRA_THEME_SETTINGS . '[' . $_section . '-margin]',
				'default'        => '',
				'type'           => 'control',
				'transport'      => 'postMessage',
				'control'        => 'ast-responsive-spacing',
				'section'        => $_section,
				'priority'       => 220,
				'title'          => __( 'Margin', 'astra' ),
				'linked_choices' => true,
				'unit_choices'   => array( 'px', 'em', '%' ),
				'choices'        => array(
					'top'    => __( 'Top', 'astra' ),
					'right'  => __( 'Right', 'astra' ),
					'bottom' => __( 'Bottom', 'astra' ),
					'left'   => __( 'Left', 'astra' ),
				),
				'context'        => Astra_Builder_Helper::$design_tab,
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new Astra_Customizer_Mobile_Trigger_Configs();
