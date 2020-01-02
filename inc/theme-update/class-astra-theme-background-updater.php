<?php
/**
 * Theme Batch Update
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since 2.1.3
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
			'2.1.3' => array(
				'astra_submenu_below_header',
			),
			'2.2.0' => array(
				'astra_page_builder_button_color_compatibility',
				'astra_vertical_horizontal_padding_migration',
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
			require_once ASTRA_THEME_DIR . 'inc/lib/batch-processing/class-wp-async-request.php';
			require_once ASTRA_THEME_DIR . 'inc/lib/batch-processing/class-wp-background-process.php';
			require_once ASTRA_THEME_DIR . 'inc/theme-update/class-wp-background-process-astra-theme.php';

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

			$is_queue_running = astra_get_option( 'is_theme_queue_running', false );
			if ( ( $this->needs_db_update() && ! $is_queue_running ) || ! $this->is_db_updated() ) {
				error_log( 'Astra: Running update() function!' );
				$this->update();
			} else {
				if ( ! $is_queue_running || ! $this->is_db_updated() ) {
					self::update_db_version();
				}
			}
		}

		/**
		 * Is this a brand new theme install?
		 *
		 * @since 2.1.3
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

		/**
		 * Is a DB update needed?
		 *
		 * @since 2.1.3
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
		 * Is the DB updated?
		 *
		 * @since 2.2.0
		 * @return boolean
		 */
		private function is_db_updated() {
			$current_theme_version = astra_get_option( 'theme-auto-version', null );

			return version_compare( $current_theme_version, ASTRA_THEME_VERSION, '=' );
		}

		/**
		 * Get list of DB update callbacks.
		 *
		 * @since 2.1.3
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

			error_log( 'Astra: Batch Process Started!' );
			if ( count( $this->get_db_update_callbacks() ) > 0 ) {
				foreach ( $this->get_db_update_callbacks() as $version => $update_callbacks ) {
					if ( version_compare( $current_db_version, $version, '<' ) ) {
						foreach ( $update_callbacks as $update_callback ) {
							error_log( sprintf( 'Astra: Queuing %s - %s', $version, $update_callback ) );
							self::$background_updater->push_to_queue( $update_callback );
						}
					}
				}
				astra_update_option( 'is_theme_queue_running', true );

				self::$background_updater->push_to_queue( 'update_db_version' );
			} else {
				self::$background_updater->push_to_queue( 'update_db_version' );
			}
			self::$background_updater->save()->dispatch();
		}

		/**
		 * Update DB version to current.
		 *
		 * @param string|null $version New Astra theme version or null.
		 */
		public static function update_db_version( $version = null ) {

			do_action( 'astra_theme_update_before' );

			// Get auto saved version number.
			$saved_version = astra_get_option( 'theme-auto-version', false );

			if ( false === $saved_version ) {

				$saved_version = ASTRA_THEME_VERSION;

				// Update auto saved version number.
				astra_update_option( 'theme-auto-version', ASTRA_THEME_VERSION );
			}

			// If equals then return.
			if ( version_compare( $saved_version, ASTRA_THEME_VERSION, '=' ) ) {
				do_action( 'astra_theme_update_after' );
				astra_update_option( 'is_theme_queue_running', false );
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

			error_log( 'Astra: db version updated successfully!' );

			// Update variables.
			Astra_Theme_Options::refresh();

			astra_update_option( 'is_theme_queue_running', false );

			do_action( 'astra_theme_update_after' );
		}
	}
}


/**
 * Kicking this off by creating a new instance
 */
new Astra_Theme_Background_Updater;
