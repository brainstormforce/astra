
window.addEventListener( 'load', function(e) {
	astra_onload_function();
});

function astra_onload_function() {

	/* Do things after DOM has fully loaded */

	var astraMetaBox = document.querySelector( '#astra_settings_meta_box' );
	if( astraMetaBox != null ){

			document.querySelector('#site-content-layout').addEventListener('change',function( event ) {

				var bodyClass = document.querySelector('body'),
					contentLayout = document.getElementById('site-content-layout').value;
				switch( contentLayout ) {
					case 'content-boxed-container':
						bodyClass.classList.add('ast-separate-container');
						bodyClass.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-plain-container');
					break;
					case 'boxed-container':
						bodyClass.classList.add('ast-separate-container' , 'ast-two-container');
						bodyClass.classList.remove('ast-page-builder-template' , 'ast-plain-container');
					break;
					case 'page-builder':
						bodyClass.classList.add('ast-page-builder-template');
						bodyClass.classList.remove('ast-two-container' , 'ast-plain-container' , 'ast-separate-container');
					break;
					case 'plain-container':
						bodyClass.classList.add('ast-plain-container');
						bodyClass.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-separate-container');
					break;
				}
			});

		var titleCheckbox = document.getElementById('site-post-title'),
			titleBlock = document.querySelector('.editor-post-title__block');

		titleCheckbox.addEventListener('change',function() {

			if( titleCheckbox.checked ){
				titleBlock.style.opacity = '0.2';
			} else {
				titleBlock.style.opacity = '1.0';
			}
		});
	}

  }
