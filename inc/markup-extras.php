<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 * All the functions here generate some kind of Markup for the frontend.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'wp_head', 'astra_pingback_header' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function astra_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

/**
 * Schema for <body> tag.
 */
if ( ! function_exists( 'astra_schema_body' ) ) :

	/**
	 * Adds schema tags to the body classes.
	 *
	 * @since 1.0.0
	 */
	function astra_schema_body() {

		if ( true !== apply_filters( 'astra_schema_enabled', true ) ) {
			return;
		}

		// Check conditions.
		$is_blog = ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) ? true : false;

		// Set up default itemtype.
		$itemtype = 'WebPage';

		// Get itemtype for the blog.
		$itemtype = ( $is_blog ) ? 'Blog' : $itemtype;

		// Get itemtype for search results.
		$itemtype = ( is_search() ) ? 'SearchResultsPage' : $itemtype;
		// Get the result.
		$result = apply_filters( 'astra_schema_body_itemtype', $itemtype );

		// Return our HTML.
		echo apply_filters( 'astra_schema_body', "itemtype='https://schema.org/" . esc_attr( $result ) . "' itemscope='itemscope'" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

/**
 * Adds custom classes to the array of body classes.
 */
if ( ! function_exists( 'astra_body_classes' ) ) {

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function astra_body_classes( $classes ) {

		if ( wp_is_mobile() ) {
			$classes[] = 'ast-header-break-point';
		} else {
			$classes[] = 'ast-desktop';
		}

		if ( astra_is_amp_endpoint() ) {
			$classes[] = 'ast-amp';
		}

		// Apply separate container class to the body.
		$content_layout = astra_get_content_layout();
		if ( 'content-boxed-container' == $content_layout ) {
			$classes[] = 'ast-separate-container';
		} elseif ( 'boxed-container' == $content_layout ) {
			$classes[] = 'ast-separate-container ast-two-container';
		} elseif ( 'page-builder' == $content_layout ) {
			$classes[] = 'ast-page-builder-template';
		} elseif ( 'plain-container' == $content_layout ) {
			$classes[] = 'ast-plain-container';
		}
		// Sidebar location.
		$page_layout = 'ast-' . astra_page_layout();
		$classes[]   = esc_attr( $page_layout );

		// Current Astra verion.
		$classes[] = esc_attr( 'astra-' . ASTRA_THEME_VERSION );

		$menu_item    = astra_get_option( 'header-main-rt-section' );
		$outside_menu = astra_get_option( 'header-display-outside-menu' );

		if ( 'none' !== $menu_item && $outside_menu ) {
			$classes[] = 'ast-header-custom-item-outside';
		} else {
			$classes[] = 'ast-header-custom-item-inside';
		}

		/**
		 * Add class for header width
		 */
		$header_content_layout = astra_get_option( 'header-main-layout-width' );

		if ( 'full' == $header_content_layout ) {
			$classes[] = 'ast-full-width-primary-header';
		}

		return $classes;
	}
}

add_filter( 'body_class', 'astra_body_classes' );


/**
 * Astra Pagination
 */
if ( ! function_exists( 'astra_number_pagination' ) ) {

	/**
	 * Astra Pagination
	 *
	 * @since 1.0.0
	 * @return void            Generate & echo pagination markup.
	 */
	function astra_number_pagination() {
		global $wp_query;
		$enabled = apply_filters( 'astra_pagination_enabled', true );

		// Don't print empty markup if their is only one page.
		if ( $wp_query->max_num_pages < 2 || ! $enabled ) {
			return;
		}

			ob_start();
			echo "<div class='ast-pagination'>";
			the_posts_pagination(
				array(
					'prev_text'    => astra_default_strings( 'string-blog-navigation-previous', false ),
					'next_text'    => astra_default_strings( 'string-blog-navigation-next', false ),
					'taxonomy'     => 'category',
					'in_same_term' => true,
				)
			);
			echo '</div>';
			$output = ob_get_clean();
			echo apply_filters( 'astra_pagination_markup', $output ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'astra_pagination', 'astra_number_pagination' );

/**
 * Return or echo site logo markup.
 */
if ( ! function_exists( 'astra_logo' ) ) {

	/**
	 * Return or echo site logo markup.
	 *
	 * @since 1.0.0
	 * @param  boolean $echo Echo markup.
	 * @return mixed echo or return markup.
	 */
	function astra_logo( $echo = true ) {

		$display_site_tagline = astra_get_option( 'display-site-tagline' );
		$display_site_title   = astra_get_option( 'display-site-title' );

		$html = '';

		$has_custom_logo = apply_filters( 'astra_has_custom_logo', has_custom_logo() );

		// Site logo.
		if ( $has_custom_logo ) {

			if ( apply_filters( 'astra_replace_logo_width', true ) ) {
				add_filter( 'wp_get_attachment_image_src', 'astra_replace_header_logo', 10, 4 );
			}

			$html .= '<span class="site-logo-img">';
			$html .= get_custom_logo();
			$html .= '</span>';

			if ( apply_filters( 'astra_replace_logo_width', true ) ) {
				remove_filter( 'wp_get_attachment_image_src', 'astra_replace_header_logo', 10 );
			}
		}

		$html .= astra_get_site_title_tagline( $display_site_title, $display_site_tagline );

		$html = apply_filters( 'astra_logo', $html, $display_site_title, $display_site_tagline );

		/**
		 * Echo or Return the Logo Markup
		 */
		if ( $echo ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $html;
		}
	}
}

/**
 * Return or echo site logo markup.
 *
 * @since 2.2.0
 * @param boolean $display_site_title Site title enable or not.
 * @param boolean $display_site_tagline Site tagline enable or not.
 *
 * @return string return markup.
 */
function astra_get_site_title_tagline( $display_site_title, $display_site_tagline ) {
	$html = '';

	if ( ! apply_filters( 'astra_disable_site_identity', false ) ) {

		// Site Title.
		$tag = 'span';
		if ( is_home() || is_front_page() ) {
			$tag = 'h1';
		}

		/**
		 * Filters the site title output.
		 *
		 * @since 1.4.9
		 *
		 * @param string the HTML output for Site Title.
		 */
		// Site Title.
		$site_title_markup = apply_filters(
			'astra_site_title_output',
			sprintf(
				'<%1$s %4$s>
				<a href="%2$s" rel="home" %5$s >
					%3$s
				</a>
			</%1$s>',
				/**
				* Filters the tags for site title.
				*
				* @since 1.3.1
				*
				* @param string $tags string containing the HTML tags for Site Title.
				*/
				apply_filters( 'astra_site_title_tag', $tag ),
				/**
				* Filters the href for the site title.
				*
				* @since 1.4.9
				*
				* @param string site title home url
				*/
				esc_url( apply_filters( 'astra_site_title_href', home_url( '/' ) ) ),
				/**
				* Filters the site title.
				*
				* @since 1.4.9
				*
				* @param string site title
				*/
				apply_filters( 'astra_site_title', get_bloginfo( 'name' ) ),
				astra_attr(
					'site-title',
					array(
						'class' => 'site-title',
					)
				),
				astra_attr(
					'site-title-link',
					array()
				)
			)
		);

		// Site Description.
		/**
		 * Filters the site description markup.
		 *
		 * @since 1.4.9
		 *
		 * @param string the HTML output for Site Title.
		 */
		$site_tagline_markup = apply_filters(
			'astra_site_description_markup',
			sprintf(
				'<%1$s class="site-description" itemprop="description">
				%2$s
			</%1$s>',
				/**
				* Filters the tags for site tagline.
				*
				* @since 1.8.5
				*/
				apply_filters( 'astra_site_tagline_tag', 'p' ),
				/**
				* Filters the site description.
				*
				* @since 1.4.9
				*
				* @param string site description
				*/
				apply_filters( 'astra_site_description', get_bloginfo( 'description' ) )
			)
		);

		if ( $display_site_title || $display_site_tagline ) {
			/* translators: 1: Site Title Markup, 2: Site Tagline Markup */
			$html .= sprintf(
				'<div class="ast-site-title-wrap">
						%1$s
						%2$s
				</div>',
				( $display_site_title ) ? $site_title_markup : '',
				( $display_site_tagline ) ? $site_tagline_markup : ''
			);
		}
	}
	return $html;
}

/**
 * Return the selected sections
 */
if ( ! function_exists( 'astra_get_dynamic_header_content' ) ) {

	/**
	 * Return the selected sections
	 *
	 * @since 1.0.0
	 * @param  string $option Custom content type. E.g. search, text-html etc.
	 * @return array         Array of Custom contents.
	 */
	function astra_get_dynamic_header_content( $option ) {

		$output  = array();
		$section = astra_get_option( $option );

		switch ( $section ) {

			case 'search':
					$output[] = astra_get_search( $option );
				break;

			case 'text-html':
					$output[] = astra_get_custom_html( $option . '-html' );
				break;

			case 'widget':
					$output[] = astra_get_custom_widget( $option );
				break;

			case 'button':
					$output[] = astra_get_custom_button( $option . '-button-text', $option . '-button-link-option', $option . '-button-style' );
				break;

			default:
					$output[] = apply_filters( 'astra_get_dynamic_header_content', '', $option, $section );
				break;
		}

		return apply_filters( 'astra_get_dynamic_header_content_final', $output );
	}
}


/**
 * Adding Wrapper for Search Form.
 */
if ( ! function_exists( 'astra_get_search' ) ) {

	/**
	 * Adding Wrapper for Search Form.
	 *
	 * @since 1.0.0
	 * @param  string $option   Search Option name.
	 * @param  string $device   Device name.
	 * @return mixed Search HTML structure created.
	 */
	function astra_get_search( $option = '', $device = '' ) {
		ob_start();
		?>
		<div class="ast-search-menu-icon slide-search" <?php echo apply_filters( 'astra_search_slide_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>id="ast-search-form" role="search" tabindex="-1">
			<div class="ast-search-icon">
				<a class="slide-search astra-search-icon" aria-label="<?php esc_attr_e( 'Search icon link', 'astra' ); ?>" href="#">
					<span class="screen-reader-text"><?php esc_html_e( 'Search', 'astra' ); ?></span>
				</a>
			</div>
			<?php astra_get_search_form(); ?>
		</div>
		<?php
		$search_html = ob_get_clean();

		return apply_filters( 'astra_get_search', $search_html, $option, $device );
	}
}

/**
 * Get custom HTML added by user.
 */
if ( ! function_exists( 'astra_get_custom_html' ) ) {

	/**
	 * Get custom HTML added by user.
	 *
	 * @since 1.0.0
	 * @param  string $option_name Option name.
	 * @return String TEXT/HTML added by user in options panel.
	 */
	function astra_get_custom_html( $option_name = '' ) {

		$custom_html         = '';
		$custom_html_content = astra_get_option( $option_name );

		if ( ! empty( $custom_html_content ) ) {
			$custom_html = '<div class="ast-custom-html">' . do_shortcode( $custom_html_content ) . '</div>';
		} elseif ( current_user_can( 'edit_theme_options' ) ) {
			$custom_html = '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=' . ASTRA_THEME_SETTINGS . '[' . $option_name . ']' ) ) . '">' . __( 'Add Custom HTML', 'astra' ) . '</a>';
		}

		return $custom_html;
	}
}

/**
 * Get custom Button.
 */
if ( ! function_exists( 'astra_get_custom_button' ) ) {

	/**
	 * Get custom HTML added by user.
	 *
	 * @since 1.0.0
	 * @param string $button_text Button Text.
	 * @param string $button_options Button Link.
	 * @param string $button_style Button Style.
	 * @return String Button added by user in options panel.
	 */
	function astra_get_custom_button( $button_text = '', $button_options = '', $button_style = '' ) {

		$custom_html    = '';
		$button_classes = '';
		$button_text    = astra_get_option( $button_text );
		$button_style   = astra_get_option( $button_style );
		$outside_menu   = astra_get_option( 'header-display-outside-menu' );

		$header_button = astra_get_option( $button_options );
		$new_tab       = ( $header_button['new_tab'] ? 'target="_blank"' : 'target="_self"' );
		$link_rel      = ( ! empty( $header_button['link_rel'] ) ? 'rel="' . esc_attr( $header_button['link_rel'] ) . '"' : '' );

		$button_classes    = ( 'theme-button' === $button_style ? 'ast-button' : 'ast-custom-button' );
		$outside_menu_item = apply_filters( 'astra_convert_link_to_button', $outside_menu );

		if ( '1' == $outside_menu_item ) {
			$custom_html = '<a class="ast-custom-button-link" href="' . esc_url( do_shortcode( $header_button['url'] ) ) . '" ' . $new_tab . ' ' . $link_rel . '><div class=' . esc_attr( $button_classes ) . '>' . esc_attr( do_shortcode( $button_text ) ) . '</div></a>';
		} else {
			$custom_html  = '<a class="ast-custom-button-link" href="' . esc_url( do_shortcode( $header_button['url'] ) ) . '" ' . $new_tab . ' ' . $link_rel . '><div class=' . esc_attr( $button_classes ) . '>' . esc_attr( do_shortcode( $button_text ) ) . '</div></a>';
			$custom_html .= '<a class="menu-link" href="' . esc_url( do_shortcode( $header_button['url'] ) ) . '" ' . $new_tab . ' ' . $link_rel . '>' . esc_attr( do_shortcode( $button_text ) ) . '</a>';
		}

		return $custom_html;
	}
}

/**
 * Get Widget added by user.
 */
if ( ! function_exists( 'astra_get_custom_widget' ) ) {

	/**
	 * Get custom widget added by user.
	 *
	 * @since  1.0.1.1
	 * @param  string $option_name Option name.
	 * @return Widget added by user in options panel.
	 */
	function astra_get_custom_widget( $option_name = '' ) {

		ob_start();

		if ( 'header-main-rt-section' == $option_name ) {
			$widget_id = 'header-widget';
		}
		if ( 'footer-sml-section-1' == $option_name ) {
			$widget_id = 'footer-widget-1';
		} elseif ( 'footer-sml-section-2' == $option_name ) {
			$widget_id = 'footer-widget-2';
		}

		echo '<div class="ast-' . esc_attr( $widget_id ) . '-area"' . apply_filters( 'astra_sidebar_data_attrs', '', $widget_id ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				astra_get_sidebar( $widget_id );
		echo '</div>';

		return ob_get_clean();
	}
}

/**
 * Function to get Small Left/Right Footer
 */
if ( ! function_exists( 'astra_get_small_footer' ) ) {

	/**
	 * Function to get Small Left/Right Footer
	 *
	 * @since 1.0.0
	 * @param string $section   Sections of Small Footer.
	 * @return mixed            Markup of sections.
	 */
	function astra_get_small_footer( $section = '' ) {

		$small_footer_type = astra_get_option( $section );
		$output            = null;

		switch ( $small_footer_type ) {
			case 'menu':
					$output = astra_get_small_footer_menu();
				break;

			case 'custom':
					$output = astra_get_small_footer_custom_text( $section . '-credit' );
				break;

			case 'widget':
					$output = astra_get_custom_widget( $section );
				break;
		}

		return $output;
	}
}

/**
 * Function to get Small Footer Custom Text
 */
if ( ! function_exists( 'astra_get_small_footer_custom_text' ) ) {

	/**
	 * Function to get Small Footer Custom Text
	 *
	 * @since 1.0.14
	 * @param string $option Custom text option name.
	 * @return mixed         Markup of custom text option.
	 */
	function astra_get_small_footer_custom_text( $option = '' ) {

		$output = $option;

		if ( '' != $option ) {
			$output = astra_get_option( $option );
			$output = str_replace( '[current_year]', date_i18n( 'Y' ), $output );
			$output = str_replace( '[site_title]', '<span class="ast-footer-site-title">' . get_bloginfo( 'name' ) . '</span>', $output );

			$theme_author = apply_filters(
				'astra_theme_author',
				array(
					'theme_name'       => __( 'Astra WordPress Theme', 'astra' ),
					'theme_author_url' => 'https://wpastra.com/',
				)
			);

			$output = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . $theme_author['theme_name'] . '</a>', $output );
		}

		return do_shortcode( $output );
	}
}

/**
 * Function to get Footer Menu
 */
if ( ! function_exists( 'astra_get_small_footer_menu' ) ) {

	/**
	 * Function to get Footer Menu
	 *
	 * @since 1.0.0
	 * @return html
	 */
	function astra_get_small_footer_menu() {

		ob_start();

		if ( has_nav_menu( 'footer_menu' ) ) {
			wp_nav_menu(
				array(
					'container'       => 'div',
					'container_class' => 'footer-primary-navigation',
					'theme_location'  => 'footer_menu',
					'menu_class'      => 'nav-menu',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'           => 1,
				)
			);
		} else {
			if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
				?>
					<a href="<?php echo esc_url( admin_url( '/nav-menus.php?action=locations' ) ); ?>"><?php esc_html_e( 'Assign Footer Menu', 'astra' ); ?></a>
				<?php
			}
		}

		return ob_get_clean();
	}
}

/**
 * Function to get site Header
 */
if ( ! function_exists( 'astra_header_markup' ) ) {

	/**
	 * Site Header - <header>
	 *
	 * @since 1.0.0
	 */
	function astra_header_markup() {

		do_action( 'astra_header_markup_before' );
		?>
		<header
		<?php
				echo astra_attr(
					'header',
					array(
						'id'    => 'masthead',
						'class' => join( ' ', astra_get_header_classes() ),
					)
				);
		?>
		>
			<?php
			astra_masthead_top();

			astra_masthead();

			astra_masthead_bottom();

			do_action( 'astra_sticky_header_markup' );
			do_action( 'astra_bottom_header_after_markup' );
			?>
		</header><!-- #masthead -->
		<?php

		do_action( 'astra_header_markup_after' );

	}
}

add_action( 'astra_header', 'astra_header_markup' );

/**
 * Function to get site title/logo
 */
if ( ! function_exists( 'astra_site_branding_markup' ) ) {

	/**
	 * Site Title / Logo
	 *
	 * @since 1.0.0
	 */
	function astra_site_branding_markup() {
		?>

		<div class="site-branding">
			<div
			<?php
				echo astra_attr(
					'site-identity',
					array(
						'class' => 'ast-site-identity',
					)
				);
			?>
			>
				<?php astra_logo(); ?>
			</div>
		</div>

		<!-- .site-branding -->
		<?php
	}
}

add_action( 'astra_masthead_content', 'astra_site_branding_markup', 8 );

/**
 * Function to get Toggle Button Markup
 */
if ( ! function_exists( 'astra_toggle_buttons_markup' ) ) {

	/**
	 * Toggle Button Markup
	 *
	 * @since 1.0.0
	 */
	function astra_toggle_buttons_markup() {
		$disable_primary_navigation = astra_get_option( 'disable-primary-nav' );
		$custom_header_section      = astra_get_option( 'header-main-rt-section' );
		$hide_custom_menu_mobile    = astra_get_option( 'hide-custom-menu-mobile', false );
		$above_header_merge         = astra_get_option( 'above-header-merge-menu' );
		$above_header_on_mobile     = astra_get_option( 'above-header-on-mobile' );
		$below_header_merge         = astra_get_option( 'below-header-merge-menu' );
		$below_header_on_mobile     = astra_get_option( 'below-header-on-mobile' );
		$menu_bottons               = true;

		if ( ( $disable_primary_navigation && 'none' == $custom_header_section ) || ( $disable_primary_navigation && true == $hide_custom_menu_mobile ) ) {
			$menu_bottons = false;
			if ( ( true == $above_header_on_mobile && true == $above_header_merge ) || ( true == $below_header_on_mobile && true == $below_header_merge ) ) {
				$menu_bottons = true;
			}
		}

		if ( apply_filters( 'astra_enable_mobile_menu_buttons', $menu_bottons ) ) {
			?>
		<div class="ast-mobile-menu-buttons">

			<?php astra_masthead_toggle_buttons_before(); ?>

			<?php astra_masthead_toggle_buttons(); ?>

			<?php astra_masthead_toggle_buttons_after(); ?>

		</div>
			<?php
		}
	}
}

add_action( 'astra_masthead_content', 'astra_toggle_buttons_markup', 9 );

/**
 * Function to get Primary navigation menu
 */
if ( ! function_exists( 'astra_primary_navigation_markup' ) ) {

	/**
	 * Site Title / Logo
	 *
	 * @since 1.0.0
	 */
	function astra_primary_navigation_markup() {

		$disable_primary_navigation = astra_get_option( 'disable-primary-nav' );
		$custom_header_section      = astra_get_option( 'header-main-rt-section' );

		if ( $disable_primary_navigation ) {

			$display_outside = astra_get_option( 'header-display-outside-menu' );

			if ( 'none' != $custom_header_section && ! $display_outside ) {

				echo '<div class="main-header-bar-navigation ast-flex-1 ast-header-custom-item ast-flex ast-justify-content-flex-end">';
				/**
				 * Fires before the Primary Header Menu navigation.
				 * Disable Primary Menu is checked
				 * Last Item in Menu is not 'none'.
				 * Take Last Item in Menu outside is unchecked.
				 *
				 * @since 1.4.0
				 */
				do_action( 'astra_main_header_custom_menu_item_before' );

				echo astra_masthead_get_menu_items(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				/**
				 * Fires after the Primary Header Menu navigation.
				 * Disable Primary Menu is checked
				 * Last Item in Menu is not 'none'.
				 * Take Last Item in Menu outside is unchecked.
				 *
				 * @since 1.4.0
				 */
				do_action( 'astra_main_header_custom_menu_item_after' );

				echo '</div>';

			}
		} else {

			$submenu_class = apply_filters( 'primary_submenu_border_class', ' submenu-with-border' );

			// Menu Animation.
			$menu_animation = astra_get_option( 'header-main-submenu-container-animation' );
			if ( ! empty( $menu_animation ) ) {
				$submenu_class .= ' astra-menu-animation-' . esc_attr( $menu_animation ) . ' ';
			}

			/**
			 * Filter the classes(array) for Primary Menu (<ul>).
			 *
			 * @since  1.5.0
			 * @var Array
			 */
			$primary_menu_classes = apply_filters( 'astra_primary_menu_classes', array( 'main-header-menu', 'ast-nav-menu', 'ast-flex', 'ast-justify-content-flex-end', $submenu_class ) );

			// Fallback Menu if primary menu not set.
			$fallback_menu_args = array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'menu_class'     => 'main-navigation',
				'container'      => 'div',
				'before'         => '<ul class="' . esc_attr( implode( ' ', $primary_menu_classes ) ) . '">',
				'after'          => '</ul>',
				'walker'         => new Astra_Walker_Page(),
			);

			$items_wrap  = '<nav ';
			$items_wrap .= astra_attr(
				'site-navigation',
				array(
					'id'         => 'site-navigation',
					'class'      => 'ast-flex-grow-1 navigation-accessibility',
					'aria-label' => esc_attr__( 'Site Navigation', 'astra' ),
				)
			);
			$items_wrap .= '>';
			$items_wrap .= '<div class="main-navigation">';
			$items_wrap .= '<ul id="%1$s" class="%2$s">%3$s</ul>';
			$items_wrap .= '</div>';
			$items_wrap .= '</nav>';

			// Primary Menu.
			$primary_menu_args = array(
				'theme_location'  => 'primary',
				'menu_id'         => 'primary-menu',
				'menu_class'      => esc_attr( implode( ' ', $primary_menu_classes ) ),
				'container'       => 'div',
				'container_class' => 'main-header-bar-navigation',
				'items_wrap'      => $items_wrap,
			);

			if ( has_nav_menu( 'primary' ) ) {
				// To add default alignment for navigation which can be added through any third party plugin.
				// Do not add any CSS from theme except header alignment.
				echo '<div ' . astra_attr( 'ast-main-header-bar-alignment' ) . '>';
					wp_nav_menu( $primary_menu_args );
				echo '</div>';
			} else {

				echo '<div ' . astra_attr( 'ast-main-header-bar-alignment' ) . '>';
					echo '<div class="main-header-bar-navigation ast-flex-1">';
						echo '<nav ';
						echo astra_attr(
							'site-navigation',
							array(
								'id' => 'site-navigation',
							)
						);
						echo ' class="ast-flex-grow-1 navigation-accessibility" aria-label="' . esc_attr__( 'Site Navigation', 'astra' ) . '">';
							wp_page_menu( $fallback_menu_args );
						echo '</nav>';
					echo '</div>';
				echo '</div>';
			}
		}

	}
}

add_action( 'astra_masthead_content', 'astra_primary_navigation_markup', 10 );

/**
 * Add CSS classes for all menu links inside WP Nav menu items.
 *
 * Right now, if Addon is active we add 'menu-link' class through walker_nav_menu_start_el, but if only theme is being used no clas is assigned to anchors.
 *
 * As we are replacing tag based selector assets to class selector, adding 'menu-link' selector to all anchors inside menu items.
 * Ex. .main-header-menu a => .main-header-menu .menu-link
 *
 * @since 2.5.0
 * @param array $atts   An array of all parameters assigned to menu anchors.
 */
function astra_menu_anchor_class_for_nav_menus( $atts ) {

	if ( ! empty( $atts['class'] ) ) {
		$atts['class'] = $atts['class'] . ' menu-link';
	} else {
		$atts['class'] = 'menu-link';
	}

	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'astra_menu_anchor_class_for_nav_menus', 11 );

/**
 * Add CSS classes for all menu links inside WP Page Menu items.
 *
 * As we are replacing tag based selector to class selector, adding 'menu-link' selector to all anchors inside menu items.
 *
 * @since 2.5.0
 * @param array $atts   An array of all parameters assigned to menu anchors.
 */
function astra_menu_anchor_class_for_page_menus( $atts ) {

	if ( ! empty( $atts['class'] ) ) {
		$atts['class'] = $atts['class'] . ' menu-link';
	} else {
		$atts['class'] = 'menu-link';
	}

	return $atts;
}

add_filter( 'page_menu_link_attributes', 'astra_menu_anchor_class_for_page_menus' );

/**
 * Add CSS classes from wp_nav_menu the wp_page_menu()'s menu items.
 * This will help avoid targeting wp_page_menu and wp_nav_manu separately in CSS/JS.
 *
 * @since 1.6.9
 * @param array   $css_class    An array of CSS classes to be applied
 *                              to each list item.
 * @param WP_Post $page         Page data object.
 * @param int     $depth        Depth of page, used for padding.
 * @param array   $args         An array of arguments.
 * @param int     $current_page ID of the current page.
 * @return Array CSS classes with added menu class `menu-item`
 */
function astra_page_css_class( $css_class, $page, $depth, $args, $current_page ) {
	$css_class[] = 'menu-item';

	if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
		$css_class[] = 'menu-item-has-children';
	}

	if ( ! empty( $current_page ) ) {
		$_current_page = get_post( $current_page );

		if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
			$css_class[] = 'current-menu-ancestor';
		}

		if ( $page->ID == $current_page ) {
			$css_class[] = 'current-menu-item';
		} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
			$css_class[] = 'current-menu-parent';
		}
	} elseif ( get_option( 'page_for_posts' ) == $page->ID ) {
		$css_class[] = 'current-menu-parent';
	}

	return $css_class;
}

add_filter( 'page_css_class', 'astra_page_css_class', 20, 5 );

/**
 * Function to get site Footer
 */
if ( ! function_exists( 'astra_footer_markup' ) ) {

	/**
	 * Site Footer - <footer>
	 *
	 * @since 1.0.0
	 */
	function astra_footer_markup() {
		?>

		<footer
		<?php
				echo astra_attr(
					'footer',
					array(
						'id'    => 'colophon',
						'class' => join( ' ', astra_get_footer_classes() ),
					)
				);
		?>
		>

			<?php astra_footer_content_top(); ?>

			<?php astra_footer_content(); ?>

			<?php astra_footer_content_bottom(); ?>

		</footer><!-- #colophon -->
		<?php
	}
}

add_action( 'astra_footer', 'astra_footer_markup' );

/**
 * Function to get Header Breakpoint
 */
if ( ! function_exists( 'astra_header_break_point' ) ) {

	/**
	 * Function to get Header Breakpoint
	 *
	 * @since 1.4.0 Added Mobile Header Breakpoint option from customizer.
	 * @since 1.0.0
	 * @return number
	 */
	function astra_header_break_point() {
		$mobile_header_brakpoint = ( Astra_Builder_Helper::$is_header_footer_builder_active ) ? astra_get_tablet_breakpoint() : astra_get_option( 'mobile-header-breakpoint', 921 );
		return absint( apply_filters( 'astra_header_break_point', $mobile_header_brakpoint ) );
	}
}

/**
 * Function to get Edit Post Link
 */
if ( ! function_exists( 'astra_edit_post_link' ) ) {

	/**
	 * Function to get Edit Post Link
	 *
	 * @since 1.0.0
	 * @param string $text      Anchor Text.
	 * @param string $before    Anchor Text.
	 * @param string $after     Anchor Text.
	 * @param int    $id           Anchor Text.
	 * @param string $class     Anchor Text.
	 * @return void
	 */
	function astra_edit_post_link( $text, $before = '', $after = '', $id = 0, $class = 'post-edit-link' ) {

		if ( apply_filters( 'astra_edit_post_link', false ) ) {
			edit_post_link( $text, $before, $after, $id, $class );
		}
	}
}

/**
 * Function to get Header Classes
 */
if ( ! function_exists( 'astra_header_classes' ) ) {

	/**
	 * Function to get Header Classes
	 *
	 * @since 1.0.0
	 */
	function astra_header_classes() {
		echo 'class="' . esc_attr( join( ' ', astra_get_header_classes() ) ) . '"';
	}
}

/**
 * Return classnames for <header> element.
 *
 * @since 2.1.0
 * @return Array classnames for the <header>
 */
function astra_get_header_classes() {
		$classes                       = array( 'site-header' );
		$menu_logo_location            = astra_get_option( 'header-layouts' );
		$mobile_header_alignment       = astra_get_option( 'header-main-menu-align' );
		$primary_menu_disable          = astra_get_option( 'disable-primary-nav' );
		$primary_menu_custom_item      = astra_get_option( 'header-main-rt-section' );
		$logo_title_inline             = astra_get_option( 'logo-title-inline' );
		$mobile_header_logo            = astra_get_option( 'mobile-header-logo' );
		$mobile_header_order           = astra_get_option( 'mobile-header-order' );
		$different_mobile_header_order = astra_get_option( 'different-mobile-logo' );
		$hide_custom_menu_mobile       = astra_get_option( 'hide-custom-menu-mobile', false );
		$menu_mobile_target            = astra_get_option( 'mobile-header-toggle-target', 'icon' );
		$submenu_container_animation   = astra_get_option( 'header-main-submenu-container-animation' );
		$builder_menu_mobile_target    = astra_get_option( 'header-builder-menu-toggle-target', 'icon' );

	if ( '' !== $submenu_container_animation ) {
		$classes[] = 'ast-primary-submenu-animation-' . $submenu_container_animation;
	}

	if ( $menu_logo_location ) {
		$classes[] = $menu_logo_location;
	}

	if ( $primary_menu_disable ) {

		$classes[] = 'ast-primary-menu-disabled';

		if ( 'none' == $primary_menu_custom_item ) {
			$classes[] = 'ast-no-menu-items';
		}
	} else {
		$classes[] = 'ast-primary-menu-enabled';
	}

		// Add class if Mobile Header Logo is set.
	if ( '' !== $mobile_header_logo && '1' == $different_mobile_header_order ) {
		$classes[] = 'ast-has-mobile-header-logo';
	}

		// Add class if Inline Logo & Site Title.
	if ( $logo_title_inline ) {
		$classes[] = 'ast-logo-title-inline';
	}

	if ( '1' == $hide_custom_menu_mobile ) {
		$classes[] = 'ast-hide-custom-menu-mobile';
	}

	if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {
		$classes[] = 'ast-builder-menu-toggle-' . $builder_menu_mobile_target;
	} else {
		$classes[] = 'ast-menu-toggle-' . $menu_mobile_target;
	}

	$classes[] = 'ast-mobile-header-' . $mobile_header_alignment;

	$classes = array_unique( apply_filters( 'astra_header_class', $classes ) );

	$classes = array_map( 'sanitize_html_class', $classes );

	return apply_filters( 'astra_get_header_classes', $classes );
}

/**
 * Function to get Footer Classes
 */
if ( ! function_exists( 'astra_footer_classes' ) ) {

	/**
	 * Function to get Footer Classes
	 *
	 * @since 1.0.0
	 */
	function astra_footer_classes() {
		echo 'class="' . esc_attr( join( ' ', astra_get_footer_classes() ) ) . '"';
	}
}

/**
 * Return classnames for <footer> element.
 *
 * @since 2.1.0
 * @return Array classnames for the <footer>
 */
function astra_get_footer_classes() {
	$classes = array_unique( apply_filters( 'astra_footer_class', array( 'site-footer' ) ) );
	$classes = array_map( 'sanitize_html_class', $classes );

	return apply_filters( 'astra_get_footer_classes', $classes );
}

/**
 * Function to filter comment form's default fields
 */
if ( ! function_exists( 'astra_comment_form_default_fields_markup' ) ) {

	/**
	 * Function filter comment form's default fields
	 *
	 * @since 1.0.0
	 * @param array $fields Array of comment form's default fields.
	 * @return array        Comment form fields.
	 */
	function astra_comment_form_default_fields_markup( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );

		$fields['author'] = '<div class="ast-comment-formwrap ast-row"><p class="comment-form-author ast-col-xs-12 ast-col-sm-12 ast-col-md-4 ast-col-lg-4">' .
					'<label for="author" class="screen-reader-text">' . esc_html( astra_default_strings( 'string-comment-label-name', false ) ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
					'" placeholder="' . esc_attr( astra_default_strings( 'string-comment-label-name', false ) ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['email']  = '<p class="comment-form-email ast-col-xs-12 ast-col-sm-12 ast-col-md-4 ast-col-lg-4">' .
					'<label for="email" class="screen-reader-text">' . esc_html( astra_default_strings( 'string-comment-label-email', false ) ) . '</label><input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) .
					'" placeholder="' . esc_attr( astra_default_strings( 'string-comment-label-email', false ) ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['url']    = '<p class="comment-form-url ast-col-xs-12 ast-col-sm-12 ast-col-md-4 ast-col-lg-4"><label for="url">' .
					'<label for="url" class="screen-reader-text">' . esc_html( astra_default_strings( 'string-comment-label-website', false ) ) . '</label><input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) .
					'" placeholder="' . esc_attr( astra_default_strings( 'string-comment-label-website', false ) ) . '" size="30" /></label></p></div>';

		return apply_filters( 'astra_comment_form_default_fields_markup', $fields );
	}
}

add_filter( 'comment_form_default_fields', 'astra_comment_form_default_fields_markup' );

/**
 * Function to filter comment form arguments
 */
if ( ! function_exists( 'astra_comment_form_default_markup' ) ) {

	/**
	 * Function filter comment form arguments
	 *
	 * @since 1.0.0
	 * @param array $args   Comment form arguments.
	 * @return array
	 */
	function astra_comment_form_default_markup( $args ) {
		/**
		 * Filter to enabled Astra comment for all Post Types where the commnets are enabled.
		 *
		 * @since 1.5.0
		 *
		 * @return bool
		 */
		$all_post_type_support = apply_filters( 'astra_comment_form_all_post_type_support', false );
		if ( 'post' == get_post_type() || $all_post_type_support ) {
			$args['id_form']           = 'ast-commentform';
			$args['title_reply']       = astra_default_strings( 'string-comment-title-reply', false );
			$args['cancel_reply_link'] = astra_default_strings( 'string-comment-cancel-reply-link', false );
			$args['label_submit']      = astra_default_strings( 'string-comment-label-submit', false );
			$args['comment_field']     = '<div class="ast-row comment-textarea"><fieldset class="comment-form-comment"><div class="comment-form-textarea ast-col-lg-12"><label for="comment" class="screen-reader-text">' . esc_html( astra_default_strings( 'string-comment-label-message', false ) ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr( astra_default_strings( 'string-comment-label-message', false ) ) . '" cols="45" rows="8" aria-required="true"></textarea></div></fieldset></div>';
		}
		return apply_filters( 'astra_comment_form_default_markup', $args );

	}
}

add_filter( 'comment_form_defaults', 'astra_comment_form_default_markup' );

/**
 * Display Blog Post Excerpt
 */
if ( ! function_exists( 'astra_the_excerpt' ) ) {

	/**
	 * Display Blog Post Excerpt
	 *
	 * @since 1.0.0
	 */
	function astra_the_excerpt() {

		$excerpt_type = apply_filters( 'astra_excerpt_type', astra_get_option( 'blog-post-content' ) );

		do_action( 'astra_the_excerpt_before', $excerpt_type );

		if ( 'full-content' === $excerpt_type ) {
			the_content();
		} else {
			the_excerpt();
		}

		do_action( 'astra_the_excerpt_after', $excerpt_type );
	}
}

/**
 * Display Sidebars
 */
if ( ! function_exists( 'astra_get_sidebar' ) ) {
	/**
	 * Get Sidebar
	 *
	 * @since 1.0.1.1
	 * @param  string $sidebar_id   Sidebar Id.
	 * @return void
	 */
	function astra_get_sidebar( $sidebar_id ) {
		if ( is_active_sidebar( $sidebar_id ) ) {
			dynamic_sidebar( $sidebar_id );
		} elseif ( current_user_can( 'edit_theme_options' ) ) {
			?>
			<div class="widget ast-no-widget-row">
				<p class='no-widget-text'>
					<a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>'>
						<?php esc_html_e( 'Add Widget', 'astra' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}
}

/**
 * Get Footer widgets
 */
if ( ! function_exists( 'astra_get_footer_widget' ) ) {

	/**
	 * Get Footer Default Sidebar
	 *
	 * @param  string $sidebar_id   Sidebar Id..
	 * @return void
	 */
	function astra_get_footer_widget( $sidebar_id ) {

		if ( is_active_sidebar( $sidebar_id ) ) {
			dynamic_sidebar( $sidebar_id );
		} elseif ( current_user_can( 'edit_theme_options' ) ) {

			global $wp_registered_sidebars;
			$sidebar_name = '';
			if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$sidebar_name = $wp_registered_sidebars[ $sidebar_id ]['name'];
			}
			?>
			<div class="widget ast-no-widget-row">
				<h2 class='widget-title'><?php echo esc_html( $sidebar_name ); ?></h2>

				<p class='no-widget-text'>
					<a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>'>
						<?php esc_html_e( 'Click here to assign a widget for this area.', 'astra' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}
}

/**
 * Astra entry header class.
 */
if ( ! function_exists( 'astra_entry_header_class' ) ) {

	/**
	 * Astra entry header class
	 *
	 * @since 1.0.15
	 */
	function astra_entry_header_class() {

		$post_id                    = astra_get_post_id();
		$classes                    = array();
		$title_markup               = astra_the_title( '', '', $post_id, false );
		$thumb_markup               = astra_get_post_thumbnail( '', '', false );
		$post_meta_markup           = astra_single_get_post_meta( '', '', false );
		$blog_single_post_structure = astra_get_option( 'blog-single-post-structure' );

		if ( ! $blog_single_post_structure || ( 'single-image' === astra_get_prop( $blog_single_post_structure, 0 ) && empty( $thumb_markup ) && 'single-title-meta' !== astra_get_prop( $blog_single_post_structure, 1 ) ) ) {
			$classes[] = 'ast-header-without-markup';
		} elseif ( empty( $title_markup ) && empty( $thumb_markup ) && ( is_page() || empty( $post_meta_markup ) ) ) {
			$classes[] = 'ast-header-without-markup';
		} else {

			if ( empty( $title_markup ) ) {
				$classes[] = 'ast-no-title';
			}

			if ( empty( $thumb_markup ) ) {
				$classes[] = 'ast-no-thumbnail';
			}

			if ( is_page() || empty( $post_meta_markup ) ) {
				$classes[] = 'ast-no-meta';
			}
		}

		$classes = array_unique( apply_filters( 'astra_entry_header_class', $classes ) );
		$classes = array_map( 'sanitize_html_class', $classes );

		echo esc_attr( join( ' ', $classes ) );
	}
}

/**
 * Astra get post thumbnail image.
 */
if ( ! function_exists( 'astra_get_post_thumbnail' ) ) {

	/**
	 * Astra get post thumbnail image
	 *
	 * @since 1.0.15
	 * @param string  $before Markup before thumbnail image.
	 * @param string  $after  Markup after thumbnail image.
	 * @param boolean $echo   Output print or return.
	 * @return string|void
	 */
	function astra_get_post_thumbnail( $before = '', $after = '', $echo = true ) {

		$output = '';

		$check_is_singular = is_singular();

		$featured_image = true;

		if ( $check_is_singular ) {
			$is_featured_image = astra_get_option_meta( 'ast-featured-img' );
		} else {
			$is_featured_image = astra_get_option( 'ast-featured-img' );
		}

		if ( 'disabled' === $is_featured_image ) {
			$featured_image = false;
		}

		$featured_image = apply_filters( 'astra_featured_image_enabled', $featured_image );

		$blog_post_thumb   = astra_get_option( 'blog-post-structure' );
		$single_post_thumb = astra_get_option( 'blog-single-post-structure' );

		if ( ( ( ! $check_is_singular && in_array( 'image', $blog_post_thumb ) ) || ( is_single() && in_array( 'single-image', $single_post_thumb ) ) || is_page() ) && has_post_thumbnail() ) {

			if ( $featured_image && ( ! ( $check_is_singular ) || ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) ) ) {

				$post_thumb = apply_filters(
					'astra_featured_image_markup',
					get_the_post_thumbnail(
						get_the_ID(),
						apply_filters( 'astra_post_thumbnail_default_size', 'large' ),
						apply_filters( 'astra_post_thumbnail_itemprop', '' )
					)
				);

				if ( '' != $post_thumb ) {
					$output .= '<div class="post-thumb-img-content post-thumb">';
					if ( ! $check_is_singular ) {
						$output .= apply_filters(
							'astra_blog_post_featured_image_link_before',
							'<a ' . astra_attr(
								'article-image-url',
								array(
									'class' => '',
									'href'  => esc_url( get_permalink() ),
								)
							) . ' >'
						);
					}
					$output .= $post_thumb;
					if ( ! $check_is_singular ) {
						$output .= apply_filters( 'astra_blog_post_featured_image_link_after', '</a>' );
					}
					$output .= '</div>';
				}
			}
		}

		if ( ! $check_is_singular ) {
			$output = apply_filters( 'astra_blog_post_featured_image_after', $output );
		}

		$output = apply_filters( 'astra_get_post_thumbnail', $output, $before, $after );

		if ( $echo ) {
			echo $before . $output . $after; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $before . $output . $after;
		}
	}
}

/**
 * Function to check if it is Internet Explorer
 */
if ( ! function_exists( 'astra_replace_header_attr' ) ) :

	/**
	 * Replace header logo.
	 *
	 * @param array  $attr Image.
	 * @param object $attachment Image obj.
	 * @param sting  $size Size name.
	 *
	 * @return array Image attr.
	 */
	function astra_replace_header_attr( $attr, $attachment, $size ) {

		if ( ! isset( $attachment ) ) {
			return $attr;
		}

		$custom_logo_id     = get_theme_mod( 'custom_logo' );
		$is_logo_attachment = ( $custom_logo_id == $attachment->ID ) ? true : false;

		if ( apply_filters( 'astra_is_logo_attachment', $is_logo_attachment, $attachment ) ) {

			if ( ! is_customize_preview() ) {
				$attach_data = wp_get_attachment_image_src( $attachment->ID, 'ast-logo-size' );

				if ( isset( $attach_data[0] ) ) {
					$attr['src'] = $attach_data[0];
				}
			}

			$file_type      = wp_check_filetype( $attr['src'] );
			$file_extension = $file_type['ext'];

			if ( 'svg' == $file_extension ) {
				$existing_classes = isset( $attr['class'] ) ? $attr['class'] : '';
				$attr['class']    = $existing_classes . ' astra-logo-svg';
			}
		}

		if ( apply_filters( 'astra_is_retina_logo_attachment', $is_logo_attachment, $attachment ) ) {

			$diff_retina_logo = astra_get_option( 'different-retina-logo' );

			if ( '1' == $diff_retina_logo ) {

				$retina_logo = astra_get_option( 'ast-header-retina-logo' );

				$attr['srcset'] = '';

				if ( apply_filters( 'astra_main_header_retina', true ) && '' !== $retina_logo ) {
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

		return apply_filters( 'astra_replace_header_attr', $attr );
	}

endif;

add_filter( 'wp_get_attachment_image_attributes', 'astra_replace_header_attr', 10, 3 );

/**
 * Astra Color Palletes.
 */
if ( ! function_exists( 'astra_color_palette' ) ) :

	/**
	 * Astra Color Palletes.
	 *
	 * @return array Color Palletes.
	 */
	function astra_color_palette() {

		$color_palette = array(
			'#000000',
			'#ffffff',
			'#dd3333',
			'#dd9933',
			'#eeee22',
			'#81d742',
			'#1e73be',
			'#8224e3',
		);

		return apply_filters( 'astra_color_palettes', $color_palette );
	}

endif;

if ( ! function_exists( 'astra_get_theme_name' ) ) :

	/**
	 * Get theme name.
	 *
	 * @return string Theme Name.
	 */
	function astra_get_theme_name() {

		$theme_name = __( 'Astra', 'astra' );

		return apply_filters( 'astra_theme_name', $theme_name );
	}

endif;

if ( ! function_exists( 'astra_get_addon_name' ) ) :

	/**
	 * Get Addon name.
	 *
	 * @return string Addon Name.
	 */
	function astra_get_addon_name() {

		$pro_name = __( 'Astra Pro', 'astra' );
		// If addon is not updated & White Label added for Addon then show the updated addon name.
		if ( class_exists( 'Astra_Ext_White_Label_Markup' ) ) {

			$plugin_data = Astra_Ext_White_Label_Markup::$branding;

			if ( '' != $plugin_data['astra-pro']['name'] ) {
				$pro_name = $plugin_data['astra-pro']['name'];
			}
		}

		return apply_filters( 'astra_addon_name', $pro_name );
	}
endif;
