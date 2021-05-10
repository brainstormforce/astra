import PropTypes from 'prop-types';

const {Dashicon, Button} = wp.components;

const HeaderTypeButtonComponent = props => {

	let defaultParams = {
		'section': '',
	};

	let controlParams = props.control.params.input_attrs ? {
		...defaultParams,
		...props.control.params.input_attrs,
	} : defaultParams;

	const focusPanel = (section) => {
		if (undefined !== props.customizer.section(section)) {
			props.customizer.section(section).focus();
		}
	};

	return <div className="ahfb-control-field ahfb-available-items">
		<div className={'ahfb-builder-item-start'}>
			<Button className="ahfb-builder-item" onClick={() => focusPanel(controlParams.section)}
					data-section={controlParams.section}>
				{controlParams.label ? controlParams.label : ''}
				<span className="ahfb-builder-item-icon">
							<Dashicon icon="arrow-right-alt2"/>
						</span>
			</Button>
		</div>
	</div>;
};

HeaderTypeButtonComponent.propTypes = {
	control: PropTypes.object.isRequired,
	customizer: PropTypes.func.isRequired
};

export default React.memo( HeaderTypeButtonComponent );
