<?php
/**
 * BB Press Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'BB Press' not exist then return.
if ( ! class_exists( 'bbPress' ) ) {
	return;
}

/**
 * Astra BB Press Compatibility
 */
if ( ! class_exists( 'Astra_BBPress' ) ) :

	/**
	 * Astra BB Press Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_BBPress
	{
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
		public function __construct()
		{
			add_action( 'astra_template_parts_content', array( $this, 'add_template_parts' ), 5 );
		}

		/**
		 * Template part default
		 *
		 * @since 1.2.8
		 * @return void
		 */
		public function add_template_parts()
		{
			if( function_exists('bbp_is_theme_compat_active') && bbp_is_theme_compat_active() )
			{
				// Load page template part.
				get_template_part( 'template-parts/content', 'page' );
				
				// Remove default hook.
				remove_action( 'astra_template_parts_content', array( Astra_Loop::get_instance(), 'template_parts_default' ) );
			}
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_BBPress::get_instance();

endif;
