<?php
/**
 * General Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Header_Layout_Configs' ) ) {

	/**
	 * Register Header Layout Customizer Configurations.
	 */
	class Astra_Header_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Header Layout
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[header-layouts]',
					'default'           => astra_get_option( 'header-layouts' ),
					'section'           => 'section-header',
					'priority'          => 4,
					'title'             => __( 'Layout', 'astra' ),
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
					'choices'           => array(
						'header-main-layout-1' => array(
							'label' => __( 'Logo Left', 'astra' ),
							'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'header-main-layout-1' ),
						),
						'header-main-layout-2' => array(
							'label' => __( 'Logo Center', 'astra' ),
							'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'header-main-layout-2' ),
						),
						'header-main-layout-3' => array(
							'label' => __( 'Logo Right', 'astra' ),
							'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'header-main-layout-3' ),
						),
					),
				),

				/**
				 * Option: Header Width
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-layout-width]',
					'default'  => astra_get_option( 'header-main-layout-width' ),
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-header',
					'priority' => 4,
					'title'    => __( 'Width', 'astra' ),
					'choices'  => array(
						'full'    => __( 'Full Width', 'astra' ),
						'content' => __( 'Content Width', 'astra' ),
					),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Bottom Border Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[header-main-sep]',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'header-main-sep' ),
					'type'        => 'control',
					'control'     => 'number',
					'section'     => 'section-header',
					'priority'    => 4,
					'title'       => __( 'Bottom Border Size', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Bottom Border Color
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[header-main-sep-color]',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'header-main-sep-color' ),
					'type'              => 'control',
					'context'           => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-sep]',
							'operator' => '>=',
							'value'    => 1,
						),
					),
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => 'section-header',
					'priority'          => 4,
					'title'             => __( 'Bottom Border Color', 'astra' ),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
					'default'  => astra_get_option( 'disable-primary-nav' ),
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'section'  => 'section-primary-menu',
					'title'    => __( 'Disable Menu', 'astra' ),
					'priority' => 5,
					'partial'  => array(
						'selector'            => '.main-header-bar .main-navigation',
						'container_inclusive' => false,
					),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
					'default'  => astra_get_option( 'header-main-rt-section' ),
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-primary-menu',
					'priority' => 7,
					'title'    => __( 'Last Item in Menu', 'astra' ),
					'choices'  => apply_filters(
						'astra_header_section_elements',
						array(
							'none'      => __( 'None', 'astra' ),
							'search'    => __( 'Search', 'astra' ),
							'button'    => __( 'Button', 'astra' ),
							'text-html' => __( 'Text / HTML', 'astra' ),
							'widget'    => __( 'Widget', 'astra' ),
						),
						'primary-header'
					),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
					'partial'  => array(
						'selector'            => '.main-header-bar .main-navigation .main-header-menu .ast-masthead-custom-menu-items.search-custom-menu-item .ast-search-icon .astra-search-icon, .main-header-bar .main-navigation .main-header-menu .ast-masthead-custom-menu-items.woocommerce-custom-menu-item, .main-header-bar .ast-masthead-custom-menu-items.widget-custom-menu-item .ast-header-widget-area .widget.ast-no-widget-row, .main-header-bar .main-navigation .main-header-menu .ast-masthead-custom-menu-items.edd-custom-menu-item',
						'container_inclusive' => false,
					),
				),

				/**
				* Option: Button Text
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-text]',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'header-main-rt-section-button-text' ),
					'type'      => 'control',
					'control'   => 'text',
					'section'   => 'section-primary-menu',
					'partial'   => array(
						'selector'            => '.button-custom-menu-item',
						'container_inclusive' => false,
						'render_callback'     => 'Astra_Customizer_Partials::render_header_main_rt_section_button_text',
					),
					'context'   => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '===',
							'value'    => 'button',
						),
					),
					'priority'  => 10,
					'title'     => __( 'Button Text', 'astra' ),
				),

				/**
				* Option: Button Link
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-link-option]',
					'default'  => astra_get_option( 'header-main-rt-section-button-link-option' ),
					'type'     => 'control',
					'control'  => 'ast-link',
					'section'  => 'section-primary-menu',
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '===',
							'value'    => 'button',
						),
					),
					'priority' => 10,
					'title'    => __( 'Button Link', 'astra' ),
				),

				/**
				* Option: Button Style
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-style]',
					'default'  => astra_get_option( 'header-main-rt-section-button-style' ),
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-primary-menu',
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '===',
							'value'    => 'button',
						),
					),
					'priority' => 10,
					'choices'  => array(
						'theme-button'  => __( 'Theme Button', 'astra' ),
						'custom-button' => __( 'Header Button', 'astra' ),
					),
					'title'    => __( 'Button Style', 'astra' ),
				),

				/**
				* Option: Theme Button Style edit link
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-button-style-link]',
					'default'   => astra_get_option( 'header-button-style-link' ),
					'type'      => 'control',
					'control'   => 'ast-customizer-link',
					'section'   => 'section-primary-menu',
					'context'   => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '===',
							'value'    => 'button',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-style]',
							'operator' => '===',
							'value'    => 'theme-button',
						),
					),
					'priority'  => 10,
					'link_type' => 'section',
					'linked'    => 'section-buttons',
					'link_text' => __( 'Customize Button Style.', 'astra' ),
				),

				/**
				 * Option: Right Section Text / HTML
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-rt-section-html]',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'header-main-rt-section-html' ),
					'type'      => 'control',
					'control'   => 'textarea',
					'section'   => 'section-primary-menu',
					'context'   => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '===',
							'value'    => 'text-html',
						),
					),
					'priority'  => 10,
					'partial'   => array(
						'selector'            => '.main-header-bar .ast-masthead-custom-menu-items .ast-custom-html',
						'container_inclusive' => false,
						'render_callback'     => 'Astra_Customizer_Partials::render_header_main_rt_section_html',
					),
					'title'     => __( 'Custom Menu Text / HTML', 'astra' ),
				),

				array(
					'name'     => 'primary-header-sub-menu-label-divider',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 30,
					'title'    => __( 'Sub Menu', 'astra' ),
					'section'  => 'section-primary-menu',
					'settings' => array(),
				),

				/**
				 * Option: Submenu Container Animation
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-submenu-container-animation]',
					'default'  => astra_get_option( 'header-main-submenu-container-animation' ),
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-primary-menu',
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
							'operator' => '!=',
							'value'    => true,
						),
					),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
					'priority' => 30,
					'title'    => __( 'Container Animation', 'astra' ),
					'choices'  => array(
						''           => __( 'Default', 'astra' ),
						'slide-down' => __( 'Slide Down', 'astra' ),
						'slide-up'   => __( 'Slide Up', 'astra' ),
						'fade'       => __( 'Fade', 'astra' ),
					),
				),

				// Option: Primary Menu Border.
				array(
					'type'           => 'control',
					'control'        => 'ast-border',
					'transport'      => 'postMessage',
					'name'           => ASTRA_THEME_SETTINGS . '[primary-submenu-border]',
					'section'        => 'section-primary-menu',
					'linked_choices' => true,
					'priority'       => 30,
					'default'        => astra_get_option( 'primary-submenu-border' ),
					'title'          => __( 'Container Border', 'astra' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
				),

				// Option: Submenu Container Border Color.
				array(
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'name'              => ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]',
					'default'           => astra_get_option( 'primary-submenu-b-color' ),
					'title'             => __( 'Border Color', 'astra' ),
					'section'           => 'section-primary-menu',
					'priority'          => 30,
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				array(
					'type'      => 'control',
					'control'   => 'ast-toggle-control',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-submenu-item-border]',
					'section'   => 'section-primary-menu',
					'priority'  => 30,
					'default'   => astra_get_option( 'primary-submenu-item-border' ),
					'title'     => __( 'Submenu Divider', 'astra' ),
				),

				// Option: Submenu item Border Color.
				array(
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'name'              => ASTRA_THEME_SETTINGS . '[primary-submenu-item-b-color]',
					'default'           => astra_get_option( 'primary-submenu-item-b-color' ),
					'title'             => __( 'Divider Color', 'astra' ),
					'section'           => 'section-primary-menu',
					'context'           => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[primary-submenu-item-border]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'          => 30,
				),

				/**
				 * Option: Mobile Menu Label Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-menu-label-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-header',
					'priority' => 35,
					'title'    => __( 'Mobile Header', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Mobile Menu Alignment
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[header-main-menu-align]',
					'default'           => astra_get_option( 'header-main-menu-align' ),
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
					'choices'           => array(
						'inline' => array(
							'label' => __( 'Inline', 'astra' ),
							'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'menu-inline' ),
						),
						'stack'  => array(
							'label' => __( 'Stack', 'astra' ),
							'path'  => Astra_Builder_UI_Controller::fetch_svg_icon( 'menu-stack' ),
						),
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'section'           => 'section-header',
					'priority'          => 40,
					'title'             => __( 'Layout', 'astra' ),
				),

				/**
				 * Option: Hide Last item in Menu on mobile device
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]',
					'default'  => astra_get_option( 'hide-custom-menu-mobile' ),
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => 'in',
							'value'    => array( 'button', 'text-html' ),
						),
					),
					'section'  => 'section-primary-menu',
					'title'    => __( 'Hide Last Item in Menu on Mobile', 'astra' ),
					'priority' => 7,
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Display outside menu
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-display-outside-menu]',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]',
							'operator' => '!=',
							'value'    => '1',
						),
					),
					'default'  => astra_get_option( 'header-display-outside-menu' ),
					'section'  => 'section-primary-menu',
					'title'    => __( 'Take Last Item Outside Menu', 'astra' ),
					'priority' => 7,
				),

				array(
					'name'     => 'primary-menu-label-divider',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 39,
					'title'    => __( 'Mobile Menu', 'astra' ),
					'section'  => 'section-primary-menu',
					'settings' => array(),
				),

				/**
				 * Option: Mobile Header Breakpoint
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[mobile-header-breakpoint]',
					'default'           => astra_get_option( 'mobile-header-breakpoint' ),
					'type'              => 'control',
					'control'           => 'ast-slider',
					'section'           => 'section-primary-menu',
					'priority'          => 40,
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Menu Breakpoint', 'astra' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 10,
						'max'  => 6000,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Notice to add # link to parent menu when Link option selected in Dropdown Target.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-target-link-notice]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-header',
					'priority' => 41,
					'title'    => '',
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-target]',
							'operator' => '==',
							'value'    => 'link',
						),
					),
					'help'     => __( 'The parent menu should have a # link for the submenu to open on a link.', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Mobile Menu Label.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-menu-label]',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'header-main-menu-label' ),
					'section'   => 'section-primary-menu',
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'   => array(
						'relation' => 'AND',
						( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab,
						array(
							'relation' => 'OR',
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
								'operator' => '!=',
								'value'    => array( 'none' ),
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
								'operator' => '!=',
								'value'    => array( '1' ),
							),
						),
					),
					'priority'  => 40,
					'title'     => __( 'Menu Label', 'astra' ),
					'type'      => 'control',
					'control'   => 'text',
					'partial'   => array(
						'selector'            => '.ast-button-wrap',
						'container_inclusive' => false,
						'render_callback'     => 'Astra_Customizer_Partials::mobile_toggle_menu',
					),
				),

				/**
				 * Option: Toggle Button Style
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'default'  => astra_get_option( 'mobile-header-toggle-btn-style' ),
					'section'  => 'section-primary-menu',
					'title'    => __( 'Toggle Button Style', 'astra' ),
					'type'     => 'control',
					'control'  => 'ast-select',
					'priority' => 42,
					'context'  => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
							'operator' => '!=',
							'value'    => true,
						),
					),
					'choices'  => array(
						'fill'    => __( 'Fill', 'astra' ),
						'outline' => __( 'Outline', 'astra' ),
						'minimal' => __( 'Minimal', 'astra' ),
					),
				),

				/**
				 * Option: Toggle Button Color
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style-color]',
					'default'           => astra_get_option( 'mobile-header-toggle-btn-style-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'context'           => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[mobile-menu-style]',
							'operator' => '!=',
							'value'    => 'no-toggle',
						),
					),
					'title'             => __( 'Toggle Button Color', 'astra' ),
					'section'           => 'section-primary-menu',
					'transport'         => 'postMessage',
					'priority'          => 42,
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Border Radius
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-border-radius]',
					'default'     => astra_get_option( 'mobile-header-toggle-btn-border-radius' ),
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-primary-menu',
					'title'       => __( 'Border Radius', 'astra' ),
					'context'     => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
							'operator' => '!=',
							'value'    => 'minimal',
						),
					),
					'priority'    => 42,
					'suffix'      => 'px',
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
				),

				/**
				 * Option: Toggle on click of button or link.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-target]',
					'default'  => astra_get_option( 'mobile-header-toggle-target' ),
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-primary-menu',
					'priority' => 42,
					'title'    => __( 'Dropdown Target', 'astra' ),
					'suffix'   => '',
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
					'choices'  => array(
						'icon' => __( 'Icon', 'astra' ),
						'link' => __( 'Link', 'astra' ),
					),
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$config = array(

					/**
					 * Option: Learn More about Mobile Header
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-header',
						'priority' => 999,
						'title'    => '',
						'help'     => '<p>' . __( 'More Options Available in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-secondary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
						'settings' => array(),
						'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
					),
				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;
		}
	}
}


new Astra_Header_Layout_Configs();
