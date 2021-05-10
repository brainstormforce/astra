<?php
/**
 * Container Layout - Dynamic CSS
 *
 * @package astra
 * @since 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Container Layout - Dynamic CSS.
 *
 * @since 3.3.0
 */
function astra_container_layout_css() {
	$container_layout = astra_get_content_layout();
   
	$page_container_css = '';
   
	if ( 'page-builder' === $container_layout ) {

		$page_container_css = '
        .ast-page-builder-template .hentry {
            margin: 0;
          }
          .ast-page-builder-template .site-content > .ast-container {
            max-width: 100%;
            padding: 0;
          }
          .ast-page-builder-template .site-content #primary {
            padding: 0;
            margin: 0;
          }
          .ast-page-builder-template .no-results {
            text-align: center;
            margin: 4em auto;
          }
          .ast-page-builder-template .ast-pagination {
            padding: 2em;
          }
         
          .ast-page-builder-template .entry-header.ast-no-title.ast-no-thumbnail {
            margin-top: 0;
          }
          .ast-page-builder-template .entry-header.ast-header-without-markup {
            margin-top: 0;
            margin-bottom: 0;
          }
          
          .ast-page-builder-template .entry-header.ast-no-title.ast-no-meta {
            margin-bottom: 0;
          }
          .ast-page-builder-template.single .post-navigation {
            padding-bottom: 2em;
          }
          .ast-page-builder-template.single-post .site-content > .ast-container {
            max-width: 100%;
          }
          .ast-page-builder-template.single-post .site-content > .ast-container {
            max-width: 100%;
          }';

		if ( is_rtl() ) {

			$page_container_css .= '
            .ast-page-builder-template .entry-header {
                margin-top: 4em;
                margin-right: auto;
                margin-left: auto;
                padding-right: 20px;
                padding-left: 20px;
            }
            .ast-page-builder-template .ast-archive-description {
                margin-top: 4em;
                margin-right: auto;
                margin-left: auto;
                padding-right: 20px;
                padding-left: 20px;
            }
            .ast-page-builder-template.ast-no-sidebar .entry-content .alignwide {
                margin-right: 0;
                margin-left: 0;
            }
            .single.ast-page-builder-template .entry-header {
                padding-right: 20px;
                padding-left: 20px;
            }
            .ast-page-builder-template.ast-no-sidebar .entry-content .alignwide {
                margin-right: 0;
                margin-left: 0;
            }';

		} else {
			$page_container_css .= '
            .ast-page-builder-template .entry-header {
                margin-top: 4em;
                margin-left: auto;
                margin-right: auto;
                padding-left: 20px;
                padding-right: 20px;
            }
            .ast-page-builder-template .ast-archive-description {
                margin-top: 4em;
                margin-left: auto;
                margin-right: auto;
                padding-left: 20px;
                padding-right: 20px;
            }
            .ast-page-builder-template.ast-no-sidebar .entry-content .alignwide {
                margin-left: 0;
                margin-right: 0;
            }
            .single.ast-page-builder-template .entry-header {
                padding-left: 20px;
                padding-right: 20px;
            }
            .ast-page-builder-template.ast-no-sidebar .entry-content .alignwide {
                margin-left: 0;
                margin-right: 0;
            }';  
		}
		return Astra_Enqueue_Scripts::trim_css( $page_container_css );
	}
	return $page_container_css;
}
