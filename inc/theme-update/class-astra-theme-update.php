<?php
/**
 * Theme Update
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! class_exists( 'Astra_Theme_Update' ) ) {

	/**
	 * Astra_Theme_Update initial setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Theme_Update {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			// Theme Updates.
			add_action( 'init', __CLASS__ . '::init' );
			add_action( 'init', __CLASS__ . '::astra_pro_compatibility' );
		}

		/**
		 * Implement theme update logic.
		 *
		 * @since 1.0.0
		 */
		static public function init() {

			do_action( 'astra_update_before' );

			// Get auto saved version number.
			$saved_version = astra_get_option( 'theme-auto-version', false );

			if ( ! $saved_version ) {

				// Get all customizer options.
				$customizer_options = get_option( ASTRA_THEME_SETTINGS );

				// Get all customizer options.
				$version_array = array(
					'theme-auto-version' => ASTRA_THEME_VERSION,
				);
				$saved_version = ASTRA_THEME_VERSION;

				// Merge customizer options with version.
				$theme_options = wp_parse_args( $version_array, $customizer_options );

				// Update auto saved version number.
				update_option( ASTRA_THEME_SETTINGS, $theme_options );
			}

			// If equals then return.
			if ( version_compare( $saved_version, ASTRA_THEME_VERSION, '=' ) ) {
				return;
			}

			// Update to older version than 1.0.4 version.
			if ( version_compare( $saved_version, '1.0.4', '<' ) ) {
				self::v_1_0_4();
			}

			// Update to older version than 1.0.5 version.
			if ( version_compare( $saved_version, '1.0.5', '<' ) ) {
				self::v_1_0_5();
			}

			// Update to older version than 1.0.8 version.
			if ( version_compare( $saved_version, '1.0.8', '<' ) && version_compare( $saved_version, '1.0.4', '>' ) ) {
				self::v_1_0_8();
			}

			// Update to older version than 1.0.12 version.
			if ( version_compare( $saved_version, '1.0.12', '<' ) ) {
				self::v_1_0_12();
			}

			// Update to older version than 1.0.14 version.
			if ( version_compare( $saved_version, '1.0.14', '<' ) ) {
				self::v_1_0_14();
			}

			// Update astra meta settings for Beaver Themer Backwards Compatibility.
			if ( version_compare( $saved_version, '1.0.28', '<' ) ) {
				self::v_1_0_28();
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

			// Get all customizer options.
			$customizer_options = get_option( ASTRA_THEME_SETTINGS );

			// Get all customizer options.
			$version_array = array(
				'theme-auto-version' => $theme_version,
			);

			// Merge customizer options with version.
			$theme_options = wp_parse_args( $version_array, $customizer_options );

			// Update auto saved version number.
			update_option( ASTRA_THEME_SETTINGS, $theme_options );

			do_action( 'astra_update_after' );

		}

		/**
		 * Footer Widgets compatibilty for astra pro.
		 */
		static public function astra_pro_compatibility() {

			if ( defined( 'ASTRA_EXT_VER' ) && version_compare( ASTRA_EXT_VER, '1.0.0-beta.6', '<' ) ) {
				remove_action( 'astra_footer_content', 'astra_advanced_footer_markup', 1 );
			}
		}

		/**
		 * Update options of older version than 1.0.4.
		 *
		 * @since 1.0.4
		 */
		static public function v_1_0_4() {

			$options = array(
				'font-size-body',
				'body-line-height',
				'font-size-site-title',
				'font-size-site-tagline',
				'font-size-entry-title',
				'font-size-page-title',
				'font-size-h1',
				'font-size-h2',
				'font-size-h3',
				'font-size-h4',
				'font-size-h5',
				'font-size-h6',

				// Addon Options.
				'footer-adv-wgt-title-font-size',
				'footer-adv-wgt-title-line-height',
				'footer-adv-wgt-content-font-size',
				'footer-adv-wgt-content-line-height',
				'above-header-font-size',
				'font-size-below-header-primary-menu',
				'font-size-below-header-dropdown-menu',
				'font-size-below-header-content',
				'font-size-related-post',
				'line-height-related-post',
				'title-bar-title-font-size',
				'title-bar-title-line-height',
				'title-bar-breadcrumb-font-size',
				'title-bar-breadcrumb-line-height',
				'line-height-page-title',
				'font-size-post-meta',
				'line-height-post-meta',
				'font-size-post-pagination',
				'line-height-h1',
				'line-height-h2',
				'line-height-h3',
				'line-height-h4',
				'line-height-h5',
				'line-height-h6',
				'font-size-footer-content',
				'line-height-footer-content',
				'line-height-site-title',
				'line-height-site-tagline',
				'font-size-primary-menu',
				'line-height-primary-menu',
				'font-size-primary-dropdown-menu',
				'line-height-primary-dropdown-menu',
				'font-size-widget-title',
				'line-height-widget-title',
				'font-size-widget-content',
				'line-height-widget-content',
				'line-height-entry-title',
			);

			$astra_options = get_option( 'ast-settings', array() );

			if ( 0 < count( $astra_options ) ) {
				foreach ( $options as $key ) {

					if ( array_key_exists( $key, $astra_options ) && ! is_array( $astra_options[ $key ] ) ) {

						$astra_options[ $key ] = array(
							'desktop'      => $astra_options[ $key ],
							'tablet'       => '',
							'mobile'       => '',
							'desktop-unit' => 'px',
							'tablet-unit'  => 'px',
							'mobile-unit'  => 'px',
						);
					}
				}
			}

			update_option( 'ast-settings', $astra_options );
		}

		/**
		 * Update options of older version than 1.0.5.
		 *
		 * @since 1.0.5
		 */
		static public function v_1_0_5() {

			$astra_old_options = get_option( 'ast-settings', array() );
			$astra_new_options = get_option( ASTRA_THEME_SETTINGS, array() );

			// Merge old customizer options in new option.
			$astra_options = wp_parse_args( $astra_new_options, $astra_old_options );

			// Update option.
			update_option( ASTRA_THEME_SETTINGS, $astra_options );

			// Delete old option.
			delete_option( 'ast-settings' );
		}

		/**
		 * Update options of older version than 1.0.8.
		 *
		 * @since 1.0.8
		 */
		static public function v_1_0_8() {

			$options = array(
				'body-line-height',

				// Addon Options.
				'footer-adv-wgt-title-line-height',
				'footer-adv-wgt-content-line-height',
				'line-height-related-post',
				'title-bar-title-line-height',
				'title-bar-breadcrumb-line-height',
				'line-height-page-title',
				'line-height-post-meta',
				'line-height-h1',
				'line-height-h2',
				'line-height-h3',
				'line-height-h4',
				'line-height-h5',
				'line-height-h6',
				'line-height-footer-content',
				'line-height-site-title',
				'line-height-site-tagline',
				'line-height-primary-menu',
				'line-height-primary-dropdown-menu',
				'line-height-widget-title',
				'line-height-widget-content',
				'line-height-entry-title',
			);

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( 0 < count( $astra_options ) ) {
				foreach ( $options as $key ) {

					if ( array_key_exists( $key, $astra_options ) && is_array( $astra_options[ $key ] ) ) {

						if ( in_array( $astra_options[ $key ]['desktop-unit'], array( '', 'em' ) ) ) {
							$astra_options[ $key ] = $astra_options[ $key ]['desktop'];
						} else {
							$astra_options[ $key ] = '';
						}
					}
				}
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.0.12.
		 *
		 * @since 1.0.12
		 */
		static public function v_1_0_12() {

			$options = array(
				'site-content-layout'         => 'plain-container',
				'single-page-content-layout'  => 'plain-container',
				'single-post-content-layout'  => 'content-boxed-container',
				'archive-post-content-layout' => 'content-boxed-container',
			);

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			foreach ( $options as $key => $value ) {
				if ( ! isset( $astra_options[ $key ] ) ) {
					$astra_options[ $key ] = $value;
				}
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.0.14.
		 *
		 * @since 1.0.14
		 * @return void
		 */
		static public function v_1_0_14() {

			$options = array(
				'footer-sml-divider'          => '4',
				'footer-sml-divider-color'    => '#fff',
				'footer-adv'                  => 'layout-4',
				'single-page-sidebar-layout'  => 'no-sidebar',
				'single-post-sidebar-layout'  => 'right-sidebar',
				'archive-post-sidebar-layout' => 'right-sidebar',
			);

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			foreach ( $options as $key => $value ) {
				if ( ! isset( $astra_options[ $key ] ) ) {
					$astra_options[ $key ] = $value;
				}
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );

			update_option( '_astra_pb_compatibility_offset', 1 );
			update_option( '_astra_pb_compatibility_time', date( 'Y-m-d H:i:s' ) );
		}

		/**
		 * Update page meta settings for all the themer layouts which are not already set.
		 * Default settings to previous versions was `no-sidebar` and `page-builder` through filters.
		 *
		 * @since  1.0.28
		 * @return void
		 */
		static public function v_1_0_28() {

			$query = array(
				'post_type'      => 'fl-theme-layout',
				'posts_per_page' => '-1',
				'no_found_rows'  => true,
				'post_status'    => 'any',
				'fields'         => 'ids',
			);

			// Execute the query.
			$posts = new WP_Query( $query );

			foreach ( $posts->posts as $id ) {

				$sidebar = get_post_meta( $id, 'site-sidebar-layout', true );

				if ( '' == $sidebar ) {
					update_post_meta( $id, 'site-sidebar-layout', 'no-sidebar' );
				}

				$content_layout = get_post_meta( $id, 'site-content-layout', true );

				if ( '' == $content_layout ) {
					update_post_meta( $id, 'site-content-layout', 'page-builder' );
				}
			}

		}

	}

}// End if().

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Theme_Update::get_instance();
