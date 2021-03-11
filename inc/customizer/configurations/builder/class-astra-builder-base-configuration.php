<?php
/**
 * Astra Builder Base Configuration.
 *
 * @package astra-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Astra_Builder_Base_Configuration.
 */
final class Astra_Builder_Base_Configuration {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance = null;


	/**
	 *  Initiator
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

	}

	/**
	 * Prepare Advance header configuration.
	 *
	 * @param string $section_id section id.
	 * @return array
	 */
	public static function prepare_advanced_tab( $section_id ) {

		return array(

			/**
			 * Option: Divider
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[' . $section_id . '-margin-padding-divider]',
				'type'     => 'control',
				'section'  => $section_id,
				'control'  => 'ast-divider',
				'priority' => 200,
				'settings' => array(),
				'context'  => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Padded Layout Custom Width
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[' . $section_id . '-padding]',
				'default'           => astra_get_option( $section_id . '-padding' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'section'           => $section_id,
				'priority'          => 210,
				'title'             => __( 'Padding', 'astra' ),
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

			/**
			 * Option: Divider
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[' . $section_id . '-margin-padding-inner-divider]',
				'type'     => 'control',
				'section'  => $section_id,
				'control'  => 'ast-divider',
				'priority' => 215,
				'settings' => array(),
				'context'  => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Padded Layout Custom Width
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[' . $section_id . '-margin]',
				'default'           => astra_get_option( $section_id . '-margin' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'section'           => $section_id,
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
	}

	/**
	 * Prepare Advance Typography configuration.
	 *
	 * @param string $section_id section id.
	 * @param array  $required_condition Required Condition.
	 * @return array
	 */
	public static function prepare_typography_options( $section_id, $required_condition = array() ) {

		$parent = ASTRA_THEME_SETTINGS . '[' . $section_id . '-typography]';

		if ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'typography' ) ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[' . $section_id . '-typo-divider]',
					'type'     => 'control',
					'section'  => $section_id,
					'control'  => 'ast-divider',
					'priority' => 16,
					'settings' => array(),
					'context'  => empty( $required_condition ) ? Astra_Builder_Helper::$design_tab : $required_condition,
				),

				array(
					'name'      => $parent,
					'default'   => astra_get_option( $section_id . '-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Text Font', 'astra' ),
					'section'   => $section_id,
					'transport' => 'postMessage',
					'priority'  => 16,
					'context'   => empty( $required_condition ) ? Astra_Builder_Helper::$design_tab : $required_condition,
				),

				/**
				 * Option: Font Size
				 */
				array(
					'name'        => 'font-size-' . $section_id,
					'type'        => 'sub-control',
					'parent'      => $parent,
					'section'     => $section_id,
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'font-size-' . $section_id ),
					'transport'   => 'postMessage',
					'priority'    => 14,
					'title'       => __( 'Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

			);
		} else {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[font-size-' . $section_id . '-heading-divider]',
					'type'     => 'control',
					'section'  => $section_id,
					'control'  => 'ast-divider',
					'priority' => 16,
					'settings' => array(),
					'context'  => empty( $required_condition ) ? Astra_Builder_Helper::$design_tab : $required_condition,
				),

				/**
				 * Option: Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-' . $section_id . ']',
					'type'        => 'control',
					'section'     => $section_id,
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'font-size-' . $section_id ),
					'transport'   => 'postMessage',
					'priority'    => 16,
					'title'       => __( 'Font Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'context'     => empty( $required_condition ) ? Astra_Builder_Helper::$design_tab : $required_condition,
				),
			);
		}

		return $_configs;
	}

	/**
	 * Prepare Visibility options.
	 *
	 * @param string $_section section id.
	 * @param string $builder_type Builder Type.
	 * @return array
	 */
	public static function prepare_visibility_tab( $_section, $builder_type = 'header' ) {

		$class_obj = Astra_Builder_Header::get_instance();

		$configs = array(

			/**
			 * Option: Divider
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[' . $_section . '-visibility-divider]',
				'type'     => 'control',
				'section'  => $_section,
				'control'  => 'ast-divider',
				'priority' => 300,
				'settings' => array(),
				'context'  => ( 'footer' === $builder_type ) ?
					Astra_Builder_Helper::$general_tab : Astra_Builder_Helper::$responsive_general_tab,
			),

			/**
			 * Option: Hide on tablet
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[' . $_section . '-hide-tablet]',
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'default'   => astra_get_option( $_section . '-hide-tablet' ),
				'section'   => $_section,
				'priority'  => 320,
				'title'     => __( 'Hide on Tablet', 'astra' ),
				'transport' => 'postMessage',
				'context'   => Astra_Builder_Helper::$tablet_general_tab,
			),

			/**
			 * Option: Hide on mobile
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[' . $_section . '-hide-mobile]',
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'default'   => astra_get_option( $_section . '-hide-mobile' ),
				'section'   => $_section,
				'priority'  => 330,
				'title'     => __( 'Hide on Mobile', 'astra' ),
				'transport' => 'postMessage',
				'context'   => Astra_Builder_Helper::$mobile_general_tab,
			),
		);

		if ( 'footer' === $builder_type ) {
			$footer_configs = array(
				/**
				 * Option: Hide on desktop
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[' . $_section . '-hide-desktop]',
					'type'      => 'control',
					'control'   => 'ast-toggle-control',
					'default'   => astra_get_option( $_section . '-hide-desktop' ),
					'section'   => $_section,
					'priority'  => 320,
					'title'     => __( 'Hide on Desktop', 'astra' ),
					'transport' => 'postMessage',
					'context'   => Astra_Builder_Helper::$desktop_general_tab,
				),
			);
			$configs        = array_merge( $configs, $footer_configs );
		}

		return $configs;
	}

	/**
	 * Prepare common options for the widgets by type.
	 *
	 * @param string $type type.
	 * @return array
	 */
	public static function prepare_widget_options( $type = 'header' ) {
		$html_config = array();


		if ( 'footer' === $type ) {
			$component_limit = defined( 'ASTRA_EXT_VER' ) ?
				Astra_Builder_Helper::$component_limit : Astra_Builder_Helper::$num_of_header_widgets;
		} else {
			$component_limit = defined( 'ASTRA_EXT_VER' ) ?
				Astra_Builder_Helper::$component_limit : Astra_Builder_Helper::$num_of_footer_widgets;
		}

		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = 'sidebar-widgets-' . $type . '-widget-' . $index;

			$_configs = array(

				array(
					'name'        => 'sidebar-widgets-' . $type . '-widget-' . $index,
					'type'        => 'section',
					'priority'    => 5,
					'title'       => __( 'Widget ', 'astra' ) . $index,
					'panel'       => 'panel-' . $type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $type . '-widget',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-heading-' . $index . '-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 6,
					'settings' => array(),
				),

				/**
				 * Option: Widget title color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-title-color]',
					'default'    => astra_get_option( $type . '-widget-' . $index . '-title-color' ),
					'title'      => __( 'Title Color', 'astra' ),
					'type'       => 'control',
					'section'    => $_section,
					'priority'   => 7,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				 * Option: Widget Color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-color]',
					'default'    => astra_get_option( $type . '-widget-' . $index . '-color' ),
					'title'      => __( 'Content Color', 'astra' ),
					'type'       => 'control',
					'section'    => $_section,
					'priority'   => 7,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
				),
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-link-color-group]',
					'default'    => astra_get_option( $type . '-widget-' . $index . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Link Color', 'astra' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 7,
					'responsive' => true,
				),

				/**
				 * Option: Widget link color.
				 */
				array(
					'name'       => $type . '-widget-' . $index . '-link-color',
					'default'    => astra_get_option( $type . '-widget-' . $index . '-link-color' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-link-color-group]',
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 3,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'title'      => __( 'Normal', 'astra' ),
				),

				/**
				 * Option: Widget link color.
				 */
				array(
					'name'       => $type . '-widget-' . $index . '-link-h-color',
					'default'    => astra_get_option( $type . '-widget-' . $index . '-link-h-color' ),
					'parent'     => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-link-color-group]',
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 1,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'title'      => __( 'Hover', 'astra' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-typo-heading-' . $index . '-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 89,
					'settings' => array(),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[' . $_section . '-margin-padding-heading-divider]',
					'type'     => 'control',
					'section'  => $_section,
					'control'  => 'ast-divider',
					'priority' => 200,
					'settings' => array(),
				),

				/**
				 * Option: Margin
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
				),
			);

			if ( 'footer' === $type ) {
				array_push(
					$_configs,
					/**
					 * Option: Divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-alignment-' . $index . '-divider]',
						'type'     => 'control',
						'section'  => $_section,
						'control'  => 'ast-divider',
						'priority' => 5,
						'settings' => array(),
					),
					/**
					 * Option: Column Alignment
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-alignment-' . $index . ']',
						'default'   => astra_get_option( $type . '-widget-alignment-' . $index ),
						'type'      => 'control',
						'control'   => 'ast-selector',
						'section'   => $_section,
						'priority'  => 5,
						'title'     => __( 'Alignment', 'astra' ),
						'transport' => 'postMessage',
						'choices'   => array(
							'left'   => 'align-left',
							'center' => 'align-center',
							'right'  => 'align-right',
						),
					)
				);
			}

			if ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'typography' ) ) {

				$new_configs = array(

					/**
					 * Option: Widget Title Typography
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-text-typography]',
						'default'   => astra_get_option( $type . '-widget-' . $index . '-text-typography' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Title Font', 'astra' ),
						'section'   => $_section,
						'transport' => 'postMessage',
						'priority'  => 90,
					),

					/**
					 * Option: Divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-text-typography-divider]',
						'type'     => 'control',
						'section'  => $_section,
						'control'  => 'ast-divider',
						'priority' => 90,
						'settings' => array(),
					),

					/**
					 * Option: Widget Title Font Size
					 */
					array(
						'name'        => $type . '-widget-' . $index . '-font-size',
						'default'     => astra_get_option( $type . '-widget-' . $index . '-font-size' ),
						'parent'      => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-text-typography]',
						'transport'   => 'postMessage',
						'title'       => __( 'Size', 'astra' ),
						'type'        => 'sub-control',
						'section'     => $_section,
						'control'     => 'ast-responsive',
						'input_attrs' => array(
							'min' => 0,
						),
						'priority'    => 3,
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
					),

					/**
					 * Option: Widget Content Typography
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-content-typography]',
						'default'   => astra_get_option( $type . '-widget-' . $index . '-content-typography' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Content Font', 'astra' ),
						'section'   => $_section,
						'transport' => 'postMessage',
						'priority'  => 91,
					),

					/**
					 * Option: Widget Content Font Size
					 */
					array(
						'name'        => $type . '-widget-' . $index . '-content-font-size',
						'default'     => astra_get_option( $type . '-widget-' . $index . '-content-font-size' ),
						'parent'      => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-content-typography]',
						'transport'   => 'postMessage',
						'title'       => __( 'Size', 'astra' ),
						'type'        => 'sub-control',
						'section'     => $_section,
						'control'     => 'ast-responsive',
						'input_attrs' => array(
							'min' => 0,
						),
						'priority'    => 3,
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
					),
				);

			} else {

				$new_configs = array(

					/**
					 * Option: Widget Title Font Size
					 */
					array(
						'name'        => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-font-size]',
						'default'     => astra_get_option( $type . '-widget-' . $index . '-font-size' ),
						'transport'   => 'postMessage',
						'title'       => __( 'Title Font Size', 'astra' ),
						'type'        => 'control',
						'section'     => $_section,
						'control'     => 'ast-responsive',
						'input_attrs' => array(
							'min' => 0,
						),
						'priority'    => 90,
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
					),

					/**
					 * Option: Widget Content Font Size
					 */
					array(
						'name'        => ASTRA_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-content-font-size]',
						'default'     => astra_get_option( $type . '-widget-' . $index . '-content-font-size' ),
						'transport'   => 'postMessage',
						'title'       => __( 'Content Font Size', 'astra' ),
						'type'        => 'control',
						'section'     => $_section,
						'control'     => 'ast-responsive',
						'input_attrs' => array(
							'min' => 0,
						),
						'priority'    => 91,
						'units'       => array(
							'px' => 'px',
							'em' => 'em',
						),
					),
				);

			}

			$_configs = array_merge( $_configs, $new_configs );

			$html_config[] = self::prepare_visibility_tab( $_section, $type );

			$_configs = array_merge( $_configs, self::prepare_visibility_tab( $_section, $type ) );

			$html_config[] = $_configs;
		}

		return $html_config;

	}

}

/**
 *  Prepare if class 'Astra_Builder_Base_Configuration' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Builder_Base_Configuration::get_instance();
