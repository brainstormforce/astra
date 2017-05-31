<?php
/**
 * Blog Config File
 * Common Functions for Blog and Single Blog
 *
 * @package Astra
 */

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

		foreach ( $post_meta as $meta_value ) {

			switch ( $meta_value ) {

				case 'author':
					$output_str .= ( 1 != $loop_count && '' != $output_str ) ? ' ' . $separator . ' ' : '';
					$output_str .= esc_html( astra_default_strings( 'string-blog-meta-author-by', false ) ) . astra_post_author();
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
			}// End switch().

			$loop_count ++;
		}// End foreach().

		return $output_str;
	}
}// End if().

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

		$output = '';
		$format = apply_filters( 'astra_post_date_format','' );
		$time_string = esc_html( get_the_date( $format ) );
		$posted_on = sprintf(
			/* translators: 1: post date */
			esc_html_x( '%s ', 'post date', 'astra' ),
			$time_string
		);
		$output .= '<span class="posted-on" itemprop="datePublished"> ' . $posted_on . '</span>';
		return apply_filters( 'astra_post_date', $output );
	}
}// End if().

/**
 * Function to get Date Box of Post
 *
 * @since 1.0.0
 * @return html
 */
if ( ! function_exists( 'astra_post_date_box' ) ) {

	/**
	 * Function to get Date of Post
	 *
	 * @return html                Markup.
	 */
	function astra_post_date_box() {
		$output = '';

		$time_string = '<time class="entry-date published updated" datetime="%1$s"><span class="date-month">%2$s</span> <span class="date-day">%3$s</span> <span class="date-year">%4$s</span></time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s"><span class="date-month">%2$s</span> <span class="date-day">%3$s</span> <span class="date-year">%4$s</span></time><time class="updated" datetime="%5$s">%6$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date( 'M' ) ),
			esc_html( get_the_date( 'j' ) ),
			esc_html( get_the_date( 'Y' ) ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: 1: post date */
			esc_html_x( '%s ', 'post date', 'astra' ),
			$time_string
		);

		return '<span class="posted-on">' . $posted_on . '</span>';
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
		$output = '';

		$byline = sprintf(
			/* translators: 1: post author */
			esc_html_x( '%s ', 'post author', 'astra' ),
			'<a class="url fn n" title="View all posts by ' . esc_attr( get_the_author() ) . '" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author" itemprop="url"> <span class="author-name" itemprop="name">' . esc_html( get_the_author() ) . '</span> </a>'
		);

		$output .= '<span class="posted-by" itemtype="http://schema.org/Person" itemscope="itemscope" itemprop="author"> ' . $byline . '</span>';

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

		if ( is_admin() ) {
			return $output_filter;
		}

		$post_link = sprintf(
			/* translators: 1: post link */
			esc_html_x( '%s ', 'post link', 'astra' ),
			'<a href="' . esc_url( get_permalink() ) . '"> ' . the_title( '<span class="screen-reader-text">', '</span>', false ) . __( 'Read More &raquo;', 'astra' ) . '</a>'
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
				comments_popup_link( astra_default_strings( 'string-blog-meta-leave-a-comment', false ), astra_default_strings( 'string-blog-meta-one-comment', false ), astra_default_strings( 'string-blog-meta-multiple-comment', false ) ); ?>
				
				<!-- Comment Schema Meta -->
				<span itemprop="interactionStatistic" itemscope itemtype="http://schema.org/InteractionCounter">
					<meta itemprop="interactionType" content="http://schema.org/CommentAction" />
					<meta itemprop="userInteractionCount" content="<?php echo absint( wp_count_comments( get_the_ID() )->approved ); ?>" />
				</span>
			</span>

			<?php
		}

		$output = ob_get_clean();

		return apply_filters( 'astra_post_comments', $output, $output_filter );
	}
}// End if().

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
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'astra' ) );
		if ( $tags_list ) {
			$tags = sprintf( // WPCS: XSS OK.
				/* translators: 1: post tags */
				esc_html_x( '%1$s ', 'post tags', 'astra' ), $tags_list
			);

			$output .= '<span class="tags-links">' . $tags . '</span>';
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
		$categories_list = get_the_category_list( esc_html__( ', ', 'astra' ) );

		if ( $categories_list ) {
			$categories = sprintf(
				/* translators: 1: post tags */
				esc_html_x( '%1$s ', 'post categories', 'astra' ), $categories_list
			);

			$output .= '<span class="cat-links">' . $categories . '</span>';
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

		$classes[]   = 'ast-post-format-' . $post_format;

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
			}// End switch().
		}// End if().

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
}// End if().
