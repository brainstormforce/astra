/**
 * Customizer controls toggles
 *
 * @package Astra
 */

( function( $ ) {


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
	    	$( 'body' ).trigger( 'astra-control-trigger.' + hook, args );
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
	    	$( 'body' ).on( 'astra-control-trigger.' + hook, callback );
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
		    $( 'body' ).off( 'astra-control-trigger.' + hook, callback );
	    },
	};

	/**
	 * Helper class that contains data for showing and hiding controls.
	 *
	 * @since 1.0.0
	 * @class ASTCustomizerToggles
	 */
	ASTCustomizerToggles = {

		'astra-settings[display-site-title]' : [],

		'astra-settings[display-site-tagline]' : [],

		'astra-settings[ast-header-retina-logo]' :[],

		'custom_logo' : [],
		
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
		'astra-settings[header-main-rt-section]' : [],
		

		'astra-settings[hide-custom-menu-mobile]' :[],
		

		/**
		 * Blog
		 */
		'astra-settings[blog-width]' :[],
		
		'astra-settings[blog-post-structure]' :[],

		/**
		 * Blog Single
		 */
		 'astra-settings[blog-single-post-structure]' : [],
		
		'astra-settings[blog-single-width]' : [],
		
		'astra-settings[blog-single-meta]' :[], 
		

		/**
		 * Small Footer
		 */
		'astra-settings[footer-sml-layout]' : [],
		
		'astra-settings[footer-sml-section-1]' :[],
		
		'astra-settings[footer-sml-section-2]' :[],
		
		'astra-settings[footer-sml-divider]' :[],
		
		'astra-settings[header-main-sep]' :[],
	
		'astra-settings[disable-primary-nav]' :[],
		
		/**
		 * Footer Widgets
		 */
		'astra-settings[footer-adv]' :[],
		
		'astra-settings[shop-archive-width]' :[],
		
		'astra-settings[mobile-header-logo]' :[],
		
		'astra-settings[different-mobile-logo]' :[],

	};

} )( jQuery );