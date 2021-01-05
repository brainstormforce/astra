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

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class Astra_Button_Component_Configs {

	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array  $configurations Configurations.
	 * @param string $builder_type Builder Type.
	 * @param string $section Section.
	 *
	 * @since 3.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public static function register_configuration( $configurations, $builder_type = 'header', $section = 'section-hb-button-' ) {

		if ( 'footer' === $builder_type ) {
			$class_obj        = Astra_Builder_Footer::get_instance();
			$number_of_button = Astra_Builder_Helper::$num_of_footer_button;
		} else {
			$class_obj        = Astra_Builder_Header::get_instance();
			$number_of_button = Astra_Builder_Helper::$num_of_header_button;
		}

		$html_config = array();
		for ( $index = 1; $index <= $number_of_button; $index++ ) {

			$_section = $section . $index;
			$_prefix  = 'button' . $index;

			/**
			 * These options are related to Header Section - Button.
			 * Prefix hs represents - Header Section.
			 */
			$_configs = array(

				/*
					* Header Builder section - Button Component Configs.
					*/
				array(
					'name'     => $_section,
					'type'     => 'section',
					'priority' => 50,
					/* translators: %s Index */
					'title'    => ( 1 === $number_of_button ) ? __( 'Button', 'astra' ) : sprintf( __( 'Button %s', 'astra' ), $index ),
					'panel'    => 'panel-' . $builder_type . '-builder-group',
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
				* Option: Button Text
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text]',
					'default'   => astra_get_option( $builder_type . '-' . $_prefix . '-text' ),
					'type'      => 'control',
					'control'   => 'text',
					'section'   => $_section,
					'priority'  => 20,
					'title'     => __( 'Text', 'astra' ),
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.ast-' . $builder_type . '-button-' . $index,
						'container_inclusive' => false,
						'render_callback'     => array( $class_obj, 'button_' . $index ),
					),
					'context'   => Astra_Builder_Helper::$general_tab,
				),

				/**
				* Option: Button Link
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-link-option]',
					'default'   => astra_get_option( $builder_type . '-' . $_prefix . '-link-option' ),
					'type'      => 'control',
					'control'   => 'ast-link',
					'section'   => $_section,
					'priority'  => 30,
					'title'     => __( 'Link', 'astra' ),
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.ast-' . $builder_type . '-button-' . $index,
						'container_inclusive' => false,
						'render_callback'     => array( $class_obj, 'button_' . $index ),
					),
					'context'   => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Group: Primary Header Button Colors Group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-color-group]',
					'default'   => astra_get_option( $builder_type . '-' . $_prefix . '-color-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Colors', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 70,
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				/**
				* Option: Button Text Color
				*/
				array(
					'name'       => $builder_type . '-' . $_prefix . '-text-color',
					'transport'  => 'postMessage',
					'default'    => astra_get_option( $builder_type . '-' . $_prefix . '-text-color' ),
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 9,
					'context'    => Astra_Builder_Helper::$design_tab,
					'title'      => __( 'Text Color', 'astra' ),
				),

				/**
				* Option: Button Text Hover Color
				*/
				array(
					'name'       => $builder_type . '-' . $_prefix . '-text-h-color',
					'default'    => astra_get_option( $builder_type . '-' . $_prefix . '-text-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'astra' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 9,
					'context'    => Astra_Builder_Helper::$design_tab,
					'title'      => __( 'Text Color', 'astra' ),
				),

				/**
				* Option: Button Background Color
				*/
				array(
					'name'       => $builder_type . '-' . $_prefix . '-back-color',
					'default'    => astra_get_option( $builder_type . '-' . $_prefix . '-back-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 10,
					'context'    => Astra_Builder_Helper::$design_tab,
					'title'      => __( 'Background Color', 'astra' ),
				),

				/**
				* Option: Button Button Hover Color
				*/
				array(
					'name'       => $builder_type . '-' . $_prefix . '-back-h-color',
					'default'    => astra_get_option( $builder_type . '-' . $_prefix . '-back-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'astra' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 10,
					'context'    => Astra_Builder_Helper::$design_tab,
					'title'      => __( 'Background Color', 'astra' ),
				),

				/**
				 * Group: Primary Header Button Border Group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-group]',
					'default'   => astra_get_option( $builder_type . '-' . $_prefix . '-border-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Border', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 80,
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				/**
				* Option: Button Border Size
				*/
				array(
					'name'           => $builder_type . '-' . $_prefix . '-border-size',
					'default'        => astra_get_option( $builder_type . '-' . $_prefix . '-border-size' ),
					'parent'         => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-group]',
					'type'           => 'sub-control',
					'section'        => $_section,
					'control'        => 'ast-border',
					'transport'      => 'postMessage',
					'linked_choices' => true,
					'priority'       => 10,
					'title'          => __( 'Width', 'astra' ),
					'context'        => Astra_Builder_Helper::$general_tab,
					'choices'        => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
				),

				/**
				* Option: Button Border Color
				*/
				array(
					'name'       => $builder_type . '-' . $_prefix . '-border-color',
					'default'    => astra_get_option( $builder_type . '-' . $_prefix . '-border-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-group]',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 12,
					'context'    => Astra_Builder_Helper::$general_tab,
					'title'      => __( 'Color', 'astra' ),
				),

				/**
				* Option: Button Border Hover Color
				*/
				array(
					'name'       => $builder_type . '-' . $_prefix . '-border-h-color',
					'default'    => astra_get_option( $builder_type . '-' . $_prefix . '-border-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-group]',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 14,
					'context'    => Astra_Builder_Helper::$general_tab,
					'title'      => __( 'Hover Color', 'astra' ),
				),

				/**
				* Option: Button Border Radius
				*/
				array(
					'name'        => $builder_type . '-' . $_prefix . '-border-radius',
					'default'     => astra_get_option( $builder_type . '-' . $_prefix . '-border-radius' ),
					'type'        => 'sub-control',
					'parent'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-group]',
					'section'     => $_section,
					'control'     => 'ast-slider',
					'transport'   => 'postMessage',
					'priority'    => 16,
					'context'     => Astra_Builder_Helper::$general_tab,
					'title'       => __( 'Border Radius', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
				),

				/**
				 * Option: Primary Header Button Typography
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'default'   => astra_get_option( $builder_type . '-' . $_prefix . '-text-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Typography', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'context'   => Astra_Builder_Helper::$design_tab,
					'priority'  => 90,
				),

				/**
				 * Option: Primary Header Button Font Size
				 */
				array(
					'name'        => $builder_type . '-' . $_prefix . '-font-size',
					'default'     => astra_get_option( $builder_type . '-' . $_prefix . '-font-size' ),
					'parent'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'transport'   => 'postMessage',
					'title'       => __( 'Size', 'astra' ),
					'type'        => 'sub-control',
					'section'     => $_section,
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'priority'    => 3,
					'context'     => Astra_Builder_Helper::$general_tab,
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),
			);

			if ( 'footer' === $builder_type ) {
				$_configs[] = array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-button-' . $index . '-alignment]',
					'default'   => astra_get_option( 'footer-button-' . $index . '-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-responsive-select',
					'section'   => $_section,
					'priority'  => 35,
					'title'     => __( 'Alignment', 'astra' ),
					'choices'   => array(
						'flex-start' => __( 'Left', 'astra' ),
						'flex-end'   => __( 'Right', 'astra' ),
						'center'     => __( 'Center', 'astra' ),
					),
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
				);
			}

			$html_config[] = Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section, $builder_type );

			$html_config[] = Astra_Builder_Base_Configuration::prepare_advanced_tab( $_section );

			$html_config[] = $_configs;
		}

		$html_config    = call_user_func_array( 'array_merge', $html_config + array( array() ) );
		$configurations = array_merge( $configurations, $html_config );

		return $configurations;
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new Astra_Button_Component_Configs();
