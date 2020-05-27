/**
 * Install Starter Templates
 *
 *
 * @since 1.2.4
 */

(function($){

	AstraThemeAdmin = {

		init: function()
		{
			this._bind();
		},


		/**
		 * Binds events for the Astra Theme.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$( document ).on('ast-after-plugin-active', AstraThemeAdmin._disableActivcationNotice );
			$( document ).on('click' , '.astra-install-recommended-plugin', AstraThemeAdmin._installNow );
			$( document ).on('click' , '.astra-activate-recommended-plugin', AstraThemeAdmin._activatePlugin);
			$( document ).on('click' , '.astra-deactivate-recommended-plugin', AstraThemeAdmin._deactivatePlugin);
			$( document ).on('wp-plugin-install-success' , AstraThemeAdmin._activatePlugin);
			$( document ).on('wp-plugin-install-error'   , AstraThemeAdmin._installError);
			$( document ).on('wp-plugin-installing'      , AstraThemeAdmin._pluginInstalling);
		},

		/**
		 * Plugin Installation Error.
		 */
		_installError: function( event, response ) {

			var $card = jQuery( '.astra-install-recommended-plugin' );

			$card
				.removeClass( 'button-primary' )
				.addClass( 'disabled' )
				.html( wp.updates.l10n.installFailedShort );
		},

		/**
		 * Installing Plugin
		 */
		_pluginInstalling: function(event, args) {
			event.preventDefault();

			var slug = args.slug;

			var $card = jQuery( '.astra-install-recommended-plugin' );
			var activatingText = astra.recommendedPluiginActivatingText;


			$card.each(function( index, element ) {
				element = jQuery( element );
				if ( element.data('slug') === slug ) {
					element.addClass('updating-message');
					element.html( activatingText );
				}
			});

		},

		/**
		 * Activate Success
		 */
		_activatePlugin: function( event, response ) {

			event.preventDefault();

			var $message = jQuery(event.target);
			var $init = $message.data('init');
			var activatedSlug; 

			if (typeof $init === 'undefined') {
				var $message = jQuery('.astra-install-recommended-plugin[data-slug=' + response.slug + ']');
				activatedSlug = response.slug;
			} else {
				activatedSlug = $init;
			}

			// Transform the 'Install' button into an 'Activate' button.
			var $init = $message.data('init');
			var activatingText = astra.recommendedPluiginActivatingText;
			var settingsLink = $message.data('settings-link');
			var settingsLinkText = astra.recommendedPluiginSettingsText;
			var deactivateText = astra.recommendedPluiginDeactivateText;
			var astraSitesLink = astra.astraSitesLink;
			var astraPluginRecommendedNonce = astra.astraPluginManagerNonce;

			$message.removeClass( 'install-now installed button-disabled updated-message' )
				.addClass('updating-message')
				.html( activatingText );

			// WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
			setTimeout( function() {

				$.ajax({
					url: astra.ajaxUrl,
					type: 'POST',
					data: {
						'action'            : 'astra-sites-plugin-activate',
						'nonce'             : astraPluginRecommendedNonce,
						'init'              : $init,
					},
				})
				.done(function (result) {

					if( result.success ) {
						var output  = '<a href="#" class="astra-deactivate-recommended-plugin" data-init="'+ $init +'" data-settings-link="'+ settingsLink +'" data-settings-link-text="'+ deactivateText +'" aria-label="'+ deactivateText +'">'+ deactivateText +'</a>';
							output += ( typeof settingsLink === 'string' && settingsLink != 'undefined' ) ? '<a href="' + settingsLink +'" aria-label="'+ settingsLinkText +'">' + settingsLinkText +' </a>' : '';
							output += ( typeof settingsLink === undefined && settingsLink != undefined ) ? '<a href="' + settingsLink +'" aria-label="'+ settingsLinkText +'">' + settingsLinkText +' </a>' : '';

						$message.removeClass( 'astra-activate-recommended-plugin astra-install-recommended-plugin button button-primary install-now activate-now updating-message' );

						$message.parent('.ast-addon-link-wrapper').parent('.astra-recommended-plugin').addClass('active');
						$message.parents('.ast-addon-link-wrapper').html( output );

						var starterSitesRedirectionUrl = astraSitesLink + result.data.starter_template_slug;
						jQuery(document).trigger( 'ast-after-plugin-active', [starterSitesRedirectionUrl, activatedSlug] );

					} else {

						$message.removeClass( 'updating-message' );
					}

				});

			}, 1200 );

		},

		/**
		 * Activate Success
		 */
		_deactivatePlugin: function( event, response ) {

			event.preventDefault();

			var $message = jQuery(event.target);

			var $init = $message.data('init');

			if (typeof $init === 'undefined') {
				var $message = jQuery('.astra-install-recommended-plugin[data-slug=' + response.slug + ']');
			}

			// Transform the 'Install' button into an 'Activate' button.
			var $init = $message.data('init');
			var deactivatingText = $message.data('deactivating-text') || astra.recommendedPluiginDeactivatingText;
			var settingsLink = $message.data('settings-link');
			var activateText = astra.recommendedPluiginActivateText;
			var astraPluginRecommendedNonce = astra.astraPluginManagerNonce;

			$message.removeClass( 'install-now installed button-disabled updated-message' )
				.addClass('updating-message')
				.html( deactivatingText );

			// WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
			setTimeout( function() {

				$.ajax({
					url: astra.ajaxUrl,
					type: 'POST',
					data: {
						'action'            : 'astra-sites-plugin-deactivate',
						'nonce'             : astraPluginRecommendedNonce,
						'init'              : $init,
					},
				})
				.done(function (result) {

					if( result.success ) {
						var output = '<a href="#" class="astra-activate-recommended-plugin" data-init="'+ $init +'" data-settings-link="'+ settingsLink +'" data-settings-link-text="'+ activateText +'" aria-label="'+ activateText +'">'+ activateText +'</a>';
						$message.removeClass( 'astra-activate-recommended-plugin astra-install-recommended-plugin button button-primary install-now activate-now updating-message' );

						$message.parent('.ast-addon-link-wrapper').parent('.astra-recommended-plugin').removeClass('active');
						
						$message.parents('.ast-addon-link-wrapper').html( output );

					} else {

						$message.removeClass( 'updating-message' );

					}

				});

			}, 1200 );

		},

		/**
		 * Install Now
		 */
		_installNow: function(event)
		{
			event.preventDefault();

			var $button 	= jQuery( event.target ),
				$document   = jQuery(document);

			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
				wp.updates.requestFilesystemCredentials( event );

				$document.on( 'credential-modal-cancel', function() {
					var $message = $( '.astra-install-recommended-plugin.updating-message' );

					$message
						.addClass('astra-activate-recommended-plugin')
						.removeClass( 'updating-message astra-install-recommended-plugin' )
						.text( wp.updates.l10n.installNow );

					wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
				} );
			}
			
			wp.updates.installPlugin( {
				slug:    $button.data( 'slug' )
			});
		},

		/**
		 * After plugin active redirect and deactivate activation notice
		 */
		_disableActivcationNotice: function( event, astraSitesLink, activatedSlug )
		{
			event.preventDefault();

			if ( activatedSlug.indexOf( 'astra-sites' ) >= 0 || activatedSlug.indexOf( 'astra-pro-sites' ) >= 0 ) {
				if ( 'undefined' != typeof AstraNotices ) {
			    	AstraNotices._ajax( 'astra-sites-on-active', '' );
				}
				window.location.href = astraSitesLink + '&ast-disable-activation-notice';
			}
		},
	};

	/**
	 * Initialize AstraThemeAdmin
	 */
	$(function(){
		AstraThemeAdmin.init();
	});

})(jQuery);