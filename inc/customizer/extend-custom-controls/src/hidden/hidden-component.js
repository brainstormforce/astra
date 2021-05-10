import PropTypes from 'prop-types';

const HiddenComponent = props => {
	let props_value = props.control.setting.get();
	let name = props.control.params.settings.default;
	name = name.replace('[', '-');
	name = name.replace(']', '');
	const cssClass = `hidden-field-${name}`;

	return <input type='hidden' className={cssClass} data-name={name} value={ JSON.stringify( props_value) }/>;
};

HiddenComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( HiddenComponent );
