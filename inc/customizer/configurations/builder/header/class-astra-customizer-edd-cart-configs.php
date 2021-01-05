<?php
/**
 * Astra Theme Customizer Configuration for EDD Cart Element.
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
class Astra_Customizer_Edd_Cart_Configs extends Astra_Customizer_Config_Base {


	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_section = ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? 'section-header-edd-cart' : 'section-edd-general';

		$defaults = Astra_Theme_Options::defaults();

		$_configs = array(

			/**
			* EDD Cart section
			*/
			array(
				'name'     => $_section,
				'type'     => 'section',
				'priority' => 5,
				'title'    => __( 'EDD Cart', 'astra' ),
				'panel'    => 'panel-header-builder-group',
			),

			/**
			 * Option: Header cart total
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[edd-header-cart-total-display]',
				'default'   => astra_get_option( 'edd-header-cart-total-display' ),
				'type'      => 'control',
				'section'   => $_section,
				'title'     => __( 'Display Cart Total', 'astra' ),
				'priority'  => 50,
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'            => '.ast-header-edd-cart',
					'container_inclusive' => false,
					'render_callback'     => array( 'Astra_Builder_Header', 'header_edd_cart' ),
				),
				'control'   => 'checkbox',
				'context'   => Astra_Builder_Helper::$general_tab,
			),

			/**
			 * Option: Cart Title
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[edd-header-cart-title-display]',
				'default'   => astra_get_option( 'edd-header-cart-title-display' ),
				'type'      => 'control',
				'section'   => $_section,
				'title'     => __( 'Display Cart Title', 'astra' ),
				'priority'  => 55,
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'            => '.ast-header-edd-cart',
					'container_inclusive' => false,
					'render_callback'     => array( 'Astra_Builder_Header', 'header_edd_cart' ),
				),
				'control'   => 'checkbox',
				'context'   => Astra_Builder_Helper::$general_tab,
			),
			/**
			 * Option: Icon Style
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[edd-header-cart-icon-style]',
				'default'   => astra_get_option( 'edd-header-cart-icon-style' ),
				'type'      => 'control',
				'transport' => 'postMessage',
				'section'   => $_section,
				'title'     => __( 'Style', 'astra' ),
				'control'   => 'select',
				'priority'  => 40,
				'choices'   => array(
					'none'    => __( 'None', 'astra' ),
					'outline' => __( 'Outline', 'astra' ),
					'fill'    => __( 'Fill', 'astra' ),
				),
				'context'   => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Background color
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[edd-header-cart-icon-color]',
				'default'   => astra_get_option( 'edd-header-cart-icon-color' ),
				'type'      => 'control',
				'control'   => 'ast-color',
				'title'     => __( 'Color', 'astra' ),
				'transport' => 'postMessage',
				'section'   => $_section,
				'context'   => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[edd-header-cart-icon-style]',
						'operator' => '!=',
						'value'    => 'none',
					),
				),
				'priority'  => 45,
			),

			/**
			 * Option: Border Radius
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[edd-header-cart-icon-radius]',
				'default'     => astra_get_option( 'edd-header-cart-icon-radius' ),
				'type'        => 'control',
				'transport'   => 'postMessage',
				'section'     => $_section,
				'context'     => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[edd-header-cart-icon-style]',
						'operator' => '!=',
						'value'    => 'none',
					),
				),
				'title'       => __( 'Border Radius', 'astra' ),
				'control'     => 'ast-slider',
				'priority'    => 47,
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 200,
				),
			),
			
		);
		
		if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {
			$_edd_configs = array(
				array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),
			);

			$configurations = array_merge( $configurations, $_edd_configs );

			$configurations = array_merge( $configurations, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );
		}

		return array_merge( $configurations, $_configs );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new Astra_Customizer_Edd_Cart_Configs();
