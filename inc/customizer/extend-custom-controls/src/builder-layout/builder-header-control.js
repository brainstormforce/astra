import React from 'react';
import ReactDOM from 'react-dom';

const {__} = wp.i18n;

const toggleBuilderSection = (props) => {
	props.customizer.section.each(function (section) {
		if (section.expanded()) {
			section.collapse();
			return false; // Breaks.
		}
	});
};

const BuilderHeader = (props) => {
	if ("section-footer-builder" === props.control.params.section || "section-header-builder" === props.control.params.section) {
		return (
			<React.Fragment>
				<p className="ast-customize-control-title">
					{ ! astra.customizer.is_pro && 
						<>
							{ __( 'Want more? Upgrade to ', 'astra' ) }<a href={astra.customizer.upgrade_link} target="_blank">{ __( 'Astra Pro', 'astra' ) }</a>
							{ __( ' for many more header and footer options along with several amazing features too!', 'astra' ) }
						</>
					}
				</p>
				<p className="ast-customize-control-description">
					<span
						className={'button button-secondary ahfb-builder-section-shortcut ' + props.control.params.section}
						data-section={props.control.params.section}
						onClick={() => toggleBuilderSection(props)}>
						<span className="dashicons dashicons-admin-generic"> </span>
					</span>
					<span className="button button-secondary ahfb-builder-hide-button ahfb-builder-tab-toggle">
						<span className="ast-builder-hide-action"> <span
							className="dashicons dashicons-arrow-down-alt2"></span> {__('Hide', 'astra')} </span>
						<span className="ast-builder-show-action"> <span
							className="dashicons dashicons-arrow-up-alt2"></span> {__('Show', 'astra')} </span>
					</span>
				</p>
			</React.Fragment>
		);

	} else {
		return (
			<React.Fragment>
				<div className="ahfb-compontent-tabs nav-tab-wrapper wp-clearfix">
					<a href="#"
					   className={"nav-tab ahfb-general-tab ahfb-compontent-tabs-button " + (('general' === props.tab) ? "nav-tab-active" : "")}
					   data-tab="general">
						<span>{__('General', 'astra')}</span>
					</a>
					<a href="#"
					   className={"nav-tab ahfb-design-tab ahfb-compontent-tabs-button " + (('design' === props.tab) ? "nav-tab-active" : "")}
					   data-tab="design">
						<span>{__('Design', 'astra')}</span>
					</a>
				</div>
			</React.Fragment>
		)
	}
};

React.memo(BuilderHeader);

export const BuilderHeaderControl = wp.customize.astraControl.extend({
	renderContent: function renderContent() {
		let control = this;

		ReactDOM.render(
			<BuilderHeader
				control={control}
				tab={wp.customize.state('astra-customizer-tab').get()}
				customizer={wp.customize}/>, control.container[0]);
	}
});

