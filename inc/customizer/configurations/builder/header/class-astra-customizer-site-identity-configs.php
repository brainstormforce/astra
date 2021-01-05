<?php
/**
 * Astra Theme Customizer Configuration Site Identity.
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

if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

	/**
	 * Register Site Identity Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class Astra_Customizer_Site_Identity_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Builder Site Identity Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'title_tagline';

			$_configs = array(

				/*
				 * Update the Site Identity section inside Layout -> Header
				 *
				 * @since 3.0.0
				 */
				array(
					'name'     => 'title_tagline',
					'type'     => 'section',
					'priority' => 100,
					'title'    => __( 'Logo', 'astra' ),
					'panel'    => 'panel-header-builder-group',
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
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-site-logo-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Site Icon', 'astra' ),
					'section'  => 'title_tagline',
					'priority' => 15,
					'settings' => array(),
					'context'  => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Display Title
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[display-site-title]',
					'type'      => 'control',
					'control'   => 'checkbox',
					'default'   => astra_get_option( 'display-site-title' ),
					'section'   => 'title_tagline',
					'title'     => __( 'Display Site Title', 'astra' ),
					'priority'  => 7,
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.site-branding',
						'container_inclusive' => true,
						'render_callback'     => 'Astra_Builder_Header::site_identity',
					),
					'context'   => Astra_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Design Typography Heading.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-logo-typography-heading]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'title_tagline',
					'title'    => __( 'Typography', 'astra' ),
					'priority' => 11,
					'settings' => array(),
					'context'  => array(
						'relation' => 'AND',
						Astra_Builder_Helper::$design_tab_config,
						array(
							'relation' => 'OR',
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[display-site-title]',
								'operator' => '==',
								'value'    => true,
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[display-site-tagline]',
								'operator' => '==',
								'value'    => true,
							),
						),

					),
				),

				// Option: Site Title Color.
				array(
					'name'      => 'header-color-site-title',
					'parent'    => ASTRA_THEME_SETTINGS . '[site-identity-title-color-group]',
					'section'   => 'title_tagline',
					'type'      => 'sub-control',
					'control'   => 'ast-color',
					'default'   => astra_get_option( 'header-color-site-title' ),
					'transport' => 'postMessage',
					'title'     => __( 'Text Color', 'astra' ),
					'tab'       => __( 'Normal', 'astra' ),
					'context'   => Astra_Builder_Helper::$design_tab,
				),

				// Option: Site Title Hover Color.
				array(
					'name'      => 'header-color-h-site-title',
					'parent'    => ASTRA_THEME_SETTINGS . '[site-identity-title-color-group]',
					'section'   => 'title_tagline',
					'type'      => 'sub-control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'header-color-h-site-title' ),
					'title'     => __( 'Hover Color', 'astra' ),
					'tab'       => __( 'Hover', 'astra' ),
					'context'   => Astra_Builder_Helper::$design_tab,
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

			$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

			$wp_customize->remove_control( 'astra-settings[divider-section-site-identity-logo]' );

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new Astra_Customizer_Site_Identity_Configs();
}
