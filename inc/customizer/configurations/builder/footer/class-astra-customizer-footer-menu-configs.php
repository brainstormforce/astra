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
	class Astra_Customizer_Footer_Menu_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-footer-menu';

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
					'title'    => __( 'Footer Menu', 'astra' ),
					'panel'    => 'panel-footer-builder-group',
					'priority' => 50,
				),

				/**
				* Option: Theme Menu create link
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-create-menu-link]',
					'default'   => astra_get_option( 'footer-create-menu-link' ),
					'type'      => 'control',
					'control'   => 'ast-customizer-link',
					'section'   => $_section,
					'priority'  => 10,
					'link_type' => 'section',
					'linked'    => 'menu_locations',
					'link_text' => __( 'Configure Menu from Here.', 'astra' ),
					'context'   => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-menu-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 15,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),

				// Option: Footer Menu Layout.
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[footer-menu-layout]',
					'default'    => astra_get_option( 'footer-menu-layout' ),
					'section'    => $_section,
					'priority'   => 20,
					'title'      => __( 'Layout', 'astra' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'transport'  => 'postMessage',
					'partial'    => array(
						'selector'            => '.footer-widget-area[data-section="section-footer-menu"] nav',
						'container_inclusive' => true,
						'render_callback'     => array( Astra_Builder_Footer::get_instance(), 'footer_menu' ),
					),
					'choices'    => array(
						'horizontal' => __( 'Inline', 'astra' ),
						'vertical'   => __( 'Stack', 'astra' ),
					),
					'context'    => Astra_Builder_Helper::$general_tab,
					'responsive' => true,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-menu-alignment-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 20,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Alignment
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-menu-alignment]',
					'default'   => astra_get_option( 'footer-menu-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $_section,
					'priority'  => 21,
					'title'     => __( 'Alignment', 'astra' ),
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'flex-start' => 'align-left',
						'center'     => 'align-center',
						'flex-end'   => 'align-right',
					),
				),

				// Option Group: Menu Color.
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[footer-menu-link-colors]',
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'context'    => Astra_Builder_Helper::$design_tab,
					'title'      => __( 'Link / Text', 'astra' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 90,
					'responsive' => true,
				),
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[footer-menu-background-colors]',
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'context'    => Astra_Builder_Helper::$design_tab,
					'title'      => __( 'Background', 'astra' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 90,
					'responsive' => true,
				),
				// Option: Menu Color.
				array(
					'name'       => 'footer-menu-color-responsive',
					'default'    => astra_get_option( 'footer-menu-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[footer-menu-link-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'section'    => $_section,
					'title'      => __( 'Normal', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 7,
				),

				// Option: Menu Background image, color.
				array(
					'name'       => 'footer-menu-bg-obj-responsive',
					'default'    => astra_get_option( 'footer-menu-bg-obj-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[footer-menu-background-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-background',
					'section'    => $_section,
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'data_attrs' => array( 'name' => 'footer-menu-bg-obj-responsive' ),
					'title'      => __( 'Normal', 'astra' ),
					'label'      => __( 'Normal', 'astra' ),
					'priority'   => 9,
				),

				// Option: Menu Hover Color.
				array(
					'name'       => 'footer-menu-h-color-responsive',
					'default'    => astra_get_option( 'footer-menu-h-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[footer-menu-link-colors]',
					'tab'        => __( 'Hover', 'astra' ),
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Hover', 'astra' ),
					'section'    => $_section,
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 19,
				),

				// Option: Menu Hover Background Color.
				array(
					'name'       => 'footer-menu-h-bg-color-responsive',
					'default'    => astra_get_option( 'footer-menu-h-bg-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[footer-menu-background-colors]',
					'type'       => 'sub-control',
					'title'      => __( 'Hover', 'astra' ),
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 21,
				),

				// Option: Active Menu Color.
				array(
					'name'       => 'footer-menu-a-color-responsive',
					'default'    => astra_get_option( 'footer-menu-a-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[footer-menu-link-colors]',
					'type'       => 'sub-control',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Active', 'astra' ),
					'title'      => __( 'Active', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 31,
				),

				// Option: Active Menu Background Color.
				array(
					'name'       => 'footer-menu-a-bg-color-responsive',
					'default'    => astra_get_option( 'footer-menu-a-bg-color-responsive' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[footer-menu-background-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'section'    => $_section,
					'title'      => __( 'Active', 'astra' ),
					'tab'        => __( 'Active', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 33,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-menu-header-typography-styling-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 110,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$design_tab,
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

				// Option - Menu Space.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[footer-main-menu-spacing]',
					'default'           => astra_get_option( 'footer-main-menu-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'transport'         => 'postMessage',
					'section'           => $_section,
					'context'           => Astra_Builder_Helper::$design_tab,
					'priority'          => 210,
					'title'             => __( 'Menu Spacing', 'astra' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[' . $_section . '-menu-spacing-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 210,
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

			if ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'typography' ) ) {

				$new_configs = array(

					// Option Group: Menu Typography.
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[footer-menu-typography]',
						'default'   => astra_get_option( 'footer-menu-typography' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Menu Font', 'astra' ),
						'section'   => $_section,
						'context'   => Astra_Builder_Helper::$design_tab,
						'transport' => 'postMessage',
						'priority'  => 120,
					),

					// Option: Menu Font Size.
					array(
						'name'        => 'footer-menu-font-size',
						'default'     => astra_get_option( 'footer-menu-font-size' ),
						'parent'      => ASTRA_THEME_SETTINGS . '[footer-menu-typography]',
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
				);
			} else {

				$new_configs = array(

					// Option: Menu Font Size.
					array(
						'name'        => ASTRA_THEME_SETTINGS . '[footer-menu-font-size]',
						'default'     => astra_get_option( 'footer-menu-font-size' ),
						'section'     => $_section,
						'type'        => 'control',
						'priority'    => 120,
						'title'       => __( 'Menu Font Size', 'astra' ),
						'control'     => 'ast-responsive',
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
				);
			}

			$_configs = array_merge( $_configs, $new_configs );

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section, 'footer' ) );

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating 'new' object of this class.
	 */
	new Astra_Customizer_Footer_Menu_Configs();
}
