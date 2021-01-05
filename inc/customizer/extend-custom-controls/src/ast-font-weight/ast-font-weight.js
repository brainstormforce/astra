import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';

const FontWeightComponent = props => {

	const {
		description,
		label,
		connect,
		variant,
		name,
		link,
		ast_all_font_weight
	} = props.control.params;

	let value = props.control.setting.get(),
		labelHtml = <span className="customize-control-title">{label ? label : __('Background', 'astra')}</span>,
		descriptionHtml = description ?
			<span className="description customize-control-description">{description}</span> : null,
		selectHtml = null,
		inp_array = [],
		inherit = __('Inherit', 'astra'),
		optionsStaticHtml = null;
	value = (undefined === value || '' === value) ? [] : value;

	if (link) {
		let splited_values = link.split(" ");
		splited_values.map((item, i) => {
			let item_values = item.split("=");
			if (item_values[1]) {
				inp_array[item_values[0]] = item_values[1].replace(/"/g, "");
			}
		});
	}

	let optionsHtml = Object.entries(ast_all_font_weight).map(key => {
		let html = <option key={key[0]} value={key[0]}>{key[1]}</option>;
		return html;
	});

	if ('normal' === value) {
		optionsStaticHtml = <option key="normal" value="normal">{inherit}</option>;
	} else {
		optionsStaticHtml = <option key="inherit" value="inherit">{inherit}</option>;
	}

	if (connect && variant) {
		selectHtml = <select {...inp_array} data-connected-control={connect} data-connected-variant={variant}
							 data-value={value} data-name={name} data-inherit={inherit}>
			{optionsStaticHtml}
			{optionsHtml}
		</select>;
	} else if (variant) {
		selectHtml = <select {...inp_array} data-connected-variant={variant} data-value={value} data-name={name}
							 data-inherit={inherit}>
			{optionsStaticHtml}
			{optionsHtml}
		</select>;
	} else if (connect) {
		selectHtml = <select {...inp_array} data-connected-control={connect} data-value={value} data-name={name}
							 data-inherit={inherit}>
			{optionsStaticHtml}
			{optionsHtml}
		</select>;
	}

	return <>
		<label>
			{labelHtml}
			{descriptionHtml}
		</label>
		{selectHtml}
	</>;
};

FontWeightComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( FontWeightComponent );
