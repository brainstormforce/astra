<?php
/**
 * BSF analytics stat class file.
 *
 * @package bsf-analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Analytics_Stats' ) ) {
	/**
	 * BSF analytics stat class.
	 */
	class BSF_Analytics_Stats {

		/**
		 * Active plugins.
		 *
		 * Holds the sites active plugins list.
		 *
		 * @var array
		 */
		private $plugins;

		/**
		 * Instance of BSF_Analytics_Stats.
		 *
		 * Holds only the first object of class.
		 *
		 * @var object
		 */
		private static $instance = null;

		/**
		 * Create only once instance of a class.
		 *
		 * @return object
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get stats.
		 *
		 * @return array stats data.
		 * @since 1.0.0
		 */
		public function get_stats() {
			return apply_filters( 'bsf_core_stats', $this->get_default_stats() );
		}

		/**
		 * Retrieve stats for site.
		 *
		 * @return array stats data.
		 * @since 1.0.0
		 */
		private function get_default_stats() {
			return array(
				'graupi_version'         => defined( 'BSF_UPDATER_VERSION' ) ? BSF_UPDATER_VERSION : false,
				'domain_name'            => get_site_url(),
				'php_os'                 => PHP_OS,
				'server_software'        => $_SERVER['SERVER_SOFTWARE'],
				'mysql_version'          => $this->get_mysql_version(),
				'php_version'            => $this->get_php_version(),
				'php_max_input_vars'     => ini_get( 'max_input_vars' ), // phpcs:ignore:PHPCompatibility.IniDirectives.NewIniDirectives.max_input_varsFound
				'php_post_max_size'      => ini_get( 'post_max_size' ),
				'php_max_execution_time' => ini_get( 'max_execution_time' ),
				'php_memory_limit'       => ini_get( 'memory_limit' ),
				'zip_installed'          => extension_loaded( 'zip' ),
				'imagick_availabile'     => extension_loaded( 'imagick' ),
				'xmlreader_exists'       => class_exists( 'XMLReader' ),
				'gd_available'           => extension_loaded( 'gd' ),
				'curl_version'           => $this->get_curl_version(),
				'curl_ssl_version'       => $this->get_curl_ssl_version(),
				'is_writable'            => $this->is_content_writable(),

				'wp_version'             => get_bloginfo( 'version' ),
				'user_count'             => $this->get_user_count(),
				'site_language'          => get_locale(),
				'timezone'               => wp_timezone_string(),
				'is_ssl'                 => is_ssl(),
				'is_multisite'           => is_multisite(),
				'network_url'            => network_site_url(),
				'external_object_cache'  => (bool) wp_using_ext_object_cache(),
				'wp_debug'               => WP_DEBUG,
				'wp_debug_display'       => WP_DEBUG_DISPLAY,
				'script_debug'           => SCRIPT_DEBUG,

				'active_plugins'         => $this->get_active_plugins(),

				'active_theme'           => get_template(),
				'active_stylesheet'      => get_stylesheet(),
			);
		}

		/**
		 * Get installed PHP version.
		 *
		 * @return float PHP version.
		 * @since 1.0.0
		 */
		private function get_php_version() {
			if ( defined( 'PHP_MAJOR_VERSION' ) && defined( 'PHP_MINOR_VERSION' ) && defined( 'PHP_RELEASE_VERSION' ) ) { // phpcs:ignore
				return PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;
			}

			return phpversion();
		}

		/**
		 * User count on site.
		 *
		 * @return int User count.
		 * @since 1.0.0
		 */
		private function get_user_count() {
			if ( is_multisite() ) {
				$user_count = get_user_count();
			} else {
				$count      = count_users();
				$user_count = $count['total_users'];
			}

			return $user_count;
		}

		/**
		 * Get active plugin's data.
		 *
		 * @return array active plugin's list.
		 * @since 1.0.0
		 */
		private function get_active_plugins() {
			if ( ! $this->plugins ) {
				// Ensure get_plugin_data function is loaded.
				if ( ! function_exists( 'get_plugin_data' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$plugins       = wp_get_active_and_valid_plugins();
				$plugins       = array_map( 'get_plugin_data', $plugins );
				$this->plugins = array_map( array( $this, 'format_plugin' ), $plugins );
			}

			return $this->plugins;
		}

		/**
		 * Format plugin data.
		 *
		 * @param string $plugin plugin.
		 * @return array formatted plugin data.
		 * @since 1.0.0
		 */
		public function format_plugin( $plugin ) {
			return array(
				'name'        => html_entity_decode( $plugin['Name'], ENT_COMPAT, 'UTF-8' ),
				'url'         => $plugin['PluginURI'],
				'version'     => $plugin['Version'],
				'slug'        => $plugin['TextDomain'],
				'author_name' => html_entity_decode( wp_strip_all_tags( $plugin['Author'] ), ENT_COMPAT, 'UTF-8' ),
				'author_url'  => $plugin['AuthorURI'],
			);
		}

		/**
		 * Curl SSL version.
		 *
		 * @return float SSL version.
		 * @since 1.0.0
		 */
		private function get_curl_ssl_version() {
			$curl = array();
			if ( function_exists( 'curl_version' ) ) {
				$curl = curl_version();
			}

			return isset( $curl['ssl_version'] ) ? $curl['ssl_version'] : false;
		}

		/**
		 * Get cURL version.
		 *
		 * @return float cURL version.
		 * @since 1.0.0
		 */
		private function get_curl_version() {
			$curl = array();
			if ( function_exists( 'curl_version' ) ) {
				$curl = curl_version();
			}

			return isset( $curl['version'] ) ? $curl['version'] : false;
		}

		/**
		 * Get MySQL version.
		 *
		 * @return float MySQL version.
		 * @since 1.0.0
		 */
		private function get_mysql_version() {
			global $wpdb;
			return $wpdb->db_version();
		}

		/**
		 * Check if content directory is writable.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		private function is_content_writable() {
			$upload_dir = wp_upload_dir();
			return wp_is_writable( $upload_dir['basedir'] );
		}
	}
}

/**
 * Polyfill for sites using WP version less than 5.3
 */
if ( ! function_exists( 'wp_timezone_string' ) ) {
	/**
	 * Get timezone string.
	 *
	 * @return string timezone string.
	 * @since 1.0.0
	 */
	function wp_timezone_string() {
		$timezone_string = get_option( 'timezone_string' );

		if ( $timezone_string ) {
			return $timezone_string;
		}

		$offset  = (float) get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );

		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

		return $tz_offset;
	}
}
