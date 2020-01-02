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
 * @since 1.0.0
 */
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Customizer_Config_Base {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'astra_customizer_configurations', array( $this, 'register_configuration' ), 30, 2 );
		}

		/**
		 * Base Method for Registering Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			return $configurations;
		}

		/**
		 * Section Description
		 *
		 * @since 1.4.3
		 *
		 * @param  array $args Description arguments.
		 * @return mixed       Markup of the section description.
		 */
		public function section_get_description( $args ) {

			// Return if white labeled.
			if ( class_exists( 'Astra_Ext_White_Label_Markup' ) ) {
				if ( ! empty( Astra_Ext_White_Label_Markup::$branding['astra']['name'] ) ) {
					return '';
				}
			}

			// Description.
			$content  = '<div class="astra-section-description">';
			$content .= wp_kses_post( astra_get_prop( $args, 'description' ) );

			// Links.
			if ( astra_get_prop( $args, 'links' ) ) {
				$content .= '<ul>';
				foreach ( $args['links'] as $index => $link ) {

					if ( astra_get_prop( $link, 'attrs' ) ) {

						$content .= '<li>';

						// Attribute mapping.
						$attributes = ' target="_blank" ';
						foreach ( astra_get_prop( $link, 'attrs' ) as $attr => $attr_value ) {
							$attributes .= ' ' . $attr . '="' . esc_attr( $attr_value ) . '" ';
						}
						$content .= '<a ' . $attributes . '>' . esc_html( astra_get_prop( $link, 'text' ) ) . '</a></li>';

						$content .= '</li>';
					}
				}
				$content .= '</ul>';
			}

			$content .= '</div><!-- .astra-section-description -->';

			return $content;
		}

	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Config_Base;
