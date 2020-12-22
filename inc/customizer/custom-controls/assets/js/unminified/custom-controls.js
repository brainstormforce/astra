/**
 * File typography.js
 *
 * Handles Typography of the site
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
	 * @class AstTypography
	 */
	AstTypography = {

		/**
		 * Initializes our custom logic for the Customizer.
		 *
		 * @since 1.0.0
		 * @method init
		 */
		init: function() {
			AstTypography._initFonts();
		},

		/**
		 * Initializes logic for font controls.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _initFonts
		 */
		_initFonts: function()
		{
			$( '.customize-control-ast-font-family select' ).each( function(e) {

				if( 'undefined' != typeof astra.customizer ) {
					var fonts = astra.customizer.settings.google_fonts;
					var optionName = $(this).data('name');

					$(this).html( fonts );

					// Set inherit option text defined in control parameters.
					$("select[data-name='" + optionName + "'] option[value='inherit']").text( $(this).data('inherit') );

					var font_val = $(this).data('value');

					$(this).val( font_val );
				}
			});

			$( '.customize-control-ast-font-family select' ).each( AstTypography._initFont );
			// Added select2 for all font family & font variant.
			$('.customize-control-ast-font-family select, .customize-control-ast-font-variant select').selectWoo();

			$('.customize-control-ast-font-variant select').on('select2:unselecting', function (e) {
				var variantSelect = $(this).data( 'customize-setting-link' ),
				    unselectedValue = e.params.args.data.id || '';

				if ( unselectedValue ) {
					$(this).find('option[value="' + e.params.args.data.id + '"]').removeAttr('selected');
					if ( null === $(this).val() ) {
						api( variantSelect ).set( '' );
					}
				}
			});
		},

		/**
		 * Initializes logic for a single font control.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _initFont
		 */
		_initFont: function()
		{
			var select  = $( this ),
			link    = select.data( 'customize-setting-link' ),
			weight  = select.data( 'connected-control' ),
			variant  = select.data( 'connected-variant' );

			if ( 'undefined' != typeof weight ) {
				api( link ).bind( AstTypography._fontSelectChange );
				AstTypography._setFontWeightOptions.apply( api( link ), [ true ] );
			}

			if ( 'undefined' != typeof variant ) {
				api( link ).bind( AstTypography._fontSelectChange );
				AstTypography._setFontVarianttOptions.apply( api( link ), [ true ] );
			}
		},

		/**
		 * Callback for when a font control changes.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _fontSelectChange
		 */
		_fontSelectChange: function()
		{
			var fontSelect          = api.control( this.id ).container.find( 'select' ),
			variants            	= fontSelect.data( 'connected-variant' );

			AstTypography._setFontWeightOptions.apply( this, [ false ] );
			
			if ( 'undefined' != typeof variants ) {
				AstTypography._setFontVarianttOptions.apply( this, [ false ] );
			}
		},

		/**
		 * Clean font name.
		 *
		 * Google Fonts are saved as {'Font Name', Category}. This function cleanes this up to retreive only the {Font Name}.
		 *
		 * @since  1.3.0
		 * @param  {String} fontValue Name of the font.
		 * 
		 * @return {String}  Font name where commas and inverted commas are removed if the font is a Google Font.
		 */
		_cleanGoogleFonts: function(fontValue)
		{
			// Bail if fontVAlue does not contain a comma.
			if ( ! fontValue.includes(',') ) return fontValue;

			var splitFont 	= fontValue.split(',');
			var pattern 	= new RegExp("'", 'gi');

			// Check if the cleaned font exists in the Google fonts array.
			var googleFontValue = splitFont[0].replace(pattern, '');
			if ( 'undefined' != typeof AstFontFamilies.google[ googleFontValue ] ) {
				fontValue = googleFontValue;
			}

			return fontValue;
		},

		/**
		 * Get font Weights.
		 *
		 * This function gets the font weights values respective to the selected fonts family{Font Name}.
		 *
		 * @since  1.5.2
		 * @param  {String} fontValue Name of the font.
		 * 
		 * @return {String}  Available font weights for the selected fonts.
		 */
		_getWeightObject: function(fontValue)
		{
			var weightObject        = [ '400', '600' ];
			if ( fontValue == 'inherit' ) {
				weightObject = [ '100','200','300','400','500','600','700','800','900' ];
			} else if ( 'undefined' != typeof AstFontFamilies.system[ fontValue ] ) {
				weightObject = AstFontFamilies.system[ fontValue ].weights;
			} else if ( 'undefined' != typeof AstFontFamilies.google[ fontValue ] ) {
				weightObject = AstFontFamilies.google[ fontValue ][0];
				weightObject = Object.keys(weightObject).map(function(k) {
				  return weightObject[k];
				});
			} else if ( 'undefined' != typeof AstFontFamilies.custom[ fontValue ] ) {
				weightObject = AstFontFamilies.custom[ fontValue ].weights;
			}

			return weightObject;
		},

		/**
		 * Sets the options for a font weight control when a
		 * font family control changes.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _setFontWeightOptions
		 * @param {Boolean} init Whether or not we're initializing this font weight control.
		 */
		_setFontWeightOptions: function( init )
		{
			var i               = 0,
			fontSelect          = api.control( this.id ).container.find( 'select' ),
			fontValue           = this(),
			selected            = '',
			weightKey           = fontSelect.data( 'connected-control' ),
			inherit             = fontSelect.data( 'inherit' ),
			weightSelect        = api.control( weightKey ).container.find( 'select' ),
			currentWeightTitle  = weightSelect.data( 'inherit' ),
			weightValue         = init ? weightSelect.val() : '400',
			inheritWeightObject = [ 'inherit' ],
			weightObject        = [ '400', '600' ],
			weightOptions       = '',
			weightMap           = astraTypo;
			if ( fontValue == 'inherit' ) {
				weightValue     = init ? weightSelect.val() : 'inherit';
			}

			var fontValue = AstTypography._cleanGoogleFonts(fontValue);
			var weightObject = AstTypography._getWeightObject( fontValue );

			weightObject = $.merge( inheritWeightObject, weightObject )
			weightMap[ 'inherit' ] = currentWeightTitle;
			for ( ; i < weightObject.length; i++ ) {

				if ( 0 === i && -1 === $.inArray( weightValue, weightObject ) ) {
					weightValue = weightObject[ 0 ];
					selected 	= ' selected="selected"';
				} else {
					selected = weightObject[ i ] == weightValue ? ' selected="selected"' : '';
				}
				if( ! weightObject[ i ].includes( "italic" ) ){
					weightOptions += '<option value="' + weightObject[ i ] + '"' + selected + '>' + weightMap[ weightObject[ i ] ] + '</option>';
				}
			}

			weightSelect.html( weightOptions );

			if ( ! init ) {
				api( weightKey ).set( '' );
				api( weightKey ).set( weightValue );
			}
		},
		/**
		 * Sets the options for a font variant control when a
		 * font family control changes.
		 *
		 * @since 1.5.2
		 * @access private
		 * @method _setFontVarianttOptions
		 * @param {Boolean} init Whether or not we're initializing this font variant control.
		 */
		_setFontVarianttOptions: function( init )
		{
				var i               = 0,
				fontSelect          = api.control( this.id ).container.find( 'select' ),
				fontValue           = this(),
				selected            = '',
				variants            = fontSelect.data( 'connected-variant' ),
				inherit             = fontSelect.data( 'inherit' ),
				variantSelect       = api.control( variants ).container.find( 'select' ),
				variantSavedField   = api.control( variants ).container.find( '.ast-font-variant-hidden-value' ),
				weightValue        = '',
				weightOptions       = '',
				currentWeightTitle  = variantSelect.data( 'inherit' ),
				weightMap           = astraTypo;

				var variantArray = variantSavedField.val().split(',');

				// Hide font variant for any ohter fonts then Google
				var selectedOptionGroup = fontSelect.find('option[value="' + fontSelect.val() + '"]').closest('optgroup').attr('label') || '';
				if ( 'Google' == selectedOptionGroup ) {
					variantSelect.parent().removeClass('ast-hide');
				} else{
					variantSelect.parent().addClass('ast-hide');
				}

				var fontValue = AstTypography._cleanGoogleFonts(fontValue);
				var weightObject = AstTypography._getWeightObject( fontValue );

				weightMap[ 'inherit' ] = currentWeightTitle;
				
				for ( var i = 0; i < weightObject.length; i++ ) {
					for ( var e = 0; e < variantArray.length; e++ ) {
						if ( weightObject[i] === variantArray[e] ) {
							weightValue = weightObject[ i ];
							selected 	= ' selected="selected"';
						} else{
							selected = ( weightObject[ i ] == weightValue ) ? ' selected="selected"' : '';
						}
					}
					weightOptions += '<option value="' + weightObject[ i ] + '"' + selected + '>' + weightMap[ weightObject[ i ] ] + '</option>';
				}

				variantSelect.html( weightOptions );
				if ( ! init ) {
					api( variants ).set( '' );
				}
		},
	};

	$( function() { AstTypography.init(); } );

})( jQuery );

/*!
 * SelectWoo 1.0.1
 * https://github.com/woocommerce/selectWoo
 *
 * Released under the MIT license
 * https://github.com/woocommerce/selectWoo/blob/master/LICENSE.md
 */
(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = function (root, jQuery) {
      if (jQuery === undefined) {
        // require('jQuery') returns a factory that requires window to
        // build a jQuery instance, we normalize how we use modules
        // that require this pattern but the window provided is a noop
        // if it's defined (how jquery works)
        if (typeof window !== 'undefined') {
          jQuery = require('jquery');
        }
        else {
          jQuery = require('jquery')(root);
        }
      }
      factory(jQuery);
      return jQuery;
    };
  } else {
    // Browser globals
    factory(jQuery);
  }
} (function (jQuery) {
  // This is needed so we can catch the AMD loader configuration and use it
  // The inner file should be wrapped (by `banner.start.js`) in a function that
  // returns the AMD loader references.
  var S2 =(function () {
  // Restore the Select2 AMD loader so it can be used
  // Needed mostly in the language files, where the loader is not inserted
  if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd) {
    var S2 = jQuery.fn.select2.amd;
  }
var S2;(function () { if (!S2 || !S2.requirejs) {
if (!S2) { S2 = {}; } else { require = S2; }
/**
 * @license almond 0.3.3 Copyright jQuery Foundation and other contributors.
 * Released under MIT license, http://github.com/requirejs/almond/LICENSE
 */
//Going sloppy to avoid 'use strict' string cost, but strict practices should
//be followed.
/*global setTimeout: false */

var requirejs, require, define;
(function (undef) {
    var main, req, makeMap, handlers,
        defined = {},
        waiting = {},
        config = {},
        defining = {},
        hasOwn = Object.prototype.hasOwnProperty,
        aps = [].slice,
        jsSuffixRegExp = /\.js$/;

    function hasProp(obj, prop) {
        return hasOwn.call(obj, prop);
    }

    /**
     * Given a relative module name, like ./something, normalize it to
     * a real name that can be mapped to a path.
     * @param {String} name the relative name
     * @param {String} baseName a real name that the name arg is relative
     * to.
     * @returns {String} normalized name
     */
    function normalize(name, baseName) {
        var nameParts, nameSegment, mapValue, foundMap, lastIndex,
            foundI, foundStarMap, starI, i, j, part, normalizedBaseParts,
            baseParts = baseName && baseName.split("/"),
            map = config.map,
            starMap = (map && map['*']) || {};

        //Adjust any relative paths.
        if (name) {
            name = name.split('/');
            lastIndex = name.length - 1;

            // If wanting node ID compatibility, strip .js from end
            // of IDs. Have to do this here, and not in nameToUrl
            // because node allows either .js or non .js to map
            // to same file.
            if (config.nodeIdCompat && jsSuffixRegExp.test(name[lastIndex])) {
                name[lastIndex] = name[lastIndex].replace(jsSuffixRegExp, '');
            }

            // Starts with a '.' so need the baseName
            if (name[0].charAt(0) === '.' && baseParts) {
                //Convert baseName to array, and lop off the last part,
                //so that . matches that 'directory' and not name of the baseName's
                //module. For instance, baseName of 'one/two/three', maps to
                //'one/two/three.js', but we want the directory, 'one/two' for
                //this normalization.
                normalizedBaseParts = baseParts.slice(0, baseParts.length - 1);
                name = normalizedBaseParts.concat(name);
            }

            //start trimDots
            for (i = 0; i < name.length; i++) {
                part = name[i];
                if (part === '.') {
                    name.splice(i, 1);
                    i -= 1;
                } else if (part === '..') {
                    // If at the start, or previous value is still ..,
                    // keep them so that when converted to a path it may
                    // still work when converted to a path, even though
                    // as an ID it is less than ideal. In larger point
                    // releases, may be better to just kick out an error.
                    if (i === 0 || (i === 1 && name[2] === '..') || name[i - 1] === '..') {
                        continue;
                    } else if (i > 0) {
                        name.splice(i - 1, 2);
                        i -= 2;
                    }
                }
            }
            //end trimDots

            name = name.join('/');
        }

        //Apply map config if available.
        if ((baseParts || starMap) && map) {
            nameParts = name.split('/');

            for (i = nameParts.length; i > 0; i -= 1) {
                nameSegment = nameParts.slice(0, i).join("/");

                if (baseParts) {
                    //Find the longest baseName segment match in the config.
                    //So, do joins on the biggest to smallest lengths of baseParts.
                    for (j = baseParts.length; j > 0; j -= 1) {
                        mapValue = map[baseParts.slice(0, j).join('/')];

                        //baseName segment has  config, find if it has one for
                        //this name.
                        if (mapValue) {
                            mapValue = mapValue[nameSegment];
                            if (mapValue) {
                                //Match, update name to the new value.
                                foundMap = mapValue;
                                foundI = i;
                                break;
                            }
                        }
                    }
                }

                if (foundMap) {
                    break;
                }

                //Check for a star map match, but just hold on to it,
                //if there is a shorter segment match later in a matching
                //config, then favor over this star map.
                if (!foundStarMap && starMap && starMap[nameSegment]) {
                    foundStarMap = starMap[nameSegment];
                    starI = i;
                }
            }

            if (!foundMap && foundStarMap) {
                foundMap = foundStarMap;
                foundI = starI;
            }

            if (foundMap) {
                nameParts.splice(0, foundI, foundMap);
                name = nameParts.join('/');
            }
        }

        return name;
    }

    function makeRequire(relName, forceSync) {
        return function () {
            //A version of a require function that passes a moduleName
            //value for items that may need to
            //look up paths relative to the moduleName
            var args = aps.call(arguments, 0);

            //If first arg is not require('string'), and there is only
            //one arg, it is the array form without a callback. Insert
            //a null so that the following concat is correct.
            if (typeof args[0] !== 'string' && args.length === 1) {
                args.push(null);
            }
            return req.apply(undef, args.concat([relName, forceSync]));
        };
    }

    function makeNormalize(relName) {
        return function (name) {
            return normalize(name, relName);
        };
    }

    function makeLoad(depName) {
        return function (value) {
            defined[depName] = value;
        };
    }

    function callDep(name) {
        if (hasProp(waiting, name)) {
            var args = waiting[name];
            delete waiting[name];
            defining[name] = true;
            main.apply(undef, args);
        }

        if (!hasProp(defined, name) && !hasProp(defining, name)) {
            throw new Error('No ' + name);
        }
        return defined[name];
    }

    //Turns a plugin!resource to [plugin, resource]
    //with the plugin being undefined if the name
    //did not have a plugin prefix.
    function splitPrefix(name) {
        var prefix,
            index = name ? name.indexOf('!') : -1;
        if (index > -1) {
            prefix = name.substring(0, index);
            name = name.substring(index + 1, name.length);
        }
        return [prefix, name];
    }

    //Creates a parts array for a relName where first part is plugin ID,
    //second part is resource ID. Assumes relName has already been normalized.
    function makeRelParts(relName) {
        return relName ? splitPrefix(relName) : [];
    }

    /**
     * Makes a name map, normalizing the name, and using a plugin
     * for normalization if necessary. Grabs a ref to plugin
     * too, as an optimization.
     */
    makeMap = function (name, relParts) {
        var plugin,
            parts = splitPrefix(name),
            prefix = parts[0],
            relResourceName = relParts[1];

        name = parts[1];

        if (prefix) {
            prefix = normalize(prefix, relResourceName);
            plugin = callDep(prefix);
        }

        //Normalize according
        if (prefix) {
            if (plugin && plugin.normalize) {
                name = plugin.normalize(name, makeNormalize(relResourceName));
            } else {
                name = normalize(name, relResourceName);
            }
        } else {
            name = normalize(name, relResourceName);
            parts = splitPrefix(name);
            prefix = parts[0];
            name = parts[1];
            if (prefix) {
                plugin = callDep(prefix);
            }
        }

        //Using ridiculous property names for space reasons
        return {
            f: prefix ? prefix + '!' + name : name, //fullName
            n: name,
            pr: prefix,
            p: plugin
        };
    };

    function makeConfig(name) {
        return function () {
            return (config && config.config && config.config[name]) || {};
        };
    }

    handlers = {
        require: function (name) {
            return makeRequire(name);
        },
        exports: function (name) {
            var e = defined[name];
            if (typeof e !== 'undefined') {
                return e;
            } else {
                return (defined[name] = {});
            }
        },
        module: function (name) {
            return {
                id: name,
                uri: '',
                exports: defined[name],
                config: makeConfig(name)
            };
        }
    };

    main = function (name, deps, callback, relName) {
        var cjsModule, depName, ret, map, i, relParts,
            args = [],
            callbackType = typeof callback,
            usingExports;

        //Use name if no relName
        relName = relName || name;
        relParts = makeRelParts(relName);

        //Call the callback to define the module, if necessary.
        if (callbackType === 'undefined' || callbackType === 'function') {
            //Pull out the defined dependencies and pass the ordered
            //values to the callback.
            //Default to [require, exports, module] if no deps
            deps = !deps.length && callback.length ? ['require', 'exports', 'module'] : deps;
            for (i = 0; i < deps.length; i += 1) {
                map = makeMap(deps[i], relParts);
                depName = map.f;

                //Fast path CommonJS standard dependencies.
                if (depName === "require") {
                    args[i] = handlers.require(name);
                } else if (depName === "exports") {
                    //CommonJS module spec 1.1
                    args[i] = handlers.exports(name);
                    usingExports = true;
                } else if (depName === "module") {
                    //CommonJS module spec 1.1
                    cjsModule = args[i] = handlers.module(name);
                } else if (hasProp(defined, depName) ||
                           hasProp(waiting, depName) ||
                           hasProp(defining, depName)) {
                    args[i] = callDep(depName);
                } else if (map.p) {
                    map.p.load(map.n, makeRequire(relName, true), makeLoad(depName), {});
                    args[i] = defined[depName];
                } else {
                    throw new Error(name + ' missing ' + depName);
                }
            }

            ret = callback ? callback.apply(defined[name], args) : undefined;

            if (name) {
                //If setting exports via "module" is in play,
                //favor that over return value and exports. After that,
                //favor a non-undefined return value over exports use.
                if (cjsModule && cjsModule.exports !== undef &&
                        cjsModule.exports !== defined[name]) {
                    defined[name] = cjsModule.exports;
                } else if (ret !== undef || !usingExports) {
                    //Use the return value from the function.
                    defined[name] = ret;
                }
            }
        } else if (name) {
            //May just be an object definition for the module. Only
            //worry about defining if have a module name.
            defined[name] = callback;
        }
    };

    requirejs = require = req = function (deps, callback, relName, forceSync, alt) {
        if (typeof deps === "string") {
            if (handlers[deps]) {
                //callback in this case is really relName
                return handlers[deps](callback);
            }
            //Just return the module wanted. In this scenario, the
            //deps arg is the module name, and second arg (if passed)
            //is just the relName.
            //Normalize module name, if it contains . or ..
            return callDep(makeMap(deps, makeRelParts(callback)).f);
        } else if (!deps.splice) {
            //deps is a config object, not an array.
            config = deps;
            if (config.deps) {
                req(config.deps, config.callback);
            }
            if (!callback) {
                return;
            }

            if (callback.splice) {
                //callback is an array, which means it is a dependency list.
                //Adjust args if there are dependencies
                deps = callback;
                callback = relName;
                relName = null;
            } else {
                deps = undef;
            }
        }

        //Support require(['a'])
        callback = callback || function () {};

        //If relName is a function, it is an errback handler,
        //so remove it.
        if (typeof relName === 'function') {
            relName = forceSync;
            forceSync = alt;
        }

        //Simulate async callback;
        if (forceSync) {
            main(undef, deps, callback, relName);
        } else {
            //Using a non-zero value because of concern for what old browsers
            //do, and latest browsers "upgrade" to 4 if lower value is used:
            //http://www.whatwg.org/specs/web-apps/current-work/multipage/timers.html#dom-windowtimers-settimeout:
            //If want a value immediately, use require('id') instead -- something
            //that works in almond on the global level, but not guaranteed and
            //unlikely to work in other AMD implementations.
            setTimeout(function () {
                main(undef, deps, callback, relName);
            }, 4);
        }

        return req;
    };

    /**
     * Just drops the config on the floor, but returns req in case
     * the config return value is used.
     */
    req.config = function (cfg) {
        return req(cfg);
    };

    /**
     * Expose module registry for debugging and tooling
     */
    requirejs._defined = defined;

    define = function (name, deps, callback) {
        if (typeof name !== 'string') {
            throw new Error('See almond README: incorrect module build, no module name');
        }

        //This module may not have dependencies
        if (!deps.splice) {
            //deps is not an array, so probably means
            //an object literal or factory function for
            //the value. Adjust args.
            callback = deps;
            deps = [];
        }

        if (!hasProp(defined, name) && !hasProp(waiting, name)) {
            waiting[name] = [name, deps, callback];
        }
    };

    define.amd = {
        jQuery: true
    };
}());

S2.requirejs = requirejs;S2.require = require;S2.define = define;
}
}());
S2.define("almond", function(){});

/* global jQuery:false, $:false */
S2.define('jquery',[],function () {
  var _$ = jQuery || $;

  if (_$ == null && console && console.error) {
    console.error(
      'Select2: An instance of jQuery or a jQuery-compatible library was not ' +
      'found. Make sure that you are including jQuery before Select2 on your ' +
      'web page.'
    );
  }

  return _$;
});

S2.define('select2/utils',[
  'jquery'
], function ($) {
  var Utils = {};

  Utils.Extend = function (ChildClass, SuperClass) {
    var __hasProp = {}.hasOwnProperty;

    function BaseConstructor () {
      this.constructor = ChildClass;
    }

    for (var key in SuperClass) {
      if (__hasProp.call(SuperClass, key)) {
        ChildClass[key] = SuperClass[key];
      }
    }

    BaseConstructor.prototype = SuperClass.prototype;
    ChildClass.prototype = new BaseConstructor();
    ChildClass.__super__ = SuperClass.prototype;

    return ChildClass;
  };

  function getMethods (theClass) {
    var proto = theClass.prototype;

    var methods = [];

    for (var methodName in proto) {
      var m = proto[methodName];

      if (typeof m !== 'function') {
        continue;
      }

      if (methodName === 'constructor') {
        continue;
      }

      methods.push(methodName);
    }

    return methods;
  }

  Utils.Decorate = function (SuperClass, DecoratorClass) {
    var decoratedMethods = getMethods(DecoratorClass);
    var superMethods = getMethods(SuperClass);

    function DecoratedClass () {
      var unshift = Array.prototype.unshift;

      var argCount = DecoratorClass.prototype.constructor.length;

      var calledConstructor = SuperClass.prototype.constructor;

      if (argCount > 0) {
        unshift.call(arguments, SuperClass.prototype.constructor);

        calledConstructor = DecoratorClass.prototype.constructor;
      }

      calledConstructor.apply(this, arguments);
    }

    DecoratorClass.displayName = SuperClass.displayName;

    function ctr () {
      this.constructor = DecoratedClass;
    }

    DecoratedClass.prototype = new ctr();

    for (var m = 0; m < superMethods.length; m++) {
        var superMethod = superMethods[m];

        DecoratedClass.prototype[superMethod] =
          SuperClass.prototype[superMethod];
    }

    var calledMethod = function (methodName) {
      // Stub out the original method if it's not decorating an actual method
      var originalMethod = function () {};

      if (methodName in DecoratedClass.prototype) {
        originalMethod = DecoratedClass.prototype[methodName];
      }

      var decoratedMethod = DecoratorClass.prototype[methodName];

      return function () {
        var unshift = Array.prototype.unshift;

        unshift.call(arguments, originalMethod);

        return decoratedMethod.apply(this, arguments);
      };
    };

    for (var d = 0; d < decoratedMethods.length; d++) {
      var decoratedMethod = decoratedMethods[d];

      DecoratedClass.prototype[decoratedMethod] = calledMethod(decoratedMethod);
    }

    return DecoratedClass;
  };

  var Observable = function () {
    this.listeners = {};
  };

  Observable.prototype.on = function (event, callback) {
    this.listeners = this.listeners || {};

    if (event in this.listeners) {
      this.listeners[event].push(callback);
    } else {
      this.listeners[event] = [callback];
    }
  };

  Observable.prototype.trigger = function (event) {
    var slice = Array.prototype.slice;
    var params = slice.call(arguments, 1);

    this.listeners = this.listeners || {};

    // Params should always come in as an array
    if (params == null) {
      params = [];
    }

    // If there are no arguments to the event, use a temporary object
    if (params.length === 0) {
      params.push({});
    }

    // Set the `_type` of the first object to the event
    params[0]._type = event;

    if (event in this.listeners) {
      this.invoke(this.listeners[event], slice.call(arguments, 1));
    }

    if ('*' in this.listeners) {
      this.invoke(this.listeners['*'], arguments);
    }
  };

  Observable.prototype.invoke = function (listeners, params) {
    for (var i = 0, len = listeners.length; i < len; i++) {
      listeners[i].apply(this, params);
    }
  };

  Utils.Observable = Observable;

  Utils.generateChars = function (length) {
    var chars = '';

    for (var i = 0; i < length; i++) {
      var randomChar = Math.floor(Math.random() * 36);
      chars += randomChar.toString(36);
    }

    return chars;
  };

  Utils.bind = function (func, context) {
    return function () {
      func.apply(context, arguments);
    };
  };

  Utils._convertData = function (data) {
    for (var originalKey in data) {
      var keys = originalKey.split('-');

      var dataLevel = data;

      if (keys.length === 1) {
        continue;
      }

      for (var k = 0; k < keys.length; k++) {
        var key = keys[k];

        // Lowercase the first letter
        // By default, dash-separated becomes camelCase
        key = key.substring(0, 1).toLowerCase() + key.substring(1);

        if (!(key in dataLevel)) {
          dataLevel[key] = {};
        }

        if (k == keys.length - 1) {
          dataLevel[key] = data[originalKey];
        }

        dataLevel = dataLevel[key];
      }

      delete data[originalKey];
    }

    return data;
  };

  Utils.hasScroll = function (index, el) {
    // Adapted from the function created by @ShadowScripter
    // and adapted by @BillBarry on the Stack Exchange Code Review website.
    // The original code can be found at
    // http://codereview.stackexchange.com/q/13338
    // and was designed to be used with the Sizzle selector engine.

    var $el = $(el);
    var overflowX = el.style.overflowX;
    var overflowY = el.style.overflowY;

    //Check both x and y declarations
    if (overflowX === overflowY &&
        (overflowY === 'hidden' || overflowY === 'visible')) {
      return false;
    }

    if (overflowX === 'scroll' || overflowY === 'scroll') {
      return true;
    }

    return ($el.innerHeight() < el.scrollHeight ||
      $el.innerWidth() < el.scrollWidth);
  };

  Utils.escapeMarkup = function (markup) {
    var replaceMap = {
      '\\': '&#92;',
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      '\'': '&#39;',
      '/': '&#47;'
    };

    // Do not try to escape the markup if it's not a string
    if (typeof markup !== 'string') {
      return markup;
    }

    return String(markup).replace(/[&<>"'\/\\]/g, function (match) {
      return replaceMap[match];
    });
  };

  Utils.entityDecode = function(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
  }

  // Append an array of jQuery nodes to a given element.
  Utils.appendMany = function ($element, $nodes) {
    // jQuery 1.7.x does not support $.fn.append() with an array
    // Fall back to a jQuery object collection using $.fn.add()
    if ($.fn.jquery.substr(0, 3) === '1.7') {
      var $jqNodes = $();

      $.map($nodes, function (node) {
        $jqNodes = $jqNodes.add(node);
      });

      $nodes = $jqNodes;
    }

    $element.append($nodes);
  };

  // Determine whether the browser is on a touchscreen device.
  Utils.isTouchscreen = function() {
    if ('undefined' === typeof Utils._isTouchscreenCache) {
      Utils._isTouchscreenCache = 'ontouchstart' in document.documentElement;
    }
    return Utils._isTouchscreenCache;
  }

  return Utils;
});

S2.define('select2/results',[
  'jquery',
  './utils'
], function ($, Utils) {
  function Results ($element, options, dataAdapter) {
    this.$element = $element;
    this.data = dataAdapter;
    this.options = options;

    Results.__super__.constructor.call(this);
  }

  Utils.Extend(Results, Utils.Observable);

  Results.prototype.render = function () {
    var $results = $(
      '<ul class="select2-results__options" role="listbox" tabindex="-1"></ul>'
    );

    if (this.options.get('multiple')) {
      $results.attr('aria-multiselectable', 'true');
    }

    this.$results = $results;

    return $results;
  };

  Results.prototype.clear = function () {
    this.$results.empty();
  };

  Results.prototype.displayMessage = function (params) {
    var escapeMarkup = this.options.get('escapeMarkup');

    this.clear();
    this.hideLoading();

    var $message = $(
      '<li role="alert" aria-live="assertive"' +
      ' class="select2-results__option"></li>'
    );

    var message = this.options.get('translations').get(params.message);

    $message.append(
      escapeMarkup(
        message(params.args)
      )
    );

    $message[0].className += ' select2-results__message';

    this.$results.append($message);
  };

  Results.prototype.hideMessages = function () {
    this.$results.find('.select2-results__message').remove();
  };

  Results.prototype.append = function (data) {
    this.hideLoading();

    var $options = [];

    if (data.results == null || data.results.length === 0) {
      if (this.$results.children().length === 0) {
        this.trigger('results:message', {
          message: 'noResults'
        });
      }

      return;
    }

    data.results = this.sort(data.results);

    for (var d = 0; d < data.results.length; d++) {
      var item = data.results[d];

      var $option = this.option(item);

      $options.push($option);
    }

    this.$results.append($options);
  };

  Results.prototype.position = function ($results, $dropdown) {
    var $resultsContainer = $dropdown.find('.select2-results');
    $resultsContainer.append($results);
  };

  Results.prototype.sort = function (data) {
    var sorter = this.options.get('sorter');

    return sorter(data);
  };

  Results.prototype.highlightFirstItem = function () {
    var $options = this.$results
      .find('.select2-results__option[data-selected]');

    var $selected = $options.filter('[data-selected=true]');

    // Check if there are any selected options
    if ($selected.length > 0) {
      // If there are selected options, highlight the first
      $selected.first().trigger('mouseenter');
    } else {
      // If there are no selected options, highlight the first option
      // in the dropdown
      $options.first().trigger('mouseenter');
    }

    this.ensureHighlightVisible();
  };

  Results.prototype.setClasses = function () {
    var self = this;

    this.data.current(function (selected) {
      var selectedIds = $.map(selected, function (s) {
        return s.id.toString();
      });

      var $options = self.$results
        .find('.select2-results__option[data-selected]');

      $options.each(function () {
        var $option = $(this);

        var item = $.data(this, 'data');

        // id needs to be converted to a string when comparing
        var id = '' + item.id;

        if ((item.element != null && item.element.selected) ||
            (item.element == null && $.inArray(id, selectedIds) > -1)) {
          $option.attr('data-selected', 'true');
        } else {
          $option.attr('data-selected', 'false');
        }
      });

    });
  };

  Results.prototype.showLoading = function (params) {
    this.hideLoading();

    var loadingMore = this.options.get('translations').get('searching');

    var loading = {
      disabled: true,
      loading: true,
      text: loadingMore(params)
    };
    var $loading = this.option(loading);
    $loading.className += ' loading-results';

    this.$results.prepend($loading);
  };

  Results.prototype.hideLoading = function () {
    this.$results.find('.loading-results').remove();
  };

  Results.prototype.option = function (data) {
    var option = document.createElement('li');
    option.className = 'select2-results__option';

    var attrs = {
      'role': 'option',
      'data-selected': 'false',
      'tabindex': -1
    };

    if (data.disabled) {
      delete attrs['data-selected'];
      attrs['aria-disabled'] = 'true';
    }

    if (data.id == null) {
      delete attrs['data-selected'];
    }

    if (data._resultId != null) {
      option.id = data._resultId;
    }

    if (data.title) {
      option.title = data.title;
    }

    if (data.children) {
      attrs['aria-label'] = data.text;
      delete attrs['data-selected'];
    }

    for (var attr in attrs) {
      var val = attrs[attr];

      option.setAttribute(attr, val);
    }

    if (data.children) {
      var $option = $(option);

      var label = document.createElement('strong');
      label.className = 'select2-results__group';

      var $label = $(label);
      this.template(data, label);
      $label.attr('role', 'presentation');

      var $children = [];

      for (var c = 0; c < data.children.length; c++) {
        var child = data.children[c];

        var $child = this.option(child);

        $children.push($child);
      }

      var $childrenContainer = $('<ul></ul>', {
        'class': 'select2-results__options select2-results__options--nested',
        'role': 'listbox'
      });
      $childrenContainer.append($children);
      $option.attr('role', 'list');

      $option.append(label);
      $option.append($childrenContainer);
    } else {
      this.template(data, option);
    }

    $.data(option, 'data', data);

    return option;
  };

  Results.prototype.bind = function (container, $container) {
    var self = this;

    var id = container.id + '-results';

    this.$results.attr('id', id);

    container.on('results:all', function (params) {
      self.clear();
      self.append(params.data);

      if (container.isOpen()) {
        self.setClasses();
        self.highlightFirstItem();
      }
    });

    container.on('results:append', function (params) {
      self.append(params.data);

      if (container.isOpen()) {
        self.setClasses();
      }
    });

    container.on('query', function (params) {
      self.hideMessages();
      self.showLoading(params);
    });

    container.on('select', function () {
      if (!container.isOpen()) {
        return;
      }

      self.setClasses();
      self.highlightFirstItem();
    });

    container.on('unselect', function () {
      if (!container.isOpen()) {
        return;
      }

      self.setClasses();
      self.highlightFirstItem();
    });

    container.on('open', function () {
      // When the dropdown is open, aria-expended="true"
      self.$results.attr('aria-expanded', 'true');
      self.$results.attr('aria-hidden', 'false');

      self.setClasses();
      self.ensureHighlightVisible();
    });

    container.on('close', function () {
      // When the dropdown is closed, aria-expended="false"
      self.$results.attr('aria-expanded', 'false');
      self.$results.attr('aria-hidden', 'true');
      self.$results.removeAttr('aria-activedescendant');
    });

    container.on('results:toggle', function () {
      var $highlighted = self.getHighlightedResults();

      if ($highlighted.length === 0) {
        return;
      }

      $highlighted.trigger('mouseup');
    });

    container.on('results:select', function () {
      var $highlighted = self.getHighlightedResults();

      if ($highlighted.length === 0) {
        return;
      }

      var data = $highlighted.data('data');

      if ($highlighted.attr('data-selected') == 'true') {
        self.trigger('close', {});
      } else {
        self.trigger('select', {
          data: data
        });
      }
    });

    container.on('results:previous', function () {
      var $highlighted = self.getHighlightedResults();

      var $options = self.$results.find('[data-selected]');

      var currentIndex = $options.index($highlighted);

      // If we are already at te top, don't move further
      if (currentIndex === 0) {
        return;
      }

      var nextIndex = currentIndex - 1;

      // If none are highlighted, highlight the first
      if ($highlighted.length === 0) {
        nextIndex = 0;
      }

      var $next = $options.eq(nextIndex);

      $next.trigger('mouseenter');

      var currentOffset = self.$results.offset().top;
      var nextTop = $next.offset().top;
      var nextOffset = self.$results.scrollTop() + (nextTop - currentOffset);

      if (nextIndex === 0) {
        self.$results.scrollTop(0);
      } else if (nextTop - currentOffset < 0) {
        self.$results.scrollTop(nextOffset);
      }
    });

    container.on('results:next', function () {
      var $highlighted = self.getHighlightedResults();

      var $options = self.$results.find('[data-selected]');

      var currentIndex = $options.index($highlighted);

      var nextIndex = currentIndex + 1;

      // If we are at the last option, stay there
      if (nextIndex >= $options.length) {
        return;
      }

      var $next = $options.eq(nextIndex);

      $next.trigger('mouseenter');

      var currentOffset = self.$results.offset().top +
        self.$results.outerHeight(false);
      var nextBottom = $next.offset().top + $next.outerHeight(false);
      var nextOffset = self.$results.scrollTop() + nextBottom - currentOffset;

      if (nextIndex === 0) {
        self.$results.scrollTop(0);
      } else if (nextBottom > currentOffset) {
        self.$results.scrollTop(nextOffset);
      }
    });

    container.on('results:focus', function (params) {
      params.element.addClass('select2-results__option--highlighted').attr('aria-selected', 'true');
      self.$results.attr('aria-activedescendant', params.element.attr('id'));
    });

    container.on('results:message', function (params) {
      self.displayMessage(params);
    });

    if ($.fn.mousewheel) {
      this.$results.on('mousewheel', function (e) {
        var top = self.$results.scrollTop();

        var bottom = self.$results.get(0).scrollHeight - top + e.deltaY;

        var isAtTop = e.deltaY > 0 && top - e.deltaY <= 0;
        var isAtBottom = e.deltaY < 0 && bottom <= self.$results.height();

        if (isAtTop) {
          self.$results.scrollTop(0);

          e.preventDefault();
          e.stopPropagation();
        } else if (isAtBottom) {
          self.$results.scrollTop(
            self.$results.get(0).scrollHeight - self.$results.height()
          );

          e.preventDefault();
          e.stopPropagation();
        }
      });
    }

    this.$results.on('mouseup', '.select2-results__option[data-selected]',
      function (evt) {
      var $this = $(this);

      var data = $this.data('data');

      if ($this.attr('data-selected') === 'true') {
        if (self.options.get('multiple')) {
          self.trigger('unselect', {
            originalEvent: evt,
            data: data
          });
        } else {
          self.trigger('close', {});
        }

        return;
      }

      self.trigger('select', {
        originalEvent: evt,
        data: data
      });
    });

    this.$results.on('mouseenter', '.select2-results__option[data-selected]',
      function (evt) {
      var data = $(this).data('data');

      self.getHighlightedResults()
          .removeClass('select2-results__option--highlighted')
          .attr('aria-selected', 'false');

      self.trigger('results:focus', {
        data: data,
        element: $(this)
      });
    });
  };

  Results.prototype.getHighlightedResults = function () {
    var $highlighted = this.$results
    .find('.select2-results__option--highlighted');

    return $highlighted;
  };

  Results.prototype.destroy = function () {
    this.$results.remove();
  };

  Results.prototype.ensureHighlightVisible = function () {
    var $highlighted = this.getHighlightedResults();

    if ($highlighted.length === 0) {
      return;
    }

    var $options = this.$results.find('[data-selected]');

    var currentIndex = $options.index($highlighted);

    var currentOffset = this.$results.offset().top;
    var nextTop = $highlighted.offset().top;
    var nextOffset = this.$results.scrollTop() + (nextTop - currentOffset);

    var offsetDelta = nextTop - currentOffset;
    nextOffset -= $highlighted.outerHeight(false) * 2;

    if (currentIndex <= 2) {
      this.$results.scrollTop(0);
    } else if (offsetDelta > this.$results.outerHeight() || offsetDelta < 0) {
      this.$results.scrollTop(nextOffset);
    }
  };

  Results.prototype.template = function (result, container) {
    var template = this.options.get('templateResult');
    var escapeMarkup = this.options.get('escapeMarkup');

    var content = template(result, container);

    if (content == null) {
      container.style.display = 'none';
    } else if (typeof content === 'string') {
      container.innerHTML = escapeMarkup(content);
    } else {
      $(container).append(content);
    }
  };

  return Results;
});

S2.define('select2/keys',[

], function () {
  var KEYS = {
    BACKSPACE: 8,
    TAB: 9,
    ENTER: 13,
    SHIFT: 16,
    CTRL: 17,
    ALT: 18,
    ESC: 27,
    SPACE: 32,
    PAGE_UP: 33,
    PAGE_DOWN: 34,
    END: 35,
    HOME: 36,
    LEFT: 37,
    UP: 38,
    RIGHT: 39,
    DOWN: 40,
    DELETE: 46
  };

  return KEYS;
});

S2.define('select2/selection/base',[
  'jquery',
  '../utils',
  '../keys'
], function ($, Utils, KEYS) {
  function BaseSelection ($element, options) {
    this.$element = $element;
    this.options = options;

    BaseSelection.__super__.constructor.call(this);
  }

  Utils.Extend(BaseSelection, Utils.Observable);

  BaseSelection.prototype.render = function () {
    var $selection = $(
      '<span class="select2-selection" ' +
      ' aria-haspopup="true" aria-expanded="false">' +
      '</span>'
    );

    this._tabindex = 0;

    if (this.$element.data('old-tabindex') != null) {
      this._tabindex = this.$element.data('old-tabindex');
    } else if (this.$element.attr('tabindex') != null) {
      this._tabindex = this.$element.attr('tabindex');
    }

    $selection.attr('title', this.$element.attr('title'));
    $selection.attr('tabindex', this._tabindex);

    this.$selection = $selection;

    return $selection;
  };

  BaseSelection.prototype.bind = function (container, $container) {
    var self = this;

    var id = container.id + '-container';
    var resultsId = container.id + '-results';
    var searchHidden = this.options.get('minimumResultsForSearch') === Infinity;

    this.container = container;

    this.$selection.on('focus', function (evt) {
      self.trigger('focus', evt);
    });

    this.$selection.on('blur', function (evt) {
      self._handleBlur(evt);
    });

    this.$selection.on('keydown', function (evt) {
      self.trigger('keypress', evt);

      if (evt.which === KEYS.SPACE) {
        evt.preventDefault();
      }
    });

    container.on('results:focus', function (params) {
      self.$selection.attr('aria-activedescendant', params.data._resultId);
    });

    container.on('selection:update', function (params) {
      self.update(params.data);
    });

    container.on('open', function () {
      // When the dropdown is open, aria-expanded="true"
      self.$selection.attr('aria-expanded', 'true');
      self.$selection.attr('aria-owns', resultsId);

      self._attachCloseHandler(container);
    });

    container.on('close', function () {
      // When the dropdown is closed, aria-expanded="false"
      self.$selection.attr('aria-expanded', 'false');
      self.$selection.removeAttr('aria-activedescendant');
      self.$selection.removeAttr('aria-owns');

      // This needs to be delayed as the active element is the body when the
      // key is pressed.
      window.setTimeout(function () {
        self.$selection.focus();
      }, 1);

      self._detachCloseHandler(container);
    });

    container.on('enable', function () {
      self.$selection.attr('tabindex', self._tabindex);
    });

    container.on('disable', function () {
      self.$selection.attr('tabindex', '-1');
    });
  };

  BaseSelection.prototype._handleBlur = function (evt) {
    var self = this;

    // This needs to be delayed as the active element is the body when the tab
    // key is pressed, possibly along with others.
    window.setTimeout(function () {
      // Don't trigger `blur` if the focus is still in the selection
      if (
        (document.activeElement == self.$selection[0]) ||
        ($.contains(self.$selection[0], document.activeElement))
      ) {
        return;
      }

      self.trigger('blur', evt);
    }, 1);
  };

  BaseSelection.prototype._attachCloseHandler = function (container) {
    var self = this;

    $(document.body).on('mousedown.select2.' + container.id, function (e) {
      var $target = $(e.target);

      var $select = $target.closest('.select2');

      var $all = $('.select2.select2-container--open');

      $all.each(function () {
        var $this = $(this);

        if (this == $select[0]) {
          return;
        }

        var $element = $this.data('element');
        $element.select2('close');

        // Remove any focus when dropdown is closed by clicking outside the select area.
        // Timeout of 1 required for close to finish wrapping up.
        setTimeout(function(){
         $this.find('*:focus').blur();
         $target.focus();
        }, 1);
      });
    });
  };

  BaseSelection.prototype._detachCloseHandler = function (container) {
    $(document.body).off('mousedown.select2.' + container.id);
  };

  BaseSelection.prototype.position = function ($selection, $container) {
    var $selectionContainer = $container.find('.selection');
    $selectionContainer.append($selection);
  };

  BaseSelection.prototype.destroy = function () {
    this._detachCloseHandler(this.container);
  };

  BaseSelection.prototype.update = function (data) {
    throw new Error('The `update` method must be defined in child classes.');
  };

  return BaseSelection;
});

S2.define('select2/selection/single',[
  'jquery',
  './base',
  '../utils',
  '../keys'
], function ($, BaseSelection, Utils, KEYS) {
  function SingleSelection () {
    SingleSelection.__super__.constructor.apply(this, arguments);
  }

  Utils.Extend(SingleSelection, BaseSelection);

  SingleSelection.prototype.render = function () {
    var $selection = SingleSelection.__super__.render.call(this);

    $selection.addClass('select2-selection--single');

    $selection.html(
      '<span class="select2-selection__rendered"></span>' +
      '<span class="select2-selection__arrow" role="presentation">' +
        '<b role="presentation"></b>' +
      '</span>'
    );

    return $selection;
  };

  SingleSelection.prototype.bind = function (container, $container) {
    var self = this;

    SingleSelection.__super__.bind.apply(this, arguments);

    var id = container.id + '-container';

    this.$selection.find('.select2-selection__rendered')
      .attr('id', id)
      .attr('role', 'textbox')
      .attr('aria-readonly', 'true');
    this.$selection.attr('aria-labelledby', id);

    // This makes single non-search selects work in screen readers. If it causes problems elsewhere, remove.
    this.$selection.attr('role', 'combobox');

    this.$selection.on('mousedown', function (evt) {
      // Only respond to left clicks
      if (evt.which !== 1) {
        return;
      }

      self.trigger('toggle', {
        originalEvent: evt
      });
    });

    this.$selection.on('focus', function (evt) {
      // User focuses on the container
    });

    this.$selection.on('keydown', function (evt) {
      // If user starts typing an alphanumeric key on the keyboard, open if not opened.
      if (!container.isOpen() && evt.which >= 48 && evt.which <= 90) {
        container.open();
      }
    });

    this.$selection.on('blur', function (evt) {
      // User exits the container
    });

    container.on('focus', function (evt) {
      if (!container.isOpen()) {
        self.$selection.focus();
      }
    });

    container.on('selection:update', function (params) {
      self.update(params.data);
    });
  };

  SingleSelection.prototype.clear = function () {
    this.$selection.find('.select2-selection__rendered').empty();
  };

  SingleSelection.prototype.display = function (data, container) {
    var template = this.options.get('templateSelection');
    var escapeMarkup = this.options.get('escapeMarkup');

    return escapeMarkup(template(data, container));
  };

  SingleSelection.prototype.selectionContainer = function () {
    return $('<span></span>');
  };

  SingleSelection.prototype.update = function (data) {
    if (data.length === 0) {
      this.clear();
      return;
    }

    var selection = data[0];

    var $rendered = this.$selection.find('.select2-selection__rendered');
    var formatted = Utils.entityDecode(this.display(selection, $rendered));

    $rendered.empty().text(formatted);
    $rendered.prop('title', selection.title || selection.text);
  };

  return SingleSelection;
});

S2.define('select2/selection/multiple',[
  'jquery',
  './base',
  '../utils'
], function ($, BaseSelection, Utils) {
  function MultipleSelection ($element, options) {
    MultipleSelection.__super__.constructor.apply(this, arguments);
  }

  Utils.Extend(MultipleSelection, BaseSelection);

  MultipleSelection.prototype.render = function () {
    var $selection = MultipleSelection.__super__.render.call(this);

    $selection.addClass('select2-selection--multiple');

    $selection.html(
      '<ul class="select2-selection__rendered" aria-live="polite" aria-relevant="additions removals" aria-atomic="true"></ul>'
    );

    return $selection;
  };

  MultipleSelection.prototype.bind = function (container, $container) {
    var self = this;

    MultipleSelection.__super__.bind.apply(this, arguments);

    this.$selection.on('click', function (evt) {
      self.trigger('toggle', {
        originalEvent: evt
      });
    });

    this.$selection.on(
      'click',
      '.select2-selection__choice__remove',
      function (evt) {
        // Ignore the event if it is disabled
        if (self.options.get('disabled')) {
          return;
        }

        var $remove = $(this);
        var $selection = $remove.parent();

        var data = $selection.data('data');

        self.trigger('unselect', {
          originalEvent: evt,
          data: data
        });
      }
    );

    this.$selection.on('keydown', function (evt) {
      // If user starts typing an alphanumeric key on the keyboard, open if not opened.
      if (!container.isOpen() && evt.which >= 48 && evt.which <= 90) {
        container.open();
      }
    });

    // Focus on the search field when the container is focused instead of the main container.
    container.on( 'focus', function(){
      self.focusOnSearch();
    });
  };

  MultipleSelection.prototype.clear = function () {
    this.$selection.find('.select2-selection__rendered').empty();
  };

  MultipleSelection.prototype.display = function (data, container) {
    var template = this.options.get('templateSelection');
    var escapeMarkup = this.options.get('escapeMarkup');

    return escapeMarkup(template(data, container));
  };

  MultipleSelection.prototype.selectionContainer = function () {
    var $container = $(
      '<li class="select2-selection__choice">' +
        '<span class="select2-selection__choice__remove" role="presentation" aria-hidden="true">' +
          '&times;' +
        '</span>' +
      '</li>'
    );

    return $container;
  };

  /**
   * Focus on the search field instead of the main multiselect container.
   */
  MultipleSelection.prototype.focusOnSearch = function() {
    var self = this;

    if ('undefined' !== typeof self.$search) {
      // Needs 1 ms delay because of other 1 ms setTimeouts when rendering.
      setTimeout(function(){
        // Prevent the dropdown opening again when focused from this.
        // This gets reset automatically when focus is triggered.
        self._keyUpPrevented = true;

        self.$search.focus();
      }, 1);
    }
  }

  MultipleSelection.prototype.update = function (data) {
    this.clear();

    if (data.length === 0) {
      return;
    }

    var $selections = [];

    for (var d = 0; d < data.length; d++) {
      var selection = data[d];

      var $selection = this.selectionContainer();
      var formatted = this.display(selection, $selection);
      if ('string' === typeof formatted) {
        formatted = Utils.entityDecode(formatted.trim());
      }

      $selection.text(formatted);
      $selection.prop('title', selection.title || selection.text);

      $selection.data('data', selection);

      $selections.push($selection);
    }

    var $rendered = this.$selection.find('.select2-selection__rendered');

    Utils.appendMany($rendered, $selections);
  };

  return MultipleSelection;
});

S2.define('select2/selection/placeholder',[
  '../utils'
], function (Utils) {
  function Placeholder (decorated, $element, options) {
    this.placeholder = this.normalizePlaceholder(options.get('placeholder'));

    decorated.call(this, $element, options);
  }

  Placeholder.prototype.normalizePlaceholder = function (_, placeholder) {
    if (typeof placeholder === 'string') {
      placeholder = {
        id: '',
        text: placeholder
      };
    }

    return placeholder;
  };

  Placeholder.prototype.createPlaceholder = function (decorated, placeholder) {
    var $placeholder = this.selectionContainer();

    $placeholder.text(Utils.entityDecode(this.display(placeholder)));
    $placeholder.addClass('select2-selection__placeholder')
                .removeClass('select2-selection__choice');

    return $placeholder;
  };

  Placeholder.prototype.update = function (decorated, data) {
    var singlePlaceholder = (
      data.length == 1 && data[0].id != this.placeholder.id
    );
    var multipleSelections = data.length > 1;

    if (multipleSelections || singlePlaceholder) {
      return decorated.call(this, data);
    }

    this.clear();

    var $placeholder = this.createPlaceholder(this.placeholder);

    this.$selection.find('.select2-selection__rendered').append($placeholder);
  };

  return Placeholder;
});

S2.define('select2/selection/allowClear',[
  'jquery',
  '../keys'
], function ($, KEYS) {
  function AllowClear () { }

  AllowClear.prototype.bind = function (decorated, container, $container) {
    var self = this;

    decorated.call(this, container, $container);

    if (this.placeholder == null) {
      if (this.options.get('debug') && window.console && console.error) {
        console.error(
          'Select2: The `allowClear` option should be used in combination ' +
          'with the `placeholder` option.'
        );
      }
    }

    this.$selection.on('mousedown', '.select2-selection__clear',
      function (evt) {
        self._handleClear(evt);
    });

    container.on('keypress', function (evt) {
      self._handleKeyboardClear(evt, container);
    });
  };

  AllowClear.prototype._handleClear = function (_, evt) {
    // Ignore the event if it is disabled
    if (this.options.get('disabled')) {
      return;
    }

    var $clear = this.$selection.find('.select2-selection__clear');

    // Ignore the event if nothing has been selected
    if ($clear.length === 0) {
      return;
    }

    evt.stopPropagation();

    var data = $clear.data('data');

    for (var d = 0; d < data.length; d++) {
      var unselectData = {
        data: data[d]
      };

      // Trigger the `unselect` event, so people can prevent it from being
      // cleared.
      this.trigger('unselect', unselectData);

      // If the event was prevented, don't clear it out.
      if (unselectData.prevented) {
        return;
      }
    }

    this.$element.val(this.placeholder.id).trigger('change');

    this.trigger('toggle', {});
  };

  AllowClear.prototype._handleKeyboardClear = function (_, evt, container) {
    if (container.isOpen()) {
      return;
    }

    if (evt.which == KEYS.DELETE || evt.which == KEYS.BACKSPACE) {
      this._handleClear(evt);
    }
  };

  AllowClear.prototype.update = function (decorated, data) {
    decorated.call(this, data);

    if (this.$selection.find('.select2-selection__placeholder').length > 0 ||
        data.length === 0) {
      return;
    }

    var $remove = $(
      '<span class="select2-selection__clear">' +
        '&times;' +
      '</span>'
    );
    $remove.data('data', data);

    this.$selection.find('.select2-selection__rendered').prepend($remove);
  };

  return AllowClear;
});

S2.define('select2/selection/search',[
  'jquery',
  '../utils',
  '../keys'
], function ($, Utils, KEYS) {
  function Search (decorated, $element, options) {
    decorated.call(this, $element, options);
  }

  Search.prototype.render = function (decorated) {
    var $search = $(
      '<li class="select2-search select2-search--inline">' +
        '<input class="select2-search__field" type="text" tabindex="-1"' +
        ' autocomplete="off" autocorrect="off" autocapitalize="none"' +
        ' spellcheck="false" role="textbox" aria-autocomplete="list" />' +
      '</li>'
    );

    this.$searchContainer = $search;
    this.$search = $search.find('input');

    var $rendered = decorated.call(this);

    this._transferTabIndex();

    return $rendered;
  };

  Search.prototype.bind = function (decorated, container, $container) {
    var self = this;
    var resultsId = container.id + '-results';

    decorated.call(this, container, $container);

    container.on('open', function () {
      self.$search.attr('aria-owns', resultsId);
      self.$search.trigger('focus');
    });

    container.on('close', function () {
      self.$search.val('');
      self.$search.removeAttr('aria-activedescendant');
      self.$search.removeAttr('aria-owns');
      self.$search.trigger('focus');
    });

    container.on('enable', function () {
      self.$search.prop('disabled', false);

      self._transferTabIndex();
    });

    container.on('disable', function () {
      self.$search.prop('disabled', true);
    });

    container.on('focus', function (evt) {
      self.$search.trigger('focus');
    });

    container.on('results:focus', function (params) {
      self.$search.attr('aria-activedescendant', params.data._resultId);
    });

    this.$selection.on('focusin', '.select2-search--inline', function (evt) {
      self.trigger('focus', evt);
    });

    this.$selection.on('focusout', '.select2-search--inline', function (evt) {
      self._handleBlur(evt);
    });

    this.$selection.on('keydown', '.select2-search--inline', function (evt) {
      evt.stopPropagation();

      self.trigger('keypress', evt);

      self._keyUpPrevented = evt.isDefaultPrevented();

      var key = evt.which;

      if (key === KEYS.BACKSPACE && self.$search.val() === '') {
        var $previousChoice = self.$searchContainer
          .prev('.select2-selection__choice');

        if ($previousChoice.length > 0) {
          var item = $previousChoice.data('data');

          self.searchRemoveChoice(item);

          evt.preventDefault();
        }
      } else if (evt.which === KEYS.ENTER) {
        container.open();
        evt.preventDefault();
      }
    });

    // Try to detect the IE version should the `documentMode` property that
    // is stored on the document. This is only implemented in IE and is
    // slightly cleaner than doing a user agent check.
    // This property is not available in Edge, but Edge also doesn't have
    // this bug.
    var msie = document.documentMode;
    var disableInputEvents = msie && msie <= 11;

    // Workaround for browsers which do not support the `input` event
    // This will prevent double-triggering of events for browsers which support
    // both the `keyup` and `input` events.
    this.$selection.on(
      'input.searchcheck',
      '.select2-search--inline',
      function (evt) {
        // IE will trigger the `input` event when a placeholder is used on a
        // search box. To get around this issue, we are forced to ignore all
        // `input` events in IE and keep using `keyup`.
        if (disableInputEvents) {
          self.$selection.off('input.search input.searchcheck');
          return;
        }

        // Unbind the duplicated `keyup` event
        self.$selection.off('keyup.search');
      }
    );

    this.$selection.on(
      'keyup.search input.search',
      '.select2-search--inline',
      function (evt) {
        // IE will trigger the `input` event when a placeholder is used on a
        // search box. To get around this issue, we are forced to ignore all
        // `input` events in IE and keep using `keyup`.
        if (disableInputEvents && evt.type === 'input') {
          self.$selection.off('input.search input.searchcheck');
          return;
        }

        var key = evt.which;

        // We can freely ignore events from modifier keys
        if (key == KEYS.SHIFT || key == KEYS.CTRL || key == KEYS.ALT) {
          return;
        }

        // Tabbing will be handled during the `keydown` phase
        if (key == KEYS.TAB) {
          return;
        }

        self.handleSearch(evt);
      }
    );
  };

  /**
   * This method will transfer the tabindex attribute from the rendered
   * selection to the search box. This allows for the search box to be used as
   * the primary focus instead of the selection container.
   *
   * @private
   */
  Search.prototype._transferTabIndex = function (decorated) {
    this.$search.attr('tabindex', this.$selection.attr('tabindex'));
    this.$selection.attr('tabindex', '-1');
  };

  Search.prototype.createPlaceholder = function (decorated, placeholder) {
    this.$search.attr('placeholder', placeholder.text);
  };

  Search.prototype.update = function (decorated, data) {
    var searchHadFocus = this.$search[0] == document.activeElement;

    this.$search.attr('placeholder', '');

    decorated.call(this, data);

    this.$selection.find('.select2-selection__rendered')
                   .append(this.$searchContainer);

    this.resizeSearch();
    if (searchHadFocus) {
      this.$search.focus();
    }
  };

  Search.prototype.handleSearch = function () {
    this.resizeSearch();

    if (!this._keyUpPrevented) {
      var input = this.$search.val();

      this.trigger('query', {
        term: input
      });
    }

    this._keyUpPrevented = false;
  };

  Search.prototype.searchRemoveChoice = function (decorated, item) {
    this.trigger('unselect', {
      data: item
    });

    this.$search.val(item.text);
    this.handleSearch();
  };

  Search.prototype.resizeSearch = function () {
    this.$search.css('width', '25px');

    var width = '';

    if (this.$search.attr('placeholder') !== '') {
      width = this.$selection.find('.select2-selection__rendered').innerWidth();
    } else {
      var minimumWidth = this.$search.val().length + 1;

      width = (minimumWidth * 0.75) + 'em';
    }

    this.$search.css('width', width);
  };

  return Search;
});

S2.define('select2/selection/eventRelay',[
  'jquery'
], function ($) {
  function EventRelay () { }

  EventRelay.prototype.bind = function (decorated, container, $container) {
    var self = this;
    var relayEvents = [
      'open', 'opening',
      'close', 'closing',
      'select', 'selecting',
      'unselect', 'unselecting'
    ];

    var preventableEvents = ['opening', 'closing', 'selecting', 'unselecting'];

    decorated.call(this, container, $container);

    container.on('*', function (name, params) {
      // Ignore events that should not be relayed
      if ($.inArray(name, relayEvents) === -1) {
        return;
      }

      // The parameters should always be an object
      params = params || {};

      // Generate the jQuery event for the Select2 event
      var evt = $.Event('select2:' + name, {
        params: params
      });

      self.$element.trigger(evt);

      // Only handle preventable events if it was one
      if ($.inArray(name, preventableEvents) === -1) {
        return;
      }

      params.prevented = evt.isDefaultPrevented();
    });
  };

  return EventRelay;
});

S2.define('select2/translation',[
  'jquery',
  'require'
], function ($, require) {
  function Translation (dict) {
    this.dict = dict || {};
  }

  Translation.prototype.all = function () {
    return this.dict;
  };

  Translation.prototype.get = function (key) {
    return this.dict[key];
  };

  Translation.prototype.extend = function (translation) {
    this.dict = $.extend({}, translation.all(), this.dict);
  };

  // Static functions

  Translation._cache = {};

  Translation.loadPath = function (path) {
    if (!(path in Translation._cache)) {
      var translations = require(path);

      Translation._cache[path] = translations;
    }

    return new Translation(Translation._cache[path]);
  };

  return Translation;
});

S2.define('select2/diacritics',[

], function () {
  var diacritics = {
    '\u24B6': 'A',
    '\uFF21': 'A',
    '\u00C0': 'A',
    '\u00C1': 'A',
    '\u00C2': 'A',
    '\u1EA6': 'A',
    '\u1EA4': 'A',
    '\u1EAA': 'A',
    '\u1EA8': 'A',
    '\u00C3': 'A',
    '\u0100': 'A',
    '\u0102': 'A',
    '\u1EB0': 'A',
    '\u1EAE': 'A',
    '\u1EB4': 'A',
    '\u1EB2': 'A',
    '\u0226': 'A',
    '\u01E0': 'A',
    '\u00C4': 'A',
    '\u01DE': 'A',
    '\u1EA2': 'A',
    '\u00C5': 'A',
    '\u01FA': 'A',
    '\u01CD': 'A',
    '\u0200': 'A',
    '\u0202': 'A',
    '\u1EA0': 'A',
    '\u1EAC': 'A',
    '\u1EB6': 'A',
    '\u1E00': 'A',
    '\u0104': 'A',
    '\u023A': 'A',
    '\u2C6F': 'A',
    '\uA732': 'AA',
    '\u00C6': 'AE',
    '\u01FC': 'AE',
    '\u01E2': 'AE',
    '\uA734': 'AO',
    '\uA736': 'AU',
    '\uA738': 'AV',
    '\uA73A': 'AV',
    '\uA73C': 'AY',
    '\u24B7': 'B',
    '\uFF22': 'B',
    '\u1E02': 'B',
    '\u1E04': 'B',
    '\u1E06': 'B',
    '\u0243': 'B',
    '\u0182': 'B',
    '\u0181': 'B',
    '\u24B8': 'C',
    '\uFF23': 'C',
    '\u0106': 'C',
    '\u0108': 'C',
    '\u010A': 'C',
    '\u010C': 'C',
    '\u00C7': 'C',
    '\u1E08': 'C',
    '\u0187': 'C',
    '\u023B': 'C',
    '\uA73E': 'C',
    '\u24B9': 'D',
    '\uFF24': 'D',
    '\u1E0A': 'D',
    '\u010E': 'D',
    '\u1E0C': 'D',
    '\u1E10': 'D',
    '\u1E12': 'D',
    '\u1E0E': 'D',
    '\u0110': 'D',
    '\u018B': 'D',
    '\u018A': 'D',
    '\u0189': 'D',
    '\uA779': 'D',
    '\u01F1': 'DZ',
    '\u01C4': 'DZ',
    '\u01F2': 'Dz',
    '\u01C5': 'Dz',
    '\u24BA': 'E',
    '\uFF25': 'E',
    '\u00C8': 'E',
    '\u00C9': 'E',
    '\u00CA': 'E',
    '\u1EC0': 'E',
    '\u1EBE': 'E',
    '\u1EC4': 'E',
    '\u1EC2': 'E',
    '\u1EBC': 'E',
    '\u0112': 'E',
    '\u1E14': 'E',
    '\u1E16': 'E',
    '\u0114': 'E',
    '\u0116': 'E',
    '\u00CB': 'E',
    '\u1EBA': 'E',
    '\u011A': 'E',
    '\u0204': 'E',
    '\u0206': 'E',
    '\u1EB8': 'E',
    '\u1EC6': 'E',
    '\u0228': 'E',
    '\u1E1C': 'E',
    '\u0118': 'E',
    '\u1E18': 'E',
    '\u1E1A': 'E',
    '\u0190': 'E',
    '\u018E': 'E',
    '\u24BB': 'F',
    '\uFF26': 'F',
    '\u1E1E': 'F',
    '\u0191': 'F',
    '\uA77B': 'F',
    '\u24BC': 'G',
    '\uFF27': 'G',
    '\u01F4': 'G',
    '\u011C': 'G',
    '\u1E20': 'G',
    '\u011E': 'G',
    '\u0120': 'G',
    '\u01E6': 'G',
    '\u0122': 'G',
    '\u01E4': 'G',
    '\u0193': 'G',
    '\uA7A0': 'G',
    '\uA77D': 'G',
    '\uA77E': 'G',
    '\u24BD': 'H',
    '\uFF28': 'H',
    '\u0124': 'H',
    '\u1E22': 'H',
    '\u1E26': 'H',
    '\u021E': 'H',
    '\u1E24': 'H',
    '\u1E28': 'H',
    '\u1E2A': 'H',
    '\u0126': 'H',
    '\u2C67': 'H',
    '\u2C75': 'H',
    '\uA78D': 'H',
    '\u24BE': 'I',
    '\uFF29': 'I',
    '\u00CC': 'I',
    '\u00CD': 'I',
    '\u00CE': 'I',
    '\u0128': 'I',
    '\u012A': 'I',
    '\u012C': 'I',
    '\u0130': 'I',
    '\u00CF': 'I',
    '\u1E2E': 'I',
    '\u1EC8': 'I',
    '\u01CF': 'I',
    '\u0208': 'I',
    '\u020A': 'I',
    '\u1ECA': 'I',
    '\u012E': 'I',
    '\u1E2C': 'I',
    '\u0197': 'I',
    '\u24BF': 'J',
    '\uFF2A': 'J',
    '\u0134': 'J',
    '\u0248': 'J',
    '\u24C0': 'K',
    '\uFF2B': 'K',
    '\u1E30': 'K',
    '\u01E8': 'K',
    '\u1E32': 'K',
    '\u0136': 'K',
    '\u1E34': 'K',
    '\u0198': 'K',
    '\u2C69': 'K',
    '\uA740': 'K',
    '\uA742': 'K',
    '\uA744': 'K',
    '\uA7A2': 'K',
    '\u24C1': 'L',
    '\uFF2C': 'L',
    '\u013F': 'L',
    '\u0139': 'L',
    '\u013D': 'L',
    '\u1E36': 'L',
    '\u1E38': 'L',
    '\u013B': 'L',
    '\u1E3C': 'L',
    '\u1E3A': 'L',
    '\u0141': 'L',
    '\u023D': 'L',
    '\u2C62': 'L',
    '\u2C60': 'L',
    '\uA748': 'L',
    '\uA746': 'L',
    '\uA780': 'L',
    '\u01C7': 'LJ',
    '\u01C8': 'Lj',
    '\u24C2': 'M',
    '\uFF2D': 'M',
    '\u1E3E': 'M',
    '\u1E40': 'M',
    '\u1E42': 'M',
    '\u2C6E': 'M',
    '\u019C': 'M',
    '\u24C3': 'N',
    '\uFF2E': 'N',
    '\u01F8': 'N',
    '\u0143': 'N',
    '\u00D1': 'N',
    '\u1E44': 'N',
    '\u0147': 'N',
    '\u1E46': 'N',
    '\u0145': 'N',
    '\u1E4A': 'N',
    '\u1E48': 'N',
    '\u0220': 'N',
    '\u019D': 'N',
    '\uA790': 'N',
    '\uA7A4': 'N',
    '\u01CA': 'NJ',
    '\u01CB': 'Nj',
    '\u24C4': 'O',
    '\uFF2F': 'O',
    '\u00D2': 'O',
    '\u00D3': 'O',
    '\u00D4': 'O',
    '\u1ED2': 'O',
    '\u1ED0': 'O',
    '\u1ED6': 'O',
    '\u1ED4': 'O',
    '\u00D5': 'O',
    '\u1E4C': 'O',
    '\u022C': 'O',
    '\u1E4E': 'O',
    '\u014C': 'O',
    '\u1E50': 'O',
    '\u1E52': 'O',
    '\u014E': 'O',
    '\u022E': 'O',
    '\u0230': 'O',
    '\u00D6': 'O',
    '\u022A': 'O',
    '\u1ECE': 'O',
    '\u0150': 'O',
    '\u01D1': 'O',
    '\u020C': 'O',
    '\u020E': 'O',
    '\u01A0': 'O',
    '\u1EDC': 'O',
    '\u1EDA': 'O',
    '\u1EE0': 'O',
    '\u1EDE': 'O',
    '\u1EE2': 'O',
    '\u1ECC': 'O',
    '\u1ED8': 'O',
    '\u01EA': 'O',
    '\u01EC': 'O',
    '\u00D8': 'O',
    '\u01FE': 'O',
    '\u0186': 'O',
    '\u019F': 'O',
    '\uA74A': 'O',
    '\uA74C': 'O',
    '\u01A2': 'OI',
    '\uA74E': 'OO',
    '\u0222': 'OU',
    '\u24C5': 'P',
    '\uFF30': 'P',
    '\u1E54': 'P',
    '\u1E56': 'P',
    '\u01A4': 'P',
    '\u2C63': 'P',
    '\uA750': 'P',
    '\uA752': 'P',
    '\uA754': 'P',
    '\u24C6': 'Q',
    '\uFF31': 'Q',
    '\uA756': 'Q',
    '\uA758': 'Q',
    '\u024A': 'Q',
    '\u24C7': 'R',
    '\uFF32': 'R',
    '\u0154': 'R',
    '\u1E58': 'R',
    '\u0158': 'R',
    '\u0210': 'R',
    '\u0212': 'R',
    '\u1E5A': 'R',
    '\u1E5C': 'R',
    '\u0156': 'R',
    '\u1E5E': 'R',
    '\u024C': 'R',
    '\u2C64': 'R',
    '\uA75A': 'R',
    '\uA7A6': 'R',
    '\uA782': 'R',
    '\u24C8': 'S',
    '\uFF33': 'S',
    '\u1E9E': 'S',
    '\u015A': 'S',
    '\u1E64': 'S',
    '\u015C': 'S',
    '\u1E60': 'S',
    '\u0160': 'S',
    '\u1E66': 'S',
    '\u1E62': 'S',
    '\u1E68': 'S',
    '\u0218': 'S',
    '\u015E': 'S',
    '\u2C7E': 'S',
    '\uA7A8': 'S',
    '\uA784': 'S',
    '\u24C9': 'T',
    '\uFF34': 'T',
    '\u1E6A': 'T',
    '\u0164': 'T',
    '\u1E6C': 'T',
    '\u021A': 'T',
    '\u0162': 'T',
    '\u1E70': 'T',
    '\u1E6E': 'T',
    '\u0166': 'T',
    '\u01AC': 'T',
    '\u01AE': 'T',
    '\u023E': 'T',
    '\uA786': 'T',
    '\uA728': 'TZ',
    '\u24CA': 'U',
    '\uFF35': 'U',
    '\u00D9': 'U',
    '\u00DA': 'U',
    '\u00DB': 'U',
    '\u0168': 'U',
    '\u1E78': 'U',
    '\u016A': 'U',
    '\u1E7A': 'U',
    '\u016C': 'U',
    '\u00DC': 'U',
    '\u01DB': 'U',
    '\u01D7': 'U',
    '\u01D5': 'U',
    '\u01D9': 'U',
    '\u1EE6': 'U',
    '\u016E': 'U',
    '\u0170': 'U',
    '\u01D3': 'U',
    '\u0214': 'U',
    '\u0216': 'U',
    '\u01AF': 'U',
    '\u1EEA': 'U',
    '\u1EE8': 'U',
    '\u1EEE': 'U',
    '\u1EEC': 'U',
    '\u1EF0': 'U',
    '\u1EE4': 'U',
    '\u1E72': 'U',
    '\u0172': 'U',
    '\u1E76': 'U',
    '\u1E74': 'U',
    '\u0244': 'U',
    '\u24CB': 'V',
    '\uFF36': 'V',
    '\u1E7C': 'V',
    '\u1E7E': 'V',
    '\u01B2': 'V',
    '\uA75E': 'V',
    '\u0245': 'V',
    '\uA760': 'VY',
    '\u24CC': 'W',
    '\uFF37': 'W',
    '\u1E80': 'W',
    '\u1E82': 'W',
    '\u0174': 'W',
    '\u1E86': 'W',
    '\u1E84': 'W',
    '\u1E88': 'W',
    '\u2C72': 'W',
    '\u24CD': 'X',
    '\uFF38': 'X',
    '\u1E8A': 'X',
    '\u1E8C': 'X',
    '\u24CE': 'Y',
    '\uFF39': 'Y',
    '\u1EF2': 'Y',
    '\u00DD': 'Y',
    '\u0176': 'Y',
    '\u1EF8': 'Y',
    '\u0232': 'Y',
    '\u1E8E': 'Y',
    '\u0178': 'Y',
    '\u1EF6': 'Y',
    '\u1EF4': 'Y',
    '\u01B3': 'Y',
    '\u024E': 'Y',
    '\u1EFE': 'Y',
    '\u24CF': 'Z',
    '\uFF3A': 'Z',
    '\u0179': 'Z',
    '\u1E90': 'Z',
    '\u017B': 'Z',
    '\u017D': 'Z',
    '\u1E92': 'Z',
    '\u1E94': 'Z',
    '\u01B5': 'Z',
    '\u0224': 'Z',
    '\u2C7F': 'Z',
    '\u2C6B': 'Z',
    '\uA762': 'Z',
    '\u24D0': 'a',
    '\uFF41': 'a',
    '\u1E9A': 'a',
    '\u00E0': 'a',
    '\u00E1': 'a',
    '\u00E2': 'a',
    '\u1EA7': 'a',
    '\u1EA5': 'a',
    '\u1EAB': 'a',
    '\u1EA9': 'a',
    '\u00E3': 'a',
    '\u0101': 'a',
    '\u0103': 'a',
    '\u1EB1': 'a',
    '\u1EAF': 'a',
    '\u1EB5': 'a',
    '\u1EB3': 'a',
    '\u0227': 'a',
    '\u01E1': 'a',
    '\u00E4': 'a',
    '\u01DF': 'a',
    '\u1EA3': 'a',
    '\u00E5': 'a',
    '\u01FB': 'a',
    '\u01CE': 'a',
    '\u0201': 'a',
    '\u0203': 'a',
    '\u1EA1': 'a',
    '\u1EAD': 'a',
    '\u1EB7': 'a',
    '\u1E01': 'a',
    '\u0105': 'a',
    '\u2C65': 'a',
    '\u0250': 'a',
    '\uA733': 'aa',
    '\u00E6': 'ae',
    '\u01FD': 'ae',
    '\u01E3': 'ae',
    '\uA735': 'ao',
    '\uA737': 'au',
    '\uA739': 'av',
    '\uA73B': 'av',
    '\uA73D': 'ay',
    '\u24D1': 'b',
    '\uFF42': 'b',
    '\u1E03': 'b',
    '\u1E05': 'b',
    '\u1E07': 'b',
    '\u0180': 'b',
    '\u0183': 'b',
    '\u0253': 'b',
    '\u24D2': 'c',
    '\uFF43': 'c',
    '\u0107': 'c',
    '\u0109': 'c',
    '\u010B': 'c',
    '\u010D': 'c',
    '\u00E7': 'c',
    '\u1E09': 'c',
    '\u0188': 'c',
    '\u023C': 'c',
    '\uA73F': 'c',
    '\u2184': 'c',
    '\u24D3': 'd',
    '\uFF44': 'd',
    '\u1E0B': 'd',
    '\u010F': 'd',
    '\u1E0D': 'd',
    '\u1E11': 'd',
    '\u1E13': 'd',
    '\u1E0F': 'd',
    '\u0111': 'd',
    '\u018C': 'd',
    '\u0256': 'd',
    '\u0257': 'd',
    '\uA77A': 'd',
    '\u01F3': 'dz',
    '\u01C6': 'dz',
    '\u24D4': 'e',
    '\uFF45': 'e',
    '\u00E8': 'e',
    '\u00E9': 'e',
    '\u00EA': 'e',
    '\u1EC1': 'e',
    '\u1EBF': 'e',
    '\u1EC5': 'e',
    '\u1EC3': 'e',
    '\u1EBD': 'e',
    '\u0113': 'e',
    '\u1E15': 'e',
    '\u1E17': 'e',
    '\u0115': 'e',
    '\u0117': 'e',
    '\u00EB': 'e',
    '\u1EBB': 'e',
    '\u011B': 'e',
    '\u0205': 'e',
    '\u0207': 'e',
    '\u1EB9': 'e',
    '\u1EC7': 'e',
    '\u0229': 'e',
    '\u1E1D': 'e',
    '\u0119': 'e',
    '\u1E19': 'e',
    '\u1E1B': 'e',
    '\u0247': 'e',
    '\u025B': 'e',
    '\u01DD': 'e',
    '\u24D5': 'f',
    '\uFF46': 'f',
    '\u1E1F': 'f',
    '\u0192': 'f',
    '\uA77C': 'f',
    '\u24D6': 'g',
    '\uFF47': 'g',
    '\u01F5': 'g',
    '\u011D': 'g',
    '\u1E21': 'g',
    '\u011F': 'g',
    '\u0121': 'g',
    '\u01E7': 'g',
    '\u0123': 'g',
    '\u01E5': 'g',
    '\u0260': 'g',
    '\uA7A1': 'g',
    '\u1D79': 'g',
    '\uA77F': 'g',
    '\u24D7': 'h',
    '\uFF48': 'h',
    '\u0125': 'h',
    '\u1E23': 'h',
    '\u1E27': 'h',
    '\u021F': 'h',
    '\u1E25': 'h',
    '\u1E29': 'h',
    '\u1E2B': 'h',
    '\u1E96': 'h',
    '\u0127': 'h',
    '\u2C68': 'h',
    '\u2C76': 'h',
    '\u0265': 'h',
    '\u0195': 'hv',
    '\u24D8': 'i',
    '\uFF49': 'i',
    '\u00EC': 'i',
    '\u00ED': 'i',
    '\u00EE': 'i',
    '\u0129': 'i',
    '\u012B': 'i',
    '\u012D': 'i',
    '\u00EF': 'i',
    '\u1E2F': 'i',
    '\u1EC9': 'i',
    '\u01D0': 'i',
    '\u0209': 'i',
    '\u020B': 'i',
    '\u1ECB': 'i',
    '\u012F': 'i',
    '\u1E2D': 'i',
    '\u0268': 'i',
    '\u0131': 'i',
    '\u24D9': 'j',
    '\uFF4A': 'j',
    '\u0135': 'j',
    '\u01F0': 'j',
    '\u0249': 'j',
    '\u24DA': 'k',
    '\uFF4B': 'k',
    '\u1E31': 'k',
    '\u01E9': 'k',
    '\u1E33': 'k',
    '\u0137': 'k',
    '\u1E35': 'k',
    '\u0199': 'k',
    '\u2C6A': 'k',
    '\uA741': 'k',
    '\uA743': 'k',
    '\uA745': 'k',
    '\uA7A3': 'k',
    '\u24DB': 'l',
    '\uFF4C': 'l',
    '\u0140': 'l',
    '\u013A': 'l',
    '\u013E': 'l',
    '\u1E37': 'l',
    '\u1E39': 'l',
    '\u013C': 'l',
    '\u1E3D': 'l',
    '\u1E3B': 'l',
    '\u017F': 'l',
    '\u0142': 'l',
    '\u019A': 'l',
    '\u026B': 'l',
    '\u2C61': 'l',
    '\uA749': 'l',
    '\uA781': 'l',
    '\uA747': 'l',
    '\u01C9': 'lj',
    '\u24DC': 'm',
    '\uFF4D': 'm',
    '\u1E3F': 'm',
    '\u1E41': 'm',
    '\u1E43': 'm',
    '\u0271': 'm',
    '\u026F': 'm',
    '\u24DD': 'n',
    '\uFF4E': 'n',
    '\u01F9': 'n',
    '\u0144': 'n',
    '\u00F1': 'n',
    '\u1E45': 'n',
    '\u0148': 'n',
    '\u1E47': 'n',
    '\u0146': 'n',
    '\u1E4B': 'n',
    '\u1E49': 'n',
    '\u019E': 'n',
    '\u0272': 'n',
    '\u0149': 'n',
    '\uA791': 'n',
    '\uA7A5': 'n',
    '\u01CC': 'nj',
    '\u24DE': 'o',
    '\uFF4F': 'o',
    '\u00F2': 'o',
    '\u00F3': 'o',
    '\u00F4': 'o',
    '\u1ED3': 'o',
    '\u1ED1': 'o',
    '\u1ED7': 'o',
    '\u1ED5': 'o',
    '\u00F5': 'o',
    '\u1E4D': 'o',
    '\u022D': 'o',
    '\u1E4F': 'o',
    '\u014D': 'o',
    '\u1E51': 'o',
    '\u1E53': 'o',
    '\u014F': 'o',
    '\u022F': 'o',
    '\u0231': 'o',
    '\u00F6': 'o',
    '\u022B': 'o',
    '\u1ECF': 'o',
    '\u0151': 'o',
    '\u01D2': 'o',
    '\u020D': 'o',
    '\u020F': 'o',
    '\u01A1': 'o',
    '\u1EDD': 'o',
    '\u1EDB': 'o',
    '\u1EE1': 'o',
    '\u1EDF': 'o',
    '\u1EE3': 'o',
    '\u1ECD': 'o',
    '\u1ED9': 'o',
    '\u01EB': 'o',
    '\u01ED': 'o',
    '\u00F8': 'o',
    '\u01FF': 'o',
    '\u0254': 'o',
    '\uA74B': 'o',
    '\uA74D': 'o',
    '\u0275': 'o',
    '\u01A3': 'oi',
    '\u0223': 'ou',
    '\uA74F': 'oo',
    '\u24DF': 'p',
    '\uFF50': 'p',
    '\u1E55': 'p',
    '\u1E57': 'p',
    '\u01A5': 'p',
    '\u1D7D': 'p',
    '\uA751': 'p',
    '\uA753': 'p',
    '\uA755': 'p',
    '\u24E0': 'q',
    '\uFF51': 'q',
    '\u024B': 'q',
    '\uA757': 'q',
    '\uA759': 'q',
    '\u24E1': 'r',
    '\uFF52': 'r',
    '\u0155': 'r',
    '\u1E59': 'r',
    '\u0159': 'r',
    '\u0211': 'r',
    '\u0213': 'r',
    '\u1E5B': 'r',
    '\u1E5D': 'r',
    '\u0157': 'r',
    '\u1E5F': 'r',
    '\u024D': 'r',
    '\u027D': 'r',
    '\uA75B': 'r',
    '\uA7A7': 'r',
    '\uA783': 'r',
    '\u24E2': 's',
    '\uFF53': 's',
    '\u00DF': 's',
    '\u015B': 's',
    '\u1E65': 's',
    '\u015D': 's',
    '\u1E61': 's',
    '\u0161': 's',
    '\u1E67': 's',
    '\u1E63': 's',
    '\u1E69': 's',
    '\u0219': 's',
    '\u015F': 's',
    '\u023F': 's',
    '\uA7A9': 's',
    '\uA785': 's',
    '\u1E9B': 's',
    '\u24E3': 't',
    '\uFF54': 't',
    '\u1E6B': 't',
    '\u1E97': 't',
    '\u0165': 't',
    '\u1E6D': 't',
    '\u021B': 't',
    '\u0163': 't',
    '\u1E71': 't',
    '\u1E6F': 't',
    '\u0167': 't',
    '\u01AD': 't',
    '\u0288': 't',
    '\u2C66': 't',
    '\uA787': 't',
    '\uA729': 'tz',
    '\u24E4': 'u',
    '\uFF55': 'u',
    '\u00F9': 'u',
    '\u00FA': 'u',
    '\u00FB': 'u',
    '\u0169': 'u',
    '\u1E79': 'u',
    '\u016B': 'u',
    '\u1E7B': 'u',
    '\u016D': 'u',
    '\u00FC': 'u',
    '\u01DC': 'u',
    '\u01D8': 'u',
    '\u01D6': 'u',
    '\u01DA': 'u',
    '\u1EE7': 'u',
    '\u016F': 'u',
    '\u0171': 'u',
    '\u01D4': 'u',
    '\u0215': 'u',
    '\u0217': 'u',
    '\u01B0': 'u',
    '\u1EEB': 'u',
    '\u1EE9': 'u',
    '\u1EEF': 'u',
    '\u1EED': 'u',
    '\u1EF1': 'u',
    '\u1EE5': 'u',
    '\u1E73': 'u',
    '\u0173': 'u',
    '\u1E77': 'u',
    '\u1E75': 'u',
    '\u0289': 'u',
    '\u24E5': 'v',
    '\uFF56': 'v',
    '\u1E7D': 'v',
    '\u1E7F': 'v',
    '\u028B': 'v',
    '\uA75F': 'v',
    '\u028C': 'v',
    '\uA761': 'vy',
    '\u24E6': 'w',
    '\uFF57': 'w',
    '\u1E81': 'w',
    '\u1E83': 'w',
    '\u0175': 'w',
    '\u1E87': 'w',
    '\u1E85': 'w',
    '\u1E98': 'w',
    '\u1E89': 'w',
    '\u2C73': 'w',
    '\u24E7': 'x',
    '\uFF58': 'x',
    '\u1E8B': 'x',
    '\u1E8D': 'x',
    '\u24E8': 'y',
    '\uFF59': 'y',
    '\u1EF3': 'y',
    '\u00FD': 'y',
    '\u0177': 'y',
    '\u1EF9': 'y',
    '\u0233': 'y',
    '\u1E8F': 'y',
    '\u00FF': 'y',
    '\u1EF7': 'y',
    '\u1E99': 'y',
    '\u1EF5': 'y',
    '\u01B4': 'y',
    '\u024F': 'y',
    '\u1EFF': 'y',
    '\u24E9': 'z',
    '\uFF5A': 'z',
    '\u017A': 'z',
    '\u1E91': 'z',
    '\u017C': 'z',
    '\u017E': 'z',
    '\u1E93': 'z',
    '\u1E95': 'z',
    '\u01B6': 'z',
    '\u0225': 'z',
    '\u0240': 'z',
    '\u2C6C': 'z',
    '\uA763': 'z',
    '\u0386': '\u0391',
    '\u0388': '\u0395',
    '\u0389': '\u0397',
    '\u038A': '\u0399',
    '\u03AA': '\u0399',
    '\u038C': '\u039F',
    '\u038E': '\u03A5',
    '\u03AB': '\u03A5',
    '\u038F': '\u03A9',
    '\u03AC': '\u03B1',
    '\u03AD': '\u03B5',
    '\u03AE': '\u03B7',
    '\u03AF': '\u03B9',
    '\u03CA': '\u03B9',
    '\u0390': '\u03B9',
    '\u03CC': '\u03BF',
    '\u03CD': '\u03C5',
    '\u03CB': '\u03C5',
    '\u03B0': '\u03C5',
    '\u03C9': '\u03C9',
    '\u03C2': '\u03C3'
  };

  return diacritics;
});

S2.define('select2/data/base',[
  '../utils'
], function (Utils) {
  function BaseAdapter ($element, options) {
    BaseAdapter.__super__.constructor.call(this);
  }

  Utils.Extend(BaseAdapter, Utils.Observable);

  BaseAdapter.prototype.current = function (callback) {
    throw new Error('The `current` method must be defined in child classes.');
  };

  BaseAdapter.prototype.query = function (params, callback) {
    throw new Error('The `query` method must be defined in child classes.');
  };

  BaseAdapter.prototype.bind = function (container, $container) {
    // Can be implemented in subclasses
  };

  BaseAdapter.prototype.destroy = function () {
    // Can be implemented in subclasses
  };

  BaseAdapter.prototype.generateResultId = function (container, data) {
    var id = '';

    if (container != null) {
      id += container.id
    } else {
      id += Utils.generateChars(4);
    }

    id += '-result-';
    id += Utils.generateChars(4);

    if (data.id != null) {
      id += '-' + data.id.toString();
    } else {
      id += '-' + Utils.generateChars(4);
    }
    return id;
  };

  return BaseAdapter;
});

S2.define('select2/data/select',[
  './base',
  '../utils',
  'jquery'
], function (BaseAdapter, Utils, $) {
  function SelectAdapter ($element, options) {
    this.$element = $element;
    this.options = options;

    SelectAdapter.__super__.constructor.call(this);
  }

  Utils.Extend(SelectAdapter, BaseAdapter);

  SelectAdapter.prototype.current = function (callback) {
    var data = [];
    var self = this;

    this.$element.find(':selected').each(function () {
      var $option = $(this);

      var option = self.item($option);

      data.push(option);
    });

    callback(data);
  };

  SelectAdapter.prototype.select = function (data) {
    var self = this;

    data.selected = true;

    // If data.element is a DOM node, use it instead
    if ($(data.element).is('option')) {
      data.element.selected = true;

      this.$element.trigger('change');

      return;
    }

    if (this.$element.prop('multiple')) {
      this.current(function (currentData) {
        var val = [];

        data = [data];
        data.push.apply(data, currentData);

        for (var d = 0; d < data.length; d++) {
          var id = data[d].id;

          if ($.inArray(id, val) === -1) {
            val.push(id);
          }
        }

        self.$element.val(val);
        self.$element.trigger('change');
      });
    } else {
      var val = data.id;

      this.$element.val(val);
      this.$element.trigger('change');
    }
  };

  SelectAdapter.prototype.unselect = function (data) {
    var self = this;

    if (!this.$element.prop('multiple')) {
      return;
    }

    data.selected = false;

    if ($(data.element).is('option')) {
      data.element.selected = false;

      this.$element.trigger('change');

      return;
    }

    this.current(function (currentData) {
      var val = [];

      for (var d = 0; d < currentData.length; d++) {
        var id = currentData[d].id;

        if (id !== data.id && $.inArray(id, val) === -1) {
          val.push(id);
        }
      }

      self.$element.val(val);

      self.$element.trigger('change');
    });
  };

  SelectAdapter.prototype.bind = function (container, $container) {
    var self = this;

    this.container = container;

    container.on('select', function (params) {
      self.select(params.data);
    });

    container.on('unselect', function (params) {
      self.unselect(params.data);
    });
  };

  SelectAdapter.prototype.destroy = function () {
    // Remove anything added to child elements
    this.$element.find('*').each(function () {
      // Remove any custom data set by Select2
      $.removeData(this, 'data');
    });
  };

  SelectAdapter.prototype.query = function (params, callback) {
    var data = [];
    var self = this;

    var $options = this.$element.children();

    $options.each(function () {
      var $option = $(this);

      if (!$option.is('option') && !$option.is('optgroup')) {
        return;
      }

      var option = self.item($option);

      var matches = self.matches(params, option);

      if (matches !== null) {
        data.push(matches);
      }
    });

    callback({
      results: data
    });
  };

  SelectAdapter.prototype.addOptions = function ($options) {
    Utils.appendMany(this.$element, $options);
  };

  SelectAdapter.prototype.option = function (data) {
    var option;

    if (data.children) {
      option = document.createElement('optgroup');
      option.label = data.text;
    } else {
      option = document.createElement('option');

      if (option.textContent !== undefined) {
        option.textContent = data.text;
      } else {
        option.innerText = data.text;
      }
    }

    if (data.id !== undefined) {
      option.value = data.id;
    }

    if (data.disabled) {
      option.disabled = true;
    }

    if (data.selected) {
      option.selected = true;
    }

    if (data.title) {
      option.title = data.title;
    }

    var $option = $(option);

    var normalizedData = this._normalizeItem(data);
    normalizedData.element = option;

    // Override the option's data with the combined data
    $.data(option, 'data', normalizedData);

    return $option;
  };

  SelectAdapter.prototype.item = function ($option) {
    var data = {};

    data = $.data($option[0], 'data');

    if (data != null) {
      return data;
    }

    if ($option.is('option')) {
      data = {
        id: $option.val(),
        text: $option.text(),
        disabled: $option.prop('disabled'),
        selected: $option.prop('selected'),
        title: $option.prop('title')
      };
    } else if ($option.is('optgroup')) {
      data = {
        text: $option.prop('label'),
        children: [],
        title: $option.prop('title')
      };

      var $children = $option.children('option');
      var children = [];

      for (var c = 0; c < $children.length; c++) {
        var $child = $($children[c]);

        var child = this.item($child);

        children.push(child);
      }

      data.children = children;
    }

    data = this._normalizeItem(data);
    data.element = $option[0];

    $.data($option[0], 'data', data);

    return data;
  };

  SelectAdapter.prototype._normalizeItem = function (item) {
    if (!$.isPlainObject(item)) {
      item = {
        id: item,
        text: item
      };
    }

    item = $.extend({}, {
      text: ''
    }, item);

    var defaults = {
      selected: false,
      disabled: false
    };

    if (item.id != null) {
      item.id = item.id.toString();
    }

    if (item.text != null) {
      item.text = item.text.toString();
    }

    if (item._resultId == null && item.id) {
      item._resultId = this.generateResultId(this.container, item);
    }

    return $.extend({}, defaults, item);
  };

  SelectAdapter.prototype.matches = function (params, data) {
    var matcher = this.options.get('matcher');

    return matcher(params, data);
  };

  return SelectAdapter;
});

S2.define('select2/data/array',[
  './select',
  '../utils',
  'jquery'
], function (SelectAdapter, Utils, $) {
  function ArrayAdapter ($element, options) {
    var data = options.get('data') || [];

    ArrayAdapter.__super__.constructor.call(this, $element, options);

    this.addOptions(this.convertToOptions(data));
  }

  Utils.Extend(ArrayAdapter, SelectAdapter);

  ArrayAdapter.prototype.select = function (data) {
    var $option = this.$element.find('option').filter(function (i, elm) {
      return elm.value == data.id.toString();
    });

    if ($option.length === 0) {
      $option = this.option(data);

      this.addOptions($option);
    }

    ArrayAdapter.__super__.select.call(this, data);
  };

  ArrayAdapter.prototype.convertToOptions = function (data) {
    var self = this;

    var $existing = this.$element.find('option');
    var existingIds = $existing.map(function () {
      return self.item($(this)).id;
    }).get();

    var $options = [];

    // Filter out all items except for the one passed in the argument
    function onlyItem (item) {
      return function () {
        return $(this).val() == item.id;
      };
    }

    for (var d = 0; d < data.length; d++) {
      var item = this._normalizeItem(data[d]);

      // Skip items which were pre-loaded, only merge the data
      if ($.inArray(item.id, existingIds) >= 0) {
        var $existingOption = $existing.filter(onlyItem(item));

        var existingData = this.item($existingOption);
        var newData = $.extend(true, {}, item, existingData);

        var $newOption = this.option(newData);

        $existingOption.replaceWith($newOption);

        continue;
      }

      var $option = this.option(item);

      if (item.children) {
        var $children = this.convertToOptions(item.children);

        Utils.appendMany($option, $children);
      }

      $options.push($option);
    }

    return $options;
  };

  return ArrayAdapter;
});

S2.define('select2/data/ajax',[
  './array',
  '../utils',
  'jquery'
], function (ArrayAdapter, Utils, $) {
  function AjaxAdapter ($element, options) {
    this.ajaxOptions = this._applyDefaults(options.get('ajax'));

    if (this.ajaxOptions.processResults != null) {
      this.processResults = this.ajaxOptions.processResults;
    }

    AjaxAdapter.__super__.constructor.call(this, $element, options);
  }

  Utils.Extend(AjaxAdapter, ArrayAdapter);

  AjaxAdapter.prototype._applyDefaults = function (options) {
    var defaults = {
      data: function (params) {
        return $.extend({}, params, {
          q: params.term
        });
      },
      transport: function (params, success, failure) {
        var $request = $.ajax(params);

        $request.then(success);
        $request.fail(failure);

        return $request;
      }
    };

    return $.extend({}, defaults, options, true);
  };

  AjaxAdapter.prototype.processResults = function (results) {
    return results;
  };

  AjaxAdapter.prototype.query = function (params, callback) {
    var matches = [];
    var self = this;

    if (this._request != null) {
      // JSONP requests cannot always be aborted
      if ($.isFunction(this._request.abort)) {
        this._request.abort();
      }

      this._request = null;
    }

    var options = $.extend({
      type: 'GET'
    }, this.ajaxOptions);

    if (typeof options.url === 'function') {
      options.url = options.url.call(this.$element, params);
    }

    if (typeof options.data === 'function') {
      options.data = options.data.call(this.$element, params);
    }

    function request () {
      var $request = options.transport(options, function (data) {
        var results = self.processResults(data, params);

        if (self.options.get('debug') && window.console && console.error) {
          // Check to make sure that the response included a `results` key.
          if (!results || !results.results || !$.isArray(results.results)) {
            console.error(
              'Select2: The AJAX results did not return an array in the ' +
              '`results` key of the response.'
            );
          }
        }

        callback(results);
        self.container.focusOnActiveElement();
      }, function () {
        // Attempt to detect if a request was aborted
        // Only works if the transport exposes a status property
        if ($request.status && $request.status === '0') {
          return;
        }

        self.trigger('results:message', {
          message: 'errorLoading'
        });
      });

      self._request = $request;
    }

    if (this.ajaxOptions.delay && params.term != null) {
      if (this._queryTimeout) {
        window.clearTimeout(this._queryTimeout);
      }

      this._queryTimeout = window.setTimeout(request, this.ajaxOptions.delay);
    } else {
      request();
    }
  };

  return AjaxAdapter;
});

S2.define('select2/data/tags',[
  'jquery'
], function ($) {
  function Tags (decorated, $element, options) {
    var tags = options.get('tags');

    var createTag = options.get('createTag');

    if (createTag !== undefined) {
      this.createTag = createTag;
    }

    var insertTag = options.get('insertTag');

    if (insertTag !== undefined) {
        this.insertTag = insertTag;
    }

    decorated.call(this, $element, options);

    if ($.isArray(tags)) {
      for (var t = 0; t < tags.length; t++) {
        var tag = tags[t];
        var item = this._normalizeItem(tag);

        var $option = this.option(item);

        this.$element.append($option);
      }
    }
  }

  Tags.prototype.query = function (decorated, params, callback) {
    var self = this;

    this._removeOldTags();

    if (params.term == null || params.page != null) {
      decorated.call(this, params, callback);
      return;
    }

    function wrapper (obj, child) {
      var data = obj.results;

      for (var i = 0; i < data.length; i++) {
        var option = data[i];

        var checkChildren = (
          option.children != null &&
          !wrapper({
            results: option.children
          }, true)
        );

        var optionText = (option.text || '').toUpperCase();
        var paramsTerm = (params.term || '').toUpperCase();

        var checkText = optionText === paramsTerm;

        if (checkText || checkChildren) {
          if (child) {
            return false;
          }

          obj.data = data;
          callback(obj);

          return;
        }
      }

      if (child) {
        return true;
      }

      var tag = self.createTag(params);

      if (tag != null) {
        var $option = self.option(tag);
        $option.attr('data-select2-tag', true);

        self.addOptions([$option]);

        self.insertTag(data, tag);
      }

      obj.results = data;

      callback(obj);
    }

    decorated.call(this, params, wrapper);
  };

  Tags.prototype.createTag = function (decorated, params) {
    var term = $.trim(params.term);

    if (term === '') {
      return null;
    }

    return {
      id: term,
      text: term
    };
  };

  Tags.prototype.insertTag = function (_, data, tag) {
    data.unshift(tag);
  };

  Tags.prototype._removeOldTags = function (_) {
    var tag = this._lastTag;

    var $options = this.$element.find('option[data-select2-tag]');

    $options.each(function () {
      if (this.selected) {
        return;
      }

      $(this).remove();
    });
  };

  return Tags;
});

S2.define('select2/data/tokenizer',[
  'jquery'
], function ($) {
  function Tokenizer (decorated, $element, options) {
    var tokenizer = options.get('tokenizer');

    if (tokenizer !== undefined) {
      this.tokenizer = tokenizer;
    }

    decorated.call(this, $element, options);
  }

  Tokenizer.prototype.bind = function (decorated, container, $container) {
    decorated.call(this, container, $container);

    this.$search =  container.dropdown.$search || container.selection.$search ||
      $container.find('.select2-search__field');
  };

  Tokenizer.prototype.query = function (decorated, params, callback) {
    var self = this;

    function createAndSelect (data) {
      // Normalize the data object so we can use it for checks
      var item = self._normalizeItem(data);

      // Check if the data object already exists as a tag
      // Select it if it doesn't
      var $existingOptions = self.$element.find('option').filter(function () {
        return $(this).val() === item.id;
      });

      // If an existing option wasn't found for it, create the option
      if (!$existingOptions.length) {
        var $option = self.option(item);
        $option.attr('data-select2-tag', true);

        self._removeOldTags();
        self.addOptions([$option]);
      }

      // Select the item, now that we know there is an option for it
      select(item);
    }

    function select (data) {
      self.trigger('select', {
        data: data
      });
    }

    params.term = params.term || '';

    var tokenData = this.tokenizer(params, this.options, createAndSelect);

    if (tokenData.term !== params.term) {
      // Replace the search term if we have the search box
      if (this.$search.length) {
        this.$search.val(tokenData.term);
        this.$search.focus();
      }

      params.term = tokenData.term;
    }

    decorated.call(this, params, callback);
  };

  Tokenizer.prototype.tokenizer = function (_, params, options, callback) {
    var separators = options.get('tokenSeparators') || [];
    var term = params.term;
    var i = 0;

    var createTag = this.createTag || function (params) {
      return {
        id: params.term,
        text: params.term
      };
    };

    while (i < term.length) {
      var termChar = term[i];

      if ($.inArray(termChar, separators) === -1) {
        i++;

        continue;
      }

      var part = term.substr(0, i);
      var partParams = $.extend({}, params, {
        term: part
      });

      var data = createTag(partParams);

      if (data == null) {
        i++;
        continue;
      }

      callback(data);

      // Reset the term to not include the tokenized portion
      term = term.substr(i + 1) || '';
      i = 0;
    }

    return {
      term: term
    };
  };

  return Tokenizer;
});

S2.define('select2/data/minimumInputLength',[

], function () {
  function MinimumInputLength (decorated, $e, options) {
    this.minimumInputLength = options.get('minimumInputLength');

    decorated.call(this, $e, options);
  }

  MinimumInputLength.prototype.query = function (decorated, params, callback) {
    params.term = params.term || '';

    if (params.term.length < this.minimumInputLength) {
      this.trigger('results:message', {
        message: 'inputTooShort',
        args: {
          minimum: this.minimumInputLength,
          input: params.term,
          params: params
        }
      });

      return;
    }

    decorated.call(this, params, callback);
  };

  return MinimumInputLength;
});

S2.define('select2/data/maximumInputLength',[

], function () {
  function MaximumInputLength (decorated, $e, options) {
    this.maximumInputLength = options.get('maximumInputLength');

    decorated.call(this, $e, options);
  }

  MaximumInputLength.prototype.query = function (decorated, params, callback) {
    params.term = params.term || '';

    if (this.maximumInputLength > 0 &&
        params.term.length > this.maximumInputLength) {
      this.trigger('results:message', {
        message: 'inputTooLong',
        args: {
          maximum: this.maximumInputLength,
          input: params.term,
          params: params
        }
      });

      return;
    }

    decorated.call(this, params, callback);
  };

  return MaximumInputLength;
});

S2.define('select2/data/maximumSelectionLength',[

], function (){
  function MaximumSelectionLength (decorated, $e, options) {
    this.maximumSelectionLength = options.get('maximumSelectionLength');

    decorated.call(this, $e, options);
  }

  MaximumSelectionLength.prototype.query =
    function (decorated, params, callback) {
      var self = this;

      this.current(function (currentData) {
        var count = currentData != null ? currentData.length : 0;
        if (self.maximumSelectionLength > 0 &&
          count >= self.maximumSelectionLength) {
          self.trigger('results:message', {
            message: 'maximumSelected',
            args: {
              maximum: self.maximumSelectionLength
            }
          });
          return;
        }
        decorated.call(self, params, callback);
      });
  };

  return MaximumSelectionLength;
});

S2.define('select2/dropdown',[
  'jquery',
  './utils'
], function ($, Utils) {
  function Dropdown ($element, options) {
    this.$element = $element;
    this.options = options;

    Dropdown.__super__.constructor.call(this);
  }

  Utils.Extend(Dropdown, Utils.Observable);

  Dropdown.prototype.render = function () {
    var $dropdown = $(
      '<span class="select2-dropdown">' +
        '<span class="select2-results"></span>' +
      '</span>'
    );

    $dropdown.attr('dir', this.options.get('dir'));

    this.$dropdown = $dropdown;

    return $dropdown;
  };

  Dropdown.prototype.bind = function () {
    // Should be implemented in subclasses
  };

  Dropdown.prototype.position = function ($dropdown, $container) {
    // Should be implmented in subclasses
  };

  Dropdown.prototype.destroy = function () {
    // Remove the dropdown from the DOM
    this.$dropdown.remove();
  };

  return Dropdown;
});

S2.define('select2/dropdown/search',[
  'jquery',
  '../utils'
], function ($, Utils) {
  function Search () { }

  Search.prototype.render = function (decorated) {
    var $rendered = decorated.call(this);

    var $search = $(
      '<span class="select2-search select2-search--dropdown">' +
        '<input class="select2-search__field" type="text" tabindex="-1"' +
        ' autocomplete="off" autocorrect="off" autocapitalize="none"' +
        ' spellcheck="false" role="combobox" aria-autocomplete="list" aria-expanded="true" />' +
      '</span>'
    );

    this.$searchContainer = $search;
    this.$search = $search.find('input');

    $rendered.prepend($search);

    return $rendered;
  };

  Search.prototype.bind = function (decorated, container, $container) {
    var self = this;
    var resultsId = container.id + '-results';

    decorated.call(this, container, $container);

    this.$search.on('keydown', function (evt) {
      self.trigger('keypress', evt);

      self._keyUpPrevented = evt.isDefaultPrevented();
    });

    // Workaround for browsers which do not support the `input` event
    // This will prevent double-triggering of events for browsers which support
    // both the `keyup` and `input` events.
    this.$search.on('input', function (evt) {
      // Unbind the duplicated `keyup` event
      $(this).off('keyup');
    });

    this.$search.on('keyup input', function (evt) {
      self.handleSearch(evt);
    });

    container.on('open', function () {
      self.$search.attr('tabindex', 0);
      self.$search.attr('aria-owns', resultsId);
      self.$search.focus();

      window.setTimeout(function () {
        self.$search.focus();
      }, 0);
    });

    container.on('close', function () {
      self.$search.attr('tabindex', -1);
      self.$search.removeAttr('aria-activedescendant');
      self.$search.removeAttr('aria-owns');
      self.$search.val('');
    });

    container.on('focus', function () {
      if (!container.isOpen()) {
        self.$search.focus();
      }
    });

    container.on('results:all', function (params) {
      if (params.query.term == null || params.query.term === '') {
        var showSearch = self.showSearch(params);

        if (showSearch) {
          self.$searchContainer.removeClass('select2-search--hide');
        } else {
          self.$searchContainer.addClass('select2-search--hide');
        }
      }
    });

    container.on('results:focus', function (params) {
      self.$search.attr('aria-activedescendant', params.data._resultId);
    });
  };

  Search.prototype.handleSearch = function (evt) {
    if (!this._keyUpPrevented) {
      var input = this.$search.val();

      this.trigger('query', {
        term: input
      });
    }

    this._keyUpPrevented = false;
  };

  Search.prototype.showSearch = function (_, params) {
    return true;
  };

  return Search;
});

S2.define('select2/dropdown/hidePlaceholder',[

], function () {
  function HidePlaceholder (decorated, $element, options, dataAdapter) {
    this.placeholder = this.normalizePlaceholder(options.get('placeholder'));

    decorated.call(this, $element, options, dataAdapter);
  }

  HidePlaceholder.prototype.append = function (decorated, data) {
    data.results = this.removePlaceholder(data.results);

    decorated.call(this, data);
  };

  HidePlaceholder.prototype.normalizePlaceholder = function (_, placeholder) {
    if (typeof placeholder === 'string') {
      placeholder = {
        id: '',
        text: placeholder
      };
    }

    return placeholder;
  };

  HidePlaceholder.prototype.removePlaceholder = function (_, data) {
    var modifiedData = data.slice(0);

    for (var d = data.length - 1; d >= 0; d--) {
      var item = data[d];

      if (this.placeholder.id === item.id) {
        modifiedData.splice(d, 1);
      }
    }

    return modifiedData;
  };

  return HidePlaceholder;
});

S2.define('select2/dropdown/infiniteScroll',[
  'jquery'
], function ($) {
  function InfiniteScroll (decorated, $element, options, dataAdapter) {
    this.lastParams = {};

    decorated.call(this, $element, options, dataAdapter);

    this.$loadingMore = this.createLoadingMore();
    this.loading = false;
  }

  InfiniteScroll.prototype.append = function (decorated, data) {
    this.$loadingMore.remove();
    this.loading = false;

    decorated.call(this, data);

    if (this.showLoadingMore(data)) {
      this.$results.append(this.$loadingMore);
    }
  };

  InfiniteScroll.prototype.bind = function (decorated, container, $container) {
    var self = this;

    decorated.call(this, container, $container);

    container.on('query', function (params) {
      self.lastParams = params;
      self.loading = true;
    });

    container.on('query:append', function (params) {
      self.lastParams = params;
      self.loading = true;
    });

    this.$results.on('scroll', function () {
      var isLoadMoreVisible = $.contains(
        document.documentElement,
        self.$loadingMore[0]
      );

      if (self.loading || !isLoadMoreVisible) {
        return;
      }

      var currentOffset = self.$results.offset().top +
        self.$results.outerHeight(false);
      var loadingMoreOffset = self.$loadingMore.offset().top +
        self.$loadingMore.outerHeight(false);

      if (currentOffset + 50 >= loadingMoreOffset) {
        self.loadMore();
      }
    });
  };

  InfiniteScroll.prototype.loadMore = function () {
    this.loading = true;

    var params = $.extend({}, {page: 1}, this.lastParams);

    params.page++;

    this.trigger('query:append', params);
  };

  InfiniteScroll.prototype.showLoadingMore = function (_, data) {
    return data.pagination && data.pagination.more;
  };

  InfiniteScroll.prototype.createLoadingMore = function () {
    var $option = $(
      '<li ' +
      'class="select2-results__option select2-results__option--load-more"' +
      'role="option" aria-disabled="true"></li>'
    );

    var message = this.options.get('translations').get('loadingMore');

    $option.html(message(this.lastParams));

    return $option;
  };

  return InfiniteScroll;
});

S2.define('select2/dropdown/attachBody',[
  'jquery',
  '../utils'
], function ($, Utils) {
  function AttachBody (decorated, $element, options) {
    this.$dropdownParent = options.get('dropdownParent') || $(document.body);

    decorated.call(this, $element, options);
  }

  AttachBody.prototype.bind = function (decorated, container, $container) {
    var self = this;

    var setupResultsEvents = false;

    decorated.call(this, container, $container);

    container.on('open', function () {
      self._showDropdown();
      self._attachPositioningHandler(container);

      if (!setupResultsEvents) {
        setupResultsEvents = true;

        container.on('results:all', function () {
          self._positionDropdown();
          self._resizeDropdown();
        });

        container.on('results:append', function () {
          self._positionDropdown();
          self._resizeDropdown();
        });
      }
    });

    container.on('close', function () {
      self._hideDropdown();
      self._detachPositioningHandler(container);
    });

    this.$dropdownContainer.on('mousedown', function (evt) {
      evt.stopPropagation();
    });
  };

  AttachBody.prototype.destroy = function (decorated) {
    decorated.call(this);

    this.$dropdownContainer.remove();
  };

  AttachBody.prototype.position = function (decorated, $dropdown, $container) {
    // Clone all of the container classes
    $dropdown.attr('class', $container.attr('class'));

    $dropdown.removeClass('select2');
    $dropdown.addClass('select2-container--open');

    $dropdown.css({
      position: 'absolute',
      top: -999999
    });

    this.$container = $container;
  };

  AttachBody.prototype.render = function (decorated) {
    var $container = $('<span></span>');

    var $dropdown = decorated.call(this);
    $container.append($dropdown);

    this.$dropdownContainer = $container;

    return $container;
  };

  AttachBody.prototype._hideDropdown = function (decorated) {
    this.$dropdownContainer.detach();
  };

  AttachBody.prototype._attachPositioningHandler =
      function (decorated, container) {
    var self = this;

    var scrollEvent = 'scroll.select2.' + container.id;
    var resizeEvent = 'resize.select2.' + container.id;
    var orientationEvent = 'orientationchange.select2.' + container.id;

    var $watchers = this.$container.parents().filter(Utils.hasScroll);
    $watchers.each(function () {
      $(this).data('select2-scroll-position', {
        x: $(this).scrollLeft(),
        y: $(this).scrollTop()
      });
    });

    $watchers.on(scrollEvent, function (ev) {
      var position = $(this).data('select2-scroll-position');
      $(this).scrollTop(position.y);
    });

    $(window).on(scrollEvent + ' ' + resizeEvent + ' ' + orientationEvent,
      function (e) {
      self._positionDropdown();
      self._resizeDropdown();
    });
  };

  AttachBody.prototype._detachPositioningHandler =
      function (decorated, container) {
    var scrollEvent = 'scroll.select2.' + container.id;
    var resizeEvent = 'resize.select2.' + container.id;
    var orientationEvent = 'orientationchange.select2.' + container.id;

    var $watchers = this.$container.parents().filter(Utils.hasScroll);
    $watchers.off(scrollEvent);

    $(window).off(scrollEvent + ' ' + resizeEvent + ' ' + orientationEvent);
  };

  AttachBody.prototype._positionDropdown = function () {
    var $window = $(window);

    var isCurrentlyAbove = this.$dropdown.hasClass('select2-dropdown--above');
    var isCurrentlyBelow = this.$dropdown.hasClass('select2-dropdown--below');

    var newDirection = null;

    var offset = this.$container.offset();

    offset.bottom = offset.top + this.$container.outerHeight(false);

    var container = {
      height: this.$container.outerHeight(false)
    };

    container.top = offset.top;
    container.bottom = offset.top + container.height;

    var dropdown = {
      height: this.$dropdown.outerHeight(false)
    };

    var viewport = {
      top: $window.scrollTop(),
      bottom: $window.scrollTop() + $window.height()
    };

    var enoughRoomAbove = viewport.top < (offset.top - dropdown.height);
    var enoughRoomBelow = viewport.bottom > (offset.bottom + dropdown.height);

    var css = {
      left: offset.left,
      top: container.bottom
    };

    // Determine what the parent element is to use for calciulating the offset
    var $offsetParent = this.$dropdownParent;

    // For statically positoned elements, we need to get the element
    // that is determining the offset
    if ($offsetParent.css('position') === 'static') {
      $offsetParent = $offsetParent.offsetParent();
    }

    var parentOffset = $offsetParent.offset();

    css.top -= parentOffset.top;
    css.left -= parentOffset.left;

    if (!isCurrentlyAbove && !isCurrentlyBelow) {
      newDirection = 'below';
    }

    if (!enoughRoomBelow && enoughRoomAbove && !isCurrentlyAbove) {
      newDirection = 'above';
    } else if (!enoughRoomAbove && enoughRoomBelow && isCurrentlyAbove) {
      newDirection = 'below';
    }

    if (newDirection == 'above' ||
      (isCurrentlyAbove && newDirection !== 'below')) {
      css.top = container.top - parentOffset.top - dropdown.height;
    }

    if (newDirection != null) {
      this.$dropdown
        .removeClass('select2-dropdown--below select2-dropdown--above')
        .addClass('select2-dropdown--' + newDirection);
      this.$container
        .removeClass('select2-container--below select2-container--above')
        .addClass('select2-container--' + newDirection);
    }

    this.$dropdownContainer.css(css);
  };

  AttachBody.prototype._resizeDropdown = function () {
    var css = {
      width: this.$container.outerWidth(false) + 'px'
    };

    if (this.options.get('dropdownAutoWidth')) {
      css.minWidth = css.width;
      css.position = 'relative';
      css.width = 'auto';
    }

    this.$dropdown.css(css);
  };

  AttachBody.prototype._showDropdown = function (decorated) {
    this.$dropdownContainer.appendTo(this.$dropdownParent);

    this._positionDropdown();
    this._resizeDropdown();
  };

  return AttachBody;
});

S2.define('select2/dropdown/minimumResultsForSearch',[

], function () {
  function countResults (data) {
    var count = 0;

    for (var d = 0; d < data.length; d++) {
      var item = data[d];

      if (item.children) {
        count += countResults(item.children);
      } else {
        count++;
      }
    }

    return count;
  }

  function MinimumResultsForSearch (decorated, $element, options, dataAdapter) {
    this.minimumResultsForSearch = options.get('minimumResultsForSearch');

    if (this.minimumResultsForSearch < 0) {
      this.minimumResultsForSearch = Infinity;
    }

    decorated.call(this, $element, options, dataAdapter);
  }

  MinimumResultsForSearch.prototype.showSearch = function (decorated, params) {
    if (countResults(params.data.results) < this.minimumResultsForSearch) {
      return false;
    }

    return decorated.call(this, params);
  };

  return MinimumResultsForSearch;
});

S2.define('select2/dropdown/selectOnClose',[

], function () {
  function SelectOnClose () { }

  SelectOnClose.prototype.bind = function (decorated, container, $container) {
    var self = this;

    decorated.call(this, container, $container);

    container.on('close', function (params) {
      self._handleSelectOnClose(params);
    });
  };

  SelectOnClose.prototype._handleSelectOnClose = function (_, params) {
    if (params && params.originalSelect2Event != null) {
      var event = params.originalSelect2Event;

      // Don't select an item if the close event was triggered from a select or
      // unselect event
      if (event._type === 'select' || event._type === 'unselect') {
        return;
      }
    }

    var $highlightedResults = this.getHighlightedResults();

    // Only select highlighted results
    if ($highlightedResults.length < 1) {
      return;
    }

    var data = $highlightedResults.data('data');

    // Don't re-select already selected resulte
    if (
      (data.element != null && data.element.selected) ||
      (data.element == null && data.selected)
    ) {
      return;
    }

    this.trigger('select', {
        data: data
    });
  };

  return SelectOnClose;
});

S2.define('select2/dropdown/closeOnSelect',[

], function () {
  function CloseOnSelect () { }

  CloseOnSelect.prototype.bind = function (decorated, container, $container) {
    var self = this;

    decorated.call(this, container, $container);

    container.on('select', function (evt) {
      self._selectTriggered(evt);
    });

    container.on('unselect', function (evt) {
      self._selectTriggered(evt);
    });
  };

  CloseOnSelect.prototype._selectTriggered = function (_, evt) {
    var originalEvent = evt.originalEvent;

    // Don't close if the control key is being held
    if (originalEvent && originalEvent.ctrlKey) {
      return;
    }

    this.trigger('close', {
      originalEvent: originalEvent,
      originalSelect2Event: evt
    });
  };

  return CloseOnSelect;
});

S2.define('select2/i18n/en',[],function () {
  // English
  return {
    errorLoading: function () {
      return 'The results could not be loaded.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Please delete ' + overChars + ' character';

      if (overChars != 1) {
        message += 's';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Please enter ' + remainingChars + ' or more characters';

      return message;
    },
    loadingMore: function () {
      return 'Loading more results…';
    },
    maximumSelected: function (args) {
      var message = 'You can only select ' + args.maximum + ' item';

      if (args.maximum != 1) {
        message += 's';
      }

      return message;
    },
    noResults: function () {
      return 'No results found';
    },
    searching: function () {
      return 'Searching…';
    }
  };
});

S2.define('select2/defaults',[
  'jquery',
  'require',

  './results',

  './selection/single',
  './selection/multiple',
  './selection/placeholder',
  './selection/allowClear',
  './selection/search',
  './selection/eventRelay',

  './utils',
  './translation',
  './diacritics',

  './data/select',
  './data/array',
  './data/ajax',
  './data/tags',
  './data/tokenizer',
  './data/minimumInputLength',
  './data/maximumInputLength',
  './data/maximumSelectionLength',

  './dropdown',
  './dropdown/search',
  './dropdown/hidePlaceholder',
  './dropdown/infiniteScroll',
  './dropdown/attachBody',
  './dropdown/minimumResultsForSearch',
  './dropdown/selectOnClose',
  './dropdown/closeOnSelect',

  './i18n/en'
], function ($, require,

             ResultsList,

             SingleSelection, MultipleSelection, Placeholder, AllowClear,
             SelectionSearch, EventRelay,

             Utils, Translation, DIACRITICS,

             SelectData, ArrayData, AjaxData, Tags, Tokenizer,
             MinimumInputLength, MaximumInputLength, MaximumSelectionLength,

             Dropdown, DropdownSearch, HidePlaceholder, InfiniteScroll,
             AttachBody, MinimumResultsForSearch, SelectOnClose, CloseOnSelect,

             EnglishTranslation) {
  function Defaults () {
    this.reset();
  }

  Defaults.prototype.apply = function (options) {
    options = $.extend(true, {}, this.defaults, options);

    if (options.dataAdapter == null) {
      if (options.ajax != null) {
        options.dataAdapter = AjaxData;
      } else if (options.data != null) {
        options.dataAdapter = ArrayData;
      } else {
        options.dataAdapter = SelectData;
      }

      if (options.minimumInputLength > 0) {
        options.dataAdapter = Utils.Decorate(
          options.dataAdapter,
          MinimumInputLength
        );
      }

      if (options.maximumInputLength > 0) {
        options.dataAdapter = Utils.Decorate(
          options.dataAdapter,
          MaximumInputLength
        );
      }

      if (options.maximumSelectionLength > 0) {
        options.dataAdapter = Utils.Decorate(
          options.dataAdapter,
          MaximumSelectionLength
        );
      }

      if (options.tags) {
        options.dataAdapter = Utils.Decorate(options.dataAdapter, Tags);
      }

      if (options.tokenSeparators != null || options.tokenizer != null) {
        options.dataAdapter = Utils.Decorate(
          options.dataAdapter,
          Tokenizer
        );
      }

      if (options.query != null) {
        var Query = require(options.amdBase + 'compat/query');

        options.dataAdapter = Utils.Decorate(
          options.dataAdapter,
          Query
        );
      }

      if (options.initSelection != null) {
        var InitSelection = require(options.amdBase + 'compat/initSelection');

        options.dataAdapter = Utils.Decorate(
          options.dataAdapter,
          InitSelection
        );
      }
    }

    if (options.resultsAdapter == null) {
      options.resultsAdapter = ResultsList;

      if (options.ajax != null) {
        options.resultsAdapter = Utils.Decorate(
          options.resultsAdapter,
          InfiniteScroll
        );
      }

      if (options.placeholder != null) {
        options.resultsAdapter = Utils.Decorate(
          options.resultsAdapter,
          HidePlaceholder
        );
      }

      if (options.selectOnClose) {
        options.resultsAdapter = Utils.Decorate(
          options.resultsAdapter,
          SelectOnClose
        );
      }
    }

    if (options.dropdownAdapter == null) {
      if (options.multiple) {
        options.dropdownAdapter = Dropdown;
      } else {
        var SearchableDropdown = Utils.Decorate(Dropdown, DropdownSearch);

        options.dropdownAdapter = SearchableDropdown;
      }

      if (options.minimumResultsForSearch !== 0) {
        options.dropdownAdapter = Utils.Decorate(
          options.dropdownAdapter,
          MinimumResultsForSearch
        );
      }

      if (options.closeOnSelect) {
        options.dropdownAdapter = Utils.Decorate(
          options.dropdownAdapter,
          CloseOnSelect
        );
      }

      if (
        options.dropdownCssClass != null ||
        options.dropdownCss != null ||
        options.adaptDropdownCssClass != null
      ) {
        var DropdownCSS = require(options.amdBase + 'compat/dropdownCss');

        options.dropdownAdapter = Utils.Decorate(
          options.dropdownAdapter,
          DropdownCSS
        );
      }

      options.dropdownAdapter = Utils.Decorate(
        options.dropdownAdapter,
        AttachBody
      );
    }

    if (options.selectionAdapter == null) {
      if (options.multiple) {
        options.selectionAdapter = MultipleSelection;
      } else {
        options.selectionAdapter = SingleSelection;
      }

      // Add the placeholder mixin if a placeholder was specified
      if (options.placeholder != null) {
        options.selectionAdapter = Utils.Decorate(
          options.selectionAdapter,
          Placeholder
        );
      }

      if (options.allowClear) {
        options.selectionAdapter = Utils.Decorate(
          options.selectionAdapter,
          AllowClear
        );
      }

      if (options.multiple) {
        options.selectionAdapter = Utils.Decorate(
          options.selectionAdapter,
          SelectionSearch
        );
      }

      if (
        options.containerCssClass != null ||
        options.containerCss != null ||
        options.adaptContainerCssClass != null
      ) {
        var ContainerCSS = require(options.amdBase + 'compat/containerCss');

        options.selectionAdapter = Utils.Decorate(
          options.selectionAdapter,
          ContainerCSS
        );
      }

      options.selectionAdapter = Utils.Decorate(
        options.selectionAdapter,
        EventRelay
      );
    }

    if (typeof options.language === 'string') {
      // Check if the language is specified with a region
      if (options.language.indexOf('-') > 0) {
        // Extract the region information if it is included
        var languageParts = options.language.split('-');
        var baseLanguage = languageParts[0];

        options.language = [options.language, baseLanguage];
      } else {
        options.language = [options.language];
      }
    }

    if ($.isArray(options.language)) {
      var languages = new Translation();
      options.language.push('en');

      var languageNames = options.language;

      for (var l = 0; l < languageNames.length; l++) {
        var name = languageNames[l];
        var language = {};

        try {
          // Try to load it with the original name
          language = Translation.loadPath(name);
        } catch (e) {
          try {
            // If we couldn't load it, check if it wasn't the full path
            name = this.defaults.amdLanguageBase + name;
            language = Translation.loadPath(name);
          } catch (ex) {
            // The translation could not be loaded at all. Sometimes this is
            // because of a configuration problem, other times this can be
            // because of how Select2 helps load all possible translation files.
            if (options.debug && window.console && console.warn) {
              console.warn(
                'Select2: The language file for "' + name + '" could not be ' +
                'automatically loaded. A fallback will be used instead.'
              );
            }

            continue;
          }
        }

        languages.extend(language);
      }

      options.translations = languages;
    } else {
      var baseTranslation = Translation.loadPath(
        this.defaults.amdLanguageBase + 'en'
      );
      var customTranslation = new Translation(options.language);

      customTranslation.extend(baseTranslation);

      options.translations = customTranslation;
    }

    return options;
  };

  Defaults.prototype.reset = function () {
    function stripDiacritics (text) {
      // Used 'uni range + named function' from http://jsperf.com/diacritics/18
      function match(a) {
        return DIACRITICS[a] || a;
      }

      return text.replace(/[^\u0000-\u007E]/g, match);
    }

    function matcher (params, data) {
      // Always return the object if there is nothing to compare
      if ($.trim(params.term) === '') {
        return data;
      }

      // Do a recursive check for options with children
      if (data.children && data.children.length > 0) {
        // Clone the data object if there are children
        // This is required as we modify the object to remove any non-matches
        var match = $.extend(true, {}, data);

        // Check each child of the option
        for (var c = data.children.length - 1; c >= 0; c--) {
          var child = data.children[c];

          var matches = matcher(params, child);

          // If there wasn't a match, remove the object in the array
          if (matches == null) {
            match.children.splice(c, 1);
          }
        }

        // If any children matched, return the new object
        if (match.children.length > 0) {
          return match;
        }

        // If there were no matching children, check just the plain object
        return matcher(params, match);
      }

      var original = stripDiacritics(data.text).toUpperCase();
      var term = stripDiacritics(params.term).toUpperCase();

      // Check if the text contains the term
      if (original.indexOf(term) > -1) {
        return data;
      }

      // If it doesn't contain the term, don't return anything
      return null;
    }

    this.defaults = {
      amdBase: './',
      amdLanguageBase: './i18n/',
      closeOnSelect: true,
      debug: false,
      dropdownAutoWidth: false,
      escapeMarkup: Utils.escapeMarkup,
      language: EnglishTranslation,
      matcher: matcher,
      minimumInputLength: 0,
      maximumInputLength: 0,
      maximumSelectionLength: 0,
      minimumResultsForSearch: 0,
      selectOnClose: false,
      sorter: function (data) {
        return data;
      },
      templateResult: function (result) {
        return result.text;
      },
      templateSelection: function (selection) {
        return selection.text;
      },
      theme: 'default',
      width: 'resolve'
    };
  };

  Defaults.prototype.set = function (key, value) {
    var camelKey = $.camelCase(key);

    var data = {};
    data[camelKey] = value;

    var convertedData = Utils._convertData(data);

    $.extend(this.defaults, convertedData);
  };

  var defaults = new Defaults();

  return defaults;
});

S2.define('select2/options',[
  'require',
  'jquery',
  './defaults',
  './utils'
], function (require, $, Defaults, Utils) {
  function Options (options, $element) {
    this.options = options;

    if ($element != null) {
      this.fromElement($element);
    }

    this.options = Defaults.apply(this.options);

    if ($element && $element.is('input')) {
      var InputCompat = require(this.get('amdBase') + 'compat/inputData');

      this.options.dataAdapter = Utils.Decorate(
        this.options.dataAdapter,
        InputCompat
      );
    }
  }

  Options.prototype.fromElement = function ($e) {
    var excludedData = ['select2'];

    if (this.options.multiple == null) {
      this.options.multiple = $e.prop('multiple');
    }

    if (this.options.disabled == null) {
      this.options.disabled = $e.prop('disabled');
    }

    if (this.options.language == null) {
      if ($e.prop('lang')) {
        this.options.language = $e.prop('lang').toLowerCase();
      } else if ($e.closest('[lang]').prop('lang')) {
        this.options.language = $e.closest('[lang]').prop('lang');
      }
    }

    if (this.options.dir == null) {
      if ($e.prop('dir')) {
        this.options.dir = $e.prop('dir');
      } else if ($e.closest('[dir]').prop('dir')) {
        this.options.dir = $e.closest('[dir]').prop('dir');
      } else {
        this.options.dir = 'ltr';
      }
    }

    $e.prop('disabled', this.options.disabled);
    $e.prop('multiple', this.options.multiple);

    if ($e.data('select2Tags')) {
      if (this.options.debug && window.console && console.warn) {
        console.warn(
          'Select2: The `data-select2-tags` attribute has been changed to ' +
          'use the `data-data` and `data-tags="true"` attributes and will be ' +
          'removed in future versions of Select2.'
        );
      }

      $e.data('data', $e.data('select2Tags'));
      $e.data('tags', true);
    }

    if ($e.data('ajaxUrl')) {
      if (this.options.debug && window.console && console.warn) {
        console.warn(
          'Select2: The `data-ajax-url` attribute has been changed to ' +
          '`data-ajax--url` and support for the old attribute will be removed' +
          ' in future versions of Select2.'
        );
      }

      $e.attr('ajax--url', $e.data('ajaxUrl'));
      $e.data('ajax--url', $e.data('ajaxUrl'));
    }

    var dataset = {};

    // Prefer the element's `dataset` attribute if it exists
    // jQuery 1.x does not correctly handle data attributes with multiple dashes
    if ($.fn.jquery && $.fn.jquery.substr(0, 2) == '1.' && $e[0].dataset) {
      dataset = $.extend(true, {}, $e[0].dataset, $e.data());
    } else {
      dataset = $e.data();
    }

    var data = $.extend(true, {}, dataset);

    data = Utils._convertData(data);

    for (var key in data) {
      if ($.inArray(key, excludedData) > -1) {
        continue;
      }

      if ($.isPlainObject(this.options[key])) {
        $.extend(this.options[key], data[key]);
      } else {
        this.options[key] = data[key];
      }
    }

    return this;
  };

  Options.prototype.get = function (key) {
    return this.options[key];
  };

  Options.prototype.set = function (key, val) {
    this.options[key] = val;
  };

  return Options;
});

S2.define('select2/core',[
  'jquery',
  './options',
  './utils',
  './keys'
], function ($, Options, Utils, KEYS) {
  var Select2 = function ($element, options) {
    if ($element.data('select2') != null) {
      $element.data('select2').destroy();
    }

    this.$element = $element;

    this.id = this._generateId($element);

    options = options || {};

    this.options = new Options(options, $element);

    Select2.__super__.constructor.call(this);

    // Set up the tabindex

    var tabindex = $element.attr('tabindex') || 0;
    $element.data('old-tabindex', tabindex);
    $element.attr('tabindex', '-1');

    // Set up containers and adapters

    var DataAdapter = this.options.get('dataAdapter');
    this.dataAdapter = new DataAdapter($element, this.options);

    var $container = this.render();

    this._placeContainer($container);

    var SelectionAdapter = this.options.get('selectionAdapter');
    this.selection = new SelectionAdapter($element, this.options);
    this.$selection = this.selection.render();

    this.selection.position(this.$selection, $container);

    var DropdownAdapter = this.options.get('dropdownAdapter');
    this.dropdown = new DropdownAdapter($element, this.options);
    this.$dropdown = this.dropdown.render();

    this.dropdown.position(this.$dropdown, $container);

    var ResultsAdapter = this.options.get('resultsAdapter');
    this.results = new ResultsAdapter($element, this.options, this.dataAdapter);
    this.$results = this.results.render();

    this.results.position(this.$results, this.$dropdown);

    // Bind events

    var self = this;

    // Bind the container to all of the adapters
    this._bindAdapters();

    // Register any DOM event handlers
    this._registerDomEvents();

    // Register any internal event handlers
    this._registerDataEvents();
    this._registerSelectionEvents();
    this._registerDropdownEvents();
    this._registerResultsEvents();
    this._registerEvents();

    // Set the initial state
    this.dataAdapter.current(function (initialData) {
      self.trigger('selection:update', {
        data: initialData
      });
    });

    // Hide the original select
    $element.addClass('select2-hidden-accessible');
    $element.attr('aria-hidden', 'true');

    // Synchronize any monitored attributes
    this._syncAttributes();

    $element.data('select2', this);
  };

  Utils.Extend(Select2, Utils.Observable);

  Select2.prototype._generateId = function ($element) {
    var id = '';

    if ($element.attr('id') != null) {
      id = $element.attr('id');
    } else if ($element.attr('name') != null) {
      id = $element.attr('name') + '-' + Utils.generateChars(2);
    } else {
      id = Utils.generateChars(4);
    }

    id = id.replace(/(:|\.|\[|\]|,)/g, '');
    id = 'select2-' + id;

    return id;
  };

  Select2.prototype._placeContainer = function ($container) {
    $container.insertAfter(this.$element);

    var width = this._resolveWidth(this.$element, this.options.get('width'));

    if (width != null) {
      $container.css('width', width);
    }
  };

  Select2.prototype._resolveWidth = function ($element, method) {
    var WIDTH = /^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i;

    if (method == 'resolve') {
      var styleWidth = this._resolveWidth($element, 'style');

      if (styleWidth != null) {
        return styleWidth;
      }

      return this._resolveWidth($element, 'element');
    }

    if (method == 'element') {
      var elementWidth = $element.outerWidth(false);

      if (elementWidth <= 0) {
        return 'auto';
      }

      return elementWidth + 'px';
    }

    if (method == 'style') {
      var style = $element.attr('style');

      if (typeof(style) !== 'string') {
        return null;
      }

      var attrs = style.split(';');

      for (var i = 0, l = attrs.length; i < l; i = i + 1) {
        var attr = attrs[i].replace(/\s/g, '');
        var matches = attr.match(WIDTH);

        if (matches !== null && matches.length >= 1) {
          return matches[1];
        }
      }

      return null;
    }

    return method;
  };

  Select2.prototype._bindAdapters = function () {
    this.dataAdapter.bind(this, this.$container);
    this.selection.bind(this, this.$container);

    this.dropdown.bind(this, this.$container);
    this.results.bind(this, this.$container);
  };

  Select2.prototype._registerDomEvents = function () {
    var self = this;

    this.$element.on('change.select2', function () {
      self.dataAdapter.current(function (data) {
        self.trigger('selection:update', {
          data: data
        });
      });
    });

    this.$element.on('focus.select2', function (evt) {
      self.trigger('focus', evt);
    });

    this._syncA = Utils.bind(this._syncAttributes, this);
    this._syncS = Utils.bind(this._syncSubtree, this);

    if (this.$element[0].attachEvent) {
      this.$element[0].attachEvent('onpropertychange', this._syncA);
    }

    var observer = window.MutationObserver ||
      window.WebKitMutationObserver ||
      window.MozMutationObserver
    ;

    if (observer != null) {
      this._observer = new observer(function (mutations) {
        $.each(mutations, self._syncA);
        $.each(mutations, self._syncS);
      });
      this._observer.observe(this.$element[0], {
        attributes: true,
        childList: true,
        subtree: false
      });
    } else if (this.$element[0].addEventListener) {
      this.$element[0].addEventListener(
        'DOMAttrModified',
        self._syncA,
        false
      );
      this.$element[0].addEventListener(
        'DOMNodeInserted',
        self._syncS,
        false
      );
      this.$element[0].addEventListener(
        'DOMNodeRemoved',
        self._syncS,
        false
      );
    }
  };

  Select2.prototype._registerDataEvents = function () {
    var self = this;

    this.dataAdapter.on('*', function (name, params) {
      self.trigger(name, params);
    });
  };

  Select2.prototype._registerSelectionEvents = function () {
    var self = this;
    var nonRelayEvents = ['toggle', 'focus'];

    this.selection.on('toggle', function () {
      self.toggleDropdown();
    });

    this.selection.on('focus', function (params) {
      self.focus(params);
    });

    this.selection.on('*', function (name, params) {
      if ($.inArray(name, nonRelayEvents) !== -1) {
        return;
      }

      self.trigger(name, params);
    });
  };

  Select2.prototype._registerDropdownEvents = function () {
    var self = this;

    this.dropdown.on('*', function (name, params) {
      self.trigger(name, params);
    });
  };

  Select2.prototype._registerResultsEvents = function () {
    var self = this;

    this.results.on('*', function (name, params) {
      self.trigger(name, params);
    });
  };

  Select2.prototype._registerEvents = function () {
    var self = this;

    this.on('open', function () {
      self.$container.addClass('select2-container--open');
    });

    this.on('close', function () {
      self.$container.removeClass('select2-container--open');
    });

    this.on('enable', function () {
      self.$container.removeClass('select2-container--disabled');
    });

    this.on('disable', function () {
      self.$container.addClass('select2-container--disabled');
    });

    this.on('blur', function () {
      self.$container.removeClass('select2-container--focus');
    });

    this.on('query', function (params) {
      if (!self.isOpen()) {
        self.trigger('open', {});
      }

      this.dataAdapter.query(params, function (data) {
        self.trigger('results:all', {
          data: data,
          query: params
        });
      });
    });

    this.on('query:append', function (params) {
      this.dataAdapter.query(params, function (data) {
        self.trigger('results:append', {
          data: data,
          query: params
        });
      });
    });

    this.on('open', function(){
      // Focus on the active element when opening dropdown.
      // Needs 1 ms delay because of other 1 ms setTimeouts when rendering.
      setTimeout(function(){
        self.focusOnActiveElement();
      }, 1);
    });

    $(document).on('keydown', function (evt) {
      var key = evt.which;
      if (self.isOpen()) {
        if (key === KEYS.ESC || key === KEYS.TAB ||
            (key === KEYS.UP && evt.altKey)) {
          self.close();

          evt.preventDefault();
        } else if (key === KEYS.ENTER) {
          self.trigger('results:select', {});

          evt.preventDefault();
        } else if ((key === KEYS.SPACE && evt.ctrlKey)) {
          self.trigger('results:toggle', {});

          evt.preventDefault();
        } else if (key === KEYS.UP) {
          self.trigger('results:previous', {});

          evt.preventDefault();
        } else if (key === KEYS.DOWN) {
          self.trigger('results:next', {});

          evt.preventDefault();
        }

        var $searchField = self.$dropdown.find('.select2-search__field');
        if (! $searchField.length) {
          $searchField = self.$container.find('.select2-search__field');
        }

        // Move the focus to the selected element on keyboard navigation.
        // Required for screen readers to work properly.
        if (key === KEYS.DOWN || key === KEYS.UP) {
            self.focusOnActiveElement();
        } else {
          // Focus on the search if user starts typing.
          $searchField.focus();
          // Focus back to active selection when finished typing.
          // Small delay so typed character can be read by screen reader.
          setTimeout(function(){
              self.focusOnActiveElement();
          }, 1000);
        }
      } else if (self.hasFocus()) {
        if (key === KEYS.ENTER || key === KEYS.SPACE ||
            key === KEYS.DOWN) {
          self.open();
          evt.preventDefault();
        }
      }
    });
  };

  Select2.prototype.focusOnActiveElement = function () {
    // Don't mess with the focus on touchscreens because it causes havoc with on-screen keyboards.
    if (this.isOpen() && ! Utils.isTouchscreen()) {
      this.$results.find('li.select2-results__option--highlighted').focus();
    }
  };

  Select2.prototype._syncAttributes = function () {
    this.options.set('disabled', this.$element.prop('disabled'));

    if (this.options.get('disabled')) {
      if (this.isOpen()) {
        this.close();
      }

      this.trigger('disable', {});
    } else {
      this.trigger('enable', {});
    }
  };

  Select2.prototype._syncSubtree = function (evt, mutations) {
    var changed = false;
    var self = this;

    // Ignore any mutation events raised for elements that aren't options or
    // optgroups. This handles the case when the select element is destroyed
    if (
      evt && evt.target && (
        evt.target.nodeName !== 'OPTION' && evt.target.nodeName !== 'OPTGROUP'
      )
    ) {
      return;
    }

    if (!mutations) {
      // If mutation events aren't supported, then we can only assume that the
      // change affected the selections
      changed = true;
    } else if (mutations.addedNodes && mutations.addedNodes.length > 0) {
      for (var n = 0; n < mutations.addedNodes.length; n++) {
        var node = mutations.addedNodes[n];

        if (node.selected) {
          changed = true;
        }
      }
    } else if (mutations.removedNodes && mutations.removedNodes.length > 0) {
      changed = true;
    }

    // Only re-pull the data if we think there is a change
    if (changed) {
      this.dataAdapter.current(function (currentData) {
        self.trigger('selection:update', {
          data: currentData
        });
      });
    }
  };

  /**
   * Override the trigger method to automatically trigger pre-events when
   * there are events that can be prevented.
   */
  Select2.prototype.trigger = function (name, args) {
    var actualTrigger = Select2.__super__.trigger;
    var preTriggerMap = {
      'open': 'opening',
      'close': 'closing',
      'select': 'selecting',
      'unselect': 'unselecting'
    };

    if (args === undefined) {
      args = {};
    }

    if (name in preTriggerMap) {
      var preTriggerName = preTriggerMap[name];
      var preTriggerArgs = {
        prevented: false,
        name: name,
        args: args
      };

      actualTrigger.call(this, preTriggerName, preTriggerArgs);

      if (preTriggerArgs.prevented) {
        args.prevented = true;

        return;
      }
    }

    actualTrigger.call(this, name, args);
  };

  Select2.prototype.toggleDropdown = function () {
    if (this.options.get('disabled')) {
      return;
    }

    if (this.isOpen()) {
      this.close();
    } else {
      this.open();
    }
  };

  Select2.prototype.open = function () {
    if (this.isOpen()) {
      return;
    }

    this.trigger('query', {});
  };

  Select2.prototype.close = function () {
    if (!this.isOpen()) {
      return;
    }

    this.trigger('close', {});
  };

  Select2.prototype.isOpen = function () {
    return this.$container.hasClass('select2-container--open');
  };

  Select2.prototype.hasFocus = function () {
    return this.$container.hasClass('select2-container--focus');
  };

  Select2.prototype.focus = function (data) {
    // No need to re-trigger focus events if we are already focused
    if (this.hasFocus()) {
      return;
    }

    this.$container.addClass('select2-container--focus');
    this.trigger('focus', {});
  };

  Select2.prototype.enable = function (args) {
    if (this.options.get('debug') && window.console && console.warn) {
      console.warn(
        'Select2: The `select2("enable")` method has been deprecated and will' +
        ' be removed in later Select2 versions. Use $element.prop("disabled")' +
        ' instead.'
      );
    }

    if (args == null || args.length === 0) {
      args = [true];
    }

    var disabled = !args[0];

    this.$element.prop('disabled', disabled);
  };

  Select2.prototype.data = function () {
    if (this.options.get('debug') &&
        arguments.length > 0 && window.console && console.warn) {
      console.warn(
        'Select2: Data can no longer be set using `select2("data")`. You ' +
        'should consider setting the value instead using `$element.val()`.'
      );
    }

    var data = [];

    this.dataAdapter.current(function (currentData) {
      data = currentData;
    });

    return data;
  };

  Select2.prototype.val = function (args) {
    if (this.options.get('debug') && window.console && console.warn) {
      console.warn(
        'Select2: The `select2("val")` method has been deprecated and will be' +
        ' removed in later Select2 versions. Use $element.val() instead.'
      );
    }

    if (args == null || args.length === 0) {
      return this.$element.val();
    }

    var newVal = args[0];

    if ($.isArray(newVal)) {
      newVal = $.map(newVal, function (obj) {
        return obj.toString();
      });
    }

    this.$element.val(newVal).trigger('change');
  };

  Select2.prototype.destroy = function () {
    this.$container.remove();

    if (this.$element[0].detachEvent) {
      this.$element[0].detachEvent('onpropertychange', this._syncA);
    }

    if (this._observer != null) {
      this._observer.disconnect();
      this._observer = null;
    } else if (this.$element[0].removeEventListener) {
      this.$element[0]
        .removeEventListener('DOMAttrModified', this._syncA, false);
      this.$element[0]
        .removeEventListener('DOMNodeInserted', this._syncS, false);
      this.$element[0]
        .removeEventListener('DOMNodeRemoved', this._syncS, false);
    }

    this._syncA = null;
    this._syncS = null;

    this.$element.off('.select2');
    this.$element.attr('tabindex', this.$element.data('old-tabindex'));

    this.$element.removeClass('select2-hidden-accessible');
    this.$element.attr('aria-hidden', 'false');
    this.$element.removeData('select2');

    this.dataAdapter.destroy();
    this.selection.destroy();
    this.dropdown.destroy();
    this.results.destroy();

    this.dataAdapter = null;
    this.selection = null;
    this.dropdown = null;
    this.results = null;
  };

  Select2.prototype.render = function () {
    var $container = $(
      '<span class="select2 select2-container">' +
        '<span class="selection"></span>' +
        '<span class="dropdown-wrapper" aria-hidden="true"></span>' +
      '</span>'
    );

    $container.attr('dir', this.options.get('dir'));

    this.$container = $container;

    this.$container.addClass('select2-container--' + this.options.get('theme'));

    $container.data('element', this.$element);

    return $container;
  };

  return Select2;
});

S2.define('jquery-mousewheel',[
  'jquery'
], function ($) {
  // Used to shim jQuery.mousewheel for non-full builds.
  return $;
});

S2.define('jquery.select2',[
  'jquery',
  'jquery-mousewheel',

  './select2/core',
  './select2/defaults'
], function ($, _, Select2, Defaults) {
  if ($.fn.selectWoo == null) {
    // All methods that should return the element
    var thisMethods = ['open', 'close', 'destroy'];

    $.fn.selectWoo = function (options) {
      options = options || {};

      if (typeof options === 'object') {
        this.each(function () {
          var instanceOptions = $.extend(true, {}, options);

          var instance = new Select2($(this), instanceOptions);
        });

        return this;
      } else if (typeof options === 'string') {
        var ret;
        var args = Array.prototype.slice.call(arguments, 1);

        this.each(function () {
          var instance = $(this).data('select2');

          if (instance == null && window.console && console.error) {
            console.error(
              'The select2(\'' + options + '\') method was called on an ' +
              'element that is not using Select2.'
            );
          }

          ret = instance[options].apply(instance, args);
        });

        // Check if we should be returning `this`
        if ($.inArray(options, thisMethods) > -1) {
          return this;
        }

        return ret;
      } else {
        throw new Error('Invalid arguments for Select2: ' + options);
      }
    };
  }

  if ($.fn.select2 != null && $.fn.select2.defaults != null) {
    $.fn.selectWoo.defaults = $.fn.select2.defaults;
  }

  if ($.fn.selectWoo.defaults == null) {
    $.fn.selectWoo.defaults = Defaults;
  }

  // Also register selectWoo under select2 if select2 is not already present.
  $.fn.select2 = $.fn.select2 || $.fn.selectWoo;

  return Select2;
});

  // Return the AMD loader configuration so it can be used outside of this file
  return {
    define: S2.define,
    require: S2.require
  };
}());

  // Autoload the jQuery bindings
  // We know that all of the modules exist above this, so we're safe
  var select2 = S2.require('jquery.select2');

  // Hold the AMD module references on the jQuery function that was just loaded
  // This allows Select2 to use the internal loader outside of this file, such
  // as in the language files.
  jQuery.fn.select2.amd = S2;
  jQuery.fn.selectWoo.amd = S2;

  // Return the Select2 instance for anyone who is importing it.
  return select2;
}));

!function(e){var t={};function r(n){if(t[n])return t[n].exports;var a=t[n]={i:n,l:!1,exports:{}};return e[n].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)r.d(n,a,function(t){return e[t]}.bind(null,a));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=108)}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t,r){e.exports=r(46)()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){!function(){e.exports=this.React}()},function(e,t,r){var n=r(90),a=r(91),o=r(35),i=r(92);e.exports=function(e,t){return n(e)||a(e,t)||o(e,t)||i()}},function(e,t){e.exports=function(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t){function r(){return e.exports=r=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},r.apply(this,arguments)}e.exports=r},function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}},function(e,t){function r(t){return e.exports=r=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},r(t)}e.exports=r},function(e,t,r){var n=r(21),a=r(23);function o(t,r){return delete e.exports[t],e.exports[t]=r,r}e.exports={Parser:n,Tokenizer:r(22),ElementType:r(16),DomHandler:a,get FeedHandler(){return o("FeedHandler",r(57))},get Stream(){return o("Stream",r(71))},get WritableStream(){return o("WritableStream",r(30))},get ProxyHandler(){return o("ProxyHandler",r(78))},get DomUtils(){return o("DomUtils",r(25))},get CollectingHandler(){return o("CollectingHandler",r(79))},DefaultHandler:a,get RssHandler(){return o("RssHandler",this.FeedHandler)},parseDOM:function(e,t){var r=new a(t);return new n(r,t).end(e),r.dom},parseFeed:function(t,r){var a=new e.exports.FeedHandler(r);return new n(a,r).end(t),a.dom},createDomStream:function(e,t,r){var o=new a(e,t,r);return new n(o,t)},EVENTS:{attribute:2,cdatastart:0,cdataend:0,text:1,processinginstruction:2,comment:1,commentend:0,closetag:1,opentag:2,opentagname:1,error:1,end:0}}},function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},function(e,t){function r(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}e.exports=function(e,t,n){return t&&r(e.prototype,t),n&&r(e,n),e}},function(e,t,r){var n=r(96);e.exports=function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&n(e,t)}},function(e,t,r){var n=r(97),a=r(8);e.exports=function(e,t){return!t||"object"!==n(t)&&"function"!=typeof t?a(e):t}},function(e,t,r){var n=E(r(98)),a=r(3),o=a.Children,i=a.cloneElement,s=a.Component,c=a.createElement,l=a.createRef,u=E(r(19)),p=r(99),d=E(p);t.Sortable=d;var h=p.Direction;t.Direction=h;var f=p.DOMRect;t.DOMRect=f;var m=p.GroupOptions;t.GroupOptions=m;var g=p.MoveEvent;t.MoveEvent=g;var b=p.Options;t.Options=b;var v=p.PullResult;t.PullResult=v;var y=p.PutResult;t.PutResult=y;var w=p.SortableEvent;t.SortableEvent=w;var _=p.SortableOptions;t.SortableOptions=_;var O=p.Utils;function E(e){return e&&e.__esModule?e.default:e}function j(e){return function(e){if(Array.isArray(e))return x(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||function(e,t){if(e){if("string"==typeof e)return x(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?x(e,t):void 0}}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function x(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}function C(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function k(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?C(Object(r),!0).forEach((function(t){S(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):C(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function S(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}function N(e){null!==e.parentElement&&e.parentElement.removeChild(e)}function z(e){e.forEach((function(e){return N(e.element)}))}function D(e){e.forEach((function(e){var t,r,n,a;t=e.parentElement,r=e.element,n=e.oldIndex,a=t.children[n]||null,t.insertBefore(r,a)}))}function A(e,t){var r=P(e),n={parentElement:e.from},a=[];switch(r){case"normal":a=[{element:e.item,newIndex:e.newIndex,oldIndex:e.oldIndex,parentElement:e.from}];break;case"swap":a=[k({element:e.item,oldIndex:e.oldIndex,newIndex:e.newIndex},n),k({element:e.swapItem,oldIndex:e.newIndex,newIndex:e.oldIndex},n)];break;case"multidrag":a=e.oldIndicies.map((function(t,r){return k({element:t.multiDragElement,oldIndex:t.index,newIndex:e.newIndicies[r].index},n)}))}return function(e,t){return e.map((function(e){return k(k({},e),{},{item:t[e.oldIndex]})})).sort((function(e,t){return e.oldIndex-t.oldIndex}))}(a,t)}function M(e,t){var r=j(t);return e.concat().reverse().forEach((function(e){return r.splice(e.oldIndex,1)})),r}function T(e,t,r,n){var a=j(t);return e.forEach((function(e){var t=n&&r&&n(e.item,r);a.splice(e.newIndex,0,t||e.item)})),a}function P(e){return e.oldIndicies&&e.oldIndicies.length>0?"multidrag":e.swapItem?"swap":"normal"}function L(e){return(L="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function q(e){return function(e){if(Array.isArray(e))return R(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||function(e,t){if(e){if("string"==typeof e)return R(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?R(e,t):void 0}}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function R(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}function B(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function H(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?B(Object(r),!0).forEach((function(t){Q(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):B(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function I(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function V(e,t){return(V=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function U(e,t){return!t||"object"!==L(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function F(e){return(F=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function Q(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}t.Utils=O;var G={dragging:null},Y=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&V(e,t)}(p,s);var t,r,a=function(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=F(e);if(t){var a=F(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return U(this,r)}}(p);function p(e){var t;!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,p),(t=a.call(this,e)).ref=l();var r=e.list.map((function(e){return H(H({},e),{},{chosen:!1,selected:!1})}));return e.setList(r,t.sortable,G),n(!e.plugins,'\nPlugins prop is no longer supported.\nInstead, mount it with "Sortable.mount(new MultiDrag())"\nPlease read the updated README.md at https://github.com/SortableJS/react-sortablejs.\n      '),t}return t=p,(r=[{key:"componentDidMount",value:function(){if(null!==this.ref.current){var e=this.makeOptions();d.create(this.ref.current,e)}}},{key:"render",value:function(){var e=this.props,t=e.tag,r={style:e.style,className:e.className,id:e.id};return c(t&&null!==t?t:"div",H({ref:this.ref},r),this.getChildren())}},{key:"getChildren",value:function(){var e=this.props,t=e.children,r=e.dataIdAttr,n=e.selectedClass,a=void 0===n?"sortable-selected":n,s=e.chosenClass,c=void 0===s?"sortable-chosen":s,l=(e.dragClass,e.fallbackClass,e.ghostClass,e.swapClass,e.filter),p=void 0===l?"sortable-filter":l,d=e.list;if(!t||null==t)return null;var h=r||"data-id";return o.map(t,(function(e,t){var r,n,o=d[t],s=e.props.className,l="string"==typeof p&&Q({},p.replace(".",""),!!o.filtered),f=u(s,H((Q(r={},a,o.selected),Q(r,c,o.chosen),r),l));return i(e,(Q(n={},h,e.key),Q(n,"className",f),n))}))}},{key:"makeOptions",value:function(){var e,t=this,r=((e=this.props).list,e.setList,e.children,e.tag,e.style,e.className,e.clone,e.onAdd,e.onChange,e.onChoose,e.onClone,e.onEnd,e.onFilter,e.onRemove,e.onSort,e.onStart,e.onUnchoose,e.onUpdate,e.onMove,e.onSpill,e.onSelect,e.onDeselect,function(e,t){if(null==e)return{};var r,n,a=function(e,t){if(null==e)return{};var r,n,a={},o=Object.keys(e);for(n=0;n<o.length;n++)r=o[n],t.indexOf(r)>=0||(a[r]=e[r]);return a}(e,t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);for(n=0;n<o.length;n++)r=o[n],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(a[r]=e[r])}return a}(e,["list","setList","children","tag","style","className","clone","onAdd","onChange","onChoose","onClone","onEnd","onFilter","onRemove","onSort","onStart","onUnchoose","onUpdate","onMove","onSpill","onSelect","onDeselect"]));return["onAdd","onChoose","onDeselect","onEnd","onRemove","onSelect","onSpill","onStart","onUnchoose","onUpdate"].forEach((function(e){return r[e]=t.prepareOnHandlerPropAndDOM(e)})),["onChange","onClone","onFilter","onSort"].forEach((function(e){return r[e]=t.prepareOnHandlerProp(e)})),H(H({},r),{},{onMove:function(e,r){var n=t.props.onMove,a=e.willInsertAfter||-1;if(!n)return a;var o=n(e,r,t.sortable,G);return void 0!==o&&o}})}},{key:"prepareOnHandlerPropAndDOM",value:function(e){var t=this;return function(r){t.callOnHandlerProp(r,e),t[e](r)}}},{key:"prepareOnHandlerProp",value:function(e){var t=this;return function(r){t.callOnHandlerProp(r,e)}}},{key:"callOnHandlerProp",value:function(e,t){var r=this.props[t];r&&r(e,this.sortable,G)}},{key:"onAdd",value:function(e){var t=this.props,r=t.list,n=t.setList,a=t.clone,o=A(e,q(G.dragging.props.list));z(o),n(T(o,r,e,a).map((function(e){return H(H({},e),{},{selected:!1})})),this.sortable,G)}},{key:"onRemove",value:function(e){var t=this,r=this.props,a=r.list,o=r.setList,i=P(e),s=A(e,a);D(s);var c=q(a);if("clone"!==e.pullMode)c=M(s,c);else{var l=s;switch(i){case"multidrag":l=s.map((function(t,r){return H(H({},t),{},{element:e.clones[r]})}));break;case"normal":l=s.map((function(t){return H(H({},t),{},{element:e.clone})}));break;case"swap":default:n(!0,'mode "'.concat(i,'" cannot clone. Please remove "props.clone" from <ReactSortable/> when using the "').concat(i,'" plugin'))}z(l),s.forEach((function(r){var n=r.oldIndex,a=t.props.clone(r.item,e);c.splice(n,1,a)}))}o(c=c.map((function(e){return H(H({},e),{},{selected:!1})})),this.sortable,G)}},{key:"onUpdate",value:function(e){var t=this.props,r=t.list,n=t.setList,a=A(e,r);return z(a),D(a),n(function(e,t){return T(e,M(e,t))}(a,r),this.sortable,G)}},{key:"onStart",value:function(){G.dragging=this}},{key:"onEnd",value:function(){G.dragging=null}},{key:"onChoose",value:function(e){var t=this.props,r=t.list;(0,t.setList)(r.map((function(t,r){return r===e.oldIndex?H(H({},t),{},{chosen:!0}):t})),this.sortable,G)}},{key:"onUnchoose",value:function(e){var t=this.props,r=t.list;(0,t.setList)(r.map((function(t,r){return r===e.oldIndex?H(H({},t),{},{chosen:!1}):t})),this.sortable,G)}},{key:"onSpill",value:function(e){var t=this.props,r=t.removeOnSpill,n=t.revertOnSpill;r&&!n&&N(e.item)}},{key:"onSelect",value:function(e){var t=this.props,r=t.list,n=t.setList,a=r.map((function(e){return H(H({},e),{},{selected:!1})}));e.newIndicies.forEach((function(t){var r=t.index;if(-1===r)return console.log('"'.concat(e.type,'" had indice of "').concat(t.index,"\", which is probably -1 and doesn't usually happen here.")),void console.log(e);a[r].selected=!0})),n(a,this.sortable,G)}},{key:"onDeselect",value:function(e){var t=this.props,r=t.list,n=t.setList,a=r.map((function(e){return H(H({},e),{},{selected:!1})}));e.newIndicies.forEach((function(e){var t=e.index;-1!==t&&(a[t].selected=!0)})),n(a,this.sortable,G)}},{key:"sortable",get:function(){var e=this.ref.current;if(null===e)return null;var t=Object.keys(e).find((function(e){return e.includes("Sortable")}));return t?e[t]:null}}])&&I(t.prototype,r),p}();t.ReactSortable=Y,Q(Y,"defaultProps",{clone:function(e){return e}})},function(e,t){e.exports={Text:"text",Directive:"directive",Comment:"comment",Script:"script",Style:"style",Tag:"tag",CDATA:"cdata",Doctype:"doctype",isTag:function(e){return"tag"===e.type||"script"===e.type||"style"===e.type}}},function(e,t){"function"==typeof Object.create?e.exports=function(e,t){t&&(e.super_=t,e.prototype=Object.create(t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}))}:e.exports=function(e,t){if(t){e.super_=t;var r=function(){};r.prototype=t.prototype,e.prototype=new r,e.prototype.constructor=e}}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e,t){return e.filter((function(e){return!(0,n.default)(e)})).map((function(e,r){var n=void 0;return"function"!=typeof t||null!==(n=t(e,r))&&!n?(0,a.default)(e,r,t):n}))};var n=o(r(48)),a=o(r(20));function o(e){return e&&e.__esModule?e:{default:e}}},function(e,t,r){var n;!function(){"use strict";var r={}.hasOwnProperty;function a(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var o=typeof n;if("string"===o||"number"===o)e.push(n);else if(Array.isArray(n)&&n.length){var i=a.apply(null,n);i&&e.push(i)}else if("object"===o)for(var s in n)r.call(n,s)&&n[s]&&e.push(s)}}return e.join(" ")}e.exports?(a.default=a,e.exports=a):void 0===(n=function(){return a}.apply(t,[]))||(e.exports=n)}()},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e,t,r){return o.default[e.type](e,t,r)};var n,a=r(49),o=(n=a)&&n.__esModule?n:{default:n}},function(e,t,r){var n=r(22),a={input:!0,option:!0,optgroup:!0,select:!0,button:!0,datalist:!0,textarea:!0},o={tr:{tr:!0,th:!0,td:!0},th:{th:!0},td:{thead:!0,th:!0,td:!0},body:{head:!0,link:!0,script:!0},li:{li:!0},p:{p:!0},h1:{p:!0},h2:{p:!0},h3:{p:!0},h4:{p:!0},h5:{p:!0},h6:{p:!0},select:a,input:a,output:a,button:a,datalist:a,textarea:a,option:{option:!0},optgroup:{optgroup:!0}},i={__proto__:null,area:!0,base:!0,basefont:!0,br:!0,col:!0,command:!0,embed:!0,frame:!0,hr:!0,img:!0,input:!0,isindex:!0,keygen:!0,link:!0,meta:!0,param:!0,source:!0,track:!0,wbr:!0},s={__proto__:null,math:!0,svg:!0},c={__proto__:null,mi:!0,mo:!0,mn:!0,ms:!0,mtext:!0,"annotation-xml":!0,foreignObject:!0,desc:!0,title:!0},l=/\s|\//;function u(e,t){this._options=t||{},this._cbs=e||{},this._tagname="",this._attribname="",this._attribvalue="",this._attribs=null,this._stack=[],this._foreignContext=[],this.startIndex=0,this.endIndex=null,this._lowerCaseTagNames="lowerCaseTags"in this._options?!!this._options.lowerCaseTags:!this._options.xmlMode,this._lowerCaseAttributeNames="lowerCaseAttributeNames"in this._options?!!this._options.lowerCaseAttributeNames:!this._options.xmlMode,this._options.Tokenizer&&(n=this._options.Tokenizer),this._tokenizer=new n(this._options,this),this._cbs.onparserinit&&this._cbs.onparserinit(this)}r(17)(u,r(55).EventEmitter),u.prototype._updatePosition=function(e){null===this.endIndex?this._tokenizer._sectionStart<=e?this.startIndex=0:this.startIndex=this._tokenizer._sectionStart-e:this.startIndex=this.endIndex+1,this.endIndex=this._tokenizer.getAbsoluteIndex()},u.prototype.ontext=function(e){this._updatePosition(1),this.endIndex--,this._cbs.ontext&&this._cbs.ontext(e)},u.prototype.onopentagname=function(e){if(this._lowerCaseTagNames&&(e=e.toLowerCase()),this._tagname=e,!this._options.xmlMode&&e in o)for(var t;(t=this._stack[this._stack.length-1])in o[e];this.onclosetag(t));!this._options.xmlMode&&e in i||(this._stack.push(e),e in s?this._foreignContext.push(!0):e in c&&this._foreignContext.push(!1)),this._cbs.onopentagname&&this._cbs.onopentagname(e),this._cbs.onopentag&&(this._attribs={})},u.prototype.onopentagend=function(){this._updatePosition(1),this._attribs&&(this._cbs.onopentag&&this._cbs.onopentag(this._tagname,this._attribs),this._attribs=null),!this._options.xmlMode&&this._cbs.onclosetag&&this._tagname in i&&this._cbs.onclosetag(this._tagname),this._tagname=""},u.prototype.onclosetag=function(e){if(this._updatePosition(1),this._lowerCaseTagNames&&(e=e.toLowerCase()),(e in s||e in c)&&this._foreignContext.pop(),!this._stack.length||e in i&&!this._options.xmlMode)this._options.xmlMode||"br"!==e&&"p"!==e||(this.onopentagname(e),this._closeCurrentTag());else{var t=this._stack.lastIndexOf(e);if(-1!==t)if(this._cbs.onclosetag)for(t=this._stack.length-t;t--;)this._cbs.onclosetag(this._stack.pop());else this._stack.length=t;else"p"!==e||this._options.xmlMode||(this.onopentagname(e),this._closeCurrentTag())}},u.prototype.onselfclosingtag=function(){this._options.xmlMode||this._options.recognizeSelfClosing||this._foreignContext[this._foreignContext.length-1]?this._closeCurrentTag():this.onopentagend()},u.prototype._closeCurrentTag=function(){var e=this._tagname;this.onopentagend(),this._stack[this._stack.length-1]===e&&(this._cbs.onclosetag&&this._cbs.onclosetag(e),this._stack.pop())},u.prototype.onattribname=function(e){this._lowerCaseAttributeNames&&(e=e.toLowerCase()),this._attribname=e},u.prototype.onattribdata=function(e){this._attribvalue+=e},u.prototype.onattribend=function(){this._cbs.onattribute&&this._cbs.onattribute(this._attribname,this._attribvalue),this._attribs&&!Object.prototype.hasOwnProperty.call(this._attribs,this._attribname)&&(this._attribs[this._attribname]=this._attribvalue),this._attribname="",this._attribvalue=""},u.prototype._getInstructionName=function(e){var t=e.search(l),r=t<0?e:e.substr(0,t);return this._lowerCaseTagNames&&(r=r.toLowerCase()),r},u.prototype.ondeclaration=function(e){if(this._cbs.onprocessinginstruction){var t=this._getInstructionName(e);this._cbs.onprocessinginstruction("!"+t,"!"+e)}},u.prototype.onprocessinginstruction=function(e){if(this._cbs.onprocessinginstruction){var t=this._getInstructionName(e);this._cbs.onprocessinginstruction("?"+t,"?"+e)}},u.prototype.oncomment=function(e){this._updatePosition(4),this._cbs.oncomment&&this._cbs.oncomment(e),this._cbs.oncommentend&&this._cbs.oncommentend()},u.prototype.oncdata=function(e){this._updatePosition(1),this._options.xmlMode||this._options.recognizeCDATA?(this._cbs.oncdatastart&&this._cbs.oncdatastart(),this._cbs.ontext&&this._cbs.ontext(e),this._cbs.oncdataend&&this._cbs.oncdataend()):this.oncomment("[CDATA["+e+"]]")},u.prototype.onerror=function(e){this._cbs.onerror&&this._cbs.onerror(e)},u.prototype.onend=function(){if(this._cbs.onclosetag)for(var e=this._stack.length;e>0;this._cbs.onclosetag(this._stack[--e]));this._cbs.onend&&this._cbs.onend()},u.prototype.reset=function(){this._cbs.onreset&&this._cbs.onreset(),this._tokenizer.reset(),this._tagname="",this._attribname="",this._attribs=null,this._stack=[],this._cbs.onparserinit&&this._cbs.onparserinit(this)},u.prototype.parseComplete=function(e){this.reset(),this.end(e)},u.prototype.write=function(e){this._tokenizer.write(e)},u.prototype.end=function(e){this._tokenizer.end(e)},u.prototype.pause=function(){this._tokenizer.pause()},u.prototype.resume=function(){this._tokenizer.resume()},u.prototype.parseChunk=u.prototype.write,u.prototype.done=u.prototype.end,e.exports=u},function(e,t,r){e.exports=ge;var n=r(50),a=r(52),o=r(53),i=r(54),s=0,c=s++,l=s++,u=s++,p=s++,d=s++,h=s++,f=s++,m=s++,g=s++,b=s++,v=s++,y=s++,w=s++,_=s++,O=s++,E=s++,j=s++,x=s++,C=s++,k=s++,S=s++,N=s++,z=s++,D=s++,A=s++,M=s++,T=s++,P=s++,L=s++,q=s++,R=s++,B=s++,H=s++,I=s++,V=s++,U=s++,F=s++,Q=s++,G=s++,Y=s++,X=s++,J=s++,W=s++,K=s++,Z=s++,$=s++,ee=s++,te=s++,re=s++,ne=s++,ae=s++,oe=s++,ie=s++,se=s++,ce=s++,le=0,ue=le++,pe=le++,de=le++;function he(e){return" "===e||"\n"===e||"\t"===e||"\f"===e||"\r"===e}function fe(e,t,r){var n=e.toLowerCase();return e===n?function(e){e===n?this._state=t:(this._state=r,this._index--)}:function(a){a===n||a===e?this._state=t:(this._state=r,this._index--)}}function me(e,t){var r=e.toLowerCase();return function(n){n===r||n===e?this._state=t:(this._state=u,this._index--)}}function ge(e,t){this._state=c,this._buffer="",this._sectionStart=0,this._index=0,this._bufferOffset=0,this._baseState=c,this._special=ue,this._cbs=t,this._running=!0,this._ended=!1,this._xmlMode=!(!e||!e.xmlMode),this._decodeEntities=!(!e||!e.decodeEntities)}ge.prototype._stateText=function(e){"<"===e?(this._index>this._sectionStart&&this._cbs.ontext(this._getSection()),this._state=l,this._sectionStart=this._index):this._decodeEntities&&this._special===ue&&"&"===e&&(this._index>this._sectionStart&&this._cbs.ontext(this._getSection()),this._baseState=c,this._state=ae,this._sectionStart=this._index)},ge.prototype._stateBeforeTagName=function(e){"/"===e?this._state=d:"<"===e?(this._cbs.ontext(this._getSection()),this._sectionStart=this._index):">"===e||this._special!==ue||he(e)?this._state=c:"!"===e?(this._state=O,this._sectionStart=this._index+1):"?"===e?(this._state=j,this._sectionStart=this._index+1):(this._state=this._xmlMode||"s"!==e&&"S"!==e?u:R,this._sectionStart=this._index)},ge.prototype._stateInTagName=function(e){("/"===e||">"===e||he(e))&&(this._emitToken("onopentagname"),this._state=m,this._index--)},ge.prototype._stateBeforeCloseingTagName=function(e){he(e)||(">"===e?this._state=c:this._special!==ue?"s"===e||"S"===e?this._state=B:(this._state=c,this._index--):(this._state=h,this._sectionStart=this._index))},ge.prototype._stateInCloseingTagName=function(e){(">"===e||he(e))&&(this._emitToken("onclosetag"),this._state=f,this._index--)},ge.prototype._stateAfterCloseingTagName=function(e){">"===e&&(this._state=c,this._sectionStart=this._index+1)},ge.prototype._stateBeforeAttributeName=function(e){">"===e?(this._cbs.onopentagend(),this._state=c,this._sectionStart=this._index+1):"/"===e?this._state=p:he(e)||(this._state=g,this._sectionStart=this._index)},ge.prototype._stateInSelfClosingTag=function(e){">"===e?(this._cbs.onselfclosingtag(),this._state=c,this._sectionStart=this._index+1):he(e)||(this._state=m,this._index--)},ge.prototype._stateInAttributeName=function(e){("="===e||"/"===e||">"===e||he(e))&&(this._cbs.onattribname(this._getSection()),this._sectionStart=-1,this._state=b,this._index--)},ge.prototype._stateAfterAttributeName=function(e){"="===e?this._state=v:"/"===e||">"===e?(this._cbs.onattribend(),this._state=m,this._index--):he(e)||(this._cbs.onattribend(),this._state=g,this._sectionStart=this._index)},ge.prototype._stateBeforeAttributeValue=function(e){'"'===e?(this._state=y,this._sectionStart=this._index+1):"'"===e?(this._state=w,this._sectionStart=this._index+1):he(e)||(this._state=_,this._sectionStart=this._index,this._index--)},ge.prototype._stateInAttributeValueDoubleQuotes=function(e){'"'===e?(this._emitToken("onattribdata"),this._cbs.onattribend(),this._state=m):this._decodeEntities&&"&"===e&&(this._emitToken("onattribdata"),this._baseState=this._state,this._state=ae,this._sectionStart=this._index)},ge.prototype._stateInAttributeValueSingleQuotes=function(e){"'"===e?(this._emitToken("onattribdata"),this._cbs.onattribend(),this._state=m):this._decodeEntities&&"&"===e&&(this._emitToken("onattribdata"),this._baseState=this._state,this._state=ae,this._sectionStart=this._index)},ge.prototype._stateInAttributeValueNoQuotes=function(e){he(e)||">"===e?(this._emitToken("onattribdata"),this._cbs.onattribend(),this._state=m,this._index--):this._decodeEntities&&"&"===e&&(this._emitToken("onattribdata"),this._baseState=this._state,this._state=ae,this._sectionStart=this._index)},ge.prototype._stateBeforeDeclaration=function(e){this._state="["===e?N:"-"===e?x:E},ge.prototype._stateInDeclaration=function(e){">"===e&&(this._cbs.ondeclaration(this._getSection()),this._state=c,this._sectionStart=this._index+1)},ge.prototype._stateInProcessingInstruction=function(e){">"===e&&(this._cbs.onprocessinginstruction(this._getSection()),this._state=c,this._sectionStart=this._index+1)},ge.prototype._stateBeforeComment=function(e){"-"===e?(this._state=C,this._sectionStart=this._index+1):this._state=E},ge.prototype._stateInComment=function(e){"-"===e&&(this._state=k)},ge.prototype._stateAfterComment1=function(e){this._state="-"===e?S:C},ge.prototype._stateAfterComment2=function(e){">"===e?(this._cbs.oncomment(this._buffer.substring(this._sectionStart,this._index-2)),this._state=c,this._sectionStart=this._index+1):"-"!==e&&(this._state=C)},ge.prototype._stateBeforeCdata1=fe("C",z,E),ge.prototype._stateBeforeCdata2=fe("D",D,E),ge.prototype._stateBeforeCdata3=fe("A",A,E),ge.prototype._stateBeforeCdata4=fe("T",M,E),ge.prototype._stateBeforeCdata5=fe("A",T,E),ge.prototype._stateBeforeCdata6=function(e){"["===e?(this._state=P,this._sectionStart=this._index+1):(this._state=E,this._index--)},ge.prototype._stateInCdata=function(e){"]"===e&&(this._state=L)},ge.prototype._stateAfterCdata1=function(e){this._state="]"===e?q:P},ge.prototype._stateAfterCdata2=function(e){">"===e?(this._cbs.oncdata(this._buffer.substring(this._sectionStart,this._index-2)),this._state=c,this._sectionStart=this._index+1):"]"!==e&&(this._state=P)},ge.prototype._stateBeforeSpecial=function(e){"c"===e||"C"===e?this._state=H:"t"===e||"T"===e?this._state=W:(this._state=u,this._index--)},ge.prototype._stateBeforeSpecialEnd=function(e){this._special!==pe||"c"!==e&&"C"!==e?this._special!==de||"t"!==e&&"T"!==e?this._state=c:this._state=ee:this._state=Q},ge.prototype._stateBeforeScript1=me("R",I),ge.prototype._stateBeforeScript2=me("I",V),ge.prototype._stateBeforeScript3=me("P",U),ge.prototype._stateBeforeScript4=me("T",F),ge.prototype._stateBeforeScript5=function(e){("/"===e||">"===e||he(e))&&(this._special=pe),this._state=u,this._index--},ge.prototype._stateAfterScript1=fe("R",G,c),ge.prototype._stateAfterScript2=fe("I",Y,c),ge.prototype._stateAfterScript3=fe("P",X,c),ge.prototype._stateAfterScript4=fe("T",J,c),ge.prototype._stateAfterScript5=function(e){">"===e||he(e)?(this._special=ue,this._state=h,this._sectionStart=this._index-6,this._index--):this._state=c},ge.prototype._stateBeforeStyle1=me("Y",K),ge.prototype._stateBeforeStyle2=me("L",Z),ge.prototype._stateBeforeStyle3=me("E",$),ge.prototype._stateBeforeStyle4=function(e){("/"===e||">"===e||he(e))&&(this._special=de),this._state=u,this._index--},ge.prototype._stateAfterStyle1=fe("Y",te,c),ge.prototype._stateAfterStyle2=fe("L",re,c),ge.prototype._stateAfterStyle3=fe("E",ne,c),ge.prototype._stateAfterStyle4=function(e){">"===e||he(e)?(this._special=ue,this._state=h,this._sectionStart=this._index-5,this._index--):this._state=c},ge.prototype._stateBeforeEntity=fe("#",oe,ie),ge.prototype._stateBeforeNumericEntity=fe("X",ce,se),ge.prototype._parseNamedEntityStrict=function(){if(this._sectionStart+1<this._index){var e=this._buffer.substring(this._sectionStart+1,this._index),t=this._xmlMode?i:a;t.hasOwnProperty(e)&&(this._emitPartial(t[e]),this._sectionStart=this._index+1)}},ge.prototype._parseLegacyEntity=function(){var e=this._sectionStart+1,t=this._index-e;for(t>6&&(t=6);t>=2;){var r=this._buffer.substr(e,t);if(o.hasOwnProperty(r))return this._emitPartial(o[r]),void(this._sectionStart+=t+1);t--}},ge.prototype._stateInNamedEntity=function(e){";"===e?(this._parseNamedEntityStrict(),this._sectionStart+1<this._index&&!this._xmlMode&&this._parseLegacyEntity(),this._state=this._baseState):(e<"a"||e>"z")&&(e<"A"||e>"Z")&&(e<"0"||e>"9")&&(this._xmlMode||this._sectionStart+1===this._index||(this._baseState!==c?"="!==e&&this._parseNamedEntityStrict():this._parseLegacyEntity()),this._state=this._baseState,this._index--)},ge.prototype._decodeNumericEntity=function(e,t){var r=this._sectionStart+e;if(r!==this._index){var a=this._buffer.substring(r,this._index),o=parseInt(a,t);this._emitPartial(n(o)),this._sectionStart=this._index}else this._sectionStart--;this._state=this._baseState},ge.prototype._stateInNumericEntity=function(e){";"===e?(this._decodeNumericEntity(2,10),this._sectionStart++):(e<"0"||e>"9")&&(this._xmlMode?this._state=this._baseState:this._decodeNumericEntity(2,10),this._index--)},ge.prototype._stateInHexEntity=function(e){";"===e?(this._decodeNumericEntity(3,16),this._sectionStart++):(e<"a"||e>"f")&&(e<"A"||e>"F")&&(e<"0"||e>"9")&&(this._xmlMode?this._state=this._baseState:this._decodeNumericEntity(3,16),this._index--)},ge.prototype._cleanup=function(){this._sectionStart<0?(this._buffer="",this._bufferOffset+=this._index,this._index=0):this._running&&(this._state===c?(this._sectionStart!==this._index&&this._cbs.ontext(this._buffer.substr(this._sectionStart)),this._buffer="",this._bufferOffset+=this._index,this._index=0):this._sectionStart===this._index?(this._buffer="",this._bufferOffset+=this._index,this._index=0):(this._buffer=this._buffer.substr(this._sectionStart),this._index-=this._sectionStart,this._bufferOffset+=this._sectionStart),this._sectionStart=0)},ge.prototype.write=function(e){this._ended&&this._cbs.onerror(Error(".write() after done!")),this._buffer+=e,this._parse()},ge.prototype._parse=function(){for(;this._index<this._buffer.length&&this._running;){var e=this._buffer.charAt(this._index);this._state===c?this._stateText(e):this._state===l?this._stateBeforeTagName(e):this._state===u?this._stateInTagName(e):this._state===d?this._stateBeforeCloseingTagName(e):this._state===h?this._stateInCloseingTagName(e):this._state===f?this._stateAfterCloseingTagName(e):this._state===p?this._stateInSelfClosingTag(e):this._state===m?this._stateBeforeAttributeName(e):this._state===g?this._stateInAttributeName(e):this._state===b?this._stateAfterAttributeName(e):this._state===v?this._stateBeforeAttributeValue(e):this._state===y?this._stateInAttributeValueDoubleQuotes(e):this._state===w?this._stateInAttributeValueSingleQuotes(e):this._state===_?this._stateInAttributeValueNoQuotes(e):this._state===O?this._stateBeforeDeclaration(e):this._state===E?this._stateInDeclaration(e):this._state===j?this._stateInProcessingInstruction(e):this._state===x?this._stateBeforeComment(e):this._state===C?this._stateInComment(e):this._state===k?this._stateAfterComment1(e):this._state===S?this._stateAfterComment2(e):this._state===N?this._stateBeforeCdata1(e):this._state===z?this._stateBeforeCdata2(e):this._state===D?this._stateBeforeCdata3(e):this._state===A?this._stateBeforeCdata4(e):this._state===M?this._stateBeforeCdata5(e):this._state===T?this._stateBeforeCdata6(e):this._state===P?this._stateInCdata(e):this._state===L?this._stateAfterCdata1(e):this._state===q?this._stateAfterCdata2(e):this._state===R?this._stateBeforeSpecial(e):this._state===B?this._stateBeforeSpecialEnd(e):this._state===H?this._stateBeforeScript1(e):this._state===I?this._stateBeforeScript2(e):this._state===V?this._stateBeforeScript3(e):this._state===U?this._stateBeforeScript4(e):this._state===F?this._stateBeforeScript5(e):this._state===Q?this._stateAfterScript1(e):this._state===G?this._stateAfterScript2(e):this._state===Y?this._stateAfterScript3(e):this._state===X?this._stateAfterScript4(e):this._state===J?this._stateAfterScript5(e):this._state===W?this._stateBeforeStyle1(e):this._state===K?this._stateBeforeStyle2(e):this._state===Z?this._stateBeforeStyle3(e):this._state===$?this._stateBeforeStyle4(e):this._state===ee?this._stateAfterStyle1(e):this._state===te?this._stateAfterStyle2(e):this._state===re?this._stateAfterStyle3(e):this._state===ne?this._stateAfterStyle4(e):this._state===ae?this._stateBeforeEntity(e):this._state===oe?this._stateBeforeNumericEntity(e):this._state===ie?this._stateInNamedEntity(e):this._state===se?this._stateInNumericEntity(e):this._state===ce?this._stateInHexEntity(e):this._cbs.onerror(Error("unknown _state"),this._state),this._index++}this._cleanup()},ge.prototype.pause=function(){this._running=!1},ge.prototype.resume=function(){this._running=!0,this._index<this._buffer.length&&this._parse(),this._ended&&this._finish()},ge.prototype.end=function(e){this._ended&&this._cbs.onerror(Error(".end() after done!")),e&&this.write(e),this._ended=!0,this._running&&this._finish()},ge.prototype._finish=function(){this._sectionStart<this._index&&this._handleTrailingData(),this._cbs.onend()},ge.prototype._handleTrailingData=function(){var e=this._buffer.substr(this._sectionStart);this._state===P||this._state===L||this._state===q?this._cbs.oncdata(e):this._state===C||this._state===k||this._state===S?this._cbs.oncomment(e):this._state!==ie||this._xmlMode?this._state!==se||this._xmlMode?this._state!==ce||this._xmlMode?this._state!==u&&this._state!==m&&this._state!==v&&this._state!==b&&this._state!==g&&this._state!==w&&this._state!==y&&this._state!==_&&this._state!==h&&this._cbs.ontext(e):(this._decodeNumericEntity(3,16),this._sectionStart<this._index&&(this._state=this._baseState,this._handleTrailingData())):(this._decodeNumericEntity(2,10),this._sectionStart<this._index&&(this._state=this._baseState,this._handleTrailingData())):(this._parseLegacyEntity(),this._sectionStart<this._index&&(this._state=this._baseState,this._handleTrailingData()))},ge.prototype.reset=function(){ge.call(this,{xmlMode:this._xmlMode,decodeEntities:this._decodeEntities},this._cbs)},ge.prototype.getAbsoluteIndex=function(){return this._bufferOffset+this._index},ge.prototype._getSection=function(){return this._buffer.substring(this._sectionStart,this._index)},ge.prototype._emitToken=function(e){this._cbs[e](this._getSection()),this._sectionStart=-1},ge.prototype._emitPartial=function(e){this._baseState!==c?this._cbs.onattribdata(e):this._cbs.ontext(e)}},function(e,t,r){var n=r(16),a=/\s+/g,o=r(24),i=r(56);function s(e,t,r){"object"==typeof e?(r=t,t=e,e=null):"function"==typeof t&&(r=t,t=c),this._callback=e,this._options=t||c,this._elementCB=r,this.dom=[],this._done=!1,this._tagStack=[],this._parser=this._parser||null}var c={normalizeWhitespace:!1,withStartIndices:!1,withEndIndices:!1};s.prototype.onparserinit=function(e){this._parser=e},s.prototype.onreset=function(){s.call(this,this._callback,this._options,this._elementCB)},s.prototype.onend=function(){this._done||(this._done=!0,this._parser=null,this._handleCallback(null))},s.prototype._handleCallback=s.prototype.onerror=function(e){if("function"==typeof this._callback)this._callback(e,this.dom);else if(e)throw e},s.prototype.onclosetag=function(){var e=this._tagStack.pop();this._options.withEndIndices&&e&&(e.endIndex=this._parser.endIndex),this._elementCB&&this._elementCB(e)},s.prototype._createDomElement=function(e){if(!this._options.withDomLvl1)return e;var t;for(var r in t="tag"===e.type?Object.create(i):Object.create(o),e)e.hasOwnProperty(r)&&(t[r]=e[r]);return t},s.prototype._addDomElement=function(e){var t=this._tagStack[this._tagStack.length-1],r=t?t.children:this.dom,n=r[r.length-1];e.next=null,this._options.withStartIndices&&(e.startIndex=this._parser.startIndex),this._options.withEndIndices&&(e.endIndex=this._parser.endIndex),n?(e.prev=n,n.next=e):e.prev=null,r.push(e),e.parent=t||null},s.prototype.onopentag=function(e,t){var r={type:"script"===e?n.Script:"style"===e?n.Style:n.Tag,name:e,attribs:t,children:[]},a=this._createDomElement(r);this._addDomElement(a),this._tagStack.push(a)},s.prototype.ontext=function(e){var t,r=this._options.normalizeWhitespace||this._options.ignoreWhitespace;if(!this._tagStack.length&&this.dom.length&&(t=this.dom[this.dom.length-1]).type===n.Text)r?t.data=(t.data+e).replace(a," "):t.data+=e;else if(this._tagStack.length&&(t=this._tagStack[this._tagStack.length-1])&&(t=t.children[t.children.length-1])&&t.type===n.Text)r?t.data=(t.data+e).replace(a," "):t.data+=e;else{r&&(e=e.replace(a," "));var o=this._createDomElement({data:e,type:n.Text});this._addDomElement(o)}},s.prototype.oncomment=function(e){var t=this._tagStack[this._tagStack.length-1];if(t&&t.type===n.Comment)t.data+=e;else{var r={data:e,type:n.Comment},a=this._createDomElement(r);this._addDomElement(a),this._tagStack.push(a)}},s.prototype.oncdatastart=function(){var e={children:[{data:"",type:n.Text}],type:n.CDATA},t=this._createDomElement(e);this._addDomElement(t),this._tagStack.push(t)},s.prototype.oncommentend=s.prototype.oncdataend=function(){this._tagStack.pop()},s.prototype.onprocessinginstruction=function(e,t){var r=this._createDomElement({name:e,data:t,type:n.Directive});this._addDomElement(r)},e.exports=s},function(e,t){var r=e.exports={get firstChild(){var e=this.children;return e&&e[0]||null},get lastChild(){var e=this.children;return e&&e[e.length-1]||null},get nodeType(){return a[this.type]||a.element}},n={tagName:"name",childNodes:"children",parentNode:"parent",previousSibling:"prev",nextSibling:"next",nodeValue:"data"},a={element:1,text:3,cdata:4,comment:8};Object.keys(n).forEach((function(e){var t=n[e];Object.defineProperty(r,e,{get:function(){return this[t]||null},set:function(e){return this[t]=e,e}})}))},function(e,t,r){var n=e.exports;[r(58),r(66),r(67),r(68),r(69),r(70)].forEach((function(e){Object.keys(e).forEach((function(t){n[t]=e[t].bind(n)}))}))},function(e,t,r){"use strict";var n=this&&this.__importDefault||function(e){return e&&e.__esModule?e:{default:e}};Object.defineProperty(t,"__esModule",{value:!0}),t.decodeHTML=t.decodeHTMLStrict=t.decodeXML=void 0;var a=n(r(27)),o=n(r(62)),i=n(r(28)),s=n(r(63));function c(e){var t=Object.keys(e).join("|"),r=u(e),n=new RegExp("&(?:"+(t+="|#[xX][\\da-fA-F]+|#\\d+")+");","g");return function(e){return String(e).replace(n,r)}}t.decodeXML=c(i.default),t.decodeHTMLStrict=c(a.default);var l=function(e,t){return e<t?1:-1};function u(e){return function(t){if("#"===t.charAt(1)){var r=t.charAt(2);return"X"===r||"x"===r?s.default(parseInt(t.substr(3),16)):s.default(parseInt(t.substr(2),10))}return e[t.slice(1,-1)]}}t.decodeHTML=function(){for(var e=Object.keys(o.default).sort(l),t=Object.keys(a.default).sort(l),r=0,n=0;r<t.length;r++)e[n]===t[r]?(t[r]+=";?",n++):t[r]+=";";var i=new RegExp("&(?:"+t.join("|")+"|#[xX][\\da-fA-F]+;?|#\\d+;?)","g"),s=u(a.default);function c(e){return";"!==e.substr(-1)&&(e+=";"),s(e)}return function(e){return String(e).replace(i,c)}}()},function(e){e.exports=JSON.parse('{"Aacute":"Á","aacute":"á","Abreve":"Ă","abreve":"ă","ac":"∾","acd":"∿","acE":"∾̳","Acirc":"Â","acirc":"â","acute":"´","Acy":"А","acy":"а","AElig":"Æ","aelig":"æ","af":"⁡","Afr":"𝔄","afr":"𝔞","Agrave":"À","agrave":"à","alefsym":"ℵ","aleph":"ℵ","Alpha":"Α","alpha":"α","Amacr":"Ā","amacr":"ā","amalg":"⨿","amp":"&","AMP":"&","andand":"⩕","And":"⩓","and":"∧","andd":"⩜","andslope":"⩘","andv":"⩚","ang":"∠","ange":"⦤","angle":"∠","angmsdaa":"⦨","angmsdab":"⦩","angmsdac":"⦪","angmsdad":"⦫","angmsdae":"⦬","angmsdaf":"⦭","angmsdag":"⦮","angmsdah":"⦯","angmsd":"∡","angrt":"∟","angrtvb":"⊾","angrtvbd":"⦝","angsph":"∢","angst":"Å","angzarr":"⍼","Aogon":"Ą","aogon":"ą","Aopf":"𝔸","aopf":"𝕒","apacir":"⩯","ap":"≈","apE":"⩰","ape":"≊","apid":"≋","apos":"\'","ApplyFunction":"⁡","approx":"≈","approxeq":"≊","Aring":"Å","aring":"å","Ascr":"𝒜","ascr":"𝒶","Assign":"≔","ast":"*","asymp":"≈","asympeq":"≍","Atilde":"Ã","atilde":"ã","Auml":"Ä","auml":"ä","awconint":"∳","awint":"⨑","backcong":"≌","backepsilon":"϶","backprime":"‵","backsim":"∽","backsimeq":"⋍","Backslash":"∖","Barv":"⫧","barvee":"⊽","barwed":"⌅","Barwed":"⌆","barwedge":"⌅","bbrk":"⎵","bbrktbrk":"⎶","bcong":"≌","Bcy":"Б","bcy":"б","bdquo":"„","becaus":"∵","because":"∵","Because":"∵","bemptyv":"⦰","bepsi":"϶","bernou":"ℬ","Bernoullis":"ℬ","Beta":"Β","beta":"β","beth":"ℶ","between":"≬","Bfr":"𝔅","bfr":"𝔟","bigcap":"⋂","bigcirc":"◯","bigcup":"⋃","bigodot":"⨀","bigoplus":"⨁","bigotimes":"⨂","bigsqcup":"⨆","bigstar":"★","bigtriangledown":"▽","bigtriangleup":"△","biguplus":"⨄","bigvee":"⋁","bigwedge":"⋀","bkarow":"⤍","blacklozenge":"⧫","blacksquare":"▪","blacktriangle":"▴","blacktriangledown":"▾","blacktriangleleft":"◂","blacktriangleright":"▸","blank":"␣","blk12":"▒","blk14":"░","blk34":"▓","block":"█","bne":"=⃥","bnequiv":"≡⃥","bNot":"⫭","bnot":"⌐","Bopf":"𝔹","bopf":"𝕓","bot":"⊥","bottom":"⊥","bowtie":"⋈","boxbox":"⧉","boxdl":"┐","boxdL":"╕","boxDl":"╖","boxDL":"╗","boxdr":"┌","boxdR":"╒","boxDr":"╓","boxDR":"╔","boxh":"─","boxH":"═","boxhd":"┬","boxHd":"╤","boxhD":"╥","boxHD":"╦","boxhu":"┴","boxHu":"╧","boxhU":"╨","boxHU":"╩","boxminus":"⊟","boxplus":"⊞","boxtimes":"⊠","boxul":"┘","boxuL":"╛","boxUl":"╜","boxUL":"╝","boxur":"└","boxuR":"╘","boxUr":"╙","boxUR":"╚","boxv":"│","boxV":"║","boxvh":"┼","boxvH":"╪","boxVh":"╫","boxVH":"╬","boxvl":"┤","boxvL":"╡","boxVl":"╢","boxVL":"╣","boxvr":"├","boxvR":"╞","boxVr":"╟","boxVR":"╠","bprime":"‵","breve":"˘","Breve":"˘","brvbar":"¦","bscr":"𝒷","Bscr":"ℬ","bsemi":"⁏","bsim":"∽","bsime":"⋍","bsolb":"⧅","bsol":"\\\\","bsolhsub":"⟈","bull":"•","bullet":"•","bump":"≎","bumpE":"⪮","bumpe":"≏","Bumpeq":"≎","bumpeq":"≏","Cacute":"Ć","cacute":"ć","capand":"⩄","capbrcup":"⩉","capcap":"⩋","cap":"∩","Cap":"⋒","capcup":"⩇","capdot":"⩀","CapitalDifferentialD":"ⅅ","caps":"∩︀","caret":"⁁","caron":"ˇ","Cayleys":"ℭ","ccaps":"⩍","Ccaron":"Č","ccaron":"č","Ccedil":"Ç","ccedil":"ç","Ccirc":"Ĉ","ccirc":"ĉ","Cconint":"∰","ccups":"⩌","ccupssm":"⩐","Cdot":"Ċ","cdot":"ċ","cedil":"¸","Cedilla":"¸","cemptyv":"⦲","cent":"¢","centerdot":"·","CenterDot":"·","cfr":"𝔠","Cfr":"ℭ","CHcy":"Ч","chcy":"ч","check":"✓","checkmark":"✓","Chi":"Χ","chi":"χ","circ":"ˆ","circeq":"≗","circlearrowleft":"↺","circlearrowright":"↻","circledast":"⊛","circledcirc":"⊚","circleddash":"⊝","CircleDot":"⊙","circledR":"®","circledS":"Ⓢ","CircleMinus":"⊖","CirclePlus":"⊕","CircleTimes":"⊗","cir":"○","cirE":"⧃","cire":"≗","cirfnint":"⨐","cirmid":"⫯","cirscir":"⧂","ClockwiseContourIntegral":"∲","CloseCurlyDoubleQuote":"”","CloseCurlyQuote":"’","clubs":"♣","clubsuit":"♣","colon":":","Colon":"∷","Colone":"⩴","colone":"≔","coloneq":"≔","comma":",","commat":"@","comp":"∁","compfn":"∘","complement":"∁","complexes":"ℂ","cong":"≅","congdot":"⩭","Congruent":"≡","conint":"∮","Conint":"∯","ContourIntegral":"∮","copf":"𝕔","Copf":"ℂ","coprod":"∐","Coproduct":"∐","copy":"©","COPY":"©","copysr":"℗","CounterClockwiseContourIntegral":"∳","crarr":"↵","cross":"✗","Cross":"⨯","Cscr":"𝒞","cscr":"𝒸","csub":"⫏","csube":"⫑","csup":"⫐","csupe":"⫒","ctdot":"⋯","cudarrl":"⤸","cudarrr":"⤵","cuepr":"⋞","cuesc":"⋟","cularr":"↶","cularrp":"⤽","cupbrcap":"⩈","cupcap":"⩆","CupCap":"≍","cup":"∪","Cup":"⋓","cupcup":"⩊","cupdot":"⊍","cupor":"⩅","cups":"∪︀","curarr":"↷","curarrm":"⤼","curlyeqprec":"⋞","curlyeqsucc":"⋟","curlyvee":"⋎","curlywedge":"⋏","curren":"¤","curvearrowleft":"↶","curvearrowright":"↷","cuvee":"⋎","cuwed":"⋏","cwconint":"∲","cwint":"∱","cylcty":"⌭","dagger":"†","Dagger":"‡","daleth":"ℸ","darr":"↓","Darr":"↡","dArr":"⇓","dash":"‐","Dashv":"⫤","dashv":"⊣","dbkarow":"⤏","dblac":"˝","Dcaron":"Ď","dcaron":"ď","Dcy":"Д","dcy":"д","ddagger":"‡","ddarr":"⇊","DD":"ⅅ","dd":"ⅆ","DDotrahd":"⤑","ddotseq":"⩷","deg":"°","Del":"∇","Delta":"Δ","delta":"δ","demptyv":"⦱","dfisht":"⥿","Dfr":"𝔇","dfr":"𝔡","dHar":"⥥","dharl":"⇃","dharr":"⇂","DiacriticalAcute":"´","DiacriticalDot":"˙","DiacriticalDoubleAcute":"˝","DiacriticalGrave":"`","DiacriticalTilde":"˜","diam":"⋄","diamond":"⋄","Diamond":"⋄","diamondsuit":"♦","diams":"♦","die":"¨","DifferentialD":"ⅆ","digamma":"ϝ","disin":"⋲","div":"÷","divide":"÷","divideontimes":"⋇","divonx":"⋇","DJcy":"Ђ","djcy":"ђ","dlcorn":"⌞","dlcrop":"⌍","dollar":"$","Dopf":"𝔻","dopf":"𝕕","Dot":"¨","dot":"˙","DotDot":"⃜","doteq":"≐","doteqdot":"≑","DotEqual":"≐","dotminus":"∸","dotplus":"∔","dotsquare":"⊡","doublebarwedge":"⌆","DoubleContourIntegral":"∯","DoubleDot":"¨","DoubleDownArrow":"⇓","DoubleLeftArrow":"⇐","DoubleLeftRightArrow":"⇔","DoubleLeftTee":"⫤","DoubleLongLeftArrow":"⟸","DoubleLongLeftRightArrow":"⟺","DoubleLongRightArrow":"⟹","DoubleRightArrow":"⇒","DoubleRightTee":"⊨","DoubleUpArrow":"⇑","DoubleUpDownArrow":"⇕","DoubleVerticalBar":"∥","DownArrowBar":"⤓","downarrow":"↓","DownArrow":"↓","Downarrow":"⇓","DownArrowUpArrow":"⇵","DownBreve":"̑","downdownarrows":"⇊","downharpoonleft":"⇃","downharpoonright":"⇂","DownLeftRightVector":"⥐","DownLeftTeeVector":"⥞","DownLeftVectorBar":"⥖","DownLeftVector":"↽","DownRightTeeVector":"⥟","DownRightVectorBar":"⥗","DownRightVector":"⇁","DownTeeArrow":"↧","DownTee":"⊤","drbkarow":"⤐","drcorn":"⌟","drcrop":"⌌","Dscr":"𝒟","dscr":"𝒹","DScy":"Ѕ","dscy":"ѕ","dsol":"⧶","Dstrok":"Đ","dstrok":"đ","dtdot":"⋱","dtri":"▿","dtrif":"▾","duarr":"⇵","duhar":"⥯","dwangle":"⦦","DZcy":"Џ","dzcy":"џ","dzigrarr":"⟿","Eacute":"É","eacute":"é","easter":"⩮","Ecaron":"Ě","ecaron":"ě","Ecirc":"Ê","ecirc":"ê","ecir":"≖","ecolon":"≕","Ecy":"Э","ecy":"э","eDDot":"⩷","Edot":"Ė","edot":"ė","eDot":"≑","ee":"ⅇ","efDot":"≒","Efr":"𝔈","efr":"𝔢","eg":"⪚","Egrave":"È","egrave":"è","egs":"⪖","egsdot":"⪘","el":"⪙","Element":"∈","elinters":"⏧","ell":"ℓ","els":"⪕","elsdot":"⪗","Emacr":"Ē","emacr":"ē","empty":"∅","emptyset":"∅","EmptySmallSquare":"◻","emptyv":"∅","EmptyVerySmallSquare":"▫","emsp13":" ","emsp14":" ","emsp":" ","ENG":"Ŋ","eng":"ŋ","ensp":" ","Eogon":"Ę","eogon":"ę","Eopf":"𝔼","eopf":"𝕖","epar":"⋕","eparsl":"⧣","eplus":"⩱","epsi":"ε","Epsilon":"Ε","epsilon":"ε","epsiv":"ϵ","eqcirc":"≖","eqcolon":"≕","eqsim":"≂","eqslantgtr":"⪖","eqslantless":"⪕","Equal":"⩵","equals":"=","EqualTilde":"≂","equest":"≟","Equilibrium":"⇌","equiv":"≡","equivDD":"⩸","eqvparsl":"⧥","erarr":"⥱","erDot":"≓","escr":"ℯ","Escr":"ℰ","esdot":"≐","Esim":"⩳","esim":"≂","Eta":"Η","eta":"η","ETH":"Ð","eth":"ð","Euml":"Ë","euml":"ë","euro":"€","excl":"!","exist":"∃","Exists":"∃","expectation":"ℰ","exponentiale":"ⅇ","ExponentialE":"ⅇ","fallingdotseq":"≒","Fcy":"Ф","fcy":"ф","female":"♀","ffilig":"ﬃ","fflig":"ﬀ","ffllig":"ﬄ","Ffr":"𝔉","ffr":"𝔣","filig":"ﬁ","FilledSmallSquare":"◼","FilledVerySmallSquare":"▪","fjlig":"fj","flat":"♭","fllig":"ﬂ","fltns":"▱","fnof":"ƒ","Fopf":"𝔽","fopf":"𝕗","forall":"∀","ForAll":"∀","fork":"⋔","forkv":"⫙","Fouriertrf":"ℱ","fpartint":"⨍","frac12":"½","frac13":"⅓","frac14":"¼","frac15":"⅕","frac16":"⅙","frac18":"⅛","frac23":"⅔","frac25":"⅖","frac34":"¾","frac35":"⅗","frac38":"⅜","frac45":"⅘","frac56":"⅚","frac58":"⅝","frac78":"⅞","frasl":"⁄","frown":"⌢","fscr":"𝒻","Fscr":"ℱ","gacute":"ǵ","Gamma":"Γ","gamma":"γ","Gammad":"Ϝ","gammad":"ϝ","gap":"⪆","Gbreve":"Ğ","gbreve":"ğ","Gcedil":"Ģ","Gcirc":"Ĝ","gcirc":"ĝ","Gcy":"Г","gcy":"г","Gdot":"Ġ","gdot":"ġ","ge":"≥","gE":"≧","gEl":"⪌","gel":"⋛","geq":"≥","geqq":"≧","geqslant":"⩾","gescc":"⪩","ges":"⩾","gesdot":"⪀","gesdoto":"⪂","gesdotol":"⪄","gesl":"⋛︀","gesles":"⪔","Gfr":"𝔊","gfr":"𝔤","gg":"≫","Gg":"⋙","ggg":"⋙","gimel":"ℷ","GJcy":"Ѓ","gjcy":"ѓ","gla":"⪥","gl":"≷","glE":"⪒","glj":"⪤","gnap":"⪊","gnapprox":"⪊","gne":"⪈","gnE":"≩","gneq":"⪈","gneqq":"≩","gnsim":"⋧","Gopf":"𝔾","gopf":"𝕘","grave":"`","GreaterEqual":"≥","GreaterEqualLess":"⋛","GreaterFullEqual":"≧","GreaterGreater":"⪢","GreaterLess":"≷","GreaterSlantEqual":"⩾","GreaterTilde":"≳","Gscr":"𝒢","gscr":"ℊ","gsim":"≳","gsime":"⪎","gsiml":"⪐","gtcc":"⪧","gtcir":"⩺","gt":">","GT":">","Gt":"≫","gtdot":"⋗","gtlPar":"⦕","gtquest":"⩼","gtrapprox":"⪆","gtrarr":"⥸","gtrdot":"⋗","gtreqless":"⋛","gtreqqless":"⪌","gtrless":"≷","gtrsim":"≳","gvertneqq":"≩︀","gvnE":"≩︀","Hacek":"ˇ","hairsp":" ","half":"½","hamilt":"ℋ","HARDcy":"Ъ","hardcy":"ъ","harrcir":"⥈","harr":"↔","hArr":"⇔","harrw":"↭","Hat":"^","hbar":"ℏ","Hcirc":"Ĥ","hcirc":"ĥ","hearts":"♥","heartsuit":"♥","hellip":"…","hercon":"⊹","hfr":"𝔥","Hfr":"ℌ","HilbertSpace":"ℋ","hksearow":"⤥","hkswarow":"⤦","hoarr":"⇿","homtht":"∻","hookleftarrow":"↩","hookrightarrow":"↪","hopf":"𝕙","Hopf":"ℍ","horbar":"―","HorizontalLine":"─","hscr":"𝒽","Hscr":"ℋ","hslash":"ℏ","Hstrok":"Ħ","hstrok":"ħ","HumpDownHump":"≎","HumpEqual":"≏","hybull":"⁃","hyphen":"‐","Iacute":"Í","iacute":"í","ic":"⁣","Icirc":"Î","icirc":"î","Icy":"И","icy":"и","Idot":"İ","IEcy":"Е","iecy":"е","iexcl":"¡","iff":"⇔","ifr":"𝔦","Ifr":"ℑ","Igrave":"Ì","igrave":"ì","ii":"ⅈ","iiiint":"⨌","iiint":"∭","iinfin":"⧜","iiota":"℩","IJlig":"Ĳ","ijlig":"ĳ","Imacr":"Ī","imacr":"ī","image":"ℑ","ImaginaryI":"ⅈ","imagline":"ℐ","imagpart":"ℑ","imath":"ı","Im":"ℑ","imof":"⊷","imped":"Ƶ","Implies":"⇒","incare":"℅","in":"∈","infin":"∞","infintie":"⧝","inodot":"ı","intcal":"⊺","int":"∫","Int":"∬","integers":"ℤ","Integral":"∫","intercal":"⊺","Intersection":"⋂","intlarhk":"⨗","intprod":"⨼","InvisibleComma":"⁣","InvisibleTimes":"⁢","IOcy":"Ё","iocy":"ё","Iogon":"Į","iogon":"į","Iopf":"𝕀","iopf":"𝕚","Iota":"Ι","iota":"ι","iprod":"⨼","iquest":"¿","iscr":"𝒾","Iscr":"ℐ","isin":"∈","isindot":"⋵","isinE":"⋹","isins":"⋴","isinsv":"⋳","isinv":"∈","it":"⁢","Itilde":"Ĩ","itilde":"ĩ","Iukcy":"І","iukcy":"і","Iuml":"Ï","iuml":"ï","Jcirc":"Ĵ","jcirc":"ĵ","Jcy":"Й","jcy":"й","Jfr":"𝔍","jfr":"𝔧","jmath":"ȷ","Jopf":"𝕁","jopf":"𝕛","Jscr":"𝒥","jscr":"𝒿","Jsercy":"Ј","jsercy":"ј","Jukcy":"Є","jukcy":"є","Kappa":"Κ","kappa":"κ","kappav":"ϰ","Kcedil":"Ķ","kcedil":"ķ","Kcy":"К","kcy":"к","Kfr":"𝔎","kfr":"𝔨","kgreen":"ĸ","KHcy":"Х","khcy":"х","KJcy":"Ќ","kjcy":"ќ","Kopf":"𝕂","kopf":"𝕜","Kscr":"𝒦","kscr":"𝓀","lAarr":"⇚","Lacute":"Ĺ","lacute":"ĺ","laemptyv":"⦴","lagran":"ℒ","Lambda":"Λ","lambda":"λ","lang":"⟨","Lang":"⟪","langd":"⦑","langle":"⟨","lap":"⪅","Laplacetrf":"ℒ","laquo":"«","larrb":"⇤","larrbfs":"⤟","larr":"←","Larr":"↞","lArr":"⇐","larrfs":"⤝","larrhk":"↩","larrlp":"↫","larrpl":"⤹","larrsim":"⥳","larrtl":"↢","latail":"⤙","lAtail":"⤛","lat":"⪫","late":"⪭","lates":"⪭︀","lbarr":"⤌","lBarr":"⤎","lbbrk":"❲","lbrace":"{","lbrack":"[","lbrke":"⦋","lbrksld":"⦏","lbrkslu":"⦍","Lcaron":"Ľ","lcaron":"ľ","Lcedil":"Ļ","lcedil":"ļ","lceil":"⌈","lcub":"{","Lcy":"Л","lcy":"л","ldca":"⤶","ldquo":"“","ldquor":"„","ldrdhar":"⥧","ldrushar":"⥋","ldsh":"↲","le":"≤","lE":"≦","LeftAngleBracket":"⟨","LeftArrowBar":"⇤","leftarrow":"←","LeftArrow":"←","Leftarrow":"⇐","LeftArrowRightArrow":"⇆","leftarrowtail":"↢","LeftCeiling":"⌈","LeftDoubleBracket":"⟦","LeftDownTeeVector":"⥡","LeftDownVectorBar":"⥙","LeftDownVector":"⇃","LeftFloor":"⌊","leftharpoondown":"↽","leftharpoonup":"↼","leftleftarrows":"⇇","leftrightarrow":"↔","LeftRightArrow":"↔","Leftrightarrow":"⇔","leftrightarrows":"⇆","leftrightharpoons":"⇋","leftrightsquigarrow":"↭","LeftRightVector":"⥎","LeftTeeArrow":"↤","LeftTee":"⊣","LeftTeeVector":"⥚","leftthreetimes":"⋋","LeftTriangleBar":"⧏","LeftTriangle":"⊲","LeftTriangleEqual":"⊴","LeftUpDownVector":"⥑","LeftUpTeeVector":"⥠","LeftUpVectorBar":"⥘","LeftUpVector":"↿","LeftVectorBar":"⥒","LeftVector":"↼","lEg":"⪋","leg":"⋚","leq":"≤","leqq":"≦","leqslant":"⩽","lescc":"⪨","les":"⩽","lesdot":"⩿","lesdoto":"⪁","lesdotor":"⪃","lesg":"⋚︀","lesges":"⪓","lessapprox":"⪅","lessdot":"⋖","lesseqgtr":"⋚","lesseqqgtr":"⪋","LessEqualGreater":"⋚","LessFullEqual":"≦","LessGreater":"≶","lessgtr":"≶","LessLess":"⪡","lesssim":"≲","LessSlantEqual":"⩽","LessTilde":"≲","lfisht":"⥼","lfloor":"⌊","Lfr":"𝔏","lfr":"𝔩","lg":"≶","lgE":"⪑","lHar":"⥢","lhard":"↽","lharu":"↼","lharul":"⥪","lhblk":"▄","LJcy":"Љ","ljcy":"љ","llarr":"⇇","ll":"≪","Ll":"⋘","llcorner":"⌞","Lleftarrow":"⇚","llhard":"⥫","lltri":"◺","Lmidot":"Ŀ","lmidot":"ŀ","lmoustache":"⎰","lmoust":"⎰","lnap":"⪉","lnapprox":"⪉","lne":"⪇","lnE":"≨","lneq":"⪇","lneqq":"≨","lnsim":"⋦","loang":"⟬","loarr":"⇽","lobrk":"⟦","longleftarrow":"⟵","LongLeftArrow":"⟵","Longleftarrow":"⟸","longleftrightarrow":"⟷","LongLeftRightArrow":"⟷","Longleftrightarrow":"⟺","longmapsto":"⟼","longrightarrow":"⟶","LongRightArrow":"⟶","Longrightarrow":"⟹","looparrowleft":"↫","looparrowright":"↬","lopar":"⦅","Lopf":"𝕃","lopf":"𝕝","loplus":"⨭","lotimes":"⨴","lowast":"∗","lowbar":"_","LowerLeftArrow":"↙","LowerRightArrow":"↘","loz":"◊","lozenge":"◊","lozf":"⧫","lpar":"(","lparlt":"⦓","lrarr":"⇆","lrcorner":"⌟","lrhar":"⇋","lrhard":"⥭","lrm":"‎","lrtri":"⊿","lsaquo":"‹","lscr":"𝓁","Lscr":"ℒ","lsh":"↰","Lsh":"↰","lsim":"≲","lsime":"⪍","lsimg":"⪏","lsqb":"[","lsquo":"‘","lsquor":"‚","Lstrok":"Ł","lstrok":"ł","ltcc":"⪦","ltcir":"⩹","lt":"<","LT":"<","Lt":"≪","ltdot":"⋖","lthree":"⋋","ltimes":"⋉","ltlarr":"⥶","ltquest":"⩻","ltri":"◃","ltrie":"⊴","ltrif":"◂","ltrPar":"⦖","lurdshar":"⥊","luruhar":"⥦","lvertneqq":"≨︀","lvnE":"≨︀","macr":"¯","male":"♂","malt":"✠","maltese":"✠","Map":"⤅","map":"↦","mapsto":"↦","mapstodown":"↧","mapstoleft":"↤","mapstoup":"↥","marker":"▮","mcomma":"⨩","Mcy":"М","mcy":"м","mdash":"—","mDDot":"∺","measuredangle":"∡","MediumSpace":" ","Mellintrf":"ℳ","Mfr":"𝔐","mfr":"𝔪","mho":"℧","micro":"µ","midast":"*","midcir":"⫰","mid":"∣","middot":"·","minusb":"⊟","minus":"−","minusd":"∸","minusdu":"⨪","MinusPlus":"∓","mlcp":"⫛","mldr":"…","mnplus":"∓","models":"⊧","Mopf":"𝕄","mopf":"𝕞","mp":"∓","mscr":"𝓂","Mscr":"ℳ","mstpos":"∾","Mu":"Μ","mu":"μ","multimap":"⊸","mumap":"⊸","nabla":"∇","Nacute":"Ń","nacute":"ń","nang":"∠⃒","nap":"≉","napE":"⩰̸","napid":"≋̸","napos":"ŉ","napprox":"≉","natural":"♮","naturals":"ℕ","natur":"♮","nbsp":" ","nbump":"≎̸","nbumpe":"≏̸","ncap":"⩃","Ncaron":"Ň","ncaron":"ň","Ncedil":"Ņ","ncedil":"ņ","ncong":"≇","ncongdot":"⩭̸","ncup":"⩂","Ncy":"Н","ncy":"н","ndash":"–","nearhk":"⤤","nearr":"↗","neArr":"⇗","nearrow":"↗","ne":"≠","nedot":"≐̸","NegativeMediumSpace":"​","NegativeThickSpace":"​","NegativeThinSpace":"​","NegativeVeryThinSpace":"​","nequiv":"≢","nesear":"⤨","nesim":"≂̸","NestedGreaterGreater":"≫","NestedLessLess":"≪","NewLine":"\\n","nexist":"∄","nexists":"∄","Nfr":"𝔑","nfr":"𝔫","ngE":"≧̸","nge":"≱","ngeq":"≱","ngeqq":"≧̸","ngeqslant":"⩾̸","nges":"⩾̸","nGg":"⋙̸","ngsim":"≵","nGt":"≫⃒","ngt":"≯","ngtr":"≯","nGtv":"≫̸","nharr":"↮","nhArr":"⇎","nhpar":"⫲","ni":"∋","nis":"⋼","nisd":"⋺","niv":"∋","NJcy":"Њ","njcy":"њ","nlarr":"↚","nlArr":"⇍","nldr":"‥","nlE":"≦̸","nle":"≰","nleftarrow":"↚","nLeftarrow":"⇍","nleftrightarrow":"↮","nLeftrightarrow":"⇎","nleq":"≰","nleqq":"≦̸","nleqslant":"⩽̸","nles":"⩽̸","nless":"≮","nLl":"⋘̸","nlsim":"≴","nLt":"≪⃒","nlt":"≮","nltri":"⋪","nltrie":"⋬","nLtv":"≪̸","nmid":"∤","NoBreak":"⁠","NonBreakingSpace":" ","nopf":"𝕟","Nopf":"ℕ","Not":"⫬","not":"¬","NotCongruent":"≢","NotCupCap":"≭","NotDoubleVerticalBar":"∦","NotElement":"∉","NotEqual":"≠","NotEqualTilde":"≂̸","NotExists":"∄","NotGreater":"≯","NotGreaterEqual":"≱","NotGreaterFullEqual":"≧̸","NotGreaterGreater":"≫̸","NotGreaterLess":"≹","NotGreaterSlantEqual":"⩾̸","NotGreaterTilde":"≵","NotHumpDownHump":"≎̸","NotHumpEqual":"≏̸","notin":"∉","notindot":"⋵̸","notinE":"⋹̸","notinva":"∉","notinvb":"⋷","notinvc":"⋶","NotLeftTriangleBar":"⧏̸","NotLeftTriangle":"⋪","NotLeftTriangleEqual":"⋬","NotLess":"≮","NotLessEqual":"≰","NotLessGreater":"≸","NotLessLess":"≪̸","NotLessSlantEqual":"⩽̸","NotLessTilde":"≴","NotNestedGreaterGreater":"⪢̸","NotNestedLessLess":"⪡̸","notni":"∌","notniva":"∌","notnivb":"⋾","notnivc":"⋽","NotPrecedes":"⊀","NotPrecedesEqual":"⪯̸","NotPrecedesSlantEqual":"⋠","NotReverseElement":"∌","NotRightTriangleBar":"⧐̸","NotRightTriangle":"⋫","NotRightTriangleEqual":"⋭","NotSquareSubset":"⊏̸","NotSquareSubsetEqual":"⋢","NotSquareSuperset":"⊐̸","NotSquareSupersetEqual":"⋣","NotSubset":"⊂⃒","NotSubsetEqual":"⊈","NotSucceeds":"⊁","NotSucceedsEqual":"⪰̸","NotSucceedsSlantEqual":"⋡","NotSucceedsTilde":"≿̸","NotSuperset":"⊃⃒","NotSupersetEqual":"⊉","NotTilde":"≁","NotTildeEqual":"≄","NotTildeFullEqual":"≇","NotTildeTilde":"≉","NotVerticalBar":"∤","nparallel":"∦","npar":"∦","nparsl":"⫽⃥","npart":"∂̸","npolint":"⨔","npr":"⊀","nprcue":"⋠","nprec":"⊀","npreceq":"⪯̸","npre":"⪯̸","nrarrc":"⤳̸","nrarr":"↛","nrArr":"⇏","nrarrw":"↝̸","nrightarrow":"↛","nRightarrow":"⇏","nrtri":"⋫","nrtrie":"⋭","nsc":"⊁","nsccue":"⋡","nsce":"⪰̸","Nscr":"𝒩","nscr":"𝓃","nshortmid":"∤","nshortparallel":"∦","nsim":"≁","nsime":"≄","nsimeq":"≄","nsmid":"∤","nspar":"∦","nsqsube":"⋢","nsqsupe":"⋣","nsub":"⊄","nsubE":"⫅̸","nsube":"⊈","nsubset":"⊂⃒","nsubseteq":"⊈","nsubseteqq":"⫅̸","nsucc":"⊁","nsucceq":"⪰̸","nsup":"⊅","nsupE":"⫆̸","nsupe":"⊉","nsupset":"⊃⃒","nsupseteq":"⊉","nsupseteqq":"⫆̸","ntgl":"≹","Ntilde":"Ñ","ntilde":"ñ","ntlg":"≸","ntriangleleft":"⋪","ntrianglelefteq":"⋬","ntriangleright":"⋫","ntrianglerighteq":"⋭","Nu":"Ν","nu":"ν","num":"#","numero":"№","numsp":" ","nvap":"≍⃒","nvdash":"⊬","nvDash":"⊭","nVdash":"⊮","nVDash":"⊯","nvge":"≥⃒","nvgt":">⃒","nvHarr":"⤄","nvinfin":"⧞","nvlArr":"⤂","nvle":"≤⃒","nvlt":"<⃒","nvltrie":"⊴⃒","nvrArr":"⤃","nvrtrie":"⊵⃒","nvsim":"∼⃒","nwarhk":"⤣","nwarr":"↖","nwArr":"⇖","nwarrow":"↖","nwnear":"⤧","Oacute":"Ó","oacute":"ó","oast":"⊛","Ocirc":"Ô","ocirc":"ô","ocir":"⊚","Ocy":"О","ocy":"о","odash":"⊝","Odblac":"Ő","odblac":"ő","odiv":"⨸","odot":"⊙","odsold":"⦼","OElig":"Œ","oelig":"œ","ofcir":"⦿","Ofr":"𝔒","ofr":"𝔬","ogon":"˛","Ograve":"Ò","ograve":"ò","ogt":"⧁","ohbar":"⦵","ohm":"Ω","oint":"∮","olarr":"↺","olcir":"⦾","olcross":"⦻","oline":"‾","olt":"⧀","Omacr":"Ō","omacr":"ō","Omega":"Ω","omega":"ω","Omicron":"Ο","omicron":"ο","omid":"⦶","ominus":"⊖","Oopf":"𝕆","oopf":"𝕠","opar":"⦷","OpenCurlyDoubleQuote":"“","OpenCurlyQuote":"‘","operp":"⦹","oplus":"⊕","orarr":"↻","Or":"⩔","or":"∨","ord":"⩝","order":"ℴ","orderof":"ℴ","ordf":"ª","ordm":"º","origof":"⊶","oror":"⩖","orslope":"⩗","orv":"⩛","oS":"Ⓢ","Oscr":"𝒪","oscr":"ℴ","Oslash":"Ø","oslash":"ø","osol":"⊘","Otilde":"Õ","otilde":"õ","otimesas":"⨶","Otimes":"⨷","otimes":"⊗","Ouml":"Ö","ouml":"ö","ovbar":"⌽","OverBar":"‾","OverBrace":"⏞","OverBracket":"⎴","OverParenthesis":"⏜","para":"¶","parallel":"∥","par":"∥","parsim":"⫳","parsl":"⫽","part":"∂","PartialD":"∂","Pcy":"П","pcy":"п","percnt":"%","period":".","permil":"‰","perp":"⊥","pertenk":"‱","Pfr":"𝔓","pfr":"𝔭","Phi":"Φ","phi":"φ","phiv":"ϕ","phmmat":"ℳ","phone":"☎","Pi":"Π","pi":"π","pitchfork":"⋔","piv":"ϖ","planck":"ℏ","planckh":"ℎ","plankv":"ℏ","plusacir":"⨣","plusb":"⊞","pluscir":"⨢","plus":"+","plusdo":"∔","plusdu":"⨥","pluse":"⩲","PlusMinus":"±","plusmn":"±","plussim":"⨦","plustwo":"⨧","pm":"±","Poincareplane":"ℌ","pointint":"⨕","popf":"𝕡","Popf":"ℙ","pound":"£","prap":"⪷","Pr":"⪻","pr":"≺","prcue":"≼","precapprox":"⪷","prec":"≺","preccurlyeq":"≼","Precedes":"≺","PrecedesEqual":"⪯","PrecedesSlantEqual":"≼","PrecedesTilde":"≾","preceq":"⪯","precnapprox":"⪹","precneqq":"⪵","precnsim":"⋨","pre":"⪯","prE":"⪳","precsim":"≾","prime":"′","Prime":"″","primes":"ℙ","prnap":"⪹","prnE":"⪵","prnsim":"⋨","prod":"∏","Product":"∏","profalar":"⌮","profline":"⌒","profsurf":"⌓","prop":"∝","Proportional":"∝","Proportion":"∷","propto":"∝","prsim":"≾","prurel":"⊰","Pscr":"𝒫","pscr":"𝓅","Psi":"Ψ","psi":"ψ","puncsp":" ","Qfr":"𝔔","qfr":"𝔮","qint":"⨌","qopf":"𝕢","Qopf":"ℚ","qprime":"⁗","Qscr":"𝒬","qscr":"𝓆","quaternions":"ℍ","quatint":"⨖","quest":"?","questeq":"≟","quot":"\\"","QUOT":"\\"","rAarr":"⇛","race":"∽̱","Racute":"Ŕ","racute":"ŕ","radic":"√","raemptyv":"⦳","rang":"⟩","Rang":"⟫","rangd":"⦒","range":"⦥","rangle":"⟩","raquo":"»","rarrap":"⥵","rarrb":"⇥","rarrbfs":"⤠","rarrc":"⤳","rarr":"→","Rarr":"↠","rArr":"⇒","rarrfs":"⤞","rarrhk":"↪","rarrlp":"↬","rarrpl":"⥅","rarrsim":"⥴","Rarrtl":"⤖","rarrtl":"↣","rarrw":"↝","ratail":"⤚","rAtail":"⤜","ratio":"∶","rationals":"ℚ","rbarr":"⤍","rBarr":"⤏","RBarr":"⤐","rbbrk":"❳","rbrace":"}","rbrack":"]","rbrke":"⦌","rbrksld":"⦎","rbrkslu":"⦐","Rcaron":"Ř","rcaron":"ř","Rcedil":"Ŗ","rcedil":"ŗ","rceil":"⌉","rcub":"}","Rcy":"Р","rcy":"р","rdca":"⤷","rdldhar":"⥩","rdquo":"”","rdquor":"”","rdsh":"↳","real":"ℜ","realine":"ℛ","realpart":"ℜ","reals":"ℝ","Re":"ℜ","rect":"▭","reg":"®","REG":"®","ReverseElement":"∋","ReverseEquilibrium":"⇋","ReverseUpEquilibrium":"⥯","rfisht":"⥽","rfloor":"⌋","rfr":"𝔯","Rfr":"ℜ","rHar":"⥤","rhard":"⇁","rharu":"⇀","rharul":"⥬","Rho":"Ρ","rho":"ρ","rhov":"ϱ","RightAngleBracket":"⟩","RightArrowBar":"⇥","rightarrow":"→","RightArrow":"→","Rightarrow":"⇒","RightArrowLeftArrow":"⇄","rightarrowtail":"↣","RightCeiling":"⌉","RightDoubleBracket":"⟧","RightDownTeeVector":"⥝","RightDownVectorBar":"⥕","RightDownVector":"⇂","RightFloor":"⌋","rightharpoondown":"⇁","rightharpoonup":"⇀","rightleftarrows":"⇄","rightleftharpoons":"⇌","rightrightarrows":"⇉","rightsquigarrow":"↝","RightTeeArrow":"↦","RightTee":"⊢","RightTeeVector":"⥛","rightthreetimes":"⋌","RightTriangleBar":"⧐","RightTriangle":"⊳","RightTriangleEqual":"⊵","RightUpDownVector":"⥏","RightUpTeeVector":"⥜","RightUpVectorBar":"⥔","RightUpVector":"↾","RightVectorBar":"⥓","RightVector":"⇀","ring":"˚","risingdotseq":"≓","rlarr":"⇄","rlhar":"⇌","rlm":"‏","rmoustache":"⎱","rmoust":"⎱","rnmid":"⫮","roang":"⟭","roarr":"⇾","robrk":"⟧","ropar":"⦆","ropf":"𝕣","Ropf":"ℝ","roplus":"⨮","rotimes":"⨵","RoundImplies":"⥰","rpar":")","rpargt":"⦔","rppolint":"⨒","rrarr":"⇉","Rrightarrow":"⇛","rsaquo":"›","rscr":"𝓇","Rscr":"ℛ","rsh":"↱","Rsh":"↱","rsqb":"]","rsquo":"’","rsquor":"’","rthree":"⋌","rtimes":"⋊","rtri":"▹","rtrie":"⊵","rtrif":"▸","rtriltri":"⧎","RuleDelayed":"⧴","ruluhar":"⥨","rx":"℞","Sacute":"Ś","sacute":"ś","sbquo":"‚","scap":"⪸","Scaron":"Š","scaron":"š","Sc":"⪼","sc":"≻","sccue":"≽","sce":"⪰","scE":"⪴","Scedil":"Ş","scedil":"ş","Scirc":"Ŝ","scirc":"ŝ","scnap":"⪺","scnE":"⪶","scnsim":"⋩","scpolint":"⨓","scsim":"≿","Scy":"С","scy":"с","sdotb":"⊡","sdot":"⋅","sdote":"⩦","searhk":"⤥","searr":"↘","seArr":"⇘","searrow":"↘","sect":"§","semi":";","seswar":"⤩","setminus":"∖","setmn":"∖","sext":"✶","Sfr":"𝔖","sfr":"𝔰","sfrown":"⌢","sharp":"♯","SHCHcy":"Щ","shchcy":"щ","SHcy":"Ш","shcy":"ш","ShortDownArrow":"↓","ShortLeftArrow":"←","shortmid":"∣","shortparallel":"∥","ShortRightArrow":"→","ShortUpArrow":"↑","shy":"­","Sigma":"Σ","sigma":"σ","sigmaf":"ς","sigmav":"ς","sim":"∼","simdot":"⩪","sime":"≃","simeq":"≃","simg":"⪞","simgE":"⪠","siml":"⪝","simlE":"⪟","simne":"≆","simplus":"⨤","simrarr":"⥲","slarr":"←","SmallCircle":"∘","smallsetminus":"∖","smashp":"⨳","smeparsl":"⧤","smid":"∣","smile":"⌣","smt":"⪪","smte":"⪬","smtes":"⪬︀","SOFTcy":"Ь","softcy":"ь","solbar":"⌿","solb":"⧄","sol":"/","Sopf":"𝕊","sopf":"𝕤","spades":"♠","spadesuit":"♠","spar":"∥","sqcap":"⊓","sqcaps":"⊓︀","sqcup":"⊔","sqcups":"⊔︀","Sqrt":"√","sqsub":"⊏","sqsube":"⊑","sqsubset":"⊏","sqsubseteq":"⊑","sqsup":"⊐","sqsupe":"⊒","sqsupset":"⊐","sqsupseteq":"⊒","square":"□","Square":"□","SquareIntersection":"⊓","SquareSubset":"⊏","SquareSubsetEqual":"⊑","SquareSuperset":"⊐","SquareSupersetEqual":"⊒","SquareUnion":"⊔","squarf":"▪","squ":"□","squf":"▪","srarr":"→","Sscr":"𝒮","sscr":"𝓈","ssetmn":"∖","ssmile":"⌣","sstarf":"⋆","Star":"⋆","star":"☆","starf":"★","straightepsilon":"ϵ","straightphi":"ϕ","strns":"¯","sub":"⊂","Sub":"⋐","subdot":"⪽","subE":"⫅","sube":"⊆","subedot":"⫃","submult":"⫁","subnE":"⫋","subne":"⊊","subplus":"⪿","subrarr":"⥹","subset":"⊂","Subset":"⋐","subseteq":"⊆","subseteqq":"⫅","SubsetEqual":"⊆","subsetneq":"⊊","subsetneqq":"⫋","subsim":"⫇","subsub":"⫕","subsup":"⫓","succapprox":"⪸","succ":"≻","succcurlyeq":"≽","Succeeds":"≻","SucceedsEqual":"⪰","SucceedsSlantEqual":"≽","SucceedsTilde":"≿","succeq":"⪰","succnapprox":"⪺","succneqq":"⪶","succnsim":"⋩","succsim":"≿","SuchThat":"∋","sum":"∑","Sum":"∑","sung":"♪","sup1":"¹","sup2":"²","sup3":"³","sup":"⊃","Sup":"⋑","supdot":"⪾","supdsub":"⫘","supE":"⫆","supe":"⊇","supedot":"⫄","Superset":"⊃","SupersetEqual":"⊇","suphsol":"⟉","suphsub":"⫗","suplarr":"⥻","supmult":"⫂","supnE":"⫌","supne":"⊋","supplus":"⫀","supset":"⊃","Supset":"⋑","supseteq":"⊇","supseteqq":"⫆","supsetneq":"⊋","supsetneqq":"⫌","supsim":"⫈","supsub":"⫔","supsup":"⫖","swarhk":"⤦","swarr":"↙","swArr":"⇙","swarrow":"↙","swnwar":"⤪","szlig":"ß","Tab":"\\t","target":"⌖","Tau":"Τ","tau":"τ","tbrk":"⎴","Tcaron":"Ť","tcaron":"ť","Tcedil":"Ţ","tcedil":"ţ","Tcy":"Т","tcy":"т","tdot":"⃛","telrec":"⌕","Tfr":"𝔗","tfr":"𝔱","there4":"∴","therefore":"∴","Therefore":"∴","Theta":"Θ","theta":"θ","thetasym":"ϑ","thetav":"ϑ","thickapprox":"≈","thicksim":"∼","ThickSpace":"  ","ThinSpace":" ","thinsp":" ","thkap":"≈","thksim":"∼","THORN":"Þ","thorn":"þ","tilde":"˜","Tilde":"∼","TildeEqual":"≃","TildeFullEqual":"≅","TildeTilde":"≈","timesbar":"⨱","timesb":"⊠","times":"×","timesd":"⨰","tint":"∭","toea":"⤨","topbot":"⌶","topcir":"⫱","top":"⊤","Topf":"𝕋","topf":"𝕥","topfork":"⫚","tosa":"⤩","tprime":"‴","trade":"™","TRADE":"™","triangle":"▵","triangledown":"▿","triangleleft":"◃","trianglelefteq":"⊴","triangleq":"≜","triangleright":"▹","trianglerighteq":"⊵","tridot":"◬","trie":"≜","triminus":"⨺","TripleDot":"⃛","triplus":"⨹","trisb":"⧍","tritime":"⨻","trpezium":"⏢","Tscr":"𝒯","tscr":"𝓉","TScy":"Ц","tscy":"ц","TSHcy":"Ћ","tshcy":"ћ","Tstrok":"Ŧ","tstrok":"ŧ","twixt":"≬","twoheadleftarrow":"↞","twoheadrightarrow":"↠","Uacute":"Ú","uacute":"ú","uarr":"↑","Uarr":"↟","uArr":"⇑","Uarrocir":"⥉","Ubrcy":"Ў","ubrcy":"ў","Ubreve":"Ŭ","ubreve":"ŭ","Ucirc":"Û","ucirc":"û","Ucy":"У","ucy":"у","udarr":"⇅","Udblac":"Ű","udblac":"ű","udhar":"⥮","ufisht":"⥾","Ufr":"𝔘","ufr":"𝔲","Ugrave":"Ù","ugrave":"ù","uHar":"⥣","uharl":"↿","uharr":"↾","uhblk":"▀","ulcorn":"⌜","ulcorner":"⌜","ulcrop":"⌏","ultri":"◸","Umacr":"Ū","umacr":"ū","uml":"¨","UnderBar":"_","UnderBrace":"⏟","UnderBracket":"⎵","UnderParenthesis":"⏝","Union":"⋃","UnionPlus":"⊎","Uogon":"Ų","uogon":"ų","Uopf":"𝕌","uopf":"𝕦","UpArrowBar":"⤒","uparrow":"↑","UpArrow":"↑","Uparrow":"⇑","UpArrowDownArrow":"⇅","updownarrow":"↕","UpDownArrow":"↕","Updownarrow":"⇕","UpEquilibrium":"⥮","upharpoonleft":"↿","upharpoonright":"↾","uplus":"⊎","UpperLeftArrow":"↖","UpperRightArrow":"↗","upsi":"υ","Upsi":"ϒ","upsih":"ϒ","Upsilon":"Υ","upsilon":"υ","UpTeeArrow":"↥","UpTee":"⊥","upuparrows":"⇈","urcorn":"⌝","urcorner":"⌝","urcrop":"⌎","Uring":"Ů","uring":"ů","urtri":"◹","Uscr":"𝒰","uscr":"𝓊","utdot":"⋰","Utilde":"Ũ","utilde":"ũ","utri":"▵","utrif":"▴","uuarr":"⇈","Uuml":"Ü","uuml":"ü","uwangle":"⦧","vangrt":"⦜","varepsilon":"ϵ","varkappa":"ϰ","varnothing":"∅","varphi":"ϕ","varpi":"ϖ","varpropto":"∝","varr":"↕","vArr":"⇕","varrho":"ϱ","varsigma":"ς","varsubsetneq":"⊊︀","varsubsetneqq":"⫋︀","varsupsetneq":"⊋︀","varsupsetneqq":"⫌︀","vartheta":"ϑ","vartriangleleft":"⊲","vartriangleright":"⊳","vBar":"⫨","Vbar":"⫫","vBarv":"⫩","Vcy":"В","vcy":"в","vdash":"⊢","vDash":"⊨","Vdash":"⊩","VDash":"⊫","Vdashl":"⫦","veebar":"⊻","vee":"∨","Vee":"⋁","veeeq":"≚","vellip":"⋮","verbar":"|","Verbar":"‖","vert":"|","Vert":"‖","VerticalBar":"∣","VerticalLine":"|","VerticalSeparator":"❘","VerticalTilde":"≀","VeryThinSpace":" ","Vfr":"𝔙","vfr":"𝔳","vltri":"⊲","vnsub":"⊂⃒","vnsup":"⊃⃒","Vopf":"𝕍","vopf":"𝕧","vprop":"∝","vrtri":"⊳","Vscr":"𝒱","vscr":"𝓋","vsubnE":"⫋︀","vsubne":"⊊︀","vsupnE":"⫌︀","vsupne":"⊋︀","Vvdash":"⊪","vzigzag":"⦚","Wcirc":"Ŵ","wcirc":"ŵ","wedbar":"⩟","wedge":"∧","Wedge":"⋀","wedgeq":"≙","weierp":"℘","Wfr":"𝔚","wfr":"𝔴","Wopf":"𝕎","wopf":"𝕨","wp":"℘","wr":"≀","wreath":"≀","Wscr":"𝒲","wscr":"𝓌","xcap":"⋂","xcirc":"◯","xcup":"⋃","xdtri":"▽","Xfr":"𝔛","xfr":"𝔵","xharr":"⟷","xhArr":"⟺","Xi":"Ξ","xi":"ξ","xlarr":"⟵","xlArr":"⟸","xmap":"⟼","xnis":"⋻","xodot":"⨀","Xopf":"𝕏","xopf":"𝕩","xoplus":"⨁","xotime":"⨂","xrarr":"⟶","xrArr":"⟹","Xscr":"𝒳","xscr":"𝓍","xsqcup":"⨆","xuplus":"⨄","xutri":"△","xvee":"⋁","xwedge":"⋀","Yacute":"Ý","yacute":"ý","YAcy":"Я","yacy":"я","Ycirc":"Ŷ","ycirc":"ŷ","Ycy":"Ы","ycy":"ы","yen":"¥","Yfr":"𝔜","yfr":"𝔶","YIcy":"Ї","yicy":"ї","Yopf":"𝕐","yopf":"𝕪","Yscr":"𝒴","yscr":"𝓎","YUcy":"Ю","yucy":"ю","yuml":"ÿ","Yuml":"Ÿ","Zacute":"Ź","zacute":"ź","Zcaron":"Ž","zcaron":"ž","Zcy":"З","zcy":"з","Zdot":"Ż","zdot":"ż","zeetrf":"ℨ","ZeroWidthSpace":"​","Zeta":"Ζ","zeta":"ζ","zfr":"𝔷","Zfr":"ℨ","ZHcy":"Ж","zhcy":"ж","zigrarr":"⇝","zopf":"𝕫","Zopf":"ℤ","Zscr":"𝒵","zscr":"𝓏","zwj":"‍","zwnj":"‌"}')},function(e){e.exports=JSON.parse('{"amp":"&","apos":"\'","gt":">","lt":"<","quot":"\\""}')},function(e,t,r){"use strict";var n=this&&this.__importDefault||function(e){return e&&e.__esModule?e:{default:e}};Object.defineProperty(t,"__esModule",{value:!0}),t.escape=t.encodeHTML=t.encodeXML=void 0;var a=c(n(r(28)).default),o=l(a);t.encodeXML=d(a,o);var i=c(n(r(27)).default),s=l(i);function c(e){return Object.keys(e).sort().reduce((function(t,r){return t[e[r]]="&"+r+";",t}),{})}function l(e){for(var t=[],r=[],n=0,a=Object.keys(e);n<a.length;n++){var o=a[n];1===o.length?t.push("\\"+o):r.push(o)}t.sort();for(var i=0;i<t.length-1;i++){for(var s=i;s<t.length-1&&t[s].charCodeAt(1)+1===t[s+1].charCodeAt(1);)s+=1;var c=1+s-i;c<3||t.splice(i,c,t[i]+"-"+t[s])}return r.unshift("["+t.join("")+"]"),new RegExp(r.join("|"),"g")}t.encodeHTML=d(i,s);var u=/(?:[\x80-\uD7FF\uE000-\uFFFF]|[\uD800-\uDBFF][\uDC00-\uDFFF]|[\uD800-\uDBFF](?![\uDC00-\uDFFF])|(?:[^\uD800-\uDBFF]|^)[\uDC00-\uDFFF])/g;function p(e){return"&#x"+e.codePointAt(0).toString(16).toUpperCase()+";"}function d(e,t){return function(r){return r.replace(t,(function(t){return e[t]})).replace(u,p)}}var h=l(a);t.escape=function(e){return e.replace(h,p).replace(u,p)}},function(e,t,r){e.exports=s;var n=r(21),a=r(72).Writable,o=r(73).StringDecoder,i=r(31).Buffer;function s(e,t){var r=this._parser=new n(e,t),i=this._decoder=new o;a.call(this,{decodeStrings:!1}),this.once("finish",(function(){r.end(i.end())}))}r(17)(s,a),s.prototype._write=function(e,t,r){e instanceof i&&(e=this._decoder.write(e)),this._parser.write(e),r()}},function(e,t,r){"use strict";(function(e){var n=r(75),a=r(76),o=r(77);function i(){return c.TYPED_ARRAY_SUPPORT?2147483647:1073741823}function s(e,t){if(i()<t)throw new RangeError("Invalid typed array length");return c.TYPED_ARRAY_SUPPORT?(e=new Uint8Array(t)).__proto__=c.prototype:(null===e&&(e=new c(t)),e.length=t),e}function c(e,t,r){if(!(c.TYPED_ARRAY_SUPPORT||this instanceof c))return new c(e,t,r);if("number"==typeof e){if("string"==typeof t)throw new Error("If encoding is specified then the first argument must be a string");return p(this,e)}return l(this,e,t,r)}function l(e,t,r,n){if("number"==typeof t)throw new TypeError('"value" argument must not be a number');return"undefined"!=typeof ArrayBuffer&&t instanceof ArrayBuffer?function(e,t,r,n){if(t.byteLength,r<0||t.byteLength<r)throw new RangeError("'offset' is out of bounds");if(t.byteLength<r+(n||0))throw new RangeError("'length' is out of bounds");t=void 0===r&&void 0===n?new Uint8Array(t):void 0===n?new Uint8Array(t,r):new Uint8Array(t,r,n);c.TYPED_ARRAY_SUPPORT?(e=t).__proto__=c.prototype:e=d(e,t);return e}(e,t,r,n):"string"==typeof t?function(e,t,r){"string"==typeof r&&""!==r||(r="utf8");if(!c.isEncoding(r))throw new TypeError('"encoding" must be a valid string encoding');var n=0|f(t,r),a=(e=s(e,n)).write(t,r);a!==n&&(e=e.slice(0,a));return e}(e,t,r):function(e,t){if(c.isBuffer(t)){var r=0|h(t.length);return 0===(e=s(e,r)).length||t.copy(e,0,0,r),e}if(t){if("undefined"!=typeof ArrayBuffer&&t.buffer instanceof ArrayBuffer||"length"in t)return"number"!=typeof t.length||(n=t.length)!=n?s(e,0):d(e,t);if("Buffer"===t.type&&o(t.data))return d(e,t.data)}var n;throw new TypeError("First argument must be a string, Buffer, ArrayBuffer, Array, or array-like object.")}(e,t)}function u(e){if("number"!=typeof e)throw new TypeError('"size" argument must be a number');if(e<0)throw new RangeError('"size" argument must not be negative')}function p(e,t){if(u(t),e=s(e,t<0?0:0|h(t)),!c.TYPED_ARRAY_SUPPORT)for(var r=0;r<t;++r)e[r]=0;return e}function d(e,t){var r=t.length<0?0:0|h(t.length);e=s(e,r);for(var n=0;n<r;n+=1)e[n]=255&t[n];return e}function h(e){if(e>=i())throw new RangeError("Attempt to allocate Buffer larger than maximum size: 0x"+i().toString(16)+" bytes");return 0|e}function f(e,t){if(c.isBuffer(e))return e.length;if("undefined"!=typeof ArrayBuffer&&"function"==typeof ArrayBuffer.isView&&(ArrayBuffer.isView(e)||e instanceof ArrayBuffer))return e.byteLength;"string"!=typeof e&&(e=""+e);var r=e.length;if(0===r)return 0;for(var n=!1;;)switch(t){case"ascii":case"latin1":case"binary":return r;case"utf8":case"utf-8":case void 0:return H(e).length;case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return 2*r;case"hex":return r>>>1;case"base64":return I(e).length;default:if(n)return H(e).length;t=(""+t).toLowerCase(),n=!0}}function m(e,t,r){var n=!1;if((void 0===t||t<0)&&(t=0),t>this.length)return"";if((void 0===r||r>this.length)&&(r=this.length),r<=0)return"";if((r>>>=0)<=(t>>>=0))return"";for(e||(e="utf8");;)switch(e){case"hex":return N(this,t,r);case"utf8":case"utf-8":return C(this,t,r);case"ascii":return k(this,t,r);case"latin1":case"binary":return S(this,t,r);case"base64":return x(this,t,r);case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return z(this,t,r);default:if(n)throw new TypeError("Unknown encoding: "+e);e=(e+"").toLowerCase(),n=!0}}function g(e,t,r){var n=e[t];e[t]=e[r],e[r]=n}function b(e,t,r,n,a){if(0===e.length)return-1;if("string"==typeof r?(n=r,r=0):r>2147483647?r=2147483647:r<-2147483648&&(r=-2147483648),r=+r,isNaN(r)&&(r=a?0:e.length-1),r<0&&(r=e.length+r),r>=e.length){if(a)return-1;r=e.length-1}else if(r<0){if(!a)return-1;r=0}if("string"==typeof t&&(t=c.from(t,n)),c.isBuffer(t))return 0===t.length?-1:v(e,t,r,n,a);if("number"==typeof t)return t&=255,c.TYPED_ARRAY_SUPPORT&&"function"==typeof Uint8Array.prototype.indexOf?a?Uint8Array.prototype.indexOf.call(e,t,r):Uint8Array.prototype.lastIndexOf.call(e,t,r):v(e,[t],r,n,a);throw new TypeError("val must be string, number or Buffer")}function v(e,t,r,n,a){var o,i=1,s=e.length,c=t.length;if(void 0!==n&&("ucs2"===(n=String(n).toLowerCase())||"ucs-2"===n||"utf16le"===n||"utf-16le"===n)){if(e.length<2||t.length<2)return-1;i=2,s/=2,c/=2,r/=2}function l(e,t){return 1===i?e[t]:e.readUInt16BE(t*i)}if(a){var u=-1;for(o=r;o<s;o++)if(l(e,o)===l(t,-1===u?0:o-u)){if(-1===u&&(u=o),o-u+1===c)return u*i}else-1!==u&&(o-=o-u),u=-1}else for(r+c>s&&(r=s-c),o=r;o>=0;o--){for(var p=!0,d=0;d<c;d++)if(l(e,o+d)!==l(t,d)){p=!1;break}if(p)return o}return-1}function y(e,t,r,n){r=Number(r)||0;var a=e.length-r;n?(n=Number(n))>a&&(n=a):n=a;var o=t.length;if(o%2!=0)throw new TypeError("Invalid hex string");n>o/2&&(n=o/2);for(var i=0;i<n;++i){var s=parseInt(t.substr(2*i,2),16);if(isNaN(s))return i;e[r+i]=s}return i}function w(e,t,r,n){return V(H(t,e.length-r),e,r,n)}function _(e,t,r,n){return V(function(e){for(var t=[],r=0;r<e.length;++r)t.push(255&e.charCodeAt(r));return t}(t),e,r,n)}function O(e,t,r,n){return _(e,t,r,n)}function E(e,t,r,n){return V(I(t),e,r,n)}function j(e,t,r,n){return V(function(e,t){for(var r,n,a,o=[],i=0;i<e.length&&!((t-=2)<0);++i)r=e.charCodeAt(i),n=r>>8,a=r%256,o.push(a),o.push(n);return o}(t,e.length-r),e,r,n)}function x(e,t,r){return 0===t&&r===e.length?n.fromByteArray(e):n.fromByteArray(e.slice(t,r))}function C(e,t,r){r=Math.min(e.length,r);for(var n=[],a=t;a<r;){var o,i,s,c,l=e[a],u=null,p=l>239?4:l>223?3:l>191?2:1;if(a+p<=r)switch(p){case 1:l<128&&(u=l);break;case 2:128==(192&(o=e[a+1]))&&(c=(31&l)<<6|63&o)>127&&(u=c);break;case 3:o=e[a+1],i=e[a+2],128==(192&o)&&128==(192&i)&&(c=(15&l)<<12|(63&o)<<6|63&i)>2047&&(c<55296||c>57343)&&(u=c);break;case 4:o=e[a+1],i=e[a+2],s=e[a+3],128==(192&o)&&128==(192&i)&&128==(192&s)&&(c=(15&l)<<18|(63&o)<<12|(63&i)<<6|63&s)>65535&&c<1114112&&(u=c)}null===u?(u=65533,p=1):u>65535&&(u-=65536,n.push(u>>>10&1023|55296),u=56320|1023&u),n.push(u),a+=p}return function(e){var t=e.length;if(t<=4096)return String.fromCharCode.apply(String,e);var r="",n=0;for(;n<t;)r+=String.fromCharCode.apply(String,e.slice(n,n+=4096));return r}(n)}t.Buffer=c,t.SlowBuffer=function(e){+e!=e&&(e=0);return c.alloc(+e)},t.INSPECT_MAX_BYTES=50,c.TYPED_ARRAY_SUPPORT=void 0!==e.TYPED_ARRAY_SUPPORT?e.TYPED_ARRAY_SUPPORT:function(){try{var e=new Uint8Array(1);return e.__proto__={__proto__:Uint8Array.prototype,foo:function(){return 42}},42===e.foo()&&"function"==typeof e.subarray&&0===e.subarray(1,1).byteLength}catch(e){return!1}}(),t.kMaxLength=i(),c.poolSize=8192,c._augment=function(e){return e.__proto__=c.prototype,e},c.from=function(e,t,r){return l(null,e,t,r)},c.TYPED_ARRAY_SUPPORT&&(c.prototype.__proto__=Uint8Array.prototype,c.__proto__=Uint8Array,"undefined"!=typeof Symbol&&Symbol.species&&c[Symbol.species]===c&&Object.defineProperty(c,Symbol.species,{value:null,configurable:!0})),c.alloc=function(e,t,r){return function(e,t,r,n){return u(t),t<=0?s(e,t):void 0!==r?"string"==typeof n?s(e,t).fill(r,n):s(e,t).fill(r):s(e,t)}(null,e,t,r)},c.allocUnsafe=function(e){return p(null,e)},c.allocUnsafeSlow=function(e){return p(null,e)},c.isBuffer=function(e){return!(null==e||!e._isBuffer)},c.compare=function(e,t){if(!c.isBuffer(e)||!c.isBuffer(t))throw new TypeError("Arguments must be Buffers");if(e===t)return 0;for(var r=e.length,n=t.length,a=0,o=Math.min(r,n);a<o;++a)if(e[a]!==t[a]){r=e[a],n=t[a];break}return r<n?-1:n<r?1:0},c.isEncoding=function(e){switch(String(e).toLowerCase()){case"hex":case"utf8":case"utf-8":case"ascii":case"latin1":case"binary":case"base64":case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return!0;default:return!1}},c.concat=function(e,t){if(!o(e))throw new TypeError('"list" argument must be an Array of Buffers');if(0===e.length)return c.alloc(0);var r;if(void 0===t)for(t=0,r=0;r<e.length;++r)t+=e[r].length;var n=c.allocUnsafe(t),a=0;for(r=0;r<e.length;++r){var i=e[r];if(!c.isBuffer(i))throw new TypeError('"list" argument must be an Array of Buffers');i.copy(n,a),a+=i.length}return n},c.byteLength=f,c.prototype._isBuffer=!0,c.prototype.swap16=function(){var e=this.length;if(e%2!=0)throw new RangeError("Buffer size must be a multiple of 16-bits");for(var t=0;t<e;t+=2)g(this,t,t+1);return this},c.prototype.swap32=function(){var e=this.length;if(e%4!=0)throw new RangeError("Buffer size must be a multiple of 32-bits");for(var t=0;t<e;t+=4)g(this,t,t+3),g(this,t+1,t+2);return this},c.prototype.swap64=function(){var e=this.length;if(e%8!=0)throw new RangeError("Buffer size must be a multiple of 64-bits");for(var t=0;t<e;t+=8)g(this,t,t+7),g(this,t+1,t+6),g(this,t+2,t+5),g(this,t+3,t+4);return this},c.prototype.toString=function(){var e=0|this.length;return 0===e?"":0===arguments.length?C(this,0,e):m.apply(this,arguments)},c.prototype.equals=function(e){if(!c.isBuffer(e))throw new TypeError("Argument must be a Buffer");return this===e||0===c.compare(this,e)},c.prototype.inspect=function(){var e="",r=t.INSPECT_MAX_BYTES;return this.length>0&&(e=this.toString("hex",0,r).match(/.{2}/g).join(" "),this.length>r&&(e+=" ... ")),"<Buffer "+e+">"},c.prototype.compare=function(e,t,r,n,a){if(!c.isBuffer(e))throw new TypeError("Argument must be a Buffer");if(void 0===t&&(t=0),void 0===r&&(r=e?e.length:0),void 0===n&&(n=0),void 0===a&&(a=this.length),t<0||r>e.length||n<0||a>this.length)throw new RangeError("out of range index");if(n>=a&&t>=r)return 0;if(n>=a)return-1;if(t>=r)return 1;if(this===e)return 0;for(var o=(a>>>=0)-(n>>>=0),i=(r>>>=0)-(t>>>=0),s=Math.min(o,i),l=this.slice(n,a),u=e.slice(t,r),p=0;p<s;++p)if(l[p]!==u[p]){o=l[p],i=u[p];break}return o<i?-1:i<o?1:0},c.prototype.includes=function(e,t,r){return-1!==this.indexOf(e,t,r)},c.prototype.indexOf=function(e,t,r){return b(this,e,t,r,!0)},c.prototype.lastIndexOf=function(e,t,r){return b(this,e,t,r,!1)},c.prototype.write=function(e,t,r,n){if(void 0===t)n="utf8",r=this.length,t=0;else if(void 0===r&&"string"==typeof t)n=t,r=this.length,t=0;else{if(!isFinite(t))throw new Error("Buffer.write(string, encoding, offset[, length]) is no longer supported");t|=0,isFinite(r)?(r|=0,void 0===n&&(n="utf8")):(n=r,r=void 0)}var a=this.length-t;if((void 0===r||r>a)&&(r=a),e.length>0&&(r<0||t<0)||t>this.length)throw new RangeError("Attempt to write outside buffer bounds");n||(n="utf8");for(var o=!1;;)switch(n){case"hex":return y(this,e,t,r);case"utf8":case"utf-8":return w(this,e,t,r);case"ascii":return _(this,e,t,r);case"latin1":case"binary":return O(this,e,t,r);case"base64":return E(this,e,t,r);case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return j(this,e,t,r);default:if(o)throw new TypeError("Unknown encoding: "+n);n=(""+n).toLowerCase(),o=!0}},c.prototype.toJSON=function(){return{type:"Buffer",data:Array.prototype.slice.call(this._arr||this,0)}};function k(e,t,r){var n="";r=Math.min(e.length,r);for(var a=t;a<r;++a)n+=String.fromCharCode(127&e[a]);return n}function S(e,t,r){var n="";r=Math.min(e.length,r);for(var a=t;a<r;++a)n+=String.fromCharCode(e[a]);return n}function N(e,t,r){var n=e.length;(!t||t<0)&&(t=0),(!r||r<0||r>n)&&(r=n);for(var a="",o=t;o<r;++o)a+=B(e[o]);return a}function z(e,t,r){for(var n=e.slice(t,r),a="",o=0;o<n.length;o+=2)a+=String.fromCharCode(n[o]+256*n[o+1]);return a}function D(e,t,r){if(e%1!=0||e<0)throw new RangeError("offset is not uint");if(e+t>r)throw new RangeError("Trying to access beyond buffer length")}function A(e,t,r,n,a,o){if(!c.isBuffer(e))throw new TypeError('"buffer" argument must be a Buffer instance');if(t>a||t<o)throw new RangeError('"value" argument is out of bounds');if(r+n>e.length)throw new RangeError("Index out of range")}function M(e,t,r,n){t<0&&(t=65535+t+1);for(var a=0,o=Math.min(e.length-r,2);a<o;++a)e[r+a]=(t&255<<8*(n?a:1-a))>>>8*(n?a:1-a)}function T(e,t,r,n){t<0&&(t=4294967295+t+1);for(var a=0,o=Math.min(e.length-r,4);a<o;++a)e[r+a]=t>>>8*(n?a:3-a)&255}function P(e,t,r,n,a,o){if(r+n>e.length)throw new RangeError("Index out of range");if(r<0)throw new RangeError("Index out of range")}function L(e,t,r,n,o){return o||P(e,0,r,4),a.write(e,t,r,n,23,4),r+4}function q(e,t,r,n,o){return o||P(e,0,r,8),a.write(e,t,r,n,52,8),r+8}c.prototype.slice=function(e,t){var r,n=this.length;if((e=~~e)<0?(e+=n)<0&&(e=0):e>n&&(e=n),(t=void 0===t?n:~~t)<0?(t+=n)<0&&(t=0):t>n&&(t=n),t<e&&(t=e),c.TYPED_ARRAY_SUPPORT)(r=this.subarray(e,t)).__proto__=c.prototype;else{var a=t-e;r=new c(a,void 0);for(var o=0;o<a;++o)r[o]=this[o+e]}return r},c.prototype.readUIntLE=function(e,t,r){e|=0,t|=0,r||D(e,t,this.length);for(var n=this[e],a=1,o=0;++o<t&&(a*=256);)n+=this[e+o]*a;return n},c.prototype.readUIntBE=function(e,t,r){e|=0,t|=0,r||D(e,t,this.length);for(var n=this[e+--t],a=1;t>0&&(a*=256);)n+=this[e+--t]*a;return n},c.prototype.readUInt8=function(e,t){return t||D(e,1,this.length),this[e]},c.prototype.readUInt16LE=function(e,t){return t||D(e,2,this.length),this[e]|this[e+1]<<8},c.prototype.readUInt16BE=function(e,t){return t||D(e,2,this.length),this[e]<<8|this[e+1]},c.prototype.readUInt32LE=function(e,t){return t||D(e,4,this.length),(this[e]|this[e+1]<<8|this[e+2]<<16)+16777216*this[e+3]},c.prototype.readUInt32BE=function(e,t){return t||D(e,4,this.length),16777216*this[e]+(this[e+1]<<16|this[e+2]<<8|this[e+3])},c.prototype.readIntLE=function(e,t,r){e|=0,t|=0,r||D(e,t,this.length);for(var n=this[e],a=1,o=0;++o<t&&(a*=256);)n+=this[e+o]*a;return n>=(a*=128)&&(n-=Math.pow(2,8*t)),n},c.prototype.readIntBE=function(e,t,r){e|=0,t|=0,r||D(e,t,this.length);for(var n=t,a=1,o=this[e+--n];n>0&&(a*=256);)o+=this[e+--n]*a;return o>=(a*=128)&&(o-=Math.pow(2,8*t)),o},c.prototype.readInt8=function(e,t){return t||D(e,1,this.length),128&this[e]?-1*(255-this[e]+1):this[e]},c.prototype.readInt16LE=function(e,t){t||D(e,2,this.length);var r=this[e]|this[e+1]<<8;return 32768&r?4294901760|r:r},c.prototype.readInt16BE=function(e,t){t||D(e,2,this.length);var r=this[e+1]|this[e]<<8;return 32768&r?4294901760|r:r},c.prototype.readInt32LE=function(e,t){return t||D(e,4,this.length),this[e]|this[e+1]<<8|this[e+2]<<16|this[e+3]<<24},c.prototype.readInt32BE=function(e,t){return t||D(e,4,this.length),this[e]<<24|this[e+1]<<16|this[e+2]<<8|this[e+3]},c.prototype.readFloatLE=function(e,t){return t||D(e,4,this.length),a.read(this,e,!0,23,4)},c.prototype.readFloatBE=function(e,t){return t||D(e,4,this.length),a.read(this,e,!1,23,4)},c.prototype.readDoubleLE=function(e,t){return t||D(e,8,this.length),a.read(this,e,!0,52,8)},c.prototype.readDoubleBE=function(e,t){return t||D(e,8,this.length),a.read(this,e,!1,52,8)},c.prototype.writeUIntLE=function(e,t,r,n){(e=+e,t|=0,r|=0,n)||A(this,e,t,r,Math.pow(2,8*r)-1,0);var a=1,o=0;for(this[t]=255&e;++o<r&&(a*=256);)this[t+o]=e/a&255;return t+r},c.prototype.writeUIntBE=function(e,t,r,n){(e=+e,t|=0,r|=0,n)||A(this,e,t,r,Math.pow(2,8*r)-1,0);var a=r-1,o=1;for(this[t+a]=255&e;--a>=0&&(o*=256);)this[t+a]=e/o&255;return t+r},c.prototype.writeUInt8=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,1,255,0),c.TYPED_ARRAY_SUPPORT||(e=Math.floor(e)),this[t]=255&e,t+1},c.prototype.writeUInt16LE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,2,65535,0),c.TYPED_ARRAY_SUPPORT?(this[t]=255&e,this[t+1]=e>>>8):M(this,e,t,!0),t+2},c.prototype.writeUInt16BE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,2,65535,0),c.TYPED_ARRAY_SUPPORT?(this[t]=e>>>8,this[t+1]=255&e):M(this,e,t,!1),t+2},c.prototype.writeUInt32LE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,4,4294967295,0),c.TYPED_ARRAY_SUPPORT?(this[t+3]=e>>>24,this[t+2]=e>>>16,this[t+1]=e>>>8,this[t]=255&e):T(this,e,t,!0),t+4},c.prototype.writeUInt32BE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,4,4294967295,0),c.TYPED_ARRAY_SUPPORT?(this[t]=e>>>24,this[t+1]=e>>>16,this[t+2]=e>>>8,this[t+3]=255&e):T(this,e,t,!1),t+4},c.prototype.writeIntLE=function(e,t,r,n){if(e=+e,t|=0,!n){var a=Math.pow(2,8*r-1);A(this,e,t,r,a-1,-a)}var o=0,i=1,s=0;for(this[t]=255&e;++o<r&&(i*=256);)e<0&&0===s&&0!==this[t+o-1]&&(s=1),this[t+o]=(e/i>>0)-s&255;return t+r},c.prototype.writeIntBE=function(e,t,r,n){if(e=+e,t|=0,!n){var a=Math.pow(2,8*r-1);A(this,e,t,r,a-1,-a)}var o=r-1,i=1,s=0;for(this[t+o]=255&e;--o>=0&&(i*=256);)e<0&&0===s&&0!==this[t+o+1]&&(s=1),this[t+o]=(e/i>>0)-s&255;return t+r},c.prototype.writeInt8=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,1,127,-128),c.TYPED_ARRAY_SUPPORT||(e=Math.floor(e)),e<0&&(e=255+e+1),this[t]=255&e,t+1},c.prototype.writeInt16LE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,2,32767,-32768),c.TYPED_ARRAY_SUPPORT?(this[t]=255&e,this[t+1]=e>>>8):M(this,e,t,!0),t+2},c.prototype.writeInt16BE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,2,32767,-32768),c.TYPED_ARRAY_SUPPORT?(this[t]=e>>>8,this[t+1]=255&e):M(this,e,t,!1),t+2},c.prototype.writeInt32LE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,4,2147483647,-2147483648),c.TYPED_ARRAY_SUPPORT?(this[t]=255&e,this[t+1]=e>>>8,this[t+2]=e>>>16,this[t+3]=e>>>24):T(this,e,t,!0),t+4},c.prototype.writeInt32BE=function(e,t,r){return e=+e,t|=0,r||A(this,e,t,4,2147483647,-2147483648),e<0&&(e=4294967295+e+1),c.TYPED_ARRAY_SUPPORT?(this[t]=e>>>24,this[t+1]=e>>>16,this[t+2]=e>>>8,this[t+3]=255&e):T(this,e,t,!1),t+4},c.prototype.writeFloatLE=function(e,t,r){return L(this,e,t,!0,r)},c.prototype.writeFloatBE=function(e,t,r){return L(this,e,t,!1,r)},c.prototype.writeDoubleLE=function(e,t,r){return q(this,e,t,!0,r)},c.prototype.writeDoubleBE=function(e,t,r){return q(this,e,t,!1,r)},c.prototype.copy=function(e,t,r,n){if(r||(r=0),n||0===n||(n=this.length),t>=e.length&&(t=e.length),t||(t=0),n>0&&n<r&&(n=r),n===r)return 0;if(0===e.length||0===this.length)return 0;if(t<0)throw new RangeError("targetStart out of bounds");if(r<0||r>=this.length)throw new RangeError("sourceStart out of bounds");if(n<0)throw new RangeError("sourceEnd out of bounds");n>this.length&&(n=this.length),e.length-t<n-r&&(n=e.length-t+r);var a,o=n-r;if(this===e&&r<t&&t<n)for(a=o-1;a>=0;--a)e[a+t]=this[a+r];else if(o<1e3||!c.TYPED_ARRAY_SUPPORT)for(a=0;a<o;++a)e[a+t]=this[a+r];else Uint8Array.prototype.set.call(e,this.subarray(r,r+o),t);return o},c.prototype.fill=function(e,t,r,n){if("string"==typeof e){if("string"==typeof t?(n=t,t=0,r=this.length):"string"==typeof r&&(n=r,r=this.length),1===e.length){var a=e.charCodeAt(0);a<256&&(e=a)}if(void 0!==n&&"string"!=typeof n)throw new TypeError("encoding must be a string");if("string"==typeof n&&!c.isEncoding(n))throw new TypeError("Unknown encoding: "+n)}else"number"==typeof e&&(e&=255);if(t<0||this.length<t||this.length<r)throw new RangeError("Out of range index");if(r<=t)return this;var o;if(t>>>=0,r=void 0===r?this.length:r>>>0,e||(e=0),"number"==typeof e)for(o=t;o<r;++o)this[o]=e;else{var i=c.isBuffer(e)?e:H(new c(e,n).toString()),s=i.length;for(o=0;o<r-t;++o)this[o+t]=i[o%s]}return this};var R=/[^+\/0-9A-Za-z-_]/g;function B(e){return e<16?"0"+e.toString(16):e.toString(16)}function H(e,t){var r;t=t||1/0;for(var n=e.length,a=null,o=[],i=0;i<n;++i){if((r=e.charCodeAt(i))>55295&&r<57344){if(!a){if(r>56319){(t-=3)>-1&&o.push(239,191,189);continue}if(i+1===n){(t-=3)>-1&&o.push(239,191,189);continue}a=r;continue}if(r<56320){(t-=3)>-1&&o.push(239,191,189),a=r;continue}r=65536+(a-55296<<10|r-56320)}else a&&(t-=3)>-1&&o.push(239,191,189);if(a=null,r<128){if((t-=1)<0)break;o.push(r)}else if(r<2048){if((t-=2)<0)break;o.push(r>>6|192,63&r|128)}else if(r<65536){if((t-=3)<0)break;o.push(r>>12|224,r>>6&63|128,63&r|128)}else{if(!(r<1114112))throw new Error("Invalid code point");if((t-=4)<0)break;o.push(r>>18|240,r>>12&63|128,r>>6&63|128,63&r|128)}}return o}function I(e){return n.toByteArray(function(e){if((e=function(e){return e.trim?e.trim():e.replace(/^\s+|\s+$/g,"")}(e).replace(R,"")).length<2)return"";for(;e.length%4!=0;)e+="=";return e}(e))}function V(e,t,r,n){for(var a=0;a<n&&!(a+r>=t.length||a>=e.length);++a)t[a+r]=e[a];return a}}).call(this,r(32))},function(e,t){var r;r=function(){return this}();try{r=r||new Function("return this")()}catch(e){"object"==typeof window&&(r=window)}e.exports=r},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e};t.default=function(e,t){var r=n({},(0,a.default)(e),{key:t});"string"==typeof r.style||r.style instanceof String?r.style=(0,o.default)(r.style):delete r.style;return r};var a=i(r(82)),o=i(r(85));function i(e){return e&&e.__esModule?e:{default:e}}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){a.hasOwnProperty(e)||(a[e]=n.test(e));return a[e]};var n=/^[a-zA-Z][a-zA-Z:_\.\-\d]*$/,a={}},function(e,t,r){var n=r(36);e.exports=function(e,t){if(e){if("string"==typeof e)return n(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?n(e,t):void 0}}},function(e,t){e.exports=function(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}},function(e,t){e.exports=function(e){var t=typeof e;return null!=e&&("object"==t||"function"==t)}},function(e,t,r){var n=r(101),a="object"==typeof self&&self&&self.Object===Object&&self,o=n||a||Function("return this")();e.exports=o},function(e,t,r){var n=r(38).Symbol;e.exports=n},function(e){e.exports=JSON.parse("{\"facebook\":\"<svg aria-labelledby='facebook' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>Facebook</title><path d='M19.5 2C21.984 2 24 4.016 24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-2.938v-9.297h3.109l.469-3.625h-3.578v-2.312c0-1.047.281-1.75 1.797-1.75L20.265 9V5.766c-.328-.047-1.469-.141-2.781-.141-2.766 0-4.672 1.687-4.672 4.781v2.672H9.687v3.625h3.125V26H4.499a4.502 4.502 0 01-4.5-4.5v-15c0-2.484 2.016-4.5 4.5-4.5h15z'></path></svg>\",\"twitter\":\"<svg aria-labelledby='twitter' xmlns='http://www.w3.org/2000/svg' width='26' height='28' viewBox='0 0 26 28'><title>twitter</title><path d='M25.312 6.375a10.85 10.85 0 01-2.531 2.609c.016.219.016.438.016.656 0 6.672-5.078 14.359-14.359 14.359-2.859 0-5.516-.828-7.75-2.266.406.047.797.063 1.219.063 2.359 0 4.531-.797 6.266-2.156a5.056 5.056 0 01-4.719-3.5c.313.047.625.078.953.078.453 0 .906-.063 1.328-.172a5.048 5.048 0 01-4.047-4.953v-.063a5.093 5.093 0 002.281.641 5.044 5.044 0 01-2.25-4.203c0-.938.25-1.797.688-2.547a14.344 14.344 0 0010.406 5.281 5.708 5.708 0 01-.125-1.156 5.045 5.045 0 015.047-5.047 5.03 5.03 0 013.687 1.594 9.943 9.943 0 003.203-1.219 5.032 5.032 0 01-2.219 2.781c1.016-.109 2-.391 2.906-.781z'></path></svg>\",\"instagram\":\"<svg aria-labelledby='instagram' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'><title>Instagram</title><path d='M21.138.242c3.767.007 3.914.038 4.65.144 1.52.219 2.795.825 3.837 1.821a6.243 6.243 0 011.349 1.848c.442.899.659 1.75.758 3.016.021.271.031 4.592.031 8.916s-.009 8.652-.03 8.924c-.098 1.245-.315 2.104-.743 2.986a6.6 6.6 0 01-4.303 3.522c-.685.177-1.304.26-2.371.31-.381.019-4.361.024-8.342.024s-7.959-.012-8.349-.029c-.921-.044-1.639-.136-2.288-.303a6.64 6.64 0 01-4.303-3.515c-.436-.904-.642-1.731-.751-3.045-.031-.373-.039-2.296-.039-8.87 0-2.215-.002-3.866 0-5.121.006-3.764.037-3.915.144-4.652.219-1.518.825-2.795 1.825-3.833a6.302 6.302 0 011.811-1.326C4.939.603 5.78.391 7.13.278 7.504.247 9.428.24 16.008.24h5.13zm-5.139 4.122c-3.159 0-3.555.014-4.796.07-1.239.057-2.084.253-2.824.541-.765.297-1.415.695-2.061 1.342S5.273 7.613 4.975 8.378c-.288.74-.485 1.586-.541 2.824-.056 1.241-.07 1.638-.07 4.798s.014 3.556.07 4.797c.057 1.239.253 2.084.541 2.824.297.765.695 1.415 1.342 2.061s1.296 1.046 2.061 1.343c.74.288 1.586.484 2.825.541 1.241.056 1.638.07 4.798.07s3.556-.014 4.797-.07c1.239-.057 2.085-.253 2.826-.541.765-.297 1.413-.696 2.06-1.343s1.045-1.296 1.343-2.061c.286-.74.482-1.586.541-2.824.056-1.241.07-1.637.07-4.797s-.015-3.557-.07-4.798c-.058-1.239-.255-2.084-.541-2.824-.298-.765-.696-1.415-1.343-2.061s-1.295-1.045-2.061-1.342c-.742-.288-1.588-.484-2.827-.541-1.241-.056-1.636-.07-4.796-.07zm-1.042 2.097h1.044c3.107 0 3.475.011 4.702.067 1.135.052 1.75.241 2.16.401.543.211.93.463 1.337.87s.659.795.871 1.338c.159.41.349 1.025.401 2.16.056 1.227.068 1.595.068 4.701s-.012 3.474-.068 4.701c-.052 1.135-.241 1.75-.401 2.16-.211.543-.463.93-.871 1.337s-.794.659-1.337.87c-.41.16-1.026.349-2.16.401-1.227.056-1.595.068-4.702.068s-3.475-.012-4.702-.068c-1.135-.052-1.75-.242-2.161-.401-.543-.211-.931-.463-1.338-.87s-.659-.794-.871-1.337c-.159-.41-.349-1.025-.401-2.16-.056-1.227-.067-1.595-.067-4.703s.011-3.474.067-4.701c.052-1.135.241-1.75.401-2.16.211-.543.463-.931.871-1.338s.795-.659 1.338-.871c.41-.16 1.026-.349 2.161-.401 1.073-.048 1.489-.063 3.658-.065v.003zm1.044 3.563a5.977 5.977 0 10.001 11.953 5.977 5.977 0 00-.001-11.953zm0 2.097a3.879 3.879 0 110 7.758 3.879 3.879 0 010-7.758zm6.211-3.728a1.396 1.396 0 100 2.792 1.396 1.396 0 000-2.792v.001z'></path></svg>\",\"vimeo\":\"<svg aria-labelledby='vimeo' xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'><title>vimeo</title><path d='M26.703 8.094c-.109 2.469-1.844 5.859-5.187 10.172C18.047 22.75 15.141 25 12.735 25c-1.484 0-2.734-1.375-3.75-4.109-.688-2.5-1.375-5.016-2.063-7.531-.75-2.734-1.578-4.094-2.453-4.094-.187 0-.844.391-1.984 1.188L1.282 8.923c1.25-1.109 2.484-2.234 3.719-3.313 1.656-1.469 2.922-2.203 3.766-2.281 1.984-.187 3.187 1.156 3.656 4.047.484 3.125.844 5.078 1.031 5.828.578 2.594 1.188 3.891 1.875 3.891.531 0 1.328-.828 2.406-2.516 1.062-1.687 1.625-2.969 1.703-3.844.141-1.453-.422-2.172-1.703-2.172-.609 0-1.234.141-1.891.406 1.25-4.094 3.641-6.078 7.172-5.969 2.609.078 3.844 1.781 3.687 5.094z'></path></svg>\",\"youtube\":\"<svg aria-labelledby='youtube' xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'><title>youtube</title><path d='M11.109 17.625l7.562-3.906-7.562-3.953v7.859zM14 4.156c5.891 0 9.797.281 9.797.281.547.063 1.75.063 2.812 1.188 0 0 .859.844 1.109 2.781.297 2.266.281 4.531.281 4.531v2.125s.016 2.266-.281 4.531c-.25 1.922-1.109 2.781-1.109 2.781-1.062 1.109-2.266 1.109-2.812 1.172 0 0-3.906.297-9.797.297-7.281-.063-9.516-.281-9.516-.281-.625-.109-2.031-.078-3.094-1.188 0 0-.859-.859-1.109-2.781C-.016 17.327 0 15.062 0 15.062v-2.125s-.016-2.266.281-4.531C.531 6.469 1.39 5.625 1.39 5.625 2.452 4.5 3.656 4.5 4.202 4.437c0 0 3.906-.281 9.797-.281z'></path></svg>\",\"github\":\"<svg aria-labelledby='github' aria-labelledby='github' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>Github</title><path d='M12 2c6.625 0 12 5.375 12 12 0 5.297-3.437 9.797-8.203 11.391-.609.109-.828-.266-.828-.578 0-.391.016-1.687.016-3.297 0-1.125-.375-1.844-.812-2.219 2.672-.297 5.484-1.313 5.484-5.922 0-1.313-.469-2.375-1.234-3.219.125-.313.531-1.531-.125-3.187-1-.313-3.297 1.234-3.297 1.234a11.28 11.28 0 00-6 0S6.704 6.656 5.704 6.969c-.656 1.656-.25 2.875-.125 3.187-.766.844-1.234 1.906-1.234 3.219 0 4.594 2.797 5.625 5.469 5.922-.344.313-.656.844-.766 1.609-.688.313-2.438.844-3.484-1-.656-1.141-1.844-1.234-1.844-1.234-1.172-.016-.078.734-.078.734.781.359 1.328 1.75 1.328 1.75.703 2.141 4.047 1.422 4.047 1.422 0 1 .016 1.937.016 2.234 0 .313-.219.688-.828.578C3.439 23.796.002 19.296.002 13.999c0-6.625 5.375-12 12-12zM4.547 19.234c.031-.063-.016-.141-.109-.187-.094-.031-.172-.016-.203.031-.031.063.016.141.109.187.078.047.172.031.203-.031zm.484.532c.063-.047.047-.156-.031-.25-.078-.078-.187-.109-.25-.047-.063.047-.047.156.031.25.078.078.187.109.25.047zm.469.703c.078-.063.078-.187 0-.297-.063-.109-.187-.156-.266-.094-.078.047-.078.172 0 .281s.203.156.266.109zm.656.656c.063-.063.031-.203-.063-.297-.109-.109-.25-.125-.313-.047-.078.063-.047.203.063.297.109.109.25.125.313.047zm.891.391c.031-.094-.063-.203-.203-.25-.125-.031-.266.016-.297.109s.063.203.203.234c.125.047.266 0 .297-.094zm.984.078c0-.109-.125-.187-.266-.172-.141 0-.25.078-.25.172 0 .109.109.187.266.172.141 0 .25-.078.25-.172zm.906-.156c-.016-.094-.141-.156-.281-.141-.141.031-.234.125-.219.234.016.094.141.156.281.125s.234-.125.219-.219z'></path></svg>\",\"rss\":\"<svg aria-labelledby='rss' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>rss</title><path d='M8 20c0-1.109-.891-2-2-2s-2 .891-2 2 .891 2 2 2 2-.891 2-2zm5.484 1.469a9.468 9.468 0 00-8.953-8.953c-.141-.016-.281.047-.375.141S4 12.876 4 13.016v2c0 .266.203.484.469.5 3.203.234 5.781 2.812 6.016 6.016a.498.498 0 00.5.469h2c.141 0 .266-.063.359-.156s.156-.234.141-.375zm6 .015C19.218 13.359 12.64 6.781 4.515 6.515a.38.38 0 00-.359.141.508.508 0 00-.156.359v2c0 .266.219.484.484.5 6.484.234 11.766 5.516 12 12a.51.51 0 00.5.484h2a.509.509 0 00.359-.156.4.4 0 00.141-.359zM24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-15A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15C21.984 2 24 4.016 24 6.5z'></path></svg>\",\"facebook_group\":\"<svg aria-labelledby='facebook_group' xmlns='http://www.w3.org/2000/svg' width='30' height='28' viewBox='0 0 30 28'><title>Facebook Group</title><path d='M9.266 14a5.532 5.532 0 00-4.141 2H3.031C1.468 16 0 15.25 0 13.516 0 12.25-.047 8 1.937 8c.328 0 1.953 1.328 4.062 1.328.719 0 1.406-.125 2.078-.359A7.624 7.624 0 007.999 10c0 1.422.453 2.828 1.266 4zM26 23.953C26 26.484 24.328 28 21.828 28H8.172C5.672 28 4 26.484 4 23.953 4 20.422 4.828 15 9.406 15c.531 0 2.469 2.172 5.594 2.172S20.063 15 20.594 15C25.172 15 26 20.422 26 23.953zM10 4c0 2.203-1.797 4-4 4S2 6.203 2 4s1.797-4 4-4 4 1.797 4 4zm11 6c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zm9 3.516C30 15.25 28.531 16 26.969 16h-2.094a5.532 5.532 0 00-4.141-2A7.066 7.066 0 0022 10a7.6 7.6 0 00-.078-1.031A6.258 6.258 0 0024 9.328C26.109 9.328 27.734 8 28.062 8c1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z'></path></svg>\",\"dribbble\":\"<svg aria-labelledby='dribbble' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>Dribbble</title><path d='M16 23.438c-.156-.906-.75-4.031-2.188-7.781-.016 0-.047.016-.063.016 0 0-6.078 2.125-8.047 6.406-.094-.078-.234-.172-.234-.172a10.297 10.297 0 006.531 2.344c1.422 0 2.766-.297 4-.812zm-2.891-9.485a29.025 29.025 0 00-.828-1.734C7 13.797 1.937 13.672 1.765 13.672c-.016.109-.016.219-.016.328 0 2.625 1 5.031 2.625 6.844 2.797-4.984 8.328-6.766 8.328-6.766.141-.047.281-.078.406-.125zm-1.671-3.312a61.656 61.656 0 00-3.813-5.906 10.267 10.267 0 00-5.656 7.156c.266 0 4.547.047 9.469-1.25zm10.687 4.984c-.219-.063-3.078-.969-6.391-.453 1.344 3.703 1.891 6.719 2 7.328a10.293 10.293 0 004.391-6.875zM9.547 4.047c-.016 0-.016 0-.031.016 0 0 .016-.016.031-.016zm9.219 2.265a10.17 10.17 0 00-9.188-2.265c.156.203 2.094 2.75 3.844 5.969 3.859-1.437 5.313-3.656 5.344-3.703zm3.484 7.579a10.273 10.273 0 00-2.328-6.406c-.031.031-1.672 2.406-5.719 4.062.234.484.469.984.688 1.484.078.172.141.359.219.531 3.531-.453 7.016.313 7.141.328zM24 14c0 6.625-5.375 12-12 12S0 20.625 0 14 5.375 2 12 2s12 5.375 12 12z'></path></svg>\",\"xing\":\"<svg aria-labelledby='xing' xmlns='http://www.w3.org/2000/svg' width='22' height='28' viewBox='0 0 22 28'><title>xing</title><path d='M9.328 10.422s-.156.266-4.016 7.125c-.203.344-.469.719-1.016.719H.562c-.219 0-.391-.109-.484-.266s-.109-.359 0-.562l3.953-7c.016 0 .016 0 0-.016L1.515 6.063c-.109-.203-.125-.422-.016-.578.094-.156.281-.234.5-.234h3.734c.562 0 .844.375 1.031.703a773.586 773.586 0 002.562 4.469zM21.922.391c.109.156.109.375 0 .578l-8.25 14.594c-.016 0-.016.016 0 .016l5.25 9.609c.109.203.109.422.016.578-.109.156-.281.234-.5.234h-3.734c-.562 0-.859-.375-1.031-.703-5.297-9.703-5.297-9.719-5.297-9.719s.266-.469 8.297-14.719c.203-.359.438-.703 1-.703h3.766c.219 0 .391.078.484.234z'></path></svg>\",\"wordpress\":\"<svg aria-labelledby='wordpress' xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'><title>wordpress</title><path d='M1.984 14c0-1.734.375-3.391 1.047-4.891l5.734 15.703c-4.016-1.953-6.781-6.062-6.781-10.813zm20.125-.609c0 1.031-.422 2.219-.922 3.891l-1.188 4-4.344-12.906s.719-.047 1.375-.125c.641-.078.562-1.031-.078-.984-1.953.141-3.203.156-3.203.156s-1.172-.016-3.156-.156c-.656-.047-.734.938-.078.984.609.063 1.25.125 1.25.125l1.875 5.125-2.625 7.875-4.375-13s.719-.047 1.375-.125c.641-.078.562-1.031-.078-.984-1.937.141-3.203.156-3.203.156-.219 0-.484-.016-.766-.016a11.966 11.966 0 0110.031-5.422c3.125 0 5.969 1.203 8.109 3.156h-.156c-1.172 0-2.016 1.016-2.016 2.125 0 .984.578 1.813 1.188 2.812.469.797.984 1.828.984 3.313zm-7.906 1.656l3.703 10.109a.59.59 0 00.078.172c-1.25.438-2.578.688-3.984.688-1.172 0-2.312-.172-3.391-.5zm10.328-6.813A11.98 11.98 0 0126.015 14c0 4.438-2.406 8.297-5.984 10.375l3.672-10.594c.609-1.75.922-3.094.922-4.312 0-.438-.031-.844-.094-1.234zM14 0c7.719 0 14 6.281 14 14s-6.281 14-14 14S0 21.719 0 14 6.281 0 14 0zm0 27.359c7.359 0 13.359-6 13.359-13.359S21.359.641 14 .641.641 6.641.641 14s6 13.359 13.359 13.359z'></path></svg>\",\"whatsapp\":\"<svg aria-labelledby='whatsapp' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>whatsapp</title><path d='M15.391 15.219c.266 0 2.812 1.328 2.922 1.516.031.078.031.172.031.234 0 .391-.125.828-.266 1.188-.359.875-1.813 1.437-2.703 1.437-.75 0-2.297-.656-2.969-.969-2.234-1.016-3.625-2.75-4.969-4.734-.594-.875-1.125-1.953-1.109-3.031v-.125c.031-1.031.406-1.766 1.156-2.469.234-.219.484-.344.812-.344.187 0 .375.047.578.047.422 0 .5.125.656.531.109.266.906 2.391.906 2.547 0 .594-1.078 1.266-1.078 1.625 0 .078.031.156.078.234.344.734 1 1.578 1.594 2.141.719.688 1.484 1.141 2.359 1.578a.681.681 0 00.344.109c.469 0 1.25-1.516 1.656-1.516zM12.219 23.5c5.406 0 9.812-4.406 9.812-9.812s-4.406-9.812-9.812-9.812-9.812 4.406-9.812 9.812c0 2.063.656 4.078 1.875 5.75l-1.234 3.641 3.781-1.203a9.875 9.875 0 005.391 1.625zm0-21.594C18.719 1.906 24 7.187 24 13.687s-5.281 11.781-11.781 11.781c-1.984 0-3.953-.5-5.703-1.469L0 26.093l2.125-6.328a11.728 11.728 0 01-1.687-6.078c0-6.5 5.281-11.781 11.781-11.781z'></path></svg>\",\"vk\":\"<svg aria-labelledby='vk' xmlns='http://www.w3.org/2000/svg' width='31' height='28' viewBox='0 0 31 28'><title>vk</title><path d='M29.953 8.125c.234.641-.5 2.141-2.344 4.594-3.031 4.031-3.359 3.656-.859 5.984 2.406 2.234 2.906 3.313 2.984 3.453 0 0 1 1.75-1.109 1.766l-4 .063c-.859.172-2-.609-2-.609-1.5-1.031-2.906-3.703-4-3.359 0 0-1.125.359-1.094 2.766.016.516-.234.797-.234.797s-.281.297-.828.344h-1.797c-3.953.25-7.438-3.391-7.438-3.391S3.421 16.595.078 8.736c-.219-.516.016-.766.016-.766s.234-.297.891-.297l4.281-.031c.406.063.688.281.688.281s.25.172.375.5c.703 1.75 1.609 3.344 1.609 3.344 1.563 3.219 2.625 3.766 3.234 3.437 0 0 .797-.484.625-4.375-.063-1.406-.453-2.047-.453-2.047-.359-.484-1.031-.625-1.328-.672-.234-.031.156-.594.672-.844.766-.375 2.125-.391 3.734-.375 1.266.016 1.625.094 2.109.203 1.484.359.984 1.734.984 5.047 0 1.062-.203 2.547.562 3.031.328.219 1.141.031 3.141-3.375 0 0 .938-1.625 1.672-3.516.125-.344.391-.484.391-.484s.25-.141.594-.094l4.5-.031c1.359-.172 1.578.453 1.578.453z'></path></svg>\",\"tumblr\":\"<svg aria-labelledby='tumblr' xmlns='http://www.w3.org/2000/svg' width='17' height='28' viewBox='0 0 17 28'><title>tumblr</title><path d='M14.75 20.766L16 24.469c-.469.703-2.594 1.5-4.5 1.531-5.672.094-7.812-4.031-7.812-6.937v-8.5H1.063V7.204C5.001 5.782 5.954 2.22 6.172.188c.016-.125.125-.187.187-.187h3.813v6.625h5.203v3.937h-5.219v8.094c0 1.094.406 2.609 2.5 2.562.688-.016 1.609-.219 2.094-.453z'></path></svg>\",\"reddit\":\"<svg aria-labelledby='reddit' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>reddit</title><path d='M14.672 17.641a.293.293 0 010 .406c-.766.766-2.234.828-2.672.828s-1.906-.063-2.672-.828a.293.293 0 010-.406.267.267 0 01.406 0c.484.484 1.531.656 2.266.656s1.781-.172 2.266-.656a.267.267 0 01.406 0zm-4.109-2.438c0 .656-.547 1.203-1.203 1.203s-1.203-.547-1.203-1.203a1.203 1.203 0 012.406 0zm5.281 0c0 .656-.547 1.203-1.203 1.203s-1.203-.547-1.203-1.203a1.203 1.203 0 012.406 0zm3.359-1.609c0-.875-.719-1.594-1.609-1.594a1.62 1.62 0 00-1.141.484c-1.094-.75-2.562-1.234-4.172-1.281l.844-3.797 2.672.609c.016.656.547 1.188 1.203 1.188S18.203 8.656 18.203 8 17.656 6.797 17 6.797a1.2 1.2 0 00-1.078.672l-2.953-.656c-.156-.047-.297.063-.328.203l-.938 4.188c-1.609.063-3.063.547-4.141 1.297a1.603 1.603 0 00-2.765 1.094c0 .641.375 1.188.906 1.453-.047.234-.078.5-.078.75 0 2.547 2.859 4.609 6.391 4.609s6.406-2.063 6.406-4.609a3.09 3.09 0 00-.094-.766c.516-.266.875-.812.875-1.437zM24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-15A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15C21.984 2 24 4.016 24 6.5z'></path></svg>\",\"patreon\":\"<svg aria-labelledby='patreon' xmlns='http://www.w3.org/2000/svg' width='33' height='32' viewBox='0 0 33 32'><title>patreon</title><path d='M21.37.033c-6.617 0-12.001 5.383-12.001 11.999 0 6.597 5.383 11.963 12.001 11.963 6.597 0 11.963-5.367 11.963-11.963C33.333 5.415 27.966.033 21.37.033zM.004 31.996h5.859V.033H.004z'></path></svg>\",\"medium\":\"<svg aria-labelledby='medium' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'><title>medium</title><path d='M0 0v32h32V0zm26.584 7.581l-1.716 1.645a.5.5 0 00-.191.486v-.003 12.089a.502.502 0 00.189.481l.001.001 1.676 1.645v.361h-8.429v-.36l1.736-1.687c.171-.171.171-.22.171-.48v-9.773l-4.827 12.26h-.653L8.92 11.986v8.217a1.132 1.132 0 00.311.943l2.259 2.739v.361H5.087v-.36l2.26-2.74a1.09 1.09 0 00.289-.949l.001.007v-9.501a.83.83 0 00-.27-.702L7.366 10 5.358 7.581v-.36h6.232l4.817 10.564L20.642 7.22h5.941z'></path></svg>\",\"behance\":\"<svg aria-labelledby='behance' xmlns='http://www.w3.org/2000/svg' width='32' height='28' viewBox='0 0 32 28'><title>Behance</title>\\t<path d='M28.875 5.297h-7.984v1.937h7.984V5.297zm-3.937 6.656c-1.875 0-3.125 1.172-3.25 3.047h6.375c-.172-1.891-1.156-3.047-3.125-3.047zm.25 9.141c1.188 0 2.719-.641 3.094-1.859h3.453c-1.062 3.266-3.266 4.797-6.672 4.797-4.5 0-7.297-3.047-7.297-7.484 0-4.281 2.953-7.547 7.297-7.547 4.469 0 6.937 3.516 6.937 7.734 0 .25-.016.5-.031.734H21.688c0 2.281 1.203 3.625 3.5 3.625zm-20.86-.782h4.625c1.766 0 3.203-.625 3.203-2.609 0-2.016-1.203-2.812-3.109-2.812H4.328v5.422zm0-8.39h4.391c1.547 0 2.641-.672 2.641-2.344 0-1.813-1.406-2.25-2.969-2.25H4.329v4.594zM0 3.969h9.281c3.375 0 6.297.953 6.297 4.875 0 1.984-.922 3.266-2.688 4.109 2.422.688 3.594 2.516 3.594 4.984 0 4-3.359 5.719-6.937 5.719H0V3.968z'></path></svg>\",\"email\":\"<svg aria-labelledby='email' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'> <title id='email'> Email </title> <path d='M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z'/></svg>\",\"phone\":\"<svg aria-labelledby='phone' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 512 512'><title id='phone'> Phone </title><path d='M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z'/></svg>\",\"google_reviews\":\"<svg aria-labelledby='google_reviews' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title id='google_reviews'> Google Reviews </title><path d='M12 12.281h11.328c.109.609.187 1.203.187 2C23.515 21.125 18.921 26 11.999 26c-6.641 0-12-5.359-12-12s5.359-12 12-12c3.234 0 5.953 1.188 8.047 3.141L16.78 8.282c-.891-.859-2.453-1.859-4.781-1.859-4.094 0-7.438 3.391-7.438 7.578s3.344 7.578 7.438 7.578c4.75 0 6.531-3.406 6.813-5.172h-6.813v-4.125z'></path></svg>\",\"telegram\":\"<svg aria-labelledby='telegram' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'><title>telegram</title><path d='M26.07 3.996a2.987 2.987 0 00-.952.23l.019-.007h-.004c-.285.113-1.64.683-3.7 1.547l-7.382 3.109c-5.297 2.23-10.504 4.426-10.504 4.426l.062-.024s-.359.118-.734.375c-.234.15-.429.339-.582.56l-.004.007c-.184.27-.332.683-.277 1.11.09.722.558 1.155.894 1.394.34.242.664.355.664.355h.008l4.883 1.645c.219.703 1.488 4.875 1.793 5.836.18.574.355.933.574 1.207.106.14.23.257.379.351.071.042.152.078.238.104l.008.002-.05-.012c.015.004.027.016.038.02.04.011.067.015.118.023.773.234 1.394-.246 1.394-.246l.035-.028 2.883-2.625 4.832 3.707.11.047c1.007.442 2.027.196 2.566-.238.543-.437.754-.996.754-.996l.035-.09 3.734-19.129c.106-.472.133-.914.016-1.343a1.818 1.818 0 00-.774-1.043l-.007-.004a1.852 1.852 0 00-1.071-.269h.005zm-.101 2.05c-.004.063.008.056-.02.177v.011l-3.699 18.93c-.016.027-.043.086-.117.145-.078.062-.14.101-.465-.028l-5.91-4.531-3.57 3.254.75-4.79 9.656-9c.398-.37.265-.448.265-.448.028-.454-.601-.133-.601-.133l-12.176 7.543-.004-.02-5.851-1.972a.237.237 0 00.032-.013l-.002.001.032-.016.031-.011s5.211-2.196 10.508-4.426c2.652-1.117 5.324-2.242 7.379-3.11a807.312 807.312 0 013.66-1.53c.082-.032.043-.032.102-.032z'></path></svg>\",\"trip_advisor\":\"<svg aria-labelledby='trip_advisor' xmlns='http://www.w3.org/2000/svg' width='36' height='28' viewBox='0 0 36 28'> <title id='trip_advisor'> Trip Advisor </title> <path d='M10.172 15.578c0 0.812-0.656 1.469-1.453 1.469-0.812 0-1.469-0.656-1.469-1.469 0-0.797 0.656-1.453 1.469-1.453 0.797 0 1.453 0.656 1.453 1.453zM28.203 15.563c0 0.812-0.656 1.469-1.469 1.469s-1.469-0.656-1.469-1.469 0.656-1.453 1.469-1.453 1.469 0.641 1.469 1.453zM11.953 15.578c0-1.656-1.359-3.016-3.016-3.016-1.672 0-3.016 1.359-3.016 3.016 0 1.672 1.344 3.016 3.016 3.016 1.656 0 3.016-1.344 3.016-3.016zM29.969 15.563c0-1.656-1.344-3.016-3.016-3.016-1.656 0-3.016 1.359-3.016 3.016 0 1.672 1.359 3.016 3.016 3.016 1.672 0 3.016-1.344 3.016-3.016zM13.281 15.578c0 2.406-1.937 4.359-4.344 4.359s-4.359-1.953-4.359-4.359c0-2.391 1.953-4.344 4.359-4.344s4.344 1.953 4.344 4.344zM31.313 15.563c0 2.406-1.953 4.344-4.359 4.344-2.391 0-4.344-1.937-4.344-4.344s1.953-4.344 4.344-4.344c2.406 0 4.359 1.937 4.359 4.344zM16.25 15.609c0-3.984-3.234-7.219-7.219-7.219-3.969 0-7.203 3.234-7.203 7.219s3.234 7.219 7.203 7.219c3.984 0 7.219-3.234 7.219-7.219zM26.688 6.656c-2.578-1.125-5.484-1.734-8.687-1.734s-6.391 0.609-8.953 1.719c4.953 0.016 8.953 4.016 8.953 8.969 0-4.859 3.859-8.813 8.687-8.953zM34.172 15.609c0-3.984-3.219-7.219-7.203-7.219s-7.219 3.234-7.219 7.219 3.234 7.219 7.219 7.219 7.203-3.234 7.203-7.219zM30.016 6.766h5.984c-0.938 1.094-1.625 2.562-1.797 3.578 1.078 1.484 1.719 3.297 1.719 5.266 0 4.953-4.016 8.953-8.953 8.953-2.812 0-5.313-1.281-6.953-3.297 0 0-0.734 0.875-2.016 2.797-0.219-0.453-1.328-2.031-2-2.812-1.641 2.031-4.156 3.313-6.969 3.313-4.937 0-8.953-4-8.953-8.953 0-1.969 0.641-3.781 1.719-5.266-0.172-1.016-0.859-2.484-1.797-3.578h5.703c3.063-2.047 7.516-3.328 12.297-3.328s8.953 1.281 12.016 3.328z'></path></svg>\",\"yelp\":\"<svg aria-labelledby='yelp' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><path d='M12.078 20.609v1.984c-.016 4.406-.016 4.562-.094 4.766-.125.328-.406.547-.797.625-1.125.187-4.641-1.109-5.375-1.984a1.107 1.107 0 01-.266-.562.882.882 0 01.063-.406c.078-.219.219-.391 3.359-4.109 0 0 .016 0 .938-1.094.313-.391.875-.516 1.391-.328.516.203.797.641.781 1.109zM9.75 16.688c-.031.547-.344.953-.812 1.094l-1.875.609c-4.203 1.344-4.344 1.375-4.562 1.375-.344-.016-.656-.219-.844-.562-.125-.25-.219-.672-.266-1.172-.172-1.531.031-3.828.484-4.547.219-.344.531-.516.875-.5.234 0 .422.094 4.953 1.937 0 0-.016.016 1.313.531.469.187.766.672.734 1.234zm12.906 4.64c-.156 1.125-2.484 4.078-3.547 4.5-.359.141-.719.109-.984-.109-.187-.141-.375-.422-2.875-4.484l-.734-1.203c-.281-.438-.234-1 .125-1.437.344-.422.844-.562 1.297-.406 0 0 .016.016 1.859.625 4.203 1.375 4.344 1.422 4.516 1.563.281.219.406.547.344.953zm-10.5-9.875c.078 1.625-.609 1.828-.844 1.906-.219.063-.906.266-1.781-1.109-5.75-9.078-5.906-9.344-5.906-9.344-.078-.328.016-.688.297-.969.859-.891 5.531-2.203 6.75-1.891.391.094.672.344.766.703.063.391.625 8.813.719 10.703zM22.5 13.141c.031.391-.109.719-.406.922-.187.125-.375.187-5.141 1.344-.766.172-1.188.281-1.422.359l.016-.031c-.469.125-1-.094-1.297-.562s-.281-.984 0-1.359c0 0 .016-.016 1.172-1.594 2.562-3.5 2.688-3.672 2.875-3.797.297-.203.656-.203 1.016-.031 1.016.484 3.063 3.531 3.187 4.703v.047z'></path></svg>\",\"pinterest\":\"<svg aria-labelledby='pinterest' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>pinterest</title><path d='M19.5 2C21.984 2 24 4.016 24 6.5v15c0 2.484-2.016 4.5-4.5 4.5H8.172c.516-.734 1.359-2 1.687-3.281 0 0 .141-.531.828-3.266.422.797 1.625 1.484 2.906 1.484 3.813 0 6.406-3.484 6.406-8.141 0-3.516-2.984-6.797-7.516-6.797-5.641 0-8.484 4.047-8.484 7.422 0 2.031.781 3.844 2.438 4.531.266.109.516 0 .594-.297.047-.203.172-.734.234-.953.078-.297.047-.406-.172-.656-.469-.578-.781-1.297-.781-2.344 0-3 2.25-5.672 5.844-5.672 3.187 0 4.937 1.937 4.937 4.547 0 3.422-1.516 6.312-3.766 6.312-1.234 0-2.172-1.031-1.875-2.297.359-1.5 1.047-3.125 1.047-4.203 0-.969-.516-1.781-1.594-1.781-1.266 0-2.281 1.313-2.281 3.063 0 0 0 1.125.375 1.891-1.297 5.5-1.531 6.469-1.531 6.469-.344 1.437-.203 3.109-.109 3.969H4.5A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15z'></path></svg>\",\"linkedin\":\"<svg aria-labelledby='linkedin' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>linkedin</title><path d='M3.703 22.094h3.609V11.25H3.703v10.844zM7.547 7.906c-.016-1.062-.781-1.875-2.016-1.875s-2.047.812-2.047 1.875c0 1.031.781 1.875 2 1.875H5.5c1.266 0 2.047-.844 2.047-1.875zm9.141 14.188h3.609v-6.219c0-3.328-1.781-4.875-4.156-4.875-1.937 0-2.797 1.078-3.266 1.828h.031V11.25H9.297s.047 1.016 0 10.844h3.609v-6.062c0-.313.016-.641.109-.875.266-.641.859-1.313 1.859-1.313 1.297 0 1.813.984 1.813 2.453v5.797zM24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-15A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15C21.984 2 24 4.016 24 6.5z'></path></svg>\",\"imdb\":\"<svg aria-labelledby='imdb' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>imdb</title><path d='M14.406 12.453v2.844c0 .562.109 1.078-.594 1.062v-4.828c.688 0 .594.359.594.922zm4.938 1.5v1.891c0 .313.094.828-.359.828a.236.236 0 01-.219-.141c-.125-.297-.063-2.547-.063-2.578 0-.219-.063-.734.281-.734.422 0 .359.422.359.734zM2.812 17.641h1.906v-7.375H2.812v7.375zm6.782 0h1.656v-7.375H8.766l-.438 3.453a123.605 123.605 0 00-.5-3.453H5.359v7.375h1.672v-4.875l.703 4.875h1.188l.672-4.984v4.984zm6.64-4.766c0-.469.016-.969-.078-1.406-.25-1.297-1.813-1.203-2.828-1.203h-1.422v7.375c4.969 0 4.328.344 4.328-4.766zm4.953 3.078v-2.078c0-1-.047-1.734-1.281-1.734-.516 0-.859.156-1.203.531v-2.406h-1.828v7.375h1.719l.109-.469c.328.391.688.562 1.203.562 1.141 0 1.281-.875 1.281-1.781zM24 4.5v19c0 1.375-1.125 2.5-2.5 2.5h-19A2.507 2.507 0 010 23.5v-19C0 3.125 1.125 2 2.5 2h19C22.875 2 24 3.125 24 4.5z'></path></svg>\",\"header-main-layout-1\":\"<svg aria-labelledby='header-main-layout-1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><g><path fill='#0085BA' d='M116.701,80.797H3.799c-1.958,0-3.549-1.593-3.549-3.55V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.957,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.658,80.797,116.701,80.797z M3.799,1.979 c-0.979,0-1.775,0.795-1.775,1.774v73.494c0,0.979,0.796,1.774,1.775,1.774h112.902c0.979,0,1.773-0.795,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><path fill='#0085BA' d='M107.275,16.6H48.385c-0.98,0-1.774-0.794-1.774-1.774s0.794-1.774,1.774-1.774h58.891 c0.979,0,1.773,0.794,1.773,1.774S108.254,16.6,107.275,16.6z'/></g><g><path fill='#0085BA' d='M34.511,16.689c0,0.989-0.929,1.789-2.074,1.789H16.116c-1.146,0-2.075-0.8-2.075-1.789v-3.727 c0-0.989,0.929-1.79,2.075-1.79h16.321c1.146,0,2.074,0.801,2.074,1.79V16.689z'/></g></g></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='0.25' y1='28.342' x2='119.535' y2='28.342'/></svg>\",\"header-main-layout-2\":\"<svg aria-labelledby='header-main-layout-2' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='0.607' y1='28.341' x2='119.893' y2='28.341'/><g><path fill='#0085BA' d='M116.701,80.795H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.752c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.955,0,3.549,1.592,3.549,3.549v73.494C120.25,79.203,118.656,80.795,116.701,80.795z M3.799,1.978 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.98,0.795,1.775,1.773,1.775h112.902c0.979,0,1.773-0.797,1.773-1.775V3.752 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><path fill='#0085BA' d='M69.314,12.413c0,1.014-0.822,1.837-1.836,1.837H53.021c-1.015,0-1.837-0.823-1.837-1.837V8.586 c0-1.015,0.822-1.837,1.837-1.837h14.458c1.014,0,1.836,0.822,1.836,1.837V12.413z'/></g></g><g><path fill='#0085BA' d='M99.697,22.067H20.804c-0.98,0-1.774-0.672-1.774-1.5c0-0.828,0.794-1.5,1.774-1.5h78.894 c0.979,0,1.772,0.672,1.772,1.5C101.471,21.395,100.676,22.067,99.697,22.067z'/></g></svg>\",\"header-main-layout-3\":\"<svg aria-labelledby='header-main-layout-3' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><g><path fill='#0085BA' d='M0.25,77.247V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902c1.957,0,3.549,1.592,3.549,3.549v73.494 c0,1.957-1.592,3.55-3.549,3.55H3.799C1.842,80.797,0.25,79.204,0.25,77.247z M3.799,1.979c-0.979,0-1.774,0.795-1.774,1.774 v73.494c0,0.979,0.796,1.774,1.774,1.774h112.902c0.979,0,1.773-0.795,1.773-1.774V3.753c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><path fill='#0085BA' d='M13.225,16.6h58.891c0.979,0,1.774-0.794,1.774-1.774s-0.795-1.774-1.774-1.774H13.225 c-0.979,0-1.773,0.794-1.773,1.774C11.451,15.806,12.246,16.6,13.225,16.6z'/></g><g><path fill='#0085BA' d='M85.988,16.689c0,0.989,0.93,1.789,2.074,1.789h16.321c1.146,0,2.074-0.8,2.074-1.789v-3.727 c0-0.989-0.929-1.79-2.074-1.79H88.063c-1.145,0-2.073,0.801-2.073,1.79L85.988,16.689L85.988,16.689z'/></g></g></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='120.25' y1='28.342' x2='0.965' y2='28.342'/></svg>\",\"menu-inline\":\"<svg aria-labelledby='menu-inline' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='60.5px' height='81px' viewBox='0 0 60.5 81' enable-background='new 0 0 60.5 81' xml:space='preserve'><g><g><g><path fill='#0085BA' d='M51.602,12.975H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,12.546,52.098,12.975,51.602,12.975z'/></g></g><g><g><path fill='#0085BA' d='M51.602,17.205H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,16.775,52.098,17.205,51.602,17.205z'/></g></g><g><g><path fill='#0085BA' d='M51.602,21.435H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,21.004,52.098,21.435,51.602,21.435z'/></g></g></g><g><path fill='#0085BA' d='M25.504,20.933c0,1.161-0.794,2.099-1.773,2.099H9.777c-0.979,0-1.773-0.938-1.773-2.099V11.56 c0-1.16,0.795-2.1,1.773-2.1H23.73c0.979,0,1.772,0.94,1.772,2.1L25.504,20.933L25.504,20.933z'/></g><g><path fill='#0085BA' d='M56.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h52.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C60.25,79.204,58.657,80.796,56.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.774,1.773,1.774h52.902c0.979,0,1.773-0.797,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g></svg>\",\"menu-stack\":\"<svg aria-labelledby='menu-stack' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='60.5px' height='81px' viewBox='0 0 60.5 81' enable-background='new 0 0 60.5 81' xml:space='preserve'><g><path fill='#0085BA' d='M56.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h52.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C60.25,79.204,58.657,80.796,56.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.774,1.773,1.774h52.902c0.979,0,1.773-0.797,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><g><path fill='#0085BA' d='M35.607,29.821H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,29.392,36.104,29.821,35.607,29.821z'/></g></g><g><g><path fill='#0085BA' d='M35.607,34.051H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,33.621,36.104,34.051,35.607,34.051z'/></g></g><g><g><path fill='#0085BA' d='M35.607,38.281H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,37.85,36.104,38.281,35.607,38.281z'/></g></g></g><g><path fill='#0085BA' d='M39,20.933c0,1.161-0.794,2.099-1.773,2.099H23.273c-0.979,0-1.773-0.938-1.773-2.099V11.56 c0-1.16,0.795-2.1,1.773-2.1h13.954c0.979,0,1.771,0.94,1.771,2.1L39,20.933L39,20.933z'/></g></svg>\",\"disabled\":\"<svg aria-labelledby='footer-layout-disabled' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'> <g> <g> <path fill='#0085BA' d='M116.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.657,80.796,116.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.772,1.773,1.772h112.902c0.979,0,1.773-0.797,1.773-1.772V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/> </g> </g> <path fill='#0085BA' d='M60.25,19.5c-11.581,0-21,9.419-21,21c0,11.578,9.419,21,21,21c11.578,0,21-9.422,21-21 C81.25,28.919,71.828,19.5,60.25,19.5z M42.308,40.5c0-9.892,8.05-17.942,17.942-17.942c4.412,0,8.452,1.6,11.578,4.249 L46.557,52.078C43.908,48.952,42.308,44.912,42.308,40.5z M60.25,58.439c-4.385,0-8.407-1.579-11.526-4.201l25.265-25.265 c2.622,3.12,4.201,7.141,4.201,11.526C78.189,50.392,70.142,58.439,60.25,58.439z'/> </svg>\",\"footer-layout-1\":\"<svg aria-labelledby='footer-layout-1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><path fill='#0085BA' d='M3.799,0.204h112.902c1.958,0,3.549,1.593,3.549,3.55v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799 c-1.957,0-3.549-1.592-3.549-3.549V3.754C0.25,1.797,1.842,0.204,3.799,0.204z M116.701,79.021c0.979,0,1.774-0.795,1.774-1.773 l0.001-73.494c0-0.979-0.797-1.774-1.775-1.774H3.799c-0.979,0-1.773,0.795-1.773,1.774v73.494c0,0.979,0.795,1.773,1.773,1.773 H116.701z'/></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='120.25' y1='58.659' x2='0.965' y2='58.659'/><g><g><path fill='#0085BA' d='M26.805,64.475h66.89c0.98,0,1.774,0.628,1.774,1.4s-0.794,1.4-1.774,1.4h-66.89 c-0.98,0-1.773-0.628-1.773-1.4C25.031,65.102,25.826,64.475,26.805,64.475z'/></g></g><g><ellipse fill='#0085BA' cx='72.604' cy='72.174' rx='2.146' ry='2.108'/><ellipse fill='#0085BA' cx='64.37' cy='72.174' rx='2.147' ry='2.108'/><ellipse fill='#0085BA' cx='56.132' cy='72.174' rx='2.145' ry='2.108'/><ellipse fill='#0085BA' cx='47.896' cy='72.174' rx='2.146' ry='2.108'/></g></svg>\",\"footer-layout-2\":\"<svg aria-labelledby='footer-layout-2' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><path fill='#0085BA' d='M120.25,3.754v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.754 c0-1.957,1.591-3.55,3.549-3.55h112.902C118.658,0.204,120.25,1.797,120.25,3.754z M116.701,79.021 c0.979,0,1.773-0.795,1.773-1.773V3.754c0-0.979-0.795-1.774-1.773-1.774H3.799c-0.979,0-1.775,0.795-1.775,1.774l0.001,73.494 c0,0.979,0.796,1.773,1.774,1.773H116.701z'/></g><g><g><path fill='#0085BA' d='M120.25,3.754v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.754 c0-1.957,1.591-3.55,3.549-3.55h112.902C118.658,0.204,120.25,1.797,120.25,3.754z M116.701,79.021 c0.979,0,1.773-0.795,1.773-1.773V3.754c0-0.979-0.795-1.774-1.773-1.774H3.799c-0.979,0-1.775,0.795-1.775,1.774l0.001,73.494 c0,0.979,0.796,1.773,1.774,1.773H116.701z'/></g></g><g><g><g><path fill='#0085BA' d='M63.184,69.175c0,0.979-0.793,1.774-1.773,1.774h-46.89c-0.98,0-1.774-0.795-1.774-1.774 S13.54,67.4,14.521,67.4h46.89C62.389,67.4,63.184,68.194,63.184,69.175z'/></g></g><g><ellipse fill='#0085BA' cx='79.872' cy='69.175' rx='2.228' ry='2.188'/><ellipse fill='#0085BA' cx='88.422' cy='69.175' rx='2.229' ry='2.188'/><ellipse fill='#0085BA' cx='96.974' cy='69.175' rx='2.227' ry='2.188'/><ellipse fill='#0085BA' cx='105.525' cy='69.175' rx='2.229' ry='2.188'/></g></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='120.25' y1='58.659' x2='0.965' y2='58.659'/></svg>\",\"footer-layout-4\":\"<svg aria-labelledby='footer-layout-4' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><g><g><g><path fill='#0085BA' d='M116.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.657,80.796,116.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.772,1.773,1.772h112.902c0.979,0,1.773-0.797,1.773-1.772V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g></g></g></g><g><path fill='#0085BA' d='M28.064,70c0,1.657-1.354,3-3.023,3H12.458c-1.669,0-3.023-1.343-3.023-3V58.25c0-1.656,1.354-3,3.023-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><path fill='#0085BA' d='M55.731,70c0,1.657-1.354,3-3.023,3H40.125c-1.669,0-3.023-1.343-3.023-3V58.25c0-1.656,1.354-3,3.023-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><path fill='#0085BA' d='M83.397,70c0,1.657-1.354,3-3.023,3H67.791c-1.669,0-3.022-1.343-3.022-3V58.25c0-1.656,1.354-3,3.022-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><path fill='#0085BA' d='M111.064,70c0,1.657-1.354,3-3.023,3H95.458c-1.669,0-3.022-1.343-3.022-3V58.25c0-1.656,1.354-3,3.022-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><rect x='0.607' y='48' fill='#0085BA' width='119.285' height='1'/></g></svg>\",\"menu\":\"<svg aria-labelledby='menu' class='ast-mobile-svg ast-menu-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M3 13h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 7h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 19h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1z'></path></svg>\",\"menu2\":\"<svg aria-labelledby='menu2' class='ast-mobile-svg ast-menu2-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><path d='M24 21v2c0 0.547-0.453 1-1 1h-22c-0.547 0-1-0.453-1-1v-2c0-0.547 0.453-1 1-1h22c0.547 0 1 0.453 1 1zM24 13v2c0 0.547-0.453 1-1 1h-22c-0.547 0-1-0.453-1-1v-2c0-0.547 0.453-1 1-1h22c0.547 0 1 0.453 1 1zM24 5v2c0 0.547-0.453 1-1 1h-22c-0.547 0-1-0.453-1-1v-2c0-0.547 0.453-1 1-1h22c0.547 0 1 0.453 1 1z'></path></svg>\",\"menu3\":\"<svg aria-labelledby='menu3' class='ast-mobile-svg ast-menu3-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'><path d='M6 3c0-1.105 0.895-2 2-2s2 0.895 2 2c0 1.105-0.895 2-2 2s-2-0.895-2-2zM6 8c0-1.105 0.895-2 2-2s2 0.895 2 2c0 1.105-0.895 2-2 2s-2-0.895-2-2zM6 13c0-1.105 0.895-2 2-2s2 0.895 2 2c0 1.105-0.895 2-2 2s-2-0.895-2-2z'></path></svg>\",\"close\":\"<svg aria-labelledby='close' class='ast-mobile-svg ast-close-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M5.293 6.707l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0l5.293-5.293 5.293 5.293c0.391 0.391 1.024 0.391 1.414 0s0.391-1.024 0-1.414l-5.293-5.293 5.293-5.293c0.391-0.391 0.391-1.024 0-1.414s-1.024-0.391-1.414 0l-5.293 5.293-5.293-5.293c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414z'></path></svg>\",\"edit\":\"<svg aria-labelledby='edit' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><path d='M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z'></path></svg>\",\"drag\":\"<svg width='18' height='18' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 18 18'><path d='M13,8c0.6,0,1-0.4,1-1s-0.4-1-1-1s-1,0.4-1,1S12.4,8,13,8z M5,6C4.4,6,4,6.4,4,7s0.4,1,1,1s1-0.4,1-1S5.6,6,5,6z M5,10 c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S5.6,10,5,10z M13,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S13.6,10,13,10z M9,6 C8.4,6,8,6.4,8,7s0.4,1,1,1s1-0.4,1-1S9.6,6,9,6z M9,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S9.6,10,9,10z'/>/svg>\",\"mobile_menu\":\"<svg x='0px' y='0px' viewBox='0 0 84.2 81'><path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h76.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h76.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/><g><path className='st0' d='M14.8,28.4c0-1.2,0.8-2.1,1.8-2.1h50.9c1,0,1.8,0.9,1.8,2.1v-0.6c0,1.2-0.8,2.1-1.8,2.1H16.6c-1,0-1.8-0.9-1.8-2.1V28.4L14.8,28.4z'/></g><g><path className='st0' d='M14.8,40.8c0-1.2,0.8-2.1,1.8-2.1h50.9c1,0,1.8,0.9,1.8,2.1v-0.6c0,1.2-0.8,2.1-1.8,2.1H16.6c-1,0-1.8-0.9-1.8-2.1V40.8L14.8,40.8z'/></g><g><path className='st0' d='M14.8,53.2c0-1.2,0.8-2.1,1.8-2.1h50.9c1,0,1.8,0.9,1.8,2.1v-0.6c0,1.2-0.8,2.1-1.8,2.1H16.6c-1,0-1.8-0.9-1.8-2.1V53.2L14.8,53.2z'/></g></svg>\",\"mobile_menu2\":\"<svg x='0px' y='0px' viewBox='0 0 84.2 81'> <path className='st0' d='M0.1,77.2V3.8c0-2,1.6-3.5,3.5-3.5h76.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.7 C1.7,80.8,0.2,79.2,0.1,77.2z M3.7,2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h76.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.7z' /> <g> <g> <path className='st0' d='M14.7,56c0-1.2,0.8-2.1,1.8-2.1h50.8c1,0,1.8,0.9,1.8,2.1v3.5c0,1.2-0.8,2.1-1.8,2.1H16.5 c-1,0-1.8-0.9-1.8-2.1L14.7,56L14.7,56z' /> </g> <g> <path className='st0' d='M14.7,38.5c0-1.2,0.8-2.1,1.8-2.1h50.8c1,0,1.8,0.9,1.8,2.1V42c0,1.2-0.8,2.1-1.8,2.1H16.5 c-1,0-1.8-0.9-1.8-2.1L14.7,38.5L14.7,38.5z' /> </g> <g> <path className='st0' d='M14.7,21.1c0-1.2,0.8-2.1,1.8-2.1h50.8c1,0,1.8,0.9,1.8,2.1v3.5c0,1.2-0.8,2.1-1.8,2.1H16.5 c-1,0-1.8-0.9-1.8-2.1L14.7,21.1L14.7,21.1z' /> </g> </g> </svg>\",\"mobile_menu3\":\"<svg x='0px' y='0px' viewBox='0 0 84.2 81'> <g> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h76.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h76.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z' /> </g> <circle className='st0' cx='42.1' cy='21.5' r='6.4' /> <circle className='st0' cx='42.1' cy='40.5' r='6.4' /> <circle className='st0' cx='42.1' cy='59.5' r='6.4' /> </svg>\",\"twocol\":\"<svg viewBox='0 0 120.5 81' xmlns='http://www.w3.org/2000/svg' x='0px' y='0px'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,1.9C2.8,1.9,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z' /> <path className='st0' d='M54.7,55.3H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h42.2c1.7,0,3-1.3,3-3V58.3 C57.7,56.6,56.4,55.3,54.7,55.3z' /> <path className='st0' d='M108,55.3H65.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h42.3c1.7,0,3-1.3,3-3V58.3 C111.1,56.6,109.7,55.3,108,55.3z' /> <rect x='0.6' y='47.9' className='st0' width='119.3' height='1' /> </svg>\",\"twoleftgolden\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z' /> <path className='st0' d='M81.7,55.4H45h-4.9H12.4c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h36.7c1.7,0,3-1.3,3-3V58.4 C84.8,56.6,83.4,55.4,81.7,55.4z' /> <path className='st0' d='M108.1,55.4H93.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h14.3c1.7,0,3-1.3,3-3V58.4 C111.1,56.6,109.7,55.4,108.1,55.4z' /> <rect x='0.6' y='48' className='st0' width='119.3' height='1' /> </svg>\",\"tworightgolden\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.3V3.8c0-1.9,1.5-3.5,3.5-3.5h112.9c1.9,0,3.5,1.5,3.5,3.5v73.5c0,1.9-1.5,3.5-3.5,3.5H3.8 C1.8,80.8,0.2,79.1,0.3,77.3z M3.8,1.9C2.8,1.9,2,2.7,2,3.7v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.7 c0-1-0.8-1.8-1.8-1.8C116.7,1.9,3.8,1.9,3.8,1.9z' /> <path className='st0' d='M35.8,58.3V70c0,1.7,1.3,3,3,3h36.7h4.9h27.7c1.6,0,3-1.3,3-3V58.3c0-1.7-1.3-3-3-3H80.4h-4.9H38.8 C37.1,55.3,35.7,56.5,35.8,58.3z' /> <path className='st0' d='M9.4,58.3V70c0,1.7,1.3,3,3,3h14.3c1.6,0,3-1.3,3-3V58.3c0-1.7-1.3-3-3-3H12.4C10.8,55.3,9.4,56.5,9.4,58.3z' /> <rect x='0.6' y='47.9' className='st0' width='119.3' height='1' /> </svg>\",\"lefthalf\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.1,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M65.7,55.1H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h53.3c1.7,0,3-1.3,3-3V58.1 C68.7,56.5,67.4,55.1,65.7,55.1z'/> <path className='st0' d='M108,55.1H96.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3H108c1.7,0,3-1.3,3-3V58.1 C111.1,56.5,109.7,55.1,108,55.1z'/> <path className='st0' d='M87.1,55.1H75.9c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.1 C90.1,56.5,88.8,55.1,87.1,55.1z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"threecol\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.1,118.7,80.8,116.7,80.8z M3.8,1.9C2.8,1.9,2,2.6,2,3.6v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.6 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M37.7,55.1H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h25.3c1.7,0,3-1.3,3-3V58.1 C40.7,56.6,39.4,55.1,37.7,55.1z'/> <path className='st0' d='M72.9,55.1H47.6c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h25.3c1.7,0,3-1.3,3-3V58.1 C75.9,56.6,74.5,55.1,72.9,55.1z'/> <path className='st0' d='M108,55.1H82.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3H108c1.7,0,3-1.3,3-3V58.1 C111.1,56.6,109.7,55.1,108,55.1z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"righthalf\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.2V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,80.8,0.4,79.2,0.3,77.2z M3.9,2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <path className='st0' d='M54.9,55.2h53.2c1.7,0,3,1.3,3,3V70c0,1.7-1.4,3-3,3H54.9c-1.7,0-3-1.3-3-3V58.2 C51.9,56.6,53.2,55.2,54.9,55.2z'/> <path className='st0' d='M12.6,55.2h11.3c1.7,0,3,1.3,3,3V70c0,1.7-1.4,3-3,3H12.6c-1.7,0-3-1.3-3-3V58.2C9.5,56.6,10.9,55.2,12.6,55.2 z'/> <path className='st0' d='M33.5,55.2h11.3c1.7,0,3,1.3,3,3V70c0,1.7-1.4,3-3,3H33.5c-1.7,0-3-1.3-3-3V58.2 C30.5,56.6,31.8,55.2,33.5,55.2z'/> <rect x='0.7' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"centerhalf\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.3V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,80.8,0.4,79.1,0.3,77.3z M3.9,1.9c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <path className='st0' d='M36.7,55.2H84c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H36.7c-1.7,0-3-1.3-3-3V58.2 C33.7,56.5,35.1,55.2,36.7,55.2z'/> <path className='st0' d='M12.6,55.2h14.3c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H12.6c-1.7,0-3-1.3-3-3V58.2 C9.5,56.5,10.9,55.2,12.6,55.2z'/> <path className='st0' d='M93.9,55.2h14.2c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H93.9c-1.7,0-3-1.3-3-3V58.2 C90.9,56.5,92.2,55.2,93.9,55.2z'/> <rect x='0.7' y='47.9' className='st0' width='119.3' height='1'/> </svg> \",\"widecenter\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <path className='st0' d='M32.7,55.5H88c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H32.7c-1.7,0-3-1.3-3-3V58.5 C29.7,56.8,31.1,55.5,32.7,55.5z'/> <path className='st0' d='M12.6,55.5h10.3c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H12.6c-1.7,0-3-1.3-3-3V58.5 C9.5,56.8,10.9,55.5,12.6,55.5z'/> <path className='st0' d='M97.9,55.5h10.2c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H97.9c-1.7,0-3-1.3-3-3V58.5 C94.9,56.8,96.2,55.5,97.9,55.5z'/> <rect x='0.7' y='48.2' className='st0' width='119.3' height='1'/> </svg>\",\"sixcol\":\"<svg xmlns='http://www.w3.org/2000/svg' version='1.1' id='Layer_1' x='0px' y='0px' viewBox='0 0 120.5 81'><g> <g> <g> <g> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <g> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> </g> <g> <g> <g> <g> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <g> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> </g> <path className='st1' d='M-0.5,77.2V3.8c0-2,1.6-3.5,3.5-3.5H116c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.1 C1.1,80.8-0.4,79.2-0.5,77.2z M3.1,2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.1z'/> <rect x='-0.1' y='56' className='st1' width='119.3' height='1'/> <g> <path className='st1' d='M22.4,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L22.4,70.8z'/> </g> <g> <path className='st1' d='M40,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10c1,0,1.8,0.9,1.8,2.1 V70.8L40,70.8z'/> </g> <g> <path className='st1' d='M57.6,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L57.6,70.8z'/> </g> <g> <path className='st1' d='M75.2,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L75.2,70.8z'/> </g> <g> <path className='st1' d='M92.8,70.8c0,1.2-0.8,2.1-1.8,2.1H81c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10c1,0,1.8,0.9,1.8,2.1 V70.8L92.8,70.8z'/> </g> <g> <path className='st1' d='M110.4,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L110.4,70.8z'/> </g> </svg>\",\"fivecol\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-526.1H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-527.7,118.7-526.1,116.7-526.1z M3.8-604.9c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-551.6H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-550.3,54.4-551.6,52.7-551.6z'/> <path className='st0' d='M108-551.6h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-550.3,109.7-551.6,108-551.6z'/> <rect x='0.6' y='-558.9' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-526.1H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-527.7,118.7-526.1,116.7-526.1z M3.8-604.9c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-551.6H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-550.3,54.4-551.6,52.7-551.6z'/> <path className='st0' d='M108-551.6h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-550.3,109.7-551.6,108-551.6z'/> <rect x='0.6' y='-558.9' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M23.7,55.2H12.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C26.7,56.6,25.4,55.2,23.7,55.2z'/> <path className='st0' d='M44.7,55.2H33.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C47.7,56.6,46.4,55.2,44.7,55.2z'/> <path className='st0' d='M65.8,55.2H54.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C68.8,56.6,67.5,55.2,65.8,55.2z'/> <path className='st0' d='M86.8,55.2H75.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C89.8,56.6,88.5,55.2,86.8,55.2z'/> <path className='st0' d='M107.8,55.2H96.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2 C110.8,56.6,109.5,55.2,107.8,55.2z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg> \",\"rfourforty\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M0.3,77.3V3.8c0-1.9,1.5-3.5,3.5-3.5h112.9c1.9,0,3.5,1.5,3.5,3.5v73.5c0,1.9-1.5,3.5-3.5,3.5H3.8 C1.8,80.8,0.2,79.2,0.3,77.3z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M66.7,58.2V70c0,1.7,1.3,3,3,3H108c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3H69.8C68.1,55.2,66.8,56.6,66.7,58.2z '/> <path className='st0' d='M47.8,58.2V70c0,1.7,1.3,3,3,3h9.3c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3h-9.2C49.2,55.2,47.9,56.6,47.8,58.2z '/> <path className='st0' d='M28.5,58.2V70c0,1.7,1.3,3,3,3h9.3c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3h-9.2C29.9,55.2,28.6,56.6,28.5,58.2z '/> <path className='st0' d='M9.4,58.2V70c0,1.7,1.3,3,3,3h9.3c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3h-9.2C10.8,55.2,9.5,56.6,9.4,58.2z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg> \",\"lfourforty\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M50.7,55.2H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h38.3c1.7,0,3-1.3,3-3V58.2 C53.7,56.6,52.4,55.2,50.7,55.2z'/> <path className='st0' d='M69.6,55.2h-9.2c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h9.3c1.7,0,3-1.3,3-3V58.2 C72.6,56.6,71.3,55.2,69.6,55.2z'/> <path className='st0' d='M88.9,55.2h-9.2c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3H89c1.7,0,3-1.3,3-3V58.2 C91.9,56.6,90.6,55.2,88.9,55.2z'/> <path className='st0' d='M108,55.2h-9.2c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h9.3c1.7,0,3-1.3,3-3V58.2C111,56.6,109.7,55.2,108,55.2 z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"fourcol\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M29.7,55.2H12.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h17.3c1.7,0,3-1.3,3-3V58.2 C32.7,56.5,31.4,55.2,29.7,55.2z'/> <path className='st0' d='M55.8,55.2H38.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h17.3c1.7,0,3-1.3,3-3V58.2 C58.8,56.5,57.5,55.2,55.8,55.2z'/> <path className='st0' d='M81.9,55.2H64.7c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3H82c1.7,0,3-1.3,3-3V58.2C84.9,56.5,83.6,55.2,81.9,55.2z '/> <path className='st0' d='M108,55.2H90.8c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h17.3c1.7,0,3-1.3,3-3V58.2C111,56.5,109.7,55.2,108,55.2z '/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"collapserowsix\":\"<svg xmlns='http://www.w3.org/2000/svg' version='1.1' id='Layer_1' x='0px' y='0px' viewBox='0 0 120.5 81'>  <g> <g> <g> <g> <path className='st0' d='M116.7-616.8H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-618.4,118.7-616.8,116.7-616.8z M3.8-695.6c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-642.3H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-641,54.4-642.3,52.7-642.3z'/> <path className='st0' d='M108-642.3h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-641,109.7-642.3,108-642.3z'/> <g> <rect x='0.6' y='-649.6' className='st0' width='119.3' height='1'/> </g> <g> <g> <g> <g> <path className='st0' d='M116.7-616.8H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-618.4,118.7-616.8,116.7-616.8z M3.8-695.6c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-642.3H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-641,54.4-642.3,52.7-642.3z'/> <path className='st0' d='M108-642.3h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-641,109.7-642.3,108-642.3z'/> <g> <rect x='0.6' y='-649.6' className='st0' width='119.3' height='1'/> </g> <g> <g> <g> <g> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8C116.7,2,3.8,2,3.8,2z'/> </g> </g> </g> </g> <g> <rect x='0.6' y='20.7' className='st0' width='119.3' height='1'/> </g> <path className='st0' d='M103.8,72.2H16.6c-1,0-1.9-0.9-1.9-1.9l0,0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9l0,0 C105.7,71.4,104.9,72.2,103.8,72.2z'/> <path className='st0' d='M103.8,64H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,63.2,104.9,64,103.8,64z'/> <path className='st0' d='M103.8,55.8H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,55,104.9,55.8,103.8,55.8z'/> <path className='st0' d='M103.8,47.6H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,46.7,104.9,47.6,103.8,47.6z'/> <path className='st0' d='M103.8,39.4H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,38.5,104.9,39.4,103.8,39.4z'/> <path className='st0' d='M103.8,31.2H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,30.3,104.9,31.2,103.8,31.2z'/> </svg>\",\"collapserowfive\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8C116.7,2,3.8,2,3.8,2z'/> <g> <path className='st0' d='M101.4,24.5H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,25.8,102.9,24.5,101.4,24.5z'/> <path className='st0' d='M101.4,11.8H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,13.1,102.9,11.8,101.4,11.8z'/> <path className='st0' d='M101.4,37.1H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,38.5,102.9,37.1,101.4,37.1z'/> <path className='st0' d='M101.4,49.7H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,51,102.9,49.7,101.4,49.7z'/> <path className='st0' d='M101.4,62.4H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,63.8,102.9,62.4,101.4,62.4z'/> </g> </svg> \",\"grid\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8C116.7,2,3.8,2,3.8,2z'/> <path className='st0' d='M55.7,43.7H12.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8C58.7,45,57.4,43.7,55.7,43.7 z'/> <path className='st0' d='M107.7,43.7H64.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8 C110.7,45,109.4,43.7,107.7,43.7z'/> <path className='st0' d='M55.7,59.7H12.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8C58.7,61,57.4,59.7,55.7,59.7 z'/> <path className='st0' d='M107.7,59.7H64.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8 C110.7,61,109.4,59.7,107.7,59.7z'/> <g> <rect x='0.6' y='38.2' className='st0' width='119.3' height='1'/> </g> </svg> \",\"collapserowfour\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,81.8H3.8c-2,0-3.5-1.6-3.5-3.5V4.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,80.2,118.7,81.8,116.7,81.8z M3.8,3C2.8,3,2,3.8,2,4.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4.8 c0-1-0.8-1.8-1.8-1.8C116.7,3,3.8,3,3.8,3z'/> <g> <path className='st0' d='M103,36.7H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3v-0.8 C105.7,38,104.5,36.7,103,36.7z'/> <path className='st0' d='M103,47.3H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3v-0.8 C105.7,48.7,104.5,47.3,103,47.3z'/> <path className='st0' d='M103,58H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3V61 C105.7,59.3,104.5,58,103,58z'/> <path className='st0' d='M103,68.6H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3v-0.8 C105.7,70,104.5,68.6,103,68.6z'/> </g> <g> <rect x='0.6' y='31.7' className='st0' width='119.3' height='1'/> </g> </svg> \",\"firstrow\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <rect x='0.7' y='43.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M111.1,57.9c0,1.2-0.8,2.1-1.8,2.1H11.4c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h97.9 c1,0,1.8,0.9,1.8,2.1V57.9L111.1,57.9z'/> <g> <path className='st0' d='M58.2,72.1c0,1.2-0.8,2.1-1.8,2.1h-45c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h45 c1,0,1.8,0.9,1.8,2.1V72.1L58.2,72.1z'/> </g> <g> <path className='st0' d='M111.1,72.1c0,1.2-0.8,2.1-1.8,2.1h-45c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h45 c1,0,1.8,0.9,1.8,2.1V72.1L111.1,72.1z'/> </g> </svg> \",\"lastrow\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <rect x='0.7' y='43.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M9.6,65.8c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v6.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V65.8L9.6,65.8z'/> <path className='st0' d='M38.5,52.6c0-1.2,0.8-2.1,1.8-2.1h69c1,0,1.8,0.9,1.8,2.1V59c0,1.2-0.8,2.1-1.8,2.1h-69c-1,0-1.8-0.9-1.8-2.1 V52.6L38.5,52.6z'/> <path className='st0' d='M9.6,52.6c0-1.2,0.8-2.1,1.8-2.1h20.9c1,0,1.8,0.9,1.8,2.1v6.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V52.6L9.6,52.6z'/> </svg>\",\"collapserowthree\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <rect x='0.7' y='39.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M9.6,68.8c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v3.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V68.8L9.6,68.8z'/> <path className='st0' d='M9.6,57.6c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v3.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V57.6L9.6,57.6z'/> <path className='st0' d='M9.6,46.4c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v3.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V46.4L9.6,46.4z'/> </svg> \",\"row\":\"<svg viewBox='0 0 120.5 81' xmlns='http://www.w3.org/2000/svg'> <path d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z' /> <path d='M108,55.3H12.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3H108c1.7,0,3-1.3,3-3V58.3C111.1,56.6,109.7,55.3,108,55.3z' /> <rect x='0.6' y='48' width='119.3' height='1'/> </svg> \"}")},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.htmlparser2=t.convertNodeToElement=t.processNodes=void 0;var n=r(18);Object.defineProperty(t,"processNodes",{enumerable:!0,get:function(){return s(n).default}});var a=r(20);Object.defineProperty(t,"convertNodeToElement",{enumerable:!0,get:function(){return s(a).default}});var o=r(10);Object.defineProperty(t,"htmlparser2",{enumerable:!0,get:function(){return s(o).default}});var i=s(r(89));function s(e){return e&&e.__esModule?e:{default:e}}t.default=i.default},function(e,t,r){var n=r(93),a=r(94),o=r(35),i=r(95);e.exports=function(e){return n(e)||a(e)||o(e)||i()}},function(e,t){!function(){e.exports=this.wp.mediaUtils}()},function(e,t){!function(){e.exports=this.ReactDOM}()},function(e,t,r){var n=r(37),a=r(100),o=r(102),i=Math.max,s=Math.min;e.exports=function(e,t,r){var c,l,u,p,d,h,f=0,m=!1,g=!1,b=!0;if("function"!=typeof e)throw new TypeError("Expected a function");function v(t){var r=c,n=l;return c=l=void 0,f=t,p=e.apply(n,r)}function y(e){return f=e,d=setTimeout(_,t),m?v(e):p}function w(e){var r=e-h;return void 0===h||r>=t||r<0||g&&e-f>=u}function _(){var e=a();if(w(e))return O(e);d=setTimeout(_,function(e){var r=t-(e-h);return g?s(r,u-(e-f)):r}(e))}function O(e){return d=void 0,b&&c?v(e):(c=l=void 0,p)}function E(){var e=a(),r=w(e);if(c=arguments,l=this,h=e,r){if(void 0===d)return y(h);if(g)return clearTimeout(d),d=setTimeout(_,t),v(h)}return void 0===d&&(d=setTimeout(_,t)),p}return t=o(t)||0,n(r)&&(m=!!r.leading,u=(g="maxWait"in r)?i(o(r.maxWait)||0,t):u,b="trailing"in r?!!r.trailing:b),E.cancel=function(){void 0!==d&&clearTimeout(d),f=0,c=h=l=d=void 0},E.flush=function(){return void 0===d?p:O(a())},E}},function(e,t,r){"use strict";var n=r(47);function a(){}function o(){}o.resetWarningCache=a,e.exports=function(){function e(e,t,r,a,o,i){if(i!==n){var s=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw s.name="Invariant Violation",s}}function t(){return e}e.isRequired=e;var r={array:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:o,resetWarningCache:a};return r.PropTypes=r,r}},function(e,t,r){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){return"text"===e.type&&/\r?\n/.test(e.data)&&""===e.data.trim()}},function(e,t,r){"use strict";var n;Object.defineProperty(t,"__esModule",{value:!0});var a=r(10),o=l(r(80)),i=l(r(81)),s=l(r(87)),c=l(r(88));function l(e){return e&&e.__esModule?e:{default:e}}function u(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}t.default=(u(n={},a.ElementType.Text,o.default),u(n,a.ElementType.Tag,i.default),u(n,a.ElementType.Style,s.default),u(n,a.ElementType.Directive,c.default),u(n,a.ElementType.Comment,c.default),u(n,a.ElementType.Script,c.default),u(n,a.ElementType.CDATA,c.default),u(n,a.ElementType.Doctype,c.default),n)},function(e,t,r){var n=r(51);e.exports=function(e){if(e>=55296&&e<=57343||e>1114111)return"�";e in n&&(e=n[e]);var t="";e>65535&&(e-=65536,t+=String.fromCharCode(e>>>10&1023|55296),e=56320|1023&e);return t+=String.fromCharCode(e)}},function(e){e.exports=JSON.parse('{"0":65533,"128":8364,"130":8218,"131":402,"132":8222,"133":8230,"134":8224,"135":8225,"136":710,"137":8240,"138":352,"139":8249,"140":338,"142":381,"145":8216,"146":8217,"147":8220,"148":8221,"149":8226,"150":8211,"151":8212,"152":732,"153":8482,"154":353,"155":8250,"156":339,"158":382,"159":376}')},function(e){e.exports=JSON.parse('{"Aacute":"Á","aacute":"á","Abreve":"Ă","abreve":"ă","ac":"∾","acd":"∿","acE":"∾̳","Acirc":"Â","acirc":"â","acute":"´","Acy":"А","acy":"а","AElig":"Æ","aelig":"æ","af":"⁡","Afr":"𝔄","afr":"𝔞","Agrave":"À","agrave":"à","alefsym":"ℵ","aleph":"ℵ","Alpha":"Α","alpha":"α","Amacr":"Ā","amacr":"ā","amalg":"⨿","amp":"&","AMP":"&","andand":"⩕","And":"⩓","and":"∧","andd":"⩜","andslope":"⩘","andv":"⩚","ang":"∠","ange":"⦤","angle":"∠","angmsdaa":"⦨","angmsdab":"⦩","angmsdac":"⦪","angmsdad":"⦫","angmsdae":"⦬","angmsdaf":"⦭","angmsdag":"⦮","angmsdah":"⦯","angmsd":"∡","angrt":"∟","angrtvb":"⊾","angrtvbd":"⦝","angsph":"∢","angst":"Å","angzarr":"⍼","Aogon":"Ą","aogon":"ą","Aopf":"𝔸","aopf":"𝕒","apacir":"⩯","ap":"≈","apE":"⩰","ape":"≊","apid":"≋","apos":"\'","ApplyFunction":"⁡","approx":"≈","approxeq":"≊","Aring":"Å","aring":"å","Ascr":"𝒜","ascr":"𝒶","Assign":"≔","ast":"*","asymp":"≈","asympeq":"≍","Atilde":"Ã","atilde":"ã","Auml":"Ä","auml":"ä","awconint":"∳","awint":"⨑","backcong":"≌","backepsilon":"϶","backprime":"‵","backsim":"∽","backsimeq":"⋍","Backslash":"∖","Barv":"⫧","barvee":"⊽","barwed":"⌅","Barwed":"⌆","barwedge":"⌅","bbrk":"⎵","bbrktbrk":"⎶","bcong":"≌","Bcy":"Б","bcy":"б","bdquo":"„","becaus":"∵","because":"∵","Because":"∵","bemptyv":"⦰","bepsi":"϶","bernou":"ℬ","Bernoullis":"ℬ","Beta":"Β","beta":"β","beth":"ℶ","between":"≬","Bfr":"𝔅","bfr":"𝔟","bigcap":"⋂","bigcirc":"◯","bigcup":"⋃","bigodot":"⨀","bigoplus":"⨁","bigotimes":"⨂","bigsqcup":"⨆","bigstar":"★","bigtriangledown":"▽","bigtriangleup":"△","biguplus":"⨄","bigvee":"⋁","bigwedge":"⋀","bkarow":"⤍","blacklozenge":"⧫","blacksquare":"▪","blacktriangle":"▴","blacktriangledown":"▾","blacktriangleleft":"◂","blacktriangleright":"▸","blank":"␣","blk12":"▒","blk14":"░","blk34":"▓","block":"█","bne":"=⃥","bnequiv":"≡⃥","bNot":"⫭","bnot":"⌐","Bopf":"𝔹","bopf":"𝕓","bot":"⊥","bottom":"⊥","bowtie":"⋈","boxbox":"⧉","boxdl":"┐","boxdL":"╕","boxDl":"╖","boxDL":"╗","boxdr":"┌","boxdR":"╒","boxDr":"╓","boxDR":"╔","boxh":"─","boxH":"═","boxhd":"┬","boxHd":"╤","boxhD":"╥","boxHD":"╦","boxhu":"┴","boxHu":"╧","boxhU":"╨","boxHU":"╩","boxminus":"⊟","boxplus":"⊞","boxtimes":"⊠","boxul":"┘","boxuL":"╛","boxUl":"╜","boxUL":"╝","boxur":"└","boxuR":"╘","boxUr":"╙","boxUR":"╚","boxv":"│","boxV":"║","boxvh":"┼","boxvH":"╪","boxVh":"╫","boxVH":"╬","boxvl":"┤","boxvL":"╡","boxVl":"╢","boxVL":"╣","boxvr":"├","boxvR":"╞","boxVr":"╟","boxVR":"╠","bprime":"‵","breve":"˘","Breve":"˘","brvbar":"¦","bscr":"𝒷","Bscr":"ℬ","bsemi":"⁏","bsim":"∽","bsime":"⋍","bsolb":"⧅","bsol":"\\\\","bsolhsub":"⟈","bull":"•","bullet":"•","bump":"≎","bumpE":"⪮","bumpe":"≏","Bumpeq":"≎","bumpeq":"≏","Cacute":"Ć","cacute":"ć","capand":"⩄","capbrcup":"⩉","capcap":"⩋","cap":"∩","Cap":"⋒","capcup":"⩇","capdot":"⩀","CapitalDifferentialD":"ⅅ","caps":"∩︀","caret":"⁁","caron":"ˇ","Cayleys":"ℭ","ccaps":"⩍","Ccaron":"Č","ccaron":"č","Ccedil":"Ç","ccedil":"ç","Ccirc":"Ĉ","ccirc":"ĉ","Cconint":"∰","ccups":"⩌","ccupssm":"⩐","Cdot":"Ċ","cdot":"ċ","cedil":"¸","Cedilla":"¸","cemptyv":"⦲","cent":"¢","centerdot":"·","CenterDot":"·","cfr":"𝔠","Cfr":"ℭ","CHcy":"Ч","chcy":"ч","check":"✓","checkmark":"✓","Chi":"Χ","chi":"χ","circ":"ˆ","circeq":"≗","circlearrowleft":"↺","circlearrowright":"↻","circledast":"⊛","circledcirc":"⊚","circleddash":"⊝","CircleDot":"⊙","circledR":"®","circledS":"Ⓢ","CircleMinus":"⊖","CirclePlus":"⊕","CircleTimes":"⊗","cir":"○","cirE":"⧃","cire":"≗","cirfnint":"⨐","cirmid":"⫯","cirscir":"⧂","ClockwiseContourIntegral":"∲","CloseCurlyDoubleQuote":"”","CloseCurlyQuote":"’","clubs":"♣","clubsuit":"♣","colon":":","Colon":"∷","Colone":"⩴","colone":"≔","coloneq":"≔","comma":",","commat":"@","comp":"∁","compfn":"∘","complement":"∁","complexes":"ℂ","cong":"≅","congdot":"⩭","Congruent":"≡","conint":"∮","Conint":"∯","ContourIntegral":"∮","copf":"𝕔","Copf":"ℂ","coprod":"∐","Coproduct":"∐","copy":"©","COPY":"©","copysr":"℗","CounterClockwiseContourIntegral":"∳","crarr":"↵","cross":"✗","Cross":"⨯","Cscr":"𝒞","cscr":"𝒸","csub":"⫏","csube":"⫑","csup":"⫐","csupe":"⫒","ctdot":"⋯","cudarrl":"⤸","cudarrr":"⤵","cuepr":"⋞","cuesc":"⋟","cularr":"↶","cularrp":"⤽","cupbrcap":"⩈","cupcap":"⩆","CupCap":"≍","cup":"∪","Cup":"⋓","cupcup":"⩊","cupdot":"⊍","cupor":"⩅","cups":"∪︀","curarr":"↷","curarrm":"⤼","curlyeqprec":"⋞","curlyeqsucc":"⋟","curlyvee":"⋎","curlywedge":"⋏","curren":"¤","curvearrowleft":"↶","curvearrowright":"↷","cuvee":"⋎","cuwed":"⋏","cwconint":"∲","cwint":"∱","cylcty":"⌭","dagger":"†","Dagger":"‡","daleth":"ℸ","darr":"↓","Darr":"↡","dArr":"⇓","dash":"‐","Dashv":"⫤","dashv":"⊣","dbkarow":"⤏","dblac":"˝","Dcaron":"Ď","dcaron":"ď","Dcy":"Д","dcy":"д","ddagger":"‡","ddarr":"⇊","DD":"ⅅ","dd":"ⅆ","DDotrahd":"⤑","ddotseq":"⩷","deg":"°","Del":"∇","Delta":"Δ","delta":"δ","demptyv":"⦱","dfisht":"⥿","Dfr":"𝔇","dfr":"𝔡","dHar":"⥥","dharl":"⇃","dharr":"⇂","DiacriticalAcute":"´","DiacriticalDot":"˙","DiacriticalDoubleAcute":"˝","DiacriticalGrave":"`","DiacriticalTilde":"˜","diam":"⋄","diamond":"⋄","Diamond":"⋄","diamondsuit":"♦","diams":"♦","die":"¨","DifferentialD":"ⅆ","digamma":"ϝ","disin":"⋲","div":"÷","divide":"÷","divideontimes":"⋇","divonx":"⋇","DJcy":"Ђ","djcy":"ђ","dlcorn":"⌞","dlcrop":"⌍","dollar":"$","Dopf":"𝔻","dopf":"𝕕","Dot":"¨","dot":"˙","DotDot":"⃜","doteq":"≐","doteqdot":"≑","DotEqual":"≐","dotminus":"∸","dotplus":"∔","dotsquare":"⊡","doublebarwedge":"⌆","DoubleContourIntegral":"∯","DoubleDot":"¨","DoubleDownArrow":"⇓","DoubleLeftArrow":"⇐","DoubleLeftRightArrow":"⇔","DoubleLeftTee":"⫤","DoubleLongLeftArrow":"⟸","DoubleLongLeftRightArrow":"⟺","DoubleLongRightArrow":"⟹","DoubleRightArrow":"⇒","DoubleRightTee":"⊨","DoubleUpArrow":"⇑","DoubleUpDownArrow":"⇕","DoubleVerticalBar":"∥","DownArrowBar":"⤓","downarrow":"↓","DownArrow":"↓","Downarrow":"⇓","DownArrowUpArrow":"⇵","DownBreve":"̑","downdownarrows":"⇊","downharpoonleft":"⇃","downharpoonright":"⇂","DownLeftRightVector":"⥐","DownLeftTeeVector":"⥞","DownLeftVectorBar":"⥖","DownLeftVector":"↽","DownRightTeeVector":"⥟","DownRightVectorBar":"⥗","DownRightVector":"⇁","DownTeeArrow":"↧","DownTee":"⊤","drbkarow":"⤐","drcorn":"⌟","drcrop":"⌌","Dscr":"𝒟","dscr":"𝒹","DScy":"Ѕ","dscy":"ѕ","dsol":"⧶","Dstrok":"Đ","dstrok":"đ","dtdot":"⋱","dtri":"▿","dtrif":"▾","duarr":"⇵","duhar":"⥯","dwangle":"⦦","DZcy":"Џ","dzcy":"џ","dzigrarr":"⟿","Eacute":"É","eacute":"é","easter":"⩮","Ecaron":"Ě","ecaron":"ě","Ecirc":"Ê","ecirc":"ê","ecir":"≖","ecolon":"≕","Ecy":"Э","ecy":"э","eDDot":"⩷","Edot":"Ė","edot":"ė","eDot":"≑","ee":"ⅇ","efDot":"≒","Efr":"𝔈","efr":"𝔢","eg":"⪚","Egrave":"È","egrave":"è","egs":"⪖","egsdot":"⪘","el":"⪙","Element":"∈","elinters":"⏧","ell":"ℓ","els":"⪕","elsdot":"⪗","Emacr":"Ē","emacr":"ē","empty":"∅","emptyset":"∅","EmptySmallSquare":"◻","emptyv":"∅","EmptyVerySmallSquare":"▫","emsp13":" ","emsp14":" ","emsp":" ","ENG":"Ŋ","eng":"ŋ","ensp":" ","Eogon":"Ę","eogon":"ę","Eopf":"𝔼","eopf":"𝕖","epar":"⋕","eparsl":"⧣","eplus":"⩱","epsi":"ε","Epsilon":"Ε","epsilon":"ε","epsiv":"ϵ","eqcirc":"≖","eqcolon":"≕","eqsim":"≂","eqslantgtr":"⪖","eqslantless":"⪕","Equal":"⩵","equals":"=","EqualTilde":"≂","equest":"≟","Equilibrium":"⇌","equiv":"≡","equivDD":"⩸","eqvparsl":"⧥","erarr":"⥱","erDot":"≓","escr":"ℯ","Escr":"ℰ","esdot":"≐","Esim":"⩳","esim":"≂","Eta":"Η","eta":"η","ETH":"Ð","eth":"ð","Euml":"Ë","euml":"ë","euro":"€","excl":"!","exist":"∃","Exists":"∃","expectation":"ℰ","exponentiale":"ⅇ","ExponentialE":"ⅇ","fallingdotseq":"≒","Fcy":"Ф","fcy":"ф","female":"♀","ffilig":"ﬃ","fflig":"ﬀ","ffllig":"ﬄ","Ffr":"𝔉","ffr":"𝔣","filig":"ﬁ","FilledSmallSquare":"◼","FilledVerySmallSquare":"▪","fjlig":"fj","flat":"♭","fllig":"ﬂ","fltns":"▱","fnof":"ƒ","Fopf":"𝔽","fopf":"𝕗","forall":"∀","ForAll":"∀","fork":"⋔","forkv":"⫙","Fouriertrf":"ℱ","fpartint":"⨍","frac12":"½","frac13":"⅓","frac14":"¼","frac15":"⅕","frac16":"⅙","frac18":"⅛","frac23":"⅔","frac25":"⅖","frac34":"¾","frac35":"⅗","frac38":"⅜","frac45":"⅘","frac56":"⅚","frac58":"⅝","frac78":"⅞","frasl":"⁄","frown":"⌢","fscr":"𝒻","Fscr":"ℱ","gacute":"ǵ","Gamma":"Γ","gamma":"γ","Gammad":"Ϝ","gammad":"ϝ","gap":"⪆","Gbreve":"Ğ","gbreve":"ğ","Gcedil":"Ģ","Gcirc":"Ĝ","gcirc":"ĝ","Gcy":"Г","gcy":"г","Gdot":"Ġ","gdot":"ġ","ge":"≥","gE":"≧","gEl":"⪌","gel":"⋛","geq":"≥","geqq":"≧","geqslant":"⩾","gescc":"⪩","ges":"⩾","gesdot":"⪀","gesdoto":"⪂","gesdotol":"⪄","gesl":"⋛︀","gesles":"⪔","Gfr":"𝔊","gfr":"𝔤","gg":"≫","Gg":"⋙","ggg":"⋙","gimel":"ℷ","GJcy":"Ѓ","gjcy":"ѓ","gla":"⪥","gl":"≷","glE":"⪒","glj":"⪤","gnap":"⪊","gnapprox":"⪊","gne":"⪈","gnE":"≩","gneq":"⪈","gneqq":"≩","gnsim":"⋧","Gopf":"𝔾","gopf":"𝕘","grave":"`","GreaterEqual":"≥","GreaterEqualLess":"⋛","GreaterFullEqual":"≧","GreaterGreater":"⪢","GreaterLess":"≷","GreaterSlantEqual":"⩾","GreaterTilde":"≳","Gscr":"𝒢","gscr":"ℊ","gsim":"≳","gsime":"⪎","gsiml":"⪐","gtcc":"⪧","gtcir":"⩺","gt":">","GT":">","Gt":"≫","gtdot":"⋗","gtlPar":"⦕","gtquest":"⩼","gtrapprox":"⪆","gtrarr":"⥸","gtrdot":"⋗","gtreqless":"⋛","gtreqqless":"⪌","gtrless":"≷","gtrsim":"≳","gvertneqq":"≩︀","gvnE":"≩︀","Hacek":"ˇ","hairsp":" ","half":"½","hamilt":"ℋ","HARDcy":"Ъ","hardcy":"ъ","harrcir":"⥈","harr":"↔","hArr":"⇔","harrw":"↭","Hat":"^","hbar":"ℏ","Hcirc":"Ĥ","hcirc":"ĥ","hearts":"♥","heartsuit":"♥","hellip":"…","hercon":"⊹","hfr":"𝔥","Hfr":"ℌ","HilbertSpace":"ℋ","hksearow":"⤥","hkswarow":"⤦","hoarr":"⇿","homtht":"∻","hookleftarrow":"↩","hookrightarrow":"↪","hopf":"𝕙","Hopf":"ℍ","horbar":"―","HorizontalLine":"─","hscr":"𝒽","Hscr":"ℋ","hslash":"ℏ","Hstrok":"Ħ","hstrok":"ħ","HumpDownHump":"≎","HumpEqual":"≏","hybull":"⁃","hyphen":"‐","Iacute":"Í","iacute":"í","ic":"⁣","Icirc":"Î","icirc":"î","Icy":"И","icy":"и","Idot":"İ","IEcy":"Е","iecy":"е","iexcl":"¡","iff":"⇔","ifr":"𝔦","Ifr":"ℑ","Igrave":"Ì","igrave":"ì","ii":"ⅈ","iiiint":"⨌","iiint":"∭","iinfin":"⧜","iiota":"℩","IJlig":"Ĳ","ijlig":"ĳ","Imacr":"Ī","imacr":"ī","image":"ℑ","ImaginaryI":"ⅈ","imagline":"ℐ","imagpart":"ℑ","imath":"ı","Im":"ℑ","imof":"⊷","imped":"Ƶ","Implies":"⇒","incare":"℅","in":"∈","infin":"∞","infintie":"⧝","inodot":"ı","intcal":"⊺","int":"∫","Int":"∬","integers":"ℤ","Integral":"∫","intercal":"⊺","Intersection":"⋂","intlarhk":"⨗","intprod":"⨼","InvisibleComma":"⁣","InvisibleTimes":"⁢","IOcy":"Ё","iocy":"ё","Iogon":"Į","iogon":"į","Iopf":"𝕀","iopf":"𝕚","Iota":"Ι","iota":"ι","iprod":"⨼","iquest":"¿","iscr":"𝒾","Iscr":"ℐ","isin":"∈","isindot":"⋵","isinE":"⋹","isins":"⋴","isinsv":"⋳","isinv":"∈","it":"⁢","Itilde":"Ĩ","itilde":"ĩ","Iukcy":"І","iukcy":"і","Iuml":"Ï","iuml":"ï","Jcirc":"Ĵ","jcirc":"ĵ","Jcy":"Й","jcy":"й","Jfr":"𝔍","jfr":"𝔧","jmath":"ȷ","Jopf":"𝕁","jopf":"𝕛","Jscr":"𝒥","jscr":"𝒿","Jsercy":"Ј","jsercy":"ј","Jukcy":"Є","jukcy":"є","Kappa":"Κ","kappa":"κ","kappav":"ϰ","Kcedil":"Ķ","kcedil":"ķ","Kcy":"К","kcy":"к","Kfr":"𝔎","kfr":"𝔨","kgreen":"ĸ","KHcy":"Х","khcy":"х","KJcy":"Ќ","kjcy":"ќ","Kopf":"𝕂","kopf":"𝕜","Kscr":"𝒦","kscr":"𝓀","lAarr":"⇚","Lacute":"Ĺ","lacute":"ĺ","laemptyv":"⦴","lagran":"ℒ","Lambda":"Λ","lambda":"λ","lang":"⟨","Lang":"⟪","langd":"⦑","langle":"⟨","lap":"⪅","Laplacetrf":"ℒ","laquo":"«","larrb":"⇤","larrbfs":"⤟","larr":"←","Larr":"↞","lArr":"⇐","larrfs":"⤝","larrhk":"↩","larrlp":"↫","larrpl":"⤹","larrsim":"⥳","larrtl":"↢","latail":"⤙","lAtail":"⤛","lat":"⪫","late":"⪭","lates":"⪭︀","lbarr":"⤌","lBarr":"⤎","lbbrk":"❲","lbrace":"{","lbrack":"[","lbrke":"⦋","lbrksld":"⦏","lbrkslu":"⦍","Lcaron":"Ľ","lcaron":"ľ","Lcedil":"Ļ","lcedil":"ļ","lceil":"⌈","lcub":"{","Lcy":"Л","lcy":"л","ldca":"⤶","ldquo":"“","ldquor":"„","ldrdhar":"⥧","ldrushar":"⥋","ldsh":"↲","le":"≤","lE":"≦","LeftAngleBracket":"⟨","LeftArrowBar":"⇤","leftarrow":"←","LeftArrow":"←","Leftarrow":"⇐","LeftArrowRightArrow":"⇆","leftarrowtail":"↢","LeftCeiling":"⌈","LeftDoubleBracket":"⟦","LeftDownTeeVector":"⥡","LeftDownVectorBar":"⥙","LeftDownVector":"⇃","LeftFloor":"⌊","leftharpoondown":"↽","leftharpoonup":"↼","leftleftarrows":"⇇","leftrightarrow":"↔","LeftRightArrow":"↔","Leftrightarrow":"⇔","leftrightarrows":"⇆","leftrightharpoons":"⇋","leftrightsquigarrow":"↭","LeftRightVector":"⥎","LeftTeeArrow":"↤","LeftTee":"⊣","LeftTeeVector":"⥚","leftthreetimes":"⋋","LeftTriangleBar":"⧏","LeftTriangle":"⊲","LeftTriangleEqual":"⊴","LeftUpDownVector":"⥑","LeftUpTeeVector":"⥠","LeftUpVectorBar":"⥘","LeftUpVector":"↿","LeftVectorBar":"⥒","LeftVector":"↼","lEg":"⪋","leg":"⋚","leq":"≤","leqq":"≦","leqslant":"⩽","lescc":"⪨","les":"⩽","lesdot":"⩿","lesdoto":"⪁","lesdotor":"⪃","lesg":"⋚︀","lesges":"⪓","lessapprox":"⪅","lessdot":"⋖","lesseqgtr":"⋚","lesseqqgtr":"⪋","LessEqualGreater":"⋚","LessFullEqual":"≦","LessGreater":"≶","lessgtr":"≶","LessLess":"⪡","lesssim":"≲","LessSlantEqual":"⩽","LessTilde":"≲","lfisht":"⥼","lfloor":"⌊","Lfr":"𝔏","lfr":"𝔩","lg":"≶","lgE":"⪑","lHar":"⥢","lhard":"↽","lharu":"↼","lharul":"⥪","lhblk":"▄","LJcy":"Љ","ljcy":"љ","llarr":"⇇","ll":"≪","Ll":"⋘","llcorner":"⌞","Lleftarrow":"⇚","llhard":"⥫","lltri":"◺","Lmidot":"Ŀ","lmidot":"ŀ","lmoustache":"⎰","lmoust":"⎰","lnap":"⪉","lnapprox":"⪉","lne":"⪇","lnE":"≨","lneq":"⪇","lneqq":"≨","lnsim":"⋦","loang":"⟬","loarr":"⇽","lobrk":"⟦","longleftarrow":"⟵","LongLeftArrow":"⟵","Longleftarrow":"⟸","longleftrightarrow":"⟷","LongLeftRightArrow":"⟷","Longleftrightarrow":"⟺","longmapsto":"⟼","longrightarrow":"⟶","LongRightArrow":"⟶","Longrightarrow":"⟹","looparrowleft":"↫","looparrowright":"↬","lopar":"⦅","Lopf":"𝕃","lopf":"𝕝","loplus":"⨭","lotimes":"⨴","lowast":"∗","lowbar":"_","LowerLeftArrow":"↙","LowerRightArrow":"↘","loz":"◊","lozenge":"◊","lozf":"⧫","lpar":"(","lparlt":"⦓","lrarr":"⇆","lrcorner":"⌟","lrhar":"⇋","lrhard":"⥭","lrm":"‎","lrtri":"⊿","lsaquo":"‹","lscr":"𝓁","Lscr":"ℒ","lsh":"↰","Lsh":"↰","lsim":"≲","lsime":"⪍","lsimg":"⪏","lsqb":"[","lsquo":"‘","lsquor":"‚","Lstrok":"Ł","lstrok":"ł","ltcc":"⪦","ltcir":"⩹","lt":"<","LT":"<","Lt":"≪","ltdot":"⋖","lthree":"⋋","ltimes":"⋉","ltlarr":"⥶","ltquest":"⩻","ltri":"◃","ltrie":"⊴","ltrif":"◂","ltrPar":"⦖","lurdshar":"⥊","luruhar":"⥦","lvertneqq":"≨︀","lvnE":"≨︀","macr":"¯","male":"♂","malt":"✠","maltese":"✠","Map":"⤅","map":"↦","mapsto":"↦","mapstodown":"↧","mapstoleft":"↤","mapstoup":"↥","marker":"▮","mcomma":"⨩","Mcy":"М","mcy":"м","mdash":"—","mDDot":"∺","measuredangle":"∡","MediumSpace":" ","Mellintrf":"ℳ","Mfr":"𝔐","mfr":"𝔪","mho":"℧","micro":"µ","midast":"*","midcir":"⫰","mid":"∣","middot":"·","minusb":"⊟","minus":"−","minusd":"∸","minusdu":"⨪","MinusPlus":"∓","mlcp":"⫛","mldr":"…","mnplus":"∓","models":"⊧","Mopf":"𝕄","mopf":"𝕞","mp":"∓","mscr":"𝓂","Mscr":"ℳ","mstpos":"∾","Mu":"Μ","mu":"μ","multimap":"⊸","mumap":"⊸","nabla":"∇","Nacute":"Ń","nacute":"ń","nang":"∠⃒","nap":"≉","napE":"⩰̸","napid":"≋̸","napos":"ŉ","napprox":"≉","natural":"♮","naturals":"ℕ","natur":"♮","nbsp":" ","nbump":"≎̸","nbumpe":"≏̸","ncap":"⩃","Ncaron":"Ň","ncaron":"ň","Ncedil":"Ņ","ncedil":"ņ","ncong":"≇","ncongdot":"⩭̸","ncup":"⩂","Ncy":"Н","ncy":"н","ndash":"–","nearhk":"⤤","nearr":"↗","neArr":"⇗","nearrow":"↗","ne":"≠","nedot":"≐̸","NegativeMediumSpace":"​","NegativeThickSpace":"​","NegativeThinSpace":"​","NegativeVeryThinSpace":"​","nequiv":"≢","nesear":"⤨","nesim":"≂̸","NestedGreaterGreater":"≫","NestedLessLess":"≪","NewLine":"\\n","nexist":"∄","nexists":"∄","Nfr":"𝔑","nfr":"𝔫","ngE":"≧̸","nge":"≱","ngeq":"≱","ngeqq":"≧̸","ngeqslant":"⩾̸","nges":"⩾̸","nGg":"⋙̸","ngsim":"≵","nGt":"≫⃒","ngt":"≯","ngtr":"≯","nGtv":"≫̸","nharr":"↮","nhArr":"⇎","nhpar":"⫲","ni":"∋","nis":"⋼","nisd":"⋺","niv":"∋","NJcy":"Њ","njcy":"њ","nlarr":"↚","nlArr":"⇍","nldr":"‥","nlE":"≦̸","nle":"≰","nleftarrow":"↚","nLeftarrow":"⇍","nleftrightarrow":"↮","nLeftrightarrow":"⇎","nleq":"≰","nleqq":"≦̸","nleqslant":"⩽̸","nles":"⩽̸","nless":"≮","nLl":"⋘̸","nlsim":"≴","nLt":"≪⃒","nlt":"≮","nltri":"⋪","nltrie":"⋬","nLtv":"≪̸","nmid":"∤","NoBreak":"⁠","NonBreakingSpace":" ","nopf":"𝕟","Nopf":"ℕ","Not":"⫬","not":"¬","NotCongruent":"≢","NotCupCap":"≭","NotDoubleVerticalBar":"∦","NotElement":"∉","NotEqual":"≠","NotEqualTilde":"≂̸","NotExists":"∄","NotGreater":"≯","NotGreaterEqual":"≱","NotGreaterFullEqual":"≧̸","NotGreaterGreater":"≫̸","NotGreaterLess":"≹","NotGreaterSlantEqual":"⩾̸","NotGreaterTilde":"≵","NotHumpDownHump":"≎̸","NotHumpEqual":"≏̸","notin":"∉","notindot":"⋵̸","notinE":"⋹̸","notinva":"∉","notinvb":"⋷","notinvc":"⋶","NotLeftTriangleBar":"⧏̸","NotLeftTriangle":"⋪","NotLeftTriangleEqual":"⋬","NotLess":"≮","NotLessEqual":"≰","NotLessGreater":"≸","NotLessLess":"≪̸","NotLessSlantEqual":"⩽̸","NotLessTilde":"≴","NotNestedGreaterGreater":"⪢̸","NotNestedLessLess":"⪡̸","notni":"∌","notniva":"∌","notnivb":"⋾","notnivc":"⋽","NotPrecedes":"⊀","NotPrecedesEqual":"⪯̸","NotPrecedesSlantEqual":"⋠","NotReverseElement":"∌","NotRightTriangleBar":"⧐̸","NotRightTriangle":"⋫","NotRightTriangleEqual":"⋭","NotSquareSubset":"⊏̸","NotSquareSubsetEqual":"⋢","NotSquareSuperset":"⊐̸","NotSquareSupersetEqual":"⋣","NotSubset":"⊂⃒","NotSubsetEqual":"⊈","NotSucceeds":"⊁","NotSucceedsEqual":"⪰̸","NotSucceedsSlantEqual":"⋡","NotSucceedsTilde":"≿̸","NotSuperset":"⊃⃒","NotSupersetEqual":"⊉","NotTilde":"≁","NotTildeEqual":"≄","NotTildeFullEqual":"≇","NotTildeTilde":"≉","NotVerticalBar":"∤","nparallel":"∦","npar":"∦","nparsl":"⫽⃥","npart":"∂̸","npolint":"⨔","npr":"⊀","nprcue":"⋠","nprec":"⊀","npreceq":"⪯̸","npre":"⪯̸","nrarrc":"⤳̸","nrarr":"↛","nrArr":"⇏","nrarrw":"↝̸","nrightarrow":"↛","nRightarrow":"⇏","nrtri":"⋫","nrtrie":"⋭","nsc":"⊁","nsccue":"⋡","nsce":"⪰̸","Nscr":"𝒩","nscr":"𝓃","nshortmid":"∤","nshortparallel":"∦","nsim":"≁","nsime":"≄","nsimeq":"≄","nsmid":"∤","nspar":"∦","nsqsube":"⋢","nsqsupe":"⋣","nsub":"⊄","nsubE":"⫅̸","nsube":"⊈","nsubset":"⊂⃒","nsubseteq":"⊈","nsubseteqq":"⫅̸","nsucc":"⊁","nsucceq":"⪰̸","nsup":"⊅","nsupE":"⫆̸","nsupe":"⊉","nsupset":"⊃⃒","nsupseteq":"⊉","nsupseteqq":"⫆̸","ntgl":"≹","Ntilde":"Ñ","ntilde":"ñ","ntlg":"≸","ntriangleleft":"⋪","ntrianglelefteq":"⋬","ntriangleright":"⋫","ntrianglerighteq":"⋭","Nu":"Ν","nu":"ν","num":"#","numero":"№","numsp":" ","nvap":"≍⃒","nvdash":"⊬","nvDash":"⊭","nVdash":"⊮","nVDash":"⊯","nvge":"≥⃒","nvgt":">⃒","nvHarr":"⤄","nvinfin":"⧞","nvlArr":"⤂","nvle":"≤⃒","nvlt":"<⃒","nvltrie":"⊴⃒","nvrArr":"⤃","nvrtrie":"⊵⃒","nvsim":"∼⃒","nwarhk":"⤣","nwarr":"↖","nwArr":"⇖","nwarrow":"↖","nwnear":"⤧","Oacute":"Ó","oacute":"ó","oast":"⊛","Ocirc":"Ô","ocirc":"ô","ocir":"⊚","Ocy":"О","ocy":"о","odash":"⊝","Odblac":"Ő","odblac":"ő","odiv":"⨸","odot":"⊙","odsold":"⦼","OElig":"Œ","oelig":"œ","ofcir":"⦿","Ofr":"𝔒","ofr":"𝔬","ogon":"˛","Ograve":"Ò","ograve":"ò","ogt":"⧁","ohbar":"⦵","ohm":"Ω","oint":"∮","olarr":"↺","olcir":"⦾","olcross":"⦻","oline":"‾","olt":"⧀","Omacr":"Ō","omacr":"ō","Omega":"Ω","omega":"ω","Omicron":"Ο","omicron":"ο","omid":"⦶","ominus":"⊖","Oopf":"𝕆","oopf":"𝕠","opar":"⦷","OpenCurlyDoubleQuote":"“","OpenCurlyQuote":"‘","operp":"⦹","oplus":"⊕","orarr":"↻","Or":"⩔","or":"∨","ord":"⩝","order":"ℴ","orderof":"ℴ","ordf":"ª","ordm":"º","origof":"⊶","oror":"⩖","orslope":"⩗","orv":"⩛","oS":"Ⓢ","Oscr":"𝒪","oscr":"ℴ","Oslash":"Ø","oslash":"ø","osol":"⊘","Otilde":"Õ","otilde":"õ","otimesas":"⨶","Otimes":"⨷","otimes":"⊗","Ouml":"Ö","ouml":"ö","ovbar":"⌽","OverBar":"‾","OverBrace":"⏞","OverBracket":"⎴","OverParenthesis":"⏜","para":"¶","parallel":"∥","par":"∥","parsim":"⫳","parsl":"⫽","part":"∂","PartialD":"∂","Pcy":"П","pcy":"п","percnt":"%","period":".","permil":"‰","perp":"⊥","pertenk":"‱","Pfr":"𝔓","pfr":"𝔭","Phi":"Φ","phi":"φ","phiv":"ϕ","phmmat":"ℳ","phone":"☎","Pi":"Π","pi":"π","pitchfork":"⋔","piv":"ϖ","planck":"ℏ","planckh":"ℎ","plankv":"ℏ","plusacir":"⨣","plusb":"⊞","pluscir":"⨢","plus":"+","plusdo":"∔","plusdu":"⨥","pluse":"⩲","PlusMinus":"±","plusmn":"±","plussim":"⨦","plustwo":"⨧","pm":"±","Poincareplane":"ℌ","pointint":"⨕","popf":"𝕡","Popf":"ℙ","pound":"£","prap":"⪷","Pr":"⪻","pr":"≺","prcue":"≼","precapprox":"⪷","prec":"≺","preccurlyeq":"≼","Precedes":"≺","PrecedesEqual":"⪯","PrecedesSlantEqual":"≼","PrecedesTilde":"≾","preceq":"⪯","precnapprox":"⪹","precneqq":"⪵","precnsim":"⋨","pre":"⪯","prE":"⪳","precsim":"≾","prime":"′","Prime":"″","primes":"ℙ","prnap":"⪹","prnE":"⪵","prnsim":"⋨","prod":"∏","Product":"∏","profalar":"⌮","profline":"⌒","profsurf":"⌓","prop":"∝","Proportional":"∝","Proportion":"∷","propto":"∝","prsim":"≾","prurel":"⊰","Pscr":"𝒫","pscr":"𝓅","Psi":"Ψ","psi":"ψ","puncsp":" ","Qfr":"𝔔","qfr":"𝔮","qint":"⨌","qopf":"𝕢","Qopf":"ℚ","qprime":"⁗","Qscr":"𝒬","qscr":"𝓆","quaternions":"ℍ","quatint":"⨖","quest":"?","questeq":"≟","quot":"\\"","QUOT":"\\"","rAarr":"⇛","race":"∽̱","Racute":"Ŕ","racute":"ŕ","radic":"√","raemptyv":"⦳","rang":"⟩","Rang":"⟫","rangd":"⦒","range":"⦥","rangle":"⟩","raquo":"»","rarrap":"⥵","rarrb":"⇥","rarrbfs":"⤠","rarrc":"⤳","rarr":"→","Rarr":"↠","rArr":"⇒","rarrfs":"⤞","rarrhk":"↪","rarrlp":"↬","rarrpl":"⥅","rarrsim":"⥴","Rarrtl":"⤖","rarrtl":"↣","rarrw":"↝","ratail":"⤚","rAtail":"⤜","ratio":"∶","rationals":"ℚ","rbarr":"⤍","rBarr":"⤏","RBarr":"⤐","rbbrk":"❳","rbrace":"}","rbrack":"]","rbrke":"⦌","rbrksld":"⦎","rbrkslu":"⦐","Rcaron":"Ř","rcaron":"ř","Rcedil":"Ŗ","rcedil":"ŗ","rceil":"⌉","rcub":"}","Rcy":"Р","rcy":"р","rdca":"⤷","rdldhar":"⥩","rdquo":"”","rdquor":"”","rdsh":"↳","real":"ℜ","realine":"ℛ","realpart":"ℜ","reals":"ℝ","Re":"ℜ","rect":"▭","reg":"®","REG":"®","ReverseElement":"∋","ReverseEquilibrium":"⇋","ReverseUpEquilibrium":"⥯","rfisht":"⥽","rfloor":"⌋","rfr":"𝔯","Rfr":"ℜ","rHar":"⥤","rhard":"⇁","rharu":"⇀","rharul":"⥬","Rho":"Ρ","rho":"ρ","rhov":"ϱ","RightAngleBracket":"⟩","RightArrowBar":"⇥","rightarrow":"→","RightArrow":"→","Rightarrow":"⇒","RightArrowLeftArrow":"⇄","rightarrowtail":"↣","RightCeiling":"⌉","RightDoubleBracket":"⟧","RightDownTeeVector":"⥝","RightDownVectorBar":"⥕","RightDownVector":"⇂","RightFloor":"⌋","rightharpoondown":"⇁","rightharpoonup":"⇀","rightleftarrows":"⇄","rightleftharpoons":"⇌","rightrightarrows":"⇉","rightsquigarrow":"↝","RightTeeArrow":"↦","RightTee":"⊢","RightTeeVector":"⥛","rightthreetimes":"⋌","RightTriangleBar":"⧐","RightTriangle":"⊳","RightTriangleEqual":"⊵","RightUpDownVector":"⥏","RightUpTeeVector":"⥜","RightUpVectorBar":"⥔","RightUpVector":"↾","RightVectorBar":"⥓","RightVector":"⇀","ring":"˚","risingdotseq":"≓","rlarr":"⇄","rlhar":"⇌","rlm":"‏","rmoustache":"⎱","rmoust":"⎱","rnmid":"⫮","roang":"⟭","roarr":"⇾","robrk":"⟧","ropar":"⦆","ropf":"𝕣","Ropf":"ℝ","roplus":"⨮","rotimes":"⨵","RoundImplies":"⥰","rpar":")","rpargt":"⦔","rppolint":"⨒","rrarr":"⇉","Rrightarrow":"⇛","rsaquo":"›","rscr":"𝓇","Rscr":"ℛ","rsh":"↱","Rsh":"↱","rsqb":"]","rsquo":"’","rsquor":"’","rthree":"⋌","rtimes":"⋊","rtri":"▹","rtrie":"⊵","rtrif":"▸","rtriltri":"⧎","RuleDelayed":"⧴","ruluhar":"⥨","rx":"℞","Sacute":"Ś","sacute":"ś","sbquo":"‚","scap":"⪸","Scaron":"Š","scaron":"š","Sc":"⪼","sc":"≻","sccue":"≽","sce":"⪰","scE":"⪴","Scedil":"Ş","scedil":"ş","Scirc":"Ŝ","scirc":"ŝ","scnap":"⪺","scnE":"⪶","scnsim":"⋩","scpolint":"⨓","scsim":"≿","Scy":"С","scy":"с","sdotb":"⊡","sdot":"⋅","sdote":"⩦","searhk":"⤥","searr":"↘","seArr":"⇘","searrow":"↘","sect":"§","semi":";","seswar":"⤩","setminus":"∖","setmn":"∖","sext":"✶","Sfr":"𝔖","sfr":"𝔰","sfrown":"⌢","sharp":"♯","SHCHcy":"Щ","shchcy":"щ","SHcy":"Ш","shcy":"ш","ShortDownArrow":"↓","ShortLeftArrow":"←","shortmid":"∣","shortparallel":"∥","ShortRightArrow":"→","ShortUpArrow":"↑","shy":"­","Sigma":"Σ","sigma":"σ","sigmaf":"ς","sigmav":"ς","sim":"∼","simdot":"⩪","sime":"≃","simeq":"≃","simg":"⪞","simgE":"⪠","siml":"⪝","simlE":"⪟","simne":"≆","simplus":"⨤","simrarr":"⥲","slarr":"←","SmallCircle":"∘","smallsetminus":"∖","smashp":"⨳","smeparsl":"⧤","smid":"∣","smile":"⌣","smt":"⪪","smte":"⪬","smtes":"⪬︀","SOFTcy":"Ь","softcy":"ь","solbar":"⌿","solb":"⧄","sol":"/","Sopf":"𝕊","sopf":"𝕤","spades":"♠","spadesuit":"♠","spar":"∥","sqcap":"⊓","sqcaps":"⊓︀","sqcup":"⊔","sqcups":"⊔︀","Sqrt":"√","sqsub":"⊏","sqsube":"⊑","sqsubset":"⊏","sqsubseteq":"⊑","sqsup":"⊐","sqsupe":"⊒","sqsupset":"⊐","sqsupseteq":"⊒","square":"□","Square":"□","SquareIntersection":"⊓","SquareSubset":"⊏","SquareSubsetEqual":"⊑","SquareSuperset":"⊐","SquareSupersetEqual":"⊒","SquareUnion":"⊔","squarf":"▪","squ":"□","squf":"▪","srarr":"→","Sscr":"𝒮","sscr":"𝓈","ssetmn":"∖","ssmile":"⌣","sstarf":"⋆","Star":"⋆","star":"☆","starf":"★","straightepsilon":"ϵ","straightphi":"ϕ","strns":"¯","sub":"⊂","Sub":"⋐","subdot":"⪽","subE":"⫅","sube":"⊆","subedot":"⫃","submult":"⫁","subnE":"⫋","subne":"⊊","subplus":"⪿","subrarr":"⥹","subset":"⊂","Subset":"⋐","subseteq":"⊆","subseteqq":"⫅","SubsetEqual":"⊆","subsetneq":"⊊","subsetneqq":"⫋","subsim":"⫇","subsub":"⫕","subsup":"⫓","succapprox":"⪸","succ":"≻","succcurlyeq":"≽","Succeeds":"≻","SucceedsEqual":"⪰","SucceedsSlantEqual":"≽","SucceedsTilde":"≿","succeq":"⪰","succnapprox":"⪺","succneqq":"⪶","succnsim":"⋩","succsim":"≿","SuchThat":"∋","sum":"∑","Sum":"∑","sung":"♪","sup1":"¹","sup2":"²","sup3":"³","sup":"⊃","Sup":"⋑","supdot":"⪾","supdsub":"⫘","supE":"⫆","supe":"⊇","supedot":"⫄","Superset":"⊃","SupersetEqual":"⊇","suphsol":"⟉","suphsub":"⫗","suplarr":"⥻","supmult":"⫂","supnE":"⫌","supne":"⊋","supplus":"⫀","supset":"⊃","Supset":"⋑","supseteq":"⊇","supseteqq":"⫆","supsetneq":"⊋","supsetneqq":"⫌","supsim":"⫈","supsub":"⫔","supsup":"⫖","swarhk":"⤦","swarr":"↙","swArr":"⇙","swarrow":"↙","swnwar":"⤪","szlig":"ß","Tab":"\\t","target":"⌖","Tau":"Τ","tau":"τ","tbrk":"⎴","Tcaron":"Ť","tcaron":"ť","Tcedil":"Ţ","tcedil":"ţ","Tcy":"Т","tcy":"т","tdot":"⃛","telrec":"⌕","Tfr":"𝔗","tfr":"𝔱","there4":"∴","therefore":"∴","Therefore":"∴","Theta":"Θ","theta":"θ","thetasym":"ϑ","thetav":"ϑ","thickapprox":"≈","thicksim":"∼","ThickSpace":"  ","ThinSpace":" ","thinsp":" ","thkap":"≈","thksim":"∼","THORN":"Þ","thorn":"þ","tilde":"˜","Tilde":"∼","TildeEqual":"≃","TildeFullEqual":"≅","TildeTilde":"≈","timesbar":"⨱","timesb":"⊠","times":"×","timesd":"⨰","tint":"∭","toea":"⤨","topbot":"⌶","topcir":"⫱","top":"⊤","Topf":"𝕋","topf":"𝕥","topfork":"⫚","tosa":"⤩","tprime":"‴","trade":"™","TRADE":"™","triangle":"▵","triangledown":"▿","triangleleft":"◃","trianglelefteq":"⊴","triangleq":"≜","triangleright":"▹","trianglerighteq":"⊵","tridot":"◬","trie":"≜","triminus":"⨺","TripleDot":"⃛","triplus":"⨹","trisb":"⧍","tritime":"⨻","trpezium":"⏢","Tscr":"𝒯","tscr":"𝓉","TScy":"Ц","tscy":"ц","TSHcy":"Ћ","tshcy":"ћ","Tstrok":"Ŧ","tstrok":"ŧ","twixt":"≬","twoheadleftarrow":"↞","twoheadrightarrow":"↠","Uacute":"Ú","uacute":"ú","uarr":"↑","Uarr":"↟","uArr":"⇑","Uarrocir":"⥉","Ubrcy":"Ў","ubrcy":"ў","Ubreve":"Ŭ","ubreve":"ŭ","Ucirc":"Û","ucirc":"û","Ucy":"У","ucy":"у","udarr":"⇅","Udblac":"Ű","udblac":"ű","udhar":"⥮","ufisht":"⥾","Ufr":"𝔘","ufr":"𝔲","Ugrave":"Ù","ugrave":"ù","uHar":"⥣","uharl":"↿","uharr":"↾","uhblk":"▀","ulcorn":"⌜","ulcorner":"⌜","ulcrop":"⌏","ultri":"◸","Umacr":"Ū","umacr":"ū","uml":"¨","UnderBar":"_","UnderBrace":"⏟","UnderBracket":"⎵","UnderParenthesis":"⏝","Union":"⋃","UnionPlus":"⊎","Uogon":"Ų","uogon":"ų","Uopf":"𝕌","uopf":"𝕦","UpArrowBar":"⤒","uparrow":"↑","UpArrow":"↑","Uparrow":"⇑","UpArrowDownArrow":"⇅","updownarrow":"↕","UpDownArrow":"↕","Updownarrow":"⇕","UpEquilibrium":"⥮","upharpoonleft":"↿","upharpoonright":"↾","uplus":"⊎","UpperLeftArrow":"↖","UpperRightArrow":"↗","upsi":"υ","Upsi":"ϒ","upsih":"ϒ","Upsilon":"Υ","upsilon":"υ","UpTeeArrow":"↥","UpTee":"⊥","upuparrows":"⇈","urcorn":"⌝","urcorner":"⌝","urcrop":"⌎","Uring":"Ů","uring":"ů","urtri":"◹","Uscr":"𝒰","uscr":"𝓊","utdot":"⋰","Utilde":"Ũ","utilde":"ũ","utri":"▵","utrif":"▴","uuarr":"⇈","Uuml":"Ü","uuml":"ü","uwangle":"⦧","vangrt":"⦜","varepsilon":"ϵ","varkappa":"ϰ","varnothing":"∅","varphi":"ϕ","varpi":"ϖ","varpropto":"∝","varr":"↕","vArr":"⇕","varrho":"ϱ","varsigma":"ς","varsubsetneq":"⊊︀","varsubsetneqq":"⫋︀","varsupsetneq":"⊋︀","varsupsetneqq":"⫌︀","vartheta":"ϑ","vartriangleleft":"⊲","vartriangleright":"⊳","vBar":"⫨","Vbar":"⫫","vBarv":"⫩","Vcy":"В","vcy":"в","vdash":"⊢","vDash":"⊨","Vdash":"⊩","VDash":"⊫","Vdashl":"⫦","veebar":"⊻","vee":"∨","Vee":"⋁","veeeq":"≚","vellip":"⋮","verbar":"|","Verbar":"‖","vert":"|","Vert":"‖","VerticalBar":"∣","VerticalLine":"|","VerticalSeparator":"❘","VerticalTilde":"≀","VeryThinSpace":" ","Vfr":"𝔙","vfr":"𝔳","vltri":"⊲","vnsub":"⊂⃒","vnsup":"⊃⃒","Vopf":"𝕍","vopf":"𝕧","vprop":"∝","vrtri":"⊳","Vscr":"𝒱","vscr":"𝓋","vsubnE":"⫋︀","vsubne":"⊊︀","vsupnE":"⫌︀","vsupne":"⊋︀","Vvdash":"⊪","vzigzag":"⦚","Wcirc":"Ŵ","wcirc":"ŵ","wedbar":"⩟","wedge":"∧","Wedge":"⋀","wedgeq":"≙","weierp":"℘","Wfr":"𝔚","wfr":"𝔴","Wopf":"𝕎","wopf":"𝕨","wp":"℘","wr":"≀","wreath":"≀","Wscr":"𝒲","wscr":"𝓌","xcap":"⋂","xcirc":"◯","xcup":"⋃","xdtri":"▽","Xfr":"𝔛","xfr":"𝔵","xharr":"⟷","xhArr":"⟺","Xi":"Ξ","xi":"ξ","xlarr":"⟵","xlArr":"⟸","xmap":"⟼","xnis":"⋻","xodot":"⨀","Xopf":"𝕏","xopf":"𝕩","xoplus":"⨁","xotime":"⨂","xrarr":"⟶","xrArr":"⟹","Xscr":"𝒳","xscr":"𝓍","xsqcup":"⨆","xuplus":"⨄","xutri":"△","xvee":"⋁","xwedge":"⋀","Yacute":"Ý","yacute":"ý","YAcy":"Я","yacy":"я","Ycirc":"Ŷ","ycirc":"ŷ","Ycy":"Ы","ycy":"ы","yen":"¥","Yfr":"𝔜","yfr":"𝔶","YIcy":"Ї","yicy":"ї","Yopf":"𝕐","yopf":"𝕪","Yscr":"𝒴","yscr":"𝓎","YUcy":"Ю","yucy":"ю","yuml":"ÿ","Yuml":"Ÿ","Zacute":"Ź","zacute":"ź","Zcaron":"Ž","zcaron":"ž","Zcy":"З","zcy":"з","Zdot":"Ż","zdot":"ż","zeetrf":"ℨ","ZeroWidthSpace":"​","Zeta":"Ζ","zeta":"ζ","zfr":"𝔷","Zfr":"ℨ","ZHcy":"Ж","zhcy":"ж","zigrarr":"⇝","zopf":"𝕫","Zopf":"ℤ","Zscr":"𝒵","zscr":"𝓏","zwj":"‍","zwnj":"‌"}')},function(e){e.exports=JSON.parse('{"Aacute":"Á","aacute":"á","Acirc":"Â","acirc":"â","acute":"´","AElig":"Æ","aelig":"æ","Agrave":"À","agrave":"à","amp":"&","AMP":"&","Aring":"Å","aring":"å","Atilde":"Ã","atilde":"ã","Auml":"Ä","auml":"ä","brvbar":"¦","Ccedil":"Ç","ccedil":"ç","cedil":"¸","cent":"¢","copy":"©","COPY":"©","curren":"¤","deg":"°","divide":"÷","Eacute":"É","eacute":"é","Ecirc":"Ê","ecirc":"ê","Egrave":"È","egrave":"è","ETH":"Ð","eth":"ð","Euml":"Ë","euml":"ë","frac12":"½","frac14":"¼","frac34":"¾","gt":">","GT":">","Iacute":"Í","iacute":"í","Icirc":"Î","icirc":"î","iexcl":"¡","Igrave":"Ì","igrave":"ì","iquest":"¿","Iuml":"Ï","iuml":"ï","laquo":"«","lt":"<","LT":"<","macr":"¯","micro":"µ","middot":"·","nbsp":" ","not":"¬","Ntilde":"Ñ","ntilde":"ñ","Oacute":"Ó","oacute":"ó","Ocirc":"Ô","ocirc":"ô","Ograve":"Ò","ograve":"ò","ordf":"ª","ordm":"º","Oslash":"Ø","oslash":"ø","Otilde":"Õ","otilde":"õ","Ouml":"Ö","ouml":"ö","para":"¶","plusmn":"±","pound":"£","quot":"\\"","QUOT":"\\"","raquo":"»","reg":"®","REG":"®","sect":"§","shy":"­","sup1":"¹","sup2":"²","sup3":"³","szlig":"ß","THORN":"Þ","thorn":"þ","times":"×","Uacute":"Ú","uacute":"ú","Ucirc":"Û","ucirc":"û","Ugrave":"Ù","ugrave":"ù","uml":"¨","Uuml":"Ü","uuml":"ü","Yacute":"Ý","yacute":"ý","yen":"¥","yuml":"ÿ"}')},function(e){e.exports=JSON.parse('{"amp":"&","apos":"\'","gt":">","lt":"<","quot":"\\""}')},function(e,t,r){"use strict";var n,a="object"==typeof Reflect?Reflect:null,o=a&&"function"==typeof a.apply?a.apply:function(e,t,r){return Function.prototype.apply.call(e,t,r)};n=a&&"function"==typeof a.ownKeys?a.ownKeys:Object.getOwnPropertySymbols?function(e){return Object.getOwnPropertyNames(e).concat(Object.getOwnPropertySymbols(e))}:function(e){return Object.getOwnPropertyNames(e)};var i=Number.isNaN||function(e){return e!=e};function s(){s.init.call(this)}e.exports=s,e.exports.once=function(e,t){return new Promise((function(r,n){function a(){void 0!==o&&e.removeListener("error",o),r([].slice.call(arguments))}var o;"error"!==t&&(o=function(r){e.removeListener(t,a),n(r)},e.once("error",o)),e.once(t,a)}))},s.EventEmitter=s,s.prototype._events=void 0,s.prototype._eventsCount=0,s.prototype._maxListeners=void 0;var c=10;function l(e){if("function"!=typeof e)throw new TypeError('The "listener" argument must be of type Function. Received type '+typeof e)}function u(e){return void 0===e._maxListeners?s.defaultMaxListeners:e._maxListeners}function p(e,t,r,n){var a,o,i,s;if(l(r),void 0===(o=e._events)?(o=e._events=Object.create(null),e._eventsCount=0):(void 0!==o.newListener&&(e.emit("newListener",t,r.listener?r.listener:r),o=e._events),i=o[t]),void 0===i)i=o[t]=r,++e._eventsCount;else if("function"==typeof i?i=o[t]=n?[r,i]:[i,r]:n?i.unshift(r):i.push(r),(a=u(e))>0&&i.length>a&&!i.warned){i.warned=!0;var c=new Error("Possible EventEmitter memory leak detected. "+i.length+" "+String(t)+" listeners added. Use emitter.setMaxListeners() to increase limit");c.name="MaxListenersExceededWarning",c.emitter=e,c.type=t,c.count=i.length,s=c,console&&console.warn&&console.warn(s)}return e}function d(){if(!this.fired)return this.target.removeListener(this.type,this.wrapFn),this.fired=!0,0===arguments.length?this.listener.call(this.target):this.listener.apply(this.target,arguments)}function h(e,t,r){var n={fired:!1,wrapFn:void 0,target:e,type:t,listener:r},a=d.bind(n);return a.listener=r,n.wrapFn=a,a}function f(e,t,r){var n=e._events;if(void 0===n)return[];var a=n[t];return void 0===a?[]:"function"==typeof a?r?[a.listener||a]:[a]:r?function(e){for(var t=new Array(e.length),r=0;r<t.length;++r)t[r]=e[r].listener||e[r];return t}(a):g(a,a.length)}function m(e){var t=this._events;if(void 0!==t){var r=t[e];if("function"==typeof r)return 1;if(void 0!==r)return r.length}return 0}function g(e,t){for(var r=new Array(t),n=0;n<t;++n)r[n]=e[n];return r}Object.defineProperty(s,"defaultMaxListeners",{enumerable:!0,get:function(){return c},set:function(e){if("number"!=typeof e||e<0||i(e))throw new RangeError('The value of "defaultMaxListeners" is out of range. It must be a non-negative number. Received '+e+".");c=e}}),s.init=function(){void 0!==this._events&&this._events!==Object.getPrototypeOf(this)._events||(this._events=Object.create(null),this._eventsCount=0),this._maxListeners=this._maxListeners||void 0},s.prototype.setMaxListeners=function(e){if("number"!=typeof e||e<0||i(e))throw new RangeError('The value of "n" is out of range. It must be a non-negative number. Received '+e+".");return this._maxListeners=e,this},s.prototype.getMaxListeners=function(){return u(this)},s.prototype.emit=function(e){for(var t=[],r=1;r<arguments.length;r++)t.push(arguments[r]);var n="error"===e,a=this._events;if(void 0!==a)n=n&&void 0===a.error;else if(!n)return!1;if(n){var i;if(t.length>0&&(i=t[0]),i instanceof Error)throw i;var s=new Error("Unhandled error."+(i?" ("+i.message+")":""));throw s.context=i,s}var c=a[e];if(void 0===c)return!1;if("function"==typeof c)o(c,this,t);else{var l=c.length,u=g(c,l);for(r=0;r<l;++r)o(u[r],this,t)}return!0},s.prototype.addListener=function(e,t){return p(this,e,t,!1)},s.prototype.on=s.prototype.addListener,s.prototype.prependListener=function(e,t){return p(this,e,t,!0)},s.prototype.once=function(e,t){return l(t),this.on(e,h(this,e,t)),this},s.prototype.prependOnceListener=function(e,t){return l(t),this.prependListener(e,h(this,e,t)),this},s.prototype.removeListener=function(e,t){var r,n,a,o,i;if(l(t),void 0===(n=this._events))return this;if(void 0===(r=n[e]))return this;if(r===t||r.listener===t)0==--this._eventsCount?this._events=Object.create(null):(delete n[e],n.removeListener&&this.emit("removeListener",e,r.listener||t));else if("function"!=typeof r){for(a=-1,o=r.length-1;o>=0;o--)if(r[o]===t||r[o].listener===t){i=r[o].listener,a=o;break}if(a<0)return this;0===a?r.shift():function(e,t){for(;t+1<e.length;t++)e[t]=e[t+1];e.pop()}(r,a),1===r.length&&(n[e]=r[0]),void 0!==n.removeListener&&this.emit("removeListener",e,i||t)}return this},s.prototype.off=s.prototype.removeListener,s.prototype.removeAllListeners=function(e){var t,r,n;if(void 0===(r=this._events))return this;if(void 0===r.removeListener)return 0===arguments.length?(this._events=Object.create(null),this._eventsCount=0):void 0!==r[e]&&(0==--this._eventsCount?this._events=Object.create(null):delete r[e]),this;if(0===arguments.length){var a,o=Object.keys(r);for(n=0;n<o.length;++n)"removeListener"!==(a=o[n])&&this.removeAllListeners(a);return this.removeAllListeners("removeListener"),this._events=Object.create(null),this._eventsCount=0,this}if("function"==typeof(t=r[e]))this.removeListener(e,t);else if(void 0!==t)for(n=t.length-1;n>=0;n--)this.removeListener(e,t[n]);return this},s.prototype.listeners=function(e){return f(this,e,!0)},s.prototype.rawListeners=function(e){return f(this,e,!1)},s.listenerCount=function(e,t){return"function"==typeof e.listenerCount?e.listenerCount(t):m.call(e,t)},s.prototype.listenerCount=m,s.prototype.eventNames=function(){return this._eventsCount>0?n(this._events):[]}},function(e,t,r){var n=r(24),a=e.exports=Object.create(n),o={tagName:"name"};Object.keys(o).forEach((function(e){var t=o[e];Object.defineProperty(a,e,{get:function(){return this[t]||null},set:function(e){return this[t]=e,e}})}))},function(e,t,r){var n=r(23),a=r(25);function o(e,t){this.init(e,t)}function i(e,t){return a.getElementsByTagName(e,t,!0)}function s(e,t){return a.getElementsByTagName(e,t,!0,1)[0]}function c(e,t,r){return a.getText(a.getElementsByTagName(e,t,r,1)).trim()}function l(e,t,r,n,a){var o=c(r,n,a);o&&(e[t]=o)}r(17)(o,n),o.prototype.init=n;var u=function(e){return"rss"===e||"feed"===e||"rdf:RDF"===e};o.prototype.onend=function(){var e,t,r={},a=s(u,this.dom);a&&("feed"===a.name?(t=a.children,r.type="atom",l(r,"id","id",t),l(r,"title","title",t),(e=s("link",t))&&(e=e.attribs)&&(e=e.href)&&(r.link=e),l(r,"description","subtitle",t),(e=c("updated",t))&&(r.updated=new Date(e)),l(r,"author","email",t,!0),r.items=i("entry",t).map((function(e){var t,r={};return l(r,"id","id",e=e.children),l(r,"title","title",e),(t=s("link",e))&&(t=t.attribs)&&(t=t.href)&&(r.link=t),(t=c("summary",e)||c("content",e))&&(r.description=t),(t=c("updated",e))&&(r.pubDate=new Date(t)),r}))):(t=s("channel",a.children).children,r.type=a.name.substr(0,3),r.id="",l(r,"title","title",t),l(r,"link","link",t),l(r,"description","description",t),(e=c("lastBuildDate",t))&&(r.updated=new Date(e)),l(r,"author","managingEditor",t,!0),r.items=i("item",a.children).map((function(e){var t,r={};return l(r,"id","guid",e=e.children),l(r,"title","title",e),l(r,"link","link",e),l(r,"description","description",e),(t=c("pubDate",e))&&(r.pubDate=new Date(t)),r})))),this.dom=r,n.prototype._handleCallback.call(this,a?null:Error("couldn't find root of feed"))},e.exports=o},function(e,t,r){var n=r(16),a=r(59),o=n.isTag;e.exports={getInnerHTML:function(e,t){return e.children?e.children.map((function(e){return a(e,t)})).join(""):""},getOuterHTML:a,getText:function e(t){return Array.isArray(t)?t.map(e).join(""):o(t)?"br"===t.name?"\n":e(t.children):t.type===n.CDATA?e(t.children):t.type===n.Text?t.data:""}}},function(e,t,r){var n=r(60),a=r(61),o=r(65);o.elementNames.__proto__=null,o.attributeNames.__proto__=null;var i={__proto__:null,style:!0,script:!0,xmp:!0,iframe:!0,noembed:!0,noframes:!0,plaintext:!0,noscript:!0};var s={__proto__:null,area:!0,base:!0,basefont:!0,br:!0,col:!0,command:!0,embed:!0,frame:!0,hr:!0,img:!0,input:!0,isindex:!0,keygen:!0,link:!0,meta:!0,param:!0,source:!0,track:!0,wbr:!0},c=e.exports=function(e,t){Array.isArray(e)||e.cheerio||(e=[e]),t=t||{};for(var r="",a=0;a<e.length;a++){var o=e[a];"root"===o.type?r+=c(o.children,t):n.isTag(o)?r+=u(o,t):o.type===n.Directive?r+=p(o):o.type===n.Comment?r+=f(o):o.type===n.CDATA?r+=h(o):r+=d(o,t)}return r},l=["mi","mo","mn","ms","mtext","annotation-xml","foreignObject","desc","title"];function u(e,t){"foreign"===t.xmlMode&&(e.name=o.elementNames[e.name]||e.name,e.parent&&l.indexOf(e.parent.name)>=0&&(t=Object.assign({},t,{xmlMode:!1}))),!t.xmlMode&&["svg","math"].indexOf(e.name)>=0&&(t=Object.assign({},t,{xmlMode:"foreign"}));var r="<"+e.name,n=function(e,t){if(e){var r,n="";for(var i in e)r=e[i],n&&(n+=" "),"foreign"===t.xmlMode&&(i=o.attributeNames[i]||i),n+=i,(null!==r&&""!==r||t.xmlMode)&&(n+='="'+(t.decodeEntities?a.encodeXML(r):r.replace(/\"/g,"&quot;"))+'"');return n}}(e.attribs,t);return n&&(r+=" "+n),!t.xmlMode||e.children&&0!==e.children.length?(r+=">",e.children&&(r+=c(e.children,t)),s[e.name]&&!t.xmlMode||(r+="</"+e.name+">")):r+="/>",r}function p(e){return"<"+e.data+">"}function d(e,t){var r=e.data||"";return!t.decodeEntities||e.parent&&e.parent.name in i||(r=a.encodeXML(r)),r}function h(e){return"<![CDATA["+e.children[0].data+"]]>"}function f(e){return"\x3c!--"+e.data+"--\x3e"}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.Doctype=t.CDATA=t.Tag=t.Style=t.Script=t.Comment=t.Directive=t.Text=t.isTag=void 0,t.isTag=function(e){return"tag"===e.type||"script"===e.type||"style"===e.type},t.Text="text",t.Directive="directive",t.Comment="comment",t.Script="script",t.Style="style",t.Tag="tag",t.CDATA="cdata",t.Doctype="doctype"},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.decodeXMLStrict=t.decodeHTML5Strict=t.decodeHTML4Strict=t.decodeHTML5=t.decodeHTML4=t.decodeHTMLStrict=t.decodeHTML=t.decodeXML=t.encodeHTML5=t.encodeHTML4=t.escape=t.encodeHTML=t.encodeXML=t.encode=t.decodeStrict=t.decode=void 0;var n=r(26),a=r(29);t.decode=function(e,t){return(!t||t<=0?n.decodeXML:n.decodeHTML)(e)},t.decodeStrict=function(e,t){return(!t||t<=0?n.decodeXML:n.decodeHTMLStrict)(e)},t.encode=function(e,t){return(!t||t<=0?a.encodeXML:a.encodeHTML)(e)};var o=r(29);Object.defineProperty(t,"encodeXML",{enumerable:!0,get:function(){return o.encodeXML}}),Object.defineProperty(t,"encodeHTML",{enumerable:!0,get:function(){return o.encodeHTML}}),Object.defineProperty(t,"escape",{enumerable:!0,get:function(){return o.escape}}),Object.defineProperty(t,"encodeHTML4",{enumerable:!0,get:function(){return o.encodeHTML}}),Object.defineProperty(t,"encodeHTML5",{enumerable:!0,get:function(){return o.encodeHTML}});var i=r(26);Object.defineProperty(t,"decodeXML",{enumerable:!0,get:function(){return i.decodeXML}}),Object.defineProperty(t,"decodeHTML",{enumerable:!0,get:function(){return i.decodeHTML}}),Object.defineProperty(t,"decodeHTMLStrict",{enumerable:!0,get:function(){return i.decodeHTMLStrict}}),Object.defineProperty(t,"decodeHTML4",{enumerable:!0,get:function(){return i.decodeHTML}}),Object.defineProperty(t,"decodeHTML5",{enumerable:!0,get:function(){return i.decodeHTML}}),Object.defineProperty(t,"decodeHTML4Strict",{enumerable:!0,get:function(){return i.decodeHTMLStrict}}),Object.defineProperty(t,"decodeHTML5Strict",{enumerable:!0,get:function(){return i.decodeHTMLStrict}}),Object.defineProperty(t,"decodeXMLStrict",{enumerable:!0,get:function(){return i.decodeXML}})},function(e){e.exports=JSON.parse('{"Aacute":"Á","aacute":"á","Acirc":"Â","acirc":"â","acute":"´","AElig":"Æ","aelig":"æ","Agrave":"À","agrave":"à","amp":"&","AMP":"&","Aring":"Å","aring":"å","Atilde":"Ã","atilde":"ã","Auml":"Ä","auml":"ä","brvbar":"¦","Ccedil":"Ç","ccedil":"ç","cedil":"¸","cent":"¢","copy":"©","COPY":"©","curren":"¤","deg":"°","divide":"÷","Eacute":"É","eacute":"é","Ecirc":"Ê","ecirc":"ê","Egrave":"È","egrave":"è","ETH":"Ð","eth":"ð","Euml":"Ë","euml":"ë","frac12":"½","frac14":"¼","frac34":"¾","gt":">","GT":">","Iacute":"Í","iacute":"í","Icirc":"Î","icirc":"î","iexcl":"¡","Igrave":"Ì","igrave":"ì","iquest":"¿","Iuml":"Ï","iuml":"ï","laquo":"«","lt":"<","LT":"<","macr":"¯","micro":"µ","middot":"·","nbsp":" ","not":"¬","Ntilde":"Ñ","ntilde":"ñ","Oacute":"Ó","oacute":"ó","Ocirc":"Ô","ocirc":"ô","Ograve":"Ò","ograve":"ò","ordf":"ª","ordm":"º","Oslash":"Ø","oslash":"ø","Otilde":"Õ","otilde":"õ","Ouml":"Ö","ouml":"ö","para":"¶","plusmn":"±","pound":"£","quot":"\\"","QUOT":"\\"","raquo":"»","reg":"®","REG":"®","sect":"§","shy":"­","sup1":"¹","sup2":"²","sup3":"³","szlig":"ß","THORN":"Þ","thorn":"þ","times":"×","Uacute":"Ú","uacute":"ú","Ucirc":"Û","ucirc":"û","Ugrave":"Ù","ugrave":"ù","uml":"¨","Uuml":"Ü","uuml":"ü","Yacute":"Ý","yacute":"ý","yen":"¥","yuml":"ÿ"}')},function(e,t,r){"use strict";var n=this&&this.__importDefault||function(e){return e&&e.__esModule?e:{default:e}};Object.defineProperty(t,"__esModule",{value:!0});var a=n(r(64));t.default=function(e){if(e>=55296&&e<=57343||e>1114111)return"�";e in a.default&&(e=a.default[e]);var t="";return e>65535&&(e-=65536,t+=String.fromCharCode(e>>>10&1023|55296),e=56320|1023&e),t+=String.fromCharCode(e)}},function(e){e.exports=JSON.parse('{"0":65533,"128":8364,"130":8218,"131":402,"132":8222,"133":8230,"134":8224,"135":8225,"136":710,"137":8240,"138":352,"139":8249,"140":338,"142":381,"145":8216,"146":8217,"147":8220,"148":8221,"149":8226,"150":8211,"151":8212,"152":732,"153":8482,"154":353,"155":8250,"156":339,"158":382,"159":376}')},function(e){e.exports=JSON.parse('{"elementNames":{"altglyph":"altGlyph","altglyphdef":"altGlyphDef","altglyphitem":"altGlyphItem","animatecolor":"animateColor","animatemotion":"animateMotion","animatetransform":"animateTransform","clippath":"clipPath","feblend":"feBlend","fecolormatrix":"feColorMatrix","fecomponenttransfer":"feComponentTransfer","fecomposite":"feComposite","feconvolvematrix":"feConvolveMatrix","fediffuselighting":"feDiffuseLighting","fedisplacementmap":"feDisplacementMap","fedistantlight":"feDistantLight","fedropshadow":"feDropShadow","feflood":"feFlood","fefunca":"feFuncA","fefuncb":"feFuncB","fefuncg":"feFuncG","fefuncr":"feFuncR","fegaussianblur":"feGaussianBlur","feimage":"feImage","femerge":"feMerge","femergenode":"feMergeNode","femorphology":"feMorphology","feoffset":"feOffset","fepointlight":"fePointLight","fespecularlighting":"feSpecularLighting","fespotlight":"feSpotLight","fetile":"feTile","feturbulence":"feTurbulence","foreignobject":"foreignObject","glyphref":"glyphRef","lineargradient":"linearGradient","radialgradient":"radialGradient","textpath":"textPath"},"attributeNames":{"definitionurl":"definitionURL","attributename":"attributeName","attributetype":"attributeType","basefrequency":"baseFrequency","baseprofile":"baseProfile","calcmode":"calcMode","clippathunits":"clipPathUnits","diffuseconstant":"diffuseConstant","edgemode":"edgeMode","filterunits":"filterUnits","glyphref":"glyphRef","gradienttransform":"gradientTransform","gradientunits":"gradientUnits","kernelmatrix":"kernelMatrix","kernelunitlength":"kernelUnitLength","keypoints":"keyPoints","keysplines":"keySplines","keytimes":"keyTimes","lengthadjust":"lengthAdjust","limitingconeangle":"limitingConeAngle","markerheight":"markerHeight","markerunits":"markerUnits","markerwidth":"markerWidth","maskcontentunits":"maskContentUnits","maskunits":"maskUnits","numoctaves":"numOctaves","pathlength":"pathLength","patterncontentunits":"patternContentUnits","patterntransform":"patternTransform","patternunits":"patternUnits","pointsatx":"pointsAtX","pointsaty":"pointsAtY","pointsatz":"pointsAtZ","preservealpha":"preserveAlpha","preserveaspectratio":"preserveAspectRatio","primitiveunits":"primitiveUnits","refx":"refX","refy":"refY","repeatcount":"repeatCount","repeatdur":"repeatDur","requiredextensions":"requiredExtensions","requiredfeatures":"requiredFeatures","specularconstant":"specularConstant","specularexponent":"specularExponent","spreadmethod":"spreadMethod","startoffset":"startOffset","stddeviation":"stdDeviation","stitchtiles":"stitchTiles","surfacescale":"surfaceScale","systemlanguage":"systemLanguage","tablevalues":"tableValues","targetx":"targetX","targety":"targetY","textlength":"textLength","viewbox":"viewBox","viewtarget":"viewTarget","xchannelselector":"xChannelSelector","ychannelselector":"yChannelSelector","zoomandpan":"zoomAndPan"}}')},function(e,t){var r=t.getChildren=function(e){return e.children},n=t.getParent=function(e){return e.parent};t.getSiblings=function(e){var t=n(e);return t?r(t):[e]},t.getAttributeValue=function(e,t){return e.attribs&&e.attribs[t]},t.hasAttrib=function(e,t){return!!e.attribs&&hasOwnProperty.call(e.attribs,t)},t.getName=function(e){return e.name}},function(e,t){t.removeElement=function(e){if(e.prev&&(e.prev.next=e.next),e.next&&(e.next.prev=e.prev),e.parent){var t=e.parent.children;t.splice(t.lastIndexOf(e),1)}},t.replaceElement=function(e,t){var r=t.prev=e.prev;r&&(r.next=t);var n=t.next=e.next;n&&(n.prev=t);var a=t.parent=e.parent;if(a){var o=a.children;o[o.lastIndexOf(e)]=t}},t.appendChild=function(e,t){if(t.parent=e,1!==e.children.push(t)){var r=e.children[e.children.length-2];r.next=t,t.prev=r,t.next=null}},t.append=function(e,t){var r=e.parent,n=e.next;if(t.next=n,t.prev=e,e.next=t,t.parent=r,n){if(n.prev=t,r){var a=r.children;a.splice(a.lastIndexOf(n),0,t)}}else r&&r.children.push(t)},t.prepend=function(e,t){var r=e.parent;if(r){var n=r.children;n.splice(n.lastIndexOf(e),0,t)}e.prev&&(e.prev.next=t),t.parent=r,t.prev=e.prev,t.next=e,e.prev=t}},function(e,t,r){var n=r(16).isTag;function a(e,t,r,n){for(var o,i=[],s=0,c=t.length;s<c&&!(e(t[s])&&(i.push(t[s]),--n<=0))&&(o=t[s].children,!(r&&o&&o.length>0&&(o=a(e,o,r,n),i=i.concat(o),(n-=o.length)<=0)));s++);return i}e.exports={filter:function(e,t,r,n){Array.isArray(t)||(t=[t]);"number"==typeof n&&isFinite(n)||(n=1/0);return a(e,t,!1!==r,n)},find:a,findOneChild:function(e,t){for(var r=0,n=t.length;r<n;r++)if(e(t[r]))return t[r];return null},findOne:function e(t,r){for(var a=null,o=0,i=r.length;o<i&&!a;o++)n(r[o])&&(t(r[o])?a=r[o]:r[o].children.length>0&&(a=e(t,r[o].children)));return a},existsOne:function e(t,r){for(var a=0,o=r.length;a<o;a++)if(n(r[a])&&(t(r[a])||r[a].children.length>0&&e(t,r[a].children)))return!0;return!1},findAll:function(e,t){var r=[],a=t.slice();for(;a.length;){var o=a.shift();n(o)&&(o.children&&o.children.length>0&&a.unshift.apply(a,o.children),e(o)&&r.push(o))}return r}}},function(e,t,r){var n=r(16),a=t.isTag=n.isTag;t.testElement=function(e,t){for(var r in e)if(e.hasOwnProperty(r)){if("tag_name"===r){if(!a(t)||!e.tag_name(t.name))return!1}else if("tag_type"===r){if(!e.tag_type(t.type))return!1}else if("tag_contains"===r){if(a(t)||!e.tag_contains(t.data))return!1}else if(!t.attribs||!e[r](t.attribs[r]))return!1}else;return!0};var o={tag_name:function(e){return"function"==typeof e?function(t){return a(t)&&e(t.name)}:"*"===e?a:function(t){return a(t)&&t.name===e}},tag_type:function(e){return"function"==typeof e?function(t){return e(t.type)}:function(t){return t.type===e}},tag_contains:function(e){return"function"==typeof e?function(t){return!a(t)&&e(t.data)}:function(t){return!a(t)&&t.data===e}}};function i(e,t){return"function"==typeof t?function(r){return r.attribs&&t(r.attribs[e])}:function(r){return r.attribs&&r.attribs[e]===t}}function s(e,t){return function(r){return e(r)||t(r)}}t.getElements=function(e,t,r,n){var a=Object.keys(e).map((function(t){var r=e[t];return t in o?o[t](r):i(t,r)}));return 0===a.length?[]:this.filter(a.reduce(s),t,r,n)},t.getElementById=function(e,t,r){return Array.isArray(t)||(t=[t]),this.findOne(i("id",e),t,!1!==r)},t.getElementsByTagName=function(e,t,r,n){return this.filter(o.tag_name(e),t,r,n)},t.getElementsByTagType=function(e,t,r,n){return this.filter(o.tag_type(e),t,r,n)}},function(e,t){t.removeSubsets=function(e){for(var t,r,n,a=e.length;--a>-1;){for(t=r=e[a],e[a]=null,n=!0;r;){if(e.indexOf(r)>-1){n=!1,e.splice(a,1);break}r=r.parent}n&&(e[a]=t)}return e};var r=1,n=2,a=4,o=8,i=16,s=t.compareDocumentPosition=function(e,t){var s,c,l,u,p,d,h=[],f=[];if(e===t)return 0;for(s=e;s;)h.unshift(s),s=s.parent;for(s=t;s;)f.unshift(s),s=s.parent;for(d=0;h[d]===f[d];)d++;return 0===d?r:(l=(c=h[d-1]).children,u=h[d],p=f[d],l.indexOf(u)>l.indexOf(p)?c===t?a|i:a:c===e?n|o:n)};t.uniqueSort=function(e){var t,r,o=e.length;for(e=e.slice();--o>-1;)t=e[o],(r=e.indexOf(t))>-1&&r<o&&e.splice(o,1);return e.sort((function(e,t){var r=s(e,t);return r&n?-1:r&a?1:0})),e}},function(e,t,r){e.exports=a;var n=r(30);function a(e){n.call(this,new o(this),e)}function o(e){this.scope=e}r(17)(a,n),a.prototype.readable=!0;var i=r(10).EVENTS;Object.keys(i).forEach((function(e){if(0===i[e])o.prototype["on"+e]=function(){this.scope.emit(e)};else if(1===i[e])o.prototype["on"+e]=function(t){this.scope.emit(e,t)};else{if(2!==i[e])throw Error("wrong number of arguments!");o.prototype["on"+e]=function(t,r){this.scope.emit(e,t,r)}}}))},function(e,t){},function(e,t,r){"use strict";var n=r(74).Buffer,a=n.isEncoding||function(e){switch((e=""+e)&&e.toLowerCase()){case"hex":case"utf8":case"utf-8":case"ascii":case"binary":case"base64":case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":case"raw":return!0;default:return!1}};function o(e){var t;switch(this.encoding=function(e){var t=function(e){if(!e)return"utf8";for(var t;;)switch(e){case"utf8":case"utf-8":return"utf8";case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return"utf16le";case"latin1":case"binary":return"latin1";case"base64":case"ascii":case"hex":return e;default:if(t)return;e=(""+e).toLowerCase(),t=!0}}(e);if("string"!=typeof t&&(n.isEncoding===a||!a(e)))throw new Error("Unknown encoding: "+e);return t||e}(e),this.encoding){case"utf16le":this.text=c,this.end=l,t=4;break;case"utf8":this.fillLast=s,t=4;break;case"base64":this.text=u,this.end=p,t=3;break;default:return this.write=d,void(this.end=h)}this.lastNeed=0,this.lastTotal=0,this.lastChar=n.allocUnsafe(t)}function i(e){return e<=127?0:e>>5==6?2:e>>4==14?3:e>>3==30?4:e>>6==2?-1:-2}function s(e){var t=this.lastTotal-this.lastNeed,r=function(e,t,r){if(128!=(192&t[0]))return e.lastNeed=0,"�";if(e.lastNeed>1&&t.length>1){if(128!=(192&t[1]))return e.lastNeed=1,"�";if(e.lastNeed>2&&t.length>2&&128!=(192&t[2]))return e.lastNeed=2,"�"}}(this,e);return void 0!==r?r:this.lastNeed<=e.length?(e.copy(this.lastChar,t,0,this.lastNeed),this.lastChar.toString(this.encoding,0,this.lastTotal)):(e.copy(this.lastChar,t,0,e.length),void(this.lastNeed-=e.length))}function c(e,t){if((e.length-t)%2==0){var r=e.toString("utf16le",t);if(r){var n=r.charCodeAt(r.length-1);if(n>=55296&&n<=56319)return this.lastNeed=2,this.lastTotal=4,this.lastChar[0]=e[e.length-2],this.lastChar[1]=e[e.length-1],r.slice(0,-1)}return r}return this.lastNeed=1,this.lastTotal=2,this.lastChar[0]=e[e.length-1],e.toString("utf16le",t,e.length-1)}function l(e){var t=e&&e.length?this.write(e):"";if(this.lastNeed){var r=this.lastTotal-this.lastNeed;return t+this.lastChar.toString("utf16le",0,r)}return t}function u(e,t){var r=(e.length-t)%3;return 0===r?e.toString("base64",t):(this.lastNeed=3-r,this.lastTotal=3,1===r?this.lastChar[0]=e[e.length-1]:(this.lastChar[0]=e[e.length-2],this.lastChar[1]=e[e.length-1]),e.toString("base64",t,e.length-r))}function p(e){var t=e&&e.length?this.write(e):"";return this.lastNeed?t+this.lastChar.toString("base64",0,3-this.lastNeed):t}function d(e){return e.toString(this.encoding)}function h(e){return e&&e.length?this.write(e):""}t.StringDecoder=o,o.prototype.write=function(e){if(0===e.length)return"";var t,r;if(this.lastNeed){if(void 0===(t=this.fillLast(e)))return"";r=this.lastNeed,this.lastNeed=0}else r=0;return r<e.length?t?t+this.text(e,r):this.text(e,r):t||""},o.prototype.end=function(e){var t=e&&e.length?this.write(e):"";return this.lastNeed?t+"�":t},o.prototype.text=function(e,t){var r=function(e,t,r){var n=t.length-1;if(n<r)return 0;var a=i(t[n]);if(a>=0)return a>0&&(e.lastNeed=a-1),a;if(--n<r||-2===a)return 0;if((a=i(t[n]))>=0)return a>0&&(e.lastNeed=a-2),a;if(--n<r||-2===a)return 0;if((a=i(t[n]))>=0)return a>0&&(2===a?a=0:e.lastNeed=a-3),a;return 0}(this,e,t);if(!this.lastNeed)return e.toString("utf8",t);this.lastTotal=r;var n=e.length-(r-this.lastNeed);return e.copy(this.lastChar,0,n),e.toString("utf8",t,n)},o.prototype.fillLast=function(e){if(this.lastNeed<=e.length)return e.copy(this.lastChar,this.lastTotal-this.lastNeed,0,this.lastNeed),this.lastChar.toString(this.encoding,0,this.lastTotal);e.copy(this.lastChar,this.lastTotal-this.lastNeed,0,e.length),this.lastNeed-=e.length}},function(e,t,r){var n=r(31),a=n.Buffer;function o(e,t){for(var r in e)t[r]=e[r]}function i(e,t,r){return a(e,t,r)}a.from&&a.alloc&&a.allocUnsafe&&a.allocUnsafeSlow?e.exports=n:(o(n,t),t.Buffer=i),i.prototype=Object.create(a.prototype),o(a,i),i.from=function(e,t,r){if("number"==typeof e)throw new TypeError("Argument must not be a number");return a(e,t,r)},i.alloc=function(e,t,r){if("number"!=typeof e)throw new TypeError("Argument must be a number");var n=a(e);return void 0!==t?"string"==typeof r?n.fill(t,r):n.fill(t):n.fill(0),n},i.allocUnsafe=function(e){if("number"!=typeof e)throw new TypeError("Argument must be a number");return a(e)},i.allocUnsafeSlow=function(e){if("number"!=typeof e)throw new TypeError("Argument must be a number");return n.SlowBuffer(e)}},function(e,t,r){"use strict";t.byteLength=function(e){var t=l(e),r=t[0],n=t[1];return 3*(r+n)/4-n},t.toByteArray=function(e){var t,r,n=l(e),i=n[0],s=n[1],c=new o(function(e,t,r){return 3*(t+r)/4-r}(0,i,s)),u=0,p=s>0?i-4:i;for(r=0;r<p;r+=4)t=a[e.charCodeAt(r)]<<18|a[e.charCodeAt(r+1)]<<12|a[e.charCodeAt(r+2)]<<6|a[e.charCodeAt(r+3)],c[u++]=t>>16&255,c[u++]=t>>8&255,c[u++]=255&t;2===s&&(t=a[e.charCodeAt(r)]<<2|a[e.charCodeAt(r+1)]>>4,c[u++]=255&t);1===s&&(t=a[e.charCodeAt(r)]<<10|a[e.charCodeAt(r+1)]<<4|a[e.charCodeAt(r+2)]>>2,c[u++]=t>>8&255,c[u++]=255&t);return c},t.fromByteArray=function(e){for(var t,r=e.length,a=r%3,o=[],i=0,s=r-a;i<s;i+=16383)o.push(u(e,i,i+16383>s?s:i+16383));1===a?(t=e[r-1],o.push(n[t>>2]+n[t<<4&63]+"==")):2===a&&(t=(e[r-2]<<8)+e[r-1],o.push(n[t>>10]+n[t>>4&63]+n[t<<2&63]+"="));return o.join("")};for(var n=[],a=[],o="undefined"!=typeof Uint8Array?Uint8Array:Array,i="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",s=0,c=i.length;s<c;++s)n[s]=i[s],a[i.charCodeAt(s)]=s;function l(e){var t=e.length;if(t%4>0)throw new Error("Invalid string. Length must be a multiple of 4");var r=e.indexOf("=");return-1===r&&(r=t),[r,r===t?0:4-r%4]}function u(e,t,r){for(var a,o,i=[],s=t;s<r;s+=3)a=(e[s]<<16&16711680)+(e[s+1]<<8&65280)+(255&e[s+2]),i.push(n[(o=a)>>18&63]+n[o>>12&63]+n[o>>6&63]+n[63&o]);return i.join("")}a["-".charCodeAt(0)]=62,a["_".charCodeAt(0)]=63},function(e,t){t.read=function(e,t,r,n,a){var o,i,s=8*a-n-1,c=(1<<s)-1,l=c>>1,u=-7,p=r?a-1:0,d=r?-1:1,h=e[t+p];for(p+=d,o=h&(1<<-u)-1,h>>=-u,u+=s;u>0;o=256*o+e[t+p],p+=d,u-=8);for(i=o&(1<<-u)-1,o>>=-u,u+=n;u>0;i=256*i+e[t+p],p+=d,u-=8);if(0===o)o=1-l;else{if(o===c)return i?NaN:1/0*(h?-1:1);i+=Math.pow(2,n),o-=l}return(h?-1:1)*i*Math.pow(2,o-n)},t.write=function(e,t,r,n,a,o){var i,s,c,l=8*o-a-1,u=(1<<l)-1,p=u>>1,d=23===a?Math.pow(2,-24)-Math.pow(2,-77):0,h=n?0:o-1,f=n?1:-1,m=t<0||0===t&&1/t<0?1:0;for(t=Math.abs(t),isNaN(t)||t===1/0?(s=isNaN(t)?1:0,i=u):(i=Math.floor(Math.log(t)/Math.LN2),t*(c=Math.pow(2,-i))<1&&(i--,c*=2),(t+=i+p>=1?d/c:d*Math.pow(2,1-p))*c>=2&&(i++,c/=2),i+p>=u?(s=0,i=u):i+p>=1?(s=(t*c-1)*Math.pow(2,a),i+=p):(s=t*Math.pow(2,p-1)*Math.pow(2,a),i=0));a>=8;e[r+h]=255&s,h+=f,s/=256,a-=8);for(i=i<<a|s,l+=a;l>0;e[r+h]=255&i,h+=f,i/=256,l-=8);e[r+h-f]|=128*m}},function(e,t){var r={}.toString;e.exports=Array.isArray||function(e){return"[object Array]"==r.call(e)}},function(e,t,r){function n(e){this._cbs=e||{}}e.exports=n;var a=r(10).EVENTS;Object.keys(a).forEach((function(e){if(0===a[e])e="on"+e,n.prototype[e]=function(){this._cbs[e]&&this._cbs[e]()};else if(1===a[e])e="on"+e,n.prototype[e]=function(t){this._cbs[e]&&this._cbs[e](t)};else{if(2!==a[e])throw Error("wrong number of arguments");e="on"+e,n.prototype[e]=function(t,r){this._cbs[e]&&this._cbs[e](t,r)}}}))},function(e,t,r){function n(e){this._cbs=e||{},this.events=[]}e.exports=n;var a=r(10).EVENTS;Object.keys(a).forEach((function(e){if(0===a[e])e="on"+e,n.prototype[e]=function(){this.events.push([e]),this._cbs[e]&&this._cbs[e]()};else if(1===a[e])e="on"+e,n.prototype[e]=function(t){this.events.push([e,t]),this._cbs[e]&&this._cbs[e](t)};else{if(2!==a[e])throw Error("wrong number of arguments");e="on"+e,n.prototype[e]=function(t,r){this.events.push([e,t,r]),this._cbs[e]&&this._cbs[e](t,r)}}})),n.prototype.onreset=function(){this.events=[],this._cbs.onreset&&this._cbs.onreset()},n.prototype.restart=function(){this._cbs.onreset&&this._cbs.onreset();for(var e=0,t=this.events.length;e<t;e++)if(this._cbs[this.events[e][0]]){var r=this.events[e].length;1===r?this._cbs[this.events[e][0]]():2===r?this._cbs[this.events[e][0]](this.events[e][1]):this._cbs[this.events[e][0]](this.events[e][1],this.events[e][2])}}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){return e.data}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e,t,r){var c=e.name;if(!(0,s.default)(c))return null;var l=(0,o.default)(e.attribs,t),u=null;-1===i.default.indexOf(c)&&(u=(0,a.default)(e.children,r));return n.default.createElement(c,l,u)};var n=c(r(3)),a=c(r(18)),o=c(r(33)),i=c(r(86)),s=c(r(34));function c(e){return e&&e.__esModule?e:{default:e}}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){return Object.keys(e).filter((function(e){return(0,o.default)(e)})).reduce((function(t,r){var o=r.toLowerCase(),i=a.default[o]||o;return t[i]=function(e,t){n.default.map((function(e){return e.toLowerCase()})).indexOf(e.toLowerCase())>=0&&(t=e);return t}(i,e[r]),t}),{})};var n=i(r(83)),a=i(r(84)),o=i(r(34));function i(e){return e&&e.__esModule?e:{default:e}}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=["allowfullScreen","async","autoplay","capture","checked","controls","default","defer","disabled","formnovalidate","hidden","loop","multiple","muted","novalidate","open","playsinline","readonly","required","reversed","scoped","seamless","selected","itemscope"]},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={accept:"accept","accept-charset":"acceptCharset",accesskey:"accessKey",action:"action",allowfullscreen:"allowFullScreen",allowtransparency:"allowTransparency",alt:"alt",as:"as",async:"async",autocomplete:"autoComplete",autoplay:"autoPlay",capture:"capture",cellpadding:"cellPadding",cellspacing:"cellSpacing",charset:"charSet",challenge:"challenge",checked:"checked",cite:"cite",classid:"classID",class:"className",cols:"cols",colspan:"colSpan",content:"content",contenteditable:"contentEditable",contextmenu:"contextMenu",controls:"controls",controlsList:"controlsList",coords:"coords",crossorigin:"crossOrigin",data:"data",datetime:"dateTime",default:"default",defer:"defer",dir:"dir",disabled:"disabled",download:"download",draggable:"draggable",enctype:"encType",form:"form",formaction:"formAction",formenctype:"formEncType",formmethod:"formMethod",formnovalidate:"formNoValidate",formtarget:"formTarget",frameborder:"frameBorder",headers:"headers",height:"height",hidden:"hidden",high:"high",href:"href",hreflang:"hrefLang",for:"htmlFor","http-equiv":"httpEquiv",icon:"icon",id:"id",inputmode:"inputMode",integrity:"integrity",is:"is",keyparams:"keyParams",keytype:"keyType",kind:"kind",label:"label",lang:"lang",list:"list",loop:"loop",low:"low",manifest:"manifest",marginheight:"marginHeight",marginwidth:"marginWidth",max:"max",maxlength:"maxLength",media:"media",mediagroup:"mediaGroup",method:"method",min:"min",minlength:"minLength",multiple:"multiple",muted:"muted",name:"name",nonce:"nonce",novalidate:"noValidate",open:"open",optimum:"optimum",pattern:"pattern",placeholder:"placeholder",playsinline:"playsInline",poster:"poster",preload:"preload",profile:"profile",radiogroup:"radioGroup",readonly:"readOnly",referrerpolicy:"referrerPolicy",rel:"rel",required:"required",reversed:"reversed",role:"role",rows:"rows",rowspan:"rowSpan",sandbox:"sandbox",scope:"scope",scoped:"scoped",scrolling:"scrolling",seamless:"seamless",selected:"selected",shape:"shape",size:"size",sizes:"sizes",slot:"slot",span:"span",spellcheck:"spellCheck",src:"src",srcdoc:"srcDoc",srclang:"srcLang",srcset:"srcSet",start:"start",step:"step",style:"style",summary:"summary",tabindex:"tabIndex",target:"target",title:"title",type:"type",usemap:"useMap",value:"value",width:"width",wmode:"wmode",wrap:"wrap",about:"about",datatype:"datatype",inlist:"inlist",prefix:"prefix",property:"property",resource:"resource",typeof:"typeof",vocab:"vocab",autocapitalize:"autoCapitalize",autocorrect:"autoCorrect",autosave:"autoSave",color:"color",itemprop:"itemProp",itemscope:"itemScope",itemtype:"itemType",itemid:"itemID",itemref:"itemRef",results:"results",security:"security",unselectable:"unselectable"}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=function(e,t){if(Array.isArray(e))return e;if(Symbol.iterator in Object(e))return function(e,t){var r=[],n=!0,a=!1,o=void 0;try{for(var i,s=e[Symbol.iterator]();!(n=(i=s.next()).done)&&(r.push(i.value),!t||r.length!==t);n=!0);}catch(e){a=!0,o=e}finally{try{!n&&s.return&&s.return()}finally{if(a)throw o}}return r}(e,t);throw new TypeError("Invalid attempt to destructure non-iterable instance")};t.default=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";if(""===e)return{};return e.split(";").reduce((function(e,t){var r=t.split(/^([^:]+):/).filter((function(e,t){return t>0})).map((function(e){return e.trim().toLowerCase()})),a=n(r,2),o=a[0],i=a[1];return void 0===i||(e[o=o.replace(/^-ms-/,"ms-").replace(/-(.)/g,(function(e,t){return t.toUpperCase()}))]=i),e}),{})}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=["area","base","br","col","command","embed","hr","img","input","keygen","link","meta","param","source","track","wbr"]},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e,t){var r=void 0;e.children.length>0&&(r=e.children[0].data);var o=(0,a.default)(e.attribs,t);return n.default.createElement("style",o,r)};var n=o(r(3)),a=o(r(33));function o(e){return e&&e.__esModule?e:{default:e}}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(){return null}},function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},r=t.decodeEntities,o=void 0===r||r,i=t.transform,s=t.preprocessNodes,c=void 0===s?function(e){return e}:s,l=c(n.default.parseDOM(e,{decodeEntities:o}));return(0,a.default)(l,i)};var n=o(r(10)),a=o(r(18));function o(e){return e&&e.__esModule?e:{default:e}}},function(e,t){e.exports=function(e){if(Array.isArray(e))return e}},function(e,t){e.exports=function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var r=[],n=!0,a=!1,o=void 0;try{for(var i,s=e[Symbol.iterator]();!(n=(i=s.next()).done)&&(r.push(i.value),!t||r.length!==t);n=!0);}catch(e){a=!0,o=e}finally{try{n||null==s.return||s.return()}finally{if(a)throw o}}return r}}},function(e,t){e.exports=function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},function(e,t,r){var n=r(36);e.exports=function(e){if(Array.isArray(e))return n(e)}},function(e,t){e.exports=function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}},function(e,t){e.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},function(e,t){function r(t,n){return e.exports=r=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},r(t,n)}e.exports=r},function(e,t){function r(t){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?e.exports=r=function(e){return typeof e}:e.exports=r=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},r(t)}e.exports=r},function(e,t,r){"use strict";r.r(t);t.default=function(e,t){if(!e)throw new Error("Invariant failed")}},function(e,t,r){"use strict";function n(){return(n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e}).apply(this,arguments)}function a(e){if("undefined"!=typeof window&&window.navigator)return!!navigator.userAgent.match(e)}r.r(t),r.d(t,"Sortable",(function(){return Pe}));var o=a(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i),i=a(/Edge/i),s=a(/firefox/i),c=a(/safari/i)&&!a(/chrome/i)&&!a(/android/i),l=a(/iP(ad|od|hone)/i),u=a(/chrome/i)&&a(/android/i),p={capture:!1,passive:!1};function d(e,t,r){e.addEventListener(t,r,!o&&p)}function h(e,t,r){e.removeEventListener(t,r,!o&&p)}function f(e,t){if(t){if(">"===t[0]&&(t=t.substring(1)),e)try{if(e.matches)return e.matches(t);if(e.msMatchesSelector)return e.msMatchesSelector(t);if(e.webkitMatchesSelector)return e.webkitMatchesSelector(t)}catch(e){return!1}return!1}}function m(e){return e.host&&e!==document&&e.host.nodeType?e.host:e.parentNode}function g(e,t,r,n){if(e){r=r||document;do{if(null!=t&&(">"===t[0]?e.parentNode===r&&f(e,t):f(e,t))||n&&e===r)return e;if(e===r)break}while(e=m(e))}return null}var b,v=/\s+/g;function y(e,t,r){if(e&&t)if(e.classList)e.classList[r?"add":"remove"](t);else{var n=(" "+e.className+" ").replace(v," ").replace(" "+t+" "," ");e.className=(n+(r?" "+t:"")).replace(v," ")}}function w(e,t,r){var n=e&&e.style;if(n){if(void 0===r)return document.defaultView&&document.defaultView.getComputedStyle?r=document.defaultView.getComputedStyle(e,""):e.currentStyle&&(r=e.currentStyle),void 0===t?r:r[t];t in n||-1!==t.indexOf("webkit")||(t="-webkit-"+t),n[t]=r+("string"==typeof r?"":"px")}}function _(e,t){var r="";if("string"==typeof e)r=e;else do{var n=w(e,"transform");n&&"none"!==n&&(r=n+" "+r)}while(!t&&(e=e.parentNode));var a=window.DOMMatrix||window.WebKitCSSMatrix||window.CSSMatrix||window.MSCSSMatrix;return a&&new a(r)}function O(e,t,r){if(e){var n=e.getElementsByTagName(t),a=0,o=n.length;if(r)for(;a<o;a++)r(n[a],a);return n}return[]}function E(){return document.scrollingElement||document.documentElement}function j(e,t,r,n,a){if(e.getBoundingClientRect||e===window){var i,s,c,l,u,p,d;if(e!==window&&e!==E()?(s=(i=e.getBoundingClientRect()).top,c=i.left,l=i.bottom,u=i.right,p=i.height,d=i.width):(s=0,c=0,l=window.innerHeight,u=window.innerWidth,p=window.innerHeight,d=window.innerWidth),(t||r)&&e!==window&&(a=a||e.parentNode,!o))do{if(a&&a.getBoundingClientRect&&("none"!==w(a,"transform")||r&&"static"!==w(a,"position"))){var h=a.getBoundingClientRect();s-=h.top+parseInt(w(a,"border-top-width")),c-=h.left+parseInt(w(a,"border-left-width")),l=s+i.height,u=c+i.width;break}}while(a=a.parentNode);if(n&&e!==window){var f=_(a||e),m=f&&f.a,g=f&&f.d;f&&(l=(s/=g)+(p/=g),u=(c/=m)+(d/=m))}return{top:s,left:c,bottom:l,right:u,width:d,height:p}}}function x(e,t,r){for(var n=z(e,!0),a=j(e)[t];n;){var o=j(n)[r];if(!("top"===r||"left"===r?a>=o:a<=o))return n;if(n===E())break;n=z(n,!1)}return!1}function C(e,t,r){for(var n=0,a=0,o=e.children;a<o.length;){if("none"!==o[a].style.display&&o[a]!==Pe.ghost&&o[a]!==Pe.dragged&&g(o[a],r.draggable,e,!1)){if(n===t)return o[a];n++}a++}return null}function k(e,t){for(var r=e.lastElementChild;r&&(r===Pe.ghost||"none"===w(r,"display")||t&&!f(r,t));)r=r.previousElementSibling;return r||null}function S(e,t){var r=0;if(!e||!e.parentNode)return-1;for(;e=e.previousElementSibling;)"TEMPLATE"===e.nodeName.toUpperCase()||e===Pe.clone||t&&!f(e,t)||r++;return r}function N(e){var t=0,r=0,n=E();if(e)do{var a=_(e);t+=e.scrollLeft*a.a,r+=e.scrollTop*a.d}while(e!==n&&(e=e.parentNode));return[t,r]}function z(e,t){if(!e||!e.getBoundingClientRect)return E();var r=e,n=!1;do{if(r.clientWidth<r.scrollWidth||r.clientHeight<r.scrollHeight){var a=w(r);if(r.clientWidth<r.scrollWidth&&("auto"==a.overflowX||"scroll"==a.overflowX)||r.clientHeight<r.scrollHeight&&("auto"==a.overflowY||"scroll"==a.overflowY)){if(!r.getBoundingClientRect||r===document.body)return E();if(n||t)return r;n=!0}}}while(r=r.parentNode);return E()}function D(e,t){return Math.round(e.top)===Math.round(t.top)&&Math.round(e.left)===Math.round(t.left)&&Math.round(e.height)===Math.round(t.height)&&Math.round(e.width)===Math.round(t.width)}function A(e,t){return function(){if(!b){var r=arguments,n=this;1===r.length?e.call(n,r[0]):e.apply(n,r),b=setTimeout((function(){b=void 0}),t)}}}function M(e,t,r){e.scrollLeft+=t,e.scrollTop+=r}function T(e){var t=window.Polymer,r=window.jQuery||window.Zepto;return t&&t.dom?t.dom(e).cloneNode(!0):r?r(e).clone(!0)[0]:e.cloneNode(!0)}function P(e,t){w(e,"position","absolute"),w(e,"top",t.top),w(e,"left",t.left),w(e,"width",t.width),w(e,"height",t.height)}function L(e){w(e,"position",""),w(e,"top",""),w(e,"left",""),w(e,"width",""),w(e,"height","")}var q="Sortable"+(new Date).getTime(),R=[],B={initializeByDefault:!0},H={mount:function(e){for(var t in B)B.hasOwnProperty(t)&&!(t in e)&&(e[t]=B[t]);R.push(e)},pluginEvent:function(e,t,r){var a=this;this.eventCanceled=!1,r.cancel=function(){a.eventCanceled=!0};var o=e+"Global";R.forEach((function(a){t[a.pluginName]&&(t[a.pluginName][o]&&t[a.pluginName][o](n({sortable:t},r)),t.options[a.pluginName]&&t[a.pluginName][e]&&t[a.pluginName][e](n({sortable:t},r)))}))},initializePlugins:function(e,t,r,n){for(var a in R.forEach((function(n){var a=n.pluginName;if(e.options[a]||n.initializeByDefault){var o=new n(e,t,e.options);o.sortable=e,o.options=e.options,e[a]=o,Object.assign(r,o.defaults)}})),e.options)if(e.options.hasOwnProperty(a)){var o=this.modifyOption(e,a,e.options[a]);void 0!==o&&(e.options[a]=o)}},getEventProperties:function(e,t){var r={};return R.forEach((function(n){"function"==typeof n.eventProperties&&Object.assign(r,n.eventProperties.call(t[n.pluginName],e))})),r},modifyOption:function(e,t,r){var n;return R.forEach((function(a){e[a.pluginName]&&a.optionListeners&&"function"==typeof a.optionListeners[t]&&(n=a.optionListeners[t].call(e[a.pluginName],r))})),n}};function I(e){var t=e.sortable,r=e.rootEl,a=e.name,s=e.targetEl,c=e.cloneEl,l=e.toEl,u=e.fromEl,p=e.oldIndex,d=e.newIndex,h=e.oldDraggableIndex,f=e.newDraggableIndex,m=e.originalEvent,g=e.putSortable,b=e.extraEventProperties;if(t=t||r&&r[q]){var v,y=t.options,w="on"+a.charAt(0).toUpperCase()+a.substr(1);!window.CustomEvent||o||i?(v=document.createEvent("Event")).initEvent(a,!0,!0):v=new CustomEvent(a,{bubbles:!0,cancelable:!0}),v.to=l||r,v.from=u||r,v.item=s||r,v.clone=c,v.oldIndex=p,v.newIndex=d,v.oldDraggableIndex=h,v.newDraggableIndex=f,v.originalEvent=m,v.pullMode=g?g.lastPutMode:void 0;var _=n({},b,H.getEventProperties(a,t));for(var O in _)v[O]=_[O];r&&r.dispatchEvent(v),y[w]&&y[w].call(t,v)}}var V=function(e,t,r){var a=void 0===r?{}:r,o=a.evt,i=function(e,t){if(null==e)return{};var r,n,a={},o=Object.keys(e);for(n=0;n<o.length;n++)t.indexOf(r=o[n])>=0||(a[r]=e[r]);return a}(a,["evt"]);H.pluginEvent.bind(Pe)(e,t,n({dragEl:F,parentEl:Q,ghostEl:G,rootEl:Y,nextEl:X,lastDownEl:J,cloneEl:W,cloneHidden:K,dragStarted:ue,putSortable:ne,activeSortable:Pe.active,originalEvent:o,oldIndex:Z,oldDraggableIndex:ee,newIndex:$,newDraggableIndex:te,hideGhostForTarget:ze,unhideGhostForTarget:De,cloneNowHidden:function(){K=!0},cloneNowShown:function(){K=!1},dispatchSortableEvent:function(e){U({sortable:t,name:e,originalEvent:o})}},i))};function U(e){I(n({putSortable:ne,cloneEl:W,targetEl:F,rootEl:Y,oldIndex:Z,oldDraggableIndex:ee,newIndex:$,newDraggableIndex:te},e))}var F,Q,G,Y,X,J,W,K,Z,$,ee,te,re,ne,ae,oe,ie,se,ce,le,ue,pe,de,he,fe,me=!1,ge=!1,be=[],ve=!1,ye=!1,we=[],_e=!1,Oe=[],Ee="undefined"!=typeof document,je=l,xe=i||o?"cssFloat":"float",Ce=Ee&&!u&&!l&&"draggable"in document.createElement("div"),ke=function(){if(Ee){if(o)return!1;var e=document.createElement("x");return e.style.cssText="pointer-events:auto","auto"===e.style.pointerEvents}}(),Se=function(e,t){var r=w(e),n=parseInt(r.width)-parseInt(r.paddingLeft)-parseInt(r.paddingRight)-parseInt(r.borderLeftWidth)-parseInt(r.borderRightWidth),a=C(e,0,t),o=C(e,1,t),i=a&&w(a),s=o&&w(o),c=i&&parseInt(i.marginLeft)+parseInt(i.marginRight)+j(a).width,l=s&&parseInt(s.marginLeft)+parseInt(s.marginRight)+j(o).width;return"flex"===r.display?"column"===r.flexDirection||"column-reverse"===r.flexDirection?"vertical":"horizontal":"grid"===r.display?r.gridTemplateColumns.split(" ").length<=1?"vertical":"horizontal":a&&i.float&&"none"!==i.float?!o||"both"!==s.clear&&s.clear!==("left"===i.float?"left":"right")?"horizontal":"vertical":a&&("block"===i.display||"flex"===i.display||"table"===i.display||"grid"===i.display||c>=n&&"none"===r[xe]||o&&"none"===r[xe]&&c+l>n)?"vertical":"horizontal"},Ne=function(e){function t(e,r){return function(n,a,o,i){if(null==e&&(r||n.options.group.name&&a.options.group.name&&n.options.group.name===a.options.group.name))return!0;if(null==e||!1===e)return!1;if(r&&"clone"===e)return e;if("function"==typeof e)return t(e(n,a,o,i),r)(n,a,o,i);var s=(r?n:a).options.group.name;return!0===e||"string"==typeof e&&e===s||e.join&&e.indexOf(s)>-1}}var r={},n=e.group;n&&"object"==typeof n||(n={name:n}),r.name=n.name,r.checkPull=t(n.pull,!0),r.checkPut=t(n.put),r.revertClone=n.revertClone,e.group=r},ze=function(){!ke&&G&&w(G,"display","none")},De=function(){!ke&&G&&w(G,"display","")};Ee&&document.addEventListener("click",(function(e){if(ge)return e.preventDefault(),e.stopPropagation&&e.stopPropagation(),e.stopImmediatePropagation&&e.stopImmediatePropagation(),ge=!1,!1}),!0);var Ae,Me=function(e){if(F){var t=(a=(e=e.touches?e.touches[0]:e).clientX,o=e.clientY,be.some((function(e){if(!k(e)){var t=j(e),r=e[q].options.emptyInsertThreshold;return r&&a>=t.left-r&&a<=t.right+r&&o>=t.top-r&&o<=t.bottom+r?i=e:void 0}})),i);if(t){var r={};for(var n in e)e.hasOwnProperty(n)&&(r[n]=e[n]);r.target=r.rootEl=t,r.preventDefault=void 0,r.stopPropagation=void 0,t[q]._onDragOver(r)}}var a,o,i},Te=function(e){F&&F.parentNode[q]._isOutsideThisEl(e.target)};function Pe(e,t){if(!e||!e.nodeType||1!==e.nodeType)throw"Sortable: `el` must be an HTMLElement, not "+{}.toString.call(e);this.el=e,this.options=t=Object.assign({},t),e[q]=this;var r,a,o={group:null,sort:!0,disabled:!1,store:null,handle:null,draggable:/^[uo]l$/i.test(e.nodeName)?">li":">*",swapThreshold:1,invertSwap:!1,invertedSwapThreshold:null,removeCloneOnHide:!0,direction:function(){return Se(e,this.options)},ghostClass:"sortable-ghost",chosenClass:"sortable-chosen",dragClass:"sortable-drag",ignore:"a, img",filter:null,preventOnFilter:!0,animation:0,easing:null,setData:function(e,t){e.setData("Text",t.textContent)},dropBubble:!1,dragoverBubble:!1,dataIdAttr:"data-id",delay:0,delayOnTouchOnly:!1,touchStartThreshold:(Number.parseInt?Number:window).parseInt(window.devicePixelRatio,10)||1,forceFallback:!1,fallbackClass:"sortable-fallback",fallbackOnBody:!1,fallbackTolerance:0,fallbackOffset:{x:0,y:0},supportPointer:!1!==Pe.supportPointer&&"PointerEvent"in window,emptyInsertThreshold:5};for(var i in H.initializePlugins(this,e,o),o)!(i in t)&&(t[i]=o[i]);for(var s in Ne(t),this)"_"===s.charAt(0)&&"function"==typeof this[s]&&(this[s]=this[s].bind(this));this.nativeDraggable=!t.forceFallback&&Ce,this.nativeDraggable&&(this.options.touchStartThreshold=1),t.supportPointer?d(e,"pointerdown",this._onTapStart):(d(e,"mousedown",this._onTapStart),d(e,"touchstart",this._onTapStart)),this.nativeDraggable&&(d(e,"dragover",this),d(e,"dragenter",this)),be.push(this.el),t.store&&t.store.get&&this.sort(t.store.get(this)||[]),Object.assign(this,(a=[],{captureAnimationState:function(){a=[],this.options.animation&&[].slice.call(this.el.children).forEach((function(e){if("none"!==w(e,"display")&&void 0!==e){a.push({target:e,rect:j(e)});var t=n({},a[a.length-1].rect);if(e.thisAnimationDuration){var r=_(e,!0);r&&(t.top-=r.f,t.left-=r.e)}e.fromRect=t}}))},addAnimationState:function(e){a.push(e)},removeAnimationState:function(e){a.splice(function(e,t){for(var r in e)if(e.hasOwnProperty(r))for(var n in t)if(t.hasOwnProperty(n)&&t[n]===e[r][n])return Number(r);return-1}(a,{target:e}),1)},animateAll:function(e){var t=this;if(!this.options.animation)return clearTimeout(r),void("function"==typeof e&&e());var n=!1,o=0;a.forEach((function(e){var r=0,a=e.target,i=a.fromRect,s=j(a),c=a.prevFromRect,l=a.prevToRect,u=e.rect,p=_(a,!0);p&&(s.top-=p.f,s.left-=p.e),a.toRect=s,a.thisAnimationDuration&&D(c,s)&&!D(i,s)&&(u.top-s.top)/(u.left-s.left)==(i.top-s.top)/(i.left-s.left)&&(r=function(e,t,r,n){return Math.sqrt(Math.pow(t.top-e.top,2)+Math.pow(t.left-e.left,2))/Math.sqrt(Math.pow(t.top-r.top,2)+Math.pow(t.left-r.left,2))*n.animation}(u,c,l,t.options)),D(s,i)||(a.prevFromRect=i,a.prevToRect=s,r||(r=t.options.animation),t.animate(a,u,s,r)),r&&(n=!0,o=Math.max(o,r),clearTimeout(a.animationResetTimer),a.animationResetTimer=setTimeout((function(){a.animationTime=0,a.prevFromRect=null,a.fromRect=null,a.prevToRect=null,a.thisAnimationDuration=null}),r),a.thisAnimationDuration=r)})),clearTimeout(r),n?r=setTimeout((function(){"function"==typeof e&&e()}),o):"function"==typeof e&&e(),a=[]},animate:function(e,t,r,n){if(n){w(e,"transition",""),w(e,"transform","");var a=_(this.el),o=(t.left-r.left)/(a&&a.a||1),i=(t.top-r.top)/(a&&a.d||1);e.animatingX=!!o,e.animatingY=!!i,w(e,"transform","translate3d("+o+"px,"+i+"px,0)"),this.forRepaintDummy=function(e){return e.offsetWidth}(e),w(e,"transition","transform "+n+"ms"+(this.options.easing?" "+this.options.easing:"")),w(e,"transform","translate3d(0,0,0)"),"number"==typeof e.animated&&clearTimeout(e.animated),e.animated=setTimeout((function(){w(e,"transition",""),w(e,"transform",""),e.animated=!1,e.animatingX=!1,e.animatingY=!1}),n)}}}))}function Le(e,t,r,n,a,s,c,l){var u,p,d=e[q],h=d.options.onMove;return!window.CustomEvent||o||i?(u=document.createEvent("Event")).initEvent("move",!0,!0):u=new CustomEvent("move",{bubbles:!0,cancelable:!0}),u.to=t,u.from=e,u.dragged=r,u.draggedRect=n,u.related=a||t,u.relatedRect=s||j(t),u.willInsertAfter=l,u.originalEvent=c,e.dispatchEvent(u),h&&(p=h.call(d,u,c)),p}function qe(e){e.draggable=!1}function Re(){_e=!1}function Be(e){for(var t=e.tagName+e.className+e.src+e.href+e.textContent,r=t.length,n=0;r--;)n+=t.charCodeAt(r);return n.toString(36)}function He(e){return setTimeout(e,0)}function Ie(e){return clearTimeout(e)}Pe.prototype={constructor:Pe,_isOutsideThisEl:function(e){this.el.contains(e)||e===this.el||(pe=null)},_getDirection:function(e,t){return"function"==typeof this.options.direction?this.options.direction.call(this,e,t,F):this.options.direction},_onTapStart:function(e){if(e.cancelable){var t=this,r=this.el,n=this.options,a=n.preventOnFilter,o=e.type,i=e.touches&&e.touches[0]||e.pointerType&&"touch"===e.pointerType&&e,s=(i||e).target,l=e.target.shadowRoot&&(e.path&&e.path[0]||e.composedPath&&e.composedPath()[0])||s,u=n.filter;if(function(e){Oe.length=0;for(var t=e.getElementsByTagName("input"),r=t.length;r--;){var n=t[r];n.checked&&Oe.push(n)}}(r),!F&&!(/mousedown|pointerdown/.test(o)&&0!==e.button||n.disabled)&&!l.isContentEditable&&(this.nativeDraggable||!c||!s||"SELECT"!==s.tagName.toUpperCase())&&!((s=g(s,n.draggable,r,!1))&&s.animated||J===s)){if(Z=S(s),ee=S(s,n.draggable),"function"==typeof u){if(u.call(this,e,s,this))return U({sortable:t,rootEl:l,name:"filter",targetEl:s,toEl:r,fromEl:r}),V("filter",t,{evt:e}),void(a&&e.cancelable&&e.preventDefault())}else if(u&&(u=u.split(",").some((function(n){if(n=g(l,n.trim(),r,!1))return U({sortable:t,rootEl:n,name:"filter",targetEl:s,fromEl:r,toEl:r}),V("filter",t,{evt:e}),!0}))))return void(a&&e.cancelable&&e.preventDefault());n.handle&&!g(l,n.handle,r,!1)||this._prepareDragStart(e,i,s)}}},_prepareDragStart:function(e,t,r){var n,a=this,c=a.el,l=a.options,u=c.ownerDocument;if(r&&!F&&r.parentNode===c){var p=j(r);if(Y=c,Q=(F=r).parentNode,X=F.nextSibling,J=r,re=l.group,Pe.dragged=F,ce=(ae={target:F,clientX:(t||e).clientX,clientY:(t||e).clientY}).clientX-p.left,le=ae.clientY-p.top,this._lastX=(t||e).clientX,this._lastY=(t||e).clientY,F.style["will-change"]="all",n=function(){V("delayEnded",a,{evt:e}),Pe.eventCanceled?a._onDrop():(a._disableDelayedDragEvents(),!s&&a.nativeDraggable&&(F.draggable=!0),a._triggerDragStart(e,t),U({sortable:a,name:"choose",originalEvent:e}),y(F,l.chosenClass,!0))},l.ignore.split(",").forEach((function(e){O(F,e.trim(),qe)})),d(u,"dragover",Me),d(u,"mousemove",Me),d(u,"touchmove",Me),d(u,"mouseup",a._onDrop),d(u,"touchend",a._onDrop),d(u,"touchcancel",a._onDrop),s&&this.nativeDraggable&&(this.options.touchStartThreshold=4,F.draggable=!0),V("delayStart",this,{evt:e}),!l.delay||l.delayOnTouchOnly&&!t||this.nativeDraggable&&(i||o))n();else{if(Pe.eventCanceled)return void this._onDrop();d(u,"mouseup",a._disableDelayedDrag),d(u,"touchend",a._disableDelayedDrag),d(u,"touchcancel",a._disableDelayedDrag),d(u,"mousemove",a._delayedDragTouchMoveHandler),d(u,"touchmove",a._delayedDragTouchMoveHandler),l.supportPointer&&d(u,"pointermove",a._delayedDragTouchMoveHandler),a._dragStartTimer=setTimeout(n,l.delay)}}},_delayedDragTouchMoveHandler:function(e){var t=e.touches?e.touches[0]:e;Math.max(Math.abs(t.clientX-this._lastX),Math.abs(t.clientY-this._lastY))>=Math.floor(this.options.touchStartThreshold/(this.nativeDraggable&&window.devicePixelRatio||1))&&this._disableDelayedDrag()},_disableDelayedDrag:function(){F&&qe(F),clearTimeout(this._dragStartTimer),this._disableDelayedDragEvents()},_disableDelayedDragEvents:function(){var e=this.el.ownerDocument;h(e,"mouseup",this._disableDelayedDrag),h(e,"touchend",this._disableDelayedDrag),h(e,"touchcancel",this._disableDelayedDrag),h(e,"mousemove",this._delayedDragTouchMoveHandler),h(e,"touchmove",this._delayedDragTouchMoveHandler),h(e,"pointermove",this._delayedDragTouchMoveHandler)},_triggerDragStart:function(e,t){t=t||"touch"==e.pointerType&&e,!this.nativeDraggable||t?d(document,this.options.supportPointer?"pointermove":t?"touchmove":"mousemove",this._onTouchMove):(d(F,"dragend",this),d(Y,"dragstart",this._onDragStart));try{document.selection?He((function(){document.selection.empty()})):window.getSelection().removeAllRanges()}catch(e){}},_dragStarted:function(e,t){if(me=!1,Y&&F){V("dragStarted",this,{evt:t}),this.nativeDraggable&&d(document,"dragover",Te);var r=this.options;!e&&y(F,r.dragClass,!1),y(F,r.ghostClass,!0),Pe.active=this,e&&this._appendGhost(),U({sortable:this,name:"start",originalEvent:t})}else this._nulling()},_emulateDragOver:function(){if(oe){this._lastX=oe.clientX,this._lastY=oe.clientY,ze();for(var e=document.elementFromPoint(oe.clientX,oe.clientY),t=e;e&&e.shadowRoot&&(e=e.shadowRoot.elementFromPoint(oe.clientX,oe.clientY))!==t;)t=e;if(F.parentNode[q]._isOutsideThisEl(e),t)do{if(t[q]&&t[q]._onDragOver({clientX:oe.clientX,clientY:oe.clientY,target:e,rootEl:t})&&!this.options.dragoverBubble)break;e=t}while(t=t.parentNode);De()}},_onTouchMove:function(e){if(ae){var t=this.options,r=t.fallbackTolerance,n=t.fallbackOffset,a=e.touches?e.touches[0]:e,o=G&&_(G,!0),i=G&&o&&o.a,s=G&&o&&o.d,c=je&&fe&&N(fe),l=(a.clientX-ae.clientX+n.x)/(i||1)+(c?c[0]-we[0]:0)/(i||1),u=(a.clientY-ae.clientY+n.y)/(s||1)+(c?c[1]-we[1]:0)/(s||1);if(!Pe.active&&!me){if(r&&Math.max(Math.abs(a.clientX-this._lastX),Math.abs(a.clientY-this._lastY))<r)return;this._onDragStart(e,!0)}if(G){o?(o.e+=l-(ie||0),o.f+=u-(se||0)):o={a:1,b:0,c:0,d:1,e:l,f:u};var p="matrix("+o.a+","+o.b+","+o.c+","+o.d+","+o.e+","+o.f+")";w(G,"webkitTransform",p),w(G,"mozTransform",p),w(G,"msTransform",p),w(G,"transform",p),ie=l,se=u,oe=a}e.cancelable&&e.preventDefault()}},_appendGhost:function(){if(!G){var e=this.options.fallbackOnBody?document.body:Y,t=j(F,!0,je,!0,e),r=this.options;if(je){for(fe=e;"static"===w(fe,"position")&&"none"===w(fe,"transform")&&fe!==document;)fe=fe.parentNode;fe!==document.body&&fe!==document.documentElement?(fe===document&&(fe=E()),t.top+=fe.scrollTop,t.left+=fe.scrollLeft):fe=E(),we=N(fe)}y(G=F.cloneNode(!0),r.ghostClass,!1),y(G,r.fallbackClass,!0),y(G,r.dragClass,!0),w(G,"transition",""),w(G,"transform",""),w(G,"box-sizing","border-box"),w(G,"margin",0),w(G,"top",t.top),w(G,"left",t.left),w(G,"width",t.width),w(G,"height",t.height),w(G,"opacity","0.8"),w(G,"position",je?"absolute":"fixed"),w(G,"zIndex","100000"),w(G,"pointerEvents","none"),Pe.ghost=G,e.appendChild(G),w(G,"transform-origin",ce/parseInt(G.style.width)*100+"% "+le/parseInt(G.style.height)*100+"%")}},_onDragStart:function(e,t){var r=this,n=e.dataTransfer,a=r.options;V("dragStart",this,{evt:e}),Pe.eventCanceled?this._onDrop():(V("setupClone",this),Pe.eventCanceled||((W=T(F)).draggable=!1,W.style["will-change"]="",this._hideClone(),y(W,this.options.chosenClass,!1),Pe.clone=W),r.cloneId=He((function(){V("clone",r),Pe.eventCanceled||(r.options.removeCloneOnHide||Y.insertBefore(W,F),r._hideClone(),U({sortable:r,name:"clone"}))})),!t&&y(F,a.dragClass,!0),t?(ge=!0,r._loopId=setInterval(r._emulateDragOver,50)):(h(document,"mouseup",r._onDrop),h(document,"touchend",r._onDrop),h(document,"touchcancel",r._onDrop),n&&(n.effectAllowed="move",a.setData&&a.setData.call(r,n,F)),d(document,"drop",r),w(F,"transform","translateZ(0)")),me=!0,r._dragStartId=He(r._dragStarted.bind(r,t,e)),d(document,"selectstart",r),ue=!0,c&&w(document.body,"user-select","none"))},_onDragOver:function(e){var t,r,a,o,i=this.el,s=e.target,c=this.options,l=c.group,u=Pe.active,p=re===l,d=c.sort,h=ne||u,f=this,m=!1;if(!_e){if(void 0!==e.preventDefault&&e.cancelable&&e.preventDefault(),s=g(s,c.draggable,i,!0),R("dragOver"),Pe.eventCanceled)return m;if(F.contains(e.target)||s.animated&&s.animatingX&&s.animatingY||f._ignoreWhileAnimating===s)return H(!1);if(ge=!1,u&&!c.disabled&&(p?d||(a=!Y.contains(F)):ne===this||(this.lastPutMode=re.checkPull(this,u,F,e))&&l.checkPut(this,u,F,e))){if(o="vertical"===this._getDirection(e,s),t=j(F),R("dragOverValid"),Pe.eventCanceled)return m;if(a)return Q=Y,B(),this._hideClone(),R("revert"),Pe.eventCanceled||(X?Y.insertBefore(F,X):Y.appendChild(F)),H(!0);var b=k(i,c.draggable);if(!b||function(e,t,r){var n=j(k(r.el,r.options.draggable));return t?e.clientX>n.right+10||e.clientX<=n.right&&e.clientY>n.bottom&&e.clientX>=n.left:e.clientX>n.right&&e.clientY>n.top||e.clientX<=n.right&&e.clientY>n.bottom+10}(e,o,this)&&!b.animated){if(b===F)return H(!1);if(b&&i===e.target&&(s=b),s&&(r=j(s)),!1!==Le(Y,i,F,t,s,r,e,!!s))return B(),i.appendChild(F),Q=i,I(),H(!0)}else if(s.parentNode===i){r=j(s);var v,_,O,E=F.parentNode!==i,C=!function(e,t,r){var n=r?e.left:e.top,a=r?t.left:t.top;return n===a||(r?e.right:e.bottom)===(r?t.right:t.bottom)||n+(r?e.width:e.height)/2===a+(r?t.width:t.height)/2}(F.animated&&F.toRect||t,s.animated&&s.toRect||r,o),N=o?"top":"left",z=x(s,"top","top")||x(F,"top","top"),D=z?z.scrollTop:void 0;if(pe!==s&&(_=r[N],ve=!1,ye=!C&&c.invertSwap||E),0!==(v=function(e,t,r,n,a,o,i,s){var c=n?e.clientY:e.clientX,l=n?r.height:r.width,u=n?r.top:r.left,p=n?r.bottom:r.right,d=!1;if(!i)if(s&&he<l*a){if(!ve&&(1===de?c>u+l*o/2:c<p-l*o/2)&&(ve=!0),ve)d=!0;else if(1===de?c<u+he:c>p-he)return-de}else if(c>u+l*(1-a)/2&&c<p-l*(1-a)/2)return function(e){return S(F)<S(e)?1:-1}(t);return(d=d||i)&&(c<u+l*o/2||c>p-l*o/2)?c>u+l/2?1:-1:0}(e,s,r,o,C?1:c.swapThreshold,null==c.invertedSwapThreshold?c.swapThreshold:c.invertedSwapThreshold,ye,pe===s))){var A=S(F);do{O=Q.children[A-=v]}while(O&&("none"===w(O,"display")||O===G))}if(0===v||O===s)return H(!1);pe=s,de=v;var T=s.nextElementSibling,P=!1,L=Le(Y,i,F,t,s,r,e,P=1===v);if(!1!==L)return 1!==L&&-1!==L||(P=1===L),_e=!0,setTimeout(Re,30),B(),P&&!T?i.appendChild(F):s.parentNode.insertBefore(F,P?T:s),z&&M(z,0,D-z.scrollTop),Q=F.parentNode,void 0===_||ye||(he=Math.abs(_-j(s)[N])),I(),H(!0)}if(i.contains(F))return H(!1)}return!1}function R(c,l){V(c,f,n({evt:e,isOwner:p,axis:o?"vertical":"horizontal",revert:a,dragRect:t,targetRect:r,canSort:d,fromSortable:h,target:s,completed:H,onMove:function(r,n){return Le(Y,i,F,t,r,j(r),e,n)},changed:I},l))}function B(){R("dragOverAnimationCapture"),f.captureAnimationState(),f!==h&&h.captureAnimationState()}function H(t){return R("dragOverCompleted",{insertion:t}),t&&(p?u._hideClone():u._showClone(f),f!==h&&(y(F,ne?ne.options.ghostClass:u.options.ghostClass,!1),y(F,c.ghostClass,!0)),ne!==f&&f!==Pe.active?ne=f:f===Pe.active&&ne&&(ne=null),h===f&&(f._ignoreWhileAnimating=s),f.animateAll((function(){R("dragOverAnimationComplete"),f._ignoreWhileAnimating=null})),f!==h&&(h.animateAll(),h._ignoreWhileAnimating=null)),(s===F&&!F.animated||s===i&&!s.animated)&&(pe=null),c.dragoverBubble||e.rootEl||s===document||(F.parentNode[q]._isOutsideThisEl(e.target),!t&&Me(e)),!c.dragoverBubble&&e.stopPropagation&&e.stopPropagation(),m=!0}function I(){$=S(F),te=S(F,c.draggable),U({sortable:f,name:"change",toEl:i,newIndex:$,newDraggableIndex:te,originalEvent:e})}},_ignoreWhileAnimating:null,_offMoveEvents:function(){h(document,"mousemove",this._onTouchMove),h(document,"touchmove",this._onTouchMove),h(document,"pointermove",this._onTouchMove),h(document,"dragover",Me),h(document,"mousemove",Me),h(document,"touchmove",Me)},_offUpEvents:function(){var e=this.el.ownerDocument;h(e,"mouseup",this._onDrop),h(e,"touchend",this._onDrop),h(e,"pointerup",this._onDrop),h(e,"touchcancel",this._onDrop),h(document,"selectstart",this)},_onDrop:function(e){var t=this.el,r=this.options;$=S(F),te=S(F,r.draggable),V("drop",this,{evt:e}),Q=F&&F.parentNode,$=S(F),te=S(F,r.draggable),Pe.eventCanceled||(me=!1,ye=!1,ve=!1,clearInterval(this._loopId),clearTimeout(this._dragStartTimer),Ie(this.cloneId),Ie(this._dragStartId),this.nativeDraggable&&(h(document,"drop",this),h(t,"dragstart",this._onDragStart)),this._offMoveEvents(),this._offUpEvents(),c&&w(document.body,"user-select",""),w(F,"transform",""),e&&(ue&&(e.cancelable&&e.preventDefault(),!r.dropBubble&&e.stopPropagation()),G&&G.parentNode&&G.parentNode.removeChild(G),(Y===Q||ne&&"clone"!==ne.lastPutMode)&&W&&W.parentNode&&W.parentNode.removeChild(W),F&&(this.nativeDraggable&&h(F,"dragend",this),qe(F),F.style["will-change"]="",ue&&!me&&y(F,ne?ne.options.ghostClass:this.options.ghostClass,!1),y(F,this.options.chosenClass,!1),U({sortable:this,name:"unchoose",toEl:Q,newIndex:null,newDraggableIndex:null,originalEvent:e}),Y!==Q?($>=0&&(U({rootEl:Q,name:"add",toEl:Q,fromEl:Y,originalEvent:e}),U({sortable:this,name:"remove",toEl:Q,originalEvent:e}),U({rootEl:Q,name:"sort",toEl:Q,fromEl:Y,originalEvent:e}),U({sortable:this,name:"sort",toEl:Q,originalEvent:e})),ne&&ne.save()):$!==Z&&$>=0&&(U({sortable:this,name:"update",toEl:Q,originalEvent:e}),U({sortable:this,name:"sort",toEl:Q,originalEvent:e})),Pe.active&&(null!=$&&-1!==$||($=Z,te=ee),U({sortable:this,name:"end",toEl:Q,originalEvent:e}),this.save())))),this._nulling()},_nulling:function(){V("nulling",this),Y=F=Q=G=X=W=J=K=ae=oe=ue=$=te=Z=ee=pe=de=ne=re=Pe.dragged=Pe.ghost=Pe.clone=Pe.active=null,Oe.forEach((function(e){e.checked=!0})),Oe.length=ie=se=0},handleEvent:function(e){switch(e.type){case"drop":case"dragend":this._onDrop(e);break;case"dragenter":case"dragover":F&&(this._onDragOver(e),function(e){e.dataTransfer&&(e.dataTransfer.dropEffect="move"),e.cancelable&&e.preventDefault()}(e));break;case"selectstart":e.preventDefault()}},toArray:function(){for(var e,t=[],r=this.el.children,n=0,a=r.length,o=this.options;n<a;n++)g(e=r[n],o.draggable,this.el,!1)&&t.push(e.getAttribute(o.dataIdAttr)||Be(e));return t},sort:function(e){var t={},r=this.el;this.toArray().forEach((function(e,n){var a=r.children[n];g(a,this.options.draggable,r,!1)&&(t[e]=a)}),this),e.forEach((function(e){t[e]&&(r.removeChild(t[e]),r.appendChild(t[e]))}))},save:function(){var e=this.options.store;e&&e.set&&e.set(this)},closest:function(e,t){return g(e,t||this.options.draggable,this.el,!1)},option:function(e,t){var r=this.options;if(void 0===t)return r[e];var n=H.modifyOption(this,e,t);r[e]=void 0!==n?n:t,"group"===e&&Ne(r)},destroy:function(){V("destroy",this);var e=this.el;e[q]=null,h(e,"mousedown",this._onTapStart),h(e,"touchstart",this._onTapStart),h(e,"pointerdown",this._onTapStart),this.nativeDraggable&&(h(e,"dragover",this),h(e,"dragenter",this)),Array.prototype.forEach.call(e.querySelectorAll("[draggable]"),(function(e){e.removeAttribute("draggable")})),this._onDrop(),this._disableDelayedDragEvents(),be.splice(be.indexOf(this.el),1),this.el=e=null},_hideClone:function(){if(!K){if(V("hideClone",this),Pe.eventCanceled)return;w(W,"display","none"),this.options.removeCloneOnHide&&W.parentNode&&W.parentNode.removeChild(W),K=!0}},_showClone:function(e){if("clone"===e.lastPutMode){if(K){if(V("showClone",this),Pe.eventCanceled)return;F.parentNode!=Y||this.options.group.revertClone?X?Y.insertBefore(W,X):Y.appendChild(W):Y.insertBefore(W,F),this.options.group.revertClone&&this.animate(F,W),w(W,"display",""),K=!1}}else this._hideClone()}},Ee&&d(document,"touchmove",(function(e){(Pe.active||me)&&e.cancelable&&e.preventDefault()})),Pe.utils={on:d,off:h,css:w,find:O,is:function(e,t){return!!g(e,t,e,!1)},extend:function(e,t){if(e&&t)for(var r in t)t.hasOwnProperty(r)&&(e[r]=t[r]);return e},throttle:A,closest:g,toggleClass:y,clone:T,index:S,nextTick:He,cancelNextTick:Ie,detectDirection:Se,getChild:C},Pe.get=function(e){return e[q]},Pe.mount=function(){var e=[].slice.call(arguments);e[0].constructor===Array&&(e=e[0]),e.forEach((function(e){if(!e.prototype||!e.prototype.constructor)throw"Sortable: Mounted plugin must be a constructor function, not "+{}.toString.call(e);e.utils&&(Pe.utils=n({},Pe.utils,e.utils)),H.mount(e)}))},Pe.create=function(e,t){return new Pe(e,t)},Pe.version="1.12.0";var Ve,Ue,Fe,Qe,Ge,Ye=[],Xe=[],Je=!1,We=!1,Ke=!1;function Ze(e,t){Xe.forEach((function(r,n){var a=t.children[r.sortableIndex+(e?Number(n):0)];a?t.insertBefore(r,a):t.appendChild(r)}))}function $e(){Ye.forEach((function(e){e!==Fe&&e.parentNode&&e.parentNode.removeChild(e)}))}var et=function(e){var t=e.originalEvent,r=e.putSortable,n=e.dragEl,a=e.dispatchSortableEvent,o=e.unhideGhostForTarget;if(t){var i=r||e.activeSortable;(0,e.hideGhostForTarget)();var s=t.changedTouches&&t.changedTouches.length?t.changedTouches[0]:t,c=document.elementFromPoint(s.clientX,s.clientY);o(),i&&!i.el.contains(c)&&(a("spill"),this.onSpill({dragEl:n,putSortable:r}))}};function tt(){}function rt(){}tt.prototype={startIndex:null,dragStart:function(e){this.startIndex=e.oldDraggableIndex},onSpill:function(e){var t=e.dragEl,r=e.putSortable;this.sortable.captureAnimationState(),r&&r.captureAnimationState();var n=C(this.sortable.el,this.startIndex,this.options);n?this.sortable.el.insertBefore(t,n):this.sortable.el.appendChild(t),this.sortable.animateAll(),r&&r.animateAll()},drop:et},Object.assign(tt,{pluginName:"revertOnSpill"}),rt.prototype={onSpill:function(e){var t=e.dragEl,r=e.putSortable||this.sortable;r.captureAnimationState(),t.parentNode&&t.parentNode.removeChild(t),r.animateAll()},drop:et},Object.assign(rt,{pluginName:"removeOnSpill"});var nt,at,ot,it,st,ct,lt=[],ut=!1;function pt(){lt.forEach((function(e){clearInterval(e.pid)})),lt=[]}function dt(){clearInterval(ct)}var ht=A((function(e,t,r,n){if(t.scroll){var a,o=(e.touches?e.touches[0]:e).clientX,i=(e.touches?e.touches[0]:e).clientY,s=t.scrollSensitivity,c=t.scrollSpeed,l=E(),u=!1;at!==r&&(at=r,pt(),a=t.scrollFn,!0===(nt=t.scroll)&&(nt=z(r,!0)));var p=0,d=nt;do{var h=d,f=j(h),m=f.top,g=f.bottom,b=f.left,v=f.right,y=f.width,_=f.height,O=void 0,x=void 0,C=h.scrollWidth,k=h.scrollHeight,S=w(h),N=h.scrollLeft,D=h.scrollTop;h===l?(O=y<C&&("auto"===S.overflowX||"scroll"===S.overflowX||"visible"===S.overflowX),x=_<k&&("auto"===S.overflowY||"scroll"===S.overflowY||"visible"===S.overflowY)):(O=y<C&&("auto"===S.overflowX||"scroll"===S.overflowX),x=_<k&&("auto"===S.overflowY||"scroll"===S.overflowY));var A=O&&(Math.abs(v-o)<=s&&N+y<C)-(Math.abs(b-o)<=s&&!!N),T=x&&(Math.abs(g-i)<=s&&D+_<k)-(Math.abs(m-i)<=s&&!!D);if(!lt[p])for(var P=0;P<=p;P++)lt[P]||(lt[P]={});lt[p].vx==A&&lt[p].vy==T&&lt[p].el===h||(lt[p].el=h,lt[p].vx=A,lt[p].vy=T,clearInterval(lt[p].pid),0==A&&0==T||(u=!0,lt[p].pid=setInterval(function(){n&&0===this.layer&&Pe.active._onTouchMove(st);var t=lt[this.layer].vy?lt[this.layer].vy*c:0,r=lt[this.layer].vx?lt[this.layer].vx*c:0;"function"==typeof a&&"continue"!==a.call(Pe.dragged.parentNode[q],r,t,e,st,lt[this.layer].el)||M(lt[this.layer].el,r,t)}.bind({layer:p}),24))),p++}while(t.bubbleScroll&&d!==l&&(d=z(d,!1)));ut=u}}),30);Pe.mount(new function(){function e(){for(var e in this.defaults={scroll:!0,scrollSensitivity:30,scrollSpeed:10,bubbleScroll:!0},this)"_"===e.charAt(0)&&"function"==typeof this[e]&&(this[e]=this[e].bind(this))}return e.prototype={dragStarted:function(e){var t=e.originalEvent;this.sortable.nativeDraggable?d(document,"dragover",this._handleAutoScroll):d(document,this.options.supportPointer?"pointermove":t.touches?"touchmove":"mousemove",this._handleFallbackAutoScroll)},dragOverCompleted:function(e){var t=e.originalEvent;this.options.dragOverBubble||t.rootEl||this._handleAutoScroll(t)},drop:function(){this.sortable.nativeDraggable?h(document,"dragover",this._handleAutoScroll):(h(document,"pointermove",this._handleFallbackAutoScroll),h(document,"touchmove",this._handleFallbackAutoScroll),h(document,"mousemove",this._handleFallbackAutoScroll)),dt(),pt(),clearTimeout(b),b=void 0},nulling:function(){st=at=nt=ut=ct=ot=it=null,lt.length=0},_handleFallbackAutoScroll:function(e){this._handleAutoScroll(e,!0)},_handleAutoScroll:function(e,t){var r=this,n=(e.touches?e.touches[0]:e).clientX,a=(e.touches?e.touches[0]:e).clientY,s=document.elementFromPoint(n,a);if(st=e,t||i||o||c){ht(e,this.options,s,t);var l=z(s,!0);!ut||ct&&n===ot&&a===it||(ct&&dt(),ct=setInterval((function(){var o=z(document.elementFromPoint(n,a),!0);o!==l&&(l=o,pt()),ht(e,r.options,o,t)}),10),ot=n,it=a)}else{if(!this.options.bubbleScroll||z(s,!0)===E())return void pt();ht(e,this.options,z(s,!1),!1)}}},Object.assign(e,{pluginName:"scroll",initializeByDefault:!0})}),Pe.mount(rt,tt),Pe.mount(new function(){function e(){this.defaults={swapClass:"sortable-swap-highlight"}}return e.prototype={dragStart:function(e){Ae=e.dragEl},dragOverValid:function(e){var t=e.completed,r=e.target,n=e.changed,a=e.cancel;if(e.activeSortable.options.swap){var o=this.options;if(r&&r!==this.sortable.el){var i=Ae;!1!==(0,e.onMove)(r)?(y(r,o.swapClass,!0),Ae=r):Ae=null,i&&i!==Ae&&y(i,o.swapClass,!1)}n(),t(!0),a()}},drop:function(e){var t,r,n,a,o,i,s=e.activeSortable,c=e.putSortable,l=e.dragEl,u=c||this.sortable,p=this.options;Ae&&y(Ae,p.swapClass,!1),Ae&&(p.swap||c&&c.options.swap)&&l!==Ae&&(u.captureAnimationState(),u!==s&&s.captureAnimationState(),i=(r=Ae).parentNode,(o=(t=l).parentNode)&&i&&!o.isEqualNode(r)&&!i.isEqualNode(t)&&(n=S(t),a=S(r),o.isEqualNode(i)&&n<a&&a++,o.insertBefore(r,o.children[n]),i.insertBefore(t,i.children[a])),u.animateAll(),u!==s&&s.animateAll())},nulling:function(){Ae=null}},Object.assign(e,{pluginName:"swap",eventProperties:function(){return{swapItem:Ae}}})}),Pe.mount(new function(){function e(e){for(var t in this)"_"===t.charAt(0)&&"function"==typeof this[t]&&(this[t]=this[t].bind(this));e.options.supportPointer?d(document,"pointerup",this._deselectMultiDrag):(d(document,"mouseup",this._deselectMultiDrag),d(document,"touchend",this._deselectMultiDrag)),d(document,"keydown",this._checkKeyDown),d(document,"keyup",this._checkKeyUp),this.defaults={selectedClass:"sortable-selected",multiDragKey:null,setData:function(t,r){var n="";Ye.length&&Ue===e?Ye.forEach((function(e,t){n+=(t?", ":"")+e.textContent})):n=r.textContent,t.setData("Text",n)}}}return e.prototype={multiDragKeyDown:!1,isMultiDrag:!1,delayStartGlobal:function(e){Fe=e.dragEl},delayEnded:function(){this.isMultiDrag=~Ye.indexOf(Fe)},setupClone:function(e){var t=e.sortable,r=e.cancel;if(this.isMultiDrag){for(var n=0;n<Ye.length;n++)Xe.push(T(Ye[n])),Xe[n].sortableIndex=Ye[n].sortableIndex,Xe[n].draggable=!1,Xe[n].style["will-change"]="",y(Xe[n],this.options.selectedClass,!1),Ye[n]===Fe&&y(Xe[n],this.options.chosenClass,!1);t._hideClone(),r()}},clone:function(e){var t=e.dispatchSortableEvent,r=e.cancel;this.isMultiDrag&&(this.options.removeCloneOnHide||Ye.length&&Ue===e.sortable&&(Ze(!0,e.rootEl),t("clone"),r()))},showClone:function(e){var t=e.cloneNowShown,r=e.cancel;this.isMultiDrag&&(Ze(!1,e.rootEl),Xe.forEach((function(e){w(e,"display","")})),t(),Ge=!1,r())},hideClone:function(e){var t=this,r=e.cloneNowHidden,n=e.cancel;this.isMultiDrag&&(Xe.forEach((function(e){w(e,"display","none"),t.options.removeCloneOnHide&&e.parentNode&&e.parentNode.removeChild(e)})),r(),Ge=!0,n())},dragStartGlobal:function(e){!this.isMultiDrag&&Ue&&Ue.multiDrag._deselectMultiDrag(),Ye.forEach((function(e){e.sortableIndex=S(e)})),Ye=Ye.sort((function(e,t){return e.sortableIndex-t.sortableIndex})),Ke=!0},dragStarted:function(e){var t=this,r=e.sortable;if(this.isMultiDrag){if(this.options.sort&&(r.captureAnimationState(),this.options.animation)){Ye.forEach((function(e){e!==Fe&&w(e,"position","absolute")}));var n=j(Fe,!1,!0,!0);Ye.forEach((function(e){e!==Fe&&P(e,n)})),We=!0,Je=!0}r.animateAll((function(){We=!1,Je=!1,t.options.animation&&Ye.forEach((function(e){L(e)})),t.options.sort&&$e()}))}},dragOver:function(e){var t=e.completed,r=e.cancel;We&&~Ye.indexOf(e.target)&&(t(!1),r())},revert:function(e){var t=e.fromSortable,r=e.rootEl,n=e.sortable,a=e.dragRect;Ye.length>1&&(Ye.forEach((function(e){n.addAnimationState({target:e,rect:We?j(e):a}),L(e),e.fromRect=a,t.removeAnimationState(e)})),We=!1,function(e,t){Ye.forEach((function(r,n){var a=t.children[r.sortableIndex+(e?Number(n):0)];a?t.insertBefore(r,a):t.appendChild(r)}))}(!this.options.removeCloneOnHide,r))},dragOverCompleted:function(e){var t=e.sortable,r=e.isOwner,n=e.activeSortable,a=e.parentEl,o=e.putSortable,i=this.options;if(e.insertion){if(r&&n._hideClone(),Je=!1,i.animation&&Ye.length>1&&(We||!r&&!n.options.sort&&!o)){var s=j(Fe,!1,!0,!0);Ye.forEach((function(e){e!==Fe&&(P(e,s),a.appendChild(e))})),We=!0}if(!r)if(We||$e(),Ye.length>1){var c=Ge;n._showClone(t),n.options.animation&&!Ge&&c&&Xe.forEach((function(e){n.addAnimationState({target:e,rect:Qe}),e.fromRect=Qe,e.thisAnimationDuration=null}))}else n._showClone(t)}},dragOverAnimationCapture:function(e){var t=e.dragRect,r=e.isOwner,n=e.activeSortable;if(Ye.forEach((function(e){e.thisAnimationDuration=null})),n.options.animation&&!r&&n.multiDrag.isMultiDrag){Qe=Object.assign({},t);var a=_(Fe,!0);Qe.top-=a.f,Qe.left-=a.e}},dragOverAnimationComplete:function(){We&&(We=!1,$e())},drop:function(e){var t=e.originalEvent,r=e.rootEl,n=e.parentEl,a=e.sortable,o=e.dispatchSortableEvent,i=e.oldIndex,s=e.putSortable,c=s||this.sortable;if(t){var l=this.options,u=n.children;if(!Ke)if(l.multiDragKey&&!this.multiDragKeyDown&&this._deselectMultiDrag(),y(Fe,l.selectedClass,!~Ye.indexOf(Fe)),~Ye.indexOf(Fe))Ye.splice(Ye.indexOf(Fe),1),Ve=null,I({sortable:a,rootEl:r,name:"deselect",targetEl:Fe,originalEvt:t});else{if(Ye.push(Fe),I({sortable:a,rootEl:r,name:"select",targetEl:Fe,originalEvt:t}),t.shiftKey&&Ve&&a.el.contains(Ve)){var p,d,h=S(Ve),f=S(Fe);if(~h&&~f&&h!==f)for(f>h?(d=h,p=f):(d=f,p=h+1);d<p;d++)~Ye.indexOf(u[d])||(y(u[d],l.selectedClass,!0),Ye.push(u[d]),I({sortable:a,rootEl:r,name:"select",targetEl:u[d],originalEvt:t}))}else Ve=Fe;Ue=c}if(Ke&&this.isMultiDrag){if((n[q].options.sort||n!==r)&&Ye.length>1){var m=j(Fe),g=S(Fe,":not(."+this.options.selectedClass+")");if(!Je&&l.animation&&(Fe.thisAnimationDuration=null),c.captureAnimationState(),!Je&&(l.animation&&(Fe.fromRect=m,Ye.forEach((function(e){if(e.thisAnimationDuration=null,e!==Fe){var t=We?j(e):m;e.fromRect=t,c.addAnimationState({target:e,rect:t})}}))),$e(),Ye.forEach((function(e){u[g]?n.insertBefore(e,u[g]):n.appendChild(e),g++})),i===S(Fe))){var b=!1;Ye.forEach((function(e){e.sortableIndex===S(e)||(b=!0)})),b&&o("update")}Ye.forEach((function(e){L(e)})),c.animateAll()}Ue=c}(r===n||s&&"clone"!==s.lastPutMode)&&Xe.forEach((function(e){e.parentNode&&e.parentNode.removeChild(e)}))}},nullingGlobal:function(){this.isMultiDrag=Ke=!1,Xe.length=0},destroyGlobal:function(){this._deselectMultiDrag(),h(document,"pointerup",this._deselectMultiDrag),h(document,"mouseup",this._deselectMultiDrag),h(document,"touchend",this._deselectMultiDrag),h(document,"keydown",this._checkKeyDown),h(document,"keyup",this._checkKeyUp)},_deselectMultiDrag:function(e){if(!(void 0!==Ke&&Ke||Ue!==this.sortable||e&&g(e.target,this.options.draggable,this.sortable.el,!1)||e&&0!==e.button))for(;Ye.length;){var t=Ye[0];y(t,this.options.selectedClass,!1),Ye.shift(),I({sortable:this.sortable,rootEl:this.sortable.el,name:"deselect",targetEl:t,originalEvt:e})}},_checkKeyDown:function(e){e.key===this.options.multiDragKey&&(this.multiDragKeyDown=!0)},_checkKeyUp:function(e){e.key===this.options.multiDragKey&&(this.multiDragKeyDown=!1)}},Object.assign(e,{pluginName:"multiDrag",utils:{select:function(e){var t=e.parentNode[q];t&&t.options.multiDrag&&!~Ye.indexOf(e)&&(Ue&&Ue!==t&&(Ue.multiDrag._deselectMultiDrag(),Ue=t),y(e,t.options.selectedClass,!0),Ye.push(e))},deselect:function(e){var t=e.parentNode[q],r=Ye.indexOf(e);t&&t.options.multiDrag&&~r&&(y(e,t.options.selectedClass,!1),Ye.splice(r,1))}},eventProperties:function(){var e=this,t=[],r=[];return Ye.forEach((function(n){var a;t.push({multiDragElement:n,index:n.sortableIndex}),a=We&&n!==Fe?-1:We?S(n,":not(."+e.options.selectedClass+")"):S(n),r.push({multiDragElement:n,index:a})})),{items:[].concat(Ye),clones:[].concat(Xe),oldIndicies:t,newIndicies:r}},optionListeners:{multiDragKey:function(e){return"ctrl"===(e=e.toLowerCase())?e="Control":e.length>1&&(e=e.charAt(0).toUpperCase()+e.substr(1)),e}}})}),t.default=Pe},function(e,t,r){var n=r(38);e.exports=function(){return n.Date.now()}},function(e,t,r){(function(t){var r="object"==typeof t&&t&&t.Object===Object&&t;e.exports=r}).call(this,r(32))},function(e,t,r){var n=r(37),a=r(103),o=/^\s+|\s+$/g,i=/^[-+]0x[0-9a-f]+$/i,s=/^0b[01]+$/i,c=/^0o[0-7]+$/i,l=parseInt;e.exports=function(e){if("number"==typeof e)return e;if(a(e))return NaN;if(n(e)){var t="function"==typeof e.valueOf?e.valueOf():e;e=n(t)?t+"":t}if("string"!=typeof e)return 0===e?e:+e;e=e.replace(o,"");var r=s.test(e);return r||c.test(e)?l(e.slice(2),r?2:8):i.test(e)?NaN:+e}},function(e,t,r){var n=r(104),a=r(107);e.exports=function(e){return"symbol"==typeof e||a(e)&&"[object Symbol]"==n(e)}},function(e,t,r){var n=r(39),a=r(105),o=r(106),i=n?n.toStringTag:void 0;e.exports=function(e){return null==e?void 0===e?"[object Undefined]":"[object Null]":i&&i in Object(e)?a(e):o(e)}},function(e,t,r){var n=r(39),a=Object.prototype,o=a.hasOwnProperty,i=a.toString,s=n?n.toStringTag:void 0;e.exports=function(e){var t=o.call(e,s),r=e[s];try{e[s]=void 0;var n=!0}catch(e){}var a=i.call(e);return n&&(t?e[s]=r:delete e[s]),a}},function(e,t){var r=Object.prototype.toString;e.exports=function(e){return r.call(e)}},function(e,t){e.exports=function(e){return null!=e&&"object"==typeof e}},function(e,t,r){"use strict";r.r(t);var n=r(40),a=(wp.customize.astraControl=wp.customize.Control.extend({initialize:function(e,t){var r=t||{};r.params=r.params||{},r.params.type||(r.params.type="ast-core"),r.params.content||(r.params.content=jQuery("<li></li>"),r.params.content.attr("id","customize-control-"+e.replace(/]/g,"").replace(/\[/g,"-")),r.params.content.attr("class","customize-control customize-control-"+r.params.type)),this.propertyElements=[],wp.customize.Control.prototype.initialize.call(this,e,r)},ready:function(){wp.customize.Control.prototype.ready.call(this),this.deferred.embedded.done()},embed:function(){var e=this,t=e.section();t&&wp.customize.section(t,(function(t){t.expanded()||wp.customize.settings.autofocus.control===e.id?e.actuallyEmbed():t.expanded.bind((function(t){t&&e.actuallyEmbed()}))}))},actuallyEmbed:function(){"resolved"!==this.deferred.embedded.state()&&(this.renderContent(),this.deferred.embedded.resolve())},focus:function(e){this.actuallyEmbed(),wp.customize.Control.prototype.focus.call(this,e)}}),r(0)),o=r(1),i=r.n(o),s=function(e){var t=null,r=null,n=null;return e.control.params.caption&&(t=Object(a.createElement)("span",{className:"customize-control-caption"},e.control.params.caption)),e.control.params.label&&(r=Object(a.createElement)("span",{className:"customize-control-title wp-ui-text-highlight"},e.control.params.label)),e.control.params.description&&(n=Object(a.createElement)("span",{className:"description customize-control-description"},e.control.params.description)),Object(a.createElement)(a.Fragment,null,t,Object(a.createElement)("div",{className:"ast-heading-wrapper wp-ui-highlight"},Object(a.createElement)("label",{className:"customizer-text"},r,n)))};s.propTypes={control:i.a.object.isRequired};var c=React.memo(s),l=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(c,{control:this}),this.container[0])}}),u=function(e){var t=e.control.setting.get(),r=e.control.params.settings.default;r=(r=r.replace("[","-")).replace("]","");var n="hidden-field-".concat(r);return Object(a.createElement)("input",{type:"hidden",className:n,"data-name":r,value:JSON.stringify(t)})};u.propTypes={control:i.a.object.isRequired};var p=React.memo(u),d=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(p,{control:this}),this.container[0])}}),h=r(41),f=r.n(h),m=function(e){var t=null,r=null,n=null;return e.control.params.label&&(t=Object(a.createElement)("span",{className:"customize-control-title"},e.control.params.label)),e.control.params.help&&(r=Object(a.createElement)("span",{className:"ast-description"},f()(e.control.params.help))),e.control.params.description&&(n=Object(a.createElement)("span",{className:"description customize-control-description"},e.control.params.description)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",{className:"customizer-text"},t,r,n))};m.propTypes={control:i.a.object.isRequired};var g=React.memo(m),b=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(g,{control:this}),this.container[0])}}),v=r(5),y=r.n(v),w=r(4),O=r.n(w),E=r(2),j=r(6),x=r(3),C=r.n(x);function k(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function S(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?k(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):k(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var N=function(e){var t=Object(x.useState)(e.control.setting.get()),r=O()(t,2),n=r[0],o=r[1],i=e.control.params,s=i.value,c=i.label,l=i.settings,u=n.url,p=n.new_tab,d=n.link_rel,h=l.default;h=(h=h.replace("[","-")).replace("]","");var f=null;return c&&(f=Object(a.createElement)("label",null,Object(a.createElement)("span",{className:"customize-control-title"},c))),Object(a.createElement)(a.Fragment,null,f,Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)(j.TextControl,{value:u,className:"ast-link-input",onChange:function(t){!function(t){var r=S(S({},n),{},{url:t});o(r),e.control.setting.set(r)}(t)}})),Object(a.createElement)("div",{className:"customize-control-content ast-link-open-in-new-tab-wrapper"},Object(a.createElement)("input",{type:"checkbox",id:"ast-link-open-in-new-tab",className:"ast-link-open-in-new-tab",name:"ast-link-open-in-new-tab",checked:p,onChange:function(){return t=S(S({},n),{},{new_tab:event.target.checked}),o(t),void e.control.setting.set(t);var t}}),Object(a.createElement)("label",null,Object(E.__)("Open in a New Tab"))),Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)("label",null,Object(a.createElement)("span",{className:"customize-control-title"},Object(E.__)("Button Link Rel"))),Object(a.createElement)(j.TextControl,{value:d,className:"ast-link-relationship",onChange:function(t){!function(t){var r=S(S({},n),{},{link_rel:t});o(r),e.control.setting.set(r)}(t)}})),Object(a.createElement)("input",{type:"hidden",id:"_customize-input-".concat(l.default),className:"customize-link-control-data",name:h,"data-customize-setting-link":l.default,"data-value":JSON.stringify(s)}))};N.propTypes={control:i.a.object.isRequired};var z=React.memo(N),D=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(z,{control:this}),this.container[0])}}),A=function(e){var t=e.control.params,r=t.caption,n=t.separator,o=t.label,i=t.description,s=null,c=null,l=null,u=null;return!1!==n&&(s=Object(a.createElement)("hr",null)),r&&(c=Object(a.createElement)("span",{className:"customize-control-caption"},r)),o&&(l=Object(a.createElement)("span",{className:"customize-control-title"},o),s=null),i&&(u=Object(a.createElement)("span",{className:"description customize-control-description"},i)),Object(a.createElement)(a.Fragment,null,c,s,Object(a.createElement)("label",{className:"customizer-text"},l,u))};A.propTypes={control:i.a.object.isRequired};var M=React.memo(A),T=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(M,{control:this}),this.container[0])}}),P=function(e){var t=null,r=null,n=e.control.params,o=n.label,i=n.help,s=n.name;return o&&(t=Object(a.createElement)("span",{className:"customize-control-title"},o)),i&&(r=Object(a.createElement)("span",{className:"ast-description"},i)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-toggle-desc-wrap"},Object(a.createElement)("label",{className:"customizer-text"},t,r,Object(a.createElement)("span",{className:"ast-adv-toggle-icon dashicons","data-control":s}))),Object(a.createElement)("div",{className:"ast-field-settings-wrap"}))};P.propTypes={control:i.a.object.isRequired};var L=React.memo(P),q=r(7),R=r.n(q);function B(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}var H=function(e){var t,r=Object(x.useState)(e.control.setting.get()),n=O()(r,2),o=n[0],i=n[1],s=function(t){var r=e.control.params.choices,n=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?B(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):B(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},o);if(event.target.classList.contains("connected"))for(var a in r)n[a]=event.target.value;else n[t]=event.target.value;e.control.setting.set(n),i(n)},c=e.control.params,l=c.label,u=c.description,p=c.linked_choices,d=c.id,h=c.choices,f=c.inputAttrs,m=c.name,g=Object(a.createElement)("span",{className:"customize-control-title"},l||Object(E.__)("Background","astra")),b=u?Object(a.createElement)("span",{className:"description customize-control-description"},u):null,v=null,w=Object(E.__)("Link Values Together","astra");return p&&(v=Object(a.createElement)("li",{key:d,className:"ast-border-input-item-link disconnected"},Object(a.createElement)("span",{className:"dashicons dashicons-admin-links ast-border-connected wp-ui-highlight",onClick:function(){!function(){for(var e=event.target.parentElement.parentElement.querySelectorAll(".ast-border-input"),t=0;t<e.length;t++)e[t].classList.remove("connected"),e[t].setAttribute("data-element-connect","");event.target.parentElement.classList.remove("disconnected")}()},"data-element-connect":d,title:w}),Object(a.createElement)("span",{className:"dashicons dashicons-editor-unlink ast-border-disconnected",onClick:function(){!function(){for(var e=event.target.dataset.elementConnect,t=event.target.parentElement.parentElement.querySelectorAll(".ast-border-input"),r=0;r<t.length;r++)t[r].classList.add("connected"),t[r].setAttribute("data-element-connect",e);event.target.parentElement.classList.add("disconnected")}()},"data-element-connect":d,title:w}))),t=Object.keys(h).map((function(e){if(h[e])var t=Object(a.createElement)("li",R()({},f,{key:e,className:"ast-border-input-item"}),Object(a.createElement)("input",{type:"number",className:"ast-border-input ast-border-desktop connected","data-id":e,"data-name":m,onChange:function(){return s(e)},value:o[e],"data-element-connect":d}),Object(a.createElement)("span",{className:"ast-border-title"},h[e]));return t})),Object(a.createElement)(a.Fragment,null,g,b,Object(a.createElement)("div",{className:"ast-border-outer-wrapper"},Object(a.createElement)("div",{className:"input-wrapper ast-border-wrapper"},Object(a.createElement)("ul",{className:"ast-border-wrapper desktop active"},v,t))))};H.propTypes={control:i.a.object.isRequired};var I=React.memo(H);function V(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function U(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?V(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):V(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var F=function(e){var t=Object(x.useState)(e.control.setting.get()),r=O()(t,2),n=r[0],o=r[1],i=function(t){var r=U({},n);r[t]=event.target.value,e.control.setting.set(r),o(r)},s=function(t){var r=U({},n);r["".concat(t,"-unit")]=event.target.value,e.control.setting.set(r),o(r)},c=function(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",o=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],c=e.control.params.units,l=!1;1===c.length&&(l=!0);var u=Object.keys(c).map((function(e){return Object(a.createElement)("option",{key:e,value:e},c[e])}));return!1===o?Object(a.createElement)(a.Fragment,null,Object(a.createElement)("input",{key:t+"input","data-id":t,className:"ast-responsive-input ast-non-reponsive ".concat(t," ").concat(r),type:"number",value:n[t],onChange:function(){i(t)}}),Object(a.createElement)("select",{key:t+"select",value:n["".concat(t,"-unit")],className:"ast-responsive-select ".concat(t),"data-id":"".concat(t,"-unit"),disabled:l,onChange:function(){s(t)}},u)):Object(a.createElement)(a.Fragment,null,Object(a.createElement)("input",{key:t+"input","data-id":t,className:"ast-responsive-input ".concat(t," ").concat(r),type:"number",value:n[t],onChange:function(){i(t)}}),Object(a.createElement)("select",{key:t+"select",value:n["".concat(t,"-unit")],className:"ast-responsive-select ".concat(t),"data-id":"".concat(t,"-unit"),disabled:l,onChange:function(){s(t)}},u))},l=e.control.params,u=l.description,p=l.label,d=l.responsive,h=null,f=null,m=null,g=null;return p&&(h=Object(a.createElement)("span",{className:"customize-control-title"},p),d&&(f=Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-btns"},Object(a.createElement)("li",{key:"desktop",className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{key:"tablet",className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{key:"mobile",className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))))),u&&(m=Object(a.createElement)("span",{className:"description customize-control-description"},u)),g=d?Object(a.createElement)(a.Fragment,null,c("desktop","active"),c("tablet"),c("mobile")):Object(a.createElement)(a.Fragment,null,c("desktop","active",!1)),Object(a.createElement)("label",{key:"customizer-text",className:"customizer-text"},h,f,m,Object(a.createElement)("div",{className:"input-wrapper ast-responsive-wrapper"},g))};F.propTypes={control:i.a.object.isRequired};var Q=React.memo(F);function G(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}var Y=function(e){var t,r,n=Object(x.useState)(e.control.setting.get()),o=O()(n,2),i=o[0],s=o[1],c=function(t){var r=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?G(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):G(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},i);r[t]=event.target.value,e.control.setting.set(r),s(r)},l=function(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=e.control.params,o=n.inputAttrs,s=n.suffix,l=null,u=[];if(s&&(l=Object(a.createElement)("span",{className:"ast-range-unit"},s)),void 0!==o){var p=o.split(" ");p.map((function(e,t){var r=e.split("=");void 0!==r[1]&&(u[r[0]]=r[1].replace(/"/g,""))}))}return Object(a.createElement)("div",{className:"input-field-wrapper ".concat(t," ").concat(r)},Object(a.createElement)("input",R()({type:"range"},u,{value:i[t],"data-reset_value":e.control.params.default[t],onChange:function(){c(t)}})),Object(a.createElement)("div",{className:"astra_range_value"},Object(a.createElement)("input",R()({type:"number"},u,{"data-id":t,className:"ast-responsive-range-value-input",value:i[t],onChange:function(){c(t)}})),l))},u=e.control.params,p=u.description,d=u.label,h=Object(E.__)("Back to default","astra"),f=null,m=null,g=null;return d&&(f=Object(a.createElement)("span",{className:"customize-control-title"},d),m=Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-slider-btns"},Object(a.createElement)("li",{className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop active","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"}))))),p&&(g=Object(a.createElement)("span",{className:"description customize-control-description"},p)),t=Object(a.createElement)(a.Fragment,null,l("desktop","active"),l("tablet"),l("mobile")),r=Object(a.createElement)("div",{className:"ast-responsive-slider-reset",onClick:function(t){!function(t){t.preventDefault(),e.control.setting.set(e.control.params.default),s(e.control.params.default)}(t)}},Object(a.createElement)("span",{className:"dashicons dashicons-image-rotate ast-control-tooltip",title:h})),Object(a.createElement)("label",{key:"customizer-text"},f,m,g,Object(a.createElement)("div",{className:"wrapper"},t,r))};Y.propTypes={control:i.a.object.isRequired};var X=React.memo(Y);function J(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function W(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?J(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):J(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var K=function(e){var t=e.control.setting.get();t=void 0===t||""===t?e.control.params.value:t;var r,n,o=Object(x.useState)(t),i=O()(o,2),s=i[0],c=i[1],l=function(){for(var e=event.target.parentElement.parentElement.querySelectorAll(".ast-spacing-input"),t=0;t<e.length;t++)e[t].classList.remove("connected"),e[t].setAttribute("data-element-connect","");event.target.parentElement.classList.remove("disconnected")},u=function(){for(var e=event.target.dataset.elementConnect,t=event.target.parentElement.parentElement.querySelectorAll(".ast-spacing-input"),r=0;r<t.length;r++)t[r].classList.add("connected"),t[r].setAttribute("data-element-connect",e);event.target.parentElement.classList.add("disconnected")},p=function(t,r){var n=e.control.params.choices,a=W({},s),o=W({},a[t]);if(event.target.classList.contains("connected"))for(var i in n)o[i]=event.target.value;else o[r]=event.target.value;a[t]=o,e.control.setting.set(a),c(a)},d=function(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=W({},s);n["".concat(t,"-unit")]=r,e.control.setting.set(n),c(n)},h=function(e){return Object(a.createElement)("input",{key:e,type:"hidden",onChange:function(){return d(e,"")},className:"ast-spacing-unit-input ast-spacing-".concat(e,"-unit"),"data-device":"".concat(e),value:s["".concat(e,"-unit")]})},f=function(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=e.control.params,o=n.linked_choices,i=n.id,c=n.choices,h=n.inputAttrs,f=n.unit_choices,m=Object(E.__)("Link Values Together","astra"),g=null,b=null,v=null;return o&&(g=Object(a.createElement)("li",{key:"connect-disconnect"+t,className:"ast-spacing-input-item-link disconnected"},Object(a.createElement)("span",{key:"connect"+t,className:"dashicons dashicons-admin-links ast-spacing-connected wp-ui-highlight",onClick:function(){l()},"data-element-connect":i,title:m}),Object(a.createElement)("span",{key:"disconnect"+t,className:"dashicons dashicons-editor-unlink ast-spacing-disconnected",onClick:function(){u()},"data-element-connect":i,title:m}))),c&&(b=Object.keys(c).map((function(e){return Object(a.createElement)("li",R()({key:e},h,{className:"ast-spacing-input-item"}),Object(a.createElement)("input",{type:"number",className:"ast-spacing-input ast-spacing-".concat(t," connected"),"data-id":e,value:s[t][e],onChange:function(){return p(t,e)},"data-element-connect":i}),Object(a.createElement)("span",{className:"ast-spacing-title"},c[e]))}))),f&&(v=Object.values(f).map((function(e){var r="";return s["".concat(t,"-unit")]===e&&(r="active"),Object(a.createElement)("li",{key:e,className:"single-unit ".concat(r),onClick:function(){return d(t,e)},"data-unit":e},Object(a.createElement)("span",{className:"unit-text"},e))}))),Object(a.createElement)("ul",{key:t,className:"ast-spacing-wrapper ".concat(t," ").concat(r)},g,b,Object(a.createElement)("ul",{key:"responsive-units",className:"ast-spacing-responsive-units ast-spacing-".concat(t,"-responsive-units")},v))},m=e.control.params,g=m.label,b=m.description,v=null,y=null;return g&&(v=Object(a.createElement)("span",{className:"customize-control-title"},g)),b&&(y=Object(a.createElement)("span",{className:"description customize-control-description"},b)),r=Object(a.createElement)(a.Fragment,null,f("desktop","active"),f("tablet"),f("mobile")),n=Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"unit-input-wrapper ast-spacing-unit-wrapper"},h("desktop"),h("tablet"),h("mobile")),Object(a.createElement)("ul",{key:"ast-spacing-responsive-btns",className:"ast-spacing-responsive-btns"},Object(a.createElement)("li",{key:"desktop",className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop active","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{key:"tablet",className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{key:"mobile",className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"}))))),Object(a.createElement)("label",{key:"ast-spacing-responsive",className:"ast-spacing-responsive",htmlFor:"ast-spacing"},v,y,Object(a.createElement)("div",{className:"ast-spacing-responsive-outer-wrapper"},Object(a.createElement)("div",{className:"input-wrapper ast-spacing-responsive-wrapper"},r),Object(a.createElement)("div",{className:"ast-spacing-responsive-units-screen-wrap"},n)))};K.propTypes={control:i.a.object.isRequired};var Z=React.memo(K);function $(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function ee(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?$(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):$(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var te=function(e){var t=e.control.setting.get();t=void 0===t||""===t?e.control.params.value:t;var r,n,o=Object(x.useState)(t),i=O()(o,2),s=i[0],c=i[1],l=function(){for(var e=event.target.parentElement.parentElement.querySelectorAll(".ast-box-shadow-input"),t=0;t<e.length;t++)e[t].classList.remove("connected"),e[t].setAttribute("data-element-connect","");event.target.parentElement.classList.remove("disconnected")},u=function(){for(var e=event.target.dataset.elementConnect,t=event.target.parentElement.parentElement.querySelectorAll(".ast-box-shadow-input"),r=0;r<t.length;r++)t[r].classList.add("connected"),t[r].setAttribute("data-element-connect",e);event.target.parentElement.classList.add("disconnected")},p=function(t,r){var n=e.control.params.choices,a=ee({},s),o=ee({},a[t]);if(event.target.classList.contains("connected"))for(var i in n)o[i]=event.target.value;else o[r]=event.target.value;a[t]=o,e.control.setting.set(a),c(a)},d=function(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=ee({},s);n["".concat(t,"-unit")]=r,e.control.setting.set(n),c(n)},h=function(e){return Object(a.createElement)("input",{key:e,type:"hidden",onChange:function(){return d(e,"")},className:"ast-box-shadow-unit-input ast-box-shadow-".concat(e,"-unit"),"data-device":"".concat(e),value:s["".concat(e,"-unit")]})},f=function(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=e.control.params,o=n.linked_choices,i=n.id,c=n.choices,h=n.inputAttrs,f=n.unit_choices,m=Object(E.__)("Link Values Together","astra"),g=null,b=null,v=null;return o&&(g=Object(a.createElement)("li",{key:"connect-disconnect"+t,className:"ast-box-shadow-input-item-link disconnected"},Object(a.createElement)("span",{key:"connect"+t,className:"dashicons dashicons-admin-links ast-box-shadow-connected wp-ui-highlight",onClick:function(){l()},"data-element-connect":i,title:m}),Object(a.createElement)("span",{key:"disconnect"+t,className:"dashicons dashicons-editor-unlink ast-box-shadow-disconnected",onClick:function(){u()},"data-element-connect":i,title:m}))),c&&(b=Object.keys(c).map((function(e){return Object(a.createElement)("li",R()({key:e},h,{className:"ast-box-shadow-input-item"}),Object(a.createElement)("input",{type:"number",className:"ast-box-shadow-input ast-box-shadow-".concat(t," connected"),"data-id":e,value:s[t][e],onChange:function(){return p(t,e)},"data-element-connect":i}),Object(a.createElement)("span",{className:"ast-box-shadow-title"},c[e]))}))),f&&(v=Object.values(f).map((function(e){var r="";return s["".concat(t,"-unit")]===e&&(r="active"),Object(a.createElement)("li",{key:e,className:"single-unit ".concat(r),onClick:function(){return d(t,e)},"data-unit":e},Object(a.createElement)("span",{className:"unit-text"},e))}))),Object(a.createElement)("ul",{key:t,className:"ast-box-shadow-wrapper ".concat(t," ").concat(r)},g,b,Object(a.createElement)("ul",{key:"responsive-units",className:"ast-box-shadow-responsive-units ast-box-shadow-".concat(t,"-responsive-units")},v))},m=e.control.params,g=m.label,b=m.description,v=null,y=null;return g&&(v=Object(a.createElement)("span",{className:"customize-control-title"},g)),b&&(y=Object(a.createElement)("span",{className:"description customize-control-description"},b)),r=Object(a.createElement)(a.Fragment,null,f("desktop","active"),f("tablet"),f("mobile")),n=Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"unit-input-wrapper ast-box-shadow-unit-wrapper"},h("desktop"),h("tablet"),h("mobile")),Object(a.createElement)("ul",{key:"ast-box-shadow-responsive-btns",className:"ast-box-shadow-responsive-btns"},Object(a.createElement)("li",{key:"desktop",className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop active","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{key:"tablet",className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{key:"mobile",className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"}))))),Object(a.createElement)("label",{key:"ast-box-shadow-responsive",className:"ast-box-shadow-responsive",htmlFor:"ast-box-shadow"},v,y,Object(a.createElement)("div",{className:"ast-box-shadow-responsive-outer-wrapper"},Object(a.createElement)("div",{className:"input-wrapper ast-box-shadow-responsive-wrapper"},r),Object(a.createElement)("div",{className:"ast-box-shadow-responsive-units-screen-wrap"},n)))};te.propTypes={control:i.a.object.isRequired};var re=React.memo(te),ne=function(e){var t=Object(x.useState)(e.control.setting.get()),r=O()(t,2),n=r[0],o=r[1],i=e.control.params,s=i.label,c=i.description,l=i.suffix,u=i.link,p=i.inputAttrs,d=i.name,h=null,f=null,m=null,g=[],b=Object(E.__)("Back to default","astra");(s&&(h=Object(a.createElement)("label",null,Object(a.createElement)("span",{className:"customize-control-title"},s))),c&&(f=Object(a.createElement)("span",{className:"description customize-control-description"},c)),l&&(m=Object(a.createElement)("span",{className:"ast-range-unit"},l)),void 0!==p)&&p.split(" ").map((function(e,t){var r=e.split("=");void 0!==r[1]&&(g[r[0]]=r[1].replace(/"/g,""))}));void 0!==u&&u.split(" ").map((function(e,t){var r=e.split("=");void 0!==r[1]&&(g[r[0]]=r[1].replace(/"/g,""))}));var v=function(t){o(t),e.control.setting.set(t)};return Object(a.createElement)("label",null,h,f,Object(a.createElement)("div",{className:"wrapper"},Object(a.createElement)("input",R()({},g,{type:"range",value:n,"data-reset_value":e.control.params.default,onChange:function(){return v(event.target.value)}})),Object(a.createElement)("div",{className:"astra_range_value"},Object(a.createElement)("input",R()({},g,{type:"number","data-name":d,className:"value ast-range-value-input",value:n,onChange:function(){return v(event.target.value)}})),m),Object(a.createElement)("div",{className:"ast-slider-reset",onClick:function(){v(e.control.params.default)}},Object(a.createElement)("span",{className:"dashicons dashicons-image-rotate ast-control-tooltip",title:b}))))};ne.propTypes={control:i.a.object.isRequired};var ae=React.memo(ne),oe=r(42),ie=r.n(oe),se=r(11),ce=r.n(se),le=r(12),ue=r.n(le),pe=r(8),de=r.n(pe),he=r(13),fe=r.n(he),me=r(14),ge=r.n(me),be=r(9),ve=r.n(be),ye=r(43);function we(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=ve()(e);if(t){var a=ve()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return ge()(this,r)}}var _e=function(e){fe()(r,e);var t=we(r);function r(e){var n;return ce()(this,r),(n=t.apply(this,arguments)).onChangeComplete=n.onChangeComplete.bind(de()(n)),n.onPaletteChangeComplete=n.onPaletteChangeComplete.bind(de()(n)),n.onChangeGradientComplete=n.onChangeGradientComplete.bind(de()(n)),n.renderImageSettings=n.renderImageSettings.bind(de()(n)),n.onRemoveImage=n.onRemoveImage.bind(de()(n)),n.onSelectImage=n.onSelectImage.bind(de()(n)),n.open=n.open.bind(de()(n)),n.onColorClearClick=n.onColorClearClick.bind(de()(n)),n.state={isVisible:!1,refresh:!1,color:n.props.color,modalCanClose:!0,backgroundType:n.props.backgroundType,supportGradient:void 0!==j.__experimentalGradientPicker},n}return ue()(r,[{key:"onResetRefresh",value:function(){!0===this.state.refresh?this.setState({refresh:!1}):this.setState({refresh:!0})}},{key:"render",value:function(){var e=this,t=this.state,r=t.refresh,n=t.modalCanClose,o=t.isVisible,i=t.supportGradient,s=t.backgroundType,c=this.props,l=c.allowGradient,u=c.allowImage,p=function(){n&&!0===o&&e.setState({isVisible:!1})},d=!(!l||!i),h=[{name:"color",title:Object(E.__)("Color","astra"),className:"astra-color-background"}];if(d){var f={name:"gradient",title:Object(E.__)("Gradient","astra"),className:"astra-image-background"};h.push(f)}if(u){var m={name:"image",title:Object(E.__)("Image","astra"),className:"astra-image-background"};h.push(m)}var g=[],b=0;return ie()(astColorPalette.colors).forEach((function(e){var t={};Object.assign(t,{name:b+"_"+e}),Object.assign(t,{color:e}),g.push(t),b++})),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"color-button-wrap"},Object(a.createElement)(j.Button,{className:o?"astra-color-icon-indicate open":"astra-color-icon-indicate",onClick:function(){o?p():(!0===r?e.setState({refresh:!1}):e.setState({refresh:!0}),e.setState({isVisible:!0}))}},("color"===s||"gradient"===s)&&Object(a.createElement)(j.ColorIndicator,{className:"astra-advanced-color-indicate",colorValue:this.props.color}),"image"===s&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(j.ColorIndicator,{className:"astra-advanced-color-indicate",colorValue:"#ffffff"}),Object(a.createElement)(j.Dashicon,{icon:"format-image"})))),Object(a.createElement)("div",{className:"astra-color-picker-wrap"},Object(a.createElement)(a.Fragment,null,o&&Object(a.createElement)("div",{className:"astra-popover-color",onClose:p},1<h.length&&Object(a.createElement)(j.TabPanel,{className:"astra-popover-tabs astra-background-tabs",activeClass:"active-tab",initialTabName:s,tabs:h},(function(t){var n;return t.name&&("gradient"===t.name&&(n=Object(a.createElement)(a.Fragment,null,Object(a.createElement)(j.__experimentalGradientPicker,{className:"ast-gradient-color-picker",value:e.props.color&&e.props.color.includes("gradient")?e.props.color:"",onChange:function(t){return e.onChangeGradientComplete(t)}}))),"image"===t.name?n=e.renderImageSettings():"color"===t.name&&(n=Object(a.createElement)(a.Fragment,null,r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(j.ColorPicker,{color:e.props.color,onChangeComplete:function(t){return e.onChangeComplete(t)}})),!r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(j.ColorPicker,{color:e.props.color,onChangeComplete:function(t){return e.onChangeComplete(t)}})),Object(a.createElement)(j.ColorPalette,{colors:g,value:e.props.color,clearable:!1,disableCustomColors:!0,className:"ast-color-palette",onChange:function(t){return e.onPaletteChangeComplete(t)}}),Object(a.createElement)("button",{type:"button",onClick:function(){e.onColorClearClick()},className:"ast-clear-btn-inside-picker components-button common components-circular-option-picker__clear is-secondary is-small"},Object(E.__)("Clear","astra"))))),Object(a.createElement)("div",null,n)})),1===h.length&&Object(a.createElement)(a.Fragment,null,r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(j.ColorPicker,{color:this.props.color,onChangeComplete:function(t){return e.onChangeComplete(t)}})),!r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(j.ColorPicker,{color:this.props.color,onChangeComplete:function(t){return e.onChangeComplete(t)}})),Object(a.createElement)(j.ColorPalette,{colors:g,value:this.props.color,clearable:!1,disableCustomColors:!0,className:"ast-color-palette",onChange:function(t){return e.onPaletteChangeComplete(t)}}),Object(a.createElement)("button",{type:"button",onClick:function(){e.onColorClearClick()},className:"ast-clear-btn-inside-picker components-button components-circular-option-picker__clear is-secondary is-small"},Object(E.__)("Clear","astra")))))))}},{key:"onColorClearClick",value:function(){!0===this.state.refresh?this.setState({refresh:!1}):this.setState({refresh:!0}),this.props.onChangeComplete("","color"),wp.customize.previewer.refresh()}},{key:"onChangeGradientComplete",value:function(e){this.setState({backgroundType:"gradient"}),this.props.onChangeComplete(e,"gradient")}},{key:"onChangeComplete",value:function(e){e.rgb&&e.rgb.a&&1!==e.rgb.a?(e.rgb.r,e.rgb.g,e.rgb.b,e.rgb.a):e.hex,this.setState({backgroundType:"color"}),this.props.onChangeComplete(e,"color")}},{key:"onPaletteChangeComplete",value:function(e){this.setState({color:e}),!0===this.state.refresh?this.setState({refresh:!1}):this.setState({refresh:!0}),this.props.onChangeComplete(e,"color")}},{key:"onSelectImage",value:function(e){this.setState({modalCanClose:!0}),this.setState({backgroundType:"image"}),this.props.onSelectImage(e,"image")}},{key:"onRemoveImage",value:function(){this.setState({modalCanClose:!0}),this.props.onSelectImage("")}},{key:"open",value:function(e){this.setState({modalCanClose:!1}),e()}},{key:"onChangeImageOptions",value:function(e,t,r){this.setState({backgroundType:"image"}),this.props.onChangeImageOptions(t,r,"image")}},{key:"toggleMoreSettings",value:function(){var e=event.target.parentElement.parentElement,t=e.querySelector(".more-settings"),r=e.querySelector(".media-position-setting"),n=t.dataset.direction;t.dataset.id;"down"===n?(t.setAttribute("data-direction","up"),e.querySelector(".message").innerHTML=Object(E.__)("Less Settings"),e.querySelector(".icon").innerHTML="↑"):(t.setAttribute("data-direction","down"),e.querySelector(".message").innerHTML=Object(E.__)("More Settings"),e.querySelector(".icon").innerHTML="↓"),r.classList.contains("hide-settings")?r.classList.remove("hide-settings"):r.classList.add("hide-settings")}},{key:"renderImageSettings",value:function(){var e=this;return Object(a.createElement)(a.Fragment,null,(this.props.media.url||this.props.backgroundImage)&&Object(a.createElement)("img",{src:this.props.media.url?this.props.media.url:this.props.backgroundImage}),Object(a.createElement)(ye.MediaUpload,{title:Object(E.__)("Select Background Image","astra"),onSelect:function(t){return e.onSelectImage(t)},allowedTypes:["image"],value:this.props.media&&this.props.media?this.props.media:"",render:function(t){var r=t.open;return Object(a.createElement)(j.Button,{className:"upload-button button-add-media",isDefault:!0,onClick:function(){return e.open(r)}},e.props.media||e.props.backgroundImage?Object(E.__)("Replace image","astra"):Object(E.__)("Select Background Image","astra"))}}),(this.props.media||this.props.backgroundImage)&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(j.Button,{className:"ast-bg-img-remove",onClick:this.onRemoveImage,isLink:!0,isDestructive:!0},Object(E.__)("Remove Image","astra")),Object(a.createElement)("a",{href:"#",className:"more-settings",onClick:this.toggleMoreSettings,"data-direction":"down","data-id":"desktop"},Object(a.createElement)("span",{className:"message"}," ",Object(E.__)("More Settings")," "),Object(a.createElement)("span",{className:"icon"}," ↓ ")),Object(a.createElement)("div",{className:"media-position-setting hide-settings"},Object(a.createElement)(j.SelectControl,{label:Object(E.__)("Image Position"),value:this.props.backgroundPosition,onChange:function(t){return e.onChangeImageOptions("backgroundPosition","background-position",t)},options:[{value:"left top",label:Object(E.__)("Left Top","astra")},{value:"left center",label:Object(E.__)("Left Center","astra")},{value:"left bottom",label:Object(E.__)("Left Bottom","astra")},{value:"right top",label:Object(E.__)("Right Top","astra")},{value:"right center",label:Object(E.__)("Right Center","astra")},{value:"right bottom",label:Object(E.__)("Right Bottom","astra")},{value:"center top",label:Object(E.__)("Center Top","astra")},{value:"center center",label:Object(E.__)("Center Center","astra")},{value:"center bottom",label:Object(E.__)("Center Bottom","astra")}]}),Object(a.createElement)(j.SelectControl,{label:Object(E.__)("Attachment","astra"),value:this.props.backgroundAttachment,onChange:function(t){return e.onChangeImageOptions("backgroundAttachment","background-attachment",t)},options:[{value:"fixed",label:Object(E.__)("Fixed","astra")},{value:"scroll",label:Object(E.__)("Scroll","astra")}]}),Object(a.createElement)(j.SelectControl,{label:Object(E.__)("Repeat","astra"),value:this.props.backgroundRepeat,onChange:function(t){return e.onChangeImageOptions("backgroundRepeat","background-repeat",t)},options:[{value:"no-repeat",label:Object(E.__)("No Repeat","astra")},{value:"repeat",label:Object(E.__)("Repeat All","astra")},{value:"repeat-x",label:Object(E.__)("Repeat Horizontally","astra")},{value:"repeat-y",label:Object(E.__)("Repeat Vertically","astra")}]}),Object(a.createElement)(j.SelectControl,{label:Object(E.__)("Size","astra"),value:this.props.backgroundSize,onChange:function(t){return e.onChangeImageOptions("backgroundSize","background-size",t)},options:[{value:"auto",label:Object(E.__)("Auto","astra")},{value:"cover",label:Object(E.__)("Cover","astra")},{value:"contain",label:Object(E.__)("Contain","astra")}]}))))}}]),r}(a.Component);_e.propTypes={color:i.a.string,usePalette:i.a.bool,palette:i.a.string,presetColors:i.a.object,onChangeComplete:i.a.func,onPaletteChangeComplete:i.a.func,onChange:i.a.func,customizer:i.a.object};var Oe=_e;function Ee(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function je(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Ee(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Ee(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var xe=function(e){var t,r=Object(x.useState)(e.control.setting.get()),n=O()(r,2),o=n[0],i=n[1],s=function(t,r){var n="";t&&(n="string"==typeof t||t instanceof String?t:void 0!==t.rgb&&void 0!==t.rgb.a&&1!==t.rgb.a?"rgba("+t.rgb.r+","+t.rgb.g+","+t.rgb.b+","+t.rgb.a+")":t.hex);var a=je({},o);a["background-color"]=n,a["background-type"]=r,e.control.setting.set(a),i(a)},c=e.control.params,l=c.defaultValue,u=c.label,p=c.description,d="#RRGGBB",h=Object(a.createElement)("span",{className:"customize-control-title"},u||Object(E.__)("Background","astra")),f=p?Object(a.createElement)("span",{className:"description customize-control-description"},p):null;return l&&(d="#"!==l.substring(0,1)?"#"+l:l,defaultValueAttr=" data-default-color="+d),t=Object(a.createElement)("div",{className:"background-wrapper"},Object(a.createElement)("div",{className:"background-container"},Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:JSON.stringify(o)===JSON.stringify(e.control.params.default),onClick:function(t){t.preventDefault();var r=JSON.parse(JSON.stringify(e.control.params.default));void 0!==r&&""!==r&&(void 0!==r["background-color"]&&""!==r["background-color"]||(r["background-color"]="",wp.customize.previewer.refresh()),void 0!==r["background-image"]&&""!==r["background-image"]||(r["background-image"]="",wp.customize.previewer.refresh()),void 0!==r["background-media"]&&""!==r["background-media"]||(r["background-media"]="",wp.customize.previewer.refresh())),e.control.setting.set(r),i(r)}},Object(a.createElement)(j.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}})))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)(Oe,{color:void 0!==o["background-color"]&&o["background-color"]?o["background-color"]:"",onChangeComplete:function(e,t){return s(e,t)},media:void 0!==o["background-media"]&&o["background-media"]?o["background-media"]:"",backgroundImage:void 0!==o["background-image"]&&o["background-image"]?o["background-image"]:"",backgroundAttachment:void 0!==o["background-attachment"]&&o["background-attachment"]?o["background-attachment"]:"",backgroundPosition:void 0!==o["background-position"]&&o["background-position"]?o["background-position"]:"",backgroundRepeat:void 0!==o["background-repeat"]&&o["background-repeat"]?o["background-repeat"]:"",backgroundSize:void 0!==o["background-size"]&&o["background-size"]?o["background-size"]:"",onSelectImage:function(t,r){return function(t,r){var n=je({},o);n["background-media"]=t.id,n["background-image"]=t.url,n["background-type"]=r,e.control.setting.set(n),i(n)}(t,r)},onChangeImageOptions:function(t,r,n){return function(t,r,n){var a=je({},o);a[t]=r,a["background-type"]=n,e.control.setting.set(a),i(a)}(t,r,n)},backgroundType:void 0!==o["background-type"]&&o["background-type"]?o["background-type"]:"color",allowGradient:!0,allowImage:!0})))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,h,f),Object(a.createElement)("div",{className:"customize-control-content"},t))};xe.propTypes={control:i.a.object.isRequired};var Ce=React.memo(xe);function ke(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function Se(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ke(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ke(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var Ne=function(e){var t=e.control.setting.get(),r=e.control.params.default,n=Object(x.useState)({value:t}),o=O()(n,2),i=o[0],s=o[1],c=function(t){s((function(e){return Se(Se({},e),{},{value:t})})),e.control.setting.set(t)},l=function(t){var r=Se({},e.control.setting.get());if(!i.value[t]["background-type"]){var n=Se({},r[t]);i.value[t]["background-color"]&&(n["background-type"]="color",r[t]=n,c(r),i.value[t]["background-color"].includes("gradient")&&(n["background-type"]="gradient",r[t]=n,c(r))),i.value[t]["background-image"]&&(n["background-type"]="image",r[t]=n,c(r))}},u=function(e){for(var t=!0,n=0,o=["desktop","mobile","tablet"];n<o.length;n++){var s=o[n];(i.value[s]["background-color"]||i.value[s]["background-image"]||i.value[s]["background-media"])&&!1,i.value[s]["background-color"]===r[s]["background-image"]&&i.value[s]["background-image"]===r[s]["background-color"]&&i.value[s]["background-media"]===r[s]["background-media"]||(t=!1)}return Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:t,onClick:function(e){e.preventDefault();var t=JSON.parse(JSON.stringify(r));if(void 0!==t&&""!==t)for(var n in t)void 0!==t[n]["background-color"]&&""!==t[n]["background-color"]||(t[n]["background-color"]="",wp.customize.previewer.refresh()),void 0!==t[n]["background-image"]&&""!==t[n]["background-image"]||(t[n]["background-image"]="",wp.customize.previewer.refresh()),void 0!==t[n]["background-media"]&&""!==t[n]["background-media"]||(t[n]["background-media"]="",wp.customize.previewer.refresh());c(t)}},Object(a.createElement)(j.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}})))))};Object(x.useEffect)((function(){for(var e=0,t=["desktop","mobile","tablet"];e<t.length;e++){l(t[e])}}),[]);var p,d,h=function(e){return Object(a.createElement)(a.Fragment,null,Object(a.createElement)(Oe,{color:void 0!==i.value[e]["background-color"]&&i.value[e]["background-color"]?i.value[e]["background-color"]:"",onChangeComplete:function(t,r){return f(t,e,r)},media:void 0!==i.value[e]["background-media"]&&i.value[e]["background-media"]?i.value[e]["background-media"]:"",backgroundImage:void 0!==i.value[e]["background-image"]&&i.value[e]["background-image"]?i.value[e]["background-image"]:"",backgroundAttachment:void 0!==i.value[e]["background-attachment"]&&i.value[e]["background-attachment"]?i.value[e]["background-attachment"]:"",backgroundPosition:void 0!==i.value[e]["background-position"]&&i.value[e]["background-position"]?i.value[e]["background-position"]:"",backgroundRepeat:void 0!==i.value[e]["background-repeat"]&&i.value[e]["background-repeat"]?i.value[e]["background-repeat"]:"",backgroundSize:void 0!==i.value[e]["background-size"]&&i.value[e]["background-size"]?i.value[e]["background-size"]:"",onSelectImage:function(t,r){return function(e,t,r){var n=Se({},i.value),a=Se({},n[t]);a["background-image"]=e.url,a["background-media"]=e.id,a["background-type"]=r,n[t]=a,c(n)}(t,e,r)},onChangeImageOptions:function(t,r,n){return function(e,t,r,n){var a=Se({},i.value),o=Se({},a[r]);o[e]=t,o["background-type"]=n,a[r]=o,c(a)}(t,r,e,n)},backgroundType:void 0!==i.value[e]["background-type"]&&i.value[e]["background-type"]?i.value[e]["background-type"]:"color",allowGradient:!0,allowImage:!0}))},f=function(e,t,r){var n="";e&&(n="string"==typeof e||e instanceof String?e:void 0!==e.rgb&&void 0!==e.rgb.a&&1!==e.rgb.a?"rgba("+e.rgb.r+","+e.rgb.g+","+e.rgb.b+","+e.rgb.a+")":e.hex);var a=Se({},i.value),o=Se({},a[t]);o["background-color"]=n,o["background-type"]=r,a[t]=o,c(a)},m=e.control.params,g=m.defaultValue,b=m.label,v=m.description,y="#RRGGBB",w=null,_=null;return g&&(y="#"!==g.substring(0,1)?"#"+g:g,defaultValueAttr=" data-default-color="+y),w=b&&""!==b&&void 0!==b?Object(a.createElement)("span",{className:"customize-control-title"},b):Object(a.createElement)("span",{className:"customize-control-title"},Object(E.__)("Background","astra")),v&&(_=Object(a.createElement)("span",{className:"description customize-control-description"},v)),p=Object(a.createElement)("ul",{className:"ast-responsive-btns"},Object(a.createElement)("li",{className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))),d=Object(a.createElement)("div",{className:"background-wrapper"},Object(a.createElement)("div",{className:"background-container desktop active"},u(),h("desktop")),Object(a.createElement)("div",{className:"background-container tablet"},u(),h("tablet")),Object(a.createElement)("div",{className:"background-container mobile"},u(),h("mobile"))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,w,_),Object(a.createElement)("div",{className:"customize-control-content"},p,d))};Ne.propTypes={control:i.a.object.isRequired};var ze=React.memo(Ne);function De(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function Ae(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?De(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):De(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var Me=function(e){var t=e.control.setting.get(),r=e.control.params.default,n=Object(x.useState)({value:t}),o=O()(n,2),i=o[0],s=o[1],c=function(t){s((function(e){return Ae(Ae({},e),{},{value:t})})),e.control.setting.set(t)},l=null,u=e.control.params.label;return u&&(l=Object(a.createElement)("span",{className:"customize-control-title"},u)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,l),Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex"},Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:JSON.stringify(i.value)===JSON.stringify(r),onClick:function(e){e.preventDefault();var t=JSON.parse(JSON.stringify(r));void 0!==t&&""!==t||(t="",wp.customize.previewer.refresh()),c(t)}},Object(a.createElement)(j.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}}))))),Object(a.createElement)(Oe,{color:void 0!==i.value&&i.value?i.value:"",onChangeComplete:function(e,t){return function(e){var t;t="string"==typeof e||e instanceof String?e:void 0!==e.rgb&&void 0!==e.rgb.a&&1!==e.rgb.a?"rgba("+e.rgb.r+","+e.rgb.g+","+e.rgb.b+","+e.rgb.a+")":e.hex,c(t)}(e)},backgroundType:"color",allowGradient:!1,allowImage:!1})))};Me.propTypes={control:i.a.object.isRequired};var Te=React.memo(Me);function Pe(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}var Le=function(e){var t=Object(x.useState)(e.control.setting.get()),r=O()(t,2),n=r[0],o=r[1],i=function(t,r){var a=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Pe(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Pe(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},n);a[r]=t,e.control.setting.set(a),o(a)},s=function(t){for(var r=0,i=["desktop","mobile","tablet"];r<i.length;r++){n[i[r]]&&!1}return Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:JSON.stringify(n)===JSON.stringify(e.control.params.default),onClick:function(t){t.preventDefault();var r=JSON.parse(JSON.stringify(e.control.params.default));if(void 0!==r&&""!==r)for(var n in r)void 0!==r[n]&&""!==r[n]||(r[n]="",wp.customize.previewer.refresh());e.control.setting.set(r),o(r)}},Object(a.createElement)(j.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}})))))},c=function(e){return Object(a.createElement)(Oe,{color:void 0!==n[e]&&n[e]?n[e]:"",onChangeComplete:function(t,r){return l(t,e)},backgroundType:"color",allowGradient:!1,allowImage:!1})},l=function(e,t){var r;r="string"==typeof e||e instanceof String?e:void 0!==e.rgb&&void 0!==e.rgb.a&&1!==e.rgb.a?"rgba("+e.rgb.r+","+e.rgb.g+","+e.rgb.b+","+e.rgb.a+")":e.hex,i(r,t)},u=e.control.params,p=u.defaultValue,d=u.label,h=u.description,f=u.responsive,m="#RRGGBB",g=null,b=null,v=null,w=null;return p&&(m="#"!==p.substring(0,1)?"#"+p:p,defaultValueAttr=" data-default-color="+m),d&&(g=Object(a.createElement)("span",{className:"customize-control-title"},d)),h&&(b=Object(a.createElement)("span",{className:"description customize-control-description"},h)),f&&(v=Object(a.createElement)("ul",{className:"ast-responsive-btns"},Object(a.createElement)("li",{className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))),w=Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex ast-responsive-color desktop active"},s(),c("desktop")),Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex ast-responsive-color tablet"},s(),c("tablet")),Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex ast-responsive-color mobile"},s(),c("mobile")))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,g,b),v,Object(a.createElement)("div",{className:"customize-control-content"},w))};Le.propTypes={control:i.a.object.isRequired};var qe=React.memo(Le),Re=function(e){var t=Object(x.useState)(e.control.setting.get()),r=O()(t,2),n=r[0],o=r[1],i=e.control.params,s=i.label,c=i.name,l=i.choices,u=null;s&&(u=Object(a.createElement)("span",{className:"customize-control-title"},s));var p=Object.entries(l).map((function(e){return Object(a.createElement)("option",{key:e[0],value:e[0]},e[1])}));return Object(a.createElement)(a.Fragment,null,u,Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)("select",{className:"ast-select-input","data-name":c,"data-value":n,value:n,onChange:function(){var t;t=event.target.value,o(t),e.control.setting.set(t)}},p)))};Re.propTypes={control:i.a.object.isRequired};var Be=React.memo(Re);function He(e,t){jQuery("html").addClass("responsive-background-img-ready");var r=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container").removeClass("active"),jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container."+r).addClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li."+r).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container").removeClass("active"),jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container."+e).addClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li."+e).addClass("active")})),e.container.find(".ast-responsive-btns button").on("click",(function(e){e.preventDefault();var t=jQuery(this).attr("data-device");t="desktop"==t?"tablet":"tablet"==t?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+t+'"]').trigger("click")})),t&&jQuery(document).mouseup((function(e){var r=jQuery(t),n=r.find(".background-wrapper");n.is(e.target)||0!==n.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}function Ie(e,t){jQuery("html").addClass("responsive-background-color-ready");var r=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha").removeClass("active"),jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha."+r).addClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li."+r).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha").removeClass("active"),jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-responsive-color."+e).addClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li."+e).addClass("active")})),e.container.find(".ast-responsive-btns button").on("click",(function(e){e.preventDefault();var t=jQuery(this).attr("data-device");t="desktop"==t?"tablet":"tablet"==t?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+t+'"]').trigger("click")})),t&&jQuery(document).mouseup((function(e){var r=jQuery(t),n=r.find(".customize-control-content");n.is(e.target)||0!==n.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}function Ve(e){var t=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive .input-wrapper input").removeClass("active"),jQuery(".customize-control-ast-responsive .input-wrapper input."+t).addClass("active"),jQuery(".customize-control-ast-responsive .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive .ast-responsive-btns li."+t).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive .input-wrapper input, .customize-control .ast-responsive-btns > li").removeClass("active"),jQuery(".customize-control-ast-responsive .input-wrapper input."+e+", .customize-control .ast-responsive-btns > li."+e).addClass("active")})),e.container.find(".ast-responsive-btns button").on("click",(function(e){e.preventDefault();var t=jQuery(this).attr("data-device");t="desktop"==t?"tablet":"tablet"==t?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+t+'"]').trigger("click")}))}function Ue(e){var t=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-slider .input-field-wrapper").removeClass("active"),jQuery(".customize-control-ast-responsive-slider .input-field-wrapper."+t).addClass("active"),jQuery(".customize-control-ast-responsive-slider .ast-responsive-slider-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-slider .ast-responsive-slider-btns li."+t).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-slider .input-field-wrapper, .customize-control .ast-responsive-slider-btns > li").removeClass("active"),jQuery(".customize-control-ast-responsive-slider .input-field-wrapper."+e+", .customize-control .ast-responsive-slider-btns > li."+e).addClass("active")})),e.container.find(".ast-responsive-slider-btns button").on("click",(function(e){e.preventDefault();var t=jQuery(this).attr("data-device");t="desktop"==t?"tablet":"tablet"==t?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+t+'"]').trigger("click")}))}function Fe(e){var t=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper").removeClass("active"),jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper."+t).addClass("active"),jQuery(".customize-control-ast-responsive-spacing .ast-spacing-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-spacing .ast-spacing-responsive-btns li."+t).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper, .customize-control .ast-spacing-responsive-btns > li").removeClass("active"),jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper."+e+", .customize-control .ast-spacing-responsive-btns > li."+e).addClass("active")})),e.container.find(".ast-spacing-responsive-btns button").on("click",(function(e){e.preventDefault();var t=jQuery(this).attr("data-device");t="desktop"==t?"tablet":"tablet"==t?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+t+'"]').trigger("click")}))}function Qe(e){console.log("heyyy from responsive-helper");var t=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-box-shadow .input-wrapper .ast-box-shadow-wrapper").removeClass("active"),jQuery(".customize-control-ast-responsive-box-shadow .input-wrapper .ast-box-shadow-wrapper."+t).addClass("active"),jQuery(".customize-control-ast-responsive-box-shadow .ast-box-shadow-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-box-shadow .ast-box-shadow-responsive-btns li."+t).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-box-shadow .input-wrapper .ast-box-shadow-wrapper, .customize-control .ast-box-shadow-responsive-btns > li").removeClass("active"),jQuery(".customize-control-ast-responsive-box-shadow .input-wrapper .ast-box-shadow-wrapper."+e+", .customize-control .ast-box-shadow-responsive-btns > li."+e).addClass("active")})),e.container.find(".ast-box-shadow-responsive-btns button").on("click",(function(e){e.preventDefault();var t=jQuery(this).attr("data-device");t="desktop"==t?"tablet":"tablet"==t?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+t+'"]').trigger("click")}))}var Ge=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(L,{control:this}),this.container[0])},ready:function(){this.setting._value;this.registerToggleEvents(),this.container.on("ast_settings_changed",this.onOptionChange);var e=0,t=jQuery(".wp-full-overlay-sidebar-content"),r=navigator.userAgent.toLowerCase();if(r.indexOf("firefox")>-1)n=16;else var n=6;jQuery("#customize-controls .wp-full-overlay-sidebar-content .control-section").on("scroll",(function(a){var o=jQuery(this);if(o.hasClass("open")){var i=o.find(".customize-section-title"),s=o.scrollTop();if(s>e)i.removeClass("maybe-sticky").removeClass("is-in-view").removeClass("is-sticky"),o.css("padding-top","");else{var c=o.outerWidth();i.addClass("maybe-sticky").addClass("is-in-view").addClass("is-sticky").width(c-n).css("top",t.css("top")),r.indexOf("firefox")>-1||o.css("padding-top",i.height()),0===s&&(i.removeClass("maybe-sticky").removeClass("is-in-view").removeClass("is-sticky"),o.css("padding-top",""))}e=s}}))},registerToggleEvents:function(){var e=this;jQuery(".wp-full-overlay-sidebar-content, .wp-picker-container").click((function(e){jQuery(e.target).closest(".ast-field-settings-modal").length||jQuery(".ast-adv-toggle-icon.open").trigger("click")})),e.container.on("click",".ast-toggle-desc-wrap .ast-adv-toggle-icon",(function(t){t.preventDefault(),t.stopPropagation();var r=jQuery(this),n=r.closest(".customize-control-ast-settings-group"),a=n.find(".ast-field-settings-modal").data("loaded"),o=n.parents(".control-section");if(r.hasClass("open"))n.find(".ast-field-settings-modal").hide();else{var i=o.find(".ast-adv-toggle-icon.open");if(i.length>0&&i.trigger("click"),a)n.find(".ast-field-settings-modal").show();else{var s=e.params.ast_fields,c=jQuery(astra.customizer.group_modal_tmpl);n.find(".ast-field-settings-wrap").append(c),n.find(".ast-fields-wrap").attr("data-control",e.params.name),e.ast_render_field(n,s,e),n.find(".ast-field-settings-modal").show();var l=jQuery("#customize-footer-actions .active").attr("data-device");"mobile"==l?(jQuery(".ast-responsive-btns .mobile, .ast-responsive-slider-btns .mobile").addClass("active"),jQuery(".ast-responsive-btns .preview-mobile, .ast-responsive-slider-btns .preview-mobile").addClass("active")):"tablet"==l?(jQuery(".ast-responsive-btns .tablet, .ast-responsive-slider-btns .tablet").addClass("active"),jQuery(".ast-responsive-btns .preview-tablet, .ast-responsive-slider-btns .preview-tablet").addClass("active")):(jQuery(".ast-responsive-btns .desktop, .ast-responsive-slider-btns .desktop").addClass("active"),jQuery(".ast-responsive-btns .preview-desktop, .ast-responsive-slider-btns .preview-desktop").addClass("active"))}}r.toggleClass("open")})),e.container.on("click",".ast-toggle-desc-wrap > .customizer-text",(function(e){e.preventDefault(),e.stopPropagation(),jQuery(this).find(".ast-adv-toggle-icon").trigger("click")}))},ast_render_field:function(e,t,r){var n=this,a=e.find(".ast-fields-wrap"),o="",i=[],s=n.isJsonString(r.params.value)?JSON.parse(r.params.value):{};if(void 0!==t.tabs){var c=(c=r.params.name.replace("[","-")).replace("]","");o+='<div id="'+c+'-tabs" class="ast-group-tabs">',o+='<ul class="ast-group-list">';var l=0;_.each(t.tabs,(function(e,t){var r="";0==l&&(r="active"),o+='<li class="'+r+'"><a href="#tab-'+t+'"><span>'+t+"</span></a></li>",l++})),o+="</ul>",o+='<div class="ast-tab-content" >',_.each(t.tabs,(function(e,t){o+='<div id="tab-'+t+'" class="tab">';var r=n.generateFieldHtml(e,s);o+=r.html,_.each(r.controls,(function(e,t){i.push({key:e.key,value:e.value,name:e.name})})),o+="</div>"})),o+="</div></div>",a.html(o),n.renderReactControl(t,n),jQuery("#"+c+"-tabs").tabs()}else{var u=n.generateFieldHtml(t,s);o+=u.html,_.each(u.controls,(function(e,t){i.push({key:e.key,value:e.value,name:e.name})})),a.html(o),n.renderReactControl(t,n)}_.each(i,(function(e,t){switch(e.key){case"ast-color":!function(e){jQuery(document).mouseup((function(t){var r=jQuery(e),n=r.find(".astra-color-picker-wrap");n.is(t.target)||0!==n.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}("#customize-control-"+e.name);break;case"ast-background":!function(e){jQuery(document).mouseup((function(t){var r=jQuery(e),n=r.find(".background-wrapper");n.is(t.target)||0!==n.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}("#customize-control-"+e.name);break;case"ast-responsive-background":He(n,"#customize-control-"+e.name);break;case"ast-responsive-color":Ie(n,"#customize-control-"+e.name);break;case"ast-responsive":Ve(n);break;case"ast-responsive-slider":Ue(n);break;case"ast-responsive-spacing":Fe(n);break;case"ast-responsive-box-shadow":Qe(n);break;case"ast-font":var r=astra.customizer.settings.google_fonts;n.container.find(".ast-font-family").html(r),n.container.find(".ast-font-family").each((function(){var e=jQuery(this).data("value");jQuery(this).val(e);var t=jQuery(this).data("name");jQuery("select[data-name='"+t+"'] option[value='inherit']").text(jQuery(this).data("inherit"));var r=jQuery(".ast-font-weight[data-connected-control='"+t+"']"),a=AstTypography._getWeightObject(AstTypography._cleanGoogleFonts(e));n.generateDropdownHtml(a,r),r.val(r.data("value"))})),n.container.find(".ast-font-family").selectWoo(),n.container.find(".ast-font-family").on("select2:select",(function(){var e=jQuery(this).val(),t=AstTypography._getWeightObject(AstTypography._cleanGoogleFonts(e)),r=jQuery(this).data("name"),a=jQuery(".ast-font-weight[data-connected-control='"+r+"']");n.generateDropdownHtml(t,a);var o=jQuery(this).parents(".customize-control").attr("id");o=o.replace("customize-control-",""),n.container.trigger("ast_settings_changed",[n,jQuery(this),e,o]);var i=a.parents(".customize-control").attr("id");i=i.replace("customize-control-",""),n.container.trigger("ast_settings_changed",[n,a,a.val(),i])})),n.container.find(".ast-font-weight").on("change",(function(){var e=jQuery(this).val();name=jQuery(this).parents(".customize-control").attr("id"),name=name.replace("customize-control-",""),n.container.trigger("ast_settings_changed",[n,jQuery(this),e,name])}))}})),e.find(".ast-field-settings-modal").data("loaded",!0)},getJS:function(e){},generateFieldHtml:function(e,t){var r="",n=[];_.each(e,(function(e,t){var a=wp.customize.control("astra-settings["+e.name+"]")?wp.customize.control("astra-settings["+e.name+"]").params.value:"",o=e.control,i="customize-control-"+o+"-content",s=wp.template(i),c=a||e.default;e.value=c;var l="",u="";if(e.label=e.title,_.each(e.data_attrs,(function(e,t){l+=" data-"+t+" ='"+e+"'"})),_.each(e.input_attrs,(function(e,t){u+=t+'="'+e+'" '})),e.dataAttrs=l,e.inputAttrs=u,n.push({key:o,value:c,name:e.name}),"ast-responsive"==o){var p=void 0===e.responsive||e.responsive;e.responsive=p}var d=e.name.replace("[","-");d=d.replace("]",""),r+="<li id='customize-control-"+d+"' class='customize-control customize-control-"+e.control+"' >",r+=s(e),r+="</li>"}));var a=new Object;return a.controls=n,a.html=r,a},generateDropdownHtml:function(e,t){var r=t.data("inherit"),n="",a=0,o=(e=jQuery.merge(["inherit"],e),t.val()||"400"),i="";for(astraTypo.inherit=r;a<e.length;a++)0===a&&-1===jQuery.inArray(o,e)?(o=e[0],i=' selected="selected"'):i=e[a]==o?' selected="selected"':"",e[a].includes("italic")||(n+='<option value="'+e[a]+'"'+i+">"+astraTypo[e[a]]+"</option>");t.html(n)},onOptionChange:function(e,t,r,n,a){jQuery(".hidden-field-astra-settings-"+a).val(n),wp.customize.control("astra-settings["+a+"]").setting.set(n)},isJsonString:function(e){try{JSON.parse(e)}catch(e){return!1}return!0},getFinalControlObject:function(e,t){return void 0!==e.choices&&void 0===t.params.choices&&(t.params.choices=e.choices),void 0!==e.inputAttrs&&void 0===t.params.inputAttrs&&(t.params.inputAttrs=e.inputAttrs),void 0!==e.link&&void 0===t.params.link&&(t.params.link=e.link),void 0!==e.units&&void 0===t.params.units&&(t.params.units=e.units),void 0!==e.linked_choices&&void 0===t.params.linked_choices&&(t.params.linked_choices=e.linked_choices),void 0===e.title||void 0!==t.params.label&&""!==t.params.label&&null!==t.params.label||(t.params.label=e.title),void 0===e.responsive||void 0!==t.params.responsive&&""!==t.params.responsive&&null!==t.params.responsive||(t.params.responsive=e.responsive),t},renderReactControl:function(e,t){var r={"ast-background":Ce,"ast-responsive-background":ze,"ast-responsive-color":qe,"ast-color":Te,"ast-border":I,"ast-responsive":Q,"ast-responsive-slider":X,"ast-slider":ae,"ast-responsive-spacing":Z,"ast-responsive-box-shadow":re,"ast-select":Be,"ast-divider":M};void 0!==e.tabs?_.each(e.tabs,(function(e,n){_.each(e,(function(e,n){if("ast-font"!==e.control){var o=e.name.replace("[","-"),i="#customize-control-"+(o=o.replace("]","")),s=wp.customize.control("astra-settings["+e.name+"]");s=t.getFinalControlObject(e,s);var c=r[e.control];ReactDOM.render(Object(a.createElement)(c,{control:s,customizer:wp.customize}),jQuery(i)[0])}}))})):_.each(e,(function(e,n){if("ast-font"!==e.control){var o=e.name.replace("[","-"),i="#customize-control-"+(o=o.replace("]","")),s=wp.customize.control("astra-settings["+e.name+"]");s=t.getFinalControlObject(e,s);var c=r[e.control];ReactDOM.render(Object(a.createElement)(c,{control:s,customizer:wp.customize}),jQuery(i)[0])}}))}}),Ye=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Te,{control:this,customizer:wp.customize}),this.container[0])},ready:function(){var e=this;jQuery(document).mouseup((function(t){var r=jQuery(e.container),n=r.find(".astra-color-picker-wrap"),a=r.find(".ast-color-btn-reset-wrap");n.is(t.target)||a.is(t.target)||0!==n.has(t.target).length||0!==a.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),Xe=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(qe,{control:this,customizer:wp.customize}),this.container[0])},ready:function(){Ie(this);var e=this;jQuery(document).mouseup((function(t){var r=jQuery(e.container),n=r.find(".customize-control-content"),a=r.find(".ast-color-btn-reset-wrap");n.is(t.target)||a.is(t.target)||0!==n.has(t.target).length||0!==a.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),Je=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(ze,{control:this}),this.container[0])},ready:function(){He(this,"");var e=this;jQuery(document).mouseup((function(t){var r=jQuery(e.container),n=r.find(".background-wrapper"),a=r.find(".ast-color-btn-reset-wrap");n.is(t.target)||a.is(t.target)||0!==n.has(t.target).length||0!==a.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),We=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Ce,{control:this}),this.container[0])},ready:function(){jQuery("html").addClass("background-colorpicker-ready");var e=this;jQuery(document).mouseup((function(t){var r=jQuery(e.container),n=r.find(".background-wrapper"),a=r.find(".ast-color-btn-reset-wrap");n.is(t.target)||a.is(t.target)||0!==n.has(t.target).length||0!==a.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),Ke=function(e){var t=null,r=null,n=e.control.params,o=n.label,i=n.description,s=n.value,c=n.choices,l=n.inputAttrs;o&&(t=Object(a.createElement)("span",{className:"customize-control-title"},o)),i&&(r=Object(a.createElement)("span",{className:"description customize-control-description"},i));var u=Object.values(s).map((function(e){var t="";return c[e]&&(t=Object(a.createElement)("li",R()({},l,{key:e,className:"ast-sortable-item","data-value":e}),Object(a.createElement)("i",{className:"dashicons dashicons-menu"}),Object(a.createElement)("i",{className:"dashicons dashicons-visibility visibility"}),c[e])),t})),p=Object.keys(c).map((function(e){var t="";return Array.isArray(s)&&-1===s.indexOf(e)&&(t=Object(a.createElement)("li",R()({},l,{key:e,className:"ast-sortable-item invisible","data-value":e}),Object(a.createElement)("i",{className:"dashicons dashicons-menu"}),Object(a.createElement)("i",{className:"dashicons dashicons-visibility visibility"}),c[e])),t}));return Object(a.createElement)("label",{className:"ast-sortable"},t,r,Object(a.createElement)("ul",{className:"sortable"},u,p))};Ke.propTypes={control:i.a.object.isRequired};var Ze=React.memo(Ke),$e=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Ze,{control:this}),this.container[0])},ready:function(){var e=this;e.sortableContainer=e.container.find("ul.sortable").first(),e.sortableContainer.sortable({stop:function(){e.updateValue()}}).disableSelection().find("li").each((function(){jQuery(this).find("i.visibility").click((function(){jQuery(this).toggleClass("dashicons-visibility-faint").parents("li:eq(0)").toggleClass("invisible")}))})).click((function(){e.updateValue()}))},updateValue:function(){this.params.choices;var e=[];this.sortableContainer.find("li").each((function(){jQuery(this).is(".invisible")||e.push(jQuery(this).data("value"))})),this.setting.set(e)}}),et=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(I,{control:this}),this.container[0])}}),tt=function(e){var t=e.control.params,r=t.linked,n=t.link_text,o=t.link_type,i=null;return r&&n&&(i=Object(a.createElement)("a",{href:"#",onClick:function(){!function(){var t=e.control.params,r=t.linked;switch(t.link_type){case"section":wp.customize.section(r).expand();break;case"control":wp.customize.control(r).focus()}}()},className:"customizer-link","data-customizer-linked":r,"data-ast-customizer-link-type":o,dangerouslySetInnerHTML:{__html:n}})),Object(a.createElement)(a.Fragment,null,i)};tt.propTypes={control:i.a.object.isRequired};var rt=React.memo(tt),nt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(rt,{control:this}),this.container[0])}}),at=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Q,{control:this}),this.container[0])},ready:function(){Ve(this)}}),ot=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(X,{control:this}),this.container[0])},ready:function(){Ue(this)}}),it=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(ae,{control:this}),this.container[0])}}),st=function(e){var t,r=Object(x.useState)(e.control.setting.get()),n=O()(r,2),o=n[0],i=n[1],s=e.control.params,c=s.label,l=s.description,u=s.id,p=s.choices,d=s.inputAttrs,h=s.choices_titles,f=s.link,m=s.labelStyle,g=null,b=null,v=[];(c&&(g=Object(a.createElement)("span",{className:"customize-control-title"},c)),l&&(b=Object(a.createElement)("span",{className:"description customize-control-description"},l)),d)&&d.split(" ").map((function(e,t){var r=e.split("=");void 0!==r[1]&&(v[r[0]]=r[1].replace(/"/g,""))}));f&&f.split(" ").map((function(e,t){var r=e.split("=");void 0!==r[1]&&(v[r[0]]=r[1].replace(/"/g,""))}));return t=Object.entries(p).map((function(t){var r=O()(t,2),n=r[0],s=(r[1],o===n);return Object(a.createElement)(a.Fragment,{key:n},Object(a.createElement)("input",R()({},v,{className:"image-select",type:"radio",value:n,name:"_customize-radio-".concat(u),id:u+n,checked:s,onChange:function(){return function(t){i(t),e.control.setting.set(t)}(n)}})),Object(a.createElement)("label",R()({htmlFor:u+n},m,{className:"ast-radio-img-svg"}),Object(a.createElement)("span",{dangerouslySetInnerHTML:{__html:p[n]}}),Object(a.createElement)("span",{className:"image-clickable",title:h[n]})))})),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",{className:"customizer-text"},g,b),Object(a.createElement)("div",{id:"input_".concat(u),className:"image"},t))};st.propTypes={control:i.a.object.isRequired};var ct=React.memo(st),lt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(ct,{control:this}),this.container[0])}}),ut=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Z,{control:this}),this.container[0])},ready:function(){Fe(this)}}),pt=wp.customize.astraControl.extend({renderContent:function(){console.log("Its control.js file");ReactDOM.render(Object(a.createElement)(re,{control:this}),this.container[0])},ready:function(){Qe(this)}}),dt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Be,{control:this}),this.container[0])}}),ht=function(e){var t=e.control.params,r=t.description,n=t.label,o=t.connect,i=t.variant,s=t.name,c=t.link,l=null,u=null,p=null,d=[],h=Object(E.__)("Inherit","astra"),f=e.control.setting.get();(n&&(l=Object(a.createElement)("span",{className:"customize-control-title"},n)),r&&(u=Object(a.createElement)("span",{className:"description customize-control-description"},r)),void 0!==c)&&c.split(" ").map((function(e,t){var r=e.split("=");void 0!==r[1]&&(d[r[0]]=r[1].replace(/"/g,""))}));return o&&i?p=Object(a.createElement)("select",R()({},d,{"data-connected-control":o,"data-connected-variant":i,"data-value":f,"data-name":s,"data-inherit":h})):o?p=Object(a.createElement)("select",R()({},d,{"data-connected-control":o,"data-value":f,"data-name":s,"data-inherit":h})):i&&(p=Object(a.createElement)("select",R()({},d,{"data-connected-variant":i,"data-value":f,"data-name":s,"data-inherit":h}))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,l,u),p)};ht.propTypes={control:i.a.object.isRequired};var ft=React.memo(ht),mt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(ft,{control:this}),this.container[0])},ready:function(){AstTypography.init()}}),gt=function(e){var t=e.control.params,r=t.description,n=t.label,o=t.connect,i=t.variant,s=t.name,c=t.link,l=t.ast_all_font_weight,u=e.control.setting.get(),p=Object(a.createElement)("span",{className:"customize-control-title"},n||Object(E.__)("Background","astra")),d=r?Object(a.createElement)("span",{className:"description customize-control-description"},r):null,h=null,f=[],m=Object(E.__)("Inherit","astra"),g=null;(u=void 0===u||""===u?[]:u,c)&&c.split(" ").map((function(e,t){var r=e.split("=");r[1]&&(f[r[0]]=r[1].replace(/"/g,""))}));var b=Object.entries(l).map((function(e){return Object(a.createElement)("option",{key:e[0],value:e[0]},e[1])}));return g="normal"===u?Object(a.createElement)("option",{key:"normal",value:"normal"},m):Object(a.createElement)("option",{key:"inherit",value:"inherit"},m),o&&i?h=Object(a.createElement)("select",R()({},f,{"data-connected-control":o,"data-connected-variant":i,"data-value":u,"data-name":s,"data-inherit":m}),g,b):i?h=Object(a.createElement)("select",R()({},f,{"data-connected-variant":i,"data-value":u,"data-name":s,"data-inherit":m}),g,b):o&&(h=Object(a.createElement)("select",R()({},f,{"data-connected-control":o,"data-value":u,"data-name":s,"data-inherit":m}),g,b)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,p,d),h)};gt.propTypes={control:i.a.object.isRequired};var bt=React.memo(gt),vt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(bt,{control:this}),this.container[0])},ready:function(){AstTypography.init()}});function yt(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function wt(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=ve()(e);if(t){var a=ve()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return ge()(this,r)}}var _t=function(e){fe()(r,e);var t=wt(r);function r(e){var n;ce()(this,r);var a=(n=t.apply(this,arguments)).props.control.setting.get();return n.state={value:a},n.onSelectChange=n.onSelectChange.bind(de()(n)),n.renderSelectHtml=n.renderSelectHtml.bind(de()(n)),n}return ue()(r,[{key:"onSelectChange",value:function(e,t){var r=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?yt(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):yt(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},this.state.value);r[t]=e.target.value,this.updateValues(r)}},{key:"renderSelectHtml",value:function(e){var t=this,r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=this.props.control.params.choices,o=Object.entries(n).map((function(e){return Object(a.createElement)("option",{key:e[0],value:e[0]},e[1])}));return Object(a.createElement)("div",{className:"ast-responsive-select-container ".concat(e," ").concat(r)},Object(a.createElement)("select",{className:"ast-select-input","data-value":this.state.value[e],value:this.state.value[e],onChange:function(r){t.onSelectChange(r,e)}},o))}},{key:"render",value:function(){var e=this.props.control.params.label,t=null;e&&(t=Object(a.createElement)("span",{className:"customize-control-title"},e));var r=Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-btns"},Object(a.createElement)("li",{key:"desktop",className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{key:"tablet",className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{key:"mobile",className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))),n=Object(a.createElement)(a.Fragment,null,this.renderSelectHtml("desktop","active"),this.renderSelectHtml("tablet"),this.renderSelectHtml("mobile"));return Object(a.createElement)(a.Fragment,null,t,r,Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)("div",{className:"ast-responsive-select-wrapper"},n)))}},{key:"updateValues",value:function(e){this.setState({value:e}),this.props.control.setting.set(e)}}]),r}(a.Component);_t.propTypes={control:i.a.object.isRequired};var Ot=_t,Et=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Ot,{control:this}),this.container[0])},ready:function(){var e=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container").removeClass("active"),jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container."+e).addClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li."+e).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container").removeClass("active"),jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container."+e).addClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li."+e).addClass("active")})),this.container.find(".ast-responsive-btns button").on("click",(function(e){var t=jQuery(this).attr("data-device");t="desktop"==t?"tablet":"tablet"==t?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+t+'"]').trigger("click")}))}}),jt=r(44),xt=r.n(jt),Ct=wp.i18n.__,kt=function(e){return"section-footer-builder"===e.control.params.section||"section-header-builder"===e.control.params.section?Object(a.createElement)(C.a.Fragment,null,Object(a.createElement)("p",{className:"ast-customize-control-title"},!astra.customizer.is_pro&&Object(a.createElement)(a.Fragment,null,Ct("Want more? Upgrade to ","astra"),Object(a.createElement)("a",{href:astra.customizer.upgrade_link,target:"_blank"},Ct("Astra Pro","astra")),Ct(" for many more header and footer options along with several amazing features too!","astra"))),Object(a.createElement)("p",{className:"ast-customize-control-description"},Object(a.createElement)("span",{className:"button button-secondary ahfb-builder-section-shortcut "+e.control.params.section,"data-section":e.control.params.section,onClick:function(){return function(e){e.customizer.section.each((function(e){if(e.expanded())return e.collapse(),!1}))}(e)}},Object(a.createElement)("span",{className:"dashicons dashicons-admin-generic"}," ")),Object(a.createElement)("span",{className:"button button-secondary ahfb-builder-hide-button ahfb-builder-tab-toggle"},Object(a.createElement)("span",{className:"ast-builder-hide-action"}," ",Object(a.createElement)("span",{className:"dashicons dashicons-arrow-down-alt2"})," ",Ct("Hide","astra")," "),Object(a.createElement)("span",{className:"ast-builder-show-action"}," ",Object(a.createElement)("span",{className:"dashicons dashicons-arrow-up-alt2"})," ",Ct("Show","astra")," ")))):Object(a.createElement)(C.a.Fragment,null,Object(a.createElement)("div",{className:"ahfb-compontent-tabs nav-tab-wrapper wp-clearfix"},Object(a.createElement)("a",{href:"#",className:"nav-tab ahfb-general-tab ahfb-compontent-tabs-button "+("general"===e.tab?"nav-tab-active":""),"data-tab":"general"},Object(a.createElement)("span",null,Ct("General","astra"))),Object(a.createElement)("a",{href:"#",className:"nav-tab ahfb-design-tab ahfb-compontent-tabs-button "+("design"===e.tab?"nav-tab-active":""),"data-tab":"design"},Object(a.createElement)("span",null,Ct("Design","astra")))))};C.a.memo(kt);var St=wp.customize.astraControl.extend({renderContent:function(){xt.a.render(Object(a.createElement)(kt,{control:this,tab:wp.customize.state("astra-customizer-tab").get(),customizer:wp.customize}),this.container[0])}}),Nt=r(15),zt=wp.components,Dt=zt.Dashicon,At=zt.Button,Mt=function(e){var t=AstraBuilderCustomizerData&&AstraBuilderCustomizerData.choices&&AstraBuilderCustomizerData.choices[e.controlParams.group]?AstraBuilderCustomizerData.choices[e.controlParams.group]:[];return Object(a.createElement)("div",{className:"ahfb-builder-item","data-id":e.item,"data-section":void 0!==t[e.item]&&void 0!==t[e.item].section?t[e.item].section:"",key:e.item,onClick:function(){e.focusItem(void 0!==t[e.item]&&void 0!==t[e.item].section?t[e.item].section:"")}},Object(a.createElement)("span",{className:"ahfb-builder-item-text"},void 0!==t[e.item]&&void 0!==t[e.item].name?t[e.item].name:""),Object(a.createElement)(At,{className:"ahfb-builder-item-icon",onClick:function(t){t.stopPropagation(),e.removeItem(e.item)}},Object(a.createElement)(Dt,{icon:"no-alt"})))},Tt=r(19),Pt=r.n(Tt);function Lt(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function qt(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Lt(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Lt(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var Rt=wp.components,Bt=Rt.ButtonGroup,Ht=Rt.Dashicon,It=Rt.Popover,Vt=Rt.Button,Ut=wp.element.Fragment,Ft=function(e){var t=Object(x.useState)({isVisible:!1}),r=O()(t,2),n=r[0],o=r[1],i=e.controlParams,s=e.location,c=e.choices,l=(e.row,e.column,e.id),u=function(t,r,n){var s=!0;return i.rows.map((function(r){Object.keys(e.settings[r]).map((function(n){e.settings[r][n].includes(t)&&(s=!1)}))})),Object(a.createElement)(Ut,{key:t},s&&Object(a.createElement)(Vt,{isTertiary:!0,className:"builder-add-btn",onClick:function(){!function(t,r,n){var a=e.setList,i=e.list;o((function(e){return qt(qt({},e),{},{isVisible:!1})}));var s=i;s.push({id:t}),a(s)}(t,e.row,e.column)}},Object(a.createElement)("span",{className:"add-btn-icon"}," ",Object(a.createElement)(Ht,{icon:void 0!==c[t]&&void 0!==c[t].icon?c[t].icon:""})," "),Object(a.createElement)("span",{className:"add-btn-title"},void 0!==c[t]&&void 0!==c[t].name?c[t].name:"")))},p=0,d=Object.keys(c).length;return n.isVisible&&i.rows.map((function(t){Object.keys(e.settings[t]).map((function(r){p+=e.settings[t][r].length}))})),Object(a.createElement)("div",{className:Pt()("ahfb-builder-add-item","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"right"!==s?null:"center-on-left","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"left"!==s?null:"center-on-right","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"left_center"!==s?null:"right-center-on-right","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"right_center"!==s?null:"left-center-on-left"),key:l},n.isVisible&&Object(a.createElement)(It,{position:"top",className:"ahfb-popover-add-builder",onClose:function(){!0===n.isVisible&&o((function(e){return qt(qt({},e),{},{isVisible:!1})}))}},Object(a.createElement)("div",{className:"ahfb-popover-builder-list"},Object(a.createElement)(Bt,{className:"ahfb-radio-container-control"},Object.keys(c).sort().map((function(e){return u(e)})),d===p&&Object(a.createElement)("p",{className:"ahfb-all-coponents-used"}," ",Object(E.__)("Hurray! All Components Are Being Used.","astra")," ")))),Object(a.createElement)(Vt,{className:"ahfb-builder-item-add-icon dashicon dashicons-plus-alt2",onClick:function(){o((function(e){return qt(qt({},e),{},{isVisible:!0})}))}}))},Qt=wp.element.Fragment,Gt=function(e){var t=e.zone.replace(e.row+"_",""),r=void 0!==e.items&&null!=e.items&&null!=e.items.length&&e.items.length>0?e.items:[],n=e.choices,o=[];r.length>0&&r.map((function(e,t){Object.keys(n).includes(e)?o.push({id:e}):r.splice(t,1)}));var i=void 0!==e.centerItems&&null!=e.centerItems&&null!=e.centerItems.length&&e.centerItems.length>0?e.centerItems:[],s=[];i.length>0&&i.map((function(e,t){Object.keys(n).includes(e)?s.push({id:e}):i.splice(t,1)}));var c=function(r,n,o){var i=o.replace("_","-");return Object(a.createElement)(Qt,null,Object(a.createElement)(Nt.ReactSortable,{animation:100,onStart:function(){return e.showDrop()},onEnd:function(){return e.hideDrop()},group:e.controlParams.group,className:"ahfb-builder-drop ahfb-builder-sortable-panel ahfb-builder-drop-"+t+o,list:r,setList:function(t){return e.onUpdate(e.row,e.zone+o,t)}},n.length>0&&n.map((function(t,r){return Object(a.createElement)(Mt,{removeItem:function(t){return e.removeItem(t,e.row,e.zone+o)},focusItem:function(t){return e.focusItem(t)},key:t,index:r,item:t,controlParams:e.controlParams})}))),Object(a.createElement)(Ft,{row:e.row,list:r,settings:e.settings,column:e.zone+o,setList:function(t){return e.onAddItem(e.row,e.zone+o,t)},key:t,location:t+o,id:"add"+i+"-"+t,controlParams:e.controlParams,choices:e.choices}))};return"footer"===e.mode?Object(a.createElement)("div",{className:"ahfb-builder-area ahfb-builder-area-".concat(t),"data-location":e.zone},c(o,r,"")):Object(a.createElement)("div",{className:"ahfb-builder-area ahfb-builder-area-".concat(t),"data-location":e.zone},"astra-settings[header-desktop-items]"===e.controlParams.group&&"right"===t&&c(s,i,"_center"),c(o,r,""),"astra-settings[header-desktop-items]"===e.controlParams.group&&"left"===t&&c(s,i,"_center"))},Yt=wp.i18n.__,Xt=wp.components,Jt=Xt.Dashicon,Wt=Xt.Button,Kt=function(e){var t="no-center-items",r=-1!==e.controlParams.group.indexOf("header")?"header":"footer",n=[],o="",i=0,s=!0;if("footer"===r&&(o="ast-grid-row-layout-".concat(e.layout[e.row].layout.desktop),i=e.layout[e.row].column-1,Object.keys(e.controlParams.zones[e.row]).map((function(t,r){i<r&&(e.items[t]=[])}))),"astra-settings[header-desktop-items]"===e.controlParams.group&&void 0!==e.items[e.row+"_center"]&&null!=e.items[e.row+"_center"]&&null!=e.items[e.row+"_center"].length&&e.items[e.row+"_center"].length>0&&(t="has-center-items"),"popup"===e.row&&(t="popup-vertical-group"),e.controlParams.hasOwnProperty("status"))switch(e.row){case"above":e.controlParams.status.above||(s=!1,"ahfb-grid-disabled");break;case"primary":e.controlParams.status.primary||(s=!1,"ahfb-grid-disabled");break;case"below":e.controlParams.status.below||(s=!1,"ahfb-grid-disabled")}return Object(a.createElement)("div",{className:"ahfb-builder-areas ahfb-builder-mode-".concat(r," ").concat(t),"data-row":e.row,"data-row-section":"section-"+e.row+"-"+r+"-builder"},Object(a.createElement)(Wt,{className:"ahfb-row-actions",title:"popup"===e.row?Yt("Off Canvas","astra"):(e.row+" "+r).charAt(0).toUpperCase()+(e.row+" "+r).slice(1).toLowerCase(),onClick:function(){return e.focusPanel(e.row+"-"+r)}},Object(a.createElement)(Jt,{icon:"admin-generic"}),"popup"===e.row&&Object(a.createElement)(a.Fragment,null,Yt("Off Canvas","astra"))),Object(a.createElement)("div",{className:"ahfb-builder-group ahfb-builder-group-horizontal ".concat(o),"data-setting":e.row},Object.keys(e.controlParams.zones[e.row]).map((function(t,o){if(!("footer"===r&&i<o)&&(e.row+"_left_center"!==t&&e.row+"_right_center"!==t||"footer"===r))return"astra-settings[header-desktop-items]"===e.controlParams.group&&e.row+"_left"===t&&"footer"!==r&&(n=e.items[e.row+"_left_center"]),"astra-settings[header-desktop-items]"===e.controlParams.group&&e.row+"_right"===t&&"footer"!==r&&(n=e.items[e.row+"_right_center"]),s&&Object(a.createElement)(Gt,{removeItem:function(t,r,n){return e.removeItem(t,r,n)},focusItem:function(t){return e.focusItem(t)},hideDrop:function(){return e.hideDrop()},showDrop:function(){return e.showDrop()},onUpdate:function(t,r,n){return e.onUpdate(t,r,n)},zone:t,row:e.row,choices:e.choices,key:t,items:e.items[t],centerItems:n,controlParams:e.controlParams,onAddItem:function(t,r,n){return e.onAddItem(t,r,n)},settings:e.settings,mode:r})}))))};function Zt(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function $t(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Zt(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Zt(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var er=function(e){var t=e.control.setting.get(),r={},n=e.control.params.default?$t($t({},r),e.control.params.default):r;t=t?$t($t({},n),t):n;var o={},i=e.control.params.input_attrs?$t($t({},o),e.control.params.input_attrs):o,s=AstraBuilderCustomizerData&&AstraBuilderCustomizerData.choices&&AstraBuilderCustomizerData.choices[i.group]?AstraBuilderCustomizerData.choices[i.group]:[],c=Object(x.useState)({value:t,layout:i.layouts}),l=O()(c,2),u=l[0],p=l[1],d=function(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=e.control.setting;if("popup"===r){var a=e.customizer("astra-settings[header-mobile-popup-items]");a.set(!a.get())}n.set($t($t($t({},n.get()),t),{},{flag:!n.get().flag}))};Object(x.useEffect)((function(){document.addEventListener("AstraBuilderPresetSettingsUpdate",(function(e){i.group===e.detail.id&&(p((function(t){return $t($t({},t),{},{value:e.detail.grid_layout})})),d(e.detail.grid_layout))})),document.addEventListener("AstraBuilderChangeRowLayout",(function(e){if("astra-settings[footer-desktop-items]"===i.group&&""!==e.detail.type){var t=i;t.layouts[e.detail.type]&&(t.layouts[e.detail.type]={column:e.detail.columns,layout:e.detail.layout},p((function(e){return $t($t({},e),{},{layout:t.layouts})})),d(t))}}))}),[]);var h=function(){for(var e=document.querySelectorAll(".ahfb-builder-area"),t=0;t<e.length;++t)e[t].classList.add("ahfb-dragging-dropzones")},f=function(){for(var e=document.querySelectorAll(".ahfb-builder-area"),t=0;t<e.length;++t)e[t].classList.remove("ahfb-dragging-dropzones")},m=function(e,t,r){var n=u.value,a=n[t],o=[];a[r].length>0&&a[r].map((function(t){e!==t&&o.push(t)})),"astra-settings[header-desktop-items]"===i.group&&t+"_center"===r&&0===o.length&&(a[t+"_left_center"].length>0&&(a[t+"_left_center"].map((function(e){n[t][t+"_left"].push(e)})),n[t][t+"_left_center"]=[]),a[t+"_right_center"].length>0&&(a[t+"_right_center"].map((function(e){n[t][t+"_right"].push(e)})),n[t][t+"_right_center"]=[])),a[r]=o,n[t][r]=o,p((function(e){return $t($t({},e),{},{value:n})})),d(n,t);var s=new CustomEvent("AstraBuilderRemovedBuilderItem",{detail:i.group});document.dispatchEvent(s)},g=function(e,t,r){var n=u.value,a=n[e],o=[];r.length>0&&r.map((function(e){o.push(e.id)})),v(a[t],o)||("astra-settings[header-desktop-items]"===i.group&&e+"_center"===t&&0===o.length&&(a[e+"_left_center"].length>0&&(a[e+"_left_center"].map((function(t){n[e][e+"_left"].push(t)})),n[e][e+"_left_center"]=[]),a[e+"_right_center"].length>0&&(a[e+"_right_center"].map((function(t){n[e][e+"_right"].push(t)})),n[e][e+"_right_center"]=[])),a[t]=o,n[e][t]=o,p((function(e){return $t($t({},e),{},{value:n})})),d(n,e))},b=function(e,t,r){g(e,t,r);var n=new CustomEvent("AstraBuilderRemovedBuilderItem",{detail:i.group});document.dispatchEvent(n)},v=function(e,t){if(e===t)return!0;if(null==e||null==t)return!1;if(e.length!=t.length)return!1;for(var r=0;r<e.length;++r)if(e[r]!==t[r])return!1;return!0},y=function(t){t="section-"+t+"-builder",void 0!==e.customizer.section(t)&&e.customizer.section(t).focus()},w=function(t){void 0!==e.customizer.section(t)&&e.customizer.section(t).focus()};return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-builder-items"},i.rows.includes("popup")&&Object(a.createElement)(Kt,{showDrop:function(){return h()},focusPanel:function(e){return y(e)},focusItem:function(e){return w(e)},removeItem:function(e,t,r){return m(e,t,r)},onAddItem:function(e,t,r){return b(e,t,r)},hideDrop:function(){return f()},onUpdate:function(e,t,r){return g(e,t,r)},key:"popup",row:"popup",controlParams:i,choices:s,items:u.value.popup,settings:u.value,layout:u.layout}),Object(a.createElement)("div",{className:"ahfb-builder-row-items"},i.rows.map((function(t){if("popup"!==t)return Object(a.createElement)(Kt,{showDrop:function(){return h()},focusPanel:function(e){return y(e)},focusItem:function(e){return w(e)},removeItem:function(e,t,r){return m(e,t,r)},hideDrop:function(){return f()},onUpdate:function(e,t,r){return g(e,t,r)},onAddItem:function(e,t,r){return b(e,t,r)},key:t,row:t,controlParams:i,choices:s,customizer:e.customizer,items:u.value[t],settings:u.value,layout:u.layout})}))))};er.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var tr=React.memo(er),rr=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(tr,{control:this,customizer:wp.customize}),this.container[0])}});function nr(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function ar(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?nr(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):nr(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var or=wp.i18n.__,ir=wp.components,sr=ir.Dashicon,cr=ir.Tooltip,lr=ir.TextControl,ur=ir.Button,pr=function(e){var t=window.svgIcons,r=Object(x.useState)({open:!1}),n=O()(r,2),o=n[0],i=n[1];return Object(a.createElement)("div",{className:"ahfb-sorter-item","data-id":e.item.id,key:e.item.id},Object(a.createElement)("div",{className:"ahfb-sorter-item-panel-header",onClick:function(){i((function(e){return ar(ar({},e),{},{open:!o.open})}))}},Object(a.createElement)(cr,{text:or("Toggle Item Visiblity","astra")},Object(a.createElement)(ur,{className:"ahfb-sorter-visiblity"},Object(a.createElement)("span",{dangerouslySetInnerHTML:{__html:t[e.item.id]}}))),Object(a.createElement)("span",{className:"ahfb-sorter-title"},void 0!==e.item.label&&""!==e.item.label?e.item.label:or("Social Item","astra")),Object(a.createElement)(ur,{className:"ahfb-sorter-item-expand ".concat(e.item.enabled?"item-is-visible":"item-is-hidden"),onClick:function(t){t.stopPropagation(),e.toggleEnabled(!e.item.enabled,e.index)}},Object(a.createElement)(sr,{icon:"visibility"})),Object(a.createElement)(ur,{className:"ahfb-sorter-item-remove",isDestructive:!0,onClick:function(){e.removeItem(e.index)}},Object(a.createElement)(sr,{icon:"no-alt"}))),o.open&&Object(a.createElement)("div",{className:"ahfb-sorter-item-panel-content"},Object(a.createElement)(lr,{label:or("Label","astra"),value:e.item.label?e.item.label:"",onChange:function(t){e.onChangeLabel(t,e.index)}}),Object(a.createElement)(lr,{label:or("URL","astra"),value:e.item.url?e.item.url:"",onChange:function(t){e.onChangeURL(t,e.index)}})))};function dr(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function hr(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?dr(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):dr(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var fr=wp.i18n.__,mr=wp.components,gr=mr.Button,br=mr.SelectControl,vr=function(e){var t=e.control.setting.get(),r={items:[{id:"facebook",enabled:!0,url:"",color:"#557dbc",background:"transparent",icon:"facebook",label:"Facebook"},{id:"twitter",enabled:!0,url:"",color:"#7acdee",background:"transparent",icon:"twitter",label:"Twitter"}]},n=e.control.params.default?hr(hr({},r),e.control.params.default):r;t=t?hr(hr({},n),t):n;var o={group:"social_item_group",options:[{value:"facebook",label:fr("Facebook","astra"),color:"#557dbc",background:"transparent"},{value:"twitter",label:fr("Twitter","astra"),color:"#7acdee",background:"transparent"},{value:"instagram",label:fr("Instagram","astra"),color:"#292929",background:"transparent"},{value:"youtube",label:fr("YouTube","astra"),color:"#e96651",background:"transparent"},{value:"facebook_group",label:fr("Facebook Group","astra"),color:"#3D87FB",background:"transparent"},{value:"vimeo",label:fr("Vimeo","astra"),color:"#8ecfde",background:"transparent"},{value:"pinterest",label:fr("Pinterest","astra"),color:"#ea575a",background:"transparent"},{value:"linkedin",label:fr("Linkedin","astra"),color:"#1c86c6",background:"transparent"},{value:"medium",label:fr("Medium","astra"),color:"#292929",background:"transparent"},{value:"wordpress",label:fr("WordPress","astra"),color:"#464646",background:"transparent"},{value:"reddit",label:fr("Reddit","astra"),color:"#FC471E",background:"transparent"},{value:"patreon",label:fr("Patreon","astra"),color:"#e65c4b",background:"transparent"},{value:"github",label:fr("GitHub","astra"),color:"#24292e",background:"transparent"},{value:"dribbble",label:fr("Dribbble","astra"),color:"#d77ea6",background:"transparent"},{value:"behance",label:fr("Behance","astra"),color:"#1B64F6",background:"transparent"},{value:"vk",label:fr("VK","astra"),color:"#5382b6",background:"transparent"},{value:"xing",label:fr("Xing","astra"),color:"#0A5C5D",background:"transparent"},{value:"rss",label:fr("RSS","astra"),color:"#f09124",background:"transparent"},{value:"email",label:fr("Email","astra"),color:"#ea4335",background:"transparent"},{value:"phone",label:fr("Phone","astra"),color:"inherit",background:"transparent"},{value:"whatsapp",label:fr("WhatsApp","astra"),color:"#5BBA67",background:"transparent"},{value:"google_reviews",label:fr("Google Reviews","astra"),color:"#dc4e41",background:"transparent"},{value:"telegram",label:fr("Telegram","astra"),color:"#229CCE",background:"transparent"},{value:"yelp",label:fr("Yelp","astra"),color:"#af0606",background:"transparent"},{value:"trip_advisor",label:fr("Trip Advisor","astra"),color:"#00aa6c",background:"transparent"},{value:"imdb",label:fr("IMDB","astra"),color:"#000000",background:"transparent"}].sort((function(e,t){return e.value<t.value?-1:e.value>t.value?1:0}))},i=e.control.params.input_attrs?hr(hr({},o),e.control.params.input_attrs):o,s=[];i.options.map((function(e){t.items.some((function(t){return t.id===e.value}))||s.push(e)}));var c=Object(x.useState)({value:t,isVisible:!1,control:void 0!==s[0]&&void 0!==s[0].value?s[0].value:""}),l=O()(c,2),u=l[0],p=l[1],d=function(t){e.control.setting.set(hr(hr(hr({},e.control.setting.get()),t),{},{flag:!e.control.setting.get().flag}))},h=function(){var e,t=document.querySelectorAll(".ahfb-builder-area");for(e=0;e<t.length;++e)t[e].classList.remove("ahfb-dragging-dropzones")},f=function(e,t){var r=u.value,n=r.items.map((function(r,n){return t===n&&(r=hr(hr({},r),e)),r}));r.items=n,p((function(e){return hr(hr({},e),{},{value:r})})),d(r)},m=function(e,t){if(e===t)return!0;if(null==e||null==t)return!1;if(e.length!=t.length)return!1;for(var r=0;r<e.length;++r)if(e[r]!==t[r])return!1;return!0},g=void 0!==u.value&&null!=u.value.items&&null!=u.value.items.length&&u.value.items.length>0?u.value.items:[],b=[];g.length>0&&g.map((function(e){b.push({id:e.id})})),i.options.map((function(e){b.some((function(t){return t.id===e.value}))||s.push(e)}));return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-sorter-items"},Object(a.createElement)("div",{className:"ahfb-sorter-row"},Object(a.createElement)(Nt.ReactSortable,{animation:100,onStart:function(){return h()},onEnd:function(){return h()},group:i.group,className:"ahfb-sorter-drop ahfb-sorter-sortable-panel ahfb-sorter-drop-".concat(i.group),handle:".ahfb-sorter-item-panel-header",list:b,setList:function(e){return t=e,r=u.value,n=r.items,a=[],t.length>0&&t.map((function(e){n.filter((function(t){t.id===e.id&&a.push(t)}))})),void(m(n,a)||(n.items=a,r.items=a,p((function(e){return hr(hr({},e),{},{value:r})})),d(r)));var t,r,n,a}},g.length>0&&g.map((function(e,t){return Object(a.createElement)(pr,{removeItem:function(e){return t=e,r=u.value,n=r.items,a=[],n.length>0&&n.map((function(e,r){t!==r&&a.push(e)})),r.items=a,p((function(e){return hr(hr({},e),{},{value:r})})),void d(r);var t,r,n,a},toggleEnabled:function(e,t){return function(e,t){f({enabled:e},t)}(e,t)},onChangeLabel:function(e,t){return function(e,t){f({label:e},t)}(e,t)},onChangeURL:function(e,t){return function(e,t){f({url:e},t)}(e,t)},key:e.id,index:t,item:e,controlParams:i})})))),void 0!==s[0]&&void 0!==s[0].value&&Object(a.createElement)("div",{className:"ahfb-social-add-area"},Object(a.createElement)(br,{value:u.control,options:s,onChange:function(e){p((function(t){return hr(hr({},t),{},{control:e})}))}}),Object(a.createElement)(gr,{className:"ahfb-sorter-add-item",isPrimary:!0,onClick:function(){!function(){var e=u.control;if(p((function(e){return hr(hr({},e),{},{isVisible:!1})})),e){var t=u.value,r=t.items,n=i.options.filter((function(t){return t.value===e})),a={id:e,enabled:!0,url:"",color:n[0].color,background:n[0].background,icon:e,label:n[0].label};r.push(a),t.items=r;var o=[];i.options.map((function(e){r.some((function(t){return t.id===e.value}))||o.push(e)})),p((function(e){return hr(hr({},e),{},{control:void 0!==o[0]&&void 0!==o[0].value?o[0].value:""})})),p((function(e){return hr(hr({},e),{},{value:t})})),d(t)}}()}},fr("Add Social Icon","astra"))))};vr.propTypes={control:i.a.object.isRequired};var yr=React.memo(vr),wr=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(yr,{control:this}),this.container[0])}}),_r=r(45),Or=r.n(_r);function Er(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function jr(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Er(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Er(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var xr=function(e){var t=e.control.setting.get(),r=Object(x.useState)({value:t,editor:{},restoreTextMode:!1}),n=O()(r,2),o=n[0],i=n[1],s={id:"header_html",toolbar1:"formatselect | styleselect | bold italic strikethrough | forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | insert ast_placeholders | fontsizeselect",toolbar2:""},c=e.control.params.input_attrs?jr(jr({},s),e.control.params.input_attrs):s,l=function(t){i((function(e){return jr(jr({},e),{},{value:t})})),e.control.setting.set(t)};Object(x.useEffect)((function(){window.tinymce.get(c.id)&&(i((function(e){return jr(jr({},e),{},{restoreTextMode:window.tinymce.get(c.id).isHidden()})})),window.wp.oldEditor.remove(c.id)),window.wp.oldEditor.initialize(c.id,{tinymce:{wpautop:!0,height:200,menubar:!1,toolbar1:c.toolbar1,toolbar2:c.toolbar2,fontsize_formats:"8pt 9pt 10pt 11pt 12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt"},quicktags:!0,mediaButtons:!0});var e=window.tinymce.get(c.id);e.initialized?u():e.on("init",u),e.addButton("ast_placeholders",{type:"menubutton",text:"Tags",icon:!1,menu:[{text:"Copyright",icon:!1,value:"[copyright]",onclick:function(){e.insertContent(this.value())}},{text:"Current Year",icon:!1,value:"[current_year]",onclick:function(){e.insertContent(this.value())}},{text:"Site Title",icon:!1,value:"[site_title]",onclick:function(){e.insertContent(this.value())}},{text:"Theme Author",icon:!1,value:"[theme_author]",onclick:function(){e.insertContent(this.value())}}]})}),[]);var u=function(){var e=window.tinymce.get(c.id);o.restoreTextMode&&window.switchEditors.go(c.id,"html"),e.on("NodeChange",Or()(p,250)),i((function(t){return jr(jr({},t),{},{editor:e})}))},p=function(){l(window.wp.oldEditor.getContent(c.id))};return Object(a.createElement)("div",{className:"ahfb-control-field ast-html-editor"},e.control.params.label&&Object(a.createElement)("span",{className:"customize-control-title"},e.control.params.label),Object(a.createElement)("textarea",{className:"ahfb-control-tinymce-editor wp-editor-area",id:c.id,value:o.value,onChange:function(e){var t=e.target.value;l(t)}}),e.control.params.description&&Object(a.createElement)("span",{className:"customize-control-description"},e.control.params.description))};xr.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var Cr=React.memo(xr),kr=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Cr,{control:this,customizer:wp.customize}),this.container[0])}});function Sr(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function Nr(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Sr(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Sr(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var zr=wp.components,Dr=zr.Dashicon,Ar=zr.Button,Mr=wp.element.Fragment,Tr=function(e){var t=window.svgIcons,r={},n={},o=e.control.params.input_attrs?Nr(Nr({},n),e.control.params.input_attrs):n;e.customizer.control(o.group)&&(r=e.customizer.control(o.group).setting.get());var i=AstraBuilderCustomizerData&&AstraBuilderCustomizerData.choices&&AstraBuilderCustomizerData.choices[o.group]?AstraBuilderCustomizerData.choices[o.group]:[],s=Object(x.useState)({settings:r}),c=O()(s,2),l=c[0],u=c[1];document.addEventListener("AstraBuilderRemovedBuilderItem",(function(e){e.detail===o.group&&p()}));var p=function(){if(e.customizer.control(o.group)){var t=e.customizer.control(o.group).setting.get();u((function(e){return Nr(Nr({},e),{},{settings:t})}))}},d=function(r,n){var s=!0;o.zones.map((function(e){Object.keys(l.settings[e]).map((function(t){l.settings[e][t].includes(r)&&(s=!1)}))}));var c=[{id:r}];return Object(a.createElement)(Mr,{key:r},s&&"available"===n&&Object(a.createElement)(Nt.ReactSortable,{animation:10,onStart:function(){return function(){var e,t=document.querySelectorAll(".ahfb-builder-area");for(e=0;e<t.length;++e)t[e].classList.add("ahfb-dragging-dropzones")}()},onEnd:function(){return function(){var e,t=document.querySelectorAll(".ahfb-builder-area");for(e=0;e<t.length;++e)t[e].classList.remove("ahfb-dragging-dropzones")}()},group:{name:o.group,put:!1},className:"ahfb-builder-item-start ahfb-move-item",list:c,setList:function(e){var t;null!=(t=e).length&&0===t.length&&p()}},Object(a.createElement)("div",{className:"ahfb-builder-item","data-id":r,"data-section":i[r]&&i[r].section?i[r].section:"",key:r},Object(a.createElement)("span",{className:"ahfb-builder-item-icon ahfb-move-icon"},"dangerouslySetInnerHTML=",{__html:t.drag}),i[r]&&i[r].name?i[r].name:"")),!s&&"links"===n&&Object(a.createElement)("div",{className:"ahfb-builder-item-start"},Object(a.createElement)(Ar,{className:"ahfb-builder-item","data-id":r,onClick:function(){return function(t){e.customizer.section(i[t].section)&&e.customizer.section(i[t].section).focus()}(r)},"data-section":i[r]&&i[r].section?i[r].section:"",key:r},i[r]&&i[r].name?i[r].name:"",Object(a.createElement)("span",{className:"ahfb-builder-item-icon"},Object(a.createElement)(Dr,{icon:"arrow-right-alt2"})))))};return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-available-items"},Object(a.createElement)("div",{className:"ahfb-available-items-pool-"},Object.keys(i).map((function(e){return d(e,"links")}))))};Tr.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var Pr=React.memo(Tr),Lr=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Pr,{control:this,customizer:wp.customize}),this.container[0])}});function qr(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function Rr(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?qr(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):qr(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var Br=wp.components,Hr=Br.Dashicon,Ir=Br.Button,Vr=function(e){var t={section:""},r=e.control.params.input_attrs?Rr(Rr({},t),e.control.params.input_attrs):t;return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-available-items"},Object(a.createElement)("div",{className:"ahfb-builder-item-start"},Object(a.createElement)(Ir,{className:"ahfb-builder-item",onClick:function(){return t=r.section,void(void 0!==e.customizer.section(t)&&e.customizer.section(t).focus());var t},"data-section":r.section},r.label?r.label:"",Object(a.createElement)("span",{className:"ahfb-builder-item-icon"},Object(a.createElement)(Hr,{icon:"arrow-right-alt2"})))))};Vr.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var Ur=React.memo(Vr),Fr=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Ur,{control:this,customizer:wp.customize}),this.container[0])}});function Qr(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function Gr(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Qr(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Qr(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}wp.i18n.__;var Yr=wp.element.Fragment,Xr=function(e){var t=Object(x.useState)({view:e.device}),r=O()(t,2),n=r[0],o=r[1],i=n.view,s=function(){document.addEventListener("AstraChangedRepsonsivePreview",(function(t){!function(t){var r="";switch(t){case"desktop":r="tablet";break;case"tablet":r="mobile";break;case"mobile":r="desktop"}o((function(e){return Gr(Gr({},e),{},{view:r})})),wp.customize.previewedDevice(r),e.onChange(r)}(t.detail)}))};return Object(x.useEffect)((function(){s()}),[]),Object(a.createElement)(Yr,null,Object(a.createElement)("div",{className:"ahfb-responsive-control-bar"},e.controlLabel&&Object(a.createElement)("span",{className:"customize-control-title"},e.controlLabel),!e.hideResponsive&&Object(a.createElement)("div",{className:"floating-controls"},Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-btns"},Object.keys({desktop:{icon:"desktop"},tablet:{icon:"tablet"},mobile:{icon:"smartphone"}}).map((function(e){return Object(a.createElement)("li",y()({key:e,className:e},"className",(e===i?"active ":"")+"preview-".concat(e)),Object(a.createElement)("button",{type:"button","data-device":e,className:(e===i?"active ":"")+"preview-".concat(e),onClick:function(){var t=new CustomEvent("AstraChangedRepsonsivePreview",{detail:e});document.dispatchEvent(t)}},Object(a.createElement)("i",{className:"dashicons dashicons-".concat("mobile"===e?"smartphone":e)})))}))))),Object(a.createElement)("div",{className:"ahfb-responsive-controls-content"},e.children))};Xr.propTypes={onChange:i.a.func,controlLabel:i.a.object};var Jr=React.memo(Xr);function Wr(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function Kr(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Wr(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Wr(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function Zr(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=ve()(e);if(t){var a=ve()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return ge()(this,r)}}wp.i18n.__;var $r=wp.components,en=$r.ButtonGroup,tn=$r.Dashicon,rn=$r.Button,nn=function(e){fe()(r,e);var t=Zr(r);function r(){var e;ce()(this,r),(e=t.apply(this,arguments)).updateValues=e.updateValues.bind(de()(e)),e.onFooterUpdate=e.onFooterUpdate.bind(de()(e)),e.onColumnUpdate();var n=e.props.control.setting.get(),a=e.props.control.params.input_attrs.layout;e.controlParams=e.props.control.params.input_attrs?Kr(Kr({},a),e.props.control.params.input_attrs):a;var o,i={mobile:"row",tablet:"",desktop:"equal"},s="equal",c=e.props.control.id.replace("astra-settings[","").replace("-footer-layout]","");e.type=c,e.footer_type="hb"===e.type?"primary":"hba"===e.type?"above":"below",e.controlParams.responsive?(o=i,e.defaultValue=e.props.control.params.default?Kr(Kr({},o),e.props.control.params.default):o):(o=s,e.defaultValue=e.props.control.params.default?e.props.control.params.default:o),n=e.controlParams.responsive?n?Kr(Kr({},JSON.parse(JSON.stringify(e.defaultValue))),n):JSON.parse(JSON.stringify(e.defaultValue)):n||e.defaultValue;var l=0;return l=parseInt(e.props.customizer.control("astra-settings["+e.type+"-footer-column]").setting.get(),10),e.state={currentDevice:"desktop",columns:l,value:n,is_updated:!1},e}return ue()(r,[{key:"render",value:function(){var e=this,t=window.svgIcons,r=Object(a.createElement)(a.Fragment,null,this.props.control.params.label&&this.props.control.params.label),n={};return n="desktop"!==this.state.currentDevice?this.controlParams.mobile[this.state.columns]:this.controlParams.desktop[this.state.columns],Object(a.createElement)("div",{className:"ahfb-control-field ahfb-radio-icon-control ahfb-row-layout-control"},this.controlParams.responsive&&Object(a.createElement)(Jr,{onChange:function(t){return e.setState({currentDevice:t})},controlLabel:r,device:this.props.device},Object(a.createElement)(en,{className:"ahfb-radio-container-control"},Object.keys(n).map((function(r,o){return Object(a.createElement)(rn,{key:o,isTertiary:!0,className:(r===e.state.value[e.state.currentDevice]?"active-radio ":"")+"ast-radio-img-svg ahfb-btn-item-"+o,onClick:function(){var t=e.state.value;t[e.state.currentDevice]=r,e.setState({value:t}),e.updateValues()}},n[r].icon&&Object(a.createElement)("span",{className:"ahfb-radio-icon",dangerouslySetInnerHTML:{__html:t[n[r].icon]}}),n[r].dashicon&&Object(a.createElement)("span",{className:"ahfb-radio-icon ahfb-radio-dashicon"},Object(a.createElement)(tn,{icon:n[r].dashicon})),n[r].name&&n[r].name)})))))}},{key:"onFooterUpdate",value:function(){var e=parseInt(this.props.customizer.control("astra-settings["+this.type+"-footer-column]").setting.get(),10),t=this.state.value;if(this.state.columns!==e){this.setState({columns:e});var r={1:"full",2:"2-equal",3:"3-equal",4:"4-equal",5:"5-equal",6:"6-equal"};t.desktop=r[e],t.tablet=r[e],t.mobile="full",this.setState({value:t}),this.updateValues()}}},{key:"onColumnUpdate",value:function(){var e=this;document.addEventListener("AstraBuilderChangeRowLayout",(function(t){t.detail.columns&&e.onFooterUpdate()}))}},{key:"updateValues",value:function(){var e=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:wp.customize.value("astra-settings["+this.type+"-footer-column]").get(),layout:this.state.value,type:this.footer_type}}),t=this.state.value;document.dispatchEvent(e),this.props.control.setting.set(Kr(Kr(Kr({},this.props.control.setting.get()),t),{},{flag:!this.props.control.setting.get().flag}))}}]),r}(wp.element.Component);nn.propTypes={control:i.a.object.isRequired};var an,on,sn,cn,ln,un,pn,dn,hn,fn,mn,gn=nn,bn=wp.customize.astraControl.extend({renderContent:function(){var e=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");ReactDOM.render(Object(a.createElement)(gn,{control:this,customizer:wp.customize,device:e}),this.container[0])},ready:function(){jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var e="";switch(jQuery(this).attr("data-device")){case"desktop":e="mobile";break;case"tablet":e="desktop";break;case"mobile":e="tablet"}jQuery(".customize-control-ast-row-layout .ahfb-responsive-control-bar .ast-responsive-btns button.preview-"+e).trigger("click")}))}});function vn(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function yn(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?vn(Object(r),!0).forEach((function(t){y()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):vn(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}an=jQuery,on=wp.customize,sn=an(window),cn=an("body"),ln=[],un="",pn=function(){var e=an(".control-section.ahfb-header-builder-active"),t=an(".control-section.ahfb-footer-builder-active"),r=an("#available-widgets");r.css("bottom","289px"),cn.hasClass("ahfb-header-builder-is-active")||cn.hasClass("ahfb-footer-builder-is-active")?cn.hasClass("ahfb-footer-builder-is-active")&&0<t.length&&!t.hasClass("ahfb-builder-hide")?on.previewer.container.css("bottom",t.outerHeight()+"px"):cn.hasClass("ahfb-header-builder-is-active")&&0<e.length&&!e.hasClass("ahfb-builder-hide")?(r.css("bottom","289px"),on.previewer.container.css({bottom:e.outerHeight()+"px"})):(r.css("bottom","46px"),on.previewer.container.css("bottom","")):on.previewer.container.css("bottom",""),e.css("overflow","visible"),t.css("overflow","visible")},dn=function(e){var t=e.id.includes("-header-")?"header":"footer",r=on.section("section-"+t+"-builder");if(r){var n=r.contentContainer,a=on.section("section-"+t+"-builder-layout");e.expanded.bind((function(o){hn.setControlContextBySection(r),hn.setControlContextBySection(a),_.each(r.controls(),(function(e){"resolved"!==e.deferred.embedded.state()&&(e.renderContent(),e.deferred.embedded.resolve(),e.container.trigger("init"))})),_.each(a.controls(),(function(e){"resolved"!==e.deferred.embedded.state()&&(e.renderContent(),e.deferred.embedded.resolve(),e.container.trigger("init"))})),o?(un=e.id,cn.addClass("ahfb-"+t+"-builder-is-active"),n.addClass("ahfb-"+t+"-builder-active"),an("#sub-accordion-panel-"+un+" li.control-section").hide(),"header"===t?an("#sub-accordion-section-section-footer-builder").css("overflow","hidden"):an("#sub-accordion-section-section-header-builder").css("overflow","hidden")):(an("#sub-accordion-section-section-footer-builder").css("overflow","hidden"),an("#sub-accordion-section-section-header-builder").css("overflow","hidden"),on.state("astra-customizer-tab").set("general"),cn.removeClass("ahfb-"+t+"-builder-is-active"),n.removeClass("ahfb-"+t+"-builder-active")),pn()})),n.on("click",".ahfb-builder-tab-toggle",(function(e){e.preventDefault(),on.previewer.container.css({bottom:"0px"}),setTimeout((function(){n.toggleClass("ahfb-builder-hide"),pn()}),120)}))}},hn={addPanel:function(e,t){if(!on.panel(e)){var r,n=on.panelConstructor[t.type]||on.Panel;r=_.extend({params:t},t),on.panel.add(new n(e,r));var a=!1;"undefined"!=typeof AstraBuilderCustomizerData&&AstraBuilderCustomizerData.is_site_rtl&&(a=!0),"panel-footer-builder-group"!==e&&"panel-header-builder-group"!==e||(a?an("#accordion-panel-"+e).find(".accordion-section-title").prepend("<span class='ahfb-highlight'> New </span>"):an("#accordion-panel-"+e).find(".accordion-section-title").append("<span class='ahfb-highlight'> New </span>")),"panel-footer-builder-group"===e&&an("#accordion-panel-"+e).on("click",(function(){var e=cn.find("iframe").contents().find("body");cn.find("iframe").contents().find("body, html").animate({scrollTop:e[0].scrollHeight},500)})),"panel-header-builder-group"===e&&an("#accordion-panel-"+e).on("click",(function(){cn.find("iframe").contents().find("body, html").animate({scrollTop:0},500)}))}},addSection:function(e,t){if(on.section(e)){if(e.startsWith("sidebar-widgets-"))return void on.section(e).panel(t.panel);on.section.remove(e)}var r,n=on.sectionConstructor[t.type]||on.Section;r=_.extend({params:t},t),on.section.add(new n(e,r))},addSubControl:function(e){if("undefined"!=typeof AstraBuilderCustomizerData){var t=AstraBuilderCustomizerData.js_configs.sub_controls[e]||[];if(t)for(var r=0;r<t.length;r++){var n=t[r];hn.addControl(n.id,n)}}},addControl:function(e,t){if(!on.control(e)){var r,n=on.controlConstructor[t.type]||on.Control;r=_.extend({params:t},t),on.control.add(new n(e,r)),fn(on.control(e)),"ast-settings-group"===t.type&&this.addSubControl(e)}},addControlContext:function(e,t){mn(t)},registerControlsBySection:function(e){if("undefined"!=typeof AstraBuilderCustomizerData){var t=AstraBuilderCustomizerData.js_configs.controls[e.id]||[];if(t)for(var r=0;r<t.length;r++){var n=t[r];this.addControl(n.id,n)}}},setControlContextBySection:function(e){if(!ln.includes(e.id)&&"undefined"!=typeof AstraBuilderCustomizerData){var t=AstraBuilderCustomizerData.js_configs.controls[e.id]||[];if(t)for(var r=0;r<t.length;r++){var n=t[r];this.addControlContext(e.id,n.id)}ln.push(e.id)}},setDefaultControlContext:function(){if("undefined"!=typeof AstraBuilderCustomizerData){var e=AstraBuilderCustomizerData.js_configs.skip_context||[];an.each(on.settings.controls,(function(t,r){if(-1==e.indexOf(t)&&-1!=AstraBuilderCustomizerData.tabbed_sections.indexOf(on.control(t).section())){var n=AstraBuilderCustomizerData.contexts[t];mn(t,n||[{setting:"ast_selected_tab",value:"general"}])}}))}},initializeConfigs:function(){if("undefined"!=typeof AstraBuilderCustomizerData&&AstraBuilderCustomizerData.js_configs){for(var e=AstraBuilderCustomizerData.js_configs.panels||[],t=AstraBuilderCustomizerData.js_configs.sections||[],r=Object.assign({},AstraBuilderCustomizerData.js_configs.controls||[]),n=0,a=Object.entries(e);n<a.length;n++){var o=O()(a[n],2),i=o[0],s=o[1];hn.addPanel(i,s)}for(var c=0,l=Object.entries(t);c<l.length;c++){var u=O()(l[c],2),p=u[0],d=u[1];hn.addSection(p,d),hn.registerControlsBySection(on.section(p)),delete r[p]}for(var h=0,f=Object.entries(r);h<f.length;h++){var m=O()(f[h],2),g=m[0];m[1],void 0!==on.section(g)&&hn.registerControlsBySection(on.section(g))}on.panel("panel-header-builder-group",dn),on.panel("panel-footer-builder-group",dn)}},moveDefaultSection:function(){if("undefined"!=typeof AstraBuilderCustomizerData&&AstraBuilderCustomizerData.js_configs.wp_defaults)for(var e=0,t=Object.entries(AstraBuilderCustomizerData.js_configs.wp_defaults);e<t.length;e++){var r=O()(t[e],2),n=r[0],a=r[1];on.control(n).section(a)}}},fn=function(e){var t=e.container.find(".customize-control-description");if(t.length){e.container.find(".customize-control-title");var r=t.closest("li"),n=t.text().replace(/[\u00A0-\u9999<>\&]/gim,(function(e){return"&#"+e.charCodeAt(0)+";"}));t.remove(),r.append(" <i class='ast-control-tooltip dashicons dashicons-editor-help'title='"+n+"'></i>")}},mn=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if("undefined"!=typeof AstraBuilderCustomizerData){var r=t||AstraBuilderCustomizerData.contexts[e];if(r){var n=function(e){switch(e){case"ast_selected_device":return on.previewedDevice;case"ast_selected_tab":return on.state("astra-customizer-tab");default:return on(e)}},a=function(e){var t=function e(t,r,a){return _.each(t,(function(t,o){if("relation"!=o&&("AND"!=r||0!=a))if(void 0===t.setting){var i=t.relation;if(!i)return;a=e(t,i,!1)}else{var s=function(e){var t=!1,r=n(e.setting);if(void 0===r)return!1;var a=e.operator,o=e.value,i=r.get();switch(null!=a&&"="!=a||(a="=="),a){case">":t=i>o;break;case"<":t=i<o;break;case">=":t=i>=o;break;case"<=":t=i<=o;break;case"in":t=0<=o.indexOf(i);break;case"contains":t=0<=i.indexOf(o);break;case"!=":t=o!=i;break;default:t=o==i}return t}(t);a=function(e,t,r){switch(e){case"OR":t=t||r;break;default:t=t&&r}return t}(r,a,s)}})),a},a=function(){var e=!1,n=r.relation;return"OR"!==n&&(n="AND",e=!0),t(r,n,e)},o=function(){e._toggleActive(a(),{duration:0})};!function e(t){_.each(t,(function(t,r){var a=n(t.setting);void 0!==a?a.bind(o):t.relation&&e(t)}))}(r),e.active.validate=a,o()};on.control(e,a)}}},on.bind("ready",(function(){on.state.create("astra-customizer-tab"),on.state("astra-customizer-tab").set("general"),an("#customize-theme-controls").on("click",".ahfb-build-tabs-button:not(.ahfb-nav-tabs-button)",(function(e){e.preventDefault(),on.previewedDevice.set(an(this).attr("data-device"))})),an("#customize-theme-controls").on("click",".ahfb-compontent-tabs-button:not(.ahfb-nav-tabs-button)",(function(e){e.preventDefault(),on.state("astra-customizer-tab").set(an(this).attr("data-tab"))})),on.state("astra-customizer-tab").bind((function(){var e=on.state("astra-customizer-tab").get();an(".ahfb-compontent-tabs-button:not(.ahfb-nav-tabs-button)").removeClass("nav-tab-active").filter(".ahfb-"+e+"-tab").addClass("nav-tab-active")})),sn.on("resize",pn),hn.initializeConfigs(),on.section.each((function(e){e.expanded.bind((function(t){hn.setControlContextBySection(on.section(e.id)),t||on.state("astra-customizer-tab").set("general"),an("#sub-accordion-panel-"+un+" li.control-section").hide();var r,n=on.section(e.id);(r=new URLSearchParams(window.location.search).get("context"))&&on.state("astra-customizer-tab").set(r),_.each(e.controls(),(function(e){!function(e){var t=an(".ahfb-builder-drop .ahfb-builder-item");an.each(t,(function(t,r){var n=an(r).attr("data-section");n===e.id&&an("#sub-accordion-section-"+n).hasClass("open")?an(r).addClass("active-builder-item"):an(r).removeClass("active-builder-item")}))}(n),function(e){var t=an(".ahfb-builder-items .ahfb-builder-areas");an.each(t,(function(t,r){var n=an(r).attr("data-row-section");n===e.id&&an("#sub-accordion-section-"+n).hasClass("open")?an(r).addClass("active-builder-row"):an(r).removeClass("active-builder-row")}))}(n)}))}))})),hn.moveDefaultSection(),on.previewer.bind("ready",(function(){hn.setDefaultControlContext()}))})),function(e,t){t.bind("ready",(function(){sessionStorage.removeItem("astPartialContentRendered"),t("astra-settings[hba-footer-column]",(function(e){e.bind((function(e){var r=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:e,layout:t.value("astra-settings[hba-footer-layout]").get(),type:"above"}});document.dispatchEvent(r)}))})),t("astra-settings[hb-footer-column]",(function(e){e.bind((function(e){var r=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:e,layout:t.value("astra-settings[hb-footer-layout]").get(),type:"primary"}});document.dispatchEvent(r)}))})),t("astra-settings[hbb-footer-column]",(function(e){e.bind((function(e){var r=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:e,layout:t.value("astra-settings[hbb-footer-layout]").get(),type:"below"}});document.dispatchEvent(r)}))})),t("astra-settings[different-mobile-logo]",(function(e){e.bind((function(e){var r=t.control("astra-settings[mobile-header-logo]");!e&&r&&r.container.find(".remove-button").click()}))})),t.previewedDevice.bind((function(e,r){t.previewer.send("astPreviewDeviceChanged",{device:e});var n=sessionStorage.getItem("astPartialContentRendered"),a=t.state("saved").get();if(n&&!a){var o=t("desktop"===e?"astra-settings[header-desktop-items]":"astra-settings[header-mobile-items]");void 0!==o&&o.set(yn(yn(yn({},o.get()),[]),{},{flag:!o.get().flag}))}}))}))}(jQuery,wp.customize),window.svgIcons=n,wp.customize.controlConstructor["ast-heading"]=l,wp.customize.controlConstructor["ast-hidden"]=d,wp.customize.controlConstructor["ast-description"]=b,wp.customize.controlConstructor["ast-link"]=D,wp.customize.controlConstructor["ast-divider"]=T,wp.customize.controlConstructor["ast-settings-group"]=Ge,wp.customize.controlConstructor["ast-color"]=Ye,wp.customize.controlConstructor["ast-customizer-link"]=nt,wp.customize.controlConstructor["ast-slider"]=it,wp.customize.controlConstructor["ast-radio-image"]=lt,wp.customize.controlConstructor["ast-select"]=dt,wp.customize.controlConstructor["ast-header-type-button"]=Fr,wp.customize.controlConstructor["ast-builder-header-control"]=St,wp.customize.controlConstructor["ast-sortable"]=$e,wp.customize.controlConstructor["ast-font-family"]=mt,wp.customize.controlConstructor["ast-font-weight"]=vt,wp.customize.controlConstructor["ast-responsive-select"]=Et,wp.customize.controlConstructor["ast-responsive-slider"]=ot,wp.customize.controlConstructor["ast-responsive-spacing"]=ut,wp.customize.controlConstructor["ast-responsive-box-shadow"]=pt,wp.customize.controlConstructor["ast-border"]=et,wp.customize.controlConstructor["ast-responsive"]=at,wp.customize.controlConstructor["ast-responsive-color"]=Xe,wp.customize.controlConstructor["ast-responsive-background"]=Je,wp.customize.controlConstructor["ast-background"]=We,wp.customize.controlConstructor["ast-social-icons"]=wr,wp.customize.controlConstructor["ast-html-editor"]=kr,wp.customize.controlConstructor["ast-builder"]=rr,wp.customize.controlConstructor["ast-draggable-items"]=Lr,wp.customize.controlConstructor["ast-row-layout"]=bn}]);