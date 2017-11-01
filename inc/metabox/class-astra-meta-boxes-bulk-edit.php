<?php
/**
 * Post Meta Box Bulk Edit
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

/**
 * Meta Boxes setup
 */
if ( ! class_exists( 'Astra_Meta_Boxes_Bulk_Edit' ) ) {

	/**
	 * Meta Boxes setup
	 */
	class Astra_Meta_Boxes_Bulk_Edit {

		/**
		 * Instance
		 *
		 * @var $instance
		 */
		private static $instance;

		/**
		 * Meta Option
		 *
		 * @var $meta_option
		 */
		private static $meta_option;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->setup_bulk_options();

			// Get all public posts.
			$post_types = get_post_types(
				array(
					'public' => true,
				)
			);

			// Enable for all posts.
			foreach ( $post_types as $type ) {

				if ( 'attachment' !== $type && 'fl-theme-layout' !== $type ) {
					// add custom column.
					add_action( 'manage_' . $type . '_posts_columns', array( $this, 'add_custom_admin_column' ), 10, 1 );
					// populate column.
					add_action( 'manage_' . $type . '_posts_custom_column', array( $this, 'manage_custom_admin_columns' ), 10, 2 );
				}
			}

			// output form elements for quickedit interface.
			add_action( 'bulk_edit_custom_box', array( $this, 'display_quick_edit_custom' ), 10, 2 );
			add_action( 'quick_edit_custom_box', array( $this, 'display_quick_edit_custom' ), 10, 2 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_styles' ) );

			add_action( 'save_post',      array( $this, 'save_meta_box' ) );

			add_action( 'wp_ajax_astra_save_post_bulk_edit', array( $this, 'save_post_bulk_edit' ) );
		}

		/**
		 *  Init bulk options
		 */
		function setup_bulk_options() {

			/**
			 * Set metabox options
			 *
			 * @see http://php.net/manual/en/filter.filters.sanitize.php
			 */
			self::$meta_option = apply_filters(
				'astra_meta_box_bulk_edit_options', array(
					'ast-main-header-display' => array(
						'sanitize' => 'FILTER_DEFAULT',
					),
					'ast-featured-img' => array(
						'sanitize' => 'FILTER_DEFAULT',
					),
					'site-post-title' => array(
						'sanitize' => 'FILTER_DEFAULT',
					),
					'site-sidebar-layout' => array(
						'default'  => 'default',
						'sanitize' => 'FILTER_DEFAULT',
					),
					'site-content-layout' => array(
						'default'  => 'default',
						'sanitize' => 'FILTER_DEFAULT',
					),
					'footer-sml-layout' => array(
						'sanitize' => 'FILTER_DEFAULT',
					),
					'footer-adv-display' => array(
						'sanitize' => 'FILTER_DEFAULT',
					),
				)
			);
		}

		/**
		 * Get metabox options
		 */
		public static function get_meta_option() {
			return self::$meta_option;
		}

		/**
		 * Metabox Save
		 *
		 * @param  number $post_id Post ID.
		 * @return void
		 */
		function save_meta_box( $post_id ) {

			// Checks save status.
			$is_autosave    = wp_is_post_autosave( $post_id );
			$is_revision    = wp_is_post_revision( $post_id );
			$is_valid_nonce = ( isset( $_POST['astra_settings_bulk_meta_box'] ) && wp_verify_nonce( $_POST['astra_settings_bulk_meta_box'], basename( __FILE__ ) ) ) ? true : false;

			// Exits script depending on save status.
			if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
				return;
			}

			/**
			 * Get meta options
			 */
			$post_meta = self::get_meta_option();

			foreach ( $post_meta as $key => $data ) {

				// Sanitize values.
				$sanitize_filter = ( isset( $data['sanitize'] ) ) ? $data['sanitize'] : 'FILTER_DEFAULT';

				switch ( $sanitize_filter ) {

					case 'FILTER_SANITIZE_STRING':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
						break;

					case 'FILTER_SANITIZE_URL':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_URL );
						break;

					case 'FILTER_SANITIZE_NUMBER_INT':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT );
						break;

					default:
							$meta_value = filter_input( INPUT_POST, $key, FILTER_DEFAULT );
						break;
				}

				// Store values.
				if ( $meta_value ) {
					update_post_meta( $post_id, $key, $meta_value );
				} else {
					delete_post_meta( $post_id, $key );
				}
			}

		}

		/**
		 * Save bulk edit options.
		 */
		function save_post_bulk_edit() {

			$post_ids = ! empty( $_POST['post'] ) ? $_POST['post'] : array();

			if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {

				/**
				 * Get meta options
				 */
				$post_meta = self::get_meta_option();

				foreach ( $post_ids as $post_id ) {

					foreach ( $post_meta as $key => $data ) {

						// Sanitize values.
						$sanitize_filter = ( isset( $data['sanitize'] ) ) ? $data['sanitize'] : 'FILTER_DEFAULT';

						switch ( $sanitize_filter ) {

							case 'FILTER_SANITIZE_STRING':
									$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
								break;

							case 'FILTER_SANITIZE_URL':
									$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_URL );
								break;

							case 'FILTER_SANITIZE_NUMBER_INT':
									$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT );
								break;

							default:
									$meta_value = filter_input( INPUT_POST, $key, FILTER_DEFAULT );
								break;
						}

						// Store values.
						if ( $meta_value ) {
							update_post_meta( $post_id, $key, $meta_value );
						} else {
							delete_post_meta( $post_id, $key );
						}
					}
				}
			}

			die();
		}

		/**
		 * Quick edit custom column to hold our data
		 *
		 * @param  number $columns Columns.
		 * @return array Column array.
		 */
		function add_custom_admin_column( $columns ) {
			$new_columns = array();

			$new_columns['astra-settings'] = 'Astra Settings';

			return array_merge( $columns, $new_columns );
		}

		/**
		 * Customize the data for our custom column,
		 * It's here we pull in metadata info for each post.
		 * These will be referred to in our JavaScript file for pre-populating our quick-edit screen
		 *
		 * @param  string $column_name Column name.
		 * @param  number $post_id Post ID.
		 * @return void
		 */
		function manage_custom_admin_columns( $column_name, $post_id ) {

			if ( 'astra-settings' == $column_name ) {

				$html = '';

				$stored = get_post_meta( $post_id );
				$meta   = self::get_meta_option();

				// Set stored and override defaults.
				foreach ( $stored as $key => $value ) {
					if ( array_key_exists( $key, $meta ) ) {
						$meta[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? $stored[ $key ][0] : '';
					}
				}

				foreach ( $meta as $key => $value ) {

					$default_value = '';

					$html .= '<div class="astra-bulk-edit-field-' . $post_id . '" data-name="' . $key . '"  id="' . $key . '-' . $post_id . '">';

					if ( isset( $meta[ $key ]['default'] ) ) {
						$default_value = $meta[ $key ]['default'];
					}

					$html .= $default_value;
					$html .= '</div>';
				}

				echo $html;
			}

		}

		/**
		 * Display our custom content on the quick-edit interface,
		 * no values can be pre-populated (all done in JavaScript)
		 *
		 * @param  string $column Column name.
		 * @param  string $screen Screen.
		 * @return void
		 */
		function display_quick_edit_custom( $column, $screen ) {

			$html = '';

				wp_nonce_field( basename( __FILE__ ), 'astra_settings_bulk_meta_box' );

			if ( 'astra-settings' == $column ) { ?>
				<fieldset class="astra-bulk-settings inline-edit-col ">
					<div class="inline-edit-col wp-clearfix">
			
									<h4 class="title"><?php esc_html_e( 'Astra Setting', 'astra' ); ?></h4>

						<div class="ast-float-left inline-edit-col-left wp-clearfix">
							<label class="inline-edit" for="site-sidebar-layout">
								<span class="title"><?php esc_html_e( 'Sidebar', 'astra' ); ?></span>
				
												<select name="site-sidebar-layout" id="site-sidebar-layout">
									<option value="default" selected="selected"><?php _e( 'Customizer Setting', 'astra' ); ?></option>
									<option value="left-sidebar"><?php _e( 'Left Sidebar', 'astra' ); ?></option>
									<option value="right-sidebar"><?php _e( 'Right Sidebar', 'astra' ); ?></option>
									<option value="no-sidebar"><?php _e( 'No Sidebar', 'astra' ); ?></option>
								</select>
							</label>

							<label class="inline-edit" for="site-content-layout">
								<span class="title"><?php esc_html_e( 'Content Layout', 'astra' ); ?></span>
				
												<select name="site-content-layout" id="site-content-layout">
									<option value="default" selected="selected"><?php _e( 'Customizer Setting', 'astra' ); ?></option>
									<option value="content-boxed-container"><?php _e( 'Boxed', 'astra' ); ?></option>
									<option value="content-boxed-container"><?php _e( 'Content Boxed', 'astra' ); ?></option>
									<option value="plain-container"><?php _e( 'Full Width / Contained', 'astra' ); ?></option>
									<option value="page-builder"><?php _e( 'Full Width / Stretched', 'astra' ); ?></option>
								</select>
							</label>

							<?php do_action( 'astra_meta_bulk_edit_left_bottom' ); ?>
						</div>

						<div class="ast-float-left inline-edit-col-center wp-clearfix">
							<label class="inline-edit" for="ast-main-header-display">
								<input type="checkbox" id="ast-main-header-display" name="ast-main-header-display" value="disabled"/>
								<?php _e( 'Disable Primary Header', 'astra' ); ?>
							</label>
							
							<label class="inline-edit" for="site-post-title">
								<input type="checkbox" id="site-post-title" name="site-post-title" value="disabled"/>
								<?php _e( 'Disable Title', 'astra' ); ?>
							</label>
							
							<label class="inline-edit" for="ast-featured-img">
								<input type="checkbox" id="ast-featured-img" name="ast-featured-img" value="disabled"/>
								<?php _e( 'Disable Featured Image', 'astra' ); ?>
							</label>

							<?php
							$footer_adv_layout = astra_get_option( 'footer-adv' );
							if ( 'disabled' != $footer_adv_layout ) {
							?>
								<label class="inline-edit" for="footer-adv-display">
									<input type="checkbox" id="footer-adv-display" name="footer-adv-display" value="disabled"/>
									<?php _e( 'Disable Footer Widgets', 'astra' ); ?>
								</label>
							<?php } ?>

							<?php
							$footer_sml_layout = astra_get_option( 'footer-sml-layout' );
							if ( 'disabled' != $footer_sml_layout ) {
							?>
								<label class="inline-edit" for="footer-sml-layout">
									<input type="checkbox" id="footer-sml-layout" name="footer-sml-layout" value="disabled"/>
									<?php _e( 'Disable Footer Bar', 'astra' ); ?>
								</label>
							<?php } ?>

							<?php do_action( 'astra_meta_bulk_edit_center_bottom' ); ?>
						</div>
					</div>
				</fieldset>;
			<?php
			}
		}

		/**
		 *  Quick edit and bulk edit script function.
		 */
		function enqueue_admin_scripts_and_styles() {
			wp_enqueue_script( 'quick-edit-script', ASTRA_THEME_URI . 'inc/assets/js/post-quick-edit-script.js', array( 'jquery', 'inline-edit-post' ) );
		}
	}
}// End if().

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Meta_Boxes_Bulk_Edit::get_instance();
