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

			//add custom column
			add_action( 'manage_post_posts_columns', array( $this, 'add_custom_admin_column' ), 10, 1 );
			//populate column
			add_action( 'manage_posts_custom_column', array( $this, 'manage_custom_admin_columns' ), 10, 2);
        	
        	//output form elements for quickedit interface
        	add_action( 'bulk_edit_custom_box', array( $this, 'display_quick_edit_custom' ), 10, 2 );
			
			//enqueue admin script (for prepopulting fields with JS)
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

		function save_post_bulk_edit() {

			$post_ids = ! empty( $_POST['post_ids'] ) ? $_POST['post_ids'] : array();
			
			if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {

				/**
				 * Get meta options
				 */
				$post_meta = self::get_meta_option();
				
				foreach ( $post_ids as $post_id ) {

					foreach ( $post_meta as $key => $data ) {

						$post_key = str_replace( '-', '_', $key );
						

						// Sanitize values.
						$sanitize_filter = ( isset( $data['sanitize'] ) ) ? $data['sanitize'] : 'FILTER_DEFAULT';


						switch ( $sanitize_filter ) {

							case 'FILTER_SANITIZE_STRING':
									$meta_value = filter_input( INPUT_POST, $post_key, FILTER_SANITIZE_STRING );
								break;

							case 'FILTER_SANITIZE_URL':
									$meta_value = filter_input( INPUT_POST, $post_key, FILTER_SANITIZE_URL );
								break;

							case 'FILTER_SANITIZE_NUMBER_INT':
									$meta_value = filter_input( INPUT_POST, $post_key, FILTER_SANITIZE_NUMBER_INT );
								break;

							default:
									$meta_value = filter_input( INPUT_POST, $post_key, FILTER_DEFAULT );
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
		 * @return void
		 */
		function add_custom_admin_column( $columns ){
		    $new_columns = array();

		    $new_columns['astra-settings'] = 'Astra Settings';

		    return array_merge($columns, $new_columns);
		}

		//customise the data for our custom column, it's here we pull in metadata info for each post. These will be referred to in our JavaScript file for pre-populating our quick-edit screen
		function manage_custom_admin_columns( $column_name, $post_id ){

		    $html = '';

		    if($column_name == 'astra-settings'){
		        
		        $stored = get_post_meta( $post_id );
				$meta 	= self::get_meta_option();

			    // Set stored and override defaults.
				foreach ( $stored as $key => $value ) {
					$meta[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? $stored[ $key ][0] : '';
				}

				// Get defaults.

				/**
				 * Get options
				 */
				$site_sidebar        = ( isset( $meta['site-sidebar-layout']['default'] ) ) ? $meta['site-sidebar-layout']['default'] : 'default';
				$site_content_layout = ( isset( $meta['site-content-layout']['default'] ) ) ? $meta['site-content-layout']['default'] : 'default';
				$site_post_title     = ( isset( $meta['site-post-title']['default'] ) ) ? $meta['site-post-title']['default'] : '';
				$primary_header      = ( isset( $meta['ast-main-header-display']['default'] ) ) ? $meta['ast-main-header-display']['default'] : '';
				$ast_featured_img    = ( isset( $meta['ast-featured-img']['default'] ) ) ? $meta['ast-featured-img']['default'] : '';
				//$footer_bar          = ( isset( $meta['footer-sml-layout']['default'] ) ) ? $meta['footer-sml-layout']['default'] : '';
				//$footer_widgets      = ( isset( $meta['footer-adv-display']['default'] ) ) ? $meta['footer-adv-display']['default'] : '';

		        $html .= '<div id="site-sidebar-layout-' . $post_id . '">';
		            $html .= $site_sidebar;
		        $html .= '</div>';

		        $html .= '<div id="site-content-layout-' . $post_id . '">';
		            $html .= $site_content_layout;
		        $html .= '</div>';

		        $html .= '<div id="site-post-title-' . $post_id . '">';
		            $html .= $site_post_title;
		        $html .= '</div>';

		        $html .= '<div id="ast-main-header-display-' . $post_id . '">';
		            $html .= $primary_header;
		        $html .= '</div>';

		        $html .= '<div id="ast-featured-img-' . $post_id . '">';
		            $html .= $ast_featured_img;
		        $html .= '</div>';
		        
		        // $html .= '<div id="footer-sml-layout-' . $post_id . '">';
		        //     $html .= $footer_bar;
		        // $html .= '</div>';

		        // $html .= '<div id="footer-adv-display-' . $post_id . '">';
		        //     $html .= $footer_widgets;
		        // $html .= '</div>';
		    }

		    echo $html;
		}

		//Display our custom content on the quick-edit interface, no values can be pre-populated (all done in JavaScript)
		function display_quick_edit_custom( $column, $screen ){

		    $html = '';
		    
		    wp_nonce_field( basename( __FILE__ ), 'astra_settings_bulk_meta_box' );

		    if($column == 'astra-settings'){     
		        $html .= '<fieldset class="inline-edit-col ">';
		            $html .= '<div class="inline-edit-col wp-clearfix">';
		            	
		            	$html .= '<h4 class="title">'. __( 'Astra Setting', 'astra' ) .'</h4>';

		                $html .= '<label class="inline-edit" for="site-sidebar-layout">';
			                $html .= '<span class="title">'. __('Sidebar', 'astra') .'</span>';
			                
			                $html .= '<select name="site-sidebar-layout" id="site-sidebar-layout">';
			                    $html .= '<option value="default" selected="selected">'. __( 'Customizer Setting', 'astra' ) .'</option>';
			                    $html .= '<option value="left-sidebar">'. __( 'Left Sidebar', 'astra' ) .'</option>';
			                    $html .= '<option value="right-sidebar">'. __( 'Right Sidebar', 'astra' ) .'</option>';
			                    $html .= '<option value="no-sidebar">'. __( 'No Sidebar', 'astra' ) .'</option>';
			                $html .= '</select>';
			            $html .= '</label>';

			            $html .= '<label class="inline-edit" for="site-content-layout">';
			                $html .= '<span class="title">'. __('Content Layout', 'astra') .'</span>';
			                
			                $html .= '<select name="site-content-layout" id="site-content-layout">';
			                    $html .= '<option value="default" selected="selected">'. __( 'Customizer Setting', 'astra' ) .'</option>';
			                    $html .= '<option value="content-boxed-container">'. __( 'Boxed', 'astra' ) .'</option>';
			                    $html .= '<option value="content-boxed-container">'. __( 'Content Boxed', 'astra' ) .'</option>';
			                    $html .= '<option value="plain-container">'. __( 'Full Width / Contained', 'astra' ) .'</option>';
			                    $html .= '<option value="page-builder">'. __( 'Full Width / Stretched', 'astra' ) .'</option>';
			                $html .= '</select>';
			            $html .= '</label>';

			            $html .= '<label class="inline-edit" for="ast-main-header-display">';
							$html .= '<input type="checkbox" id="ast-main-header-display" name="ast-main-header-display" value="disabled"/>';
							$html .= __( 'Disable Primary Header', 'astra' );
						$html .= '</label>';
						
						$html .= '<label class="inline-edit" for="site-post-title">';
							$html .= '<input type="checkbox" id="site-post-title" name="site-post-title" value="disabled"/>';
							$html .= __( 'Disable Title', 'astra' );
						$html .= '</label>';
						
						$html .= '<label class="inline-edit" for="ast-featured-img">';
							$html .= '<input type="checkbox" id="ast-featured-img" name="ast-featured-img" value="disabled"/>';
							$html .= __( 'Disable Featured Image', 'astra' );
						$html .= '</label>';
						
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
Astra_Meta_Boxes_Bulk_Edit::get_instance();
