<?php
/**
 * Sticky Header Markup
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_ExtTransparenty_Header_Markup' ) ) {

	/**
	 * Sticky Header Markup Initial Setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Transparent_Header_Markup {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
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

			add_action( 'body_class', array( $this, 'add_body_class' ) );

			/* Fixed header markup */
			add_action( 'astra_header', array( $this, 'transparent_header_logo' ), 1 );

			/**
			 * Metabox setup
			 */
			add_filter( 'astra_meta_box_options', array( $this, 'add_options' ) );
			add_action( 'astra_meta_box_markup_after', array( $this, 'add_options_markup' ) );

			add_action( 'astra_customizer_save', array( $this, 'customizer_save' ) );
		}

		/**
		 * Add Body Classes
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		function add_body_class( $classes ) {

			$inherit_desk_logo              = astra_get_option( 'different-transparent-logo', false );
			$transparent_header_logo        = astra_get_option( 'transparent-header-logo', true );
			$transparent_header_logo_retina = astra_get_option( 'transparent-header-retina-logo', true );

			if ( '1' == $inherit_desk_logo && ( '' !== $transparent_header_logo || '' !== $transparent_header_logo_retina ) ) {
				$classes[] = 'ast-replace-site-logo-transparent';
			}

			if ( '1' !== $inherit_desk_logo ) {
				$classes[] = 'ast-inherit-site-logo-transparent';
			}

			/**
			 * Add class 'ast-theme-transparent-header'
			 */

			if ( self::is_transparent_header() ) {
				$classes[] = 'ast-theme-transparent-header';
			}

			return $classes;
		}

		/**
		 * Astra check if transparent header is enabled.
		 *
		 * @return boolean true/false.
		 */
		public static function is_transparent_header() {

			// Transparent Header.
			$enable_trans_header = astra_get_option( 'transparent-header-enable' );
			$trans_meta_option   = astra_get_option_meta( 'theme-transparent-header-meta', 'default' );

			if ( $enable_trans_header ) {

				if ( ( is_archive() || is_search() || is_404() ) && '1' == astra_get_option( 'transparent-header-disable-archive' ) ) {
					$enable_trans_header = false;
				}

				if ( is_front_page() && 'posts' == get_option( 'show_on_front' ) ) {
					$enable_trans_header = false;
				}

				if ( is_home() && '1' == astra_get_option( 'transparent-header-disable-index' ) ) {
					$enable_trans_header = false;
				}

				if ( is_page() && '1' == astra_get_option( 'transparent-header-disable-page' ) ) {
					$enable_trans_header = false;
				}

				if ( is_single() && '1' == astra_get_option( 'transparent-header-disable-posts' ) ) {
					$enable_trans_header = false;
				}
			}

			if ( class_exists( 'Astra_Woocommerce' ) ) {
				if ( is_product() && '1' == astra_get_option( 'transparent-header-disable-woo-products' ) ) {
					$enable_trans_header = false;
				}
			}

			// Force Meta settings to override global settings.
			if ( 'enabled' === $trans_meta_option ) {
				$enable_trans_header = true;
			} elseif ( 'disabled' === $trans_meta_option ) {
				$enable_trans_header = false;
			}

			return apply_filters( 'astra_is_transparent_header', $enable_trans_header );
		}

		/**
		 * Site Header - <header>
		 *
		 * @since 1.0.0
		 */
		function transparent_header_logo() {

			$inherit_desk_logo       = astra_get_option( 'different-transparent-logo', false );
			$transparent_header_logo = astra_get_option( 'transparent-header-logo' );

			if ( self::is_transparent_header() && '1' == $inherit_desk_logo && '' !== $transparent_header_logo ) {
				// Logo For None Effect.
				add_filter( 'astra_has_custom_logo', '__return_true' );
				add_filter( 'get_custom_logo', array( $this, 'transparent_custom_logo' ), 10, 2 );
			}
		}


		/**
		 * Replace transparent header logo.
		 *
		 * @param sting $html Size name.
		 * @param int   $blog_id Icon.
		 *
		 * @return string html markup of logo.
		 */
		function transparent_custom_logo( $html, $blog_id ) {

			$trans_logo                 = astra_get_option( 'transparent-header-logo' );
			$transparent_header_devices = astra_get_option( 'transparent-header-on-devices' );

			if ( '' !== $trans_logo ) {

				/* Replace transparent header logo and width */
				add_filter( 'wp_get_attachment_image_attributes', array( $this, 'replace_trans_header_attr' ), 10, 3 );

				$custom_logo_id = attachment_url_to_postid( $trans_logo );

				$size = 'ast-transparent-logo-size';

				if ( is_customize_preview() ) {
					$size = 'full';
				}

				$html = sprintf(
					'<a href="%1$s" class="custom-logo-link transparent-custom-logo" rel="home" itemprop="url">%2$s</a>',
					esc_url( home_url( '/' ) ),
					wp_get_attachment_image(
						$custom_logo_id,
						$size,
						false,
						array(
							'class' => 'custom-logo',
						)
					)
				);

				if ( 'mobile' === $transparent_header_devices ) {

					$html .= sprintf(
						'<a href="%1$s" class="custom-logo-link ast-transparent-desktop-logo" rel="home" itemprop="url">%2$s</a>',
						esc_url( home_url( '/' ) ),
						wp_get_attachment_image(
							get_theme_mod( 'custom_logo' ),
							$size,
							false,
							array(
								'class' => 'custom-logo',
							)
						)
					);
				}

				if ( 'desktop' === $transparent_header_devices ) {

					$html .= sprintf(
						'<a href="%1$s" class="custom-logo-link ast-transparent-mobile-logo" rel="home" itemprop="url">%2$s</a>',
						esc_url( home_url( '/' ) ),
						wp_get_attachment_image(
							get_theme_mod( 'custom_logo' ),
							$size,
							false,
							array(
								'class' => 'custom-logo',
							)
						)
					);
				}

				remove_filter( 'wp_get_attachment_image_attributes', array( $this, 'replace_trans_header_attr' ) );
			}

			return $html;
		}



		/**
		 * Replace transparent header logo.
		 *
		 * @param array  $attr Image.
		 * @param object $attachment Image obj.
		 * @param sting  $size Size name.
		 *
		 * @return array Image attr.
		 */
		function replace_trans_header_attr( $attr, $attachment, $size ) {

			$trans_logo     = astra_get_option( 'transparent-header-logo' );
			$custom_logo_id = attachment_url_to_postid( $trans_logo );

			if ( $custom_logo_id == $attachment->ID ) {

				$attach_data = array();
				if ( ! is_customize_preview() ) {
					$attach_data = wp_get_attachment_image_src( $attachment->ID, 'ast-transparent-logo-size' );
					if ( isset( $attach_data[0] ) ) {
						$attr['src'] = $attach_data[0];
					}
				}

				$file_type      = wp_check_filetype( $attr['src'] );
				$file_extension = $file_type['ext'];

				if ( 'svg' == $file_extension ) {
					$attr['width']  = '100%';
					$attr['height'] = '100%';
					$attr['class']  = 'astra-logo-svg';
				}

				$diff_retina_logo = astra_get_option( 'different-transparent-retina-logo' );

				if ( '1' == $diff_retina_logo ) {

					$retina_logo = astra_get_option( 'transparent-header-retina-logo' );

					$attr['srcset'] = '';

					if ( apply_filters( 'astra_transparent_header_retina', true ) && '' !== $retina_logo ) {
						$cutom_logo     = wp_get_attachment_image_src( $custom_logo_id, 'full' );
						$cutom_logo_url = $cutom_logo[0];

						if ( astra_check_is_ie() ) {
							// Replace header logo url to retina logo url.
							$attr['src'] = $retina_logo;
						}

						$attr['srcset'] = $cutom_logo_url . ' 1x, ' . $retina_logo . ' 2x';

					}
				}
			}

			return $attr;
		}

		/**
		 * Add Meta Options
		 *
		 * @param array $meta_option Page Meta.
		 * @return array
		 */
		function add_options( $meta_option ) {

			$meta_option['theme-transparent-header-meta'] = array(
				'sanitize' => 'FILTER_DEFAULT',
			);

			return $meta_option;
		}

		/**
		 * Transparent Header Meta Field markup
		 *
		 * Loads appropriate template file based on the style option selected in options panel.
		 *
		 * @param array $meta Page Meta.
		 * @since 1.0.0
		 */
		function add_options_markup( $meta ) {

			/**
			 * Get options
			 */
			$trans_header_meta = ( isset( $meta['theme-transparent-header-meta']['default'] ) ) ? $meta['theme-transparent-header-meta']['default'] : 'default';
			$show_meta_field   = ! astra_check_is_bb_themer_layout();
			?>

			<?php if ( $show_meta_field ) { ?>
				<div class="transparent-header-wrapper">
					<p class="post-attributes-label-wrapper">
						<strong> <?php esc_html_e( 'Transparent Header', 'astra' ); ?> </strong><br/>
					</p>
					<select name="theme-transparent-header-meta" id="theme-transparent-header-meta">
						<option value="default" <?php selected( $trans_header_meta, 'default' ); ?>> <?php esc_html_e( 'Customizer Setting', 'astra' ); ?> </option>
						<option value="enabled" <?php selected( $trans_header_meta, 'enabled' ); ?>> <?php esc_html_e( 'Enabled', 'astra' ); ?> </option>
						<option value="disabled" <?php selected( $trans_header_meta, 'disabled' ); ?>> <?php esc_html_e( 'Disabled', 'astra' ); ?> </option>
					</select>
				</div>
			<?php } ?>

			<?php
		}

		/**
		 * Add Styles Callback
		 */
		function customizer_save() {

			/* Generate Transparent Header Logo */
			$trans_logo = astra_get_option( 'transparent-header-logo' );

			if ( '' !== $trans_logo ) {
				add_filter( 'intermediate_image_sizes_advanced', array( $this, 'transparent_logo_image_sizes' ), 10, 2 );
				$trans_logo_id = attachment_url_to_postid( $trans_logo );
				Astra_Customizer::generate_logo_by_width( $trans_logo_id );
				remove_filter( 'intermediate_image_sizes_advanced', array( $this, 'transparent_logo_image_sizes' ), 10 );
			}
		}

		/**
		 * Add logo image sizes in filter.
		 *
		 * @since 1.0.0
		 * @param array $sizes Sizes.
		 * @param array $metadata attachment data.
		 *
		 * @return array
		 */
		function transparent_logo_image_sizes( $sizes, $metadata ) {

			$logo_width = astra_get_option( 'transparent-header-logo-width' );

			if ( is_array( $sizes ) && '' != $logo_width['desktop'] ) {
				$max_value                          = max( $logo_width );
				$sizes['ast-transparent-logo-size'] = array(
					'width'  => (int) $max_value,
					'height' => 0,
					'crop'   => false,
				);
			}

			return $sizes;
		}
	}
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_Transparent_Header_Markup::get_instance();
