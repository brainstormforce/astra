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

if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class Astra_Mobile_Menu_Component_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-header-mobile-menu';

			$_configs = array(

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

				// Section: Primary Header.
				array(
					'name'     => $_section,
					'type'     => 'section',
					'title'    => __( 'Mobile Menu', 'astra' ),
					'panel'    => 'panel-header-builder-group',
					'priority' => 40,
				),

				/**
				* Option: Theme Menu create link
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-mobile-menu-create-menu-link]',
					'default'   => astra_get_option( 'header-mobile-menu-create-menu-link' ),
					'type'      => 'control',
					'control'   => 'ast-customizer-link',
					'section'   => $_section,
					'priority'  => 30,
					'link_type' => 'section',
					'linked'    => 'menu_locations',
					'link_text' => __( 'Configure Menu from Here.', 'astra' ),
					'context'   => Astra_Builder_Helper::$general_tab,
				),

				// Option: Submenu Divider Checkbox.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-mobile-menu-submenu-item-border]',
					'default'   => astra_get_option( 'header-mobile-menu-submenu-item-border' ),
					'type'      => 'control',
					'control'   => 'checkbox',
					'section'   => $_section,
					'priority'  => 35,
					'title'     => __( 'Item Divider', 'astra' ),
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
				),

				// Option: Submenu item Border Color.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-mobile-menu-submenu-item-b-color]',
					'default'   => astra_get_option( 'header-mobile-menu-submenu-item-b-color' ),
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'title'     => __( 'Divider Color', 'astra' ),
					'section'   => $_section,
					'priority'  => 40,
					'context'   => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-mobile-menu-submenu-item-border]',
							'operator' => '==',
							'value'    => true,
						),
					),
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

				// Option: Menu Color Divider.
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors-divider]',
					'section'  => $_section,
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Colors', 'astra' ),
					'priority' => 80,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$design_tab,
				),

				// Option Group: Menu Color.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors]',
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Menu', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 90,
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				// Option: Menu Color.
				array(
					'name'       => 'header-mobile-menu-color-responsive',
					'default'    => astra_get_option( 'header-mobile-menu-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'section'    => $_section,
					'title'      => __( 'Link / Text Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 7,
					'context'    => Astra_Builder_Helper::$general_tab,
				),

				// Option: Menu Background image, color.
				array(
					'name'       => 'header-mobile-menu-bg-obj-responsive',
					'default'    => astra_get_option( 'header-mobile-menu-bg-obj-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-background',
					'section'    => $_section,
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'data_attrs' => array( 'name' => 'header-mobile-menu-bg-obj-responsive' ),
					'title'      => __( 'Background Color', 'astra' ),
					'priority'   => 9,
					'context'    => Astra_Builder_Helper::$general_tab,
				),

				// Option: Menu Hover Color.
				array(
					'name'       => 'header-mobile-menu-h-color-responsive',
					'default'    => astra_get_option( 'header-mobile-menu-h-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors]',
					'tab'        => __( 'Hover', 'astra' ),
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Color', 'astra' ),
					'section'    => $_section,
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 19,
					'context'    => Astra_Builder_Helper::$general_tab,
				),

				// Option: Menu Hover Background Color.
				array(
					'name'       => 'header-mobile-menu-h-bg-color-responsive',
					'default'    => astra_get_option( 'header-mobile-menu-h-bg-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors]',
					'type'       => 'sub-control',
					'title'      => __( 'Background Color', 'astra' ),
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 21,
					'context'    => Astra_Builder_Helper::$general_tab,
				),

				// Option: Active Menu Color.
				array(
					'name'       => 'header-mobile-menu-a-color-responsive',
					'default'    => astra_get_option( 'header-mobile-menu-a-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors]',
					'type'       => 'sub-control',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Active', 'astra' ),
					'title'      => __( 'Link Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 31,
					'context'    => Astra_Builder_Helper::$general_tab,
				),

				// Option: Active Menu Background Color.
				array(
					'name'       => 'header-mobile-menu-a-bg-color-responsive',
					'default'    => astra_get_option( 'header-mobile-menu-a-bg-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'section'    => $_section,
					'title'      => __( 'Background Color', 'astra' ),
					'tab'        => __( 'Active', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 33,
					'context'    => Astra_Builder_Helper::$general_tab,
				),

				// Option: Typography Heading.
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-header-typography-styling-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => $_section,
					'title'    => __( 'Typography', 'astra' ),
					'priority' => 110,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$design_tab,
				),

				// Option Group: Menu Typography.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
					'default'   => astra_get_option( 'header-mobile-menu-header-menu-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Menu', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 120,
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				// Option: Menu Font Family.
				array(
					'name'      => 'header-mobile-menu-font-family',
					'default'   => astra_get_option( 'header-mobile-menu-font-family' ),
					'parent'    => ASTRA_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
					'type'      => 'sub-control',
					'section'   => $_section,
					'transport' => 'postMessage',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Family', 'astra' ),
					'priority'  => 22,
					'connect'   => 'header-mobile-menu-font-weight',
					'context'   => Astra_Builder_Helper::$general_tab,
				),

				// Option: Menu Font Weight.
				array(
					'name'              => 'header-mobile-menu-font-weight',
					'default'           => astra_get_option( 'header-mobile-menu-font-weight' ),
					'parent'            => ASTRA_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
					'section'           => $_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'transport'         => 'postMessage',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'title'             => __( 'Weight', 'astra' ),
					'priority'          => 24,
					'connect'           => 'header-mobile-menu-font-family',
					'context'           => Astra_Builder_Helper::$general_tab,
				),

				// Option: Menu Text Transform.
				array(
					'name'      => 'header-mobile-menu-text-transform',
					'default'   => astra_get_option( 'header-mobile-menu-text-transform' ),
					'parent'    => ASTRA_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
					'section'   => $_section,
					'type'      => 'sub-control',
					'control'   => 'ast-select',
					'transport' => 'postMessage',
					'title'     => __( 'Text Transform', 'astra' ),
					'priority'  => 25,
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
					'context'   => Astra_Builder_Helper::$general_tab,
				),

				// Option: Menu Font Size.
				array(
					'name'        => 'header-mobile-menu-font-size',
					'default'     => astra_get_option( 'header-mobile-menu-font-size' ),
					'parent'      => ASTRA_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
					'section'     => $_section,
					'type'        => 'sub-control',
					'priority'    => 23,
					'title'       => __( 'Size', 'astra' ),
					'control'     => 'ast-responsive',
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'context'     => Astra_Builder_Helper::$general_tab,
				),

				// Option: Menu Line Height.
				array(
					'name'              => 'header-mobile-menu-line-height',
					'parent'            => ASTRA_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
					'section'           => $_section,
					'type'              => 'sub-control',
					'priority'          => 26,
					'title'             => __( 'Line Height', 'astra' ),
					'transport'         => 'postMessage',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 10,
					),
					'context'           => Astra_Builder_Helper::$general_tab,
				),

				// Option: Spacing Heading.
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-mobile-menu-menu-spacing-divider]',
					'section'  => $_section,
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Spacing', 'astra' ),
					'priority' => 140,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$design_tab,
				),

				// Option - Menu Space.
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[header-mobile-menu-menu-spacing]',
					'default'        => astra_get_option( 'header-mobile-menu-menu-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => $_section,
					'priority'       => 150,
					'title'          => __( 'Menu Space', 'astra' ),
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

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new Astra_Mobile_Menu_Component_Configs();
}
