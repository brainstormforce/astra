import PropTypes from 'prop-types';
import {useState} from 'react';
import {Dashicon} from '@wordpress/components';
import AstraColorPickerControl from '../common/astra-color-picker-control';

const ResponsiveColorComponent = props => {

	const [props_value, setPropsValue] = useState(props.control.setting.get());


	const updateValues = ( value, key ) => {
		const obj = {
			...props_value
		};
		obj[key] = value;
		props.control.setting.set(obj);
		setPropsValue(obj);
	};

	const renderReset = ( key ) => {
		let deleteBtnDisabled = true;
		let devices = ['desktop', 'mobile', 'tablet'];

		for (let device of devices) {
			if (props_value[device]) {
				deleteBtnDisabled = false;
			}
		}

		return <span className="customize-control-title">
			<>
				<div className="ast-color-btn-reset-wrap">
					<button
						className="ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small"
						disabled={JSON.stringify(props_value) === JSON.stringify(props.control.params.default)} onClick={e => {
						e.preventDefault();
						let value = JSON.parse(JSON.stringify(props.control.params.default));

						if (undefined !== value && '' !== value) {
							for (let device in value) {
								if (undefined === value[device] || '' === value[device]) {
									value[device] = '';
									wp.customize.previewer.refresh();
								}
							}
						}

						props.control.setting.set(value);
						setPropsValue(value);
					}}>
						<Dashicon icon='image-rotate' style={{
							width: 12,
							height: 12,
							fontSize: 12
						}}/>
					</button>
				</div>
			</>
			</span>;
	};

	const renderSettings = ( key ) => {
		return <AstraColorPickerControl
			color={undefined !== props_value[key] && props_value[key] ? props_value[key] : ''}
			onChangeComplete={(color, backgroundType) => handleChangeComplete(color, key)} backgroundType={'color'}
			allowGradient={false} allowImage={false}/>;
	};

	const handleChangeComplete = ( color, key ) => {
		let value;

		if (typeof color === 'string' || color instanceof String) {
			value = color;
		} else if (undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a) {
			value = 'rgba(' + color.rgb.r + ',' + color.rgb.g + ',' + color.rgb.b + ',' + color.rgb.a + ')';
		} else {
			value = color.hex;
		}

		updateValues(value, key);
	};

	const {
		defaultValue,
		label,
		description,
		responsive,
	} = props.control.params;

	let defaultVal = '#RRGGBB';
	let labelHtml = null;
	let descriptionHtml = null;
	let responsiveHtml = null;
	let inputHtml = null;

	if (defaultValue) {
		if ('#' !== defaultValue.substring(0, 1)) {
			defaultVal = '#' + defaultValue;
		} else {
			defaultVal = defaultValue;
		}

		defaultValueAttr = ' data-default-color=' + defaultVal; // Quotes added automatically.
	}

	if (label) {
		labelHtml = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		descriptionHtml = <span className="description customize-control-description">{description}</span>;
	}

	if (responsive) {
		responsiveHtml = <ul className="ast-responsive-btns">
			<li className="desktop active">
				<button type="button" className="preview-desktop" data-device="desktop">
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
		inputHtml = <>

			<div className="ast-color-picker-alpha color-picker-hex ast-responsive-color desktop active">
				{renderReset('desktop')}
				{renderSettings('desktop')}
			</div>
			<div className="ast-color-picker-alpha color-picker-hex ast-responsive-color tablet">
				{renderReset('tablet')}
				{renderSettings('tablet')}
			</div>
			<div className="ast-color-picker-alpha color-picker-hex ast-responsive-color mobile">
				{renderReset('mobile')}
				{renderSettings('mobile')}
			</div>
		</>;
	}

	return <>
		<label>
			{labelHtml}
			{descriptionHtml}
		</label>

		{responsiveHtml}

		<div className="customize-control-content">
			{inputHtml}
		</div>
	</>;

};

ResponsiveColorComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( ResponsiveColorComponent );
