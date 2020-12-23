import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';
import {useState} from 'react';

const ResponsiveBoxShadowComponent = props => {

	let value = props.control.setting.get()
	value = (undefined === value || '' === value) ? props.control.params.value : value;
	const [props_value, setPropsValue] = useState(value);

	const onBoxShadowChange = ( choiceID ) => {
		const {
			choices
		} = props.control.params;
		let updateState = {
			...props_value
		};
		let deviceUpdateState = {
			...updateState[device]
		};

		// if (!event.target.classList.contains('connected')) {
			deviceUpdateState[choiceID] = event.target.value;
		// } else {
		// 	for (let choiceID in choices) {
		// 		deviceUpdateState[choiceID] = event.target.value;
		// 	}
		// }

		updateState[device] = deviceUpdateState;
		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const renderInputHtml = () => {
		const {
			id,
			choices,
			inputAttrs
		} = props.control.params;

		let htmlChoices = null;

		if( choices ) {
			htmlChoices = Object.keys(choices).map(choiceID => {
				let html = <li key={choiceID} {...inputAttrs} className='ast-box-shadow-input-item'>
					<input type='number' className={`ast-box-shadow-input`} data-id={choiceID}
						   value={props_value[device][choiceID]} onChange={() => onBoxShadowChange(choiceID)}
						   data-element-connect=''/>
					<span className="ast-box-shadow-title">{choices[choiceID]}</span>
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

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		htmlDescription = <span className="description customize-control-description">{description}</span>;
	}

	inputHtml = <>
		{renderInputHtml()}
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
