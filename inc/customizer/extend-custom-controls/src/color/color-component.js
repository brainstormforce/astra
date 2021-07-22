import PropTypes from 'prop-types';
import {Dashicon} from '@wordpress/components';
import AstraColorPickerControl from '../common/astra-color-picker-control';
import {useEffect, useState} from 'react';

const ColorComponent = props => {

	let value = props.control.setting.get();

	let defaultValue = props.control.params.default;

	const [state, setState] = useState({
		value: value,
	});

	// useEffect( () => {
	// 	// If settings are changed externally.
	// 	if( state.value !== value ) {
	// 		setState(value);
	// 	}
	// }, [props]);

	const updateValues = (value) => {
		setState(prevState => ({
			...prevState,
			value: value
		}));
		props.control.setting.set(value);
	};

	const renderOperationButtons = () => {

		let resetFlag = true;
		const tempVal = state.value.replace( 'unset', '' );

		if ( JSON.stringify(tempVal) !== JSON.stringify(defaultValue) ) {
			resetFlag = false;
		}
		return <span className="customize-control-title">
				<>
					<div className="ast-color-btn-reset-wrap">
						<button
							className="ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small"
							disabled={resetFlag} onClick={e => {
							e.preventDefault();
							let value = JSON.parse(JSON.stringify(defaultValue));

							if (undefined === value || '' === value) {
								value = 'unset';
							}

							updateValues(value);
						}}>
						<Dashicon icon='image-rotate'/>
						</button>
					</div>
				</>
			</span>;
	};

	const handleChangeComplete = ( color ) => {
		let value;

		if (typeof color === 'string') {
			value = color;
		} else if (undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a) {
			value = `rgba(${color.rgb.r},${color.rgb.g},${color.rgb.b},${color.rgb.a})`;
		} else {
			value = color.hex;
		}

		updateValues(value);
	};

	let labelHtml = null;
	const {
		label
	} = props.control.params;

	if (label) {
		labelHtml = <span className="customize-control-title">{label}</span>;
	}

	return <div className="ast-control-wrap">
		<label>
			{labelHtml}
		</label>
		<div className="ast-color-picker-alpha color-picker-hex">
			{renderOperationButtons()}
			<AstraColorPickerControl color={undefined !== state.value && state.value ? state.value : ''}
									 onChangeComplete={(color, backgroundType) => handleChangeComplete(color)}
									 backgroundType={'color'}
									 allowGradient={false}
									 allowImage={false}/>

		</div>
	</div>;

};

ColorComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default ColorComponent;
