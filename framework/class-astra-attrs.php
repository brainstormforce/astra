<?php
/**
 * Astra Attrs
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.20
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Attrs' ) ) {

	/**
	 * Astra Attrs
	 */
	class Astra_Attrs {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
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
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'init' ) );
		}

		/**
		 * Init attr filters.
		 */
		public function init() {
			add_filter( 'astra_attr_body', array( $this, 'body_attrs' ) );
			add_filter( 'astra_attr_primary', array( $this, 'primary_attrs' ) );
			add_filter( 'astra_attr_main', array( $this, 'main_attrs' ) );
			add_filter( 'astra_attr_page', array( $this, 'page_attrs' ) );
			add_filter( 'astra_attr_content', array( $this, 'content_attrs' ) );
			add_filter( 'astra_attr_comment', array( $this, 'comment_attrs' ) );
			add_filter( 'astra_attr_comment-navigation', array( $this, 'comment_navigation_attrs' ) );
			add_filter( 'astra_attr_article', array( $this, 'article_attrs' ) );
			add_filter( 'astra_attr_sidebar', array( $this, 'sidebar_attrs' ) );
			add_filter( 'astra_attr_footer', array( $this, 'footer_attrs' ) );
			add_filter( 'astra_attr_header', array( $this, 'header_attrs' ) );
			add_filter( 'astra_attr_post-meta-author-url', array( $this, 'post_meta_author_url_attrs' ) );
			add_filter( 'astra_attr_site-identity-url', array( $this, 'site_identity_url_attrs' ) );
			add_filter( 'astra_attr_site-navigation', array( $this, 'site_navigation_attrs' ) );
		}


		/**
		 * Body Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function body_attrs( $attrs ) {

			$classes = get_body_class();

			$attrs['class'] = join( ' ', $classes );
			return $attrs;
		}

		/**
		 * Primary Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function primary_attrs( $attrs ) {

			$attrs['id']    = 'primary';
			$attrs['class'] = join( ' ', astra_get_primary_class() );
			return $attrs;
		}

		/**
		 * Main Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function main_attrs( $attrs ) {

			$attrs['id']    = 'main';
			$attrs['role']  = 'main';
			$attrs['class'] = 'site-main';
			return $attrs;
		}

		/**
		 * Page Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function page_attrs( $attrs ) {

			$attrs['id']    = 'page';
			$attrs['class'] = 'hfeed site';
			return $attrs;
		}

		/**
		 * Content Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function content_attrs( $attrs ) {

			$attrs['id']    = 'content';
			$attrs['class'] = 'site-content';
			return $attrs;
		}

		/**
		 * Comment Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function comment_attrs( $attrs ) {

			$attrs['id']    = 'comments';
			$attrs['class'] = 'comments-area';
			return $attrs;
		}

		/**
		 * Comment Navigation Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function comment_navigation_attrs( $attrs ) {

			$attrs['id']         = 'comment-nav-below';
			$attrs['role']       = 'navigation';
			$attrs['class']      = 'navigation comment-navigation';
			$attrs['aria-label'] = esc_html__( 'Comments Navigation', 'astra' );
			return $attrs;
		}

		/**
		 * Article Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function article_attrs( $attrs ) {

			$classes = get_post_class();

			$attrs['id']    = 'post-' . get_the_ID();
			$attrs['class'] = join( ' ', $classes );
			return $attrs;
		}

		/**
		 * Sidebar Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function sidebar_attrs( $attrs ) {

			$attrs['id']    = 'secondary';
			$attrs['role']  = 'complementary';
			$attrs['class'] = join( ' ', get_astra_secondary_class() );
			return $attrs;
		}

		/**
		 * Footer Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function footer_attrs( $attrs ) {

			$attrs['id']   = 'colophon';
			$attrs['role'] = 'contentinfo';
			return $attrs;
		}

		/**
		 * Header Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function header_attrs( $attrs ) {

			$attrs['id']   = 'masthead';
			$attrs['role'] = 'banner';
			return $attrs;
		}

		/**
		 * Post meta author url Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_author_url_attrs( $attrs ) {

			/* translators: 1. %s: author */
			$attrs['title'] = sprintf( __( 'View all posts by %s', 'astra' ), get_the_author() );
			$attrs['href']  = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
			$attrs['rel']   = 'author';
			return $attrs;
		}

		/**
		 * Site Identity Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function site_identity_url_attrs( $attrs ) {

			$attrs['href'] = esc_url( home_url( '/' ) );
			$attrs['rel']  = 'home';
			return $attrs;
		}

		/**
		 * Site Navigation Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function site_navigation_attrs( $attrs ) {

			$attrs['id']         = 'site-navigation';
			$attrs['role']       = 'navigation';
			$attrs['aria-label'] = __( 'Site Navigation', 'astra' );
			return $attrs;
		}
	}

	Astra_Attrs::get_instance();

}// End if().
