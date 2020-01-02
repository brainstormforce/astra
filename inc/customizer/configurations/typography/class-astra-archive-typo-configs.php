<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
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
				 * Option: Blog Typography
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-typography-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-blog',
					'title'    => __( 'Typography', 'astra' ),
					'priority' => 135,
					'settings' => array(),
				),

				/**
				 * Option: Blog / Archive Typography
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[blog-content-archive-summary-typo]',
					'default'   => astra_get_option( 'blog-content-archive-summary-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Archive Title', 'astra' ),
					'section'   => 'section-blog',
					'transport' => 'postMessage',
					'priority'  => 140,
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[blog-content-blog-post-title-typo]',
					'default'   => astra_get_option( 'blog-content-blog-post-title-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Post Title', 'astra' ),
					'section'   => 'section-blog',
					'transport' => 'postMessage',
					'priority'  => 140,
				),

				/**
				 * Option: Blog - Post Title Font Size
				 */
				array(
					'name'        => 'font-size-page-title',
					'parent'      => ASTRA_THEME_SETTINGS . '[blog-content-blog-post-title-typo]',
					'section'     => 'section-blog',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive',
					'transport'   => 'postMessage',
					'priority'    => 2,
					'default'     => astra_get_option( 'font-size-page-title' ),
					'title'       => __( 'Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Archive Summary Box Title Font Size
				 */
				array(
					'name'        => 'font-size-archive-summary-title',
					'parent'      => ASTRA_THEME_SETTINGS . '[blog-content-archive-summary-typo]',
					'section'     => 'section-blog',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'font-size-archive-summary-title' ),
					'priority'    => 8,
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
						'section'  => 'section-blog',
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
						'section'  => 'section-blog',
						'priority' => 999,
						'title'    => '',
						'help'     => '<p>' . __( 'More Options Available in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
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


