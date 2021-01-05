import PropTypes from 'prop-types';
import {useState} from 'react';

const ResponsiveComponent = props => {

	const [props_value, setPropsValue] = useState(props.control.setting.get());

	const onInputChange = (device) => {
		let updateState = {
			...props_value
		};
		updateState[device] = event.target.value;
		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const onSelectChange = (device) => {
		let updateState = {
			...props_value
		};
		updateState[`${device}-unit`] = event.target.value;
		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const renderInputHtml = (device, active = '', resp = true) => {
		const {
			units
		} = props.control.params;
		let disabled = false;

		if (1 === units.length) {
			disabled = true;
		}

		let optionsHtml = Object.keys(units).map(key => {
			let html = <option key={key} value={key}>{units[key]}</option>;
			return html;
		});

		if (false === resp) {
			return <>
				<input key={device + 'input'} data-id={device}
					   className={`ast-responsive-input ast-non-reponsive ${device} ${active}`} type="number"
					   value={props_value[device]} onChange={() => {
					onInputChange(device);
				}}/>
				<select key={device + 'select'} value={props_value[`${device}-unit`]}
						className={`ast-responsive-select ${device}`} data-id={`${device}-unit`} disabled={disabled}
						onChange={() => {
							onSelectChange(device);
						}}>
					{optionsHtml}
				</select>
			</>;
		}

		return <>
			<input key={device + 'input'} data-id={device} className={`ast-responsive-input ${device} ${active}`}
				   type="number" value={props_value[device]} onChange={() => {
				onInputChange(device);
			}}/>
			<select key={device + 'select'} value={props_value[`${device}-unit`]}
					className={`ast-responsive-select ${device}`} data-id={`${device}-unit`} disabled={disabled}
					onChange={() => {
						onSelectChange(device);
					}}>
				{optionsHtml}
			</select>
		</>;
	};

	const {
		description,
		label,
		responsive
	} = props.control.params;
	let labelHtml = null;
	let responsiveHtml = null;
	let descriptionHtml = null;
	let inputHtml = null;

	if (label) {
		labelHtml = <span className="customize-control-title">{label}</span>;

		if (responsive) {
			responsiveHtml = <ul key={'ast-resp-ul'} className="ast-responsive-btns">
				<li key={'desktop'} className="desktop active">
					<button type="button" className="preview-desktop" data-device="desktop">
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
			</ul>;
		}
	}

	if (description) {
		descriptionHtml = <span className="description customize-control-description">{description}</span>;
	}

	if (responsive) {
		inputHtml = <>
			{renderInputHtml('desktop', 'active')}
			{renderInputHtml('tablet')}
			{renderInputHtml('mobile')}
		</>;
	} else {
		inputHtml = <>
			{renderInputHtml('desktop', 'active', false)}
		</>;
	}

	return <label key={'customizer-text'} className="customizer-text">
		{labelHtml}
		{responsiveHtml}
		{descriptionHtml}
		<div className="input-wrapper ast-responsive-wrapper">
			{inputHtml}
		</div>
	</label>;

};

ResponsiveComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( ResponsiveComponent );
