
window.addEventListener( 'load', function(e) {
	astra_onload_function();
});
  
function astra_onload_function() { 
	
	/* Do things after DOM has fully loaded */ 
	
	var astra_meta_box = document.querySelector( '#astra_settings_meta_box' );
	if( astra_meta_box != null ){
		
			document.querySelector('#site-content-layout').addEventListener('change',function( event ) {
			
				var body_class = document.querySelector('body');
		
				var content_Layout = document.getElementById('site-content-layout').value;
		
				if ( 'content-boxed-container' == content_Layout ) {
		
					body_class.classList.add('ast-separate-container');
					body_class.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-plain-container');
		
				} else if ( 'boxed-container' == content_Layout ) {
		
					body_class.classList.add('ast-separate-container' , 'ast-two-container');
					body_class.classList.remove('ast-page-builder-template' , 'ast-plain-container');
		
				} else if ( 'page-builder' == content_Layout ) {
		
					body_class.classList.add('ast-page-builder-template');
					body_class.classList.remove('ast-two-container' , 'ast-plain-container' , 'ast-separate-container');
		
				} else if ( 'plain-container' == content_Layout ) {
		
					body_class.classList.add('ast-plain-container');
					body_class.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-separate-container');
		
				}
			});

		


		var title_checkbox = document.getElementById('site-post-title');
		var title_block = document.querySelector('.editor-post-title__block');
		
		title_checkbox.addEventListener('change',function() {
					
			if( title_checkbox.checked ){	

				title_block.style.opacity = '0.2';
			}
			else {
				title_block.style.opacity = '1.0';
			}
		});
	}

  }