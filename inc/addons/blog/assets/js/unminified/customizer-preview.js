/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra Addon
 * @since  x.x.x
 */

 ( function( $ ) {

    // Space Between Posts.
    wp.customize( 'astra-settings[blog-space-bet-posts]', function( value ) {
        value.bind( function( value ) {

            if( ! astBlogGrid.apply_grid_based_css ){
                if ( value ) {
                    jQuery( '.ast-archive-post' ).addClass('ast-separate-posts');
                } else {
                    jQuery( '.ast-archive-post' ).removeClass('ast-separate-posts');
                }
            }else{
                if ( value ) {
                    jQuery( '.ast-blog-grid' ).addClass('ast-separate-posts');
                } else {
                    jQuery( '.ast-blog-grid' ).removeClass('ast-separate-posts');
                }
            }
        } );
    } );

    if ( jQuery( 'body' ).hasClass( 'ast-blog-grid-1' ) ) {
		astra_responsive_spacing( 'astra-settings[blog-post-inside-spacing]', '.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post', 'padding', ['top', 'right', 'bottom', 'left' ] );
    }else{
        // Blog Grid Inside Spacing.
        astra_responsive_spacing( 'astra-settings[blog-post-inside-spacing]', '.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3', 'padding', ['top', 'right', 'bottom', 'left' ] );
        wp.customize( 'astra-settings[blog-post-inside-spacing]', function( setting ) {
            setting.bind( function( padding ) { 

                if ( padding.desktop.top  || padding.desktop.left || padding.desktop.right || padding.tablet.top || padding.tablet.left || padding.tablet.right || padding.mobile.top || padding.mobile.left || padding.mobile.right ) {
                    var dynamicStyle =  '.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on { margin-top: -' + padding['desktop']['top'] + padding['desktop-unit'] + ';} .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on{ margin-left: -' + padding['desktop']['left'] + padding['desktop-unit'] + '; margin-right: -' + padding['desktop']['right'] + padding['desktop-unit'] + ';}';
                    dynamicStyle +=  '@media (max-width: 768px) { .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on{ margin-top: -' + padding['tablet']['top'] + padding['tablet-unit'] + ';} .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on{ margin-left: -' + padding['tablet']['left'] + padding['tablet-unit'] + '; margin-right: -' + padding['tablet']['right'] + padding['tablet-unit'] + ';} }';
                    dynamicStyle +=  '@media (max-width: 544px) { .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on{ margin-top: -' + padding['mobile']['top'] + padding['mobile-unit'] + ';} .ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content.post-thumb, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on{ margin-left: -' + padding['mobile']['left'] + padding['mobile-unit'] + '; margin-right: -' + padding['mobile']['right'] + padding['mobile-unit'] + ';} }';
                    astra_add_dynamic_css( 'blog-post-inside-spacing', dynamicStyle );
                } else {
                    wp.customize.preview.send( 'refresh' );
                }
            } );
        } );
    }    
} )( jQuery );