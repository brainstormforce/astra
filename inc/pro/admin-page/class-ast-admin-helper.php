<?php
/**
 * Admin settings helper
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0
 */

if ( ! class_exists( 'Ast_Admin_Helper' ) ) :

	/**
	 * Admin Helper
	 */
	final class Ast_Admin_Helper {

		/**
		 * Returns an option from the database for
		 * the admin settings page.
		 *
		 * @param  string  $key     The option key.
		 * @param  boolean $network Whether to allow the network admin setting to be overridden on subsites.
		 * @return string           Return the option value
		 */
		static public function get_admin_settings_option( $key, $network = false ) {
			// Get the site-wide option if we're in the network admin.
			if ( is_network_admin() ) {
				$value = get_site_option( $key );
			} elseif ( $network && is_multisite() ) {
				$value = get_site_option( $key );
			} // End if().
			else {
				$value = get_option( $key );
			}

			return $value;
		}

		/**
		 * Updates an option from the admin settings page.
		 *
		 * @param string $key 		The option key.
		 * @param mixed  $value 	The value to update.
		 * @param bool   $network 	Whether to allow the network admin setting to be overridden on subsites.
		 * @return mixed
		 */
		static public function update_admin_settings_option( $key, $value, $network = false ) {
			// Update the site-wide option since we're in the network admin.
			if ( is_network_admin() || $network ) {
				update_site_option( $key, $value );
			} elseif ( $network && is_multisite() ) {
				update_site_option( $key, $value );
			} // End if().
			else {
				update_option( $key, $value );
			}
		}

		/**
		 * Returns an option from the database for
		 * the admin settings page.
		 *
		 * @param string $key The option key.
		 * @param bool   $network Whether to allow the network admin setting to be overridden on subsites.
		 * @return mixed
		 */
		static public function delete_admin_settings_option( $key, $network = false ) {
			// Get the site-wide option if we're in the network admin.
			if ( is_network_admin() ) {
				$value = delete_site_option( $key );
			} elseif ( $network && is_multisite() ) {
				$value = delete_site_option( $key );
			} // End if().
			else {
				$value = delete_option( $key );
			}

			return $value;
		}
	}


endif;
