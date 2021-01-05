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

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class Astra_Customizer_Header_Builder_Configs extends Astra_Customizer_Config_Base {

	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_header_button; $index++ ) {

			$item = array(
				'name'    => ( 1 === Astra_Builder_Helper::$num_of_header_button ) ? 'Button' : 'Button ' . $index,
				'icon'    => 'admin-links',
				'section' => 'section-hb-button-' . $index,
			);

			Astra_Builder_Helper::$header_desktop_items[ 'button-' . $index ] = $item;
			Astra_Builder_Helper::$header_mobile_items[ 'button-' . $index ]  = $item;
		}

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_header_html; $index++ ) {

			$item = array(
				'name'    => ( 1 === Astra_Builder_Helper::$num_of_header_html ) ? 'HTML' : 'HTML ' . $index,
				'icon'    => 'text',
				'section' => 'section-hb-html-' . $index,
			);

			Astra_Builder_Helper::$header_desktop_items[ 'html-' . $index ] = $item;
			Astra_Builder_Helper::$header_mobile_items[ 'html-' . $index ]  = $item;
		}

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_header_widgets; $index++ ) {

			$item = array(
				'name'    => ( 1 === Astra_Builder_Helper::$num_of_header_widgets ) ? 'Widget' : 'Widget ' . $index,
				'icon'    => 'wordpress',
				'section' => 'sidebar-widgets-header-widget-' . $index,
			);

			Astra_Builder_Helper::$header_desktop_items[ 'widget-' . $index ] = $item;
			Astra_Builder_Helper::$header_mobile_items[ 'widget-' . $index ]  = $item;
		}

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_header_menu; $index++ ) {

			switch ( $index ) {
				case 1:
					$name = __( 'Primary Menu', 'astra' );
					break;
				case 2:
					$name = __( 'Secondary Menu', 'astra' );
					break;
				default:
					$name = __( 'Menu ', 'astra' ) . $index;
					break;
			}

			$item = array(
				'name'    => $name,
				'icon'    => 'menu',
				'section' => 'section-hb-menu-' . $index,
			);

			Astra_Builder_Helper::$header_desktop_items[ 'menu-' . $index ] = $item;

			Astra_Builder_Helper::$header_mobile_items[ 'menu-' . $index ] = $item;
		}

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_header_social_icons; $index++ ) {

			$item = array(
				'name'    => ( 1 === Astra_Builder_Helper::$num_of_header_social_icons ) ? 'Social' : 'Social ' . $index,
				'icon'    => 'share',
				'section' => 'section-hb-social-icons-' . $index,
			);

			Astra_Builder_Helper::$header_desktop_items[ 'social-icons-' . $index ] = $item;
			Astra_Builder_Helper::$header_mobile_items[ 'social-icons-' . $index ]  = $item;

		}

		$_configs = array(

			/*
			* Header Builder section
			*/
			array(
				'name'     => 'section-header-builder',
				'type'     => 'section',
				'priority' => 5,
				'title'    => __( 'Header Builder', 'astra' ),
				'panel'    => 'panel-header-builder-group',
			),

			/**
			 * Option: Header Layout
			 */
			array(
				'name'     => 'section-header-builder-layout',
				'type'     => 'section',
				'priority' => 0,
				'title'    => __( 'Header Layout', 'astra' ),
				'panel'    => 'panel-header-builder-group',
			),

			/**
			 * Option: Header Builder Tabs
			 */
			array(
				'name'        => 'section-header-builder-layout-ast-context-tabs',
				'section'     => 'section-header-builder-layout',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			),

			/**
			 * Option: Header Builder
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[builder-header]',
				'section'     => 'section-header-builder',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 40,
				'description' => '',
			),

			/**
			 * Option: Header Desktop Items.
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-desktop-items]',
				'section'     => 'section-header-builder',
				'type'        => 'control',
				'control'     => 'ast-builder',
				'title'       => __( 'Header Builder', 'astra' ),
				'priority'    => 25,
				'default'     => astra_get_option( 'header-desktop-items' ),
				'choices'     => Astra_Builder_Helper::$header_desktop_items,
				'transport'   => 'postMessage',
				'partial'     => array(
					'selector'            => '#masthead',
					'container_inclusive' => true,
					'render_callback'     => array( Astra_Builder_Header::get_instance(), 'header_builder_markup' ),
				),
				'input_attrs' => array(
					'group'  => ASTRA_THEME_SETTINGS . '[header-desktop-items]',
					'rows'   => array( 'above', 'primary', 'below' ),
					'zones'  => array(
						'above'   => array(
							'above_left'         => 'Top - Left',
							'above_left_center'  => 'Top - Left Center',
							'above_center'       => 'Top - Center',
							'above_right_center' => 'Top - Right Center',
							'above_right'        => 'Top - Right',
						),
						'primary' => array(
							'primary_left'         => 'Main - Left',
							'primary_left_center'  => 'Main - Left Center',
							'primary_center'       => 'Main - Center',
							'primary_right_center' => 'Main - Right Center',
							'primary_right'        => 'Main - Right',
						),
						'below'   => array(
							'below_left'         => 'Bottom - Left',
							'below_left_center'  => 'Bottom - Left Center',
							'below_center'       => 'Bottom - Center',
							'below_right_center' => 'Bottom - Right Center',
							'below_right'        => 'Bottom - Right',
						),
					),
					'status' => array(
						'above'   => true,
						'primary' => true,
						'below'   => true,
					),
				),
				'context'     => array(
					array(
						'setting' => 'ast_selected_device',
						'value'   => 'desktop',
					),
				),
			),

			/**
			 * Header Desktop Available draggable items.
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-desktop-draggable-items]',
				'section'     => 'section-header-builder-layout',
				'type'        => 'control',
				'control'     => 'ast-draggable-items',
				'priority'    => 30,
				'input_attrs' => array(
					'group' => ASTRA_THEME_SETTINGS . '[header-desktop-items]',
					'zones' => array( 'above', 'primary', 'below' ),
				),
				'context'     => array(
					array(
						'setting' => 'ast_selected_device',
						'value'   => 'desktop',
					),
					array(
						'setting' => 'ast_selected_tab',
						'value'   => 'general',
					),
				),
			),

			/**
			 * Option: Header Mobile Items.
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-mobile-items]',
				'section'     => 'section-header-builder',
				'type'        => 'control',
				'control'     => 'ast-builder',
				'title'       => __( 'Header Builder', 'astra' ),
				'priority'    => 35,
				'default'     => astra_get_option( 'header-mobile-items' ),
				'choices'     => Astra_Builder_Helper::$header_mobile_items,
				'transport'   => 'postMessage',
				'partial'     => array(
					'selector'            => '#masthead',
					'container_inclusive' => true,
					'render_callback'     => array( Astra_Builder_Header::get_instance(), 'header_builder_markup' ),
				),
				'input_attrs' => array(
					'group'  => ASTRA_THEME_SETTINGS . '[header-mobile-items]',
					'rows'   =>
						array( 'popup', 'above', 'primary', 'below' ),
					'zones'  =>
						array(
							'popup'   =>
								array(
									'popup_content' => 'Popup Content',
								),
							'above'   =>
								array(
									'above_left'   => 'Top - Left',
									'above_center' => 'Top - Center',
									'above_right'  => 'Top - Right',
								),
							'primary' =>
								array(
									'primary_left'   => 'Main - Left',
									'primary_center' => 'Main - Center',
									'primary_right'  => 'Main - Right',
								),
							'below'   =>
								array(
									'below_left'   => 'Bottom - Left',
									'below_center' => 'Bottom - Center',
									'below_right'  => 'Bottom - Right',
								),
						),
					'status' => array(
						'above'   => true,
						'primary' => true,
						'below'   => true,
					),
				),
				'context'     => Astra_Builder_Helper::$responsive_devices,
			),

			/**
			 * Header Mobile Available draggable items.
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-mobile-draggable-items]',
				'section'     => 'section-header-builder-layout',
				'type'        => 'control',
				'control'     => 'ast-draggable-items',
				'input_attrs' => array(
					'group' => ASTRA_THEME_SETTINGS . '[header-mobile-items]',
					'zones' => array( 'popup', 'above', 'primary', 'below' ),
				),
				'priority'    => 43,
				'context'     => array(
					array(
						'setting'  => 'ast_selected_device',
						'operator' => 'in',
						'value'    => array( 'tablet', 'mobile' ),
					),
					array(
						'setting' => 'ast_selected_tab',
						'value'   => 'general',
					),
				),
			),

			/**
			 * Header Mobile popup items.
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-mobile-popup-items]',
				'section'   => 'section-header-builder-layout',
				'type'      => 'control',
				'control'   => 'ast-hidden',
				'priority'  => 43,
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'            => '#ast-mobile-popup-wrapper',
					'container_inclusive' => true,
					'render_callback'     => array( Astra_Builder_Header::get_instance(), 'mobile_popup' ),
				),
				'default'   => false,
			),

			/**
			 * Option: Blog Color Section heading
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[header-transparent-link-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => 'section-header-builder-layout',
				'title'    => __( 'Header Types', 'astra' ),
				'priority' => 44,
				'settings' => array(),
				'context'  => Astra_Builder_Helper::$general_tab,
			),

			/**
			 * Option: Header Transparant
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-transparant-link]',
				'section'     => 'section-header-builder-layout',
				'type'        => 'control',
				'control'     => 'ast-header-type-button',
				'input_attrs' => array(
					'section' => 'section-transparent-header',
					'label'   => esc_html__( 'Transparent Header', 'astra' ),
				),
				'priority'    => 45,
				'context'     => Astra_Builder_Helper::$general_tab,
				'settings'    => false,
			),

			// Option: Header Width.
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[hb-header-main-layout-width]',
				'default'   => astra_get_option( 'hb-header-main-layout-width' ),
				'type'      => 'control',
				'control'   => 'select',
				'section'   => 'section-header-builder-layout',
				'priority'  => 4,
				'title'     => __( 'Width', 'astra' ),
				'choices'   => array(
					'full'    => __( 'Full Width', 'astra' ),
					'content' => __( 'Content Width', 'astra' ),
				),
				'context'   => array(
					array(
						'setting' => 'ast_selected_tab',
						'value'   => 'design',
					),
					array(
						'setting' => 'ast_selected_device',
						'value'   => 'desktop',
					),
				),
				'transport' => 'postMessage',
			),

			/**
			 * Option: Margin for Header Builder.
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[section-header-builder-layout-margin-padding-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => 'section-header-builder-layout',
				'title'    => __( 'Spacing', 'astra' ),
				'priority' => 200,
				'settings' => array(),
				'context'  => Astra_Builder_Helper::$design_tab,
			),

			array(
				'name'           => ASTRA_THEME_SETTINGS . '[section-header-builder-layout-margin]',
				'default'        => astra_get_option( 'section-header-builder-layout-margin' ),
				'type'           => 'control',
				'transport'      => 'postMessage',
				'control'        => 'ast-responsive-spacing',
				'section'        => 'section-header-builder-layout',
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

		if ( defined( 'ASTRA_EXT_VER' ) && Astra_Ext_Extension::is_active( 'sticky-header' ) ) {
			/**
			 * Option: Header Transparant
			 */
			$_configs[] = array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-sticky-link]',
				'section'     => 'section-header-builder-layout',
				'type'        => 'control',
				'control'     => 'ast-header-type-button',
				'input_attrs' => array(
					'section' => 'section-sticky-header',
					'label'   => esc_html__( 'Sticky Header', 'astra' ),
				),
				'priority'    => 45,
				'context'     => Astra_Builder_Helper::$general_tab,
				'settings'    => false,
			);
		}

		return array_merge( $configurations, $_configs );
	}
}

/**
 * Kicking this off by creating object of this class.
 */
if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {
	new Astra_Customizer_Header_Builder_Configs();
}
