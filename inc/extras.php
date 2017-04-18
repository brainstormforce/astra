<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

add_action( 'wp_head', 'ast_pingback_header' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function ast_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}

/**
 * Schema for <body> tag.
 */
if ( ! function_exists( 'ast_schema_body' ) ) :

	/**
	 * Adds schema tags to the body classes.
	 *
	 * @since 1.0.0
	 */
	function ast_schema_body() {

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
		echo apply_filters( 'astra_schema_body', "itemtype='http://schema.org/" . esc_html( $result ) . "' itemscope='itemscope'" );
	}
endif;

/**
 * Adds custom classes to the array of body classes.
 */
if ( ! function_exists( 'ast_body_classes' ) ) {

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function ast_body_classes( $classes ) {

		if ( wp_is_mobile() ) {
			$classes[] = 'ast-header-break-point';
		}

		// Apply header layout class to the body.
		$classes[] = 'header-main-layout-1';

		// Apply separate container class to the body.
		$content_layout = ast_get_content_layout();
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
		$page_layout = 'ast-' . ast_page_layout();
		$classes[]   = $page_layout;

		return $classes;
	}
}// End if().

add_filter( 'body_class', 'ast_body_classes' );


/**
 * Astra Pagination
 */
if ( ! function_exists( 'ast_number_pagination' ) ) {

	/**
	 * Astra Pagination
	 *
	 * @since 1.0.0
	 * @return void            Generate & echo pagination markup.
	 */
	function ast_number_pagination() {

		echo "<div class='ast-pagination'>";
		the_posts_pagination( array(
			'prev_text' => ast_default_strings( 'string-blog-navigation-previous', false ),
			'next_text' => ast_default_strings( 'string-blog-navigation-next', false ),
		) );
		echo '</div>';
	}
}// End if().

add_action( 'ast_pagination', 'ast_number_pagination' );

/**
 * Return or echo site logo markup.
 */
if ( ! function_exists( 'ast_logo' ) ) {

	/**
	 * Return or echo site logo markup.
	 *
	 * @since 1.0.0
	 * @param  boolean $echo Echo markup.
	 * @return mixed echo or return markup.
	 */
	function ast_logo( $echo = true ) {

		$site_tagline         = ast_get_option( 'display-site-tagline' );
		$display_site_tagline = ast_get_option( 'display-site-title' );
		$logo                 = apply_filters( 'ast_logo_url', ast_get_option( 'site-logo' ) );
		$html                 = '';

		// Site logo.
		$html .= '<span class="site-logo-img">';
		$html .= get_custom_logo();
		$html .= '</span>';

		// Site Title.
		if ( $display_site_tagline ) {

			$tag = 'span';
			if ( is_home() || is_front_page() ) {
				$tag = 'h1';
			}
			$html .= '<' . $tag . ' itemprop="name" class="site-title"> <a href="' . esc_url( home_url( '/' ) ) . '" itemprop="url" rel="home">' . get_bloginfo( 'name' ) . '</a> </' . $tag . '>';
		}

		// Site description.
		if ( $site_tagline ) {
			$html .= '<p class="site-description" itemprop="description">' . get_bloginfo( 'description' ) . '</p>';
		}
		/**
		 * Echo or Return the Logo Markup
		 */
		if ( $echo ) {
			echo apply_filters( 'ast_logo', $html );
		} else {
			return apply_filters( 'ast_logo', $html );
		}
	}
}// End if().

/**
 * Return the selected sections
 */
if ( ! function_exists( 'ast_get_dynamic_header_content' ) ) {

	/**
	 * Return the selected sections
	 *
	 * @since 1.0.0
	 * @param  string $option Custom content type. E.g. search, text-html etc.
	 * @return array         Array of Custom contents.
	 */
	function ast_get_dynamic_header_content( $option ) {

		$output  = array();
		$section = ast_get_option( $option );

		switch ( $section ) {

			case 'search':
					$output[] = ast_get_search( $option );
				break;

			case 'text-html':
					$output[] = ast_get_custom_html( $option . '-html' );
				break;
		}

		return $output;
	}
}

/**
 * Adding Wrapper for Search Form.
 */
if ( ! function_exists( 'ast_get_search' ) ) {

	/**
	 * Adding Wrapper for Search Form.
	 *
	 * @since 1.0.0
	 * @param  string $option 	Search Option name.
	 * @return mixed Search HTML structure created.
	 */
	function ast_get_search( $option = '' ) {

		$search_html = '<div class="ast-search-icon"><a class="slide-search astra-search-icon" href="#"></a></div>
						<div class="ast-search-menu-icon slide-search" id="ast-search-form" >';
		$search_html .= get_search_form( false );
		$search_html .= '</div>';

		return apply_filters( 'ast_get_search', $search_html, $option );
	}
}

/**
 * Get custom HTML added by user.
 */
if ( ! function_exists( 'ast_get_custom_html' ) ) {

	/**
	 * Get custom HTML added by user.
	 *
	 * @since 1.0.0
	 * @param  string $option_name Option name.
	 * @return String TEXT/HTML added by user in options panel.
	 */
	function ast_get_custom_html( $option_name = '' ) {

		$custom_html         = '';
		$custom_html_content = ast_get_option( $option_name );

		if ( ! empty( $custom_html_content ) ) {
			$custom_html = '<div class="ast-custom-html">' . do_shortcode( $custom_html_content ) . '</div>';
		} elseif ( current_user_can( 'edit_theme_options' ) ) {
			$custom_html = '<a href="' . admin_url( 'customize.php?autofocus[control]=ast-settings[' . $option_name . ']' ) . '">' . __( 'Add Custom HTML', 'astra' ) . '</a>';
		}

		return $custom_html;
	}
}

/**
 * Astra Theme Nav Menu
 */
if ( ! function_exists( 'ast_nav_menu' ) ) {

	/**
	 * Helper function for wp_nav_menu() checks if menu is set, if not set returns with message for capable users to set the menu.
	 *
	 * @since 1.0.0
	 * @param  array   $menu		  It will be either 'Menu location' (string) or Argument array of 'Menu'.
	 * @param  array   $fallback_menu Fallback menu if menu location is not set.
	 * @param  boolean $echo          Echo menu markup.
	 * @return mixed 				  Echo or Return Markup for menu or message to set the menu.
	 */
	function ast_nav_menu( $menu = array(), $fallback_menu = array(), $echo = true ) {

		/**
		 * Get menu / fallback menu markup
		 */
		if ( has_nav_menu( $menu['theme_location'] ) ) {

			// Initially set echo to false and get nav markup.
			$menu['echo'] = false;

			$nav = wp_nav_menu( $menu );

			/**
		 * Has fallback menu support?
		 */
		} elseif ( false == $fallback_menu ) {

			/* translators: 1: nav manu location 2: menu location name */
			$nav = printf( __( '<span class="nav-fallback-text"><a href="%1$s" style="padding: 0;">Assign a menu</a> to location %2$s </span>', 'astra' ),
				admin_url( 'nav-menus.php?action=locations' ),
				strtoupper( $menu['theme_location'] )
			);

			/**
		 * Set fallback menu support.
		 */
		} else {

			// Initially set echo to false and get nav markup.
			$fallback_menu['echo'] = false;
			$nav = wp_page_menu( $fallback_menu );
		}

		/**
		 * Echo / return markup
		 */
		if ( $echo ) {
			echo $nav;

		} else {
			return $nav;
		}

	}
}// End if().

/**
 * Function to get Small Left/Right Footer
 */
if ( ! function_exists( 'ast_get_small_footer' ) ) {

	/**
	 * Function to get Small Left/Right Footer
	 *
	 * @since 1.0.0
	 * @param string $section 	Sections of Small Footer.
	 * @return mixed 			Markup of sections.
	 */
	function ast_get_small_footer( $section = '' ) {

		$small_footer_type = ast_get_option( $section );
		$output = null;

		switch ( $small_footer_type ) {
			case 'menu':
					$output = ast_get_small_footer_menu();
				break;

			case 'custom':
					$output = ast_get_option( $section . '-credit' );
					$output = str_replace( '[current_year]', date( 'Y' ), $output );
					$output = str_replace( '[site_title]', '<span class="ast-footer-site-title">' . get_option( 'blogname' ) . '</span>', $output );

					$theme_author = apply_filters( 'ast_theme_author', array(
						'theme_name'       => __( 'Astra', 'astra' ),
						'theme_author_url' => 'https://www.brainstormforce.com/',
					) );

					$output = str_replace( '[theme_author]', '<a href="' . $theme_author['theme_author_url'] . '">' . $theme_author['theme_name'] . '</a>', $output );

				break;
		}

		return $output;
	}
}// End if().

/**
 * Function to get Footer Menu
 */
if ( ! function_exists( 'ast_get_small_footer_menu' ) ) {

	/**
	 * Function to get Footer Menu
	 *
	 * @since 1.0.0
	 * @return html
	 */
	function ast_get_small_footer_menu() {

		ob_start(); ?>

		<div class="footer-primary-navigation">
			<?php
			if ( has_nav_menu( 'footer_menu' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu',
						'menu_class'     => 'nav-menu',
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'			 => 1,
						)
				);
			} else {
				if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
					?>
						<a href="<?php echo esc_url( admin_url( '/nav-menus.php?action=locations' ) ); ?>"><?php _e( 'Assign Footer Menu', 'astra' ); ?></a>
					<?php
				}
			}
			?>
		</div> <!-- .footer-primary-navigation -->
		<?php

		return ob_get_clean();
	}
}// End if().

/**
 * Function to get site Header
 */
if ( ! function_exists( 'ast_header_markup' ) ) {

	/**
	 * Site Header - <header>
	 *
	 * @since 1.0.0
	 */
	function ast_header_markup() {
		?>

		<header itemtype="http://schema.org/WPHeader" itemscope="itemscope" id="masthead" class="<?php ast_header_classes(); ?>" role="banner">

			<?php ast_masthead_top(); ?>

			<?php ast_masthead(); ?>

			<?php ast_masthead_bottom(); ?>

		</header><!-- #masthead -->
		<?php
	}
}

add_action( 'ast_header', 'ast_header_markup' );

/**
 * Function to get site title/logo
 */
if ( ! function_exists( 'ast_site_branding_markup' ) ) {

	/**
	 * Site Title / Logo
	 *
	 * @since 1.0.0
	 */
	function ast_site_branding_markup() {
		?>

		<div class="site-branding">
			<div class="ast-site-identity" itemscope="itemscope" itemtype="http://schema.org/Organization">
				<?php ast_logo(); ?>
			</div>
		</div>
		<!-- .site-branding -->
		<?php
	}
}

add_action( 'ast_masthead_content', 'ast_site_branding_markup', 8 );

/**
 * Function to get Toggle Button Markup
 */
if ( ! function_exists( 'ast_toggle_buttons_markup' ) ) {

	/**
	 * Toggle Button Markup
	 *
	 * @since 1.0.0
	 */
	function ast_toggle_buttons_markup() {
		?>
		<div class="ast-mobile-menu-buttons">

			<?php ast_masthead_toggle_buttons_before(); ?>

			<?php ast_masthead_toggle_buttons(); ?>

			<?php ast_masthead_toggle_buttons_after(); ?>

		</div>
		<?php
	}
}// End if().

add_action( 'ast_masthead_content', 'ast_toggle_buttons_markup', 9 );

/**
 * Function to get Primary navigation menu
 */
if ( ! function_exists( 'ast_primary_navigation_markup' ) ) {

	/**
	 * Site Title / Logo
	 *
	 * @since 1.0.0
	 */
	function ast_primary_navigation_markup() {

		$submenu_class = apply_filters( 'primary_submenu_border_class', ' submenu-with-border' );

		// Primary Menu.
		$primary_menu_args = array(
			'theme_location'  => 'primary',
			'menu_id'         => 'primary-menu',
			'menu_class'      => 'main-header-menu ast-flex ast-justify-content-flex-end' . $submenu_class,
			'container_class' => 'main-navigation',
			'echo'            => false,
			'container'       => 'div',
			'walker'          => new Ast_Nav_Menu_Walker(),
		);

		// Fallback Menu if primary menu not set.
		$fallback_menu_args = array(
			'menu_class' => 'main-navigation',
			'menu_id'    => 'primary-menu',
			'container'  => 'div',
			'before'     => '<ul class="main-header-menu ast-flex ast-justify-content-flex-end' . $submenu_class . '">',
			'after'      => '</ul>',
			'echo'       => false,
			'walker'     => new Ast_Walker_Page(),

			// Below option is NOT a nav page menu option.
			// Just used to apply filter to add custom menu items though filter.
			'theme_location' => 'primary',
		); ?>

		<div class="main-header-bar-navigation" >

			<nav itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" id="site-navigation" class="ast-flex-grow-1" role="navigation" aria-label="<?php _e( 'Site Navigation', 'astra' ); ?>">
				<?php ast_nav_menu( $primary_menu_args, $fallback_menu_args ); ?>
			</nav><!-- #site-navigation -->

		</div>
		<?php
	}
}// End if().

add_action( 'ast_masthead_content', 'ast_primary_navigation_markup', 10 );

/**
 * Function to get site Footer
 */
if ( ! function_exists( 'ast_footer_markup' ) ) {

	/**
	 * Site Footer - <footer>
	 *
	 * @since 1.0.0
	 */
	function ast_footer_markup() {
		?>

		<footer itemtype="http://schema.org/WPFooter" itemscope="itemscope" id="colophon" class="<?php ast_footer_classes(); ?>" role="contentinfo">

			<?php ast_footer_content_top(); ?>

			<?php ast_footer_content(); ?>

			<?php ast_footer_content_bottom(); ?>

		</footer><!-- #colophon -->
		<?php
	}
}

add_action( 'ast_footer', 'ast_footer_markup' );

/**
 * Function to get Header Breakpoint
 */
if ( ! function_exists( 'ast_header_break_point' ) ) {

	/**
	 * Function to get Header Breakpoint
	 *
	 * @since 1.0.0
	 * @return number
	 */
	function ast_header_break_point() {
		$break_point = apply_filters( 'ast_header_break_point', 920 );
		return absint( $break_point );
	}
}

/**
 * Function to get Body Font Family
 */
if ( ! function_exists( 'ast_body_font_family' ) ) {

	/**
	 * Function to get Body Font Family
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function ast_body_font_family() {

		$font_family = ast_get_option( 'body-font-family' );

		// Body Font Family.
		if ( 'inherit' == $font_family ) {
			$font_family = '-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen-Sans, Ubuntu, Cantarell, Helvetica Neue, sans-serif';
		}

		return $font_family;
	}
}

/**
 * Function to get Header Classes
 */
if ( ! function_exists( 'ast_header_classes' ) ) {

	/**
	 * Function to get Header Classes
	 *
	 * @since 1.0.0
	 */
	function ast_header_classes() {

		$classes = apply_filters( 'ast_header_class', array( 'site-header' ) );

		echo join( ' ', array_unique( $classes ) );
	}
}

/**
 * Function to get Footer Classes
 */
if ( ! function_exists( 'ast_footer_classes' ) ) {

	/**
	 * Function to get Footer Classes
	 *
	 * @since 1.0.0
	 */
	function ast_footer_classes() {

		$classes = apply_filters( 'ast_footer_class', array( 'site-footer' ) );

		echo join( ' ', array_unique( $classes ) );
	}
}

/**
 * Function to Add Header Breakpoint Style
 */
if ( ! function_exists( 'ast_header_breakpoint_style' ) ) {

	/**
	 * Function to Add Header Breakpoint Style
	 *
	 * @since 1.0.0
	 */
	function ast_header_breakpoint_style() {

		// Header Break Point.
		$header_break_point = ast_header_break_point();

		ob_start();
		?>
		.main-header-bar-wrap {
			content: "<?php echo $header_break_point; ?>";
		}

		@media all and ( min-width: <?php echo $header_break_point; ?>px ) {
			.main-header-bar-wrap {
				content: "";
			}
		}
		<?php

		$custom_width = apply_filters( 'ast_single_post_content_width', false );
		if ( $custom_width && is_numeric( $custom_width ) ) { ?>
			@media ( min-width: 920px ) {
				.single .site-content > .ast-container {
					max-width : <?php echo $custom_width; ?>px;
				}
			}
		<?php }

		$dynamic_css = ob_get_clean();

		// trim white space for faster page loading.
		$dynamic_css = AST_Enqueue_Scripts::trim_css( $dynamic_css );

		wp_add_inline_style( 'ast-theme-css', $dynamic_css );
	}
}// End if().
add_action( 'wp_enqueue_scripts', 'ast_header_breakpoint_style' );

/**
 * Function to filter comment form's default fields
 */
if ( ! function_exists( 'ast_comment_form_default_fields_markup' ) ) {

	/**
	 * Function filter comment form's default fields
	 *
	 * @since 1.0.0
	 * @param array $fields Array of comment form's default fields.
	 * @return array 		Comment form fields.
	 */
	function ast_comment_form_default_fields_markup( $fields ) {

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields['author'] = '<div class="ast-comment-formwrap ast-row"><p class="comment-form-author ast-col-xs-12 ast-col-sm-12 ast-col-md-4 ast-col-lg-4">' .
					'<label for="author" class="screen-reader-text">' . ast_default_strings( 'string-comment-label-name', false ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
					'" placeholder="' . ast_default_strings( 'string-comment-label-name', false ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['email'] = '<p class="comment-form-email ast-col-xs-12 ast-col-sm-12 ast-col-md-4 ast-col-lg-4">' .
					'<label for="email" class="screen-reader-text">' . ast_default_strings( 'string-comment-label-email', false ) . '</label><input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) .
					'" placeholder="' . ast_default_strings( 'string-comment-label-email', false ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['url'] = '<p class="comment-form-url ast-col-xs-12 ast-col-sm-12 ast-col-md-4 ast-col-lg-4"><label for="url">' .
					'<label for="url" class="screen-reader-text">' . ast_default_strings( 'string-comment-label-website', false ) . '</label><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
					'" placeholder="' . ast_default_strings( 'string-comment-label-website', false ) . '" size="30" /></label></p></div>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'ast_comment_form_default_fields_markup' );

/**
 * Function to filter comment form arguments
 */
if ( ! function_exists( 'ast_comment_form_default_markup' ) ) {

	/**
	 * Function filter comment form arguments
	 *
	 * @since 1.0.0
	 * @param array $args 	Comment form arguments.
	 * @return array
	 */
	function ast_comment_form_default_markup( $args ) {

		$args['id_form']            = 'ast-commentform';
		$args['title_reply']        = ast_default_strings( 'string-comment-title-reply', false );
		$args['cancel_reply_link']  = ast_default_strings( 'string-comment-cancel-reply-link', false );
		$args['label_submit']       = ast_default_strings( 'string-comment-label-submit', false );
		$args['comment_field']      = '<div class="ast-row comment-textarea"><fieldset class="comment-form-comment"><div class="comment-form-textarea ast-col-lg-12"><textarea id="comment" name="comment" placeholder="' . ast_default_strings( 'string-comment-label-message', false ) . '" cols="45" rows="8" aria-required="true"></textarea></div></fieldset></div>';

		return $args;
	}
}
add_filter( 'comment_form_defaults', 'ast_comment_form_default_markup' );


/**
 * Function to filter comment form arguments
 */
if ( ! function_exists( 'ast_404_page_layout' ) ) {

	/**
	 * Function filter comment form arguments
	 *
	 * @since 1.0.0
	 * @param array $layout 	Comment form arguments.
	 * @return array
	 */
	function ast_404_page_layout( $layout ) {

		if ( is_404() ) {
			$layout = 'no-sidebar';
		}
		return $layout;
	}
}
add_filter( 'ast_page_layout', 'ast_404_page_layout', 10, 1 );

/**
 * Return current content layout
 */
if ( ! function_exists( 'ast_get_content_layout' ) ) {

	/**
	 * Return current content layout
	 *
	 * @since 1.0.0
	 * @return boolean 	content layout.
	 */
	function ast_get_content_layout() {

		$value = false;

		if ( is_singular() ) {

			// If post meta value is empty,
			// Then get the POST_TYPE content layout.
			$content_layout = ast_get_option_meta( 'site-content-layout', '', '', true );

			if ( empty( $content_layout ) ) {

				$content_layout = ast_get_option( 'single-' . get_post_type() . '-content-layout', '', '' );

				if ( 'default' == $content_layout || empty( $content_layout ) ) {

					// Get the GLOBAL content layout value.
					// NOTE: Here not used `true` in the below function call.
					$content_layout = ast_get_option( 'site-content-layout', '', 'full-width' );
				}
			}
		} else {

			$content_layout = ast_get_option( 'archive-' . get_post_type() . '-content-layout', '', '' );
			if ( is_search() ) {
				$content_layout = ast_get_option( 'archive-post-content-layout', '', '' );
			}

			if ( 'default' == $content_layout || empty( $content_layout ) ) {

				// Get the GLOBAL content layout value.
				// NOTE: Here not used `true` in the below function call.
				$content_layout = ast_get_option( 'site-content-layout', '', 'full-width' );
			}
		}

		return apply_filters( 'ast_get_content_layout', $content_layout );
	}
}// End if().

add_filter( 'ast_the_title_enabled', 'page_builder_disable_title', 12 );

/**
 * Disbale title for Page Builder template
 */
if ( ! function_exists( 'page_builder_disable_title' ) ) {

	/**
	 * Disbale title for Page Builder template
	 *
	 * @since 1.0.0
	 * @param boolean $default 	Title enabled or not.
	 * @return boolean 			Title enable or disable.
	 */
	function page_builder_disable_title( $default ) {
		$content_layout = ast_get_content_layout();

		if ( 'page-builder' == $content_layout ) {
			$default = false;
		}
		return $default;
	}
}

/**
 * Display Blog Post Excerpt
 */
if ( ! function_exists( 'ast_the_excerpt' ) ) {

	/**
	 * Display Blog Post Excerpt
	 *
	 * @since 1.0.0
	 */
	function ast_the_excerpt() {

		$excerpt_type = ast_get_option( 'blog-post-content' );

		if ( 'full-content' == $excerpt_type ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}
