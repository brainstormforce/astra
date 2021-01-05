import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';
import {useState} from 'react';

const BorderComponent = props => {

	const [props_value, setPropsValue] = useState(props.control.setting.get());

	const onBorderChange = (key) => {
		const {
			choices
		} = props.control.params;

		let updateState = {
			...props_value
		};

		if (!event.target.classList.contains('connected')) {
			updateState[key] = event.target.value;
		} else {
			for (let choiceID in choices) {
				updateState[choiceID] = event.target.value;
			}
		}

		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const onConnectedClick = () => {
		let parent = event.target.parentElement.parentElement;
		let inputs = parent.querySelectorAll('.ast-border-input');
		for (let i = 0; i < inputs.length; i++) {
			inputs[i].classList.remove('connected');
			inputs[i].setAttribute('data-element-connect', '');
		}
		event.target.parentElement.classList.remove('disconnected');
	};

	const onDisconnectedClick = () => {
		let elements = event.target.dataset.elementConnect;
		let parent = event.target.parentElement.parentElement;
		let inputs = parent.querySelectorAll('.ast-border-input');
		for (let i = 0; i < inputs.length; i++) {
			inputs[i].classList.add('connected');
			inputs[i].setAttribute('data-element-connect', elements);
		}
		event.target.parentElement.classList.add('disconnected');
	};

	const {
		label,
		description,
		linked_choices,
		id,
		choices,
		inputAttrs,
		name
	} = props.control.params;

	let htmlLabel = <span className="customize-control-title">{label ? label : __('Background', 'astra')}</span>;
	let htmlDescription = description ?
		<span className="description customize-control-description">{description}</span> : null;
	let htmlLinkedChoices = null;
	let htmlChoices = null;

	let itemLinkDesc = __('Link Values Together', 'astra');

	if (linked_choices) {
		htmlLinkedChoices = <li key={id} className="ast-border-input-item-link disconnected">
					<span className="dashicons dashicons-admin-links ast-border-connected wp-ui-highlight"
						  onClick={() => {
							  onConnectedClick();
						  }} data-element-connect={id} title={itemLinkDesc}></span>
			<span className="dashicons dashicons-editor-unlink ast-border-disconnected" onClick={() => {
				onDisconnectedClick();
			}} data-element-connect={id} title={itemLinkDesc}></span>
		</li>;
	}

	htmlChoices = Object.keys(choices).map(choiceID => {
		if (choices[choiceID]) {
			var html = <li {...inputAttrs} key={choiceID} className='ast-border-input-item'>
				<input type='number' className='ast-border-input ast-border-desktop connected' data-id={choiceID}
					   data-name={name} onChange={() => onBorderChange(choiceID)} value={props_value[choiceID]}
					   data-element-connect={id}/>
				<span className="ast-border-title">{choices[choiceID]}</span>
			</li>;
		}
		return html;
	});

	return <>
		{htmlLabel}
		{htmlDescription}
		<div className="ast-border-outer-wrapper">
			<div className="input-wrapper ast-border-wrapper">
				<ul className="ast-border-wrapper desktop active">
					{htmlLinkedChoices}
					{htmlChoices}
				</ul>
			</div>
		</div>
	</>;
};

BorderComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( BorderComponent );
