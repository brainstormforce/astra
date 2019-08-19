<?php
/**
 * Blog Config File
 * Common Functions for Blog and Single Blog
 *
 * @package Astra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Functions for Blog and Single Blog
 *
 * @return  post meta
 */
if ( ! function_exists( 'astra_get_post_meta' ) ) {

	/**
	 * Post meta
	 *
	 * @param  string $post_meta Post meta.
	 * @param  string $separator Separator.
	 * @return string            post meta markup.
	 */
	function astra_get_post_meta( $post_meta, $separator = '/' ) {

		$output_str = '';
		$loop_count = 1;

		$separator = apply_filters( 'astra_post_meta_separator', $separator );

		foreach ( $post_meta as $meta_value ) {

			switch ( $meta_value ) {

				case 'author':
					$author = get_the_author();
					if ( ! empty( $author ) ) {
						$output_str .= ( 1 != $loop_count && '' != $output_str ) ? ' ' . $separator . ' ' : '';
						$output_str .= esc_html( astra_default_strings( 'string-blog-meta-author-by', false ) ) . astra_post_author();
					}
					break;

				case 'date':
					$output_str .= ( 1 != $loop_count && '' != $output_str ) ? ' ' . $separator . ' ' : '';
					$output_str .= astra_post_date();
					break;

				case 'category':
					$category = astra_post_categories();
					if ( '' != $category ) {
						$output_str .= ( 1 != $loop_count && '' != $output_str ) ? ' ' . $separator . ' ' : '';
						$output_str .= $category;
					}
					break;

				case 'tag':
					$tags = astra_post_tags();
					if ( '' != $tags ) {
						$output_str .= ( 1 != $loop_count && '' != $output_str ) ? ' ' . $separator . ' ' : '';
						$output_str .= $tags;
					}
					break;

				case 'comments':
					$comment = astra_post_comments();
					if ( '' != $comment ) {
						$output_str .= ( 1 != $loop_count && '' != $output_str ) ? ' ' . $separator . ' ' : '';
						$output_str .= $comment;
					}
					break;
				default:
					$output_str = apply_filters( 'astra_meta_case_' . $meta_value, $output_str, $loop_count, $separator );

			}

			$loop_count ++;
		}

		return $output_str;
	}
}

/**
 * Function to get Date of Post
 *
 * @since 1.0.0
 * @return html
 */
if ( ! function_exists( 'astra_post_date' ) ) {

	/**
	 * Function to get Date of Post
	 *
	 * @return html                Markup.
	 */
	function astra_post_date() {

		$output        = '';
		$format        = apply_filters( 'astra_post_date_format', '' );
		$time_string   = esc_html( get_the_date( $format ) );
		$modified_date = esc_html( get_the_modified_date( $format ) );
		$posted_on     = sprintf(
			esc_html( '%s' ),
			$time_string
		);
		$modified_on   = sprintf(
			esc_html( '%s' ),
			$modified_date
		);
		$output       .= '<span class="posted-on">';
		$output       .= '<span class="published" itemprop="datePublished"> ' . $posted_on . '</span>';
		$output       .= '<span class="updated" itemprop="dateModified"> ' . $modified_on . '</span>';
		$output       .= '</span>';
		return apply_filters( 'astra_post_date', $output );
	}
}

/**
 * Function to get Author of Post
 *
 * @since 1.0.0
 * @return html
 */
if ( ! function_exists( 'astra_post_author' ) ) {

	/**
	 * Function to get Author of Post
	 *
	 * @param  string $output_filter Filter string.
	 * @return html                Markup.
	 */
	function astra_post_author( $output_filter = '' ) {

		ob_start();

		echo '<span ';
			echo astra_attr(
				'post-meta-author',
				array(
					'class'     => 'posted-by vcard author',
					'itemtype'  => 'https://schema.org/Person',
					'itemscope' => 'itemscope',
					'itemprop'  => 'author',
				)
			);
		echo '>';
			// Translators: Author Name. ?>
			<a class="url fn n" title="<?php printf( esc_attr__( 'View all posts by %1$s', 'astra' ), get_the_author() ); ?>" 
				href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" itemprop="url">
				<span class="author-name" itemprop="name"><?php echo get_the_author(); ?></span>
			</a>
		</span>

		<?php

		$output = ob_get_clean();

		return apply_filters( 'astra_post_author', $output, $output_filter );
	}
}

/**
 * Function to get Read More Link of Post
 *
 * @since 1.0.0
 * @return html
 */
if ( ! function_exists( 'astra_post_link' ) ) {

	/**
	 * Function to get Read More Link of Post
	 *
	 * @param  string $output_filter Filter string.
	 * @return html                Markup.
	 */
	function astra_post_link( $output_filter = '' ) {

		$enabled = apply_filters( 'astra_post_link_enabled', '__return_true' );
		if ( ( is_admin() && ! wp_doing_ajax() ) || ! $enabled ) {
			return $output_filter;
		}

		$read_more_text    = apply_filters( 'astra_post_read_more', __( 'Read More &raquo;', 'astra' ) );
		$read_more_classes = apply_filters( 'astra_post_read_more_class', array() );

		$post_link = sprintf(
			esc_html( '%s' ),
			'<a class="' . esc_attr( implode( ' ', $read_more_classes ) ) . '" href="' . esc_url( get_permalink() ) . '"> ' . the_title( '<span class="screen-reader-text">', '</span>', false ) . ' ' . $read_more_text . '</a>'
		);

		$output = ' &hellip;<p class="read-more"> ' . $post_link . '</p>';

		return apply_filters( 'astra_post_link', $output, $output_filter );
	}
}
add_filter( 'excerpt_more', 'astra_post_link', 1 );

/**
 * Function to get Number of Comments of Post
 *
 * @since 1.0.0
 * @return html
 */
if ( ! function_exists( 'astra_post_comments' ) ) {

	/**
	 * Function to get Number of Comments of Post
	 *
	 * @param  string $output_filter Output filter.
	 * @return html                Markup.
	 */
	function astra_post_comments( $output_filter = '' ) {

		$output = '';

		ob_start();
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			?>
			<span class="comments-link">
				<?php
				/**
				 * Get Comment Link
				 *
				 * @see astra_default_strings()
				 */
				comments_popup_link( astra_default_strings( 'string-blog-meta-leave-a-comment', false ), astra_default_strings( 'string-blog-meta-one-comment', false ), astra_default_strings( 'string-blog-meta-multiple-comment', false ) );
				?>

				<!-- Comment Schema Meta -->
				<?php
				echo '<span ';
					echo astra_attr(
						'comments-interactioncounter',
						array(
							'itemprop'  => 'interactionStatistic',
							'itemscope' => '',
							'itemtype'  => 'https://schema.org/InteractionCounter',
						)
					);
				echo '>';

					echo '<meta ';
						echo astra_attr(
							'comments-interactioncounter-interactiontype',
							array(
								'itemprop' => 'interactionType',
								'content'  => 'https://schema.org/CommentAction',
							)
						);
					echo '/>';

					echo '<meta ';
						echo astra_attr(
							'comments-interactioncounter-userinteractioncount',
							array(
								'itemprop' => 'userInteractionCount',
								'content'  => absint( wp_count_comments( get_the_ID() )->approved ),
							)
						);

					echo '/>';

				echo '</span>';
				?>
			</span>

			<?php
		}

		$output = ob_get_clean();

		return apply_filters( 'astra_post_comments', $output, $output_filter );
	}
}

/**
 * Function to get Tags applied of Post
 *
 * @since 1.0.0
 * @return html
 */
if ( ! function_exists( 'astra_post_tags' ) ) {

	/**
	 * Function to get Tags applied of Post
	 *
	 * @param  string $output_filter Output filter.
	 * @return html                Markup.
	 */
	function astra_post_tags( $output_filter = '' ) {

		$output = '';

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'astra' ) );

		if ( $tags_list ) {
			$output .= '<span class="tags-links">' . $tags_list . '</span>';
		}

		return apply_filters( 'astra_post_tags', $output, $output_filter );
	}
}

/**
 * Function to get Categories of Post
 *
 * @since 1.0.0
 * @return html
 */
if ( ! function_exists( 'astra_post_categories' ) ) {

	/**
	 * Function to get Categories applied of Post
	 *
	 * @param  string $output_filter Output filter.
	 * @return html                Markup.
	 */
	function astra_post_categories( $output_filter = '' ) {

		$output = '';

		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'astra' ) );

		if ( $categories_list ) {
			$output .= '<span class="cat-links">' . $categories_list . '</span>';
		}

		return apply_filters( 'astra_post_categories', $output, $output_filter );
	}
}

/**
 * Display classes for primary div
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'astra_blog_layout_class' ) ) {

	/**
	 * Layout class
	 *
	 * @param  string $class Class.
	 */
	function astra_blog_layout_class( $class = '' ) {

		// Separates classes with a single space, collates classes for body element.
		echo 'class="' . esc_attr( join( ' ', astra_get_blog_layout_class( $class ) ) ) . '"';
	}
}

/**
 * Retrieve the classes for the body element as an array.
 *
 * @since 1.0.0
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
if ( ! function_exists( 'astra_get_blog_layout_class' ) ) {

	/**
	 * Retrieve the classes for the body element as an array.
	 *
	 * @param string $class Class.
	 */
	function astra_get_blog_layout_class( $class = '' ) {

		// array of class names.
		$classes = array();

		$post_format = get_post_format();
		if ( $post_format ) {
			$post_format = 'standard';
		}

		$classes[] = 'ast-post-format-' . $post_format;

		if ( ! has_post_thumbnail() || ! wp_get_attachment_image_src( get_post_thumbnail_id() ) ) {
			switch ( $post_format ) {

				case 'aside':
								$classes[] = 'ast-no-thumb';
					break;

				case 'image':
								$has_image = astra_get_first_image_from_post();
					if ( empty( $has_image ) || is_single() ) {
						$classes[] = 'ast-no-thumb';
					}
					break;

				case 'video':
								$post_featured_data = astra_get_video_from_post( get_the_ID() );
					if ( empty( $post_featured_data ) ) {
						$classes[] = 'ast-no-thumb';
					}
					break;

				case 'quote':
								$classes[] = 'ast-no-thumb';
					break;

				case 'link':
								$classes[] = 'ast-no-thumb';
					break;

				case 'gallery':
								$post_featured_data = get_post_gallery();
					if ( empty( $post_featured_data ) || is_single() ) {
						$classes[] = 'ast-no-thumb';
					}
					break;

				case 'audio':
								$has_audio = astra_get_audios_from_post( get_the_ID() );
					if ( empty( $has_audio ) || is_single() ) {
						$classes[] = 'ast-no-thumb';
					} else {
						$classes[] = 'ast-embeded-audio';
					}
					break;

				case 'standard':
				default:
					if ( ! has_post_thumbnail() || ! wp_get_attachment_image_src( get_post_thumbnail_id() ) ) {
						$classes[] = 'ast-no-thumb';
					}
					break;
			}
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		/**
		 * Filter primary div class names
		 */
		$classes = apply_filters( 'astra_blog_layout_class', $classes, $class );

		$classes = array_map( 'sanitize_html_class', $classes );

		return array_unique( $classes );
	}
}

/**
 * Function to get Content Read More Link of Post
 *
 * @since 1.2.7
 * @return html
 */
if ( ! function_exists( 'astra_the_content_more_link' ) ) {

	/**
	 * Filters the Read More link text.
	 *
	 * @param  string $more_link_element Read More link element.
	 * @param  string $more_link_text Read More text.
	 * @return html                Markup.
	 */
	function astra_the_content_more_link( $more_link_element = '', $more_link_text = '' ) {

		$enabled = apply_filters( 'astra_the_content_more_link_enabled', '__return_true' );
		if ( ( is_admin() && ! wp_doing_ajax() ) || ! $enabled ) {
			return $more_link_element;
		}

		$more_link_text    = apply_filters( 'astra_the_content_more_string', __( 'Read More &raquo;', 'astra' ) );
		$read_more_classes = apply_filters( 'astra_the_content_more_link_class', array() );

		$post_link = sprintf(
			esc_html( '%s' ),
			'<a class="' . esc_attr( implode( ' ', $read_more_classes ) ) . '" href="' . esc_url( get_permalink() ) . '"> ' . the_title( '<span class="screen-reader-text">', '</span>', false ) . $more_link_text . '</a>'
		);

		$more_link_element = ' &hellip;<p class="ast-the-content-more-link"> ' . $post_link . '</p>';

		return apply_filters( 'astra_the_content_more_link', $more_link_element, $more_link_text );
	}
}
add_filter( 'the_content_more_link', 'astra_the_content_more_link', 10, 2 );
