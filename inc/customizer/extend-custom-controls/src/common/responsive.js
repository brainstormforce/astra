import PropTypes from 'prop-types';
import {useEffect, useState} from 'react';

const {__} = wp.i18n;
const {Fragment} = wp.element;

const ResponsiveControl = props => {

	const [state, setState] = useState({
			view: 'desktop'
		}
	);

	const changeViewType = (device) => {
		setState(prevState => ({
			...prevState,
			view: device
		}));
		wp.customize.previewedDevice(device);
		props.onChange(device);
	};

	const linkResponsiveButtons = () => {
		document.addEventListener('AstraChangedRepsonsivePreview', function (e) {
			changeViewType(e.detail);
		});
	};

	useEffect(() => {
		linkResponsiveButtons();
	}, []);

	return <Fragment>
		<div className={'ahfb-responsive-control-bar'}>
			{props.controlLabel && <span className="customize-control-title">{props.controlLabel}</span>}
		</div>
		<div className="ahfb-responsive-controls-content">
			{props.children}
		</div>
	</Fragment>;

};

ResponsiveControl.propTypes = {
	onChange: PropTypes.func,
	controlLabel: PropTypes.object
};
ResponsiveControl.defaultProps = {
	tooltip: true,
};

export default React.memo(ResponsiveControl);
