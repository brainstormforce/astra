<?php
/**
 * Helper class for font settings.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.19
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Font info class for System and Google fonts.
 */
if ( ! class_exists( 'Astra_Font_Families' ) ) :

	/**
	 * Font info class for System and Google fonts.
	 */
	final class Astra_Font_Families {

		/**
		 * System Fonts
		 *
		 * @since 1.0.19
		 * @var array
		 */
		public static $system_fonts = array();

		/**
		 * Google Fonts
		 *
		 * @since 1.0.19
		 * @var array
		 */
		public static $google_fonts = array();

		/**
		 * Get System Fonts
		 *
		 * @since 1.0.19
		 *
		 * @return Array All the system fonts in Astra
		 */
		public static function get_system_fonts() {
			if ( empty( self::$system_fonts ) ) {
				self::$system_fonts = array(
					'Helvetica' => array(
						'fallback' => 'Verdana, Arial, sans-serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Verdana'   => array(
						'fallback' => 'Helvetica, Arial, sans-serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Arial'     => array(
						'fallback' => 'Helvetica, Verdana, sans-serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Times'     => array(
						'fallback' => 'Georgia, serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Georgia'   => array(
						'fallback' => 'Times, serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Courier'   => array(
						'fallback' => 'monospace',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
				);
			}

			return apply_filters( 'astra_system_fonts', self::$system_fonts );
		}

		/**
		 * Custom Fonts
		 *
		 * @since 1.0.19
		 *
		 * @return Array All the custom fonts in Astra
		 */
		public static function get_custom_fonts() {
			$custom_fonts = array();

			return apply_filters( 'astra_custom_fonts', $custom_fonts );
		}

		/**
		 * Google Fonts used in astra.
		 * Array is generated from the google-fonts.json file.
		 *
		 * @since  1.0.19
		 *
		 * @return Array Array of Google Fonts.
		 */
		public static function get_google_fonts() {

			if ( empty( self::$google_fonts ) ) {

				/**
				 * Deprecating the Filter to change the Google Fonts JSON file path.
				 *
				 * @since x.x.x
				 * @param string $json_file File where google fonts json format added.
				 * @return array
				 */
				$google_fonts_json_file = astra_apply_filters_deprecated( 'astra_google_fonts_json_file', array( ASTRA_THEME_DIR . 'assets/fonts/google-fonts.json', '' ), '2.5.0', false, '`astra_google_fonts_php_file`. Please check this doc for more information - https://wpastra.com/docs/deprecated-google-fonts-filter' );
				$google_fonts_file      = apply_filters( 'astra_google_fonts_php_file', ASTRA_THEME_DIR . 'inc/google-fonts.php' );

				if ( ! file_exists( $google_fonts_file ) ) {
					return array();
				}

				if ( ( ASTRA_THEME_DIR . 'assets/fonts/google-fonts.json' ) === $google_fonts_json_file ) {
					$google_fonts_arr = include $google_fonts_file;
				} else {
					if ( ! file_exists( $google_fonts_json_file ) ) {
						return array();
					}
					$file_contents    = astra_filesystem()->get_contents( $google_fonts_json_file );
					$google_fonts_arr = json_decode( $file_contents, 1 );
				}

				foreach ( $google_fonts_arr as $key => $font ) {
					$name = key( $font );
					foreach ( $font[ $name ] as $font_key => $single_font ) {

						if ( 'variants' === $font_key ) {

							foreach ( $single_font as $variant_key => $variant ) {

								if ( 'regular' == $variant ) {
									$font[ $name ][ $font_key ][ $variant_key ] = '400';
								}
							}
						}

						self::$google_fonts[ $name ] = array_values( $font[ $name ] );
					}
				}
			}

			return apply_filters( 'astra_google_fonts', self::$google_fonts );
		}

	}

endif;
