<?php
/**
 * Helper class for font settings.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Font info class for System and Google fonts.
 */
if ( ! class_exists( 'Astra_Fonts_Data' ) ) :

	/**
	 * Fonts Data
	 */
	final class Astra_Fonts_Data {

		/**
		 * Localize Fonts
		 */
		static public function js() {

			$system = json_encode( Astra_Font_Families::get_system_fonts() );
			$google = json_encode( Astra_Font_Families::get_google_fonts() );
			$custom = json_encode( Astra_Font_Families::get_custom_fonts() );
			if ( ! empty( $custom ) ) {
				return 'var AstFontFamilies = { system: ' . $system . ', custom: ' . $custom . ', google: ' . $google . ' };';
			} else {
				return 'var AstFontFamilies = { system: ' . $system . ', google: ' . $google . ' };';
			}
		}
	}

endif;

