import PropTypes from 'prop-types';
import {RangeControl, Dashicon} from '@wordpress/components';
import {useEffect, useState} from 'react';

const ResponsiveSliderComponent = props => {

	let prop_value = props.control.setting.get();

	const [state, setState] = useState( prop_value );

	useEffect( () => {
		// If settings are changed externally.
		if( state !== prop_value ) {
			setState(prop_value);
		}
	}, [props]);

	const updateValues = (device, newVal) => {
		let updateState = {...state};
		updateState[device] = newVal;
		props.control.setting.set(updateState);
		setState(updateState);
	};
	const renderOperationButtons = ( defaultVal ) => {
		return (
			<div className="ast-resp-slider-reset-wrap">
				<button
					className="ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small"
					disabled={ JSON.stringify(state) === JSON.stringify(defaultVal)} onClick={ e => {
					e.preventDefault();
					props.control.setting.set(defaultVal);
					setState(defaultVal);
				}}>
				<Dashicon icon='image-rotate'/>
				</button>
			</div>
		);
	};

	const renderInputHtml = (device, active = '') => {
		const {
			input_attrs,
		} = props.control.params;
		let defaultVal = props.control.params.default[device];

		const defaults = { min: 0, max: 500, step: 1 };
		const controlProps = {
			...defaults,
			...( input_attrs || {} ),
		};
		const { min, max, step } = controlProps;

		let savedValue = ( state[device] || 0 === state[device] ) ? parseFloat( state[device] ) : '';

		if ( 1 === step ) {
			savedValue = ( state[device] || 0 === state[device] ) ? parseInt( state[device] ) : '';
		}

		return <div className={`input-field-wrapper ${device} ${active}`}>
			<RangeControl
				resetFallbackValue={defaultVal}
				value={ savedValue }
				min={ min < 0 ? min : 0 }
				max={ max || 100 }
				step={ step || 1 }
				onChange={ ( newVal ) => { updateValues( device, newVal ) } }
			/>
		</div>;
	};

	const {
		description,
		label,
		suffix
	} = props.control.params;

	let labelHtml = null;
	let responsiveHtml = null;
	let suffixHtml = null;
	let descriptionHtml = null;
	let inputHtml = null;
	let defaultVal = props.control.params.default;

	if (label) {
		labelHtml = <span className="customize-control-title slider-control-label">{label}</span>;
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

	if (suffix) {
		suffixHtml = <span className="ast-range-unit">{suffix}</span>;
	}

	inputHtml = <>
		{renderInputHtml('desktop', 'active')}
		{renderInputHtml('tablet')}
		{renderInputHtml('mobile')}
	</>;

	return <div>
		<label key={'customizer-text'}>
			{labelHtml}
		</label>
		{responsiveHtml}
		{ renderOperationButtons( defaultVal ) }
		{descriptionHtml}
		<div className="wrapper">
			{inputHtml}
			{suffixHtml}
		</div>
	</div>;
};

ResponsiveSliderComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default ResponsiveSliderComponent;
