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

if ( ! class_exists( 'Astra_Archive_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Archive_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Archive Typography Customizer Configurations.
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
					'name'      => ASTRA_THEME_SETTINGS . '[divider-section-archive-typo-archive-title]',
					'type'      => 'control',
					'control'   => 'ast-divider',
					'section'   => 'section-archive-typo',
					'priority'  => 0,
					'title'     => __( 'Blog Post Title', 'astra' ),
					'settings'  => array(),
					'separator' => false,
				),

				/**
				 * Option: Blog - Post Title Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-page-title]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-archive-typo',
					'transport'   => 'postMessage',
					'priority'    => 4,
					'default'     => astra_get_option( 'font-size-page-title' ),
					'title'       => __( 'Font Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-archive-summary-box-typo]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-archive-typo',
					'priority' => 6,
					'title'    => __( 'Archive Summary Box Title', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Archive Summary Box Title Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-archive-summary-title]',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'section'     => 'section-archive-typo',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'font-size-archive-summary-title' ),
					'priority'    => 11,
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
						'name'     => ASTRA_THEME_SETTINGS . '[ast-blog-typography-more-feature-divider]',
						'type'     => 'control',
						'control'  => 'ast-divider',
						'section'  => 'section-archive-typo',
						'priority' => 999,
						'settings' => array(),
					),

					/**
					 * Option: Learn More about Contant Typography
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-blog-typography-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-archive-typo',
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

new Astra_Archive_Typo_Configs;


