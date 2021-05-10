<?php
/**
 * Pagination - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'astra_dynamic_theme_css', 'astra_pagination_css', 11 );

/**
 * Pagination - Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @return String Generated dynamic CSS for Pagination.
 *
 * @since 3.3.0
 */
function astra_pagination_css( $dynamic_css ) {

	if ( is_astra_pagination_enabled() || false !== apply_filters( 'astra_enable_pagination_css', false ) ) {
		
		$theme_color      = astra_get_option( 'theme-color' );
		$link_color       = astra_get_option( 'link-color', $theme_color );
		$link_hover_color = astra_get_option( 'link-h-color' );
		
		$pagination_color_output = array(
			'.ast-pagination .next.page-numbers' => array(
				'display' => 'inherit',
				'float'   => 'none',
			),
			'.ast-pagination a'                  => array(
				'color' => esc_attr( $link_color ),
			),
			'.ast-pagination a:hover, .ast-pagination a:focus, .ast-pagination > span:hover:not(.dots), .ast-pagination > span.current' => array(
				'color' => esc_attr( $link_hover_color ),
			),
		);
		$dynamic_css            .= astra_parse_css( $pagination_color_output );
		
		$pagination_static_css = '
            .ast-pagination .prev.page-numbers,
            .ast-pagination .next.page-numbers {
                padding: 0 1.5em;
                height: 2.33333em;
                line-height: calc(2.33333em - 3px);
            }
            .ast-pagination {
                display: inline-block;
                width: 100%;
                padding-top: 2em;
                text-align: center;
            }
            .ast-pagination .page-numbers {
                display: inline-block;
                width: 2.33333em;
                height: 2.33333em;
                font-size: 16px;
                font-size: 1.06666rem;
                line-height: calc(2.33333em - 3px);
            }
            .ast-pagination .nav-links {
                display: inline-block;
                width: 100%;
            }
            @media (max-width: 420px) {
                .ast-pagination .prev.page-numbers,
                .ast-pagination .next.page-numbers {
                width: 100%;
                text-align: center;
                margin: 0;
                }
            }
            .ast-pagination .prev,
            .ast-pagination .prev:visited,
            .ast-pagination .prev:focus,
            .ast-pagination .next,
            .ast-pagination .next:visited,
            .ast-pagination .next:focus {
                display: inline-block;
                width: auto;
            }
            .ast-page-builder-template .ast-pagination {
                padding: 2em;
            }';

		if ( ! Astra_Builder_Helper::apply_flex_based_css() ) {
			$pagination_static_css .= '
                .ast-pagination .prev.page-numbers.dots, .ast-pagination .prev.page-numbers.dots:hover, .ast-pagination .prev.page-numbers.dots:focus,
                .ast-pagination .prev.page-numbers:visited.dots,
                .ast-pagination .prev.page-numbers:visited.dots:hover,
                .ast-pagination .prev.page-numbers:visited.dots:focus,
                .ast-pagination .prev.page-numbers:focus.dots,
                .ast-pagination .prev.page-numbers:focus.dots:hover,
                .ast-pagination .prev.page-numbers:focus.dots:focus,
                .ast-pagination .next.page-numbers.dots,
                .ast-pagination .next.page-numbers.dots:hover,
                .ast-pagination .next.page-numbers.dots:focus,
                .ast-pagination .next.page-numbers:visited.dots,
                .ast-pagination .next.page-numbers:visited.dots:hover,
                .ast-pagination .next.page-numbers:visited.dots:focus,
                .ast-pagination .next.page-numbers:focus.dots,
                .ast-pagination .next.page-numbers:focus.dots:hover,
                .ast-pagination .next.page-numbers:focus.dots:focus {
                    border: 2px solid #eaeaea;
                    background: transparent;
                }
                
                .ast-pagination .prev.page-numbers.dots,
                .ast-pagination .prev.page-numbers:visited.dots,
                .ast-pagination .prev.page-numbers:focus.dots,
                .ast-pagination .next.page-numbers.dots,
                .ast-pagination .next.page-numbers:visited.dots,
                .ast-pagination .next.page-numbers:focus.dots {
                    cursor: default;
                }';
		}
			
		if ( is_rtl() ) {
			$pagination_static_css .= '
            @media (min-width: 993px) {
                .ast-pagination {
                    padding-right: 3.33333em;
                    padding-left: 3.33333em;
                }
                .ast-pagination .next.page-numbers {
                    float: left;
                    text-align: left;
                } 
                @media (max-width: 768px) {
                    .ast-pagination .next.page-numbers .page-navigation {
                        padding-left: 0;
                    }
                }';
					
			if ( ! Astra_Builder_Helper::apply_flex_based_css() ) {
				$pagination_static_css .= '
                    @media (min-width: 769px) {
                    .ast-pagination .prev.page-numbers.next,
                    .ast-pagination .prev.page-numbers:visited.next,
                    .ast-pagination .prev.page-numbers:focus.next,
                    .ast-pagination .next.page-numbers.next,
                    .ast-pagination .next.page-numbers:visited.next,
                    .ast-pagination .next.page-numbers:focus.next {
                        margin-left: 0;
                    }
                }';
			}           
		} else {
			$pagination_static_css .= '
                @media (min-width: 993px) {
                    .ast-pagination {
                        padding-left: 3.33333em;
                        padding-right: 3.33333em;
                    }
                }
                .ast-pagination .next.page-numbers {
                    float: right;
                }
                    
                @media (max-width: 768px) {
                    .ast-pagination .next.page-numbers .page-navigation {
                        padding-right: 0;
                    }
                }';

			if ( ! Astra_Builder_Helper::apply_flex_based_css() ) {
				$pagination_static_css .= '
                    @media (min-width: 769px) {
                        .ast-pagination .prev.page-numbers.next,
                        .ast-pagination .prev.page-numbers:visited.next,
                        .ast-pagination .prev.page-numbers:focus.next,
                        .ast-pagination .next.page-numbers.next,
                        .ast-pagination .next.page-numbers:visited.next,
                        .ast-pagination .next.page-numbers:focus.next {
                          margin-right: 0;
                        }
                    }';
			}           
		}
		return $dynamic_css .= Astra_Enqueue_Scripts::trim_css( $pagination_static_css );
	}
	return $dynamic_css;
	
}
