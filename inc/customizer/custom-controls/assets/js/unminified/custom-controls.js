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

!function(t){var e={};function r(n){if(e[n])return e[n].exports;var a=e[n]={i:n,l:!1,exports:{}};return t[n].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=t,r.c=e,r.d=function(t,e,n){r.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},r.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var a in t)r.d(n,a,function(e){return t[e]}.bind(null,a));return n},r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,"a",e),e},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.p="",r(r.s=98)}([function(t,e){!function(){t.exports=this.wp.element}()},function(t,e,r){t.exports=r(45)()},function(t,e){!function(){t.exports=this.wp.i18n}()},function(t,e){!function(){t.exports=this.React}()},function(t,e,r){var n=r(82),a=r(83),o=r(34),i=r(84);t.exports=function(t,e){return n(t)||a(t,e)||o(t,e)||i()}},function(t,e){t.exports=function(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}},function(t,e){!function(){t.exports=this.wp.components}()},function(t,e){function r(){return t.exports=r=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var r=arguments[e];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(t[n]=r[n])}return t},r.apply(this,arguments)}t.exports=r},function(t,e){t.exports=function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}},function(t,e){function r(e){return t.exports=r=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},r(e)}t.exports=r},function(t,e,r){var n=r(22),a=r(26);function o(e,r){return delete t.exports[e],t.exports[e]=r,r}t.exports={Parser:n,Tokenizer:r(23),ElementType:r(11),DomHandler:a,get FeedHandler(){return o("FeedHandler",r(52))},get Stream(){return o("Stream",r(63))},get WritableStream(){return o("WritableStream",r(29))},get ProxyHandler(){return o("ProxyHandler",r(70))},get DomUtils(){return o("DomUtils",r(28))},get CollectingHandler(){return o("CollectingHandler",r(71))},DefaultHandler:a,get RssHandler(){return o("RssHandler",this.FeedHandler)},parseDOM:function(t,e){var r=new a(e);return new n(r,e).end(t),r.dom},parseFeed:function(e,r){var a=new t.exports.FeedHandler(r);return new n(a,r).end(e),a.dom},createDomStream:function(t,e,r){var o=new a(t,e,r);return new n(o,e)},EVENTS:{attribute:2,cdatastart:0,cdataend:0,text:1,processinginstruction:2,comment:1,commentend:0,closetag:1,opentag:2,opentagname:1,error:1,end:0}}},function(t,e){t.exports={Text:"text",Directive:"directive",Comment:"comment",Script:"script",Style:"style",Tag:"tag",CDATA:"cdata",Doctype:"doctype",isTag:function(t){return"tag"===t.type||"script"===t.type||"style"===t.type}}},function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},function(t,e){function r(t,e){for(var r=0;r<e.length;r++){var n=e[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}t.exports=function(t,e,n){return e&&r(t.prototype,e),n&&r(t,n),t}},function(t,e,r){var n=r(88);t.exports=function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&n(t,e)}},function(t,e,r){var n=r(89),a=r(8);t.exports=function(t,e){return!e||"object"!==n(e)&&"function"!=typeof e?a(t):e}},function(t,e){"function"==typeof Object.create?t.exports=function(t,e){e&&(t.super_=e,t.prototype=Object.create(e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}))}:t.exports=function(t,e){if(e){t.super_=e;var r=function(){};r.prototype=e.prototype,t.prototype=new r,t.prototype.constructor=t}}},function(t,e,r){var n;
/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/!function(){"use strict";var r={}.hasOwnProperty;function a(){for(var t=[],e=0;e<arguments.length;e++){var n=arguments[e];if(n){var o=typeof n;if("string"===o||"number"===o)t.push(n);else if(Array.isArray(n)&&n.length){var i=a.apply(null,n);i&&t.push(i)}else if("object"===o)for(var s in n)r.call(n,s)&&n[s]&&t.push(s)}}return t.join(" ")}t.exports?(a.default=a,t.exports=a):void 0===(n=function(){return a}.apply(e,[]))||(t.exports=n)}()},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t,e){return t.filter((function(t){return!(0,n.default)(t)})).map((function(t,r){var n=void 0;return"function"!=typeof e||null!==(n=e(t,r))&&!n?(0,a.default)(t,r,e):n}))};var n=o(r(47)),a=o(r(21));function o(t){return t&&t.__esModule?t:{default:t}}},function(t){t.exports=JSON.parse('{"Aacute":"Á","aacute":"á","Abreve":"Ă","abreve":"ă","ac":"∾","acd":"∿","acE":"∾̳","Acirc":"Â","acirc":"â","acute":"´","Acy":"А","acy":"а","AElig":"Æ","aelig":"æ","af":"⁡","Afr":"𝔄","afr":"𝔞","Agrave":"À","agrave":"à","alefsym":"ℵ","aleph":"ℵ","Alpha":"Α","alpha":"α","Amacr":"Ā","amacr":"ā","amalg":"⨿","amp":"&","AMP":"&","andand":"⩕","And":"⩓","and":"∧","andd":"⩜","andslope":"⩘","andv":"⩚","ang":"∠","ange":"⦤","angle":"∠","angmsdaa":"⦨","angmsdab":"⦩","angmsdac":"⦪","angmsdad":"⦫","angmsdae":"⦬","angmsdaf":"⦭","angmsdag":"⦮","angmsdah":"⦯","angmsd":"∡","angrt":"∟","angrtvb":"⊾","angrtvbd":"⦝","angsph":"∢","angst":"Å","angzarr":"⍼","Aogon":"Ą","aogon":"ą","Aopf":"𝔸","aopf":"𝕒","apacir":"⩯","ap":"≈","apE":"⩰","ape":"≊","apid":"≋","apos":"\'","ApplyFunction":"⁡","approx":"≈","approxeq":"≊","Aring":"Å","aring":"å","Ascr":"𝒜","ascr":"𝒶","Assign":"≔","ast":"*","asymp":"≈","asympeq":"≍","Atilde":"Ã","atilde":"ã","Auml":"Ä","auml":"ä","awconint":"∳","awint":"⨑","backcong":"≌","backepsilon":"϶","backprime":"‵","backsim":"∽","backsimeq":"⋍","Backslash":"∖","Barv":"⫧","barvee":"⊽","barwed":"⌅","Barwed":"⌆","barwedge":"⌅","bbrk":"⎵","bbrktbrk":"⎶","bcong":"≌","Bcy":"Б","bcy":"б","bdquo":"„","becaus":"∵","because":"∵","Because":"∵","bemptyv":"⦰","bepsi":"϶","bernou":"ℬ","Bernoullis":"ℬ","Beta":"Β","beta":"β","beth":"ℶ","between":"≬","Bfr":"𝔅","bfr":"𝔟","bigcap":"⋂","bigcirc":"◯","bigcup":"⋃","bigodot":"⨀","bigoplus":"⨁","bigotimes":"⨂","bigsqcup":"⨆","bigstar":"★","bigtriangledown":"▽","bigtriangleup":"△","biguplus":"⨄","bigvee":"⋁","bigwedge":"⋀","bkarow":"⤍","blacklozenge":"⧫","blacksquare":"▪","blacktriangle":"▴","blacktriangledown":"▾","blacktriangleleft":"◂","blacktriangleright":"▸","blank":"␣","blk12":"▒","blk14":"░","blk34":"▓","block":"█","bne":"=⃥","bnequiv":"≡⃥","bNot":"⫭","bnot":"⌐","Bopf":"𝔹","bopf":"𝕓","bot":"⊥","bottom":"⊥","bowtie":"⋈","boxbox":"⧉","boxdl":"┐","boxdL":"╕","boxDl":"╖","boxDL":"╗","boxdr":"┌","boxdR":"╒","boxDr":"╓","boxDR":"╔","boxh":"─","boxH":"═","boxhd":"┬","boxHd":"╤","boxhD":"╥","boxHD":"╦","boxhu":"┴","boxHu":"╧","boxhU":"╨","boxHU":"╩","boxminus":"⊟","boxplus":"⊞","boxtimes":"⊠","boxul":"┘","boxuL":"╛","boxUl":"╜","boxUL":"╝","boxur":"└","boxuR":"╘","boxUr":"╙","boxUR":"╚","boxv":"│","boxV":"║","boxvh":"┼","boxvH":"╪","boxVh":"╫","boxVH":"╬","boxvl":"┤","boxvL":"╡","boxVl":"╢","boxVL":"╣","boxvr":"├","boxvR":"╞","boxVr":"╟","boxVR":"╠","bprime":"‵","breve":"˘","Breve":"˘","brvbar":"¦","bscr":"𝒷","Bscr":"ℬ","bsemi":"⁏","bsim":"∽","bsime":"⋍","bsolb":"⧅","bsol":"\\\\","bsolhsub":"⟈","bull":"•","bullet":"•","bump":"≎","bumpE":"⪮","bumpe":"≏","Bumpeq":"≎","bumpeq":"≏","Cacute":"Ć","cacute":"ć","capand":"⩄","capbrcup":"⩉","capcap":"⩋","cap":"∩","Cap":"⋒","capcup":"⩇","capdot":"⩀","CapitalDifferentialD":"ⅅ","caps":"∩︀","caret":"⁁","caron":"ˇ","Cayleys":"ℭ","ccaps":"⩍","Ccaron":"Č","ccaron":"č","Ccedil":"Ç","ccedil":"ç","Ccirc":"Ĉ","ccirc":"ĉ","Cconint":"∰","ccups":"⩌","ccupssm":"⩐","Cdot":"Ċ","cdot":"ċ","cedil":"¸","Cedilla":"¸","cemptyv":"⦲","cent":"¢","centerdot":"·","CenterDot":"·","cfr":"𝔠","Cfr":"ℭ","CHcy":"Ч","chcy":"ч","check":"✓","checkmark":"✓","Chi":"Χ","chi":"χ","circ":"ˆ","circeq":"≗","circlearrowleft":"↺","circlearrowright":"↻","circledast":"⊛","circledcirc":"⊚","circleddash":"⊝","CircleDot":"⊙","circledR":"®","circledS":"Ⓢ","CircleMinus":"⊖","CirclePlus":"⊕","CircleTimes":"⊗","cir":"○","cirE":"⧃","cire":"≗","cirfnint":"⨐","cirmid":"⫯","cirscir":"⧂","ClockwiseContourIntegral":"∲","CloseCurlyDoubleQuote":"”","CloseCurlyQuote":"’","clubs":"♣","clubsuit":"♣","colon":":","Colon":"∷","Colone":"⩴","colone":"≔","coloneq":"≔","comma":",","commat":"@","comp":"∁","compfn":"∘","complement":"∁","complexes":"ℂ","cong":"≅","congdot":"⩭","Congruent":"≡","conint":"∮","Conint":"∯","ContourIntegral":"∮","copf":"𝕔","Copf":"ℂ","coprod":"∐","Coproduct":"∐","copy":"©","COPY":"©","copysr":"℗","CounterClockwiseContourIntegral":"∳","crarr":"↵","cross":"✗","Cross":"⨯","Cscr":"𝒞","cscr":"𝒸","csub":"⫏","csube":"⫑","csup":"⫐","csupe":"⫒","ctdot":"⋯","cudarrl":"⤸","cudarrr":"⤵","cuepr":"⋞","cuesc":"⋟","cularr":"↶","cularrp":"⤽","cupbrcap":"⩈","cupcap":"⩆","CupCap":"≍","cup":"∪","Cup":"⋓","cupcup":"⩊","cupdot":"⊍","cupor":"⩅","cups":"∪︀","curarr":"↷","curarrm":"⤼","curlyeqprec":"⋞","curlyeqsucc":"⋟","curlyvee":"⋎","curlywedge":"⋏","curren":"¤","curvearrowleft":"↶","curvearrowright":"↷","cuvee":"⋎","cuwed":"⋏","cwconint":"∲","cwint":"∱","cylcty":"⌭","dagger":"†","Dagger":"‡","daleth":"ℸ","darr":"↓","Darr":"↡","dArr":"⇓","dash":"‐","Dashv":"⫤","dashv":"⊣","dbkarow":"⤏","dblac":"˝","Dcaron":"Ď","dcaron":"ď","Dcy":"Д","dcy":"д","ddagger":"‡","ddarr":"⇊","DD":"ⅅ","dd":"ⅆ","DDotrahd":"⤑","ddotseq":"⩷","deg":"°","Del":"∇","Delta":"Δ","delta":"δ","demptyv":"⦱","dfisht":"⥿","Dfr":"𝔇","dfr":"𝔡","dHar":"⥥","dharl":"⇃","dharr":"⇂","DiacriticalAcute":"´","DiacriticalDot":"˙","DiacriticalDoubleAcute":"˝","DiacriticalGrave":"`","DiacriticalTilde":"˜","diam":"⋄","diamond":"⋄","Diamond":"⋄","diamondsuit":"♦","diams":"♦","die":"¨","DifferentialD":"ⅆ","digamma":"ϝ","disin":"⋲","div":"÷","divide":"÷","divideontimes":"⋇","divonx":"⋇","DJcy":"Ђ","djcy":"ђ","dlcorn":"⌞","dlcrop":"⌍","dollar":"$","Dopf":"𝔻","dopf":"𝕕","Dot":"¨","dot":"˙","DotDot":"⃜","doteq":"≐","doteqdot":"≑","DotEqual":"≐","dotminus":"∸","dotplus":"∔","dotsquare":"⊡","doublebarwedge":"⌆","DoubleContourIntegral":"∯","DoubleDot":"¨","DoubleDownArrow":"⇓","DoubleLeftArrow":"⇐","DoubleLeftRightArrow":"⇔","DoubleLeftTee":"⫤","DoubleLongLeftArrow":"⟸","DoubleLongLeftRightArrow":"⟺","DoubleLongRightArrow":"⟹","DoubleRightArrow":"⇒","DoubleRightTee":"⊨","DoubleUpArrow":"⇑","DoubleUpDownArrow":"⇕","DoubleVerticalBar":"∥","DownArrowBar":"⤓","downarrow":"↓","DownArrow":"↓","Downarrow":"⇓","DownArrowUpArrow":"⇵","DownBreve":"̑","downdownarrows":"⇊","downharpoonleft":"⇃","downharpoonright":"⇂","DownLeftRightVector":"⥐","DownLeftTeeVector":"⥞","DownLeftVectorBar":"⥖","DownLeftVector":"↽","DownRightTeeVector":"⥟","DownRightVectorBar":"⥗","DownRightVector":"⇁","DownTeeArrow":"↧","DownTee":"⊤","drbkarow":"⤐","drcorn":"⌟","drcrop":"⌌","Dscr":"𝒟","dscr":"𝒹","DScy":"Ѕ","dscy":"ѕ","dsol":"⧶","Dstrok":"Đ","dstrok":"đ","dtdot":"⋱","dtri":"▿","dtrif":"▾","duarr":"⇵","duhar":"⥯","dwangle":"⦦","DZcy":"Џ","dzcy":"џ","dzigrarr":"⟿","Eacute":"É","eacute":"é","easter":"⩮","Ecaron":"Ě","ecaron":"ě","Ecirc":"Ê","ecirc":"ê","ecir":"≖","ecolon":"≕","Ecy":"Э","ecy":"э","eDDot":"⩷","Edot":"Ė","edot":"ė","eDot":"≑","ee":"ⅇ","efDot":"≒","Efr":"𝔈","efr":"𝔢","eg":"⪚","Egrave":"È","egrave":"è","egs":"⪖","egsdot":"⪘","el":"⪙","Element":"∈","elinters":"⏧","ell":"ℓ","els":"⪕","elsdot":"⪗","Emacr":"Ē","emacr":"ē","empty":"∅","emptyset":"∅","EmptySmallSquare":"◻","emptyv":"∅","EmptyVerySmallSquare":"▫","emsp13":" ","emsp14":" ","emsp":" ","ENG":"Ŋ","eng":"ŋ","ensp":" ","Eogon":"Ę","eogon":"ę","Eopf":"𝔼","eopf":"𝕖","epar":"⋕","eparsl":"⧣","eplus":"⩱","epsi":"ε","Epsilon":"Ε","epsilon":"ε","epsiv":"ϵ","eqcirc":"≖","eqcolon":"≕","eqsim":"≂","eqslantgtr":"⪖","eqslantless":"⪕","Equal":"⩵","equals":"=","EqualTilde":"≂","equest":"≟","Equilibrium":"⇌","equiv":"≡","equivDD":"⩸","eqvparsl":"⧥","erarr":"⥱","erDot":"≓","escr":"ℯ","Escr":"ℰ","esdot":"≐","Esim":"⩳","esim":"≂","Eta":"Η","eta":"η","ETH":"Ð","eth":"ð","Euml":"Ë","euml":"ë","euro":"€","excl":"!","exist":"∃","Exists":"∃","expectation":"ℰ","exponentiale":"ⅇ","ExponentialE":"ⅇ","fallingdotseq":"≒","Fcy":"Ф","fcy":"ф","female":"♀","ffilig":"ﬃ","fflig":"ﬀ","ffllig":"ﬄ","Ffr":"𝔉","ffr":"𝔣","filig":"ﬁ","FilledSmallSquare":"◼","FilledVerySmallSquare":"▪","fjlig":"fj","flat":"♭","fllig":"ﬂ","fltns":"▱","fnof":"ƒ","Fopf":"𝔽","fopf":"𝕗","forall":"∀","ForAll":"∀","fork":"⋔","forkv":"⫙","Fouriertrf":"ℱ","fpartint":"⨍","frac12":"½","frac13":"⅓","frac14":"¼","frac15":"⅕","frac16":"⅙","frac18":"⅛","frac23":"⅔","frac25":"⅖","frac34":"¾","frac35":"⅗","frac38":"⅜","frac45":"⅘","frac56":"⅚","frac58":"⅝","frac78":"⅞","frasl":"⁄","frown":"⌢","fscr":"𝒻","Fscr":"ℱ","gacute":"ǵ","Gamma":"Γ","gamma":"γ","Gammad":"Ϝ","gammad":"ϝ","gap":"⪆","Gbreve":"Ğ","gbreve":"ğ","Gcedil":"Ģ","Gcirc":"Ĝ","gcirc":"ĝ","Gcy":"Г","gcy":"г","Gdot":"Ġ","gdot":"ġ","ge":"≥","gE":"≧","gEl":"⪌","gel":"⋛","geq":"≥","geqq":"≧","geqslant":"⩾","gescc":"⪩","ges":"⩾","gesdot":"⪀","gesdoto":"⪂","gesdotol":"⪄","gesl":"⋛︀","gesles":"⪔","Gfr":"𝔊","gfr":"𝔤","gg":"≫","Gg":"⋙","ggg":"⋙","gimel":"ℷ","GJcy":"Ѓ","gjcy":"ѓ","gla":"⪥","gl":"≷","glE":"⪒","glj":"⪤","gnap":"⪊","gnapprox":"⪊","gne":"⪈","gnE":"≩","gneq":"⪈","gneqq":"≩","gnsim":"⋧","Gopf":"𝔾","gopf":"𝕘","grave":"`","GreaterEqual":"≥","GreaterEqualLess":"⋛","GreaterFullEqual":"≧","GreaterGreater":"⪢","GreaterLess":"≷","GreaterSlantEqual":"⩾","GreaterTilde":"≳","Gscr":"𝒢","gscr":"ℊ","gsim":"≳","gsime":"⪎","gsiml":"⪐","gtcc":"⪧","gtcir":"⩺","gt":">","GT":">","Gt":"≫","gtdot":"⋗","gtlPar":"⦕","gtquest":"⩼","gtrapprox":"⪆","gtrarr":"⥸","gtrdot":"⋗","gtreqless":"⋛","gtreqqless":"⪌","gtrless":"≷","gtrsim":"≳","gvertneqq":"≩︀","gvnE":"≩︀","Hacek":"ˇ","hairsp":" ","half":"½","hamilt":"ℋ","HARDcy":"Ъ","hardcy":"ъ","harrcir":"⥈","harr":"↔","hArr":"⇔","harrw":"↭","Hat":"^","hbar":"ℏ","Hcirc":"Ĥ","hcirc":"ĥ","hearts":"♥","heartsuit":"♥","hellip":"…","hercon":"⊹","hfr":"𝔥","Hfr":"ℌ","HilbertSpace":"ℋ","hksearow":"⤥","hkswarow":"⤦","hoarr":"⇿","homtht":"∻","hookleftarrow":"↩","hookrightarrow":"↪","hopf":"𝕙","Hopf":"ℍ","horbar":"―","HorizontalLine":"─","hscr":"𝒽","Hscr":"ℋ","hslash":"ℏ","Hstrok":"Ħ","hstrok":"ħ","HumpDownHump":"≎","HumpEqual":"≏","hybull":"⁃","hyphen":"‐","Iacute":"Í","iacute":"í","ic":"⁣","Icirc":"Î","icirc":"î","Icy":"И","icy":"и","Idot":"İ","IEcy":"Е","iecy":"е","iexcl":"¡","iff":"⇔","ifr":"𝔦","Ifr":"ℑ","Igrave":"Ì","igrave":"ì","ii":"ⅈ","iiiint":"⨌","iiint":"∭","iinfin":"⧜","iiota":"℩","IJlig":"Ĳ","ijlig":"ĳ","Imacr":"Ī","imacr":"ī","image":"ℑ","ImaginaryI":"ⅈ","imagline":"ℐ","imagpart":"ℑ","imath":"ı","Im":"ℑ","imof":"⊷","imped":"Ƶ","Implies":"⇒","incare":"℅","in":"∈","infin":"∞","infintie":"⧝","inodot":"ı","intcal":"⊺","int":"∫","Int":"∬","integers":"ℤ","Integral":"∫","intercal":"⊺","Intersection":"⋂","intlarhk":"⨗","intprod":"⨼","InvisibleComma":"⁣","InvisibleTimes":"⁢","IOcy":"Ё","iocy":"ё","Iogon":"Į","iogon":"į","Iopf":"𝕀","iopf":"𝕚","Iota":"Ι","iota":"ι","iprod":"⨼","iquest":"¿","iscr":"𝒾","Iscr":"ℐ","isin":"∈","isindot":"⋵","isinE":"⋹","isins":"⋴","isinsv":"⋳","isinv":"∈","it":"⁢","Itilde":"Ĩ","itilde":"ĩ","Iukcy":"І","iukcy":"і","Iuml":"Ï","iuml":"ï","Jcirc":"Ĵ","jcirc":"ĵ","Jcy":"Й","jcy":"й","Jfr":"𝔍","jfr":"𝔧","jmath":"ȷ","Jopf":"𝕁","jopf":"𝕛","Jscr":"𝒥","jscr":"𝒿","Jsercy":"Ј","jsercy":"ј","Jukcy":"Є","jukcy":"є","Kappa":"Κ","kappa":"κ","kappav":"ϰ","Kcedil":"Ķ","kcedil":"ķ","Kcy":"К","kcy":"к","Kfr":"𝔎","kfr":"𝔨","kgreen":"ĸ","KHcy":"Х","khcy":"х","KJcy":"Ќ","kjcy":"ќ","Kopf":"𝕂","kopf":"𝕜","Kscr":"𝒦","kscr":"𝓀","lAarr":"⇚","Lacute":"Ĺ","lacute":"ĺ","laemptyv":"⦴","lagran":"ℒ","Lambda":"Λ","lambda":"λ","lang":"⟨","Lang":"⟪","langd":"⦑","langle":"⟨","lap":"⪅","Laplacetrf":"ℒ","laquo":"«","larrb":"⇤","larrbfs":"⤟","larr":"←","Larr":"↞","lArr":"⇐","larrfs":"⤝","larrhk":"↩","larrlp":"↫","larrpl":"⤹","larrsim":"⥳","larrtl":"↢","latail":"⤙","lAtail":"⤛","lat":"⪫","late":"⪭","lates":"⪭︀","lbarr":"⤌","lBarr":"⤎","lbbrk":"❲","lbrace":"{","lbrack":"[","lbrke":"⦋","lbrksld":"⦏","lbrkslu":"⦍","Lcaron":"Ľ","lcaron":"ľ","Lcedil":"Ļ","lcedil":"ļ","lceil":"⌈","lcub":"{","Lcy":"Л","lcy":"л","ldca":"⤶","ldquo":"“","ldquor":"„","ldrdhar":"⥧","ldrushar":"⥋","ldsh":"↲","le":"≤","lE":"≦","LeftAngleBracket":"⟨","LeftArrowBar":"⇤","leftarrow":"←","LeftArrow":"←","Leftarrow":"⇐","LeftArrowRightArrow":"⇆","leftarrowtail":"↢","LeftCeiling":"⌈","LeftDoubleBracket":"⟦","LeftDownTeeVector":"⥡","LeftDownVectorBar":"⥙","LeftDownVector":"⇃","LeftFloor":"⌊","leftharpoondown":"↽","leftharpoonup":"↼","leftleftarrows":"⇇","leftrightarrow":"↔","LeftRightArrow":"↔","Leftrightarrow":"⇔","leftrightarrows":"⇆","leftrightharpoons":"⇋","leftrightsquigarrow":"↭","LeftRightVector":"⥎","LeftTeeArrow":"↤","LeftTee":"⊣","LeftTeeVector":"⥚","leftthreetimes":"⋋","LeftTriangleBar":"⧏","LeftTriangle":"⊲","LeftTriangleEqual":"⊴","LeftUpDownVector":"⥑","LeftUpTeeVector":"⥠","LeftUpVectorBar":"⥘","LeftUpVector":"↿","LeftVectorBar":"⥒","LeftVector":"↼","lEg":"⪋","leg":"⋚","leq":"≤","leqq":"≦","leqslant":"⩽","lescc":"⪨","les":"⩽","lesdot":"⩿","lesdoto":"⪁","lesdotor":"⪃","lesg":"⋚︀","lesges":"⪓","lessapprox":"⪅","lessdot":"⋖","lesseqgtr":"⋚","lesseqqgtr":"⪋","LessEqualGreater":"⋚","LessFullEqual":"≦","LessGreater":"≶","lessgtr":"≶","LessLess":"⪡","lesssim":"≲","LessSlantEqual":"⩽","LessTilde":"≲","lfisht":"⥼","lfloor":"⌊","Lfr":"𝔏","lfr":"𝔩","lg":"≶","lgE":"⪑","lHar":"⥢","lhard":"↽","lharu":"↼","lharul":"⥪","lhblk":"▄","LJcy":"Љ","ljcy":"љ","llarr":"⇇","ll":"≪","Ll":"⋘","llcorner":"⌞","Lleftarrow":"⇚","llhard":"⥫","lltri":"◺","Lmidot":"Ŀ","lmidot":"ŀ","lmoustache":"⎰","lmoust":"⎰","lnap":"⪉","lnapprox":"⪉","lne":"⪇","lnE":"≨","lneq":"⪇","lneqq":"≨","lnsim":"⋦","loang":"⟬","loarr":"⇽","lobrk":"⟦","longleftarrow":"⟵","LongLeftArrow":"⟵","Longleftarrow":"⟸","longleftrightarrow":"⟷","LongLeftRightArrow":"⟷","Longleftrightarrow":"⟺","longmapsto":"⟼","longrightarrow":"⟶","LongRightArrow":"⟶","Longrightarrow":"⟹","looparrowleft":"↫","looparrowright":"↬","lopar":"⦅","Lopf":"𝕃","lopf":"𝕝","loplus":"⨭","lotimes":"⨴","lowast":"∗","lowbar":"_","LowerLeftArrow":"↙","LowerRightArrow":"↘","loz":"◊","lozenge":"◊","lozf":"⧫","lpar":"(","lparlt":"⦓","lrarr":"⇆","lrcorner":"⌟","lrhar":"⇋","lrhard":"⥭","lrm":"‎","lrtri":"⊿","lsaquo":"‹","lscr":"𝓁","Lscr":"ℒ","lsh":"↰","Lsh":"↰","lsim":"≲","lsime":"⪍","lsimg":"⪏","lsqb":"[","lsquo":"‘","lsquor":"‚","Lstrok":"Ł","lstrok":"ł","ltcc":"⪦","ltcir":"⩹","lt":"<","LT":"<","Lt":"≪","ltdot":"⋖","lthree":"⋋","ltimes":"⋉","ltlarr":"⥶","ltquest":"⩻","ltri":"◃","ltrie":"⊴","ltrif":"◂","ltrPar":"⦖","lurdshar":"⥊","luruhar":"⥦","lvertneqq":"≨︀","lvnE":"≨︀","macr":"¯","male":"♂","malt":"✠","maltese":"✠","Map":"⤅","map":"↦","mapsto":"↦","mapstodown":"↧","mapstoleft":"↤","mapstoup":"↥","marker":"▮","mcomma":"⨩","Mcy":"М","mcy":"м","mdash":"—","mDDot":"∺","measuredangle":"∡","MediumSpace":" ","Mellintrf":"ℳ","Mfr":"𝔐","mfr":"𝔪","mho":"℧","micro":"µ","midast":"*","midcir":"⫰","mid":"∣","middot":"·","minusb":"⊟","minus":"−","minusd":"∸","minusdu":"⨪","MinusPlus":"∓","mlcp":"⫛","mldr":"…","mnplus":"∓","models":"⊧","Mopf":"𝕄","mopf":"𝕞","mp":"∓","mscr":"𝓂","Mscr":"ℳ","mstpos":"∾","Mu":"Μ","mu":"μ","multimap":"⊸","mumap":"⊸","nabla":"∇","Nacute":"Ń","nacute":"ń","nang":"∠⃒","nap":"≉","napE":"⩰̸","napid":"≋̸","napos":"ŉ","napprox":"≉","natural":"♮","naturals":"ℕ","natur":"♮","nbsp":" ","nbump":"≎̸","nbumpe":"≏̸","ncap":"⩃","Ncaron":"Ň","ncaron":"ň","Ncedil":"Ņ","ncedil":"ņ","ncong":"≇","ncongdot":"⩭̸","ncup":"⩂","Ncy":"Н","ncy":"н","ndash":"–","nearhk":"⤤","nearr":"↗","neArr":"⇗","nearrow":"↗","ne":"≠","nedot":"≐̸","NegativeMediumSpace":"​","NegativeThickSpace":"​","NegativeThinSpace":"​","NegativeVeryThinSpace":"​","nequiv":"≢","nesear":"⤨","nesim":"≂̸","NestedGreaterGreater":"≫","NestedLessLess":"≪","NewLine":"\\n","nexist":"∄","nexists":"∄","Nfr":"𝔑","nfr":"𝔫","ngE":"≧̸","nge":"≱","ngeq":"≱","ngeqq":"≧̸","ngeqslant":"⩾̸","nges":"⩾̸","nGg":"⋙̸","ngsim":"≵","nGt":"≫⃒","ngt":"≯","ngtr":"≯","nGtv":"≫̸","nharr":"↮","nhArr":"⇎","nhpar":"⫲","ni":"∋","nis":"⋼","nisd":"⋺","niv":"∋","NJcy":"Њ","njcy":"њ","nlarr":"↚","nlArr":"⇍","nldr":"‥","nlE":"≦̸","nle":"≰","nleftarrow":"↚","nLeftarrow":"⇍","nleftrightarrow":"↮","nLeftrightarrow":"⇎","nleq":"≰","nleqq":"≦̸","nleqslant":"⩽̸","nles":"⩽̸","nless":"≮","nLl":"⋘̸","nlsim":"≴","nLt":"≪⃒","nlt":"≮","nltri":"⋪","nltrie":"⋬","nLtv":"≪̸","nmid":"∤","NoBreak":"⁠","NonBreakingSpace":" ","nopf":"𝕟","Nopf":"ℕ","Not":"⫬","not":"¬","NotCongruent":"≢","NotCupCap":"≭","NotDoubleVerticalBar":"∦","NotElement":"∉","NotEqual":"≠","NotEqualTilde":"≂̸","NotExists":"∄","NotGreater":"≯","NotGreaterEqual":"≱","NotGreaterFullEqual":"≧̸","NotGreaterGreater":"≫̸","NotGreaterLess":"≹","NotGreaterSlantEqual":"⩾̸","NotGreaterTilde":"≵","NotHumpDownHump":"≎̸","NotHumpEqual":"≏̸","notin":"∉","notindot":"⋵̸","notinE":"⋹̸","notinva":"∉","notinvb":"⋷","notinvc":"⋶","NotLeftTriangleBar":"⧏̸","NotLeftTriangle":"⋪","NotLeftTriangleEqual":"⋬","NotLess":"≮","NotLessEqual":"≰","NotLessGreater":"≸","NotLessLess":"≪̸","NotLessSlantEqual":"⩽̸","NotLessTilde":"≴","NotNestedGreaterGreater":"⪢̸","NotNestedLessLess":"⪡̸","notni":"∌","notniva":"∌","notnivb":"⋾","notnivc":"⋽","NotPrecedes":"⊀","NotPrecedesEqual":"⪯̸","NotPrecedesSlantEqual":"⋠","NotReverseElement":"∌","NotRightTriangleBar":"⧐̸","NotRightTriangle":"⋫","NotRightTriangleEqual":"⋭","NotSquareSubset":"⊏̸","NotSquareSubsetEqual":"⋢","NotSquareSuperset":"⊐̸","NotSquareSupersetEqual":"⋣","NotSubset":"⊂⃒","NotSubsetEqual":"⊈","NotSucceeds":"⊁","NotSucceedsEqual":"⪰̸","NotSucceedsSlantEqual":"⋡","NotSucceedsTilde":"≿̸","NotSuperset":"⊃⃒","NotSupersetEqual":"⊉","NotTilde":"≁","NotTildeEqual":"≄","NotTildeFullEqual":"≇","NotTildeTilde":"≉","NotVerticalBar":"∤","nparallel":"∦","npar":"∦","nparsl":"⫽⃥","npart":"∂̸","npolint":"⨔","npr":"⊀","nprcue":"⋠","nprec":"⊀","npreceq":"⪯̸","npre":"⪯̸","nrarrc":"⤳̸","nrarr":"↛","nrArr":"⇏","nrarrw":"↝̸","nrightarrow":"↛","nRightarrow":"⇏","nrtri":"⋫","nrtrie":"⋭","nsc":"⊁","nsccue":"⋡","nsce":"⪰̸","Nscr":"𝒩","nscr":"𝓃","nshortmid":"∤","nshortparallel":"∦","nsim":"≁","nsime":"≄","nsimeq":"≄","nsmid":"∤","nspar":"∦","nsqsube":"⋢","nsqsupe":"⋣","nsub":"⊄","nsubE":"⫅̸","nsube":"⊈","nsubset":"⊂⃒","nsubseteq":"⊈","nsubseteqq":"⫅̸","nsucc":"⊁","nsucceq":"⪰̸","nsup":"⊅","nsupE":"⫆̸","nsupe":"⊉","nsupset":"⊃⃒","nsupseteq":"⊉","nsupseteqq":"⫆̸","ntgl":"≹","Ntilde":"Ñ","ntilde":"ñ","ntlg":"≸","ntriangleleft":"⋪","ntrianglelefteq":"⋬","ntriangleright":"⋫","ntrianglerighteq":"⋭","Nu":"Ν","nu":"ν","num":"#","numero":"№","numsp":" ","nvap":"≍⃒","nvdash":"⊬","nvDash":"⊭","nVdash":"⊮","nVDash":"⊯","nvge":"≥⃒","nvgt":">⃒","nvHarr":"⤄","nvinfin":"⧞","nvlArr":"⤂","nvle":"≤⃒","nvlt":"<⃒","nvltrie":"⊴⃒","nvrArr":"⤃","nvrtrie":"⊵⃒","nvsim":"∼⃒","nwarhk":"⤣","nwarr":"↖","nwArr":"⇖","nwarrow":"↖","nwnear":"⤧","Oacute":"Ó","oacute":"ó","oast":"⊛","Ocirc":"Ô","ocirc":"ô","ocir":"⊚","Ocy":"О","ocy":"о","odash":"⊝","Odblac":"Ő","odblac":"ő","odiv":"⨸","odot":"⊙","odsold":"⦼","OElig":"Œ","oelig":"œ","ofcir":"⦿","Ofr":"𝔒","ofr":"𝔬","ogon":"˛","Ograve":"Ò","ograve":"ò","ogt":"⧁","ohbar":"⦵","ohm":"Ω","oint":"∮","olarr":"↺","olcir":"⦾","olcross":"⦻","oline":"‾","olt":"⧀","Omacr":"Ō","omacr":"ō","Omega":"Ω","omega":"ω","Omicron":"Ο","omicron":"ο","omid":"⦶","ominus":"⊖","Oopf":"𝕆","oopf":"𝕠","opar":"⦷","OpenCurlyDoubleQuote":"“","OpenCurlyQuote":"‘","operp":"⦹","oplus":"⊕","orarr":"↻","Or":"⩔","or":"∨","ord":"⩝","order":"ℴ","orderof":"ℴ","ordf":"ª","ordm":"º","origof":"⊶","oror":"⩖","orslope":"⩗","orv":"⩛","oS":"Ⓢ","Oscr":"𝒪","oscr":"ℴ","Oslash":"Ø","oslash":"ø","osol":"⊘","Otilde":"Õ","otilde":"õ","otimesas":"⨶","Otimes":"⨷","otimes":"⊗","Ouml":"Ö","ouml":"ö","ovbar":"⌽","OverBar":"‾","OverBrace":"⏞","OverBracket":"⎴","OverParenthesis":"⏜","para":"¶","parallel":"∥","par":"∥","parsim":"⫳","parsl":"⫽","part":"∂","PartialD":"∂","Pcy":"П","pcy":"п","percnt":"%","period":".","permil":"‰","perp":"⊥","pertenk":"‱","Pfr":"𝔓","pfr":"𝔭","Phi":"Φ","phi":"φ","phiv":"ϕ","phmmat":"ℳ","phone":"☎","Pi":"Π","pi":"π","pitchfork":"⋔","piv":"ϖ","planck":"ℏ","planckh":"ℎ","plankv":"ℏ","plusacir":"⨣","plusb":"⊞","pluscir":"⨢","plus":"+","plusdo":"∔","plusdu":"⨥","pluse":"⩲","PlusMinus":"±","plusmn":"±","plussim":"⨦","plustwo":"⨧","pm":"±","Poincareplane":"ℌ","pointint":"⨕","popf":"𝕡","Popf":"ℙ","pound":"£","prap":"⪷","Pr":"⪻","pr":"≺","prcue":"≼","precapprox":"⪷","prec":"≺","preccurlyeq":"≼","Precedes":"≺","PrecedesEqual":"⪯","PrecedesSlantEqual":"≼","PrecedesTilde":"≾","preceq":"⪯","precnapprox":"⪹","precneqq":"⪵","precnsim":"⋨","pre":"⪯","prE":"⪳","precsim":"≾","prime":"′","Prime":"″","primes":"ℙ","prnap":"⪹","prnE":"⪵","prnsim":"⋨","prod":"∏","Product":"∏","profalar":"⌮","profline":"⌒","profsurf":"⌓","prop":"∝","Proportional":"∝","Proportion":"∷","propto":"∝","prsim":"≾","prurel":"⊰","Pscr":"𝒫","pscr":"𝓅","Psi":"Ψ","psi":"ψ","puncsp":" ","Qfr":"𝔔","qfr":"𝔮","qint":"⨌","qopf":"𝕢","Qopf":"ℚ","qprime":"⁗","Qscr":"𝒬","qscr":"𝓆","quaternions":"ℍ","quatint":"⨖","quest":"?","questeq":"≟","quot":"\\"","QUOT":"\\"","rAarr":"⇛","race":"∽̱","Racute":"Ŕ","racute":"ŕ","radic":"√","raemptyv":"⦳","rang":"⟩","Rang":"⟫","rangd":"⦒","range":"⦥","rangle":"⟩","raquo":"»","rarrap":"⥵","rarrb":"⇥","rarrbfs":"⤠","rarrc":"⤳","rarr":"→","Rarr":"↠","rArr":"⇒","rarrfs":"⤞","rarrhk":"↪","rarrlp":"↬","rarrpl":"⥅","rarrsim":"⥴","Rarrtl":"⤖","rarrtl":"↣","rarrw":"↝","ratail":"⤚","rAtail":"⤜","ratio":"∶","rationals":"ℚ","rbarr":"⤍","rBarr":"⤏","RBarr":"⤐","rbbrk":"❳","rbrace":"}","rbrack":"]","rbrke":"⦌","rbrksld":"⦎","rbrkslu":"⦐","Rcaron":"Ř","rcaron":"ř","Rcedil":"Ŗ","rcedil":"ŗ","rceil":"⌉","rcub":"}","Rcy":"Р","rcy":"р","rdca":"⤷","rdldhar":"⥩","rdquo":"”","rdquor":"”","rdsh":"↳","real":"ℜ","realine":"ℛ","realpart":"ℜ","reals":"ℝ","Re":"ℜ","rect":"▭","reg":"®","REG":"®","ReverseElement":"∋","ReverseEquilibrium":"⇋","ReverseUpEquilibrium":"⥯","rfisht":"⥽","rfloor":"⌋","rfr":"𝔯","Rfr":"ℜ","rHar":"⥤","rhard":"⇁","rharu":"⇀","rharul":"⥬","Rho":"Ρ","rho":"ρ","rhov":"ϱ","RightAngleBracket":"⟩","RightArrowBar":"⇥","rightarrow":"→","RightArrow":"→","Rightarrow":"⇒","RightArrowLeftArrow":"⇄","rightarrowtail":"↣","RightCeiling":"⌉","RightDoubleBracket":"⟧","RightDownTeeVector":"⥝","RightDownVectorBar":"⥕","RightDownVector":"⇂","RightFloor":"⌋","rightharpoondown":"⇁","rightharpoonup":"⇀","rightleftarrows":"⇄","rightleftharpoons":"⇌","rightrightarrows":"⇉","rightsquigarrow":"↝","RightTeeArrow":"↦","RightTee":"⊢","RightTeeVector":"⥛","rightthreetimes":"⋌","RightTriangleBar":"⧐","RightTriangle":"⊳","RightTriangleEqual":"⊵","RightUpDownVector":"⥏","RightUpTeeVector":"⥜","RightUpVectorBar":"⥔","RightUpVector":"↾","RightVectorBar":"⥓","RightVector":"⇀","ring":"˚","risingdotseq":"≓","rlarr":"⇄","rlhar":"⇌","rlm":"‏","rmoustache":"⎱","rmoust":"⎱","rnmid":"⫮","roang":"⟭","roarr":"⇾","robrk":"⟧","ropar":"⦆","ropf":"𝕣","Ropf":"ℝ","roplus":"⨮","rotimes":"⨵","RoundImplies":"⥰","rpar":")","rpargt":"⦔","rppolint":"⨒","rrarr":"⇉","Rrightarrow":"⇛","rsaquo":"›","rscr":"𝓇","Rscr":"ℛ","rsh":"↱","Rsh":"↱","rsqb":"]","rsquo":"’","rsquor":"’","rthree":"⋌","rtimes":"⋊","rtri":"▹","rtrie":"⊵","rtrif":"▸","rtriltri":"⧎","RuleDelayed":"⧴","ruluhar":"⥨","rx":"℞","Sacute":"Ś","sacute":"ś","sbquo":"‚","scap":"⪸","Scaron":"Š","scaron":"š","Sc":"⪼","sc":"≻","sccue":"≽","sce":"⪰","scE":"⪴","Scedil":"Ş","scedil":"ş","Scirc":"Ŝ","scirc":"ŝ","scnap":"⪺","scnE":"⪶","scnsim":"⋩","scpolint":"⨓","scsim":"≿","Scy":"С","scy":"с","sdotb":"⊡","sdot":"⋅","sdote":"⩦","searhk":"⤥","searr":"↘","seArr":"⇘","searrow":"↘","sect":"§","semi":";","seswar":"⤩","setminus":"∖","setmn":"∖","sext":"✶","Sfr":"𝔖","sfr":"𝔰","sfrown":"⌢","sharp":"♯","SHCHcy":"Щ","shchcy":"щ","SHcy":"Ш","shcy":"ш","ShortDownArrow":"↓","ShortLeftArrow":"←","shortmid":"∣","shortparallel":"∥","ShortRightArrow":"→","ShortUpArrow":"↑","shy":"­","Sigma":"Σ","sigma":"σ","sigmaf":"ς","sigmav":"ς","sim":"∼","simdot":"⩪","sime":"≃","simeq":"≃","simg":"⪞","simgE":"⪠","siml":"⪝","simlE":"⪟","simne":"≆","simplus":"⨤","simrarr":"⥲","slarr":"←","SmallCircle":"∘","smallsetminus":"∖","smashp":"⨳","smeparsl":"⧤","smid":"∣","smile":"⌣","smt":"⪪","smte":"⪬","smtes":"⪬︀","SOFTcy":"Ь","softcy":"ь","solbar":"⌿","solb":"⧄","sol":"/","Sopf":"𝕊","sopf":"𝕤","spades":"♠","spadesuit":"♠","spar":"∥","sqcap":"⊓","sqcaps":"⊓︀","sqcup":"⊔","sqcups":"⊔︀","Sqrt":"√","sqsub":"⊏","sqsube":"⊑","sqsubset":"⊏","sqsubseteq":"⊑","sqsup":"⊐","sqsupe":"⊒","sqsupset":"⊐","sqsupseteq":"⊒","square":"□","Square":"□","SquareIntersection":"⊓","SquareSubset":"⊏","SquareSubsetEqual":"⊑","SquareSuperset":"⊐","SquareSupersetEqual":"⊒","SquareUnion":"⊔","squarf":"▪","squ":"□","squf":"▪","srarr":"→","Sscr":"𝒮","sscr":"𝓈","ssetmn":"∖","ssmile":"⌣","sstarf":"⋆","Star":"⋆","star":"☆","starf":"★","straightepsilon":"ϵ","straightphi":"ϕ","strns":"¯","sub":"⊂","Sub":"⋐","subdot":"⪽","subE":"⫅","sube":"⊆","subedot":"⫃","submult":"⫁","subnE":"⫋","subne":"⊊","subplus":"⪿","subrarr":"⥹","subset":"⊂","Subset":"⋐","subseteq":"⊆","subseteqq":"⫅","SubsetEqual":"⊆","subsetneq":"⊊","subsetneqq":"⫋","subsim":"⫇","subsub":"⫕","subsup":"⫓","succapprox":"⪸","succ":"≻","succcurlyeq":"≽","Succeeds":"≻","SucceedsEqual":"⪰","SucceedsSlantEqual":"≽","SucceedsTilde":"≿","succeq":"⪰","succnapprox":"⪺","succneqq":"⪶","succnsim":"⋩","succsim":"≿","SuchThat":"∋","sum":"∑","Sum":"∑","sung":"♪","sup1":"¹","sup2":"²","sup3":"³","sup":"⊃","Sup":"⋑","supdot":"⪾","supdsub":"⫘","supE":"⫆","supe":"⊇","supedot":"⫄","Superset":"⊃","SupersetEqual":"⊇","suphsol":"⟉","suphsub":"⫗","suplarr":"⥻","supmult":"⫂","supnE":"⫌","supne":"⊋","supplus":"⫀","supset":"⊃","Supset":"⋑","supseteq":"⊇","supseteqq":"⫆","supsetneq":"⊋","supsetneqq":"⫌","supsim":"⫈","supsub":"⫔","supsup":"⫖","swarhk":"⤦","swarr":"↙","swArr":"⇙","swarrow":"↙","swnwar":"⤪","szlig":"ß","Tab":"\\t","target":"⌖","Tau":"Τ","tau":"τ","tbrk":"⎴","Tcaron":"Ť","tcaron":"ť","Tcedil":"Ţ","tcedil":"ţ","Tcy":"Т","tcy":"т","tdot":"⃛","telrec":"⌕","Tfr":"𝔗","tfr":"𝔱","there4":"∴","therefore":"∴","Therefore":"∴","Theta":"Θ","theta":"θ","thetasym":"ϑ","thetav":"ϑ","thickapprox":"≈","thicksim":"∼","ThickSpace":"  ","ThinSpace":" ","thinsp":" ","thkap":"≈","thksim":"∼","THORN":"Þ","thorn":"þ","tilde":"˜","Tilde":"∼","TildeEqual":"≃","TildeFullEqual":"≅","TildeTilde":"≈","timesbar":"⨱","timesb":"⊠","times":"×","timesd":"⨰","tint":"∭","toea":"⤨","topbot":"⌶","topcir":"⫱","top":"⊤","Topf":"𝕋","topf":"𝕥","topfork":"⫚","tosa":"⤩","tprime":"‴","trade":"™","TRADE":"™","triangle":"▵","triangledown":"▿","triangleleft":"◃","trianglelefteq":"⊴","triangleq":"≜","triangleright":"▹","trianglerighteq":"⊵","tridot":"◬","trie":"≜","triminus":"⨺","TripleDot":"⃛","triplus":"⨹","trisb":"⧍","tritime":"⨻","trpezium":"⏢","Tscr":"𝒯","tscr":"𝓉","TScy":"Ц","tscy":"ц","TSHcy":"Ћ","tshcy":"ћ","Tstrok":"Ŧ","tstrok":"ŧ","twixt":"≬","twoheadleftarrow":"↞","twoheadrightarrow":"↠","Uacute":"Ú","uacute":"ú","uarr":"↑","Uarr":"↟","uArr":"⇑","Uarrocir":"⥉","Ubrcy":"Ў","ubrcy":"ў","Ubreve":"Ŭ","ubreve":"ŭ","Ucirc":"Û","ucirc":"û","Ucy":"У","ucy":"у","udarr":"⇅","Udblac":"Ű","udblac":"ű","udhar":"⥮","ufisht":"⥾","Ufr":"𝔘","ufr":"𝔲","Ugrave":"Ù","ugrave":"ù","uHar":"⥣","uharl":"↿","uharr":"↾","uhblk":"▀","ulcorn":"⌜","ulcorner":"⌜","ulcrop":"⌏","ultri":"◸","Umacr":"Ū","umacr":"ū","uml":"¨","UnderBar":"_","UnderBrace":"⏟","UnderBracket":"⎵","UnderParenthesis":"⏝","Union":"⋃","UnionPlus":"⊎","Uogon":"Ų","uogon":"ų","Uopf":"𝕌","uopf":"𝕦","UpArrowBar":"⤒","uparrow":"↑","UpArrow":"↑","Uparrow":"⇑","UpArrowDownArrow":"⇅","updownarrow":"↕","UpDownArrow":"↕","Updownarrow":"⇕","UpEquilibrium":"⥮","upharpoonleft":"↿","upharpoonright":"↾","uplus":"⊎","UpperLeftArrow":"↖","UpperRightArrow":"↗","upsi":"υ","Upsi":"ϒ","upsih":"ϒ","Upsilon":"Υ","upsilon":"υ","UpTeeArrow":"↥","UpTee":"⊥","upuparrows":"⇈","urcorn":"⌝","urcorner":"⌝","urcrop":"⌎","Uring":"Ů","uring":"ů","urtri":"◹","Uscr":"𝒰","uscr":"𝓊","utdot":"⋰","Utilde":"Ũ","utilde":"ũ","utri":"▵","utrif":"▴","uuarr":"⇈","Uuml":"Ü","uuml":"ü","uwangle":"⦧","vangrt":"⦜","varepsilon":"ϵ","varkappa":"ϰ","varnothing":"∅","varphi":"ϕ","varpi":"ϖ","varpropto":"∝","varr":"↕","vArr":"⇕","varrho":"ϱ","varsigma":"ς","varsubsetneq":"⊊︀","varsubsetneqq":"⫋︀","varsupsetneq":"⊋︀","varsupsetneqq":"⫌︀","vartheta":"ϑ","vartriangleleft":"⊲","vartriangleright":"⊳","vBar":"⫨","Vbar":"⫫","vBarv":"⫩","Vcy":"В","vcy":"в","vdash":"⊢","vDash":"⊨","Vdash":"⊩","VDash":"⊫","Vdashl":"⫦","veebar":"⊻","vee":"∨","Vee":"⋁","veeeq":"≚","vellip":"⋮","verbar":"|","Verbar":"‖","vert":"|","Vert":"‖","VerticalBar":"∣","VerticalLine":"|","VerticalSeparator":"❘","VerticalTilde":"≀","VeryThinSpace":" ","Vfr":"𝔙","vfr":"𝔳","vltri":"⊲","vnsub":"⊂⃒","vnsup":"⊃⃒","Vopf":"𝕍","vopf":"𝕧","vprop":"∝","vrtri":"⊳","Vscr":"𝒱","vscr":"𝓋","vsubnE":"⫋︀","vsubne":"⊊︀","vsupnE":"⫌︀","vsupne":"⊋︀","Vvdash":"⊪","vzigzag":"⦚","Wcirc":"Ŵ","wcirc":"ŵ","wedbar":"⩟","wedge":"∧","Wedge":"⋀","wedgeq":"≙","weierp":"℘","Wfr":"𝔚","wfr":"𝔴","Wopf":"𝕎","wopf":"𝕨","wp":"℘","wr":"≀","wreath":"≀","Wscr":"𝒲","wscr":"𝓌","xcap":"⋂","xcirc":"◯","xcup":"⋃","xdtri":"▽","Xfr":"𝔛","xfr":"𝔵","xharr":"⟷","xhArr":"⟺","Xi":"Ξ","xi":"ξ","xlarr":"⟵","xlArr":"⟸","xmap":"⟼","xnis":"⋻","xodot":"⨀","Xopf":"𝕏","xopf":"𝕩","xoplus":"⨁","xotime":"⨂","xrarr":"⟶","xrArr":"⟹","Xscr":"𝒳","xscr":"𝓍","xsqcup":"⨆","xuplus":"⨄","xutri":"△","xvee":"⋁","xwedge":"⋀","Yacute":"Ý","yacute":"ý","YAcy":"Я","yacy":"я","Ycirc":"Ŷ","ycirc":"ŷ","Ycy":"Ы","ycy":"ы","yen":"¥","Yfr":"𝔜","yfr":"𝔶","YIcy":"Ї","yicy":"ї","Yopf":"𝕐","yopf":"𝕪","Yscr":"𝒴","yscr":"𝓎","YUcy":"Ю","yucy":"ю","yuml":"ÿ","Yuml":"Ÿ","Zacute":"Ź","zacute":"ź","Zcaron":"Ž","zcaron":"ž","Zcy":"З","zcy":"з","Zdot":"Ż","zdot":"ż","zeetrf":"ℨ","ZeroWidthSpace":"​","Zeta":"Ζ","zeta":"ζ","zfr":"𝔷","Zfr":"ℨ","ZHcy":"Ж","zhcy":"ж","zigrarr":"⇝","zopf":"𝕫","Zopf":"ℤ","Zscr":"𝒵","zscr":"𝓏","zwj":"‍","zwnj":"‌"}')},function(t){t.exports=JSON.parse('{"amp":"&","apos":"\'","gt":">","lt":"<","quot":"\\""}')},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t,e,r){return o.default[t.type](t,e,r)};var n,a=r(48),o=(n=a)&&n.__esModule?n:{default:n}},function(t,e,r){var n=r(23),a={input:!0,option:!0,optgroup:!0,select:!0,button:!0,datalist:!0,textarea:!0},o={tr:{tr:!0,th:!0,td:!0},th:{th:!0},td:{thead:!0,th:!0,td:!0},body:{head:!0,link:!0,script:!0},li:{li:!0},p:{p:!0},h1:{p:!0},h2:{p:!0},h3:{p:!0},h4:{p:!0},h5:{p:!0},h6:{p:!0},select:a,input:a,output:a,button:a,datalist:a,textarea:a,option:{option:!0},optgroup:{optgroup:!0}},i={__proto__:null,area:!0,base:!0,basefont:!0,br:!0,col:!0,command:!0,embed:!0,frame:!0,hr:!0,img:!0,input:!0,isindex:!0,keygen:!0,link:!0,meta:!0,param:!0,source:!0,track:!0,wbr:!0},s={__proto__:null,math:!0,svg:!0},c={__proto__:null,mi:!0,mo:!0,mn:!0,ms:!0,mtext:!0,"annotation-xml":!0,foreignObject:!0,desc:!0,title:!0},l=/\s|\//;function u(t,e){this._options=e||{},this._cbs=t||{},this._tagname="",this._attribname="",this._attribvalue="",this._attribs=null,this._stack=[],this._foreignContext=[],this.startIndex=0,this.endIndex=null,this._lowerCaseTagNames="lowerCaseTags"in this._options?!!this._options.lowerCaseTags:!this._options.xmlMode,this._lowerCaseAttributeNames="lowerCaseAttributeNames"in this._options?!!this._options.lowerCaseAttributeNames:!this._options.xmlMode,this._options.Tokenizer&&(n=this._options.Tokenizer),this._tokenizer=new n(this._options,this),this._cbs.onparserinit&&this._cbs.onparserinit(this)}r(16)(u,r(50).EventEmitter),u.prototype._updatePosition=function(t){null===this.endIndex?this._tokenizer._sectionStart<=t?this.startIndex=0:this.startIndex=this._tokenizer._sectionStart-t:this.startIndex=this.endIndex+1,this.endIndex=this._tokenizer.getAbsoluteIndex()},u.prototype.ontext=function(t){this._updatePosition(1),this.endIndex--,this._cbs.ontext&&this._cbs.ontext(t)},u.prototype.onopentagname=function(t){if(this._lowerCaseTagNames&&(t=t.toLowerCase()),this._tagname=t,!this._options.xmlMode&&t in o)for(var e;(e=this._stack[this._stack.length-1])in o[t];this.onclosetag(e));!this._options.xmlMode&&t in i||(this._stack.push(t),t in s?this._foreignContext.push(!0):t in c&&this._foreignContext.push(!1)),this._cbs.onopentagname&&this._cbs.onopentagname(t),this._cbs.onopentag&&(this._attribs={})},u.prototype.onopentagend=function(){this._updatePosition(1),this._attribs&&(this._cbs.onopentag&&this._cbs.onopentag(this._tagname,this._attribs),this._attribs=null),!this._options.xmlMode&&this._cbs.onclosetag&&this._tagname in i&&this._cbs.onclosetag(this._tagname),this._tagname=""},u.prototype.onclosetag=function(t){if(this._updatePosition(1),this._lowerCaseTagNames&&(t=t.toLowerCase()),(t in s||t in c)&&this._foreignContext.pop(),!this._stack.length||t in i&&!this._options.xmlMode)this._options.xmlMode||"br"!==t&&"p"!==t||(this.onopentagname(t),this._closeCurrentTag());else{var e=this._stack.lastIndexOf(t);if(-1!==e)if(this._cbs.onclosetag)for(e=this._stack.length-e;e--;)this._cbs.onclosetag(this._stack.pop());else this._stack.length=e;else"p"!==t||this._options.xmlMode||(this.onopentagname(t),this._closeCurrentTag())}},u.prototype.onselfclosingtag=function(){this._options.xmlMode||this._options.recognizeSelfClosing||this._foreignContext[this._foreignContext.length-1]?this._closeCurrentTag():this.onopentagend()},u.prototype._closeCurrentTag=function(){var t=this._tagname;this.onopentagend(),this._stack[this._stack.length-1]===t&&(this._cbs.onclosetag&&this._cbs.onclosetag(t),this._stack.pop())},u.prototype.onattribname=function(t){this._lowerCaseAttributeNames&&(t=t.toLowerCase()),this._attribname=t},u.prototype.onattribdata=function(t){this._attribvalue+=t},u.prototype.onattribend=function(){this._cbs.onattribute&&this._cbs.onattribute(this._attribname,this._attribvalue),this._attribs&&!Object.prototype.hasOwnProperty.call(this._attribs,this._attribname)&&(this._attribs[this._attribname]=this._attribvalue),this._attribname="",this._attribvalue=""},u.prototype._getInstructionName=function(t){var e=t.search(l),r=e<0?t:t.substr(0,e);return this._lowerCaseTagNames&&(r=r.toLowerCase()),r},u.prototype.ondeclaration=function(t){if(this._cbs.onprocessinginstruction){var e=this._getInstructionName(t);this._cbs.onprocessinginstruction("!"+e,"!"+t)}},u.prototype.onprocessinginstruction=function(t){if(this._cbs.onprocessinginstruction){var e=this._getInstructionName(t);this._cbs.onprocessinginstruction("?"+e,"?"+t)}},u.prototype.oncomment=function(t){this._updatePosition(4),this._cbs.oncomment&&this._cbs.oncomment(t),this._cbs.oncommentend&&this._cbs.oncommentend()},u.prototype.oncdata=function(t){this._updatePosition(1),this._options.xmlMode||this._options.recognizeCDATA?(this._cbs.oncdatastart&&this._cbs.oncdatastart(),this._cbs.ontext&&this._cbs.ontext(t),this._cbs.oncdataend&&this._cbs.oncdataend()):this.oncomment("[CDATA["+t+"]]")},u.prototype.onerror=function(t){this._cbs.onerror&&this._cbs.onerror(t)},u.prototype.onend=function(){if(this._cbs.onclosetag)for(var t=this._stack.length;t>0;this._cbs.onclosetag(this._stack[--t]));this._cbs.onend&&this._cbs.onend()},u.prototype.reset=function(){this._cbs.onreset&&this._cbs.onreset(),this._tokenizer.reset(),this._tagname="",this._attribname="",this._attribs=null,this._stack=[],this._cbs.onparserinit&&this._cbs.onparserinit(this)},u.prototype.parseComplete=function(t){this.reset(),this.end(t)},u.prototype.write=function(t){this._tokenizer.write(t)},u.prototype.end=function(t){this._tokenizer.end(t)},u.prototype.pause=function(){this._tokenizer.pause()},u.prototype.resume=function(){this._tokenizer.resume()},u.prototype.parseChunk=u.prototype.write,u.prototype.done=u.prototype.end,t.exports=u},function(t,e,r){t.exports=gt;var n=r(24),a=r(19),o=r(25),i=r(20),s=0,c=s++,l=s++,u=s++,p=s++,d=s++,h=s++,f=s++,m=s++,g=s++,v=s++,b=s++,y=s++,w=s++,_=s++,O=s++,j=s++,E=s++,C=s++,x=s++,k=s++,z=s++,N=s++,S=s++,M=s++,A=s++,D=s++,T=s++,P=s++,B=s++,H=s++,R=s++,L=s++,I=s++,V=s++,q=s++,U=s++,Q=s++,F=s++,Y=s++,G=s++,X=s++,J=s++,W=s++,Z=s++,K=s++,$=s++,tt=s++,et=s++,rt=s++,nt=s++,at=s++,ot=s++,it=s++,st=s++,ct=s++,lt=0,ut=lt++,pt=lt++,dt=lt++;function ht(t){return" "===t||"\n"===t||"\t"===t||"\f"===t||"\r"===t}function ft(t,e,r){var n=t.toLowerCase();return t===n?function(t){t===n?this._state=e:(this._state=r,this._index--)}:function(a){a===n||a===t?this._state=e:(this._state=r,this._index--)}}function mt(t,e){var r=t.toLowerCase();return function(n){n===r||n===t?this._state=e:(this._state=u,this._index--)}}function gt(t,e){this._state=c,this._buffer="",this._sectionStart=0,this._index=0,this._bufferOffset=0,this._baseState=c,this._special=ut,this._cbs=e,this._running=!0,this._ended=!1,this._xmlMode=!(!t||!t.xmlMode),this._decodeEntities=!(!t||!t.decodeEntities)}gt.prototype._stateText=function(t){"<"===t?(this._index>this._sectionStart&&this._cbs.ontext(this._getSection()),this._state=l,this._sectionStart=this._index):this._decodeEntities&&this._special===ut&&"&"===t&&(this._index>this._sectionStart&&this._cbs.ontext(this._getSection()),this._baseState=c,this._state=at,this._sectionStart=this._index)},gt.prototype._stateBeforeTagName=function(t){"/"===t?this._state=d:"<"===t?(this._cbs.ontext(this._getSection()),this._sectionStart=this._index):">"===t||this._special!==ut||ht(t)?this._state=c:"!"===t?(this._state=O,this._sectionStart=this._index+1):"?"===t?(this._state=E,this._sectionStart=this._index+1):(this._state=this._xmlMode||"s"!==t&&"S"!==t?u:R,this._sectionStart=this._index)},gt.prototype._stateInTagName=function(t){("/"===t||">"===t||ht(t))&&(this._emitToken("onopentagname"),this._state=m,this._index--)},gt.prototype._stateBeforeCloseingTagName=function(t){ht(t)||(">"===t?this._state=c:this._special!==ut?"s"===t||"S"===t?this._state=L:(this._state=c,this._index--):(this._state=h,this._sectionStart=this._index))},gt.prototype._stateInCloseingTagName=function(t){(">"===t||ht(t))&&(this._emitToken("onclosetag"),this._state=f,this._index--)},gt.prototype._stateAfterCloseingTagName=function(t){">"===t&&(this._state=c,this._sectionStart=this._index+1)},gt.prototype._stateBeforeAttributeName=function(t){">"===t?(this._cbs.onopentagend(),this._state=c,this._sectionStart=this._index+1):"/"===t?this._state=p:ht(t)||(this._state=g,this._sectionStart=this._index)},gt.prototype._stateInSelfClosingTag=function(t){">"===t?(this._cbs.onselfclosingtag(),this._state=c,this._sectionStart=this._index+1):ht(t)||(this._state=m,this._index--)},gt.prototype._stateInAttributeName=function(t){("="===t||"/"===t||">"===t||ht(t))&&(this._cbs.onattribname(this._getSection()),this._sectionStart=-1,this._state=v,this._index--)},gt.prototype._stateAfterAttributeName=function(t){"="===t?this._state=b:"/"===t||">"===t?(this._cbs.onattribend(),this._state=m,this._index--):ht(t)||(this._cbs.onattribend(),this._state=g,this._sectionStart=this._index)},gt.prototype._stateBeforeAttributeValue=function(t){'"'===t?(this._state=y,this._sectionStart=this._index+1):"'"===t?(this._state=w,this._sectionStart=this._index+1):ht(t)||(this._state=_,this._sectionStart=this._index,this._index--)},gt.prototype._stateInAttributeValueDoubleQuotes=function(t){'"'===t?(this._emitToken("onattribdata"),this._cbs.onattribend(),this._state=m):this._decodeEntities&&"&"===t&&(this._emitToken("onattribdata"),this._baseState=this._state,this._state=at,this._sectionStart=this._index)},gt.prototype._stateInAttributeValueSingleQuotes=function(t){"'"===t?(this._emitToken("onattribdata"),this._cbs.onattribend(),this._state=m):this._decodeEntities&&"&"===t&&(this._emitToken("onattribdata"),this._baseState=this._state,this._state=at,this._sectionStart=this._index)},gt.prototype._stateInAttributeValueNoQuotes=function(t){ht(t)||">"===t?(this._emitToken("onattribdata"),this._cbs.onattribend(),this._state=m,this._index--):this._decodeEntities&&"&"===t&&(this._emitToken("onattribdata"),this._baseState=this._state,this._state=at,this._sectionStart=this._index)},gt.prototype._stateBeforeDeclaration=function(t){this._state="["===t?N:"-"===t?C:j},gt.prototype._stateInDeclaration=function(t){">"===t&&(this._cbs.ondeclaration(this._getSection()),this._state=c,this._sectionStart=this._index+1)},gt.prototype._stateInProcessingInstruction=function(t){">"===t&&(this._cbs.onprocessinginstruction(this._getSection()),this._state=c,this._sectionStart=this._index+1)},gt.prototype._stateBeforeComment=function(t){"-"===t?(this._state=x,this._sectionStart=this._index+1):this._state=j},gt.prototype._stateInComment=function(t){"-"===t&&(this._state=k)},gt.prototype._stateAfterComment1=function(t){this._state="-"===t?z:x},gt.prototype._stateAfterComment2=function(t){">"===t?(this._cbs.oncomment(this._buffer.substring(this._sectionStart,this._index-2)),this._state=c,this._sectionStart=this._index+1):"-"!==t&&(this._state=x)},gt.prototype._stateBeforeCdata1=ft("C",S,j),gt.prototype._stateBeforeCdata2=ft("D",M,j),gt.prototype._stateBeforeCdata3=ft("A",A,j),gt.prototype._stateBeforeCdata4=ft("T",D,j),gt.prototype._stateBeforeCdata5=ft("A",T,j),gt.prototype._stateBeforeCdata6=function(t){"["===t?(this._state=P,this._sectionStart=this._index+1):(this._state=j,this._index--)},gt.prototype._stateInCdata=function(t){"]"===t&&(this._state=B)},gt.prototype._stateAfterCdata1=function(t){this._state="]"===t?H:P},gt.prototype._stateAfterCdata2=function(t){">"===t?(this._cbs.oncdata(this._buffer.substring(this._sectionStart,this._index-2)),this._state=c,this._sectionStart=this._index+1):"]"!==t&&(this._state=P)},gt.prototype._stateBeforeSpecial=function(t){"c"===t||"C"===t?this._state=I:"t"===t||"T"===t?this._state=W:(this._state=u,this._index--)},gt.prototype._stateBeforeSpecialEnd=function(t){this._special!==pt||"c"!==t&&"C"!==t?this._special!==dt||"t"!==t&&"T"!==t?this._state=c:this._state=tt:this._state=F},gt.prototype._stateBeforeScript1=mt("R",V),gt.prototype._stateBeforeScript2=mt("I",q),gt.prototype._stateBeforeScript3=mt("P",U),gt.prototype._stateBeforeScript4=mt("T",Q),gt.prototype._stateBeforeScript5=function(t){("/"===t||">"===t||ht(t))&&(this._special=pt),this._state=u,this._index--},gt.prototype._stateAfterScript1=ft("R",Y,c),gt.prototype._stateAfterScript2=ft("I",G,c),gt.prototype._stateAfterScript3=ft("P",X,c),gt.prototype._stateAfterScript4=ft("T",J,c),gt.prototype._stateAfterScript5=function(t){">"===t||ht(t)?(this._special=ut,this._state=h,this._sectionStart=this._index-6,this._index--):this._state=c},gt.prototype._stateBeforeStyle1=mt("Y",Z),gt.prototype._stateBeforeStyle2=mt("L",K),gt.prototype._stateBeforeStyle3=mt("E",$),gt.prototype._stateBeforeStyle4=function(t){("/"===t||">"===t||ht(t))&&(this._special=dt),this._state=u,this._index--},gt.prototype._stateAfterStyle1=ft("Y",et,c),gt.prototype._stateAfterStyle2=ft("L",rt,c),gt.prototype._stateAfterStyle3=ft("E",nt,c),gt.prototype._stateAfterStyle4=function(t){">"===t||ht(t)?(this._special=ut,this._state=h,this._sectionStart=this._index-5,this._index--):this._state=c},gt.prototype._stateBeforeEntity=ft("#",ot,it),gt.prototype._stateBeforeNumericEntity=ft("X",ct,st),gt.prototype._parseNamedEntityStrict=function(){if(this._sectionStart+1<this._index){var t=this._buffer.substring(this._sectionStart+1,this._index),e=this._xmlMode?i:a;e.hasOwnProperty(t)&&(this._emitPartial(e[t]),this._sectionStart=this._index+1)}},gt.prototype._parseLegacyEntity=function(){var t=this._sectionStart+1,e=this._index-t;for(e>6&&(e=6);e>=2;){var r=this._buffer.substr(t,e);if(o.hasOwnProperty(r))return this._emitPartial(o[r]),void(this._sectionStart+=e+1);e--}},gt.prototype._stateInNamedEntity=function(t){";"===t?(this._parseNamedEntityStrict(),this._sectionStart+1<this._index&&!this._xmlMode&&this._parseLegacyEntity(),this._state=this._baseState):(t<"a"||t>"z")&&(t<"A"||t>"Z")&&(t<"0"||t>"9")&&(this._xmlMode||this._sectionStart+1===this._index||(this._baseState!==c?"="!==t&&this._parseNamedEntityStrict():this._parseLegacyEntity()),this._state=this._baseState,this._index--)},gt.prototype._decodeNumericEntity=function(t,e){var r=this._sectionStart+t;if(r!==this._index){var a=this._buffer.substring(r,this._index),o=parseInt(a,e);this._emitPartial(n(o)),this._sectionStart=this._index}else this._sectionStart--;this._state=this._baseState},gt.prototype._stateInNumericEntity=function(t){";"===t?(this._decodeNumericEntity(2,10),this._sectionStart++):(t<"0"||t>"9")&&(this._xmlMode?this._state=this._baseState:this._decodeNumericEntity(2,10),this._index--)},gt.prototype._stateInHexEntity=function(t){";"===t?(this._decodeNumericEntity(3,16),this._sectionStart++):(t<"a"||t>"f")&&(t<"A"||t>"F")&&(t<"0"||t>"9")&&(this._xmlMode?this._state=this._baseState:this._decodeNumericEntity(3,16),this._index--)},gt.prototype._cleanup=function(){this._sectionStart<0?(this._buffer="",this._bufferOffset+=this._index,this._index=0):this._running&&(this._state===c?(this._sectionStart!==this._index&&this._cbs.ontext(this._buffer.substr(this._sectionStart)),this._buffer="",this._bufferOffset+=this._index,this._index=0):this._sectionStart===this._index?(this._buffer="",this._bufferOffset+=this._index,this._index=0):(this._buffer=this._buffer.substr(this._sectionStart),this._index-=this._sectionStart,this._bufferOffset+=this._sectionStart),this._sectionStart=0)},gt.prototype.write=function(t){this._ended&&this._cbs.onerror(Error(".write() after done!")),this._buffer+=t,this._parse()},gt.prototype._parse=function(){for(;this._index<this._buffer.length&&this._running;){var t=this._buffer.charAt(this._index);this._state===c?this._stateText(t):this._state===l?this._stateBeforeTagName(t):this._state===u?this._stateInTagName(t):this._state===d?this._stateBeforeCloseingTagName(t):this._state===h?this._stateInCloseingTagName(t):this._state===f?this._stateAfterCloseingTagName(t):this._state===p?this._stateInSelfClosingTag(t):this._state===m?this._stateBeforeAttributeName(t):this._state===g?this._stateInAttributeName(t):this._state===v?this._stateAfterAttributeName(t):this._state===b?this._stateBeforeAttributeValue(t):this._state===y?this._stateInAttributeValueDoubleQuotes(t):this._state===w?this._stateInAttributeValueSingleQuotes(t):this._state===_?this._stateInAttributeValueNoQuotes(t):this._state===O?this._stateBeforeDeclaration(t):this._state===j?this._stateInDeclaration(t):this._state===E?this._stateInProcessingInstruction(t):this._state===C?this._stateBeforeComment(t):this._state===x?this._stateInComment(t):this._state===k?this._stateAfterComment1(t):this._state===z?this._stateAfterComment2(t):this._state===N?this._stateBeforeCdata1(t):this._state===S?this._stateBeforeCdata2(t):this._state===M?this._stateBeforeCdata3(t):this._state===A?this._stateBeforeCdata4(t):this._state===D?this._stateBeforeCdata5(t):this._state===T?this._stateBeforeCdata6(t):this._state===P?this._stateInCdata(t):this._state===B?this._stateAfterCdata1(t):this._state===H?this._stateAfterCdata2(t):this._state===R?this._stateBeforeSpecial(t):this._state===L?this._stateBeforeSpecialEnd(t):this._state===I?this._stateBeforeScript1(t):this._state===V?this._stateBeforeScript2(t):this._state===q?this._stateBeforeScript3(t):this._state===U?this._stateBeforeScript4(t):this._state===Q?this._stateBeforeScript5(t):this._state===F?this._stateAfterScript1(t):this._state===Y?this._stateAfterScript2(t):this._state===G?this._stateAfterScript3(t):this._state===X?this._stateAfterScript4(t):this._state===J?this._stateAfterScript5(t):this._state===W?this._stateBeforeStyle1(t):this._state===Z?this._stateBeforeStyle2(t):this._state===K?this._stateBeforeStyle3(t):this._state===$?this._stateBeforeStyle4(t):this._state===tt?this._stateAfterStyle1(t):this._state===et?this._stateAfterStyle2(t):this._state===rt?this._stateAfterStyle3(t):this._state===nt?this._stateAfterStyle4(t):this._state===at?this._stateBeforeEntity(t):this._state===ot?this._stateBeforeNumericEntity(t):this._state===it?this._stateInNamedEntity(t):this._state===st?this._stateInNumericEntity(t):this._state===ct?this._stateInHexEntity(t):this._cbs.onerror(Error("unknown _state"),this._state),this._index++}this._cleanup()},gt.prototype.pause=function(){this._running=!1},gt.prototype.resume=function(){this._running=!0,this._index<this._buffer.length&&this._parse(),this._ended&&this._finish()},gt.prototype.end=function(t){this._ended&&this._cbs.onerror(Error(".end() after done!")),t&&this.write(t),this._ended=!0,this._running&&this._finish()},gt.prototype._finish=function(){this._sectionStart<this._index&&this._handleTrailingData(),this._cbs.onend()},gt.prototype._handleTrailingData=function(){var t=this._buffer.substr(this._sectionStart);this._state===P||this._state===B||this._state===H?this._cbs.oncdata(t):this._state===x||this._state===k||this._state===z?this._cbs.oncomment(t):this._state!==it||this._xmlMode?this._state!==st||this._xmlMode?this._state!==ct||this._xmlMode?this._state!==u&&this._state!==m&&this._state!==b&&this._state!==v&&this._state!==g&&this._state!==w&&this._state!==y&&this._state!==_&&this._state!==h&&this._cbs.ontext(t):(this._decodeNumericEntity(3,16),this._sectionStart<this._index&&(this._state=this._baseState,this._handleTrailingData())):(this._decodeNumericEntity(2,10),this._sectionStart<this._index&&(this._state=this._baseState,this._handleTrailingData())):(this._parseLegacyEntity(),this._sectionStart<this._index&&(this._state=this._baseState,this._handleTrailingData()))},gt.prototype.reset=function(){gt.call(this,{xmlMode:this._xmlMode,decodeEntities:this._decodeEntities},this._cbs)},gt.prototype.getAbsoluteIndex=function(){return this._bufferOffset+this._index},gt.prototype._getSection=function(){return this._buffer.substring(this._sectionStart,this._index)},gt.prototype._emitToken=function(t){this._cbs[t](this._getSection()),this._sectionStart=-1},gt.prototype._emitPartial=function(t){this._baseState!==c?this._cbs.onattribdata(t):this._cbs.ontext(t)}},function(t,e,r){var n=r(49);t.exports=function(t){if(t>=55296&&t<=57343||t>1114111)return"�";t in n&&(t=n[t]);var e="";t>65535&&(t-=65536,e+=String.fromCharCode(t>>>10&1023|55296),t=56320|1023&t);return e+=String.fromCharCode(t)}},function(t){t.exports=JSON.parse('{"Aacute":"Á","aacute":"á","Acirc":"Â","acirc":"â","acute":"´","AElig":"Æ","aelig":"æ","Agrave":"À","agrave":"à","amp":"&","AMP":"&","Aring":"Å","aring":"å","Atilde":"Ã","atilde":"ã","Auml":"Ä","auml":"ä","brvbar":"¦","Ccedil":"Ç","ccedil":"ç","cedil":"¸","cent":"¢","copy":"©","COPY":"©","curren":"¤","deg":"°","divide":"÷","Eacute":"É","eacute":"é","Ecirc":"Ê","ecirc":"ê","Egrave":"È","egrave":"è","ETH":"Ð","eth":"ð","Euml":"Ë","euml":"ë","frac12":"½","frac14":"¼","frac34":"¾","gt":">","GT":">","Iacute":"Í","iacute":"í","Icirc":"Î","icirc":"î","iexcl":"¡","Igrave":"Ì","igrave":"ì","iquest":"¿","Iuml":"Ï","iuml":"ï","laquo":"«","lt":"<","LT":"<","macr":"¯","micro":"µ","middot":"·","nbsp":" ","not":"¬","Ntilde":"Ñ","ntilde":"ñ","Oacute":"Ó","oacute":"ó","Ocirc":"Ô","ocirc":"ô","Ograve":"Ò","ograve":"ò","ordf":"ª","ordm":"º","Oslash":"Ø","oslash":"ø","Otilde":"Õ","otilde":"õ","Ouml":"Ö","ouml":"ö","para":"¶","plusmn":"±","pound":"£","quot":"\\"","QUOT":"\\"","raquo":"»","reg":"®","REG":"®","sect":"§","shy":"­","sup1":"¹","sup2":"²","sup3":"³","szlig":"ß","THORN":"Þ","thorn":"þ","times":"×","Uacute":"Ú","uacute":"ú","Ucirc":"Û","ucirc":"û","Ugrave":"Ù","ugrave":"ù","uml":"¨","Uuml":"Ü","uuml":"ü","Yacute":"Ý","yacute":"ý","yen":"¥","yuml":"ÿ"}')},function(t,e,r){var n=r(11),a=/\s+/g,o=r(27),i=r(51);function s(t,e,r){"object"==typeof t?(r=e,e=t,t=null):"function"==typeof e&&(r=e,e=c),this._callback=t,this._options=e||c,this._elementCB=r,this.dom=[],this._done=!1,this._tagStack=[],this._parser=this._parser||null}var c={normalizeWhitespace:!1,withStartIndices:!1,withEndIndices:!1};s.prototype.onparserinit=function(t){this._parser=t},s.prototype.onreset=function(){s.call(this,this._callback,this._options,this._elementCB)},s.prototype.onend=function(){this._done||(this._done=!0,this._parser=null,this._handleCallback(null))},s.prototype._handleCallback=s.prototype.onerror=function(t){if("function"==typeof this._callback)this._callback(t,this.dom);else if(t)throw t},s.prototype.onclosetag=function(){var t=this._tagStack.pop();this._options.withEndIndices&&t&&(t.endIndex=this._parser.endIndex),this._elementCB&&this._elementCB(t)},s.prototype._createDomElement=function(t){if(!this._options.withDomLvl1)return t;var e;for(var r in e="tag"===t.type?Object.create(i):Object.create(o),t)t.hasOwnProperty(r)&&(e[r]=t[r]);return e},s.prototype._addDomElement=function(t){var e=this._tagStack[this._tagStack.length-1],r=e?e.children:this.dom,n=r[r.length-1];t.next=null,this._options.withStartIndices&&(t.startIndex=this._parser.startIndex),this._options.withEndIndices&&(t.endIndex=this._parser.endIndex),n?(t.prev=n,n.next=t):t.prev=null,r.push(t),t.parent=e||null},s.prototype.onopentag=function(t,e){var r={type:"script"===t?n.Script:"style"===t?n.Style:n.Tag,name:t,attribs:e,children:[]},a=this._createDomElement(r);this._addDomElement(a),this._tagStack.push(a)},s.prototype.ontext=function(t){var e,r=this._options.normalizeWhitespace||this._options.ignoreWhitespace;if(!this._tagStack.length&&this.dom.length&&(e=this.dom[this.dom.length-1]).type===n.Text)r?e.data=(e.data+t).replace(a," "):e.data+=t;else if(this._tagStack.length&&(e=this._tagStack[this._tagStack.length-1])&&(e=e.children[e.children.length-1])&&e.type===n.Text)r?e.data=(e.data+t).replace(a," "):e.data+=t;else{r&&(t=t.replace(a," "));var o=this._createDomElement({data:t,type:n.Text});this._addDomElement(o)}},s.prototype.oncomment=function(t){var e=this._tagStack[this._tagStack.length-1];if(e&&e.type===n.Comment)e.data+=t;else{var r={data:t,type:n.Comment},a=this._createDomElement(r);this._addDomElement(a),this._tagStack.push(a)}},s.prototype.oncdatastart=function(){var t={children:[{data:"",type:n.Text}],type:n.CDATA},e=this._createDomElement(t);this._addDomElement(e),this._tagStack.push(e)},s.prototype.oncommentend=s.prototype.oncdataend=function(){this._tagStack.pop()},s.prototype.onprocessinginstruction=function(t,e){var r=this._createDomElement({name:t,data:e,type:n.Directive});this._addDomElement(r)},t.exports=s},function(t,e){var r=t.exports={get firstChild(){var t=this.children;return t&&t[0]||null},get lastChild(){var t=this.children;return t&&t[t.length-1]||null},get nodeType(){return a[this.type]||a.element}},n={tagName:"name",childNodes:"children",parentNode:"parent",previousSibling:"prev",nextSibling:"next",nodeValue:"data"},a={element:1,text:3,cdata:4,comment:8};Object.keys(n).forEach((function(t){var e=n[t];Object.defineProperty(r,t,{get:function(){return this[e]||null},set:function(t){return this[e]=t,t}})}))},function(t,e,r){var n=t.exports;[r(53),r(58),r(59),r(60),r(61),r(62)].forEach((function(t){Object.keys(t).forEach((function(e){n[e]=t[e].bind(n)}))}))},function(t,e,r){t.exports=s;var n=r(22),a=r(64).Writable,o=r(65).StringDecoder,i=r(30).Buffer;function s(t,e){var r=this._parser=new n(t,e),i=this._decoder=new o;a.call(this,{decodeStrings:!1}),this.once("finish",(function(){r.end(i.end())}))}r(16)(s,a),s.prototype._write=function(t,e,r){t instanceof i&&(t=this._decoder.write(t)),this._parser.write(t),r()}},function(t,e,r){"use strict";(function(t){
/*!
 * The buffer module from node.js, for the browser.
 *
 * @author   Feross Aboukhadijeh <http://feross.org>
 * @license  MIT
 */
var n=r(67),a=r(68),o=r(69);function i(){return c.TYPED_ARRAY_SUPPORT?2147483647:1073741823}function s(t,e){if(i()<e)throw new RangeError("Invalid typed array length");return c.TYPED_ARRAY_SUPPORT?(t=new Uint8Array(e)).__proto__=c.prototype:(null===t&&(t=new c(e)),t.length=e),t}function c(t,e,r){if(!(c.TYPED_ARRAY_SUPPORT||this instanceof c))return new c(t,e,r);if("number"==typeof t){if("string"==typeof e)throw new Error("If encoding is specified then the first argument must be a string");return p(this,t)}return l(this,t,e,r)}function l(t,e,r,n){if("number"==typeof e)throw new TypeError('"value" argument must not be a number');return"undefined"!=typeof ArrayBuffer&&e instanceof ArrayBuffer?function(t,e,r,n){if(e.byteLength,r<0||e.byteLength<r)throw new RangeError("'offset' is out of bounds");if(e.byteLength<r+(n||0))throw new RangeError("'length' is out of bounds");e=void 0===r&&void 0===n?new Uint8Array(e):void 0===n?new Uint8Array(e,r):new Uint8Array(e,r,n);c.TYPED_ARRAY_SUPPORT?(t=e).__proto__=c.prototype:t=d(t,e);return t}(t,e,r,n):"string"==typeof e?function(t,e,r){"string"==typeof r&&""!==r||(r="utf8");if(!c.isEncoding(r))throw new TypeError('"encoding" must be a valid string encoding');var n=0|f(e,r),a=(t=s(t,n)).write(e,r);a!==n&&(t=t.slice(0,a));return t}(t,e,r):function(t,e){if(c.isBuffer(e)){var r=0|h(e.length);return 0===(t=s(t,r)).length||e.copy(t,0,0,r),t}if(e){if("undefined"!=typeof ArrayBuffer&&e.buffer instanceof ArrayBuffer||"length"in e)return"number"!=typeof e.length||(n=e.length)!=n?s(t,0):d(t,e);if("Buffer"===e.type&&o(e.data))return d(t,e.data)}var n;throw new TypeError("First argument must be a string, Buffer, ArrayBuffer, Array, or array-like object.")}(t,e)}function u(t){if("number"!=typeof t)throw new TypeError('"size" argument must be a number');if(t<0)throw new RangeError('"size" argument must not be negative')}function p(t,e){if(u(e),t=s(t,e<0?0:0|h(e)),!c.TYPED_ARRAY_SUPPORT)for(var r=0;r<e;++r)t[r]=0;return t}function d(t,e){var r=e.length<0?0:0|h(e.length);t=s(t,r);for(var n=0;n<r;n+=1)t[n]=255&e[n];return t}function h(t){if(t>=i())throw new RangeError("Attempt to allocate Buffer larger than maximum size: 0x"+i().toString(16)+" bytes");return 0|t}function f(t,e){if(c.isBuffer(t))return t.length;if("undefined"!=typeof ArrayBuffer&&"function"==typeof ArrayBuffer.isView&&(ArrayBuffer.isView(t)||t instanceof ArrayBuffer))return t.byteLength;"string"!=typeof t&&(t=""+t);var r=t.length;if(0===r)return 0;for(var n=!1;;)switch(e){case"ascii":case"latin1":case"binary":return r;case"utf8":case"utf-8":case void 0:return I(t).length;case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return 2*r;case"hex":return r>>>1;case"base64":return V(t).length;default:if(n)return I(t).length;e=(""+e).toLowerCase(),n=!0}}function m(t,e,r){var n=!1;if((void 0===e||e<0)&&(e=0),e>this.length)return"";if((void 0===r||r>this.length)&&(r=this.length),r<=0)return"";if((r>>>=0)<=(e>>>=0))return"";for(t||(t="utf8");;)switch(t){case"hex":return N(this,e,r);case"utf8":case"utf-8":return x(this,e,r);case"ascii":return k(this,e,r);case"latin1":case"binary":return z(this,e,r);case"base64":return C(this,e,r);case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return S(this,e,r);default:if(n)throw new TypeError("Unknown encoding: "+t);t=(t+"").toLowerCase(),n=!0}}function g(t,e,r){var n=t[e];t[e]=t[r],t[r]=n}function v(t,e,r,n,a){if(0===t.length)return-1;if("string"==typeof r?(n=r,r=0):r>2147483647?r=2147483647:r<-2147483648&&(r=-2147483648),r=+r,isNaN(r)&&(r=a?0:t.length-1),r<0&&(r=t.length+r),r>=t.length){if(a)return-1;r=t.length-1}else if(r<0){if(!a)return-1;r=0}if("string"==typeof e&&(e=c.from(e,n)),c.isBuffer(e))return 0===e.length?-1:b(t,e,r,n,a);if("number"==typeof e)return e&=255,c.TYPED_ARRAY_SUPPORT&&"function"==typeof Uint8Array.prototype.indexOf?a?Uint8Array.prototype.indexOf.call(t,e,r):Uint8Array.prototype.lastIndexOf.call(t,e,r):b(t,[e],r,n,a);throw new TypeError("val must be string, number or Buffer")}function b(t,e,r,n,a){var o,i=1,s=t.length,c=e.length;if(void 0!==n&&("ucs2"===(n=String(n).toLowerCase())||"ucs-2"===n||"utf16le"===n||"utf-16le"===n)){if(t.length<2||e.length<2)return-1;i=2,s/=2,c/=2,r/=2}function l(t,e){return 1===i?t[e]:t.readUInt16BE(e*i)}if(a){var u=-1;for(o=r;o<s;o++)if(l(t,o)===l(e,-1===u?0:o-u)){if(-1===u&&(u=o),o-u+1===c)return u*i}else-1!==u&&(o-=o-u),u=-1}else for(r+c>s&&(r=s-c),o=r;o>=0;o--){for(var p=!0,d=0;d<c;d++)if(l(t,o+d)!==l(e,d)){p=!1;break}if(p)return o}return-1}function y(t,e,r,n){r=Number(r)||0;var a=t.length-r;n?(n=Number(n))>a&&(n=a):n=a;var o=e.length;if(o%2!=0)throw new TypeError("Invalid hex string");n>o/2&&(n=o/2);for(var i=0;i<n;++i){var s=parseInt(e.substr(2*i,2),16);if(isNaN(s))return i;t[r+i]=s}return i}function w(t,e,r,n){return q(I(e,t.length-r),t,r,n)}function _(t,e,r,n){return q(function(t){for(var e=[],r=0;r<t.length;++r)e.push(255&t.charCodeAt(r));return e}(e),t,r,n)}function O(t,e,r,n){return _(t,e,r,n)}function j(t,e,r,n){return q(V(e),t,r,n)}function E(t,e,r,n){return q(function(t,e){for(var r,n,a,o=[],i=0;i<t.length&&!((e-=2)<0);++i)r=t.charCodeAt(i),n=r>>8,a=r%256,o.push(a),o.push(n);return o}(e,t.length-r),t,r,n)}function C(t,e,r){return 0===e&&r===t.length?n.fromByteArray(t):n.fromByteArray(t.slice(e,r))}function x(t,e,r){r=Math.min(t.length,r);for(var n=[],a=e;a<r;){var o,i,s,c,l=t[a],u=null,p=l>239?4:l>223?3:l>191?2:1;if(a+p<=r)switch(p){case 1:l<128&&(u=l);break;case 2:128==(192&(o=t[a+1]))&&(c=(31&l)<<6|63&o)>127&&(u=c);break;case 3:o=t[a+1],i=t[a+2],128==(192&o)&&128==(192&i)&&(c=(15&l)<<12|(63&o)<<6|63&i)>2047&&(c<55296||c>57343)&&(u=c);break;case 4:o=t[a+1],i=t[a+2],s=t[a+3],128==(192&o)&&128==(192&i)&&128==(192&s)&&(c=(15&l)<<18|(63&o)<<12|(63&i)<<6|63&s)>65535&&c<1114112&&(u=c)}null===u?(u=65533,p=1):u>65535&&(u-=65536,n.push(u>>>10&1023|55296),u=56320|1023&u),n.push(u),a+=p}return function(t){var e=t.length;if(e<=4096)return String.fromCharCode.apply(String,t);var r="",n=0;for(;n<e;)r+=String.fromCharCode.apply(String,t.slice(n,n+=4096));return r}(n)}e.Buffer=c,e.SlowBuffer=function(t){+t!=t&&(t=0);return c.alloc(+t)},e.INSPECT_MAX_BYTES=50,c.TYPED_ARRAY_SUPPORT=void 0!==t.TYPED_ARRAY_SUPPORT?t.TYPED_ARRAY_SUPPORT:function(){try{var t=new Uint8Array(1);return t.__proto__={__proto__:Uint8Array.prototype,foo:function(){return 42}},42===t.foo()&&"function"==typeof t.subarray&&0===t.subarray(1,1).byteLength}catch(t){return!1}}(),e.kMaxLength=i(),c.poolSize=8192,c._augment=function(t){return t.__proto__=c.prototype,t},c.from=function(t,e,r){return l(null,t,e,r)},c.TYPED_ARRAY_SUPPORT&&(c.prototype.__proto__=Uint8Array.prototype,c.__proto__=Uint8Array,"undefined"!=typeof Symbol&&Symbol.species&&c[Symbol.species]===c&&Object.defineProperty(c,Symbol.species,{value:null,configurable:!0})),c.alloc=function(t,e,r){return function(t,e,r,n){return u(e),e<=0?s(t,e):void 0!==r?"string"==typeof n?s(t,e).fill(r,n):s(t,e).fill(r):s(t,e)}(null,t,e,r)},c.allocUnsafe=function(t){return p(null,t)},c.allocUnsafeSlow=function(t){return p(null,t)},c.isBuffer=function(t){return!(null==t||!t._isBuffer)},c.compare=function(t,e){if(!c.isBuffer(t)||!c.isBuffer(e))throw new TypeError("Arguments must be Buffers");if(t===e)return 0;for(var r=t.length,n=e.length,a=0,o=Math.min(r,n);a<o;++a)if(t[a]!==e[a]){r=t[a],n=e[a];break}return r<n?-1:n<r?1:0},c.isEncoding=function(t){switch(String(t).toLowerCase()){case"hex":case"utf8":case"utf-8":case"ascii":case"latin1":case"binary":case"base64":case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return!0;default:return!1}},c.concat=function(t,e){if(!o(t))throw new TypeError('"list" argument must be an Array of Buffers');if(0===t.length)return c.alloc(0);var r;if(void 0===e)for(e=0,r=0;r<t.length;++r)e+=t[r].length;var n=c.allocUnsafe(e),a=0;for(r=0;r<t.length;++r){var i=t[r];if(!c.isBuffer(i))throw new TypeError('"list" argument must be an Array of Buffers');i.copy(n,a),a+=i.length}return n},c.byteLength=f,c.prototype._isBuffer=!0,c.prototype.swap16=function(){var t=this.length;if(t%2!=0)throw new RangeError("Buffer size must be a multiple of 16-bits");for(var e=0;e<t;e+=2)g(this,e,e+1);return this},c.prototype.swap32=function(){var t=this.length;if(t%4!=0)throw new RangeError("Buffer size must be a multiple of 32-bits");for(var e=0;e<t;e+=4)g(this,e,e+3),g(this,e+1,e+2);return this},c.prototype.swap64=function(){var t=this.length;if(t%8!=0)throw new RangeError("Buffer size must be a multiple of 64-bits");for(var e=0;e<t;e+=8)g(this,e,e+7),g(this,e+1,e+6),g(this,e+2,e+5),g(this,e+3,e+4);return this},c.prototype.toString=function(){var t=0|this.length;return 0===t?"":0===arguments.length?x(this,0,t):m.apply(this,arguments)},c.prototype.equals=function(t){if(!c.isBuffer(t))throw new TypeError("Argument must be a Buffer");return this===t||0===c.compare(this,t)},c.prototype.inspect=function(){var t="",r=e.INSPECT_MAX_BYTES;return this.length>0&&(t=this.toString("hex",0,r).match(/.{2}/g).join(" "),this.length>r&&(t+=" ... ")),"<Buffer "+t+">"},c.prototype.compare=function(t,e,r,n,a){if(!c.isBuffer(t))throw new TypeError("Argument must be a Buffer");if(void 0===e&&(e=0),void 0===r&&(r=t?t.length:0),void 0===n&&(n=0),void 0===a&&(a=this.length),e<0||r>t.length||n<0||a>this.length)throw new RangeError("out of range index");if(n>=a&&e>=r)return 0;if(n>=a)return-1;if(e>=r)return 1;if(this===t)return 0;for(var o=(a>>>=0)-(n>>>=0),i=(r>>>=0)-(e>>>=0),s=Math.min(o,i),l=this.slice(n,a),u=t.slice(e,r),p=0;p<s;++p)if(l[p]!==u[p]){o=l[p],i=u[p];break}return o<i?-1:i<o?1:0},c.prototype.includes=function(t,e,r){return-1!==this.indexOf(t,e,r)},c.prototype.indexOf=function(t,e,r){return v(this,t,e,r,!0)},c.prototype.lastIndexOf=function(t,e,r){return v(this,t,e,r,!1)},c.prototype.write=function(t,e,r,n){if(void 0===e)n="utf8",r=this.length,e=0;else if(void 0===r&&"string"==typeof e)n=e,r=this.length,e=0;else{if(!isFinite(e))throw new Error("Buffer.write(string, encoding, offset[, length]) is no longer supported");e|=0,isFinite(r)?(r|=0,void 0===n&&(n="utf8")):(n=r,r=void 0)}var a=this.length-e;if((void 0===r||r>a)&&(r=a),t.length>0&&(r<0||e<0)||e>this.length)throw new RangeError("Attempt to write outside buffer bounds");n||(n="utf8");for(var o=!1;;)switch(n){case"hex":return y(this,t,e,r);case"utf8":case"utf-8":return w(this,t,e,r);case"ascii":return _(this,t,e,r);case"latin1":case"binary":return O(this,t,e,r);case"base64":return j(this,t,e,r);case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return E(this,t,e,r);default:if(o)throw new TypeError("Unknown encoding: "+n);n=(""+n).toLowerCase(),o=!0}},c.prototype.toJSON=function(){return{type:"Buffer",data:Array.prototype.slice.call(this._arr||this,0)}};function k(t,e,r){var n="";r=Math.min(t.length,r);for(var a=e;a<r;++a)n+=String.fromCharCode(127&t[a]);return n}function z(t,e,r){var n="";r=Math.min(t.length,r);for(var a=e;a<r;++a)n+=String.fromCharCode(t[a]);return n}function N(t,e,r){var n=t.length;(!e||e<0)&&(e=0),(!r||r<0||r>n)&&(r=n);for(var a="",o=e;o<r;++o)a+=L(t[o]);return a}function S(t,e,r){for(var n=t.slice(e,r),a="",o=0;o<n.length;o+=2)a+=String.fromCharCode(n[o]+256*n[o+1]);return a}function M(t,e,r){if(t%1!=0||t<0)throw new RangeError("offset is not uint");if(t+e>r)throw new RangeError("Trying to access beyond buffer length")}function A(t,e,r,n,a,o){if(!c.isBuffer(t))throw new TypeError('"buffer" argument must be a Buffer instance');if(e>a||e<o)throw new RangeError('"value" argument is out of bounds');if(r+n>t.length)throw new RangeError("Index out of range")}function D(t,e,r,n){e<0&&(e=65535+e+1);for(var a=0,o=Math.min(t.length-r,2);a<o;++a)t[r+a]=(e&255<<8*(n?a:1-a))>>>8*(n?a:1-a)}function T(t,e,r,n){e<0&&(e=4294967295+e+1);for(var a=0,o=Math.min(t.length-r,4);a<o;++a)t[r+a]=e>>>8*(n?a:3-a)&255}function P(t,e,r,n,a,o){if(r+n>t.length)throw new RangeError("Index out of range");if(r<0)throw new RangeError("Index out of range")}function B(t,e,r,n,o){return o||P(t,0,r,4),a.write(t,e,r,n,23,4),r+4}function H(t,e,r,n,o){return o||P(t,0,r,8),a.write(t,e,r,n,52,8),r+8}c.prototype.slice=function(t,e){var r,n=this.length;if((t=~~t)<0?(t+=n)<0&&(t=0):t>n&&(t=n),(e=void 0===e?n:~~e)<0?(e+=n)<0&&(e=0):e>n&&(e=n),e<t&&(e=t),c.TYPED_ARRAY_SUPPORT)(r=this.subarray(t,e)).__proto__=c.prototype;else{var a=e-t;r=new c(a,void 0);for(var o=0;o<a;++o)r[o]=this[o+t]}return r},c.prototype.readUIntLE=function(t,e,r){t|=0,e|=0,r||M(t,e,this.length);for(var n=this[t],a=1,o=0;++o<e&&(a*=256);)n+=this[t+o]*a;return n},c.prototype.readUIntBE=function(t,e,r){t|=0,e|=0,r||M(t,e,this.length);for(var n=this[t+--e],a=1;e>0&&(a*=256);)n+=this[t+--e]*a;return n},c.prototype.readUInt8=function(t,e){return e||M(t,1,this.length),this[t]},c.prototype.readUInt16LE=function(t,e){return e||M(t,2,this.length),this[t]|this[t+1]<<8},c.prototype.readUInt16BE=function(t,e){return e||M(t,2,this.length),this[t]<<8|this[t+1]},c.prototype.readUInt32LE=function(t,e){return e||M(t,4,this.length),(this[t]|this[t+1]<<8|this[t+2]<<16)+16777216*this[t+3]},c.prototype.readUInt32BE=function(t,e){return e||M(t,4,this.length),16777216*this[t]+(this[t+1]<<16|this[t+2]<<8|this[t+3])},c.prototype.readIntLE=function(t,e,r){t|=0,e|=0,r||M(t,e,this.length);for(var n=this[t],a=1,o=0;++o<e&&(a*=256);)n+=this[t+o]*a;return n>=(a*=128)&&(n-=Math.pow(2,8*e)),n},c.prototype.readIntBE=function(t,e,r){t|=0,e|=0,r||M(t,e,this.length);for(var n=e,a=1,o=this[t+--n];n>0&&(a*=256);)o+=this[t+--n]*a;return o>=(a*=128)&&(o-=Math.pow(2,8*e)),o},c.prototype.readInt8=function(t,e){return e||M(t,1,this.length),128&this[t]?-1*(255-this[t]+1):this[t]},c.prototype.readInt16LE=function(t,e){e||M(t,2,this.length);var r=this[t]|this[t+1]<<8;return 32768&r?4294901760|r:r},c.prototype.readInt16BE=function(t,e){e||M(t,2,this.length);var r=this[t+1]|this[t]<<8;return 32768&r?4294901760|r:r},c.prototype.readInt32LE=function(t,e){return e||M(t,4,this.length),this[t]|this[t+1]<<8|this[t+2]<<16|this[t+3]<<24},c.prototype.readInt32BE=function(t,e){return e||M(t,4,this.length),this[t]<<24|this[t+1]<<16|this[t+2]<<8|this[t+3]},c.prototype.readFloatLE=function(t,e){return e||M(t,4,this.length),a.read(this,t,!0,23,4)},c.prototype.readFloatBE=function(t,e){return e||M(t,4,this.length),a.read(this,t,!1,23,4)},c.prototype.readDoubleLE=function(t,e){return e||M(t,8,this.length),a.read(this,t,!0,52,8)},c.prototype.readDoubleBE=function(t,e){return e||M(t,8,this.length),a.read(this,t,!1,52,8)},c.prototype.writeUIntLE=function(t,e,r,n){(t=+t,e|=0,r|=0,n)||A(this,t,e,r,Math.pow(2,8*r)-1,0);var a=1,o=0;for(this[e]=255&t;++o<r&&(a*=256);)this[e+o]=t/a&255;return e+r},c.prototype.writeUIntBE=function(t,e,r,n){(t=+t,e|=0,r|=0,n)||A(this,t,e,r,Math.pow(2,8*r)-1,0);var a=r-1,o=1;for(this[e+a]=255&t;--a>=0&&(o*=256);)this[e+a]=t/o&255;return e+r},c.prototype.writeUInt8=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,1,255,0),c.TYPED_ARRAY_SUPPORT||(t=Math.floor(t)),this[e]=255&t,e+1},c.prototype.writeUInt16LE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,2,65535,0),c.TYPED_ARRAY_SUPPORT?(this[e]=255&t,this[e+1]=t>>>8):D(this,t,e,!0),e+2},c.prototype.writeUInt16BE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,2,65535,0),c.TYPED_ARRAY_SUPPORT?(this[e]=t>>>8,this[e+1]=255&t):D(this,t,e,!1),e+2},c.prototype.writeUInt32LE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,4,4294967295,0),c.TYPED_ARRAY_SUPPORT?(this[e+3]=t>>>24,this[e+2]=t>>>16,this[e+1]=t>>>8,this[e]=255&t):T(this,t,e,!0),e+4},c.prototype.writeUInt32BE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,4,4294967295,0),c.TYPED_ARRAY_SUPPORT?(this[e]=t>>>24,this[e+1]=t>>>16,this[e+2]=t>>>8,this[e+3]=255&t):T(this,t,e,!1),e+4},c.prototype.writeIntLE=function(t,e,r,n){if(t=+t,e|=0,!n){var a=Math.pow(2,8*r-1);A(this,t,e,r,a-1,-a)}var o=0,i=1,s=0;for(this[e]=255&t;++o<r&&(i*=256);)t<0&&0===s&&0!==this[e+o-1]&&(s=1),this[e+o]=(t/i>>0)-s&255;return e+r},c.prototype.writeIntBE=function(t,e,r,n){if(t=+t,e|=0,!n){var a=Math.pow(2,8*r-1);A(this,t,e,r,a-1,-a)}var o=r-1,i=1,s=0;for(this[e+o]=255&t;--o>=0&&(i*=256);)t<0&&0===s&&0!==this[e+o+1]&&(s=1),this[e+o]=(t/i>>0)-s&255;return e+r},c.prototype.writeInt8=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,1,127,-128),c.TYPED_ARRAY_SUPPORT||(t=Math.floor(t)),t<0&&(t=255+t+1),this[e]=255&t,e+1},c.prototype.writeInt16LE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,2,32767,-32768),c.TYPED_ARRAY_SUPPORT?(this[e]=255&t,this[e+1]=t>>>8):D(this,t,e,!0),e+2},c.prototype.writeInt16BE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,2,32767,-32768),c.TYPED_ARRAY_SUPPORT?(this[e]=t>>>8,this[e+1]=255&t):D(this,t,e,!1),e+2},c.prototype.writeInt32LE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,4,2147483647,-2147483648),c.TYPED_ARRAY_SUPPORT?(this[e]=255&t,this[e+1]=t>>>8,this[e+2]=t>>>16,this[e+3]=t>>>24):T(this,t,e,!0),e+4},c.prototype.writeInt32BE=function(t,e,r){return t=+t,e|=0,r||A(this,t,e,4,2147483647,-2147483648),t<0&&(t=4294967295+t+1),c.TYPED_ARRAY_SUPPORT?(this[e]=t>>>24,this[e+1]=t>>>16,this[e+2]=t>>>8,this[e+3]=255&t):T(this,t,e,!1),e+4},c.prototype.writeFloatLE=function(t,e,r){return B(this,t,e,!0,r)},c.prototype.writeFloatBE=function(t,e,r){return B(this,t,e,!1,r)},c.prototype.writeDoubleLE=function(t,e,r){return H(this,t,e,!0,r)},c.prototype.writeDoubleBE=function(t,e,r){return H(this,t,e,!1,r)},c.prototype.copy=function(t,e,r,n){if(r||(r=0),n||0===n||(n=this.length),e>=t.length&&(e=t.length),e||(e=0),n>0&&n<r&&(n=r),n===r)return 0;if(0===t.length||0===this.length)return 0;if(e<0)throw new RangeError("targetStart out of bounds");if(r<0||r>=this.length)throw new RangeError("sourceStart out of bounds");if(n<0)throw new RangeError("sourceEnd out of bounds");n>this.length&&(n=this.length),t.length-e<n-r&&(n=t.length-e+r);var a,o=n-r;if(this===t&&r<e&&e<n)for(a=o-1;a>=0;--a)t[a+e]=this[a+r];else if(o<1e3||!c.TYPED_ARRAY_SUPPORT)for(a=0;a<o;++a)t[a+e]=this[a+r];else Uint8Array.prototype.set.call(t,this.subarray(r,r+o),e);return o},c.prototype.fill=function(t,e,r,n){if("string"==typeof t){if("string"==typeof e?(n=e,e=0,r=this.length):"string"==typeof r&&(n=r,r=this.length),1===t.length){var a=t.charCodeAt(0);a<256&&(t=a)}if(void 0!==n&&"string"!=typeof n)throw new TypeError("encoding must be a string");if("string"==typeof n&&!c.isEncoding(n))throw new TypeError("Unknown encoding: "+n)}else"number"==typeof t&&(t&=255);if(e<0||this.length<e||this.length<r)throw new RangeError("Out of range index");if(r<=e)return this;var o;if(e>>>=0,r=void 0===r?this.length:r>>>0,t||(t=0),"number"==typeof t)for(o=e;o<r;++o)this[o]=t;else{var i=c.isBuffer(t)?t:I(new c(t,n).toString()),s=i.length;for(o=0;o<r-e;++o)this[o+e]=i[o%s]}return this};var R=/[^+\/0-9A-Za-z-_]/g;function L(t){return t<16?"0"+t.toString(16):t.toString(16)}function I(t,e){var r;e=e||1/0;for(var n=t.length,a=null,o=[],i=0;i<n;++i){if((r=t.charCodeAt(i))>55295&&r<57344){if(!a){if(r>56319){(e-=3)>-1&&o.push(239,191,189);continue}if(i+1===n){(e-=3)>-1&&o.push(239,191,189);continue}a=r;continue}if(r<56320){(e-=3)>-1&&o.push(239,191,189),a=r;continue}r=65536+(a-55296<<10|r-56320)}else a&&(e-=3)>-1&&o.push(239,191,189);if(a=null,r<128){if((e-=1)<0)break;o.push(r)}else if(r<2048){if((e-=2)<0)break;o.push(r>>6|192,63&r|128)}else if(r<65536){if((e-=3)<0)break;o.push(r>>12|224,r>>6&63|128,63&r|128)}else{if(!(r<1114112))throw new Error("Invalid code point");if((e-=4)<0)break;o.push(r>>18|240,r>>12&63|128,r>>6&63|128,63&r|128)}}return o}function V(t){return n.toByteArray(function(t){if((t=function(t){return t.trim?t.trim():t.replace(/^\s+|\s+$/g,"")}(t).replace(R,"")).length<2)return"";for(;t.length%4!=0;)t+="=";return t}(t))}function q(t,e,r,n){for(var a=0;a<n&&!(a+r>=e.length||a>=t.length);++a)e[a+r]=t[a];return a}}).call(this,r(31))},function(t,e){var r;r=function(){return this}();try{r=r||new Function("return this")()}catch(t){"object"==typeof window&&(r=window)}t.exports=r},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var r=arguments[e];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(t[n]=r[n])}return t};e.default=function(t,e){var r=n({},(0,a.default)(t),{key:e});"string"==typeof r.style||r.style instanceof String?r.style=(0,o.default)(r.style):delete r.style;return r};var a=i(r(74)),o=i(r(77));function i(t){return t&&t.__esModule?t:{default:t}}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){a.hasOwnProperty(t)||(a[t]=n.test(t));return a[t]};var n=/^[a-zA-Z][a-zA-Z:_\.\-\d]*$/,a={}},function(t,e,r){var n=r(35);t.exports=function(t,e){if(t){if("string"==typeof t)return n(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?n(t,e):void 0}}},function(t,e){t.exports=function(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}},function(t,e){t.exports=function(t){var e=typeof t;return null!=t&&("object"==e||"function"==e)}},function(t,e,r){var n=r(91),a="object"==typeof self&&self&&self.Object===Object&&self,o=n||a||Function("return this")();t.exports=o},function(t,e,r){var n=r(37).Symbol;t.exports=n},function(t){t.exports=JSON.parse("{\"facebook\":\"<svg aria-labelledby='facebook' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>Facebook</title><path d='M19.5 2C21.984 2 24 4.016 24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-2.938v-9.297h3.109l.469-3.625h-3.578v-2.312c0-1.047.281-1.75 1.797-1.75L20.265 9V5.766c-.328-.047-1.469-.141-2.781-.141-2.766 0-4.672 1.687-4.672 4.781v2.672H9.687v3.625h3.125V26H4.499a4.502 4.502 0 01-4.5-4.5v-15c0-2.484 2.016-4.5 4.5-4.5h15z'></path></svg>\",\"twitter\":\"<svg aria-labelledby='twitter' xmlns='http://www.w3.org/2000/svg' width='26' height='28' viewBox='0 0 26 28'><title>twitter</title><path d='M25.312 6.375a10.85 10.85 0 01-2.531 2.609c.016.219.016.438.016.656 0 6.672-5.078 14.359-14.359 14.359-2.859 0-5.516-.828-7.75-2.266.406.047.797.063 1.219.063 2.359 0 4.531-.797 6.266-2.156a5.056 5.056 0 01-4.719-3.5c.313.047.625.078.953.078.453 0 .906-.063 1.328-.172a5.048 5.048 0 01-4.047-4.953v-.063a5.093 5.093 0 002.281.641 5.044 5.044 0 01-2.25-4.203c0-.938.25-1.797.688-2.547a14.344 14.344 0 0010.406 5.281 5.708 5.708 0 01-.125-1.156 5.045 5.045 0 015.047-5.047 5.03 5.03 0 013.687 1.594 9.943 9.943 0 003.203-1.219 5.032 5.032 0 01-2.219 2.781c1.016-.109 2-.391 2.906-.781z'></path></svg>\",\"instagram\":\"<svg aria-labelledby='instagram' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'><title>Instagram</title><path d='M21.138.242c3.767.007 3.914.038 4.65.144 1.52.219 2.795.825 3.837 1.821a6.243 6.243 0 011.349 1.848c.442.899.659 1.75.758 3.016.021.271.031 4.592.031 8.916s-.009 8.652-.03 8.924c-.098 1.245-.315 2.104-.743 2.986a6.6 6.6 0 01-4.303 3.522c-.685.177-1.304.26-2.371.31-.381.019-4.361.024-8.342.024s-7.959-.012-8.349-.029c-.921-.044-1.639-.136-2.288-.303a6.64 6.64 0 01-4.303-3.515c-.436-.904-.642-1.731-.751-3.045-.031-.373-.039-2.296-.039-8.87 0-2.215-.002-3.866 0-5.121.006-3.764.037-3.915.144-4.652.219-1.518.825-2.795 1.825-3.833a6.302 6.302 0 011.811-1.326C4.939.603 5.78.391 7.13.278 7.504.247 9.428.24 16.008.24h5.13zm-5.139 4.122c-3.159 0-3.555.014-4.796.07-1.239.057-2.084.253-2.824.541-.765.297-1.415.695-2.061 1.342S5.273 7.613 4.975 8.378c-.288.74-.485 1.586-.541 2.824-.056 1.241-.07 1.638-.07 4.798s.014 3.556.07 4.797c.057 1.239.253 2.084.541 2.824.297.765.695 1.415 1.342 2.061s1.296 1.046 2.061 1.343c.74.288 1.586.484 2.825.541 1.241.056 1.638.07 4.798.07s3.556-.014 4.797-.07c1.239-.057 2.085-.253 2.826-.541.765-.297 1.413-.696 2.06-1.343s1.045-1.296 1.343-2.061c.286-.74.482-1.586.541-2.824.056-1.241.07-1.637.07-4.797s-.015-3.557-.07-4.798c-.058-1.239-.255-2.084-.541-2.824-.298-.765-.696-1.415-1.343-2.061s-1.295-1.045-2.061-1.342c-.742-.288-1.588-.484-2.827-.541-1.241-.056-1.636-.07-4.796-.07zm-1.042 2.097h1.044c3.107 0 3.475.011 4.702.067 1.135.052 1.75.241 2.16.401.543.211.93.463 1.337.87s.659.795.871 1.338c.159.41.349 1.025.401 2.16.056 1.227.068 1.595.068 4.701s-.012 3.474-.068 4.701c-.052 1.135-.241 1.75-.401 2.16-.211.543-.463.93-.871 1.337s-.794.659-1.337.87c-.41.16-1.026.349-2.16.401-1.227.056-1.595.068-4.702.068s-3.475-.012-4.702-.068c-1.135-.052-1.75-.242-2.161-.401-.543-.211-.931-.463-1.338-.87s-.659-.794-.871-1.337c-.159-.41-.349-1.025-.401-2.16-.056-1.227-.067-1.595-.067-4.703s.011-3.474.067-4.701c.052-1.135.241-1.75.401-2.16.211-.543.463-.931.871-1.338s.795-.659 1.338-.871c.41-.16 1.026-.349 2.161-.401 1.073-.048 1.489-.063 3.658-.065v.003zm1.044 3.563a5.977 5.977 0 10.001 11.953 5.977 5.977 0 00-.001-11.953zm0 2.097a3.879 3.879 0 110 7.758 3.879 3.879 0 010-7.758zm6.211-3.728a1.396 1.396 0 100 2.792 1.396 1.396 0 000-2.792v.001z'></path></svg>\",\"vimeo\":\"<svg aria-labelledby='vimeo' xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'><title>vimeo</title><path d='M26.703 8.094c-.109 2.469-1.844 5.859-5.187 10.172C18.047 22.75 15.141 25 12.735 25c-1.484 0-2.734-1.375-3.75-4.109-.688-2.5-1.375-5.016-2.063-7.531-.75-2.734-1.578-4.094-2.453-4.094-.187 0-.844.391-1.984 1.188L1.282 8.923c1.25-1.109 2.484-2.234 3.719-3.313 1.656-1.469 2.922-2.203 3.766-2.281 1.984-.187 3.187 1.156 3.656 4.047.484 3.125.844 5.078 1.031 5.828.578 2.594 1.188 3.891 1.875 3.891.531 0 1.328-.828 2.406-2.516 1.062-1.687 1.625-2.969 1.703-3.844.141-1.453-.422-2.172-1.703-2.172-.609 0-1.234.141-1.891.406 1.25-4.094 3.641-6.078 7.172-5.969 2.609.078 3.844 1.781 3.687 5.094z'></path></svg>\",\"youtube\":\"<svg aria-labelledby='youtube' xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'><title>youtube</title><path d='M11.109 17.625l7.562-3.906-7.562-3.953v7.859zM14 4.156c5.891 0 9.797.281 9.797.281.547.063 1.75.063 2.812 1.188 0 0 .859.844 1.109 2.781.297 2.266.281 4.531.281 4.531v2.125s.016 2.266-.281 4.531c-.25 1.922-1.109 2.781-1.109 2.781-1.062 1.109-2.266 1.109-2.812 1.172 0 0-3.906.297-9.797.297-7.281-.063-9.516-.281-9.516-.281-.625-.109-2.031-.078-3.094-1.188 0 0-.859-.859-1.109-2.781C-.016 17.327 0 15.062 0 15.062v-2.125s-.016-2.266.281-4.531C.531 6.469 1.39 5.625 1.39 5.625 2.452 4.5 3.656 4.5 4.202 4.437c0 0 3.906-.281 9.797-.281z'></path></svg>\",\"github\":\"<svg aria-labelledby='github' aria-labelledby='github' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>Github</title><path d='M12 2c6.625 0 12 5.375 12 12 0 5.297-3.437 9.797-8.203 11.391-.609.109-.828-.266-.828-.578 0-.391.016-1.687.016-3.297 0-1.125-.375-1.844-.812-2.219 2.672-.297 5.484-1.313 5.484-5.922 0-1.313-.469-2.375-1.234-3.219.125-.313.531-1.531-.125-3.187-1-.313-3.297 1.234-3.297 1.234a11.28 11.28 0 00-6 0S6.704 6.656 5.704 6.969c-.656 1.656-.25 2.875-.125 3.187-.766.844-1.234 1.906-1.234 3.219 0 4.594 2.797 5.625 5.469 5.922-.344.313-.656.844-.766 1.609-.688.313-2.438.844-3.484-1-.656-1.141-1.844-1.234-1.844-1.234-1.172-.016-.078.734-.078.734.781.359 1.328 1.75 1.328 1.75.703 2.141 4.047 1.422 4.047 1.422 0 1 .016 1.937.016 2.234 0 .313-.219.688-.828.578C3.439 23.796.002 19.296.002 13.999c0-6.625 5.375-12 12-12zM4.547 19.234c.031-.063-.016-.141-.109-.187-.094-.031-.172-.016-.203.031-.031.063.016.141.109.187.078.047.172.031.203-.031zm.484.532c.063-.047.047-.156-.031-.25-.078-.078-.187-.109-.25-.047-.063.047-.047.156.031.25.078.078.187.109.25.047zm.469.703c.078-.063.078-.187 0-.297-.063-.109-.187-.156-.266-.094-.078.047-.078.172 0 .281s.203.156.266.109zm.656.656c.063-.063.031-.203-.063-.297-.109-.109-.25-.125-.313-.047-.078.063-.047.203.063.297.109.109.25.125.313.047zm.891.391c.031-.094-.063-.203-.203-.25-.125-.031-.266.016-.297.109s.063.203.203.234c.125.047.266 0 .297-.094zm.984.078c0-.109-.125-.187-.266-.172-.141 0-.25.078-.25.172 0 .109.109.187.266.172.141 0 .25-.078.25-.172zm.906-.156c-.016-.094-.141-.156-.281-.141-.141.031-.234.125-.219.234.016.094.141.156.281.125s.234-.125.219-.219z'></path></svg>\",\"rss\":\"<svg aria-labelledby='rss' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>rss</title><path d='M8 20c0-1.109-.891-2-2-2s-2 .891-2 2 .891 2 2 2 2-.891 2-2zm5.484 1.469a9.468 9.468 0 00-8.953-8.953c-.141-.016-.281.047-.375.141S4 12.876 4 13.016v2c0 .266.203.484.469.5 3.203.234 5.781 2.812 6.016 6.016a.498.498 0 00.5.469h2c.141 0 .266-.063.359-.156s.156-.234.141-.375zm6 .015C19.218 13.359 12.64 6.781 4.515 6.515a.38.38 0 00-.359.141.508.508 0 00-.156.359v2c0 .266.219.484.484.5 6.484.234 11.766 5.516 12 12a.51.51 0 00.5.484h2a.509.509 0 00.359-.156.4.4 0 00.141-.359zM24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-15A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15C21.984 2 24 4.016 24 6.5z'></path></svg>\",\"facebook_group\":\"<svg aria-labelledby='facebook_group' xmlns='http://www.w3.org/2000/svg' width='30' height='28' viewBox='0 0 30 28'><title>Facebook Group</title><path d='M9.266 14a5.532 5.532 0 00-4.141 2H3.031C1.468 16 0 15.25 0 13.516 0 12.25-.047 8 1.937 8c.328 0 1.953 1.328 4.062 1.328.719 0 1.406-.125 2.078-.359A7.624 7.624 0 007.999 10c0 1.422.453 2.828 1.266 4zM26 23.953C26 26.484 24.328 28 21.828 28H8.172C5.672 28 4 26.484 4 23.953 4 20.422 4.828 15 9.406 15c.531 0 2.469 2.172 5.594 2.172S20.063 15 20.594 15C25.172 15 26 20.422 26 23.953zM10 4c0 2.203-1.797 4-4 4S2 6.203 2 4s1.797-4 4-4 4 1.797 4 4zm11 6c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zm9 3.516C30 15.25 28.531 16 26.969 16h-2.094a5.532 5.532 0 00-4.141-2A7.066 7.066 0 0022 10a7.6 7.6 0 00-.078-1.031A6.258 6.258 0 0024 9.328C26.109 9.328 27.734 8 28.062 8c1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z'></path></svg>\",\"dribbble\":\"<svg aria-labelledby='dribbble' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>Dribbble</title><path d='M16 23.438c-.156-.906-.75-4.031-2.188-7.781-.016 0-.047.016-.063.016 0 0-6.078 2.125-8.047 6.406-.094-.078-.234-.172-.234-.172a10.297 10.297 0 006.531 2.344c1.422 0 2.766-.297 4-.812zm-2.891-9.485a29.025 29.025 0 00-.828-1.734C7 13.797 1.937 13.672 1.765 13.672c-.016.109-.016.219-.016.328 0 2.625 1 5.031 2.625 6.844 2.797-4.984 8.328-6.766 8.328-6.766.141-.047.281-.078.406-.125zm-1.671-3.312a61.656 61.656 0 00-3.813-5.906 10.267 10.267 0 00-5.656 7.156c.266 0 4.547.047 9.469-1.25zm10.687 4.984c-.219-.063-3.078-.969-6.391-.453 1.344 3.703 1.891 6.719 2 7.328a10.293 10.293 0 004.391-6.875zM9.547 4.047c-.016 0-.016 0-.031.016 0 0 .016-.016.031-.016zm9.219 2.265a10.17 10.17 0 00-9.188-2.265c.156.203 2.094 2.75 3.844 5.969 3.859-1.437 5.313-3.656 5.344-3.703zm3.484 7.579a10.273 10.273 0 00-2.328-6.406c-.031.031-1.672 2.406-5.719 4.062.234.484.469.984.688 1.484.078.172.141.359.219.531 3.531-.453 7.016.313 7.141.328zM24 14c0 6.625-5.375 12-12 12S0 20.625 0 14 5.375 2 12 2s12 5.375 12 12z'></path></svg>\",\"xing\":\"<svg aria-labelledby='xing' xmlns='http://www.w3.org/2000/svg' width='22' height='28' viewBox='0 0 22 28'><title>xing</title><path d='M9.328 10.422s-.156.266-4.016 7.125c-.203.344-.469.719-1.016.719H.562c-.219 0-.391-.109-.484-.266s-.109-.359 0-.562l3.953-7c.016 0 .016 0 0-.016L1.515 6.063c-.109-.203-.125-.422-.016-.578.094-.156.281-.234.5-.234h3.734c.562 0 .844.375 1.031.703a773.586 773.586 0 002.562 4.469zM21.922.391c.109.156.109.375 0 .578l-8.25 14.594c-.016 0-.016.016 0 .016l5.25 9.609c.109.203.109.422.016.578-.109.156-.281.234-.5.234h-3.734c-.562 0-.859-.375-1.031-.703-5.297-9.703-5.297-9.719-5.297-9.719s.266-.469 8.297-14.719c.203-.359.438-.703 1-.703h3.766c.219 0 .391.078.484.234z'></path></svg>\",\"wordpress\":\"<svg aria-labelledby='wordpress' xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'><title>wordpress</title><path d='M1.984 14c0-1.734.375-3.391 1.047-4.891l5.734 15.703c-4.016-1.953-6.781-6.062-6.781-10.813zm20.125-.609c0 1.031-.422 2.219-.922 3.891l-1.188 4-4.344-12.906s.719-.047 1.375-.125c.641-.078.562-1.031-.078-.984-1.953.141-3.203.156-3.203.156s-1.172-.016-3.156-.156c-.656-.047-.734.938-.078.984.609.063 1.25.125 1.25.125l1.875 5.125-2.625 7.875-4.375-13s.719-.047 1.375-.125c.641-.078.562-1.031-.078-.984-1.937.141-3.203.156-3.203.156-.219 0-.484-.016-.766-.016a11.966 11.966 0 0110.031-5.422c3.125 0 5.969 1.203 8.109 3.156h-.156c-1.172 0-2.016 1.016-2.016 2.125 0 .984.578 1.813 1.188 2.812.469.797.984 1.828.984 3.313zm-7.906 1.656l3.703 10.109a.59.59 0 00.078.172c-1.25.438-2.578.688-3.984.688-1.172 0-2.312-.172-3.391-.5zm10.328-6.813A11.98 11.98 0 0126.015 14c0 4.438-2.406 8.297-5.984 10.375l3.672-10.594c.609-1.75.922-3.094.922-4.312 0-.438-.031-.844-.094-1.234zM14 0c7.719 0 14 6.281 14 14s-6.281 14-14 14S0 21.719 0 14 6.281 0 14 0zm0 27.359c7.359 0 13.359-6 13.359-13.359S21.359.641 14 .641.641 6.641.641 14s6 13.359 13.359 13.359z'></path></svg>\",\"whatsapp\":\"<svg aria-labelledby='whatsapp' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>whatsapp</title><path d='M15.391 15.219c.266 0 2.812 1.328 2.922 1.516.031.078.031.172.031.234 0 .391-.125.828-.266 1.188-.359.875-1.813 1.437-2.703 1.437-.75 0-2.297-.656-2.969-.969-2.234-1.016-3.625-2.75-4.969-4.734-.594-.875-1.125-1.953-1.109-3.031v-.125c.031-1.031.406-1.766 1.156-2.469.234-.219.484-.344.812-.344.187 0 .375.047.578.047.422 0 .5.125.656.531.109.266.906 2.391.906 2.547 0 .594-1.078 1.266-1.078 1.625 0 .078.031.156.078.234.344.734 1 1.578 1.594 2.141.719.688 1.484 1.141 2.359 1.578a.681.681 0 00.344.109c.469 0 1.25-1.516 1.656-1.516zM12.219 23.5c5.406 0 9.812-4.406 9.812-9.812s-4.406-9.812-9.812-9.812-9.812 4.406-9.812 9.812c0 2.063.656 4.078 1.875 5.75l-1.234 3.641 3.781-1.203a9.875 9.875 0 005.391 1.625zm0-21.594C18.719 1.906 24 7.187 24 13.687s-5.281 11.781-11.781 11.781c-1.984 0-3.953-.5-5.703-1.469L0 26.093l2.125-6.328a11.728 11.728 0 01-1.687-6.078c0-6.5 5.281-11.781 11.781-11.781z'></path></svg>\",\"vk\":\"<svg aria-labelledby='vk' xmlns='http://www.w3.org/2000/svg' width='31' height='28' viewBox='0 0 31 28'><title>vk</title><path d='M29.953 8.125c.234.641-.5 2.141-2.344 4.594-3.031 4.031-3.359 3.656-.859 5.984 2.406 2.234 2.906 3.313 2.984 3.453 0 0 1 1.75-1.109 1.766l-4 .063c-.859.172-2-.609-2-.609-1.5-1.031-2.906-3.703-4-3.359 0 0-1.125.359-1.094 2.766.016.516-.234.797-.234.797s-.281.297-.828.344h-1.797c-3.953.25-7.438-3.391-7.438-3.391S3.421 16.595.078 8.736c-.219-.516.016-.766.016-.766s.234-.297.891-.297l4.281-.031c.406.063.688.281.688.281s.25.172.375.5c.703 1.75 1.609 3.344 1.609 3.344 1.563 3.219 2.625 3.766 3.234 3.437 0 0 .797-.484.625-4.375-.063-1.406-.453-2.047-.453-2.047-.359-.484-1.031-.625-1.328-.672-.234-.031.156-.594.672-.844.766-.375 2.125-.391 3.734-.375 1.266.016 1.625.094 2.109.203 1.484.359.984 1.734.984 5.047 0 1.062-.203 2.547.562 3.031.328.219 1.141.031 3.141-3.375 0 0 .938-1.625 1.672-3.516.125-.344.391-.484.391-.484s.25-.141.594-.094l4.5-.031c1.359-.172 1.578.453 1.578.453z'></path></svg>\",\"tumblr\":\"<svg aria-labelledby='tumblr' xmlns='http://www.w3.org/2000/svg' width='17' height='28' viewBox='0 0 17 28'><title>tumblr</title><path d='M14.75 20.766L16 24.469c-.469.703-2.594 1.5-4.5 1.531-5.672.094-7.812-4.031-7.812-6.937v-8.5H1.063V7.204C5.001 5.782 5.954 2.22 6.172.188c.016-.125.125-.187.187-.187h3.813v6.625h5.203v3.937h-5.219v8.094c0 1.094.406 2.609 2.5 2.562.688-.016 1.609-.219 2.094-.453z'></path></svg>\",\"reddit\":\"<svg aria-labelledby='reddit' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>reddit</title><path d='M14.672 17.641a.293.293 0 010 .406c-.766.766-2.234.828-2.672.828s-1.906-.063-2.672-.828a.293.293 0 010-.406.267.267 0 01.406 0c.484.484 1.531.656 2.266.656s1.781-.172 2.266-.656a.267.267 0 01.406 0zm-4.109-2.438c0 .656-.547 1.203-1.203 1.203s-1.203-.547-1.203-1.203a1.203 1.203 0 012.406 0zm5.281 0c0 .656-.547 1.203-1.203 1.203s-1.203-.547-1.203-1.203a1.203 1.203 0 012.406 0zm3.359-1.609c0-.875-.719-1.594-1.609-1.594a1.62 1.62 0 00-1.141.484c-1.094-.75-2.562-1.234-4.172-1.281l.844-3.797 2.672.609c.016.656.547 1.188 1.203 1.188S18.203 8.656 18.203 8 17.656 6.797 17 6.797a1.2 1.2 0 00-1.078.672l-2.953-.656c-.156-.047-.297.063-.328.203l-.938 4.188c-1.609.063-3.063.547-4.141 1.297a1.603 1.603 0 00-2.765 1.094c0 .641.375 1.188.906 1.453-.047.234-.078.5-.078.75 0 2.547 2.859 4.609 6.391 4.609s6.406-2.063 6.406-4.609a3.09 3.09 0 00-.094-.766c.516-.266.875-.812.875-1.437zM24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-15A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15C21.984 2 24 4.016 24 6.5z'></path></svg>\",\"patreon\":\"<svg aria-labelledby='patreon' xmlns='http://www.w3.org/2000/svg' width='33' height='32' viewBox='0 0 33 32'><title>patreon</title><path d='M21.37.033c-6.617 0-12.001 5.383-12.001 11.999 0 6.597 5.383 11.963 12.001 11.963 6.597 0 11.963-5.367 11.963-11.963C33.333 5.415 27.966.033 21.37.033zM.004 31.996h5.859V.033H.004z'></path></svg>\",\"medium\":\"<svg aria-labelledby='medium' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'><title>medium</title><path d='M0 0v32h32V0zm26.584 7.581l-1.716 1.645a.5.5 0 00-.191.486v-.003 12.089a.502.502 0 00.189.481l.001.001 1.676 1.645v.361h-8.429v-.36l1.736-1.687c.171-.171.171-.22.171-.48v-9.773l-4.827 12.26h-.653L8.92 11.986v8.217a1.132 1.132 0 00.311.943l2.259 2.739v.361H5.087v-.36l2.26-2.74a1.09 1.09 0 00.289-.949l.001.007v-9.501a.83.83 0 00-.27-.702L7.366 10 5.358 7.581v-.36h6.232l4.817 10.564L20.642 7.22h5.941z'></path></svg>\",\"behance\":\"<svg aria-labelledby='behance' xmlns='http://www.w3.org/2000/svg' width='32' height='28' viewBox='0 0 32 28'><title>Behance</title>\\t<path d='M28.875 5.297h-7.984v1.937h7.984V5.297zm-3.937 6.656c-1.875 0-3.125 1.172-3.25 3.047h6.375c-.172-1.891-1.156-3.047-3.125-3.047zm.25 9.141c1.188 0 2.719-.641 3.094-1.859h3.453c-1.062 3.266-3.266 4.797-6.672 4.797-4.5 0-7.297-3.047-7.297-7.484 0-4.281 2.953-7.547 7.297-7.547 4.469 0 6.937 3.516 6.937 7.734 0 .25-.016.5-.031.734H21.688c0 2.281 1.203 3.625 3.5 3.625zm-20.86-.782h4.625c1.766 0 3.203-.625 3.203-2.609 0-2.016-1.203-2.812-3.109-2.812H4.328v5.422zm0-8.39h4.391c1.547 0 2.641-.672 2.641-2.344 0-1.813-1.406-2.25-2.969-2.25H4.329v4.594zM0 3.969h9.281c3.375 0 6.297.953 6.297 4.875 0 1.984-.922 3.266-2.688 4.109 2.422.688 3.594 2.516 3.594 4.984 0 4-3.359 5.719-6.937 5.719H0V3.968z'></path></svg>\",\"email\":\"<svg aria-labelledby='email' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'> <title id='email'> Email </title> <path d='M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z'/></svg>\",\"phone\":\"<svg aria-labelledby='phone' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 512 512'><title id='phone'> Phone </title><path d='M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z'/></svg>\",\"google_reviews\":\"<svg aria-labelledby='google_reviews' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title id='google_reviews'> Google Reviews </title><path d='M12 12.281h11.328c.109.609.187 1.203.187 2C23.515 21.125 18.921 26 11.999 26c-6.641 0-12-5.359-12-12s5.359-12 12-12c3.234 0 5.953 1.188 8.047 3.141L16.78 8.282c-.891-.859-2.453-1.859-4.781-1.859-4.094 0-7.438 3.391-7.438 7.578s3.344 7.578 7.438 7.578c4.75 0 6.531-3.406 6.813-5.172h-6.813v-4.125z'></path></svg>\",\"telegram\":\"<svg aria-labelledby='telegram' xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'><title>telegram</title><path d='M26.07 3.996a2.987 2.987 0 00-.952.23l.019-.007h-.004c-.285.113-1.64.683-3.7 1.547l-7.382 3.109c-5.297 2.23-10.504 4.426-10.504 4.426l.062-.024s-.359.118-.734.375c-.234.15-.429.339-.582.56l-.004.007c-.184.27-.332.683-.277 1.11.09.722.558 1.155.894 1.394.34.242.664.355.664.355h.008l4.883 1.645c.219.703 1.488 4.875 1.793 5.836.18.574.355.933.574 1.207.106.14.23.257.379.351.071.042.152.078.238.104l.008.002-.05-.012c.015.004.027.016.038.02.04.011.067.015.118.023.773.234 1.394-.246 1.394-.246l.035-.028 2.883-2.625 4.832 3.707.11.047c1.007.442 2.027.196 2.566-.238.543-.437.754-.996.754-.996l.035-.09 3.734-19.129c.106-.472.133-.914.016-1.343a1.818 1.818 0 00-.774-1.043l-.007-.004a1.852 1.852 0 00-1.071-.269h.005zm-.101 2.05c-.004.063.008.056-.02.177v.011l-3.699 18.93c-.016.027-.043.086-.117.145-.078.062-.14.101-.465-.028l-5.91-4.531-3.57 3.254.75-4.79 9.656-9c.398-.37.265-.448.265-.448.028-.454-.601-.133-.601-.133l-12.176 7.543-.004-.02-5.851-1.972a.237.237 0 00.032-.013l-.002.001.032-.016.031-.011s5.211-2.196 10.508-4.426c2.652-1.117 5.324-2.242 7.379-3.11a807.312 807.312 0 013.66-1.53c.082-.032.043-.032.102-.032z'></path></svg>\",\"trip_advisor\":\"<svg aria-labelledby='trip_advisor' xmlns='http://www.w3.org/2000/svg' width='36' height='28' viewBox='0 0 36 28'> <title id='trip_advisor'> Trip Advisor </title> <path d='M10.172 15.578c0 0.812-0.656 1.469-1.453 1.469-0.812 0-1.469-0.656-1.469-1.469 0-0.797 0.656-1.453 1.469-1.453 0.797 0 1.453 0.656 1.453 1.453zM28.203 15.563c0 0.812-0.656 1.469-1.469 1.469s-1.469-0.656-1.469-1.469 0.656-1.453 1.469-1.453 1.469 0.641 1.469 1.453zM11.953 15.578c0-1.656-1.359-3.016-3.016-3.016-1.672 0-3.016 1.359-3.016 3.016 0 1.672 1.344 3.016 3.016 3.016 1.656 0 3.016-1.344 3.016-3.016zM29.969 15.563c0-1.656-1.344-3.016-3.016-3.016-1.656 0-3.016 1.359-3.016 3.016 0 1.672 1.359 3.016 3.016 3.016 1.672 0 3.016-1.344 3.016-3.016zM13.281 15.578c0 2.406-1.937 4.359-4.344 4.359s-4.359-1.953-4.359-4.359c0-2.391 1.953-4.344 4.359-4.344s4.344 1.953 4.344 4.344zM31.313 15.563c0 2.406-1.953 4.344-4.359 4.344-2.391 0-4.344-1.937-4.344-4.344s1.953-4.344 4.344-4.344c2.406 0 4.359 1.937 4.359 4.344zM16.25 15.609c0-3.984-3.234-7.219-7.219-7.219-3.969 0-7.203 3.234-7.203 7.219s3.234 7.219 7.203 7.219c3.984 0 7.219-3.234 7.219-7.219zM26.688 6.656c-2.578-1.125-5.484-1.734-8.687-1.734s-6.391 0.609-8.953 1.719c4.953 0.016 8.953 4.016 8.953 8.969 0-4.859 3.859-8.813 8.687-8.953zM34.172 15.609c0-3.984-3.219-7.219-7.203-7.219s-7.219 3.234-7.219 7.219 3.234 7.219 7.219 7.219 7.203-3.234 7.203-7.219zM30.016 6.766h5.984c-0.938 1.094-1.625 2.562-1.797 3.578 1.078 1.484 1.719 3.297 1.719 5.266 0 4.953-4.016 8.953-8.953 8.953-2.812 0-5.313-1.281-6.953-3.297 0 0-0.734 0.875-2.016 2.797-0.219-0.453-1.328-2.031-2-2.812-1.641 2.031-4.156 3.313-6.969 3.313-4.937 0-8.953-4-8.953-8.953 0-1.969 0.641-3.781 1.719-5.266-0.172-1.016-0.859-2.484-1.797-3.578h5.703c3.063-2.047 7.516-3.328 12.297-3.328s8.953 1.281 12.016 3.328z'></path></svg>\",\"yelp\":\"<svg aria-labelledby='yelp' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><path d='M12.078 20.609v1.984c-.016 4.406-.016 4.562-.094 4.766-.125.328-.406.547-.797.625-1.125.187-4.641-1.109-5.375-1.984a1.107 1.107 0 01-.266-.562.882.882 0 01.063-.406c.078-.219.219-.391 3.359-4.109 0 0 .016 0 .938-1.094.313-.391.875-.516 1.391-.328.516.203.797.641.781 1.109zM9.75 16.688c-.031.547-.344.953-.812 1.094l-1.875.609c-4.203 1.344-4.344 1.375-4.562 1.375-.344-.016-.656-.219-.844-.562-.125-.25-.219-.672-.266-1.172-.172-1.531.031-3.828.484-4.547.219-.344.531-.516.875-.5.234 0 .422.094 4.953 1.937 0 0-.016.016 1.313.531.469.187.766.672.734 1.234zm12.906 4.64c-.156 1.125-2.484 4.078-3.547 4.5-.359.141-.719.109-.984-.109-.187-.141-.375-.422-2.875-4.484l-.734-1.203c-.281-.438-.234-1 .125-1.437.344-.422.844-.562 1.297-.406 0 0 .016.016 1.859.625 4.203 1.375 4.344 1.422 4.516 1.563.281.219.406.547.344.953zm-10.5-9.875c.078 1.625-.609 1.828-.844 1.906-.219.063-.906.266-1.781-1.109-5.75-9.078-5.906-9.344-5.906-9.344-.078-.328.016-.688.297-.969.859-.891 5.531-2.203 6.75-1.891.391.094.672.344.766.703.063.391.625 8.813.719 10.703zM22.5 13.141c.031.391-.109.719-.406.922-.187.125-.375.187-5.141 1.344-.766.172-1.188.281-1.422.359l.016-.031c-.469.125-1-.094-1.297-.562s-.281-.984 0-1.359c0 0 .016-.016 1.172-1.594 2.562-3.5 2.688-3.672 2.875-3.797.297-.203.656-.203 1.016-.031 1.016.484 3.063 3.531 3.187 4.703v.047z'></path></svg>\",\"pinterest\":\"<svg aria-labelledby='pinterest' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>pinterest</title><path d='M19.5 2C21.984 2 24 4.016 24 6.5v15c0 2.484-2.016 4.5-4.5 4.5H8.172c.516-.734 1.359-2 1.687-3.281 0 0 .141-.531.828-3.266.422.797 1.625 1.484 2.906 1.484 3.813 0 6.406-3.484 6.406-8.141 0-3.516-2.984-6.797-7.516-6.797-5.641 0-8.484 4.047-8.484 7.422 0 2.031.781 3.844 2.438 4.531.266.109.516 0 .594-.297.047-.203.172-.734.234-.953.078-.297.047-.406-.172-.656-.469-.578-.781-1.297-.781-2.344 0-3 2.25-5.672 5.844-5.672 3.187 0 4.937 1.937 4.937 4.547 0 3.422-1.516 6.312-3.766 6.312-1.234 0-2.172-1.031-1.875-2.297.359-1.5 1.047-3.125 1.047-4.203 0-.969-.516-1.781-1.594-1.781-1.266 0-2.281 1.313-2.281 3.063 0 0 0 1.125.375 1.891-1.297 5.5-1.531 6.469-1.531 6.469-.344 1.437-.203 3.109-.109 3.969H4.5A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15z'></path></svg>\",\"linkedin\":\"<svg aria-labelledby='linkedin' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>linkedin</title><path d='M3.703 22.094h3.609V11.25H3.703v10.844zM7.547 7.906c-.016-1.062-.781-1.875-2.016-1.875s-2.047.812-2.047 1.875c0 1.031.781 1.875 2 1.875H5.5c1.266 0 2.047-.844 2.047-1.875zm9.141 14.188h3.609v-6.219c0-3.328-1.781-4.875-4.156-4.875-1.937 0-2.797 1.078-3.266 1.828h.031V11.25H9.297s.047 1.016 0 10.844h3.609v-6.062c0-.313.016-.641.109-.875.266-.641.859-1.313 1.859-1.313 1.297 0 1.813.984 1.813 2.453v5.797zM24 6.5v15c0 2.484-2.016 4.5-4.5 4.5h-15A4.502 4.502 0 010 21.5v-15C0 4.016 2.016 2 4.5 2h15C21.984 2 24 4.016 24 6.5z'></path></svg>\",\"imdb\":\"<svg aria-labelledby='imdb' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><title>imdb</title><path d='M14.406 12.453v2.844c0 .562.109 1.078-.594 1.062v-4.828c.688 0 .594.359.594.922zm4.938 1.5v1.891c0 .313.094.828-.359.828a.236.236 0 01-.219-.141c-.125-.297-.063-2.547-.063-2.578 0-.219-.063-.734.281-.734.422 0 .359.422.359.734zM2.812 17.641h1.906v-7.375H2.812v7.375zm6.782 0h1.656v-7.375H8.766l-.438 3.453a123.605 123.605 0 00-.5-3.453H5.359v7.375h1.672v-4.875l.703 4.875h1.188l.672-4.984v4.984zm6.64-4.766c0-.469.016-.969-.078-1.406-.25-1.297-1.813-1.203-2.828-1.203h-1.422v7.375c4.969 0 4.328.344 4.328-4.766zm4.953 3.078v-2.078c0-1-.047-1.734-1.281-1.734-.516 0-.859.156-1.203.531v-2.406h-1.828v7.375h1.719l.109-.469c.328.391.688.562 1.203.562 1.141 0 1.281-.875 1.281-1.781zM24 4.5v19c0 1.375-1.125 2.5-2.5 2.5h-19A2.507 2.507 0 010 23.5v-19C0 3.125 1.125 2 2.5 2h19C22.875 2 24 3.125 24 4.5z'></path></svg>\",\"header-main-layout-1\":\"<svg aria-labelledby='header-main-layout-1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><g><path fill='#0085BA' d='M116.701,80.797H3.799c-1.958,0-3.549-1.593-3.549-3.55V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.957,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.658,80.797,116.701,80.797z M3.799,1.979 c-0.979,0-1.775,0.795-1.775,1.774v73.494c0,0.979,0.796,1.774,1.775,1.774h112.902c0.979,0,1.773-0.795,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><path fill='#0085BA' d='M107.275,16.6H48.385c-0.98,0-1.774-0.794-1.774-1.774s0.794-1.774,1.774-1.774h58.891 c0.979,0,1.773,0.794,1.773,1.774S108.254,16.6,107.275,16.6z'/></g><g><path fill='#0085BA' d='M34.511,16.689c0,0.989-0.929,1.789-2.074,1.789H16.116c-1.146,0-2.075-0.8-2.075-1.789v-3.727 c0-0.989,0.929-1.79,2.075-1.79h16.321c1.146,0,2.074,0.801,2.074,1.79V16.689z'/></g></g></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='0.25' y1='28.342' x2='119.535' y2='28.342'/></svg>\",\"header-main-layout-2\":\"<svg aria-labelledby='header-main-layout-2' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='0.607' y1='28.341' x2='119.893' y2='28.341'/><g><path fill='#0085BA' d='M116.701,80.795H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.752c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.955,0,3.549,1.592,3.549,3.549v73.494C120.25,79.203,118.656,80.795,116.701,80.795z M3.799,1.978 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.98,0.795,1.775,1.773,1.775h112.902c0.979,0,1.773-0.797,1.773-1.775V3.752 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><path fill='#0085BA' d='M69.314,12.413c0,1.014-0.822,1.837-1.836,1.837H53.021c-1.015,0-1.837-0.823-1.837-1.837V8.586 c0-1.015,0.822-1.837,1.837-1.837h14.458c1.014,0,1.836,0.822,1.836,1.837V12.413z'/></g></g><g><path fill='#0085BA' d='M99.697,22.067H20.804c-0.98,0-1.774-0.672-1.774-1.5c0-0.828,0.794-1.5,1.774-1.5h78.894 c0.979,0,1.772,0.672,1.772,1.5C101.471,21.395,100.676,22.067,99.697,22.067z'/></g></svg>\",\"header-main-layout-3\":\"<svg aria-labelledby='header-main-layout-3' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><g><path fill='#0085BA' d='M0.25,77.247V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902c1.957,0,3.549,1.592,3.549,3.549v73.494 c0,1.957-1.592,3.55-3.549,3.55H3.799C1.842,80.797,0.25,79.204,0.25,77.247z M3.799,1.979c-0.979,0-1.774,0.795-1.774,1.774 v73.494c0,0.979,0.796,1.774,1.774,1.774h112.902c0.979,0,1.773-0.795,1.773-1.774V3.753c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><path fill='#0085BA' d='M13.225,16.6h58.891c0.979,0,1.774-0.794,1.774-1.774s-0.795-1.774-1.774-1.774H13.225 c-0.979,0-1.773,0.794-1.773,1.774C11.451,15.806,12.246,16.6,13.225,16.6z'/></g><g><path fill='#0085BA' d='M85.988,16.689c0,0.989,0.93,1.789,2.074,1.789h16.321c1.146,0,2.074-0.8,2.074-1.789v-3.727 c0-0.989-0.929-1.79-2.074-1.79H88.063c-1.145,0-2.073,0.801-2.073,1.79L85.988,16.689L85.988,16.689z'/></g></g></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='120.25' y1='28.342' x2='0.965' y2='28.342'/></svg>\",\"menu-inline\":\"<svg aria-labelledby='menu-inline' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='60.5px' height='81px' viewBox='0 0 60.5 81' enable-background='new 0 0 60.5 81' xml:space='preserve'><g><g><g><path fill='#0085BA' d='M51.602,12.975H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,12.546,52.098,12.975,51.602,12.975z'/></g></g><g><g><path fill='#0085BA' d='M51.602,17.205H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,16.775,52.098,17.205,51.602,17.205z'/></g></g><g><g><path fill='#0085BA' d='M51.602,21.435H40.884c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C52.496,21.004,52.098,21.435,51.602,21.435z'/></g></g></g><g><path fill='#0085BA' d='M25.504,20.933c0,1.161-0.794,2.099-1.773,2.099H9.777c-0.979,0-1.773-0.938-1.773-2.099V11.56 c0-1.16,0.795-2.1,1.773-2.1H23.73c0.979,0,1.772,0.94,1.772,2.1L25.504,20.933L25.504,20.933z'/></g><g><path fill='#0085BA' d='M56.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h52.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C60.25,79.204,58.657,80.796,56.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.774,1.773,1.774h52.902c0.979,0,1.773-0.797,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g></svg>\",\"menu-stack\":\"<svg aria-labelledby='menu-stack' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='60.5px' height='81px' viewBox='0 0 60.5 81' enable-background='new 0 0 60.5 81' xml:space='preserve'><g><path fill='#0085BA' d='M56.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h52.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C60.25,79.204,58.657,80.796,56.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.774,1.773,1.774h52.902c0.979,0,1.773-0.797,1.773-1.774V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g><g><g><g><path fill='#0085BA' d='M35.607,29.821H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,29.392,36.104,29.821,35.607,29.821z'/></g></g><g><g><path fill='#0085BA' d='M35.607,34.051H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,33.621,36.104,34.051,35.607,34.051z'/></g></g><g><g><path fill='#0085BA' d='M35.607,38.281H24.889c-0.493,0-0.892-0.429-0.892-0.959c0-0.529,0.396-0.959,0.892-0.959h10.718 c0.496,0,0.896,0.432,0.896,0.959C36.502,37.85,36.104,38.281,35.607,38.281z'/></g></g></g><g><path fill='#0085BA' d='M39,20.933c0,1.161-0.794,2.099-1.773,2.099H23.273c-0.979,0-1.773-0.938-1.773-2.099V11.56 c0-1.16,0.795-2.1,1.773-2.1h13.954c0.979,0,1.771,0.94,1.771,2.1L39,20.933L39,20.933z'/></g></svg>\",\"disabled\":\"<svg aria-labelledby='footer-layout-disabled' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'> <g> <g> <path fill='#0085BA' d='M116.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.657,80.796,116.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.772,1.773,1.772h112.902c0.979,0,1.773-0.797,1.773-1.772V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/> </g> </g> <path fill='#0085BA' d='M60.25,19.5c-11.581,0-21,9.419-21,21c0,11.578,9.419,21,21,21c11.578,0,21-9.422,21-21 C81.25,28.919,71.828,19.5,60.25,19.5z M42.308,40.5c0-9.892,8.05-17.942,17.942-17.942c4.412,0,8.452,1.6,11.578,4.249 L46.557,52.078C43.908,48.952,42.308,44.912,42.308,40.5z M60.25,58.439c-4.385,0-8.407-1.579-11.526-4.201l25.265-25.265 c2.622,3.12,4.201,7.141,4.201,11.526C78.189,50.392,70.142,58.439,60.25,58.439z'/> </svg>\",\"footer-layout-1\":\"<svg aria-labelledby='footer-layout-1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><path fill='#0085BA' d='M3.799,0.204h112.902c1.958,0,3.549,1.593,3.549,3.55v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799 c-1.957,0-3.549-1.592-3.549-3.549V3.754C0.25,1.797,1.842,0.204,3.799,0.204z M116.701,79.021c0.979,0,1.774-0.795,1.774-1.773 l0.001-73.494c0-0.979-0.797-1.774-1.775-1.774H3.799c-0.979,0-1.773,0.795-1.773,1.774v73.494c0,0.979,0.795,1.773,1.773,1.773 H116.701z'/></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='120.25' y1='58.659' x2='0.965' y2='58.659'/><g><g><path fill='#0085BA' d='M26.805,64.475h66.89c0.98,0,1.774,0.628,1.774,1.4s-0.794,1.4-1.774,1.4h-66.89 c-0.98,0-1.773-0.628-1.773-1.4C25.031,65.102,25.826,64.475,26.805,64.475z'/></g></g><g><ellipse fill='#0085BA' cx='72.604' cy='72.174' rx='2.146' ry='2.108'/><ellipse fill='#0085BA' cx='64.37' cy='72.174' rx='2.147' ry='2.108'/><ellipse fill='#0085BA' cx='56.132' cy='72.174' rx='2.145' ry='2.108'/><ellipse fill='#0085BA' cx='47.896' cy='72.174' rx='2.146' ry='2.108'/></g></svg>\",\"footer-layout-2\":\"<svg aria-labelledby='footer-layout-2' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><path fill='#0085BA' d='M120.25,3.754v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.754 c0-1.957,1.591-3.55,3.549-3.55h112.902C118.658,0.204,120.25,1.797,120.25,3.754z M116.701,79.021 c0.979,0,1.773-0.795,1.773-1.773V3.754c0-0.979-0.795-1.774-1.773-1.774H3.799c-0.979,0-1.775,0.795-1.775,1.774l0.001,73.494 c0,0.979,0.796,1.773,1.774,1.773H116.701z'/></g><g><g><path fill='#0085BA' d='M120.25,3.754v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.754 c0-1.957,1.591-3.55,3.549-3.55h112.902C118.658,0.204,120.25,1.797,120.25,3.754z M116.701,79.021 c0.979,0,1.773-0.795,1.773-1.773V3.754c0-0.979-0.795-1.774-1.773-1.774H3.799c-0.979,0-1.775,0.795-1.775,1.774l0.001,73.494 c0,0.979,0.796,1.773,1.774,1.773H116.701z'/></g></g><g><g><g><path fill='#0085BA' d='M63.184,69.175c0,0.979-0.793,1.774-1.773,1.774h-46.89c-0.98,0-1.774-0.795-1.774-1.774 S13.54,67.4,14.521,67.4h46.89C62.389,67.4,63.184,68.194,63.184,69.175z'/></g></g><g><ellipse fill='#0085BA' cx='79.872' cy='69.175' rx='2.228' ry='2.188'/><ellipse fill='#0085BA' cx='88.422' cy='69.175' rx='2.229' ry='2.188'/><ellipse fill='#0085BA' cx='96.974' cy='69.175' rx='2.227' ry='2.188'/><ellipse fill='#0085BA' cx='105.525' cy='69.175' rx='2.229' ry='2.188'/></g></g><line fill='none' stroke='#0085BA' stroke-miterlimit='10' x1='120.25' y1='58.659' x2='0.965' y2='58.659'/></svg>\",\"footer-layout-4\":\"<svg aria-labelledby='footer-layout-4' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' role='img' id='Layer_1' x='0px' y='0px' width='120.5px' height='81px' viewBox='0 0 120.5 81' enable-background='new 0 0 120.5 81' xml:space='preserve'><g><g><g><g><path fill='#0085BA' d='M116.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.657,80.796,116.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.772,1.773,1.772h112.902c0.979,0,1.773-0.797,1.773-1.772V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z'/></g></g></g></g><g><path fill='#0085BA' d='M28.064,70c0,1.657-1.354,3-3.023,3H12.458c-1.669,0-3.023-1.343-3.023-3V58.25c0-1.656,1.354-3,3.023-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><path fill='#0085BA' d='M55.731,70c0,1.657-1.354,3-3.023,3H40.125c-1.669,0-3.023-1.343-3.023-3V58.25c0-1.656,1.354-3,3.023-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><path fill='#0085BA' d='M83.397,70c0,1.657-1.354,3-3.023,3H67.791c-1.669,0-3.022-1.343-3.022-3V58.25c0-1.656,1.354-3,3.022-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><path fill='#0085BA' d='M111.064,70c0,1.657-1.354,3-3.023,3H95.458c-1.669,0-3.022-1.343-3.022-3V58.25c0-1.656,1.354-3,3.022-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z'/></g><g><rect x='0.607' y='48' fill='#0085BA' width='119.285' height='1'/></g></svg>\",\"menu\":\"<svg aria-labelledby='menu' class='ast-mobile-svg ast-menu-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M3 13h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 7h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1zM3 19h18c0.552 0 1-0.448 1-1s-0.448-1-1-1h-18c-0.552 0-1 0.448-1 1s0.448 1 1 1z'></path></svg>\",\"menu2\":\"<svg aria-labelledby='menu2' class='ast-mobile-svg ast-menu2-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='24' height='28' viewBox='0 0 24 28'><path d='M24 21v2c0 0.547-0.453 1-1 1h-22c-0.547 0-1-0.453-1-1v-2c0-0.547 0.453-1 1-1h22c0.547 0 1 0.453 1 1zM24 13v2c0 0.547-0.453 1-1 1h-22c-0.547 0-1-0.453-1-1v-2c0-0.547 0.453-1 1-1h22c0.547 0 1 0.453 1 1zM24 5v2c0 0.547-0.453 1-1 1h-22c-0.547 0-1-0.453-1-1v-2c0-0.547 0.453-1 1-1h22c0.547 0 1 0.453 1 1z'></path></svg>\",\"menu3\":\"<svg aria-labelledby='menu3' class='ast-mobile-svg ast-menu3-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'><path d='M6 3c0-1.105 0.895-2 2-2s2 0.895 2 2c0 1.105-0.895 2-2 2s-2-0.895-2-2zM6 8c0-1.105 0.895-2 2-2s2 0.895 2 2c0 1.105-0.895 2-2 2s-2-0.895-2-2zM6 13c0-1.105 0.895-2 2-2s2 0.895 2 2c0 1.105-0.895 2-2 2s-2-0.895-2-2z'></path></svg>\",\"close\":\"<svg aria-labelledby='close' class='ast-mobile-svg ast-close-svg' fill='currentColor' version='1.1' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='M5.293 6.707l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0l5.293-5.293 5.293 5.293c0.391 0.391 1.024 0.391 1.414 0s0.391-1.024 0-1.414l-5.293-5.293 5.293-5.293c0.391-0.391 0.391-1.024 0-1.414s-1.024-0.391-1.414 0l-5.293 5.293-5.293-5.293c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414z'></path></svg>\",\"edit\":\"<svg aria-labelledby='edit' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><path d='M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z'></path></svg>\",\"drag\":\"<svg width='18' height='18' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 18 18'><path d='M13,8c0.6,0,1-0.4,1-1s-0.4-1-1-1s-1,0.4-1,1S12.4,8,13,8z M5,6C4.4,6,4,6.4,4,7s0.4,1,1,1s1-0.4,1-1S5.6,6,5,6z M5,10 c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S5.6,10,5,10z M13,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S13.6,10,13,10z M9,6 C8.4,6,8,6.4,8,7s0.4,1,1,1s1-0.4,1-1S9.6,6,9,6z M9,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S9.6,10,9,10z'/>/svg>\",\"mobile_menu\":\"<svg x='0px' y='0px' viewBox='0 0 84.2 81'><path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h76.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h76.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/><g><path className='st0' d='M14.8,28.4c0-1.2,0.8-2.1,1.8-2.1h50.9c1,0,1.8,0.9,1.8,2.1v-0.6c0,1.2-0.8,2.1-1.8,2.1H16.6c-1,0-1.8-0.9-1.8-2.1V28.4L14.8,28.4z'/></g><g><path className='st0' d='M14.8,40.8c0-1.2,0.8-2.1,1.8-2.1h50.9c1,0,1.8,0.9,1.8,2.1v-0.6c0,1.2-0.8,2.1-1.8,2.1H16.6c-1,0-1.8-0.9-1.8-2.1V40.8L14.8,40.8z'/></g><g><path className='st0' d='M14.8,53.2c0-1.2,0.8-2.1,1.8-2.1h50.9c1,0,1.8,0.9,1.8,2.1v-0.6c0,1.2-0.8,2.1-1.8,2.1H16.6c-1,0-1.8-0.9-1.8-2.1V53.2L14.8,53.2z'/></g></svg>\",\"mobile_menu2\":\"<svg x='0px' y='0px' viewBox='0 0 84.2 81'> <path className='st0' d='M0.1,77.2V3.8c0-2,1.6-3.5,3.5-3.5h76.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.7 C1.7,80.8,0.2,79.2,0.1,77.2z M3.7,2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h76.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.7z' /> <g> <g> <path className='st0' d='M14.7,56c0-1.2,0.8-2.1,1.8-2.1h50.8c1,0,1.8,0.9,1.8,2.1v3.5c0,1.2-0.8,2.1-1.8,2.1H16.5 c-1,0-1.8-0.9-1.8-2.1L14.7,56L14.7,56z' /> </g> <g> <path className='st0' d='M14.7,38.5c0-1.2,0.8-2.1,1.8-2.1h50.8c1,0,1.8,0.9,1.8,2.1V42c0,1.2-0.8,2.1-1.8,2.1H16.5 c-1,0-1.8-0.9-1.8-2.1L14.7,38.5L14.7,38.5z' /> </g> <g> <path className='st0' d='M14.7,21.1c0-1.2,0.8-2.1,1.8-2.1h50.8c1,0,1.8,0.9,1.8,2.1v3.5c0,1.2-0.8,2.1-1.8,2.1H16.5 c-1,0-1.8-0.9-1.8-2.1L14.7,21.1L14.7,21.1z' /> </g> </g> </svg>\",\"mobile_menu3\":\"<svg x='0px' y='0px' viewBox='0 0 84.2 81'> <g> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h76.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h76.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z' /> </g> <circle className='st0' cx='42.1' cy='21.5' r='6.4' /> <circle className='st0' cx='42.1' cy='40.5' r='6.4' /> <circle className='st0' cx='42.1' cy='59.5' r='6.4' /> </svg>\",\"twocol\":\"<svg viewBox='0 0 120.5 81' xmlns='http://www.w3.org/2000/svg' x='0px' y='0px'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,1.9C2.8,1.9,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z' /> <path className='st0' d='M54.7,55.3H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h42.2c1.7,0,3-1.3,3-3V58.3 C57.7,56.6,56.4,55.3,54.7,55.3z' /> <path className='st0' d='M108,55.3H65.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h42.3c1.7,0,3-1.3,3-3V58.3 C111.1,56.6,109.7,55.3,108,55.3z' /> <rect x='0.6' y='47.9' className='st0' width='119.3' height='1' /> </svg>\",\"twoleftgolden\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z' /> <path className='st0' d='M81.7,55.4H45h-4.9H12.4c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h36.7c1.7,0,3-1.3,3-3V58.4 C84.8,56.6,83.4,55.4,81.7,55.4z' /> <path className='st0' d='M108.1,55.4H93.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h14.3c1.7,0,3-1.3,3-3V58.4 C111.1,56.6,109.7,55.4,108.1,55.4z' /> <rect x='0.6' y='48' className='st0' width='119.3' height='1' /> </svg>\",\"tworightgolden\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.3V3.8c0-1.9,1.5-3.5,3.5-3.5h112.9c1.9,0,3.5,1.5,3.5,3.5v73.5c0,1.9-1.5,3.5-3.5,3.5H3.8 C1.8,80.8,0.2,79.1,0.3,77.3z M3.8,1.9C2.8,1.9,2,2.7,2,3.7v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.7 c0-1-0.8-1.8-1.8-1.8C116.7,1.9,3.8,1.9,3.8,1.9z' /> <path className='st0' d='M35.8,58.3V70c0,1.7,1.3,3,3,3h36.7h4.9h27.7c1.6,0,3-1.3,3-3V58.3c0-1.7-1.3-3-3-3H80.4h-4.9H38.8 C37.1,55.3,35.7,56.5,35.8,58.3z' /> <path className='st0' d='M9.4,58.3V70c0,1.7,1.3,3,3,3h14.3c1.6,0,3-1.3,3-3V58.3c0-1.7-1.3-3-3-3H12.4C10.8,55.3,9.4,56.5,9.4,58.3z' /> <rect x='0.6' y='47.9' className='st0' width='119.3' height='1' /> </svg>\",\"lefthalf\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.1,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M65.7,55.1H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h53.3c1.7,0,3-1.3,3-3V58.1 C68.7,56.5,67.4,55.1,65.7,55.1z'/> <path className='st0' d='M108,55.1H96.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3H108c1.7,0,3-1.3,3-3V58.1 C111.1,56.5,109.7,55.1,108,55.1z'/> <path className='st0' d='M87.1,55.1H75.9c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.1 C90.1,56.5,88.8,55.1,87.1,55.1z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"threecol\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.1,118.7,80.8,116.7,80.8z M3.8,1.9C2.8,1.9,2,2.6,2,3.6v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.6 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M37.7,55.1H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h25.3c1.7,0,3-1.3,3-3V58.1 C40.7,56.6,39.4,55.1,37.7,55.1z'/> <path className='st0' d='M72.9,55.1H47.6c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h25.3c1.7,0,3-1.3,3-3V58.1 C75.9,56.6,74.5,55.1,72.9,55.1z'/> <path className='st0' d='M108,55.1H82.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3H108c1.7,0,3-1.3,3-3V58.1 C111.1,56.6,109.7,55.1,108,55.1z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"righthalf\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.2V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,80.8,0.4,79.2,0.3,77.2z M3.9,2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <path className='st0' d='M54.9,55.2h53.2c1.7,0,3,1.3,3,3V70c0,1.7-1.4,3-3,3H54.9c-1.7,0-3-1.3-3-3V58.2 C51.9,56.6,53.2,55.2,54.9,55.2z'/> <path className='st0' d='M12.6,55.2h11.3c1.7,0,3,1.3,3,3V70c0,1.7-1.4,3-3,3H12.6c-1.7,0-3-1.3-3-3V58.2C9.5,56.6,10.9,55.2,12.6,55.2 z'/> <path className='st0' d='M33.5,55.2h11.3c1.7,0,3,1.3,3,3V70c0,1.7-1.4,3-3,3H33.5c-1.7,0-3-1.3-3-3V58.2 C30.5,56.6,31.8,55.2,33.5,55.2z'/> <rect x='0.7' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"centerhalf\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.3V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,80.8,0.4,79.1,0.3,77.3z M3.9,1.9c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <path className='st0' d='M36.7,55.2H84c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H36.7c-1.7,0-3-1.3-3-3V58.2 C33.7,56.5,35.1,55.2,36.7,55.2z'/> <path className='st0' d='M12.6,55.2h14.3c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H12.6c-1.7,0-3-1.3-3-3V58.2 C9.5,56.5,10.9,55.2,12.6,55.2z'/> <path className='st0' d='M93.9,55.2h14.2c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H93.9c-1.7,0-3-1.3-3-3V58.2 C90.9,56.5,92.2,55.2,93.9,55.2z'/> <rect x='0.7' y='47.9' className='st0' width='119.3' height='1'/> </svg> \",\"widecenter\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <path className='st0' d='M32.7,55.5H88c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H32.7c-1.7,0-3-1.3-3-3V58.5 C29.7,56.8,31.1,55.5,32.7,55.5z'/> <path className='st0' d='M12.6,55.5h10.3c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H12.6c-1.7,0-3-1.3-3-3V58.5 C9.5,56.8,10.9,55.5,12.6,55.5z'/> <path className='st0' d='M97.9,55.5h10.2c1.7,0,3,1.3,3,3v11.7c0,1.7-1.4,3-3,3H97.9c-1.7,0-3-1.3-3-3V58.5 C94.9,56.8,96.2,55.5,97.9,55.5z'/> <rect x='0.7' y='48.2' className='st0' width='119.3' height='1'/> </svg>\",\"sixcol\":\"<svg xmlns='http://www.w3.org/2000/svg' version='1.1' id='Layer_1' x='0px' y='0px' viewBox='0 0 120.5 81'><g> <g> <g> <g> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <g> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> </g> <g> <g> <g> <g> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <g> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> </g> <path className='st1' d='M-0.5,77.2V3.8c0-2,1.6-3.5,3.5-3.5H116c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.1 C1.1,80.8-0.4,79.2-0.5,77.2z M3.1,2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.1z'/> <rect x='-0.1' y='56' className='st1' width='119.3' height='1'/> <g> <path className='st1' d='M22.4,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L22.4,70.8z'/> </g> <g> <path className='st1' d='M40,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10c1,0,1.8,0.9,1.8,2.1 V70.8L40,70.8z'/> </g> <g> <path className='st1' d='M57.6,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L57.6,70.8z'/> </g> <g> <path className='st1' d='M75.2,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L75.2,70.8z'/> </g> <g> <path className='st1' d='M92.8,70.8c0,1.2-0.8,2.1-1.8,2.1H81c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10c1,0,1.8,0.9,1.8,2.1 V70.8L92.8,70.8z'/> </g> <g> <path className='st1' d='M110.4,70.8c0,1.2-0.8,2.1-1.8,2.1h-10c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h10 c1,0,1.8,0.9,1.8,2.1V70.8L110.4,70.8z'/> </g> </svg>\",\"fivecol\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-526.1H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-527.7,118.7-526.1,116.7-526.1z M3.8-604.9c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-551.6H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-550.3,54.4-551.6,52.7-551.6z'/> <path className='st0' d='M108-551.6h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-550.3,109.7-551.6,108-551.6z'/> <rect x='0.6' y='-558.9' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-526.1H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-527.7,118.7-526.1,116.7-526.1z M3.8-604.9c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-551.6H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-550.3,54.4-551.6,52.7-551.6z'/> <path className='st0' d='M108-551.6h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-550.3,109.7-551.6,108-551.6z'/> <rect x='0.6' y='-558.9' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M23.7,55.2H12.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C26.7,56.6,25.4,55.2,23.7,55.2z'/> <path className='st0' d='M44.7,55.2H33.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C47.7,56.6,46.4,55.2,44.7,55.2z'/> <path className='st0' d='M65.8,55.2H54.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C68.8,56.6,67.5,55.2,65.8,55.2z'/> <path className='st0' d='M86.8,55.2H75.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2C89.8,56.6,88.5,55.2,86.8,55.2z'/> <path className='st0' d='M107.8,55.2H96.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h11.3c1.7,0,3-1.3,3-3V58.2 C110.8,56.6,109.5,55.2,107.8,55.2z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg> \",\"rfourforty\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M0.3,77.3V3.8c0-1.9,1.5-3.5,3.5-3.5h112.9c1.9,0,3.5,1.5,3.5,3.5v73.5c0,1.9-1.5,3.5-3.5,3.5H3.8 C1.8,80.8,0.2,79.2,0.3,77.3z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M66.7,58.2V70c0,1.7,1.3,3,3,3H108c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3H69.8C68.1,55.2,66.8,56.6,66.7,58.2z '/> <path className='st0' d='M47.8,58.2V70c0,1.7,1.3,3,3,3h9.3c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3h-9.2C49.2,55.2,47.9,56.6,47.8,58.2z '/> <path className='st0' d='M28.5,58.2V70c0,1.7,1.3,3,3,3h9.3c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3h-9.2C29.9,55.2,28.6,56.6,28.5,58.2z '/> <path className='st0' d='M9.4,58.2V70c0,1.7,1.3,3,3,3h9.3c1.6,0,3-1.3,3-3V58.2c0-1.7-1.3-3-3-3h-9.2C10.8,55.2,9.5,56.6,9.4,58.2z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg> \",\"lfourforty\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M50.7,55.2H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h38.3c1.7,0,3-1.3,3-3V58.2 C53.7,56.6,52.4,55.2,50.7,55.2z'/> <path className='st0' d='M69.6,55.2h-9.2c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h9.3c1.7,0,3-1.3,3-3V58.2 C72.6,56.6,71.3,55.2,69.6,55.2z'/> <path className='st0' d='M88.9,55.2h-9.2c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3H89c1.7,0,3-1.3,3-3V58.2 C91.9,56.6,90.6,55.2,88.9,55.2z'/> <path className='st0' d='M108,55.2h-9.2c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h9.3c1.7,0,3-1.3,3-3V58.2C111,56.6,109.7,55.2,108,55.2 z'/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"fourcol\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7-715.4H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-717,118.7-715.4,116.7-715.4z M3.8-794.2c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M52.7-740.9H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-739.6,54.4-740.9,52.7-740.9z'/> <path className='st0' d='M108-740.9h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-739.6,109.7-740.9,108-740.9z'/> <rect x='0.6' y='-748.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z'/> <path className='st0' d='M29.7,55.2H12.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h17.3c1.7,0,3-1.3,3-3V58.2 C32.7,56.5,31.4,55.2,29.7,55.2z'/> <path className='st0' d='M55.8,55.2H38.6c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h17.3c1.7,0,3-1.3,3-3V58.2 C58.8,56.5,57.5,55.2,55.8,55.2z'/> <path className='st0' d='M81.9,55.2H64.7c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3H82c1.7,0,3-1.3,3-3V58.2C84.9,56.5,83.6,55.2,81.9,55.2z '/> <path className='st0' d='M108,55.2H90.8c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3h17.3c1.7,0,3-1.3,3-3V58.2C111,56.5,109.7,55.2,108,55.2z '/> <rect x='0.6' y='48' className='st0' width='119.3' height='1'/> </svg>\",\"collapserowsix\":\"<svg xmlns='http://www.w3.org/2000/svg' version='1.1' id='Layer_1' x='0px' y='0px' viewBox='0 0 120.5 81'>  <g> <g> <g> <g> <path className='st0' d='M116.7-616.8H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-618.4,118.7-616.8,116.7-616.8z M3.8-695.6c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-642.3H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-641,54.4-642.3,52.7-642.3z'/> <path className='st0' d='M108-642.3h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-641,109.7-642.3,108-642.3z'/> <g> <rect x='0.6' y='-649.6' className='st0' width='119.3' height='1'/> </g> <g> <g> <g> <g> <path className='st0' d='M116.7-616.8H3.8c-2,0-3.5-1.6-3.5-3.5v-73.5c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3-618.4,118.7-616.8,116.7-616.8z M3.8-695.6c-1,0-1.8,0.8-1.8,1.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8 v-73.5c0-1-0.8-1.8-1.8-1.8H3.8z'/> </g> </g> </g> </g> <path className='st0' d='M52.7-642.3H45h-4.9H12.5c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7H45h7.7c1.7,0,3-1.3,3-3v-11.8 C55.7-641,54.4-642.3,52.7-642.3z'/> <path className='st0' d='M108-642.3h-7.7h-4.9H67.8c-1.7,0-3,1.3-3,3v11.8c0,1.7,1.4,3,3,3h27.7h4.9h7.7c1.7,0,3-1.3,3-3v-11.8 C111.1-641,109.7-642.3,108-642.3z'/> <g> <rect x='0.6' y='-649.6' className='st0' width='119.3' height='1'/> </g> <g> <g> <g> <g> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8C116.7,2,3.8,2,3.8,2z'/> </g> </g> </g> </g> <g> <rect x='0.6' y='20.7' className='st0' width='119.3' height='1'/> </g> <path className='st0' d='M103.8,72.2H16.6c-1,0-1.9-0.9-1.9-1.9l0,0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9l0,0 C105.7,71.4,104.9,72.2,103.8,72.2z'/> <path className='st0' d='M103.8,64H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,63.2,104.9,64,103.8,64z'/> <path className='st0' d='M103.8,55.8H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,55,104.9,55.8,103.8,55.8z'/> <path className='st0' d='M103.8,47.6H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,46.7,104.9,47.6,103.8,47.6z'/> <path className='st0' d='M103.8,39.4H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,38.5,104.9,39.4,103.8,39.4z'/> <path className='st0' d='M103.8,31.2H16.6c-1,0-1.9-0.9-1.9-1.9v0c0-1,0.9-1.9,1.9-1.9h87.2c1,0,1.9,0.9,1.9,1.9v0 C105.7,30.3,104.9,31.2,103.8,31.2z'/> </svg>\",\"collapserowfive\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8C116.7,2,3.8,2,3.8,2z'/> <g> <path className='st0' d='M101.4,24.5H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,25.8,102.9,24.5,101.4,24.5z'/> <path className='st0' d='M101.4,11.8H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,13.1,102.9,11.8,101.4,11.8z'/> <path className='st0' d='M101.4,37.1H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,38.5,102.9,37.1,101.4,37.1z'/> <path className='st0' d='M101.4,49.7H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,51,102.9,49.7,101.4,49.7z'/> <path className='st0' d='M101.4,62.4H19.1c-1.4,0-2.6,1.3-2.6,3v0.8c0,1.7,1.3,3,2.6,3h82.4c1.4,0,2.6-1.3,2.6-3v-0.8 C104,63.8,102.9,62.4,101.4,62.4z'/> </g> </svg> \",\"grid\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8C116.7,2,3.8,2,3.8,2z'/> <path className='st0' d='M55.7,43.7H12.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8C58.7,45,57.4,43.7,55.7,43.7 z'/> <path className='st0' d='M107.7,43.7H64.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8 C110.7,45,109.4,43.7,107.7,43.7z'/> <path className='st0' d='M55.7,59.7H12.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8C58.7,61,57.4,59.7,55.7,59.7 z'/> <path className='st0' d='M107.7,59.7H64.5c-1.7,0-3,1.3-3,3v7.8c0,1.7,1.4,3,3,3h43.3c1.7,0,3-1.3,3-3v-7.8 C110.7,61,109.4,59.7,107.7,59.7z'/> <g> <rect x='0.6' y='38.2' className='st0' width='119.3' height='1'/> </g> </svg> \",\"collapserowfour\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M116.7,81.8H3.8c-2,0-3.5-1.6-3.5-3.5V4.7c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,80.2,118.7,81.8,116.7,81.8z M3.8,3C2.8,3,2,3.8,2,4.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4.8 c0-1-0.8-1.8-1.8-1.8C116.7,3,3.8,3,3.8,3z'/> <g> <path className='st0' d='M103,36.7H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3v-0.8 C105.7,38,104.5,36.7,103,36.7z'/> <path className='st0' d='M103,47.3H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3v-0.8 C105.7,48.7,104.5,47.3,103,47.3z'/> <path className='st0' d='M103,58H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3V61 C105.7,59.3,104.5,58,103,58z'/> <path className='st0' d='M103,68.6H17.4c-1.5,0-2.7,1.3-2.7,3v0.8c0,1.7,1.3,3,2.7,3H103c1.5,0,2.7-1.3,2.7-3v-0.8 C105.7,70,104.5,68.6,103,68.6z'/> </g> <g> <rect x='0.6' y='31.7' className='st0' width='119.3' height='1'/> </g> </svg> \",\"firstrow\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <rect x='0.7' y='43.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M111.1,57.9c0,1.2-0.8,2.1-1.8,2.1H11.4c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h97.9 c1,0,1.8,0.9,1.8,2.1V57.9L111.1,57.9z'/> <g> <path className='st0' d='M58.2,72.1c0,1.2-0.8,2.1-1.8,2.1h-45c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h45 c1,0,1.8,0.9,1.8,2.1V72.1L58.2,72.1z'/> </g> <g> <path className='st0' d='M111.1,72.1c0,1.2-0.8,2.1-1.8,2.1h-45c-1,0-1.8-0.9-1.8-2.1v-6.4c0-1.2,0.8-2.1,1.8-2.1h45 c1,0,1.8,0.9,1.8,2.1V72.1L111.1,72.1z'/> </g> </svg> \",\"lastrow\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <rect x='0.7' y='43.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M9.6,65.8c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v6.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V65.8L9.6,65.8z'/> <path className='st0' d='M38.5,52.6c0-1.2,0.8-2.1,1.8-2.1h69c1,0,1.8,0.9,1.8,2.1V59c0,1.2-0.8,2.1-1.8,2.1h-69c-1,0-1.8-0.9-1.8-2.1 V52.6L38.5,52.6z'/> <path className='st0' d='M9.6,52.6c0-1.2,0.8-2.1,1.8-2.1h20.9c1,0,1.8,0.9,1.8,2.1v6.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V52.6L9.6,52.6z'/> </svg>\",\"collapserowthree\":\"<svg x='0px' y='0px' viewBox='0 0 120.5 81'> <path className='st0' d='M0.3,77.4V4c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5c0,2-1.6,3.5-3.5,3.5H3.9 C1.9,81,0.4,79.4,0.3,77.4z M3.9,2.2C2.9,2.2,2.1,3,2.1,4v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V4 c0-1-0.8-1.8-1.8-1.8H3.9z'/> <rect x='0.7' y='39.2' className='st0' width='119.3' height='1'/> <path className='st0' d='M9.6,68.8c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v3.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V68.8L9.6,68.8z'/> <path className='st0' d='M9.6,57.6c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v3.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V57.6L9.6,57.6z'/> <path className='st0' d='M9.6,46.4c0-1.2,0.8-2.1,1.8-2.1h97.9c1,0,1.8,0.9,1.8,2.1v3.4c0,1.2-0.8,2.1-1.8,2.1H11.4 c-1,0-1.8-0.9-1.8-2.1V46.4L9.6,46.4z'/> </svg> \",\"row\":\"<svg viewBox='0 0 120.5 81' xmlns='http://www.w3.org/2000/svg'> <path d='M116.7,80.8H3.8c-2,0-3.5-1.6-3.5-3.5V3.8c0-2,1.6-3.5,3.5-3.5h112.9c2,0,3.5,1.6,3.5,3.5v73.5 C120.3,79.2,118.7,80.8,116.7,80.8z M3.8,2C2.8,2,2,2.8,2,3.8v73.5c0,1,0.8,1.8,1.8,1.8h112.9c1,0,1.8-0.8,1.8-1.8V3.8 c0-1-0.8-1.8-1.8-1.8H3.8z' /> <path d='M108,55.3H12.5c-1.7,0-3,1.3-3,3V70c0,1.7,1.4,3,3,3H108c1.7,0,3-1.3,3-3V58.3C111.1,56.6,109.7,55.3,108,55.3z' /> <rect x='0.6' y='48' width='119.3' height='1'/> </svg> \",\"account-2\":\"<svg version='1.1' id='Layer_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 120 120' enable-background='new 0 0 120 120' xml:space='preserve'><path fill='#1D1D1B' d='M115.4,60c0-30.5-24.8-55.4-55.4-55.4S4.6,29.5,4.6,60c0,15.6,6.5,29.7,17,39.8c0.2,0.3,0.5,0.6,0.9,0.8 c9.9,9.2,23.1,14.8,37.6,14.8s27.7-5.6,37.6-14.8c0.3-0.2,0.6-0.5,0.9-0.8C108.9,89.7,115.4,75.6,115.4,60z M60,11.5 c26.7,0,48.5,21.7,48.5,48.5c0,11.3-3.9,21.7-10.5,30c-3-9.7-11.6-17.3-23.2-20.7c6.3-4.6,10.5-12,10.5-20.4 c0-13.9-11.3-25.2-25.3-25.2S34.7,34.9,34.7,48.8c0,8.4,4.1,15.8,10.5,20.4C33.6,72.7,25,80.3,22,90c-6.5-8.3-10.5-18.7-10.5-30 C11.5,33.3,33.3,11.5,60,11.5z M41.7,48.8c0-10.1,8.2-18.3,18.3-18.3s18.3,8.2,18.3,18.3S70.1,67.1,60,67.1S41.7,58.9,41.7,48.8z M27.8,96.1c1-12.7,14.5-22.1,32.2-22.1s31.1,9.4,32.2,22.1c-8.6,7.6-19.8,12.3-32.2,12.3S36.4,103.8,27.8,96.1z'/></svg>\",\"account-3\":\"<svg version='1.1' id='Layer_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 120 120' enable-background='new 0 0 120 120' xml:space='preserve'><path fill='#1D1D1B' d='M60.8,5.9C30.3,5.9,5.6,30.5,5.6,61s24.7,55.1,55.1,55.1s55.1-24.7,55.1-55.1S91.2,5.9,60.8,5.9z M60.8,22.4 c9.1,0,16.5,7.4,16.5,16.5c0,9.2-7.4,16.5-16.5,16.5S44.2,48.1,44.2,39C44.2,29.8,51.6,22.4,60.8,22.4z M60.8,100.7 c-13.8,0-25.9-7.1-33.1-17.8C27.8,72,49.8,66,60.8,66s32.9,6,33.1,17C86.7,93.6,74.6,100.7,60.8,100.7z'/><path class='ast-hf-account-unfill' fill='none' d='M60.8,8h118.5v118.5H60.8V8z'/></svg>\",\"account-4\":\"<svg version='1.1' id='Layer_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 120 120' enable-background='new 0 0 120 120' xml:space='preserve'><g id='info'></g><path fill='#010101' d='M60.7,6.8c-14.9,0-27.1,11.9-27.1,26.7c0,9.5,5,17.8,12.6,22.5C25.1,62.3,9.4,82.3,7.5,104.7 c-0.4,4.9,3.6,8.5,8,8.5h90.4c4.4,0,8.4-3.7,8-8.5c-1.8-22.5-17.5-42.4-38.7-48.8c7.6-4.7,12.6-13,12.6-22.5 C87.9,18.6,75.6,6.8,60.7,6.8z M41.8,33.4c0-10.1,8.4-18.5,18.9-18.5s18.9,8.3,18.9,18.5c0,10.1-8.4,18.5-18.9,18.5 S41.8,43.5,41.8,33.4z M20.6,105c-2.6,0-4.7-2.2-4.2-4.8C20.6,79,39,62,60.8,62c21.8,0,40.2,17,44.3,38.3c0.5,2.6-1.6,4.8-4.2,4.8 H20.6z'/></svg>\",\"account-1\":\"<svg version='1.1' id='user' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 120 120' enable-background='new 0 0 120 120' xml:space='preserve'><path d='M84.6,62c-14.1,12.3-35.1,12.3-49.2,0C16.1,71.4,3.8,91,3.8,112.5c0,2.1,1.7,3.8,3.8,3.8h105c2.1,0,3.8-1.7,3.8-3.8 C116.2,91,103.9,71.4,84.6,62z'/><circle cx='60' cy='33.8' r='30'/></svg>\"}")},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.htmlparser2=e.convertNodeToElement=e.processNodes=void 0;var n=r(18);Object.defineProperty(e,"processNodes",{enumerable:!0,get:function(){return s(n).default}});var a=r(21);Object.defineProperty(e,"convertNodeToElement",{enumerable:!0,get:function(){return s(a).default}});var o=r(10);Object.defineProperty(e,"htmlparser2",{enumerable:!0,get:function(){return s(o).default}});var i=s(r(81));function s(t){return t&&t.__esModule?t:{default:t}}e.default=i.default},function(t,e,r){var n=r(85),a=r(86),o=r(34),i=r(87);t.exports=function(t){return n(t)||a(t)||o(t)||i()}},function(t,e){!function(){t.exports=this.wp.mediaUtils}()},function(t,e){!function(){t.exports=this.ReactDOM}()},function(t,e,r){var n=r(36),a=r(90),o=r(92),i=Math.max,s=Math.min;t.exports=function(t,e,r){var c,l,u,p,d,h,f=0,m=!1,g=!1,v=!0;if("function"!=typeof t)throw new TypeError("Expected a function");function b(e){var r=c,n=l;return c=l=void 0,f=e,p=t.apply(n,r)}function y(t){return f=t,d=setTimeout(_,e),m?b(t):p}function w(t){var r=t-h;return void 0===h||r>=e||r<0||g&&t-f>=u}function _(){var t=a();if(w(t))return O(t);d=setTimeout(_,function(t){var r=e-(t-h);return g?s(r,u-(t-f)):r}(t))}function O(t){return d=void 0,v&&c?b(t):(c=l=void 0,p)}function j(){var t=a(),r=w(t);if(c=arguments,l=this,h=t,r){if(void 0===d)return y(h);if(g)return clearTimeout(d),d=setTimeout(_,e),b(h)}return void 0===d&&(d=setTimeout(_,e)),p}return e=o(e)||0,n(r)&&(m=!!r.leading,u=(g="maxWait"in r)?i(o(r.maxWait)||0,e):u,v="trailing"in r?!!r.trailing:v),j.cancel=function(){void 0!==d&&clearTimeout(d),f=0,c=h=l=d=void 0},j.flush=function(){return void 0===d?p:O(a())},j}},function(t,e,r){"use strict";var n=r(46);function a(){}function o(){}o.resetWarningCache=a,t.exports=function(){function t(t,e,r,a,o,i){if(i!==n){var s=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw s.name="Invariant Violation",s}}function e(){return t}t.isRequired=t;var r={array:t,bool:t,func:t,number:t,object:t,string:t,symbol:t,any:t,arrayOf:e,element:t,elementType:t,instanceOf:e,node:t,objectOf:e,oneOf:e,oneOfType:e,shape:e,exact:e,checkPropTypes:o,resetWarningCache:a};return r.PropTypes=r,r}},function(t,e,r){"use strict";t.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){return"text"===t.type&&/\r?\n/.test(t.data)&&""===t.data.trim()}},function(t,e,r){"use strict";var n;Object.defineProperty(e,"__esModule",{value:!0});var a=r(10),o=l(r(72)),i=l(r(73)),s=l(r(79)),c=l(r(80));function l(t){return t&&t.__esModule?t:{default:t}}function u(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}e.default=(u(n={},a.ElementType.Text,o.default),u(n,a.ElementType.Tag,i.default),u(n,a.ElementType.Style,s.default),u(n,a.ElementType.Directive,c.default),u(n,a.ElementType.Comment,c.default),u(n,a.ElementType.Script,c.default),u(n,a.ElementType.CDATA,c.default),u(n,a.ElementType.Doctype,c.default),n)},function(t){t.exports=JSON.parse('{"0":65533,"128":8364,"130":8218,"131":402,"132":8222,"133":8230,"134":8224,"135":8225,"136":710,"137":8240,"138":352,"139":8249,"140":338,"142":381,"145":8216,"146":8217,"147":8220,"148":8221,"149":8226,"150":8211,"151":8212,"152":732,"153":8482,"154":353,"155":8250,"156":339,"158":382,"159":376}')},function(t,e,r){"use strict";var n,a="object"==typeof Reflect?Reflect:null,o=a&&"function"==typeof a.apply?a.apply:function(t,e,r){return Function.prototype.apply.call(t,e,r)};n=a&&"function"==typeof a.ownKeys?a.ownKeys:Object.getOwnPropertySymbols?function(t){return Object.getOwnPropertyNames(t).concat(Object.getOwnPropertySymbols(t))}:function(t){return Object.getOwnPropertyNames(t)};var i=Number.isNaN||function(t){return t!=t};function s(){s.init.call(this)}t.exports=s,s.EventEmitter=s,s.prototype._events=void 0,s.prototype._eventsCount=0,s.prototype._maxListeners=void 0;var c=10;function l(t){if("function"!=typeof t)throw new TypeError('The "listener" argument must be of type Function. Received type '+typeof t)}function u(t){return void 0===t._maxListeners?s.defaultMaxListeners:t._maxListeners}function p(t,e,r,n){var a,o,i,s;if(l(r),void 0===(o=t._events)?(o=t._events=Object.create(null),t._eventsCount=0):(void 0!==o.newListener&&(t.emit("newListener",e,r.listener?r.listener:r),o=t._events),i=o[e]),void 0===i)i=o[e]=r,++t._eventsCount;else if("function"==typeof i?i=o[e]=n?[r,i]:[i,r]:n?i.unshift(r):i.push(r),(a=u(t))>0&&i.length>a&&!i.warned){i.warned=!0;var c=new Error("Possible EventEmitter memory leak detected. "+i.length+" "+String(e)+" listeners added. Use emitter.setMaxListeners() to increase limit");c.name="MaxListenersExceededWarning",c.emitter=t,c.type=e,c.count=i.length,s=c,console&&console.warn&&console.warn(s)}return t}function d(){if(!this.fired)return this.target.removeListener(this.type,this.wrapFn),this.fired=!0,0===arguments.length?this.listener.call(this.target):this.listener.apply(this.target,arguments)}function h(t,e,r){var n={fired:!1,wrapFn:void 0,target:t,type:e,listener:r},a=d.bind(n);return a.listener=r,n.wrapFn=a,a}function f(t,e,r){var n=t._events;if(void 0===n)return[];var a=n[e];return void 0===a?[]:"function"==typeof a?r?[a.listener||a]:[a]:r?function(t){for(var e=new Array(t.length),r=0;r<e.length;++r)e[r]=t[r].listener||t[r];return e}(a):g(a,a.length)}function m(t){var e=this._events;if(void 0!==e){var r=e[t];if("function"==typeof r)return 1;if(void 0!==r)return r.length}return 0}function g(t,e){for(var r=new Array(e),n=0;n<e;++n)r[n]=t[n];return r}Object.defineProperty(s,"defaultMaxListeners",{enumerable:!0,get:function(){return c},set:function(t){if("number"!=typeof t||t<0||i(t))throw new RangeError('The value of "defaultMaxListeners" is out of range. It must be a non-negative number. Received '+t+".");c=t}}),s.init=function(){void 0!==this._events&&this._events!==Object.getPrototypeOf(this)._events||(this._events=Object.create(null),this._eventsCount=0),this._maxListeners=this._maxListeners||void 0},s.prototype.setMaxListeners=function(t){if("number"!=typeof t||t<0||i(t))throw new RangeError('The value of "n" is out of range. It must be a non-negative number. Received '+t+".");return this._maxListeners=t,this},s.prototype.getMaxListeners=function(){return u(this)},s.prototype.emit=function(t){for(var e=[],r=1;r<arguments.length;r++)e.push(arguments[r]);var n="error"===t,a=this._events;if(void 0!==a)n=n&&void 0===a.error;else if(!n)return!1;if(n){var i;if(e.length>0&&(i=e[0]),i instanceof Error)throw i;var s=new Error("Unhandled error."+(i?" ("+i.message+")":""));throw s.context=i,s}var c=a[t];if(void 0===c)return!1;if("function"==typeof c)o(c,this,e);else{var l=c.length,u=g(c,l);for(r=0;r<l;++r)o(u[r],this,e)}return!0},s.prototype.addListener=function(t,e){return p(this,t,e,!1)},s.prototype.on=s.prototype.addListener,s.prototype.prependListener=function(t,e){return p(this,t,e,!0)},s.prototype.once=function(t,e){return l(e),this.on(t,h(this,t,e)),this},s.prototype.prependOnceListener=function(t,e){return l(e),this.prependListener(t,h(this,t,e)),this},s.prototype.removeListener=function(t,e){var r,n,a,o,i;if(l(e),void 0===(n=this._events))return this;if(void 0===(r=n[t]))return this;if(r===e||r.listener===e)0==--this._eventsCount?this._events=Object.create(null):(delete n[t],n.removeListener&&this.emit("removeListener",t,r.listener||e));else if("function"!=typeof r){for(a=-1,o=r.length-1;o>=0;o--)if(r[o]===e||r[o].listener===e){i=r[o].listener,a=o;break}if(a<0)return this;0===a?r.shift():function(t,e){for(;e+1<t.length;e++)t[e]=t[e+1];t.pop()}(r,a),1===r.length&&(n[t]=r[0]),void 0!==n.removeListener&&this.emit("removeListener",t,i||e)}return this},s.prototype.off=s.prototype.removeListener,s.prototype.removeAllListeners=function(t){var e,r,n;if(void 0===(r=this._events))return this;if(void 0===r.removeListener)return 0===arguments.length?(this._events=Object.create(null),this._eventsCount=0):void 0!==r[t]&&(0==--this._eventsCount?this._events=Object.create(null):delete r[t]),this;if(0===arguments.length){var a,o=Object.keys(r);for(n=0;n<o.length;++n)"removeListener"!==(a=o[n])&&this.removeAllListeners(a);return this.removeAllListeners("removeListener"),this._events=Object.create(null),this._eventsCount=0,this}if("function"==typeof(e=r[t]))this.removeListener(t,e);else if(void 0!==e)for(n=e.length-1;n>=0;n--)this.removeListener(t,e[n]);return this},s.prototype.listeners=function(t){return f(this,t,!0)},s.prototype.rawListeners=function(t){return f(this,t,!1)},s.listenerCount=function(t,e){return"function"==typeof t.listenerCount?t.listenerCount(e):m.call(t,e)},s.prototype.listenerCount=m,s.prototype.eventNames=function(){return this._eventsCount>0?n(this._events):[]}},function(t,e,r){var n=r(27),a=t.exports=Object.create(n),o={tagName:"name"};Object.keys(o).forEach((function(t){var e=o[t];Object.defineProperty(a,t,{get:function(){return this[e]||null},set:function(t){return this[e]=t,t}})}))},function(t,e,r){var n=r(26),a=r(28);function o(t,e){this.init(t,e)}function i(t,e){return a.getElementsByTagName(t,e,!0)}function s(t,e){return a.getElementsByTagName(t,e,!0,1)[0]}function c(t,e,r){return a.getText(a.getElementsByTagName(t,e,r,1)).trim()}function l(t,e,r,n,a){var o=c(r,n,a);o&&(t[e]=o)}r(16)(o,n),o.prototype.init=n;var u=function(t){return"rss"===t||"feed"===t||"rdf:RDF"===t};o.prototype.onend=function(){var t,e,r={},a=s(u,this.dom);a&&("feed"===a.name?(e=a.children,r.type="atom",l(r,"id","id",e),l(r,"title","title",e),(t=s("link",e))&&(t=t.attribs)&&(t=t.href)&&(r.link=t),l(r,"description","subtitle",e),(t=c("updated",e))&&(r.updated=new Date(t)),l(r,"author","email",e,!0),r.items=i("entry",e).map((function(t){var e,r={};return l(r,"id","id",t=t.children),l(r,"title","title",t),(e=s("link",t))&&(e=e.attribs)&&(e=e.href)&&(r.link=e),(e=c("summary",t)||c("content",t))&&(r.description=e),(e=c("updated",t))&&(r.pubDate=new Date(e)),r}))):(e=s("channel",a.children).children,r.type=a.name.substr(0,3),r.id="",l(r,"title","title",e),l(r,"link","link",e),l(r,"description","description",e),(t=c("lastBuildDate",e))&&(r.updated=new Date(t)),l(r,"author","managingEditor",e,!0),r.items=i("item",a.children).map((function(t){var e,r={};return l(r,"id","guid",t=t.children),l(r,"title","title",t),l(r,"link","link",t),l(r,"description","description",t),(e=c("pubDate",t))&&(r.pubDate=new Date(e)),r})))),this.dom=r,n.prototype._handleCallback.call(this,a?null:Error("couldn't find root of feed"))},t.exports=o},function(t,e,r){var n=r(11),a=r(54),o=n.isTag;t.exports={getInnerHTML:function(t,e){return t.children?t.children.map((function(t){return a(t,e)})).join(""):""},getOuterHTML:a,getText:function t(e){return Array.isArray(e)?e.map(t).join(""):o(e)||e.type===n.CDATA?t(e.children):e.type===n.Text?e.data:""}}},function(t,e,r){var n=r(11),a=r(55),o={__proto__:null,style:!0,script:!0,xmp:!0,iframe:!0,noembed:!0,noframes:!0,plaintext:!0,noscript:!0};var i={__proto__:null,area:!0,base:!0,basefont:!0,br:!0,col:!0,command:!0,embed:!0,frame:!0,hr:!0,img:!0,input:!0,isindex:!0,keygen:!0,link:!0,meta:!0,param:!0,source:!0,track:!0,wbr:!0},s=t.exports=function(t,e){Array.isArray(t)||t.cheerio||(t=[t]),e=e||{};for(var r="",a=0;a<t.length;a++){var o=t[a];"root"===o.type?r+=s(o.children,e):n.isTag(o)?r+=c(o,e):o.type===n.Directive?r+=l(o):o.type===n.Comment?r+=d(o):o.type===n.CDATA?r+=p(o):r+=u(o,e)}return r};function c(t,e){"svg"===t.name&&(e={decodeEntities:e.decodeEntities,xmlMode:!0});var r="<"+t.name,n=function(t,e){if(t){var r,n="";for(var o in t)n&&(n+=" "),n+=o,(null!==(r=t[o])&&""!==r||e.xmlMode)&&(n+='="'+(e.decodeEntities?a.encodeXML(r):r)+'"');return n}}(t.attribs,e);return n&&(r+=" "+n),!e.xmlMode||t.children&&0!==t.children.length?(r+=">",t.children&&(r+=s(t.children,e)),i[t.name]&&!e.xmlMode||(r+="</"+t.name+">")):r+="/>",r}function l(t){return"<"+t.data+">"}function u(t,e){var r=t.data||"";return!e.decodeEntities||t.parent&&t.parent.name in o||(r=a.encodeXML(r)),r}function p(t){return"<![CDATA["+t.children[0].data+"]]>"}function d(t){return"\x3c!--"+t.data+"--\x3e"}},function(t,e,r){var n=r(56),a=r(57);e.decode=function(t,e){return(!e||e<=0?a.XML:a.HTML)(t)},e.decodeStrict=function(t,e){return(!e||e<=0?a.XML:a.HTMLStrict)(t)},e.encode=function(t,e){return(!e||e<=0?n.XML:n.HTML)(t)},e.encodeXML=n.XML,e.encodeHTML4=e.encodeHTML5=e.encodeHTML=n.HTML,e.decodeXML=e.decodeXMLStrict=a.XML,e.decodeHTML4=e.decodeHTML5=e.decodeHTML=a.HTML,e.decodeHTML4Strict=e.decodeHTML5Strict=e.decodeHTMLStrict=a.HTMLStrict,e.escape=n.escape},function(t,e,r){var n=s(r(20)),a=c(n);e.XML=h(n,a);var o=s(r(19)),i=c(o);function s(t){return Object.keys(t).sort().reduce((function(e,r){return e[t[r]]="&"+r+";",e}),{})}function c(t){var e=[],r=[];return Object.keys(t).forEach((function(t){1===t.length?e.push("\\"+t):r.push(t)})),r.unshift("["+e.join("")+"]"),new RegExp(r.join("|"),"g")}e.HTML=h(o,i);var l=/[^\0-\x7F]/g,u=/[\uD800-\uDBFF][\uDC00-\uDFFF]/g;function p(t){return"&#x"+t.charCodeAt(0).toString(16).toUpperCase()+";"}function d(t){return"&#x"+(1024*(t.charCodeAt(0)-55296)+t.charCodeAt(1)-56320+65536).toString(16).toUpperCase()+";"}function h(t,e){function r(e){return t[e]}return function(t){return t.replace(e,r).replace(u,d).replace(l,p)}}var f=c(n);e.escape=function(t){return t.replace(f,p).replace(u,d).replace(l,p)}},function(t,e,r){var n=r(19),a=r(25),o=r(20),i=r(24),s=l(o),c=l(n);function l(t){var e=Object.keys(t).join("|"),r=d(t),n=new RegExp("&(?:"+(e+="|#[xX][\\da-fA-F]+|#\\d+")+");","g");return function(t){return String(t).replace(n,r)}}var u=function(){for(var t=Object.keys(a).sort(p),e=Object.keys(n).sort(p),r=0,o=0;r<e.length;r++)t[o]===e[r]?(e[r]+=";?",o++):e[r]+=";";var i=new RegExp("&(?:"+e.join("|")+"|#[xX][\\da-fA-F]+;?|#\\d+;?)","g"),s=d(n);function c(t){return";"!==t.substr(-1)&&(t+=";"),s(t)}return function(t){return String(t).replace(i,c)}}();function p(t,e){return t<e?1:-1}function d(t){return function(e){return"#"===e.charAt(1)?"X"===e.charAt(2)||"x"===e.charAt(2)?i(parseInt(e.substr(3),16)):i(parseInt(e.substr(2),10)):t[e.slice(1,-1)]}}t.exports={XML:s,HTML:u,HTMLStrict:c}},function(t,e){var r=e.getChildren=function(t){return t.children},n=e.getParent=function(t){return t.parent};e.getSiblings=function(t){var e=n(t);return e?r(e):[t]},e.getAttributeValue=function(t,e){return t.attribs&&t.attribs[e]},e.hasAttrib=function(t,e){return!!t.attribs&&hasOwnProperty.call(t.attribs,e)},e.getName=function(t){return t.name}},function(t,e){e.removeElement=function(t){if(t.prev&&(t.prev.next=t.next),t.next&&(t.next.prev=t.prev),t.parent){var e=t.parent.children;e.splice(e.lastIndexOf(t),1)}},e.replaceElement=function(t,e){var r=e.prev=t.prev;r&&(r.next=e);var n=e.next=t.next;n&&(n.prev=e);var a=e.parent=t.parent;if(a){var o=a.children;o[o.lastIndexOf(t)]=e}},e.appendChild=function(t,e){if(e.parent=t,1!==t.children.push(e)){var r=t.children[t.children.length-2];r.next=e,e.prev=r,e.next=null}},e.append=function(t,e){var r=t.parent,n=t.next;if(e.next=n,e.prev=t,t.next=e,e.parent=r,n){if(n.prev=e,r){var a=r.children;a.splice(a.lastIndexOf(n),0,e)}}else r&&r.children.push(e)},e.prepend=function(t,e){var r=t.parent;if(r){var n=r.children;n.splice(n.lastIndexOf(t),0,e)}t.prev&&(t.prev.next=e),e.parent=r,e.prev=t.prev,e.next=t,t.prev=e}},function(t,e,r){var n=r(11).isTag;function a(t,e,r,n){for(var o,i=[],s=0,c=e.length;s<c&&!(t(e[s])&&(i.push(e[s]),--n<=0))&&(o=e[s].children,!(r&&o&&o.length>0&&(o=a(t,o,r,n),i=i.concat(o),(n-=o.length)<=0)));s++);return i}t.exports={filter:function(t,e,r,n){Array.isArray(e)||(e=[e]);"number"==typeof n&&isFinite(n)||(n=1/0);return a(t,e,!1!==r,n)},find:a,findOneChild:function(t,e){for(var r=0,n=e.length;r<n;r++)if(t(e[r]))return e[r];return null},findOne:function t(e,r){for(var a=null,o=0,i=r.length;o<i&&!a;o++)n(r[o])&&(e(r[o])?a=r[o]:r[o].children.length>0&&(a=t(e,r[o].children)));return a},existsOne:function t(e,r){for(var a=0,o=r.length;a<o;a++)if(n(r[a])&&(e(r[a])||r[a].children.length>0&&t(e,r[a].children)))return!0;return!1},findAll:function t(e,r){for(var a=[],o=0,i=r.length;o<i;o++)n(r[o])&&(e(r[o])&&a.push(r[o]),r[o].children.length>0&&(a=a.concat(t(e,r[o].children))));return a}}},function(t,e,r){var n=r(11),a=e.isTag=n.isTag;e.testElement=function(t,e){for(var r in t)if(t.hasOwnProperty(r)){if("tag_name"===r){if(!a(e)||!t.tag_name(e.name))return!1}else if("tag_type"===r){if(!t.tag_type(e.type))return!1}else if("tag_contains"===r){if(a(e)||!t.tag_contains(e.data))return!1}else if(!e.attribs||!t[r](e.attribs[r]))return!1}else;return!0};var o={tag_name:function(t){return"function"==typeof t?function(e){return a(e)&&t(e.name)}:"*"===t?a:function(e){return a(e)&&e.name===t}},tag_type:function(t){return"function"==typeof t?function(e){return t(e.type)}:function(e){return e.type===t}},tag_contains:function(t){return"function"==typeof t?function(e){return!a(e)&&t(e.data)}:function(e){return!a(e)&&e.data===t}}};function i(t,e){return"function"==typeof e?function(r){return r.attribs&&e(r.attribs[t])}:function(r){return r.attribs&&r.attribs[t]===e}}function s(t,e){return function(r){return t(r)||e(r)}}e.getElements=function(t,e,r,n){var a=Object.keys(t).map((function(e){var r=t[e];return e in o?o[e](r):i(e,r)}));return 0===a.length?[]:this.filter(a.reduce(s),e,r,n)},e.getElementById=function(t,e,r){return Array.isArray(e)||(e=[e]),this.findOne(i("id",t),e,!1!==r)},e.getElementsByTagName=function(t,e,r,n){return this.filter(o.tag_name(t),e,r,n)},e.getElementsByTagType=function(t,e,r,n){return this.filter(o.tag_type(t),e,r,n)}},function(t,e){e.removeSubsets=function(t){for(var e,r,n,a=t.length;--a>-1;){for(e=r=t[a],t[a]=null,n=!0;r;){if(t.indexOf(r)>-1){n=!1,t.splice(a,1);break}r=r.parent}n&&(t[a]=e)}return t};var r=1,n=2,a=4,o=8,i=16,s=e.compareDocumentPosition=function(t,e){var s,c,l,u,p,d,h=[],f=[];if(t===e)return 0;for(s=t;s;)h.unshift(s),s=s.parent;for(s=e;s;)f.unshift(s),s=s.parent;for(d=0;h[d]===f[d];)d++;return 0===d?r:(l=(c=h[d-1]).children,u=h[d],p=f[d],l.indexOf(u)>l.indexOf(p)?c===e?a|i:a:c===t?n|o:n)};e.uniqueSort=function(t){var e,r,o=t.length;for(t=t.slice();--o>-1;)e=t[o],(r=t.indexOf(e))>-1&&r<o&&t.splice(o,1);return t.sort((function(t,e){var r=s(t,e);return r&n?-1:r&a?1:0})),t}},function(t,e,r){t.exports=a;var n=r(29);function a(t){n.call(this,new o(this),t)}function o(t){this.scope=t}r(16)(a,n),a.prototype.readable=!0;var i=r(10).EVENTS;Object.keys(i).forEach((function(t){if(0===i[t])o.prototype["on"+t]=function(){this.scope.emit(t)};else if(1===i[t])o.prototype["on"+t]=function(e){this.scope.emit(t,e)};else{if(2!==i[t])throw Error("wrong number of arguments!");o.prototype["on"+t]=function(e,r){this.scope.emit(t,e,r)}}}))},function(t,e){},function(t,e,r){"use strict";var n=r(66).Buffer,a=n.isEncoding||function(t){switch((t=""+t)&&t.toLowerCase()){case"hex":case"utf8":case"utf-8":case"ascii":case"binary":case"base64":case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":case"raw":return!0;default:return!1}};function o(t){var e;switch(this.encoding=function(t){var e=function(t){if(!t)return"utf8";for(var e;;)switch(t){case"utf8":case"utf-8":return"utf8";case"ucs2":case"ucs-2":case"utf16le":case"utf-16le":return"utf16le";case"latin1":case"binary":return"latin1";case"base64":case"ascii":case"hex":return t;default:if(e)return;t=(""+t).toLowerCase(),e=!0}}(t);if("string"!=typeof e&&(n.isEncoding===a||!a(t)))throw new Error("Unknown encoding: "+t);return e||t}(t),this.encoding){case"utf16le":this.text=c,this.end=l,e=4;break;case"utf8":this.fillLast=s,e=4;break;case"base64":this.text=u,this.end=p,e=3;break;default:return this.write=d,void(this.end=h)}this.lastNeed=0,this.lastTotal=0,this.lastChar=n.allocUnsafe(e)}function i(t){return t<=127?0:t>>5==6?2:t>>4==14?3:t>>3==30?4:t>>6==2?-1:-2}function s(t){var e=this.lastTotal-this.lastNeed,r=function(t,e,r){if(128!=(192&e[0]))return t.lastNeed=0,"�";if(t.lastNeed>1&&e.length>1){if(128!=(192&e[1]))return t.lastNeed=1,"�";if(t.lastNeed>2&&e.length>2&&128!=(192&e[2]))return t.lastNeed=2,"�"}}(this,t);return void 0!==r?r:this.lastNeed<=t.length?(t.copy(this.lastChar,e,0,this.lastNeed),this.lastChar.toString(this.encoding,0,this.lastTotal)):(t.copy(this.lastChar,e,0,t.length),void(this.lastNeed-=t.length))}function c(t,e){if((t.length-e)%2==0){var r=t.toString("utf16le",e);if(r){var n=r.charCodeAt(r.length-1);if(n>=55296&&n<=56319)return this.lastNeed=2,this.lastTotal=4,this.lastChar[0]=t[t.length-2],this.lastChar[1]=t[t.length-1],r.slice(0,-1)}return r}return this.lastNeed=1,this.lastTotal=2,this.lastChar[0]=t[t.length-1],t.toString("utf16le",e,t.length-1)}function l(t){var e=t&&t.length?this.write(t):"";if(this.lastNeed){var r=this.lastTotal-this.lastNeed;return e+this.lastChar.toString("utf16le",0,r)}return e}function u(t,e){var r=(t.length-e)%3;return 0===r?t.toString("base64",e):(this.lastNeed=3-r,this.lastTotal=3,1===r?this.lastChar[0]=t[t.length-1]:(this.lastChar[0]=t[t.length-2],this.lastChar[1]=t[t.length-1]),t.toString("base64",e,t.length-r))}function p(t){var e=t&&t.length?this.write(t):"";return this.lastNeed?e+this.lastChar.toString("base64",0,3-this.lastNeed):e}function d(t){return t.toString(this.encoding)}function h(t){return t&&t.length?this.write(t):""}e.StringDecoder=o,o.prototype.write=function(t){if(0===t.length)return"";var e,r;if(this.lastNeed){if(void 0===(e=this.fillLast(t)))return"";r=this.lastNeed,this.lastNeed=0}else r=0;return r<t.length?e?e+this.text(t,r):this.text(t,r):e||""},o.prototype.end=function(t){var e=t&&t.length?this.write(t):"";return this.lastNeed?e+"�":e},o.prototype.text=function(t,e){var r=function(t,e,r){var n=e.length-1;if(n<r)return 0;var a=i(e[n]);if(a>=0)return a>0&&(t.lastNeed=a-1),a;if(--n<r||-2===a)return 0;if((a=i(e[n]))>=0)return a>0&&(t.lastNeed=a-2),a;if(--n<r||-2===a)return 0;if((a=i(e[n]))>=0)return a>0&&(2===a?a=0:t.lastNeed=a-3),a;return 0}(this,t,e);if(!this.lastNeed)return t.toString("utf8",e);this.lastTotal=r;var n=t.length-(r-this.lastNeed);return t.copy(this.lastChar,0,n),t.toString("utf8",e,n)},o.prototype.fillLast=function(t){if(this.lastNeed<=t.length)return t.copy(this.lastChar,this.lastTotal-this.lastNeed,0,this.lastNeed),this.lastChar.toString(this.encoding,0,this.lastTotal);t.copy(this.lastChar,this.lastTotal-this.lastNeed,0,t.length),this.lastNeed-=t.length}},function(t,e,r){var n=r(30),a=n.Buffer;function o(t,e){for(var r in t)e[r]=t[r]}function i(t,e,r){return a(t,e,r)}a.from&&a.alloc&&a.allocUnsafe&&a.allocUnsafeSlow?t.exports=n:(o(n,e),e.Buffer=i),o(a,i),i.from=function(t,e,r){if("number"==typeof t)throw new TypeError("Argument must not be a number");return a(t,e,r)},i.alloc=function(t,e,r){if("number"!=typeof t)throw new TypeError("Argument must be a number");var n=a(t);return void 0!==e?"string"==typeof r?n.fill(e,r):n.fill(e):n.fill(0),n},i.allocUnsafe=function(t){if("number"!=typeof t)throw new TypeError("Argument must be a number");return a(t)},i.allocUnsafeSlow=function(t){if("number"!=typeof t)throw new TypeError("Argument must be a number");return n.SlowBuffer(t)}},function(t,e,r){"use strict";e.byteLength=function(t){var e=l(t),r=e[0],n=e[1];return 3*(r+n)/4-n},e.toByteArray=function(t){var e,r,n=l(t),i=n[0],s=n[1],c=new o(function(t,e,r){return 3*(e+r)/4-r}(0,i,s)),u=0,p=s>0?i-4:i;for(r=0;r<p;r+=4)e=a[t.charCodeAt(r)]<<18|a[t.charCodeAt(r+1)]<<12|a[t.charCodeAt(r+2)]<<6|a[t.charCodeAt(r+3)],c[u++]=e>>16&255,c[u++]=e>>8&255,c[u++]=255&e;2===s&&(e=a[t.charCodeAt(r)]<<2|a[t.charCodeAt(r+1)]>>4,c[u++]=255&e);1===s&&(e=a[t.charCodeAt(r)]<<10|a[t.charCodeAt(r+1)]<<4|a[t.charCodeAt(r+2)]>>2,c[u++]=e>>8&255,c[u++]=255&e);return c},e.fromByteArray=function(t){for(var e,r=t.length,a=r%3,o=[],i=0,s=r-a;i<s;i+=16383)o.push(u(t,i,i+16383>s?s:i+16383));1===a?(e=t[r-1],o.push(n[e>>2]+n[e<<4&63]+"==")):2===a&&(e=(t[r-2]<<8)+t[r-1],o.push(n[e>>10]+n[e>>4&63]+n[e<<2&63]+"="));return o.join("")};for(var n=[],a=[],o="undefined"!=typeof Uint8Array?Uint8Array:Array,i="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",s=0,c=i.length;s<c;++s)n[s]=i[s],a[i.charCodeAt(s)]=s;function l(t){var e=t.length;if(e%4>0)throw new Error("Invalid string. Length must be a multiple of 4");var r=t.indexOf("=");return-1===r&&(r=e),[r,r===e?0:4-r%4]}function u(t,e,r){for(var a,o,i=[],s=e;s<r;s+=3)a=(t[s]<<16&16711680)+(t[s+1]<<8&65280)+(255&t[s+2]),i.push(n[(o=a)>>18&63]+n[o>>12&63]+n[o>>6&63]+n[63&o]);return i.join("")}a["-".charCodeAt(0)]=62,a["_".charCodeAt(0)]=63},function(t,e){e.read=function(t,e,r,n,a){var o,i,s=8*a-n-1,c=(1<<s)-1,l=c>>1,u=-7,p=r?a-1:0,d=r?-1:1,h=t[e+p];for(p+=d,o=h&(1<<-u)-1,h>>=-u,u+=s;u>0;o=256*o+t[e+p],p+=d,u-=8);for(i=o&(1<<-u)-1,o>>=-u,u+=n;u>0;i=256*i+t[e+p],p+=d,u-=8);if(0===o)o=1-l;else{if(o===c)return i?NaN:1/0*(h?-1:1);i+=Math.pow(2,n),o-=l}return(h?-1:1)*i*Math.pow(2,o-n)},e.write=function(t,e,r,n,a,o){var i,s,c,l=8*o-a-1,u=(1<<l)-1,p=u>>1,d=23===a?Math.pow(2,-24)-Math.pow(2,-77):0,h=n?0:o-1,f=n?1:-1,m=e<0||0===e&&1/e<0?1:0;for(e=Math.abs(e),isNaN(e)||e===1/0?(s=isNaN(e)?1:0,i=u):(i=Math.floor(Math.log(e)/Math.LN2),e*(c=Math.pow(2,-i))<1&&(i--,c*=2),(e+=i+p>=1?d/c:d*Math.pow(2,1-p))*c>=2&&(i++,c/=2),i+p>=u?(s=0,i=u):i+p>=1?(s=(e*c-1)*Math.pow(2,a),i+=p):(s=e*Math.pow(2,p-1)*Math.pow(2,a),i=0));a>=8;t[r+h]=255&s,h+=f,s/=256,a-=8);for(i=i<<a|s,l+=a;l>0;t[r+h]=255&i,h+=f,i/=256,l-=8);t[r+h-f]|=128*m}},function(t,e){var r={}.toString;t.exports=Array.isArray||function(t){return"[object Array]"==r.call(t)}},function(t,e,r){function n(t){this._cbs=t||{}}t.exports=n;var a=r(10).EVENTS;Object.keys(a).forEach((function(t){if(0===a[t])t="on"+t,n.prototype[t]=function(){this._cbs[t]&&this._cbs[t]()};else if(1===a[t])t="on"+t,n.prototype[t]=function(e){this._cbs[t]&&this._cbs[t](e)};else{if(2!==a[t])throw Error("wrong number of arguments");t="on"+t,n.prototype[t]=function(e,r){this._cbs[t]&&this._cbs[t](e,r)}}}))},function(t,e,r){function n(t){this._cbs=t||{},this.events=[]}t.exports=n;var a=r(10).EVENTS;Object.keys(a).forEach((function(t){if(0===a[t])t="on"+t,n.prototype[t]=function(){this.events.push([t]),this._cbs[t]&&this._cbs[t]()};else if(1===a[t])t="on"+t,n.prototype[t]=function(e){this.events.push([t,e]),this._cbs[t]&&this._cbs[t](e)};else{if(2!==a[t])throw Error("wrong number of arguments");t="on"+t,n.prototype[t]=function(e,r){this.events.push([t,e,r]),this._cbs[t]&&this._cbs[t](e,r)}}})),n.prototype.onreset=function(){this.events=[],this._cbs.onreset&&this._cbs.onreset()},n.prototype.restart=function(){this._cbs.onreset&&this._cbs.onreset();for(var t=0,e=this.events.length;t<e;t++)if(this._cbs[this.events[t][0]]){var r=this.events[t].length;1===r?this._cbs[this.events[t][0]]():2===r?this._cbs[this.events[t][0]](this.events[t][1]):this._cbs[this.events[t][0]](this.events[t][1],this.events[t][2])}}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){return t.data}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t,e,r){var c=t.name;if(!(0,s.default)(c))return null;var l=(0,o.default)(t.attribs,e),u=null;-1===i.default.indexOf(c)&&(u=(0,a.default)(t.children,r));return n.default.createElement(c,l,u)};var n=c(r(3)),a=c(r(18)),o=c(r(32)),i=c(r(78)),s=c(r(33));function c(t){return t&&t.__esModule?t:{default:t}}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){return Object.keys(t).filter((function(t){return(0,o.default)(t)})).reduce((function(e,r){var o=r.toLowerCase(),i=a.default[o]||o;return e[i]=function(t,e){n.default.map((function(t){return t.toLowerCase()})).indexOf(t.toLowerCase())>=0&&(e=t);return e}(i,t[r]),e}),{})};var n=i(r(75)),a=i(r(76)),o=i(r(33));function i(t){return t&&t.__esModule?t:{default:t}}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=["allowfullScreen","async","autoplay","capture","checked","controls","default","defer","disabled","formnovalidate","hidden","loop","multiple","muted","novalidate","open","playsinline","readonly","required","reversed","scoped","seamless","selected","itemscope"]},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={accept:"accept","accept-charset":"acceptCharset",accesskey:"accessKey",action:"action",allowfullscreen:"allowFullScreen",allowtransparency:"allowTransparency",alt:"alt",as:"as",async:"async",autocomplete:"autoComplete",autoplay:"autoPlay",capture:"capture",cellpadding:"cellPadding",cellspacing:"cellSpacing",charset:"charSet",challenge:"challenge",checked:"checked",cite:"cite",classid:"classID",class:"className",cols:"cols",colspan:"colSpan",content:"content",contenteditable:"contentEditable",contextmenu:"contextMenu",controls:"controls",controlsList:"controlsList",coords:"coords",crossorigin:"crossOrigin",data:"data",datetime:"dateTime",default:"default",defer:"defer",dir:"dir",disabled:"disabled",download:"download",draggable:"draggable",enctype:"encType",form:"form",formaction:"formAction",formenctype:"formEncType",formmethod:"formMethod",formnovalidate:"formNoValidate",formtarget:"formTarget",frameborder:"frameBorder",headers:"headers",height:"height",hidden:"hidden",high:"high",href:"href",hreflang:"hrefLang",for:"htmlFor","http-equiv":"httpEquiv",icon:"icon",id:"id",inputmode:"inputMode",integrity:"integrity",is:"is",keyparams:"keyParams",keytype:"keyType",kind:"kind",label:"label",lang:"lang",list:"list",loop:"loop",low:"low",manifest:"manifest",marginheight:"marginHeight",marginwidth:"marginWidth",max:"max",maxlength:"maxLength",media:"media",mediagroup:"mediaGroup",method:"method",min:"min",minlength:"minLength",multiple:"multiple",muted:"muted",name:"name",nonce:"nonce",novalidate:"noValidate",open:"open",optimum:"optimum",pattern:"pattern",placeholder:"placeholder",playsinline:"playsInline",poster:"poster",preload:"preload",profile:"profile",radiogroup:"radioGroup",readonly:"readOnly",referrerpolicy:"referrerPolicy",rel:"rel",required:"required",reversed:"reversed",role:"role",rows:"rows",rowspan:"rowSpan",sandbox:"sandbox",scope:"scope",scoped:"scoped",scrolling:"scrolling",seamless:"seamless",selected:"selected",shape:"shape",size:"size",sizes:"sizes",slot:"slot",span:"span",spellcheck:"spellCheck",src:"src",srcdoc:"srcDoc",srclang:"srcLang",srcset:"srcSet",start:"start",step:"step",style:"style",summary:"summary",tabindex:"tabIndex",target:"target",title:"title",type:"type",usemap:"useMap",value:"value",width:"width",wmode:"wmode",wrap:"wrap",about:"about",datatype:"datatype",inlist:"inlist",prefix:"prefix",property:"property",resource:"resource",typeof:"typeof",vocab:"vocab",autocapitalize:"autoCapitalize",autocorrect:"autoCorrect",autosave:"autoSave",color:"color",itemprop:"itemProp",itemscope:"itemScope",itemtype:"itemType",itemid:"itemID",itemref:"itemRef",results:"results",security:"security",unselectable:"unselectable"}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=function(t,e){if(Array.isArray(t))return t;if(Symbol.iterator in Object(t))return function(t,e){var r=[],n=!0,a=!1,o=void 0;try{for(var i,s=t[Symbol.iterator]();!(n=(i=s.next()).done)&&(r.push(i.value),!e||r.length!==e);n=!0);}catch(t){a=!0,o=t}finally{try{!n&&s.return&&s.return()}finally{if(a)throw o}}return r}(t,e);throw new TypeError("Invalid attempt to destructure non-iterable instance")};e.default=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";if(""===t)return{};return t.split(";").reduce((function(t,e){var r=e.split(/^([^:]+):/).filter((function(t,e){return e>0})).map((function(t){return t.trim().toLowerCase()})),a=n(r,2),o=a[0],i=a[1];return void 0===i||(t[o=o.replace(/^-ms-/,"ms-").replace(/-(.)/g,(function(t,e){return e.toUpperCase()}))]=i),t}),{})}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=["area","base","br","col","command","embed","hr","img","input","keygen","link","meta","param","source","track","wbr"]},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t,e){var r=void 0;t.children.length>0&&(r=t.children[0].data);var o=(0,a.default)(t.attribs,e);return n.default.createElement("style",o,r)};var n=o(r(3)),a=o(r(32));function o(t){return t&&t.__esModule?t:{default:t}}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(){return null}},function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},r=e.decodeEntities,o=void 0===r||r,i=e.transform,s=e.preprocessNodes,c=void 0===s?function(t){return t}:s,l=c(n.default.parseDOM(t,{decodeEntities:o}));return(0,a.default)(l,i)};var n=o(r(10)),a=o(r(18));function o(t){return t&&t.__esModule?t:{default:t}}},function(t,e){t.exports=function(t){if(Array.isArray(t))return t}},function(t,e){t.exports=function(t,e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t)){var r=[],n=!0,a=!1,o=void 0;try{for(var i,s=t[Symbol.iterator]();!(n=(i=s.next()).done)&&(r.push(i.value),!e||r.length!==e);n=!0);}catch(t){a=!0,o=t}finally{try{n||null==s.return||s.return()}finally{if(a)throw o}}return r}}},function(t,e){t.exports=function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},function(t,e,r){var n=r(35);t.exports=function(t){if(Array.isArray(t))return n(t)}},function(t,e){t.exports=function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}},function(t,e){t.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},function(t,e){function r(e,n){return t.exports=r=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},r(e,n)}t.exports=r},function(t,e){function r(e){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?t.exports=r=function(t){return typeof t}:t.exports=r=function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},r(e)}t.exports=r},function(t,e,r){var n=r(37);t.exports=function(){return n.Date.now()}},function(t,e,r){(function(e){var r="object"==typeof e&&e&&e.Object===Object&&e;t.exports=r}).call(this,r(31))},function(t,e,r){var n=r(36),a=r(93),o=/^\s+|\s+$/g,i=/^[-+]0x[0-9a-f]+$/i,s=/^0b[01]+$/i,c=/^0o[0-7]+$/i,l=parseInt;t.exports=function(t){if("number"==typeof t)return t;if(a(t))return NaN;if(n(t)){var e="function"==typeof t.valueOf?t.valueOf():t;t=n(e)?e+"":e}if("string"!=typeof t)return 0===t?t:+t;t=t.replace(o,"");var r=s.test(t);return r||c.test(t)?l(t.slice(2),r?2:8):i.test(t)?NaN:+t}},function(t,e,r){var n=r(94),a=r(97);t.exports=function(t){return"symbol"==typeof t||a(t)&&"[object Symbol]"==n(t)}},function(t,e,r){var n=r(38),a=r(95),o=r(96),i=n?n.toStringTag:void 0;t.exports=function(t){return null==t?void 0===t?"[object Undefined]":"[object Null]":i&&i in Object(t)?a(t):o(t)}},function(t,e,r){var n=r(38),a=Object.prototype,o=a.hasOwnProperty,i=a.toString,s=n?n.toStringTag:void 0;t.exports=function(t){var e=o.call(t,s),r=t[s];try{t[s]=void 0;var n=!0}catch(t){}var a=i.call(t);return n&&(e?t[s]=r:delete t[s]),a}},function(t,e){var r=Object.prototype.toString;t.exports=function(t){return r.call(t)}},function(t,e){t.exports=function(t){return null!=t&&"object"==typeof t}},function(t,e,r){"use strict";r.r(e);var n=r(39),a=(wp.customize.astraControl=wp.customize.Control.extend({initialize:function(t,e){var r=e||{};r.params=r.params||{},r.params.type||(r.params.type="ast-core"),r.params.content||(r.params.content=jQuery("<li></li>"),r.params.content.attr("id","customize-control-"+t.replace(/]/g,"").replace(/\[/g,"-")),r.params.content.attr("class","customize-control customize-control-"+r.params.type)),this.propertyElements=[],wp.customize.Control.prototype.initialize.call(this,t,r)},ready:function(){wp.customize.Control.prototype.ready.call(this),this.deferred.embedded.done()},embed:function(){var t=this,e=t.section();e&&wp.customize.section(e,(function(e){e.expanded()||wp.customize.settings.autofocus.control===t.id?t.actuallyEmbed():e.expanded.bind((function(e){e&&t.actuallyEmbed()}))}))},actuallyEmbed:function(){"resolved"!==this.deferred.embedded.state()&&(this.renderContent(),this.deferred.embedded.resolve())},focus:function(t){this.actuallyEmbed(),wp.customize.Control.prototype.focus.call(this,t)}}),r(0)),o=r(1),i=r.n(o),s=function(t){var e=null,r=null,n=null;return t.control.params.caption&&(e=Object(a.createElement)("span",{className:"customize-control-caption"},t.control.params.caption)),t.control.params.label&&(r=Object(a.createElement)("span",{className:"customize-control-title wp-ui-text-highlight"},t.control.params.label)),t.control.params.description&&(n=Object(a.createElement)("span",{className:"description customize-control-description"},t.control.params.description)),Object(a.createElement)(a.Fragment,null,e,Object(a.createElement)("div",{className:"ast-heading-wrapper wp-ui-highlight"},Object(a.createElement)("label",{className:"customizer-text"},r,n)))};s.propTypes={control:i.a.object.isRequired};var c=React.memo(s),l=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(c,{control:this}),this.container[0])}}),u=function(t){var e=t.control.setting.get(),r=t.control.params.settings.default;r=(r=r.replace("[","-")).replace("]","");var n="hidden-field-".concat(r);return Object(a.createElement)("input",{type:"hidden",className:n,"data-name":r,value:JSON.stringify(e)})};u.propTypes={control:i.a.object.isRequired};var p=React.memo(u),d=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(p,{control:this}),this.container[0])}}),h=r(40),f=r.n(h),m=function(t){var e=null,r=null,n=null;return t.control.params.label&&(e=Object(a.createElement)("span",{className:"customize-control-title"},t.control.params.label)),t.control.params.help&&(r=Object(a.createElement)("span",{className:"ast-description"},f()(t.control.params.help))),t.control.params.description&&(n=Object(a.createElement)("span",{className:"description customize-control-description"},t.control.params.description)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",{className:"customizer-text"},e,r,n))};m.propTypes={control:i.a.object.isRequired};var g=React.memo(m),v=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(g,{control:this}),this.container[0])}}),b=r(5),y=r.n(b),w=r(4),O=r.n(w),j=r(2),E=r(6),C=r(3),x=r.n(C);function k(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function z(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?k(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):k(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var N=function(t){var e=Object(C.useState)(t.control.setting.get()),r=O()(e,2),n=r[0],o=r[1],i=t.control.params,s=i.value,c=i.label,l=i.settings,u=n.url,p=n.new_tab,d=n.link_rel,h=l.default;h=(h=h.replace("[","-")).replace("]","");var f=null;return c&&(f=Object(a.createElement)("label",null,Object(a.createElement)("span",{className:"customize-control-title"},c))),Object(a.createElement)(a.Fragment,null,f,Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)(E.TextControl,{value:u,className:"ast-link-input",onChange:function(e){!function(e){var r=z(z({},n),{},{url:e});o(r),t.control.setting.set(r)}(e)}})),Object(a.createElement)("div",{className:"customize-control-content ast-link-open-in-new-tab-wrapper"},Object(a.createElement)("input",{type:"checkbox",id:"ast-link-open-in-new-tab",className:"ast-link-open-in-new-tab",name:"ast-link-open-in-new-tab",checked:p,onChange:function(){return e=z(z({},n),{},{new_tab:event.target.checked}),o(e),void t.control.setting.set(e);var e}}),Object(a.createElement)("label",null,Object(j.__)("Open in a New Tab"))),Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)("label",null,Object(a.createElement)("span",{className:"customize-control-title"},Object(j.__)("Link Rel"))),Object(a.createElement)(E.TextControl,{value:d,className:"ast-link-relationship",onChange:function(e){!function(e){var r=z(z({},n),{},{link_rel:e});o(r),t.control.setting.set(r)}(e)}})),Object(a.createElement)("input",{type:"hidden",id:"_customize-input-".concat(l.default),className:"customize-link-control-data",name:h,"data-customize-setting-link":l.default,"data-value":JSON.stringify(s)}))};N.propTypes={control:i.a.object.isRequired};var S=React.memo(N),M=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(S,{control:this}),this.container[0])}}),A=function(t){var e=t.control.params,r=e.caption,n=e.separator,o=e.label,i=e.description,s=null,c=null,l=null,u=null;return!1!==n&&(s=Object(a.createElement)("hr",null)),r&&(c=Object(a.createElement)("span",{className:"customize-control-caption"},r)),o&&(l=Object(a.createElement)("span",{className:"customize-control-title"},o),s=null),i&&(u=Object(a.createElement)("span",{className:"description customize-control-description"},i)),Object(a.createElement)(a.Fragment,null,c,s,Object(a.createElement)("label",{className:"customizer-text"},l,u))};A.propTypes={control:i.a.object.isRequired};var D=React.memo(A),T=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(D,{control:this}),this.container[0])}}),P=function(t){var e=null,r=null,n=t.control.params,o=n.label,i=n.help,s=n.name;return o&&(e=Object(a.createElement)("span",{className:"customize-control-title"},o)),i&&(r=Object(a.createElement)("span",{className:"ast-description"},i)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-toggle-desc-wrap"},Object(a.createElement)("label",{className:"customizer-text"},e,r,Object(a.createElement)("span",{className:"ast-adv-toggle-icon dashicons","data-control":s}))),Object(a.createElement)("div",{className:"ast-field-settings-wrap"}))};P.propTypes={control:i.a.object.isRequired};var B=React.memo(P),H=r(7),R=r.n(H);function L(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}var I=function(t){var e,r=Object(C.useState)(t.control.setting.get()),n=O()(r,2),o=n[0],i=n[1],s=function(e){var r=t.control.params.choices,n=function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?L(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):L(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}({},o);if(event.target.classList.contains("connected"))for(var a in r)n[a]=event.target.value;else n[e]=event.target.value;t.control.setting.set(n),i(n)},c=t.control.params,l=c.label,u=c.description,p=c.linked_choices,d=c.id,h=c.choices,f=c.inputAttrs,m=c.name,g=Object(a.createElement)("span",{className:"customize-control-title"},l||Object(j.__)("Background","astra")),v=u?Object(a.createElement)("span",{className:"description customize-control-description"},u):null,b=null,w=Object(j.__)("Link Values Together","astra");return p&&(b=Object(a.createElement)("li",{key:d,className:"ast-border-input-item-link disconnected"},Object(a.createElement)("span",{className:"dashicons dashicons-admin-links ast-border-connected wp-ui-highlight",onClick:function(){!function(){for(var t=event.target.parentElement.parentElement.querySelectorAll(".ast-border-input"),e=0;e<t.length;e++)t[e].classList.remove("connected"),t[e].setAttribute("data-element-connect","");event.target.parentElement.classList.remove("disconnected")}()},"data-element-connect":d,title:w}),Object(a.createElement)("span",{className:"dashicons dashicons-editor-unlink ast-border-disconnected",onClick:function(){!function(){for(var t=event.target.dataset.elementConnect,e=event.target.parentElement.parentElement.querySelectorAll(".ast-border-input"),r=0;r<e.length;r++)e[r].classList.add("connected"),e[r].setAttribute("data-element-connect",t);event.target.parentElement.classList.add("disconnected")}()},"data-element-connect":d,title:w}))),e=Object.keys(h).map((function(t){if(h[t])var e=Object(a.createElement)("li",R()({},f,{key:t,className:"ast-border-input-item"}),Object(a.createElement)("input",{type:"number",className:"ast-border-input ast-border-desktop connected","data-id":t,"data-name":m,onChange:function(){return s(t)},value:o[t],"data-element-connect":d}),Object(a.createElement)("span",{className:"ast-border-title"},h[t]));return e})),Object(a.createElement)(a.Fragment,null,g,v,Object(a.createElement)("div",{className:"ast-border-outer-wrapper"},Object(a.createElement)("div",{className:"input-wrapper ast-border-wrapper"},Object(a.createElement)("ul",{className:"ast-border-wrapper desktop active"},b,e))))};I.propTypes={control:i.a.object.isRequired};var V=React.memo(I);function q(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function U(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?q(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):q(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Q=function(t){var e=Object(C.useState)(t.control.setting.get()),r=O()(e,2),n=r[0],o=r[1],i=function(e){var r=U({},n);r[e]=event.target.value,t.control.setting.set(r),o(r)},s=function(e){var r=U({},n);r["".concat(e,"-unit")]=event.target.value,t.control.setting.set(r),o(r)},c=function(e){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",o=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],c=t.control.params.units,l=!1;1===c.length&&(l=!0);var u=Object.keys(c).map((function(t){return Object(a.createElement)("option",{key:t,value:t},c[t])}));return!1===o?Object(a.createElement)(a.Fragment,null,Object(a.createElement)("input",{key:e+"input","data-id":e,className:"ast-responsive-input ast-non-reponsive ".concat(e," ").concat(r),type:"number",value:n[e],onChange:function(){i(e)}}),Object(a.createElement)("select",{key:e+"select",value:n["".concat(e,"-unit")],className:"ast-responsive-select ".concat(e),"data-id":"".concat(e,"-unit"),disabled:l,onChange:function(){s(e)}},u)):Object(a.createElement)(a.Fragment,null,Object(a.createElement)("input",{key:e+"input","data-id":e,className:"ast-responsive-input ".concat(e," ").concat(r),type:"number",value:n[e],onChange:function(){i(e)}}),Object(a.createElement)("select",{key:e+"select",value:n["".concat(e,"-unit")],className:"ast-responsive-select ".concat(e),"data-id":"".concat(e,"-unit"),disabled:l,onChange:function(){s(e)}},u))},l=t.control.params,u=l.description,p=l.label,d=l.responsive,h=null,f=null,m=null,g=null;return p&&(h=Object(a.createElement)("span",{className:"customize-control-title"},p),d&&(f=Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-btns"},Object(a.createElement)("li",{key:"desktop",className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{key:"tablet",className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{key:"mobile",className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))))),u&&(m=Object(a.createElement)("span",{className:"description customize-control-description"},u)),g=d?Object(a.createElement)(a.Fragment,null,c("desktop","active"),c("tablet"),c("mobile")):Object(a.createElement)(a.Fragment,null,c("desktop","active",!1)),Object(a.createElement)("label",{key:"customizer-text",className:"customizer-text"},h,f,m,Object(a.createElement)("div",{className:"input-wrapper ast-responsive-wrapper"},g))};Q.propTypes={control:i.a.object.isRequired};var F=React.memo(Q);function Y(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}var G=function(t){var e,r,n=Object(C.useState)(t.control.setting.get()),o=O()(n,2),i=o[0],s=o[1],c=function(e){var r=function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?Y(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):Y(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}({},i);r[e]=event.target.value,t.control.setting.set(r),s(r)},l=function(e){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=t.control.params,o=n.inputAttrs,s=n.suffix,l=null,u=[];if(s&&(l=Object(a.createElement)("span",{className:"ast-range-unit"},s)),void 0!==o){var p=o.split(" ");p.map((function(t,e){var r=t.split("=");void 0!==r[1]&&(u[r[0]]=r[1].replace(/"/g,""))}))}return Object(a.createElement)("div",{className:"input-field-wrapper ".concat(e," ").concat(r)},Object(a.createElement)("input",R()({type:"range"},u,{value:i[e],"data-reset_value":t.control.params.default[e],onChange:function(){c(e)}})),Object(a.createElement)("div",{className:"astra_range_value"},Object(a.createElement)("input",R()({type:"number"},u,{"data-id":e,className:"ast-responsive-range-value-input",value:i[e],onChange:function(){c(e)}})),l))},u=t.control.params,p=u.description,d=u.label,h=Object(j.__)("Back to default","astra"),f=null,m=null,g=null;return d&&(f=Object(a.createElement)("span",{className:"customize-control-title"},d),m=Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-slider-btns"},Object(a.createElement)("li",{className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop active","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"}))))),p&&(g=Object(a.createElement)("span",{className:"description customize-control-description"},p)),e=Object(a.createElement)(a.Fragment,null,l("desktop","active"),l("tablet"),l("mobile")),r=Object(a.createElement)("div",{className:"ast-responsive-slider-reset",onClick:function(e){!function(e){e.preventDefault(),t.control.setting.set(t.control.params.default),s(t.control.params.default)}(e)}},Object(a.createElement)("span",{className:"dashicons dashicons-image-rotate ast-control-tooltip",title:h})),Object(a.createElement)("label",{key:"customizer-text"},f,m,g,Object(a.createElement)("div",{className:"wrapper"},e,r))};G.propTypes={control:i.a.object.isRequired};var X=React.memo(G);function J(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function W(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?J(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):J(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Z=function(t){var e=t.control.setting.get();e=void 0===e||""===e?t.control.params.value:e;var r,n,o=Object(C.useState)(e),i=O()(o,2),s=i[0],c=i[1],l=function(){for(var t=event.target.parentElement.parentElement.querySelectorAll(".ast-spacing-input"),e=0;e<t.length;e++)t[e].classList.remove("connected"),t[e].setAttribute("data-element-connect","");event.target.parentElement.classList.remove("disconnected")},u=function(){for(var t=event.target.dataset.elementConnect,e=event.target.parentElement.parentElement.querySelectorAll(".ast-spacing-input"),r=0;r<e.length;r++)e[r].classList.add("connected"),e[r].setAttribute("data-element-connect",t);event.target.parentElement.classList.add("disconnected")},p=function(e,r){var n=t.control.params.choices,a=W({},s),o=W({},a[e]);if(event.target.classList.contains("connected"))for(var i in n)o[i]=event.target.value;else o[r]=event.target.value;a[e]=o,t.control.setting.set(a),c(a)},d=function(e){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=W({},s);n["".concat(e,"-unit")]=r,t.control.setting.set(n),c(n)},h=function(t){return Object(a.createElement)("input",{key:t,type:"hidden",onChange:function(){return d(t,"")},className:"ast-spacing-unit-input ast-spacing-".concat(t,"-unit"),"data-device":"".concat(t),value:s["".concat(t,"-unit")]})},f=function(e){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=t.control.params,o=n.linked_choices,i=n.id,c=n.choices,h=n.inputAttrs,f=n.unit_choices,m=Object(j.__)("Link Values Together","astra"),g=null,v=null,b=null;return o&&(g=Object(a.createElement)("li",{key:"connect-disconnect"+e,className:"ast-spacing-input-item-link disconnected"},Object(a.createElement)("span",{key:"connect"+e,className:"dashicons dashicons-admin-links ast-spacing-connected wp-ui-highlight",onClick:function(){l()},"data-element-connect":i,title:m}),Object(a.createElement)("span",{key:"disconnect"+e,className:"dashicons dashicons-editor-unlink ast-spacing-disconnected",onClick:function(){u()},"data-element-connect":i,title:m}))),c&&(v=Object.keys(c).map((function(t){return Object(a.createElement)("li",R()({key:t},h,{className:"ast-spacing-input-item"}),Object(a.createElement)("input",{type:"number",className:"ast-spacing-input ast-spacing-".concat(e," connected"),"data-id":t,value:s[e][t],onChange:function(){return p(e,t)},"data-element-connect":i}),Object(a.createElement)("span",{className:"ast-spacing-title"},c[t]))}))),f&&(b=Object.values(f).map((function(t){var r="";return s["".concat(e,"-unit")]===t&&(r="active"),Object(a.createElement)("li",{key:t,className:"single-unit ".concat(r),onClick:function(){return d(e,t)},"data-unit":t},Object(a.createElement)("span",{className:"unit-text"},t))}))),Object(a.createElement)("ul",{key:e,className:"ast-spacing-wrapper ".concat(e," ").concat(r)},g,v,Object(a.createElement)("ul",{key:"responsive-units",className:"ast-spacing-responsive-units ast-spacing-".concat(e,"-responsive-units")},b))},m=t.control.params,g=m.label,v=m.description,b=null,y=null;return g&&(b=Object(a.createElement)("span",{className:"customize-control-title"},g)),v&&(y=Object(a.createElement)("span",{className:"description customize-control-description"},v)),r=Object(a.createElement)(a.Fragment,null,f("desktop","active"),f("tablet"),f("mobile")),n=Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"unit-input-wrapper ast-spacing-unit-wrapper"},h("desktop"),h("tablet"),h("mobile")),Object(a.createElement)("ul",{key:"ast-spacing-responsive-btns",className:"ast-spacing-responsive-btns"},Object(a.createElement)("li",{key:"desktop",className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop active","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{key:"tablet",className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{key:"mobile",className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"}))))),Object(a.createElement)("label",{key:"ast-spacing-responsive",className:"ast-spacing-responsive",htmlFor:"ast-spacing"},b,y,Object(a.createElement)("div",{className:"ast-spacing-responsive-outer-wrapper"},Object(a.createElement)("div",{className:"input-wrapper ast-spacing-responsive-wrapper"},r),Object(a.createElement)("div",{className:"ast-spacing-responsive-units-screen-wrap"},n)))};Z.propTypes={control:i.a.object.isRequired};var K=React.memo(Z),$=function(t){var e=Object(C.useState)(t.control.setting.get()),r=O()(e,2),n=r[0],o=r[1],i=t.control.params,s=i.label,c=i.description,l=i.suffix,u=i.link,p=i.inputAttrs,d=i.name,h=null,f=null,m=null,g=[],v=Object(j.__)("Back to default","astra");(s&&(h=Object(a.createElement)("label",null,Object(a.createElement)("span",{className:"customize-control-title"},s))),c&&(f=Object(a.createElement)("span",{className:"description customize-control-description"},c)),l&&(m=Object(a.createElement)("span",{className:"ast-range-unit"},l)),void 0!==p)&&p.split(" ").map((function(t,e){var r=t.split("=");void 0!==r[1]&&(g[r[0]]=r[1].replace(/"/g,""))}));void 0!==u&&u.split(" ").map((function(t,e){var r=t.split("=");void 0!==r[1]&&(g[r[0]]=r[1].replace(/"/g,""))}));var b=function(e){o(e),t.control.setting.set(e)};return Object(a.createElement)("label",null,h,f,Object(a.createElement)("div",{className:"wrapper"},Object(a.createElement)("input",R()({},g,{type:"range",value:n,"data-reset_value":t.control.params.default,onChange:function(){return b(event.target.value)}})),Object(a.createElement)("div",{className:"astra_range_value"},Object(a.createElement)("input",R()({},g,{type:"number","data-name":d,className:"value ast-range-value-input",value:n,onChange:function(){return b(event.target.value)}})),m),Object(a.createElement)("div",{className:"ast-slider-reset",onClick:function(){b(t.control.params.default)}},Object(a.createElement)("span",{className:"dashicons dashicons-image-rotate ast-control-tooltip",title:v}))))};$.propTypes={control:i.a.object.isRequired};var tt=React.memo($),et=r(41),rt=r.n(et),nt=r(12),at=r.n(nt),ot=r(13),it=r.n(ot),st=r(8),ct=r.n(st),lt=r(14),ut=r.n(lt),pt=r(15),dt=r.n(pt),ht=r(9),ft=r.n(ht),mt=r(42);function gt(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var r,n=ft()(t);if(e){var a=ft()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return dt()(this,r)}}var vt=function(t){ut()(r,t);var e=gt(r);function r(t){var n;return at()(this,r),(n=e.apply(this,arguments)).onChangeComplete=n.onChangeComplete.bind(ct()(n)),n.onPaletteChangeComplete=n.onPaletteChangeComplete.bind(ct()(n)),n.onChangeGradientComplete=n.onChangeGradientComplete.bind(ct()(n)),n.renderImageSettings=n.renderImageSettings.bind(ct()(n)),n.onRemoveImage=n.onRemoveImage.bind(ct()(n)),n.onSelectImage=n.onSelectImage.bind(ct()(n)),n.open=n.open.bind(ct()(n)),n.onColorClearClick=n.onColorClearClick.bind(ct()(n)),n.state={isVisible:!1,refresh:!1,color:n.props.color,modalCanClose:!0,backgroundType:n.props.backgroundType,supportGradient:void 0!==E.__experimentalGradientPicker},n}return it()(r,[{key:"onResetRefresh",value:function(){!0===this.state.refresh?this.setState({refresh:!1}):this.setState({refresh:!0})}},{key:"render",value:function(){var t=this,e=this.state,r=e.refresh,n=e.modalCanClose,o=e.isVisible,i=e.supportGradient,s=e.backgroundType,c=this.props,l=c.allowGradient,u=c.allowImage,p=function(){n&&!0===o&&t.setState({isVisible:!1})},d=!(!l||!i),h=[{name:"color",title:Object(j.__)("Color","astra"),className:"astra-color-background"}];if(d){var f={name:"gradient",title:Object(j.__)("Gradient","astra"),className:"astra-image-background"};h.push(f)}if(u){var m={name:"image",title:Object(j.__)("Image","astra"),className:"astra-image-background"};h.push(m)}var g=[],v=0;return rt()(astColorPalette.colors).forEach((function(t){var e={};Object.assign(e,{name:v+"_"+t}),Object.assign(e,{color:t}),g.push(e),v++})),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"color-button-wrap"},Object(a.createElement)(E.Button,{className:o?"astra-color-icon-indicate open":"astra-color-icon-indicate",onClick:function(){o?p():(!0===r?t.setState({refresh:!1}):t.setState({refresh:!0}),t.setState({isVisible:!0}))}},("color"===s||"gradient"===s)&&Object(a.createElement)(E.ColorIndicator,{className:"astra-advanced-color-indicate",colorValue:this.props.color}),"image"===s&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(E.ColorIndicator,{className:"astra-advanced-color-indicate",colorValue:"#ffffff"}),Object(a.createElement)(E.Dashicon,{icon:"format-image"})))),Object(a.createElement)("div",{className:"astra-color-picker-wrap"},Object(a.createElement)(a.Fragment,null,o&&Object(a.createElement)("div",{className:"astra-popover-color",onClose:p},1<h.length&&Object(a.createElement)(E.TabPanel,{className:"astra-popover-tabs astra-background-tabs",activeClass:"active-tab",initialTabName:s,tabs:h},(function(e){var n;return e.name&&("gradient"===e.name&&(n=Object(a.createElement)(a.Fragment,null,Object(a.createElement)(E.__experimentalGradientPicker,{className:"ast-gradient-color-picker",value:t.props.color&&t.props.color.includes("gradient")?t.props.color:"",onChange:function(e){return t.onChangeGradientComplete(e)}}))),"image"===e.name?n=t.renderImageSettings():"color"===e.name&&(n=Object(a.createElement)(a.Fragment,null,r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(E.ColorPicker,{color:t.props.color,onChangeComplete:function(e){return t.onChangeComplete(e)}})),!r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(E.ColorPicker,{color:t.props.color,onChangeComplete:function(e){return t.onChangeComplete(e)}})),Object(a.createElement)(E.ColorPalette,{colors:g,value:t.props.color,clearable:!1,disableCustomColors:!0,className:"ast-color-palette",onChange:function(e){return t.onPaletteChangeComplete(e)}}),Object(a.createElement)("button",{type:"button",onClick:function(){t.onColorClearClick()},className:"ast-clear-btn-inside-picker components-button common components-circular-option-picker__clear is-secondary is-small"},Object(j.__)("Clear","astra"))))),Object(a.createElement)("div",null,n)})),1===h.length&&Object(a.createElement)(a.Fragment,null,r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(E.ColorPicker,{color:this.props.color,onChangeComplete:function(e){return t.onChangeComplete(e)}})),!r&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(E.ColorPicker,{color:this.props.color,onChangeComplete:function(e){return t.onChangeComplete(e)}})),Object(a.createElement)(E.ColorPalette,{colors:g,value:this.props.color,clearable:!1,disableCustomColors:!0,className:"ast-color-palette",onChange:function(e){return t.onPaletteChangeComplete(e)}}),Object(a.createElement)("button",{type:"button",onClick:function(){t.onColorClearClick()},className:"ast-clear-btn-inside-picker components-button components-circular-option-picker__clear is-secondary is-small"},Object(j.__)("Clear","astra")))))))}},{key:"onColorClearClick",value:function(){!0===this.state.refresh?this.setState({refresh:!1}):this.setState({refresh:!0}),this.props.onChangeComplete("","color"),wp.customize.previewer.refresh()}},{key:"onChangeGradientComplete",value:function(t){this.setState({backgroundType:"gradient"}),this.props.onChangeComplete(t,"gradient")}},{key:"onChangeComplete",value:function(t){t.rgb&&t.rgb.a&&1!==t.rgb.a?(t.rgb.r,t.rgb.g,t.rgb.b,t.rgb.a):t.hex,this.setState({backgroundType:"color"}),this.props.onChangeComplete(t,"color")}},{key:"onPaletteChangeComplete",value:function(t){this.setState({color:t}),!0===this.state.refresh?this.setState({refresh:!1}):this.setState({refresh:!0}),this.props.onChangeComplete(t,"color")}},{key:"onSelectImage",value:function(t){this.setState({modalCanClose:!0}),this.setState({backgroundType:"image"}),this.props.onSelectImage(t,"image")}},{key:"onRemoveImage",value:function(){this.setState({modalCanClose:!0}),this.props.onSelectImage("")}},{key:"open",value:function(t){this.setState({modalCanClose:!1}),t()}},{key:"onChangeImageOptions",value:function(t,e,r){this.setState({backgroundType:"image"}),this.props.onChangeImageOptions(e,r,"image")}},{key:"toggleMoreSettings",value:function(){var t=event.target.parentElement.parentElement,e=t.querySelector(".more-settings"),r=t.querySelector(".media-position-setting"),n=e.dataset.direction;e.dataset.id;"down"===n?(e.setAttribute("data-direction","up"),t.querySelector(".message").innerHTML=Object(j.__)("Less Settings"),t.querySelector(".icon").innerHTML="↑"):(e.setAttribute("data-direction","down"),t.querySelector(".message").innerHTML=Object(j.__)("More Settings"),t.querySelector(".icon").innerHTML="↓"),r.classList.contains("hide-settings")?r.classList.remove("hide-settings"):r.classList.add("hide-settings")}},{key:"renderImageSettings",value:function(){var t=this;return Object(a.createElement)(a.Fragment,null,(this.props.media.url||this.props.backgroundImage)&&Object(a.createElement)("img",{src:this.props.media.url?this.props.media.url:this.props.backgroundImage}),Object(a.createElement)(mt.MediaUpload,{title:Object(j.__)("Select Background Image","astra"),onSelect:function(e){return t.onSelectImage(e)},allowedTypes:["image"],value:this.props.media&&this.props.media?this.props.media:"",render:function(e){var r=e.open;return Object(a.createElement)(E.Button,{className:"upload-button button-add-media",isDefault:!0,onClick:function(){return t.open(r)}},t.props.media||t.props.backgroundImage?Object(j.__)("Replace image","astra"):Object(j.__)("Select Background Image","astra"))}}),(this.props.media||this.props.backgroundImage)&&Object(a.createElement)(a.Fragment,null,Object(a.createElement)(E.Button,{className:"ast-bg-img-remove",onClick:this.onRemoveImage,isLink:!0,isDestructive:!0},Object(j.__)("Remove Image","astra")),Object(a.createElement)("a",{href:"#",className:"more-settings",onClick:this.toggleMoreSettings,"data-direction":"down","data-id":"desktop"},Object(a.createElement)("span",{className:"message"}," ",Object(j.__)("More Settings")," "),Object(a.createElement)("span",{className:"icon"}," ↓ ")),Object(a.createElement)("div",{className:"media-position-setting hide-settings"},Object(a.createElement)(E.SelectControl,{label:Object(j.__)("Image Position"),value:this.props.backgroundPosition,onChange:function(e){return t.onChangeImageOptions("backgroundPosition","background-position",e)},options:[{value:"left top",label:Object(j.__)("Left Top","astra")},{value:"left center",label:Object(j.__)("Left Center","astra")},{value:"left bottom",label:Object(j.__)("Left Bottom","astra")},{value:"right top",label:Object(j.__)("Right Top","astra")},{value:"right center",label:Object(j.__)("Right Center","astra")},{value:"right bottom",label:Object(j.__)("Right Bottom","astra")},{value:"center top",label:Object(j.__)("Center Top","astra")},{value:"center center",label:Object(j.__)("Center Center","astra")},{value:"center bottom",label:Object(j.__)("Center Bottom","astra")}]}),Object(a.createElement)(E.SelectControl,{label:Object(j.__)("Attachment","astra"),value:this.props.backgroundAttachment,onChange:function(e){return t.onChangeImageOptions("backgroundAttachment","background-attachment",e)},options:[{value:"fixed",label:Object(j.__)("Fixed","astra")},{value:"scroll",label:Object(j.__)("Scroll","astra")}]}),Object(a.createElement)(E.SelectControl,{label:Object(j.__)("Repeat","astra"),value:this.props.backgroundRepeat,onChange:function(e){return t.onChangeImageOptions("backgroundRepeat","background-repeat",e)},options:[{value:"no-repeat",label:Object(j.__)("No Repeat","astra")},{value:"repeat",label:Object(j.__)("Repeat All","astra")},{value:"repeat-x",label:Object(j.__)("Repeat Horizontally","astra")},{value:"repeat-y",label:Object(j.__)("Repeat Vertically","astra")}]}),Object(a.createElement)(E.SelectControl,{label:Object(j.__)("Size","astra"),value:this.props.backgroundSize,onChange:function(e){return t.onChangeImageOptions("backgroundSize","background-size",e)},options:[{value:"auto",label:Object(j.__)("Auto","astra")},{value:"cover",label:Object(j.__)("Cover","astra")},{value:"contain",label:Object(j.__)("Contain","astra")}]}))))}}]),r}(a.Component);vt.propTypes={color:i.a.string,usePalette:i.a.bool,palette:i.a.string,presetColors:i.a.object,onChangeComplete:i.a.func,onPaletteChangeComplete:i.a.func,onChange:i.a.func,customizer:i.a.object};var bt=vt;function yt(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function wt(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?yt(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):yt(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var _t=function(t){var e,r=Object(C.useState)(t.control.setting.get()),n=O()(r,2),o=n[0],i=n[1],s=function(e,r){var n="";e&&(n="string"==typeof e||e instanceof String?e:void 0!==e.rgb&&void 0!==e.rgb.a&&1!==e.rgb.a?"rgba("+e.rgb.r+","+e.rgb.g+","+e.rgb.b+","+e.rgb.a+")":e.hex);var a=wt({},o);a["background-color"]=n,a["background-type"]=r,t.control.setting.set(a),i(a)},c=t.control.params,l=c.defaultValue,u=c.label,p=c.description,d="#RRGGBB",h=Object(a.createElement)("span",{className:"customize-control-title"},u||Object(j.__)("Background","astra")),f=p?Object(a.createElement)("span",{className:"description customize-control-description"},p):null;return l&&(d="#"!==l.substring(0,1)?"#"+l:l,defaultValueAttr=" data-default-color="+d),e=Object(a.createElement)("div",{className:"background-wrapper"},Object(a.createElement)("div",{className:"background-container"},Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:JSON.stringify(o)===JSON.stringify(t.control.params.default),onClick:function(e){e.preventDefault();var r=JSON.parse(JSON.stringify(t.control.params.default));void 0!==r&&""!==r&&(void 0!==r["background-color"]&&""!==r["background-color"]||(r["background-color"]="",wp.customize.previewer.refresh()),void 0!==r["background-image"]&&""!==r["background-image"]||(r["background-image"]="",wp.customize.previewer.refresh()),void 0!==r["background-media"]&&""!==r["background-media"]||(r["background-media"]="",wp.customize.previewer.refresh())),t.control.setting.set(r),i(r)}},Object(a.createElement)(E.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}})))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)(bt,{color:void 0!==o["background-color"]&&o["background-color"]?o["background-color"]:"",onChangeComplete:function(t,e){return s(t,e)},media:void 0!==o["background-media"]&&o["background-media"]?o["background-media"]:"",backgroundImage:void 0!==o["background-image"]&&o["background-image"]?o["background-image"]:"",backgroundAttachment:void 0!==o["background-attachment"]&&o["background-attachment"]?o["background-attachment"]:"",backgroundPosition:void 0!==o["background-position"]&&o["background-position"]?o["background-position"]:"",backgroundRepeat:void 0!==o["background-repeat"]&&o["background-repeat"]?o["background-repeat"]:"",backgroundSize:void 0!==o["background-size"]&&o["background-size"]?o["background-size"]:"",onSelectImage:function(e,r){return function(e,r){var n=wt({},o);n["background-media"]=e.id,n["background-image"]=e.url,n["background-type"]=r,t.control.setting.set(n),i(n)}(e,r)},onChangeImageOptions:function(e,r,n){return function(e,r,n){var a=wt({},o);a[e]=r,a["background-type"]=n,t.control.setting.set(a),i(a)}(e,r,n)},backgroundType:void 0!==o["background-type"]&&o["background-type"]?o["background-type"]:"color",allowGradient:!0,allowImage:!0})))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,h,f),Object(a.createElement)("div",{className:"customize-control-content"},e))};_t.propTypes={control:i.a.object.isRequired};var Ot=React.memo(_t);function jt(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function Et(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?jt(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):jt(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Ct=function(t){var e=t.control.setting.get(),r=t.control.params.default,n=Object(C.useState)({value:e}),o=O()(n,2),i=o[0],s=o[1],c=function(e){s((function(t){return Et(Et({},t),{},{value:e})})),t.control.setting.set(e)},l=function(e){var r=Et({},t.control.setting.get());if(!i.value[e]["background-type"]){var n=Et({},r[e]);i.value[e]["background-color"]&&(n["background-type"]="color",r[e]=n,c(r),i.value[e]["background-color"].includes("gradient")&&(n["background-type"]="gradient",r[e]=n,c(r))),i.value[e]["background-image"]&&(n["background-type"]="image",r[e]=n,c(r))}},u=function(t){for(var e=!0,n=0,o=["desktop","mobile","tablet"];n<o.length;n++){var s=o[n];(i.value[s]["background-color"]||i.value[s]["background-image"]||i.value[s]["background-media"])&&!1,i.value[s]["background-color"]===r[s]["background-image"]&&i.value[s]["background-image"]===r[s]["background-color"]&&i.value[s]["background-media"]===r[s]["background-media"]||(e=!1)}return Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:e,onClick:function(t){t.preventDefault();var e=JSON.parse(JSON.stringify(r));if(void 0!==e&&""!==e)for(var n in e)void 0!==e[n]["background-color"]&&""!==e[n]["background-color"]||(e[n]["background-color"]="",wp.customize.previewer.refresh()),void 0!==e[n]["background-image"]&&""!==e[n]["background-image"]||(e[n]["background-image"]="",wp.customize.previewer.refresh()),void 0!==e[n]["background-media"]&&""!==e[n]["background-media"]||(e[n]["background-media"]="",wp.customize.previewer.refresh());c(e)}},Object(a.createElement)(E.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}})))))};Object(C.useEffect)((function(){for(var t=0,e=["desktop","mobile","tablet"];t<e.length;t++){l(e[t])}}),[]);var p,d,h=function(t){return Object(a.createElement)(a.Fragment,null,Object(a.createElement)(bt,{color:void 0!==i.value[t]["background-color"]&&i.value[t]["background-color"]?i.value[t]["background-color"]:"",onChangeComplete:function(e,r){return f(e,t,r)},media:void 0!==i.value[t]["background-media"]&&i.value[t]["background-media"]?i.value[t]["background-media"]:"",backgroundImage:void 0!==i.value[t]["background-image"]&&i.value[t]["background-image"]?i.value[t]["background-image"]:"",backgroundAttachment:void 0!==i.value[t]["background-attachment"]&&i.value[t]["background-attachment"]?i.value[t]["background-attachment"]:"",backgroundPosition:void 0!==i.value[t]["background-position"]&&i.value[t]["background-position"]?i.value[t]["background-position"]:"",backgroundRepeat:void 0!==i.value[t]["background-repeat"]&&i.value[t]["background-repeat"]?i.value[t]["background-repeat"]:"",backgroundSize:void 0!==i.value[t]["background-size"]&&i.value[t]["background-size"]?i.value[t]["background-size"]:"",onSelectImage:function(e,r){return function(t,e,r){var n=Et({},i.value),a=Et({},n[e]);a["background-image"]=t.url,a["background-media"]=t.id,a["background-type"]=r,n[e]=a,c(n)}(e,t,r)},onChangeImageOptions:function(e,r,n){return function(t,e,r,n){var a=Et({},i.value),o=Et({},a[r]);o[t]=e,o["background-type"]=n,a[r]=o,c(a)}(e,r,t,n)},backgroundType:void 0!==i.value[t]["background-type"]&&i.value[t]["background-type"]?i.value[t]["background-type"]:"color",allowGradient:!0,allowImage:!0}))},f=function(t,e,r){var n="";t&&(n="string"==typeof t||t instanceof String?t:void 0!==t.rgb&&void 0!==t.rgb.a&&1!==t.rgb.a?"rgba("+t.rgb.r+","+t.rgb.g+","+t.rgb.b+","+t.rgb.a+")":t.hex);var a=Et({},i.value),o=Et({},a[e]);o["background-color"]=n,o["background-type"]=r,a[e]=o,c(a)},m=t.control.params,g=m.defaultValue,v=m.label,b=m.description,y="#RRGGBB",w=null,_=null;return g&&(y="#"!==g.substring(0,1)?"#"+g:g,defaultValueAttr=" data-default-color="+y),w=v&&""!==v&&void 0!==v?Object(a.createElement)("span",{className:"customize-control-title"},v):Object(a.createElement)("span",{className:"customize-control-title"},Object(j.__)("Background","astra")),b&&(_=Object(a.createElement)("span",{className:"description customize-control-description"},b)),p=Object(a.createElement)("ul",{className:"ast-responsive-btns"},Object(a.createElement)("li",{className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))),d=Object(a.createElement)("div",{className:"background-wrapper"},Object(a.createElement)("div",{className:"background-container desktop active"},u(),h("desktop")),Object(a.createElement)("div",{className:"background-container tablet"},u(),h("tablet")),Object(a.createElement)("div",{className:"background-container mobile"},u(),h("mobile"))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,w,_),Object(a.createElement)("div",{className:"customize-control-content"},p,d))};Ct.propTypes={control:i.a.object.isRequired};var xt=React.memo(Ct);function kt(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function zt(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?kt(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):kt(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Nt=function(t){var e=t.control.setting.get(),r=t.control.params.default,n=Object(C.useState)({value:e}),o=O()(n,2),i=o[0],s=o[1],c=function(e){s((function(t){return zt(zt({},t),{},{value:e})})),t.control.setting.set(e)},l=null,u=t.control.params.label;return u&&(l=Object(a.createElement)("span",{className:"customize-control-title"},u)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,l),Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex"},Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:JSON.stringify(i.value)===JSON.stringify(r),onClick:function(t){t.preventDefault();var e=JSON.parse(JSON.stringify(r));void 0!==e&&""!==e||(e="",wp.customize.previewer.refresh()),c(e)}},Object(a.createElement)(E.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}}))))),Object(a.createElement)(bt,{color:void 0!==i.value&&i.value?i.value:"",onChangeComplete:function(t,e){return function(t){var e;e="string"==typeof t||t instanceof String?t:void 0!==t.rgb&&void 0!==t.rgb.a&&1!==t.rgb.a?"rgba("+t.rgb.r+","+t.rgb.g+","+t.rgb.b+","+t.rgb.a+")":t.hex,c(e)}(t)},backgroundType:"color",allowGradient:!1,allowImage:!1})))};Nt.propTypes={control:i.a.object.isRequired};var St=React.memo(Nt);function Mt(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}var At=function(t){var e=Object(C.useState)(t.control.setting.get()),r=O()(e,2),n=r[0],o=r[1],i=function(e,r){var a=function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?Mt(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):Mt(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}({},n);a[r]=e,t.control.setting.set(a),o(a)},s=function(e){for(var r=0,i=["desktop","mobile","tablet"];r<i.length;r++){n[i[r]]&&!1}return Object(a.createElement)("span",{className:"customize-control-title"},Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-btn-reset-wrap"},Object(a.createElement)("button",{className:"ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small",disabled:JSON.stringify(n)===JSON.stringify(t.control.params.default),onClick:function(e){e.preventDefault();var r=JSON.parse(JSON.stringify(t.control.params.default));if(void 0!==r&&""!==r)for(var n in r)void 0!==r[n]&&""!==r[n]||(r[n]="",wp.customize.previewer.refresh());t.control.setting.set(r),o(r)}},Object(a.createElement)(E.Dashicon,{icon:"image-rotate",style:{width:12,height:12,fontSize:12}})))))},c=function(t){return Object(a.createElement)(bt,{color:void 0!==n[t]&&n[t]?n[t]:"",onChangeComplete:function(e,r){return l(e,t)},backgroundType:"color",allowGradient:!1,allowImage:!1})},l=function(t,e){var r;r="string"==typeof t||t instanceof String?t:void 0!==t.rgb&&void 0!==t.rgb.a&&1!==t.rgb.a?"rgba("+t.rgb.r+","+t.rgb.g+","+t.rgb.b+","+t.rgb.a+")":t.hex,i(r,e)},u=t.control.params,p=u.defaultValue,d=u.label,h=u.description,f=u.responsive,m="#RRGGBB",g=null,v=null,b=null,w=null;return p&&(m="#"!==p.substring(0,1)?"#"+p:p,defaultValueAttr=" data-default-color="+m),d&&(g=Object(a.createElement)("span",{className:"customize-control-title"},d)),h&&(v=Object(a.createElement)("span",{className:"description customize-control-description"},h)),f&&(b=Object(a.createElement)("ul",{className:"ast-responsive-btns"},Object(a.createElement)("li",{className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))),w=Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex ast-responsive-color desktop active"},s(),c("desktop")),Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex ast-responsive-color tablet"},s(),c("tablet")),Object(a.createElement)("div",{className:"ast-color-picker-alpha color-picker-hex ast-responsive-color mobile"},s(),c("mobile")))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,g,v),b,Object(a.createElement)("div",{className:"customize-control-content"},w))};At.propTypes={control:i.a.object.isRequired};var Dt=React.memo(At),Tt=function(t){var e=Object(C.useState)(t.control.setting.get()),r=O()(e,2),n=r[0],o=r[1],i=t.control.params,s=i.label,c=i.name,l=i.choices,u=null;s&&(u=Object(a.createElement)("span",{className:"customize-control-title"},s));var p=Object.entries(l).map((function(t){return Object(a.createElement)("option",{key:t[0],value:t[0]},t[1])}));return Object(a.createElement)(a.Fragment,null,u,Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)("select",{className:"ast-select-input","data-name":c,"data-value":n,value:n,onChange:function(){var e;e=event.target.value,o(e),t.control.setting.set(e)}},p)))};Tt.propTypes={control:i.a.object.isRequired};var Pt=React.memo(Tt);function Bt(t,e){jQuery("html").addClass("responsive-background-img-ready");var r=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container").removeClass("active"),jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container."+r).addClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li."+r).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var t=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container").removeClass("active"),jQuery(".customize-control-ast-responsive-background .customize-control-content .background-container."+t).addClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-background .ast-responsive-btns li."+t).addClass("active")})),t.container.find(".ast-responsive-btns button").on("click",(function(t){t.preventDefault();var e=jQuery(this).attr("data-device");e="desktop"==e?"tablet":"tablet"==e?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+e+'"]').trigger("click")})),e&&jQuery(document).mouseup((function(t){var r=jQuery(e),n=r.find(".background-wrapper");n.is(t.target)||0!==n.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}function Ht(t,e){jQuery("html").addClass("responsive-background-color-ready");var r=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha").removeClass("active"),jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha."+r).addClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li."+r).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var t=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha").removeClass("active"),jQuery(".customize-control-ast-responsive-color .customize-control-content .ast-responsive-color."+t).addClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-color .ast-responsive-btns li."+t).addClass("active")})),t.container.find(".ast-responsive-btns button").on("click",(function(t){t.preventDefault();var e=jQuery(this).attr("data-device");e="desktop"==e?"tablet":"tablet"==e?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+e+'"]').trigger("click")})),e&&jQuery(document).mouseup((function(t){var r=jQuery(e),n=r.find(".customize-control-content");n.is(t.target)||0!==n.has(t.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}function Rt(t){var e=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive .input-wrapper input").removeClass("active"),jQuery(".customize-control-ast-responsive .input-wrapper input."+e).addClass("active"),jQuery(".customize-control-ast-responsive .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive .ast-responsive-btns li."+e).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var t=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive .input-wrapper input, .customize-control .ast-responsive-btns > li").removeClass("active"),jQuery(".customize-control-ast-responsive .input-wrapper input."+t+", .customize-control .ast-responsive-btns > li."+t).addClass("active")})),t.container.find(".ast-responsive-btns button").on("click",(function(t){t.preventDefault();var e=jQuery(this).attr("data-device");e="desktop"==e?"tablet":"tablet"==e?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+e+'"]').trigger("click")}))}function Lt(t){var e=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-slider .input-field-wrapper").removeClass("active"),jQuery(".customize-control-ast-responsive-slider .input-field-wrapper."+e).addClass("active"),jQuery(".customize-control-ast-responsive-slider .ast-responsive-slider-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-slider .ast-responsive-slider-btns li."+e).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var t=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-slider .input-field-wrapper, .customize-control .ast-responsive-slider-btns > li").removeClass("active"),jQuery(".customize-control-ast-responsive-slider .input-field-wrapper."+t+", .customize-control .ast-responsive-slider-btns > li."+t).addClass("active")})),t.container.find(".ast-responsive-slider-btns button").on("click",(function(t){t.preventDefault();var e=jQuery(this).attr("data-device");e="desktop"==e?"tablet":"tablet"==e?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+e+'"]').trigger("click")}))}function It(t){var e=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper").removeClass("active"),jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper."+e).addClass("active"),jQuery(".customize-control-ast-responsive-spacing .ast-spacing-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-spacing .ast-spacing-responsive-btns li."+e).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var t=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper, .customize-control .ast-spacing-responsive-btns > li").removeClass("active"),jQuery(".customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper."+t+", .customize-control .ast-spacing-responsive-btns > li."+t).addClass("active")})),t.container.find(".ast-spacing-responsive-btns button").on("click",(function(t){t.preventDefault();var e=jQuery(this).attr("data-device");e="desktop"==e?"tablet":"tablet"==e?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+e+'"]').trigger("click")}))}var Vt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(B,{control:this}),this.container[0])},ready:function(){this.setting._value;this.registerToggleEvents(),this.container.on("ast_settings_changed",this.onOptionChange);var t=0,e=jQuery(".wp-full-overlay-sidebar-content"),r=navigator.userAgent.toLowerCase();if(r.indexOf("firefox")>-1)n=16;else var n=6;jQuery("#customize-controls .wp-full-overlay-sidebar-content .control-section").on("scroll",(function(a){var o=jQuery(this);if(o.hasClass("open")){var i=o.find(".customize-section-title"),s=o.scrollTop();if(s>t)i.removeClass("maybe-sticky").removeClass("is-in-view").removeClass("is-sticky"),o.css("padding-top","");else{var c=o.outerWidth();i.addClass("maybe-sticky").addClass("is-in-view").addClass("is-sticky").width(c-n).css("top",e.css("top")),r.indexOf("firefox")>-1||o.css("padding-top",i.height()),0===s&&(i.removeClass("maybe-sticky").removeClass("is-in-view").removeClass("is-sticky"),o.css("padding-top",""))}t=s}}))},registerToggleEvents:function(){var t=this;jQuery(".wp-full-overlay-sidebar-content, .wp-picker-container").click((function(t){jQuery(t.target).closest(".ast-field-settings-modal").length||jQuery(".ast-adv-toggle-icon.open").trigger("click")})),t.container.on("click",".ast-toggle-desc-wrap .ast-adv-toggle-icon",(function(e){e.preventDefault(),e.stopPropagation();var r=jQuery(this),n=r.closest(".customize-control-ast-settings-group"),a=n.find(".ast-field-settings-modal").data("loaded"),o=n.parents(".control-section");if(r.hasClass("open"))n.find(".ast-field-settings-modal").hide();else{var i=o.find(".ast-adv-toggle-icon.open");if(i.length>0&&i.trigger("click"),a)n.find(".ast-field-settings-modal").show();else{var s=t.params.ast_fields,c=jQuery(astra.customizer.group_modal_tmpl);n.find(".ast-field-settings-wrap").append(c),n.find(".ast-fields-wrap").attr("data-control",t.params.name),t.ast_render_field(n,s,t),n.find(".ast-field-settings-modal").show();var l=jQuery("#customize-footer-actions .active").attr("data-device");"mobile"==l?(jQuery(".ast-responsive-btns .mobile, .ast-responsive-slider-btns .mobile").addClass("active"),jQuery(".ast-responsive-btns .preview-mobile, .ast-responsive-slider-btns .preview-mobile").addClass("active")):"tablet"==l?(jQuery(".ast-responsive-btns .tablet, .ast-responsive-slider-btns .tablet").addClass("active"),jQuery(".ast-responsive-btns .preview-tablet, .ast-responsive-slider-btns .preview-tablet").addClass("active")):(jQuery(".ast-responsive-btns .desktop, .ast-responsive-slider-btns .desktop").addClass("active"),jQuery(".ast-responsive-btns .preview-desktop, .ast-responsive-slider-btns .preview-desktop").addClass("active"))}}r.toggleClass("open")})),t.container.on("click",".ast-toggle-desc-wrap > .customizer-text",(function(t){t.preventDefault(),t.stopPropagation(),jQuery(this).find(".ast-adv-toggle-icon").trigger("click")}))},ast_render_field:function(t,e,r){var n=this,a=t.find(".ast-fields-wrap"),o="",i=[],s=n.isJsonString(r.params.value)?JSON.parse(r.params.value):{};if(void 0!==e.tabs){var c=(c=r.params.name.replace("[","-")).replace("]","");o+='<div id="'+c+'-tabs" class="ast-group-tabs">',o+='<ul class="ast-group-list">';var l=0;_.each(e.tabs,(function(t,e){var r="";0==l&&(r="active"),o+='<li class="'+r+'"><a href="#tab-'+e+'"><span>'+e+"</span></a></li>",l++})),o+="</ul>",o+='<div class="ast-tab-content" >',_.each(e.tabs,(function(t,e){o+='<div id="tab-'+e+'" class="tab">';var r=n.generateFieldHtml(t,s);o+=r.html,_.each(r.controls,(function(t,e){i.push({key:t.key,value:t.value,name:t.name})})),o+="</div>"})),o+="</div></div>",a.html(o),n.renderReactControl(e,n),jQuery("#"+c+"-tabs").tabs()}else{var u=n.generateFieldHtml(e,s);o+=u.html,_.each(u.controls,(function(t,e){i.push({key:t.key,value:t.value,name:t.name})})),a.html(o),n.renderReactControl(e,n)}_.each(i,(function(t,e){switch(t.key){case"ast-color":!function(t){jQuery(document).mouseup((function(e){var r=jQuery(t),n=r.find(".astra-color-picker-wrap");n.is(e.target)||0!==n.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}("#customize-control-"+t.name);break;case"ast-background":!function(t){jQuery(document).mouseup((function(e){var r=jQuery(t),n=r.find(".background-wrapper");n.is(e.target)||0!==n.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}("#customize-control-"+t.name);break;case"ast-responsive-background":Bt(n,"#customize-control-"+t.name);break;case"ast-responsive-color":Ht(n,"#customize-control-"+t.name);break;case"ast-responsive":Rt(n);break;case"ast-responsive-slider":Lt(n);break;case"ast-responsive-spacing":It(n);break;case"ast-font":var r=astra.customizer.settings.google_fonts;n.container.find(".ast-font-family").html(r),n.container.find(".ast-font-family").each((function(){var t=jQuery(this).data("value");jQuery(this).val(t);var e=jQuery(this).data("name");jQuery("select[data-name='"+e+"'] option[value='inherit']").text(jQuery(this).data("inherit"));var r=jQuery(".ast-font-weight[data-connected-control='"+e+"']"),a=AstTypography._getWeightObject(AstTypography._cleanGoogleFonts(t));n.generateDropdownHtml(a,r),r.val(r.data("value"))})),n.container.find(".ast-font-family").selectWoo(),n.container.find(".ast-font-family").on("select2:select",(function(){var t=jQuery(this).val(),e=AstTypography._getWeightObject(AstTypography._cleanGoogleFonts(t)),r=jQuery(this).data("name"),a=jQuery(".ast-font-weight[data-connected-control='"+r+"']");n.generateDropdownHtml(e,a);var o=jQuery(this).parents(".customize-control").attr("id");o=o.replace("customize-control-",""),n.container.trigger("ast_settings_changed",[n,jQuery(this),t,o]);var i=a.parents(".customize-control").attr("id");i=i.replace("customize-control-",""),n.container.trigger("ast_settings_changed",[n,a,a.val(),i])})),n.container.find(".ast-font-weight").on("change",(function(){var t=jQuery(this).val();name=jQuery(this).parents(".customize-control").attr("id"),name=name.replace("customize-control-",""),n.container.trigger("ast_settings_changed",[n,jQuery(this),t,name])}))}})),t.find(".ast-field-settings-modal").data("loaded",!0)},getJS:function(t){},generateFieldHtml:function(t,e){var r="",n=[];_.each(t,(function(t,e){var a=wp.customize.control("astra-settings["+t.name+"]")?wp.customize.control("astra-settings["+t.name+"]").params.value:"",o=t.control,i="customize-control-"+o+"-content",s=wp.template(i),c=a||t.default;t.value=c;var l="",u="";if(t.label=t.title,_.each(t.data_attrs,(function(t,e){l+=" data-"+e+" ='"+t+"'"})),_.each(t.input_attrs,(function(t,e){u+=e+'="'+t+'" '})),t.dataAttrs=l,t.inputAttrs=u,n.push({key:o,value:c,name:t.name}),"ast-responsive"==o){var p=void 0===t.responsive||t.responsive;t.responsive=p}var d=t.name.replace("[","-");d=d.replace("]",""),r+="<li id='customize-control-"+d+"' class='customize-control customize-control-"+t.control+"' >",r+=s(t),r+="</li>"}));var a=new Object;return a.controls=n,a.html=r,a},generateDropdownHtml:function(t,e){var r=e.data("inherit"),n="",a=0,o=(t=jQuery.merge(["inherit"],t),e.val()||"400"),i="";for(astraTypo.inherit=r;a<t.length;a++)0===a&&-1===jQuery.inArray(o,t)?(o=t[0],i=' selected="selected"'):i=t[a]==o?' selected="selected"':"",t[a].includes("italic")||(n+='<option value="'+t[a]+'"'+i+">"+astraTypo[t[a]]+"</option>");e.html(n)},onOptionChange:function(t,e,r,n,a){jQuery(".hidden-field-astra-settings-"+a).val(n),wp.customize.control("astra-settings["+a+"]").setting.set(n)},isJsonString:function(t){try{JSON.parse(t)}catch(t){return!1}return!0},getFinalControlObject:function(t,e){return void 0!==t.choices&&void 0===e.params.choices&&(e.params.choices=t.choices),void 0!==t.inputAttrs&&void 0===e.params.inputAttrs&&(e.params.inputAttrs=t.inputAttrs),void 0!==t.link&&void 0===e.params.link&&(e.params.link=t.link),void 0!==t.units&&void 0===e.params.units&&(e.params.units=t.units),void 0!==t.linked_choices&&void 0===e.params.linked_choices&&(e.params.linked_choices=t.linked_choices),void 0===t.title||void 0!==e.params.label&&""!==e.params.label&&null!==e.params.label||(e.params.label=t.title),void 0===t.responsive||void 0!==e.params.responsive&&""!==e.params.responsive&&null!==e.params.responsive||(e.params.responsive=t.responsive),e},renderReactControl:function(t,e){var r={"ast-background":Ot,"ast-responsive-background":xt,"ast-responsive-color":Dt,"ast-color":St,"ast-border":V,"ast-responsive":F,"ast-responsive-slider":X,"ast-slider":tt,"ast-responsive-spacing":K,"ast-select":Pt,"ast-divider":D};void 0!==t.tabs?_.each(t.tabs,(function(t,n){_.each(t,(function(t,n){if("ast-font"!==t.control){var o=t.name.replace("[","-"),i="#customize-control-"+(o=o.replace("]","")),s=wp.customize.control("astra-settings["+t.name+"]");s=e.getFinalControlObject(t,s);var c=r[t.control];ReactDOM.render(Object(a.createElement)(c,{control:s,customizer:wp.customize}),jQuery(i)[0])}}))})):_.each(t,(function(t,n){if("ast-font"!==t.control){var o=t.name.replace("[","-"),i="#customize-control-"+(o=o.replace("]","")),s=wp.customize.control("astra-settings["+t.name+"]");s=e.getFinalControlObject(t,s);var c=r[t.control];ReactDOM.render(Object(a.createElement)(c,{control:s,customizer:wp.customize}),jQuery(i)[0])}}))}}),qt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(St,{control:this,customizer:wp.customize}),this.container[0])},ready:function(){var t=this;jQuery(document).mouseup((function(e){var r=jQuery(t.container),n=r.find(".astra-color-picker-wrap"),a=r.find(".ast-color-btn-reset-wrap");n.is(e.target)||a.is(e.target)||0!==n.has(e.target).length||0!==a.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),Ut=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Dt,{control:this,customizer:wp.customize}),this.container[0])},ready:function(){Ht(this);var t=this;jQuery(document).mouseup((function(e){var r=jQuery(t.container),n=r.find(".customize-control-content"),a=r.find(".ast-color-btn-reset-wrap");n.is(e.target)||a.is(e.target)||0!==n.has(e.target).length||0!==a.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),Qt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(xt,{control:this}),this.container[0])},ready:function(){Bt(this,"");var t=this;jQuery(document).mouseup((function(e){var r=jQuery(t.container),n=r.find(".background-wrapper"),a=r.find(".ast-color-btn-reset-wrap");n.is(e.target)||a.is(e.target)||0!==n.has(e.target).length||0!==a.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),Ft=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Ot,{control:this}),this.container[0])},ready:function(){jQuery("html").addClass("background-colorpicker-ready");var t=this;jQuery(document).mouseup((function(e){var r=jQuery(t.container),n=r.find(".background-wrapper"),a=r.find(".ast-color-btn-reset-wrap");n.is(e.target)||a.is(e.target)||0!==n.has(e.target).length||0!==a.has(e.target).length||r.find(".components-button.astra-color-icon-indicate.open").click()}))}}),Yt=function(t){var e=null,r=null,n=t.control.params,o=n.label,i=n.description,s=n.value,c=n.choices,l=n.inputAttrs;o&&(e=Object(a.createElement)("span",{className:"customize-control-title"},o)),i&&(r=Object(a.createElement)("span",{className:"description customize-control-description"},i));var u=Object.values(s).map((function(t){var e="";return c[t]&&(e=Object(a.createElement)("li",R()({},l,{key:t,className:"ast-sortable-item","data-value":t}),Object(a.createElement)("i",{className:"dashicons dashicons-menu"}),Object(a.createElement)("i",{className:"dashicons dashicons-visibility visibility"}),c[t])),e})),p=Object.keys(c).map((function(t){var e="";return Array.isArray(s)&&-1===s.indexOf(t)&&(e=Object(a.createElement)("li",R()({},l,{key:t,className:"ast-sortable-item invisible","data-value":t}),Object(a.createElement)("i",{className:"dashicons dashicons-menu"}),Object(a.createElement)("i",{className:"dashicons dashicons-visibility visibility"}),c[t])),e}));return Object(a.createElement)("label",{className:"ast-sortable"},e,r,Object(a.createElement)("ul",{className:"sortable"},u,p))};Yt.propTypes={control:i.a.object.isRequired};var Gt=React.memo(Yt),Xt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Gt,{control:this}),this.container[0])},ready:function(){var t=this;t.sortableContainer=t.container.find("ul.sortable").first(),t.sortableContainer.sortable({stop:function(){t.updateValue()}}).disableSelection().find("li").each((function(){jQuery(this).find("i.visibility").click((function(){jQuery(this).toggleClass("dashicons-visibility-faint").parents("li:eq(0)").toggleClass("invisible")}))})).click((function(){t.updateValue()}))},updateValue:function(){this.params.choices;var t=[];this.sortableContainer.find("li").each((function(){jQuery(this).is(".invisible")||t.push(jQuery(this).data("value"))})),this.setting.set(t)}}),Jt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(V,{control:this}),this.container[0])}}),Wt=function(t){var e=t.control.params,r=e.linked,n=e.link_text,o=e.link_type,i=null;return r&&n&&(i=Object(a.createElement)("a",{href:"#",onClick:function(){!function(){var e=t.control.params,r=e.linked;switch(e.link_type){case"section":wp.customize.section(r).expand();break;case"control":wp.customize.control(r).focus()}}()},className:"customizer-link","data-customizer-linked":r,"data-ast-customizer-link-type":o,dangerouslySetInnerHTML:{__html:n}})),Object(a.createElement)(a.Fragment,null,i)};Wt.propTypes={control:i.a.object.isRequired};var Zt=React.memo(Wt),Kt=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Zt,{control:this}),this.container[0])}}),$t=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(F,{control:this}),this.container[0])},ready:function(){Rt(this)}}),te=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(X,{control:this}),this.container[0])},ready:function(){Lt(this)}}),ee=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(tt,{control:this}),this.container[0])}}),re=function(t){var e,r=Object(C.useState)(t.control.setting.get()),n=O()(r,2),o=n[0],i=n[1],s=t.control.params,c=s.label,l=s.description,u=s.id,p=s.choices,d=s.inputAttrs,h=s.choices_titles,f=s.link,m=s.labelStyle,g=null,v=null,b=[];(c&&(g=Object(a.createElement)("span",{className:"customize-control-title"},c)),l&&(v=Object(a.createElement)("span",{className:"description customize-control-description"},l)),d)&&d.split(" ").map((function(t,e){var r=t.split("=");void 0!==r[1]&&(b[r[0]]=r[1].replace(/"/g,""))}));f&&f.split(" ").map((function(t,e){var r=t.split("=");void 0!==r[1]&&(b[r[0]]=r[1].replace(/"/g,""))}));return e=Object.entries(p).map((function(e){var r=O()(e,2),n=r[0],s=(r[1],o===n);return Object(a.createElement)(a.Fragment,{key:n},Object(a.createElement)("input",R()({},b,{className:"image-select",type:"radio",value:n,name:"_customize-radio-".concat(u),id:u+n,checked:s,onChange:function(){return function(e){i(e),t.control.setting.set(e)}(n)}})),Object(a.createElement)("label",R()({htmlFor:u+n},m,{className:"ast-radio-img-svg"}),Object(a.createElement)("span",{dangerouslySetInnerHTML:{__html:p[n]}}),Object(a.createElement)("span",{className:"image-clickable",title:h[n]})))})),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",{className:"customizer-text"},g,v),Object(a.createElement)("div",{id:"input_".concat(u),className:"image"},e))};re.propTypes={control:i.a.object.isRequired};var ne=React.memo(re),ae=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(ne,{control:this}),this.container[0])}}),oe=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(K,{control:this}),this.container[0])},ready:function(){It(this)}}),ie=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Pt,{control:this}),this.container[0])}}),se=function(t){var e=t.control.params,r=e.description,n=e.label,o=e.connect,i=e.variant,s=e.name,c=e.link,l=null,u=null,p=null,d=[],h=Object(j.__)("Inherit","astra"),f=t.control.setting.get();(n&&(l=Object(a.createElement)("span",{className:"customize-control-title"},n)),r&&(u=Object(a.createElement)("span",{className:"description customize-control-description"},r)),void 0!==c)&&c.split(" ").map((function(t,e){var r=t.split("=");void 0!==r[1]&&(d[r[0]]=r[1].replace(/"/g,""))}));return o&&i?p=Object(a.createElement)("select",R()({},d,{"data-connected-control":o,"data-connected-variant":i,"data-value":f,"data-name":s,"data-inherit":h})):o?p=Object(a.createElement)("select",R()({},d,{"data-connected-control":o,"data-value":f,"data-name":s,"data-inherit":h})):i&&(p=Object(a.createElement)("select",R()({},d,{"data-connected-variant":i,"data-value":f,"data-name":s,"data-inherit":h}))),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,l,u),p)};se.propTypes={control:i.a.object.isRequired};var ce=React.memo(se),le=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(ce,{control:this}),this.container[0])},ready:function(){AstTypography.init()}}),ue=function(t){var e=t.control.params,r=e.description,n=e.label,o=e.connect,i=e.variant,s=e.name,c=e.link,l=e.ast_all_font_weight,u=t.control.setting.get(),p=Object(a.createElement)("span",{className:"customize-control-title"},n||Object(j.__)("Background","astra")),d=r?Object(a.createElement)("span",{className:"description customize-control-description"},r):null,h=null,f=[],m=Object(j.__)("Inherit","astra"),g=null;(u=void 0===u||""===u?[]:u,c)&&c.split(" ").map((function(t,e){var r=t.split("=");r[1]&&(f[r[0]]=r[1].replace(/"/g,""))}));var v=Object.entries(l).map((function(t){return Object(a.createElement)("option",{key:t[0],value:t[0]},t[1])}));return g="normal"===u?Object(a.createElement)("option",{key:"normal",value:"normal"},m):Object(a.createElement)("option",{key:"inherit",value:"inherit"},m),o&&i?h=Object(a.createElement)("select",R()({},f,{"data-connected-control":o,"data-connected-variant":i,"data-value":u,"data-name":s,"data-inherit":m}),g,v):i?h=Object(a.createElement)("select",R()({},f,{"data-connected-variant":i,"data-value":u,"data-name":s,"data-inherit":m}),g,v):o&&(h=Object(a.createElement)("select",R()({},f,{"data-connected-control":o,"data-value":u,"data-name":s,"data-inherit":m}),g,v)),Object(a.createElement)(a.Fragment,null,Object(a.createElement)("label",null,p,d),h)};ue.propTypes={control:i.a.object.isRequired};var pe=React.memo(ue),de=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(pe,{control:this}),this.container[0])},ready:function(){AstTypography.init()}});function he(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function fe(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var r,n=ft()(t);if(e){var a=ft()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return dt()(this,r)}}var me=function(t){ut()(r,t);var e=fe(r);function r(t){var n;at()(this,r);var a=(n=e.apply(this,arguments)).props.control.setting.get();return n.state={value:a},n.onSelectChange=n.onSelectChange.bind(ct()(n)),n.renderSelectHtml=n.renderSelectHtml.bind(ct()(n)),n}return it()(r,[{key:"onSelectChange",value:function(t,e){var r=function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?he(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):he(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}({},this.state.value);r[e]=t.target.value,this.updateValues(r)}},{key:"renderSelectHtml",value:function(t){var e=this,r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=this.props.control.params.choices,o=Object.entries(n).map((function(t){return Object(a.createElement)("option",{key:t[0],value:t[0]},t[1])}));return Object(a.createElement)("div",{className:"ast-responsive-select-container ".concat(t," ").concat(r)},Object(a.createElement)("select",{className:"ast-select-input","data-value":this.state.value[t],value:this.state.value[t],onChange:function(r){e.onSelectChange(r,t)}},o))}},{key:"render",value:function(){var t=this.props.control.params.label,e=null;t&&(e=Object(a.createElement)("span",{className:"customize-control-title"},t));var r=Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-btns"},Object(a.createElement)("li",{key:"desktop",className:"desktop active"},Object(a.createElement)("button",{type:"button",className:"preview-desktop","data-device":"desktop"},Object(a.createElement)("i",{className:"dashicons dashicons-desktop"}))),Object(a.createElement)("li",{key:"tablet",className:"tablet"},Object(a.createElement)("button",{type:"button",className:"preview-tablet","data-device":"tablet"},Object(a.createElement)("i",{className:"dashicons dashicons-tablet"}))),Object(a.createElement)("li",{key:"mobile",className:"mobile"},Object(a.createElement)("button",{type:"button",className:"preview-mobile","data-device":"mobile"},Object(a.createElement)("i",{className:"dashicons dashicons-smartphone"})))),n=Object(a.createElement)(a.Fragment,null,this.renderSelectHtml("desktop","active"),this.renderSelectHtml("tablet"),this.renderSelectHtml("mobile"));return Object(a.createElement)(a.Fragment,null,e,r,Object(a.createElement)("div",{className:"customize-control-content"},Object(a.createElement)("div",{className:"ast-responsive-select-wrapper"},n)))}},{key:"updateValues",value:function(t){this.setState({value:t}),this.props.control.setting.set(t)}}]),r}(a.Component);me.propTypes={control:i.a.object.isRequired};var ge=me,ve=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(ge,{control:this}),this.container[0])},ready:function(){var t=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container").removeClass("active"),jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container."+t).addClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li."+t).addClass("active"),jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var t=jQuery(this).attr("data-device");jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container").removeClass("active"),jQuery(".customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container."+t).addClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li").removeClass("active"),jQuery(".customize-control-ast-responsive-select .ast-responsive-btns li."+t).addClass("active")})),this.container.find(".ast-responsive-btns button").on("click",(function(t){var e=jQuery(this).attr("data-device");e="desktop"==e?"tablet":"tablet"==e?"mobile":"desktop",jQuery('.wp-full-overlay-footer .devices button[data-device="'+e+'"]').trigger("click")}))}}),be=r(43),ye=r.n(be),we=wp.i18n.__,_e=function(t){return"section-footer-builder"===t.control.params.section||"section-header-builder"===t.control.params.section?Object(a.createElement)(x.a.Fragment,null,Object(a.createElement)("p",{className:"ast-customize-control-title"},!astra.customizer.is_pro&&Object(a.createElement)(a.Fragment,null,we("Want more? Upgrade to ","astra"),Object(a.createElement)("a",{href:astra.customizer.upgrade_link,target:"_blank"},we("Astra Pro","astra")),we(" for many more header and footer options along with several amazing features too!","astra"))),Object(a.createElement)("p",{className:"ast-customize-control-description"},Object(a.createElement)("span",{className:"button button-secondary ahfb-builder-section-shortcut "+t.control.params.section,"data-section":t.control.params.section,onClick:function(){return function(t){t.customizer.section.each((function(t){if(t.expanded())return t.collapse(),!1}))}(t)}},Object(a.createElement)("span",{className:"dashicons dashicons-admin-generic"}," ")),Object(a.createElement)("span",{className:"button button-secondary ahfb-builder-hide-button ahfb-builder-tab-toggle"},Object(a.createElement)("span",{className:"ast-builder-hide-action"}," ",Object(a.createElement)("span",{className:"dashicons dashicons-arrow-down-alt2"})," ",we("Hide","astra")," "),Object(a.createElement)("span",{className:"ast-builder-show-action"}," ",Object(a.createElement)("span",{className:"dashicons dashicons-arrow-up-alt2"})," ",we("Show","astra")," ")))):Object(a.createElement)(x.a.Fragment,null,Object(a.createElement)("div",{className:"ahfb-compontent-tabs nav-tab-wrapper wp-clearfix"},Object(a.createElement)("a",{href:"#",className:"nav-tab ahfb-general-tab ahfb-compontent-tabs-button "+("general"===t.tab?"nav-tab-active":""),"data-tab":"general"},Object(a.createElement)("span",null,we("General","astra"))),Object(a.createElement)("a",{href:"#",className:"nav-tab ahfb-design-tab ahfb-compontent-tabs-button "+("design"===t.tab?"nav-tab-active":""),"data-tab":"design"},Object(a.createElement)("span",null,we("Design","astra")))))};x.a.memo(_e);var Oe=wp.customize.astraControl.extend({renderContent:function(){ye.a.render(Object(a.createElement)(_e,{control:this,tab:wp.customize.state("astra-customizer-tab").get(),customizer:wp.customize}),this.container[0])}});
/**!
 * Sortable 1.10.1
 * @author	RubaXa   <trash@rubaxa.org>
 * @author	owenm    <owen23355@gmail.com>
 * @license MIT
 */function je(t){return(je="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function Ee(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}function Ce(){return(Ce=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var r=arguments[e];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(t[n]=r[n])}return t}).apply(this,arguments)}function xe(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{},n=Object.keys(r);"function"==typeof Object.getOwnPropertySymbols&&(n=n.concat(Object.getOwnPropertySymbols(r).filter((function(t){return Object.getOwnPropertyDescriptor(r,t).enumerable})))),n.forEach((function(e){Ee(t,e,r[e])}))}return t}function ke(t,e){if(null==t)return{};var r,n,a=function(t,e){if(null==t)return{};var r,n,a={},o=Object.keys(t);for(n=0;n<o.length;n++)r=o[n],e.indexOf(r)>=0||(a[r]=t[r]);return a}(t,e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);for(n=0;n<o.length;n++)r=o[n],e.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(t,r)&&(a[r]=t[r])}return a}function ze(t){if("undefined"!=typeof window&&window.navigator)return!!navigator.userAgent.match(t)}var Ne=ze(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i),Se=ze(/Edge/i),Me=ze(/firefox/i),Ae=ze(/safari/i)&&!ze(/chrome/i)&&!ze(/android/i),De=ze(/iP(ad|od|hone)/i),Te=ze(/chrome/i)&&ze(/android/i),Pe={capture:!1,passive:!1};function Be(t,e,r){t.addEventListener(e,r,!Ne&&Pe)}function He(t,e,r){t.removeEventListener(e,r,!Ne&&Pe)}function Re(t,e){if(e){if(">"===e[0]&&(e=e.substring(1)),t)try{if(t.matches)return t.matches(e);if(t.msMatchesSelector)return t.msMatchesSelector(e);if(t.webkitMatchesSelector)return t.webkitMatchesSelector(e)}catch(t){return!1}return!1}}function Le(t){return t.host&&t!==document&&t.host.nodeType?t.host:t.parentNode}function Ie(t,e,r,n){if(t){r=r||document;do{if(null!=e&&(">"===e[0]?t.parentNode===r&&Re(t,e):Re(t,e))||n&&t===r)return t;if(t===r)break}while(t=Le(t))}return null}var Ve,qe=/\s+/g;function Ue(t,e,r){if(t&&e)if(t.classList)t.classList[r?"add":"remove"](e);else{var n=(" "+t.className+" ").replace(qe," ").replace(" "+e+" "," ");t.className=(n+(r?" "+e:"")).replace(qe," ")}}function Qe(t,e,r){var n=t&&t.style;if(n){if(void 0===r)return document.defaultView&&document.defaultView.getComputedStyle?r=document.defaultView.getComputedStyle(t,""):t.currentStyle&&(r=t.currentStyle),void 0===e?r:r[e];e in n||-1!==e.indexOf("webkit")||(e="-webkit-"+e),n[e]=r+("string"==typeof r?"":"px")}}function Fe(t,e){var r="";if("string"==typeof t)r=t;else do{var n=Qe(t,"transform");n&&"none"!==n&&(r=n+" "+r)}while(!e&&(t=t.parentNode));var a=window.DOMMatrix||window.WebKitCSSMatrix||window.CSSMatrix;return a&&new a(r)}function Ye(t,e,r){if(t){var n=t.getElementsByTagName(e),a=0,o=n.length;if(r)for(;a<o;a++)r(n[a],a);return n}return[]}function Ge(){return Ne?document.documentElement:document.scrollingElement}function Xe(t,e,r,n,a){if(t.getBoundingClientRect||t===window){var o,i,s,c,l,u,p;if(t!==window&&t!==Ge()?(i=(o=t.getBoundingClientRect()).top,s=o.left,c=o.bottom,l=o.right,u=o.height,p=o.width):(i=0,s=0,c=window.innerHeight,l=window.innerWidth,u=window.innerHeight,p=window.innerWidth),(e||r)&&t!==window&&(a=a||t.parentNode,!Ne))do{if(a&&a.getBoundingClientRect&&("none"!==Qe(a,"transform")||r&&"static"!==Qe(a,"position"))){var d=a.getBoundingClientRect();i-=d.top+parseInt(Qe(a,"border-top-width")),s-=d.left+parseInt(Qe(a,"border-left-width")),c=i+o.height,l=s+o.width;break}}while(a=a.parentNode);if(n&&t!==window){var h=Fe(a||t),f=h&&h.a,m=h&&h.d;h&&(c=(i/=m)+(u/=m),l=(s/=f)+(p/=f))}return{top:i,left:s,bottom:c,right:l,width:p,height:u}}}function Je(t,e,r){for(var n=tr(t,!0),a=Xe(t)[e];n;){var o=Xe(n)[r];if(!("top"===r||"left"===r?a>=o:a<=o))return n;if(n===Ge())break;n=tr(n,!1)}return!1}function We(t,e,r){for(var n=0,a=0,o=t.children;a<o.length;){if("none"!==o[a].style.display&&o[a]!==nn.ghost&&o[a]!==nn.dragged&&Ie(o[a],r.draggable,t,!1)){if(n===e)return o[a];n++}a++}return null}function Ze(t,e){for(var r=t.lastElementChild;r&&(r===nn.ghost||"none"===Qe(r,"display")||e&&!Re(r,e));)r=r.previousElementSibling;return r||null}function Ke(t,e){var r=0;if(!t||!t.parentNode)return-1;for(;t=t.previousElementSibling;)"TEMPLATE"===t.nodeName.toUpperCase()||t===nn.clone||e&&!Re(t,e)||r++;return r}function $e(t){var e=0,r=0,n=Ge();if(t)do{var a=Fe(t),o=a.a,i=a.d;e+=t.scrollLeft*o,r+=t.scrollTop*i}while(t!==n&&(t=t.parentNode));return[e,r]}function tr(t,e){if(!t||!t.getBoundingClientRect)return Ge();var r=t,n=!1;do{if(r.clientWidth<r.scrollWidth||r.clientHeight<r.scrollHeight){var a=Qe(r);if(r.clientWidth<r.scrollWidth&&("auto"==a.overflowX||"scroll"==a.overflowX)||r.clientHeight<r.scrollHeight&&("auto"==a.overflowY||"scroll"==a.overflowY)){if(!r.getBoundingClientRect||r===document.body)return Ge();if(n||e)return r;n=!0}}}while(r=r.parentNode);return Ge()}function er(t,e){return Math.round(t.top)===Math.round(e.top)&&Math.round(t.left)===Math.round(e.left)&&Math.round(t.height)===Math.round(e.height)&&Math.round(t.width)===Math.round(e.width)}function rr(t,e){return function(){if(!Ve){var r=arguments,n=this;1===r.length?t.call(n,r[0]):t.apply(n,r),Ve=setTimeout((function(){Ve=void 0}),e)}}}function nr(t,e,r){t.scrollLeft+=e,t.scrollTop+=r}function ar(t){var e=window.Polymer,r=window.jQuery||window.Zepto;return e&&e.dom?e.dom(t).cloneNode(!0):r?r(t).clone(!0)[0]:t.cloneNode(!0)}var or="Sortable"+(new Date).getTime();function ir(){var t,e=[];return{captureAnimationState:function(){(e=[],this.options.animation)&&[].slice.call(this.el.children).forEach((function(t){if("none"!==Qe(t,"display")&&t!==nn.ghost){e.push({target:t,rect:Xe(t)});var r=xe({},e[e.length-1].rect);if(t.thisAnimationDuration){var n=Fe(t,!0);n&&(r.top-=n.f,r.left-=n.e)}t.fromRect=r}}))},addAnimationState:function(t){e.push(t)},removeAnimationState:function(t){e.splice(function(t,e){for(var r in t)if(t.hasOwnProperty(r))for(var n in e)if(e.hasOwnProperty(n)&&e[n]===t[r][n])return Number(r);return-1}(e,{target:t}),1)},animateAll:function(r){var n=this;if(!this.options.animation)return clearTimeout(t),void("function"==typeof r&&r());var a=!1,o=0;e.forEach((function(t){var e=0,r=t.target,i=r.fromRect,s=Xe(r),c=r.prevFromRect,l=r.prevToRect,u=t.rect,p=Fe(r,!0);p&&(s.top-=p.f,s.left-=p.e),r.toRect=s,r.thisAnimationDuration&&er(c,s)&&!er(i,s)&&(u.top-s.top)/(u.left-s.left)==(i.top-s.top)/(i.left-s.left)&&(e=function(t,e,r,n){return Math.sqrt(Math.pow(e.top-t.top,2)+Math.pow(e.left-t.left,2))/Math.sqrt(Math.pow(e.top-r.top,2)+Math.pow(e.left-r.left,2))*n.animation}(u,c,l,n.options)),er(s,i)||(r.prevFromRect=i,r.prevToRect=s,e||(e=n.options.animation),n.animate(r,u,s,e)),e&&(a=!0,o=Math.max(o,e),clearTimeout(r.animationResetTimer),r.animationResetTimer=setTimeout((function(){r.animationTime=0,r.prevFromRect=null,r.fromRect=null,r.prevToRect=null,r.thisAnimationDuration=null}),e),r.thisAnimationDuration=e)})),clearTimeout(t),a?t=setTimeout((function(){"function"==typeof r&&r()}),o):"function"==typeof r&&r(),e=[]},animate:function(t,e,r,n){if(n){Qe(t,"transition",""),Qe(t,"transform","");var a=Fe(this.el),o=a&&a.a,i=a&&a.d,s=(e.left-r.left)/(o||1),c=(e.top-r.top)/(i||1);t.animatingX=!!s,t.animatingY=!!c,Qe(t,"transform","translate3d("+s+"px,"+c+"px,0)"),function(t){t.offsetWidth}(t),Qe(t,"transition","transform "+n+"ms"+(this.options.easing?" "+this.options.easing:"")),Qe(t,"transform","translate3d(0,0,0)"),"number"==typeof t.animated&&clearTimeout(t.animated),t.animated=setTimeout((function(){Qe(t,"transition",""),Qe(t,"transform",""),t.animated=!1,t.animatingX=!1,t.animatingY=!1}),n)}}}}var sr=[],cr={initializeByDefault:!0},lr={mount:function(t){for(var e in cr)cr.hasOwnProperty(e)&&!(e in t)&&(t[e]=cr[e]);sr.push(t)},pluginEvent:function(t,e,r){var n=this;this.eventCanceled=!1,r.cancel=function(){n.eventCanceled=!0};var a=t+"Global";sr.forEach((function(n){e[n.pluginName]&&(e[n.pluginName][a]&&e[n.pluginName][a](xe({sortable:e},r)),e.options[n.pluginName]&&e[n.pluginName][t]&&e[n.pluginName][t](xe({sortable:e},r)))}))},initializePlugins:function(t,e,r,n){for(var a in sr.forEach((function(n){var a=n.pluginName;if(t.options[a]||n.initializeByDefault){var o=new n(t,e,t.options);o.sortable=t,o.options=t.options,t[a]=o,Ce(r,o.defaults)}})),t.options)if(t.options.hasOwnProperty(a)){var o=this.modifyOption(t,a,t.options[a]);void 0!==o&&(t.options[a]=o)}},getEventProperties:function(t,e){var r={};return sr.forEach((function(n){"function"==typeof n.eventProperties&&Ce(r,n.eventProperties.call(e[n.pluginName],t))})),r},modifyOption:function(t,e,r){var n;return sr.forEach((function(a){t[a.pluginName]&&a.optionListeners&&"function"==typeof a.optionListeners[e]&&(n=a.optionListeners[e].call(t[a.pluginName],r))})),n}};function ur(t){var e=t.sortable,r=t.rootEl,n=t.name,a=t.targetEl,o=t.cloneEl,i=t.toEl,s=t.fromEl,c=t.oldIndex,l=t.newIndex,u=t.oldDraggableIndex,p=t.newDraggableIndex,d=t.originalEvent,h=t.putSortable,f=t.extraEventProperties;if(e=e||r&&r[or]){var m,g=e.options,v="on"+n.charAt(0).toUpperCase()+n.substr(1);!window.CustomEvent||Ne||Se?(m=document.createEvent("Event")).initEvent(n,!0,!0):m=new CustomEvent(n,{bubbles:!0,cancelable:!0}),m.to=i||r,m.from=s||r,m.item=a||r,m.clone=o,m.oldIndex=c,m.newIndex=l,m.oldDraggableIndex=u,m.newDraggableIndex=p,m.originalEvent=d,m.pullMode=h?h.lastPutMode:void 0;var b=xe({},f,lr.getEventProperties(n,e));for(var y in b)m[y]=b[y];r&&r.dispatchEvent(m),g[v]&&g[v].call(e,m)}}var pr=function(t,e){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},n=r.evt,a=ke(r,["evt"]);lr.pluginEvent.bind(nn)(t,e,xe({dragEl:hr,parentEl:fr,ghostEl:mr,rootEl:gr,nextEl:vr,lastDownEl:br,cloneEl:yr,cloneHidden:wr,dragStarted:Dr,putSortable:xr,activeSortable:nn.active,originalEvent:n,oldIndex:_r,oldDraggableIndex:jr,newIndex:Or,newDraggableIndex:Er,hideGhostForTarget:$r,unhideGhostForTarget:tn,cloneNowHidden:function(){wr=!0},cloneNowShown:function(){wr=!1},dispatchSortableEvent:function(t){dr({sortable:e,name:t,originalEvent:n})}},a))};function dr(t){ur(xe({putSortable:xr,cloneEl:yr,targetEl:hr,rootEl:gr,oldIndex:_r,oldDraggableIndex:jr,newIndex:Or,newDraggableIndex:Er},t))}var hr,fr,mr,gr,vr,br,yr,wr,_r,Or,jr,Er,Cr,xr,kr,zr,Nr,Sr,Mr,Ar,Dr,Tr,Pr,Br,Hr,Rr=!1,Lr=!1,Ir=[],Vr=!1,qr=!1,Ur=[],Qr=!1,Fr=[],Yr="undefined"!=typeof document,Gr=De,Xr=Se||Ne?"cssFloat":"float",Jr=Yr&&!Te&&!De&&"draggable"in document.createElement("div"),Wr=function(){if(Yr){if(Ne)return!1;var t=document.createElement("x");return t.style.cssText="pointer-events:auto","auto"===t.style.pointerEvents}}(),Zr=function(t,e){var r=Qe(t),n=parseInt(r.width)-parseInt(r.paddingLeft)-parseInt(r.paddingRight)-parseInt(r.borderLeftWidth)-parseInt(r.borderRightWidth),a=We(t,0,e),o=We(t,1,e),i=a&&Qe(a),s=o&&Qe(o),c=i&&parseInt(i.marginLeft)+parseInt(i.marginRight)+Xe(a).width,l=s&&parseInt(s.marginLeft)+parseInt(s.marginRight)+Xe(o).width;if("flex"===r.display)return"column"===r.flexDirection||"column-reverse"===r.flexDirection?"vertical":"horizontal";if("grid"===r.display)return r.gridTemplateColumns.split(" ").length<=1?"vertical":"horizontal";if(a&&i.float&&"none"!==i.float){var u="left"===i.float?"left":"right";return!o||"both"!==s.clear&&s.clear!==u?"horizontal":"vertical"}return a&&("block"===i.display||"flex"===i.display||"table"===i.display||"grid"===i.display||c>=n&&"none"===r[Xr]||o&&"none"===r[Xr]&&c+l>n)?"vertical":"horizontal"},Kr=function(t){function e(t,r){return function(n,a,o,i){var s=n.options.group.name&&a.options.group.name&&n.options.group.name===a.options.group.name;if(null==t&&(r||s))return!0;if(null==t||!1===t)return!1;if(r&&"clone"===t)return t;if("function"==typeof t)return e(t(n,a,o,i),r)(n,a,o,i);var c=(r?n:a).options.group.name;return!0===t||"string"==typeof t&&t===c||t.join&&t.indexOf(c)>-1}}var r={},n=t.group;n&&"object"==je(n)||(n={name:n}),r.name=n.name,r.checkPull=e(n.pull,!0),r.checkPut=e(n.put),r.revertClone=n.revertClone,t.group=r},$r=function(){!Wr&&mr&&Qe(mr,"display","none")},tn=function(){!Wr&&mr&&Qe(mr,"display","")};Yr&&document.addEventListener("click",(function(t){if(Lr)return t.preventDefault(),t.stopPropagation&&t.stopPropagation(),t.stopImmediatePropagation&&t.stopImmediatePropagation(),Lr=!1,!1}),!0);var en=function(t){if(hr){t=t.touches?t.touches[0]:t;var e=(a=t.clientX,o=t.clientY,Ir.some((function(t){if(!Ze(t)){var e=Xe(t),r=t[or].options.emptyInsertThreshold,n=a>=e.left-r&&a<=e.right+r,s=o>=e.top-r&&o<=e.bottom+r;return r&&n&&s?i=t:void 0}})),i);if(e){var r={};for(var n in t)t.hasOwnProperty(n)&&(r[n]=t[n]);r.target=r.rootEl=e,r.preventDefault=void 0,r.stopPropagation=void 0,e[or]._onDragOver(r)}}var a,o,i},rn=function(t){hr&&hr.parentNode[or]._isOutsideThisEl(t.target)};function nn(t,e){if(!t||!t.nodeType||1!==t.nodeType)throw"Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(t));this.el=t,this.options=e=Ce({},e),t[or]=this;var r={group:null,sort:!0,disabled:!1,store:null,handle:null,draggable:/^[uo]l$/i.test(t.nodeName)?">li":">*",swapThreshold:1,invertSwap:!1,invertedSwapThreshold:null,removeCloneOnHide:!0,direction:function(){return Zr(t,this.options)},ghostClass:"sortable-ghost",chosenClass:"sortable-chosen",dragClass:"sortable-drag",ignore:"a, img",filter:null,preventOnFilter:!0,animation:0,easing:null,setData:function(t,e){t.setData("Text",e.textContent)},dropBubble:!1,dragoverBubble:!1,dataIdAttr:"data-id",delay:0,delayOnTouchOnly:!1,touchStartThreshold:(Number.parseInt?Number:window).parseInt(window.devicePixelRatio,10)||1,forceFallback:!1,fallbackClass:"sortable-fallback",fallbackOnBody:!1,fallbackTolerance:0,fallbackOffset:{x:0,y:0},supportPointer:!1!==nn.supportPointer&&"PointerEvent"in window,emptyInsertThreshold:5};for(var n in lr.initializePlugins(this,t,r),r)!(n in e)&&(e[n]=r[n]);for(var a in Kr(e),this)"_"===a.charAt(0)&&"function"==typeof this[a]&&(this[a]=this[a].bind(this));this.nativeDraggable=!e.forceFallback&&Jr,this.nativeDraggable&&(this.options.touchStartThreshold=1),e.supportPointer?Be(t,"pointerdown",this._onTapStart):(Be(t,"mousedown",this._onTapStart),Be(t,"touchstart",this._onTapStart)),this.nativeDraggable&&(Be(t,"dragover",this),Be(t,"dragenter",this)),Ir.push(this.el),e.store&&e.store.get&&this.sort(e.store.get(this)||[]),Ce(this,ir())}function an(t,e,r,n,a,o,i,s){var c,l,u=t[or],p=u.options.onMove;return!window.CustomEvent||Ne||Se?(c=document.createEvent("Event")).initEvent("move",!0,!0):c=new CustomEvent("move",{bubbles:!0,cancelable:!0}),c.to=e,c.from=t,c.dragged=r,c.draggedRect=n,c.related=a||e,c.relatedRect=o||Xe(e),c.willInsertAfter=s,c.originalEvent=i,t.dispatchEvent(c),p&&(l=p.call(u,c,i)),l}function on(t){t.draggable=!1}function sn(){Qr=!1}function cn(t){for(var e=t.tagName+t.className+t.src+t.href+t.textContent,r=e.length,n=0;r--;)n+=e.charCodeAt(r);return n.toString(36)}function ln(t){return setTimeout(t,0)}function un(t){return clearTimeout(t)}nn.prototype={constructor:nn,_isOutsideThisEl:function(t){this.el.contains(t)||t===this.el||(Tr=null)},_getDirection:function(t,e){return"function"==typeof this.options.direction?this.options.direction.call(this,t,e,hr):this.options.direction},_onTapStart:function(t){if(t.cancelable){var e=this,r=this.el,n=this.options,a=n.preventOnFilter,o=t.type,i=t.touches&&t.touches[0]||t.pointerType&&"touch"===t.pointerType&&t,s=(i||t).target,c=t.target.shadowRoot&&(t.path&&t.path[0]||t.composedPath&&t.composedPath()[0])||s,l=n.filter;if(function(t){Fr.length=0;var e=t.getElementsByTagName("input"),r=e.length;for(;r--;){var n=e[r];n.checked&&Fr.push(n)}}(r),!hr&&!(/mousedown|pointerdown/.test(o)&&0!==t.button||n.disabled||c.isContentEditable||(s=Ie(s,n.draggable,r,!1))&&s.animated||br===s)){if(_r=Ke(s),jr=Ke(s,n.draggable),"function"==typeof l){if(l.call(this,t,s,this))return dr({sortable:e,rootEl:c,name:"filter",targetEl:s,toEl:r,fromEl:r}),pr("filter",e,{evt:t}),void(a&&t.cancelable&&t.preventDefault())}else if(l&&(l=l.split(",").some((function(n){if(n=Ie(c,n.trim(),r,!1))return dr({sortable:e,rootEl:n,name:"filter",targetEl:s,fromEl:r,toEl:r}),pr("filter",e,{evt:t}),!0}))))return void(a&&t.cancelable&&t.preventDefault());n.handle&&!Ie(c,n.handle,r,!1)||this._prepareDragStart(t,i,s)}}},_prepareDragStart:function(t,e,r){var n,a=this,o=a.el,i=a.options,s=o.ownerDocument;if(r&&!hr&&r.parentNode===o){var c=Xe(r);if(gr=o,fr=(hr=r).parentNode,vr=hr.nextSibling,br=r,Cr=i.group,nn.dragged=hr,kr={target:hr,clientX:(e||t).clientX,clientY:(e||t).clientY},Mr=kr.clientX-c.left,Ar=kr.clientY-c.top,this._lastX=(e||t).clientX,this._lastY=(e||t).clientY,hr.style["will-change"]="all",n=function(){pr("delayEnded",a,{evt:t}),nn.eventCanceled?a._onDrop():(a._disableDelayedDragEvents(),!Me&&a.nativeDraggable&&(hr.draggable=!0),a._triggerDragStart(t,e),dr({sortable:a,name:"choose",originalEvent:t}),Ue(hr,i.chosenClass,!0))},i.ignore.split(",").forEach((function(t){Ye(hr,t.trim(),on)})),Be(s,"dragover",en),Be(s,"mousemove",en),Be(s,"touchmove",en),Be(s,"mouseup",a._onDrop),Be(s,"touchend",a._onDrop),Be(s,"touchcancel",a._onDrop),Me&&this.nativeDraggable&&(this.options.touchStartThreshold=4,hr.draggable=!0),pr("delayStart",this,{evt:t}),!i.delay||i.delayOnTouchOnly&&!e||this.nativeDraggable&&(Se||Ne))n();else{if(nn.eventCanceled)return void this._onDrop();Be(s,"mouseup",a._disableDelayedDrag),Be(s,"touchend",a._disableDelayedDrag),Be(s,"touchcancel",a._disableDelayedDrag),Be(s,"mousemove",a._delayedDragTouchMoveHandler),Be(s,"touchmove",a._delayedDragTouchMoveHandler),i.supportPointer&&Be(s,"pointermove",a._delayedDragTouchMoveHandler),a._dragStartTimer=setTimeout(n,i.delay)}}},_delayedDragTouchMoveHandler:function(t){var e=t.touches?t.touches[0]:t;Math.max(Math.abs(e.clientX-this._lastX),Math.abs(e.clientY-this._lastY))>=Math.floor(this.options.touchStartThreshold/(this.nativeDraggable&&window.devicePixelRatio||1))&&this._disableDelayedDrag()},_disableDelayedDrag:function(){hr&&on(hr),clearTimeout(this._dragStartTimer),this._disableDelayedDragEvents()},_disableDelayedDragEvents:function(){var t=this.el.ownerDocument;He(t,"mouseup",this._disableDelayedDrag),He(t,"touchend",this._disableDelayedDrag),He(t,"touchcancel",this._disableDelayedDrag),He(t,"mousemove",this._delayedDragTouchMoveHandler),He(t,"touchmove",this._delayedDragTouchMoveHandler),He(t,"pointermove",this._delayedDragTouchMoveHandler)},_triggerDragStart:function(t,e){e=e||"touch"==t.pointerType&&t,!this.nativeDraggable||e?this.options.supportPointer?Be(document,"pointermove",this._onTouchMove):Be(document,e?"touchmove":"mousemove",this._onTouchMove):(Be(hr,"dragend",this),Be(gr,"dragstart",this._onDragStart));try{document.selection?ln((function(){document.selection.empty()})):window.getSelection().removeAllRanges()}catch(t){}},_dragStarted:function(t,e){if(Rr=!1,gr&&hr){pr("dragStarted",this,{evt:e}),this.nativeDraggable&&Be(document,"dragover",rn);var r=this.options;!t&&Ue(hr,r.dragClass,!1),Ue(hr,r.ghostClass,!0),nn.active=this,t&&this._appendGhost(),dr({sortable:this,name:"start",originalEvent:e})}else this._nulling()},_emulateDragOver:function(){if(zr){this._lastX=zr.clientX,this._lastY=zr.clientY,$r();for(var t=document.elementFromPoint(zr.clientX,zr.clientY),e=t;t&&t.shadowRoot&&(t=t.shadowRoot.elementFromPoint(zr.clientX,zr.clientY))!==e;)e=t;if(hr.parentNode[or]._isOutsideThisEl(t),e)do{if(e[or]){if(e[or]._onDragOver({clientX:zr.clientX,clientY:zr.clientY,target:t,rootEl:e})&&!this.options.dragoverBubble)break}t=e}while(e=e.parentNode);tn()}},_onTouchMove:function(t){if(kr){var e=this.options,r=e.fallbackTolerance,n=e.fallbackOffset,a=t.touches?t.touches[0]:t,o=mr&&Fe(mr),i=mr&&o&&o.a,s=mr&&o&&o.d,c=Gr&&Hr&&$e(Hr),l=(a.clientX-kr.clientX+n.x)/(i||1)+(c?c[0]-Ur[0]:0)/(i||1),u=(a.clientY-kr.clientY+n.y)/(s||1)+(c?c[1]-Ur[1]:0)/(s||1);if(!nn.active&&!Rr){if(r&&Math.max(Math.abs(a.clientX-this._lastX),Math.abs(a.clientY-this._lastY))<r)return;this._onDragStart(t,!0)}if(mr){o?(o.e+=l-(Nr||0),o.f+=u-(Sr||0)):o={a:1,b:0,c:0,d:1,e:l,f:u};var p="matrix(".concat(o.a,",").concat(o.b,",").concat(o.c,",").concat(o.d,",").concat(o.e,",").concat(o.f,")");Qe(mr,"webkitTransform",p),Qe(mr,"mozTransform",p),Qe(mr,"msTransform",p),Qe(mr,"transform",p),Nr=l,Sr=u,zr=a}t.cancelable&&t.preventDefault()}},_appendGhost:function(){if(!mr){var t=this.options.fallbackOnBody?document.body:gr,e=Xe(hr,!0,Gr,!0,t),r=this.options;if(Gr){for(Hr=t;"static"===Qe(Hr,"position")&&"none"===Qe(Hr,"transform")&&Hr!==document;)Hr=Hr.parentNode;Hr!==document.body&&Hr!==document.documentElement?(Hr===document&&(Hr=Ge()),e.top+=Hr.scrollTop,e.left+=Hr.scrollLeft):Hr=Ge(),Ur=$e(Hr)}Ue(mr=hr.cloneNode(!0),r.ghostClass,!1),Ue(mr,r.fallbackClass,!0),Ue(mr,r.dragClass,!0),Qe(mr,"transition",""),Qe(mr,"transform",""),Qe(mr,"box-sizing","border-box"),Qe(mr,"margin",0),Qe(mr,"top",e.top),Qe(mr,"left",e.left),Qe(mr,"width",e.width),Qe(mr,"height",e.height),Qe(mr,"opacity","0.8"),Qe(mr,"position",Gr?"absolute":"fixed"),Qe(mr,"zIndex","100000"),Qe(mr,"pointerEvents","none"),nn.ghost=mr,t.appendChild(mr),Qe(mr,"transform-origin",Mr/parseInt(mr.style.width)*100+"% "+Ar/parseInt(mr.style.height)*100+"%")}},_onDragStart:function(t,e){var r=this,n=t.dataTransfer,a=r.options;pr("dragStart",this,{evt:t}),nn.eventCanceled?this._onDrop():(pr("setupClone",this),nn.eventCanceled||((yr=ar(hr)).draggable=!1,yr.style["will-change"]="",this._hideClone(),Ue(yr,this.options.chosenClass,!1),nn.clone=yr),r.cloneId=ln((function(){pr("clone",r),nn.eventCanceled||(r.options.removeCloneOnHide||gr.insertBefore(yr,hr),r._hideClone(),dr({sortable:r,name:"clone"}))})),!e&&Ue(hr,a.dragClass,!0),e?(Lr=!0,r._loopId=setInterval(r._emulateDragOver,50)):(He(document,"mouseup",r._onDrop),He(document,"touchend",r._onDrop),He(document,"touchcancel",r._onDrop),n&&(n.effectAllowed="move",a.setData&&a.setData.call(r,n,hr)),Be(document,"drop",r),Qe(hr,"transform","translateZ(0)")),Rr=!0,r._dragStartId=ln(r._dragStarted.bind(r,e,t)),Be(document,"selectstart",r),Dr=!0,Ae&&Qe(document.body,"user-select","none"))},_onDragOver:function(t){var e,r,n,a,o=this.el,i=t.target,s=this.options,c=s.group,l=nn.active,u=Cr===c,p=s.sort,d=xr||l,h=this,f=!1;if(!Qr){if(void 0!==t.preventDefault&&t.cancelable&&t.preventDefault(),i=Ie(i,s.draggable,o,!0),z("dragOver"),nn.eventCanceled)return f;if(hr.contains(t.target)||i.animated&&i.animatingX&&i.animatingY||h._ignoreWhileAnimating===i)return S(!1);if(Lr=!1,l&&!s.disabled&&(u?p||(n=!gr.contains(hr)):xr===this||(this.lastPutMode=Cr.checkPull(this,l,hr,t))&&c.checkPut(this,l,hr,t))){if(a="vertical"===this._getDirection(t,i),e=Xe(hr),z("dragOverValid"),nn.eventCanceled)return f;if(n)return fr=gr,N(),this._hideClone(),z("revert"),nn.eventCanceled||(vr?gr.insertBefore(hr,vr):gr.appendChild(hr)),S(!0);var m=Ze(o,s.draggable);if(!m||function(t,e,r){var n=Xe(Ze(r.el,r.options.draggable));return e?t.clientX>n.right+10||t.clientX<=n.right&&t.clientY>n.bottom&&t.clientX>=n.left:t.clientX>n.right&&t.clientY>n.top||t.clientX<=n.right&&t.clientY>n.bottom+10}(t,a,this)&&!m.animated){if(m===hr)return S(!1);if(m&&o===t.target&&(i=m),i&&(r=Xe(i)),!1!==an(gr,o,hr,e,i,r,t,!!i))return N(),o.appendChild(hr),fr=o,M(),S(!0)}else if(i.parentNode===o){r=Xe(i);var g,v,b,y=hr.parentNode!==o,w=!function(t,e,r){var n=r?t.left:t.top,a=r?t.right:t.bottom,o=r?t.width:t.height,i=r?e.left:e.top,s=r?e.right:e.bottom,c=r?e.width:e.height;return n===i||a===s||n+o/2===i+c/2}(hr.animated&&hr.toRect||e,i.animated&&i.toRect||r,a),_=a?"top":"left",O=Je(i,"top","top")||Je(hr,"top","top"),j=O?O.scrollTop:void 0;if(Tr!==i&&(v=r[_],Vr=!1,qr=!w&&s.invertSwap||y),0!==(g=function(t,e,r,n,a,o,i,s){var c=n?t.clientY:t.clientX,l=n?r.height:r.width,u=n?r.top:r.left,p=n?r.bottom:r.right,d=!1;if(!i)if(s&&Br<l*a){if(!Vr&&(1===Pr?c>u+l*o/2:c<p-l*o/2)&&(Vr=!0),Vr)d=!0;else if(1===Pr?c<u+Br:c>p-Br)return-Pr}else if(c>u+l*(1-a)/2&&c<p-l*(1-a)/2)return function(t){return Ke(hr)<Ke(t)?1:-1}(e);if((d=d||i)&&(c<u+l*o/2||c>p-l*o/2))return c>u+l/2?1:-1;return 0}(t,i,r,a,w?1:s.swapThreshold,null==s.invertedSwapThreshold?s.swapThreshold:s.invertedSwapThreshold,qr,Tr===i))){var E=Ke(hr);do{E-=g,b=fr.children[E]}while(b&&("none"===Qe(b,"display")||b===mr))}if(0===g||b===i)return S(!1);Tr=i,Pr=g;var C=i.nextElementSibling,x=!1,k=an(gr,o,hr,e,i,r,t,x=1===g);if(!1!==k)return 1!==k&&-1!==k||(x=1===k),Qr=!0,setTimeout(sn,30),N(),x&&!C?o.appendChild(hr):i.parentNode.insertBefore(hr,x?C:i),O&&nr(O,0,j-O.scrollTop),fr=hr.parentNode,void 0===v||qr||(Br=Math.abs(v-Xe(i)[_])),M(),S(!0)}if(o.contains(hr))return S(!1)}return!1}function z(s,c){pr(s,h,xe({evt:t,isOwner:u,axis:a?"vertical":"horizontal",revert:n,dragRect:e,targetRect:r,canSort:p,fromSortable:d,target:i,completed:S,onMove:function(r,n){return an(gr,o,hr,e,r,Xe(r),t,n)},changed:M},c))}function N(){z("dragOverAnimationCapture"),h.captureAnimationState(),h!==d&&d.captureAnimationState()}function S(e){return z("dragOverCompleted",{insertion:e}),e&&(u?l._hideClone():l._showClone(h),h!==d&&(Ue(hr,xr?xr.options.ghostClass:l.options.ghostClass,!1),Ue(hr,s.ghostClass,!0)),xr!==h&&h!==nn.active?xr=h:h===nn.active&&xr&&(xr=null),d===h&&(h._ignoreWhileAnimating=i),h.animateAll((function(){z("dragOverAnimationComplete"),h._ignoreWhileAnimating=null})),h!==d&&(d.animateAll(),d._ignoreWhileAnimating=null)),(i===hr&&!hr.animated||i===o&&!i.animated)&&(Tr=null),s.dragoverBubble||t.rootEl||i===document||(hr.parentNode[or]._isOutsideThisEl(t.target),!e&&en(t)),!s.dragoverBubble&&t.stopPropagation&&t.stopPropagation(),f=!0}function M(){Or=Ke(hr),Er=Ke(hr,s.draggable),dr({sortable:h,name:"change",toEl:o,newIndex:Or,newDraggableIndex:Er,originalEvent:t})}},_ignoreWhileAnimating:null,_offMoveEvents:function(){He(document,"mousemove",this._onTouchMove),He(document,"touchmove",this._onTouchMove),He(document,"pointermove",this._onTouchMove),He(document,"dragover",en),He(document,"mousemove",en),He(document,"touchmove",en)},_offUpEvents:function(){var t=this.el.ownerDocument;He(t,"mouseup",this._onDrop),He(t,"touchend",this._onDrop),He(t,"pointerup",this._onDrop),He(t,"touchcancel",this._onDrop),He(document,"selectstart",this)},_onDrop:function(t){var e=this.el,r=this.options;Or=Ke(hr),Er=Ke(hr,r.draggable),pr("drop",this,{evt:t}),fr=hr&&hr.parentNode,Or=Ke(hr),Er=Ke(hr,r.draggable),nn.eventCanceled||(Rr=!1,qr=!1,Vr=!1,clearInterval(this._loopId),clearTimeout(this._dragStartTimer),un(this.cloneId),un(this._dragStartId),this.nativeDraggable&&(He(document,"drop",this),He(e,"dragstart",this._onDragStart)),this._offMoveEvents(),this._offUpEvents(),Ae&&Qe(document.body,"user-select",""),t&&(Dr&&(t.cancelable&&t.preventDefault(),!r.dropBubble&&t.stopPropagation()),mr&&mr.parentNode&&mr.parentNode.removeChild(mr),(gr===fr||xr&&"clone"!==xr.lastPutMode)&&yr&&yr.parentNode&&yr.parentNode.removeChild(yr),hr&&(this.nativeDraggable&&He(hr,"dragend",this),on(hr),hr.style["will-change"]="",Dr&&!Rr&&Ue(hr,xr?xr.options.ghostClass:this.options.ghostClass,!1),Ue(hr,this.options.chosenClass,!1),dr({sortable:this,name:"unchoose",toEl:fr,newIndex:null,newDraggableIndex:null,originalEvent:t}),gr!==fr?(Or>=0&&(dr({rootEl:fr,name:"add",toEl:fr,fromEl:gr,originalEvent:t}),dr({sortable:this,name:"remove",toEl:fr,originalEvent:t}),dr({rootEl:fr,name:"sort",toEl:fr,fromEl:gr,originalEvent:t}),dr({sortable:this,name:"sort",toEl:fr,originalEvent:t})),xr&&xr.save()):Or!==_r&&Or>=0&&(dr({sortable:this,name:"update",toEl:fr,originalEvent:t}),dr({sortable:this,name:"sort",toEl:fr,originalEvent:t})),nn.active&&(null!=Or&&-1!==Or||(Or=_r,Er=jr),dr({sortable:this,name:"end",toEl:fr,originalEvent:t}),this.save())))),this._nulling()},_nulling:function(){pr("nulling",this),gr=hr=fr=mr=vr=yr=br=wr=kr=zr=Dr=Or=Er=_r=jr=Tr=Pr=xr=Cr=nn.dragged=nn.ghost=nn.clone=nn.active=null,Fr.forEach((function(t){t.checked=!0})),Fr.length=Nr=Sr=0},handleEvent:function(t){switch(t.type){case"drop":case"dragend":this._onDrop(t);break;case"dragenter":case"dragover":hr&&(this._onDragOver(t),function(t){t.dataTransfer&&(t.dataTransfer.dropEffect="move");t.cancelable&&t.preventDefault()}(t));break;case"selectstart":t.preventDefault()}},toArray:function(){for(var t,e=[],r=this.el.children,n=0,a=r.length,o=this.options;n<a;n++)Ie(t=r[n],o.draggable,this.el,!1)&&e.push(t.getAttribute(o.dataIdAttr)||cn(t));return e},sort:function(t){var e={},r=this.el;this.toArray().forEach((function(t,n){var a=r.children[n];Ie(a,this.options.draggable,r,!1)&&(e[t]=a)}),this),t.forEach((function(t){e[t]&&(r.removeChild(e[t]),r.appendChild(e[t]))}))},save:function(){var t=this.options.store;t&&t.set&&t.set(this)},closest:function(t,e){return Ie(t,e||this.options.draggable,this.el,!1)},option:function(t,e){var r=this.options;if(void 0===e)return r[t];var n=lr.modifyOption(this,t,e);r[t]=void 0!==n?n:e,"group"===t&&Kr(r)},destroy:function(){pr("destroy",this);var t=this.el;t[or]=null,He(t,"mousedown",this._onTapStart),He(t,"touchstart",this._onTapStart),He(t,"pointerdown",this._onTapStart),this.nativeDraggable&&(He(t,"dragover",this),He(t,"dragenter",this)),Array.prototype.forEach.call(t.querySelectorAll("[draggable]"),(function(t){t.removeAttribute("draggable")})),this._onDrop(),Ir.splice(Ir.indexOf(this.el),1),this.el=t=null},_hideClone:function(){if(!wr){if(pr("hideClone",this),nn.eventCanceled)return;Qe(yr,"display","none"),this.options.removeCloneOnHide&&yr.parentNode&&yr.parentNode.removeChild(yr),wr=!0}},_showClone:function(t){if("clone"===t.lastPutMode){if(wr){if(pr("showClone",this),nn.eventCanceled)return;gr.contains(hr)&&!this.options.group.revertClone?gr.insertBefore(yr,hr):vr?gr.insertBefore(yr,vr):gr.appendChild(yr),this.options.group.revertClone&&this.animate(hr,yr),Qe(yr,"display",""),wr=!1}}else this._hideClone()}},Yr&&Be(document,"touchmove",(function(t){(nn.active||Rr)&&t.cancelable&&t.preventDefault()})),nn.utils={on:Be,off:He,css:Qe,find:Ye,is:function(t,e){return!!Ie(t,e,t,!1)},extend:function(t,e){if(t&&e)for(var r in e)e.hasOwnProperty(r)&&(t[r]=e[r]);return t},throttle:rr,closest:Ie,toggleClass:Ue,clone:ar,index:Ke,nextTick:ln,cancelNextTick:un,detectDirection:Zr,getChild:We},nn.get=function(t){return t[or]},nn.mount=function(){for(var t=arguments.length,e=new Array(t),r=0;r<t;r++)e[r]=arguments[r];e[0].constructor===Array&&(e=e[0]),e.forEach((function(t){if(!t.prototype||!t.prototype.constructor)throw"Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(t));t.utils&&(nn.utils=xe({},nn.utils,t.utils)),lr.mount(t)}))},nn.create=function(t,e){return new nn(t,e)},nn.version="1.10.1";var pn,dn,hn,fn,mn,gn,vn=[],bn=!1;function yn(){vn.forEach((function(t){clearInterval(t.pid)})),vn=[]}function wn(){clearInterval(gn)}var _n=rr((function(t,e,r,n){if(e.scroll){var a,o=(t.touches?t.touches[0]:t).clientX,i=(t.touches?t.touches[0]:t).clientY,s=e.scrollSensitivity,c=e.scrollSpeed,l=Ge(),u=!1;dn!==r&&(dn=r,yn(),pn=e.scroll,a=e.scrollFn,!0===pn&&(pn=tr(r,!0)));var p=0,d=pn;do{var h=d,f=Xe(h),m=f.top,g=f.bottom,v=f.left,b=f.right,y=f.width,w=f.height,_=void 0,O=void 0,j=h.scrollWidth,E=h.scrollHeight,C=Qe(h),x=h.scrollLeft,k=h.scrollTop;h===l?(_=y<j&&("auto"===C.overflowX||"scroll"===C.overflowX||"visible"===C.overflowX),O=w<E&&("auto"===C.overflowY||"scroll"===C.overflowY||"visible"===C.overflowY)):(_=y<j&&("auto"===C.overflowX||"scroll"===C.overflowX),O=w<E&&("auto"===C.overflowY||"scroll"===C.overflowY));var z=_&&(Math.abs(b-o)<=s&&x+y<j)-(Math.abs(v-o)<=s&&!!x),N=O&&(Math.abs(g-i)<=s&&k+w<E)-(Math.abs(m-i)<=s&&!!k);if(!vn[p])for(var S=0;S<=p;S++)vn[S]||(vn[S]={});vn[p].vx==z&&vn[p].vy==N&&vn[p].el===h||(vn[p].el=h,vn[p].vx=z,vn[p].vy=N,clearInterval(vn[p].pid),0==z&&0==N||(u=!0,vn[p].pid=setInterval(function(){n&&0===this.layer&&nn.active._onTouchMove(mn);var e=vn[this.layer].vy?vn[this.layer].vy*c:0,r=vn[this.layer].vx?vn[this.layer].vx*c:0;"function"==typeof a&&"continue"!==a.call(nn.dragged.parentNode[or],r,e,t,mn,vn[this.layer].el)||nr(vn[this.layer].el,r,e)}.bind({layer:p}),24))),p++}while(e.bubbleScroll&&d!==l&&(d=tr(d,!1)));bn=u}}),30),On=function(t){var e=t.originalEvent,r=t.putSortable,n=t.dragEl,a=t.activeSortable,o=t.dispatchSortableEvent,i=t.hideGhostForTarget,s=t.unhideGhostForTarget;if(e){var c=r||a;i();var l=e.changedTouches&&e.changedTouches.length?e.changedTouches[0]:e,u=document.elementFromPoint(l.clientX,l.clientY);s(),c&&!c.el.contains(u)&&(o("spill"),this.onSpill({dragEl:n,putSortable:r}))}};function jn(){}function En(){}jn.prototype={startIndex:null,dragStart:function(t){var e=t.oldDraggableIndex;this.startIndex=e},onSpill:function(t){var e=t.dragEl,r=t.putSortable;this.sortable.captureAnimationState(),r&&r.captureAnimationState();var n=We(this.sortable.el,this.startIndex,this.options);n?this.sortable.el.insertBefore(e,n):this.sortable.el.appendChild(e),this.sortable.animateAll(),r&&r.animateAll()},drop:On},Ce(jn,{pluginName:"revertOnSpill"}),En.prototype={onSpill:function(t){var e=t.dragEl,r=t.putSortable||this.sortable;r.captureAnimationState(),e.parentNode&&e.parentNode.removeChild(e),r.animateAll()},drop:On},Ce(En,{pluginName:"removeOnSpill"});nn.mount(new function(){function t(){for(var t in this.defaults={scroll:!0,scrollSensitivity:30,scrollSpeed:10,bubbleScroll:!0},this)"_"===t.charAt(0)&&"function"==typeof this[t]&&(this[t]=this[t].bind(this))}return t.prototype={dragStarted:function(t){var e=t.originalEvent;this.sortable.nativeDraggable?Be(document,"dragover",this._handleAutoScroll):this.options.supportPointer?Be(document,"pointermove",this._handleFallbackAutoScroll):e.touches?Be(document,"touchmove",this._handleFallbackAutoScroll):Be(document,"mousemove",this._handleFallbackAutoScroll)},dragOverCompleted:function(t){var e=t.originalEvent;this.options.dragOverBubble||e.rootEl||this._handleAutoScroll(e)},drop:function(){this.sortable.nativeDraggable?He(document,"dragover",this._handleAutoScroll):(He(document,"pointermove",this._handleFallbackAutoScroll),He(document,"touchmove",this._handleFallbackAutoScroll),He(document,"mousemove",this._handleFallbackAutoScroll)),wn(),yn(),clearTimeout(Ve),Ve=void 0},nulling:function(){mn=dn=pn=bn=gn=hn=fn=null,vn.length=0},_handleFallbackAutoScroll:function(t){this._handleAutoScroll(t,!0)},_handleAutoScroll:function(t,e){var r=this,n=(t.touches?t.touches[0]:t).clientX,a=(t.touches?t.touches[0]:t).clientY,o=document.elementFromPoint(n,a);if(mn=t,e||Se||Ne||Ae){_n(t,this.options,o,e);var i=tr(o,!0);!bn||gn&&n===hn&&a===fn||(gn&&wn(),gn=setInterval((function(){var o=tr(document.elementFromPoint(n,a),!0);o!==i&&(i=o,yn()),_n(t,r.options,o,e)}),10),hn=n,fn=a)}else{if(!this.options.bubbleScroll||tr(o,!0)===Ge())return void yn();_n(t,this.options,tr(o,!1),!1)}}},Ce(t,{pluginName:"scroll",initializeByDefault:!0})}),nn.mount(En,jn);var Cn=nn,xn=r(17),kn=r.n(xn);var zn=function(t,e){if(!t)throw new Error("Invariant failed")},Nn=function(t,e){return(Nn=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(t,e){t.__proto__=e}||function(t,e){for(var r in e)e.hasOwnProperty(r)&&(t[r]=e[r])})(t,e)};
/*! *****************************************************************************
Copyright (c) Microsoft Corporation. All rights reserved.
Licensed under the Apache License, Version 2.0 (the "License"); you may not use
this file except in compliance with the License. You may obtain a copy of the
License at http://www.apache.org/licenses/LICENSE-2.0

THIS CODE IS PROVIDED ON AN *AS IS* BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
KIND, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED
WARRANTIES OR CONDITIONS OF TITLE, FITNESS FOR A PARTICULAR PURPOSE,
MERCHANTABLITY OR NON-INFRINGEMENT.

See the Apache Version 2.0 License for specific language governing permissions
and limitations under the License.
***************************************************************************** */var Sn=function(){return(Sn=Object.assign||function(t){for(var e,r=1,n=arguments.length;r<n;r++)for(var a in e=arguments[r])Object.prototype.hasOwnProperty.call(e,a)&&(t[a]=e[a]);return t}).apply(this,arguments)};function Mn(t,e){var r="function"==typeof Symbol&&t[Symbol.iterator];if(!r)return t;var n,a,o=r.call(t),i=[];try{for(;(void 0===e||e-- >0)&&!(n=o.next()).done;)i.push(n.value)}catch(t){a={error:t}}finally{try{n&&!n.done&&(r=o.return)&&r.call(o)}finally{if(a)throw a.error}}return i}function An(){for(var t=[],e=0;e<arguments.length;e++)t=t.concat(Mn(arguments[e]));return t}function Dn(t){null!==t.parentElement&&t.parentElement.removeChild(t)}function Tn(t){t.forEach((function(t){return Dn(t.element)}))}function Pn(t){t.forEach((function(t){var e,r,n,a;e=t.parentElement,r=t.element,n=t.oldIndex,a=e.children[n]||null,e.insertBefore(r,a)}))}function Bn(t,e){var r=Ln(t),n={parentElement:t.from},a=[];switch(r){case"normal":a=[{element:t.item,newIndex:t.newIndex,oldIndex:t.oldIndex,parentElement:t.from}];break;case"swap":a=[Sn({element:t.item,oldIndex:t.oldIndex,newIndex:t.newIndex},n),Sn({element:t.swapItem,oldIndex:t.newIndex,newIndex:t.oldIndex},n)];break;case"multidrag":a=t.oldIndicies.map((function(e,r){return Sn({element:e.multiDragElement,oldIndex:e.index,newIndex:t.newIndicies[r].index},n)}))}return function(t,e){return t.map((function(t){return Sn(Sn({},t),{item:e[t.oldIndex]})})).sort((function(t,e){return t.oldIndex-e.oldIndex}))}(a,e)}function Hn(t,e){var r=An(e);return t.concat().reverse().forEach((function(t){return r.splice(t.oldIndex,1)})),r}function Rn(t,e){var r=An(e);return t.forEach((function(t){return r.splice(t.newIndex,0,t.item)})),r}function Ln(t){return t.oldIndicies&&t.oldIndicies.length>0?"multidrag":t.swapItem?"swap":"normal"}function In(t){t.list,t.setList,t.children,t.tag,t.style,t.className,t.clone,t.onAdd,t.onChange,t.onChoose,t.onClone,t.onEnd,t.onFilter,t.onRemove,t.onSort,t.onStart,t.onUnchoose,t.onUpdate,t.onMove,t.onSpill,t.onSelect,t.onDeselect;return function(t,e){var r={};for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&e.indexOf(n)<0&&(r[n]=t[n]);if(null!=t&&"function"==typeof Object.getOwnPropertySymbols){var a=0;for(n=Object.getOwnPropertySymbols(t);a<n.length;a++)e.indexOf(n[a])<0&&Object.prototype.propertyIsEnumerable.call(t,n[a])&&(r[n[a]]=t[n[a]])}return r}(t,["list","setList","children","tag","style","className","clone","onAdd","onChange","onChoose","onClone","onEnd","onFilter","onRemove","onSort","onStart","onUnchoose","onUpdate","onMove","onSpill","onSelect","onDeselect"])}var Vn={dragging:null},qn=function(t){function e(e){var r=t.call(this,e)||this;r.ref=Object(C.createRef)();var n=An(e.list).map((function(t){return Sn(Sn({},t),{chosen:!1,selected:!1})}));return e.setList(n,r.sortable,Vn),zn(!e.plugins,'\nPlugins prop is no longer supported.\nInstead, mount it with "Sortable.mount(new MultiDrag())"\nPlease read the updated README.md at https://github.com/SortableJS/react-sortablejs.\n      '),r}return function(t,e){function r(){this.constructor=t}Nn(t,e),t.prototype=null===e?Object.create(e):(r.prototype=e.prototype,new r)}(e,t),e.prototype.componentDidMount=function(){if(null!==this.ref.current){var t=this.makeOptions();Cn.create(this.ref.current,t)}},e.prototype.render=function(){var t=this.props,e=t.tag,r={style:t.style,className:t.className,id:t.id},n=e&&null!==e?e:"div";return Object(C.createElement)(n,Sn({ref:this.ref},r),this.getChildren())},e.prototype.getChildren=function(){var t=this.props,e=t.children,r=t.dataIdAttr,n=t.selectedClass,a=void 0===n?"sortable-selected":n,o=t.chosenClass,i=void 0===o?"sortable-chosen":o,s=(t.dragClass,t.fallbackClass,t.ghostClass,t.swapClass,t.filter),c=void 0===s?"sortable-filter":s,l=t.list;if(!e||null==e)return null;var u=r||"data-id";return C.Children.map(e,(function(t,e){var r,n,o,s=l[e],p=t.props.className,d="string"==typeof c&&((r={})[c.replace(".","")]=!!s.filtered,r),h=kn()(p,Sn(((n={})[a]=s.selected,n[i]=s.chosen,n),d));return Object(C.cloneElement)(t,((o={})[u]=t.key,o.className=h,o))}))},Object.defineProperty(e.prototype,"sortable",{get:function(){var t=this.ref.current;if(null===t)return null;var e=Object.keys(t).find((function(t){return t.includes("Sortable")}));return e?t[e]:null},enumerable:!0,configurable:!0}),e.prototype.makeOptions=function(){var t=this,e=In(this.props);["onAdd","onChoose","onDeselect","onEnd","onRemove","onSelect","onSpill","onStart","onUnchoose","onUpdate"].forEach((function(r){return e[r]=t.prepareOnHandlerPropAndDOM(r)})),["onChange","onClone","onFilter","onSort"].forEach((function(r){return e[r]=t.prepareOnHandlerProp(r)}));return Sn(Sn({},e),{onMove:function(e,r){var n=t.props.onMove,a=e.willInsertAfter||-1;if(!n)return a;var o=n(e,r,t.sortable,Vn);return void 0!==o&&o}})},e.prototype.prepareOnHandlerPropAndDOM=function(t){var e=this;return function(r){e.callOnHandlerProp(r,t),e[t](r)}},e.prototype.prepareOnHandlerProp=function(t){var e=this;return function(r){e.callOnHandlerProp(r,t)}},e.prototype.callOnHandlerProp=function(t,e){var r=this.props[e];r&&r(t,this.sortable,Vn)},e.prototype.onAdd=function(t){var e=this.props,r=e.list,n=e.setList,a=Bn(t,An(Vn.dragging.props.list));Tn(a),n(Rn(a,r),this.sortable,Vn)},e.prototype.onRemove=function(t){var e=this,r=this.props,n=r.list,a=r.setList,o=Ln(t),i=Bn(t,n);Pn(i);var s=An(n);if("clone"!==t.pullMode)s=Hn(i,s);else{var c=i;switch(o){case"multidrag":c=i.map((function(e,r){return Sn(Sn({},e),{element:t.clones[r]})}));break;case"normal":c=i.map((function(e,r){return Sn(Sn({},e),{element:t.clone})}));break;case"swap":default:zn(!0,'mode "'+o+'" cannot clone. Please remove "props.clone" from <ReactSortable/> when using the "'+o+'" plugin')}Tn(c),i.forEach((function(r){var n=r.oldIndex,a=e.props.clone(r.item,t);s.splice(n,1,a)}))}a(s=s.map((function(t){return Sn(Sn({},t),{selected:!1})})),this.sortable,Vn)},e.prototype.onUpdate=function(t){var e=this.props,r=e.list,n=e.setList,a=Bn(t,r);return Tn(a),Pn(a),n(function(t,e){return Rn(t,Hn(t,e))}(a,r),this.sortable,Vn)},e.prototype.onStart=function(t){Vn.dragging=this},e.prototype.onEnd=function(t){Vn.dragging=null},e.prototype.onChoose=function(t){var e=this.props,r=e.list,n=e.setList,a=An(r);a[t.oldIndex].chosen=!0,n(a,this.sortable,Vn)},e.prototype.onUnchoose=function(t){var e=this.props,r=e.list,n=e.setList,a=An(r);a[t.oldIndex].chosen=!1,n(a,this.sortable,Vn)},e.prototype.onSpill=function(t){var e=this.props,r=e.removeOnSpill,n=e.revertOnSpill;r&&!n&&Dn(t.item)},e.prototype.onSelect=function(t){var e=this.props,r=e.list,n=e.setList,a=An(r).map((function(t){return Sn(Sn({},t),{selected:!1})}));t.newIndicies.forEach((function(e){var r=e.index;if(-1===r)return console.log('"'+t.type+'" had indice of "'+e.index+"\", which is probably -1 and doesn't usually happen here."),void console.log(t);a[r].selected=!0})),n(a,this.sortable,Vn)},e.prototype.onDeselect=function(t){var e=this.props,r=e.list,n=e.setList,a=An(r).map((function(t){return Sn(Sn({},t),{selected:!1})}));t.newIndicies.forEach((function(t){var e=t.index;-1!==e&&(a[e].selected=!0)})),n(a,this.sortable,Vn)},e.defaultProps={clone:function(t){return t}},e}(C.Component),Un=wp.components,Qn=Un.Dashicon,Fn=Un.Button,Yn=function(t){var e=AstraBuilderCustomizerData&&AstraBuilderCustomizerData.choices&&AstraBuilderCustomizerData.choices[t.controlParams.group]?AstraBuilderCustomizerData.choices[t.controlParams.group]:[];return Object(a.createElement)("div",{className:"ahfb-builder-item","data-id":t.item,"data-section":void 0!==e[t.item]&&void 0!==e[t.item].section?e[t.item].section:"",key:t.item,onClick:function(){t.focusItem(void 0!==e[t.item]&&void 0!==e[t.item].section?e[t.item].section:"")}},Object(a.createElement)("span",{className:"ahfb-builder-item-text"},void 0!==e[t.item]&&void 0!==e[t.item].name?e[t.item].name:""),Object(a.createElement)(Fn,{className:"ahfb-builder-item-icon",onClick:function(e){e.stopPropagation(),t.removeItem(t.item)}},Object(a.createElement)(Qn,{icon:"no-alt"})))};function Gn(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function Xn(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?Gn(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):Gn(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Jn=wp.components,Wn=Jn.ButtonGroup,Zn=Jn.Dashicon,Kn=Jn.Popover,$n=Jn.Button,ta=wp.element.Fragment,ea=function(t){var e=Object(C.useState)({isVisible:!1}),r=O()(e,2),n=r[0],o=r[1],i=t.controlParams,s=t.location,c=t.choices,l=(t.row,t.column,t.id),u=function(e,r,n){var s=!0;return i.rows.map((function(r){Object.keys(t.settings[r]).map((function(n){t.settings[r][n].includes(e)&&(s=!1)}))})),Object(a.createElement)(ta,{key:e},s&&Object(a.createElement)($n,{isTertiary:!0,className:"builder-add-btn",onClick:function(){!function(e,r,n){var a=t.setList,i=t.list;o((function(t){return Xn(Xn({},t),{},{isVisible:!1})}));var s=i;s.push({id:e}),a(s)}(e,t.row,t.column)}},Object(a.createElement)("span",{className:"add-btn-icon"}," ",Object(a.createElement)(Zn,{icon:void 0!==c[e]&&void 0!==c[e].icon?c[e].icon:""})," "),Object(a.createElement)("span",{className:"add-btn-title"},void 0!==c[e]&&void 0!==c[e].name?c[e].name:"")))},p=0,d=Object.keys(c).length;return n.isVisible&&i.rows.map((function(e){Object.keys(t.settings[e]).map((function(r){p+=t.settings[e][r].length}))})),Object(a.createElement)("div",{className:kn()("ahfb-builder-add-item","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"right"!==s?null:"center-on-left","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"left"!==s?null:"center-on-right","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"left_center"!==s?null:"right-center-on-right","astra-settings[header-desktop-items]"!==i.group&&"astra-settings[footer-desktop-items]"!==i.group||"right_center"!==s?null:"left-center-on-left"),key:l},n.isVisible&&Object(a.createElement)(Kn,{position:"top",className:"ahfb-popover-add-builder",onClose:function(){!0===n.isVisible&&o((function(t){return Xn(Xn({},t),{},{isVisible:!1})}))}},Object(a.createElement)("div",{className:"ahfb-popover-builder-list"},Object(a.createElement)(Wn,{className:"ahfb-radio-container-control"},Object.keys(c).sort().map((function(t){return u(t)})),d===p&&Object(a.createElement)("p",{className:"ahfb-all-coponents-used"}," ",Object(j.__)("Hurray! All Components Are Being Used.","astra")," ")))),Object(a.createElement)($n,{className:"ahfb-builder-item-add-icon dashicon dashicons-plus-alt2",onClick:function(){o((function(t){return Xn(Xn({},t),{},{isVisible:!0})}))}}))},ra=wp.element.Fragment,na=function(t){var e=t.zone.replace(t.row+"_",""),r=void 0!==t.items&&null!=t.items&&null!=t.items.length&&t.items.length>0?t.items:[],n=t.choices,o=[];r.length>0&&r.map((function(t,e){Object.keys(n).includes(t)?o.push({id:t}):r.splice(e,1)}));var i=void 0!==t.centerItems&&null!=t.centerItems&&null!=t.centerItems.length&&t.centerItems.length>0?t.centerItems:[],s=[];i.length>0&&i.map((function(t,e){Object.keys(n).includes(t)?s.push({id:t}):i.splice(e,1)}));var c=function(r,n,o){var i=o.replace("_","-");return Object(a.createElement)(ra,null,Object(a.createElement)(qn,{animation:100,onStart:function(){return t.showDrop()},onEnd:function(){return t.hideDrop()},group:t.controlParams.group,className:"ahfb-builder-drop ahfb-builder-sortable-panel ahfb-builder-drop-"+e+o,list:r,setList:function(e){return t.onUpdate(t.row,t.zone+o,e)}},n.length>0&&n.map((function(e,r){return Object(a.createElement)(Yn,{removeItem:function(e){return t.removeItem(e,t.row,t.zone+o)},focusItem:function(e){return t.focusItem(e)},key:e,index:r,item:e,controlParams:t.controlParams})}))),Object(a.createElement)(ea,{row:t.row,list:r,settings:t.settings,column:t.zone+o,setList:function(e){return t.onAddItem(t.row,t.zone+o,e)},key:e,location:e+o,id:"add"+i+"-"+e,controlParams:t.controlParams,choices:t.choices}))};return"footer"===t.mode?Object(a.createElement)("div",{className:"ahfb-builder-area ahfb-builder-area-".concat(e),"data-location":t.zone},c(o,r,"")):Object(a.createElement)("div",{className:"ahfb-builder-area ahfb-builder-area-".concat(e),"data-location":t.zone},"astra-settings[header-desktop-items]"===t.controlParams.group&&"right"===e&&c(s,i,"_center"),c(o,r,""),"astra-settings[header-desktop-items]"===t.controlParams.group&&"left"===e&&c(s,i,"_center"))},aa=wp.i18n.__,oa=wp.components,ia=oa.Dashicon,sa=oa.Button,ca=function(t){var e="no-center-items",r=-1!==t.controlParams.group.indexOf("header")?"header":"footer",n=[],o="",i=0,s=!0;if("footer"===r&&(o="ast-grid-row-layout-".concat(t.layout[t.row].layout.desktop),i=t.layout[t.row].column-1,Object.keys(t.controlParams.zones[t.row]).map((function(e,r){i<r&&(t.items[e]=[])}))),"astra-settings[header-desktop-items]"===t.controlParams.group&&void 0!==t.items[t.row+"_center"]&&null!=t.items[t.row+"_center"]&&null!=t.items[t.row+"_center"].length&&t.items[t.row+"_center"].length>0&&(e="has-center-items"),"popup"===t.row&&(e="popup-vertical-group"),t.controlParams.hasOwnProperty("status"))switch(t.row){case"above":t.controlParams.status.above||(s=!1,"ahfb-grid-disabled");break;case"primary":t.controlParams.status.primary||(s=!1,"ahfb-grid-disabled");break;case"below":t.controlParams.status.below||(s=!1,"ahfb-grid-disabled")}return Object(a.createElement)("div",{className:"ahfb-builder-areas ahfb-builder-mode-".concat(r," ").concat(e),"data-row":t.row,"data-row-section":"section-"+t.row+"-"+r+"-builder"},Object(a.createElement)(sa,{className:"ahfb-row-actions",title:"popup"===t.row?aa("Off Canvas","astra"):(t.row+" "+r).charAt(0).toUpperCase()+(t.row+" "+r).slice(1).toLowerCase(),onClick:function(){return t.focusPanel(t.row+"-"+r)}},Object(a.createElement)(ia,{icon:"admin-generic"}),"popup"===t.row&&Object(a.createElement)(a.Fragment,null,aa("Off Canvas","astra"))),Object(a.createElement)("div",{className:"ahfb-builder-group ahfb-builder-group-horizontal ".concat(o),"data-setting":t.row},Object.keys(t.controlParams.zones[t.row]).map((function(e,o){if(!("footer"===r&&i<o)&&(t.row+"_left_center"!==e&&t.row+"_right_center"!==e||"footer"===r))return"astra-settings[header-desktop-items]"===t.controlParams.group&&t.row+"_left"===e&&"footer"!==r&&(n=t.items[t.row+"_left_center"]),"astra-settings[header-desktop-items]"===t.controlParams.group&&t.row+"_right"===e&&"footer"!==r&&(n=t.items[t.row+"_right_center"]),s&&Object(a.createElement)(na,{removeItem:function(e,r,n){return t.removeItem(e,r,n)},focusItem:function(e){return t.focusItem(e)},hideDrop:function(){return t.hideDrop()},showDrop:function(){return t.showDrop()},onUpdate:function(e,r,n){return t.onUpdate(e,r,n)},zone:e,row:t.row,choices:t.choices,key:e,items:t.items[e],centerItems:n,controlParams:t.controlParams,onAddItem:function(e,r,n){return t.onAddItem(e,r,n)},settings:t.settings,mode:r})}))))};function la(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function ua(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?la(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):la(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var pa=function(t){var e=t.control.setting.get(),r={},n=t.control.params.default?ua(ua({},r),t.control.params.default):r;e=e?ua(ua({},n),e):n;var o={},i=t.control.params.input_attrs?ua(ua({},o),t.control.params.input_attrs):o,s=AstraBuilderCustomizerData&&AstraBuilderCustomizerData.choices&&AstraBuilderCustomizerData.choices[i.group]?AstraBuilderCustomizerData.choices[i.group]:[],c=Object(C.useState)({value:e,layout:i.layouts}),l=O()(c,2),u=l[0],p=l[1],d=function(e){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",n=t.control.setting;if("popup"===r){var a=t.customizer("astra-settings[header-mobile-popup-items]");a.set(!a.get())}n.set(ua(ua(ua({},n.get()),e),{},{flag:!n.get().flag}))};Object(C.useEffect)((function(){document.addEventListener("AstraBuilderPresetSettingsUpdate",(function(t){i.group===t.detail.id&&(p((function(e){return ua(ua({},e),{},{value:t.detail.grid_layout})})),d(t.detail.grid_layout))})),document.addEventListener("AstraBuilderChangeRowLayout",(function(t){if("astra-settings[footer-desktop-items]"===i.group&&""!==t.detail.type){var e=i;e.layouts[t.detail.type]&&(e.layouts[t.detail.type]={column:t.detail.columns,layout:t.detail.layout},p((function(t){return ua(ua({},t),{},{layout:e.layouts})})),d(e))}}))}),[]);var h=function(){for(var t=document.querySelectorAll(".ahfb-builder-area"),e=0;e<t.length;++e)t[e].classList.add("ahfb-dragging-dropzones")},f=function(){for(var t=document.querySelectorAll(".ahfb-builder-area"),e=0;e<t.length;++e)t[e].classList.remove("ahfb-dragging-dropzones")},m=function(t,e,r){var n=u.value,a=n[e],o=[];a[r].length>0&&a[r].map((function(e){t!==e&&o.push(e)})),"astra-settings[header-desktop-items]"===i.group&&e+"_center"===r&&0===o.length&&(a[e+"_left_center"].length>0&&(a[e+"_left_center"].map((function(t){n[e][e+"_left"].push(t)})),n[e][e+"_left_center"]=[]),a[e+"_right_center"].length>0&&(a[e+"_right_center"].map((function(t){n[e][e+"_right"].push(t)})),n[e][e+"_right_center"]=[])),a[r]=o,n[e][r]=o,p((function(t){return ua(ua({},t),{},{value:n})})),d(n,e);var s=new CustomEvent("AstraBuilderRemovedBuilderItem",{detail:i.group});document.dispatchEvent(s)},g=function(t,e,r){var n=u.value,a=n[t],o=[];r.length>0&&r.map((function(t){o.push(t.id)})),b(a[e],o)||("astra-settings[header-desktop-items]"===i.group&&t+"_center"===e&&0===o.length&&(a[t+"_left_center"].length>0&&(a[t+"_left_center"].map((function(e){n[t][t+"_left"].push(e)})),n[t][t+"_left_center"]=[]),a[t+"_right_center"].length>0&&(a[t+"_right_center"].map((function(e){n[t][t+"_right"].push(e)})),n[t][t+"_right_center"]=[])),a[e]=o,n[t][e]=o,p((function(t){return ua(ua({},t),{},{value:n})})),d(n,t))},v=function(t,e,r){g(t,e,r);var n=new CustomEvent("AstraBuilderRemovedBuilderItem",{detail:i.group});document.dispatchEvent(n)},b=function(t,e){if(t===e)return!0;if(null==t||null==e)return!1;if(t.length!=e.length)return!1;for(var r=0;r<t.length;++r)if(t[r]!==e[r])return!1;return!0},y=function(e){e="section-"+e+"-builder",void 0!==t.customizer.section(e)&&t.customizer.section(e).focus()},w=function(e){void 0!==t.customizer.section(e)&&t.customizer.section(e).focus()};return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-builder-items"},i.rows.includes("popup")&&Object(a.createElement)(ca,{showDrop:function(){return h()},focusPanel:function(t){return y(t)},focusItem:function(t){return w(t)},removeItem:function(t,e,r){return m(t,e,r)},onAddItem:function(t,e,r){return v(t,e,r)},hideDrop:function(){return f()},onUpdate:function(t,e,r){return g(t,e,r)},key:"popup",row:"popup",controlParams:i,choices:s,items:u.value.popup,settings:u.value,layout:u.layout}),Object(a.createElement)("div",{className:"ahfb-builder-row-items"},i.rows.map((function(e){if("popup"!==e)return Object(a.createElement)(ca,{showDrop:function(){return h()},focusPanel:function(t){return y(t)},focusItem:function(t){return w(t)},removeItem:function(t,e,r){return m(t,e,r)},hideDrop:function(){return f()},onUpdate:function(t,e,r){return g(t,e,r)},onAddItem:function(t,e,r){return v(t,e,r)},key:e,row:e,controlParams:i,choices:s,customizer:t.customizer,items:u.value[e],settings:u.value,layout:u.layout})}))))};pa.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var da=React.memo(pa),ha=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(da,{control:this,customizer:wp.customize}),this.container[0])}});function fa(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function ma(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?fa(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):fa(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var ga=wp.i18n.__,va=wp.components,ba=va.Dashicon,ya=va.Tooltip,wa=va.TextControl,_a=va.Button,Oa=function(t){var e=window.svgIcons,r=Object(C.useState)({open:!1}),n=O()(r,2),o=n[0],i=n[1];return Object(a.createElement)("div",{className:"ahfb-sorter-item","data-id":t.item.id,key:t.item.id},Object(a.createElement)("div",{className:"ahfb-sorter-item-panel-header",onClick:function(){i((function(t){return ma(ma({},t),{},{open:!o.open})}))}},Object(a.createElement)(ya,{text:ga("Toggle Item Visiblity","astra")},Object(a.createElement)(_a,{className:"ahfb-sorter-visiblity"},Object(a.createElement)("span",{dangerouslySetInnerHTML:{__html:e[t.item.id]}}))),Object(a.createElement)("span",{className:"ahfb-sorter-title"},void 0!==t.item.label&&""!==t.item.label?t.item.label:ga("Social Item","astra")),Object(a.createElement)(_a,{className:"ahfb-sorter-item-expand ".concat(t.item.enabled?"item-is-visible":"item-is-hidden"),onClick:function(e){e.stopPropagation(),t.toggleEnabled(!t.item.enabled,t.index)}},Object(a.createElement)(ba,{icon:"visibility"})),Object(a.createElement)(_a,{className:"ahfb-sorter-item-remove",isDestructive:!0,onClick:function(){t.removeItem(t.index)}},Object(a.createElement)(ba,{icon:"no-alt"}))),o.open&&Object(a.createElement)("div",{className:"ahfb-sorter-item-panel-content"},Object(a.createElement)(wa,{label:ga("Label","astra"),value:t.item.label?t.item.label:"",onChange:function(e){t.onChangeLabel(e,t.index)}}),Object(a.createElement)(wa,{label:ga("URL","astra"),value:t.item.url?t.item.url:"",onChange:function(e){t.onChangeURL(e,t.index)}})))};function ja(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function Ea(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?ja(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):ja(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Ca=wp.i18n.__,xa=wp.components,ka=xa.Button,za=xa.SelectControl,Na=function(t){var e=t.control.setting.get(),r={items:[{id:"facebook",enabled:!0,url:"",color:"#557dbc",background:"transparent",icon:"facebook",label:"Facebook"},{id:"twitter",enabled:!0,url:"",color:"#7acdee",background:"transparent",icon:"twitter",label:"Twitter"}]},n=t.control.params.default?Ea(Ea({},r),t.control.params.default):r;e=e?Ea(Ea({},n),e):n;var o={group:"social_item_group",options:[{value:"facebook",label:Ca("Facebook","astra"),color:"#557dbc",background:"transparent"},{value:"twitter",label:Ca("Twitter","astra"),color:"#7acdee",background:"transparent"},{value:"instagram",label:Ca("Instagram","astra"),color:"#8a3ab9",background:"transparent"},{value:"youtube",label:Ca("YouTube","astra"),color:"#e96651",background:"transparent"},{value:"facebook_group",label:Ca("Facebook Group","astra"),color:"#3D87FB",background:"transparent"},{value:"vimeo",label:Ca("Vimeo","astra"),color:"#8ecfde",background:"transparent"},{value:"pinterest",label:Ca("Pinterest","astra"),color:"#ea575a",background:"transparent"},{value:"linkedin",label:Ca("Linkedin","astra"),color:"#1c86c6",background:"transparent"},{value:"medium",label:Ca("Medium","astra"),color:"#292929",background:"transparent"},{value:"wordpress",label:Ca("WordPress","astra"),color:"#464646",background:"transparent"},{value:"reddit",label:Ca("Reddit","astra"),color:"#FC471E",background:"transparent"},{value:"patreon",label:Ca("Patreon","astra"),color:"#e65c4b",background:"transparent"},{value:"github",label:Ca("GitHub","astra"),color:"#24292e",background:"transparent"},{value:"dribbble",label:Ca("Dribbble","astra"),color:"#d77ea6",background:"transparent"},{value:"behance",label:Ca("Behance","astra"),color:"#1B64F6",background:"transparent"},{value:"vk",label:Ca("VK","astra"),color:"#5382b6",background:"transparent"},{value:"xing",label:Ca("Xing","astra"),color:"#0A5C5D",background:"transparent"},{value:"rss",label:Ca("RSS","astra"),color:"#f09124",background:"transparent"},{value:"email",label:Ca("Email","astra"),color:"#ea4335",background:"transparent"},{value:"phone",label:Ca("Phone","astra"),color:"inherit",background:"transparent"},{value:"whatsapp",label:Ca("WhatsApp","astra"),color:"#5BBA67",background:"transparent"},{value:"google_reviews",label:Ca("Google Reviews","astra"),color:"#dc4e41",background:"transparent"},{value:"telegram",label:Ca("Telegram","astra"),color:"#229CCE",background:"transparent"},{value:"yelp",label:Ca("Yelp","astra"),color:"#af0606",background:"transparent"},{value:"trip_advisor",label:Ca("Trip Advisor","astra"),color:"#00aa6c",background:"transparent"},{value:"imdb",label:Ca("IMDB","astra"),color:"#000000",background:"transparent"}].sort((function(t,e){return t.value<e.value?-1:t.value>e.value?1:0}))},i=t.control.params.input_attrs?Ea(Ea({},o),t.control.params.input_attrs):o,s=[];i.options.map((function(t){e.items.some((function(e){return e.id===t.value}))||s.push(t)}));var c=Object(C.useState)({value:e,isVisible:!1,control:void 0!==s[0]&&void 0!==s[0].value?s[0].value:""}),l=O()(c,2),u=l[0],p=l[1],d=function(e){t.control.setting.set(Ea(Ea(Ea({},t.control.setting.get()),e),{},{flag:!t.control.setting.get().flag}))},h=function(){var t,e=document.querySelectorAll(".ahfb-builder-area");for(t=0;t<e.length;++t)e[t].classList.remove("ahfb-dragging-dropzones")},f=function(t,e){var r=u.value,n=r.items.map((function(r,n){return e===n&&(r=Ea(Ea({},r),t)),r}));r.items=n,p((function(t){return Ea(Ea({},t),{},{value:r})})),d(r)},m=function(t,e){if(t===e)return!0;if(null==t||null==e)return!1;if(t.length!=e.length)return!1;for(var r=0;r<t.length;++r)if(t[r]!==e[r])return!1;return!0},g=void 0!==u.value&&null!=u.value.items&&null!=u.value.items.length&&u.value.items.length>0?u.value.items:[],v=[];g.length>0&&g.map((function(t){v.push({id:t.id})})),i.options.map((function(t){v.some((function(e){return e.id===t.value}))||s.push(t)}));return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-sorter-items"},Object(a.createElement)("div",{className:"ahfb-sorter-row"},Object(a.createElement)(qn,{animation:100,onStart:function(){return h()},onEnd:function(){return h()},group:i.group,className:"ahfb-sorter-drop ahfb-sorter-sortable-panel ahfb-sorter-drop-".concat(i.group),handle:".ahfb-sorter-item-panel-header",list:v,setList:function(t){return e=t,r=u.value,n=r.items,a=[],e.length>0&&e.map((function(t){n.filter((function(e){e.id===t.id&&a.push(e)}))})),void(m(n,a)||(n.items=a,r.items=a,p((function(t){return Ea(Ea({},t),{},{value:r})})),d(r)));var e,r,n,a}},g.length>0&&g.map((function(t,e){return Object(a.createElement)(Oa,{removeItem:function(t){return e=t,r=u.value,n=r.items,a=[],n.length>0&&n.map((function(t,r){e!==r&&a.push(t)})),r.items=a,p((function(t){return Ea(Ea({},t),{},{value:r})})),void d(r);var e,r,n,a},toggleEnabled:function(t,e){return function(t,e){f({enabled:t},e)}(t,e)},onChangeLabel:function(t,e){return function(t,e){f({label:t},e)}(t,e)},onChangeURL:function(t,e){return function(t,e){f({url:t},e)}(t,e)},key:t.id,index:e,item:t,controlParams:i})})))),void 0!==s[0]&&void 0!==s[0].value&&Object(a.createElement)("div",{className:"ahfb-social-add-area"},Object(a.createElement)(za,{value:u.control,options:s,onChange:function(t){p((function(e){return Ea(Ea({},e),{},{control:t})}))}}),Object(a.createElement)(ka,{className:"ahfb-sorter-add-item",isPrimary:!0,onClick:function(){!function(){var t=u.control;if(p((function(t){return Ea(Ea({},t),{},{isVisible:!1})})),t){var e=u.value,r=e.items,n=i.options.filter((function(e){return e.value===t})),a={id:t,enabled:!0,url:"",color:n[0].color,background:n[0].background,icon:t,label:n[0].label};r.push(a),e.items=r;var o=[];i.options.map((function(t){r.some((function(e){return e.id===t.value}))||o.push(t)})),p((function(t){return Ea(Ea({},t),{},{control:void 0!==o[0]&&void 0!==o[0].value?o[0].value:""})})),p((function(t){return Ea(Ea({},t),{},{value:e})})),d(e)}}()}},Ca("Add Social Icon","astra"))))};Na.propTypes={control:i.a.object.isRequired};var Sa=React.memo(Na),Ma=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Sa,{control:this}),this.container[0])}}),Aa=r(44),Da=r.n(Aa);function Ta(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function Pa(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?Ta(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):Ta(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Ba=function(t){var e=t.control.setting.get(),r=Object(C.useState)({value:e,editor:{},restoreTextMode:!1}),n=O()(r,2),o=n[0],i=n[1],s={id:"header_html",toolbar1:"formatselect | styleselect | bold italic strikethrough | forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | insert ast_placeholders | fontsizeselect",toolbar2:""},c=t.control.params.input_attrs?Pa(Pa({},s),t.control.params.input_attrs):s,l=function(e){i((function(t){return Pa(Pa({},t),{},{value:e})})),t.control.setting.set(e)};Object(C.useEffect)((function(){window.tinymce.get(c.id)&&(i((function(t){return Pa(Pa({},t),{},{restoreTextMode:window.tinymce.get(c.id).isHidden()})})),window.wp.oldEditor.remove(c.id)),window.wp.oldEditor.initialize(c.id,{tinymce:{wpautop:!0,height:200,menubar:!1,toolbar1:c.toolbar1,toolbar2:c.toolbar2,fontsize_formats:"8pt 9pt 10pt 11pt 12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt"},quicktags:!0,mediaButtons:!0});var t=window.tinymce.get(c.id);t.initialized?u():t.on("init",u),t.addButton("ast_placeholders",{type:"menubutton",text:"Tags",icon:!1,menu:[{text:"Copyright",icon:!1,value:"[copyright]",onclick:function(){t.insertContent(this.value())}},{text:"Current Year",icon:!1,value:"[current_year]",onclick:function(){t.insertContent(this.value())}},{text:"Site Title",icon:!1,value:"[site_title]",onclick:function(){t.insertContent(this.value())}},{text:"Theme Author",icon:!1,value:"[theme_author]",onclick:function(){t.insertContent(this.value())}}]})}),[]);var u=function(){var t=window.tinymce.get(c.id);o.restoreTextMode&&window.switchEditors.go(c.id,"html"),t.on("NodeChange",Da()(p,250)),i((function(e){return Pa(Pa({},e),{},{editor:t})}))},p=function(){l(window.wp.oldEditor.getContent(c.id))};return Object(a.createElement)("div",{className:"ahfb-control-field ast-html-editor"},t.control.params.label&&Object(a.createElement)("span",{className:"customize-control-title"},t.control.params.label),Object(a.createElement)("textarea",{className:"ahfb-control-tinymce-editor wp-editor-area",id:c.id,value:o.value,onChange:function(t){var e=t.target.value;l(e)}}),t.control.params.description&&Object(a.createElement)("span",{className:"customize-control-description"},t.control.params.description))};Ba.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var Ha=React.memo(Ba),Ra=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Ha,{control:this,customizer:wp.customize}),this.container[0])}});function La(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function Ia(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?La(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):La(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Va=wp.components,qa=Va.Dashicon,Ua=Va.Button,Qa=wp.element.Fragment,Fa=function(t){var e=window.svgIcons,r={},n={},o=t.control.params.input_attrs?Ia(Ia({},n),t.control.params.input_attrs):n;t.customizer.control(o.group)&&(r=t.customizer.control(o.group).setting.get());var i=AstraBuilderCustomizerData&&AstraBuilderCustomizerData.choices&&AstraBuilderCustomizerData.choices[o.group]?AstraBuilderCustomizerData.choices[o.group]:[],s=Object(C.useState)({settings:r}),c=O()(s,2),l=c[0],u=c[1];document.addEventListener("AstraBuilderRemovedBuilderItem",(function(t){t.detail===o.group&&p()}));var p=function(){if(t.customizer.control(o.group)){var e=t.customizer.control(o.group).setting.get();u((function(t){return Ia(Ia({},t),{},{settings:e})}))}},d=function(r,n){var s=!0;o.zones.map((function(t){Object.keys(l.settings[t]).map((function(e){l.settings[t][e].includes(r)&&(s=!1)}))}));var c=[{id:r}];return Object(a.createElement)(Qa,{key:r},s&&"available"===n&&Object(a.createElement)(qn,{animation:10,onStart:function(){return function(){var t,e=document.querySelectorAll(".ahfb-builder-area");for(t=0;t<e.length;++t)e[t].classList.add("ahfb-dragging-dropzones")}()},onEnd:function(){return function(){var t,e=document.querySelectorAll(".ahfb-builder-area");for(t=0;t<e.length;++t)e[t].classList.remove("ahfb-dragging-dropzones")}()},group:{name:o.group,put:!1},className:"ahfb-builder-item-start ahfb-move-item",list:c,setList:function(t){var e;null!=(e=t).length&&0===e.length&&p()}},Object(a.createElement)("div",{className:"ahfb-builder-item","data-id":r,"data-section":i[r]&&i[r].section?i[r].section:"",key:r},Object(a.createElement)("span",{className:"ahfb-builder-item-icon ahfb-move-icon"},"dangerouslySetInnerHTML=",{__html:e.drag}),i[r]&&i[r].name?i[r].name:"")),!s&&"links"===n&&Object(a.createElement)("div",{className:"ahfb-builder-item-start"},Object(a.createElement)(Ua,{className:"ahfb-builder-item","data-id":r,onClick:function(){return function(e){t.customizer.section(i[e].section)&&t.customizer.section(i[e].section).focus()}(r)},"data-section":i[r]&&i[r].section?i[r].section:"",key:r},i[r]&&i[r].name?i[r].name:"",Object(a.createElement)("span",{className:"ahfb-builder-item-icon"},Object(a.createElement)(qa,{icon:"arrow-right-alt2"})))))};return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-available-items"},Object(a.createElement)("div",{className:"ahfb-available-items-pool-"},Object.keys(i).map((function(t){return d(t,"links")}))))};Fa.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var Ya=React.memo(Fa),Ga=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(Ya,{control:this,customizer:wp.customize}),this.container[0])}});function Xa(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function Ja(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?Xa(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):Xa(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}var Wa=wp.components,Za=Wa.Dashicon,Ka=Wa.Button,$a=function(t){var e={section:""},r=t.control.params.input_attrs?Ja(Ja({},e),t.control.params.input_attrs):e;return Object(a.createElement)("div",{className:"ahfb-control-field ahfb-available-items"},Object(a.createElement)("div",{className:"ahfb-builder-item-start"},Object(a.createElement)(Ka,{className:"ahfb-builder-item",onClick:function(){return e=r.section,void(void 0!==t.customizer.section(e)&&t.customizer.section(e).focus());var e},"data-section":r.section},r.label?r.label:"",Object(a.createElement)("span",{className:"ahfb-builder-item-icon"},Object(a.createElement)(Za,{icon:"arrow-right-alt2"})))))};$a.propTypes={control:i.a.object.isRequired,customizer:i.a.func.isRequired};var to=React.memo($a),eo=wp.customize.astraControl.extend({renderContent:function(){ReactDOM.render(Object(a.createElement)(to,{control:this,customizer:wp.customize}),this.container[0])}});function ro(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function no(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?ro(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):ro(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}wp.i18n.__;var ao=wp.element.Fragment,oo=function(t){var e=Object(C.useState)({view:t.device}),r=O()(e,2),n=r[0],o=r[1],i=n.view,s=function(){document.addEventListener("AstraChangedRepsonsivePreview",(function(e){!function(e){var r="";switch(e){case"desktop":r="tablet";break;case"tablet":r="mobile";break;case"mobile":r="desktop"}o((function(t){return no(no({},t),{},{view:r})})),wp.customize.previewedDevice(r),t.onChange(r)}(e.detail)}))};return Object(C.useEffect)((function(){s()}),[]),Object(a.createElement)(ao,null,Object(a.createElement)("div",{className:"ahfb-responsive-control-bar"},t.controlLabel&&Object(a.createElement)("span",{className:"customize-control-title"},t.controlLabel),!t.hideResponsive&&Object(a.createElement)("div",{className:"floating-controls"},Object(a.createElement)("ul",{key:"ast-resp-ul",className:"ast-responsive-btns"},Object.keys({desktop:{icon:"desktop"},tablet:{icon:"tablet"},mobile:{icon:"smartphone"}}).map((function(t){return Object(a.createElement)("li",y()({key:t,className:t},"className",(t===i?"active ":"")+"preview-".concat(t)),Object(a.createElement)("button",{type:"button","data-device":t,className:(t===i?"active ":"")+"preview-".concat(t),onClick:function(){var e=new CustomEvent("AstraChangedRepsonsivePreview",{detail:t});document.dispatchEvent(e)}},Object(a.createElement)("i",{className:"dashicons dashicons-".concat("mobile"===t?"smartphone":t)})))}))))),Object(a.createElement)("div",{className:"ahfb-responsive-controls-content"},t.children))};oo.propTypes={onChange:i.a.func,controlLabel:i.a.object};var io=React.memo(oo);function so(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function co(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?so(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):so(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function lo(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var r,n=ft()(t);if(e){var a=ft()(this).constructor;r=Reflect.construct(n,arguments,a)}else r=n.apply(this,arguments);return dt()(this,r)}}wp.i18n.__;var uo=wp.components,po=uo.ButtonGroup,ho=uo.Dashicon,fo=uo.Button,mo=function(t){ut()(r,t);var e=lo(r);function r(){var t;at()(this,r),(t=e.apply(this,arguments)).updateValues=t.updateValues.bind(ct()(t)),t.onFooterUpdate=t.onFooterUpdate.bind(ct()(t)),t.onColumnUpdate();var n=t.props.control.setting.get(),a=t.props.control.params.input_attrs.layout;t.controlParams=t.props.control.params.input_attrs?co(co({},a),t.props.control.params.input_attrs):a;var o,i={mobile:"row",tablet:"",desktop:"equal"},s="equal",c=t.props.control.id.replace("astra-settings[","").replace("-footer-layout]","");t.type=c,t.footer_type="hb"===t.type?"primary":"hba"===t.type?"above":"below",t.controlParams.responsive?(o=i,t.defaultValue=t.props.control.params.default?co(co({},o),t.props.control.params.default):o):(o=s,t.defaultValue=t.props.control.params.default?t.props.control.params.default:o),n=t.controlParams.responsive?n?co(co({},JSON.parse(JSON.stringify(t.defaultValue))),n):JSON.parse(JSON.stringify(t.defaultValue)):n||t.defaultValue;var l=0;return l=parseInt(t.props.customizer.control("astra-settings["+t.type+"-footer-column]").setting.get(),10),t.state={currentDevice:"desktop",columns:l,value:n,is_updated:!1},t}return it()(r,[{key:"render",value:function(){var t=this,e=window.svgIcons,r=Object(a.createElement)(a.Fragment,null,this.props.control.params.label&&this.props.control.params.label),n={};return n="desktop"!==this.state.currentDevice?this.controlParams.mobile[this.state.columns]:this.controlParams.desktop[this.state.columns],Object(a.createElement)("div",{className:"ahfb-control-field ahfb-radio-icon-control ahfb-row-layout-control"},this.controlParams.responsive&&Object(a.createElement)(io,{onChange:function(e){return t.setState({currentDevice:e})},controlLabel:r,device:this.props.device},Object(a.createElement)(po,{className:"ahfb-radio-container-control"},Object.keys(n).map((function(r,o){return Object(a.createElement)(fo,{key:o,isTertiary:!0,className:(r===t.state.value[t.state.currentDevice]?"active-radio ":"")+"ast-radio-img-svg ahfb-btn-item-"+o,onClick:function(){var e=t.state.value;e[t.state.currentDevice]=r,t.setState({value:e}),t.updateValues()}},n[r].icon&&Object(a.createElement)("span",{className:"ahfb-radio-icon",dangerouslySetInnerHTML:{__html:e[n[r].icon]}}),n[r].dashicon&&Object(a.createElement)("span",{className:"ahfb-radio-icon ahfb-radio-dashicon"},Object(a.createElement)(ho,{icon:n[r].dashicon})),n[r].name&&n[r].name)})))))}},{key:"onFooterUpdate",value:function(){var t=parseInt(this.props.customizer.control("astra-settings["+this.type+"-footer-column]").setting.get(),10),e=this.state.value;if(this.state.columns!==t){this.setState({columns:t});var r={1:"full",2:"2-equal",3:"3-equal",4:"4-equal",5:"5-equal",6:"6-equal"};e.desktop=r[t],e.tablet=r[t],e.mobile="full",this.setState({value:e}),this.updateValues()}}},{key:"onColumnUpdate",value:function(){var t=this;document.addEventListener("AstraBuilderChangeRowLayout",(function(e){e.detail.columns&&t.onFooterUpdate()}))}},{key:"updateValues",value:function(){var t=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:wp.customize.value("astra-settings["+this.type+"-footer-column]").get(),layout:this.state.value,type:this.footer_type}}),e=this.state.value;document.dispatchEvent(t),this.props.control.setting.set(co(co(co({},this.props.control.setting.get()),e),{},{flag:!this.props.control.setting.get().flag}))}}]),r}(wp.element.Component);mo.propTypes={control:i.a.object.isRequired};var go,vo,bo,yo,wo,_o,Oo,jo,Eo,Co,xo,ko=mo,zo=wp.customize.astraControl.extend({renderContent:function(){var t=jQuery(".wp-full-overlay-footer .devices button.active").attr("data-device");ReactDOM.render(Object(a.createElement)(ko,{control:this,customizer:wp.customize,device:t}),this.container[0])},ready:function(){jQuery(".wp-full-overlay-footer .devices button").on("click",(function(){var t="";switch(jQuery(this).attr("data-device")){case"desktop":t="mobile";break;case"tablet":t="desktop";break;case"mobile":t="tablet"}jQuery(".customize-control-ast-row-layout .ahfb-responsive-control-bar .ast-responsive-btns button.preview-"+t).trigger("click")}))}});function No(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function So(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?No(Object(r),!0).forEach((function(e){y()(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):No(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}go=jQuery,vo=wp.customize,bo=go(window),yo=go("body"),wo=[],_o="",Oo=function(){var t=go(".control-section.ahfb-header-builder-active"),e=go(".control-section.ahfb-footer-builder-active"),r=go("#available-widgets");r.css("bottom","289px"),yo.hasClass("ahfb-header-builder-is-active")||yo.hasClass("ahfb-footer-builder-is-active")?yo.hasClass("ahfb-footer-builder-is-active")&&0<e.length&&!e.hasClass("ahfb-builder-hide")?vo.previewer.container.css("bottom",e.outerHeight()+"px"):yo.hasClass("ahfb-header-builder-is-active")&&0<t.length&&!t.hasClass("ahfb-builder-hide")?(r.css("bottom","289px"),vo.previewer.container.css({bottom:t.outerHeight()+"px"})):(r.css("bottom","46px"),vo.previewer.container.css("bottom","")):vo.previewer.container.css("bottom",""),t.css("overflow","visible"),e.css("overflow","visible")},jo=function(t){var e=t.id.includes("-header-")?"header":"footer",r=vo.section("section-"+e+"-builder");if(r){var n=r.contentContainer,a=vo.section("section-"+e+"-builder-layout");t.expanded.bind((function(o){Eo.setControlContextBySection(r),Eo.setControlContextBySection(a),_.each(r.controls(),(function(t){"resolved"!==t.deferred.embedded.state()&&(t.renderContent(),t.deferred.embedded.resolve(),t.container.trigger("init"))})),_.each(a.controls(),(function(t){"resolved"!==t.deferred.embedded.state()&&(t.renderContent(),t.deferred.embedded.resolve(),t.container.trigger("init"))})),o?(_o=t.id,yo.addClass("ahfb-"+e+"-builder-is-active"),n.addClass("ahfb-"+e+"-builder-active"),go("#sub-accordion-panel-"+_o+" li.control-section").hide(),"header"===e?go("#sub-accordion-section-section-footer-builder").css("overflow","hidden"):go("#sub-accordion-section-section-header-builder").css("overflow","hidden")):(go("#sub-accordion-section-section-footer-builder").css("overflow","hidden"),go("#sub-accordion-section-section-header-builder").css("overflow","hidden"),vo.state("astra-customizer-tab").set("general"),yo.removeClass("ahfb-"+e+"-builder-is-active"),n.removeClass("ahfb-"+e+"-builder-active")),Oo()})),n.on("click",".ahfb-builder-tab-toggle",(function(t){t.preventDefault(),vo.previewer.container.css({bottom:"0px"}),setTimeout((function(){n.toggleClass("ahfb-builder-hide"),Oo()}),120)}))}},Eo={addPanel:function(t,e){if(!vo.panel(t)){var r,n=vo.panelConstructor[e.type]||vo.Panel;r=_.extend({params:e},e),vo.panel.add(new n(t,r)),"undefined"!=typeof AstraBuilderCustomizerData&&AstraBuilderCustomizerData.is_site_rtl,"panel-footer-builder-group"===t&&go("#accordion-panel-"+t).on("click",(function(){var t=yo.find("iframe").contents().find("body");yo.find("iframe").contents().find("body, html").animate({scrollTop:t[0].scrollHeight},500)})),"panel-header-builder-group"===t&&go("#accordion-panel-"+t).on("click",(function(){yo.find("iframe").contents().find("body, html").animate({scrollTop:0},500)}))}},addSection:function(t,e){if(vo.section(t)){if(t.startsWith("sidebar-widgets-"))return void vo.section(t).panel(e.panel);vo.section.remove(t)}var r,n=vo.sectionConstructor[e.type]||vo.Section;r=_.extend({params:e},e),vo.section.add(new n(t,r))},addSubControl:function(t){if("undefined"!=typeof AstraBuilderCustomizerData){var e=AstraBuilderCustomizerData.js_configs.sub_controls[t]||[];if(e)for(var r=0;r<e.length;r++){var n=e[r];Eo.addControl(n.id,n)}}},addControl:function(t,e){if(!vo.control(t)){var r,n=vo.controlConstructor[e.type]||vo.Control;r=_.extend({params:e},e),vo.control.add(new n(t,r)),Co(vo.control(t)),"ast-settings-group"===e.type&&this.addSubControl(t)}},addControlContext:function(t,e){xo(e)},registerControlsBySection:function(t){if("undefined"!=typeof AstraBuilderCustomizerData){var e=AstraBuilderCustomizerData.js_configs.controls[t.id]||[];if(e)for(var r=0;r<e.length;r++){var n=e[r];this.addControl(n.id,n)}}},setControlContextBySection:function(t){if(!wo.includes(t.id)&&"undefined"!=typeof AstraBuilderCustomizerData){var e=AstraBuilderCustomizerData.js_configs.controls[t.id]||[];if(e)for(var r=0;r<e.length;r++){var n=e[r];this.addControlContext(t.id,n.id)}wo.push(t.id)}},setDefaultControlContext:function(){if("undefined"!=typeof AstraBuilderCustomizerData){var t=AstraBuilderCustomizerData.js_configs.skip_context||[];go.each(vo.settings.controls,(function(e,r){if(-1==t.indexOf(e)&&-1!=AstraBuilderCustomizerData.tabbed_sections.indexOf(vo.control(e).section())){var n=AstraBuilderCustomizerData.contexts[e];xo(e,n||[{setting:"ast_selected_tab",value:"general"}])}}))}},initializeConfigs:function(){if("undefined"!=typeof AstraBuilderCustomizerData&&AstraBuilderCustomizerData.js_configs){for(var t=AstraBuilderCustomizerData.js_configs.panels||[],e=AstraBuilderCustomizerData.js_configs.sections||[],r=Object.assign({},AstraBuilderCustomizerData.js_configs.controls||[]),n=0,a=Object.entries(t);n<a.length;n++){var o=O()(a[n],2),i=o[0],s=o[1];Eo.addPanel(i,s)}for(var c=0,l=Object.entries(e);c<l.length;c++){var u=O()(l[c],2),p=u[0],d=u[1];Eo.addSection(p,d),Eo.registerControlsBySection(vo.section(p)),delete r[p]}for(var h=0,f=Object.entries(r);h<f.length;h++){var m=O()(f[h],2),g=m[0];m[1],void 0!==vo.section(g)&&Eo.registerControlsBySection(vo.section(g))}vo.panel("panel-header-builder-group",jo),vo.panel("panel-footer-builder-group",jo)}},moveDefaultSection:function(){if("undefined"!=typeof AstraBuilderCustomizerData&&AstraBuilderCustomizerData.js_configs.wp_defaults)for(var t=0,e=Object.entries(AstraBuilderCustomizerData.js_configs.wp_defaults);t<e.length;t++){var r=O()(e[t],2),n=r[0],a=r[1];vo.control(n).section(a)}}},Co=function(t){var e=t.container.find(".customize-control-description");if(e.length){t.container.find(".customize-control-title");var r=e.closest("li"),n=e.text().replace(/[\u00A0-\u9999<>\&]/gim,(function(t){return"&#"+t.charCodeAt(0)+";"}));e.remove(),r.append(" <i class='ast-control-tooltip dashicons dashicons-editor-help'title='"+n+"'></i>")}},xo=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if("undefined"!=typeof AstraBuilderCustomizerData){var r=e||AstraBuilderCustomizerData.contexts[t];if(r){var n=function(t){switch(t){case"ast_selected_device":return vo.previewedDevice;case"ast_selected_tab":return vo.state("astra-customizer-tab");default:return vo(t)}},a=function(t){var e=function t(e,r,a){return _.each(e,(function(e,o){if("relation"!=o&&("AND"!=r||0!=a))if(void 0===e.setting){var i=e.relation;if(!i)return;a=t(e,i,!1)}else{var s=function(t){var e=!1,r=n(t.setting);if(void 0===r)return!1;var a=t.operator,o=t.value,i=r.get();switch(null!=a&&"="!=a||(a="=="),a){case">":e=i>o;break;case"<":e=i<o;break;case">=":e=i>=o;break;case"<=":e=i<=o;break;case"in":e=0<=o.indexOf(i);break;case"contains":e=0<=i.indexOf(o);break;case"!=":e=o!=i;break;default:e=o==i}return e}(e);a=function(t,e,r){switch(t){case"OR":e=e||r;break;default:e=e&&r}return e}(r,a,s)}})),a},a=function(){var t=!1,n=r.relation;return"OR"!==n&&(n="AND",t=!0),e(r,n,t)},o=function(){t._toggleActive(a(),{duration:0})};!function t(e){_.each(e,(function(e,r){var a=n(e.setting);void 0!==a?a.bind(o):e.relation&&t(e)}))}(r),t.active.validate=a,o()};vo.control(t,a)}}},vo.bind("ready",(function(){vo.state.create("astra-customizer-tab"),vo.state("astra-customizer-tab").set("general"),go("#customize-theme-controls").on("click",".ahfb-build-tabs-button:not(.ahfb-nav-tabs-button)",(function(t){t.preventDefault(),vo.previewedDevice.set(go(this).attr("data-device"))})),go("#customize-theme-controls").on("click",".ahfb-compontent-tabs-button:not(.ahfb-nav-tabs-button)",(function(t){t.preventDefault(),vo.state("astra-customizer-tab").set(go(this).attr("data-tab"))})),vo.state("astra-customizer-tab").bind((function(){var t=vo.state("astra-customizer-tab").get();go(".ahfb-compontent-tabs-button:not(.ahfb-nav-tabs-button)").removeClass("nav-tab-active").filter(".ahfb-"+t+"-tab").addClass("nav-tab-active")})),bo.on("resize",Oo),Eo.initializeConfigs(),vo.section.each((function(t){t.expanded.bind((function(e){Eo.setControlContextBySection(vo.section(t.id)),e||vo.state("astra-customizer-tab").set("general"),go("#sub-accordion-panel-"+_o+" li.control-section").hide();var r,n=vo.section(t.id);(r=new URLSearchParams(window.location.search).get("context"))&&vo.state("astra-customizer-tab").set(r),_.each(t.controls(),(function(t){!function(t){var e=go(".ahfb-builder-drop .ahfb-builder-item");go.each(e,(function(e,r){var n=go(r).attr("data-section");n===t.id&&go("#sub-accordion-section-"+n).hasClass("open")?go(r).addClass("active-builder-item"):go(r).removeClass("active-builder-item")}))}(n),function(t){var e=go(".ahfb-builder-items .ahfb-builder-areas");go.each(e,(function(e,r){var n=go(r).attr("data-row-section");n===t.id&&go("#sub-accordion-section-"+n).hasClass("open")?go(r).addClass("active-builder-row"):go(r).removeClass("active-builder-row")}))}(n)}))}))})),Eo.moveDefaultSection(),vo.previewer.bind("ready",(function(){Eo.setDefaultControlContext()}))})),function(t,e){e.bind("ready",(function(){sessionStorage.removeItem("astPartialContentRendered"),e("astra-settings[hba-footer-column]",(function(t){t.bind((function(t){var r=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:t,layout:e.value("astra-settings[hba-footer-layout]").get(),type:"above"}});document.dispatchEvent(r)}))})),e("astra-settings[hb-footer-column]",(function(t){t.bind((function(t){var r=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:t,layout:e.value("astra-settings[hb-footer-layout]").get(),type:"primary"}});document.dispatchEvent(r)}))})),e("astra-settings[hbb-footer-column]",(function(t){t.bind((function(t){var r=new CustomEvent("AstraBuilderChangeRowLayout",{detail:{columns:t,layout:e.value("astra-settings[hbb-footer-layout]").get(),type:"below"}});document.dispatchEvent(r)}))})),e("astra-settings[different-mobile-logo]",(function(t){t.bind((function(t){var r=e.control("astra-settings[mobile-header-logo]");!t&&r&&r.container.find(".remove-button").click()}))})),e.previewedDevice.bind((function(t,r){e.previewer.send("astPreviewDeviceChanged",{device:t});var n=sessionStorage.getItem("astPartialContentRendered"),a=e.state("saved").get();if(n&&!a){var o=e("desktop"===t?"astra-settings[header-desktop-items]":"astra-settings[header-mobile-items]");void 0!==o&&o.set(So(So(So({},o.get()),[]),{},{flag:!o.get().flag}))}}))}))}(jQuery,wp.customize),window.svgIcons=n,wp.customize.controlConstructor["ast-heading"]=l,wp.customize.controlConstructor["ast-hidden"]=d,wp.customize.controlConstructor["ast-description"]=v,wp.customize.controlConstructor["ast-link"]=M,wp.customize.controlConstructor["ast-divider"]=T,wp.customize.controlConstructor["ast-settings-group"]=Vt,wp.customize.controlConstructor["ast-color"]=qt,wp.customize.controlConstructor["ast-customizer-link"]=Kt,wp.customize.controlConstructor["ast-slider"]=ee,wp.customize.controlConstructor["ast-radio-image"]=ae,wp.customize.controlConstructor["ast-select"]=ie,wp.customize.controlConstructor["ast-header-type-button"]=eo,wp.customize.controlConstructor["ast-builder-header-control"]=Oe,wp.customize.controlConstructor["ast-sortable"]=Xt,wp.customize.controlConstructor["ast-font-family"]=le,wp.customize.controlConstructor["ast-font-weight"]=de,wp.customize.controlConstructor["ast-responsive-select"]=ve,wp.customize.controlConstructor["ast-responsive-slider"]=te,wp.customize.controlConstructor["ast-responsive-spacing"]=oe,wp.customize.controlConstructor["ast-border"]=Jt,wp.customize.controlConstructor["ast-responsive"]=$t,wp.customize.controlConstructor["ast-responsive-color"]=Ut,wp.customize.controlConstructor["ast-responsive-background"]=Qt,wp.customize.controlConstructor["ast-background"]=Ft,wp.customize.controlConstructor["ast-social-icons"]=Ma,wp.customize.controlConstructor["ast-html-editor"]=Ra,wp.customize.controlConstructor["ast-builder"]=ha,wp.customize.controlConstructor["ast-draggable-items"]=Ga,wp.customize.controlConstructor["ast-row-layout"]=zo}]);