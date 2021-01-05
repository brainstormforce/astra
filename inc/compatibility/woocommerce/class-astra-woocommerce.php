<?php
/**
 * WooCommerce Compatibility File.
 *
 * @link https://woocommerce.com/
 *
 * @package Astra
 */

// If plugin - 'WooCommerce' not exist then return.
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Astra WooCommerce Compatibility
 */
if ( ! class_exists( 'Astra_Woocommerce' ) ) :

	/**
	 * Astra WooCommerce Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Woocommerce {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			require_once ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/woocommerce-common-functions.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			add_filter( 'woocommerce_enqueue_styles', array( $this, 'woo_filter_style' ) );

			add_filter( 'astra_theme_defaults', array( $this, 'theme_defaults' ) );

			add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );

			// Register Store Sidebars.
			add_action( 'widgets_init', array( $this, 'store_widgets_init' ), 15 );
			// Replace Store Sidebars.
			add_filter( 'astra_get_sidebar', array( $this, 'replace_store_sidebar' ) );
			// Store Sidebar Layout.
			add_filter( 'astra_page_layout', array( $this, 'store_sidebar_layout' ) );
			// Store Content Layout.
			add_filter( 'astra_get_content_layout', array( $this, 'store_content_layout' ) );

			add_action( 'woocommerce_before_main_content', array( $this, 'before_main_content_start' ) );
			add_action( 'woocommerce_after_main_content', array( $this, 'before_main_content_end' ) );
			add_filter( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
			add_action( 'wp', array( $this, 'shop_customization' ), 5 );
			add_action( 'wp_head', array( $this, 'single_product_customization' ), 5 );
			add_action( 'wp', array( $this, 'woocommerce_init' ), 1 );
			add_action( 'wp', array( $this, 'woocommerce_checkout' ) );
			add_action( 'wp', array( $this, 'shop_meta_option' ), 1 );
			add_action( 'wp', array( $this, 'cart_page_upselles' ) );

			add_filter( 'loop_shop_columns', array( $this, 'shop_columns' ) );
			add_filter( 'loop_shop_per_page', array( $this, 'shop_no_of_products' ) );
			add_filter( 'body_class', array( $this, 'shop_page_products_item_class' ) );
			add_filter( 'post_class', array( $this, 'single_product_class' ) );
			add_filter( 'woocommerce_product_get_rating_html', array( $this, 'rating_markup' ), 10, 3 );
			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );

			// Add Cart icon in Menu.
			add_filter( 'astra_get_dynamic_header_content', array( $this, 'astra_header_cart' ), 10, 3 );

			// Add Cart option in dropdown.
			add_filter( 'astra_header_section_elements', array( $this, 'header_section_elements' ) );

			// Cart fragment.
			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
				add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );
			} else {
				add_filter( 'add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );
			}

			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_flip_image' ), 10 );
			add_filter( 'woocommerce_subcategory_count_html', array( $this, 'subcategory_count_markup' ), 10, 2 );

			add_action( 'customize_register', array( $this, 'customize_register' ), 2 );

			add_filter( 'woocommerce_get_stock_html', 'astra_woo_product_in_stock', 10, 2 );

			add_filter( 'astra_schema_body', array( $this, 'remove_body_schema' ) );

			// Header Cart Icon.
			add_action( 'astra_woo_header_cart_icons_before', array( $this, 'header_cart_icon_markup' ) );

			add_action( 'astra_cart_in_menu_class', array( $this, 'header_cart_icon_class' ), 99 );

		}

		/**
		 * Header Cart Extra Icons markup
		 *
		 * @return void;
		 */
		public function header_cart_icon_markup() {

			if ( ! Astra_Builder_Helper::$is_header_footer_builder_active && ! defined( 'ASTRA_EXT_VER' ) ) {
				return;
			}

			$icon               = astra_get_option( 'woo-header-cart-icon' );
			$cart_total_display = astra_get_option( 'woo-header-cart-total-display' );
			$cart_count_display = apply_filters( 'astra_header_cart_count', true );
			$cart_title_display = astra_get_option( 'woo-header-cart-title-display' );
			$cart_title         = apply_filters( 'astra_header_cart_title', __( 'Cart', 'astra' ) );

			$cart_title_markup = '<span class="ast-woo-header-cart-title">' . esc_html( $cart_title ) . '</span>';
			$cart_total_markup = '';
			if ( null !== WC()->cart ) {
				$cart_total_markup = '<span class="ast-woo-header-cart-total">' . WC()->cart->get_cart_subtotal() . '</span>';
			}

			// Cart Title & Cart Cart total markup.
			$cart_info_markup = sprintf(
				'<span class="ast-woo-header-cart-info-wrap">
						%1$s
						%2$s
						%3$s
					</span>',
				( $cart_title_display ) ? $cart_title_markup : '',
				( $cart_total_display && $cart_title_display ) ? '/' : '',
				( $cart_total_display ) ? $cart_total_markup : ''
			);

			$cart_contents_count = 0;
			if ( null !== WC()->cart ) {
				$cart_contents_count = WC()->cart->get_cart_contents_count();
			}

			// Cart Icon markup with total number of items.
			$cart_icon = sprintf(
				'<i class="astra-icon ast-icon-shopping-%1$s %2$s"	
							%3$s
						></i>',
				( $icon ) ? $icon : '',
				( $cart_count_display ) ? '' : 'no-cart-total',
				( $cart_count_display ) ? 'data-cart-total="' . $cart_contents_count . '"' : ''
			);

			// Theme's default icon with cart title and cart total.
			if ( 'default' == $icon || ! defined( 'ASTRA_EXT_VER' ) || ( defined( 'ASTRA_EXT_VER' ) && ! Astra_Ext_Extension::is_active( 'woocommerce' ) ) ) {
				// Cart Total or Cart Title enable then only add markup.
				if ( $cart_title_display || $cart_total_display ) {
					echo $cart_info_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			} else {

				// Remove Default cart icon added by theme.
				add_filter( 'astra_woo_default_header_cart_icon', '__return_false' );

				/* translators: 1: Cart Title Markup, 2: Cart Icon Markup */
				printf(
					'<div class="ast-addon-cart-wrap">
							%1$s
							%2$s
					</div>',
					( $cart_title_display || $cart_total_display ) ? $cart_info_markup : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					( $cart_icon ) ? $cart_icon : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		/**
		 * Header Cart Icon Class
		 *
		 * @param array $classes Default argument array.
		 *
		 * @return array;
		 */
		public function header_cart_icon_class( $classes ) {

			if ( ! Astra_Builder_Helper::$is_header_footer_builder_active && ! defined( 'ASTRA_EXT_VER' ) ) {
				return $classes;
			}

			$header_cart_icon_style = astra_get_option( 'woo-header-cart-icon-style' );

			$classes[]                  = 'ast-menu-cart-' . $header_cart_icon_style;
			$header_cart_icon_has_color = astra_get_option( 'woo-header-cart-icon-color' );
			if ( ! empty( $header_cart_icon_has_color ) && ( 'none' !== $header_cart_icon_style ) ) {
				$classes[] = 'ast-menu-cart-has-color';
			}

			return $classes;
		}

		/**
		 * Remove body schema when using WooCommerce template.
		 * WooCommerce adds it's own product schema hence schema data from Astra should be disabled here.
		 *
		 * @since 1.8.0
		 * @param String $schema Schema markup.
		 * @return String
		 */
		public function remove_body_schema( $schema ) {
			if ( is_woocommerce() ) {
				$schema = '';
			}

			return $schema;
		}

		/**
		 * Rating Markup
		 *
		 * @since 1.2.2
		 * @param  string $html  Rating Markup.
		 * @param  float  $rating Rating being shown.
		 * @param  int    $count  Total number of ratings.
		 * @return string
		 */
		public function rating_markup( $html, $rating, $count ) {

			if ( 0 == $rating ) {
				$html  = '<div class="star-rating">';
				$html .= wc_get_star_rating_html( $rating, $count );
				$html .= '</div>';
			}
			return $html;
		}

		/**
		 * Cart Page Upselles products.
		 *
		 * @return void
		 */
		public function cart_page_upselles() {

			$upselles_enabled = astra_get_option( 'enable-cart-upsells' );
			if ( ! $upselles_enabled ) {
				remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			}
		}

		/**
		 * Subcategory Count Markup
		 *
		 * @param  array $styles  Css files.
		 *
		 * @return array
		 */
		public function woo_filter_style( $styles ) {

			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$css_uri = ASTRA_THEME_URI . 'assets/css/' . $dir_name . '/compatibility/woocommerce/';
			$key     = 'astra-woocommerce';

			// Register & Enqueue Styles.
			// Generate CSS URL.
			$css_file = $css_uri . '' . $file_prefix . '.css';

			$styles = array(
				'woocommerce-layout'      => array(
					'src'     => $css_uri . 'woocommerce-layout' . $file_prefix . '.css',
					'deps'    => '',
					'version' => ASTRA_THEME_VERSION,
					'media'   => 'all',
					'has_rtl' => true,
				),
				'woocommerce-smallscreen' => array(
					'src'     => $css_uri . 'woocommerce-smallscreen' . $file_prefix . '.css',
					'deps'    => 'woocommerce-layout',
					'version' => ASTRA_THEME_VERSION,
					'media'   => 'only screen and (max-width: ' . apply_filters( 'woocommerce_style_smallscreen_breakpoint', astra_get_tablet_breakpoint() . 'px' ) . ')',
					'has_rtl' => true,
				),
				'woocommerce-general'     => array(
					'src'     => $css_uri . 'woocommerce' . $file_prefix . '.css',
					'deps'    => '',
					'version' => ASTRA_THEME_VERSION,
					'media'   => 'all',
					'has_rtl' => true,
				),
			);

			return $styles;
		}

		/**
		 * Subcategory Count Markup
		 *
		 * @param  mixed  $content  Count Markup.
		 * @param  object $category Object of Category.
		 * @return mixed
		 */
		public function subcategory_count_markup( $content, $category ) {

			$content = sprintf( // WPCS: XSS OK.
					/* translators: 1: number of products */
				_nx( '%1$s Product', '%1$s Products', $category->count, 'product categories', 'astra' ),
				number_format_i18n( $category->count )
			);

			return '<mark class="count">' . $content . '</mark>';
		}

		/**
		 * Product Flip Image
		 */
		public function product_flip_image() {

			global $product;

			$hover_style = astra_get_option( 'shop-hover-style' );

			if ( 'swap' === $hover_style ) {

				$attachment_ids = $product->get_gallery_image_ids();

				if ( $attachment_ids ) {

					$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );

					echo apply_filters( 'astra_woocommerce_product_flip_image', wp_get_attachment_image( reset( $attachment_ids ), $image_size, false, array( 'class' => 'show-on-hover' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}

		/**
		 * Theme Defaults.
		 *
		 * @param array $defaults Array of options value.
		 * @return array
		 */
		public function theme_defaults( $defaults ) {

			// Container.
			$defaults['woocommerce-content-layout'] = 'plain-container';

			// Sidebar.
			$defaults['woocommerce-sidebar-layout']    = 'no-sidebar';
			$defaults['single-product-sidebar-layout'] = 'default';

			/* Shop */
			$defaults['shop-grids']             = array(
				'desktop' => 4,
				'tablet'  => 3,
				'mobile'  => 2,
			);
			$defaults['shop-no-of-products']    = '12';
			$defaults['shop-product-structure'] = array(
				'category',
				'title',
				'ratings',
				'price',
				'add_cart',
			);
			$defaults['shop-hover-style']       = '';

			/* Single */
			$defaults['single-product-breadcrumb-disable'] = false;

			/* Cart */
			$defaults['enable-cart-upsells'] = true;

			$defaults['shop-archive-width']     = 'default';
			$defaults['shop-archive-max-width'] = 1200;

			return $defaults;
		}

		/**
		 * Update Shop page grid
		 *
		 * @param  int $col Shop Column.
		 * @return int
		 */
		public function shop_columns( $col ) {

			$col = astra_get_option( 'shop-grids' );
			return $col['desktop'];
		}

		/**
		 * Check if the current page is a Product Subcategory page or not.
		 *
		 * @param integer $category_id Current page Category ID.
		 * @return boolean
		 */
		public function astra_woo_is_subcategory( $category_id = null ) {
			if ( is_tax( 'product_cat' ) ) {
				if ( empty( $category_id ) ) {
					$category_id = get_queried_object_id();
				}
				$category = get_term( get_queried_object_id(), 'product_cat' );
				if ( empty( $category->parent ) ) {
					return false;
				} else {
					return true;
				}
			}
			return false;
		}

		/**
		 * Update Shop page grid
		 *
		 * @return int
		 */
		public function shop_no_of_products() {
			$taxonomy_page_display = get_option( 'woocommerce_category_archive_display', false );
			if ( is_product_taxonomy() && 'subcategories' === $taxonomy_page_display ) {
				if ( $this->astra_woo_is_subcategory() ) {
					$products = astra_get_option( 'shop-no-of-products' );
					return $products;
				}
				$products = wp_count_posts( 'product' )->publish;
			} else {
				$products = astra_get_option( 'shop-no-of-products' );
			}
			return $products;
		}

		/**
		 * Add products item class on shop page
		 *
		 * @param Array $classes product classes.
		 *
		 * @return array.
		 */
		public function shop_page_products_item_class( $classes = '' ) {

			if ( is_shop() || is_product_taxonomy() ) {
				$shop_grid = astra_get_option( 'shop-grids' );
				$classes[] = 'columns-' . $shop_grid['desktop'];
				$classes[] = 'tablet-columns-' . $shop_grid['tablet'];
				$classes[] = 'mobile-columns-' . $shop_grid['mobile'];

				$classes[] = 'ast-woo-shop-archive';
			}
			// Cart menu is emabled.
			$rt_section = astra_get_option( 'header-main-rt-section' );

			if ( 'woocommerce' === $rt_section ) {
				$classes[] = 'ast-woocommerce-cart-menu';
			}

			return $classes;
		}

		/**
		 * Add class on single product page
		 *
		 * @param Array $classes product classes.
		 *
		 * @return array.
		 */
		public function single_product_class( $classes ) {

			if ( is_product() && 0 == get_post_meta( get_the_ID(), '_wc_review_count', true ) ) {
				$classes[] = 'ast-woo-product-no-review';
			}

			if ( is_shop() || is_product_taxonomy() ) {
				$hover_style = astra_get_option( 'shop-hover-style' );

				if ( '' !== $hover_style ) {
					$classes[] = 'astra-woo-hover-' . $hover_style;
				}
			}

			return $classes;
		}

		/**
		 * Update woocommerce related product numbers
		 *
		 * @param  array $args Related products array.
		 * @return array
		 */
		public function related_products_args( $args ) {

			$col                    = astra_get_option( 'shop-grids' );
			$args['posts_per_page'] = $col['desktop'];
			return $args;
		}

		/**
		 * Setup theme
		 *
		 * @since 1.0.3
		 */
		public function setup_theme() {

			// WooCommerce.
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}

		/**
		 * Store widgets init.
		 */
		public function store_widgets_init() {
			register_sidebar(
				apply_filters(
					'astra_woocommerce_shop_sidebar_init',
					array(
						'name'          => esc_html__( 'WooCommerce Sidebar', 'astra' ),
						'id'            => 'astra-woo-shop-sidebar',
						'description'   => __( 'This sidebar will be used on Product archive, Cart, Checkout and My Account pages.', 'astra' ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					)
				)
			);
			register_sidebar(
				apply_filters(
					'astra_woocommerce_single_sidebar_init',
					array(
						'name'          => esc_html__( 'Product Sidebar', 'astra' ),
						'id'            => 'astra-woo-single-sidebar',
						'description'   => __( 'This sidebar will be used on Single Product page.', 'astra' ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					)
				)
			);
		}

		/**
		 * Assign shop sidebar for store page.
		 *
		 * @param String $sidebar Sidebar.
		 *
		 * @return String $sidebar Sidebar.
		 */
		public function replace_store_sidebar( $sidebar ) {

			if ( is_shop() || is_product_taxonomy() || is_checkout() || is_cart() || is_account_page() ) {
				$sidebar = 'astra-woo-shop-sidebar';
			} elseif ( is_product() ) {
				$sidebar = 'astra-woo-single-sidebar';
			}

			return $sidebar;
		}

		/**
		 * WooCommerce Container
		 *
		 * @param String $sidebar_layout Layout type.
		 *
		 * @return String $sidebar_layout Layout type.
		 */
		public function store_sidebar_layout( $sidebar_layout ) {

			if ( is_shop() || is_product_taxonomy() || is_checkout() || is_cart() || is_account_page() ) {

				$woo_sidebar = astra_get_option( 'woocommerce-sidebar-layout' );

				if ( 'default' !== $woo_sidebar ) {

					$sidebar_layout = $woo_sidebar;
				}

				if ( is_shop() ) {
					$shop_page_id = get_option( 'woocommerce_shop_page_id' );
					$shop_sidebar = get_post_meta( $shop_page_id, 'site-sidebar-layout', true );
				} elseif ( is_product_taxonomy() ) {
					$shop_sidebar = 'default';
				} else {
					$shop_sidebar = astra_get_option_meta( 'site-sidebar-layout', '', true );
				}

				if ( 'default' !== $shop_sidebar && ! empty( $shop_sidebar ) ) {
					$sidebar_layout = $shop_sidebar;
				}
			}

			return $sidebar_layout;
		}
		/**
		 * WooCommerce Container
		 *
		 * @param String $layout Layout type.
		 *
		 * @return String $layout Layout type.
		 */
		public function store_content_layout( $layout ) {

			if ( is_woocommerce() || is_checkout() || is_cart() || is_account_page() ) {

				$woo_layout = astra_get_option( 'woocommerce-content-layout' );

				if ( 'default' !== $woo_layout ) {

					$layout = $woo_layout;
				}

				if ( is_shop() ) {
					$shop_page_id = get_option( 'woocommerce_shop_page_id' );
					$shop_layout  = get_post_meta( $shop_page_id, 'site-content-layout', true );
				} elseif ( is_product_taxonomy() ) {
					$shop_layout = 'default';
				} else {
					$shop_layout = astra_get_option_meta( 'site-content-layout', '', true );
				}

				if ( 'default' !== $shop_layout && ! empty( $shop_layout ) ) {
					$layout = $shop_layout;
				}
			}

			return apply_filters( 'astra_get_store_content_layout', $layout );
		}

		/**
		 * Shop Page Meta
		 *
		 * @return void
		 */
		public function shop_meta_option() {

			// Page Title.
			if ( is_shop() ) {

				$shop_page_id        = get_option( 'woocommerce_shop_page_id' );
				$shop_title          = get_post_meta( $shop_page_id, 'site-post-title', true );
				$main_header_display = get_post_meta( $shop_page_id, 'ast-main-header-display', true );
				$footer_layout       = get_post_meta( $shop_page_id, 'footer-sml-layout', true );

				if ( 'disabled' === $shop_title ) {
					add_filter( 'woocommerce_show_page_title', '__return_false' );
				}

				if ( 'disabled' === $main_header_display ) {
					remove_action( 'astra_masthead', 'astra_masthead_primary_template' );
				}

				if ( 'disabled' === $footer_layout ) {
					remove_action( 'astra_footer_content', 'astra_footer_small_footer_template', 5 );
				}
			}
		}


		/**
		 * Shop customization.
		 *
		 * @return void
		 */
		public function shop_customization() {

			if ( ! apply_filters( 'astra_woo_shop_product_structure_override', false ) ) {

				add_action( 'woocommerce_before_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_start', 6 );
				/**
				 * Add sale flash before shop loop.
				 */
				add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 9 );

				add_action( 'woocommerce_after_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_end', 8 );
				/**
				 * Add Out of Stock to the Shop page
				 */
				add_action( 'woocommerce_shop_loop_item_title', 'astra_woo_shop_out_of_stock', 8 );

				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

				/**
				 * Shop Page Product Content Sorting
				 */
				add_action( 'woocommerce_after_shop_loop_item', 'astra_woo_woocommerce_shop_product_content' );
			}
		}

		/**
		 * Checkout customization.
		 *
		 * @return void
		 */
		public function woocommerce_checkout() {

			if ( is_admin() ) {
				return;
			}

			if ( ! apply_filters( 'astra_woo_shop_product_structure_override', false ) ) {

				/**
				 * Checkout Page
				 */
				add_action( 'woocommerce_checkout_billing', array( WC()->checkout(), 'checkout_form_shipping' ) );
			}

			// Checkout Page.
			remove_action( 'woocommerce_checkout_shipping', array( WC()->checkout(), 'checkout_form_shipping' ) );
		}

		/**
		 * Single product customization.
		 *
		 * @return void
		 */
		public function single_product_customization() {

			if ( ! is_product() ) {
				return;
			}

			add_filter( 'woocommerce_product_description_heading', '__return_false' );
			add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );

			// Breadcrumb.
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
			if ( ! astra_get_option( 'single-product-breadcrumb-disable' ) ) {
				add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 2 );
			}
		}

		/**
		 * Remove Woo-Commerce Default actions
		 */
		public function woocommerce_init() {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		}

		/**
		 * Add start of wrapper
		 */
		public function before_main_content_start() {
			$site_sidebar = astra_page_layout();
			if ( 'left-sidebar' == $site_sidebar ) {
				get_sidebar();
			}
			?>
			<div id="primary" class="content-area primary">

				<?php astra_primary_content_top(); ?>

				<main id="main" class="site-main">
					<div class="ast-woocommerce-container">
			<?php
		}

		/**
		 * Add end of wrapper
		 */
		public function before_main_content_end() {
			?>
					</div> <!-- .ast-woocommerce-container -->
				</main> <!-- #main -->

				<?php astra_primary_content_bottom(); ?>

			</div> <!-- #primary -->
			<?php
			$site_sidebar = astra_page_layout();
			if ( 'right-sidebar' == $site_sidebar ) {
				get_sidebar();
			}
		}

		/**
		 * Enqueue styles
		 *
		 * @since 1.0.31
		 */
		public function add_styles() {

			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$css_uri = ASTRA_THEME_URI . 'assets/css/' . $dir_name . '/';

			$new_style = 'compatibility/woocommerce-new';
			$new_key   = 'astra-woocommerce-new';

			// Register & Enqueue Styles.
			// Generate CSS URL.
			$new_css_file = $css_uri . $new_style . $file_prefix . '.css';

			/**
			 * - Variable Declaration
			 */
			$is_site_rtl  = is_rtl();
			$theme_color  = astra_get_option( 'theme-color' );
			$link_color   = astra_get_option( 'link-color', $theme_color );
			$text_color   = astra_get_option( 'text-color' );
			$link_h_color = astra_get_option( 'link-h-color' );

			$btn_color = astra_get_option( 'button-color' );
			if ( empty( $btn_color ) ) {
				$btn_color = astra_get_foreground_color( $theme_color );
			}

			$btn_h_color = astra_get_option( 'button-h-color' );
			if ( empty( $btn_h_color ) ) {
				$btn_h_color = astra_get_foreground_color( $link_h_color );
			}
			$btn_bg_color   = astra_get_option( 'button-bg-color', '', $theme_color );
			$btn_bg_h_color = astra_get_option( 'button-bg-h-color', '', $link_h_color );

			$btn_border_radius = astra_get_option( 'button-radius' );
			$theme_btn_padding = astra_get_option( 'theme-button-padding' );

			$cart_h_color = astra_get_foreground_color( $link_h_color );

			$site_content_width         = astra_get_option( 'site-content-width', 1200 );
			$woo_shop_archive_width     = astra_get_option( 'shop-archive-width' );
			$woo_shop_archive_max_width = astra_get_option( 'shop-archive-max-width' );

			// global button border settings.
			$global_custom_button_border_size = astra_get_option( 'theme-button-border-group-border-size' );
			$btn_border_color                 = astra_get_option( 'theme-button-border-group-border-color' );
			$btn_border_h_color               = astra_get_option( 'theme-button-border-group-border-h-color' );

			$css_output = array(
				'.woocommerce span.onsale, .wc-block-grid__product .wc-block-grid__product-onsale' => array(
					'background-color' => $theme_color,
					'color'            => astra_get_foreground_color( $theme_color ),
				),
				'.woocommerce a.button, .woocommerce button.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce #respond input#submit, .woocommerce button.button.alt.disabled, .wc-block-grid__products .wc-block-grid__product .wp-block-button__link, .wc-block-grid__product-onsale' => array(
					'color'            => $btn_color,
					'border-color'     => $btn_bg_color,
					'background-color' => $btn_bg_color,
				),
				'.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce .woocommerce-message a.button:hover,.woocommerce #respond input#submit:hover,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce input.button:hover, .woocommerce button.button.alt.disabled:hover, .wc-block-grid__products .wc-block-grid__product .wp-block-button__link:hover' => array(
					'color'            => $btn_h_color,
					'border-color'     => $btn_bg_h_color,
					'background-color' => $btn_bg_h_color,
				),
				'.woocommerce-message, .woocommerce-info' => array(
					'border-top-color' => $link_color,
				),
				'.woocommerce-message::before,.woocommerce-info::before' => array(
					'color' => $link_color,
				),
				'.woocommerce ul.products li.product .price, .woocommerce div.product p.price, .woocommerce div.product span.price, .widget_layered_nav_filters ul li.chosen a, .woocommerce-page ul.products li.product .ast-woo-product-category, .wc-layered-nav-rating a' => array(
					'color' => $text_color,
				),
				// Form Fields, Pagination border Color.
				'.woocommerce nav.woocommerce-pagination ul,.woocommerce nav.woocommerce-pagination ul li' => array(
					'border-color' => $link_color,
				),
				'.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current' => array(
					'background' => $link_color,
					'color'      => $btn_color,
				),
				'.woocommerce-MyAccount-navigation-link.is-active a' => array(
					'color' => $link_h_color,
				),
				'.woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle' => array(
					'background-color' => $link_color,
				),
				// Button Typography.
				'.woocommerce a.button, .woocommerce button.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce-cart table.cart td.actions .button, .woocommerce form.checkout_coupon .button, .woocommerce #respond input#submit, .wc-block-grid__products .wc-block-grid__product .wp-block-button__link' => array(
					'border-radius'  => astra_get_css_value( $btn_border_radius, 'px' ),
					'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'desktop' ),
					'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'desktop' ),
					'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'desktop' ),
					'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'desktop' ),
				),
				'.woocommerce .star-rating, .woocommerce .comment-form-rating .stars a, .woocommerce .star-rating::before' => array(
					'color' => $link_color,
				),
				'.woocommerce div.product .woocommerce-tabs ul.tabs li.active:before' => array(
					'background' => $link_color,
				),

				/**
				 * Cart in menu
				 */
				'.ast-site-header-cart a'                 => array(
					'color' => esc_attr( $text_color ),
				),

				'.ast-site-header-cart a:focus, .ast-site-header-cart a:hover, .ast-site-header-cart .current-menu-item a' => array(
					'color' => esc_attr( $link_color ),
				),

				'.ast-cart-menu-wrap .count, .ast-cart-menu-wrap .count:after' => array(
					'border-color' => esc_attr( $link_color ),
					'color'        => esc_attr( $link_color ),
				),

				'.ast-cart-menu-wrap:hover .count'        => array(
					'color'            => esc_attr( $cart_h_color ),
					'background-color' => esc_attr( $link_color ),
				),

				'.ast-site-header-cart .widget_shopping_cart .total .woocommerce-Price-amount' => array(
					'color' => esc_attr( $link_color ),
				),

				'.woocommerce a.remove:hover, .ast-woocommerce-cart-menu .main-header-menu .woocommerce-custom-menu-item .menu-item:hover > .menu-link.remove:hover' => array(
					'color'            => esc_attr( $link_color ),
					'border-color'     => esc_attr( $link_color ),
					'background-color' => esc_attr( '#ffffff' ),
				),

				/**
				 * Checkout button color for widget
				 */
				'.ast-site-header-cart .widget_shopping_cart .buttons .button.checkout, .woocommerce .widget_shopping_cart .woocommerce-mini-cart__buttons .checkout.wc-forward' => array(
					'color'            => $btn_h_color,
					'border-color'     => $btn_bg_h_color,
					'background-color' => $btn_bg_h_color,
				),
				'.site-header .ast-site-header-cart-data .button.wc-forward, .site-header .ast-site-header-cart-data .button.wc-forward:hover' => array(
					'color' => $btn_color,
				),
				'.below-header-user-select .ast-site-header-cart .widget, .ast-above-header-section .ast-site-header-cart .widget a, .below-header-user-select .ast-site-header-cart .widget_shopping_cart a' => array(
					'color' => $text_color,
				),
				'.below-header-user-select .ast-site-header-cart .widget_shopping_cart a:hover, .ast-above-header-section .ast-site-header-cart .widget_shopping_cart a:hover, .below-header-user-select .ast-site-header-cart .widget_shopping_cart a.remove:hover, .ast-above-header-section .ast-site-header-cart .widget_shopping_cart a.remove:hover' => array(
					'color' => esc_attr( $link_color ),
				),
			);

			/* Parse WooCommerce General CSS from array() */
			$css_output = astra_parse_css( $css_output );

			$tablet_css_shop_page_grid = array(
				'.woocommerce.tablet-columns-6 ul.products li.product, .woocommerce-page.tablet-columns-6 ul.products li.product' => array(
					'width' => '12.7%',
					'width' => 'calc(16.66% - 16.66px)',
				),
				'.woocommerce.tablet-columns-5 ul.products li.product, .woocommerce-page.tablet-columns-5 ul.products li.product' => array(
					'width' => '16.2%',
					'width' => 'calc(20% - 16px)',
				),
				'.woocommerce.tablet-columns-4 ul.products li.product, .woocommerce-page.tablet-columns-4 ul.products li.product' => array(
					'width' => '21.5%',
					'width' => 'calc(25% - 15px)',
				),
				'.woocommerce.tablet-columns-3 ul.products li.product, .woocommerce-page.tablet-columns-3 ul.products li.product' => array(
					'width' => '30.2%',
					'width' => 'calc(33.33% - 14px)',
				),
				'.woocommerce.tablet-columns-2 ul.products li.product, .woocommerce-page.tablet-columns-2 ul.products li.product' => array(
					'width' => '47.6%',
					'width' => 'calc(50% - 10px)',
				),
				'.woocommerce.tablet-columns-1 ul.products li.product, .woocommerce-page.tablet-columns-1 ul.products li.product' => array(
					'width' => '100%',
				),
				'.woocommerce div.product .related.products ul.products li.product' => array(
					'width' => '30.2%',
					'width' => 'calc(33.33% - 14px)',
				),
			);

			$css_output .= astra_parse_css( $tablet_css_shop_page_grid, astra_get_mobile_breakpoint( '', 1 ), astra_get_tablet_breakpoint() );

			if ( $is_site_rtl ) {
				$tablet_shop_page_grid_lang_direction_css = array(
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product, .woocommerce[class*="columns-"].columns-4 > ul.products li.product, .woocommerce[class*="columns-"].columns-5 > ul.products li.product, .woocommerce[class*="columns-"].columns-6 > ul.products li.product' => array(
						'width'       => '30.2%',
						'width'       => 'calc(33.33% - 14px)',
						'margin-left' => '20px',
					),
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product:nth-child(3n), .woocommerce[class*="columns-"].columns-4 > ul.products li.product:nth-child(3n), .woocommerce[class*="columns-"].columns-5 > ul.products li.product:nth-child(3n), .woocommerce[class*="columns-"].columns-6 > ul.products li.product:nth-child(3n)' => array(
						'margin-left' => 0,
						'clear'       => 'left',
					),
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product:nth-child(3n+1), .woocommerce[class*="columns-"].columns-4 > ul.products li.product:nth-child(3n+1), .woocommerce[class*="columns-"].columns-5 > ul.products li.product:nth-child(3n+1), .woocommerce[class*="columns-"].columns-6 > ul.products li.product:nth-child(3n+1)' => array(
						'clear' => 'right',
					),
					'.woocommerce[class*="columns-"] ul.products li.product:nth-child(n), .woocommerce-page[class*="columns-"] ul.products li.product:nth-child(n)' => array(
						'margin-left' => '20px',
						'clear'       => 'none',
					),
					'.woocommerce.tablet-columns-2 ul.products li.product:nth-child(2n), .woocommerce-page.tablet-columns-2 ul.products li.product:nth-child(2n), .woocommerce.tablet-columns-3 ul.products li.product:nth-child(3n), .woocommerce-page.tablet-columns-3 ul.products li.product:nth-child(3n), .woocommerce.tablet-columns-4 ul.products li.product:nth-child(4n), .woocommerce-page.tablet-columns-4 ul.products li.product:nth-child(4n), .woocommerce.tablet-columns-5 ul.products li.product:nth-child(5n), .woocommerce-page.tablet-columns-5 ul.products li.product:nth-child(5n), .woocommerce.tablet-columns-6 ul.products li.product:nth-child(6n), .woocommerce-page.tablet-columns-6 ul.products li.product:nth-child(6n)' => array(
						'margin-left' => '0',
						'clear'       => 'left',
					),
					'.woocommerce.tablet-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce-page.tablet-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce.tablet-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce-page.tablet-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce.tablet-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce-page.tablet-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce.tablet-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce-page.tablet-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce.tablet-columns-6 ul.products li.product:nth-child(6n+1), .woocommerce-page.tablet-columns-6 ul.products li.product:nth-child(6n+1)' => array(
						'clear' => 'right',
					),
					'.woocommerce div.product .related.products ul.products li.product:nth-child(3n)' => array(
						'margin-left' => 0,
						'clear'       => 'left',
					),
					'.woocommerce div.product .related.products ul.products li.product:nth-child(3n+1)' => array(
						'clear' => 'right',
					),
				);
			} else {
				$tablet_shop_page_grid_lang_direction_css = array(
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product, .woocommerce[class*="columns-"].columns-4 > ul.products li.product, .woocommerce[class*="columns-"].columns-5 > ul.products li.product, .woocommerce[class*="columns-"].columns-6 > ul.products li.product' => array(
						'width'        => '30.2%',
						'width'        => 'calc(33.33% - 14px)',
						'margin-right' => '20px',
					),
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product:nth-child(3n), .woocommerce[class*="columns-"].columns-4 > ul.products li.product:nth-child(3n), .woocommerce[class*="columns-"].columns-5 > ul.products li.product:nth-child(3n), .woocommerce[class*="columns-"].columns-6 > ul.products li.product:nth-child(3n)' => array(
						'margin-right' => 0,
						'clear'        => 'right',
					),
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product:nth-child(3n+1), .woocommerce[class*="columns-"].columns-4 > ul.products li.product:nth-child(3n+1), .woocommerce[class*="columns-"].columns-5 > ul.products li.product:nth-child(3n+1), .woocommerce[class*="columns-"].columns-6 > ul.products li.product:nth-child(3n+1)' => array(
						'clear' => 'left',
					),
					'.woocommerce[class*="columns-"] ul.products li.product:nth-child(n), .woocommerce-page[class*="columns-"] ul.products li.product:nth-child(n)' => array(
						'margin-right' => '20px',
						'clear'        => 'none',
					),
					'.woocommerce.tablet-columns-2 ul.products li.product:nth-child(2n), .woocommerce-page.tablet-columns-2 ul.products li.product:nth-child(2n), .woocommerce.tablet-columns-3 ul.products li.product:nth-child(3n), .woocommerce-page.tablet-columns-3 ul.products li.product:nth-child(3n), .woocommerce.tablet-columns-4 ul.products li.product:nth-child(4n), .woocommerce-page.tablet-columns-4 ul.products li.product:nth-child(4n), .woocommerce.tablet-columns-5 ul.products li.product:nth-child(5n), .woocommerce-page.tablet-columns-5 ul.products li.product:nth-child(5n), .woocommerce.tablet-columns-6 ul.products li.product:nth-child(6n), .woocommerce-page.tablet-columns-6 ul.products li.product:nth-child(6n)' => array(
						'margin-right' => '0',
						'clear'        => 'right',
					),
					'.woocommerce.tablet-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce-page.tablet-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce.tablet-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce-page.tablet-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce.tablet-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce-page.tablet-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce.tablet-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce-page.tablet-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce.tablet-columns-6 ul.products li.product:nth-child(6n+1), .woocommerce-page.tablet-columns-6 ul.products li.product:nth-child(6n+1)' => array(
						'clear' => 'left',
					),
					'.woocommerce div.product .related.products ul.products li.product:nth-child(3n)' => array(
						'margin-right' => 0,
						'clear'        => 'right',
					),
					'.woocommerce div.product .related.products ul.products li.product:nth-child(3n+1)' => array(
						'clear' => 'left',
					),
				);
			}

			$css_output .= astra_parse_css( $tablet_shop_page_grid_lang_direction_css, astra_get_mobile_breakpoint( '', 1 ), astra_get_tablet_breakpoint() );

			/**
			 * Global button CSS - Tablet = min-wdth: (tablet + 1)px
			 */
			if ( $is_site_rtl ) {
				$min_tablet_css = array(
					'.woocommerce #reviews #comments'   => array(
						'width' => '55%',
						'float' => 'right',
					),
					'.woocommerce #reviews #review_form_wrapper' => array(
						'width'         => '45%',
						'float'         => 'left',
						'padding-right' => '2em',
					),
					'.woocommerce form.checkout_coupon' => array(
						'width' => '50%',
					),
				);
			} else {
				$min_tablet_css = array(
					'.woocommerce #reviews #comments'   => array(
						'width' => '55%',
						'float' => 'left',
					),
					'.woocommerce #reviews #review_form_wrapper' => array(
						'width'        => '45%',
						'float'        => 'right',
						'padding-left' => '2em',
					),
					'.woocommerce form.checkout_coupon' => array(
						'width' => '50%',
					),
				);
			}

			$css_output .= astra_parse_css( $min_tablet_css, astra_get_tablet_breakpoint( '', 1 ) );

			/**
			 * Global button CSS - Tablet = max-width: (tab-breakpoint)px.
			 */
			$css_global_button_tablet = array(
				'.ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack.ast-no-menu-items .ast-site-header-cart, .ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack.ast-no-menu-items .ast-site-header-cart' => array(
					'padding-right' => 0,
					'padding-left'  => 0,
				),
				'.ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack .main-header-bar' => array(
					'text-align' => 'center',
				),
				'.ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack .ast-site-header-cart, .ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'display' => 'inline-block',
				),
				'.ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-2.ast-mobile-header-inline .site-branding' => array(
					'flex' => 'auto',
				),
				'.ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack .site-branding' => array(
					'flex' => '0 0 100%',
				),
				'.ast-header-break-point.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack .main-header-container' => array(
					'display'         => 'flex',
					'justify-content' => 'center',
				),
				'.woocommerce-cart .woocommerce-shipping-calculator .button' => array(
					'width' => '100%',
				),
				'.woocommerce a.button, .woocommerce button.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce-cart table.cart td.actions .button, .woocommerce form.checkout_coupon .button, .woocommerce #respond input#submit, .wc-block-grid__products .wc-block-grid__product .wp-block-button__link' => array(
					'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'tablet' ),
					'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'tablet' ),
					'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'tablet' ),
					'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'tablet' ),
				),
				'.woocommerce div.product div.images, .woocommerce div.product div.summary, .woocommerce #content div.product div.images, .woocommerce #content div.product div.summary, .woocommerce-page div.product div.images, .woocommerce-page div.product div.summary, .woocommerce-page #content div.product div.images, .woocommerce-page #content div.product div.summary' => array(
					'float' => 'none',
					'width' => '100%',
				),
				'.woocommerce-cart table.cart td.actions .ast-return-to-shop' => array(
					'display'    => 'block',
					'text-align' => 'center',
					'margin-top' => '1em',
				),
			);

			$css_output .= astra_parse_css( $css_global_button_tablet, '', astra_get_tablet_breakpoint() );

			/**
			 * Global button CSS - Mobile = max-width: (mobile-breakpoint)px.
			 */
			$css_global_button_mobile = array(
				'.ast-separate-container .ast-woocommerce-container' => array(
					'padding' => '.54em 1em 1.33333em',
				),
				'.woocommerce a.button, .woocommerce button.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce-cart table.cart td.actions .button, .woocommerce form.checkout_coupon .button, .woocommerce #respond input#submit, .wc-block-grid__products .wc-block-grid__product .wp-block-button__link' => array(
					'padding-top'    => astra_responsive_spacing( $theme_btn_padding, 'top', 'mobile' ),
					'padding-right'  => astra_responsive_spacing( $theme_btn_padding, 'right', 'mobile' ),
					'padding-bottom' => astra_responsive_spacing( $theme_btn_padding, 'bottom', 'mobile' ),
					'padding-left'   => astra_responsive_spacing( $theme_btn_padding, 'left', 'mobile' ),
				),
				'.woocommerce-message, .woocommerce-error, .woocommerce-info' => array(
					'display'   => 'flex',
					'flex-wrap' => 'wrap',
				),
				'.woocommerce-message a.button, .woocommerce-error a.button, .woocommerce-info a.button' => array(
					'order'      => '1',
					'margin-top' => '.5em',
				),
				'.woocommerce.mobile-columns-6 ul.products li.product, .woocommerce-page.mobile-columns-6 ul.products li.product' => array(
					'width' => '10.2%',
					'width' => 'calc(16.66% - 16.66px)',
				),
				'.woocommerce.mobile-columns-5 ul.products li.product, .woocommerce-page.mobile-columns-5 ul.products li.product' => array(
					'width' => '13%',
					'width' => 'calc(20% - 16px)',
				),
				'.woocommerce.mobile-columns-4 ul.products li.product, .woocommerce-page.mobile-columns-4 ul.products li.product' => array(
					'width' => '19%',
					'width' => 'calc(25% - 15px)',
				),
				'.woocommerce.mobile-columns-3 ul.products li.product, .woocommerce-page.mobile-columns-3 ul.products li.product' => array(
					'width' => '28.2%',
					'width' => 'calc(33.33% - 14px)',
				),
				'.woocommerce.mobile-columns-2 ul.products li.product, .woocommerce-page.mobile-columns-2 ul.products li.product' => array(
					'width' => '46.1%',
					'width' => 'calc(50% - 10px)',
				),
				'.woocommerce.mobile-columns-1 ul.products li.product, .woocommerce-page.mobile-columns-1 ul.products li.product' => array(
					'width' => '100%',
				),
				'.woocommerce .woocommerce-ordering, .woocommerce-page .woocommerce-ordering' => array(
					'float'         => 'none',
					'margin-bottom' => '2em',
					'width'         => '100%',
				),
				'.woocommerce ul.products a.button, .woocommerce-page ul.products a.button' => array(
					'padding' => '0.5em 0.75em',
				),
				'.woocommerce div.product .related.products ul.products li.product' => array(
					'width' => '46.1%',
					'width' => 'calc(50% - 10px)',
				),
				'.woocommerce table.cart td.actions .button, .woocommerce #content table.cart td.actions .button, .woocommerce-page table.cart td.actions .button, .woocommerce-page #content table.cart td.actions .button' => array(
					'padding-left'  => '1em',
					'padding-right' => '1em',
				),
				'.woocommerce #content table.cart .button, .woocommerce-page #content table.cart .button' => array(
					'width' => '100%',
				),
				'.woocommerce #content table.cart .product-thumbnail, .woocommerce-page #content table.cart .product-thumbnail' => array(
					'display'    => 'block',
					'text-align' => 'center !important',
				),
				'.woocommerce #content table.cart .product-thumbnail::before, .woocommerce-page #content table.cart .product-thumbnail::before' => array(
					'display' => 'none',
				),
				'.woocommerce #content table.cart td.actions .coupon, .woocommerce-page #content table.cart td.actions .coupon' => array(
					'float' => 'none',
				),
				'.woocommerce #content table.cart td.actions .coupon .button, .woocommerce-page #content table.cart td.actions .coupon .button' => array(
					'flex' => '1',
				),
				'.woocommerce #content div.product .woocommerce-tabs ul.tabs li a, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a' => array(
					'display' => 'block',
				),
			);

			$css_output .= astra_parse_css( $css_global_button_mobile, '', astra_get_mobile_breakpoint() );

			if ( $is_site_rtl ) {
				$global_button_mobile_lang_direction_css = array(
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product, .woocommerce[class*="columns-"].columns-4 > ul.products li.product, .woocommerce[class*="columns-"].columns-5 > ul.products li.product, .woocommerce[class*="columns-"].columns-6 > ul.products li.product' => array(
						'width'       => '46.1%',
						'width'       => 'calc(50% - 10px)',
						'margin-left' => '20px',
					),
					'.woocommerce-page[class*=columns-] ul.products li.product:nth-child(n), .woocommerce[class*=columns-] ul.products li.product:nth-child(n)' => array(
						'margin-left' => '20px',
						'clear'       => 'none',
					),
					'.woocommerce-page[class*=columns-].columns-3>ul.products li.product:nth-child(2n), .woocommerce-page[class*=columns-].columns-4>ul.products li.product:nth-child(2n), .woocommerce-page[class*=columns-].columns-5>ul.products li.product:nth-child(2n), .woocommerce-page[class*=columns-].columns-6>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-3>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-4>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-5>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-6>ul.products li.product:nth-child(2n)' => array(
						'margin-left' => 0,
						'clear'       => 'left',
					),
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product:nth-child(2n+1), .woocommerce[class*="columns-"].columns-4 > ul.products li.product:nth-child(2n+1), .woocommerce[class*="columns-"].columns-5 > ul.products li.product:nth-child(2n+1), .woocommerce[class*="columns-"].columns-6 > ul.products li.product:nth-child(2n+1)' => array(
						'clear' => 'right',
					),
					'.woocommerce[class*="columns-"] ul.products li.product:nth-child(n), .woocommerce-page[class*="columns-"] ul.products li.product:nth-child(n)' => array(
						'margin-left' => '20px',
						'clear'       => 'none',
					),
					'.woocommerce.mobile-columns-6 ul.products li.product:nth-child(6n), .woocommerce-page.mobile-columns-6 ul.products li.product:nth-child(6n), .woocommerce.mobile-columns-5 ul.products li.product:nth-child(5n), .woocommerce-page.mobile-columns-5 ul.products li.product:nth-child(5n), .woocommerce.mobile-columns-4 ul.products li.product:nth-child(4n), .woocommerce-page.mobile-columns-4 ul.products li.product:nth-child(4n), .woocommerce.mobile-columns-3 ul.products li.product:nth-child(3n), .woocommerce-page.mobile-columns-3 ul.products li.product:nth-child(3n), .woocommerce.mobile-columns-2 ul.products li.product:nth-child(2n), .woocommerce-page.mobile-columns-2 ul.products li.product:nth-child(2n), .woocommerce div.product .related.products ul.products li.product:nth-child(2n)' => array(
						'margin-left' => 0,
						'clear'       => 'left',
					),
					'.woocommerce.mobile-columns-6 ul.products li.product:nth-child(6n+1), .woocommerce-page.mobile-columns-6 ul.products li.product:nth-child(6n+1), .woocommerce.mobile-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce-page.mobile-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce.mobile-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce-page.mobile-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce.mobile-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce-page.mobile-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce.mobile-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce-page.mobile-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce div.product .related.products ul.products li.product:nth-child(2n+1)' => array(
						'clear' => 'right',
					),
					'.woocommerce ul.products a.button.loading::after, .woocommerce-page ul.products a.button.loading::after' => array(
						'display'      => 'inline-block',
						'margin-right' => '5px',
						'position'     => 'initial',
					),
					'.woocommerce.mobile-columns-1 ul.products li.product:nth-child(n), .woocommerce-page.mobile-columns-1 ul.products li.product:nth-child(n)' => array(
						'margin-left' => 0,
					),
					'.woocommerce #content div.product .woocommerce-tabs ul.tabs li, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li' => array(
						'display'     => 'block',
						'margin-left' => 0,
					),
				);
			} else {
				$global_button_mobile_lang_direction_css = array(
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product, .woocommerce[class*="columns-"].columns-4 > ul.products li.product, .woocommerce[class*="columns-"].columns-5 > ul.products li.product, .woocommerce[class*="columns-"].columns-6 > ul.products li.product' => array(
						'width'        => '46.1%',
						'width'        => 'calc(50% - 10px)',
						'margin-right' => '20px',
					),
					'.woocommerce-page[class*=columns-] ul.products li.product:nth-child(n), .woocommerce[class*=columns-] ul.products li.product:nth-child(n)' => array(
						'margin-right' => '20px',
						'clear'        => 'none',
					),
					'.woocommerce-page[class*=columns-].columns-3>ul.products li.product:nth-child(2n), .woocommerce-page[class*=columns-].columns-4>ul.products li.product:nth-child(2n), .woocommerce-page[class*=columns-].columns-5>ul.products li.product:nth-child(2n), .woocommerce-page[class*=columns-].columns-6>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-3>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-4>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-5>ul.products li.product:nth-child(2n), .woocommerce[class*=columns-].columns-6>ul.products li.product:nth-child(2n)' => array(
						'margin-right' => 0,
						'clear'        => 'right',
					),
					'.woocommerce[class*="columns-"].columns-3 > ul.products li.product:nth-child(2n+1), .woocommerce[class*="columns-"].columns-4 > ul.products li.product:nth-child(2n+1), .woocommerce[class*="columns-"].columns-5 > ul.products li.product:nth-child(2n+1), .woocommerce[class*="columns-"].columns-6 > ul.products li.product:nth-child(2n+1)' => array(
						'clear' => 'left',
					),
					'.woocommerce[class*="columns-"] ul.products li.product:nth-child(n), .woocommerce-page[class*="columns-"] ul.products li.product:nth-child(n)' => array(
						'margin-right' => '20px',
						'clear'        => 'none',
					),
					'.woocommerce.mobile-columns-6 ul.products li.product:nth-child(6n), .woocommerce-page.mobile-columns-6 ul.products li.product:nth-child(6n), .woocommerce.mobile-columns-5 ul.products li.product:nth-child(5n), .woocommerce-page.mobile-columns-5 ul.products li.product:nth-child(5n), .woocommerce.mobile-columns-4 ul.products li.product:nth-child(4n), .woocommerce-page.mobile-columns-4 ul.products li.product:nth-child(4n), .woocommerce.mobile-columns-3 ul.products li.product:nth-child(3n), .woocommerce-page.mobile-columns-3 ul.products li.product:nth-child(3n), .woocommerce.mobile-columns-2 ul.products li.product:nth-child(2n), .woocommerce-page.mobile-columns-2 ul.products li.product:nth-child(2n), .woocommerce div.product .related.products ul.products li.product:nth-child(2n)' => array(
						'margin-right' => 0,
						'clear'        => 'right',
					),
					'.woocommerce.mobile-columns-6 ul.products li.product:nth-child(6n+1), .woocommerce-page.mobile-columns-6 ul.products li.product:nth-child(6n+1), .woocommerce.mobile-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce-page.mobile-columns-5 ul.products li.product:nth-child(5n+1), .woocommerce.mobile-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce-page.mobile-columns-4 ul.products li.product:nth-child(4n+1), .woocommerce.mobile-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce-page.mobile-columns-3 ul.products li.product:nth-child(3n+1), .woocommerce.mobile-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce-page.mobile-columns-2 ul.products li.product:nth-child(2n+1), .woocommerce div.product .related.products ul.products li.product:nth-child(2n+1)' => array(
						'clear' => 'left',
					),
					'.woocommerce ul.products a.button.loading::after, .woocommerce-page ul.products a.button.loading::after' => array(
						'display'     => 'inline-block',
						'margin-left' => '5px',
						'position'    => 'initial',
					),
					'.woocommerce.mobile-columns-1 ul.products li.product:nth-child(n), .woocommerce-page.mobile-columns-1 ul.products li.product:nth-child(n)' => array(
						'margin-right' => 0,
					),
					'.woocommerce #content div.product .woocommerce-tabs ul.tabs li, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li' => array(
						'display'      => 'block',
						'margin-right' => 0,
					),
				);
			}

			$css_output .= astra_parse_css( $global_button_mobile_lang_direction_css, '', astra_get_mobile_breakpoint() );

			if ( 'page-builder' !== astra_get_content_layout() ) {
				/* Woocommerce Shop Archive width */
				if ( 'custom' === $woo_shop_archive_width ) :
					// Woocommerce shop archive custom width.
					$site_width  = array(
						'.ast-woo-shop-archive .site-content > .ast-container' => array(
							'max-width' => astra_get_css_value( $woo_shop_archive_max_width, 'px' ),
						),
					);
					$css_output .= astra_parse_css( $site_width, astra_get_tablet_breakpoint( '', 1 ) );

				else :
					// Woocommerce shop archive default width.
					$site_width = array(
						'.ast-woo-shop-archive .site-content > .ast-container' => array(
							'max-width' => astra_get_css_value( $site_content_width + 40, 'px' ),
						),
					);

					/* Parse CSS from array()*/
					$css_output .= astra_parse_css( $site_width, astra_get_tablet_breakpoint( '', 1 ) );
				endif;
			}

			$woo_product_css = array(
				'.woocommerce #content .ast-woocommerce-container div.product div.images, .woocommerce .ast-woocommerce-container div.product div.images, .woocommerce-page #content .ast-woocommerce-container div.product div.images, .woocommerce-page .ast-woocommerce-container div.product div.images' => array(
					'width' => '50%',
				),
				'.woocommerce #content .ast-woocommerce-container div.product div.summary, .woocommerce .ast-woocommerce-container div.product div.summary, .woocommerce-page #content .ast-woocommerce-container div.product div.summary, .woocommerce-page .ast-woocommerce-container div.product div.summary' => array(
					'width' => '46%',
				),
				'.woocommerce.woocommerce-checkout form #customer_details.col2-set .col-1, .woocommerce.woocommerce-checkout form #customer_details.col2-set .col-2, .woocommerce-page.woocommerce-checkout form #customer_details.col2-set .col-1, .woocommerce-page.woocommerce-checkout form #customer_details.col2-set .col-2' => array(
					'float' => 'none',
					'width' => 'auto',
				),
			);

			/* Parse CSS from array()*/
			$css_output .= astra_parse_css( $woo_product_css, astra_get_tablet_breakpoint( '', 1 ) );

			/*
			* global button settings not working for woocommerce button on shop and single page.
			* check if the current user is existing user or new user.
			* if new user load the CSS bty default if existing provide a filter
			*/
			if ( self::astra_global_btn_woo_comp() ) {

				$woo_global_button_css = array(
					'.woocommerce a.button , .woocommerce button.button.alt ,.woocommerce-page table.cart td.actions .button, .woocommerce-page #content table.cart td.actions .button , .woocommerce a.button.alt ,.woocommerce .woocommerce-message a.button , .ast-site-header-cart .widget_shopping_cart .buttons .button.checkout, .woocommerce button.button.alt.disabled , .wc-block-grid__products .wc-block-grid__product .wp-block-button__link ' => array(
						'border'              => 'solid',
						'border-top-width'    => ( isset( $global_custom_button_border_size['top'] ) && '' !== $global_custom_button_border_size['top'] ) ? astra_get_css_value( $global_custom_button_border_size['top'], 'px' ) : '0',
						'border-right-width'  => ( isset( $global_custom_button_border_size['right'] ) && '' !== $global_custom_button_border_size['right'] ) ? astra_get_css_value( $global_custom_button_border_size['right'], 'px' ) : '0',
						'border-left-width'   => ( isset( $global_custom_button_border_size['left'] ) && '' !== $global_custom_button_border_size['left'] ) ? astra_get_css_value( $global_custom_button_border_size['left'], 'px' ) : '0',
						'border-bottom-width' => ( isset( $global_custom_button_border_size['bottom'] ) && '' !== $global_custom_button_border_size['bottom'] ) ? astra_get_css_value( $global_custom_button_border_size['bottom'], 'px' ) : '0',
						'border-color'        => $btn_border_color ? $btn_border_color : $btn_bg_color,
					),
					'.woocommerce a.button:hover , .woocommerce button.button.alt:hover , .woocommerce-page table.cart td.actions .button:hover, .woocommerce-page #content table.cart td.actions .button:hover, .woocommerce a.button.alt:hover ,.woocommerce .woocommerce-message a.button:hover , .ast-site-header-cart .widget_shopping_cart .buttons .button.checkout:hover , .woocommerce button.button.alt.disabled:hover , .wc-block-grid__products .wc-block-grid__product .wp-block-button__link:hover' => array(
						'border-color' => $btn_border_h_color ? $btn_border_h_color : $btn_bg_h_color,
					),
				);

				$css_output .= astra_parse_css( $woo_global_button_css );
			}

			if ( $is_site_rtl ) {
				$woo_product_lang_direction_css = array(
					'.woocommerce.woocommerce-checkout form #customer_details.col2-set, .woocommerce-page.woocommerce-checkout form #customer_details.col2-set' => array(
						'width'       => '55%',
						'float'       => 'right',
						'margin-left' => '4.347826087%',
					),
					'.woocommerce.woocommerce-checkout form #order_review, .woocommerce.woocommerce-checkout form #order_review_heading, .woocommerce-page.woocommerce-checkout form #order_review, .woocommerce-page.woocommerce-checkout form #order_review_heading' => array(
						'width'       => '40%',
						'float'       => 'left',
						'margin-left' => '0',
						'clear'       => 'left',
					),
				);
			} else {
				$woo_product_lang_direction_css = array(
					'.woocommerce.woocommerce-checkout form #customer_details.col2-set, .woocommerce-page.woocommerce-checkout form #customer_details.col2-set' => array(
						'width'        => '55%',
						'float'        => 'left',
						'margin-right' => '4.347826087%',
					),
					'.woocommerce.woocommerce-checkout form #order_review, .woocommerce.woocommerce-checkout form #order_review_heading, .woocommerce-page.woocommerce-checkout form #order_review, .woocommerce-page.woocommerce-checkout form #order_review_heading' => array(
						'width'        => '40%',
						'float'        => 'right',
						'margin-right' => '0',
						'clear'        => 'right',
					),
				);
			}

			/* Parse CSS from array()*/
			$css_output .= astra_parse_css( $woo_product_lang_direction_css, astra_get_tablet_breakpoint( '', 1 ) );

			wp_add_inline_style( 'woocommerce-general', apply_filters( 'astra_theme_woocommerce_dynamic_css', $css_output ) );

			/**
			 * YITH WooCommerce Wishlist Style
			 */
			$yith_wcwl_main_style = array(
				'.yes-js.js_active .ast-plain-container.ast-single-post #primary' => array(
					'margin' => esc_attr( '4em 0' ),
				),
				'.js_active .ast-plain-container.ast-single-post .entry-header' => array(
					'margin-top' => esc_attr( '0' ),
				),
				'.woocommerce table.wishlist_table' => array(
					'font-size' => esc_attr( '100%' ),
				),
				'.woocommerce table.wishlist_table tbody td.product-name' => array(
					'font-weight' => esc_attr( '700' ),
				),
				'.woocommerce table.wishlist_table thead th' => array(
					'border-top' => esc_attr( '0' ),
				),
				'.woocommerce table.wishlist_table tr td.product-remove' => array(
					'padding' => esc_attr( '.7em 1em' ),
				),
				'.woocommerce table.wishlist_table tbody td' => array(
					'border-right' => esc_attr( '0' ),
				),
				'.woocommerce .wishlist_table td.product-add-to-cart a' => array(
					'display' => esc_attr( 'inherit !important' ),
				),
				'.wishlist_table tr td, .wishlist_table tr th.wishlist-delete, .wishlist_table tr th.product-checkbox' => array(
					'text-align' => esc_attr( 'left' ),
				),
				'.woocommerce #content table.wishlist_table.cart a.remove' => array(
					'display'        => esc_attr( 'inline-block' ),
					'vertical-align' => esc_attr( 'middle' ),
					'font-size'      => esc_attr( '18px' ),
					'font-weight'    => esc_attr( 'normal' ),
					'width'          => esc_attr( '24px' ),
					'height'         => esc_attr( '24px' ),
					'line-height'    => esc_attr( '21px' ),
					'color'          => esc_attr( '#ccc !important' ),
					'text-align'     => esc_attr( 'center' ),
					'border'         => esc_attr( '1px solid #ccc' ),
				),
				'.woocommerce #content table.wishlist_table.cart a.remove:hover' => array(
					'color'            => esc_attr( $link_color . '!important' ),
					'border-color'     => esc_attr( $link_color ),
					'background-color' => esc_attr( '#ffffff' ),
				),
			);
			/* Parse CSS from array() */
			$yith_wcwl_main_style = astra_parse_css( $yith_wcwl_main_style );

			$yith_wcwl_main_style_small = array(
				'.yes-js.js_active .ast-plain-container.ast-single-post #primary' => array(
					'padding' => esc_attr( '1.5em 0' ),
					'margin'  => esc_attr( '0' ),
				),
			);
			/* Parse CSS from array()*/
			$yith_wcwl_main_style .= astra_parse_css( $yith_wcwl_main_style_small, '', astra_get_tablet_breakpoint() );

			wp_add_inline_style( 'yith-wcwl-main', $yith_wcwl_main_style );
		}

		/**
		 * Register Customizer sections and panel for woocommerce
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_register( $wp_customize ) {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			/**
			 * Register Sections & Panels
			 */
			require ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/customizer/class-astra-customizer-register-woo-section.php';

			/**
			 * Sections
			 */
			require ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/customizer/sections/class-astra-woo-shop-container-configs.php';
			require ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/customizer/sections/class-astra-woo-shop-sidebar-configs.php';
			require ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/customizer/sections/layout/class-astra-woo-shop-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/customizer/sections/layout/class-astra-woo-shop-single-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/customizer/sections/layout/class-astra-woo-shop-cart-layout-configs.php';
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

		}

		/**
		 * Add Cart icon markup
		 *
		 * @param String $output Markup.
		 * @param String $section Section name.
		 * @param String $section_type Section selected option.
		 * @return Markup String.
		 *
		 * @since 1.0.0
		 */
		public function astra_header_cart( $output, $section, $section_type ) {

			if ( 'woocommerce' === $section_type && apply_filters( 'astra_woo_header_cart_icon', true ) ) {

				$output = $this->woo_mini_cart_markup();
			}

			return $output;
		}

		/**
		 * Woocommerce mini cart markup markup
		 *
		 * @since 1.2.2
		 * @return html
		 */
		public function woo_mini_cart_markup() {

			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}

			$cart_menu_classes = apply_filters( 'astra_cart_in_menu_class', array( 'ast-menu-cart-with-border' ) );

			ob_start();
			if ( is_customize_preview() && Astra_Builder_Helper::$is_header_footer_builder_active ) { 
				Astra_Builder_UI_Controller::render_customizer_edit_button();
			}
			?>
			<div id="ast-site-header-cart" class="ast-site-header-cart <?php echo esc_attr( implode( ' ', $cart_menu_classes ) ); ?>">
				<div class="ast-site-header-cart-li <?php echo esc_attr( $class ); ?>">
					<?php $this->astra_get_cart_link(); ?>
				</div>
				<div class="ast-site-header-cart-data">
					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * Add Cart icon markup
		 *
		 * @param Array $options header options array.
		 *
		 * @return Array header options array.
		 * @since 1.0.0
		 */
		public function header_section_elements( $options ) {

			$options['woocommerce'] = 'WooCommerce';

			return $options;
		}

		/**
		 * Cart Link
		 * Displayed a link to the cart including the number of items present and the cart total
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function astra_get_cart_link() {

			$view_shopping_cart = apply_filters( 'astra_woo_view_shopping_cart_title', __( 'View your shopping cart', 'astra' ) );
			?>
			<a class="cart-container" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_attr( $view_shopping_cart ); ?>">

						<?php
						do_action( 'astra_woo_header_cart_icons_before' );

						if ( apply_filters( 'astra_woo_default_header_cart_icon', true ) ) {
							?>
							<div class="ast-cart-menu-wrap">
								<span class="count">
									<?php
									if ( apply_filters( 'astra_woo_header_cart_total', true ) && null != WC()->cart ) {
										echo WC()->cart->get_cart_contents_count(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
									?>
								</span>
							</div>
							<?php
						}

						do_action( 'astra_woo_header_cart_icons_after' );

						?>
			</a>
			<?php
		}

		/**
		 * Cart Fragments
		 * Ensure cart contents update when products are added to the cart via AJAX
		 *
		 * @param  array $fragments Fragments to refresh via AJAX.
		 * @return array            Fragments to refresh via AJAX
		 */
		public function cart_link_fragment( $fragments ) {

			ob_start();
			$this->astra_get_cart_link();
			$fragments['a.cart-container'] = ob_get_clean();

			return $fragments;
		}

		/**
		 * For existing users, do not load the wide/full width image CSS by default.
		 *
		 * @since 2.5.0
		 * @return boolean false if it is an existing user , true if not.
		 */
		public static function astra_global_btn_woo_comp() {
			$astra_settings                       = get_option( ASTRA_THEME_SETTINGS );
			$astra_settings['global-btn-woo-css'] = isset( $astra_settings['global-btn-woo-css'] ) ? false : true;
			return apply_filters( 'astra_global_btn_woo_comp', $astra_settings['global-btn-woo-css'] );
		}
	}

endif;

if ( apply_filters( 'astra_enable_woocommerce_integration', true ) ) {
	Astra_Woocommerce::get_instance();
}
