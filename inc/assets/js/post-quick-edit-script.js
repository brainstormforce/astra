/*
 * Post Bulk Edit Script
 * Hooks into the inline post editor functionality to extend it to our custom metadata
 */

jQuery(document).ready(function($){

    //Prepopulating our quick-edit post info
    var $inline_editor = inlineEditPost.edit;
    inlineEditPost.edit = function(id){

        //call old copy 
        $inline_editor.apply( this, arguments);

        //our custom functionality below
        var post_id = 0;
        if( typeof(id) == 'object'){
            post_id = parseInt(this.getId(id));
        }

        //if we have our post
        if(post_id != 0){

            //find our row
            $row = $('#edit-' + post_id);

            //sidebar_layout
            $sidebar_layout = $('#site-sidebar-layout-' + post_id);
            $sidebar_layout_value = $sidebar_layout.text();
            $row.find('#site-sidebar-layout').val($sidebar_layout_value);
            $row.find('#site-sidebar-layout').children('[value="' + $sidebar_layout_value + '"]').attr('selected', true);
        }
    }


    jQuery( "#bulk_edit" ).on( "click", function(e) {

        // e.preventDefault();


        var bulk_row = jQuery( "#bulk-edit" );
        var post_ids = new Array();
        bulk_row.find( "#bulk-titles" ).children().each( function() {
            post_ids.push( jQuery( this ).attr( "id" ).replace( /^(ttle)/i, "" ) );
        });

        var site_sidebar_layout         = bulk_row.find('[name="site-sidebar-layout"]').val();
        var site_content_layout         = bulk_row.find('[name="site-content-layout"]').val();
        var site_post_title             = ( bulk_row.find('[name="site-post-title"]').prop('checked') ) ? bulk_row.find('[name="site-post-title"]').val() : '';
        var ast_main_header_display     = ( bulk_row.find('[name="ast-main-header-display"]').prop('checked') ) ? bulk_row.find('[name="ast-main-header-display"]').val() : '';
        var ast_featured_img            = ( bulk_row.find('[name="ast-featured-img"]').prop('checked') ) ? bulk_row.find('[name="ast-featured-img"]').val() : '';
        

        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            async: false,
            cache: false,
            data: {
                action: "astra_save_post_bulk_edit",
                post_ids: post_ids,
                site_sidebar_layout: site_sidebar_layout,
                site_content_layout: site_content_layout,
                site_post_title: site_post_title,
                ast_main_header_display: ast_main_header_display,
                ast_featured_img: ast_featured_img,
            }
        });
    });
});