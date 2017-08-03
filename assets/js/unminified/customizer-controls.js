/**
 * Customizer controls
 *
 * @package Astra
 */

( function( $ ) {

	/* Internal shorthand */
	var api = wp.customize;

	/**
	 * Helper class for the main Customizer interface.
	 *
	 * @since 1.0.0
	 * @class ASTCustomizer
	 */
	ASTCustomizer = {

		controls	: {},

		/**
		 * Initializes our custom logic for the Customizer.
		 *
		 * @since 1.0.0
		 * @method init
		 */
		init: function()
		{
			ASTCustomizer._initToggles();
		},

		/**
		 * Initializes the logic for showing and hiding controls
		 * when a setting changes.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _initToggles
		 */
		_initToggles: function()
		{
			// Trigger the Adv Tab Click trigger.
			ASTControlTrigger.triggerHook( 'astra-toggle-control', api );

			// Loop through each setting.
			$.each( ASTCustomizerToggles, function( settingId, toggles ) {

				// Get the setting object.
				api( settingId, function( setting ) {

					// Loop though the toggles for the setting.
					$.each( toggles, function( i, toggle ) {

						// Loop through the controls for the toggle.
						$.each( toggle.controls, function( k, controlId ) {

							// Get the control object.
							api.control( controlId, function( control ) {

								// Define the visibility callback.
								var visibility = function( to ) {
									control.container.toggle( toggle.callback( to ) );
								};

								// Init visibility.
								visibility( setting.get() );

								// Bind the visibility callback to the setting.
								setting.bind( visibility );
							});
						});
					});
				});

				// Hide Section without Controls.
				$( '.customize-control *[data-customize-setting-link="' + settingId + '"]' ).on('change', function() {

					setTimeout( function() {
						$.each(toggles, function( i, toggle) {

							$.each(toggle.controls, function( k, controlId) {

								controlId = controlId.replace( 'astra-settings[','' ).replace( ']','' );
								var parent = $( '#customize-control-astra-settings-' + controlId ).closest( '.control-section' );
								if ( typeof parent != 'undefined' ) {

									var parentId = parent.attr( 'id' );
									var visibleIt = false;

									parent.find( ' > .customize-control' ).each(function(i,e) {
										if ( $( this ).css( 'display' ) != 'none' ) {
											visibleIt = true;
										}
									});

									if ( ! visibleIt ) {
										$( '.control-section[aria-owns="' + parentId + '"]' ).addClass( 'ast-hide' );
									} else {
										$( '.control-section[aria-owns="' + parentId + '"]' ).removeClass( 'ast-hide' );
									}
								}
							});
						});
					},300);
				});

				$.each(toggles, function( i, toggle) {

					$.each(toggle.controls, function( k, controlId) {

						controlId = controlId.replace( 'astra-settings[','' ).replace( ']','' );
						var parent = $( '#customize-control-astra-settings-' + controlId ).closest( '.control-section' );

						if ( typeof parent != 'undefined' ) {

							var parentId = parent.attr( 'id' );
							var visibleIt = false;

							parent.find( ' > .customize-control' ).each(function(i,e) {
								if ( $( this ).css( 'display' ) != 'none' ) {
									visibleIt = true;
								}
							});

							if ( ! visibleIt ) {
								$( '.control-section[aria-owns="' + parentId + '"]' ).addClass( 'ast-hide' );
							} else {
								$( '.control-section[aria-owns="' + parentId + '"]' ).removeClass( 'ast-hide' );
							}
						}
					});
				});

			});
		}
	};

	$( function() { ASTCustomizer.init(); } );

})( jQuery );


( function( api ) {
    // Extends our custom astra-pro section.
    api.sectionConstructor['astra-pro'] = api.Section.extend( {
        // No events for this type of section.
        attachEvents: function () {},
        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    } );
} )( wp.customize );