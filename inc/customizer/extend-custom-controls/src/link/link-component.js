import PropTypes from 'prop-types';

import {__} from '@wordpress/i18n';
import {Fragment} from '@wordpress/element';
import {TextControl, ToggleControl} from '@wordpress/components';
import {useEffect, useState} from "react";

const LinkComponent = props => {

	let prop_value = props.control.setting.get();

	const [state, setState] = useState( prop_value );

	useEffect( () => {
		// If settings are changed externally.
		if( state !== prop_value ) {
			setState(prop_value);
		}
	}, [props]);

	const onUrlChange = (value) => {
		const obj = {
			...state,
			url: value
		};
		setState(obj);
		props.control.setting.set(obj);
	};

	const onCheckboxChange = () => {
		const obj = {
			...state,
			new_tab: event.target.checked
		};
		setState(obj);
		props.control.setting.set(obj);
	};

	const onRelChange = (value) => {
		const obj = {
			...state,
			link_rel: value
		};
		setState(obj);
		props.control.setting.set(obj);
	};

	const {
		value,
		label,
		settings
	} = props.control.params;

	const {
		url,
		new_tab,
		link_rel
	} = state;

	let name = settings.default;
	name = name.replace('[', '-');
	name = name.replace(']', '');
	let labelHtml = null;

	if (label) {
		labelHtml = <label><span className="customize-control-title">{label}</span></label>;
	}

	return <Fragment>

		{labelHtml}

		<div className="customize-control-content">
			<TextControl value={url} className={'ast-link-input'} onChange={value => {
				onUrlChange(value);
			}}/>
		</div>
		<div className="customize-control-content ast-link-open-in-new-tab-wrapper ast-togglecontrol-wrapper">
			<ToggleControl
            label={ __('Open in a New Tab', 'astra') }
            checked={new_tab}
            onChange={() => onCheckboxChange()}
            />
		</div>
		<div className="customize-control-content">
			<label>
				<span className="customize-control-title">{__('Link Rel')}</span>
			</label>
			<TextControl value={link_rel} className={'ast-link-relationship'} onChange={value => {
				onRelChange(value);
			}}/>
		</div>
		<input type="hidden" id={`_customize-input-${settings.default}`} className="customize-link-control-data"
			   name={name} data-customize-setting-link={settings.default} data-value={JSON.stringify(value)}></input>
	</Fragment>;
};

LinkComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default LinkComponent;
