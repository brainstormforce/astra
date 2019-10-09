<?php
/**
 * Astra Theme Customizer Controls.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Loader
 *
 * @since 1.0.0
 */
class Astra_Customizer_WP_Compatibility {

	/**
	 * Constructor
	 */
	public function __construct() {

		/**
		 * Customizer
		 */
		global $wp_version;
		if ( is_customize_preview() && version_compare( $wp_version, '5.2.3', '>' ) ) {
			add_action( 'customize_controls_print_styles', array( $this, 'wp_customizer_compatibility_new_styles' ), 50 );
		} else {
			add_action( 'customize_controls_print_styles', array( $this, 'wp_customizer_compatibility_old_styles' ), 50 );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	function wp_customizer_compatibility_new_styles() { ?>
		<style>
			.wp-picker-container .wp-color-result-text {
				line-height: 26px !important;
			}
			.wp-picker-container span.color-alpha {
				height: 26px !important;
			}
			.ast-field-settings-modal .customize-control-ast-responsive-background .ast-responsive-bg-color-control.wp-color-picker,
			.ast-field-settings-modal .customize-control-ast-responsive-color .ast-responsive-color.wp-color-picker,
			.customize-control-ast-responsive-color .ast-responsive-color.wp-color-picker,
			.customize-control-ast-color .ast-color-picker-alpha.wp-color-picker {
				width: 77px;
			}
		</style>
		<?php
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	function wp_customizer_compatibility_old_styles() { ?>
		<style>
		</style>
		<?php
	}
}

new Astra_Customizer_WP_Compatibility();
