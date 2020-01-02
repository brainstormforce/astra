<?php
/**
 * Custom functions that used for Easy Digital Downloads compatibility.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Current Page is EDD page
 */
if ( ! function_exists( 'astra_is_edd_page' ) ) :

	/**
	 * Check current page is an EDD page
	 *
	 * @since 1.5.5
	 * @return bool true | false
	 */
	function astra_is_edd_page() {
		if (
			is_singular( 'download' ) ||
			is_post_type_archive( 'download' ) ||
			is_tax( 'download_category' ) ||
			is_tax( 'download_tag' ) ||
			edd_is_checkout() ||
			edd_is_success_page() ||
			edd_is_failed_transaction_page() ||
			edd_is_purchase_history_page()
		) {
			return true;
		}
		return false;
	}

endif;

/**
 * Current Page is EDD single page
 */
if ( ! function_exists( 'astra_is_edd_single_page' ) ) :

	/**
	 * Check current page is an EDD single page
	 *
	 * @since 1.5.5
	 * @return bool true | false
	 */
	function astra_is_edd_single_page() {
		if (
			is_singular( 'download' ) ||
			edd_is_checkout() ||
			edd_is_success_page() ||
			edd_is_failed_transaction_page() ||
			edd_is_purchase_history_page()
		) {
			return true;
		}
		return false;
	}

endif;

/**
 * Current Page is EDD archive page
 */
if ( ! function_exists( 'astra_is_edd_archive_page' ) ) :

	/**
	 * Check current page is an EDD archive page
	 *
	 * @since 1.5.5
	 * @return bool true | false
	 */
	function astra_is_edd_archive_page() {
		if (
			is_post_type_archive( 'download' ) ||
			is_tax( 'download_category' ) ||
			is_tax( 'download_tag' )
		) {
			return true;
		}
		return false;
	}

endif;


/**
 * Current Page is EDD single Product page
 */
if ( ! function_exists( 'astra_is_edd_single_product_page' ) ) :

	/**
	 * Check current page is an EDD single product page
	 *
	 * @since 1.5.5
	 * @return bool true | false
	 */
	function astra_is_edd_single_product_page() {
		if ( is_singular( 'download' ) ) {
			return true;
		}
		return false;
	}

endif;

if ( ! function_exists( 'astra_edd_archive_product_structure' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function astra_edd_archive_product_structure() {
		$edd_structure = apply_filters( 'astra_edd_archive_product_structure', astra_get_option( 'edd-archive-product-structure' ) );

		if ( is_array( $edd_structure ) && ! empty( $edd_structure ) ) {

			do_action( 'astra_edd_archive_before_block_wrap' );
			echo '<div class="ast-edd-archive-block-wrap">';
			do_action( 'astra_edd_archive_block_wrap_top' );

			foreach ( $edd_structure as $value ) {

				switch ( $value ) {
					case 'title':
						/**
						 * Add Product Title on edd page for all products.
						 */
						do_action( 'astra_edd_archive_title_before' );
						do_action( 'astra_edd_archive_title' );
						do_action( 'astra_edd_archive_title_after' );
						break;
					case 'image':
						/**
						 * Add Product Title on edd page for all products.
						 */
						do_action( 'astra_edd_archive_image_before' );
						do_action( 'astra_edd_archive_image' );
						do_action( 'astra_edd_archive_image_after' );
						break;
					case 'price':
						/**
						 * Add Product Price on edd page for all products.
						 */
						do_action( 'astra_edd_archive_price_before' );
						do_action( 'astra_edd_archive_price' );
						do_action( 'astra_edd_archive_price_after' );
						break;
					case 'short_desc':
						/**
						 * Add Product short description on edd page for all products.
						 */
						do_action( 'astra_edd_archive_short_description_before' );
						do_action( 'astra_edd_archive_short_description' );
						do_action( 'astra_edd_archive_short_description_after' );
						break;
					case 'add_cart':
						/**
						 * Add to cart on edd page for all products.
						 */
						do_action( 'astra_edd_archive_add_to_cart_before' );
						do_action( 'astra_edd_archive_add_to_cart' );
						do_action( 'astra_edd_archive_add_to_cart_after' );

						break;
					case 'category':
						/**
						 * Add and/or Remove Categories from edd archive page.
						 */
						do_action( 'astra_edd_archive_category_before' );
						do_action( 'astra_edd_archive_category' );
						do_action( 'astra_edd_archive_category_after' );
						break;
					default:
						break;
				}
			}

			do_action( 'astra_edd_archive_block_wrap_bottom' );
			echo '</div>';
			do_action( 'astra_edd_archive_after_block_wrap' );
		}
	}

	add_action( 'astra_edd_archive_product_content', 'astra_edd_archive_product_structure' );
}

/**
 * Returns list of Easy Digital Downloads Terms
 */
if ( ! function_exists( 'astra_edd_terms_list' ) ) {
	/**
	 * Show EDD product terms
	 *
	 * @param  string $taxonomy_name Taxonomy name.
	 * @return void
	 */
	function astra_edd_terms_list( $taxonomy_name ) {
		$terms = get_terms( $taxonomy_name );
		?>
	<div class="ast-edd-download-categories">
		<?php foreach ( $terms as $term ) : ?>
			<a href="<?php echo esc_url( get_term_link( $term, $taxonomy_name ) ); ?>" title="<?php echo esc_attr( $term->name ); ?>"> <?php echo esc_html( $term->name ); ?> </a>
		<?php endforeach; ?>
	</div>
		<?php
	}
}

if ( ! function_exists( 'astra_edd_archive_product_title' ) ) {
	/**
	 * Show EDD archive product title
	 *
	 * @return void
	 */
	function astra_edd_archive_product_title() {
		edd_get_template_part( 'shortcode', 'content-title' );
	}

	add_action( 'astra_edd_archive_title', 'astra_edd_archive_product_title' );
}

if ( ! function_exists( 'astra_edd_archive_product_image' ) ) {
	/**
	 * Show EDD archive product image
	 *
	 * @return void
	 */
	function astra_edd_archive_product_image() {
		edd_get_template_part( 'shortcode', 'content-image' );
	}

	add_action( 'astra_edd_archive_image', 'astra_edd_archive_product_image' );
}

if ( ! function_exists( 'astra_edd_archive_product_price' ) ) {
	/**
	 * Show EDD archive product price
	 *
	 * @return void
	 */
	function astra_edd_archive_product_price() {
		edd_get_template_part( 'shortcode', 'content-price' );
	}

	add_action( 'astra_edd_archive_price', 'astra_edd_archive_product_price' );
}

if ( ! function_exists( 'astra_edd_archive_product_short_description' ) ) {
	/**
	 * Show EDD archive product description
	 *
	 * @return void
	 */
	function astra_edd_archive_product_short_description() {
		edd_get_template_part( 'shortcode', 'content-excerpt' );
	}

	add_action( 'astra_edd_archive_short_description', 'astra_edd_archive_product_short_description' );
}

if ( ! function_exists( 'astra_edd_archive_product_add_to_cart' ) ) {
	/**
	 * Show EDD archive product add to cart
	 *
	 * @return void
	 */
	function astra_edd_archive_product_add_to_cart() {
		echo astra_edd_cart_button_markup();
	}

	add_action( 'astra_edd_archive_add_to_cart', 'astra_edd_archive_product_add_to_cart' );
}


if ( ! function_exists( 'astra_edd_archive_product_category' ) ) {
	/**
	 * Show EDD archive product category
	 *
	 * @return void
	 */
	function astra_edd_archive_product_category() {
		echo astra_edd_terms_list( 'download_category' );
	}

	add_action( 'astra_edd_archive_category', 'astra_edd_archive_product_category' );
}


/**
 * EDD archive page Cart button markup
 *
 * @return array $output Add to cart button markup
 */
function astra_edd_cart_button_markup() {
	$variable_button      = astra_get_option( 'edd-archive-variable-button' );
	$add_to_cart_text     = astra_get_option( 'edd-archive-add-to-cart-button-text' );
	$variable_button_text = astra_get_option( 'edd-archive-variable-button-text' );
	$output               = edd_get_purchase_link();
	if ( edd_has_variable_prices( get_the_ID() ) && 'button' == $variable_button ) {
		$output  = '<div class="ast-edd-variable-details-button-wrap">';
		$output .= '<a class="button ast-edd-variable-btn" href="' . esc_url( get_permalink() ) . '">' . esc_html( $variable_button_text ) . '</a>';
		$output .= '</div>';
	} else {
		if ( ! empty( $add_to_cart_text ) ) {
			$output = edd_get_purchase_link(
				array(
					'price' => false,
					'text'  => esc_html( $add_to_cart_text ),
				)
			);
		}
	}

	return $output;
}
