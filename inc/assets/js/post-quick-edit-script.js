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

});