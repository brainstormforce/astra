<?php
/**
 * Astra Theme Customizer Configuration Off Canvas.
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
	 * Register Off Canvas Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class Astra_Customizer_Off_Canvas_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Above Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
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
					'name'        => $_section . '-ast-context-tabs',
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
					'name'       => ASTRA_THEME_SETTINGS . '[mobile-header-type]',
					'default'    => astra_get_option( 'mobile-header-type' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $_section,
					'priority'   => 25,
					'title'      => __( 'Header Type', 'astra' ),
					'choices'    => array(
						'off-canvas' => __( 'Flyout', 'astra' ),
						'full-width' => __( 'Full-Screen', 'astra' ),
						'dropdown'   => __( 'Dropdown', 'astra' ),
					),
					'transport'  => 'postMessage',
					'context'    => Astra_Builder_Helper::$general_tab,
					'renderAs'   => 'text',
					'responsive' => false,
				),

				/**
				 * Option: Off-Canvas Slide-Out.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[off-canvas-slide]',
					'default'    => astra_get_option( 'off-canvas-slide' ),
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-selector',
					'section'    => $_section,
					'priority'   => 30,
					'title'      => __( 'Position', 'astra' ),
					'choices'    => array(
						'left'  => __( 'Left', 'astra' ),
						'right' => __( 'Right', 'astra' ),
					),
					'context'    => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-type]',
							'operator' => '==',
							'value'    => 'off-canvas',
						),
					),
					'renderAs'   => 'text',
					'responsive' => false,
					'divider'    => array( 'ast_class' => 'ast-bottom-divider ast-top-divider' ),
				),

				/**
				 * Option: Toggle on click of button or link.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[header-builder-menu-toggle-target]',
					'default'    => astra_get_option( 'header-builder-menu-toggle-target' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $_section,
					'context'    => Astra_Builder_Helper::$general_tab,
					'priority'   => 40,
					'title'      => __( 'Dropdown Target', 'astra' ),
					'suffix'     => '',
					'choices'    => array(
						'icon' => __( 'Icon', 'astra' ),
						'link' => __( 'Link', 'astra' ),
					),
					'renderAs'   => 'text',
					'responsive' => false,
					'transport'  => 'postMessage',
					'divider'    => array( 'ast_class' => 'ast-bottom-divider ast-top-divider' ),
				),

				/**
				 * Option: Content alignment option for offcanvas
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[header-offcanvas-content-alignment]',
					'default'    => astra_get_option( 'header-offcanvas-content-alignment' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $_section,
					'context'    => Astra_Builder_Helper::$general_tab,
					'priority'   => 40,
					'title'      => __( 'Content Alignment', 'astra' ),
					'suffix'     => '',
					'choices'    => array(
						'flex-start' => __( 'Left', 'astra' ),
						'center'     => __( 'Center', 'astra' ),
						'flex-end'   => __( 'Right', 'astra' ),
					),
					'renderAs'   => 'text',
					'responsive' => false,
					'transport'  => 'postMessage',
				),

				// Option Group: Off-Canvas Colors Group.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[off-canvas-background]',
					'type'              => 'control',
					'control'           => 'ast-background',
					'title'             => __( 'Background', 'astra' ),
					'section'           => $_section,
					'transport'         => 'postMessage',
					'priority'          => 26,
					'context'           => Astra_Builder_Helper::$design_tab,
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_background_obj' ),
					'default'           => astra_get_option( 'off-canvas-background' ),
				),

				// Option: Off-Canvas Close Icon Color.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[off-canvas-close-color]',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'off-canvas-close-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => $_section,
					'priority'          => 27,
					'title'             => __( 'Close Icon Color', 'astra' ),
					'context'           => array(
						'relation' => 'AND',
						Astra_Builder_Helper::$design_tab_config,
						array(
							'relation' => 'OR',
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-type]',
								'operator' => '==',
								'value'    => 'off-canvas',
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-type]',
								'operator' => '==',
								'value'    => 'full-width',
							),
						),
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider ast-top-divider' ),
				),

				// Spacing Between every element in the flyout.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[off-canvas-inner-spacing]',
					'default'   => astra_get_option( 'off-canvas-inner-spacing' ),
					'type'      => 'control',
					'control'   => 'ast-slider',
					'title'     => __( 'Inner Element Spacing', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 28,
					'context'   => Astra_Builder_Helper::$design_tab,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				// Option Group: Off-Canvas Colors Group.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[off-canvas-background]',
					'type'              => 'control',
					'control'           => 'ast-background',
					'title'             => __( 'Background', 'astra' ),
					'section'           => $_section,
					'transport'         => 'postMessage',
					'priority'          => 30,
					'context'           => Astra_Builder_Helper::$design_tab,
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_background_obj' ),
					'default'           => astra_get_option( 'off-canvas-background' ),
				),

				/**
				 * Option: Popup Padding.
				 */

				array(
					'name'           => ASTRA_THEME_SETTINGS . '[off-canvas-padding]',
					'default'        => astra_get_option( 'off-canvas-padding' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => $_section,
					'priority'       => 210,
					'title'          => __( 'Popup Padding', 'astra' ),
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
					'context'        => array(
						'relation' => 'AND',
						Astra_Builder_Helper::$design_tab_config,
						array(
							'relation' => 'OR',
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-type]',
								'operator' => '==',
								'value'    => 'off-canvas',
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-type]',
								'operator' => '==',
								'value'    => 'full-width',
							),
						),
					),
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
