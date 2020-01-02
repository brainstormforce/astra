<?php
/**
 * Filters to override defaults in UABB
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
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
			add_filter( 'uabb_theme_button_border_width', array( $this, 'button_border_width' ) );
			add_filter( 'uabb_theme_border_color', array( $this, 'button_border_color' ) );
			add_filter( 'uabb_theme_border_hover_color', array( $this, 'button_border_hover_color' ) );
			add_filter( 'uabb_theme_button_vertical_padding', array( $this, 'button_vertical_padding' ) );
			add_filter( 'uabb_theme_button_horizontal_padding', array( $this, 'button_horizontal_padding' ) );

			/**
			 * Default button type UABB compatibility.
			 */
			add_filter( 'uabb_theme_default_button_font_size', array( $this, 'default_type_button_font_size' ) );
			add_filter( 'uabb_theme_default_button_line_height', array( $this, 'default_type_button_line_height' ) );
			add_filter( 'uabb_theme_default_button_letter_spacing', array( $this, 'default_type_button_letter_spacing' ) );
			add_filter( 'uabb_theme_default_button_text_transform', array( $this, 'default_type_button_text_transform' ) );
			add_filter( 'uabb_theme_default_button_text_color', array( $this, 'default_type_button_text_color' ) );
			add_filter( 'uabb_theme_default_button_text_hover_color', array( $this, 'default_type_button_text_hover_color' ) );
			add_filter( 'uabb_theme_default_button_bg_color', array( $this, 'default_type_button_bg_color' ) );
			add_filter( 'uabb_theme_default_button_bg_hover_color', array( $this, 'default_type_button_bg_hover_color' ) );
			add_filter( 'uabb_theme_default_button_padding', array( $this, 'default_type_button_padding' ) );
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
			$font_family = str_replace( "'", '', astra_get_option( 'font-family-button' ) );
			$font_family = explode( ',', $font_family );
			return array(
				'family' => $font_family[0],
				'weight' => astra_get_option( 'font-weight-button' ),
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
		 * Default type : Button Font Size
		 *
		 * @since 2.2.0
		 */
		function default_type_button_font_size() {
			$font_size_arr       = array();
			$body_font_size      = astra_get_option( 'font-size-body' );
			$theme_btn_font_size = astra_get_option( 'font-size-button' );

			$font_size_arr['desktop'] = astra_responsive_font( $theme_btn_font_size, 'desktop' );
			$font_size_arr['tablet']  = astra_responsive_font( $theme_btn_font_size, 'tablet' );
			$font_size_arr['mobile']  = astra_responsive_font( $theme_btn_font_size, 'mobile' );

			if ( empty( $font_size_arr['desktop'] ) ) {
				$font_size_arr['desktop'] = astra_responsive_font( $body_font_size, 'desktop' );
			}
			if ( empty( $font_size_arr['tablet'] ) ) {
				$font_size_arr['tablet'] = astra_responsive_font( $body_font_size, 'tablet' );
			}
			if ( empty( $font_size_arr['mobile'] ) ) {
				$font_size_arr['mobile'] = astra_responsive_font( $body_font_size, 'mobile' );
			}

			return $font_size_arr;
		}

		/**
		 * Default type : Button Line Height
		 *
		 * @since 2.2.0
		 */
		function default_type_button_line_height() {
			$theme_btn_body_line_height = astra_get_option( 'body-line-height', 1.85714285714286 );
			$theme_btn_line_height      = astra_get_option( 'theme-btn-line-height', $theme_btn_body_line_height );
			return $theme_btn_line_height;
		}

		/**
		 * Button Letter Spacing
		 */
		function button_letter_spacing() {
			return '';
		}

		/**
		 * Default type : Button Letter Spacing
		 *
		 * @since 2.2.0
		 */
		function default_type_button_letter_spacing() {
			$theme_btn_letter_spacing = astra_get_option( 'theme-btn-letter-spacing' );
			return $theme_btn_letter_spacing;
		}

		/**
		 * Button Text Transform
		 */
		function button_text_transform() {
			return '';
		}

		/**
		 * Default type : Button Text Transform
		 *
		 * @since 2.2.0
		 */
		function default_type_button_text_transform() {
			$theme_btn_text_transform = astra_get_option( 'text-transform-button' );
			return $theme_btn_text_transform;
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
		 * Default type : Button Text Color
		 *
		 * @since 2.2.0
		 */
		function default_type_button_text_color() {
			$theme_color    = astra_get_option( 'theme-color' );
			$btn_text_color = astra_get_option( 'button-color' );
			if ( empty( $btn_text_color ) ) {
				$btn_text_color = astra_get_foreground_color( $theme_color );
			}

			return $btn_text_color;
		}

		/**
		 * Button Text Hover Color
		 */
		function button_text_hover_color() {
			$link_hover_color     = astra_get_option( 'link-h-color' );
			$btn_text_hover_color = astra_get_option( 'button-h-color' );
			if ( empty( $btn_text_hover_color ) ) {
				$btn_text_hover_color = astra_get_foreground_color( $link_hover_color );
			}

			return $btn_text_hover_color;
		}

		/**
		 * Default type : Button Text Hover Color
		 *
		 * @since 2.2.0
		 */
		function default_type_button_text_hover_color() {
			$link_hover_color     = astra_get_option( 'link-h-color' );
			$btn_text_hover_color = astra_get_option( 'button-h-color' );
			if ( empty( $btn_text_hover_color ) ) {
				$btn_text_hover_color = astra_get_foreground_color( $link_hover_color );
			}

			return $btn_text_hover_color;
		}

		/**
		 * Button Background Color
		 */
		function button_bg_color() {
			return astra_get_option( 'button-bg-color' );
		}

		/**
		 * Default type : Button Background Color
		 *
		 * @since 2.2.0
		 */
		function default_type_button_bg_color() {
			$theme_color  = astra_get_option( 'theme-color' );
			$btn_bg_color = astra_get_option( 'button-bg-color', $theme_color );
			return $btn_bg_color;
		}

		/**
		 * Button Background Color
		 */
		function button_bg_hover_color() {
			return astra_get_option( 'button-bg-h-color' );
		}

		/**
		 * Default type : Button Background Color
		 *
		 * @since 2.2.0
		 */
		function default_type_button_bg_hover_color() {
			$link_hover_color   = astra_get_option( 'link-h-color' );
			$btn_bg_hover_color = astra_get_option( 'button-bg-h-color', $link_hover_color );
			return $btn_bg_hover_color;
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
		 * Default type : Button Padding
		 *
		 * @since 2.2.0
		 */
		function default_type_button_padding() {

			$padding = astra_get_option( 'theme-button-padding' );

			return $padding;
		}

		/**
		 * Button Border Width
		 */
		function button_border_width() {

			$btn_width     = array();
			$get_btn_width = astra_get_option( 'theme-button-border-group-border-size' );

			if ( ! empty( $get_btn_width ) ) {
				$btn_width = $get_btn_width;
			}

			return $btn_width;
		}

		/**
		 * Button Border Color
		 */
		function button_border_color() {

			$theme_color          = astra_get_option( 'theme-color' );
			$btn_bg_color         = astra_get_option( 'button-bg-color', $theme_color );
			$get_btn_border_color = astra_get_option( 'theme-button-border-group-border-color', $btn_bg_color );

			return $get_btn_border_color;
		}

		/**
		 * Button Border Hover Color
		 */
		function button_border_hover_color() {

			$link_hover_color       = astra_get_option( 'link-h-color' );
			$btn_bg_hover_color     = astra_get_option( 'button-bg-h-color', $link_hover_color );
			$get_btn_border_h_color = astra_get_option( 'theme-button-border-group-border-h-color', $btn_bg_hover_color );

			return $get_btn_border_h_color;
		}

		/**
		 * Button Vertical Padding.
		 *
		 * @deprecated 2.2.0
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
		 * Button Horizontal Padding.
		 *
		 * @deprecated 2.2.0
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
