<?php
/**
 * Theme Update
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
		 * Process All
		 *
		 * @since 2.0.0
		 * @var object Class object.
		 * @access public
		 */
		public static $process_all;

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
			if ( is_admin() ) {
				add_action( 'admin_init', __CLASS__ . '::init', 5 );
			} else {
				add_action( 'wp', __CLASS__ . '::init', 5 );
			}
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

			// If there is no saved version in the database then return.
			if ( false === $saved_version ) {
				return;
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

			// Update astra meta settings for Beaver Themer Backwards Compatibility.
			if ( version_compare( $saved_version, '1.1.0-beta.3', '<' ) ) {
				self::v_1_1_0_beta_3();
			}

			// Update astra meta settings for Beaver Themer Backwards Compatibility.
			if ( version_compare( $saved_version, '1.1.0-beta.4', '<' ) ) {
				self::v_1_1_0_beta_4();
			}

			// Update astra meta settings for Beaver Themer Backwards Compatibility.
			if ( version_compare( $saved_version, '1.2.2', '<' ) ) {
				self::v_1_2_2();
			}

			// Update astra Theme colors values same as Link color.
			if ( version_compare( $saved_version, '1.2.4', '<' ) ) {
				self::v_1_2_4();
			}

			// Update astra Google Fonts values with fallback font.
			if ( version_compare( $saved_version, '1.2.7', '<' ) ) {
				self::v_1_2_7();
			}

			// Update astra background image data.
			if ( version_compare( $saved_version, '1.3.0', '<' ) ) {
				self::v_1_3_0();
			}

			// Update astra setting for inherit site logo compatibility.
			if ( version_compare( $saved_version, '1.4.0-beta.3', '<' ) ) {
				self::v_1_4_0_beta_3();
			}

			if ( version_compare( $saved_version, '1.4.0-beta.4', '<' ) ) {
				self::v_1_4_0_beta_4();
			}

			if ( version_compare( $saved_version, '1.4.0-beta.5', '<' ) ) {
				self::v_1_4_0_beta_5();
			}

			if ( version_compare( $saved_version, '1.4.3-alpha.1', '<' ) ) {
				self::v_1_4_3_alpha_1();
			}

			if ( version_compare( $saved_version, '1.4.9', '<' ) ) {
				self::v_1_4_9();
			}

			if ( version_compare( $saved_version, '1.5.0-beta.4', '<' ) ) {
				self::v_1_5_0_beta_4();
			}

			if ( version_compare( $saved_version, '1.5.0-rc.1', '<' ) ) {
				self::v_1_5_0_rc_1();
			}

			if ( version_compare( $saved_version, '1.5.0', '<' ) ) {
				self::v_1_5_0_rc_3();
			}

			if ( version_compare( $saved_version, '1.5.1', '<' ) ) {
				self::v_1_5_1();
			}

			if ( version_compare( $saved_version, '1.5.2', '<' ) ) {
				self::v_1_5_2();
			}

			if ( version_compare( $saved_version, '1.6.0', '<' ) ) {
				self::v_1_6_0();
			}

			if ( version_compare( $saved_version, '1.6.1-alpha.3', '<' ) ) {
				self::v_1_6_1();
			}
			if ( version_compare( $saved_version, '2.0.0', '<' ) ) {
				self::v_2_0_0();
			}
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
			update_option( '_astra_pb_compatibility_time', gmdate( 'Y-m-d H:i:s' ) );
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

		/**
		 * Update options of older version than 1.1.0-beta.3.
		 *
		 * @since 1.1.0-beta.3
		 */
		static public function v_1_1_0_beta_3() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( isset( $astra_options['shop-grid'] ) ) {

				$astra_options['shop-grids'] = array(
					'desktop' => $astra_options['shop-grid'],
					'tablet'  => 2,
					'mobile'  => 1,
				);

				unset( $astra_options['shop-grid'] );
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.1.0-beta.3.
		 *
		 * Container Style
		 * Sidebar
		 * Grid
		 *
		 * @since 1.1.0-beta.3
		 */
		static public function v_1_1_0_beta_4() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			$options = array(
				'woocommerce-content-layout' => 'default',
				'woocommerce-sidebar-layout' => 'default',
				/* Shop */
				'shop-grids'                 => array(
					'desktop' => 3,
					'tablet'  => 2,
					'mobile'  => 1,
				),
				'shop-no-of-products'        => '9',
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
		 * Update options of older version than 1.2.2.
		 *
		 * Logo Width
		 *
		 * @since 1.2.2
		 */
		static public function v_1_2_2() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( isset( $astra_options['ast-header-logo-width'] ) && ! is_array( $astra_options['ast-header-logo-width'] ) ) {
				$astra_options['ast-header-responsive-logo-width'] = array(
					'desktop' => $astra_options['ast-header-logo-width'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			if ( isset( $astra_options['blog-width'] ) ) {
				$astra_options['shop-archive-width'] = $astra_options['blog-width'];
			}

			if ( isset( $astra_options['blog-max-width'] ) ) {
				$astra_options['shop-archive-max-width'] = $astra_options['blog-max-width'];
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update Theme Color value same as Link Color for older version than 1.2.4.
		 *
		 * Theme Color update
		 *
		 * @since 1.2.4
		 */
		static public function v_1_2_4() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( isset( $astra_options['link-color'] ) ) {
				$astra_options['theme-color'] = $astra_options['link-color'];
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update Google Fonts value with font categories
		 *
		 * Google Font Update
		 *
		 * @since 1.2.7
		 */
		static public function v_1_2_7() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );
			$google_fonts  = Astra_Font_Families::get_google_fonts();

			foreach ( $astra_options as $key => $value ) {

				if ( ! is_array( $value ) && ! empty( $value ) && ! is_bool( $value ) ) {

					if ( array_key_exists( $value, $google_fonts ) ) {
						$astra_options[ $key ] = "'" . $value . "', " . $google_fonts[ $value ][1];
					}
				}
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.3.0.
		 *
		 * Background options
		 *
		 * @since 1.3.0
		 */
		static public function v_1_3_0() {
			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			$astra_options['header-bg-obj'] = array(
				'background-color' => isset( $astra_options['header-bg-color'] ) ? $astra_options['header-bg-color'] : '',
			);

			$astra_options['content-bg-obj'] = array(
				'background-color' => isset( $astra_options['content-bg-color'] ) ? $astra_options['content-bg-color'] : '#ffffff',
			);

			$astra_options['footer-adv-bg-obj'] = array(
				'background-color'      => isset( $astra_options['footer-adv-bg-color'] ) ? $astra_options['footer-adv-bg-color'] : '',
				'background-image'      => isset( $astra_options['footer-adv-bg-img'] ) ? $astra_options['footer-adv-bg-img'] : '',
				'background-repeat'     => isset( $astra_options['footer-adv-bg-repeat'] ) ? $astra_options['footer-adv-bg-repeat'] : 'no-repeat',
				'background-position'   => isset( $astra_options['footer-adv-bg-pos'] ) ? $astra_options['footer-adv-bg-pos'] : 'center center',
				'background-size'       => isset( $astra_options['footer-adv-bg-size'] ) ? $astra_options['footer-adv-bg-size'] : 'cover',
				'background-attachment' => isset( $astra_options['footer-adv-bg-attac'] ) ? $astra_options['footer-adv-bg-attac'] : 'scroll',
			);

			$astra_options['footer-bg-obj'] = array(
				'background-color'      => isset( $astra_options['footer-bg-color'] ) ? $astra_options['footer-bg-color'] : '',
				'background-image'      => isset( $astra_options['footer-bg-img'] ) ? $astra_options['footer-bg-img'] : '',
				'background-repeat'     => isset( $astra_options['footer-bg-rep'] ) ? $astra_options['footer-bg-rep'] : 'repeat',
				'background-position'   => isset( $astra_options['footer-bg-pos'] ) ? $astra_options['footer-bg-pos'] : 'center center',
				'background-size'       => isset( $astra_options['footer-bg-size'] ) ? $astra_options['footer-bg-size'] : 'auto',
				'background-attachment' => isset( $astra_options['footer-bg-atch'] ) ? $astra_options['footer-bg-atch'] : 'scroll',
			);

			// Site layout background image and color.
			$site_layout = isset( $astra_options['site-layout'] ) ? $astra_options['site-layout'] : '';
			switch ( $site_layout ) {
				case 'ast-box-layout':
						$astra_options['site-layout-outside-bg-obj'] = array(
							'background-color'      => isset( $astra_options['site-layout-outside-bg-color'] ) ? $astra_options['site-layout-outside-bg-color'] : '',
							'background-image'      => isset( $astra_options['site-layout-box-bg-img'] ) ? $astra_options['site-layout-box-bg-img'] : '',
							'background-repeat'     => isset( $astra_options['site-layout-box-bg-rep'] ) ? $astra_options['site-layout-box-bg-rep'] : 'no-repeat',
							'background-position'   => isset( $astra_options['site-layout-box-bg-pos'] ) ? $astra_options['site-layout-box-bg-pos'] : 'center center',
							'background-size'       => isset( $astra_options['site-layout-box-bg-size'] ) ? $astra_options['site-layout-box-bg-size'] : 'cover',
							'background-attachment' => isset( $astra_options['site-layout-box-bg-atch'] ) ? $astra_options['site-layout-box-bg-atch'] : 'scroll',
						);
					break;

				case 'ast-padded-layout':
						$bg_color = isset( $astra_options['site-layout-outside-bg-color'] ) ? $astra_options['site-layout-outside-bg-color'] : '';
						$bg_image = isset( $astra_options['site-layout-padded-bg-img'] ) ? $astra_options['site-layout-padded-bg-img'] : '';

						$astra_options['site-layout-outside-bg-obj'] = array(
							'background-color'      => empty( $bg_image ) ? $bg_color : '',
							'background-image'      => $bg_image,
							'background-repeat'     => isset( $astra_options['site-layout-padded-bg-rep'] ) ? $astra_options['site-layout-padded-bg-rep'] : 'no-repeat',
							'background-position'   => isset( $astra_options['site-layout-padded-bg-pos'] ) ? $astra_options['site-layout-padded-bg-pos'] : 'center center',
							'background-size'       => isset( $astra_options['site-layout-padded-bg-size'] ) ? $astra_options['site-layout-padded-bg-size'] : 'cover',
							'background-attachment' => '',
						);
					break;

				case 'ast-full-width-layout':
				case 'ast-fluid-width-layout':
				default:
								$astra_options['site-layout-outside-bg-obj'] = array(
									'background-color' => isset( $astra_options['site-layout-outside-bg-color'] ) ? $astra_options['site-layout-outside-bg-color'] : '',
								);
					break;
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Mobile Header - Border new param introduced for Top, Right, Bottom and left border.
		 * Update options of older version than 1.4.0-beta.3.
		 *
		 * @since 1.4.0-beta.3
		 */
		static public function v_1_4_0_beta_3() {

			$theme_options     = get_option( 'astra-settings' );
			$mobile_logo_width = astra_get_option( 'mobile-header-logo-width' );

			if ( '' != $mobile_logo_width ) {
				$theme_options['ast-header-responsive-logo-width']['tablet'] = $mobile_logo_width;
			}

			$mobile_logo = ( isset( $theme_options['mobile-header-logo'] ) && '' !== $theme_options['mobile-header-logo'] ) ? $theme_options['mobile-header-logo'] : false;

			if ( '' != $mobile_logo ) {
				$theme_options['inherit-sticky-logo'] = false;
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Introduced different logo for mobile devices option
		 *
		 * @since 1.4.0-beta.4
		 */
		static public function v_1_4_0_beta_4() {

			$mobile_header_logo = astra_get_option( 'mobile-header-logo' );
			$theme_options      = get_option( 'astra-settings' );

			if ( '' != $mobile_header_logo ) {
				$theme_options['different-mobile-logo'] = true;
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Function to backward compatibility for version less than 1.4.0
		 *
		 * @since 1.4.0-beta.5
		 */
		static public function v_1_4_0_beta_5() {

			// Set default toggle button style.
			$theme_options = get_option( 'astra-settings' );

			if ( ! isset( $theme_options['mobile-header-toggle-btn-style'] ) ) {
				$theme_options['mobile-header-toggle-btn-style'] = 'fill';
			}

			$theme_options['hide-custom-menu-mobile'] = 0;

			update_option( 'astra-settings', $theme_options );

		}

		/**
		 * Function to backward compatibility for version less than 1.4.3
		 * Set the new option different-retina-logo to true for users who are already using a retina logo.
		 *
		 * @since 1.4.3-aplha.1
		 */
		static public function v_1_4_3_alpha_1() {

			$mobile_header_logo = astra_get_option( 'ast-header-retina-logo' );
			$theme_options      = get_option( 'astra-settings' );

			if ( '' != $mobile_header_logo ) {
				$theme_options['different-retina-logo'] = '1';
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Manage backwards compatibility when migrating to v1.4.9
		 *
		 * @since 1.4.9
		 * @return void
		 */
		public static function v_1_4_9() {
			$theme_options = get_option( 'astra-settings' );

			// Set flag to use anchors CSS selectors in the CSS for headings.
			if ( ! isset( $theme_options['include-headings-in-typography'] ) ) {
				$theme_options['include-headings-in-typography'] = true;
				update_option( 'astra-settings', $theme_options );
			}
		}

		/**
		 * Added Submenu Border options into theme from Addon
		 *
		 * @since 1.5.0-beta.4
		 *
		 * @return void
		 */
		public static function v_1_5_0_beta_4() {

			$border_disabled_values        = array(
				'top'    => '0',
				'bottom' => '0',
				'left'   => '0',
				'right'  => '0',
			);
			$inside_border_disabled_values = array(
				'bottom' => '0',
			);

			$border_enabled_values        = array(
				'top'    => '1',
				'bottom' => '1',
				'left'   => '1',
				'right'  => '1',
			);
			$inside_border_enabled_values = array(
				'bottom' => '1',
			);

			$theme_options  = get_option( 'astra-settings' );
			$submenu_border = isset( $theme_options['primary-submenu-border'] ) ? $theme_options['primary-submenu-border'] : true;

			// Primary Header.
			if ( $submenu_border ) {
				$theme_options['primary-submenu-border']      = $border_enabled_values;
				$theme_options['primary-submenu-item-border'] = $inside_border_enabled_values;
			} else {
				$theme_options['primary-submenu-border']      = $border_disabled_values;
				$theme_options['primary-submenu-item-border'] = $inside_border_disabled_values;
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Set flag 'submenu-below-header' to false to load fallback CSS to force menu load right after the container cropping logo and header.
		 *
		 * @see https://github.com/brainstormforce/astra/pull/820/
		 *
		 * @return void
		 */
		public static function v_1_5_0_rc_1() {
			$theme_options = get_option( 'astra-settings' );

			// Set flag to use anchors CSS selectors in the CSS for headings.
			if ( ! isset( $theme_options['submenu-below-header'] ) ) {
				$theme_options['submenu-below-header'] = false;
				update_option( 'astra-settings', $theme_options );
			}
		}

		/**
		 * Set Primary Header submenu border color 'primary-submenu-b-color' to '#eaeaea' for old users who doesn't set any color and set the theme color who install the fresh 1.5.0-rc.3 theme.
		 *
		 * @see https://github.com/brainstormforce/astra/pull/835
		 *
		 * @return void
		 */
		public static function v_1_5_0_rc_3() {

			$theme_options = get_option( 'astra-settings' );

			// Set the default #eaeaea sub menu border color who doesn't set any color.
			if ( ! isset( $theme_options['primary-submenu-b-color'] ) || empty( $theme_options['primary-submenu-b-color'] ) ) {
				$theme_options['primary-submenu-b-color'] = '#eaeaea';
			}

			// Set the primary sub menu animation to default for existing user.
			if ( ! isset( $theme_options['header-main-submenu-container-animation'] ) ) {
				$theme_options['header-main-submenu-container-animation'] = '';
			}

			update_option( 'astra-settings', $theme_options );

		}

		/**
		 * Change the Primary submenu option to be checkbpx rather than border selection.
		 *
		 * @return void
		 */
		public static function v_1_5_1() {
			$theme_options               = get_option( 'astra-settings', array() );
			$primary_submenu_otem_border = isset( $theme_options['primary-submenu-item-border'] ) ? $theme_options['primary-submenu-item-border'] : array();

			if ( ( is_array( $primary_submenu_otem_border ) && '0' != $primary_submenu_otem_border['bottom'] ) ) {
				$theme_options['primary-submenu-item-border'] = 1;
			} else {
				$theme_options['primary-submenu-item-border'] = 0;
			}
			if ( isset( $theme_options['primary-submenu-b-color'] ) && ! empty( $theme_options['primary-submenu-b-color'] ) ) {
				$theme_options['primary-submenu-item-b-color'] = $theme_options['primary-submenu-b-color'];
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Add same font variant as font weight for body and heading.
		 *
		 * @return void
		 */
		public static function v_1_5_2() {
			$theme_options = get_option( 'astra-settings', array() );
			if ( isset( $theme_options['body-font-weight'] ) && is_numeric( $theme_options['body-font-weight'] ) ) {
				$theme_options['body-font-variant'] = $theme_options['body-font-weight'];
			}
			if ( isset( $theme_options['headings-font-weight'] ) && is_numeric( $theme_options['headings-font-weight'] ) ) {
				$theme_options['headings-font-variant'] = $theme_options['headings-font-weight'];
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Disable transparent header in customizer if the transparent header addon was disabled.
		 *
		 * @return void
		 */
		public static function v_1_6_0() {
			$theme_options = get_option( 'astra-settings', array() );

			// Disable Transparent header is Transparent Header addon was deactivated from Astra Pro.
			if ( is_callable( 'Astra_Ext_Extension::get_enabled_addons' ) ) {
				$addons = Astra_Ext_Extension::get_enabled_addons();

				// If transparent header is addon was disabled, disable the transparent header.
				if ( 'transparent-header' !== $addons['transparent-header'] ) {
					$theme_options['transparent-header-enable'] = 0;
				}
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Add backward compatibility for Heading tags previous default values.
		 * Set Inline Logo & Site Title as false if user had not changed its value.
		 * Change default value for blog archive blog title.
		 *
		 * @return void
		 */
		public static function v_1_6_1() {
			$theme_options = get_option( 'astra-settings', array() );

			// If user was using a default value for h1, Set the default in the option.
			if ( ! isset( $theme_options['font-size-h1'] ) ) {
				$theme_options['font-size-h1'] = array(
					'desktop'      => '48',
					'tablet'       => '',
					'mobile'       => '',
					'desktop-unit' => 'px',
					'tablet-unit'  => 'px',
					'mobile-unit'  => 'px',
				);
			}
			// If user was using a default value for h2, Set the default in the option.
			if ( ! isset( $theme_options['font-size-h2'] ) ) {
				$theme_options['font-size-h2'] = array(
					'desktop'      => '42',
					'tablet'       => '',
					'mobile'       => '',
					'desktop-unit' => 'px',
					'tablet-unit'  => 'px',
					'mobile-unit'  => 'px',
				);
			}
			// If user was using a default value for h3, Set the default in the option.
			if ( ! isset( $theme_options['font-size-h3'] ) ) {
				$theme_options['font-size-h3'] = array(
					'desktop'      => '30',
					'tablet'       => '',
					'mobile'       => '',
					'desktop-unit' => 'px',
					'tablet-unit'  => 'px',
					'mobile-unit'  => 'px',
				);
			}

			// If user was using a default value for h3, Set the default in the option.
			if ( ! isset( $theme_options['font-size-page-title'] ) ) {
				$theme_options['font-size-page-title'] = array(
					'desktop'      => '30',
					'tablet'       => '',
					'mobile'       => '',
					'desktop-unit' => 'px',
					'tablet-unit'  => 'px',
					'mobile-unit'  => 'px',
				);
			}

			// If inline-logo option was unset previously, set to to false as new default is `true`.
			if ( ! isset( $theme_options['logo-title-inline'] ) ) {
				$theme_options['logo-title-inline'] = 0;
			}

			update_option( 'astra-settings', $theme_options );
		}

		/**
		 * Flush bundled products After udpating to version 2.0.0
		 *
		 * @return void
		 */
		public static function v_2_0_0() {
			update_site_option( 'bsf_force_check_extensions', true );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Theme_Update::get_instance();
