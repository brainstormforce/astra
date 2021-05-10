import PropTypes from 'prop-types';
import {Fragment} from '@wordpress/element';
import {useState} from 'react';


const RadioImageComponent = props => {

	const [props_value, setPropsValue] = useState(props.control.setting.get());

	const onLayoutChange = (value) => {
		setPropsValue(value);
		props.control.setting.set(value);
	};

	const {
		label,
		description,
		id,
		choices,
		inputAttrs,
		choices_titles,
		link,
		labelStyle
	} = props.control.params;

	let htmlLabel = null,
		htmlDescription = null,
		htmlRadio,
		inp_array = [];

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		htmlDescription = <span className="description customize-control-description">{description}</span>;
	}

	if( inputAttrs ) {
		let splited_values = inputAttrs.split(" ");
		splited_values.map((item, i) => {
			let item_values = item.split("=");
			if (undefined !== item_values[1]) {
				inp_array[item_values[0]] = item_values[1].replace(/"/g, "");
			}
		});
	}

	if( link ) {
		let splited_values = link.split(" ");
		splited_values.map((item, i) => {
			let item_values = item.split("=");
			if (undefined !== item_values[1]) {
				inp_array[item_values[0]] = item_values[1].replace(/"/g, "");
			}
		});
	}

	htmlRadio = Object.entries(choices).map(([key, value]) => {
		let checked = props_value === key ? true : false;
		return <Fragment key={key}>
			<input {...inp_array} className="image-select" type="radio" value={key} name={`_customize-radio-${id}`}
				   id={id + key} checked={checked} onChange={() => onLayoutChange(key)}/>

			<label htmlFor={id + key} {...labelStyle} className="ast-radio-img-svg">
						<span dangerouslySetInnerHTML={{
							__html: choices[key]
						}}/>
				<span className="image-clickable" title={choices_titles[key]}></span>
			</label>
		</Fragment>;
	});
	return <Fragment>
		<label className="customizer-text">
			{htmlLabel}
			{htmlDescription}
		</label>
		<div id={`input_${id}`} className="image">
			{htmlRadio}
		</div>
	</Fragment>;
};

RadioImageComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( RadioImageComponent );
