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

			//add custom column
			add_action( 'manage_post_posts_columns', array( $this, 'add_custom_admin_column' ), 10, 1 );
			
			//populate column
			add_action( 'manage_posts_custom_column', array( $this, 'manage_custom_admin_columns' ), 10, 2);
        	
        	//output form elements for quickedit interface
        	add_action( 'quick_edit_custom_box', array( $this, 'display_quick_edit_custom' ), 10, 2 );

        	//enqueue admin script (for prepopulting fields with JS)
        	add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_styles' ) );
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
					'footer-adv-display' => array(
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
					'ast-featured-img' => array(
						'sanitize' => 'FILTER_DEFAULT',
					),
				)
			);
		}

		/**
		 *  Setup Metabox
		 */
		function setup_meta_box() {

			// Get all public posts.
			$post_types = get_post_types(
				array(
					'public' => true,
				)
			);

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
			$footer_bar          = ( isset( $meta['footer-sml-layout']['default'] ) ) ? $meta['footer-sml-layout']['default'] : '';
			$footer_widgets      = ( isset( $meta['footer-adv-display']['default'] ) ) ? $meta['footer-adv-display']['default'] : '';
			$primary_header      = ( isset( $meta['ast-main-header-display']['default'] ) ) ? $meta['ast-main-header-display']['default'] : '';
			$ast_featured_img    = ( isset( $meta['ast-featured-img']['default'] ) ) ? $meta['ast-featured-img']['default'] : '';
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
			 * Option: Disable Sections - Primary Header, Title, Footer Widgets, Footer Bar
			 */
			?>
			<p class="post-attributes-label-wrapper">
				<strong> <?php esc_html_e( 'Disable Sections', 'astra' ); ?> </strong>
			</p>
			<span>
				<?php do_action( 'astra_meta_box_markup_disable_sections_before', $meta ); ?>

				<span class="ast-main-header-display-option-wrap">
					<label for="ast-main-header-display">
						<input type="checkbox" id="ast-main-header-display" name="ast-main-header-display" value="disabled" <?php checked( $primary_header, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Primary Header', 'astra' ); ?>
					</label>
				</span>
				<br />

				<span class="site-post-title-option-wrap">
					<label for="site-post-title">
						<input type="checkbox" id="site-post-title" name="site-post-title" value="disabled" <?php checked( $site_post_title, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Title', 'astra' ); ?>
					</label>
				</span>
				<br />

				<span class="ast-featured-img-option-wrap">
					<label for="ast-featured-img">
						<input type="checkbox" id="ast-featured-img" name="ast-featured-img" value="disabled" <?php checked( $ast_featured_img, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Featured Image', 'astra' ); ?>
					</label>
				</span>
				<br />

				<?php
				$footer_adv_layout = astra_get_option( 'footer-adv' );

				if ( 'disabled' != $footer_adv_layout ) {
				?>
				<span class="footer-adv-display-option-wrap">
					<label for="footer-adv-display">
						<input type="checkbox" id="footer-adv-display" name="footer-adv-display" value="disabled" <?php checked( $footer_widgets, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Footer Widgets', 'astra' ); ?>
					</label>
				</span>
				<br />

				<?php
				}
				$footer_sml_layout = astra_get_option( 'footer-sml-layout' );
				if ( 'disabled' != $footer_sml_layout ) {
				?>
				<span class="footer-sml-layout-option-wrap">
					<label for="footer-sml-layout">
						<input type="checkbox" id="footer-sml-layout" name="footer-sml-layout" value="disabled" <?php checked( $footer_bar, 'disabled' ); ?> />
						<?php esc_html_e( 'Disable Footer Bar', 'astra' ); ?>
					</label>
				</span>
				<br />
				<?php } ?>

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

		/**
		 * Quick edit custom column to hold our data
		 *
		 * @param  number $columns Columns.
		 * @return void
		 */
		function add_custom_admin_column( $columns ){
		    $new_columns = array();

		    $new_columns['site-sidebar-layout'] = 'Sidebar Layout?';

		    return array_merge($columns, $new_columns);
		}

		//customise the data for our custom column, it's here we pull in metadata info for each post. These will be referred to in our JavaScript file for pre-populating our quick-edit screen
		function manage_custom_admin_columns( $column_name, $post_id ){

		    $html = '';

		    if($column_name == 'site-sidebar-layout'){
		        $site_sidebar = get_post_meta( $post_id, 'site-sidebar-layout', true );

		        $html .= '<div id="site-sidebar-layout-' . $post_id . '">';
		            $html .= $site_sidebar;
		        $html .= '</div>';
		    }

		    echo $html;
		}

		//Display our custom content on the quick-edit interface, no values can be pre-populated (all done in JavaScript)
		function display_quick_edit_custom( $column ){

		    $html = '';
		    wp_nonce_field( 'post_metadata', 'post_metadata_field' );

		    if($column == 'site-sidebar-layout'){     
		        $html .= '<fieldset class="inline-edit-col-center ">';
		            $html .= '<div class="inline-edit-group wp-clearfix">';
		                $html .= '<label class="alignleft" for="site-sidebar-layout">Sidebar Layout</label>';
		                $html .= '<select name="site-sidebar-layout" id="site-sidebar-layout">';
		                    $html .= '<option value="default" selected="selected">Customizer Setting</option>';
		                    $html .= '<option value="left-sidebar">Left Sidebar</option>';
		                    $html .= '<option value="right-sidebar">Right Sidebar</option>';
		                    $html .= '<option value="no-sidebar">No Sidebar</option>';
		                $html .= '</select>';
		            $html .= '</div>';
		        $html .= '</fieldset>';    
		    }

		    echo $html;
		}

		function enqueue_admin_scripts_and_styles(){
		    wp_enqueue_script( 'quick-edit-script', ASTRA_THEME_URI . 'inc/assets/js/post-quick-edit-script.js', array('jquery','inline-edit-post' ));
		}
	}
}// End if().

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Meta_Boxes::get_instance();
