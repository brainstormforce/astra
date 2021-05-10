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
class Astra_Html_Component_Configs {

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
	public static function register_configuration( $configurations, $builder_type = 'header', $section = 'section-hb-html-' ) {

		$html_config = array();

		if ( 'footer' === $builder_type ) {
			$class_obj       = Astra_Builder_Footer::get_instance();
			$component_limit = defined( 'ASTRA_EXT_VER' ) ? Astra_Builder_Helper::$component_limit : Astra_Builder_Helper::$num_of_footer_html;
		} else {
			$class_obj       = Astra_Builder_Header::get_instance();
			$component_limit = defined( 'ASTRA_EXT_VER' ) ? Astra_Builder_Helper::$component_limit : Astra_Builder_Helper::$num_of_header_html;
		}

		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = $section . $index;

			$_configs = array(

				/**
				 * Option: Builder Tabs
				 */
				array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				/*
				 * Builder section
				 */
				array(
					'name'        => $_section,
					'type'        => 'section',
					'priority'    => 60,
					/* translators: %s Index */
					'title'       => sprintf( __( 'HTML %s', 'astra' ), $index ),
					'panel'       => 'panel-' . $builder_type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $builder_type . '-html',
				),

				/**
				 * Option: Html Editor.
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . ']',
					'type'        => 'control',
					'control'     => 'ast-html-editor',
					'section'     => $_section,
					'transport'   => 'postMessage',
					'priority'    => 4,
					'default'     => astra_get_option( $builder_type . '-html-' . $index ),
					'input_attrs' => array(
						'id' => $builder_type . '-html-' . $index,
					),
					'partial'     => array(
						'selector'         => '.ast-' . $builder_type . '-html-' . $index,
						'render_callback'  => array( $class_obj, $builder_type . '_html_' . $index ),
						'fallback_refresh' => false,
					),
					'context'     => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: HTML Color.
				 */

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . 'color]',
					'default'    => astra_get_option( $builder_type . '-html-' . $index . 'color' ),
					'type'       => 'control',
					'section'    => $_section,
					'priority'   => 8,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'title'      => __( 'Text Color', 'astra' ),
					'context'    => Astra_Builder_Helper::$design_tab,
				),
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'default'    => astra_get_option( $builder_type . '-html-' . $index . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Link Color', 'astra' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 8,
					'context'    => Astra_Builder_Helper::$design_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Link Color.
				 */
				array(
					'name'       => $builder_type . '-html-' . $index . 'link-color',
					'default'    => astra_get_option( $builder_type . '-html-' . $index . 'link-color' ),
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 9,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'title'      => __( 'Normal', 'astra' ),
					'context'    => Astra_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Link Hover Color.
				 */
				array(
					'name'       => $builder_type . '-html-' . $index . 'link-h-color',
					'default'    => astra_get_option( $builder_type . '-html-' . $index . 'link-h-color' ),
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 10,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'title'      => __( 'Hover', 'astra' ),
					'context'    => Astra_Builder_Helper::$design_tab,
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
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
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

			if ( 'footer' === $builder_type ) {
				$_configs[] = array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-html-' . $index . '-alignment]',
					'default'   => astra_get_option( 'footer-html-' . $index . '-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $_section,
					'priority'  => 6,
					'title'     => __( 'Alignment', 'astra' ),
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
				);
			}

			$html_config[] = Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section, $builder_type );

			$html_config[] = Astra_Builder_Base_Configuration::prepare_typography_options( $_section );

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

new Astra_Html_Component_Configs();
