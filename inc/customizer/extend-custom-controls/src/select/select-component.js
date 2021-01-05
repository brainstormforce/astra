import PropTypes from 'prop-types';
import {useState} from 'react';


const SelectComponent = props => {

	const [props_value, setPropsValue] = useState(props.control.setting.get());

	const onSelectChange = (value) => {
		setPropsValue(value);
		props.control.setting.set(value);
	};

	const {
		label,
		name,
		choices
	} = props.control.params;

	let htmlLabel = null;

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	let optionsHtml = Object.entries(choices).map(key => {
		let html = <option key={key[0]} value={key[0]}>{key[1]}</option>;
		return html;
	});

	return <>
		{htmlLabel}
		<div className="customize-control-content">
			<select className="ast-select-input" data-name={name} data-value={props_value} value={props_value}
					onChange={() => {
						onSelectChange(event.target.value);
					}}>
				{optionsHtml}
			</select>
		</div>
	</>;

};

SelectComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( SelectComponent );
