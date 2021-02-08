import PropTypes from 'prop-types';
import { __ } from '@wordpress/i18n';
import AstraColorPickerControl from '../common/astra-color-picker-control';
import {Dashicon, Tooltip} from '@wordpress/components';
import {useState} from 'react';

const ColorGroupComponent = props => {

	let htmlLabel = null;
	let htmlHelp = null;
	const {
		label,
		help,
		name
	} = props.control.params;

	const linkedSubColors = AstraBuilderCustomizerData.js_configs.sub_controls[name];
	const colorGroup = [],
		colorGroupDefaults = [],
		tooltips = [];

	Object.entries( linkedSubColors ).map( ( [ key,value ] ) => {
		colorGroup[value.name] = wp.customize.control( value.name ).setting.get();
		colorGroupDefaults[value.name] = value.default;
		tooltips[value.name] = value.title;
	});

	const[ colorGroupState , setState ] = useState(colorGroup);

	const handleChangeComplete = ( key, color='' ) => {
		let updateState = {
			...colorGroupState
		};

		let value;

		if (typeof color === 'string') {
			value = color;
		} else if (undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a) {
			value = `rgba(${color.rgb.r},${color.rgb.g},${color.rgb.b},${color.rgb.a})`;
		} else {
			value = color.hex;
		}
		
		updateState[key] = value;
		wp.customize.control( key ).setting.set(value);
		
		setState(updateState);
	};

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (help) {
		htmlHelp = <span className="ast-description">{help}</span>;
	}	

	let optionsHtml = Object.entries( colorGroupState ).map( ( [ key,value ] ) => {
		let tooltip = tooltips[key] || __('Color', 'astra');
		return (
			<Tooltip key={ key } text={ tooltip }>
				<div className="color-group-item" id={ key }>
					<AstraColorPickerControl color={value ? value : ''}
					onChangeComplete={(color, backgroundType) => handleChangeComplete(key, color)}
					backgroundType={'color'}
					allowGradient={false}
					allowImage={false}/>
				</div>
			</Tooltip>
		);
	});

	const renderResetButton = () => {
		let resetFlag = true;

		for ( let index in colorGroupState ) {
			if ( JSON.stringify( colorGroupState[index] ) !== JSON.stringify( colorGroupDefaults[index] ) ) {
				resetFlag = false;
			}
		}

		return <div className="ast-color-btn-reset-wrap ast-color-group-reset">
			<button
				className="ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small"
				disabled={ resetFlag } onClick={ e => {
				e.preventDefault();
				let resetState = {
					...colorGroupState
				};
				for ( let index in colorGroupState ) {
					resetState[index] = colorGroupDefaults[index];
					wp.customize.control( index ).setting.set(colorGroupDefaults[index]);
					setState(resetState);
				}
			}}>
			<Dashicon icon='image-rotate'/>
			</button>
		</div>;
	};

	return <>
		<div className="ast-toggle-desc-wrap">
			<label className="customizer-text">
				{htmlLabel}
				{htmlHelp}
			</label>
		</div>
			{ renderResetButton() }
		<div className="ast-field-color-group-wrap">
			{optionsHtml}
		</div>
	</>;
};

ColorGroupComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo(  ColorGroupComponent );