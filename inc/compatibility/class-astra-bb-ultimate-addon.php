<?php
/**
 * Filters to override defaults in UABB
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

// If plugin - 'BB Ultimate Addon' not exist then return.
if ( ! class_exists( 'BB_Ultimate_Addon' ) ) {
	return;
}

/**
 * Astra BB Ultimate Addon Compatibility
 */
if ( ! class_exists( 'Astra_BB_Ultimate_Addon' ) ) :

	/**
	 * Astra BB Ultimate Addon Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_BB_Ultimate_Addon {

		/**
		 * Member Variable
		 *
		 * @var object instance
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
		public function __construct() {

			add_filter( 'uabb_global_support', array( $this, 'remove_uabb_global_setting' ) );
			add_filter( 'uabb_theme_theme_color', array( $this, 'theme_color' ) );
			add_filter( 'uabb_theme_text_color', array( $this, 'text_color' ) );
			add_filter( 'uabb_theme_link_color', array( $this, 'link_color' ) );
			add_filter( 'uabb_theme_link_hover_color', array( $this, 'link_hover_color' ) );
			add_filter( 'uabb_theme_button_font_family', array( $this, 'button_font_family' ) );
			add_filter( 'uabb_theme_button_font_size', array( $this, 'button_font_size' ) );
			add_filter( 'uabb_theme_button_line_height', array( $this, 'button_line_height' ) );
			add_filter( 'uabb_theme_button_letter_spacing', array( $this, 'button_letter_spacing' ) );
			add_filter( 'uabb_theme_button_text_transform', array( $this, 'button_text_transform' ) );
			add_filter( 'uabb_theme_button_text_color', array( $this, 'button_text_color' ) );
			add_filter( 'uabb_theme_button_text_hover_color', array( $this, 'button_text_hover_color' ) );
			add_filter( 'uabb_theme_button_bg_color', array( $this, 'button_bg_color' ) );
			add_filter( 'uabb_theme_button_bg_hover_color', array( $this, 'button_bg_hover_color' ) );
			add_filter( 'uabb_theme_button_border_radius', array( $this, 'button_border_radius' ) );
			add_filter( 'uabb_theme_button_padding', array( $this, 'button_padding' ) );
			add_filter( 'uabb_theme_button_vertical_padding', array( $this, 'button_vertical_padding' ) );
			add_filter( 'uabb_theme_button_horizontal_padding', array( $this, 'button_horizontal_padding' ) );
		}

		/**
		 * Remove UABB Global Setting Option
		 */
		function remove_uabb_global_setting() {
			return false;
		}

		/**
		 * Theme Color
		 */
		function theme_color() {
			return astra_get_option( 'theme-color' );
		}


		/**
		 * Text Color
		 */
		function text_color() {
			return astra_get_option( 'text-color' );
		}


		/**
		 * Link Color
		 */
		function link_color() {
			return astra_get_option( 'link-color' );
		}


		/**
		 * Link Hover Color
		 */
		function link_hover_color() {
			return astra_get_option( 'link-h-color' );
		}

		/**
		 * Button Font Family
		 */
		function button_font_family() {
			return array(
				'family' => astra_get_option( 'body-font-family' ),
				'weight' => astra_get_option( 'body-font-weight' ),
			);
		}

		/**
		 * Button Font Size
		 */
		function button_font_size() {
			return '';
		}

		/**
		 * Button Line Height
		 */
		function button_line_height() {
			return '';
		}

		/**
		 * Button Letter Spacing
		 */
		function button_letter_spacing() {
			return '';
		}

		/**
		 * Button Text Transform
		 */
		function button_text_transform() {
			return '';
		}

		/**
		 * Button Text Color
		 */
		function button_text_color() {
			$theme_color = astra_get_option( 'theme-color' );
			$link_color  = astra_get_option( 'link-color', $theme_color );
			$color       = astra_get_option( 'button-color' );
			if ( empty( $color ) ) {
				$color = astra_get_foreground_color( $link_color );
			}

			return $color;
		}

		/**
		 * Button Text Hover Color
		 */
		function button_text_hover_color() {
			$link_hover_color = astra_get_option( 'link-h-color' );
			$color            = astra_get_option( 'button-h-color' );
			if ( empty( $color ) ) {
				$color = astra_get_foreground_color( $link_hover_color );
			}

			return $color;
		}

		/**
		 * Button Background Color
		 */
		function button_bg_color() {
			return astra_get_option( 'button-bg-color' );
		}

		/**
		 * Button Background Color
		 */
		function button_bg_hover_color() {
			return astra_get_option( 'button-bg-h-color' );
		}

		/**
		 * Button Border Radius
		 */
		function button_border_radius() {
			return astra_get_option( 'button-radius' );
		}


		/**
		 * Button Padding
		 */
		function button_padding() {

			$padding   = '';
			$v_padding = astra_get_option( 'button-v-padding' );
			$h_padding = astra_get_option( 'button-h-padding' );

			if ( '' != $v_padding && '' != $h_padding ) {
				$padding = $v_padding . 'px ' . $h_padding . 'px';
			}

			return $padding;
		}

		/**
		 * Button Padding
		 */
		function button_vertical_padding() {

			$padding   = '';
			$v_padding = astra_get_option( 'button-v-padding' );

			if ( '' != $v_padding ) {
				$padding = $v_padding;
			}

			return $padding;
		}

		/**
		 * Button Padding
		 */
		function button_horizontal_padding() {

			$padding   = '';
			$h_padding = astra_get_option( 'button-h-padding' );

			if ( '' != $h_padding ) {
				$padding = $h_padding;
			}

			return $padding;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_BB_Ultimate_Addon::get_instance();
