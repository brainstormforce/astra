import PropTypes from 'prop-types';

const {__} = wp.i18n;
const {Fragment} = wp.element;
import {useEffect, useState} from 'react';

const ResponsiveDeviceControl = props => {

	const [state, setState] = useState({
			view: props.device
		}
	);

	let { view } = state, deviceMap = {
			'desktop': {
				'icon': 'desktop'
			},
			'tablet': {
				'icon': 'tablet'
			},
			'mobile': {
				'icon': 'smartphone'
			}
		};

	const changeViewType = (device) => {

		let triggerDevice = "";

		switch (device) {
			case "desktop":
				triggerDevice = "tablet";
				break;

			case "tablet":
				triggerDevice = "mobile";
				break;

			case "mobile":
				triggerDevice = "desktop";
				break;

			default:
				break;
		}

		setState(prevState => ({
			...prevState,
			view: triggerDevice
		}));
		wp.customize.previewedDevice(triggerDevice);
		props.onChange(triggerDevice);
	};

	const linkResponsiveButtons = () => {
		document.addEventListener('AstraChangedRepsonsivePreview', function (e) {
			changeViewType(e.detail);
		});
	};

	useEffect(() => {
		linkResponsiveButtons();
	}, [] );

	return <Fragment>
		<div className={'ahfb-responsive-control-bar'}>
			{props.controlLabel && <span className="customize-control-title">{props.controlLabel}</span>}
			{!props.hideResponsive && <div className="floating-controls">
				<ul key={'ast-resp-ul'} className="ast-responsive-btns">
					{Object.keys(deviceMap).map(device => {
						return <li key={device} className={device}
								   className={(device === view ? 'active ' : '') + `preview-${device}`}>
							<button type="button" data-device={device}
									className={(device === view ? 'active ' : '') + `preview-${device}`}
									onClick={() => {
										let event = new CustomEvent('AstraChangedRepsonsivePreview', {
											'detail': device
										});
										document.dispatchEvent(event);
									}}>
								<i className={`dashicons dashicons-${device === 'mobile' ? 'smartphone' : device}`}>
								</i>
							</button>
						</li>;
					})}
				</ul>
			</div>}
		</div>
		<div className="ahfb-responsive-controls-content">
			{props.children}
		</div>
	</Fragment>;

};

ResponsiveDeviceControl.propTypes = {
	onChange: PropTypes.func,
	controlLabel: PropTypes.object
};

export default React.memo( ResponsiveDeviceControl );
