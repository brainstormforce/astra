<?php
/**
 * Astra Loop
 *
 * @package Astra
 * @since x.x.x
 */

if ( ! class_exists( 'Astra_Mobile_Header' ) ) :

	/**
	 * Astra_Mobile_Header
	 *
	 * @since x.x.x
	 */
	class Astra_Mobile_Header {

		/**
		 * Instance
		 *
		 * @since x.x.x
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since x.x.x
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since x.x.x
		 */
		public function __construct() {

			add_action( 'astra_header', array( $this, 'mobile_header_markup' ), 5 );
		}

		function mobile_header_markup() {
			$mobile_header_logo = astra_get_option( 'mobile-header-logo' );

			if ( '' !== $mobile_header_logo ) {
				add_filter( 'astra_has_custom_logo', '__return_true' );
				add_filter( 'get_custom_logo', array( $this, 'astra_mobile_header_custom_logo' ), 10, 2 );
			}
		}
		/**
		 * Replace logo with Mobile Header logo.
		 *
		 * @param sting $html Size name.
		 * @param int   $blog_id Icon.
		 * @since x.x.x
		 * @return string html markup of logo.
		 */
		function astra_mobile_header_custom_logo( $html, $blog_id ) {

			$mobile_header_logo = astra_get_option( 'mobile-header-logo' );

			$custom_logo_id = attachment_url_to_postid( $mobile_header_logo );

			$size = 'ast-mobile-header-logo-size';

			if ( is_customize_preview() ) {
				$size = 'full';
			}

			$logo = sprintf(
				'<a href="%1$s" class="custom-mobile-logo-link" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image(
					$custom_logo_id, $size, false, array(
						'class' => 'ast-mobile-header-logo',
					)
				)
			);

			return $html . $logo;

		}

	}

	/**
	 * Initialize class object with 'get_instance()' method
	 */
	Astra_Mobile_Header::get_instance();

endif;
