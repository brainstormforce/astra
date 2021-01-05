import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';

const FontFamilyComponent = props => {
	const {
		description,
		label,
		connect,
		variant,
		name,
		link
	} = props.control.params;

	let labelHtml = null,
		descriptionHtml = null,
		selectHtml = null,
		inp_array = [],
		inherit = __('Inherit', 'astra'),
		value = props.control.setting.get();

	if (label) {
		labelHtml = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		descriptionHtml = <span className="description customize-control-description">{description}</span>;
	}

	if (undefined !== link) {
		let splited_values = link.split(" ");
		splited_values.map((item, i) => {
			let item_values = item.split("=");

			if (undefined !== item_values[1]) {
				inp_array[item_values[0]] = item_values[1].replace(/"/g, "");
			}
		});
	}

	if (connect && variant) {
		selectHtml = <select {...inp_array} data-connected-control={connect} data-connected-variant={variant} data-value={value} data-name={name} data-inherit={inherit}></select>;
	} else if (connect) {
		selectHtml = <select {...inp_array} data-connected-control={connect} data-value={value} data-name={name} data-inherit={inherit}></select>;
	} else if (variant) {
		selectHtml = <select {...inp_array} data-connected-variant={variant} data-value={value} data-name={name} data-inherit={inherit}></select>;
	}

	return <>
		<label>
			{labelHtml}
			{descriptionHtml}
		</label>

		{selectHtml}
	</>;
};

FontFamilyComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( FontFamilyComponent );
