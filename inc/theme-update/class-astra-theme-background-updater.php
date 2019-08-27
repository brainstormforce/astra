<?php
/**
 * Theme Batch Update
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since x.x.x
 */

if ( ! class_exists( 'Astra_Theme_Background_Updater' ) ) {

	/**
	 * Astra_Theme_Background_Updater Class.
	 */
	class Astra_Theme_Background_Updater {

		/**
		 * Background update class.
		 *
		 * @var object
		 */
		private static $background_updater;

		/**
		 * DB updates and callbacks that need to be run per version.
		 *
		 * @var array
		 */
		private static $db_updates = array(
			'2.1.1' => array(
				'theme_update_site_icon_defaults',
				'theme_update_transparent_header_defaults',
				'theme_update_sticky_header_new_option',
				'theme_update_above_header_new_option',
				'theme_update_footer_options_defaults',
			),
		);

		/**
		 *  Constructor
		 */
		public function __construct() {

			// Theme Updates.
			if ( is_admin() ) {
				add_action( 'admin_init', array( $this, 'install_actions' ) );
			} else {
				add_action( 'wp', array( $this, 'install_actions' ) );
			}

			// Core Helpers - Batch Processing.
			require_once ASTRA_THEME_DIR . 'inc/theme-update/batch-processing/class-wp-async-request.php';
			require_once ASTRA_THEME_DIR . 'inc/theme-update/batch-processing/class-wp-background-process.php';
			require_once ASTRA_THEME_DIR . 'inc/theme-update/batch-processing/class-wp-background-process-astra-theme.php';

			self::$background_updater = new WP_Background_Process_Astra_Theme();

		}

		/**
		 * Install actions when a update button is clicked within the admin area.
		 *
		 * This function is hooked into admin_init to affect admin and wp to affect the frontend.
		 */
		public function install_actions() {

			do_action( 'astra_update_initiated', self::$background_updater );

			if ( true === $this->is_new_install() ) {
				self::update_db_version();
				return;
			}

			$is_queue_running = astra_get_option( 'is_theme_queue_running' );

			if ( $this->needs_db_update() && ! $is_queue_running ) {
				$this->update();
			} else {
				self::update_db_version();
			}
		}

		/**
		 * Is a DB update needed?
		 *
		 * @since x.x.x
		 * @return boolean
		 */
		private function needs_db_update() {
			$current_theme_version = astra_get_option( 'theme-auto-version', null );
			$updates               = $this->get_db_update_callbacks();

			if ( empty( $updates ) ) {
				return false;
			}

			return ! is_null( $current_theme_version ) && version_compare( $current_theme_version, max( array_keys( $updates ) ), '<' );
		}

		/**
		 * Get list of DB update callbacks.
		 *
		 * @since x.x.x
		 * @return array
		 */
		public function get_db_update_callbacks() {
			return self::$db_updates;
		}

		/**
		 * Push all needed DB updates to the queue for processing.
		 */
		private function update() {
			$current_db_version = astra_get_option( 'theme-auto-version' );

			error_log( 'Batch Process Started!' );
			foreach ( $this->get_db_update_callbacks() as $version => $update_callbacks ) {
				if ( version_compare( $current_db_version, $version, '<' ) ) {
					foreach ( $update_callbacks as $update_callback ) {
						error_log( sprintf( 'Queuing %s - %s', $version, $update_callback ) );

						self::$background_updater->push_to_queue( $update_callback );
					}
				}
			}

			astra_update_option( 'is_theme_queue_running', true );

			self::$background_updater->push_to_queue( 'update_db_version' );
			self::$background_updater->save()->dispatch();

		}

		/**
		 * Update DB version to current.
		 *
		 * @param string|null $version New Astra theme version or null.
		 */
		public static function update_db_version( $version = null ) {

			do_action( 'astra_update_before' );

			// Get auto saved version number.
			$saved_version = astra_get_option( 'theme-auto-version', false );

			if ( false === $saved_version ) {

				$saved_version = ASTRA_THEME_VERSION;

				// Update auto saved version number.
				astra_update_option( 'theme-auto-version', ASTRA_THEME_VERSION );
			}

			// If equals then return.
			if ( version_compare( $saved_version, ASTRA_THEME_VERSION, '=' ) ) {
				return;
			}

			// Not have stored?
			if ( empty( $saved_version ) ) {

				// Get old version.
				$theme_version = get_option( '_astra_auto_version', ASTRA_THEME_VERSION );

				// Remove option.
				delete_option( '_astra_auto_version' );

			} else {

				// Get latest version.
				$theme_version = ASTRA_THEME_VERSION;
			}

			// Update auto saved version number.
			astra_update_option( 'theme-auto-version', $theme_version );

			// Update variables.
			Astra_Theme_Options::refresh();

			astra_update_option( 'is_theme_queue_running', false );

			do_action( 'astra_update_after' );
		}

		/**
		 * Is this a brand new theme install?
		 *
		 * @since x.x.x
		 * @return boolean
		 */
		function is_new_install() {

			// Get auto saved version number.
			$saved_version = astra_get_option( 'theme-auto-version', false );

			if ( false === $saved_version ) {
				return true;
			}

			return false;
		}
	}
}


/**
 * Kicking this off by creating a new instance
 */
new Astra_Theme_Background_Updater;
