<?php
/**
 * Brizy Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'Brizy' not exist then return.
if ( ! class_exists( 'Brizy_Editor' ) ) {
	return;
}


/**
 * Astra Brizy Compatibility
 */
if ( ! class_exists( 'Astra_Brizy_Editor' ) ) :

	/**
	 * Astra Brizy Compatibility
	 *
	 * @since x.x.x
	 */
	class Astra_Brizy_Editor {

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
			add_action( 'wp', array( $this, 'brizy_default_setting' ), 20 );
		}

		/**
		 * Brizy Content layout set as Page Builder
		 *
		 * @return void
		 * @since  x.x.x
		 */
		function brizy_default_setting() {

			if ( false == astra_enable_page_builder_compatibility() || 'post' == get_post_type() ) {
				return;
			}
			// don't modify post meta settings if we are not on Brizy's edit page.
			if ( ! $this->is_brizy_editor() ) {
				return;
			}
		}

		/**
		 * Check if Brizy Editor is open.
		 *
		 * @since x.x.x
		 *
		 * @return boolean True IF Brizy Editor is loaded, False If Brizy Editor is not loaded.
		 */
		private function is_brizy_editor() {
			if ( isset( $_REQUEST['brizy-edit'] ) ) {
				return true;
			}

			return false;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Brizy_Editor::get_instance();
