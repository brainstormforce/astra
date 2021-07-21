import PropTypes from 'prop-types';
import {RangeControl,Dashicon} from '@wordpress/components';
import {useState} from 'react';

const SliderComponent = props => {

	const [props_value, setPropsValue] = useState( props.control.setting.get() );

	const {
		label,
		description,
		suffix,
		input_attrs,
	} = props.control.params;

	let labelHtml = null,
		descriptionHtml = null,
		suffixHtml = null,
		defaultVal = props.control.params.default;

	const defaults = { min: 0, max: 500, step: 1 };
	const controlProps = {
		...defaults,
		...( input_attrs || {} ),
	};
	const { min, max, step } = controlProps;

	if (label) {
		labelHtml = <label><span className="customize-control-title">{label}</span></label>;
	}

	if (description) {
		descriptionHtml = <span className="description customize-control-description">{description}</span>;
	}

	if (suffix) {
		suffixHtml = <span className="ast-range-unit">{suffix}</span>;
	}

	const linkRemoteUpdate = () => {

		document.addEventListener( 'AstRemoteUpdateState', function( e ) {
			if ( e.detail === 'typography' ) {
				let value = props.control.setting.get();
				updateValues( value );
			}
		} );
	}

	linkRemoteUpdate();

	const updateValues = ( newVal ) => {
		setPropsValue( newVal );
		props.control.setting.set( newVal );
	};

	const renderOperationButtons = () => {
		return (
			<div className="ast-resp-slider-reset-wrap">
				<button
					className="ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small"
					disabled={ JSON.stringify(props_value) === JSON.stringify(defaultVal)} onClick={ e => {
					e.preventDefault();
					let value = JSON.parse(JSON.stringify(defaultVal));
					updateValues(value);
				}}>
				<Dashicon icon='image-rotate'/>
				</button>
			</div>
		);
	};

	let savedValue = ( props_value || 0 === props_value ) ? parseFloat( props_value ) : '';

	if ( 1 === step ) {
		savedValue = ( props_value || 0 === props_value ) ? parseInt( props_value ) : '';
	}

	return <div className="ast-slider-wrap">
		{labelHtml}
		{descriptionHtml}
		{ renderOperationButtons() }
		<div className="wrapper">
			<RangeControl
				value={ savedValue }
				onChange={ ( value ) => updateValues( value ) }
				resetFallbackValue={ defaultVal }
				min={ min < 0 ? min : 0 }
				max={ max || 500 }
				step={ step || 1 }
			/>
			{ suffixHtml }
		</div>
	</div>;
};

SliderComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( SliderComponent );
