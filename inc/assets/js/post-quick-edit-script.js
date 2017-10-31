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

        var form = bulk_row.closest('form');
        var post_data = form.serialize();

        post_data += '&action=astra_save_post_bulk_edit';

        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            async: false,
            cache: false,
            data: post_data,
            type: 'POST',
            dataType: 'json',
        });
    });
});