/**
 * Customizer controls
 *
 * @package Astra
 */

(function ($) {

    'use strict';

    /* Internal shorthand */
    var api = wp.customize;

	/**
	 * Helper class for the main Customizer interface.
	 *
	 * @since 1.4.3
	 * @class Astra_Customizer
	 */
    var Astra_Customizer = {

        controls: {},

		/**
		 * Initializes the logic for showing and hiding controls
		 * when a setting changes.
		 *
		 * @since 1.4.3
		 * @access private
		 * @method init
		 */
        init: function () {
            var $this = this;
            $this.handleDependency();
            $this.hideEmptySections();

            api.bind('change', function ( setting, data ) {

                var has_dependents = $this.hasDependentControls( setting.id );

                if( has_dependents ) {
                    
                    $this.handleDependency();
                    $this.hideEmptySections();
                    
                }
            });
        },

        hasDependentControls: function( control_id ) {

            var check = false;

            $.each(astra.config, function (index, val) {

                if( !_.isUndefined( val.conditions ) ) {

                    var conditions = val.conditions;

                    $.each( conditions, function (index, val) {

                        var control = val[0];

                        if( control_id == control ) {
                            check = true;
                            return;
                        }
                    });

                } else {

                    var control = val[0];

                    if( control_id == control ) {
                        check = true;
                        return;
                    }
                }

            });   

            return check;              

        },

		/**
		 * Handles dependency for controls.
		 *
		 * @since 1.4.3
		 * @access private
		 * @method handleDependency
		 */
        handleDependency: function () {
            var $this = this;
            var values = api.get();

            $this.checked_controls = {};

            _.each(values, function (value, id) {
                var control = api.control(id);

                $this.checkControlVisibility( control, id );

            });
        },

		/**
		 * Hide OR display controls according to dependency
		 *
		 * @since 1.4.3
		 * @access private
		 * @method checkControlVisibility
		 */
        checkControlVisibility: function (control, id) {

            var $this = this;
            var values = api.get();

            if ( !_.isUndefined( control ) ) {

                // If control has dependency defined
                if ( 'undefined' != typeof astra.config[id] ) {
                    var check = false;
                    var required_param = astra.config[id];
                    var conditions = !_.isUndefined(required_param.conditions) ? required_param.conditions : required_param;
                    var operator = !_.isUndefined(required_param.operator) ? required_param.operator : 'AND';

                    if ( 'undefined' !== typeof conditions ) {
                        check = $this.checkDependency(conditions, values, operator);

                        this.checked_controls[id] = check;

                        if (!check) {
                            control.container.addClass('ast-hide');
                        } else {
                            control.container.removeClass('ast-hide');
                        }
                    }
                }
            }
        },

		/**
		 * Checks dependency condtions for controls
		 *
		 * @since 1.4.3
		 * @access private
		 * @method checkDependency
		 */
        checkDependency: function (conditions, values, compare_operator) {

            var control = this;
            var check = true;
            var returnNow = false;
            var test = conditions[0];

            if ( _.isString( test ) ) {

                var cond = conditions[1];
                var cond_val = conditions[2];
                var value;

                if ( !_.isUndefined( astra.config[test] ) ) {

                    var conditions = !_.isUndefined(astra.config[test]['conditions']) ? astra.config[test]['conditions'] : astra.config[test];
                    var operator = !_.isUndefined(astra.config[test]['operator']) ? astra.config[test]['operator'] : 'AND';

                    if ( !_.isUndefined( conditions ) ) {

                        // Check visibility for dependent controls also
                        if ( ! control.checkDependency( conditions, values, operator ) ) {
                            returnNow = true;
                            check = false;
                            if( 'AND' == compare_operator ) {
                                return;
                            }
                        } else {
                            var control_obj = api.control(test);
                            control_obj.container.removeClass('ast-hide');
                        }
                    }
                }

                if ( !_.isUndefined( values[test] ) && !returnNow && check ) {
                    value = values[test];
                    check = control.compareValues( value, cond, cond_val );
                }
                

            } else if ( _.isArray( test ) ) {

                $.each( conditions, function ( index, val ) {

                    var cond_key = val[0];
                    var cond_cond = val[1];
                    var cond_val = val[2];
                    var t_val = !_.isUndefined( values[cond_key] ) ? values[cond_key] : ''; 

                    if ( 'undefined' !== typeof astra.config[cond_key] ) {

                        var conditions = !_.isUndefined(astra.config[cond_key]['conditions']) ? astra.config[cond_key]['conditions'] : astra.config[cond_key];
                        var operator = !_.isUndefined(astra.config[cond_key]['operator']) ? astra.config[cond_key]['operator'] : 'AND';

                        if ( !_.isUndefined( conditions ) ) {

                            // Check visibility for dependent controls also
                            if ( ! control.checkDependency( conditions, values, operator ) ) {

                                check = false;
                                if( 'AND' == compare_operator ) {
                                    return;
                                }
                            } else {
                                check = true;
                                var control_obj = api.control(cond_key);
                                control_obj.container.removeClass('ast-hide');
                            }
                        }
                    } else {
                        check = true;
                    }

                    if( check ) {

                        if ( 'AND' == compare_operator ) {
                            if ( ! control.compareValues( t_val, cond_cond, cond_val ) ) {
                                check = false;
                                return false;
                            }
                        } else {

                            if ( control.compareValues( t_val, cond_cond, cond_val ) ) {
                                returnNow = true;
                                check = true;
                            } else {
                                check = false;
                            }
                        }
                    }
                });

                // Break loop in case of OR operator
                if ( returnNow && 'OR' == compare_operator ) {
                    check = true;
                }
            }

            return check;
        },

        /**
         * Hide Section without Controls.
         *
        */
        hideEmptySections: function () {

            $('ul.accordion-section.control-section-ast_section').each(function () {

                var parentId = $(this).attr('id');
                var visibleIt = false;
                var controls = $(this).find(' > .customize-control');

                if ( controls.length > 0 ) {

                    controls.each(function () {

                        if ( ! $(this).hasClass('ast-hide') && $(this).css('display') != 'none' ) {
                            visibleIt = true;
                        }
                    });

                    if (!visibleIt) {
                        $('.control-section[aria-owns="' + parentId + '"]').addClass('ast-hide');
                    } else {
                        $('.control-section[aria-owns="' + parentId + '"]').removeClass('ast-hide');
                    }
                }
            });

        },

        /**
		 * Compare values
		 *
		 * @since 1.4.3
		 * @access private
		 * @method compareValues
		 */
        compareValues: function (value1, cond, value2) {
            var equal = false;
            switch (cond) {
                case '===':
                    equal = (value1 === value2) ? true : false;
                    break;
                case '>':
                    equal = (value1 > value2) ? true : false;
                    break;
                case '<':
                    equal = (value1 < value2) ? true : false;
                    break;
                case '<=':
                    equal = (value1 <= value2) ? true : false;
                    break;
                case '>=':
                    equal = (value1 >= value2) ? true : false;
                    break;
                case '!=':
                    equal = (value1 != value2) ? true : false;
                    break;
                case 'empty':
                    var _v = _.clone(value1);
                    if (_.isObject(_v) || _.isArray(_v)) {
                        _.each(_v, function (v, i) {
                            if (_.isEmpty(v)) {
                                delete _v[i];
                            }
                        });

                        equal = _.isEmpty(_v) ? true : false;
                    } else {
                        equal = _.isNull(_v) || _v == '' ? true : false;
                    }
                    break;
                case 'not_empty':
                    var _v = _.clone(value1);
                    if (_.isObject(_v) || _.isArray(_v)) {
                        _.each(_v, function (v, i) {
                            if (_.isEmpty(v)) {
                                delete _v[i];
                            }
                        })
                    }
                    equal = _.isEmpty(_v) ? false : true;
                    break;
                case 'contains':
                    if (_.isArray(value1)) {
                        if ($.inArray(value2, value1) !== -1) {
                            equal = true;
                        }
                    }
                    break;
                default:
                    if (_.isArray(value2)) {
                        if (!_.isEmpty(value2) && !_.isEmpty(value1)) {
                            equal = _.contains(value2, value1);
                        } else {
                            equal = false;
                        }
                    } else {
                        equal = (value1 == value2) ? true : false;
                    }
            }

            return equal;
        },
    };

    $(function () { Astra_Customizer.init(); });


})(jQuery);