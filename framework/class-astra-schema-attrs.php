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

if ( ! class_exists( 'Astra_Schema_Attrs' ) ) {

	/**
	 * Astra Attrs
	 */
	class Astra_Schema_Attrs {

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
		 * Schema init.
		 */
		public function init() {

			$article_schema = apply_filters( 'astra_article_schema_enabled', true );
			if ( $article_schema ) {
				add_filter( 'astra_attr_article', array( $this, 'article_attrs' ) );
				add_filter( 'astra_attr_entry-content', array( $this, 'entry_content_attrs' ) );

				add_filter( 'astra_attr_post-meta-author', array( $this, 'post_meta_author_attrs' ) );
				add_filter( 'astra_attr_post-meta-author-url', array( $this, 'post_meta_author_url_attrs' ) );
				add_filter( 'astra_attr_post-meta-author-name', array( $this, 'post_meta_author_name_attrs' ) );
				add_filter( 'astra_attr_post-entry-title', array( $this, 'post_entry_title_attrs' ) );

				add_filter( 'astra_attr_post-meta-comment-statistic', array( $this, 'post_meta_comment_statistic_attrs' ) );
				add_filter( 'astra_attr_post-meta-comment-type', array( $this, 'post_meta_comment_type_attrs' ) );
				add_filter( 'astra_attr_post-meta-comment-count', array( $this, 'post_meta_comment_count_attrs' ) );

				add_filter( 'astra_attr_post-meta-posted-date', array( $this, 'post_meta_posted_date_attrs' ) );
			}

			$sidebar_schema = apply_filters( 'astra_sidebar_schema_enabled', true );
			if ( $sidebar_schema ) {
				add_filter( 'astra_attr_sidebar', array( $this, 'sidebar_attrs' ) );
			}

			$footer_schema = apply_filters( 'astra_footer_schema_enabled', true );
			if ( $footer_schema ) {
				add_filter( 'astra_attr_footer', array( $this, 'footer_attrs' ) );
			}

			$header_schema = apply_filters( 'astra_header_schema_enabled', true );
			if ( $header_schema ) {
				add_filter( 'astra_attr_header', array( $this, 'header_attrs' ) );
			}

			$site_identity_schema = apply_filters( 'astra_site_identity_schema_enabled', true );
			if ( $site_identity_schema ) {
				add_filter( 'astra_attr_site-identity', array( $this, 'site_identity_attrs' ) );
				add_filter( 'astra_attr_site-identity-name', array( $this, 'site_identity_name_attrs' ) );
				add_filter( 'astra_attr_site-identity-url', array( $this, 'site_identity_url_attrs' ) );
			}

			$site_navigation_schema = apply_filters( 'astra_site_navigation_schema_enabled', true );
			if ( $site_navigation_schema ) {
				add_filter( 'astra_attr_site-navigation', array( $this, 'site_navigation_attrs' ) );
			}

			$body_schema = apply_filters( 'astra_body_schema_enabled', true );
			if ( $body_schema ) {
				add_filter( 'astra_attr_body', array( $this, 'body_attrs' ) );
			}
		}

		/**
		 * Article Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function article_attrs( $attrs ) {

			$attrs['itemtype']  = 'http://schema.org/CreativeWork';
			$attrs['itemscope'] = 'itemscope';
			return $attrs;
		}

		/**
		 * Entry Content Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function entry_content_attrs( $attrs ) {

			$attrs['itemprop'] = 'text';
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

			$attrs['itemtype']  = 'http://schema.org/WPSideBar';
			$attrs['itemscope'] = 'itemscope';
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

			$attrs['itemtype']  = 'http://schema.org/WPFooter';
			$attrs['itemscope'] = 'itemscope';
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

			$attrs['itemtype']  = 'http://schema.org/WPHeader';
			$attrs['itemscope'] = 'itemscope';
			return $attrs;
		}

		/**
		 * Post Meta Author Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_author_attrs( $attrs ) {

			$attrs['itemtype']  = 'http://schema.org/Person';
			$attrs['itemscope'] = 'itemscope';
			$attrs['itemprop']  = 'author';
			return $attrs;
		}

		/**
		 * Post Meta Author Url Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_author_url_attrs( $attrs ) {

			$attrs['itemprop'] = 'url';
			return $attrs;
		}

		/**
		 * Post Meta Author Name Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_author_name_attrs( $attrs ) {

			$attrs['itemprop'] = 'name';
			return $attrs;
		}

		/**
		 * Entry Title Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_entry_title_attrs( $attrs ) {

			$attrs['itemprop'] = 'headline';
			return $attrs;
		}

		/**
		 * Post Meta Comment Stats Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_comment_statistic_attrs( $attrs ) {

			$attrs['itemtype']  = 'http://schema.org/InteractionCounter';
			$attrs['itemprop']  = 'interactionStatistic';
			$attrs['itemscope'] = 'itemscope';
			return $attrs;
		}

		/**
		 * Post Meta Comment Type Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_comment_type_attrs( $attrs ) {

			$attrs['content']  = 'http://schema.org/CommentAction';
			$attrs['itemprop'] = 'interactionType';
			return $attrs;
		}

		/**
		 * Post Meta Comment Count Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_comment_count_attrs( $attrs ) {

			$attrs['content']  = absint( wp_count_comments( get_the_ID() )->approved );
			$attrs['itemprop'] = 'userInteractionCount';
			return $attrs;
		}

		/**
		 * Post Meta Posted Date Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function post_meta_posted_date_attrs( $attrs ) {

			$attrs['itemprop'] = 'datePublished';
			return $attrs;
		}

		/**
		 * Site Identity Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function site_identity_attrs( $attrs ) {

			$attrs['itemtype']  = 'http://schema.org/Organization';
			$attrs['itemscope'] = 'itemscope';
			return $attrs;
		}

		/**
		 * Site Identity Name Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function site_identity_name_attrs( $attrs ) {

			$attrs['itemprop'] = 'name';
			return $attrs;
		}

		/**
		 * Site Identity Url Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function site_identity_url_attrs( $attrs ) {

			$attrs['itemprop'] = 'url';
			return $attrs;
		}

		/**
		 * Site Identity Description Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function site_identity_description_attrs( $attrs ) {

			$attrs['itemprop'] = 'description';
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

			$attrs['itemtype']  = 'http://schema.org/SiteNavigationElement';
			$attrs['itemscope'] = 'itemscope';
			return $attrs;
		}

		/**
		 * Body Attributes.
		 *
		 * @param array $attrs default attributes.
		 *
		 * @return array attributes.
		 */
		public function body_attrs( $attrs ) {

			// Check conditions.
			$is_blog = ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) ? true : false;

			// Set up default itemtype.
			$itemtype = 'WebPage';

			// Get itemtype for the blog.
			$itemtype = ( $is_blog ) ? 'Blog' : $itemtype;

			// Get itemtype for search results.
			$itemtype = ( is_search() ) ? 'SearchResultsPage' : $itemtype;

			$attrs['itemtype']  = 'http://schema.org/' . $itemtype;
			$attrs['itemscope'] = 'itemscope';
			return $attrs;
		}
	}

	Astra_Schema_Attrs::get_instance();

}// End if().
