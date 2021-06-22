(function ($, api) {

	api.bind('ready', function () {

		sessionStorage.removeItem('astPartialContentRendered')

		/**
		 * Trigger on Above header column or layout change.
		 */
		api('astra-settings[hba-footer-column]', function (value) {
			value.bind(function (columns) {

				let event = new CustomEvent(
					'AstraBuilderChangeRowLayout', {
						'detail': {
							'columns': columns,
							'layout': api.value('astra-settings[hba-footer-layout]').get(),
							'type': 'above'
						}
					});
				document.dispatchEvent(event);
			});
		});

		/**
		 * Trigger on Primary header column or layout change.
		 */
		api('astra-settings[hb-footer-column]', function (value) {
			value.bind(function (columns) {

				let event = new CustomEvent(
					'AstraBuilderChangeRowLayout', {
						'detail': {
							'columns': columns,
							'layout': api.value('astra-settings[hb-footer-layout]').get(),
							'type': 'primary'
						}
					});
				document.dispatchEvent(event);
			});
		});

		/**
		 * Trigger on Below header column or layout change.
		 */
		api('astra-settings[hbb-footer-column]', function (value) {
			value.bind(function (columns) {

				let event = new CustomEvent(
					'AstraBuilderChangeRowLayout', {
						'detail': {
							'columns': columns,
							'layout': api.value('astra-settings[hbb-footer-layout]').get(),
							'type': 'below'
						}
					});
				document.dispatchEvent(event);
			});
		});


		/**
		 * Trigger on different-mobile-logo change.
		 */
		api('astra-settings[different-mobile-logo]', function (value) {
			value.bind(function (checked) {

				let ctrl = api.control('astra-settings[mobile-header-logo]');
				if( ! checked && ctrl ) {
					ctrl.container.find('.remove-button').click();
				}
			});
		});

		/**
		 * Trigger on ast-header-responsive-logo-width change.
		 */
		 api('astra-settings[ast-header-responsive-logo-width]', function (value) {
			value.bind(function (currVal) {

				const customizer_preview_container =  document.getElementById('customize-preview');
				let iframe 						   = customizer_preview_container.getElementsByTagName('iframe')[0];
				let htmlContent 				   = iframe.contentDocument || iframe.contentWindow.document;

				setTimeout(function () {
					if( null !== htmlContent.querySelector('.astra-logo-svg:not(.sticky-custom-logo .astra-logo-svg, .transparent-custom-logo .astra-logo-svg, .advanced-header-logo .astra-logo-svg)') ) {
						let existingValues 	    = api('astra-settings[ast-header-responsive-logo-width]').get();
						let desktopHeight       = htmlContent.querySelector('#ast-desktop-header .astra-logo-svg:not(.sticky-custom-logo .astra-logo-svg, .transparent-custom-logo .astra-logo-svg, .advanced-header-logo .astra-logo-svg)').clientHeight;
						let mobileTabletHeight  = htmlContent.querySelector('#ast-mobile-header .astra-logo-svg:not(.sticky-custom-logo .astra-logo-svg, .transparent-custom-logo .astra-logo-svg, .advanced-header-logo .astra-logo-svg)').clientHeight;
						let selectedDevice = wp.customize.previewedDevice.get();
						switch (selectedDevice) {
							case 'desktop':
								existingValues['desktop-svg-height'] = ( desktopHeight > 0 ) ? desktopHeight : '';
								break;
							case 'tablet':
								existingValues['tablet-svg-height']  = ( mobileTabletHeight > 0) ? mobileTabletHeight : '';
								break;
							case 'mobile':
								existingValues['mobile-svg-height']  = ( mobileTabletHeight > 0 ) ? mobileTabletHeight : '' ;
								break;
							default:
								break;
						}
						api('astra-settings[ast-header-responsive-logo-width]').set( existingValues );
					}
				}, 250);
			});
		});

		/**
		 * Trigger on transparent-header-logo-width change.
		 */
		 api('astra-settings[transparent-header-logo-width]', function (value) {
			value.bind(function (currVal) {

				const customizer_preview_container =  document.getElementById('customize-preview');
				let iframe 						 = customizer_preview_container.getElementsByTagName('iframe')[0];
				let htmlContent 				 = iframe.contentDocument || iframe.contentWindow.document;
				
				setTimeout(function () {
					if( null !== htmlContent.querySelector('.transparent-custom-logo .astra-logo-svg') ) {
						let existingValues 	    = api('astra-settings[transparent-header-logo-width]').get();
						let desktopHeight       = htmlContent.querySelector('#ast-desktop-header .transparent-custom-logo .astra-logo-svg').clientHeight;
						let mobileTabletHeight  = htmlContent.querySelector('#ast-mobile-header .transparent-custom-logo .astra-logo-svg').clientHeight;
						let selectedDevice = wp.customize.previewedDevice.get();
						switch (selectedDevice) {
							case 'desktop':
								existingValues['desktop-svg-height'] = ( desktopHeight > 0 ) ? desktopHeight : '';
								break;
							case 'tablet':
								existingValues['tablet-svg-height']  = ( mobileTabletHeight > 0 ) ? mobileTabletHeight : '';
								break;
							case 'mobile':
								existingValues['mobile-svg-height']  = ( mobileTabletHeight > 0 ) ? mobileTabletHeight : '';
								break;
							default:
								break;
						}
						api('astra-settings[transparent-header-logo-width]').set( existingValues );
					}
				}, 250);	
			});
		});

		/**
		 * Pass data to previewer when device changed.
		 */

		api.previewedDevice.bind(function(new_device, old_device) {

			api.previewer.send( 'astPreviewDeviceChanged', { 'device' : new_device } );

			let partialRendered = sessionStorage.getItem('astPartialContentRendered'),
				isCustomizerSaved = api.state('saved').get();

			if( ! partialRendered || isCustomizerSaved ) {
				return ;
			}

			let id = ( 'desktop' === new_device ) ? 'astra-settings[header-desktop-items]' : 'astra-settings[header-mobile-items]';

			let api_id = api(id);

			if( 'undefined' == typeof api_id ) {
				return ;
			}

			api_id.set( { ...api_id.get(), ...[], flag: !api_id.get().flag } );

		});

		/**
		 * Pass data to previewer when palette is modified.
		 */
		 api('astra-settings[global-color-palette]', function (value) {
			value.bind(function (palette) {

				let paletteUpdateEvent = new CustomEvent(
					'AstPaletteUpdated', {
						'detail': {
							'data': palette,
						}
					});

				document.dispatchEvent(paletteUpdateEvent);
			});
		});

	});

})(jQuery, wp.customize);
