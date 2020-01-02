<?php
/**
 * Schema markup.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Astra CreativeWork Schema Markup.
 *
 * @since 2.1.3
 */
class Astra_CreativeWork_Schema extends Astra_Schema {

	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {

		if ( true !== $this->schema_enabled() ) {
			return false;
		}

		add_filter( 'astra_attr_article-blog', array( $this, 'creative_work_schema' ) );
		add_filter( 'astra_attr_article-page', array( $this, 'creative_work_schema' ) );
		add_filter( 'astra_attr_article-single', array( $this, 'creative_work_schema' ) );
		add_filter( 'astra_attr_article-content', array( $this, 'creative_work_schema' ) );
		add_filter( 'astra_attr_article-title-blog', array( $this, 'article_title_blog_schema_prop' ) );
		add_filter( 'astra_attr_article-title-blog-single', array( $this, 'article_title_blog_single_schema_prop' ) );
		add_filter( 'astra_attr_article-title-content-page', array( $this, 'article_title_content_page_schema_prop' ) );
		add_filter( 'astra_attr_article-title-content', array( $this, 'article_title_content_schema_prop' ) );
		add_filter( 'astra_attr_article-entry-content-blog-layout', array( $this, 'article_content_blog_layout_schema_prop' ) );
		add_filter( 'astra_attr_article-entry-content-page', array( $this, 'article_content_page_schema_prop' ) );
		add_filter( 'astra_attr_article-entry-content', array( $this, 'article_content_schema_prop' ) );
		add_filter( 'astra_attr_article-entry-content-blog-layout-2', array( $this, 'article_content_blog_layout_2_schema_prop' ) );
		add_filter( 'astra_attr_article-entry-content-blog-layout-3', array( $this, 'article_content_blog_layout_3_schema_prop' ) );
		add_filter( 'astra_attr_article-entry-content-single-layout', array( $this, 'article_content_single_layout_schema_prop' ) );
		add_filter( 'astra_post_thumbnail_itemprop', array( $this, 'article_image_schema_prop' ) );
		add_filter( 'astra_attr_article-image-blog-archive', array( $this, 'article_image_blog_archive_schema_prop' ) );
		add_filter( 'astra_attr_article-image-blog-single-post', array( $this, 'article_image_blog_single_post_schema_prop' ) );
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function creative_work_schema( $attr ) {
		$attr['itemtype']  = 'https://schema.org/CreativeWork';
		$attr['itemscope'] = 'itemscope';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_title_blog_schema_prop( $attr ) {
		$attr['itemprop'] = 'headline';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_title_blog_single_schema_prop( $attr ) {
		$attr['itemprop'] = 'headline';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_title_content_page_schema_prop( $attr ) {
		$attr['itemprop'] = 'headline';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_title_content_schema_prop( $attr ) {
		$attr['itemprop'] = 'headline';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_content_blog_layout_schema_prop( $attr ) {
		$attr['itemprop'] = 'text';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_content_page_schema_prop( $attr ) {
		$attr['itemprop'] = 'text';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_content_schema_prop( $attr ) {
		$attr['itemprop'] = 'text';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_content_blog_layout_2_schema_prop( $attr ) {
		$attr['itemprop'] = 'text';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_content_blog_layout_3_schema_prop( $attr ) {
		$attr['itemprop'] = 'text';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_content_single_layout_schema_prop( $attr ) {
		$attr['itemprop'] = 'text';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_image_schema_prop( $attr ) {
		$attr = 'itemprop=image';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array       Updated embed markup.
	 */
	public function article_image_blog_archive_schema_prop( $attr ) {
		$attr['itemprop'] = 'image';

		return $attr;
	}

	/**
	 * Update Schema markup attribute.
	 *
	 * @param  array $attr An array of attributes.
	 *
	 * @return array Updated embed markup.
	 */
	public function article_image_blog_single_post_schema_prop( $attr ) {
		$attr['itemprop'] = 'image';

		return $attr;
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'astra_creativework_schema_enabled', parent::schema_enabled() );
	}

}

new Astra_CreativeWork_Schema();
