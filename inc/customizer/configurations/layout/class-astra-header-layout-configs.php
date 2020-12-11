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

			$header_rt_sections = array(
				'none'      => __( 'None', 'astra' ),
				'search'    => __( 'Search', 'astra' ),
				'text-html' => __( 'Text / HTML', 'astra' ),
				'widget'    => __( 'Widget', 'astra' ),
			);

			$_configs = array(

				/**
				 * Option: Header Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-layouts]',
					'default'  => astra_get_option( 'header-layouts' ),
					'section'  => 'section-header',
					'priority' => 4,
					'title'    => __( 'Layout', 'astra' ),
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'choices'  => array(
						'header-main-layout-1' => array(
							'label' => __( 'Logo Left', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="0 0 120.5 81" enable-background="new 0 0 120.5 81" xml:space="preserve"><g><g><path fill="#0085BA" d="M116.701,80.797H3.799c-1.958,0-3.549-1.593-3.549-3.55V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.957,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.658,80.797,116.701,80.797z M3.799,1.979 c-0.979,0-1.775,0.795-1.775,1.774v73.494c0,0.979,0.796,1.774,1.775,1.774h112.902c0.979,0,1.773-0.795,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z"/></g><g><g><path fill="#0085BA" d="M107.275,16.6H48.385c-0.98,0-1.774-0.794-1.774-1.774s0.794-1.774,1.774-1.774h58.891 c0.979,0,1.773,0.794,1.773,1.774S108.254,16.6,107.275,16.6z"/></g><g><path fill="#0085BA" d="M34.511,16.689c0,0.989-0.929,1.789-2.074,1.789H16.116c-1.146,0-2.075-0.8-2.075-1.789v-3.727 c0-0.989,0.929-1.79,2.075-1.79h16.321c1.146,0,2.074,0.801,2.074,1.79V16.689z"/></g></g></g><line fill="none" stroke="#0085BA" stroke-miterlimit="10" x1="0.25" y1="28.342" x2="119.535" y2="28.342"/></svg>',
						),
						'header-main-layout-2' => array(
							'label' => __( 'Logo Center', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="0 0 120.5 81" enable-background="new 0 0 120.5 81" xml:space="preserve"><line fill="none" stroke="#0085BA" stroke-miterlimit="10" x1="0.607" y1="28.341" x2="119.893" y2="28.341"/><g><path fill="#0085BA" d="M116.701,80.795H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.752c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.955,0,3.549,1.592,3.549,3.549v73.494C120.25,79.203,118.656,80.795,116.701,80.795z M3.799,1.978 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.98,0.795,1.775,1.773,1.775h112.902c0.979,0,1.773-0.797,1.773-1.775V3.752 c0-0.979-0.795-1.774-1.773-1.774H3.799z"/></g><g><g><path fill="#0085BA" d="M69.314,12.413c0,1.014-0.822,1.837-1.836,1.837H53.021c-1.015,0-1.837-0.823-1.837-1.837V8.586 c0-1.015,0.822-1.837,1.837-1.837h14.458c1.014,0,1.836,0.822,1.836,1.837V12.413z"/></g></g><g><path fill="#0085BA" d="M99.697,22.067H20.804c-0.98,0-1.774-0.672-1.774-1.5c0-0.828,0.794-1.5,1.774-1.5h78.894 c0.979,0,1.772,0.672,1.772,1.5C101.471,21.395,100.676,22.067,99.697,22.067z"/></g></svg>',
						),
						'header-main-layout-3' => array(
							'label' => __( 'Logo Right', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="0 0 120.5 81" enable-background="new 0 0 120.5 81" xml:space="preserve"><g><g><path fill="#0085BA" d="M0.25,77.247V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902c1.957,0,3.549,1.592,3.549,3.549v73.494 c0,1.957-1.592,3.55-3.549,3.55H3.799C1.842,80.797,0.25,79.204,0.25,77.247z M3.799,1.979c-0.979,0-1.774,0.795-1.774,1.774 v73.494c0,0.979,0.796,1.774,1.774,1.774h112.902c0.979,0,1.773-0.795,1.773-1.774V3.753c0-0.979-0.795-1.774-1.773-1.774H3.799z"/></g><g><g><path fill="#0085BA" d="M13.225,16.6h58.891c0.979,0,1.774-0.794,1.774-1.774s-0.795-1.774-1.774-1.774H13.225 c-0.979,0-1.773,0.794-1.773,1.774C11.451,15.806,12.246,16.6,13.225,16.6z"/></g><g><path fill="#0085BA" d="M85.988,16.689c0,0.989,0.93,1.789,2.074,1.789h16.321c1.146,0,2.074-0.8,2.074-1.789v-3.727 c0-0.989-0.929-1.79-2.074-1.79H88.063c-1.145,0-2.073,0.801-2.073,1.79L85.988,16.689L85.988,16.689z"/></g></g></g><line fill="none" stroke="#0085BA" stroke-miterlimit="10" x1="120.25" y1="28.342" x2="0.965" y2="28.342"/></svg>',
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
					'control'  => 'select',
					'section'  => 'section-header',
					'priority' => 4,
					'title'    => __( 'Width', 'astra' ),
					'choices'  => array(
						'full'    => __( 'Full Width', 'astra' ),
						'content' => __( 'Content Width', 'astra' ),
					),
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
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-sep-color]',
					'transport' => 'postMessage',
					'default'   => '',
					'type'      => 'control',
					'required'  => array( ASTRA_THEME_SETTINGS . '[header-main-sep]', '>=', 1 ),
					'control'   => 'ast-color',
					'section'   => 'section-header',
					'priority'  => 4,
					'title'     => __( 'Bottom Border Color', 'astra' ),
				),

				array(
					'name'     => 'primary-header-menu-label-divider',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 5,
					'title'    => __( 'Menu', 'astra' ),
					'section'  => 'section-primary-menu',
					'settings' => array(),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
					'default'  => astra_get_option( 'disable-primary-nav' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'section'  => 'section-primary-menu',
					'title'    => __( 'Disable Menu', 'astra' ),
					'priority' => 5,
					'partial'  => array(
						'selector'            => '.main-header-bar .main-navigation',
						'container_inclusive' => false,
					),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
					'default'  => astra_get_option( 'header-main-rt-section' ),
					'type'     => 'control',
					'control'  => 'select',
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
					'required'  => array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '===', 'button' ),
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
					'required' => array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '===', 'button' ),
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
					'control'  => 'select',
					'section'  => 'section-primary-menu',
					'required' => array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '===', 'button' ),
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
					'required'  => array(
						array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '===', 'button' ),
						array( ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-style]', '===', 'theme-button' ),
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
					'required'  => array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '===', 'text-html' ),
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
					'control'  => 'select',
					'section'  => 'section-primary-menu',
					'required' => array(
						ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
						'!=',
						true,
					),
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
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]',
					'default'   => '',
					'title'     => __( 'Border Color', 'astra' ),
					'section'   => 'section-primary-menu',
					'priority'  => 30,
				),

				array(
					'type'      => 'control',
					'control'   => 'checkbox',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-submenu-item-border]',
					'section'   => 'section-primary-menu',
					'priority'  => 30,
					'default'   => astra_get_option( 'primary-submenu-item-border' ),
					'title'     => __( 'Submenu Divider', 'astra' ),
				),

				// Option: Submenu item Border Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-submenu-item-b-color]',
					'default'   => '',
					'title'     => __( 'Divider Color', 'astra' ),
					'section'   => 'section-primary-menu',
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[primary-submenu-item-border]',
						'==',
						true,
					),
					'priority'  => 30,
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
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-menu-align]',
					'default'  => astra_get_option( 'header-main-menu-align' ),
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'choices'  => array(
						'inline' => array(
							'label' => __( 'Inline', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="60.5px" height="81px" viewBox="0 0 60.5 81" enable-background="new 0 0 60.5 81" xml:space="preserve"><g><g><g><path fill="#0085BA" d="M51.602,12.975H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,12.546,52.098,12.975,51.602,12.975z"/></g></g><g><g><path fill="#0085BA" d="M51.602,17.205H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,16.775,52.098,17.205,51.602,17.205z"/></g></g><g><g><path fill="#0085BA" d="M51.602,21.435H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,21.004,52.098,21.435,51.602,21.435z"/></g></g></g><g><path fill="#0085BA" d="M25.504,20.933c0,1.161-0.794,2.099-1.773,2.099H9.777c-0.979,0-1.773-0.938-1.773-2.099V11.56 c0-1.16,0.795-2.1,1.773-2.1H23.73c0.979,0,1.772,0.94,1.772,2.1L25.504,20.933L25.504,20.933z"/></g><g><path fill="#0085BA" d="M56.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h52.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C60.25,79.204,58.657,80.796,56.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.774,1.773,1.774h52.902c0.979,0,1.773-0.797,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z"/></g></svg>',
						),
						'stack'  => array(
							'label' => __( 'Stack', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="60.5px" height="81px" viewBox="0 0 60.5 81" enable-background="new 0 0 60.5 81" xml:space="preserve"><g><path fill="#0085BA" d="M56.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h52.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C60.25,79.204,58.657,80.796,56.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.774,1.773,1.774h52.902c0.979,0,1.773-0.797,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z"/></g><g><g><g><path fill="#0085BA" d="M35.607,29.821H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,29.392,36.104,29.821,35.607,29.821z"/></g></g><g><g><path fill="#0085BA" d="M35.607,34.051H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,33.621,36.104,34.051,35.607,34.051z"/></g></g><g><g><path fill="#0085BA" d="M35.607,38.281H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,37.85,36.104,38.281,35.607,38.281z"/></g></g></g><g><path fill="#0085BA" d="M39,20.933c0,1.161-0.794,2.099-1.773,2.099H23.273c-0.979,0-1.773-0.938-1.773-2.099V11.56 c0-1.16,0.795-2.1,1.773-2.1h13.954c0.979,0,1.771,0.94,1.771,2.1L39,20.933L39,20.933z"/></g></svg>',
						),
					),
					'section'  => 'section-header',
					'priority' => 40,
					'title'    => __( 'Layout', 'astra' ),
				),

				/**
				 * Option: Hide Last item in Menu on mobile device
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]',
					'default'  => astra_get_option( 'hide-custom-menu-mobile' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '!=', 'none' ),
					'section'  => 'section-primary-menu',
					'title'    => __( 'Hide Last Item in Menu on Mobile', 'astra' ),
					'priority' => 7,
				),

				/**
				 * Option: Display outside menu
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-display-outside-menu]',
					'type'     => 'control',
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]', '!=', '1' ),
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
					'default'           => '',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'section'           => 'section-primary-menu',
					'priority'          => 40,
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Menu Breakpoint', 'astra' ),
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 10,
						'max'  => 6000,
					),
				),

				/**
				 * Option: Toggle on click of button or link.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-target]',
					'default'  => astra_get_option( 'mobile-header-toggle-target' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-header',
					'priority' => 42,
					'title'    => __( 'Dropdown Target', 'astra' ),
					'suffix'   => '',
					'choices'  => array(
						'icon' => __( 'Icon', 'astra' ),
						'link' => __( 'Link', 'astra' ),
					),
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
					'required' => array( ASTRA_THEME_SETTINGS . '[mobile-header-toggle-target]', '==', 'link' ),
					'help'     => __( 'The parent menu should have a # link for the submenu to open on a link.', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Mobile Menu Label
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-menu-label]',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'header-main-menu-label' ),
					'section'   => 'section-primary-menu',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '!=', array( 'none' ) ),
							array( ASTRA_THEME_SETTINGS . '[disable-primary-nav]', '!=', array( '1' ) ),
						),
						'operator'   => 'OR',
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
					'control'  => 'select',
					'priority' => 42,
					'required' => array( ASTRA_THEME_SETTINGS . '[mobile-menu-style]', '!=', 'no-toggle' ),
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
					'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style-color]',
					'default'   => astra_get_option( 'mobile-header-toggle-btn-style-color' ),
					'type'      => 'control',
					'control'   => 'ast-color',
					'required'  => array( ASTRA_THEME_SETTINGS . '[mobile-menu-style]', '!=', 'no-toggle' ),
					'title'     => __( 'Toggle Button Color', 'astra' ),
					'section'   => 'section-primary-menu',
					'transport' => 'postMessage',
					'priority'  => 42,
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
					'required'    => array( ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]', '!=', 'minimal' ),
					'priority'    => 42,
					'suffix'      => '',
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
					'control'  => 'select',
					'section'  => 'section-primary-menu',
					'priority' => 42,
					'title'    => __( 'Dropdown Target', 'astra' ),
					'suffix'   => '',
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
					 * Option: Divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-more-feature-divider]',
						'type'     => 'control',
						'control'  => 'ast-divider',
						'section'  => 'section-header',
						'priority' => 999,
						'settings' => array(),
					),

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
					),
				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;
		}
	}
}


new Astra_Header_Layout_Configs();




