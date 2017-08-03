<?php
/**
 * Post Meta Box
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
if ( ! class_exists( 'Astra_Meta_Boxes' ) ) {

	/**
	 * Meta Boxes setup
	 */
	class Astra_Meta_Boxes {

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

			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );

		}

		/**
		 *  Init Metabox
		 */
		public function init_metabox() {

			add_action( 'add_meta_boxes', array( $this, 'setup_meta_box' ) );
			add_action( 'save_post',      array( $this, 'save_meta_box' ) );

			/**
			 * Set metabox options
			 *
			 * @see http://php.net/manual/en/filter.filters.sanitize.php
			 */
			self::$meta_option = apply_filters(
				'astra_meta_box_options', array(
					'ast-main-header-display' => array(
						'sanitize' => 'FILTER_DEFAULT',
					),
					'footer-sml-layout' => array(
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
				)
			);
		}

		/**
		 *  Setup Metabox
		 */
		function setup_meta_box() {

			// Get all posts.
			$post_types = get_post_types();

			// Enable for all posts.
			foreach ( $post_types as $type ) {

				if ( 'attachment' !== $type && 'fl-theme-layout' !== $type ) {
					add_meta_box(
						'astra_settings_meta_box',              // Id.
						__( 'Astra Settings', 'astra' ), // Title.
						array( $this, 'markup_meta_box' ),    // Callback.
						$type,                                // Post_type.
						'side',                               // Context.
						'default'                             // Priority.
					);
				}
			}
		}

		/**
		 * Get metabox options
		 */
		public static function get_meta_option() {
			return self::$meta_option;
		}

		/**
		 * Metabox Markup
		 *
		 * @param  object $post Post object.
		 * @return void
		 */
		function markup_meta_box( $post ) {

			wp_nonce_field( basename( __FILE__ ), 'astra_settings_meta_box' );
			$stored = get_post_meta( $post->ID );

			// Set stored and override defaults.
			foreach ( $stored as $key => $value ) {
				self::$meta_option[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? $stored[ $key ][0] : '';
			}

			// Get defaults.
			$meta = self::get_meta_option();

			/**
			 * Get options
			 */
			$site_sidebar        = ( isset( $meta['site-sidebar-layout']['default'] ) ) ? $meta['site-sidebar-layout']['default'] : 'default';
			$site_content_layout = ( isset( $meta['site-content-layout']['default'] ) ) ? $meta['site-content-layout']['default'] : 'default';
			$site_post_title     = ( isset( $meta['site-post-title']['default'] ) ) ? $meta['site-post-title']['default'] : '';
			$small_footer        = ( isset( $meta['footer-sml-layout']['default'] ) ) ? $meta['footer-sml-layout']['default'] : '';
			$primary_header      = ( isset( $meta['ast-main-header-display']['default'] ) ) ? $meta['ast-main-header-display']['default'] : '';

			do_action( 'astra_meta_box_markup_before', $meta );

			/**
			 * Option: Sidebar
			 */
			?>
			<p class="post-attributes-label-wrapper" >
				<strong> <?php esc_html_e( 'Sidebar', 'astra' ); ?> </strong>
			</p>
			<select name="site-sidebar-layout" id="site-sidebar-layout">
				<option value="default" <?php selected( $site_sidebar, 'default' ); ?> > <?php esc_html_e( 'Customizer Setting', 'astra' ); ?></option>
				<option value="left-sidebar" <?php selected( $site_sidebar, 'left-sidebar' ); ?> > <?php esc_html_e( 'Left Sidebar', 'astra' ); ?></option>
				<option value="right-sidebar" <?php selected( $site_sidebar, 'right-sidebar' ); ?> > <?php esc_html_e( 'Right Sidebar', 'astra' ); ?></option>
				<option value="no-sidebar" <?php selected( $site_sidebar, 'no-sidebar' ); ?> > <?php esc_html_e( 'No Sidebar', 'astra' ); ?></option>
			</select>

			<?php
			/**
			 * Option: Sidebar
			 */
			?>
			<p class="post-attributes-label-wrapper" >
				<strong> <?php esc_html_e( 'Content Layout', 'astra' ); ?> </strong>
			</p>
			<select name="site-content-layout" id="site-content-layout">
				<option value="default" <?php selected( $site_content_layout, 'default' ); ?> > <?php esc_html_e( 'Customizer Setting', 'astra' ); ?></option>
				<option value="boxed-container" <?php selected( $site_content_layout, 'boxed-container' ); ?> > <?php esc_html_e( 'Boxed', 'astra' ); ?></option>
				<option value="content-boxed-container" <?php selected( $site_content_layout, 'content-boxed-container' ); ?> > <?php esc_html_e( 'Content Boxed', 'astra' ); ?></option>
				<option value="plain-container" <?php selected( $site_content_layout, 'plain-container' ); ?> > <?php esc_html_e( 'Full Width / Contained', 'astra' ); ?></option>
				<option value="page-builder" <?php selected( $site_content_layout, 'page-builder' ); ?> > <?php esc_html_e( 'Full Width / Stretched', 'astra' ); ?></option>
			</select>

			<?php
			/**
			 * Option: Small Footer
			 */
			?>
			<p class="post-attributes-label-wrapper">
				<strong> <?php esc_html_e( 'Disable Sections', 'astra' ); ?> </strong>
			</p>
			<span>
				<?php do_action( 'astra_meta_box_markup_disable_sections_before', $meta ); ?>

				<span class="site-post-title-option-wrap">
					<label for="site-post-title">
						<input type="checkbox" id="site-post-title" name="site-post-title" value="disabled" <?php checked( $site_post_title, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Title', 'astra' ); ?>
					</label>
				</span>
				<br />

				<?php

				$footer_sml_layout = astra_get_option( 'footer-sml-layout' );

				if ( 'disabled' != $footer_sml_layout ) {
				?>
				<span class="footer-sml-layout-option-wrap">
					<label for="footer-sml-layout">
						<input type="checkbox" id="footer-sml-layout" name="footer-sml-layout" value="disabled" <?php checked( $small_footer, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Small Footer', 'astra' ); ?>
					</label>
				</span>
				<br />
				<?php } ?>

				<span class="ast-main-header-display-option-wrap">
					<label for="ast-main-header-display">
						<input type="checkbox" id="ast-main-header-display" name="ast-main-header-display" value="disabled" <?php checked( $primary_header, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Primary Header', 'astra' ); ?>
					</label>
				</span>
				<br />

				<?php do_action( 'astra_meta_box_markup_disable_sections_after', $meta ); ?>
			</span>

			<?php

			do_action( 'astra_meta_box_markup_after', $meta );
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
			$is_valid_nonce = ( isset( $_POST['astra_settings_meta_box'] ) && wp_verify_nonce( $_POST['astra_settings_meta_box'], basename( __FILE__ ) ) ) ? true : false;

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
	}
}// End if().

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Meta_Boxes::get_instance();
