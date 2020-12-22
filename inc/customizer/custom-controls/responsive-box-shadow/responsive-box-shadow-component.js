import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';
import {useState} from 'react';

const ResponsiveBoxShadowComponent = props => {

	let value = props.control.setting.get()
	value = (undefined === value || '' === value) ? props.control.params.value : value;
	const [props_value, setPropsValue] = useState(value);

	const onBoxShadowChange = (device, choiceID) => {
		const {
			choices
		} = props.control.params;
		let updateState = {
			...props_value
		};
		let deviceUpdateState = {
			...updateState[device]
		};

		if (!event.target.classList.contains('connected')) {
			deviceUpdateState[choiceID] = event.target.value;
		} else {
			for (let choiceID in choices) {
				deviceUpdateState[choiceID] = event.target.value;
			}
		}

		updateState[device] = deviceUpdateState;
		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const onUnitChange = (device, unitKey = '') => {
		let updateState = {
			...props_value
		};
		updateState[`${device}-unit`] = unitKey;
		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const renderResponsiveInput = (device) => {
		return <input key={device} type='hidden' onChange={() => onUnitChange(device, '')}
					  className={`ast-box-shadow-unit-input ast-box-shadow-${device}-unit`} data-device={`${device}`}
					  value={props_value[`${device}-unit`]}></input>;
	};

	const renderInputHtml = (device, active = '') => {
		const {
			id,
			choices,
			inputAttrs,
			unit_choices
		} = props.control.params;

		let itemLinkDesc = __('Link Values Together', 'astra');

		let linkHtml = null;
		let htmlChoices = null;
		let respHtml = null;

		if( choices ) {
			htmlChoices = Object.keys(choices).map(choiceID => {
				let html = <li key={choiceID} {...inputAttrs} className='ast-box-shadow-input-item'>
					<input type='number' className={`ast-box-shadow-input ast-box-shadow-${device} connected`} data-id={choiceID}
						   value={props_value[device][choiceID]} onChange={() => onbox-shadowChange(device, choiceID)}
						   data-element-connect={id}/>
					<span className="ast-box-shadow-title">{choices[choiceID]}</span>
				</li>;
				return html;
			});
		}

		if( unit_choices ) {
			respHtml = Object.values(unit_choices).map(unitKey => {
				let unitClass = '';

				if (props_value[`${device}-unit`] === unitKey) {
					unitClass = 'active';
				}

				let html = <li key={unitKey} className={`single-unit ${unitClass}`}
							   onClick={() => onUnitChange(device, unitKey)} data-unit={unitKey}>
					<span className="unit-text">{unitKey}</span>
				</li>;
				return html;
			});
		}


		return <ul key={device} className={`ast-box-shadow-wrapper ${device} ${active}`}>
			{htmlChoices}
		</ul>;
	};

	const {
		label,
		description
	} = props.control.params;
	let htmlLabel = null;
	let htmlDescription = null;
	let inputHtml = null;
	let responsiveHtml = null;

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		htmlDescription = <span className="description customize-control-description">{description}</span>;
	}

	inputHtml = <>
		{renderInputHtml('desktop', 'active')}
		{renderInputHtml('tablet')}
		{renderInputHtml('mobile')}
	</>;
	responsiveHtml = <>
		<div className="unit-input-wrapper ast-box-shadow-unit-wrapper">
			{renderResponsiveInput('desktop')}
			{renderResponsiveInput('tablet')}
			{renderResponsiveInput('mobile')}
		</div>
		<ul key={'ast-box-shadow-responsive-btns'} className="ast-box-shadow-responsive-btns">
			<li key={'desktop'} className="desktop active">
				<button type="button" className="preview-desktop active" data-device="desktop">
					<i className="dashicons dashicons-desktop"></i>
				</button>
			</li>
			<li key={'tablet'} className="tablet">
				<button type="button" className="preview-tablet" data-device="tablet">
					<i className="dashicons dashicons-tablet"></i>
				</button>
			</li>
			<li key={'mobile'} className="mobile">
				<button type="button" className="preview-mobile" data-device="mobile">
					<i className="dashicons dashicons-smartphone"></i>
				</button>
			</li>
		</ul>
	</>;

	return <label key={'ast-box-shadow-responsive'} className='ast-box-shadow-responsive' htmlFor="ast-box-shadow">
		{htmlLabel}
		{htmlDescription}
		<div className="ast-box-shadow-responsive-outer-wrapper">
			<div className="input-wrapper ast-box-shadow-responsive-wrapper">
				{inputHtml}
			</div>
		</div>
	</label>;

};

ResponsiveBoxShadowComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( ResponsiveBoxShadowComponent );
