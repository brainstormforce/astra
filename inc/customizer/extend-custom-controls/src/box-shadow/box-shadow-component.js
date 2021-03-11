import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';
import {useEffect, useState} from 'react';
const { SelectControl} = wp.components;

const BoxShadowComponent = props => {

	let prop_value = props.control.setting.get();

	const [state, setState] = useState( prop_value );

	useEffect( () => {
		// If settings are changed externally.
		setState(prevState => ({
			...prevState,
			value: props.control.setting.get()
		}));
	}, [props]);

	const onBoxShadowChange = (choiceID) => {

		let updateState = {
			...state
		};

		updateState[choiceID] = event.target.value;

		props.control.setting.set(updateState);
		setState(updateState);
	};

	const onPositionChange = ( value ) => {


		let updateState = {
			...state
		};

		updateState['position'] = event.target.value;
		props.control.setting.set(updateState);
		setState(updateState);

	};

	const renderInputHtml = () => {
		const {
			id,
			choices,
			inputAttrs,
			name
		} = props.control.params;

		let htmlChoices = null;

		if( choices ) {
			htmlChoices = Object.keys(choices).map(choiceID => {
				if (choices[choiceID]) {
					let html = <li key={choiceID} {...inputAttrs} className='ast-box-shadow-input-item'>
						<input type='number' className={`ast-box-shadow-input ast-box-shadow-desktop`} data-id={choiceID}
							data-name={name} value={state[choiceID]} onChange={() => onBoxShadowChange( choiceID )}
							data-element-connect={id}/>
						<span className="ast-box-shadow-title">{choices[choiceID]}</span>
					</li>;
					return html;
				}
			});
		}

		return <ul className="ast-box-shadow-wrapper desktop active">
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

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		htmlDescription = <span className="description customize-control-description">{description}</span>;
	}

	inputHtml = <>
		{renderInputHtml()}
	</>;

	return <label key={'ast-box-shadow'} className='ast-box-shadow' htmlFor="ast-box-shadow">
		{htmlLabel}
		{htmlDescription}
		<div className="ast-box-shadow-outer-wrapper">
			<div className="input-wrapper ast-box-shadow-wrapper">
				{inputHtml}
			</div>
		</div>
		<div className="customize-control-content ast-box-shadow-inset-wrapper">
			{<SelectControl
				label={ __( "Position", 'astra' ) }
				value={ state['position'] }
				options={ [
					{ value: "outline", label:  __( "Outline", 'astra' )  },
					{ value: "inset", label:  __( "Inset", 'astra' )  }
				] }
				onChange={() => onPositionChange( value )}
				/>
			}

		</div>
	</label>;

}

BoxShadowComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default BoxShadowComponent;
