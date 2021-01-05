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

		$class_obj      = Astra_Builder_Header::get_instance();
		$number_of_html = Astra_Builder_Helper::$num_of_header_html;

		if ( 'footer' === $builder_type ) {
			$class_obj      = Astra_Builder_Footer::get_instance();
			$number_of_html = Astra_Builder_Helper::$num_of_footer_html;
		}

		for ( $index = 1; $index <= $number_of_html; $index++ ) {

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
					'name'     => $_section,
					'type'     => 'section',
					'priority' => 60,
					/* translators: %s Index */
					'title'    => sprintf( __( 'HTML %s', 'astra' ), $index ),
					'panel'    => 'panel-' . $builder_type . '-builder-group',
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
					'default'     => 'Insert HTML text here.',
					'input_attrs' => array(
						'id' => $builder_type . '-html-' . $index,
					),
					'partial'     => array(
						'selector'        => '.ast-' . $builder_type . '-html-' . $index,
						'render_callback' => array( $class_obj, $builder_type . '_html_' . $index ),
					),
					'context'     => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: HTML Color.
				 */

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-color-group]',
					'default'   => astra_get_option( $builder_type . '-html-' . $index . '-color-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Colors', 'astra' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 8,
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				array(
					'name'       => $builder_type . '-html-' . $index . 'color',
					'default'    => astra_get_option( $builder_type . '-html-' . $index . 'color' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-color-group]',
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 1,
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'title'      => __( 'Text Color', 'astra' ),
					'context'    => Astra_Builder_Helper::$design_tab,
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
					'tab'        => __( 'Normal', 'astra' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-color-group]',
					'title'      => __( 'Link Color', 'astra' ),
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
					'tab'        => __( 'Hover', 'astra' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-color-group]',
					'title'      => __( 'Link Hover Color', 'astra' ),
					'context'    => Astra_Builder_Helper::$design_tab,
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

			if ( 'footer' === $builder_type ) {
				$_configs[] = array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-html-' . $index . '-alignment]',
					'default'   => astra_get_option( 'footer-html-' . $index . '-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-responsive-select',
					'section'   => $_section,
					'priority'  => 6,
					'title'     => __( 'Alignment', 'astra' ),
					'choices'   => array(
						'left'   => __( 'Left', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'center' => __( 'Center', 'astra' ),
					),
					'context'   => Astra_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
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
