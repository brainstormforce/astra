<?php
/**
 * Astra Notices
 *
 * An easy to use PHP Library to add dismissible admin notices in the WordPress admin.
 *
 * @package Astra Notices
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Astra_Notices' ) ) :

	/**
	 * Astra_Notices
	 *
	 * @since 1.0.0
	 */
	class Astra_Notices {

		/**
		 * Notices
		 *
		 * @access private
		 * @var array Notices.
		 * @since 1.0.0
		 */
		private static $version = '1.1.8';

		/**
		 * Notices
		 *
		 * @access private
		 * @var array Notices.
		 * @since 1.0.0
		 */
		private static $notices = array();

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class object.
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'admin_notices', array( $this, 'show_notices' ), 30 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_astra-notice-dismiss', array( $this, 'dismiss_notice' ) );
			add_filter( 'wp_kses_allowed_html', array( $this, 'add_data_attributes' ), 10, 2 );
		}

		/**
		 * Filters and Returns a list of allowed tags and attributes for a given context.
		 *
		 * @param array  $allowedposttags array of allowed tags.
		 * @param string $context Context type (explicit).
		 * @since 1.0.0
		 * @return array
		 */
		public function add_data_attributes( $allowedposttags, $context ) {
			$allowedposttags['a']['data-repeat-notice-after'] = true;

			return $allowedposttags;
		}

		/**
		 * Add Notice.
		 *
		 * @since 1.0.0
		 * @param array $args Notice arguments.
		 * @return void
		 */
		public static function add_notice( $args = array() ) {
			self::$notices[] = $args;
		}

		/**
		 * Dismiss Notice.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function dismiss_notice() {
			$notice_id           = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( $_POST['notice_id'] ) : '';
			$repeat_notice_after = ( isset( $_POST['repeat_notice_after'] ) ) ? absint( $_POST['repeat_notice_after'] ) : '';
			$nonce               = ( isset( $_POST['nonce'] ) ) ? sanitize_key( $_POST['nonce'] ) : '';
			$notice              = $this->get_notice_by_id( $notice_id );
			$capability          = isset( $notice['capability'] ) ? $notice['capability'] : 'manage_options';

			if ( ! apply_filters( 'astra_notices_user_cap_check', current_user_can( $capability ) ) ) {
				return;
			}

			if ( false === wp_verify_nonce( $nonce, 'astra-notices' ) ) {
				wp_send_json_error( esc_html_e( 'WordPress Nonce not validated.', 'astra' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( ! empty( $repeat_notice_after ) ) {
					set_transient( $notice_id, true, $repeat_notice_after );
				} else {
					update_user_meta( get_current_user_id(), $notice_id, 'notice-dismissed' );
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}

		/**
		 * Enqueue Scripts.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function enqueue_scripts() {
			wp_register_script( 'astra-notices', self::get_url() . 'notices.js', array( 'jquery' ), self::$version, true );
			wp_localize_script(
				'astra-notices',
				'astraNotices',
				array(
					'_notice_nonce' => wp_create_nonce( 'astra-notices' ),
				)
			);
		}

		/**
		 * Sort the notices based on the given priority of the notice.
		 * This function is called from usort()
		 *
		 * @since 1.5.2
		 * @param array $notice_1 First notice.
		 * @param array $notice_2 Second Notice.
		 * @return array
		 */
		public function sort_notices( $notice_1, $notice_2 ) {
			if ( ! isset( $notice_1['priority'] ) ) {
				$notice_1['priority'] = 10;
			}
			if ( ! isset( $notice_2['priority'] ) ) {
				$notice_2['priority'] = 10;
			}

			return $notice_1['priority'] - $notice_2['priority'];
		}

		/**
		 * Get all registered notices.
		 * Since v1.1.8 it is recommended to register the notices on
		 *
		 * @return array|null
		 */
		private function get_notices() {
			usort( self::$notices, array( $this, 'sort_notices' ) );

			return self::$notices;
		}

		/**
		 * Get notice by notice_id
		 *
		 * @param string $notice_id Notice id.
		 *
		 * @return array notice based on the notice id.
		 */
		private function get_notice_by_id( $notice_id ) {
			if ( empty( $notice_id ) ) {
				return array();
			}

			$notices = $this->get_notices();
			$notice  = wp_list_filter(
				$notices,
				array(
					'id' => $notice_id,
				)
			);

			return ! empty( $notice ) ? $notice[0] : array();
		}

		/**
		 * Display the notices in the WordPress admin.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function show_notices() {
			$defaults = array(
				'id'                         => '',      // Optional, Notice ID. If empty it set `astra-notices-id-<$array-index>`.
				'type'                       => 'info',  // Optional, Notice type. Default `info`. Expected [info, warning, notice, error].
				'message'                    => '',      // Optional, Message.
				'show_if'                    => true,    // Optional, Show notice on custom condition. E.g. 'show_if' => if( is_admin() ) ? true, false, .
				'repeat-notice-after'        => '',      // Optional, Dismiss-able notice time. It'll auto show after given time.
				'display-notice-after'       => false,      // Optional, Dismiss-able notice time. It'll auto show after given time.
				'class'                      => '',      // Optional, Additional notice wrapper class.
				'priority'                   => 10,      // Priority of the notice.
				'display-with-other-notices' => true,    // Should the notice be displayed if other notices  are being displayed from Astra_Notices.
				'is_dismissible'             => true,
				'capability'                 => 'manage_options', // User capability - This capability is required for the current user to see this notice.
			);

			// Count for the notices that are rendered.
			$notices_displayed = 0;
			$notices           = $this->get_notices();

			foreach ( $notices as $key => $notice ) {
				$notice = wp_parse_args( $notice, $defaults );

				// Show notices only for users with `manage_options` cap.
				if ( ! current_user_can( $notice['capability'] ) ) {
					continue;
				}

				$notice['id']      = self::get_notice_id( $notice, $key );
				$notice['classes'] = self::get_wrap_classes( $notice );

				// Notices visible after transient expire.
				if ( isset( $notice['show_if'] ) && true === $notice['show_if'] ) {

					// don't display the notice if it is not supposed to be displayed with other notices.
					if ( 0 !== $notices_displayed && false === $notice['display-with-other-notices'] ) {
						continue;
					}

					if ( self::is_expired( $notice ) ) {

						self::markup( $notice );
						++$notices_displayed;
					}
				}
			}

		}

		/**
		 * Render a notice.
		 *
		 * @since 1.0.0
		 * @param  array $notice Notice markup.
		 * @return void
		 */
		public static function markup( $notice = array() ) {
			wp_enqueue_script( 'astra-notices' );

			do_action( 'astra_notice_before_markup' );

			do_action( "astra_notice_before_markup_{$notice['id']}" );

			?>
			<div id="<?php echo esc_attr( $notice['id'] ); ?>" class="<?php echo esc_attr( $notice['classes'] ); ?>" data-repeat-notice-after="<?php echo esc_attr( $notice['repeat-notice-after'] ); ?>">
				<div class="notice-container">
					<?php do_action( "astra_notice_inside_markup_{$notice['id']}" ); ?>
					<?php echo wp_kses_post( $notice['message'] ); ?>
				</div>
			</div>
			<?php

			do_action( "astra_notice_after_markup_{$notice['id']}" );

			do_action( 'astra_notice_after_markup' );
		}

		/**
		 * Get wrapper classes for a notice.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $notice Notice arguments.
		 * @return array       Notice wrapper classes.
		 */
		private static function get_wrap_classes( $notice ) {
			$classes = array( 'astra-notice', 'notice' );

			if ( $notice['is_dismissible'] ) {
				$classes[] = 'is-dismissible';
			}

			$classes[] = $notice['class'];
			if ( isset( $notice['type'] ) && '' !== $notice['type'] ) {
				$classes[] = 'notice-' . $notice['type'];
			}

			return esc_attr( implode( ' ', $classes ) );
		}

		/**
		 * Get HTML ID for a given notice.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $notice Notice arguments.
		 * @param  int   $key    Notice array index.
		 * @return string HTML if for the notice.
		 */
		private static function get_notice_id( $notice, $key ) {
			if ( isset( $notice['id'] ) && ! empty( $notice['id'] ) ) {
				return $notice['id'];
			}

			return 'astra-notices-id-' . $key;
		}

		/**
		 * Check if the notice is expires.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $notice Notice arguments.
		 * @return boolean
		 */
		private static function is_expired( $notice ) {
			$transient_status = get_transient( $notice['id'] );

			if ( false === $transient_status ) {

				if ( isset( $notice['display-notice-after'] ) && false !== $notice['display-notice-after'] ) {

					if ( 'delayed-notice' !== get_user_meta( get_current_user_id(), $notice['id'], true ) &&
						'notice-dismissed' !== get_user_meta( get_current_user_id(), $notice['id'], true ) ) {
						set_transient( $notice['id'], 'delayed-notice', $notice['display-notice-after'] );
						update_user_meta( get_current_user_id(), $notice['id'], 'delayed-notice' );

						return false;
					}
				}

				// Check the user meta status if current notice is dismissed or delay completed.
				$meta_status = get_user_meta( get_current_user_id(), $notice['id'], true );

				if ( empty( $meta_status ) || 'delayed-notice' === $meta_status ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Get base URL for the astra-notices.
		 *
		 * @return mixed URL.
		 */
		public static function get_url() {
			$path      = wp_normalize_path( dirname( __FILE__ ) );
			$theme_dir = wp_normalize_path( get_template_directory() );

			if ( strpos( $path, $theme_dir ) !== false ) {
				return trailingslashit( get_template_directory_uri() . str_replace( $theme_dir, '', $path ) );
			} else {
				return plugin_dir_url( __FILE__ );
			}
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Notices::get_instance();

endif;
