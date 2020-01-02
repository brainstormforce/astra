<?php
/**
 * Custom functions that used for Woocommerce compatibility.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shop page - Products Title markup updated
 */
if ( ! function_exists( 'astra_woo_shop_products_title' ) ) :

	/**
	 * Shop Page product titles with anchor
	 *
	 * @hooked woocommerce_after_shop_loop_item - 10
	 *
	 * @since 1.1.0
	 */
	function astra_woo_shop_products_title() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';

		echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';

		echo '</a>';
	}

endif;

/**
 * Shop page - Parent Category
 */
if ( ! function_exists( 'astra_woo_shop_parent_category' ) ) :
	/**
	 * Add and/or Remove Categories from shop archive page.
	 *
	 * @hooked woocommerce_after_shop_loop_item - 9
	 *
	 * @since 1.1.0
	 */
	function astra_woo_shop_parent_category() {
		if ( apply_filters( 'astra_woo_shop_parent_category', true ) ) : ?>
			<span class="ast-woo-product-category">
				<?php
				global $product;
				$product_categories = function_exists( 'wc_get_product_category_list' ) ? wc_get_product_category_list( get_the_ID(), ';', '', '' ) : $product->get_categories( ';', '', '' );

				$product_categories = htmlspecialchars_decode( strip_tags( $product_categories ) );
				if ( $product_categories ) {
					list( $parent_cat ) = explode( ';', $product_categories );
					echo apply_filters( 'astra_woo_shop_product_categories', esc_html( $parent_cat ), get_the_ID() );
				}

				?>
			</span> 
			<?php
		endif;
	}
endif;

/**
 * Shop page - Out of Stock
 */
if ( ! function_exists( 'astra_woo_shop_out_of_stock' ) ) :
	/**
	 * Add Out of Stock to the Shop page
	 *
	 * @hooked woocommerce_shop_loop_item_title - 8
	 *
	 * @since 1.1.0
	 */
	function astra_woo_shop_out_of_stock() {
		$out_of_stock        = get_post_meta( get_the_ID(), '_stock_status', true );
		$out_of_stock_string = apply_filters( 'astra_woo_shop_out_of_stock_string', __( 'Out of stock', 'astra' ) );
		if ( 'outofstock' === $out_of_stock ) {
			?>
			<span class="ast-shop-product-out-of-stock"><?php echo esc_html( $out_of_stock_string ); ?></span>
			<?php
		}
	}

endif;

/**
 * Shop page - Short Description
 */
if ( ! function_exists( 'astra_woo_shop_product_short_description' ) ) :
	/**
	 * Product short description
	 *
	 * @hooked woocommerce_after_shop_loop_item
	 *
	 * @since 1.1.0
	 */
	function astra_woo_shop_product_short_description() {
		?>
		<?php if ( has_excerpt() ) { ?>
		<div class="ast-woo-shop-product-description">
			<?php the_excerpt(); ?>
		</div>
	<?php } ?>
		<?php
	}
endif;
/**
 * Product page - Availability: in stock
 */
if ( ! function_exists( 'astra_woo_product_in_stock' ) ) :
	/**
	 * Availability: in stock string updated
	 *
	 * @param  string $markup  Markup.
	 * @param  object $product Object of Product.
	 *
	 * @since 1.1.0
	 */
	function astra_woo_product_in_stock( $markup, $product ) {

		if ( is_product() ) {
			$product_avail  = $product->get_availability();
			$stock_quantity = $product->get_stock_quantity();
			$availability   = $product_avail['availability'];
			if ( ! empty( $availability ) && $stock_quantity ) {
				ob_start();
				?>
				<p class="ast-stock-detail">
					<span class="ast-stock-avail"><?php esc_html_e( 'Availability:', 'astra' ); ?></span>
					<span class="stock in-stock"><?php echo esc_html( $availability ); ?></span>
				</p>
				<?php
				$markup = ob_get_clean();
			}
		}

		return $markup;
	}
endif;

if ( ! function_exists( 'astra_woo_woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function astra_woo_woocommerce_template_loop_product_title() {

		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="ast-loop-product__link">';
			woocommerce_template_loop_product_title();
		echo '</a>';
	}
}

if ( ! function_exists( 'astra_woo_woocommerce_shop_product_content' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function astra_woo_woocommerce_shop_product_content() {

		$shop_structure = apply_filters( 'astra_woo_shop_product_structure', astra_get_option( 'shop-product-structure' ) );

		if ( is_array( $shop_structure ) && ! empty( $shop_structure ) ) {

			do_action( 'astra_woo_shop_before_summary_wrap' );
			echo '<div class="astra-shop-summary-wrap">';
			do_action( 'astra_woo_shop_summary_wrap_top' );

			foreach ( $shop_structure as $value ) {

				switch ( $value ) {
					case 'title':
						/**
						 * Add Product Title on shop page for all products.
						 */
						do_action( 'astra_woo_shop_title_before' );
						astra_woo_woocommerce_template_loop_product_title();
						do_action( 'astra_woo_shop_title_after' );
						break;
					case 'price':
						/**
						 * Add Product Price on shop page for all products.
						 */
						do_action( 'astra_woo_shop_price_before' );
						woocommerce_template_loop_price();
						do_action( 'astra_woo_shop_price_after' );
						break;
					case 'ratings':
						/**
						 * Add rating on shop page for all products.
						 */
						do_action( 'astra_woo_shop_rating_before' );
						woocommerce_template_loop_rating();
						do_action( 'astra_woo_shop_rating_after' );
						break;
					case 'short_desc':
						do_action( 'astra_woo_shop_short_description_before' );
						astra_woo_shop_product_short_description();
						do_action( 'astra_woo_shop_short_description_after' );
						break;
					case 'add_cart':
						do_action( 'astra_woo_shop_add_to_cart_before' );
						woocommerce_template_loop_add_to_cart();
						do_action( 'astra_woo_shop_add_to_cart_after' );
						break;
					case 'category':
						/**
						 * Add and/or Remove Categories from shop archive page.
						 */
						do_action( 'astra_woo_shop_category_before' );
						astra_woo_shop_parent_category();
						do_action( 'astra_woo_shop_category_after' );
						break;
					default:
						break;
				}
			}

			do_action( 'astra_woo_shop_summary_wrap_bottom' );
			echo '</div>';
			do_action( 'astra_woo_shop_after_summary_wrap' );
		}
	}
}

if ( ! function_exists( 'astra_woo_shop_thumbnail_wrap_start' ) ) {

	/**
	 * Thumbnail wrap start.
	 */
	function astra_woo_shop_thumbnail_wrap_start() {

		echo '<div class="astra-shop-thumbnail-wrap">';
	}
}

if ( ! function_exists( 'astra_woo_shop_thumbnail_wrap_end' ) ) {

	/**
	 * Thumbnail wrap end.
	 */
	function astra_woo_shop_thumbnail_wrap_end() {

		echo '</div>';
	}
}


/**
 * Woocommerce filter - Widget Products Tags
 */
if ( ! function_exists( 'astra_widget_product_tag_cloud_args' ) ) {

	/**
	 * Woocommerce filter - Widget Products Tags
	 *
	 * @param  array $args Tag arguments.
	 * @return array       Modified tag arguments.
	 */
	function astra_widget_product_tag_cloud_args( $args = array() ) {

		$sidebar_link_font_size            = astra_get_option( 'font-size-body' );
		$sidebar_link_font_size['desktop'] = ( '' != $sidebar_link_font_size['desktop'] ) ? $sidebar_link_font_size['desktop'] : 15;

		$args['smallest'] = intval( $sidebar_link_font_size['desktop'] ) - 2;
		$args['largest']  = intval( $sidebar_link_font_size['desktop'] ) + 3;
		$args['unit']     = 'px';

		return apply_filters( 'astra_widget_product_tag_cloud_args', $args );
	}
	add_filter( 'woocommerce_product_tag_cloud_widget_args', 'astra_widget_product_tag_cloud_args', 90 );

}

/**
 * Woocommerce shop/product div close tag.
 */
if ( ! function_exists( 'astra_woocommerce_div_wrapper_close' ) ) :

	/**
	 * Woocommerce shop/product div close tag.
	 *
	 * @return void
	 */
	function astra_woocommerce_div_wrapper_close() {

		echo '</div>';

	}

endif;
