(function ($, api) {
	const $window = $(window),
		$body = $('body');

	var expandedSection = [];

	var expandedPanel = '';

	/**
	 * Resize Preview Frame when show / hide Builder.
	 */
	const resizePreviewer = function () {
		var $section = $('.control-section.ahfb-header-builder-active');
		var $footer = $('.control-section.ahfb-footer-builder-active');
		var sidebar_widgets = $('#available-widgets');
		sidebar_widgets.css('bottom', '289px');

		if ($body.hasClass('ahfb-header-builder-is-active') || $body.hasClass('ahfb-footer-builder-is-active')) {
			if ($body.hasClass('ahfb-footer-builder-is-active') && 0 < $footer.length && !$footer.hasClass('ahfb-builder-hide')) {
				api.previewer.container.css('bottom', $footer.outerHeight() + 'px');
			} else if ($body.hasClass('ahfb-header-builder-is-active') && 0 < $section.length && !$section.hasClass('ahfb-builder-hide')) {
				sidebar_widgets.css('bottom', '289px');
				api.previewer.container.css({"bottom": $section.outerHeight() + 'px'});
			} else {
				sidebar_widgets.css('bottom', '46px');
				api.previewer.container.css('bottom', '');
			}
		} else {
			api.previewer.container.css('bottom', '');
		}

		$section.css( 'overflow', 'visible' );
		$footer.css( 'overflow', 'visible' );
	}

	/**
	 * Init Astra Header & Footer Builder
	 */
	const initAstraBuilderPanel = function (panel) {

		let builder = panel.id.includes("-header-") ? 'header' : 'footer';
		var section = api.section('section-' + builder + '-builder');

		if (section) {

			var $section = section.contentContainer,
				section_layout = api.section('section-' + builder + '-builder-layout');

			panel.expanded.bind(function (isExpanded) {

				// Lazy load section on panel expand.
				AstCustomizerAPI.setControlContextBySection(section);
				AstCustomizerAPI.setControlContextBySection(section_layout);

				_.each(section.controls(), function (control) {

					if ('resolved' === control.deferred.embedded.state()) {
						return;
					}
					control.renderContent();
					control.deferred.embedded.resolve(); // This triggers control.ready().

					// Fire event after control is initialized.
					control.container.trigger('init');
				});
				_.each(section_layout.controls(), function (control) {

					if ('resolved' === control.deferred.embedded.state()) {
						return;
					}
					control.renderContent();
					control.deferred.embedded.resolve(); // This triggers control.ready().

					// Fire event after control is initialized.
					control.container.trigger('init');
				});



				if (isExpanded) {

					expandedPanel = panel.id;
					$body.addClass('ahfb-' + builder + '-builder-is-active');
					$section.addClass('ahfb-' + builder + '-builder-active');
					$('#sub-accordion-panel-' + expandedPanel + ' li.control-section').hide();

					if( 'header' === builder ) {
						$('#sub-accordion-section-section-footer-builder').css( 'overflow', 'hidden' );
					} else {
						$('#sub-accordion-section-section-header-builder').css( 'overflow', 'hidden' );
					}

				} else {

					$('#sub-accordion-section-section-footer-builder').css( 'overflow', 'hidden' );
					$('#sub-accordion-section-section-header-builder').css( 'overflow', 'hidden' );

					api.state('astra-customizer-tab').set('general');
					$body.removeClass('ahfb-' + builder + '-builder-is-active');
					$section.removeClass('ahfb-' + builder + '-builder-active');
				}

				resizePreviewer();

			});
			$section.on('click', '.ahfb-builder-tab-toggle', function (e) {
				e.preventDefault();
				api.previewer.container.css({"bottom": '0px'});
				setTimeout(function () {
					$section.toggleClass('ahfb-builder-hide');
					resizePreviewer();
				}, 120);

			});
		}
	};

	/**
	 * API for control/section/panel registrations.
	 */
	const AstCustomizerAPI = {

		addPanel: function (id, data) {

			// Return if panel already exists.
			if (api.panel(id)) {
				return;
			}

			var Constructor = api.panelConstructor[data.type] || api.Panel, options;
			options = _.extend({params: data}, data);
			api.panel.add(new Constructor(id, options));
			var isSiteRTL = false;

			if ('undefined' != typeof AstraBuilderCustomizerData && AstraBuilderCustomizerData.is_site_rtl) {
				isSiteRTL = true;
			}

			// Scroll to footer.
			if ('panel-footer-builder-group' === id) {
				$('#accordion-panel-' + id).on('click', function () {
					let $iframeBody = $body.find('iframe').contents().find('body');
					$body.find('iframe').contents().find('body, html').animate({
						scrollTop: $iframeBody[0].scrollHeight
					}, 500);
				});
			}

			// Scroll to header.
			if ('panel-header-builder-group' === id) {
				$('#accordion-panel-' + id).on('click', function () {
					$body.find('iframe').contents().find('body, html').animate({
						scrollTop: 0
					}, 500);
				});
			}
		},

		addSection: function (id, data) {

			// Return if section already exists.
			if (api.section(id)) {
				if (id.startsWith("sidebar-widgets-")) {
					api.section(id).panel(data['panel']); // Change panel.
					return;
				}
				api.section.remove(id);
			}

			var Constructor = api.sectionConstructor[data.type] || api.Section, options;
			options = _.extend({params: data}, data);
			api.section.add(new Constructor(id, options));

		},

		addSubControl: function (parent_control_id) {

			if ('undefined' != typeof AstraBuilderCustomizerData) {
				let sub_controls = AstraBuilderCustomizerData.js_configs.sub_controls[parent_control_id] || [];
				if (sub_controls) {
					for (let i = 0; i < sub_controls.length; i++) {
						let config = sub_controls[i];
						AstCustomizerAPI.addControl(config.id, config);
					}
				}
			}
		},

		addControl: function (id, data) {

			// Return if control already exists.
			if (api.control(id)) {
				return;
			}

			var Constructor = api.controlConstructor[data.type] || api.Control, options;
			options = _.extend({params: data}, data);
			api.control.add(new Constructor(id, options));

			// Change description to tooltip.
			change_description_as_tooltip(api.control(id));

			if ('ast-settings-group' === data['type']) {
				this.addSubControl(id);
			}
		},

		addControlContext: function (section_id, control_id) {
			set_context(control_id);
		},

		registerControlsBySection: function (section) {

			if ('undefined' != typeof AstraBuilderCustomizerData) {
				let controls = AstraBuilderCustomizerData.js_configs.controls[section.id] || [];
				if (controls) {
					for (let i = 0; i < controls.length; i++) {
						let config = controls[i];
						this.addControl(config.id, config);
					}
				}
			}
		},

		setControlContextBySection: function (section) {

			// Skip setting context when no tabs added inside section.
			if( expandedSection.includes(section.id) ) {
				return ;
			}

			if ('undefined' != typeof AstraBuilderCustomizerData) {
				let controls = AstraBuilderCustomizerData.js_configs.controls[section.id] || [];
				if (controls) {
					for (let i = 0; i < controls.length; i++) {
						let control = controls[i];
						this.addControlContext(section.id, control.id);
					}
				}
				expandedSection.push( section.id );
			}
		},

		setDefaultControlContext: function () {

			if( 'undefined' === typeof AstraBuilderCustomizerData ) {
				return ;
			}
			let skip_context = AstraBuilderCustomizerData.js_configs.skip_context || [];
			// Set tab status as general for all wp default controls.
			$.each(api.settings.controls, function (id, data) {

				if ( -1 != (skip_context.indexOf(id) ) ) {
					// Do not init context if skipped.
					return;
				}

				if ( -1 == AstraBuilderCustomizerData.tabbed_sections.indexOf( api.control(id).section() ) ) {
					return ;
				}

				let rules = AstraBuilderCustomizerData.contexts[id];
				if( rules ) {
					set_context(id, rules);
				} else {
					set_context(id, [
						{
							"setting": "ast_selected_tab",
							"value": "general"
						}
					]);
				}

			});
		},

		initializeConfigs: function () {

			if ('undefined' != typeof AstraBuilderCustomizerData && AstraBuilderCustomizerData.js_configs) {

				let panels = AstraBuilderCustomizerData.js_configs.panels || [];
				let sections = AstraBuilderCustomizerData.js_configs.sections || [];
				let controls = Object.assign({}, AstraBuilderCustomizerData.js_configs.controls || [] ) ;

				for (const [panel_id, config] of Object.entries(panels)) {
					AstCustomizerAPI.addPanel(panel_id, config);
				}

				// Add controls to theme sections.
				for (const [section_id, config] of Object.entries(sections)) {
					AstCustomizerAPI.addSection(section_id, config);
					AstCustomizerAPI.registerControlsBySection(api.section(section_id));
					delete controls[section_id];
				}

				// Add controls to third party sections.
				for (const [section_id, config] of Object.entries(controls)) {

					if( "undefined"  === typeof api.section(section_id)  ) {
						continue;
					}

					AstCustomizerAPI.registerControlsBySection(api.section(section_id));
				}

				api.panel('panel-header-builder-group', initAstraBuilderPanel)
				api.panel('panel-footer-builder-group', initAstraBuilderPanel);

			}
		},

		moveDefaultSection: function () {

			// Updating Section for wp default controls.
			if ('undefined' != typeof AstraBuilderCustomizerData && AstraBuilderCustomizerData.js_configs.wp_defaults) {
				for (const [control, section] of Object.entries(AstraBuilderCustomizerData.js_configs.wp_defaults)) {
					api.control(control).section(section);
				}
			}
		}
	};

	/**
	 * Change description to tooltip.
	 * @param ctrl
	 */
	const change_description_as_tooltip = function(ctrl) {

		var desc = ctrl.container.find(".customize-control-description");
		if (desc.length) {
			var title = ctrl.container.find(".customize-control-title");
			var li_wrapper = desc.closest("li");

			var tooltip = desc.text().replace(/[\u00A0-\u9999<>\&]/gim, function (i) {
				return '&#' + i.charCodeAt(0) + ';';
			});
			desc.remove();
			li_wrapper.append(" <i class=\'ast-control-tooltip dashicons dashicons-editor-help\'title=\'" + tooltip + "\'></i>");
		}
	}

	/**
	 * Set context for all controls.
	 * @param control_id
	 * @param control_rules
	 */
	const set_context = function(control_id, control_rules = null) {

		if ('undefined' != typeof AstraBuilderCustomizerData) {
			let rules = control_rules ? control_rules : AstraBuilderCustomizerData.contexts[control_id];
			if (rules) {
				var getSetting = function (settingName) {

					switch (settingName) {
						case 'ast_selected_device':
							return api.previewedDevice;
						case 'ast_selected_tab':
							return api.state('astra-customizer-tab');
						default:
							return api(settingName);
					}
				}

				var initContext = function (element) {

					const compareByOperator = function (rule) {

						var result = false,
							setting = getSetting(rule['setting']);

						if( 'undefined' == typeof setting ) {
							return false;
						}

						var operator = rule['operator'],
							comparedValue = rule['value'],
							currentValue = setting.get();
						if (undefined == operator || '=' == operator) {
							operator = '==';
						}

						switch (operator) {
							case '>':
								result = currentValue > comparedValue;
								break;

							case '<':
								result = currentValue < comparedValue;
								break;

							case '>=':
								result = currentValue >= comparedValue;
								break;

							case '<=':
								result = currentValue <= comparedValue;
								break;

							case 'in':
								result = 0 <= comparedValue.indexOf(currentValue);
								break;

							case 'contains':
								result = 0 <= currentValue.indexOf(comparedValue);
								break;

							case '!=':
								result = comparedValue != currentValue;
								break;

							default:
								result = comparedValue == currentValue;
								break;
						}

						return result;
					}

					const compareByRelation = function (relation, displayed, result) {

						switch (relation) {
							case 'OR':
								displayed = displayed || result;
								break;

							default:
								displayed = displayed && result;
								break;
						}

						return displayed;
					}

					const getResultByRules = function (rules, relation, displayed) {

						_.each(rules, function (rule, key) {

							if ('relation' == key) return;

							if ('AND' == relation && false == displayed) return;

							if (undefined === rule['setting']) {

								let tmp_relation = rule['relation'];
								if (!tmp_relation) {
									return;
								}

								displayed = getResultByRules(rule, tmp_relation, false);

							} else {

								var result = compareByOperator(rule);
								displayed = compareByRelation(relation, displayed, result);
							}

						});

						return displayed;

					}

					const bindSettings = function (rules) {
						_.each(rules, function (rule, index) {

							var setting = getSetting(rule['setting']);

							if (undefined !== setting) {
								setting.bind(setActiveState);
							} else {
								if (rule['relation']) {
									bindSettings(rule);
								}
							}
						});
					}

					var isDisplayed = function () {

						var displayed = false,
							relation = rules['relation'];

						if ('OR' !== relation) {
							relation = 'AND';
							displayed = true;
						}

						return getResultByRules(rules, relation, displayed);

					};

					var setActiveState = function () {
						element._toggleActive(isDisplayed(), {duration: 0});
					};

					bindSettings(rules);

					element.active.validate = isDisplayed;
					setActiveState();
				};
				api.control(control_id, initContext);
			}
		}
	}

	/**
	 * Highliting the active componenet.
	 * @param customizer_section
	 */
	const highlight_active_component = function(customizer_section) {
		var builder_items = $('.ahfb-builder-drop .ahfb-builder-item');
		$.each(builder_items, function (i, val) {
			var component_section = $(val).attr('data-section');
			if (component_section === customizer_section.id && $( '#sub-accordion-section-' + component_section ).hasClass('open')) {
				$(val).addClass('active-builder-item');
			} else {
				$(val).removeClass('active-builder-item');
			}
		});
	}

	/**
	 * Highliting the active row.
	 * @param customizer_section
	 */
	const highlight_active_row = function(customizer_section) {
		// Highlight builder rows.
		var builder_rows = $('.ahfb-builder-items .ahfb-builder-areas');
		$.each(builder_rows, function (i, val) {
			var builder_row = $(val).attr('data-row-section');
			if( builder_row === customizer_section.id && $( '#sub-accordion-section-' + builder_row ).hasClass('open') ) {
				$(val).addClass('active-builder-row');
			} else {
				$(val).removeClass('active-builder-row');
			}
		});
	}

	/**
	 * Set context using URL query params.
	 */
	const set_context_by_url_params = function() {

		let urlParams = new URLSearchParams( window.location.search );
		let tab = urlParams.get( "context" );

		if ( tab ) {

			api.state('astra-customizer-tab').set( tab );
		}
	}

	api.bind('ready', function () {

		api.state.create('astra-customizer-tab');
		api.state('astra-customizer-tab').set('general');

		// Set handler when custom responsive toggle is clicked.
		$('#customize-theme-controls').on('click', '.ahfb-build-tabs-button:not(.ahfb-nav-tabs-button)', function (e) {
			e.preventDefault();
			api.previewedDevice.set($(this).attr('data-device'));
		});

		// Set handler when custom responsive toggle is clicked.
		$('#customize-theme-controls').on('click', '.ahfb-compontent-tabs-button:not(.ahfb-nav-tabs-button)', function (e) {
			e.preventDefault();
			api.state('astra-customizer-tab').set($(this).attr('data-tab'));
		});

		const setCustomTabElementsDisplay = function () {
			var tabState = api.state('astra-customizer-tab').get(),
				$tabs = $('.ahfb-compontent-tabs-button:not(.ahfb-nav-tabs-button)');
			$tabs.removeClass('nav-tab-active').filter('.ahfb-' + tabState + '-tab').addClass('nav-tab-active');
		}
		// Refresh all responsive elements when previewedDevice is changed.
		api.state('astra-customizer-tab').bind(setCustomTabElementsDisplay);

		$window.on('resize', resizePreviewer);

		AstCustomizerAPI.initializeConfigs();
		api.section.each(function (section) {
			section.expanded.bind(function (isExpanded) {
				// Lazy Loaded Context.
				AstCustomizerAPI.setControlContextBySection(api.section(section.id));

				if ( ! isExpanded ) {
					// Setting general context when collapsed.
					api.state('astra-customizer-tab').set('general');
				}

				$('#sub-accordion-panel-' + expandedPanel + ' li.control-section').hide();

				var customizer_section = api.section(section.id);
				set_context_by_url_params();

				_.each(section.controls(), function (control) {
					highlight_active_component(customizer_section);
					highlight_active_row(customizer_section);
				});
			});
		});
		AstCustomizerAPI.moveDefaultSection();

		api.previewer.bind('ready', function () {
			AstCustomizerAPI.setDefaultControlContext();
		} );

	});

})(jQuery, wp.customize);
