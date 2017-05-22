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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AST_Admin_Settings' ) ) {

	/**
	 * Astra Admin Settings
	 */
	class AST_Admin_Settings {

		/**
		 * View all actions
		 *
		 * @since 1.0
		 * @var array $view_actions
		 */
		static public $view_actions = array();

		/**
		 * Menu page title
		 *
		 * @since 1.0
		 * @var array $menu_page_title
		 */
		static public $menu_page_title = 'Astra';

		/**
		 * Plugin slug
		 *
		 * @since 1.0
		 * @var array $plugin_slug
		 */
		static public $plugin_slug = 'astra';

		/**
		 * Is Top Level page
		 *
		 * @since 1.0
		 * @var array $is_top_level_page
		 */
		static public $is_top_level_page = false;

		/**
		 * Is Multisite active
		 *
		 * @since 1.0
		 * @var array $is_multisite_active
		 */
		static public $is_multisite_active = false;

		/**
		 * Is Network Admin active
		 *
		 * @since 1.0
		 * @var array $network_admin_active
		 */
		static public $network_admin_active = false;

		/**
		 * Default Menu position
		 *
		 * @since 1.0
		 * @var array $default_menu_position
		 */
		static public $default_menu_position = 'themes.php';

		/**
		 * Parent Page Slug
		 *
		 * @since 1.0
		 * @var array $parent_page_slug
		 */
		static public $parent_page_slug = 'general';

		/**
		 * Current Slug
		 *
		 * @since 1.0
		 * @var array $current_slug
		 */
		static public $current_slug = 'general';

		/**
		 * Constructor
		 */
		function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_setup_theme', __CLASS__ . '::init_admin_settings', 99 );
		}

		/**
		 * Admin settings init
		 */
		static public function init_admin_settings() {
			self::$menu_page_title	= apply_filters( 'ast_menu_page_title', __( 'Astra' , 'astra' ) );

			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], self::$plugin_slug ) !== false ) {

				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );

				// Let extensions hook into saving.
				do_action( 'ast_admin_settings_scripts' );

				self::save_settings();
			}

			add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_scripts' );

			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );

			if ( is_multisite() ) {

				self::$is_multisite_active   = true;

				self::$default_menu_position = 'themes.php';

				if ( is_network_admin() ) {
					self::$network_admin_active   = true;
					self::$default_menu_position = 'top';
				}

				add_action( 'network_admin_menu', __CLASS__ . '::add_admin_menu', 99 );
			}

			add_action( 'ast_menu_general_action', __CLASS__ . '::general_page' );
		}

		/**
		 * View actions
		 */
		static public function get_view_actions() {

			if ( empty( self::$view_actions ) ) {

				$actions = array(
					'general'          => array(
											'label'	=> __( 'General Settings', 'astra' ),
											'show'	=> ! is_network_admin(),
										),
				);
				self::$view_actions = apply_filters( 'ast_menu_options', $actions );
			}

			return self::$view_actions;
		}

		/**
		 * Save All admin settings here
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Let extensions hook into saving.
			do_action( 'ast_admin_settings_save' );
		}

		/**
		 * Enqueues the needed CSS/JS for Backend.
		 *
		 * @since 1.0
		 */
		static public function admin_scripts() {

			// Styles.
			wp_enqueue_style( 'ast-admin', AST_THEME_URI . 'inc/assets/css/ast-admin.css', array(), AST_THEME_VERSION );
		}

		/**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 1.0
		 */
		static public function styles_scripts() {

			// Styles.
			wp_enqueue_style( 'ast-admin-settings', AST_THEME_URI . 'inc/assets/css/ast-admin-menu-settings.css', array(), AST_THEME_VERSION );
		}

		/**
		 * Init Nav Menu
		 *
		 * @param mixed $action Action name.
		 * @since 1.0
		 */
		static public function init_nav_menu( $action = '' ) {

			$settings = Ast_Admin_Helper::get_admin_settings_option( '_ast_ext_white_label' );

			// Menu position.
			$position = isset( $settings['menu_position'] ) ? $settings['menu_position'] : false;

			if ( $position ) {
				self::$default_menu_position = $position;
			}

			self::$is_top_level_page = in_array( self::$default_menu_position, array( 'top', 'middle', 'bottom' ), true );

			if ( '' !== $action ) {
				self::render_tab_menu( $action );
			}
		}

		/**
		 * Render tab menu
		 *
		 * @param mixed $action Action name.
		 * @since 1.0
		 */
		static public function render_tab_menu( $action = '' ) {
			echo '<div id="ast-menu-page" class="wrap">';
			self::render( $action );
			echo '</div>';
		}

		/**
		 * Prints HTML content for tabs
		 *
		 * @param mixed $action Action name.
		 * @since 1.0
		 */
		static public function render( $action ) {
			$output = '<div class="nav-tab-wrapper">';

			$output .= "<h1 class='ast-title'>" . esc_html( self::$menu_page_title ) . '</h1>';

			$view_actions = self::get_view_actions();

			foreach ( $view_actions as $slug => $data ) {

				if ( ! $data['show'] ) {
					continue;
				}

				$url = self::get_page_url( $slug );

				if ( $slug == self::$parent_page_slug ) {
					update_option( 'ast_parent_page_url', $url );
				}

				$active = ( $slug == $action ) ? 'nav-tab-active' : '';

				$output .= "<a class='nav-tab " . esc_attr( $active ) . "' href='" . esc_url( $url ) . "'>" . esc_html( $data['label'] ) . '</a>';
			}

			$output .= '</div>';

			echo $output;

			// Settings update message.
			if ( isset( $_REQUEST['message'] ) && ( 'saved' == $_REQUEST['message'] || 'saved_ext' == $_REQUEST['message'] ) ) {
				printf( '<span id="message" class="notice notice-success is-dismissive"><p>%s</p></span>', __( 'Settings saved successfully.', 'astra' ) );
			}

		}

		/**
		 * Get and return page URL
		 *
		 * @param string $menu_slug Menu name.
		 * @since 1.0
		 * @return  string page url
		 */
		static public function get_page_url( $menu_slug ) {

			$plugin_slug = self::$plugin_slug;

			if ( self::$is_top_level_page ) {

				if ( self::$network_admin_active ) {

					if ( $menu_slug == self::$parent_page_slug ) {
						$url = network_admin_url( 'admin.php?page=' . $plugin_slug );
					} else {
						$url = network_admin_url( 'admin.php?page=' . $plugin_slug . '-' . $menu_slug );
					}
				} else {

					if ( $menu_slug == self::$parent_page_slug ) {
						$url = admin_url( 'admin.php?page=' . $plugin_slug );
					} else {
						$url = admin_url( 'admin.php?page=' . $plugin_slug . '-' . $menu_slug );
					}
				}
			} else {

				$parent_page = self::$default_menu_position;

				if ( strpos( $parent_page, '?' ) !== false ) {
					$query_var = '&page=' . $plugin_slug;
				} else {
					$query_var = '?page=' . $plugin_slug;
				}

				if ( self::$network_admin_active ) {
					$parent_page_url = network_admin_url( $parent_page . $query_var );
				} else {
					$parent_page_url = admin_url( $parent_page . $query_var );
				}

							$url = $parent_page_url . '&action=' . $menu_slug;
			}// End if().

			return esc_url( $url );
		}

		/**
		 * Add main menu
		 *
		 * @since 1.0
		 */
		static public function add_admin_menu() {

			$parent_page    = self::$default_menu_position;
			$page_title     = self::$menu_page_title;
			$capability     = 'manage_options';
			$page_menu_slug = self::$plugin_slug;
			$page_menu_func = __CLASS__ . '::menu_callback';

			add_theme_page( $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func );
		}

		/**
		 * Menu callback
		 *
		 * @since 1.0
		 */
		static public function menu_callback() {

			if ( self::$is_top_level_page ) {

				$screen_base = $_REQUEST['page'];

				if ( self::$network_admin_active ) {
					$current_slug = str_replace( array( self::$plugin_slug . '-' ), '', $screen_base );
				} else {

					$current_slug = str_replace( array( self::$plugin_slug . '-' ), '', $screen_base );
				}

				if ( 'astra' == $current_slug ) {
					$current_slug = self::$parent_page_slug;
				}
			} else {

				$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;
			}

				$active_tab   = str_replace( '_', '-', $current_slug );
				$current_slug = str_replace( '-', '_', $current_slug );

			echo '<div class="ast-menu-page-wrapper">';
			self::init_nav_menu( $active_tab );
			do_action( 'ast_menu_' . $current_slug . '_action' );
			echo '</div>';
		}

		/**
		 * Include general page
		 *
		 * @since 1.0
		 */
		static public function general_page() {

			$settings = self::get_options();

			require_once AST_THEME_DIR . 'inc/core/view-general.php';
		}

		/**
		 * Get Astra Options
		 *
		 * @since 1.0
		 */
		static public function get_options() {
			$stored   = Ast_Admin_Helper::get_admin_settings_option( '_ast_general_settings' );
			$defaults = self::defaults();
			return wp_parse_args( $stored, $defaults );
		}

		/**
		 * Get Options default values
		 *
		 * @since 1.0
		 */
		static public function defaults() {
			return apply_filters( 'ast_page_general_settings', array() );
		}

	}

	new AST_Admin_Settings;

}// End if().
