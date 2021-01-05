import PropTypes from 'prop-types';
import {useState} from 'react';
import {__} from '@wordpress/i18n';

const ResponsiveSliderComponent = props => {

	const [props_value, setPropsValue] = useState(props.control.setting.get());

	const onResetClick = (e) => {
		e.preventDefault();
		props.control.setting.set(props.control.params.default);
		setPropsValue(props.control.params.default);
	};

	const onInputChange = (device) => {
		let updateState = {...props_value};
		updateState[device] = event.target.value;
		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const renderInputHtml = (device, active = '') => {
		const {
			inputAttrs,
			suffix
		} = props.control.params;
		let suffixHtml = null;
		let inp_array = [];

		if (suffix) {
			suffixHtml = <span className="ast-range-unit">{suffix}</span>;
		}

		if (undefined !== inputAttrs) {
			let splited_values = inputAttrs.split(" ");
			splited_values.map((item, i) => {
				let item_values = item.split("=");

				if (undefined !== item_values[1]) {
					inp_array[item_values[0]] = item_values[1].replace(/"/g, "");
				}
			});
		}

		return <div className={`input-field-wrapper ${device} ${active}`}>
			<input type="range" {...inp_array} value={props_value[device]}
				   data-reset_value={props.control.params.default[device]} onChange={() => {
				onInputChange(device);
			}}/>
			<div className="astra_range_value">
				<input type="number" {...inp_array} data-id={device} className="ast-responsive-range-value-input"
					   value={props_value[device]} onChange={() => {
					onInputChange(device);
				}}/>
				{suffixHtml}
			</div>
		</div>;
	};

	const {
		description,
		label
	} = props.control.params;

	const reset = __('Back to default', 'astra');

	let labelHtml = null;
	let responsiveHtml = null;
	let descriptionHtml = null;
	let inputHtml = null;
	let resetHtml = null;

	if (label) {
		labelHtml = <span className="customize-control-title">{label}</span>;
		responsiveHtml = <ul key={'ast-resp-ul'} className="ast-responsive-slider-btns">
			<li className="desktop active">
				<button type="button" className="preview-desktop active" data-device="desktop">
					<i className="dashicons dashicons-desktop"></i>
				</button>
			</li>
			<li className="tablet">
				<button type="button" className="preview-tablet" data-device="tablet">
					<i className="dashicons dashicons-tablet"></i>
				</button>
			</li>
			<li className="mobile">
				<button type="button" className="preview-mobile" data-device="mobile">
					<i className="dashicons dashicons-smartphone"></i>
				</button>
			</li>
		</ul>;
	}

	if (description) {
		descriptionHtml = <span className="description customize-control-description">{description}</span>;
	}

	inputHtml = <>
		{renderInputHtml('desktop', 'active')}
		{renderInputHtml('tablet')}
		{renderInputHtml('mobile')}
	</>;
	resetHtml = <div className="ast-responsive-slider-reset" onClick={e => {
		onResetClick(e);
	}}>
		<span className="dashicons dashicons-image-rotate ast-control-tooltip" title={reset}></span>
	</div>;
	return <label key={'customizer-text'}>
		{labelHtml}
		{responsiveHtml}
		{descriptionHtml}

		<div className="wrapper">
			{inputHtml}
			{resetHtml}
		</div>
	</label>;

};

ResponsiveSliderComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( ResponsiveSliderComponent );
