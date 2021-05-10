<?php
/**
 * Astra Generate Markup Class.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2021, Astra
 * @link        https://wpastra.com/
 * @since       Astra 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Astra_Attr
 */
class Astra_Markup {

	/**
	 * Initialuze the Class.
	 *
	 * @since 3.3.0
	 */
	public function __construct() {

		if ( ! Astra_Builder_Helper::apply_flex_based_css() ) {
			// Add filters here.
			add_filter( 'astra_markup_footer-widget-div_open', array( $this, 'footer_widget_div_open' ) );
			add_filter( 'astra_markup_footer-widget-div_close', array( $this, 'footer_widget_div_close' ) );
			add_filter( 'astra_markup_header-widget-div_open', array( $this, 'header_widget_div_open' ) );
			add_filter( 'astra_markup_header-widget-div_close', array( $this, 'footer_widget_div_close' ) );                        
			add_filter( 'astra_markup_comment-count-wrapper_open', array( $this, 'comment_count_wrapper_open' ) );
			add_filter( 'astra_markup_comment-count-wrapper_close', array( $this, 'comment_count_wrapper_close' ) );
			add_filter( 'astra_markup_ast-comment-data-wrap_open', array( $this, 'ast_comment_data_wrap_open' ) );
			add_filter( 'astra_markup_ast-comment-data-wrap_close', array( $this, 'ast_comment_data_wrap_close' ) );
			add_filter( 'astra_markup_ast-comment-meta-wrap_open', array( $this, 'ast_comment_meta_wrap_open' ) );
			add_filter( 'astra_markup_ast-comment-meta-wrap_close', array( $this, 'ast_comment_meta_wrap_close' ) );                                
			add_filter( 'astra_attr_ast-comment-time_output', array( $this, 'ast_comment_time_attr' ) );
			add_filter( 'astra_attr_ast-comment-cite-wrap_output', array( $this, 'ast_comment_cite_wrap_attr' ) );
		}
		add_filter( 'astra_attr_comment-form-grid-class_output', array( $this, 'comment_form_grid_class' ) );
		add_filter( 'astra_attr_header-widget-area-inner', array( $this, 'header_widget_area_inner' ) );
		add_filter( 'astra_attr_footer-widget-area-inner', array( $this, 'footer_widget_area_inner' ) );
		add_filter( 'astra_attr_ast-grid-lg-12_output', array( $this, 'ast_grid_lg_12' ) );
		add_filter( 'astra_attr_ast-grid-common-col_output', array( $this, 'ast_grid_common_css' ) );
		add_filter( 'astra_attr_ast-grid-blog-col_output', array( $this, 'ast_grid_blog_col' ) );
		add_filter( 'astra_attr_ast-blog-col_output', array( $this, 'ast_blog_common_css' ) );
		add_filter( 'astra_attr_ast-grid-col-6_output', array( $this, 'ast_grid_col_6' ) );
		add_filter( 'astra_attr_ast-layout-4-grid_output', array( $this, 'ast_layout_4_grid' ) );
		add_filter( 'astra_attr_ast-layout-1-grid_output', array( $this, 'ast_layout_1_grid' ) );
		add_filter( 'astra_attr_ast-layout-2-grid_output', array( $this, 'ast_layout_2_grid' ) );
		add_filter( 'astra_attr_ast-layout-3-grid_output', array( $this, 'ast_layout_3_grid' ) );
		add_filter( 'astra_attr_ast-layout-5-grid_output', array( $this, 'ast_layout_5_grid' ) );
		add_filter( 'astra_attr_ast-layout-6-grid_output', array( $this, 'ast_layout_6_grid' ) ); 
			
	}

	/** 
	 * Comment count wrapper opening div.
	 *
	 * @param array $args markup arguments.
	 * @since 3.3.0
	 * @return array.
	 */
	public function comment_count_wrapper_open( $args ) {
		$args['open']  = '<div %s>';
		$args['attrs'] = array( 'class' => 'comments-count-wrapper' );           
		return $args;
	}

	/** 
	 * Comment count wrapper closing div.
	 *
	 * @param array $args markup arguments.
	 * @since 3.3.0
	 * @return array.
	 */
	public function comment_count_wrapper_close( $args ) {
		$args['close'] = '</div>';
		return $args;
	}

	/** 
	 * Comment data wrapper opening div.
	 *
	 * @param array $args markup arguments.
	 * @since 3.3.0
	 * @return array.
	 */
	public function ast_comment_data_wrap_open( $args ) {
		$args['open']  = '<div %s>';
		$args['attrs'] = array( 'class' => 'ast-comment-data-wrap' );           
		return $args;
	}

	/** 
	 * Comment data wrapper closing div.
	 *
	 * @param array $args markup arguments.
	 * @since 3.3.0
	 * @return array.
	 */
	public function ast_comment_data_wrap_close( $args ) {
		$args['close'] = '</div>';
		return $args;
	}

	/** 
	 * Comment meta wrapper opening div.
	 *
	 * @param array $args markup arguments.
	 * @since 3.3.0
	 * @return array.
	 */
	public function ast_comment_meta_wrap_open( $args ) {
		$args['open']  = '<div %s>';
		$args['attrs'] = array( 'class' => 'ast-comment-meta-wrap' );           
		return $args;
	}

	/** 
	 * Comment meta wrapper closing div.
	 *
	 * @param array $args markup arguments.
	 * @since 3.3.0
	 * @return array.
	 */
	public function ast_comment_meta_wrap_close( $args ) {
		$args['close'] = '</div>';
		return $args;
	}

	/** 
	 * Comment time div attributes.
	 *
	 * @since 3.3.0
	 * @return string.
	 */
	public function ast_comment_time_attr() {
		return 'class = "ast-comment-time ast-col-lg-12" ';
	}

	/** 
	 * Comment cite wrapper div attributes.
	 *
	 * @since 3.3.0
	 * @return string.
	 */
	public function ast_comment_cite_wrap_attr() {
		return 'class = "ast-comment-cite-wrap ast-col-lg-12" ';
	}

	/**
	 * We have removed grid css and make common css for grid style.
	 *
	 * @since 3.3.0
	 * @return string.
	 */
	public function ast_grid_common_css() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col' : 'ast-col-md-12'; 
	}

	/**
	 * Blog content Grid CSS.
	 *
	 * @since 3.3.0
	 * @return string.
	 */
	public function ast_grid_blog_col() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col ast-float' : 'ast-col-md-12'; 
	}

	/**
	 * We have removed grid css and make common css for grid style.
	 *
	 * @since 3.3.0
	 * @return string.
	 */
	public function ast_blog_common_css() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col ast-full-width' : 'ast-col-sm-12'; 
	}

	/**
	 * Removed grid layout classes and make common class for same style.
	 *
	 * @since 3.3.0
	 * @return string.
	 */
	public function ast_grid_col_6() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-width-md-6' : 'ast-col-md-6'; 
	}

	/** 
	 * Comment form grid classes.
	 *
	 * @since 3.3.0 
	 * @return string.
	 */
	public function comment_form_grid_class() {
		return ( Astra_Builder_Helper::apply_flex_based_css() ) ? 'ast-grid-common-col ast-width-lg-33 ast-width-md-4 ast-float' : 'ast-col-xs-12 ast-col-sm-12 ast-col-md-4 ast-col-lg-4';
	}

	/** 
	 * Removed grid layout classes and make common class for same style
	 *
	 * @since 3.3.0
	 * @return string.
	 */
	public function ast_grid_lg_12() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col' : 'ast-col-lg-12'; 
	}

	/** 
	 * Layout-4 grid css backward comaptibility.
	 *
	 * @return string.
	 */
	public function ast_layout_4_grid() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col ast-width-sm-25 ast-width-md-3 ast-float ast-full-width' : 'ast-col-lg-3 ast-col-md-3 ast-col-sm-12 ast-col-xs-12'; 
	}

	/** 
	 * Layout-2 grid css backward comaptibility.
	 *
	 * @return string.
	 */
	public function ast_layout_2_grid() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col ast-width-md-6 ast-full-width' : 'ast-col-lg-6 ast-col-md-6 ast-col-sm-12 ast-col-xs-12'; 
	}

	/** 
	 * Layout-1 grid css backward comaptibility.
	 *
	 * @return string.
	 */
	public function ast_layout_1_grid() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col' : 'ast-col-lg-12 ast-col-md-12 ast-col-sm-12 ast-col-xs-12'; 
	}

	/** 
	 * Layout-3 grid css backward comaptibility.
	 *
	 * @return string.
	 */
	public function ast_layout_3_grid() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col ast-width-md-4 ast-float ast-full-width' : 'ast-col-lg-4 ast-col-md-4 ast-col-sm-12 ast-col-xs-12'; 
	}

	/** 
	 * Layout-5 grid css backward comaptibility.
	 *
	 * @return string.
	 */
	public function ast_layout_5_grid() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col ast-width-md-16 ast-width-md-20 ast-float ast-full-width' : 'ast-col-lg-2 ast-col-md-2 ast-col-sm-12 ast-col-xs-12'; 
	}

	/** 
	 * Layout-6 grid css backward comaptibility.
	 *
	 * @return string.
	 */
	public function ast_layout_6_grid() {
		return Astra_Builder_Helper::apply_flex_based_css() ? 'ast-grid-common-col ast-width-md-6 ast-width-lg-50 ast-float ast-full-width' : 'ast-col-lg-6 ast-col-md-6 ast-col-sm-12 ast-col-xs-12';
	}

	/**
	 * Footer widget opening div.
	 * 
	 * @since 3.3.0
	 * @param array $args div attributes.
	 * @return array.
	 */
	public function footer_widget_div_open( $args ) {
		$args['open']  = '<div %s>';
		$args['attrs'] = array( 'class' => 'footer-widget-area-inner site-info-inner' );
		return $args;
	}

	/**
	 * Footer widget closing div.
	 * 
	 * @since 3.3.0
	 * @param array $args div attributes.
	 * @return array.
	 */
	public function footer_widget_div_close( $args ) {
		$args['close'] = '</div>';
		return $args;
	}

	/**
	 * Footer widget inner class.
	 *
	 * @param array $args attributes.
	 * @since 3.3.0
	 * @return string.
	 */
	public function footer_widget_area_inner( $args ) {
		if ( Astra_Builder_Helper::apply_flex_based_css() ) {
			$args['class'] = $args['class'] . ' footer-widget-area-inner';
		}
		return $args;
	}

	/**
	 * Header widget inner class.
	 *
	 * @param array $args Attributes.
	 * @since 3.3.0
	 * @return string.
	 */
	public function header_widget_area_inner( $args ) {
		if ( Astra_Builder_Helper::apply_flex_based_css() ) {
			$args['class'] = $args['class'] . ' header-widget-area-inner';
		}
		return $args;
	}

	/**
	 * Footer widget opening div.
	 * 
	 * @since 3.3.0
	 * @param array $args div attributes.
	 * @return array.
	 */
	public function header_widget_div_open( $args ) {
		$args['open']  = '<div %s>';
		$args['attrs'] = array( 'class' => 'header-widget-area-inner site-info-inner' );
		return $args;   
	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Markup();
