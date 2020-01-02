<?php
/**
 * Functions for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Foreground Color
 */
if ( ! function_exists( 'astra_get_foreground_color' ) ) {

	/**
	 * Foreground Color
	 *
	 * @param  string $hex Color code in HEX format.
	 * @return string      Return foreground color depend on input HEX color.
	 */
	function astra_get_foreground_color( $hex ) {

		// bail early if color's not set.
		if ( 'transparent' == $hex || 'false' == $hex || '#' == $hex || empty( $hex ) ) {
			return 'transparent';

		}

		// Get clean hex code.
		$hex = str_replace( '#', '', $hex );

		if ( 3 == strlen( $hex ) ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Get r, g & b codes from hex code.
		$r   = hexdec( substr( $hex, 0, 2 ) );
		$g   = hexdec( substr( $hex, 2, 2 ) );
		$b   = hexdec( substr( $hex, 4, 2 ) );
		$hex = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;

		return 128 <= $hex ? '#000000' : '#ffffff';
	}
}

/**
 * Generate CSS
 */
if ( ! function_exists( 'astra_css' ) ) {

	/**
	 * Generate CSS
	 *
	 * @param  mixed  $value         CSS value.
	 * @param  string $css_property CSS property.
	 * @param  string $selector     CSS selector.
	 * @param  string $unit         CSS property unit.
	 * @return void               Echo generated CSS.
	 */
	function astra_css( $value = '', $css_property = '', $selector = '', $unit = '' ) {

		if ( $selector ) {
			if ( $css_property && $value ) {

				if ( '' != $unit ) {
					$value .= $unit;
				}

				$css  = $selector;
				$css .= '{';
				$css .= '	' . $css_property . ': ' . $value . ';';
				$css .= '}';

				echo $css;
			}
		}
	}
}

/**
 * Get Font Size value
 */
if ( ! function_exists( 'astra_responsive_font' ) ) {

	/**
	 * Get Font CSS value
	 *
	 * @param  array  $font    CSS value.
	 * @param  string $device  CSS device.
	 * @param  string $default Default value.
	 * @return mixed
	 */
	function astra_responsive_font( $font, $device = 'desktop', $default = '' ) {

		$css_val = '';

		if ( isset( $font[ $device ] ) && isset( $font[ $device . '-unit' ] ) ) {
			if ( '' != $default ) {
				$font_size = astra_get_css_value( $font[ $device ], $font[ $device . '-unit' ], $default );
			} else {
				$font_size = astra_get_font_css_value( $font[ $device ], $font[ $device . '-unit' ] );
			}
		} elseif ( is_numeric( $font ) ) {
			$font_size = astra_get_css_value( $font );
		} else {
			$font_size = ( ! is_array( $font ) ) ? $font : '';
		}

		return $font_size;
	}
}

/**
 * Get Font Size value
 */
if ( ! function_exists( 'astra_get_font_css_value' ) ) {

	/**
	 * Get Font CSS value
	 *
	 * Syntax:
	 *
	 *  astra_get_font_css_value( VALUE, DEVICE, UNIT );
	 *
	 * E.g.
	 *
	 *  astra_get_css_value( VALUE, 'desktop', '%' );
	 *  astra_get_css_value( VALUE, 'tablet' );
	 *  astra_get_css_value( VALUE, 'mobile' );
	 *
	 * @param  string $value        CSS value.
	 * @param  string $unit         CSS unit.
	 * @param  string $device       CSS device.
	 * @return mixed                CSS value depends on $unit & $device
	 */
	function astra_get_font_css_value( $value, $unit = 'px', $device = 'desktop' ) {

		// If value is empty or 0 then return blank.
		if ( '' == $value || 0 == $value ) {
			return '';
		}

		$css_val = '';

		switch ( $unit ) {
			case 'em':
			case '%':
						$css_val = esc_attr( $value ) . $unit;
				break;

			case 'px':
				if ( is_numeric( $value ) || strpos( $value, 'px' ) ) {
					$value            = intval( $value );
					$fonts            = array();
					$body_font_size   = astra_get_option( 'font-size-body' );
					$fonts['desktop'] = ( isset( $body_font_size['desktop'] ) && '' != $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 15;
					$fonts['tablet']  = ( isset( $body_font_size['tablet'] ) && '' != $body_font_size['tablet'] ) ? $body_font_size['tablet'] : $fonts['desktop'];
					$fonts['mobile']  = ( isset( $body_font_size['mobile'] ) && '' != $body_font_size['mobile'] ) ? $body_font_size['mobile'] : $fonts['tablet'];

					if ( $fonts[ $device ] ) {
						$css_val = esc_attr( $value ) . 'px;font-size:' . ( esc_attr( $value ) / esc_attr( $fonts[ $device ] ) ) . 'rem';
					}
				} else {
					$css_val = esc_attr( $value );
				}
		}

		return $css_val;
	}
}

/**
 * Get Font family
 */
if ( ! function_exists( 'astra_get_font_family' ) ) {

	/**
	 * Get Font family
	 *
	 * Syntax:
	 *
	 *  astra_get_font_family( VALUE, DEFAULT );
	 *
	 * E.g.
	 *  astra_get_font_family( VALUE, '' );
	 *
	 * @since  1.0.19
	 *
	 * @param  string $value       CSS value.
	 * @return mixed               CSS value depends on $unit
	 */
	function astra_get_font_family( $value = '' ) {
		$system_fonts = Astra_Font_Families::get_system_fonts();
		if ( isset( $system_fonts[ $value ] ) && isset( $system_fonts[ $value ]['fallback'] ) ) {
			$value .= ',' . $system_fonts[ $value ]['fallback'];
		}

		return $value;
	}
}


/**
 * Get CSS value
 */
if ( ! function_exists( 'astra_get_css_value' ) ) {

	/**
	 * Get CSS value
	 *
	 * Syntax:
	 *
	 *  astra_get_css_value( VALUE, UNIT );
	 *
	 * E.g.
	 *
	 *  astra_get_css_value( VALUE, 'url' );
	 *  astra_get_css_value( VALUE, 'px' );
	 *  astra_get_css_value( VALUE, 'em' );
	 *
	 * @param  string $value        CSS value.
	 * @param  string $unit         CSS unit.
	 * @param  string $default      CSS default font.
	 * @return mixed               CSS value depends on $unit
	 */
	function astra_get_css_value( $value = '', $unit = 'px', $default = '' ) {

		if ( '' == $value && '' == $default ) {
			return $value;
		}

		$css_val = '';

		switch ( $unit ) {

			case 'font':
				if ( 'inherit' != $value ) {
					$value   = astra_get_font_family( $value );
					$css_val = $value;
				} elseif ( '' != $default ) {
					$css_val = $default;
				} else {
					$css_val = '';
				}
				break;

			case 'px':
			case '%':
				if ( 'inherit' === strtolower( $value ) || 'inherit' === strtolower( $default ) ) {
					return $value;
				}

				$value   = ( '' != $value ) ? $value : $default;
				$css_val = esc_attr( $value ) . $unit;
				break;

			case 'url':
						$css_val = $unit . '(' . esc_url( $value ) . ')';
				break;

			case 'rem':
				if ( 'inherit' === strtolower( $value ) || 'inherit' === strtolower( $default ) ) {
					return $value;
				}
				if ( is_numeric( $value ) || strpos( $value, 'px' ) ) {
					$value          = intval( $value );
					$body_font_size = astra_get_option( 'font-size-body' );
					if ( is_array( $body_font_size ) ) {
						$body_font_size_desktop = ( isset( $body_font_size['desktop'] ) && '' != $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 15;
					} else {
						$body_font_size_desktop = ( '' != $body_font_size ) ? $body_font_size : 15;
					}

					if ( $body_font_size_desktop ) {
						$css_val = esc_attr( $value ) . 'px;font-size:' . ( esc_attr( $value ) / esc_attr( $body_font_size_desktop ) ) . $unit;
					}
				} else {
					$css_val = esc_attr( $value );
				}

				break;

			default:
				$value = ( '' != $value ) ? $value : $default;
				if ( '' != $value ) {
					$css_val = esc_attr( $value ) . $unit;
				}
		}

		return $css_val;
	}
}

/**
 * Adjust the background obj.
 */
if ( ! function_exists( 'astra_get_background_obj' ) ) {

	/**
	 * Adjust Brightness
	 *
	 * @param  array $bg_obj   Color code in HEX.
	 *
	 * @return array         Color code in HEX.
	 */
	function astra_get_background_obj( $bg_obj ) {

		$gen_bg_css = array();

		$bg_img   = isset( $bg_obj['background-image'] ) ? $bg_obj['background-image'] : '';
		$bg_color = isset( $bg_obj['background-color'] ) ? $bg_obj['background-color'] : '';

		if ( '' !== $bg_img && '' !== $bg_color ) {
			$gen_bg_css = array(
				'background-color' => 'unset',
				'background-image' => 'linear-gradient(to right, ' . esc_attr( $bg_color ) . ', ' . esc_attr( $bg_color ) . '), url(' . esc_url( $bg_img ) . ')',
			);
		} elseif ( '' !== $bg_img ) {
			$gen_bg_css = array( 'background-image' => 'url(' . esc_url( $bg_img ) . ')' );
		} elseif ( '' !== $bg_color ) {
			$gen_bg_css = array( 'background-color' => esc_attr( $bg_color ) );
		}

		if ( '' !== $bg_img ) {
			if ( isset( $bg_obj['background-repeat'] ) ) {
				$gen_bg_css['background-repeat'] = esc_attr( $bg_obj['background-repeat'] );
			}

			if ( isset( $bg_obj['background-position'] ) ) {
				$gen_bg_css['background-position'] = esc_attr( $bg_obj['background-position'] );
			}

			if ( isset( $bg_obj['background-size'] ) ) {
				$gen_bg_css['background-size'] = esc_attr( $bg_obj['background-size'] );
			}

			if ( isset( $bg_obj['background-attachment'] ) ) {
				$gen_bg_css['background-attachment'] = esc_attr( $bg_obj['background-attachment'] );
			}
		}

		return $gen_bg_css;
	}
}

/**
 * Parse CSS
 */
if ( ! function_exists( 'astra_parse_css' ) ) {

	/**
	 * Parse CSS
	 *
	 * @param  array  $css_output Array of CSS.
	 * @param  string $min_media  Min Media breakpoint.
	 * @param  string $max_media  Max Media breakpoint.
	 * @return string             Generated CSS.
	 */
	function astra_parse_css( $css_output = array(), $min_media = '', $max_media = '' ) {

		$parse_css = '';
		if ( is_array( $css_output ) && count( $css_output ) > 0 ) {

			foreach ( $css_output as $selector => $properties ) {

				if ( null === $properties ) {
					break;
				}

				if ( ! count( $properties ) ) {
					continue; }

				$temp_parse_css   = $selector . '{';
				$properties_added = 0;

				foreach ( $properties as $property => $value ) {

					if ( '' === $value ) {
						continue; }

					$properties_added++;
					$temp_parse_css .= $property . ':' . $value . ';';
				}

				$temp_parse_css .= '}';

				if ( $properties_added > 0 ) {
					$parse_css .= $temp_parse_css;
				}
			}

			if ( '' != $parse_css && ( '' !== $min_media || '' !== $max_media ) ) {

				$media_css       = '@media ';
				$min_media_css   = '';
				$max_media_css   = '';
				$media_separator = '';

				if ( '' !== $min_media ) {
					$min_media_css = '(min-width:' . $min_media . 'px)';
				}
				if ( '' !== $max_media ) {
					$max_media_css = '(max-width:' . $max_media . 'px)';
				}
				if ( '' !== $min_media && '' !== $max_media ) {
					$media_separator = ' and ';
				}

				$media_css .= $min_media_css . $media_separator . $max_media_css . '{' . $parse_css . '}';

				return $media_css;
			}
		}

		return $parse_css;
	}
}

/**
 * Return Theme options.
 */
if ( ! function_exists( 'astra_get_option' ) ) {

	/**
	 * Return Theme options.
	 *
	 * @param  string $option       Option key.
	 * @param  string $default      Option default value.
	 * @param  string $deprecated   Option default value.
	 * @return Mixed               Return option value.
	 */
	function astra_get_option( $option, $default = '', $deprecated = '' ) {

		if ( '' != $deprecated ) {
			$default = $deprecated;
		}

		$theme_options = Astra_Theme_Options::get_options();

		/**
		 * Filter the options array for Astra Settings.
		 *
		 * @since  1.0.20
		 * @var Array
		 */
		$theme_options = apply_filters( 'astra_get_option_array', $theme_options, $option, $default );

		$value = ( isset( $theme_options[ $option ] ) && '' !== $theme_options[ $option ] ) ? $theme_options[ $option ] : $default;

		/**
		 * Dynamic filter astra_get_option_$option.
		 * $option is the name of the Astra Setting, Refer Astra_Theme_Options::defaults() for option names from the theme.
		 *
		 * @since  1.0.20
		 * @var Mixed.
		 */
		return apply_filters( "astra_get_option_{$option}", $value, $option, $default );
	}
}

if ( ! function_exists( 'astra_update_option' ) ) {

	/**
	 * Update Theme options.
	 *
	 * @param  string $option option key.
	 * @param  Mixed  $value  option value.
	 * @return void
	 */
	function astra_update_option( $option, $value ) {

		do_action( "astra_before_update_option_{$option}", $value, $option );

		// Get all customizer options.
		$theme_options = get_option( ASTRA_THEME_SETTINGS );

		// Update value in options array.
		$theme_options[ $option ] = $value;

		update_option( ASTRA_THEME_SETTINGS, $theme_options );

		do_action( "astra_after_update_option_{$option}", $value, $option );
	}
}

if ( ! function_exists( 'astra_delete_option' ) ) {

	/**
	 * Update Theme options.
	 *
	 * @param  string $option option key.
	 * @return void
	 */
	function astra_delete_option( $option ) {

		do_action( "astra_before_delete_option_{$option}", $option );

		// Get all customizer options.
		$theme_options = get_option( ASTRA_THEME_SETTINGS );

		// Update value in options array.
		unset( $theme_options[ $option ] );

		update_option( ASTRA_THEME_SETTINGS, $theme_options );

		do_action( "astra_after_delete_option_{$option}", $option );
	}
}

/**
 * Return Theme options from postmeta.
 */
if ( ! function_exists( 'astra_get_option_meta' ) ) {

	/**
	 * Return Theme options from postmeta.
	 *
	 * @param  string  $option_id Option ID.
	 * @param  string  $default   Option default value.
	 * @param  boolean $only_meta Get only meta value.
	 * @param  string  $extension Is value from extension.
	 * @param  string  $post_id   Get value from specific post by post ID.
	 * @return Mixed             Return option value.
	 */
	function astra_get_option_meta( $option_id, $default = '', $only_meta = false, $extension = '', $post_id = '' ) {

		$post_id = ( '' != $post_id ) ? $post_id : astra_get_post_id();

		$value = astra_get_option( $option_id, $default );

		// Get value from option 'post-meta'.
		if ( is_singular() || ( is_home() && ! is_front_page() ) ) {

			$value = get_post_meta( $post_id, $option_id, true );

			if ( empty( $value ) || 'default' == $value ) {

				if ( true == $only_meta ) {
					return false;
				}

				$value = astra_get_option( $option_id, $default );
			}
		}

		/**
		 * Dynamic filter astra_get_option_meta_$option.
		 * $option_id is the name of the Astra Meta Setting.
		 *
		 * @since  1.0.20
		 * @var Mixed.
		 */
		return apply_filters( "astra_get_option_meta_{$option_id}", $value, $default, $default );
	}
}

/**
 * Helper function to get the current post id.
 */
if ( ! function_exists( 'astra_get_post_id' ) ) {

	/**
	 * Get post ID.
	 *
	 * @param  string $post_id_override Get override post ID.
	 * @return number                   Post ID.
	 */
	function astra_get_post_id( $post_id_override = '' ) {

		if ( null == Astra_Theme_Options::$post_id ) {
			global $post;

			$post_id = 0;

			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' );
			} elseif ( is_archive() ) {
				global $wp_query;
				$post_id = $wp_query->get_queried_object_id();
			} elseif ( isset( $post->ID ) && ! is_search() && ! is_category() ) {
				$post_id = $post->ID;
			}

			Astra_Theme_Options::$post_id = $post_id;
		}

		return apply_filters( 'astra_get_post_id', Astra_Theme_Options::$post_id, $post_id_override );
	}
}


/**
 * Display classes for primary div
 */
if ( ! function_exists( 'astra_primary_class' ) ) {

	/**
	 * Display classes for primary div
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return void        Echo classes.
	 */
	function astra_primary_class( $class = '' ) {

		// Separates classes with a single space, collates classes for body element.
		echo 'class="' . esc_attr( join( ' ', astra_get_primary_class( $class ) ) ) . '"';
	}
}

/**
 * Retrieve the classes for the primary element as an array.
 */
if ( ! function_exists( 'astra_get_primary_class' ) ) {

	/**
	 * Retrieve the classes for the primary element as an array.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array        Return array of classes.
	 */
	function astra_get_primary_class( $class = '' ) {

		// array of class names.
		$classes = array();

		// default class for content area.
		$classes[] = 'content-area';

		// primary base class.
		$classes[] = 'primary';

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {

			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		// Filter primary div class names.
		$classes = apply_filters( 'astra_primary_class', $classes, $class );

		$classes = array_map( 'sanitize_html_class', $classes );

		return array_unique( $classes );
	}
}

/**
 * Display classes for secondary div
 */
if ( ! function_exists( 'astra_secondary_class' ) ) {

	/**
	 * Retrieve the classes for the secondary element as an array.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return void        echo classes.
	 */
	function astra_secondary_class( $class = '' ) {

		// Separates classes with a single space, collates classes for body element.
		echo 'class="' . esc_attr( join( ' ', astra_get_secondary_class( $class ) ) ) . '"';
	}
}

/**
 * Retrieve the classes for the secondary element as an array.
 */
if ( ! function_exists( 'astra_get_secondary_class' ) ) {

	/**
	 * Retrieve the classes for the secondary element as an array.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array        Return array of classes.
	 */
	function astra_get_secondary_class( $class = '' ) {

		// array of class names.
		$classes = array();

		// default class from widget area.
		$classes[] = 'widget-area';

		// secondary base class.
		$classes[] = 'secondary';

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {

			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		// Filter secondary div class names.
		$classes = apply_filters( 'astra_secondary_class', $classes, $class );

		$classes = array_map( 'sanitize_html_class', $classes );

		return array_unique( $classes );
	}
}

/**
 * Get post format
 */
if ( ! function_exists( 'astra_get_post_format' ) ) {

	/**
	 * Get post format
	 *
	 * @param  string $post_format_override Override post formate.
	 * @return string                       Return post format.
	 */
	function astra_get_post_format( $post_format_override = '' ) {

		if ( ( is_home() ) || is_archive() ) {
			$post_format = 'blog';
		} else {
			$post_format = get_post_format();
		}

		return apply_filters( 'astra_get_post_format', $post_format, $post_format_override );
	}
}

/**
 * Wrapper function for get_the_title() for blog post.
 */
if ( ! function_exists( 'astra_the_post_title' ) ) {

	/**
	 * Wrapper function for get_the_title() for blog post.
	 *
	 * Displays title only if the page title bar is disabled.
	 *
	 * @since 1.0.15
	 * @param string $before Optional. Content to prepend to the title.
	 * @param string $after  Optional. Content to append to the title.
	 * @param int    $post_id Optional, default to 0. Post id.
	 * @param bool   $echo   Optional, default to true.Whether to display or return.
	 * @return string|void String if $echo parameter is false.
	 */
	function astra_the_post_title( $before = '', $after = '', $post_id = 0, $echo = true ) {

		$enabled = apply_filters( 'astra_the_post_title_enabled', true );
		if ( $enabled ) {

			$title  = astra_get_the_title( $post_id );
			$before = apply_filters( 'astra_the_post_title_before', $before );
			$after  = apply_filters( 'astra_the_post_title_after', $after );

			// This will work same as `the_title` function but with Custom Title if exits.
			if ( $echo ) {
				echo $before . $title . $after; // WPCS: XSS OK.
			} else {
				return $before . $title . $after;
			}
		}
	}
}

/**
 * Wrapper function for the_title()
 */
if ( ! function_exists( 'astra_the_title' ) ) {

	/**
	 * Wrapper function for the_title()
	 *
	 * Displays title only if the page title bar is disabled.
	 *
	 * @param string $before Optional. Content to prepend to the title.
	 * @param string $after  Optional. Content to append to the title.
	 * @param int    $post_id Optional, default to 0. Post id.
	 * @param bool   $echo   Optional, default to true.Whether to display or return.
	 * @return string|void String if $echo parameter is false.
	 */
	function astra_the_title( $before = '', $after = '', $post_id = 0, $echo = true ) {

		$title             = '';
		$blog_post_title   = astra_get_option( 'blog-post-structure' );
		$single_post_title = astra_get_option( 'blog-single-post-structure' );

		if ( ( ( ! is_singular() && in_array( 'title-meta', $blog_post_title ) ) || ( is_single() && in_array( 'single-title-meta', $single_post_title ) ) || is_page() ) ) {
			if ( apply_filters( 'astra_the_title_enabled', true ) ) {

				$title  = astra_get_the_title( $post_id );
				$before = apply_filters( 'astra_the_title_before', $before );
				$after  = apply_filters( 'astra_the_title_after', $after );

				$title = $before . $title . $after;
			}
		}

		// This will work same as `the_title` function but with Custom Title if exits.
		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}
}

/**
 * Wrapper function for get_the_title()
 */
if ( ! function_exists( 'astra_get_the_title' ) ) {

	/**
	 * Wrapper function for get_the_title()
	 *
	 * Return title for Title Bar and Normal Title.
	 *
	 * @param int  $post_id Optional, default to 0. Post id.
	 * @param bool $echo   Optional, default to false. Whether to display or return.
	 * @return string|void String if $echo parameter is false.
	 */
	function astra_get_the_title( $post_id = 0, $echo = false ) {

		$title = '';
		if ( $post_id || is_singular() ) {
			$title = get_the_title( $post_id );
		} else {
			if ( is_front_page() && is_home() ) {
				// Default homepage.
				$title = apply_filters( 'astra_the_default_home_page_title', esc_html__( 'Home', 'astra' ) );
			} elseif ( is_home() ) {
				// blog page.
				$title = apply_filters( 'astra_the_blog_home_page_title', get_the_title( get_option( 'page_for_posts', true ) ) );
			} elseif ( is_404() ) {
				// for 404 page - title always display.
				$title = apply_filters( 'astra_the_404_page_title', esc_html__( 'This page doesn\'t seem to exist.', 'astra' ) );

				// for search page - title always display.
			} elseif ( is_search() ) {

				/* translators: 1: search string */
				$title = apply_filters( 'astra_the_search_page_title', sprintf( __( 'Search Results for: %s', 'astra' ), '<span>' . get_search_query() . '</span>' ) );

			} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {

				$title = woocommerce_page_title( false );

			} elseif ( is_archive() ) {

				$title = get_the_archive_title();

			}
		}

		$title = apply_filters( 'astra_the_title', $title, $post_id );

		// This will work same as `get_the_title` function but with Custom Title if exits.
		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}
}

/**
 * Archive Page Title
 */
if ( ! function_exists( 'astra_archive_page_info' ) ) {

	/**
	 * Wrapper function for the_title()
	 *
	 * Displays title only if the page title bar is disabled.
	 */
	function astra_archive_page_info() {

		if ( apply_filters( 'astra_the_title_enabled', true ) ) {

			// Author.
			if ( is_author() ) { ?>

				<section class="ast-author-box ast-archive-description">
					<div class="ast-author-bio">
						<?php do_action( 'astra_before_archive_title' ); ?>
						<h1 class='page-title ast-archive-title'><?php echo get_the_author(); ?></h1>
						<?php do_action( 'astra_after_archive_title' ); ?>
						<p><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
						<?php do_action( 'astra_after_archive_description' ); ?>
					</div>
					<div class="ast-author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'email' ), 120 ); ?>
					</div>
				</section>

				<?php

				// Category.
			} elseif ( is_category() ) {
				?>

				<section class="ast-archive-description">
					<?php do_action( 'astra_before_archive_title' ); ?>
					<h1 class="page-title ast-archive-title"><?php echo single_cat_title(); ?></h1>
					<?php do_action( 'astra_after_archive_title' ); ?>
					<?php the_archive_description(); ?>
					<?php do_action( 'astra_after_archive_description' ); ?>
				</section>

				<?php

				// Tag.
			} elseif ( is_tag() ) {
				?>

				<section class="ast-archive-description">
					<?php do_action( 'astra_before_archive_title' ); ?>
					<h1 class="page-title ast-archive-title"><?php echo single_tag_title(); ?></h1>
					<?php do_action( 'astra_after_archive_title' ); ?>
					<?php the_archive_description(); ?>
					<?php do_action( 'astra_after_archive_description' ); ?>
				</section>

				<?php

				// Search.
			} elseif ( is_search() ) {
				?>

				<section class="ast-archive-description">
					<?php do_action( 'astra_before_archive_title' ); ?>
					<?php
						/* translators: 1: search string */
						$title = apply_filters( 'astra_the_search_page_title', sprintf( __( 'Search Results for: %s', 'astra' ), '<span>' . get_search_query() . '</span>' ) );
					?>
					<h1 class="page-title ast-archive-title"> <?php echo $title; ?> </h1>
					<?php do_action( 'astra_after_archive_title' ); ?>
				</section>

				<?php

				// Other.
			} else {
				?>

				<section class="ast-archive-description">
					<?php do_action( 'astra_before_archive_title' ); ?>
					<?php the_archive_title( '<h1 class="page-title ast-archive-title">', '</h1>' ); ?>
					<?php do_action( 'astra_after_archive_title' ); ?>
					<?php the_archive_description(); ?>
					<?php do_action( 'astra_after_archive_description' ); ?>
				</section>

				<?php
			}
		}
	}

	add_action( 'astra_archive_header', 'astra_archive_page_info' );
}


/**
 * Adjust the HEX color brightness
 */
if ( ! function_exists( 'astra_adjust_brightness' ) ) {

	/**
	 * Adjust Brightness
	 *
	 * @param  string $hex   Color code in HEX.
	 * @param  number $steps brightness value.
	 * @param  string $type  brightness is reverse or default.
	 * @return string        Color code in HEX.
	 */
	function astra_adjust_brightness( $hex, $steps, $type ) {

		// Get rgb vars.
		$hex = str_replace( '#', '', $hex );

		$shortcode_atts = array(
			'r' => hexdec( substr( $hex, 0, 2 ) ),
			'g' => hexdec( substr( $hex, 2, 2 ) ),
			'b' => hexdec( substr( $hex, 4, 2 ) ),
		);

		// Should we darken the color?
		if ( 'reverse' == $type && $shortcode_atts['r'] + $shortcode_atts['g'] + $shortcode_atts['b'] > 382 ) {
			$steps = -$steps;
		} elseif ( 'darken' == $type ) {
			$steps = -$steps;
		}

		// Build the new color.
		$steps = max( -255, min( 255, $steps ) );

		$shortcode_atts['r'] = max( 0, min( 255, $shortcode_atts['r'] + $steps ) );
		$shortcode_atts['g'] = max( 0, min( 255, $shortcode_atts['g'] + $steps ) );
		$shortcode_atts['b'] = max( 0, min( 255, $shortcode_atts['b'] + $steps ) );

		$r_hex = str_pad( dechex( $shortcode_atts['r'] ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $shortcode_atts['g'] ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $shortcode_atts['b'] ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}
} // End if.

/**
 * Convert colors from HEX to RGBA
 */
if ( ! function_exists( 'astra_hex_to_rgba' ) ) :

	/**
	 * Convert colors from HEX to RGBA
	 *
	 * @param  string  $color   Color code in HEX.
	 * @param  boolean $opacity Color code opacity.
	 * @return string           Color code in RGB or RGBA.
	 */
	function astra_hex_to_rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		// Return default if no color provided.
		if ( empty( $color ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided.
		if ( '#' == $color[0] ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values.
		if ( 6 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( 3 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		// Convert HEX to RGB.
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(RGBA or RGB).
		if ( $opacity ) {
			if ( 1 < abs( $opacity ) ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		// Return RGB(a) color string.
		return $output;
	}

endif;


if ( ! function_exists( 'astra_enable_page_builder_compatibility' ) ) :

	/**
	 * Allow filter to enable/disable page builder compatibility.
	 *
	 * @see  https://wpastra.com/docs/recommended-settings-beaver-builder-astra/
	 * @see  https://wpastra.com/docs/recommended-settings-for-elementor/
	 *
	 * @since  1.2.2
	 * @return  bool True - If the page builder compatibility is enabled. False - IF the page builder compatibility is disabled.
	 */
	function astra_enable_page_builder_compatibility() {
		return apply_filters( 'astra_enable_page_builder_compatibility', true );
	}

endif;


if ( ! function_exists( 'astra_get_pro_url' ) ) :
	/**
	 * Returns an URL with utm tags
	 * the admin settings page.
	 *
	 * @param string $url    URL fo the site.
	 * @param string $source utm source.
	 * @param string $medium utm medium.
	 * @param string $campaign utm campaign.
	 * @return mixed
	 */
	function astra_get_pro_url( $url, $source = '', $medium = '', $campaign = '' ) {

		$astra_pro_url = trailingslashit( $url );

		// Set up our URL if we have a source.
		if ( isset( $source ) ) {
			$astra_pro_url = add_query_arg( 'utm_source', sanitize_text_field( $source ), $url );
		}
		// Set up our URL if we have a medium.
		if ( isset( $medium ) ) {
			$astra_pro_url = add_query_arg( 'utm_medium', sanitize_text_field( $medium ), $url );
		}
		// Set up our URL if we have a campaign.
		if ( isset( $campaign ) ) {
			$astra_pro_url = add_query_arg( 'utm_campaign', sanitize_text_field( $campaign ), $url );
		}

		return esc_url( apply_filters( 'astra_get_pro_url', $astra_pro_url, $url ) );
	}

endif;


/**
 * Search Form
 */
if ( ! function_exists( 'astra_get_search_form' ) ) :
	/**
	 * Display search form.
	 *
	 * @param bool $echo Default to echo and not return the form.
	 * @return string|void String when $echo is false.
	 */
	function astra_get_search_form( $echo = true ) {

		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
			<label>
				<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'astra' ) . '</span>
				<input type="search" class="search-field" ' . apply_filters( 'astra_search_field_toggle_data_attrs', '' ) . ' placeholder="' . apply_filters( 'astra_search_field_placeholder', esc_attr_x( 'Search &hellip;', 'placeholder', 'astra' ) ) . '" value="' . get_search_query() . '" name="s" role="search" tabindex="-1"/>
			</label>
			<button type="submit" class="search-submit" value="' . esc_attr__( 'Search', 'astra' ) . '"  aria-label="search submit"><i class="astra-search-icon"></i></button>
		</form>';

		/**
		 * Filters the HTML output of the search form.
		 *
		 * @param string $form The search form HTML output.
		 */
		$result = apply_filters( 'astra_get_search_form', $form );

		if ( null === $result ) {
			$result = $form;
		}

		if ( $echo ) {
			echo $result;
		} else {
			return $result;
		}
	}

endif;

/**
 * Check if we're being delivered AMP
 *
 * @return bool
 */
function astra_is_amp_endpoint() {
	return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
}

/*
 * Get Responsive Spacing
 */
if ( ! function_exists( 'astra_responsive_spacing' ) ) {

	/**
	 * Get Spacing value
	 *
	 * @param  array  $option    CSS value.
	 * @param  string $side  top | bottom | left | right.
	 * @param  string $device  CSS device.
	 * @param  string $default Default value.
	 * @param  string $prefix Prefix value.
	 * @return mixed
	 */
	function astra_responsive_spacing( $option, $side = '', $device = 'desktop', $default = '', $prefix = '' ) {

		if ( isset( $option[ $device ][ $side ] ) && isset( $option[ $device . '-unit' ] ) ) {
			$spacing = astra_get_css_value( $option[ $device ][ $side ], $option[ $device . '-unit' ], $default );
		} elseif ( is_numeric( $option ) ) {
			$spacing = astra_get_css_value( $option );
		} else {
			$spacing = ( ! is_array( $option ) ) ? $option : '';
		}

		if ( '' !== $prefix && '' !== $spacing ) {
			return $prefix . $spacing;
		}
		return $spacing;
	}
}

/*
 * Apply CSS for the element
 */
if ( ! function_exists( 'astra_color_responsive_css' ) ) {

	/**
	 * Astra Responsive Colors
	 *
	 * @param  array  $setting      Responsive colors.
	 * @param  string $css_property CSS property.
	 * @param  string $selector     CSS selector.
	 * @return string               Dynamic responsive CSS.
	 */
	function astra_color_responsive_css( $setting, $css_property, $selector ) {
		$css = '';
		if ( isset( $setting['desktop'] ) && ! empty( $setting['desktop'] ) ) {
			$css .= $selector . '{' . $css_property . ':' . esc_attr( $setting['desktop'] ) . ';}';
		}
		if ( isset( $setting['tablet'] ) && ! empty( $setting['tablet'] ) ) {
			$css .= '@media (max-width:768px) {' . $selector . '{' . $css_property . ':' . esc_attr( $setting['tablet'] ) . ';} }';
		}
		if ( isset( $setting['mobile'] ) && ! empty( $setting['mobile'] ) ) {
			$css .= '@media (max-width:544px) {' . $selector . '{' . $css_property . ':' . esc_attr( $setting['mobile'] ) . ';} }';
		}
		return $css;
	}
}

if ( ! function_exists( 'astra_check_is_bb_themer_layout' ) ) :

	/**
	 * Check if layout is bb themer's layout
	 */
	function astra_check_is_bb_themer_layout() {

		$is_layout = false;

		$post_type = get_post_type();
		$post_id   = get_the_ID();

		if ( 'fl-theme-layout' === $post_type && $post_id ) {

			$is_layout = true;
		}

		return $is_layout;
	}

endif;


if ( ! function_exists( 'astra_is_white_labelled' ) ) :

	/**
	 * Check if white label option is enabled in astra pro plugin
	 */
	function astra_is_white_labelled() {

		if ( is_callable( 'Astra_Ext_White_Label_Markup::show_branding' ) && ! Astra_Ext_White_Label_Markup::show_branding() ) {
			return apply_filters( 'astra_is_white_labelled', true );
		}

		return apply_filters( 'astra_is_white_labelled', false );
	}

endif;

/**
 * Get the value for font-display property.
 *
 * @since 1.8.6
 * @return string
 */
function astra_get_fonts_display_property() {
	return apply_filters( 'astra_fonts_display_property', 'fallback' );
}
