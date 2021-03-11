<?php
/**
 * Colors - Breadcrumbs Options for theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 1.7.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.7.0
 */
if ( ! class_exists( 'Astra_Breadcrumbs_Color_Configs' ) ) {

	/**
	 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
	 */
	class Astra_Breadcrumbs_Color_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.7.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {
				$content_colors_control_title = __( 'Content Colors', 'astra' );
			} else {
				$content_colors_control_title = __( 'Content', 'astra' );
			}

			$_configs = array(

				/*
				 * Breadcrumb Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-bg-color]',
					'type'       => 'control',
					'default'    => astra_get_option( 'breadcrumb-bg-color' ),
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						Astra_Builder_Helper::$is_header_footer_builder_active ?
							Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-active-color-responsive]',
					'default'    => astra_get_option( 'breadcrumb-active-color-responsive' ),
					'type'       => 'control',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						Astra_Builder_Helper::$is_header_footer_builder_active ?
							Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[breadcrumb-separator-color]',
					'default'    => astra_get_option( 'breadcrumb-separator-color' ),
					'type'       => 'control',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Separator Color', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'context'    => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						Astra_Builder_Helper::$is_header_footer_builder_active ?
							Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
					),
					'priority'   => 72,
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'default'    => astra_get_option( 'section-breadcrumb-color' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Content Link Color', 'astra' ),
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'priority'   => 72,
					'context'    => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						Astra_Builder_Helper::$is_header_footer_builder_active ?
							Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
					),
					'responsive' => true,
				),

				array(
					'name'       => 'breadcrumb-text-color-responsive',
					'default'    => astra_get_option( 'breadcrumb-text-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Normal', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 15,
				),

				array(
					'name'       => 'breadcrumb-hover-color-responsive',
					'default'    => astra_get_option( 'breadcrumb-hover-color-responsive' ),
					'type'       => 'sub-control',
					'parent'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-link-color]',
					'section'    => 'section-breadcrumb',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Hover', 'astra' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 20,
				),
			);

			if ( ! Astra_Builder_Helper::$is_header_footer_builder_active ) {
				array_push(
					$_configs,
					/**
					 * Option: Divider
					 * Option: breadcrumb color Section divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[section-breadcrumb-color-divider]',
						'type'     => 'control',
						'control'  => 'ast-heading',
						'section'  => 'section-breadcrumb',
						'title'    => __( 'Colors', 'astra' ),
						'priority' => 71,
						'settings' => array(),
						'context'  => array(
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[breadcrumb-position]',
								'operator' => '!=',
								'value'    => 'none',
							),
							Astra_Builder_Helper::$general_tab_config,
						),
					)
				);
			}
			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Breadcrumbs_Color_Configs();
