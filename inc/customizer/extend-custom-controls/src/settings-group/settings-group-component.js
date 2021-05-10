import PropTypes from 'prop-types';

const SettingsGroupComponent = props => {

	let htmlLabel = null;
	let htmlHelp = null;
	const {
		label,
		help,
		name
	} = props.control.params;

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (help) {
		htmlHelp = <span className="ast-description">{help}</span>;
	}

	return <>
		<div className="ast-toggle-desc-wrap">
			<label className="customizer-text">
				{htmlLabel}
				{htmlHelp}
				<span className="ast-adv-toggle-icon dashicons" data-control={name}></span>
			</label>
		</div>
		<div className="ast-field-settings-wrap">
		</div>
	</>;
};

SettingsGroupComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo(  SettingsGroupComponent );
