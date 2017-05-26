<?php
/**
 * Blog Helper Functions
 *
 * @package Astra
 */

/**
 * Adds custom classes to the array of body classes.
 */
if ( ! function_exists( 'astra_blog_body_classes' ) ) {

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function astra_blog_body_classes( $classes ) {

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		return $classes;
	}
}

add_filter( 'body_class', 'astra_blog_body_classes' );

/**
 * Adds custom classes to the array of post grid classes.
 */
if ( ! function_exists( 'astra_post_class_blog_grid' ) ) {

	/**
	 * Adds custom classes to the array of post grid classes.
	 *
	 * @since 1.0
	 * @param array $classes Classes for the post element.
	 * @return array
	 */
	function astra_post_class_blog_grid( $classes ) {

		if ( is_archive() || is_home() || is_search() ) {
			$classes[] = 'ast-col-sm-12';
			$classes[] = 'ast-article-post';
		}

		return $classes;
	}
}

add_filter( 'post_class', 'astra_post_class_blog_grid' );

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
if ( ! function_exists( 'astra_blog_get_post_meta' ) ) {

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since 1.0
	 * @param  array  $disabled  Disabled post meta.
	 * @param  string $separator Meta separator.
	 * @return mixed            Markup.
	 */
	function astra_blog_get_post_meta( $disabled = array(), $separator = '/' ) {

		$post_meta = astra_get_option( 'blog-meta' );

		if ( is_array( $post_meta ) ) {

			$output_str = astra_get_post_meta( $post_meta );

			if ( 'post' == get_post_type() && ! empty( $output_str ) ) {
				echo apply_filters( 'astra_blog_post_meta', '<div class="entry-meta">' . wp_kses_post( $output_str ) . '</div>' );
			}
		}
	}
}

/**
 * Featured post meta.
 */
if ( ! function_exists( 'astra_blog_post_get_featured_item' ) ) {

	/**
	 * To featured image / gallery / audio / video etc. As per the post format.
	 *
	 * @since 1.0
	 * @return mixed
	 */
	function astra_blog_post_get_featured_item() {

		$post_featured_data = '';
		$post_format        = get_post_format();

		if ( has_post_thumbnail() ) {

			$post_featured_data  = '<a href="' . esc_url( get_permalink() ) . '" >';
			$post_featured_data .= get_the_post_thumbnail();
			$post_featured_data .= '</a>';

		} else {

			switch ( $post_format ) {
				case 'image':
					break;

				case 'video':
									$post_featured_data = astra_get_video_from_post( get_the_ID() );
					break;

				case 'gallery':
									$post_featured_data = get_post_gallery( get_the_ID(), false );
					if ( isset( $post_featured_data['ids'] ) ) {
						$img_ids = explode( ',', $post_featured_data['ids'] );

						$image_alt = get_post_meta( $img_ids[0], '_wp_attachment_image_alt', true );
						$image_url = wp_get_attachment_url( $img_ids[0] );

						if ( isset( $img_ids[0] ) ) {
							$post_featured_data  = '<a href="' . esc_url( get_permalink() ) . '" >';
							$post_featured_data .= '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image_alt ) . '" >';
							$post_featured_data .= '</a>';
						}
					}
					break;

				case 'audio':
									$post_featured_data = do_shortcode( astra_get_audios_from_post( get_the_ID() ) );
					break;
			}
		}// End if().

		echo $post_featured_data;
	}
}// End if().

add_action( 'astra_blog_post_featured_format', 'astra_blog_post_get_featured_item' );

/**
 * Get audio files from post content
 */
if ( ! function_exists( 'astra_get_audios_from_post' ) ) {

	/**
	 * Get audio files from post content
	 *
	 * @param  number $post_id Post id.
	 * @return mixed          Iframe.
	 */
	function astra_get_audios_from_post( $post_id ) {

		// for audio post type - grab.
		$post    = get_post( $post_id );
		$content = do_shortcode( apply_filters( 'the_content', $post->post_content ) );
		$embeds  = get_media_embedded_in_content( $content );

		if ( empty( $embeds ) ) {
			return '';
		}

		// check what is the first embed containg video tag, youtube or vimeo.
		foreach ( $embeds as $embed ) {
			if ( strpos( $embed, 'audio' ) ) {
				return '<span class="ast-post-audio-wrapper">' . $embed . '</span>';
			}
		}
	}
}

/**
 * Get first image from post content
 */
if ( ! function_exists( 'astra_get_video_from_post' ) ) {

	/**
	 * Get first image from post content
	 *
	 * @since 1.0
	 * @param  number $post_id Post id.
	 * @return mixed
	 */
	function astra_get_video_from_post( $post_id ) {

		$post    = get_post( $post_id );
		$content = do_shortcode( apply_filters( 'the_content', $post->post_content ) );
		$embeds  = get_media_embedded_in_content( $content );

		if ( empty( $embeds ) ) {
			return '';
		}

		// check what is the first embed containg video tag, youtube or vimeo.
		foreach ( $embeds as $embed ) {
			if ( strpos( $embed, 'video' ) || strpos( $embed, 'youtube' ) || strpos( $embed, 'vimeo' ) ) {
				return $embed;
			}
		}
	}
}
