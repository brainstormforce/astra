<?php
/**
 * Astra Theme Customizer Configuration Builder.
 *
 * @package     astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class Astra_Customizer_Footer_Builder_Configs extends Astra_Customizer_Config_Base {

	/**
	 * Footer Zones.
	 *
	 * @var array
	 * @since 3.0.0
	 */
	public static $zones = array(
		'above'   => array(),
		'primary' => array(),
		'below'   => array(),
	);

	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$cloned_component_track = Astra_Builder_Helper::$component_count_array;

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_footer_html; $index++ ) {

			$tmp_section = 'section-fb-html-' . $index;

			if ( in_array( $tmp_section, $cloned_component_track['removed-items'], true ) ) {
				continue;
			}

			Astra_Builder_Helper::$footer_desktop_items[ 'html-' . $index ] = array(
				'name'    => 'HTML ' . $index,
				'icon'    => 'text',
				'section' => $tmp_section,
				'clone'   => defined( 'ASTRA_EXT_VER' ),
				'type'    => 'html',
				'builder' => 'footer',
			);
		}

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_footer_widgets; $index++ ) {

			$tmp_section = 'sidebar-widgets-footer-widget-' . $index;

			if ( in_array( $tmp_section, $cloned_component_track['removed-items'], true ) ) {
				continue;
			}

			Astra_Builder_Helper::$footer_desktop_items[ 'widget-' . $index ] = array(
				'name'    => 'Widget ' . $index,
				'icon'    => 'wordpress',
				'section' => $tmp_section,
				'clone'   => defined( 'ASTRA_EXT_VER' ),
				'type'    => 'widget',
				'builder' => 'footer',
			);
		}

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_footer_button; $index++ ) {


			$tmp_section = 'section-fb-button-' . $index;

			if ( in_array( $tmp_section, $cloned_component_track['removed-items'], true ) ) {
				continue;
			}

			Astra_Builder_Helper::$footer_desktop_items[ 'button-' . $index ] = array(
				'name'    => ( 1 === Astra_Builder_Helper::$num_of_footer_button ) ? 'Button' : 'Button ' . $index,
				'icon'    => 'admin-links',
				'section' => $tmp_section,
				'clone'   => defined( 'ASTRA_EXT_VER' ),
				'type'    => 'button',
				'builder' => 'footer',
			);
		}

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_footer_social_icons; $index++ ) {

			$tmp_section = 'section-fb-social-icons-' . $index;

			if ( in_array( $tmp_section, $cloned_component_track['removed-items'], true ) ) {
				continue;
			}


			Astra_Builder_Helper::$footer_desktop_items[ 'social-icons-' . $index ] = array(
				'name'    => ( 1 === Astra_Builder_Helper::$num_of_footer_social_icons ) ? 'Social' : 'Social ' . $index,
				'icon'    => 'share',
				'section' => $tmp_section,
				'clone'   => defined( 'ASTRA_EXT_VER' ),
				'type'    => 'social-icons',
				'builder' => 'footer',
			);
		}

		$zone_base = array( 'above', 'primary', 'below' );

		foreach ( $zone_base as $key => $base ) {
			for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_footer_columns; $index++ ) {
				self::$zones[ $base ][ $base . '_' . $index ] = ucfirst( $base ) . ' Section ' . $index;
			}
		}

		$_configs = array(

			array(
				'name'     => 'panel-footer-builder-group',
				'type'     => 'panel',
				'priority' => 60,
				'title'    => __( 'Footer Builder', 'astra' ),
			),

			/**
			 * Option: Footer Layout
			 */
			array(
				'name'     => 'section-footer-builder-layout',
				'type'     => 'section',
				'priority' => 5,
				'title'    => __( 'Footer Layout', 'astra' ),
				'panel'    => 'panel-footer-builder-group',
			),

			/**
			 * Option: Header Builder Tabs
			 */
			array(
				'name'        => 'section-footer-builder-layout-ast-context-tabs',
				'section'     => 'section-footer-builder-layout',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			),

			/*
			* Header Builder section
			*/
			array(
				'name'     => 'section-footer-builder',
				'type'     => 'section',
				'priority' => 5,
				'title'    => __( 'Footer Builder', 'astra' ),
				'panel'    => 'panel-footer-builder-group',
				'context'  => array(
					array(
						'setting'  => 'ast_selected_tab',
						'operator' => 'in',
						'value'    => array( 'general', 'design' ),
					),
				),
			),

			/**
			 * Option: Footer Builder
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[builder-footer]',
				'section'     => 'section-footer-builder',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 20,
				'description' => '',
			),

			// Group Option: Global Footer Background styling.
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[footer-bg-obj-responsive]',
				'type'      => 'control',
				'control'   => 'ast-responsive-background',
				'default'   => astra_get_option( 'footer-bg-obj-responsive' ),
				'section'   => 'section-footer-builder-layout',
				'transport' => 'postMessage',
				'priority'  => 70,
				'title'     => __( 'Background Color & Image', 'astra' ),
				'context'   => Astra_Builder_Helper::$design_tab,
			),

			// Footer Background Color notice.
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[footer-bg-obj-responsive-description]',
				'type'     => 'control',
				'control'  => 'ast-description',
				'section'  => 'section-footer-builder-layout',
				'priority' => 71,
				'label'    => '',
				'help'     => __( 'If this color setting is not reflecting, check if colors are set from dedicated above, below or primary footer settings.', 'astra' ),
				'context'  => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Footer Desktop Items.
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[footer-desktop-items]',
				'section'     => 'section-footer-builder',
				'type'        => 'control',
				'control'     => 'ast-builder',
				'title'       => __( 'Footer Builder', 'astra' ),
				'priority'    => 10,
				'default'     => astra_get_option( 'footer-desktop-items' ),
				'choices'     => Astra_Builder_Helper::$footer_desktop_items,
				'transport'   => 'postMessage',
				'partial'     => array(
					'selector'            => '.site-footer',
					'container_inclusive' => true,
					'render_callback'     => array( Astra_Builder_Footer::get_instance(), 'footer_markup' ),
				),
				'input_attrs' => array(
					'group'   => ASTRA_THEME_SETTINGS . '[footer-desktop-items]',
					'rows'    => array( 'above', 'primary', 'below' ),
					'zones'   => self::$zones,
					'layouts' => array(
						'above'   => array(
							'column' => astra_get_option( 'hba-footer-column' ),
							'layout' => astra_get_option( 'hba-footer-layout' ),
						),
						'primary' => array(
							'column' => astra_get_option( 'hb-footer-column' ),
							'layout' => astra_get_option( 'hb-footer-layout' ),
						),
						'below'   => array(
							'column' => astra_get_option( 'hbb-footer-column' ),
							'layout' => astra_get_option( 'hbb-footer-layout' ),
						),
					),
					'status'  => array(
						'above'   => true,
						'primary' => true,
						'below'   => true,
					),
				),
				'context'     => array(
					array(
						'setting'  => 'ast_selected_tab',
						'operator' => 'in',
						'value'    => array( 'general', 'design' ),
					),
				),
			),

			/**
			 * Footer Available draggable items.
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[footer-draggable-items]',
				'section'     => 'section-footer-builder-layout',
				'type'        => 'control',
				'control'     => 'ast-draggable-items',
				'priority'    => 10,
				'input_attrs' => array(
					'group' => ASTRA_THEME_SETTINGS . '[footer-desktop-items]',
					'zones' => array( 'above', 'primary', 'below' ),
				),
				'context'     => Astra_Builder_Helper::$general_tab,
			),
		);

		$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_advanced_tab( 'section-footer-builder-layout' ) );

		return array_merge( $configurations, $_configs );
	}
}

/**
 * Kicking this off by creating object of this class.
 */
if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {
	new Astra_Customizer_Footer_Builder_Configs();
}
