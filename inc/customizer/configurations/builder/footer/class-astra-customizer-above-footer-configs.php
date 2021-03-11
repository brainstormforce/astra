<?php
/**
 * Astra Theme Customizer Configuration Above footer.
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
	 * Register Above footer Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class Astra_Customizer_Above_Footer_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Above Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-above-footer-builder';

			$column_count = range( 1, Astra_Builder_Helper::$num_of_footer_columns );
			$column_count = array_combine( $column_count, $column_count );

			$_configs = array(

				// Section: Above Footer.
				array(
					'name'     => $_section,
					'type'     => 'section',
					'title'    => __( 'Above Footer', 'astra' ),
					'panel'    => 'panel-footer-builder-group',
					'priority' => 30,
				),

				/**
				 * Option: Footer Builder Tabs
				 */
				array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				// Section: Above Footer Layout Divider.
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hba-footer-layout-options-separator-divider]',
					'section'  => $_section,
					'priority' => 20,
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Column count
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[hba-footer-column]',
					'default'   => astra_get_option( 'hba-footer-column' ),
					'type'      => 'control',
					'control'   => 'select',
					'section'   => $_section,
					'priority'  => 2,
					'title'     => __( 'Column', 'astra' ),
					'choices'   => $column_count,
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.site-above-footer-wrap',
						'container_inclusive' => false,
						'render_callback'     => array( Astra_Builder_Footer::get_instance(), 'above_footer' ),
					),
				),

				/**
				 * Option: Row Layout
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[hba-footer-layout]',
					'section'     => $_section,
					'default'     => astra_get_option( 'hba-footer-layout' ),
					'priority'    => 3,
					'title'       => __( 'Layout', 'astra' ),
					'type'        => 'control',
					'control'     => 'ast-row-layout',
					'context'     => Astra_Builder_Helper::$general_tab,
					'input_attrs' => array(
						'responsive' => true,
						'footer'     => 'primary',
						'layout'     => Astra_Builder_Helper::$footer_row_layouts,
					),
					'transport'   => 'postMessage',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hba-footer-layout-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 3,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Layout Width
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[hba-footer-layout-width]',
					'default'    => astra_get_option( 'hba-footer-layout-width' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $_section,
					'priority'   => 25,
					'title'      => __( 'Width', 'astra' ),
					'choices'    => array(
						'full'    => __( 'Full Width', 'astra' ),
						'content' => __( 'Content Width', 'astra' ),
					),
					'suffix'     => '',
					'context'    => Astra_Builder_Helper::$general_tab,
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hba-footer-layout-width-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 25,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),

				// Section: Above Footer Height.
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[hba-footer-height]',
					'section'     => $_section,
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'hba-footer-height' ),
					'priority'    => 30,
					'title'       => __( 'Height', 'astra' ),
					'suffix'      => 'px',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'input_attrs' => array(
						'min'  => 30,
						'step' => 1,
						'max'  => 600,
					),
					'context'     => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hba-footer-height-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 30,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Vertical Alignment
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[hba-footer-vertical-alignment]',
					'default'    => astra_get_option( 'hba-footer-vertical-alignment' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $_section,
					'priority'   => 34,
					'title'      => __( 'Vertical Alignment', 'astra' ),
					'choices'    => array(
						'flex-start' => __( 'Top', 'astra' ),
						'center'     => __( 'Middle', 'astra' ),
						'flex-end'   => __( 'Bottom', 'astra' ),
					),
					'context'    => Astra_Builder_Helper::$general_tab,
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[hba-stack]',
					'default'   => astra_get_option( 'hba-stack' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $_section,
					'priority'  => 5,
					'title'     => __( 'Inner Elements Layout', 'astra' ),
					'choices'   => array(
						'stack'  => __( 'Stack', 'astra' ),
						'inline' => __( 'Inline', 'astra' ),
					),
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.site-above-footer-wrap',
						'container_inclusive' => false,
						'render_callback'     => array( Astra_Builder_Footer::get_instance(), 'above_footer' ),
					),
					'renderAs'  => 'text',
				),

				// Section: Above Footer Border.
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[hba-footer-separator]',
					'section'     => $_section,
					'priority'    => 40,
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'hba-footer-separator' ),
					'title'       => __( 'Top Border Size', 'astra' ),
					'suffix'      => 'px',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
					'context'     => Astra_Builder_Helper::$design_tab,
				),

				// Section: Above Footer Border Color.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[hba-footer-top-border-color]',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'hba-footer-top-border-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => $_section,
					'priority'          => 50,
					'title'             => __( 'Border Color', 'astra' ),
					'context'           => array(
						Astra_Builder_Helper::$design_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[hba-footer-separator]',
							'operator' => '>=',
							'value'    => 1,
						),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hba-footer-separator-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 50,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$design_tab,
				),

				// Option: Above Footer Background styling.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[hba-footer-bg-obj-responsive]',
					'type'      => 'control',
					'section'   => $_section,
					'control'   => 'ast-responsive-background',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'hba-footer-bg-obj-responsive' ),
					'title'     => __( 'Background', 'astra' ),
					'priority'  => 70,
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Inner Spacing
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[hba-inner-spacing]',
					'section'           => $_section,
					'priority'          => 205,
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'hba-inner-spacing' ),
					'title'             => __( 'Inner Column Spacing', 'astra' ),
					'suffix'            => 'px',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 200,
					),
					'context'           => Astra_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hba-inner-spacing-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 205,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$design_tab,
				),
			);

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_advanced_tab( $_section ) );

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section, 'footer' ) );

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new Astra_Customizer_Above_Footer_Configs();
}
