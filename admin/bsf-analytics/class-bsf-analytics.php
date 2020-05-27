<?php
/**
 * BSF analytics class file.
 *
 * @version 1.0.0
 *
 * @package bsf-analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Analytics' ) ) {

	/**
	 * BSF analytics
	 */
	class BSF_Analytics {

		/**
		 * Member Variable
		 *
		 * @var string Usage tracking document URL
		 */
		private $usage_doc_link = 'https://store.brainstormforce.com/usage-tracking/?utm_source=wp_dashboard&utm_medium=general_settings&utm_campaign=usage_tracking';

		/**
		 * Setup actions, load files.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			define( 'BSF_ANALYTICS_FILE', __FILE__ );
			define( 'BSF_ANALYTICS_VERSION', '1.0.0' );
			define( 'BSF_ANALYTICS_PATH', dirname( __FILE__ ) );
			define( 'BSF_ANALYTICS_URI', $this->bsf_analytics_url() );

			add_action( 'admin_init', array( $this, 'handle_optin_optout' ) );
			add_action( 'cron_schedules', array( $this, 'every_two_days_schedule' ) );
			add_action( 'admin_notices', array( $this, 'option_notice' ) );
			add_action( 'astra_notice_before_markup_bsf-optin-notice', array( $this, 'enqueue_assets' ) );

			add_action( 'init', array( $this, 'schedule_unschedule_event' ) );

			if ( ! has_action( 'bsf_analytics_send', array( $this, 'send' ) ) ) {
				add_action( 'bsf_analytics_send', array( $this, 'send' ) );
			}

			add_action( 'admin_init', array( $this, 'register_usage_tracking_setting' ) );

			add_action( 'update_option_bsf_analytics_optin', array( $this, 'update_analytics_option_callback' ), 10, 3 );
			add_action( 'add_option_bsf_analytics_optin', array( $this, 'add_analytics_option_callback' ), 10, 2 );

			$this->includes();
		}

		/**
		 * BSF Analytics URL
		 *
		 * @return String URL of bsf-analytics directory.
		 * @since 1.0.0
		 */
		public function bsf_analytics_url() {

			$path      = wp_normalize_path( BSF_ANALYTICS_PATH );
			$theme_dir = wp_normalize_path( get_template_directory() );

			if ( strpos( $path, $theme_dir ) !== false ) {
				return rtrim( get_template_directory_uri() . '/admin/bsf-analytics/', '/' );
			} else {
				return rtrim( plugin_dir_url( BSF_ANALYTICS_FILE ), '/' );
			}
		}

		/**
		 * Get API URL for sending analytics.
		 *
		 * @return string API URL.
		 * @since 1.0.0
		 */
		private function get_api_url() {
			return defined( 'BSF_API_URL' ) ? BSF_API_URL : 'https://support.brainstormforce.com/';
		}

		/**
		 * Enqueue Scripts.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function enqueue_assets() {

			/**
			 * Load unminified if SCRIPT_DEBUG is true.
			 *
			 * Directory and Extensions.
			 */
			$dir_name = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';
			$file_rtl = ( is_rtl() ) ? '-rtl' : '';
			$css_ext  = ( SCRIPT_DEBUG ) ? '.css' : '.min.css';

			$css_uri = BSF_ANALYTICS_URI . '/assets/css/' . $dir_name . '/style' . $file_rtl . $css_ext;

			wp_enqueue_style( 'bsf-analytics-admin-style', $css_uri, false, BSF_ANALYTICS_VERSION, 'all' );
		}

		/**
		 * Send analytics API call.
		 *
		 * @since 1.0.0
		 */
		public function send() {
			wp_remote_post(
				$this->get_api_url() . 'wp-json/bsf-core/v1/analytics/',
				array(
					'body'     => BSF_Analytics_Stats::instance()->get_stats(),
					'timeout'  => 5,
					'blocking' => false,
				)
			);
		}

		/**
		 * Check if usage tracking is enabled.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_tracking_enabled() {
			$is_enabled = get_site_option( 'bsf_analytics_optin' ) === 'yes' ? true : false;
			$is_enabled = $this->is_white_label_enabled() ? false : $is_enabled;

			return apply_filters( 'bsf_tracking_enabled', $is_enabled );
		}

		/**
		 * Check if WHITE label is enabled for BSF products.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_white_label_enabled() {

			$options    = apply_filters( 'bsf_white_label_options', array() );
			$is_enabled = false;

			if ( is_array( $options ) ) {
				foreach ( $options as $option ) {
					if ( true === $option ) {
						$is_enabled = true;
						break;
					}
				}
			}

			return $is_enabled;
		}

		/**
		 * Display admin notice for usage tracking.
		 *
		 * @since 1.0.0
		 */
		public function option_notice() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Don't display the notice if tracking is disabled or White Label is enabled for any of our plugins.
			if ( false !== get_site_option( 'bsf_analytics_optin', false ) || $this->is_white_label_enabled() ) {
				return;
			}

			// Show tracker consent notice after 24 hours from installed time.
			if ( strtotime( '+24 hours', $this->get_analytics_install_time() ) > time() ) {
				return;
			}

			/* translators: %s product name */
			$notice_string = __( 'Want to help make <strong>%1s</strong> even more awesome? Allow us to collect non-sensitive diagnostic data and usage information. ', 'astra' );

			if ( is_multisite() ) {
				$notice_string .= __( 'This will be applicable for all sites from the network.', 'astra' );
			}

			Astra_Notices::add_notice(
				array(
					'id'                         => 'bsf-optin-notice',
					'type'                       => '',
					'message'                    => sprintf(
						'<div class="notice-content">
								<div class="notice-heading">
									%1$s
								</div>
								<div class="astra-notices-container">
									<a href="%2$s" class="astra-notices button-primary">
									%3$s
									</a>
									<a href="%4$s" data-repeat-notice-after="%5$s" class="astra-notices button-secondary">
									%6$s
									</a>
								</div>
							</div>',
						/* translators: %s usage doc link */
						sprintf( $notice_string . '<a href="%2s" target="_blank" rel="noreferrer noopener">%3s</a>', $this->get_product_name(), esc_url( $this->usage_doc_link ), __( ' Know More.', 'astra' ) ),
						add_query_arg(
							array(
								'bsf_analytics_optin' => 'yes',
								'bsf_analytics_nonce' => wp_create_nonce( 'bsf_analytics_optin' ),
							)
						),
						__( 'Yes! Allow it', 'astra' ),
						add_query_arg(
							array(
								'bsf_analytics_optin' => 'no',
								'bsf_analytics_nonce' => wp_create_nonce( 'bsf_analytics_optin' ),
							)
						),
						MONTH_IN_SECONDS,
						__( 'No Thanks', 'astra' )
					),
					'show_if'                    => true,
					'repeat-notice-after'        => false,
					'priority'                   => 18,
					'display-with-other-notices' => true,
				)
			);
		}

		/**
		 * Process usage tracking opt out.
		 *
		 * @since 1.0.0
		 */
		public function handle_optin_optout() {
			if ( ! isset( $_GET['bsf_analytics_nonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( $_GET['bsf_analytics_nonce'] ), 'bsf_analytics_optin' ) ) {
				return;
			}

			$optin_status = sanitize_text_field( $_GET['bsf_analytics_optin'] );

			if ( 'yes' === $optin_status ) {
				$this->optin();
			} elseif ( 'no' === $optin_status ) {
				$this->optout();
			}

			wp_safe_redirect(
				remove_query_arg(
					array(
						'bsf_analytics_optin',
						'bsf_analytics_nonce',
					)
				)
			);
		}

		/**
		 * Opt in to usage tracking.
		 *
		 * @since 1.0.0
		 */
		private function optin() {
			update_site_option( 'bsf_analytics_optin', 'yes' );
		}

		/**
		 * Opt out to usage tracking.
		 *
		 * @since 1.0.0
		 */
		private function optout() {
			update_site_option( 'bsf_analytics_optin', 'no' );
		}

		/**
		 * Add two days event schedule variables.
		 *
		 * @param array $schedules scheduled array data.
		 * @since 1.0.0
		 */
		public function every_two_days_schedule( $schedules ) {
			$schedules['every_two_days'] = array(
				'interval' => 2 * DAY_IN_SECONDS,
				'display'  => __( 'Every two days', 'astra' ),
			);

			return $schedules;
		}

		/**
		 * Schedule usage tracking event.
		 *
		 * @since 1.0.0
		 */
		private function schedule_event() {
			if ( ! wp_next_scheduled( 'bsf_analytics_send' ) && $this->is_tracking_enabled() ) {
				wp_schedule_event( time(), 'every_two_days', 'bsf_analytics_send' );
			}
		}

		/**
		 * Unschedule usage tracking event.
		 *
		 * @since 1.0.0
		 */
		private function unschedule_event() {
			wp_clear_scheduled_hook( 'bsf_analytics_send' );
		}

		/**
		 * Load analytics stat class.
		 *
		 * @since 1.0.0
		 */
		private function includes() {
			require_once __DIR__ . '/class-bsf-analytics-stats.php';
		}

		/**
		 * Register usage tracking option in General settings page.
		 *
		 * @since 1.0.0
		 */
		public function register_usage_tracking_setting() {

			if ( ! apply_filters( 'bsf_tracking_enabled', true ) || $this->is_white_label_enabled() ) {
				return;
			}

			register_setting(
				'general',             // Options group.
				'bsf_analytics_optin',      // Option name/database.
				array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) // sanitize callback function.
			);

			add_settings_field(
				'bsf-analytics-optin',       // Field ID.
				__( 'Usage Tracking', 'astra' ),       // Field title.
				array( $this, 'render_settings_field_html' ), // Field callback function.
				'general'                    // Settings page slug.
			);
		}

		/**
		 * Sanitize Callback Function
		 *
		 * @param bool $input Option value.
		 * @since 1.0.0
		 */
		public function sanitize_option( $input ) {

			if ( ! $input || 'no' === $input ) {
				return 'no';
			}

			return 'yes';
		}

		/**
		 * Print settings field HTML.
		 *
		 * @since 1.0.0
		 */
		public function render_settings_field_html() {
			?>
			<label for="bsf-analytics-optin">
				<input id="bsf-analytics-optin" type="checkbox" value="1" name="bsf_analytics_optin" <?php checked( get_site_option( 'bsf_analytics_optin', 'no' ), 'yes' ); ?>>
				<?php
				esc_html_e( 'Allow Brainstorm Force products to track non-sensitive usage tracking data.', 'astra' );

				if ( is_multisite() ) {
					esc_html_e( ' This will be applicable for all sites from the network.', 'astra' );
				}
				?>
			</label>
			<?php
			echo wp_kses_post( sprintf( '<a href="%1s" target="_blank" rel="noreferrer noopener">%2s</a>', esc_url( $this->usage_doc_link ), __( 'Learn More.', 'astra' ) ) );
		}

		/**
		 * Get current product name.
		 *
		 * @return string $plugin_data['Name] Name of plugin.
		 * @since 1.0.0
		 */
		private function get_product_name() {

			$base      = wp_normalize_path( dirname( __FILE__ ) );
			$theme_dir = wp_normalize_path( get_template_directory() );

			if ( false !== strpos( $base, $theme_dir ) ) {
				$theme = wp_get_theme( get_template() );
				return $theme->get( 'Name' );
			}

			$base = plugin_basename( __FILE__ );

			$exploded_path = explode( '/', $base, 2 );
			$plugin_slug   = $exploded_path[0];

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_main_file = WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_slug . '.php';
			$plugin_data      = get_plugin_data( wp_normalize_path( $plugin_main_file ) );

			return $plugin_data['Name'];
		}

		/**
		 * Set analytics installed time in option.
		 *
		 * @return string $time analytics installed time.
		 * @since 1.0.0
		 */
		private function get_analytics_install_time() {

			$time = get_site_option( 'bsf_analytics_installed_time' );

			if ( ! $time ) {
				$time = time();
				update_site_option( 'bsf_analytics_installed_time', time() );
			}

			return $time;
		}

		/**
		 * Schedule/unschedule cron event on updation of option.
		 *
		 * @param string $old_value old value of option.
		 * @param string $value value of option.
		 * @param string $option Option name.
		 * @since 1.0.0
		 */
		public function update_analytics_option_callback( $old_value, $value, $option ) {
			$this->add_option_to_network( $value );
		}

		/**
		 * Analytics option add callback.
		 *
		 * @param string $option Option name.
		 * @param string $value value of option.
		 * @since 1.0.0
		 */
		public function add_analytics_option_callback( $option, $value ) {
			$this->add_option_to_network( $value );
		}

		/**
		 * Schedule or unschedule event based on analytics option value.
		 *
		 * @since 1.0.0
		 */
		public function schedule_unschedule_event() {

			if ( true === $this->is_white_label_enabled() ) {
				$this->unschedule_event();
				return;
			}

			$analytics_option = get_site_option( 'bsf_analytics_optin' );

			if ( 'no' === $analytics_option ) {
				$this->unschedule_event();
			} elseif ( 'yes' === $analytics_option ) {
				$this->schedule_event();
			}
		}

		/**
		 * Save analytics option to network.
		 *
		 * @param string $value value of option.
		 * @since 1.0.0
		 */
		public function add_option_to_network( $value ) {

			// If action coming from general settings page.
			if ( isset( $_POST['option_page'] ) && 'general' === $_POST['option_page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing

				if ( get_site_option( 'bsf_analytics_optin' ) ) {
					update_site_option( 'bsf_analytics_optin', $value );
				} else {
					add_site_option( 'bsf_analytics_optin', $value );
				}
			}
		}
	}

	new BSF_Analytics();

}
