<?php
/**
 * Related Posts Functions
 *
 * @package Astra
 * @since 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get related posts based on configurations.
 *
 * @param int $post_id Current Post ID.
 *
 * @since 3.4.0
 *
 * @return WP_Query|bool
 */
function astra_get_related_posts_by_query( $post_id ) {
	$term_ids                  = array();
	$current_post_type         = get_post_type( $post_id );
	$related_posts_total_count = absint( astra_get_option( 'related-posts-total-count', 2 ) );
	// Taking one post extra in loop because if current post excluded from while loop then this extra one post will cover total post count. Apperently avoided 'post__not_in' from WP_Query.
	$updated_total_posts_count = $related_posts_total_count + 1;
	$related_posts_order_by    = astra_get_option( 'related-posts-order-by', 'date' );
	$related_posts_order       = astra_get_option( 'related-posts-order', 'desc' );
	$related_posts_based_on    = astra_get_option( 'related-posts-based-on', 'categories' );

	$query_args = array(
		'update_post_meta_cache' => false,
		'posts_per_page'         => $updated_total_posts_count,
		'no_found_rows'          => true,
		'post_status'            => 'publish',
		'post_type'              => $current_post_type,
		'orderby'                => $related_posts_order_by,
		'fields'                 => 'ids',
		'order'                  => $related_posts_order,
	);

	if ( 'tags' === $related_posts_based_on ) {
		$terms = get_the_tags( $post_id );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$term_ids = wp_list_pluck( $terms, 'term_id' );
		}

		$query_args['tag__in'] = $term_ids;

	} else {
		$terms = get_the_category( $post_id );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$term_ids = wp_list_pluck( $terms, 'term_id' );
		}

		$query_args['category__in'] = $term_ids;
	}

	$query_args = apply_filters( 'astra_related_posts_query_args', $query_args );

	return new WP_Query( $query_args );
}

/**
 * Render Featured Image HTML.
 *
 * @param int     $current_post_id current post ID.
 * @param string  $before Markup before thumbnail image.
 * @param string  $after  Markup after thumbnail image.
 * @param boolean $echo   Output print or return.
 * @return string|null
 *
 * @since 3.4.0
 */
function astra_get_related_post_featured_image( $current_post_id, $before = '', $after = '', $echo = true ) {
	$related_post_structure = astra_get_option_meta( 'related-posts-structure' );

	if ( ! in_array( 'featured-image', $related_post_structure ) ) {
		return;
	}

	$post_thumb = apply_filters(
		'astra_related_post_featured_image_markup',
		get_the_post_thumbnail(
			$current_post_id,
			apply_filters( 'astra_related_posts_thumbnail_default_size', 'large' ),
			apply_filters( 'astra_related_posts_thumbnail_itemprop', '' )
		)
	);

	$appended_class = has_post_thumbnail( $current_post_id ) ? 'post-has-thumb' : 'ast-no-thumb';

	$featured_img_markup = '<div class="ast-related-post-featured-section ' . $appended_class . '">';

	if ( '' !== $post_thumb ) {
		$featured_img_markup .= '<div class="post-thumb-img-content post-thumb">';
		$featured_img_markup .= astra_markup_open(
			'ast-related-post-image',
			array(
				'open'  => '<a %s>',
				'echo'  => false,
				'attrs' => array(
					'class' => '',
					'href'  => esc_url( get_permalink() ),
				),
			)
		);
		$featured_img_markup .= $post_thumb;
		$featured_img_markup .= '</a> </div>';
	}

	$featured_img_markup  = apply_filters( 'astra_related_post_featured_image_after', $featured_img_markup );
	$featured_img_markup .= '</div>';

	$featured_img_markup = apply_filters( 'astra_related_post_thumbnail', $featured_img_markup, $before, $after );

	if ( false === $echo ) {
		return $before . $featured_img_markup . $after;
	}

	echo $before . $featured_img_markup . $after; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Render Post Title HTML.
 *
 * @param int $current_post_id current post ID.
 *
 * @since 3.4.0
 */
function astra_get_related_post_title( $current_post_id ) {
	$related_post_structure = astra_get_option_meta( 'related-posts-structure' );

	if ( ! in_array( 'title-meta', $related_post_structure ) ) {
		return;
	}

	$target    = apply_filters( 'astra_related_post_title_opening_target', '_self' );
	$title_tag = apply_filters( 'astra_related_post_title_tag', 'h3' );

	do_action( 'astra_related_post_before_title', $current_post_id );
	?>
		<<?php echo esc_html( $title_tag ); ?> class="ast-related-post-title entry-title">
			<a href="<?php echo esc_url( apply_filters( 'astra_related_post_link', get_the_permalink(), $current_post_id ) ); ?>" target="<?php echo esc_html( $target ); ?>" rel="bookmark noopener noreferrer"><?php the_title(); ?></a>
		</<?php echo esc_html( $title_tag ); ?>>
	<?php
	do_action( 'astra_related_post_after_title', $current_post_id );
}

/**
 * Related Posts Excerpt markup.
 *
 * @param int $current_post_id current post ID.
 *
 * @since 3.4.0
 */
function astra_get_related_post_excerpt( $current_post_id ) {
	if ( ! astra_get_option( 'enable-related-posts-excerpt' ) ) {
		return;
	}

	$related_posts_content_type = apply_filters( 'astra_related_posts_content_type', 'excerpt' );

	if ( 'full-content' === $related_posts_content_type ) {
		return the_content();
	}

	$excerpt_length = absint( astra_get_option( 'related-posts-excerpt-count' ) );

	$excerpt = wp_trim_words( get_the_excerpt(), $excerpt_length );

	if ( ! $excerpt ) {
		$excerpt = null;
	}

	$excerpt = apply_filters( 'astra_related_post_excerpt', $excerpt, $current_post_id );

	do_action( 'astra_related_post_before_excerpt', $current_post_id );

	?>
		<p class="ast-related-post-excerpt entry-content clear">
			<?php echo wp_kses_post( $excerpt ); ?>
		</p>
	<?php

	do_action( 'astra_related_post_after_excerpt', $current_post_id );
}

/**
 * Render Post CTA button HTML marup.
 *
 * @param int $current_post_id current post ID.
 *
 * @since 3.4.0
 */
function astra_get_related_post_read_more( $current_post_id ) {
	if ( ! astra_get_option( 'enable-related-posts-excerpt' ) ) {
		return;
	}

	$related_posts_content_type = apply_filters( 'astra_related_posts_content_type', 'excerpt' );

	if ( 'full-content' === $related_posts_content_type ) {
		return;
	}

	$target = apply_filters( 'astra_related_post_cta_target', '_self' );

	$cta_text = apply_filters( 'astra_related_post_read_more_text', astra_get_option( 'blog-read-more-text' ) );

	$show_read_more_as_button = apply_filters( 'astra_related_post_read_more_as_button', astra_get_option( 'blog-read-more-as-button' ) );

	$class = '';

	if ( $show_read_more_as_button ) {
		$class = 'ast-button';
	}

	$custom_class = apply_filters( 'astra_related_post_cta_custom_classes', $class );

	do_action( 'astra_related_post_before_cta', $current_post_id );

	?>
		<p class="ast-related-post-cta read-more">
			<a class="ast-related-post-link <?php echo esc_html( $custom_class ); ?>" href="<?php echo esc_url( apply_filters( 'astra_related_post_link', get_the_permalink(), $current_post_id ) ); ?>" target="<?php echo esc_html( $target ); ?>" rel="bookmark noopener noreferrer"><?php echo esc_html( $cta_text ); ?></a>
		</p>
	<?php

	do_action( 'astra_related_post_after_cta', $current_post_id );
}

/**
 * Related Posts markup.
 *
 * @since 3.4.0
 * @return bool
 */
function astra_get_related_posts() {
	global $post;
	$post_id                   = $post->ID;
	$related_posts_grid        = astra_get_option( 'related-posts-grid', 2 );
	$related_post_meta         = astra_get_option( 'related-posts-meta-structure' );
	$related_post_structure    = astra_get_option_meta( 'related-posts-structure' );
	$output_str                = astra_get_post_meta( $related_post_meta );
	$exclude_ids               = apply_filters( 'astra_related_posts_exclude_post_ids', array( $post_id ), $post_id );
	$related_posts_total_count = absint( astra_get_option( 'related-posts-total-count', 2 ) );

	// Get related posts by WP_Query.
	$query_posts = astra_get_related_posts_by_query( $post_id );

	if ( $query_posts ) {

		if ( ! $query_posts->have_posts() ) {
			return apply_filters( 'astra_related_posts_no_posts_avilable_message', '', $post_id );
		}

		// Added flag to load wrapper section 'ast-single-related-posts-container' only once, because as we removed 'posts__not_in' param from WP_Query and we conditionally handle posts__not_in below so it needs to verify if there are other posts as well to load, then only we will display wrapper.
		$related_posts_section_loaded = false;

		do_action( 'astra_related_posts_loop_before' );

		/**
		 * WP_Query posts loop.
		 *
		 * Used $post_counter & ( $post_counter < $total_posts_count ) condition to manage posts in while loop because there is case where manual 'post__not_in' condition handling scenario fails within loop.
		 *
		 * # CASE EXAMPLE - If total posts set to 4 (where 'post__not_in' not used in WP_Query) so there is a chance that out of those 4 posts, 1 post will be currently active on frontend.
		 *
		 * So what will happen in this case - Within following loop the current post will exclude by if condition & only 3 posts will be shown up.
		 *
		 * To avoid such cases $post_counter & ( $post_counter < $total_posts_count ) condition used.
		 *
		 * @since 3.4.0
		 */
		$post_counter      = 1;
		$total_posts_count = $related_posts_total_count + 1;

		while ( $query_posts->have_posts() && $post_counter < $total_posts_count ) {
			$query_posts->the_post();
			$post_id = get_the_ID();

			if ( is_array( $exclude_ids ) && ! in_array( $post_id, $exclude_ids ) ) {

				if ( false === $related_posts_section_loaded ) {
					$grid_class = ( $related_posts_grid ) ? 'ast-grid-' . $related_posts_grid : 'ast-grid-2';

					echo '<div class="ast-single-related-posts-container">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					do_action( 'astra_related_posts_title_before' );

					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'astra_related_posts_title',
						sprintf(
							'<div class="ast-related-posts-title-section"> <%1$s class="ast-related-posts-title"> %2$s </%1$s> </div>',
							'h2',
							esc_html__( 'Related Posts', 'astra' )
						)
					);

					do_action( 'astra_related_posts_title_after' );

					echo '<div class="ast-related-posts-wrapper ' . $grid_class . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					$related_posts_section_loaded = true;
				}

				?>
					<article <?php post_class( 'ast-related-post' ); ?>>
						<div class="ast-related-posts-inner-section">
							<div class="ast-related-post-content">
								<?php
									// Render post based on order of Featured Image & Title-Meta.
								if ( is_array( $related_post_structure ) ) {
									foreach ( $related_post_structure as $post_thumb_title_order ) {
										if ( 'featured-image' === $post_thumb_title_order ) {
											do_action( 'astra_related_post_before_featured_image', $post_id );
											astra_get_related_post_featured_image( $post_id );
											do_action( 'astra_related_post_after_featured_image', $post_id );
										} else {
											?>
													<header class="entry-header">
													<?php
														astra_get_related_post_title( $post_id );
														echo apply_filters( 'astra_related_posts_meta_html', '<div class="entry-meta">' . $output_str . '</div>', $output_str ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													?>
													</header>
												<?php
										}
									}
								}
								?>
								<div class="entry-content clear">
									<?php
										astra_get_related_post_excerpt( $post_id );
										astra_get_related_post_read_more( $post_id );
									?>
								</div>
							</div>
						</div>
					</article>
				<?php
				$post_counter++;
			}

			wp_reset_postdata();
		}

		if ( true === $related_posts_section_loaded ) {
			echo '</div> </div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		do_action( 'astra_related_posts_loop_after' );
	}
}

/**
 * Enable/Disable Single Post -> Related Posts section.
 *
 * @since 3.4.0
 * @return void
 */
function astra_related_posts_markup() {
	if ( astra_target_rules_for_related_posts() ) {
		astra_get_related_posts();
	}
}

add_action( 'astra_entry_after', 'astra_related_posts_markup', 10 );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since 3.4.0
 * @param array $classes Classes for the body element.
 * @return array
 */
function astra_related_posts_body_class( $classes ) {
	if ( astra_target_rules_for_related_posts() ) {
		$related_posts_grid = astra_get_option( 'related-posts-grid', 2 );

		$classes[] = 'ast-related-posts-grid-' . esc_attr( $related_posts_grid );
	}

	return $classes;
}

add_filter( 'body_class', 'astra_related_posts_body_class' );

