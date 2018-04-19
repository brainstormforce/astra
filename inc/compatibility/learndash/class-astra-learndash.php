<?php
/**
 * LearnDash Compatibility File.
 *
 * @package Astra
 * @since 1.3.0
 */

// If plugin - 'LearnDash' not exist then return.
if ( ! class_exists( 'SFWD_LMS' ) ) {
	return;
}

/**
 * Astra LearnDash Compatibility
 */
if ( ! class_exists( 'Astra_LearnDash' ) ) :

	/**
	 * Astra LearnDash Compatibility
	 *
	 * @since 1.3.0
	 */
	class Astra_LearnDash {

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
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			add_filter( 'astra_theme_assets', array( $this, 'add_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_dynamic_styles' ) );

			add_action( 'customize_register', array( $this, 'customize_register' ), 11 );
			add_filter( 'astra_theme_defaults', array( $this, 'theme_defaults' ) );

			// Sidebar Layout.
			add_filter( 'astra_page_layout', array( $this, 'sidebar_layout' ) );
			// Content Layout.
			add_filter( 'astra_get_content_layout', array( $this, 'content_layout' ) );
		}

		/**
		 * Enqueue styles
		 *
		 * @since 1.3.0
		 * @return void
		 */
		function add_dynamic_styles() {

			/**
			 * - Variable Declaration
			 */
			$link_color   = astra_get_option( 'link-color' );
			$theme_color  = astra_get_option( 'theme-color' );
			$text_color   = astra_get_option( 'text-color' );
			$link_h_color = astra_get_option( 'link-h-color' );

			$body_font_family = astra_body_font_family();

			$link_forground_color  = astra_get_foreground_color( $link_color );
			$theme_forground_color = astra_get_foreground_color( $theme_color );
			$btn_color             = astra_get_option( 'button-color' );
			if ( empty( $btn_color ) ) {
				$btn_color = $link_forground_color;
			}

			$btn_h_color = astra_get_option( 'button-h-color' );
			if ( empty( $btn_h_color ) ) {
				$btn_h_color = astra_get_foreground_color( $link_h_color );
			}
			$btn_bg_color   = astra_get_option( 'button-bg-color', '', $theme_color );
			$btn_bg_h_color = astra_get_option( 'button-bg-h-color', '', $link_h_color );

			$btn_border_radius      = astra_get_option( 'button-radius' );
			$btn_vertical_padding   = astra_get_option( 'button-v-padding' );
			$btn_horizontal_padding = astra_get_option( 'button-h-padding' );

			$archive_post_title_font_size = astra_get_option( 'font-size-page-title' );

			$css_output = array(
				'body #learndash_lessons a, body #learndash_quizzes a, body .expand_collapse a, body .learndash_topic_dots a, body .learndash_topic_dots a > span, body #learndash_lesson_topics_list span a, body #learndash_profile a, body #learndash_profile a span' => array(
					'font-family' => astra_get_font_family( $body_font_family ),
				),
				'body #ld_course_list .btn, body a.btn-blue, body a.btn-blue:visited, body a#quiz_continue_link, body .btn-join, body .learndash_checkout_buttons input.btn-join[type="button"], body #btn-join, body .learndash_checkout_buttons input.btn-join[type="submit"], body .wpProQuiz_content .wpProQuiz_button2' => array(
					'color'            => $btn_color,
					'border-color'     => $btn_bg_color,
					'background-color' => $btn_bg_color,
					'border-radius'    => astra_get_css_value( $btn_border_radius, 'px' ),
				),
				'body #ld_course_list .btn:hover, body #ld_course_list .btn:focus, body a.btn-blue:hover, body a.btn-blue:focus, body a#quiz_continue_link:hover, body a#quiz_continue_link:focus, body .btn-join:hover, body .learndash_checkout_buttons input.btn-join[type="button"]:hover, body .btn-join:focus, body .learndash_checkout_buttons input.btn-join[type="button"]:focus, .wpProQuiz_content .wpProQuiz_button2:hover, .wpProQuiz_content .wpProQuiz_button2:focus, body #btn-join:hover, body .learndash_checkout_buttons input.btn-join[type="submit"]:hover, body #btn-join:focus, body .learndash_checkout_buttons input.btn-join[type="submit"]:focus' => array(
					'color'            => $btn_h_color,
					'border-color'     => $btn_bg_h_color,
					'background-color' => $btn_bg_h_color,
				),
				'body dd.course_progress div.course_progress_blue, body .wpProQuiz_content .wpProQuiz_time_limit .wpProQuiz_progress' => array(
					'background-color' => $theme_color,
				),
				'body #learndash_lessons a, body #learndash_quizzes a, body .expand_collapse a, body .learndash_topic_dots a, body .learndash_topic_dots a > span, body #learndash_lesson_topics_list span a, body #learndash_profile a, #learndash_profile .profile_edit_profile a, body #learndash_profile .expand_collapse a, body #learndash_profile a span, #lessons_list .list-count, #quiz_list .list-count' => array(
					'color' => $link_color,
				),
				'.learndash .notcompleted:before, #learndash_profile .notcompleted:before, .learndash_topic_dots ul .topic-notcompleted span:before, .learndash_navigation_lesson_topics_list .topic-notcompleted span:before, .learndash_navigation_lesson_topics_list ul .topic-notcompleted span:before, .learndash .topic-notcompleted span:before' => array(
					'color' => astra_hex_to_rgba( $text_color, .5 ),
				),
				'body .thumbnail.course .ld_course_grid_price, body .thumbnail.course .ld_course_grid_price.ribbon-enrolled, body #learndash_lessons #lesson_heading, body #learndash_profile .learndash_profile_heading, body #learndash_quizzes #quiz_heading, body #learndash_lesson_topics_list div > strong, body .learndash-pager span a, body #learndash_profile .learndash_profile_quiz_heading' => array(
					'background-color' => $theme_color,
					'color'            => $theme_forground_color,
				),
				'.learndash .completed:before, #learndash_profile .completed:before, .learndash_topic_dots ul .topic-completed span:before, .learndash_navigation_lesson_topics_list .topic-completed span:before, .learndash_navigation_lesson_topics_list ul .topic-completed span:before, .learndash .topic-completed span:before, body .list_arrow.lesson_completed:before' => array(
					'color' => $theme_color,
				),
				'body .thumbnail.course .ld_course_grid_price:before' => array(
					'border-top-color'   => astra_hex_to_rgba( $theme_color, .75 ),
					'border-right-color' => astra_hex_to_rgba( $theme_color, .75 ),
				),
				'body .wpProQuiz_loadQuiz, body .wpProQuiz_lock' => array(
					'border-color'     => astra_hex_to_rgba( $link_color, .5 ),
					'background-color' => astra_hex_to_rgba( $link_color, .1 ),
				),
				'#ld_course_list .entry-title' => array(
					'font-size' => astra_responsive_font( $archive_post_title_font_size, 'desktop' ),
				),
			);

			if ( ! astra_get_option( 'learndash-lesson-serial-number' ) ) {
				$css_output['body #course_list .list-count, body #lessons_list .list-count, body #quiz_list .list-count'] = array(
					'display' => 'none',
				);
				$css_output['body #course_list > div h4 > a, body #lessons_list > div h4 > a, body #quiz_list > div h4 > a, body #learndash_course_content .learndash_topic_dots ul > li a'] = array(
					'padding-left' => '.75em',
					'margin-left'  => 'auto',
				);
			}
			if ( ! astra_get_option( 'learndash-differentiate-rows' ) ) {
				$css_output['body #course_list > div:nth-of-type(odd), body #lessons_list > div:nth-of-type(odd), body #quiz_list > div:nth-of-type(odd), body #learndash_lesson_topics_list .learndash_topic_dots ul > li.nth-of-type-odd'] = array(
					'background' => 'none',
				);
			}

			/* Parse CSS from array() */
			$css_output = astra_parse_css( $css_output );

			$tablet_typography = array(
				'#ld_course_list .entry-title' => array(
					'font-size' => astra_responsive_font( $archive_post_title_font_size, 'tablet', 30 ),
				),
			);
			/* Parse CSS from array()*/
			$css_output .= astra_parse_css( $tablet_typography, '', '768' );

			$mobile_typography = array(
				'#ld_course_list .entry-title' => array(
					'font-size' => astra_responsive_font( $archive_post_title_font_size, 'mobile', 30 ),
				),
			);
			/* Parse CSS from array()*/
			$css_output .= astra_parse_css( $mobile_typography, '', '544' );

			wp_add_inline_style( 'learndash_style', apply_filters( 'astra_theme_learndash_dynamic_css', $css_output ) );

		}

		/**
		 * Register Customizer sections and panel for learndash.
		 *
		 * @since 1.3.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register( $wp_customize ) {

			/**
			 * Register Sections & Panels
			 */
			require ASTRA_THEME_DIR . 'inc/compatibility/learndash/customizer/register-panels-and-sections.php';

			/**
			 * Sections
			 */
			require ASTRA_THEME_DIR . 'inc/compatibility/learndash/customizer/sections/section-container.php';
			require ASTRA_THEME_DIR . 'inc/compatibility/learndash/customizer/sections/section-sidebar.php';
			require ASTRA_THEME_DIR . 'inc/compatibility/learndash/customizer/sections/layout/section-general.php';
		}

		/**
		 * Theme Defaults.
		 *
		 * @since 1.3.0
		 * @param array $defaults Array of options value.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			// General.
			$defaults['learndash-lesson-serial-number'] = false;
			$defaults['learndash-differentiate-rows']   = true;

			// Container.
			$defaults['learndash-content-layout'] = 'plain-container';

			// Sidebar.
			$defaults['learndash-sidebar-layout'] = 'default';

			return $defaults;
		}

		/**
		 * Add assets in theme
		 *
		 * @since 1.3.0
		 * @param array $assets list of theme assets (JS & CSS).
		 * @return array List of updated assets.
		 */
		function add_styles( $assets ) {
			$assets['css']['astra-learndash'] = 'compatibility/learndash';
			return $assets;
		}

		/**
		 * LeanDash Sidebar
		 *
		 * @since 1.3.0
		 * @param string $layout Layout type.
		 * @return string $layout Layout type.
		 */
		function sidebar_layout( $layout ) {

			if ( is_singular( 'sfwd-courses' ) || is_singular( 'sfwd-lessons' ) || is_singular( 'sfwd-topic' ) || is_singular( 'sfwd-quiz' ) || is_singular( 'sfwd-certificates' ) || is_singular( 'sfwd-assignment' ) ) {

				$learndash_sidebar = astra_get_option( 'learndash-sidebar-layout' );
				if ( 'default' !== $learndash_sidebar ) {
					$layout = $learndash_sidebar;
				}

				$sidebar = astra_get_option_meta( 'site-sidebar-layout', '', true );

				if ( 'default' !== $sidebar && ! empty( $sidebar ) ) {
					$layout = $sidebar;
				}
			}

			// When Learhdash shortoce is used on the Page.
			// Applied only to the pages which uses the learndash shortcode.
			global $learndash_shortcode_used;
			$post_type = get_post_type();
			if ( $learndash_shortcode_used && ! ( is_singular( 'sfwd-courses' ) || is_singular( 'sfwd-lessons' ) || is_singular( 'sfwd-topic' ) || is_singular( 'sfwd-quiz' ) || is_singular( 'sfwd-certificates' ) || is_singular( 'sfwd-assignment' ) ) ) {
				// Page Meta Sidebar.
				$layout = astra_get_option_meta( 'site-sidebar-layout', '', true );
				if ( empty( $layout ) ) {
					// Page Sidebar.
					$layout = astra_get_option( 'single-page-sidebar-layout' );
					// Default Site Sidebar.
					if ( 'default' == $layout || empty( $layout ) ) {
						// Get the global sidebar value.
						// NOTE: Here not used `true` in the below function call.
						$layout = astra_get_option( 'site-sidebar-layout' );
					}
				}
			}
			return $layout;
		}

		/**
		 * LeanDash Container
		 *
		 * @since 1.3.0
		 * @param string $layout Layout type.
		 * @return string $layout Layout type.
		 */
		function content_layout( $layout ) {

			if ( is_singular( 'sfwd-courses' ) || is_singular( 'sfwd-lessons' ) || is_singular( 'sfwd-topic' ) || is_singular( 'sfwd-quiz' ) || is_singular( 'sfwd-certificates' ) || is_singular( 'sfwd-assignment' ) ) {

				$learndash_layout = astra_get_option( 'learndash-content-layout' );

				if ( 'default' !== $learndash_layout ) {
					$layout = $learndash_layout;
				}

				$learndash_layout = astra_get_option_meta( 'site-content-layout', '', true );

				if ( 'default' !== $learndash_layout && ! empty( $learndash_layout ) ) {
					$layout = $learndash_layout;
				}
			}

			return $layout;
		}
	}

endif;

if ( apply_filters( 'astra_enable_learndash_integration', true ) ) {

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_LearnDash::get_instance();
}
