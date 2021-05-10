<?php
/**
 * Register customizer Aspra Pro Section.
 *
 * @package   Astra
 * @author    Astra
 * @copyright Copyright (c) 2020, Astra
 * @link      https://wpastra.com/
 * @since     Astra 1.0.10
 */

if ( ! class_exists( 'Astra_Pro_Upgrade_Link_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class Astra_Pro_Upgrade_Link_Configs extends Astra_Customizer_Config_Base {

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
					'name'             => 'astra-pro',
					'type'             => 'section',
					'ast_type'         => 'astra-pro',
					'title'            => esc_html__( 'More Options Available in Astra Pro!', 'astra' ),
					'pro_url'          => htmlspecialchars_decode( astra_get_pro_url( 'https://wpastra.com/pricing/', 'customizer', 'upgrade-link', 'upgrade-to-pro' ) ),
					'priority'         => 1,
					'section_callback' => 'Astra_Pro_Customizer',
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[astra-pro-section-notice]',
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-hidden',
					'section'   => 'astra-pro',
					'priority'  => 0,
				),

			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Pro_Upgrade_Link_Configs();
