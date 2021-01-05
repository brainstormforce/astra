<?php
/**
 * Astra Theme Customizer Configuration Base.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Button_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class Astra_Customizer_Button_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Button Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[button-color-styling-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-buttons',
					'title'    => __( 'Colors and Border', 'astra' ),
					'priority' => 1,
					'settings' => array(),
				),
				/**
				 * Group: Theme Button Colors Group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[theme-button-color-group]',
					'default'   => astra_get_option( 'theme-button-color-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Color', 'astra' ),
					'section'   => 'section-buttons',
					'transport' => 'postMessage',
					'priority'  => 18,
				),

				/**
				 * Group: Theme Button Border Group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[theme-button-border-group]',
					'default'   => astra_get_option( 'theme-button-border-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Border', 'astra' ),
					'section'   => 'section-buttons',
					'transport' => 'postMessage',
					'priority'  => 19,
				),

				/**
				 * Option: Button Color
				 */
				array(
					'name'    => 'button-color',
					'default' => '',
					'type'    => 'sub-control',
					'parent'  => ASTRA_THEME_SETTINGS . '[theme-button-color-group]',
					'section' => 'section-buttons',
					'tab'     => __( 'Normal', 'astra' ),
					'control' => 'ast-color',
					'title'   => __( 'Text Color', 'astra' ),
				),

				/**
				 * Option: Button Hover Color
				 */
				array(
					'name'     => 'button-h-color',
					'default'  => '',
					'type'     => 'sub-control',
					'parent'   => ASTRA_THEME_SETTINGS . '[theme-button-color-group]',
					'section'  => 'section-buttons',
					'tab'      => __( 'Hover', 'astra' ),
					'control'  => 'ast-color',
					'title'    => __( 'Text Color', 'astra' ),
					'priority' => 39,
				),

				/**
				 * Option: Button Background Color
				 */
				array(
					'name'    => 'button-bg-color',
					'default' => '',
					'type'    => 'sub-control',
					'parent'  => ASTRA_THEME_SETTINGS . '[theme-button-color-group]',
					'section' => 'section-buttons',
					'tab'     => __( 'Normal', 'astra' ),
					'control' => 'ast-color',
					'title'   => __( 'Background Color', 'astra' ),
				),

				/**
				 * Option: Button Background Hover Color
				 */
				array(
					'name'     => 'button-bg-h-color',
					'default'  => '',
					'type'     => 'sub-control',
					'parent'   => ASTRA_THEME_SETTINGS . '[theme-button-color-group]',
					'section'  => 'section-buttons',
					'tab'      => __( 'Hover', 'astra' ),
					'control'  => 'ast-color',
					'title'    => __( 'Background Color', 'astra' ),
					'priority' => 40,
				),

				/**
				 * Option: Global Button Border Size
				 */
				array(
					'type'           => 'sub-control',
					'parent'         => ASTRA_THEME_SETTINGS . '[theme-button-border-group]',
					'section'        => 'section-buttons',
					'control'        => 'ast-border',
					'name'           => 'theme-button-border-group-border-size',
					'transport'      => 'postMessage',
					'linked_choices' => true,
					'priority'       => 10,
					'default'        => astra_get_option( 'theme-button-border-group-border-size' ),
					'title'          => __( 'Width', 'astra' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
				),

				/**
				 * Option: Global Button Border Color
				 */
				array(
					'name'      => 'theme-button-border-group-border-color',
					'default'   => astra_get_option( 'theme-button-border-group-border-color' ),
					'transport' => 'postMessage',
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[theme-button-border-group]',
					'section'   => 'section-buttons',
					'control'   => 'ast-color',
					'priority'  => 12,
					'title'     => __( 'Color', 'astra' ),
				),

				/**
				 * Option: Global Button Border Hover Color
				 */
				array(
					'name'      => 'theme-button-border-group-border-h-color',
					'default'   => astra_get_option( 'theme-button-border-group-border-h-color' ),
					'transport' => 'postMessage',
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[theme-button-border-group]',
					'section'   => 'section-buttons',
					'control'   => 'ast-color',
					'priority'  => 14,
					'title'     => __( 'Hover Color', 'astra' ),
				),

				/**
				 * Option: Global Button Radius
				 */
				array(
					'name'        => 'button-radius',
					'default'     => astra_get_option( 'button-radius' ),
					'type'        => 'sub-control',
					'parent'      => ASTRA_THEME_SETTINGS . '[theme-button-border-group]',
					'section'     => 'section-buttons',
					'control'     => 'ast-slider',
					'priority'    => 15,
					'title'       => __( 'Border Radius', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 200,
					),
				),

				/**
				 * Option: Button Padding Section
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[button-padding-styling-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-buttons',
					'title'    => __( 'Spacing', 'astra' ),
					'priority' => 30,
					'settings' => array(),
				),

				/**
				 * Option: Theme Button Padding
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[theme-button-padding]',
					'default'        => astra_get_option( 'theme-button-padding' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-buttons',
					'title'          => __( 'Padding', 'astra' ),
					'linked_choices' => true,
					'transport'      => 'postMessage',
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
					'priority'       => 35,
				),

			);

			if ( ! Astra_Builder_Helper::$is_header_footer_builder_active ) {

				$_trans_config = array(
					/**
					 * Option: Transparent Header Button Colors Divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[transparent-header-button-color-divider]',
						'type'     => 'control',
						'control'  => 'ast-heading',
						'section'  => 'section-transparent-header',
						'title'    => __( 'Header Button', 'astra' ),
						'settings' => array(),
						'priority' => 40,
						'context'  => array(
							Astra_Builder_Helper::$general_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
					),
					/**
					 * Group: Transparent Header Button Colors Group
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'default'   => astra_get_option( 'transparent-header-button-color-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Colors', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 40,
						'context'   => array(
							Astra_Builder_Helper::$general_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
					),
					/**
					 * Group: Transparent Header Button Border Group
					 */
					array(
						'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'default'   => astra_get_option( 'transparent-header-button-border-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Border', 'astra' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 40,
						'context'   => array(
							Astra_Builder_Helper::$general_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
					),

					/**
					 * Option: Button Text Color
					 */
					array(
						'name'      => 'header-main-rt-trans-section-button-text-color',
						'transport' => 'postMessage',
						'default'   => astra_get_option( 'header-main-rt-trans-section-button-text-color' ),
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Normal', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 10,
						'title'     => __( 'Text Color', 'astra' ),
					),

					/**
					 * Option: Button Text Hover Color
					 */
					array(
						'name'      => 'header-main-rt-trans-section-button-text-h-color',
						'default'   => astra_get_option( 'header-main-rt-trans-section-button-text-h-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Hover', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 10,
						'title'     => __( 'Text Color', 'astra' ),
					),

					/**
					 * Option: Button Background Color
					 */
					array(
						'name'      => 'header-main-rt-trans-section-button-back-color',
						'default'   => astra_get_option( 'header-main-rt-trans-section-button-back-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Normal', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 10,
						'title'     => __( 'Background Color', 'astra' ),
					),

					/**
					 * Option: Button Button Hover Color
					 */
					array(
						'name'      => 'header-main-rt-trans-section-button-back-h-color',
						'default'   => astra_get_option( 'header-main-rt-trans-section-button-back-h-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'   => 'section-transparent-header',
						'tab'       => __( 'Hover', 'astra' ),
						'control'   => 'ast-color',
						'priority'  => 10,
						'title'     => __( 'Background Color', 'astra' ),
					),

					// Option: Custom Menu Button Border.
					array(
						'type'           => 'control',
						'control'        => 'ast-responsive-spacing',
						'name'           => ASTRA_THEME_SETTINGS . '[header-main-rt-trans-section-button-padding]',
						'section'        => 'section-transparent-header',
						'transport'      => 'postMessage',
						'linked_choices' => true,
						'priority'       => 40,
						'context'        => array(
							Astra_Builder_Helper::$general_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
						'default'        => astra_get_option( 'header-main-rt-trans-section-button-padding' ),
						'title'          => __( 'Padding', 'astra' ),
						'choices'        => array(
							'top'    => __( 'Top', 'astra' ),
							'right'  => __( 'Right', 'astra' ),
							'bottom' => __( 'Bottom', 'astra' ),
							'left'   => __( 'Left', 'astra' ),
						),
					),

					/**
					 * Option: Button Border Size
					 */
					array(
						'type'           => 'sub-control',
						'parent'         => ASTRA_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'section'        => 'section-transparent-header',
						'control'        => 'ast-border',
						'name'           => 'header-main-rt-trans-section-button-border-size',
						'transport'      => 'postMessage',
						'linked_choices' => true,
						'priority'       => 10,
						'default'        => astra_get_option( 'header-main-rt-trans-section-button-border-size' ),
						'title'          => __( 'Width', 'astra' ),
						'choices'        => array(
							'top'    => __( 'Top', 'astra' ),
							'right'  => __( 'Right', 'astra' ),
							'bottom' => __( 'Bottom', 'astra' ),
							'left'   => __( 'Left', 'astra' ),
						),
					),

					/**
					 * Option: Button Border Color
					 */
					array(
						'name'      => 'header-main-rt-trans-section-button-border-color',
						'default'   => astra_get_option( 'header-main-rt-trans-section-button-border-color' ),
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'control'   => 'ast-color',
						'priority'  => 12,
						'title'     => __( 'Color', 'astra' ),
					),

					/**
					 * Option: Button Border Hover Color
					 */
					array(
						'name'      => 'header-main-rt-trans-section-button-border-h-color',
						'default'   => astra_get_option( 'header-main-rt-trans-section-button-border-h-color' ),
						'transport' => 'postMessage',
						'type'      => 'sub-control',
						'parent'    => ASTRA_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'control'   => 'ast-color',
						'priority'  => 14,
						'title'     => __( 'Hover Color', 'astra' ),
					),

					/**
					 * Option: Button Border Radius
					 */
					array(
						'name'        => 'header-main-rt-trans-section-button-border-radius',
						'default'     => astra_get_option( 'header-main-rt-trans-section-button-border-radius' ),
						'type'        => 'sub-control',
						'parent'      => ASTRA_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'section'     => 'section-transparent-header',
						'control'     => 'ast-slider',
						'transport'   => 'postMessage',
						'priority'    => 16,
						'title'       => __( 'Border Radius', 'astra' ),
						'input_attrs' => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 100,
						),
					),
				);
				$_configs = array_merge( $_configs, $_trans_config );

			}

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Button_Configs();
