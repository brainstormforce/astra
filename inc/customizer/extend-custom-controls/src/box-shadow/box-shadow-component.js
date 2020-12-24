import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';
import {useState} from 'react';

const BoxShadowComponent = props => {

	const [props_value, setPropsValue] = useState(props.control.setting.get());

	const onCheckboxChange = () => {
		let updateState = {
			...props_value
		};
		updateState['inset'] = event.target.checked;
		props.control.setting.set(updateState);
		setPropsValue(updateState);

	};	

	const onBoxShadowChange = (choiceID) => {

		let updateState = {
			...props_value
		};

		updateState[choiceID] = event.target.value;

		props.control.setting.set(updateState);
		setPropsValue(updateState);
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
							data-name={name} value={props_value[choiceID]} onChange={() => onBoxShadowChange( choiceID )}
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
			<input type="checkbox" id="ast-box-shadow-inset" className="ast-box-shadow-inset"
				name="ast-box-shadow-inset" onChange={() => onCheckboxChange()}  />
			<label>{ __( 'Inset', 'astra' ) }</label>
		</div>
	</label>;

};

BoxShadowComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( BoxShadowComponent );
