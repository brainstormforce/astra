/**
 * Customizer controls toggles
 *
 * @package Astra
 */

( function( $ ) {

	/* Internal shorthand */
	var api = wp.customize;

	/**
	 * Trigger hooks
	 */
	ASTControlTrigger = {

	    /**
	     * Trigger a hook.
	     *
	     * @since 1.0.0
	     * @method triggerHook
	     * @param {String} hook The hook to trigger.
	     * @param {Array} args An array of args to pass to the hook.
		 */
	    triggerHook: function( hook, args )
	    {
	    	$( 'body' ).trigger( 'ast-control-trigger.' + hook, args );
	    },

	    /**
	     * Add a hook.
	     *
	     * @since 1.0.0
	     * @method addHook
	     * @param {String} hook The hook to add.
	     * @param {Function} callback A function to call when the hook is triggered.
	     */
	    addHook: function( hook, callback )
	    {
	    	$( 'body' ).on( 'ast-control-trigger.' + hook, callback );
	    },

	    /**
	     * Remove a hook.
	     *
	     * @since 1.0.0
	     * @method removeHook
	     * @param {String} hook The hook to remove.
	     * @param {Function} callback The callback function to remove.
	     */
	    removeHook: function( hook, callback )
	    {
		    $( 'body' ).off( 'ast-control-trigger.' + hook, callback );
	    },
	};

	/**
	 * Helper class that contains data for showing and hiding controls.
	 *
	 * @since 1.0.0
	 * @class ASTCustomizerToggles
	 */
	ASTCustomizerToggles = {

		'astra-settings[display-site-title]' :
		[
			{
				controls: [
					'astra-settings[divider-section-header-typo-title]',
					'astra-settings[font-size-site-title]',
				],
				callback: function( value ) {

					if ( value ) {
						return true;
					}
					return false;
				}
			},
		],

		'astra-settings[display-site-tagline]' :
		[
			{
				controls: [
					'astra-settings[divider-section-header-typo-tagline]',
					'astra-settings[font-size-site-tagline]',
				],
				callback: function( value ) {

					if ( value ) {
						return true;
					}
					return false;
				}
			},
		],

		/**
		 * Section - Header
		 *
		 * @link  ?autofocus[section]=section-header
		 */

		/**
		 * Layout 2
		 */
		// Layout 2 > Right Section > Text / HTML
		// Layout 2 > Right Section > Search Type
		// Layout 2 > Right Section > Search Type > Search Box Type.
		'astra-settings[header-main-rt-section]' :
		[
			{
				controls: [
					'astra-settings[header-main-rt-section-html]'
				],
				callback: function( val ) {

					if ( 'text-html' == val ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[header-main-menu-label]',
					'astra-settings[header-main-menu-label-divider]',
				],
				callback: function( custom_menu ) {
					var menu = api( 'astra-settings[disable-primary-nav]' ).get();
					if ( !menu || 'none' !=  custom_menu) {
						return true;
					}
					return false;
				}
			},
		],

		/**
		 * Blog
		 */
		'astra-settings[blog-width]' :
		[
			{
				controls: [
					'astra-settings[blog-max-width]'
				],
				callback: function( blog_width ) {

					if ( 'custom' == blog_width ) {
						return true;
					}
					return false;
				}
		}
		],
		'astra-settings[blog-meta]' :
		[
			{
				controls: [
					'astra-settings[blog-meta-comments]',
					'astra-settings[blog-meta-category]',
					'astra-settings[blog-meta-author]',
					'astra-settings[blog-meta-date]',
					'astra-settings[blog-meta-tag]',
				],
				callback: function( enable_postmeta ) {

					if ( '1' == enable_postmeta ) {
						return true;
					}
					return false;
				}
		}
		],

		/**
		 * Blog Single
		 */
		'astra-settings[blog-single-width]' :
		[
			{
				controls: [
					'astra-settings[blog-single-max-width]'
				],
				callback: function( blog_width ) {

					if ( 'custom' == blog_width ) {
						return true;
					}
					return false;
				}
		}
		],
		'astra-settings[blog-single-meta]' :
		[
			{
				controls: [
					'astra-settings[blog-single-meta-comments]',
					'astra-settings[blog-single-meta-cat]',
					'astra-settings[blog-single-meta-author]',
					'astra-settings[blog-single-meta-date]',
					'astra-settings[blog-single-meta-tag]',
				],
				callback: function( enable_postmeta ) {

					if ( '1' == enable_postmeta ) {
						return true;
					}
					return false;
				}
		}
		],

		/**
		 * Small Footer
		 */
		'astra-settings[footer-sml-layout]' :
		[
			{
				controls: [
					'astra-settings[footer-sml-section-1]',
					'astra-settings[footer-sml-section-2]',
					'astra-settings[section-ast-small-footer-background-styling]',
					'astra-settings[ast-small-footer-color]',
					'astra-settings[ast-small-footer-link-color]',
					'astra-settings[ast-small-footer-link-hover-color]',
					'astra-settings[ast-small-footer-bg-img]',
					'astra-settings[section-ast-small-footer-typography]',
					'astra-settings[ast-small-footer-text-font]',
					'astra-settings[footer-sml-divider]',
					'astra-settings[section-ast-small-footer-layout-info]',
					'astra-settings[footer-layout-width]',
				],
				callback: function( small_footer_layout ) {

					if ( 'disabled' != small_footer_layout ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[footer-sml-section-1-credit]',
				],
				callback: function( small_footer_layout ) {

					var footer_section_1 = api( 'astra-settings[footer-sml-section-1]' ).get();

					if ( 'disabled' != small_footer_layout && 'custom' == footer_section_1 ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[footer-sml-section-2-credit]',
				],
				callback: function( small_footer_layout ) {

					var footer_section_2 = api( 'astra-settings[footer-sml-section-2]' ).get();

					if ( 'disabled' != small_footer_layout && 'custom' == footer_section_2 ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[footer-sml-divider-color]',
				],
				callback: function( small_footer_layout ) {

					var border_width = api( 'astra-settings[footer-sml-divider]' ).get();

					if ( '1' <= border_width && 'disabled' != small_footer_layout ) {
						return true;
					}
					return false;
				}
			},
		],
		'astra-settings[footer-sml-section-1]' :
		[
			{
				controls: [
					'astra-settings[footer-sml-section-1-credit]',
				],
				callback: function( enabled_section_1 ) {

					var footer_layout = api( 'astra-settings[footer-sml-layout]' ).get();

					if ( 'custom' == enabled_section_1 && 'disabled' != footer_layout ) {
						return true;
					}
					return false;
				}
			}
		],
		'astra-settings[footer-sml-section-2]' :
		[
			{
				controls: [
					'astra-settings[footer-sml-section-2-credit]',
				],
				callback: function( enabled_section_2 ) {

					var footer_layout = api( 'astra-settings[footer-sml-layout]' ).get();

					if ( 'custom' == enabled_section_2 && 'disabled' != footer_layout ) {
						return true;
					}
					return false;
				}
			}
		],

		'astra-settings[footer-sml-divider]' :
		[
			{
				controls: [
					'astra-settings[footer-sml-divider-color]',
				],
				callback: function( border_width ) {

					var footer_layout = api( 'astra-settings[footer-sml-layout]' ).get();

					if ( '1' <= border_width && 'disabled' != footer_layout ) {
						return true;
					}
					return false;
				}
			},
		],

		'astra-settings[header-main-sep]' :
		[
			{
				controls: [
					'astra-settings[header-main-sep-color]',
				],
				callback: function( border_width ) {

					if ( '1' <= border_width ) {
						return true;
					}
					return false;
				}
			},
		],

		'astra-settings[disable-primary-nav]' :
		[
			{
				controls: [
					'astra-settings[header-main-menu-label]',
					'astra-settings[header-main-menu-label-divider]',
				],
				callback: function( menu ) {
					var custom_menu = api( 'astra-settings[header-main-rt-section]' ).get();
					if ( !menu || 'none' !=  custom_menu) {
						return true;
					}
					return false;
				}
			},
		],

	};

	/**
	 * Sidebar Manager
	 *
	 * => Dependent Addons:
	 *
	 * @ Spacing Addon
	 */
	var site_layout      = [ 'site-sidebar-layout' ],
		sidebars_single  = ast.customizer.settings.sidebars.single,
		sidebars_archive = ast.customizer.settings.sidebars.archive,
		merged_sidebars  = jQuery.merge( site_layout, sidebars_single ),
		merged_sidebars  = jQuery.merge( merged_sidebars, sidebars_archive );

	jQuery.each( merged_sidebars , function( sidebar_switch, sidebar_layout ) {

		ASTControlTrigger.addHook( 'ast-toggle-control', function( argument, api ) {

			ASTCustomizerToggles[ 'astra-settings['+sidebar_layout+']' ] =
			[
				{
					controls: [
						'astra-settings[site-sidebar-width]',
						'astra-settings[divider-section-sidebar-width]',

						// @SPACING addon setting
						'astra-settings[sidebar-content-plain-spacing]',
						'astra-settings[sidebar-content-boxed-spacing]',

						// @BLOGPRO addon setting
						'astra-settings[responsive-sidebar]',
						'astra-settings[responsive-sidebar-divider]',
					],
					callback: function( sidebar ) {

						var any_layout = '';
						var sidebar    = api( 'astra-settings[site-sidebar-layout]' ).get();

						jQuery.each( merged_sidebars, function( index, s_layout ) {

							var type   = api( 'astra-settings['+s_layout+']' ).get() || '';

							// Is no-sidebar?
							if( 'no-sidebar' != type && 'default' != type ) {
								any_layout = 'yes';
								return false;
							}
						});

						// Sidebar.
						if( 'no-sidebar' != sidebar && 'default' != sidebar ) {
							any_layout = 'yes';
						}

						if( any_layout ) {
							return true;
						} else {
							return false;
						}
					}
				},
			]

		});
	});

} )( jQuery );
