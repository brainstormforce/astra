import PropTypes from 'prop-types';

const { __ } = wp.i18n;
const {
	Component,
	Fragment
} = wp.element;
const {
	Button,
	Dashicon,
	Tooltip,
	ButtonGroup
} = wp.components;

class ResponsiveControl extends Component {
	constructor(props) {
		super( props );
		this.state = {
			view: 'desktop'
		};
		this.linkResponsiveButtons();
	}

	render() {
		let { view } = this.state,
		deviceMap = {
			'desktop': {
				'tooltip': __( 'Desktop', 'kadence' ),
				'icon': 'desktop'
			},
			'tablet': {
				'tooltip': __( 'Tablet', 'kadence' ),
				'icon': 'tablet'
			},
			'mobile': {
				'tooltip': __( 'Mobile', 'kadence' ),
				'icon': 'smartphone'
			}
		};
		return (
				<Fragment>
					<div className={ 'kadence-responsive-control-bar' }>
						{ this.props.controlLabel && (
							<span className="customize-control-title">{ this.props.controlLabel }</span>
						) }
						{
							!this.props.hideResponsive &&
							<div className="floating-controls">
								{ this.props.tooltip && (
									<ButtonGroup>
										{Object.keys( deviceMap ).map( (device) => {
											return (
													<Tooltip text={deviceMap[device].tooltip}>
														<Button
																isTertiary
																className={( device === view ?
																		'active-device ' :
																		'' ) + device}
																onClick={() => {
																	let event = new CustomEvent(
																			'kadenceChangedRepsonsivePreview', {
																				'detail': device
																			} );
																	document.dispatchEvent( event );
																}}
														>
															<Dashicon icon={deviceMap[device].icon}/>
														</Button>
													</Tooltip>
											);
										} )}
									</ButtonGroup>
								) }
								{ ! this.props.tooltip && (
									<ButtonGroup>
										{Object.keys( deviceMap ).map( (device) => {
											return (
												<Button
														isTertiary
														className={( device === view ?
																'active-device ' :
																'' ) + device}
														onClick={() => {
															let event = new CustomEvent(
																	'kadenceChangedRepsonsivePreview', {
																		'detail': device
																	} );
															document.dispatchEvent( event );
														}}
												>
													<Dashicon icon={deviceMap[device].icon}/>
												</Button>
											);
										} )}
									</ButtonGroup>
								) }
							</div>
						}
					</div>
					<div className="kadence-responsive-controls-content">
						{this.props.children}
					</div>
				</Fragment>
		);
	}

	changeViewType(device) {
		this.setState( { view: device } );
		wp.customize.previewedDevice( device );
		this.props.onChange( device );
	}

	linkResponsiveButtons() {
		let self = this;
		document.addEventListener( 'kadenceChangedRepsonsivePreview', function(e) {
			self.changeViewType( e.detail );
		} );
	}
}

ResponsiveControl.propTypes = {
	onChange: PropTypes.func,
	controlLabel: PropTypes.string
};
ResponsiveControl.defaultProps = {
	tooltip: true,
};

export default ResponsiveControl;
