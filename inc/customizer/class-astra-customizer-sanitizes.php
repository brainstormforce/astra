<?php
/**
 * Astra Theme Customizer Sanitize.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
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
if ( ! class_exists( 'Astra_Customizer_Sanitizes' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Customizer_Sanitizes {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() { }

		/**
		 * Sanitize Integer
		 *
		 * @param  number $input Customizer setting input number.
		 * @return number        Absolute number.
		 */
		static public function sanitize_integer( $input ) {
			return absint( $input );
		}

		/**
		 * Sanitize Integer
		 *
		 * @param  number $val      Customizer setting input number.
		 * @param  object $setting  Setting object.
		 * @return number           Return number.
		 */
		static public function sanitize_number( $val, $setting ) {

			$input_attrs = $setting->manager->get_control( $setting->id )->input_attrs;

			if ( isset( $input_attrs ) ) {

				$input_attrs['min']  = isset( $input_attrs['min'] ) ? $input_attrs['min'] : 0;
				$input_attrs['step'] = isset( $input_attrs['step'] ) ? $input_attrs['step'] : 1;

				if ( isset( $input_attrs['max'] ) && $val > $input_attrs['max'] ) {
					$val = $input_attrs['max'];
				} elseif ( $val < $input_attrs['min'] ) {
					$val = $input_attrs['min'];
				}

						$dv = $val / $input_attrs['step'];

						$dv = round( $dv );

						$val = $dv * $input_attrs['step'];

					$val = number_format( (float) $val, 2, '.', '' );
				if ( $val == (int) $val ) {
					$val = (int) $val;
				}
			}

			return is_numeric( $val ) ? $val : 0;
		}

		/**
		 * Sanitize Integer
		 *
		 * @param  number $val Customizer setting input number.
		 * @return number        Return number.
		 */
		static public function sanitize_number_n_blank( $val ) {
			return is_numeric( $val ) ? $val : '';
		}

		/**
		 * Sanitize Spacing
		 *
		 * @param  number $val Customizer setting input number.
		 * @return number        Return number.
		 * @since  1.0.6
		 */
		static public function sanitize_spacing( $val ) {

			foreach ( $val as $key => $value ) {
				$val[ $key ] = is_numeric( $val[ $key ] ) ? $val[ $key ] : '';
			}

			return $val;
		}

		/**
		 * Sanitize Responsive Typography
		 *
		 * @param  number $val Customizer setting input number.
		 * @return number        Return number.
		 */
		static public function sanitize_responsive_typo( $val ) {

			$responsive = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => '',
				'tablet-unit'  => '',
				'mobile-unit'  => '',
			);
			if ( is_array( $val ) ) {
				$responsive['desktop']      = is_numeric( $val['desktop'] ) ? $val['desktop'] : '';
				$responsive['tablet']       = is_numeric( $val['tablet'] ) ? $val['tablet'] : '';
				$responsive['mobile']       = is_numeric( $val['mobile'] ) ? $val['mobile'] : '';
				$responsive['desktop-unit'] = in_array( $val['desktop-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['desktop-unit'] : 'px';
				$responsive['tablet-unit']  = in_array( $val['tablet-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['tablet-unit'] : 'px';
				$responsive['mobile-unit']  = in_array( $val['mobile-unit'], array( '', 'px', 'em', 'rem', '%' ) ) ? $val['mobile-unit'] : 'px';
			} else {
				$responsive['desktop'] = is_numeric( $val ) ? $val : '';
			}
			return $responsive;
		}

		/**
		 * Validate Email
		 *
		 * @param  object $validity setting input validity.
		 * @param  string $value    setting input value.
		 * @return object           Return the validity object.
		 */
		static public function validate_email( $validity, $value ) {
			if ( ! is_email( $value ) ) {
				$validity->add( 'required', __( 'Enter valid email address!', 'astra' ) );
			}
			return $validity;
		}

		/**
		 * Validate Sidebar Content Width
		 *
		 * @param  number $value Sidebar content width.
		 * @return number        Sidebar content width value.
		 */
		static public function validate_sidebar_content_width( $value ) {
			$value = intval( $value );
			if ( $value > 50 ) {
				$value = 50;
			} elseif ( $value < 15 ) {
				$value = 15;
			}
			return $value;
		}

		/**
		 * Validate Site width
		 *
		 * @param  number $value Site width.
		 * @return number        Site width value.
		 */
		static public function validate_site_width( $value ) {
			$value = intval( $value );
			if ( 1920 < $value ) {
				$value = 1920;
			} elseif ( 768 > $value ) {
				$value = 768;
			}
			return $value;
		}

		/**
		 * Validate Site padding
		 *
		 * @param  number $value Site padding.
		 * @return number        Site padding value.
		 */
		static public function validate_site_padding( $value ) {
			$value = intval( $value );
			if ( 200 < $value ) {
				$value = 200;
			} elseif ( 1 > $value ) {
				$value = 1;
			}
			return $value;
		}

		/**
		 * Validate Site margin
		 *
		 * @param  number $value Site margin.
		 * @return number        Site margin value.
		 */
		static public function validate_site_margin( $value ) {
			$value = intval( $value );
			if ( 600 < $value ) {
				$value = 600;
			} elseif ( 0 > $value ) {
				$value = 0;
			}
			return $value;
		}

		/**
		 * Sanitize checkbox
		 *
		 * @param  number $input setting input.
		 * @return number        setting input value.
		 */
		static public function sanitize_checkbox( $input ) {
			if ( $input ) {
				$output = '1';
			} else {
				$output = false;
			}
			return $output;
		}

		/**
		 * Sanitize HEX color
		 *
		 * @param  string $color setting input.
		 * @return string        setting input value.
		 */
		static public function sanitize_hex_color( $color ) {

			if ( '' === $color ) {
				return '';
			}

			// 3 or 6 hex digits, or the empty string.
			if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
				return $color;
			}

			return '';
		}

		/**
		 * Sanitize Alpha color
		 *
		 * @param  string $color setting input.
		 * @return string        setting input value.
		 */
		static public function sanitize_alpha_color( $color ) {

			if ( '' === $color ) {
				return '';
			}

			if ( false === strpos( $color, 'rgba' ) ) {
				/* Hex sanitize */
				return self::sanitize_hex_color( $color );
			}

			/* rgba sanitize */
			$color = str_replace( ' ', '', $color );
			sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
			return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
		}

		/**
		 * Sanitize html
		 *
		 * @param  string $input    setting input.
		 * @return mixed            setting input value.
		 */
		static public function sanitize_html( $input ) {
			return wp_kses_post( $input );
		}

		/**
		 * Sanitize Select choices
		 *
		 * @param  string $input    setting input.
		 * @param  object $setting  setting object.
		 * @return mixed            setting input value.
		 */
		static public function sanitize_multi_choices( $input, $setting ) {

			// Get list of choices from the control
			// associated with the setting.
			$choices    = $setting->manager->get_control( $setting->id )->choices;
			$input_keys = $input;

			foreach ( $input_keys as $key => $value ) {
				if ( ! array_key_exists( $value, $choices ) ) {
					unset( $input[ $key ] );
				}
			}

			// If the input is a valid key, return it;
			// otherwise, return the default.
			return ( is_array( $input ) ? $input : $setting->default );
		}

		/**
		 * Sanitize Select choices
		 *
		 * @param  string $input    setting input.
		 * @param  object $setting  setting object.
		 * @return mixed            setting input value.
		 */
		static public function sanitize_choices( $input, $setting ) {

			// Ensure input is a slug.
			$input = sanitize_key( $input );

			// Get list of choices from the control
			// associated with the setting.
			$choices = $setting->manager->get_control( $setting->id )->choices;

			// If the input is a valid key, return it;
			// otherwise, return the default.
			return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
		}

		/**
		 * Sanitize Font weight
		 *
		 * @param  mixed $input setting input.
		 * @return mixed        setting input value.
		 */
		static public function sanitize_font_weight( $input ) {

			$valid = array(
				'normal',
				'bold',
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
			);

			if ( in_array( $input, $valid ) ) {
				return $input;
			} else {
				return 'normal';
			}
		}
	}
}// End if().

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Customizer_Sanitizes::get_instance();
