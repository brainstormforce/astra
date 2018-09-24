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

	var menu_toggle_all 	= document.querySelectorAll( '.main-header-menu-toggle' );

	/* Add break point Class and related trigger */
	var updateHeaderBreakPoint = function () {

		var break_point = astra.break_point,
			headerWrap = document.querySelectorAll( '.main-header-bar-wrap' );

		if ( headerWrap.length > 0  ) {
			for ( var i = 0; i < headerWrap.length; i++ ) {

				if ( headerWrap[i].tagName == 'DIV' && headerWrap[i].classList.contains( 'main-header-bar-wrap' ) ) {

					var header_content_bp = window.getComputedStyle( headerWrap[i], '::before' ).getPropertyValue('content');

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
						document.body.classList.add("ast-desktop");
						astraTriggerEvent( document.body, "astra-header-responsive-enabled" );

					} else {

						document.body.classList.add( "ast-header-break-point" );
						document.body.classList.remove("ast-desktop");
						astraTriggerEvent( document.body, "astra-header-responsive-disabled" )						
					}
				}
			}
		}
	}

	updateHeaderBreakPoint();

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

				if ( parent_li.classList.contains( 'ast-submenu-expanded' ) && document.querySelector("header.site-header").classList.contains("ast-menu-toggle-link") ) {
					if( ! this.classList.contains( 'ast-menu-toggle' ) ) {
					    var link = parent_li.querySelector('a').getAttribute('href');
					    if( '' !== link || '#' !== link ) {
	    				    window.location = link;
	                    }
					}
				}

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

	if ( menu_toggle_all.length > 0 ) {

		for (var i = 0; i < menu_toggle_all.length; i++) {
			
			menu_toggle_all[i].setAttribute('data-index', i);

			menu_toggle_all[i].addEventListener( 'click', function( event ) {
		    	event.preventDefault();

		    	var event_index = this.getAttribute( 'data-index' );

		    	if ( 'undefined' === typeof __main_header_all[event_index] ) {

		    		return false;
		    	}

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
								document.body.classList.add( "ast-main-header-nav-open" );	
							} else {		
								__main_header_all[event_index].style.display = '';
								document.body.classList.remove( "ast-main-header-nav-open" );
							}
						break;
				}
		    }, false);
			
			if ( 'undefined' !== typeof __main_header_all[i] ) {
				var parentList = __main_header_all[i].querySelectorAll( 'ul.main-header-menu li' );
				AstraNavigationMenu( parentList );

				if ( document.querySelector("header.site-header").classList.contains("ast-menu-toggle-link") ) {
					var astra_menu_toggle 	   = __main_header_all[i].querySelectorAll( '.ast-header-break-point .main-header-menu .menu-item-has-children > a, .ast-header-break-point .main-header-menu .page_item_has_children > a, .ast-header-break-point ul.main-header-menu .ast-menu-toggle' );
				} else { 
				 	var astra_menu_toggle      = __main_header_all[i].querySelectorAll( 'ul.main-header-menu .ast-menu-toggle' );
				}

                AstraToggleMenu( astra_menu_toggle );
			 	
			}
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

	window.addEventListener("resize", function() {
		updateHeaderBreakPoint();
	});


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

	    bodyElement = document.body;
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
	};

	/* Hide Dropdown on body click*/
	document.body.onclick = function( event ) {
		if ( ! event.target.classList.contains( 'ast-search-menu-icon' ) && getParents( event.target, '.ast-search-menu-icon' ).length === 0 && getParents( event.target, '.ast-search-icon' ).length === 0  ) {
            var dropdownSearchWrap = document.getElementsByClassName( 'ast-search-menu-icon' );
            
            for (var i = 0; i < dropdownSearchWrap.length; i++) {
                dropdownSearchWrap[i].classList.remove( 'ast-dropdown-active' );
            };
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
		links[i].addEventListener( 'click', toggleClose, true );
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

        if( self && ! self.classList.contains('astra-search-icon') ) {
            var link = new String( self );
            if( link.indexOf( hash ) !== -1 ) {

                if ( document.body.classList.contains('ast-header-break-point') && ! document.querySelector("header.site-header").classList.contains("ast-menu-toggle-link") ) {
	                var main_header_menu_toggle = document.querySelector( '.main-header-menu-toggle' );
	                main_header_menu_toggle.classList.remove( 'toggled' );

	                var main_header_bar_navigation = document.querySelector( '.main-header-bar-navigation' );
	                main_header_bar_navigation.classList.remove( 'toggle-on' );

					main_header_bar_navigation.style.display = 'none';
					
					astraTriggerEvent( document.querySelector('body'), 'astraMenuHashLinkClicked' );
                }
            }
        }        
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

} )();
