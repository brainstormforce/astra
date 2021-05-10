/**
 * File fronend.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 *
 * @package Astra
 */

/**
 * Get all of an element's parent elements up the DOM tree
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var astraGetParents = function ( elem, selector ) {

	// Element.matches() polyfill.
	if ( ! Element.prototype.matches) {
		Element.prototype.matches =
			Element.prototype.matchesSelector ||
			Element.prototype.mozMatchesSelector ||
			Element.prototype.msMatchesSelector ||
			Element.prototype.oMatchesSelector ||
			Element.prototype.webkitMatchesSelector ||
			function(s) {
				var matches = (this.document || this.ownerDocument).querySelectorAll( s ),
					i = matches.length;
				while (--i >= 0 && matches.item( i ) !== this) {}
				return i > -1;
			};
	}

	// Setup parents array.
	var parents = [];

	// Get matching parent elements.
	for ( ; elem && elem !== document; elem = elem.parentNode ) {

		// Add matching parents to array.
		if ( selector ) {
			if ( elem.matches( selector ) ) {
				parents.push( elem );
			}
		} else {
			parents.push( elem );
		}
	}
	return parents;
};

/**
 * Deprecated: Get all of an element's parent elements up the DOM tree
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var getParents = function ( elem, selector ) {
	console.warn( 'getParents() function has been deprecated since version 2.5.0 or above of Astra Theme and will be removed in the future. Use astraGetParents() instead.' );
	astraGetParents( elem, selector );
}

/**
 * Toggle Class funtion
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var astraToggleClass = function ( el, className ) {
	if ( el.classList.contains( className ) ) {
		el.classList.remove( className );
	} else {
		el.classList.add( className );
	}
};

/**
 * Deprecated: Toggle Class funtion
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var toggleClass = function ( el, className ) {
	console.warn( 'toggleClass() function has been deprecated since version 2.5.0 or above of Astra Theme and will be removed in the future. Use astraToggleClass() instead.' );
	astraToggleClass( el, className );
};

// CustomEvent() constructor functionality in Internet Explorer 9 and higher.
(function () {

	if (typeof window.CustomEvent === "function") return false;
	function CustomEvent(event, params) {
		params = params || { bubbles: false, cancelable: false, detail: undefined };
		var evt = document.createEvent('CustomEvent');
		evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
		return evt;
	}
	CustomEvent.prototype = window.Event.prototype;
	window.CustomEvent = CustomEvent;
})();

/**
 * Trigget custom JS Event.
 *
 * @since 1.4.6
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent
 * @param {Node} el Dom Node element on which the event is to be triggered.
 * @param {Node} typeArg A DOMString representing the name of the event.
 * @param {String} A CustomEventInit dictionary, having the following fields:
 *			"detail", optional and defaulting to null, of type any, that is an event-dependent value associated with the event.
 */
var astraTriggerEvent = function astraTriggerEvent( el, typeArg ) {
	var customEventInit =
	  arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

	var event = new CustomEvent(typeArg, customEventInit);
	el.dispatchEvent(event);
};

( function() {

	var menu_toggle_all 	 = document.querySelectorAll( '#masthead .main-header-menu-toggle' ),
		main_header_masthead = document.getElementById('masthead'),
		menu_click_listeners = {},
		mobileHeaderType = '',
		body = document.body,
		mobileHeader = '';

	if ( undefined !== main_header_masthead && null !== main_header_masthead ) {

		mobileHeader = main_header_masthead.querySelector("#ast-mobile-header");
	}

	if ( '' !== mobileHeader && null !== mobileHeader ) {

		mobileHeaderType = mobileHeader.dataset.type;
	}

	document.addEventListener( 'astMobileHeaderTypeChange', updateHeaderType, false );

	/**
	 * Updates the header type.
	 */
	function updateHeaderType( e ) {

		mobileHeaderType = e.detail.type;
		var popupTrigger = document.querySelectorAll( '.menu-toggle' );

		if( 'dropdown' === mobileHeaderType ) {

			document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
			updateTrigger('updateHeader');
		}

		if ( 'off-canvas' === mobileHeaderType ) {

			for ( var item = 0;  item < popupTrigger.length; item++ ) {
				if ( undefined !==  popupTrigger[item] && popupTrigger[item].classList.contains( 'toggled') ) {
					popupTrigger[item].click();
				}
			}
		}

		init( mobileHeaderType );

	}

	/**
	 * Opens the Popup when trigger is clicked.
	 */
	popupTriggerClick = function ( event ) {

		var triggerType = event.currentTarget.trigger_type;
		var popupWrap = document.getElementById( 'ast-mobile-popup' );

        if ( ! body.classList.contains( 'ast-popup-nav-open' ) ) {
			body.classList.add( 'ast-popup-nav-open' );
        }

		if ( ! body.classList.contains( 'ast-main-header-nav-open' ) ) {
			body.classList.add( 'ast-main-header-nav-open' );
		}

		if ( ! document.documentElement.classList.contains( 'ast-off-canvas-active' ) ) {
			document.documentElement.classList.add( 'ast-off-canvas-active' );
		}

		if ( 'desktop' === triggerType ) {

			popupWrap.querySelector( '.ast-mobile-popup-content' ).style.display = 'none';
			popupWrap.querySelector( '.ast-desktop-popup-content' ).style.display = 'block';
		}
		if ( 'mobile' === triggerType ) {

			popupWrap.querySelector( '.ast-desktop-popup-content' ).style.display = 'none';
			popupWrap.querySelector( '.ast-mobile-popup-content' ).style.display = 'block';
		}
		this.style.display = 'none';

		popupWrap.classList.add( 'active', 'show' );
	}

	/**
	 * Closes the Trigger when Popup is Closed.
	 */
	function updateTrigger(currentElement) {

		mobileHeader = main_header_masthead.querySelector( "#ast-mobile-header" );
		var parent_li_sibling = '';

		if( undefined !== mobileHeader && null !== mobileHeader && 'dropdown' === mobileHeader.dataset.type && 'updateHeader' !== currentElement ) {
			return;
		}
		if ( undefined !== currentElement && 'updateHeader' !== currentElement ) {

			parent_li_sibling = currentElement.closest( '.ast-mobile-popup-inner' ).querySelectorAll('.menu-item-has-children');

		} else {
			var popup = document.querySelector( '#ast-mobile-popup' );
			parent_li_sibling = popup.querySelectorAll('.menu-item-has-children');

		}

		for ( var j = 0; j < parent_li_sibling.length; j++ ) {

			parent_li_sibling[j].classList.remove('ast-submenu-expanded');

			var all_sub_menu = parent_li_sibling[j].querySelectorAll('.sub-menu');
			for ( var k = 0; k < all_sub_menu.length; k++ ) {
				all_sub_menu[k].style.display = 'none';
			}
		}

		var popupTrigger = document.querySelectorAll( '.menu-toggle' );

		document.body.classList.remove( 'ast-main-header-nav-open', 'ast-popup-nav-open' );
		document.documentElement.classList.remove( 'ast-off-canvas-active' );

		for ( var item = 0;  item < popupTrigger.length; item++ ) {

			popupTrigger[item].classList.remove( 'toggled' );

			popupTrigger[item].style.display = 'flex';
		}
	}

	/**
	 * Main Init Function.
	 */
	function init( mobileHeaderType ) {

		var popupTriggerMobile = document.querySelectorAll( '#ast-mobile-header .menu-toggle' );
		var popupTriggerDesktop = document.querySelectorAll( '#ast-desktop-header .menu-toggle' );

		if ( undefined === mobileHeaderType && null !== main_header_masthead ) {

			mobileHeader = main_header_masthead.querySelector("#ast-mobile-header");
			if( mobileHeader ) {
				mobileHeaderType = mobileHeader.dataset.type;
			} else {
				var desktopHeader = main_header_masthead.querySelector("#ast-desktop-header");
				if ( desktopHeader ) {

					mobileHeaderType = desktopHeader.dataset.toggleType;
				} else {
					return;
				}
			}
		}

		if ( 'off-canvas' === mobileHeaderType ) {
			var popupClose = document.getElementById( 'menu-toggle-close' ),
				popupInner = document.querySelector( '.ast-mobile-popup-inner' ),
				popupLinks = popupInner.getElementsByTagName('a');

			for ( var item = 0;  item < popupTriggerMobile.length; item++ ) {

				popupTriggerMobile[item].removeEventListener("click", astraNavMenuToggle, false);
				// Open the Popup when click on trigger
				popupTriggerMobile[item].addEventListener("click", popupTriggerClick, false);
				popupTriggerMobile[item].trigger_type = 'mobile';

			}
			for ( var item = 0;  item < popupTriggerDesktop.length; item++ ) {

				popupTriggerDesktop[item].removeEventListener("click", astraNavMenuToggle, false);
				// Open the Popup when click on trigger
				popupTriggerDesktop[item].addEventListener("click", popupTriggerClick, false);
				popupTriggerDesktop[item].trigger_type = 'desktop';

			}

			//Close Popup on CLose Button Click.
			popupClose.addEventListener("click", function( e ) {
				document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
				updateTrigger(this);
			});

			// Close Popup if esc is pressed.
			document.addEventListener( 'keyup', function (event) {
				// 27 is keymap for esc key.
				if ( event.keyCode === 27 ) {
					event.preventDefault();
					document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
					updateTrigger();
				}
			});

			// Close Popup on outside click.
			document.addEventListener('click', function (event) {

				var target = event.target;
				var modal = document.querySelector( '.ast-mobile-popup-drawer.active .ast-mobile-popup-overlay' );
				if ( target === modal ) {
					document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
					updateTrigger();
				}
			});

			// Close Popup on # link click inside Popup.
			for ( link = 0, len = popupLinks.length; link < len; link++ ) {
				if( null !== popupLinks[link].getAttribute("href") && '#' !== popupLinks[link].getAttribute("href") ){
					popupLinks[link].addEventListener( 'click', triggerToggleClose, true );
					popupLinks[link].headerType = 'off-canvas';
				}
			}

			AstraToggleSetup();
		} else if ( 'dropdown' === mobileHeaderType ) {

			var mobileDropdownContent = document.querySelector( '.ast-mobile-header-content' ),
			    desktopDropdownContent = document.querySelector( '.ast-desktop-header-content' ),
				mobileLinks = mobileDropdownContent.getElementsByTagName('a'),
				desktopLinks = desktopDropdownContent.getElementsByTagName('a');

			// Close Popup on # link click inside Popup.
			for ( link = 0, len = mobileLinks.length; link < len; link++ ) {
				if( null !== mobileLinks[link].getAttribute("href") && '#' !== mobileLinks[link].getAttribute("href") ){
					mobileLinks[link].addEventListener( 'click', triggerToggleClose, true );
					mobileLinks[link].headerType = 'dropdown';
				}
			}

			// Close Popup on # link click inside Popup.
			for ( link = 0, len = desktopLinks.length; link < len; link++ ) {
				desktopLinks[link].addEventListener( 'click', triggerToggleClose, true );
				desktopLinks[link].headerType = 'dropdown';
			}

			for ( var item = 0;  item < popupTriggerMobile.length; item++ ) {

				popupTriggerMobile[item].removeEventListener("click", popupTriggerClick, false);
				popupTriggerMobile[item].addEventListener('click', astraNavMenuToggle, false);
				popupTriggerMobile[item].trigger_type = 'mobile';

			}
			for ( var item = 0;  item < popupTriggerDesktop.length; item++ ) {

				popupTriggerDesktop[item].removeEventListener("click", popupTriggerClick, false);
				popupTriggerDesktop[item].addEventListener('click', astraNavMenuToggle, false);
				popupTriggerDesktop[item].trigger_type = 'desktop';

			}

			AstraToggleSetup();
		}

		accountPopupTrigger();

	}

	function triggerToggleClose( event ) {

		var headerType = event.currentTarget.headerType;

		switch( headerType ) {

			case 'dropdown':

				var popupTrigger = document.querySelectorAll( '.menu-toggle.toggled' );

				for ( var item = 0;  item < popupTrigger.length; item++ ) {

					popupTrigger[item].click();
				}
				break;
			case 'off-canvas':

				var popupClose = document.getElementById( 'menu-toggle-close' );

				popupClose.click();
				break;
			default:
				break;
		}
	}

	window.addEventListener( 'load', function() {
		init();
	} );
	document.addEventListener( 'astLayoutWidthChanged', function() {
		init();
	} );

	document.addEventListener( 'astPartialContentRendered', function() {

		menu_toggle_all = document.querySelectorAll( '.main-header-menu-toggle' );

		body.classList.remove("ast-main-header-nav-open");

		document.addEventListener( 'astMobileHeaderTypeChange', updateHeaderType, false );

		init();

		accountPopupTrigger();

	} );

	window.addEventListener('resize', function () {

		var menu_offcanvas_close 	= document.getElementById('menu-toggle-close');
		var menu_dropdown_close 	= document.querySelector('.menu-toggle.toggled');
		var desktop_header_content	= document.querySelector('#masthead > #ast-desktop-header .ast-desktop-header-content');
		var elementor_editor 		= document.querySelector('.elementor-editor-active');
		if ( menu_dropdown_close && null === elementor_editor) {
			menu_dropdown_close.click();
		}
		if ( desktop_header_content ) {
			desktop_header_content.style.display = 'none';
		}
		document.body.classList.remove( 'ast-main-header-nav-open', 'ast-popup-nav-open' );

		if( menu_offcanvas_close && null === elementor_editor ) {
			menu_offcanvas_close.click();
		}

		// Skip resize event when keyboard display event triggers on devices.
		if( 'INPUT' !== document.activeElement.tagName ) {
			updateHeaderBreakPoint();
			if ( 'dropdown' === mobileHeaderType ) {
				AstraToggleSetup();
			}
		}
	});

	document.addEventListener('DOMContentLoaded', function () {
		AstraToggleSetup();
		/**
		 * Navigation Keyboard Navigation.
		 */
		var container, count;

		container = document.querySelectorAll( '.navigation-accessibility' );

		for ( count = 0; count <= container.length - 1; count++ ) {
			if ( container[count] ) {
				navigation_accessibility( container[count] );
			}
		}
	});

	var get_window_width = function () {

		return document.documentElement.clientWidth;
	}

	/* Add break point Class and related trigger */
	var updateHeaderBreakPoint = function () {

		// Content overrflowing out of screen can give incorrect window.innerWidth.
		// Adding overflow hidden and then calculating the window.innerWidth fixes the problem.
		var originalOverflow = body.style.overflow;
		body.style.overflow = 'hidden';
		var ww = get_window_width();
		body.style.overflow = originalOverflow;

		var break_point = astra.break_point;

		/**
		 * This case is when one hits a URL one after the other via `Open in New Tab` option
		 * Chrome returns the value of outer width as 0 in this case.
		 * This mis-calculates the width of the window and header seems invisible.
		 * This could be fixed by using `0 === ww` condition below.
		 */
		if (ww > break_point || 0 === ww) {
			//remove menu toggled class.
			if ( menu_toggle_all.length > 0 ) {

				for (var i = 0; i < menu_toggle_all.length; i++) {

					if( null !== menu_toggle_all[i] ) {
						menu_toggle_all[i].classList.remove('toggled');
					}
				}
			}
			body.classList.remove("ast-header-break-point");
			body.classList.add("ast-desktop");
			astraTriggerEvent(body, "astra-header-responsive-enabled");

		} else {

			body.classList.add("ast-header-break-point");
			body.classList.remove("ast-desktop");
			astraTriggerEvent(body, "astra-header-responsive-disabled")
		}
	}

	var accountPopupTrigger = function () {
		// Account login form popup.
		var header_account_trigger =  document.querySelectorAll( '.ast-account-action-login' )[0];

		if( undefined !== header_account_trigger ) {

			var header_account__close_trigger =  document.getElementById( 'ast-hb-login-close' );
			var login_popup =  document.getElementById( 'ast-hb-account-login-wrap' );

			header_account_trigger.onclick = function( event ) {
				event.preventDefault();
				event.stopPropagation();
				if ( ! login_popup.classList.contains( 'show' ) ) {
					login_popup.classList.add( 'show' );
				}
			};

			header_account__close_trigger.onclick = function( event ) {
				event.preventDefault();
				login_popup.classList.remove( 'show' );
			};
		}
	}

	updateHeaderBreakPoint();

	AstraToggleSubMenu = function( event ) {

		event.preventDefault();

		var parent_li = this.parentNode;

		if ( parent_li.classList.contains('ast-submenu-expanded') && document.querySelector('header.site-header').classList.contains('ast-builder-menu-toggle-link') ) {

			if (!this.classList.contains('ast-menu-toggle')) {

				var link = parent_li.querySelector('a').getAttribute('href');
				if ( '' !== link && '#' !== link) {
					window.location = link;
				}
			}
		}

		var parent_li_child = parent_li.querySelectorAll('.menu-item-has-children');
		for (var j = 0; j < parent_li_child.length; j++) {

			parent_li_child[j].classList.remove('ast-submenu-expanded');
			var parent_li_child_sub_menu = parent_li_child[j].querySelector('.sub-menu, .children');
			if( null !== parent_li_child_sub_menu ) {
				parent_li_child_sub_menu.style.display = 'none';
			}
		}

		var parent_li_sibling = parent_li.parentNode.querySelectorAll('.menu-item-has-children');
		for (var j = 0; j < parent_li_sibling.length; j++) {

			if (parent_li_sibling[j] != parent_li) {

				parent_li_sibling[j].classList.remove('ast-submenu-expanded');
				var all_sub_menu = parent_li_sibling[j].querySelectorAll('.sub-menu');
				for (var k = 0; k < all_sub_menu.length; k++) {
					all_sub_menu[k].style.display = 'none';
				}
			}
		}

		if (parent_li.classList.contains('menu-item-has-children') ) {
			astraToggleClass(parent_li, 'ast-submenu-expanded');
			if (parent_li.classList.contains('ast-submenu-expanded')) {
				parent_li.querySelector('.sub-menu').style.display = 'block';
			} else {
				parent_li.querySelector('.sub-menu').style.display = 'none';
			}
		}
	};

	AstraNavigationMenu = function( parentList ) {
		console.warn( 'AstraNavigationMenu() function has been deprecated since version 1.6.5 or above of Astra Theme and will be removed in the future.' );
	};

	AstraToggleMenu = function( astra_menu_toggle ) {
		console.warn('AstraToggleMenu() function has been deprecated since version 1.6.5 or above of Astra Theme and will be removed in the future. Use AstraToggleSubMenu() instead.');

		// Add Eventlisteners for Submenu.
		if (astra_menu_toggle.length > 0) {
			for (var i = 0; i < astra_menu_toggle.length; i++) {
				astra_menu_toggle[i].addEventListener('click', AstraToggleSubMenu, false);
			}
		}
	};

	AstraToggleSetup = function () {

		if ( 'off-canvas' === mobileHeaderType || 'full-width' === mobileHeaderType ) {
			var __main_header_all = document.querySelectorAll( '#ast-mobile-popup' );
			if ( body.classList.contains('ast-header-break-point') ) {

				var menu_toggle_all   = document.querySelectorAll( '#ast-mobile-header .main-header-menu-toggle' );
			} else {
				menu_toggle_all   = document.querySelectorAll( '#ast-desktop-header .main-header-menu-toggle' );
			}
		} else {

			if ( body.classList.contains('ast-header-break-point') ) {

				var __main_header_all = document.querySelectorAll( '#ast-mobile-header' ),
					menu_toggle_all   = document.querySelectorAll( '#ast-mobile-header .main-header-menu-toggle' );
			} else {

				var __main_header_all = document.querySelectorAll( '#ast-desktop-header' ),
					menu_toggle_all   = document.querySelectorAll( '#ast-desktop-header .main-header-menu-toggle' );
			}
		}

		if (menu_toggle_all.length > 0) {

			for (var i = 0; i < menu_toggle_all.length; i++) {

				menu_toggle_all[i].setAttribute('data-index', i);

				if ( ! menu_click_listeners[i] ) {
					menu_click_listeners[i] = menu_toggle_all[i];
					menu_toggle_all[i].addEventListener('click', astraNavMenuToggle, false);
				}

				if ('undefined' !== typeof __main_header_all[i]) {

					if (document.querySelector('header.site-header').classList.contains('ast-builder-menu-toggle-link')) {
						var astra_menu_toggle = __main_header_all[i].querySelectorAll('ul.main-header-menu .menu-item-has-children > .menu-link, ul.main-header-menu .ast-menu-toggle');
					} else {
						var astra_menu_toggle = __main_header_all[i].querySelectorAll('ul.main-header-menu .ast-menu-toggle');
					}
					// Add Eventlisteners for Submenu.
					if (astra_menu_toggle.length > 0) {

						for (var j = 0; j < astra_menu_toggle.length; j++) {

							astra_menu_toggle[j].addEventListener('click', AstraToggleSubMenu, false);
						}
					}
				}
			}
		}
	};

	astraNavMenuToggle = function ( event ) {

		event.preventDefault();

		var desktop_header = event.target.closest('#ast-desktop-header');

		var desktop_header_content = document.querySelector('#masthead > #ast-desktop-header .ast-desktop-header-content');

		if ( null !== desktop_header && undefined !== desktop_header && '' !== desktop_header ) {

			var desktop_toggle = desktop_header.querySelector( '.main-header-menu-toggle' );
		} else {
			var desktop_toggle = document.querySelector('#masthead > #ast-desktop-header .main-header-menu-toggle');
		}

		var desktop_menu = document.querySelector('#masthead > #ast-desktop-header .ast-desktop-header-content .main-header-bar-navigation');

		if ( 'desktop' === event.currentTarget.trigger_type ) {

			if ( null !== desktop_menu && '' !== desktop_menu && undefined !== desktop_menu ) {
				astraToggleClass(desktop_menu, 'toggle-on');
				if (desktop_menu.classList.contains('toggle-on')) {
					desktop_menu.style.display = 'block';
				} else {
					desktop_menu.style.display = '';
				}
			}
			astraToggleClass(desktop_toggle, 'toggled');
			if ( desktop_toggle.classList.contains( 'toggled' ) ) {
				body.classList.add("ast-main-header-nav-open");
				if ( 'dropdown' === mobileHeaderType ) {
					desktop_header_content.style.display = 'block';
				}
			} else {
				body.classList.remove("ast-main-header-nav-open");
				desktop_header_content.style.display = 'none';
			}
			return;
		}

		var __main_header_all = document.querySelectorAll('#masthead > #ast-mobile-header .main-header-bar-navigation');
		menu_toggle_all 	 = document.querySelectorAll( '#masthead > #ast-mobile-header .main-header-menu-toggle' )
		var event_index = '0';
		var sticky_header = false;
		if ( null !== this.closest( '#ast-fixed-header' ) ) {

			__main_header_all = document.querySelectorAll('#ast-fixed-header > #ast-mobile-header .main-header-bar-navigation');
			menu_toggle_all 	 = document.querySelectorAll( '#ast-fixed-header .main-header-menu-toggle' )

			event_index = '0';
			sticky_header = true;

		}

		if ('undefined' === typeof __main_header_all[event_index]) {
			return false;
		}
		var menuHasChildren = __main_header_all[event_index].querySelectorAll('.menu-item-has-children');
		for (var i = 0; i < menuHasChildren.length; i++) {
			menuHasChildren[i].classList.remove('ast-submenu-expanded');
			var menuHasChildrenSubMenu = menuHasChildren[i].querySelectorAll('.sub-menu');
			for (var j = 0; j < menuHasChildrenSubMenu.length; j++) {
				menuHasChildrenSubMenu[j].style.display = 'none';
			}
		}

		var menu_class = this.getAttribute('class') || '';

		if ( menu_class.indexOf('main-header-menu-toggle') !== -1 ) {
			astraToggleClass(__main_header_all[event_index], 'toggle-on');
			astraToggleClass(menu_toggle_all[event_index], 'toggled');
			if ( sticky_header && 1 < menu_toggle_all.length ) {
				astraToggleClass(menu_toggle_all['1'], 'toggled');
			}
			if (__main_header_all[event_index].classList.contains('toggle-on')) {
				__main_header_all[event_index].style.display = 'block';
				body.classList.add("ast-main-header-nav-open");
			} else {
				__main_header_all[event_index].style.display = '';
				body.classList.remove("ast-main-header-nav-open");
			}
		}
	};

	body.addEventListener("astra-header-responsive-enabled", function () {

		var __main_header_all = document.querySelectorAll('.main-header-bar-navigation');

		if (__main_header_all.length > 0) {

			for (var i = 0; i < __main_header_all.length; i++) {
				if (null != __main_header_all[i]) {
					__main_header_all[i].classList.remove('toggle-on');
					__main_header_all[i].style.display = '';
				}

				var sub_menu = __main_header_all[i].getElementsByClassName('sub-menu');
				for (var j = 0; j < sub_menu.length; j++) {
					sub_menu[j].style.display = '';
				}
				var child_menu = __main_header_all[i].getElementsByClassName('children');
				for (var k = 0; k < child_menu.length; k++) {
					child_menu[k].style.display = '';
				}

				var searchIcons = __main_header_all[i].getElementsByClassName('ast-search-menu-icon');
				for (var l = 0; l < searchIcons.length; l++) {
					searchIcons[l].classList.remove('ast-dropdown-active');
					searchIcons[l].style.display = '';
				}
			}
		}
	}, false);

	var get_browser = function () {
	    var ua = navigator.userAgent,tem,M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(/trident/i.test(M[1])) {
	        tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
	        return;
	    }
	    if( 'Chrome'  === M[1] ) {
	        tem = ua.match(/\bOPR|Edge\/(\d+)/)
	        if(tem != null)   {
	        	return;
	        	}
	        }
	    M = M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
	    if((tem = ua.match(/version\/(\d+)/i)) != null) {
	    	M.splice(1,1,tem[1]);
	    }

	    if( 'Safari' === M[0] && M[1] < 11 ) {
		   bodyElement.classList.add( "ast-safari-browser-less-than-11" );
	    }
	}

	get_browser();

	/* Search Script */
	var SearchIcons = document.getElementsByClassName( 'astra-search-icon' );
	for (var i = 0; i < SearchIcons.length; i++) {

		SearchIcons[i].onclick = function(event) {
            if ( this.classList.contains( 'slide-search' ) ) {
                event.preventDefault();
                var sibling = this.parentNode.parentNode.parentNode.querySelector( '.ast-search-menu-icon' );
                if ( ! sibling.classList.contains( 'ast-dropdown-active' ) ) {
                    sibling.classList.add( 'ast-dropdown-active' );
                    sibling.querySelector( '.search-field' ).setAttribute('autocomplete','off');
                    setTimeout(function() {
                     sibling.querySelector( '.search-field' ).focus();
                    },200);
                } else {
                	var searchTerm = sibling.querySelector( '.search-field' ).value || '';
	                if( '' !== searchTerm ) {
    		            sibling.querySelector( '.search-form' ).submit();
                    }
                    sibling.classList.remove( 'ast-dropdown-active' );
                }
            }
        }
	}

	/* Hide Dropdown on body click*/
	body.onclick = function( event ) {
		if ( typeof event.target.classList !==  'undefined' ) {
			if ( ! event.target.classList.contains( 'ast-search-menu-icon' ) && astraGetParents( event.target, '.ast-search-menu-icon' ).length === 0 && astraGetParents( event.target, '.ast-search-icon' ).length === 0  ) {
				var dropdownSearchWrap = document.getElementsByClassName( 'ast-search-menu-icon' );
				for (var i = 0; i < dropdownSearchWrap.length; i++) {
					dropdownSearchWrap[i].classList.remove( 'ast-dropdown-active' );
				}
			}
		}
	}

	/**
	 * Navigation Keyboard Navigation.
	 */
	function navigation_accessibility( container ) {
		if ( ! container ) {
			return;
		}

		var button = container.getElementsByTagName( 'button' )[0];
		if ( 'undefined' === typeof button ) {
			button = container.getElementsByTagName( 'a' )[0];
			if ( 'undefined' === typeof button ) {
				return;
			}
		}

		var menu = container.getElementsByTagName( 'ul' )[0];

		// Hide menu toggle button if menu is empty and return early.
		if ( 'undefined' === typeof menu ) {
			button.style.display = 'none';
			return;
		}

		menu.setAttribute( 'aria-expanded', 'false' );
		if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
			menu.className += ' nav-menu';
		}

		button.onclick = function() {
			if ( -1 !== container.className.indexOf( 'toggled' ) ) {
				container.className = container.className.replace( ' toggled', '' );
				button.setAttribute( 'aria-expanded', 'false' );
				menu.setAttribute( 'aria-expanded', 'false' );
			} else {
				container.className += ' toggled';
				button.setAttribute( 'aria-expanded', 'true' );
				menu.setAttribute( 'aria-expanded', 'true' );
			}
		};

		// Get all the link elements within the menu.
		var links    = menu.getElementsByTagName( 'a' );
		var subMenus = menu.getElementsByTagName( 'ul' );


		// Set menu items with submenus to aria-haspopup="true".
		for ( var i = 0, len = subMenus.length; i < len; i++ ) {
			subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
		}

		// Each time a menu link is focused or blurred, toggle focus.
		for ( i = 0, len = links.length; i < len; i++ ) {
			links[i].addEventListener( 'focus', toggleFocus, true );
			links[i].addEventListener( 'blur', toggleFocus, true );
			links[i].addEventListener( 'click', toggleClose, true );
		}
	}

	/**
     * Close the Toggle Menu on Click on hash (#) link.
     *
     * @since 1.3.2
     * @return void
     */
    function toggleClose( )
    {
        var self = this || '',
			hash = '#';

        if( self && ! self.classList.contains('astra-search-icon') && null === self.closest('.ast-builder-menu') ) {
            var link = new String( self );
            if( link.indexOf( hash ) !== -1 ) {
            	var link_parent = self.parentNode;
                if ( body.classList.contains('ast-header-break-point') ) {
					if( ! ( document.querySelector('header.site-header').classList.contains('ast-builder-menu-toggle-link') && link_parent.classList.contains('menu-item-has-children') ) ) {
						/* Close Builder Header Menu */
						var builder_header_menu_toggle = document.querySelector( '.main-header-menu-toggle' );
						builder_header_menu_toggle.classList.remove( 'toggled' );

						var main_header_bar_navigation = document.querySelector( '.main-header-bar-navigation' );
						main_header_bar_navigation.classList.remove( 'toggle-on' );

						main_header_bar_navigation.style.display = 'none';

						astraTriggerEvent( document.querySelector('body'), 'astraMenuHashLinkClicked' );
					}

                } else {
	            	while ( -1 === self.className.indexOf( 'nav-menu' ) ) {
						// On li elements toggle the class .focus.
						if ( 'li' === self.tagName.toLowerCase() ) {
							if ( -1 !== self.className.indexOf( 'focus' ) ) {
								self.className = self.className.replace( ' focus', '' );
							}
						}
						self = self.parentElement;
					}
				}
            }
        }
   	}

	/**
	 * Sets or removes .focus class on an element on focus.
	 */
	function toggleFocus() {
		var self = this;
		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'navigation-accessibility' ) ) {
			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				self.classList.toggle('focus');
			}
			self = self.parentElement;
		}
	}

	/* Add class if mouse clicked and remove if tab pressed */
	if ( 'querySelector' in document && 'addEventListener' in window ) {
		body.addEventListener( 'mousedown', function() {
			body.classList.add( 'ast-mouse-clicked' );
		} );

		body.addEventListener( 'keydown', function() {
			body.classList.remove( 'ast-mouse-clicked' );
		} );
	}

})();
