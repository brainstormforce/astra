/**
 * File navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 *
 * @package Astra
 */

var isIE = false;
var isEdge = false;

/**
 * Get all of an element's parent elements up the DOM tree
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var getParents = function ( elem, selector ) {

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

/* . */
/**
 * Toggle Class funtion
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var toggleClass = function ( el, className ) {
	if ( el.classList.contains( className ) ) {
		el.classList.remove( className );
	} else {
		el.classList.add( className );
	}
};

// CustomEvent() constructor functionality in Internet Explorer 9 and higher.
(function () {

	
    // Internet Explorer 6-11
    isIE = /*@cc_on!@*/false || !!document.documentMode;

    // Edge 20+
    isEdge = !isIE && !!window.StyleMedia;


	if ( typeof window.CustomEvent === "function" ) return false;

	function CustomEvent ( event, params ) {
		params = params || { bubbles: false, cancelable: false, detail: undefined };
		var evt = document.createEvent( 'CustomEvent' );
		evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
		return evt;
	}

	CustomEvent.prototype = window.Event.prototype;

	window.CustomEvent = CustomEvent;

})();

( function() {

	AstraNavigationMenu = function( parentList ) {

		for (var i = 0; i < parentList.length; i++) {

			if ( null != parentList[i].querySelector( '.sub-menu, .children' ) ) {

				// Insert Toggle Button.
				var  toggleButton = document.createElement("BUTTON");        // Create a <button> element
					toggleButton.setAttribute("role", "button");
					toggleButton.setAttribute("class", "ast-menu-toggle");
					toggleButton.setAttribute("aria-expanded", "false");
					toggleButton.innerHTML="<span class='screen-reader-text'>Menu Toggle</span>";
				parentList[i].insertBefore( toggleButton, parentList[i].childNodes[1] );

				var menuLeft         = parentList[i].getBoundingClientRect().left,
					windowWidth      = window.innerWidth,
					menuFromLeft     = (parseInt( windowWidth ) - parseInt( menuLeft ) ),
					menuGoingOutside = false;

				if( menuFromLeft < 500 ) {
					menuGoingOutside = true;
				}

				// Submenu items goes outside?
				if( menuGoingOutside ) {
					parentList[i].classList.add( 'ast-left-align-sub-menu' );

					var all_submenu_parents = parentList[i].querySelectorAll( '.menu-item-has-children, .page_item_has_children' );
					for (var k = 0; k < all_submenu_parents.length; k++) {
						all_submenu_parents[k].classList.add( 'ast-left-align-sub-menu' );
					}
				}

				// Submenu Container goes to outside?
				if( menuFromLeft < 240 ) {
					parentList[i].classList.add( 'ast-sub-menu-goes-outside' );
				}

			};
		};
	};

	AstraToggleMenu = function( astra_menu_toggle ) {
		
		/* Submenu button click */
		for (var i = 0; i < astra_menu_toggle.length; i++) {

			astra_menu_toggle[i].addEventListener( 'click', function ( event ) {
				event.preventDefault();

				var parent_li = this.parentNode;

				var parent_li_child = parent_li.querySelectorAll( '.menu-item-has-children, .page_item_has_children' );
				for (var j = 0; j < parent_li_child.length; j++) {

					parent_li_child[j].classList.remove( 'ast-submenu-expanded' );
					var parent_li_child_sub_menu = parent_li_child[j].querySelector( '.sub-menu, .children' );		
					parent_li_child_sub_menu.style.display = 'none';
				};

				var parent_li_sibling = parent_li.parentNode.querySelectorAll( '.menu-item-has-children, .page_item_has_children' );
				for (var j = 0; j < parent_li_sibling.length; j++) {

					if ( parent_li_sibling[j] != parent_li ) {

						parent_li_sibling[j].classList.remove( 'ast-submenu-expanded' );
						var all_sub_menu = parent_li_sibling[j].querySelectorAll( '.sub-menu, .children' );
						for (var k = 0; k < all_sub_menu.length; k++) {		
							all_sub_menu[k].style.display = 'none';		
						};
					}
				};

				if ( parent_li.classList.contains( 'menu-item-has-children' ) || parent_li.classList.contains( 'page_item_has_children' ) ) {
					toggleClass( parent_li, 'ast-submenu-expanded' );
					if ( parent_li.classList.contains( 'ast-submenu-expanded' ) ) {
						parent_li.querySelector( '.sub-menu, .children' ).style.display = 'block';
					} else {
						parent_li.querySelector( '.sub-menu, .children' ).style.display = 'none';
					}
				}
			}, false);
		};
	};

	var __main_header_all 	= document.querySelectorAll( '.main-header-bar-navigation' );
	var menu_toggle_all 	= document.querySelectorAll( '.main-header-menu-toggle' );

	if ( menu_toggle_all.length > 0 ) {

		for (var i = 0; i < menu_toggle_all.length; i++) {
			
			menu_toggle_all[i].setAttribute('data-index', i);

			menu_toggle_all[i].addEventListener( 'click', function( event ) {
		    	event.preventDefault();

		    	var event_index = this.getAttribute( 'data-index' );

		    	var menuHasChildren = __main_header_all[event_index].querySelectorAll( '.menu-item-has-children, .page_item_has_children' );
				for ( var i = 0; i < menuHasChildren.length; i++ ) {
					menuHasChildren[i].classList.remove( 'ast-submenu-expanded' );
					var menuHasChildrenSubMenu = menuHasChildren[i].querySelectorAll( '.sub-menu, .children' );		
					for (var j = 0; j < menuHasChildrenSubMenu.length; j++) {		
						menuHasChildrenSubMenu[j].style.display = 'none';		
					};
				}

				var rel = this.getAttribute( 'rel' ) || '';

				switch ( rel ) {
					case 'main-menu':
							toggleClass( __main_header_all[event_index], 'toggle-on' );
							toggleClass( menu_toggle_all[event_index], 'toggled' );
							if ( __main_header_all[event_index].classList.contains( 'toggle-on' ) ) {		
								__main_header_all[event_index].style.display = 'block';		
							} else {		
								__main_header_all[event_index].style.display = '';		
							}
						break;
				}
		    }, false);
			
			var parentList = __main_header_all[i].querySelectorAll( 'ul.main-header-menu li' );
			AstraNavigationMenu( parentList );
		 	
		 	var astra_menu_toggle = __main_header_all[i].querySelectorAll( 'ul.main-header-menu .ast-menu-toggle' );
			AstraToggleMenu( astra_menu_toggle );


		};

	}
	
	document.body.addEventListener("astra-header-responsive-enabled", function() {

		if ( __main_header_all.length > 0 ) {

			for (var i = 0; i < __main_header_all.length; i++) {
				if( null != __main_header_all[i] ) {
					__main_header_all[i].classList.remove( 'toggle-on' );
					__main_header_all[i].style.display = '';
				}

				var sub_menu = __main_header_all[i].getElementsByClassName( 'sub-menu' );
				for ( var j = 0; j < sub_menu.length; j++ ) {
					sub_menu[j].style.display = '';
				}
				var child_menu = __main_header_all[i].getElementsByClassName( 'children' );
				for ( var k = 0; k < child_menu.length; k++ ) {
					child_menu[k].style.display = '';
				}

				var searchIcons = __main_header_all[i].getElementsByClassName( 'ast-search-menu-icon' );
				for ( var l = 0; l < searchIcons.length; l++ ) {
					searchIcons[l].classList.remove( 'ast-dropdown-active' );
					searchIcons[l].style.display = '';
				}
			}
		}
	}, false);
	
	/* Add break point Class and related trigger */
	var updateHeaderBreakPoint = function () {

		var break_point = astra.break_point,
			headerWrap = document.querySelectorAll( '.main-header-bar-wrap' );

		if ( headerWrap.length > 0  ) {
			for ( var i = 0; i < headerWrap.length; i++ ) {

				if ( headerWrap[i].tagName == 'DIV' && headerWrap[i].classList.contains( 'main-header-bar-wrap' ) ) {

					var header_content_bp = window.getComputedStyle( headerWrap[i] ).content;

					// Edge/Explorer header break point.
					if( isEdge || isIE || header_content_bp === 'normal' ) {
						if( window.innerWidth <= break_point ) {
							header_content_bp = break_point;
						}
					}

					header_content_bp = header_content_bp.replace( /[^0-9]/g, '' );
					header_content_bp = parseInt( header_content_bp );

					// `ast-header-break-point` class will use for Responsive Style of Header.
					if ( header_content_bp != break_point ) {
						//remove menu toggled class.
						if ( null != menu_toggle_all[i] ) {
							menu_toggle_all[i].classList.remove( 'toggled' );
						}
						document.body.classList.remove( "ast-header-break-point" );
						var responsive_enabled = new CustomEvent( "astra-header-responsive-enabled" );
						document.body.dispatchEvent( responsive_enabled );

					} else {

						document.body.classList.add( "ast-header-break-point" );
						var responsive_disabled = new CustomEvent( "astra-header-responsive-disabled" );
						document.body.dispatchEvent( responsive_disabled );
					}
				}
			}
		}
	}

	window.addEventListener("resize", function() {
		updateHeaderBreakPoint();
	});

	updateHeaderBreakPoint();
	
	/* Search Script */
	var SearchIcons = document.getElementsByClassName( 'astra-search-icon' );
	for (var i = 0; i < SearchIcons.length; i++) {

		SearchIcons[i].onclick = function() {
			if ( this.classList.contains( 'slide-search' ) ) {
				var sibling = this.parentNode.parentNode.querySelector( '.ast-search-menu-icon' );
				if ( ! sibling.classList.contains( 'ast-dropdown-active' ) ) {
					sibling.classList.add( 'ast-dropdown-active' );
					sibling.querySelector( '.search-field' ).setAttribute('autocomplete','off');
					setTimeout(function() {
						sibling.querySelector( '.search-field' ).focus();
					},200);
				} else {
					sibling.classList.remove( 'ast-dropdown-active' );
				}
			}
		}
	};

	/* Hide Dropdown on body click*/
	document.body.onclick = function( event ) {
		if ( ! this.classList.contains( 'ast-header-break-point' ) ) {
			if ( ! event.target.classList.contains( 'ast-search-menu-icon' ) && getParents( event.target, '.ast-search-menu-icon' ).length === 0 && getParents( event.target, '.ast-search-icon' ).length === 0  ) {

				var dropdownSearchWrap = document.getElementsByClassName( 'ast-search-menu-icon' );

				for (var i = 0; i < dropdownSearchWrap.length; i++) {
					dropdownSearchWrap[i].classList.remove( 'ast-dropdown-active' );
				};
			}
		}
	}
	/**
	 * Navigation Keyboard Navigation.
	 */
	var container, button, menu, links, subMenus, i, len;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

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
	links    = menu.getElementsByTagName( 'a' );
	subMenus = menu.getElementsByTagName( 'ul' );


	// Set menu items with submenus to aria-haspopup="true".
	for ( i = 0, len = subMenus.length; i < len; i++ ) {
		subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
	}

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

	/**
	 * Toggles `focus` class to allow submenu access on tablets.
	 */
	( function( container ) {
		var touchStartFn, i,
			parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

		if ( 'ontouchstart' in window ) {
			touchStartFn = function( e ) {
				var menuItem = this.parentNode, i;

				if ( ! menuItem.classList.contains( 'focus' ) ) {
					e.preventDefault();
					for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
						if ( menuItem === menuItem.parentNode.children[i] ) {
							continue;
						}
						menuItem.parentNode.children[i].classList.remove( 'focus' );
					}
					menuItem.classList.add( 'focus' );
				} else {
					menuItem.classList.remove( 'focus' );
				}
			};

			for ( i = 0; i < parentLink.length; ++i ) {
				parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
			}
		}
	}( container ) );

} )();
