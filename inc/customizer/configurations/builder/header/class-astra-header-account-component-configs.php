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

if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class Astra_Header_Account_Component_Configs extends Astra_Customizer_Config_Base {

	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_section = 'section-header-account';
		$defaults = Astra_Theme_Options::defaults();

		$account_choices = array(
			'default' => __( 'Default', 'astra' ),
		);

		$login_link_context = Astra_Builder_Helper::$general_tab;

		$logout_link_context = array(
			'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
			'operator' => '!=',
			'value'    => 'none',
		);

		if ( defined( 'ASTRA_EXT_VER' ) ) {

			$account_type_condition = array(
				'setting'  => ASTRA_THEME_SETTINGS . '[header-account-action-type]',
				'operator' => '==',
				'value'    => 'link',
			);

			if ( class_exists( 'LifterLMS' ) ) {
				$account_choices['lifterlms'] = __( 'LifterLMS', 'astra' );
			}
	
			if ( class_exists( 'WooCommerce' ) ) {
				$account_choices['woocommerce'] = __( 'WooCommerce', 'astra' );
			}

			if ( count( $account_choices ) > 1 ) {
				$account_type_condition = array(
					'setting'  => ASTRA_THEME_SETTINGS . '[header-account-type]',
					'operator' => '==',
					'value'    => 'default',
				);
			}

			$login_link_context = array(
				'relation' => 'AND',
				Astra_Builder_Helper::$general_tab_config,
				array(
					'setting'  => ASTRA_THEME_SETTINGS . '[header-account-action-type]',
					'operator' => '==',
					'value'    => 'link',
				),
				array(
					'relation' => 'OR',
					$account_type_condition,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[header-account-link-type]',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			);

			$logout_link_context = array(
				'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-action]',
				'operator' => '==',
				'value'    => 'link',
			);
		
		}

		$_configs = array(

			/*
			* Header Builder section
			*/
			array(
				'name'     => $_section,
				'type'     => 'section',
				'priority' => 80,
				'title'    => __( 'Account', 'astra' ),
				'panel'    => 'panel-header-builder-group',
			),

			/**
			 * Option: Header Builder Tabs
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-account-tabs]',
				'section'     => $_section,
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			),

			/**
			 * Option: Log In view
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[header-account-login-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'priority' => 2,
				'title'    => __( 'Logged In View', 'astra' ),
				'settings' => array(),
			),

			/**
			 * Option: Style
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
				'default'   => astra_get_option( 'header-account-login-style' ),
				'type'      => 'control',
				'control'   => 'select',
				'section'   => $_section,
				'priority'  => 3,
				'title'     => __( 'Profile Type', 'astra' ),
				'choices'   => array(
					'icon'   => __( 'Icon', 'astra' ),
					'avatar' => __( 'Avatar', 'astra' ),
					'text'   => __( 'Text', 'astra' ),
				),
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'        => '.ast-header-account',
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_account' ),
				),
			),
			
			/** 
			* Option: Logged Out Text   
			*/  
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-logged-in-text]',
				'default'   => astra_get_option( 'header-account-logged-in-text' ),
				'type'      => 'control',
				'control'   => 'text',
				'section'   => $_section,
				'title'     => __( 'Text', 'astra' ),
				'priority'  => 3, 
				'transport' => 'postMessage',
				'context'   => array(   
					array(  
						'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',   
					),
					Astra_Builder_Helper::$general_tab_config,  
				),
				'partial'   => array(   
					'selector'        => '.ast-header-account', 
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_account' ),  
				),  
			),

			/**
			* Option: Account Log In Link
			*/
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-login-link]',
				'default'   => astra_get_option( 'header-account-login-link' ),
				'type'      => 'control',
				'control'   => 'ast-link',
				'section'   => $_section,
				'title'     => __( 'Account URL', 'astra' ),
				'priority'  => 6,
				'transport' => 'postMessage',
				'context'   => $login_link_context,
				'partial'   => array(
					'selector'        => '.ast-header-account',
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_account' ),
				),
			),

			/**
			 * Option: Log Out view
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[header-account-logout-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Logged Out View', 'astra' ),
				'priority' => 200,
				'settings' => array(),
			),

			/**
			 * Option: Style
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
				'default'   => astra_get_option( 'header-account-logout-style' ),
				'type'      => 'control',
				'control'   => 'select',
				'section'   => $_section,
				'title'     => __( 'Profile Type', 'astra' ),
				'priority'  => 201,
				'choices'   => array(
					'none' => __( 'None', 'astra' ),
					'icon' => __( 'Icon', 'astra' ),
					'text' => __( 'Text', 'astra' ),
				),
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'        => '.ast-header-account',
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_account' ),
				),
			),

			// Option: Logged out options preview.
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-logout-preview]',
				'default'   => '',
				'type'      => 'control',
				'control'   => 'checkbox',
				'section'   => $_section,
				'title'     => __( 'Preview', 'astra' ),
				'priority'  => 202,
				'context'   => array(
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '!=',
						'value'    => 'none',
					),
					Astra_Builder_Helper::$general_tab_config,
				),
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'        => '.ast-header-account',
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_account' ),
				),
			),
			
			/** 
			* Option: Logged Out Text   
			*/  
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-logged-out-text]',
				'default'   => astra_get_option( 'header-account-logged-out-text' ),
				'type'      => 'control',
				'control'   => 'text',
				'section'   => $_section,
				'title'     => __( 'Text', 'astra' ),
				'priority'  => 203, 
				'transport' => 'postMessage',
				'context'   => array(   
					array(  
						'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'text',   
					),
					Astra_Builder_Helper::$general_tab_config,  
				),
				'partial'   => array(   
					'selector'        => '.ast-header-account', 
					'render_callback' => array( 'Astra_Builder_UI_Controller', 'render_account' ),  
				),  
			),

			/**
			* Option: Account Log Out Link
			*/
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-logout-link]',
				'default'   => astra_get_option( 'header-account-logout-link' ),
				'type'      => 'control',
				'control'   => 'ast-link',
				'section'   => $_section,
				'title'     => __( 'Login URL', 'astra' ),
				'priority'  => 205,
				'transport' => 'postMessage',
				'context'   => array(
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '!=',
						'value'    => 'none',
					),
					$logout_link_context,
					Astra_Builder_Helper::$general_tab_config,
				),
			),

			/**
			 * Option: Log Out view
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[header-account-image-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Avatar', 'astra' ),
				'priority' => 1,
				'settings' => array(),
				'context'  => array(
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'avatar',
					),
					Astra_Builder_Helper::$design_tab_config,
				),
			),

			/**
			 * Option: Image Width
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-account-image-width]',
				'section'     => $_section,
				'priority'    => 2,
				'transport'   => 'postMessage',
				'default'     => astra_get_option( 'header-account-image-width' ),
				'title'       => __( 'Image Width', 'astra' ),
				'type'        => 'control',
				'control'     => 'ast-responsive-slider',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'context'     => array(
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'avatar',
					),
					Astra_Builder_Helper::$design_tab_config,
				),
			),

			/**
			 * Option: Log Out view
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[header-account-icon-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Icon', 'astra' ),
				'priority' => 3,
				'settings' => array(),
				'context'  => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'relation' => 'OR',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
							'operator' => '==',
							'value'    => 'icon',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			),

			/**
			 * Option: account Size
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[header-account-icon-size]',
				'section'     => $_section,
				'priority'    => 4,
				'transport'   => 'postMessage',
				'default'     => $defaults['header-account-icon-size'],
				'title'       => __( 'Icon Size', 'astra' ),
				'type'        => 'control',
				'control'     => 'ast-responsive-slider',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 50,
				),
				'context'     => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'relation' => 'OR',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
							'operator' => '==',
							'value'    => 'icon',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			),

			/**
			 * Option: account Color.
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-icon-color]',
				'default'   => $defaults['header-account-icon-color'],
				'type'      => 'control',
				'section'   => $_section,
				'priority'  => 5,
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Icon Color', 'astra' ),
				'context'   => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'relation' => 'OR',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
							'operator' => '==',
							'value'    => 'icon',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			),

			/**
			 * Option: Profile text
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[header-account-type-text-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Profile Text', 'astra' ),
				'priority' => 10,
				'settings' => array(),
				'context'  => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'relation' => 'OR',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
							'operator' => '==',
							'value'    => 'text',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
							'operator' => '==',
							'value'    => 'text',
						),
					),
				),
			),

			/**
			* Option: account Color.
			*/
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[header-account-type-text-color]',
				'default'   => $defaults['header-account-type-text-color'],
				'type'      => 'control',
				'section'   => $_section,
				'priority'  => 16,
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Color', 'astra' ),
				'context'   => array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'relation' => 'OR',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
							'operator' => '==',
							'value'    => 'text',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
							'operator' => '==',
							'value'    => 'text',
						),
					),
				),
			),

			/**
			 * Option: Margin heading
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[header-account-margin-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Spacing', 'astra' ),
				'priority' => 500,
				'settings' => array(),
				'context'  => Astra_Builder_Helper::$design_tab,
			),

			/**
			 * Option: Margin Space
			 */
			array(
				'name'           => ASTRA_THEME_SETTINGS . '[header-account-margin]',
				'default'        => '',
				'type'           => 'control',
				'transport'      => 'postMessage',
				'control'        => 'ast-responsive-spacing',
				'section'        => $_section,
				'priority'       => 520,
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

		$_configs = array_merge(
			$_configs,
			Astra_Builder_Base_Configuration::prepare_typography_options(
				$_section,
				array(
					Astra_Builder_Helper::$design_tab_config,
					array(
						'relation' => 'OR',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
							'operator' => '==',
							'value'    => 'text',
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
							'operator' => '==',
							'value'    => 'text',
						),
					),
				) 
			) 
		);

		$_configs = array_merge( $_configs, Astra_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

		return array_merge( $configurations, $_configs );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new Astra_Header_Account_Component_Configs();


