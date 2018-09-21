<?php
/**
 * Admin settings helper
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
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
		static public $menu_page_title = 'Astra Theme';

		/**
		 * Page title
		 *
		 * @since 1.0
		 * @var array $page_title
		 */
		static public $page_title = 'Astra';

		/**
		 * Plugin slug
		 *
		 * @since 1.0
		 * @var array $plugin_slug
		 */
		static public $plugin_slug = 'astra';

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
			self::$menu_page_title = apply_filters( 'astra_menu_page_title', __( 'Astra Options', 'astra' ) );
			self::$page_title      = apply_filters( 'astra_page_title', __( 'Astra', 'astra' ) );

			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], self::$plugin_slug ) !== false ) {

				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );

				// Let extensions hook into saving.
				do_action( 'astra_admin_settings_scripts' );

				self::save_settings();
			}

			add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_scripts' );

			add_action( 'customize_controls_enqueue_scripts', __CLASS__ . '::customizer_scripts' );

			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );

			add_action( 'astra_menu_general_action', __CLASS__ . '::general_page' );

			add_action( 'astra_header_right_section', __CLASS__ . '::top_header_right_section' );

			add_filter( 'admin_title', __CLASS__ . '::astra_admin_title', 10, 2 );

			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_starter_sites_section', 10 );
			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_knowledge_base_scetion', 11 );
			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_community_scetion', 12 );
			add_action( 'astra_welcome_page_right_sidebar_content', __CLASS__ . '::astra_welcome_page_five_star_scetion', 13 );

			add_action( 'astra_welcome_page_content', __CLASS__ . '::astra_welcome_page_content' );

			// AJAX.
			add_action( 'wp_ajax_astra-sites-plugin-activate', __CLASS__ . '::required_plugin_activate' );

			add_action( 'admin_notices', __CLASS__ . '::register_notices' );
		}

		/**
		 * Ask Theme Rating
		 *
		 * @since 1.4.0
		 */
		public static function register_notices() {

			if ( class_exists( 'Astra_Ext_White_Label_Markup' ) ) {
				if ( ! empty( Astra_Ext_White_Label_Markup::$branding['astra']['name'] ) ) {
					return;
				}
			}

			if ( false === get_option( 'astra-theme-old-setup' ) ) {
				set_transient( 'astra-theme-first-rating', true, MONTH_IN_SECONDS );
				update_option( 'astra-theme-old-setup', true );
			} elseif ( false === get_transient( 'astra-theme-first-rating' ) ) {
				$image_path = ASTRA_THEME_URI . 'inc/assets/images/astra-logo.svg';
				Astra_Notices::add_notice(
					array(
						'id'                  => 'astra-theme-rating',
						'type'                => '',
						/* translators: %1$s logo link, %2$s product rating link, %3$s dismissable notice transient time. */
						'message'             => sprintf( __( '<div class="notice-image"><img src="%1$s" class="custom-logo" alt="Astra" itemprop="logo"></div> <div class="notice-content"> <div class="notice-heading">Hello! Seems like you have used Astra theme to build this website — Thanks a ton!</div>Could you please do us a BIG favor and give it a 5-star rating on WordPress? This would boost our motivation and help other users make a comfortable decision while choosing the Astra theme.<br /><div class="astra-review-notice-container"><a href="%2$s" class="astra-notice-close astra-review-notice button-primary" target="_blank">Ok, you deserve it</a><span class="dashicons dashicons-calendar"></span><a href="#" data-repeat-notice-after="%3$s" class="astra-notice-close astra-review-notice">Nope, maybe later</a><span class="dashicons dashicons-smiley"></span><a href="#" class="astra-notice-close astra-review-notice">I already did</a></div></div>', 'astra' ), $image_path, 'https://wordpress.org/support/theme/astra/reviews/?filter=5#new-post', MONTH_IN_SECONDS ),
						'repeat-notice-after' => MONTH_IN_SECONDS,
					)
				);
			}
		}

		/**
		 * View actions
		 */
		static public function get_view_actions() {

			if ( empty( self::$view_actions ) ) {

				$actions            = array(
					'general' => array(
						'label' => __( 'Welcome', 'astra' ),
						'show'  => ! is_network_admin(),
					),
				);
				self::$view_actions = apply_filters( 'astra_menu_options', $actions );
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
			do_action( 'astra_admin_settings_save' );
		}

		/**
		 * Load the scripts and styles in the customizer controls.
		 *
		 * @since 1.2.1
		 */
		static public function customizer_scripts() {
			$color_palettes = json_encode( astra_color_palette() );
			wp_add_inline_script( 'wp-color-picker', 'jQuery.wp.wpColorPicker.prototype.options.palettes = ' . $color_palettes . ';' );
		}

		/**
		 * Enqueues the needed CSS/JS for Backend.
		 *
		 * @since 1.0
		 */
		static public function admin_scripts() {

			// Styles.
			if ( is_rtl() ) {
				wp_enqueue_style( 'astra-admin-rtl', ASTRA_THEME_URI . 'inc/assets/css/astra-admin-rtl.css', array(), ASTRA_THEME_VERSION );
			} else {
				wp_enqueue_style( 'astra-admin', ASTRA_THEME_URI . 'inc/assets/css/astra-admin.css', array(), ASTRA_THEME_VERSION );
			}

			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$assets_js_uri = ASTRA_THEME_URI . 'assets/js/' . $dir_name . '/';

			/**
			 * Filters the Admin JavaScript handles added
			 *
			 * @since v1.4.10
			 *
			 * @param array array of the javascript handles.
			 */
			$js_handle = apply_filters( 'astra_admin_script_handles', array( 'jquery', 'wp-color-picker' ) );
			// Add customize-base handle only for the Customizer Preview Screen.
			if ( true === is_customize_preview() ) {
				$js_handle[] = 'customize-base';
			}

			wp_enqueue_script( 'astra-color-alpha', $assets_js_uri . 'wp-color-picker-alpha' . $file_prefix . '.js', $js_handle, ASTRA_THEME_VERSION, true );
		}

		/**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 1.0
		 */
		static public function styles_scripts() {

			// Styles.
			if ( is_rtl() ) {
				wp_enqueue_style( 'astra-admin-settings-rtl', ASTRA_THEME_URI . 'inc/assets/css/astra-admin-menu-settings-rtl.css', array(), ASTRA_THEME_VERSION );
			} else {
				wp_enqueue_style( 'astra-admin-settings', ASTRA_THEME_URI . 'inc/assets/css/astra-admin-menu-settings.css', array(), ASTRA_THEME_VERSION );
			}

			// Script.
			wp_enqueue_script( 'astra-admin-settings', ASTRA_THEME_URI . 'inc/assets/js/astra-admin-menu-settings.js', array( 'jquery', 'wp-util', 'updates' ), ASTRA_THEME_VERSION );

			$localize = array(
				'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
				'btnActivating'       => __( 'Activating Importer Plugin ', 'astra' ) . '&hellip;',
				'astraSitesLink'      => admin_url( 'themes.php?page=astra-sites' ),
				'astraSitesLinkTitle' => __( 'See Library »', 'astra' ),
			);
			wp_localize_script( 'astra-admin-settings', 'astra', apply_filters( 'astra_theme_js_localize', $localize ) );
		}

		/**
		 * Update Admin Title.
		 *
		 * @since 1.0.19
		 *
		 * @param string $admin_title Admin Title.
		 * @param string $title Title.
		 * @return string
		 */
		static public function astra_admin_title( $admin_title, $title ) {

			$screen = get_current_screen();
			if ( 'appearance_page_astra' == $screen->id ) {

				$view_actions = self::get_view_actions();

				$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;
				$active_tab   = str_replace( '_', '-', $current_slug );

				if ( 'general' != $active_tab && isset( $view_actions[ $active_tab ]['label'] ) ) {
					$admin_title = str_replace( $title, $view_actions[ $active_tab ]['label'], $admin_title );
				}
			}

			return $admin_title;
		}


		/**
		 * Get and return page URL
		 *
		 * @param string $menu_slug Menu name.
		 * @since 1.0
		 * @return  string page url
		 */
		static public function get_page_url( $menu_slug ) {

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
		static public function add_admin_menu() {

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
		static public function menu_callback() {

			$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;

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
									<span class="astra-theme-version"><?php echo ASTRA_THEME_VERSION; ?></span>
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
		static public function general_page() {
			require_once ASTRA_THEME_DIR . 'inc/core/view-general.php';
		}

		/**
		 * Include Welcome page right starter sites content
		 *
		 * @since 1.2.4
		 */
		static public function astra_welcome_page_starter_sites_section() {
			?>

			<div class="postbox">
				<h2 class="hndle ast-normal-cusror">
					<span class="dashicons dashicons-admin-customizer"></span>
					<span><?php echo esc_html( apply_filters( 'astra_sites_menu_page_title', __( 'Import Starter Site', 'astra' ) ) ); ?></span>
				</h2>
				<img class="ast-starter-sites-img" src="<?php echo esc_url( ASTRA_THEME_URI . 'assets/images/astra-starter-sites.jpg' ); ?>">
				<div class="inside">
					<p>
						<?php
							$astra_starter_sites_doc_link      = apply_filters( 'astra_starter_sites_documentation_link', astra_get_pro_url( 'https://wpastra.com/ready-websites/installing-importing-astra-sites/', 'astra-dashboard', 'how-astra-sites-works', 'welcome-page' ) );
							$astra_starter_sites_doc_link_text = apply_filters( 'astra_starter_sites_doc_link_text', __( 'Starter Site Templates?', 'astra' ) );
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
							esc_html_e( 'Import your favorite site one click and start your project in style!', 'astra' );
						?>
					</p>
						<?php
						// Astra Sites - Installed but Inactive.
						// Astra Premium Sites - Inactive.
						if ( file_exists( WP_PLUGIN_DIR . '/astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-pro-sites/astra-pro-sites.php' ) ) {

							$class       = 'button ast-sites-inactive';
							$button_text = __( 'Activate Importer Plugin', 'astra' );
							$data_slug   = 'astra-sites';
							$data_init   = '/astra-sites/astra-sites.php';

							// Astra Sites - Not Installed.
							// Astra Premium Sites - Inactive.
						} elseif ( ! file_exists( WP_PLUGIN_DIR . '/astra-sites/astra-sites.php' ) && is_plugin_inactive( 'astra-pro-sites/astra-pro-sites.php' ) ) {

							$class       = 'button ast-sites-notinstalled';
							$button_text = __( 'Install Importer Plugin', 'astra' );
							$data_slug   = 'astra-sites';
							$data_init   = '/astra-sites/astra-sites.php';

							// Astra Premium Sites - Active.
						} elseif ( is_plugin_active( 'astra-pro-sites/astra-pro-sites.php' ) ) {
							$class       = 'active';
							$button_text = __( 'See Library »', 'astra' );
							$link        = admin_url( 'themes.php?page=astra-sites' );
						} else {
							$class       = 'active';
							$button_text = __( 'See Library »', 'astra' );
							$link        = admin_url( 'themes.php?page=astra-sites' );
						}

						printf(
							'<a class="%1$s" %2$s %3$s %4$s> %5$s </a>',
							esc_attr( $class ),
							isset( $link ) ? 'href="' . esc_url( $link ) . '"' : '',
							isset( $data_slug ) ? 'data-slug="' . esc_attr( $data_slug ) . '"' : '',
							isset( $data_init ) ? 'data-init="' . esc_attr( $data_init ) . '"' : '',
							esc_html( $button_text )
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
		static public function astra_welcome_page_knowledge_base_scetion() {
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
					$astra_knowledge_base_doc_link_text = apply_filters( 'astra_knowledge_base_documentation_link_text', __( 'Visit Knowledge Base »', 'astra' ) );

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
		static public function astra_welcome_page_community_scetion() {
			?>

			<div class="postbox">
				<h2 class="hndle ast-normal-cusror">
					<span class="dashicons dashicons-groups"></span>
					<span>
						<?php
						printf(
							/* translators: %1$s: Astra Theme name. */
							esc_html__( '%1$s Community', 'astra' ),
							self::$page_title
						);
						?>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							/* translators: %1$s: Astra Theme name. */
							esc_html__( 'Join the community of super helpful %1$s users. Say hello, ask questions, give feedback and help each other!', 'astra' ),
							self::$page_title
						);
						?>
					</p>
					<?php
					$astra_community_group_link      = apply_filters( 'astra_community_group_link', 'https://www.facebook.com/groups/wpastra' );
					$astra_community_group_link_text = apply_filters( 'astra_community_group_link_text', __( 'Join Our Facebook Group »', 'astra' ) );

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
		static public function astra_welcome_page_five_star_scetion() {
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
							self::$page_title
						);
						?>
					</p>
					<?php
						$astra_support_link      = apply_filters( 'astra_support_link', astra_get_pro_url( 'https://wpastra.com/contact/', 'astra-dashboard', 'submit-a-ticket', 'welcome-page' ) );
						$astra_support_link_text = apply_filters( 'astra_support_link_text', __( 'Submit a Ticket »', 'astra' ) );

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
		static public function astra_welcome_page_content() {

			$astra_addon_tagline = apply_filters( 'astra_addon_list_tagline', __( 'More Options Available with Astra Pro!', 'astra' ) );

			// Quick settings.
			$quick_settings = apply_filters(
				'astra_quick_settings',
				array(
					'logo-favicon' => array(
						'title'     => __( 'Upload Logo', 'astra' ),
						'dashicon'  => 'dashicons-format-image',
						'quick_url' => admin_url( 'customize.php?autofocus[control]=custom_logo' ),
					),
					'colors'       => array(
						'title'     => __( 'Set Colors', 'astra' ),
						'dashicon'  => 'dashicons-admin-customizer',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=panel-colors-background' ),
					),
					'typography'   => array(
						'title'     => __( 'Customize Fonts', 'astra' ),
						'dashicon'  => 'dashicons-editor-textcolor',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=panel-typography' ),
					),
					'layout'       => array(
						'title'     => __( 'Layout Options', 'astra' ),
						'dashicon'  => 'dashicons-layout',
						'quick_url' => admin_url( 'customize.php?autofocus[panel]=panel-layout' ),
					),
					'header'       => array(
						'title'     => __( 'Header Options', 'astra' ),
						'dashicon'  => 'dashicons-align-center',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-header' ),
					),
					'blog-layout'  => array(
						'title'     => __( 'Blog Layouts', 'astra' ),
						'dashicon'  => 'dashicons-welcome-write-blog',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-blog-group' ),
					),
					'footer'       => array(
						'title'     => __( 'Footer Settings', 'astra' ),
						'dashicon'  => 'dashicons-admin-generic',
						'quick_url' => admin_url( 'customize.php?autofocus[section]=section-footer-group' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/how-to-white-label-astra/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More »', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'transparent-header'    => array(
						'title'     => __( 'Transparent Header', 'astra' ),
						'class'     => 'ast-addon',
						'title_url' => astra_get_pro_url( 'https://wpastra.com/docs/transparent-header-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'     => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/transparent-header-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
								'target_blank' => true,
							),
						),
					),
					'advanced-hooks'        => array(
						'title'           => __( 'Custom Layouts', 'astra' ),
						// 'icon'            => ASTRA_EXT_URI . 'assets/img/astra-advanced-hooks.png',
						'description'     => __( 'Add content conditionally in the various hook areas of the theme.', 'astra' ),
						'manage_settings' => true,
						'class'           => 'ast-addon',
						'title_url'       => astra_get_pro_url( 'https://wpastra.com/docs/custom-layouts-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
						'links'           => array(
							array(
								'link_class'   => 'ast-learn-more',
								'link_url'     => astra_get_pro_url( 'https://wpastra.com/docs/custom-layouts-pro/', 'astra-dashboard', 'learn-more', 'welcome-page' ),
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
								'link_text'    => __( 'Learn More »', 'astra' ),
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
										echo '<li class=""><span class="dashicons ' . esc_attr( $link['dashicon'] ) . '"></span><a class="ast-quick-setting-title" href="' . esc_url( $link['quick_url'] ) . '" target="_blank" rel="noopener">' . esc_html( $link['title'] ) . '</a></li>';
									}
									?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
			</div>

			<!-- Notice for Older version of Astra Addon -->
			<?php self::min_addon_version_message(); ?>

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

										echo '<li id="' . esc_attr( $addon ) . '"  class="' . esc_attr( $info['class'] ) . '"><a class="ast-addon-title"' . $title_url . $anchor_target . ' >' . esc_html( $info['title'] ) . '</a><div class="ast-addon-link-wrapper">';

										foreach ( $info['links'] as $key => $link ) {
											printf(
												'<a class="%1$s" %2$s %3$s> %4$s </a>',
												esc_attr( $link['link_class'] ),
												isset( $link['link_url'] ) ? 'href="' . esc_url( $link['link_url'] ) . '"' : '',
												( isset( $link['target_blank'] ) && $link['target_blank'] ) ? 'target="_blank" rel="noopener"' : '',
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
		 * Required Plugin Activate
		 *
		 * @since 1.2.4
		 */
		static public function required_plugin_activate() {

			if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! $_POST['init'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => __( 'No plugin specified', 'astra' ),
					)
				);
			}

			$plugin_init = ( isset( $_POST['init'] ) ) ? esc_attr( $_POST['init'] ) : '';

			$activate = activate_plugin( $plugin_init, '', false, true );

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $activate->get_error_message(),
					)
				);
			}

			wp_send_json_success(
				array(
					'success' => true,
					'message' => __( 'Plugin Successfully Activated', 'astra' ),
				)
			);

		}

		/**
		 * Check compatible theme version.
		 *
		 * @since 1.2.4
		 */
		static public function min_addon_version_message() {

			$astra_global_options = get_option( 'astra-settings' );

			if ( isset( $astra_global_options['astra-addon-auto-version'] ) && defined( 'ASTRA_EXT_VER' ) ) {

				if ( version_compare( $astra_global_options['astra-addon-auto-version'], '1.2.1' ) < 0 ) {

					// If addon is not updated & White Label for Addon is added then show the white labelewd pro name.
					$astra_addon_name        = astra_get_addon_name();
					$update_astra_addon_link = astra_get_pro_url( 'https://wpastra.com/?p=25258', 'astra-dashboard', 'update-to-astra-pro', 'welcome-page' );
					if ( class_exists( 'Astra_Ext_White_Label_Markup' ) ) {
						$plugin_data = Astra_Ext_White_Label_Markup::$branding;
						if ( ! empty( $plugin_data['astra-pro']['name'] ) ) {
							$update_astra_addon_link = '';
						}
					}

					$class   = 'ast-notice ast-notice-error';
					$message = sprintf(
						/* translators: %1$1s: Addon Name, %2$2s: Minimum Required version of the Astra Addon */
						__( 'Update to the latest version of %1$2s to make changes in settings below.', 'astra' ),
						( ! empty( $update_astra_addon_link ) ) ? '<a href=' . esc_url( $update_astra_addon_link ) . ' target="_blank" rel="noopener">' . $astra_addon_name . '</a>' : $astra_addon_name
					);

					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
				}
			}
		}

		/**
		 * Astra Header Right Section Links
		 *
		 * @since 1.2.4
		 */
		static public function top_header_right_section() {

			$top_links = apply_filters(
				'astra_header_top_links',
				array(
					'astra-theme-info' => array(
						'title' => __( '⚡ Lightning Fast & Fully Customizable WordPress theme!', 'astra' ),
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
								isset( $info['url'] ) ? 'href="' . esc_url( $info['url'] ) . '"' : '',
								isset( $info['url'] ) ? 'target="_blank" rel="noopener"' : '',
								esc_html( $info['title'] )
							);
						}
						?>
						</ul>
					</div>
				<?php
			}
		}
	}

	new Astra_Admin_Settings;
}
