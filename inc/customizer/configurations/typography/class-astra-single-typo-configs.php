<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Single_Typo_Configs' ) ) {

	/**
	 * Customizer Single Typography Configurations.
	 *
	 * @since 1.4.3
	 */
	class Astra_Single_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Single Typography configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[divider-section-header-single-title]',
					'type'      => 'control',
					'control'   => 'ast-divider',
					'section'   => 'section-single-typo',
					'priority'  => 5,
					'title'     => __( 'Single Post / Page Title', 'astra' ),
					'settings'  => array(),
					'separator' => false,
				),

				/**
				 * Option: Single Post / Page Title Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-entry-title]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-single-typo',
					'default'     => astra_get_option( 'font-size-entry-title' ),
					'transport'   => 'postMessage',
					'priority'    => 10,
					'title'       => __( 'Font Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$_configs = array(

					/**
					 * Option: Divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-sngle-blog-typography-more-feature-divider]',
						'type'     => 'control',
						'control'  => 'ast-divider',
						'section'  => 'section-single-typo',
						'priority' => 999,
						'settings' => array(),
					),

					/**
					 * Option: Learn More about Typography
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-sngle-blog-typography-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-single-typo',
						'priority' => 999,
						'title'    => '',
						'help'     => '<p>' . __( 'More Options Available for Typography in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
						'settings' => array(),
					),

				);

				$configurations = array_merge( $configurations, $_configs );
			}

			return $configurations;
		}
	}
}

new Astra_Single_Typo_Configs;


