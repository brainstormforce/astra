import PropTypes from 'prop-types';
import { isEqual } from 'lodash';
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { Tooltip, Button, Dashicon } = wp.components;
import ColorControl from '../common/color.js';

class ColorComponent extends Component {
	constructor(props) {
		super( props );
		this.handleChangeComplete = this.handleChangeComplete.bind( this );
		this.updateValues = this.updateValues.bind( this );
		let value = this.props.control.setting.get();
		let baseDefault = {
			'color': '',
			'hover': '',
		};
		this.defaultValue = this.props.control.params.default ? {
			...baseDefault,
			...this.props.control.params.default
		} : baseDefault;
		value = value ? {
			...JSON.parse( JSON.stringify( this.defaultValue ) ),
			...value
		} : JSON.parse( JSON.stringify( this.defaultValue ) );
		let defaultParams = {
			colors: {
				color: {
					tooltip: __( 'Color', 'kadence' ),
					palette: true,
				},
				hover: {
					tooltip: __( 'Hover Color', 'kadence' ),
					palette: true,
				},
			},
			allowGradient: false,
		};
		this.controlParams = this.props.control.params.input_attrs ? {
			...defaultParams,
			...this.props.control.params.input_attrs,
		} : defaultParams;
		const palette = [];
		console.log(palette);
		this.state = {
			value: value,
			colorPalette: palette,
		};
	}
	handleChangeComplete( color, isPalette, item ) {
		let value = this.state.value;
		if ( isPalette ) {
			value[ item ] = isPalette;
		} else if ( typeof color === 'string' || color instanceof String ) {
			value[ item ] = color;
		} else if ( undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a ) {
			value[ item ] = 'rgba(' +  color.rgb.r + ',' +  color.rgb.g + ',' +  color.rgb.b + ',' + color.rgb.a + ')';
		} else {
			value[ item ] = color.hex;
		}
		this.updateValues( value );
	}

	render() {
		return (
				<div className="kadence-control-field kadence-color-control">
					<span className="customize-control-title">
						<Fragment>
							<Tooltip text={ __( 'Reset Values', 'kadence' ) }>
								<Button
									className="reset kadence-reset"
									disabled={ ( JSON.stringify( this.state.value ) === JSON.stringify( this.defaultValue ) ) }
									onClick={ () => {
										let value = JSON.parse( JSON.stringify( this.defaultValue ) );
										this.updateValues( value );
									} }
								>
									<Dashicon icon='image-rotate' />
								</Button>
							</Tooltip>
							{ this.props.control.params.label && (
								this.props.control.params.label
							) }
						</Fragment>
					</span>
					{ Object.keys( this.controlParams.colors ).map( ( item ) => {
						return (
							<ColorControl
								key={ item }
								presetColors={ this.state.colorPalette }
								color={ ( undefined !== this.state.value[ item ] && this.state.value[ item ] ? this.state.value[ item ] : '' ) }
								usePalette={ ( undefined !== this.controlParams.colors[ item ] && undefined !== this.controlParams.colors[ item ].palette && '' !== this.controlParams.colors[ item ].palette ? this.controlParams.colors[ item ].palette : true ) }
								tooltip={ ( undefined !== this.controlParams.colors[ item ] && undefined !== this.controlParams.colors[ item ].tooltip ? this.controlParams.colors[ item ].tooltip : '' ) }
								onChangeComplete={ ( color, isPalette ) => this.handleChangeComplete( color, isPalette, item ) }
								customizer={ this.props.customizer }
								allowGradient={ this.controlParams.allowGradient }
							/>
						)
					} ) }
				</div>
		);
	}

	updateValues( value ) {
		this.setState( { value: value } );
		this.props.control.setting.set( {
			...this.props.control.setting.get(),
			...value,
		} );
	}
}

ColorComponent.propTypes = {
	control: PropTypes.object.isRequired,
	customizer: PropTypes.object.isRequired
};

export default ColorComponent;
