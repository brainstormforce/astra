( function( $, api ) {
    var $document = $( document );

    wp.customize.bind( 'preview-ready', function() {

        var defaultTarget = window.parent === window ? null : window.parent;
        $document.on(
            'click',
            '.site-header-focus-item .item-customizer-focus, .builder-item-focus .edit-row-action',
            function(e) {

                e.preventDefault();
                e.stopPropagation();
                var p = $( this ).closest( '.site-header-focus-item' );
                var section_id = p.attr( 'data-section' ) || '';
                if ( section_id ) {
                    if ( defaultTarget.wp.customize.section( section_id ) ) {
                        defaultTarget.wp.customize.section( section_id ).focus();
                    }
                }
            }
        );

        $document.on(
            'click',
            '.site-footer-focus-item .item-customizer-focus',
            function(e) {

                e.preventDefault();
                e.stopPropagation();
                var p = $( this ).closest( '.site-footer-focus-item' );
                var section_id = p.attr( 'data-section' ) || '';
                if ( section_id ) {
                    if ( defaultTarget.wp.customize.section( section_id ) ) {
                        defaultTarget.wp.customize.section( section_id ).focus();
                    }
                }
            }
        );

		/**
		 * Register partial refresh events at once asynchronously.
		 */
		wp.customize.preview.bind( 'active', function() {
			var partials = $.extend({}, astraCustomizer.dynamic_partial_options), key;
			var register_partial = async function () {
				for ( key in partials) {
					wp.customize.selectiveRefresh.partial.add(
						new wp.customize.selectiveRefresh.Partial(
							key,
							_.extend({params: partials[key]}, partials[key])
						)
					);
					await null;
				}
			}
			register_partial();
		});

    } );

    /**
     * Inline logo and title css only.
     */
    wp.customize( 'astra-settings[logo-title-inline]', function( value ) {
        value.bind( function( is_checked ) {
           jQuery('#masthead').toggleClass( 'ast-logo-title-inline', is_checked );
        } );
    } );

} )( jQuery, wp );

/**
 * Apply Advanced CSS for the element
 *
 * @param string section Section ID.
 * @param string selector Base Selector.
 */
function astra_builder_advanced_css( section, selector ) {

    var tablet_break_point    = astraBuilderPreview.tablet_break_point || 768,
		mobile_break_point    = astraBuilderPreview.mobile_break_point || 544;

    // Padding.
    wp.customize( 'astra-settings[' + section + '-padding]', function( value ) {
        value.bind( function( padding ) {

			if( ! padding.hasOwnProperty('desktop') ) {
				return
			}

            if(
                padding.desktop.bottom != '' || padding.desktop.top != '' || padding.desktop.left != '' || padding.desktop.right != '' ||
                padding.tablet.bottom != '' || padding.tablet.top != '' || padding.tablet.left != '' || padding.tablet.right != '' ||
                padding.mobile.bottom != '' || padding.mobile.top != '' || padding.mobile.left != '' || padding.mobile.right != ''
            ) {
                var dynamicStyle = '';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'padding-left: ' + padding['desktop']['left'] + padding['desktop-unit'] + ';';
                dynamicStyle += 'padding-right: ' + padding['desktop']['right'] + padding['desktop-unit'] + ';';
                dynamicStyle += 'padding-top: ' + padding['desktop']['top'] + padding['desktop-unit'] + ';';
                dynamicStyle += 'padding-bottom: ' + padding['desktop']['bottom'] + padding['desktop-unit'] + ';';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'padding-left: ' + padding['tablet']['left'] + padding['tablet-unit'] + ';';
                dynamicStyle += 'padding-right: ' + padding['tablet']['right'] + padding['tablet-unit'] + ';';
                dynamicStyle += 'padding-top: ' + padding['tablet']['top'] + padding['tablet-unit'] + ';';
                dynamicStyle += 'padding-bottom: ' + padding['tablet']['bottom'] + padding['tablet-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'padding-left: ' + padding['mobile']['left'] + padding['mobile-unit'] + ';';
                dynamicStyle += 'padding-right: ' + padding['mobile']['right'] + padding['mobile-unit'] + ';';
                dynamicStyle += 'padding-top: ' + padding['mobile']['top'] + padding['mobile-unit'] + ';';
                dynamicStyle += 'padding-bottom: ' + padding['mobile']['bottom'] + padding['mobile-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';
                astra_add_dynamic_css( section + '-padding-toggle-button', dynamicStyle );
            } else {
                astra_add_dynamic_css( section + '-padding-toggle-button', '' );
            }
        } );
    } );

    // Margin.
    wp.customize( 'astra-settings[' + section + '-margin]', function( value ) {
        value.bind( function( margin ) {

        	if( ! margin.hasOwnProperty('desktop') ) {
        		return
			}

            if(
                margin.desktop.bottom != '' || margin.desktop.top != '' || margin.desktop.left != '' || margin.desktop.right != '' ||
                margin.tablet.bottom != '' || margin.tablet.top != '' || margin.tablet.left != '' || margin.tablet.right != '' ||
                margin.mobile.bottom != '' || margin.mobile.top != '' || margin.mobile.left != '' || margin.mobile.right != ''
            ) {
                var dynamicStyle = '';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'margin-left: ' + margin['desktop']['left'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['desktop']['right'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['desktop']['top'] + margin['desktop-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['desktop']['bottom'] + margin['desktop-unit'] + ';';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'margin-left: ' + margin['tablet']['left'] + margin['tablet-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['tablet']['right'] + margin['tablet-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['tablet']['top'] + margin['tablet-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['tablet']['bottom'] + margin['tablet-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
                dynamicStyle += selector + ' {';
                dynamicStyle += 'margin-left: ' + margin['mobile']['left'] + margin['mobile-unit'] + ';';
                dynamicStyle += 'margin-right: ' + margin['mobile']['right'] + margin['mobile-unit'] + ';';
                dynamicStyle += 'margin-top: ' + margin['mobile']['top'] + margin['mobile-unit'] + ';';
                dynamicStyle += 'margin-bottom: ' + margin['mobile']['bottom'] + margin['mobile-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';
                astra_add_dynamic_css( section + '-margin-toggle-button', dynamicStyle );
            } else {
                astra_add_dynamic_css( section + '-margin-toggle-button', '' );
            }
        } );
    } );

}
// Single Post Content Width
wp.customize( 'astra-settings[blog-single-width]', function( value ) {
    value.bind( function( value ) {

        var single_post_max_width = wp.customize('astra-settings[blog-single-max-width]').get();

        var dynamicStyle = '';

        if ( 'custom' === value ) {

            dynamicStyle += '.single-post .site-content > .ast-container {';
            dynamicStyle += 'max-width: ' + single_post_max_width + 'px;';
            dynamicStyle += '} ';
        }
        astra_add_dynamic_css( 'blog-single-width', dynamicStyle );
    } );
} );

// Blog Post Content Width
wp.customize( 'astra-settings[blog-width]', function( value ) {
    value.bind( function( value ) {

        var blog_max_width = wp.customize('astra-settings[blog-max-width]').get();

        var dynamicStyle = '';

        if ( 'custom' === value ) {

            dynamicStyle += '.blog .site-content > .ast-container, .archive .site-content > .ast-container, .search .site-content > .ast-container {';
            dynamicStyle += 'max-width: ' + blog_max_width + 'px;';
            dynamicStyle += '} ';
        }
        astra_add_dynamic_css( 'blog-width', dynamicStyle );
    } );
} );

// Blog Post Content Width
wp.customize( 'astra-settings[edd-archive-grids]', function( value ) {
    value.bind( function( value ) {

        for ( var i = 1; i < 7; i++ ) {
            jQuery('body').removeClass( 'columns-' + i );
            jQuery('body').removeClass( 'tablet-columns-' + i );
            jQuery('body').removeClass( 'mobile-columns-' + i );
        }

        if ( jQuery('body').hasClass( 'ast-edd-archive-page' ) ) {

            jQuery('body').addClass( 'columns-' + value['desktop'] );
            jQuery('body').addClass( 'tablet-columns-' + value['tablet'] );
            jQuery('body').addClass( 'mobile-columns-' + value['mobile'] );
        }
    } );
} );


// Blog Post Content Width
wp.customize( 'astra-settings[edd-archive-width]', function( value ) {
    value.bind( function( value ) {

        var edd_archive_max_width = wp.customize('astra-settings[edd-archive-max-width]').get();
        edd_archive_max_width = ( 'custom' === value ) ? edd_archive_max_width : edd_archive_max_width + 40;

        var dynamicStyle = '';
        dynamicStyle += '.ast-edd-archive-page .site-content > .ast-container {';
        dynamicStyle += 'max-width: ' + edd_archive_max_width + 'px;';
        dynamicStyle += '} ';

        astra_add_dynamic_css( 'edd-archive-width', dynamicStyle );
    } );
} );

