<?php
/**
 * Comments - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'astra_dynamic_theme_css', 'astra_comments_css', 11 );

/**
 * Comments - Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @return String Generated dynamic CSS for Pagination.
 *
 * @since 3.2.0
 */
function astra_comments_css( $dynamic_css ) {

	if ( is_current_post_comment_enabled() || 0 < get_comments_number() ) {

		$body_font_size = astra_get_option( 'font-size-body' );
		$theme_color    = astra_get_option( 'theme-color' );
		$link_color     = astra_get_option( 'link-color', $theme_color );
		
		if ( is_array( $body_font_size ) ) {
			$body_font_size_desktop = ( isset( $body_font_size['desktop'] ) && '' != $body_font_size['desktop'] ) ? $body_font_size['desktop'] : 15;
		} else {
			$body_font_size_desktop = ( '' != $body_font_size ) ? $body_font_size : 15;
		}
		
		$desktop_comment_global = array(
			'.comment-reply-title'                         => array(
				'font-size' => astra_get_font_css_value( (int) $body_font_size_desktop * 1.66666 ),
			),
			// Single Post Meta.
			'.ast-comment-meta'                            => array(
				'line-height' => '1.666666667',
				'color'       => esc_attr( $link_color ),
				'font-size'   => astra_get_font_css_value( (int) $body_font_size_desktop * 0.8571428571 ),
			),
			'.ast-comment-list #cancel-comment-reply-link' => array(
				'font-size' => astra_responsive_font( $body_font_size, 'desktop' ),
			),                  
		);
		$dynamic_css .= astra_parse_css( $desktop_comment_global );

		$single_post_comment_css = '.comments-count-wrapper {
      padding: 2em 0;
      }

      .comments-count-wrapper .comments-title {
      font-weight: normal;
      word-wrap: break-word;
      }

      .ast-comment-list {
      margin: 0;
      word-wrap: break-word;
      padding-bottom: 0.5em;
      list-style: none;
      }

      .ast-comment-list li {
      list-style: none;
      }

      .ast-comment-list li.depth-1 .ast-comment,
      .ast-comment-list li.depth-2 .ast-comment {
      border-bottom: 1px solid #eeeeee;
      }

      .ast-comment-list .comment-respond {
      padding: 1em 0;
      border-bottom: 1px solid #eeeeee;
      }

      .ast-comment-list .comment-respond .comment-reply-title {
      margin-top: 0;
      padding-top: 0;
      }

      .ast-comment-list .comment-respond p {
      margin-bottom: .5em;
      }

      .ast-comment-list .ast-comment-edit-reply-wrap {
      -js-display: flex;
      display: flex;
      justify-content: flex-end;
      }

      .ast-comment-list .ast-edit-link {
      flex: 1;
      }

      .ast-comment-list .comment-awaiting-moderation {
      margin-bottom: 0;
      }

      .ast-comment {
      padding: 1em 0;
      }
      .ast-comment-avatar-wrap img {
      border-radius: 50%;
      }
      .ast-comment-content {
      clear: both;
      }

      .ast-comment-cite-wrap {
      text-align: left;
      }

      .ast-comment-cite-wrap cite {
      font-style: normal;
      }

      .comment-reply-title {
      padding-top: 1em;
      font-weight: normal;
      line-height: 1.65;
      }

      .ast-comment-meta {
      margin-bottom: 0.5em;
      }
      .comments-area {
      border-top: 1px solid #eeeeee;
      margin-top: 2em;
      }

      .comments-area .comment-form-comment {
      width: 100%;
      border: none;
      margin: 0;
      padding: 0;
      }

      .comments-area .comment-notes,
      .comments-area .comment-textarea,
      .comments-area .form-allowed-tags {
      margin-bottom: 1.5em;
      }

      .comments-area .form-submit {
      margin-bottom: 0;
      }

      .comments-area textarea#comment,
      .comments-area .ast-comment-formwrap input[type="text"] {
      width: 100%;
      border-radius: 0;
      vertical-align: middle;
      margin-bottom: 10px;
      }
      .comments-area .no-comments {
      margin-top: 0.5em;
      margin-bottom: 0.5em;
      }
      .comments-area p.logged-in-as {
      margin-bottom: 1em;
      }
      .ast-separate-container .comments-count-wrapper {
      background-color: #fff;
      padding: 2em 6.67em 0;
      }

      @media (max-width: 1200px) {
      .ast-separate-container .comments-count-wrapper {
        padding: 2em 3.34em;
      }
      }

      .ast-separate-container .comments-area {
      border-top: 0;
      }

      .ast-separate-container .ast-comment-list {
      padding-bottom: 0;
      }

      .ast-separate-container .ast-comment-list li {
      background-color: #fff;
      }

      .ast-separate-container .ast-comment-list li.depth-1 {
      padding: 4em 6.67em;
      margin-bottom: 2em;
      }

      @media (max-width: 1200px) {
      .ast-separate-container .ast-comment-list li.depth-1 {
        padding: 3em 3.34em;
      }
      }

      .ast-separate-container .ast-comment-list li.depth-1 .children li {
      padding-bottom: 0;
      padding-top: 0;
      margin-bottom: 0;
      }

      .ast-separate-container .ast-comment-list li.depth-1 .ast-comment,
      .ast-separate-container .ast-comment-list li.depth-2 .ast-comment {
      border-bottom: 0;
      }

      .ast-separate-container .ast-comment-list .comment-respond {
      padding-top: 0;
      padding-bottom: 1em;
      background-color: transparent;
      }

      .ast-separate-container .ast-comment-list .pingback p {
      margin-bottom: 0;
      }

      .ast-separate-container .ast-comment-list .bypostauthor {
      padding: 2em;
      margin-bottom: 1em;
      }

      .ast-separate-container .ast-comment-list .bypostauthor li {
      background: transparent;
      margin-bottom: 0;
      padding: 0 0 0 2em;
      }

      .ast-separate-container .comment-respond {
      background-color: #fff;
      padding: 4em 6.67em;
      border-bottom: 0;
      }

      @media (max-width: 1200px) {
      .ast-separate-container .comment-respond {
        padding: 3em 2.34em;
      }
      }

      .ast-separate-container .comment-reply-title {
      padding-top: 0;
      }';
		if ( is_rtl() ) {
			$single_post_comment_css .= '
      .ast-comment-list .children {
        margin-right: 2em;
      }
    
      @media (max-width: 992px) {
        .ast-comment-list .children {
          margin-right: 1em;
        }
      }
  
      .ast-comment-list #cancel-comment-reply-link {
        white-space: nowrap;
        font-size: 15px;
        font-size: 1rem;
        margin-right: 1em;
      }
        
      .ast-comment-avatar-wrap {
        float: right;
        clear: left;
        margin-left: 1.33333em;
      }
      .ast-comment-meta-wrap {
        float: right;
        clear: left;
        padding: 0 0 1.33333em;
      }
      .ast-comment-time .timendate,
        .ast-comment-time .reply {
        margin-left: 0.5em;
      }
      .comments-area #wp-comment-cookies-consent {
        margin-left: 10px;
      }
      .ast-page-builder-template .comments-area {
        padding-right: 20px;
        padding-left: 20px;
        margin-top: 2em;
        margin-bottom: 2em;
      }
      .ast-separate-container .ast-comment-list .bypostauthor .bypostauthor {
        background: transparent;
        margin-bottom: 0;
        padding-left: 0;
        padding-bottom: 0;
        padding-top: 0;
      }';
		} else {
			$single_post_comment_css .= '
      .ast-comment-list .children {
        margin-left: 2em;
      }
    
      @media (max-width: 992px) {
        .ast-comment-list .children {
          margin-left: 1em;
        }
      }
    
      .ast-comment-list #cancel-comment-reply-link {
        white-space: nowrap;
        font-size: 15px;
        font-size: 1rem;
        margin-left: 1em;
      }
        
      .ast-comment-avatar-wrap {
        float: left;
        clear: right;
        margin-right: 1.33333em;
      }
      .ast-comment-meta-wrap {
        float: left;
        clear: right;
        padding: 0 0 1.33333em;
      }
      .ast-comment-time .timendate,
        .ast-comment-time .reply {
        margin-right: 0.5em;
      }
      .comments-area #wp-comment-cookies-consent {
        margin-right: 10px;
      }
      .ast-page-builder-template .comments-area {
        padding-left: 20px;
        padding-right: 20px;
        margin-top: 2em;
        margin-bottom: 2em;
      }
      .ast-separate-container .ast-comment-list .bypostauthor .bypostauthor {
        background: transparent;
        margin-bottom: 0;
        padding-right: 0;
        padding-bottom: 0;
        padding-top: 0;
      }';
		}

		$dynamic_css .= Astra_Enqueue_Scripts::trim_css( $single_post_comment_css );

		$static_layout_css_min_comment = array(
			'.ast-separate-container .ast-comment-list li .comment-respond' => array(
				'padding-left'  => '2.66666em',
				'padding-right' => '2.66666em',
			),
		);
		$dynamic_css                  .= astra_parse_css( $static_layout_css_min_comment, astra_get_tablet_breakpoint( '', '1' ) );

		$global_button_comment_mobile = array(
			'.ast-separate-container .comments-count-wrapper' => array(
				'padding' => '1.5em 1em',
			),
			'.ast-separate-container .ast-comment-list li.depth-1' => array(
				'padding'       => '1.5em 1em',
				'margin-bottom' => '1.5em',
			),
			'.ast-separate-container .ast-comment-list .bypostauthor' => array(
				'padding' => '.5em',
			),
			'.ast-separate-container .comment-respond'     => array(
				'padding' => '1.5em 1em',
			),
			// Single Post Meta.
			'.ast-comment-meta'                            => array(
				'font-size' => ! empty( $body_font_size['mobile'] ) ? astra_get_font_css_value( (int) $body_font_size['mobile'] * 0.8571428571, 'px', 'mobile' ) : '',
			),
			'.comment-reply-title'                         => array(
				'font-size' => ! empty( $body_font_size['mobile'] ) ? astra_get_font_css_value( (int) $body_font_size['mobile'] * 1.66666, 'px', 'mobile' ) : '',
			),
			'.ast-comment-list #cancel-comment-reply-link' => array(
				'font-size' => astra_responsive_font( $body_font_size, 'mobile' ),
			),
		);

		$dynamic_css .= astra_parse_css( $global_button_comment_mobile, '', astra_get_mobile_breakpoint() );

		$global_button_comment_tablet = array(
			'.ast-comment-avatar-wrap img'                 => array(
				'max-width' => '2.5em',
			),
			'.comments-area'                               => array(
				'margin-top' => '1.5em',
			),
			'.ast-separate-container .comments-count-wrapper' => array(
				'padding' => '2em 2.14em',
			),
			'.ast-separate-container .ast-comment-list li.depth-1' => array(
				'padding' => '1.5em 2.14em',
			),
			'.ast-separate-container .comment-respond'     => array(
				'padding' => '2em 2.14em',
			),
			// Single Post Meta.
			'.ast-comment-meta'                            => array(
				'font-size' => ! empty( $body_font_size['tablet'] ) ? astra_get_font_css_value( (int) $body_font_size['tablet'] * 0.8571428571, 'px', 'tablet' ) : '',
			),
			'.comment-reply-title'                         => array(
				'font-size' => ! empty( $body_font_size['tablet'] ) ? astra_get_font_css_value( (int) $body_font_size['tablet'] * 1.66666, 'px', 'tablet' ) : '',
			),
			'.ast-comment-list #cancel-comment-reply-link' => array(
				'font-size' => astra_responsive_font( $body_font_size, 'tablet' ),
			),

		);      
		
		$dynamic_css .= astra_parse_css( $global_button_comment_tablet, '', astra_get_tablet_breakpoint() );
	
		if ( is_rtl() ) {
			$global_button_tablet_lang_direction_css = array(
				'.ast-comment-avatar-wrap' => array(
					'margin-left' => '0.5em',
				),
			);
		} else {
			$global_button_tablet_lang_direction_css = array(
				'.ast-comment-avatar-wrap' => array(
					'margin-right' => '0.5em',
				),
			);
		}
		return $dynamic_css .= astra_parse_css( $global_button_tablet_lang_direction_css, '', astra_get_tablet_breakpoint() );   
	}
	return $dynamic_css;
}
