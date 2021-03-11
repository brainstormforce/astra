<?php
/**
 * Admin settings helper
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Admin_Settings' ) ) {

	/**
	 * Astra Admin Settings
	 */
	class Astra_Admin_Settings {

		/**
		 * Menu page title
		 *
		 * @since 1.0
		 * @var array $menu_page_title
		 */
		public static $menu_page_title;

		/**
		 * Page title
		 *
		 * @since 1.0
		 * @var array $page_title
		 */
		public static $page_title = 'Astra';

		/**
		 * Plugin slug
		 *
		 * @since 1.0
		 * @var array $plugin_slug
		 */
		public static $plugin_slug = 'astra';

		/**
		 * Default Menu position
		 *
		 * @since 1.0
		 * @var array $default_menu_position
		 */
		public static $default_menu_position = 'themes.php';

		/**
		 * Parent Page Slug
		 *
		 * @since 1.0
		 * @var array $parent_page_slug
		 */
		public static $parent_page_slug = 'general';

		/**
		 * Current Slug
		 *
		 * @since 1.0
		 * @var array $current_slug
		 */
		public static $current_slug = 'general';

		/**
		 * Starter Templates Slug
		 *
		 * @since 2.3.2
		 * @var array $starter_templates_slug
		 */
		public static $starter_templates_slug = 'astra-sites';

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			self::get_starter_templates_slug();

			add_action( 'after_setup_theme', __CLASS__ . '::init_admin_settings', 99 );
		}

		/**
		 * Admin settings init
		 */
		public static function init_admin_settings() {
			self::$menu_page_title = apply_filters( 'astra_menu_page_title', __( 'Astra Options', 'astra' ) );
			self::$page_title      = apply_filters( 'astra_page_title', __( 'Astra', 'astra' ) );
			self::$plugin_slug     = self::get_theme_page_slug();

			add_action( 'admin_enqueue_scripts', __CLASS__ . '::register_scripts' );

			if ( ! is_customize_preview() ) {
				// add css on the admin init action to resolve the error in the PWA service worker js.
				add_action( 'admin_head', __CLASS__ . '::admin_submenu_css' );
			}

			$requested_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';// phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( strpos( $requested_page, self::$plugin_slug ) !== false ) {

				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );

				// Let extensions hook into saving.
				do_action( 'astra_admin_settings_scripts' );

				if ( defined( 'ASTRA_EXT_VER' ) && version_compare( ASTRA_EXT_VER, '2.5.0', '<' ) ) {
					self::save_settings();
				}
			}

			add_action( 'customize_controls_enqueue_scripts', __CLASS__ . '::customizer_scripts' );

			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );

			add_action( 'astra_menu_general_action', __CLASS__ . '::general_page' );

			add_action( 'astra_header_right_section', __CLASS__ . '::top_header_right_section' );

			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_starter_sites_section', 10 );
			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_knowledge_base_scetion', 11 );
			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_community_scetion', 12 );
			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_five_star_scetion', 13 );

			add_action( 'astra_welcome_page_content', __CLASS__ . '::astra_welcome_page_content' );
			add_action( 'astra_welcome_page_content', __class__ . '::astra_available_plugins', 30 );

			// AJAX.
			add_action( 'wp_ajax_astra-sites-plugin-activate', __CLASS__ . '::required_plugin_activate' );
			add_action( 'wp_ajax_astra-sites-plugin-deactivate', __CLASS__ . '::required_plugin_deactivate' );

			add_action( 'admin_notices', __CLASS__ . '::register_notices' );
			add_action( 'astra_notice_before_markup', __CLASS__ . '::notice_assets' );

			add_action( 'admin_notices', __CLASS__ . '::minimum_addon_version_notice' );
		}

		/**
		 * Save All admin settings here
		 */
		public static function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Let extensions hook into saving.
			do_action( 'astra_admin_settings_save' );
		}

		/**
		 * Theme options page Slug getter including White Label string.
		 *
		 * @since 2.1.0
		 * @return string Theme Options Page Slug.
		 */
		public static function get_theme_page_slug() {
			return apply_filters( 'astra_theme_page_slug', self::$plugin_slug );
		}

		/**
		 * Ask Theme Rating
		 *
		 * @since 1.4.0
		 */
		public static function register_notices() {
			// Return if white labeled.
			if ( astra_is_white_labelled() ) {
				return;
			}

			// Force Astra welcome notice on theme activation.
			if ( current_user_can( 'install_plugins' ) && ! defined( 'ASTRA_SITES_NAME' ) && '1' == get_option( 'fresh_site' ) ) {

				wp_enqueue_script( 'astra-admin-settings' );
				$image_path           = ASTRA_THEME_URI . 'inc/assets/images/astra-logo.svg';
				$ast_sites_notice_btn = self::astra_sites_notice_button();

				if ( file_exists( WP_PLUGIN_DIR . '/astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-pro-sites/astra-pro-sites.php' ) ) {
					$ast_sites_notice_btn['button_text'] = __( 'Get Started', 'astra' );
					$ast_sites_notice_btn['class']      .= ' button button-primary button-hero';
				} elseif ( ! file_exists( WP_PLUGIN_DIR . '/astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-pro-sites/astra-pro-sites.php' ) ) {
					$ast_sites_notice_btn['button_text'] = __( 'Get Started', 'astra' );
					$ast_sites_notice_btn['class']      .= ' button button-primary button-hero';
					// Astra Premium Sites - Active.
				} else {
					$ast_sites_notice_btn['class'] = ' button button-primary button-hero astra-notice-close';
				}

				$astra_sites_notice_args = array(
					'id'                         => 'astra-sites-on-active',
					'type'                       => 'info',
					'message'                    => sprintf(
						'<div class="notice-image">
							<img src="%1$s" class="custom-logo" alt="Astra" itemprop="logo"></div>
							<div class="notice-content">
								<h2 class="notice-heading">
									%2$s
								</h2>
								<p>%3$s</p>
								<div class="astra-review-notice-container">
									<a class="%4$s" %5$s %6$s %7$s %8$s %9$s %10$s> %11$s </a>
								</div>
							</div>',
						$image_path,
						__( 'Thank you for installing Astra!', 'astra' ),
						__( 'Did you know Astra comes with dozens of ready-to-use <a href="https://wpastra.com/ready-websites/?utm_source=install-notice">starter templates</a>? Install the Starter Templates plugin to get started.', 'astra' ),
						esc_attr( $ast_sites_notice_btn['class'] ),
						'href="' . astra_get_prop( $ast_sites_notice_btn, 'link', '' ) . '"',
						'data-slug="' . astra_get_prop( $ast_sites_notice_btn, 'data_slug', '' ) . '"',
						'data-init="' . astra_get_prop( $ast_sites_notice_btn, 'data_init', '' ) . '"',
						'data-settings-link-text="' . astra_get_prop( $ast_sites_notice_btn, 'data_settings_link_text', '' ) . '"',
						'data-settings-link="' . astra_get_prop( $ast_sites_notice_btn, 'data_settings_link', '' ) . '"',
						'data-activating-text="' . astra_get_prop( $ast_sites_notice_btn, 'activating_text', '' ) . '"',
						esc_html( $ast_sites_notice_btn['button_text'] )
					),
					'priority'                   => 5,
					'display-with-other-notices' => false,
					'show_if'                    => class_exists( 'Astra_Ext_White_Label_Markup' ) ? Astra_Ext_White_Label_Markup::show_branding() : true,
				);

				Astra_Notices::add_notice(
					$astra_sites_notice_args
				);
			}
		}

		/**
		 * Display notice for minimun version for Astra addon.
		 *
		 * @since 2.0.0
		 */
		public static function minimum_addon_version_notice() {

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				return;
			}

			if ( version_compare( ASTRA_EXT_VER, ASTRA_EXT_MIN_VER ) < 0 ) {

				$message = sprintf(
					/* translators: %1$1s: Theme Name, %2$2s: Minimum Required version of the addon */
					__( 'Please update the %1$1s to version %2$2s or higher. Ignore if already updated.', 'astra' ),
					astra_get_addon_name(),
					ASTRA_EXT_MIN_VER
				);

				$min_version = get_user_meta( get_current_user_id(), 'ast-minimum-addon-version-notice-min-ver', true );

				if ( ! $min_version ) {
					update_user_meta( get_current_user_id(), 'ast-minimum-addon-version-notice-min-ver', ASTRA_EXT_MIN_VER );
				}

				if ( version_compare( $min_version, ASTRA_EXT_MIN_VER, '!=' ) ) {
					delete_user_meta( get_current_user_id(), 'ast-minimum-addon-version-notice' );
					update_user_meta( get_current_user_id(), 'ast-minimum-addon-version-notice-min-ver', ASTRA_EXT_MIN_VER );
				}

				$notice_args = array(
					'id'                         => 'ast-minimum-addon-version-notice',
					'type'                       => 'warning',
					'message'                    => $message,
					'show_if'                    => true,
					'repeat-notice-after'        => false,
					'priority'                   => 18,
					'display-with-other-notices' => true,
				);

				Astra_Notices::add_notice( $notice_args );
			}
		}

		/**
		 * Enqueue Astra Notices CSS.
		 *
		 * @since 2.0.0
		 *
		 * @return void
		 */
		public static function notice_assets() {
			if ( is_rtl() ) {
				wp_enqueue_style( 'astra-notices-rtl', ASTRA_THEME_URI . 'inc/assets/css/astra-notices-rtl.css', array(), ASTRA_THEME_VERSION );
			} else {
				wp_enqueue_style( 'astra-notices', ASTRA_THEME_URI . 'inc/assets/css/astra-notices.css', array(), ASTRA_THEME_VERSION );
			}
		}

		/**
		 * Render button for Astra Site notices
		 *
		 * @since 1.6.5
		 * @return array $ast_sites_notice_btn Rendered button
		 */
		public static function astra_sites_notice_button() {

			$ast_sites_notice_btn = array();

			// Any of the Starter Templtes plugin - Active.
			if ( is_plugin_active( 'astra-pro-sites/astra-pro-sites.php' ) || is_plugin_active( 'astra-sites/astra-sites.php' ) ) {
				$ast_sites_notice_btn['class']       = 'active';
				$ast_sites_notice_btn['button_text'] = __( 'See Library &#187;', 'astra' );
				$ast_sites_notice_btn['link']        = admin_url( 'themes.php?page=' . self::$starter_templates_slug );

				return $ast_sites_notice_btn;
			}

			// Starter Templates PRO Plugin - Installed but Inactive.
			if ( file_exists( WP_PLUGIN_DIR . '/astra-pro-sites/astra-pro-sites.php' ) && is_plugin_inactive( 'astra-pro-sites/astra-pro-sites.php' ) ) {
				$ast_sites_notice_btn['class']                   = 'astra-activate-recommended-plugin';
				$ast_sites_notice_btn['button_text']             = __( 'Activate Importer Plugin', 'astra' );
				$ast_sites_notice_btn['data_slug']               = 'astra-pro-sites';
				$ast_sites_notice_btn['data_init']               = '/astra-pro-sites/astra-pro-sites.php';
				$ast_sites_notice_btn['data_settings_link']      = admin_url( 'themes.php?page=' . self::$starter_templates_slug );
				$ast_sites_notice_btn['data_settings_link_text'] = __( 'See Library &#187;', 'astra' );
				$ast_sites_notice_btn['activating_text']         = __( 'Activating Importer Plugin ', 'astra' ) . '&hellip;';

				return $ast_sites_notice_btn;
			}

			// Starter Templates FREE Plugin - Installed but Inactive.
			if ( file_exists( WP_PLUGIN_DIR . '/astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-sites/astra-sites.php' ) ) {
				$ast_sites_notice_btn['class']                   = 'astra-activate-recommended-plugin';
				$ast_sites_notice_btn['button_text']             = __( 'Activate Importer Plugin', 'astra' );
				$ast_sites_notice_btn['data_slug']               = 'astra-sites';
				$ast_sites_notice_btn['data_init']               = '/astra-sites/astra-sites.php';
				$ast_sites_notice_btn['data_settings_link']      = admin_url( 'themes.php?page=' . self::$starter_templates_slug );
				$ast_sites_notice_btn['data_settings_link_text'] = __( 'See Library &#187;', 'astra' );
				$ast_sites_notice_btn['activating_text']         = __( 'Activating Importer Plugin ', 'astra' ) . '&hellip;';

				return $ast_sites_notice_btn;
			}

			// Any of the Starter Templates plugin not available.
			if ( ! file_exists( WP_PLUGIN_DIR . '/astra-sites/astra-sites.php' ) || ! file_exists( WP_PLUGIN_DIR . '/astra-pro-sites/astra-pro-sites.php' ) ) {
				$ast_sites_notice_btn['class']                   = 'astra-install-recommended-plugin';
				$ast_sites_notice_btn['button_text']             = __( 'Install Importer Plugin', 'astra' );
				$ast_sites_notice_btn['data_slug']               = 'astra-sites';
				$ast_sites_notice_btn['data_init']               = '/astra-sites/astra-sites.php';
				$ast_sites_notice_btn['data_settings_link']      = admin_url( 'themes.php?page=' . self::$starter_templates_slug );
				$ast_sites_notice_btn['data_settings_link_text'] = __( 'See Library &#187;', 'astra' );
				$ast_sites_notice_btn['detail_link_class']       = 'plugin-detail thickbox open-plugin-details-modal astra-starter-sites-detail-link';
				$ast_sites_notice_btn['detail_link']             = network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=astra-sites&TB_iframe=true&width=772&height=400' );
				$ast_sites_notice_btn['detail_link_text']        = __( 'Details &#187;', 'astra' );

				return $ast_sites_notice_btn;
			}

			$ast_sites_notice_btn['class']       = 'active';
			$ast_sites_notice_btn['button_text'] = __( 'See Library &#187;', 'astra' );
			$ast_sites_notice_btn['link']        = admin_url( 'themes.php?page=' . self::$starter_templates_slug );

			return $ast_sites_notice_btn;
		}

		/**
		 * Check if installed Starter Sites plugin is new.
		 *
		 * @since 2.3.2
		 */
		public static function get_starter_templates_slug() {

			if ( defined( 'ASTRA_PRO_SITES_VER' ) && version_compare( ASTRA_PRO_SITES_VER, '2.0.0', '>=' ) ) {
				self::$starter_templates_slug = 'starter-templates';
			}

			if ( defined( 'ASTRA_SITES_VER' ) && version_compare( ASTRA_SITES_VER, '2.0.0', '>=' ) ) {
				self::$starter_templates_slug = 'starter-templates';
			}
		}

		/**
		 * Load the scripts and styles in the customizer controls.
		 *
		 * @since 1.2.1
		 */
		public static function customizer_scripts() {
			$color_palettes = wp_json_encode( astra_color_palette() );
			wp_add_inline_script( 'wp-color-picker', 'jQuery.wp.wpColorPicker.prototype.options.palettes = ' . $color_palettes . ';' );
		}

		/**
		 * Register admin scripts.
		 *
		 * @param String $hook Screen name where the hook is fired.
		 * @return void
		 */
		public static function register_scripts( $hook ) {
			$js_prefix  = '.min.js';
			$css_prefix = '.min.css';
			$dir        = 'minified';
			if ( SCRIPT_DEBUG ) {
				$js_prefix  = '.js';
				$css_prefix = '.css';
				$dir        = 'unminified';
			}

			if ( is_rtl() ) {
				$css_prefix = '-rtl.min.css';
				if ( SCRIPT_DEBUG ) {
					$css_prefix = '-rtl.css';
				}
			}

			if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
				$post_types = get_post_types( array( 'public' => true ) );
				$screen     = get_current_screen();
				$post_type  = $screen->id;

				if ( in_array( $post_type, (array) $post_types ) ) {

					echo '<style class="astra-meta-box-style">
						.block-editor-page #side-sortables #astra_settings_meta_box select { min-width: 84%; padding: 3px 24px 3px 8px; height: 20px; }
						.block-editor-page #normal-sortables #astra_settings_meta_box select { min-width: 200px; }
						.block-editor-page .edit-post-meta-boxes-area #poststuff #astra_settings_meta_box h2.hndle { border-bottom: 0; }
						.block-editor-page #astra_settings_meta_box .components-base-control__field, .block-editor-page #astra_settings_meta_box .block-editor-page .transparent-header-wrapper, .block-editor-page #astra_settings_meta_box .adv-header-wrapper, .block-editor-page #astra_settings_meta_box .stick-header-wrapper, .block-editor-page #astra_settings_meta_box .disable-section-meta div { margin-bottom: 8px; }
						.block-editor-page #astra_settings_meta_box .disable-section-meta div label { vertical-align: inherit; }
						.block-editor-page #astra_settings_meta_box .post-attributes-label-wrapper { margin-bottom: 4px; }
						#side-sortables #astra_settings_meta_box select { min-width: 100%; }
						#normal-sortables #astra_settings_meta_box select { min-width: 200px; }
					</style>';
				}
			}
			/* Add CSS for the Submenu for BSF plugins added in Appearance Menu */

			if ( ! is_customize_preview() ) {

				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				wp_register_script( 'astra-admin-settings', ASTRA_THEME_URI . 'inc/assets/js/astra-admin-menu-settings.js', array( 'jquery', 'wp-util', 'updates' ), ASTRA_THEME_VERSION, false );

				$localize = array(
					'ajaxUrl'                            => admin_url( 'admin-ajax.php' ),
					'btnActivating'                      => __( 'Activating Importer Plugin ', 'astra' ) . '&hellip;',
					'astraSitesLink'                     => admin_url( 'themes.php?page=' ),
					'astraSitesLinkTitle'                => __( 'See Library &#187;', 'astra' ),
					'recommendedPluiginActivatingText'   => __( 'Activating', 'astra' ) . '&hellip;',
					'recommendedPluiginDeactivatingText' => __( 'Deactivating', 'astra' ) . '&hellip;',
					'recommendedPluiginActivateText'     => __( 'Activate', 'astra' ),
					'recommendedPluiginDeactivateText'   => __( 'Deactivate', 'astra' ),
					'recommendedPluiginSettingsText'     => __( 'Settings', 'astra' ),
					'astraPluginManagerNonce'            => wp_create_nonce( 'astra-recommended-plugin-nonce' ),
				);

				wp_localize_script( 'astra-admin-settings', 'astra', apply_filters( 'astra_theme_js_localize', $localize ) );
			}
		}

		/**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 1.0
		 */
		public static function styles_scripts() {

			// Styles.
			if ( is_rtl() ) {
				wp_enqueue_style( 'astra-admin-settings-rtl', ASTRA_THEME_URI . 'inc/assets/css/astra-admin-menu-settings-rtl.css', array(), ASTRA_THEME_VERSION );
			} else {
				wp_enqueue_style( 'astra-admin-settings', ASTRA_THEME_URI . 'inc/assets/css/astra-admin-menu-settings.css', array(), ASTRA_THEME_VERSION );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			wp_register_script( 'astra-admin-settings', ASTRA_THEME_URI . 'inc/assets/js/astra-admin-menu-settings.js', array( 'jquery', 'wp-util', 'updates' ), ASTRA_THEME_VERSION, false );

			$localize = array(
				'ajaxUrl'                            => admin_url( 'admin-ajax.php' ),
				'btnActivating'                      => __( 'Activating Importer Plugin ', 'astra' ) . '&hellip;',
				'astraSitesLink'                     => admin_url( 'themes.php?page=' ),
				'astraSitesLinkTitle'                => __( 'See Library &#187;', 'astra' ),
				'recommendedPluiginActivatingText'   => __( 'Activating', 'astra' ) . '&hellip;',
				'recommendedPluiginDeactivatingText' => __( 'Deactivating', 'astra' ) . '&hellip;',
				'recommendedPluiginActivateText'     => __( 'Activate', 'astra' ),
				'recommendedPluiginDeactivateText'   => __( 'Deactivate', 'astra' ),
				'recommendedPluiginSettingsText'     => __( 'Settings', 'astra' ),
				'astraPluginManagerNonce'            => wp_create_nonce( 'astra-recommended-plugin-nonce' ),
				'ajax_nonce'                         => wp_create_nonce( 'astra-builder-module-nonce' ),
				'old_header_footer'                  => __( 'Use Old Header/Footer', 'astra' ),
				'migrate_to_builder'                 => __( 'Use New Header/Footer Builder', 'astra' ),
			);
			wp_localize_script( 'astra-admin-settings', 'astra', apply_filters( 'astra_theme_js_localize', $localize ) );

			// Script.
			wp_enqueue_script( 'astra-admin-settings' );

			if ( ! file_exists( WP_PLUGIN_DIR . '/astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-pro-sites/astra-pro-sites.php' ) ) {
				// For starter site plugin popup detail "Details &#187;" on Astra Options page.
				wp_enqueue_script( 'plugin-install' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );
			}
		}


		/**
		 * Get and return page URL
		 *
		 * @param string $menu_slug Menu name.
		 * @since 1.0
		 * @return  string page url
		 */
		public static function get_page_url( $menu_slug ) {

			$parent_page = self::$default_menu_position;

			if ( strpos( $parent_page, '?' ) !== false ) {
				$query_var = '&page=' . self::$plugin_slug;
			} else {
				$query_var = '?page=' . self::$plugin_slug;
			}

			$parent_page_url = admin_url( $parent_page . $query_var );

			$url = $parent_page_url . '&action=' . $menu_slug;

			return esc_url( $url );
		}

		/**
		 * Add main menu
		 *
		 * @since 1.0
		 */
		public static function add_admin_menu() {

			$parent_page    = self::$default_menu_position;
			$page_title     = self::$menu_page_title;
			$capability     = 'manage_options';
			$page_menu_slug = self::$plugin_slug;
			$page_menu_func = __CLASS__ . '::menu_callback';

			if ( apply_filters( 'astra_dashboard_admin_menu', true ) ) {
				add_theme_page( $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func );
			} else {
				do_action( 'asta_register_admin_menu', $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func );
			}
		}

		/**
		 * Menu callback
		 *
		 * @since 1.0
		 */
		public static function menu_callback() {

			$current_slug = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : self::$current_slug; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$active_tab   = str_replace( '_', '-', $current_slug );
			$current_slug = str_replace( '-', '_', $current_slug );

			$ast_icon           = apply_filters( 'astra_page_top_icon', true );
			$ast_visit_site_url = apply_filters( 'astra_site_url', 'https://wpastra.com' );
			$ast_wrapper_class  = apply_filters( 'astra_welcome_wrapper_class', array( $current_slug ) );

			?>
			<div class="ast-menu-page-wrapper wrap ast-clear <?php echo esc_attr( implode( ' ', $ast_wrapper_class ) ); ?>">
					<div class="ast-theme-page-header">
						<div class="ast-container ast-flex">
							<div class="ast-theme-title">
								<a href="<?php echo esc_url( $ast_visit_site_url ); ?>" target="_blank" rel="noopener" >
								<?php if ( $ast_icon ) { ?>
									<img src="<?php echo esc_url( ASTRA_THEME_URI . 'inc/assets/images/astra.svg' ); ?>" class="ast-theme-icon" alt="<?php echo esc_attr( self::$page_title ); ?> " >
									<span class="astra-theme-version"><?php echo esc_html( ASTRA_THEME_VERSION ); ?></span>
								<?php } ?>
								<?php do_action( 'astra_welcome_page_header_title' ); ?>
								</a>
							</div>

							<?php do_action( 'astra_header_right_section' ); ?>

						</div>
					</div>

				<?php do_action( 'astra_menu_' . esc_attr( $current_slug ) . '_action' ); ?>
			</div>
			<?php
		}

		/**
		 * Include general page
		 *
		 * @since 1.0
		 */
		public static function general_page() {
			require_once ASTRA_THEME_DIR . 'inc/core/view-general.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}

		/**
		 * Include Welcome page right starter sites content
		 *
		 * @since 1.2.4
		 */
		public static function astra_welcome_page_starter_sites_section() {

			if ( astra_is_white_labelled() ) {
				return;
			}
			?>

			<div class="postbox">
				<h2 class="hndle ast-normal-cusror">
					<span class="dashicons dashicons-admin-customizer"></span>
					<span><?php echo esc_html( apply_filters( 'astra_sites_menu_page_title', __( 'Import Starter Template', 'astra' ) ) ); ?></span>
				</h2>
				<img class="ast-starter-sites-img" src="<?php echo esc_url( ASTRA_THEME_URI . 'assets/images/astra-starter-sites.jpg' ); ?>">
				<div class="inside">
					<p>
						<?php
							$astra_starter_sites_doc_link      = apply_filters( 'astra_starter_sites_documentation_link', astra_get_pro_url( 'https://wpastra.com/docs/installing-importing-astra-sites', 'astra-dashboard', 'how-astra-sites-works', 'welcome-page' ) );
							$astra_starter_sites_doc_link_text = apply_filters( 'astra_starter_sites_doc_link_text', __( 'Starter Templates?', 'astra' ) );
							printf(
								/* translators: %1$s: Starter site link. */
								esc_html__( 'Did you know %1$s offers a free library of %2$s ', 'astra' ),
								self::$page_title,
								! empty( $astra_starter_sites_doc_link ) ? '<a href=' . esc_url( $astra_starter_sites_doc_link ) . ' target="_blank" rel="noopener">' . esc_html( $astra_starter_sites_doc_link_text ) . '</a>' :
								esc_html( $astra_starter_sites_doc_link_text )
							);
						?>
					</p>
					<p>
						<?php
							esc_html_e( 'Import your favorite site in one click and start your project with style!', 'astra' );
						?>
					</p>
						<?php
						$ast_sites_notice_btn = self::astra_sites_notice_button();

						printf(
							'<a class="%1$s" %2$s %3$s %4$s %5$s %6$s %7$s> %8$s </a>',
							esc_attr( $ast_sites_notice_btn['class'] ),
							'href="' . esc_url( astra_get_prop( $ast_sites_notice_btn, 'link', '' ) ) . '"',
							'data-slug="' . esc_attr( astra_get_prop( $ast_sites_notice_btn, 'data_slug', '' ) ) . '"',
							'data-init="' . esc_attr( astra_get_prop( $ast_sites_notice_btn, 'data_init', '' ) ) . '"',
							'data-settings-link-text="' . esc_attr( astra_get_prop( $ast_sites_notice_btn, 'data_settings_link_text', '' ) ) . '"',
							'data-settings-link="' . esc_attr( astra_get_prop( $ast_sites_notice_btn, 'data_settings_link', '' ) ) . '"',
							'data-activating-text="' . esc_attr( astra_get_prop( $ast_sites_notice_btn, 'activating_text', '' ) ) . '"',
							esc_html( $ast_sites_notice_btn['button_text'] )
						);
						printf(
							'<a class="%1$s" %2$s target="_blank" rel="noopener"> %3$s </a>',
							isset( $ast_sites_notice_btn['detail_link_class'] ) ? esc_attr( $ast_sites_notice_btn['detail_link_class'] ) : '',
							isset( $ast_sites_notice_btn['detail_link'] ) ? 'href="' . esc_url( $ast_sites_notice_btn['detail_link'] ) . '"' : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							isset( $ast_sites_notice_btn['detail_link_class'] ) ? esc_html( $ast_sites_notice_btn['detail_link_text'] ) : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						);
						?>
					<div>
					</div>
				</div>
			</div>

			<?php
		}

		/**
		 * Include Welcome page right side knowledge base content
		 *
		 * @since 1.2.4
		 */
		public static function astra_welcome_page_knowledge_base_scetion() {

			if ( astra_is_white_labelled() ) {
				return;
			}

			?>

			<div class="postbox">
				<h2 class="hndle ast-normal-cusror">
					<span class="dashicons dashicons-book"></span>
					<span><?php esc_html_e( 'Knowledge Base', 'astra' ); ?></span>
				</h2>
				<div class="inside">
					<p>
						<?php esc_html_e( 'Not sure how something works? Take a peek at the knowledge base and learn.', 'astra' ); ?>
					</p>
					<?php
					$astra_knowledge_base_doc_link      = apply_filters( 'astra_knowledge_base_documentation_link', astra_get_pro_url( 'https://wpastra.com/docs/', 'astra-dashboard', 'visit-documentation', 'welcome-page' ) );
					$astra_knowledge_base_doc_link_text = apply_filters( 'astra_knowledge_base_documentation_link_text', __( 'Visit Knowledge Base &#187;', 'astra' ) );

					printf(
						/* translators: %1$s: Astra Knowledge doc link. */
						'%1$s',
						! empty( $astra_knowledge_base_doc_link ) ? '<a href=' . esc_url( $astra_knowledge_base_doc_link ) . ' target="_blank" rel="noopener">' . esc_html( $astra_knowledge_base_doc_link_text ) . '</a>' :
						esc_html( $astra_knowledge_base_doc_link_text )
					);
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Include Welcome page right side Astra community content
		 *
		 * @since 1.2.4
		 */
		public static function astra_welcome_page_community_scetion() {

			if ( astra_is_white_labelled() ) {
				return;
			}

			?>

			<div class="postbox">
				<h2 class="hndle ast-normal-cusror">
					<span class="dashicons dashicons-groups"></span>
					<span>
						<?php
						printf(
							/* translators: %1$s: Astra Theme name. */
							esc_html__( '%1$s Community', 'astra' ),
							esc_html( self::$page_title )
						);
						?>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							/* translators: %1$s: Astra Theme name. */
							esc_html__( 'Join the community of super helpful %1$s users. Say hello, ask questions, give feedback and help each other!', 'astra' ),
							esc_html( self::$page_title )
						);
						?>
					</p>
					<?php
					$astra_community_group_link      = apply_filters( 'astra_community_group_link', 'https://www.facebook.com/groups/wpastra' );
					$astra_community_group_link_text = apply_filters( 'astra_community_group_link_text', __( 'Join Our Facebook Group &#187;', 'astra' ) );

					printf(
						/* translators: %1$s: Astra Knowledge doc link. */
						'%1$s',
						! empty( $astra_community_group_link ) ? '<a href=' . esc_url( $astra_community_group_link ) . ' target="_blank" rel="noopener">' . esc_html( $astra_community_group_link_text ) . '</a>' :
						esc_html( $astra_community_group_link_text )
					);
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Include Welcome page right side Five Star Support
		 *
		 * @since 1.2.4
		 */
		public static function astra_welcome_page_five_star_scetion() {

			if ( astra_is_white_labelled() ) {
				return;
			}

			?>

			<div class="postbox">
				<h2 class="hndle ast-normal-cusror">
					<span class="dashicons dashicons-sos"></span>
					<span><?php esc_html_e( 'Five Star Support', 'astra' ); ?></span>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							/* translators: %1$s: Astra Theme name. */
							esc_html__( 'Got a question? Get in touch with %1$s developers. We\'re happy to help!', 'astra' ),
							esc_html( self::$page_title )
						);
						?>
					</p>
					<?php
						$astra_support_link      = apply_filters( 'astra_support_link', astra_get_pro_url( 'https://wpastra.com/contact/', 'astra-dashboard', 'submit-a-ticket', 'welcome-page' ) );
						$astra_support_link_text = apply_filters( 'astra_support_link_text', __( 'Submit a Ticket &#187;', 'astra' ) );

						printf(
							/* translators: %1$s: Astra Knowledge doc link. */
							'%1$s',
							! empty( $astra_support_link ) ? '<a href=' . esc_url( $astra_support_link ) . ' target="_blank" rel="noopener">' . esc_html( $astra_support_link_text ) . '</a>' :
							esc_html( $astra_support_link_text )
						);
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Include Welcome page content
		 *
		 * @since 1.2.4
		 */
		public static function astra_welcome_page_content() {

			$astra_addon_tagline = apply_filters( 'astra_addon_list_tagline', __( 'More Options Available with Astra Pro!', 'astra' ) );

			// Quick settings.
			$quick_settings = apply_filters(
				'astra_quick_settings',
				array(
					'header'       => array(
						'title'     => __( 'Header Options', 'astra' ),
						'dashicon'  => 'dashicons-align-center',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=panel-header-group' ),
					),
					'logo-favicon' => array(
						'title'     => __( 'Upload Logo', 'astra' ),
						'dashicon'  => 'dashicons-format-image',
						'quick_url' => admin_url( 'customize.php?autofocus[control]=custom_logo' ),
					),
					'footer'       => array(
						'title'     => __( 'Footer Settings', 'astra' ),
						'dashicon'  => 'dashicons-align-wide',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-footer-group' ),
					),
					'colors'       => array(
						'title'     => __( 'Set Colors', 'astra' ),
						'dashicon'  => 'dashicons-admin-customizer',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-colors-background' ),
					),
					'layout'       => array(
						'title'     => __( 'Layout Options', 'astra' ),
						'dashicon'  => 'dashicons-layout',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-container-layout' ),
					),
					'typography'   => array(
						'title'     => __( 'Customize Fonts', 'astra' ),
						'dashicon'  => 'dashicons-editor-textcolor',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-typography' ),
					),
					'blog-layout'  => array(
						'title'     => __( 'Blog Layouts', 'astra' ),
						'dashicon'  => 'dashicons-welcome-write-blog',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-blog-group' ),
					),
					'sidebars'     => array(
						'title'     => __( 'Sidebar Options', 'astra' ),
						'dashicon'  => 'dashicons-align-left',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-sidebars' ),
					),
				)
			);

			$extensions = apply_filters(
				'astra_addon_list',
				array(
					'colors-and-background' => array(
						'title'     => __( 'Colors & Background', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/colors-background-module/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/colors-background-module/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'typography'            => array(
						'title'     => __( 'Typography', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'spacing'               => array(
						'title'     => __( 'Spacing', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/spacing-addon-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/spacing-addon-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'blog-pro'              => array(
						'title'     => __( 'Blog Pro', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/blog-pro-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/blog-pro-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'mobile-header'         => array(
						'title'     => __( 'Mobile Header', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/mobile-header-with-astra/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/mobile-header-with-astra/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'header-sections'       => array(
						'title'     => __( 'Header Sections', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/header-sections-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/header-sections-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'nav-menu'              => array(
						'title'     => __( 'Nav Menu', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/nav-menu-addon/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/nav-menu-addon/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'sticky-header'         => array(
						'title'     => __( 'Sticky Header', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/sticky-header-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/sticky-header-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'advanced-headers'      => array(
						'title'           => __( 'Page Headers', 'astra' ),
						'description'     => __( 'Make your header layouts look more appealing and sexy!', 'astra' ),
						'manage_settings' => true,
						'class'           => 'ast-addon',
						'title_url'       => astra_get_pro_url( 'https://wpastra.com/docs/page-headers-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'           => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/page-headers-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'advanced-hooks'        => array(
						'title'           => __( 'Custom Layouts', 'astra' ),
						// 'icon'            => ASTRA_THEME_URI . 'assets/img/astra-advanced-hooks.png',
						'description'     => __( 'Add content conditionally in the various hook areas of the theme.', 'astra' ),
						'manage_settings' => true,
						'class'           => 'ast-addon',
						'title_url'       => astra_get_pro_url( 'https://wpastra.com/docs/custom-layouts-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'           => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/custom-layouts-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'site-layouts'          => array(
						'title'     => __( 'Site Layouts', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/site-layout-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/site-layout-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'advanced-footer'       => array(
						'title'     => __( 'Footer Widgets', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/footer-widgets-astra-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/footer-widgets-astra-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'scroll-to-top'         => array(
						'title'     => __( 'Scroll To Top', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/scroll-to-top-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/scroll-to-top-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'woocommerce'           => array(
						'title'     => __( 'WooCommerce', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/woocommerce-module-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/woocommerce-module-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'edd'                   => array(
						'title'     => __( 'Easy Digital Downloads', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/easy-digital-downloads-module-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/easy-digital-downloads-module-overview/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'learndash'             => array(
						'title'       => __( 'LearnDash', 'astra' ),
						'description' => __( 'Supercharge your LearnDash website with amazing design features.', 'astra' ),
						'class'       => 'ast-addon',
						'title_url'   => astra_get_pro_url( 'https://wpastra.com/docs/learndash-integration-in-astra-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'       => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/learndash-integration-in-astra-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'lifterlms'             => array(
						'title'     => __( 'LifterLMS', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/lifterlms-module-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/lifterlms-module-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'white-label'           => array(
						'title'     => __( 'White Label', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/how-to-white-label-astra/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/how-to-white-label-astra/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More &#187;', 'astra' ),
								'target_blank' => true,
							),
						),
					),
				)
			);
			?>
			<div class="postbox">
				<h2 class="hndle ast-normal-cusror"><span><?php esc_html_e( 'Links to Customizer Settings:', 'astra' ); ?></span></h2>
					<div class="ast-quick-setting-section">
						<?php
						if ( ! empty( $quick_settings ) ) :
							?>
							<div class="ast-quick-links">
								<ul class="ast-flex">
									<?php
									foreach ( (array) $quick_settings as $key => $link ) {
										echo '<li class="' . esc_attr( $key ) . '"><span class="dashicons ' . esc_attr( $link['dashicon'] ) . '"></span><a class="ast-quick-setting-title" href="' . esc_url( $link['quick_url'] ) . '" target="_blank" rel="noopener">' . esc_html( $link['title'] ) . '</a></li>';
									}
									?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
			</div>

			<div class="postbox">
				<h2 class="hndle ast-normal-cusror ast-addon-heading ast-flex"><span><?php echo esc_html( $astra_addon_tagline ); ?></span>
					<?php do_action( 'astra_addon_bulk_action' ); ?>
				</h2>
					<div class="ast-addon-list-section">
						<?php
						if ( ! empty( $extensions ) ) :
							?>
							<div>
								<ul class="ast-addon-list">
									<?php
									foreach ( (array) $extensions as $addon => $info ) {
										$title_url     = ( isset( $info['title_url'] ) && ! empty( $info['title_url'] ) ) ? 'href="' . esc_url( $info['title_url'] ) . '"' : '';
										$anchor_target = ( isset( $info['title_url'] ) && ! empty( $info['title_url'] ) ) ? "target='_blank' rel='noopener'" : '';

										echo '<li id="' . esc_attr( $addon ) . '"  class="' . esc_attr( $info['class'] ) . '"><a class="ast-addon-title"' . $title_url . $anchor_target . ' >' . esc_html( $info['title'] ) . '</a><div class="ast-addon-link-wrapper">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

										foreach ( $info['links'] as $key => $link ) {
											printf(
												'<a class="%1$s" %2$s %3$s> %4$s </a>',
												esc_attr( $link['link_class'] ),
												isset( $link['link_url'] ) ? 'href="' . esc_url( $link['link_url'] ) . '"' : '',
												( isset( $link['target_blank'] ) && $link['target_blank'] ) ? 'target="_blank" rel="noopener"' : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												esc_html( $link['link_text'] )
											);
										}
										echo '</div></li>';
									}
									?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
			</div>

			<?php
		}

		/**
		 * Include Welcome page content
		 *
		 * @since 1.2.4
		 */
		public static function astra_available_plugins() {

			// Return if white labeled.
			if ( astra_is_white_labelled() ) {
				return;
			}

			$astra_addon_tagline = apply_filters(
				'astra_available_plugins',
				sprintf(
					/* translators: %1s Astra Theme */
					__( 'Extend %1s with free plugins!', 'astra' ),
					astra_get_theme_name()
				)
			);

			$recommended_plugins = apply_filters(
				'astra_recommended_plugins',
				array(
					'astra-import-export'           =>
						array(
							'plugin-name'        => __( 'Import / Export Customizer Settings', 'astra' ),
							'plugin-init'        => 'astra-import-export/astra-import-export.php',
							'settings-link'      => '',
							'settings-link-text' => 'Settings',
						),
					'reset-astra-customizer'        =>
						array(
							'plugin-name'        => __( 'Astra Customizer Reset', 'astra' ),
							'plugin-init'        => 'reset-astra-customizer/class-astra-theme-customizer-reset.php',
							'settings-link'      => admin_url( 'customize.php' ),
							'settings-link-text' => 'Settings',
						),

					'customizer-search'             =>
					array(
						'plugin-name'        => __( 'Customizer Search', 'astra' ),
						'plugin-init'        => 'customizer-search/customizer-search.php',
						'settings-link'      => admin_url( 'customize.php' ),
						'settings-link-text' => 'Settings',
					),

					'astra-bulk-edit'               =>
					array(
						'plugin-name'        => __( 'Astra Bulk Edit', 'astra' ),
						'plugin-init'        => 'astra-bulk-edit/astra-bulk-edit.php',
						'settings-link'      => '',
						'settings-link-text' => 'Settings',
					),

					'astra-widgets'                 =>
					array(
						'plugin-name'        => __( 'Astra Widgets', 'astra' ),
						'plugin-init'        => 'astra-widgets/astra-widgets.php',
						'settings-link'      => admin_url( 'widgets.php' ),
						'settings-link-text' => 'Settings',
					),

					'custom-fonts'                  =>
					array(
						'plugin-name'        => __( 'Custom Fonts', 'astra' ),
						'plugin-init'        => 'custom-fonts/custom-fonts.php',
						'settings-link'      => admin_url( 'edit-tags.php?taxonomy=bsf_custom_fonts' ),
						'settings-link-text' => 'Settings',
					),

					'custom-typekit-fonts'          =>
						array(
							'plugin-name'        => __( 'Custom Typekit Fonts', 'astra' ),
							'plugin-init'        => 'custom-typekit-fonts/custom-typekit-fonts.php',
							'settings-link'      => admin_url( 'themes.php?page=custom-typekit-fonts' ),
							'settings-link-text' => 'Settings',
						),

					'sidebar-manager'               =>
					array(
						'plugin-name'        => __( 'Sidebar Manager', 'astra' ),
						'plugin-init'        => 'sidebar-manager/sidebar-manager.php',
						'settings-link'      => admin_url( 'edit.php?post_type=bsf-sidebar' ),
						'settings-link-text' => 'Settings',
					),

					'ultimate-addons-for-gutenberg' =>
						array(
							'plugin-name'        => __( 'Ultimate Addons for Gutenberg', 'astra' ),
							'plugin-init'        => 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php',
							'settings-link'      => admin_url( 'options-general.php?page=uag' ),
							'settings-link-text' => 'Settings',
							'display'            => function_exists( 'register_block_type' ),
						),
				)
			);

			if ( apply_filters( 'astra_show_free_extend_plugins', true ) ) {
				?>

				<div class="postbox">
					<h2 class="hndle ast-normal-cusror ast-addon-heading ast-flex"><span><?php echo esc_html( $astra_addon_tagline ); ?></span>
					</h2>
						<div class="ast-addon-list-section">
							<?php
							if ( ! empty( $recommended_plugins ) ) :
								?>
								<div>
									<ul class="ast-addon-list">
										<?php
										foreach ( $recommended_plugins as $slug => $plugin ) {

											// If display condition for the plugin does not meet, skip the plugin from displaying.
											if ( isset( $plugin['display'] ) && false === $plugin['display'] ) {
												continue;
											}

											$plugin_active_status = '';
											if ( is_plugin_active( $plugin['plugin-init'] ) ) {
												$plugin_active_status = ' active';
											}

											echo '<li ' . astra_attr(
												'astra-recommended-plugin-' . esc_attr( $slug ),
												array(
													'id' => esc_attr( $slug ),
													'class' => 'astra-recommended-plugin' . $plugin_active_status,
													'data-slug' => $slug,
												)
											) . '>';

												echo '<a href="' . esc_url( self::build_worg_plugin_link( $slug ) ) . '" target="_blank">';
													echo esc_html( $plugin['plugin-name'] );
												echo '</a>';

												echo '<div class="ast-addon-link-wrapper">';

											if ( ! is_plugin_active( $plugin['plugin-init'] ) ) {

												if ( file_exists( WP_CONTENT_DIR . '/plugins/' . $plugin['plugin-init'] ) ) {
													echo '<a ' . astra_attr(
														'astra-activate-recommended-plugin',
														array(
															'data-slug' => $slug,
															'href' => '#',
															'data-init' => $plugin['plugin-init'],
															'data-settings-link' => esc_url( $plugin['settings-link'] ),
															'data-settings-link-text' => $plugin['settings-link-text'],
														)
													) . '>';

													esc_html_e( 'Activate', 'astra' );

													echo '</a>';

												} else {

													echo '<a ' . astra_attr(
														'astra-install-recommended-plugin',
														array(
															'data-slug' => $slug,
															'href' => '#',
															'data-init' => $plugin['plugin-init'],
															'data-settings-link' => esc_url( $plugin['settings-link'] ),
															'data-settings-link-text' => $plugin['settings-link-text'],
														)
													) . '>';

													esc_html_e( 'Activate', 'astra' );

													echo '</a>';
												}
											} else {

												echo '<a ' . astra_attr(
													'astra-deactivate-recommended-plugin',
													array(
														'data-slug' => $slug,
														'href' => '#',
														'data-init' => $plugin['plugin-init'],
														'data-settings-link' => esc_url( $plugin['settings-link'] ),
														'data-settings-link-text' => $plugin['settings-link-text'],
													)
												) . '>';

												esc_html_e( 'Deactivate', 'astra' );

												echo '</a>';

												if ( '' !== $plugin['settings-link'] ) {

													echo '<a ' . astra_attr(
														'astra-recommended-plugin-links',
														array(
															'data-slug' => $slug,
															'href' => $plugin['settings-link'],
														)
													) . '>';

													echo esc_html( $plugin['settings-link-text'] );

													echo '</a>';
												}
											}

												echo '</div>';

											echo '</li>';
										}
										?>
									</ul>
								</div>
								<?php endif; ?>
						</div>
				</div>

				<?php
			}

		}

		/**
		 * Build plugin's page URL on WordPress.org
		 * https://wordpress.org/plugins/{plugin-slug}
		 *
		 * @since 1.6.9
		 * @param String $slug plugin slug.
		 * @return String Plugin URL on WordPress.org
		 */
		private static function build_worg_plugin_link( $slug ) {
			return esc_url( trailingslashit( 'https://wordpress.org/plugins/' . $slug ) );
		}

		/**
		 * Required Plugin Activate
		 *
		 * @since 1.2.4
		 */
		public static function required_plugin_activate() {

			$nonce = ( isset( $_POST['nonce'] ) ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( false === wp_verify_nonce( $nonce, 'astra-recommended-plugin-nonce' ) ) {
				wp_send_json_error( esc_html_e( 'WordPress Nonce not validated.', 'astra' ) );
			}

			if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! sanitize_text_field( wp_unslash( $_POST['init'] ) ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => __( 'No plugin specified', 'astra' ),
					)
				);
			}

			$plugin_init = ( isset( $_POST['init'] ) ) ? sanitize_text_field( wp_unslash( $_POST['init'] ) ) : '';

			$activate = activate_plugin( $plugin_init, '', false, true );

			if ( '/astra-pro-sites/astra-pro-sites.php' === $plugin_init || '/astra-sites/astra-sites.php' === $plugin_init ) {
				self::get_starter_templates_slug();
			}

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error(
					array(
						'success'               => false,
						'message'               => $activate->get_error_message(),
						'starter_template_slug' => self::$starter_templates_slug,
					)
				);
			}

			wp_send_json_success(
				array(
					'success'               => true,
					'message'               => __( 'Plugin Successfully Activated', 'astra' ),
					'starter_template_slug' => self::$starter_templates_slug,
				)
			);
		}

		/**
		 * Required Plugin Activate
		 *
		 * @since 1.2.4
		 */
		public static function required_plugin_deactivate() {

			$nonce = ( isset( $_POST['nonce'] ) ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( false === wp_verify_nonce( $nonce, 'astra-recommended-plugin-nonce' ) ) {
				wp_send_json_error( esc_html_e( 'WordPress Nonce not validated.', 'astra' ) );
			}

			if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! sanitize_text_field( wp_unslash( $_POST['init'] ) ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => __( 'No plugin specified', 'astra' ),
					)
				);
			}

			$plugin_init = ( isset( $_POST['init'] ) ) ? sanitize_text_field( wp_unslash( $_POST['init'] ) ) : '';

			$deactivate = deactivate_plugins( $plugin_init, '', false );

			if ( is_wp_error( $deactivate ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $deactivate->get_error_message(),
					)
				);
			}

			wp_send_json_success(
				array(
					'success' => true,
					'message' => __( 'Plugin Successfully Deactivated', 'astra' ),
				)
			);

		}

		/**
		 * Astra Header Right Section Links
		 *
		 * @since 1.2.4
		 */
		public static function top_header_right_section() {

			$allowed_html = array(
				'svg'  => array(
					'width'   => array(),
					'height'  => array(),
					'xmlns'   => array(),
					'viewBox' => array(),
				),
				'path' => array(
					'fill' => array(),
					'd'    => array(),
				),
			);

			$top_links = apply_filters(
				'astra_header_top_links',
				array(
					'astra-theme-info' => array(
						'title' => '<img src=" ' . ASTRA_THEME_URI . 'inc/assets/images/lightning-speed.svg" class="astra-lightning-icon" alt="Astra Lightning Speed">' . __( ' Lightning Fast & Fully Customizable WordPress theme!', 'astra' ),
					),
				)
			);

			if ( ! empty( $top_links ) ) {
				?>
				<div class="ast-top-links">
					<ul>
						<?php
						foreach ( (array) $top_links as $key => $info ) {
							/* translators: %1$s: Top Link URL wrapper, %2$s: Top Link URL, %3$s: Top Link URL target attribute */
							printf(
								'<li><%1$s %2$s %3$s > %4$s </%1$s>',
								isset( $info['url'] ) ? 'a' : 'span',
								isset( $info['url'] ) ? 'href="' . esc_url( $info['url'] ) . '"' : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								isset( $info['url'] ) ? 'target="_blank" rel="noopener"' : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$info['title']// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							);
						}
						?>
						</ul>
					</div>
				<?php
			}
		}

		/**
		 * Add custom CSS for admin area sub menu icons.
		 *
		 * @since 2.5.4
		 */
		public static function admin_submenu_css() {

			echo '<style class="astra-menu-appearance-style">
					#menu-appearance a[href^="edit.php?post_type=astra-"]:before,
					#menu-appearance a[href^="themes.php?page=astra-"]:before,
					#menu-appearance a[href^="edit.php?post_type=astra_"]:before,
					#menu-appearance a[href^="edit-tags.php?taxonomy=bsf_custom_fonts"]:before,
					#menu-appearance a[href^="themes.php?page=custom-typekit-fonts"]:before,
					#menu-appearance a[href^="edit.php?post_type=bsf-sidebar"]:before {
						content: "\21B3";
						margin-right: 0.5em;
						opacity: 0.5;
					}
				</style>';

		}
	}

	new Astra_Admin_Settings();
}
