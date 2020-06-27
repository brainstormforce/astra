import color from 'react-color/lib/helpers/color'

import { EditableInput } from 'react-color/lib/components/common'
const {
	Component,
	Fragment
} = wp.element;
const {
	Dashicon,
} = wp.components;
class PickerFields extends Component {
	constructor(props) {
		super( props );
		this.toggleViews = this.toggleViews.bind( this );
		this.handleChange = this.handleChange.bind( this );
		this.state = {
			view: 'rgb',
		};
	}
	toggleViews() {
		if ( this.state.view === 'hsl' ) {
			this.setState({ view: 'rgb' })
		} else if (this.state.view === 'rgb') {
		  this.setState({ view: 'hsl' })
		}
	}
	handleChange(data, e) {
		if (data.hex) {
			color.isValidHex(data.hex) && this.props.onChange({
				hex: data.hex,
				source: 'hex',
		  	}, e)
		} else if (data.r || data.g || data.b) {
			this.props.onChange({
				r: data.r || this.props.rgb.r,
				g: data.g || this.props.rgb.g,
				b: data.b || this.props.rgb.b,
				a: this.props.rgb.a,
				source: 'rgb',
			}, e)
		} else if (data.a) {
			if (data.a < 0) {
				data.a = 0
			} else if (data.a > 1) {
				data.a = 1
			}

			this.props.onChange({
				h: this.props.hsl.h,
				s: this.props.hsl.s,
				l: this.props.hsl.l,
				a: Math.round(data.a * 100) / 100,
				source: 'rgb',
			}, e)
		} else if (data.h || data.s || data.l) {
			// Remove any occurances of '%'.
			if (typeof(data.s) === 'string') { data.s = data.s.replace('%', '') } 
			if (typeof(data.l) === 'string') { data.l = data.l.replace('%', '') }
			this.props.onChange({
				h: data.h || this.props.hsl.h || 0,
				s: Number((data.s && data.s / 100) || this.props.hsl.s || 0.0 ),
				l: Number((data.l && data.l / 100) || this.props.hsl.l || 0.0 ),
				a: Math.round(data.a * 100) / 100 || this.props.rgb.a || 1,
				source: 'hsl',
			}, e)
		}
	}
	render() {
		const styles = {
			fields: {
				display: 'flex',
				paddingTop: '4px',
			  },
			  single: {
				flex: '1',
				paddingLeft: '6px',
			  },
			  alpha: {
				flex: '1',
				paddingLeft: '6px',
			  },
			  double: {
				flex: '2',
			  },
			  input: {
				width: '100%',
				padding: '4px 10% 3px',
				border: 'none',
				borderRadius: '2px',
				boxShadow: 'rgb(218, 218, 218) 0px 0px 0px 1px inset',
				fontSize: '11px',
			  },
			  label: {
				display: 'block',
				textAlign: 'center',
				fontSize: '11px',
				color: '#222',
				paddingTop: '3px',
				paddingBottom: '4px',
				textTransform: 'capitalize',
			  },
			  toggle: {
				width: '32px',
				textAlign: 'right',
				position: 'relative',
			  },
		}
		return (
			<div style={ styles.fields } className="flexbox-fix">
				<div style={ styles.double }>
					<EditableInput
						style={{ input: styles.input, label: styles.label }}
						label="hex"
						value={ this.props.hex.replace('#', '') }
						onChange={ this.handleChange }
					/>
				</div>
			  	{ this.state.view === 'rgb' && (
					<Fragment>
						<div style={ styles.single }>
							<EditableInput
								style={{ input: styles.input, label: styles.label }}
								label="r"
								value={ this.props.rgb.r }
								onChange={ this.handleChange }
								dragLabel="true"
								dragMax="255"
							/>
						</div>
						<div style={ styles.single }>
							<EditableInput
							style={{ input: styles.input, label: styles.label }}
							label="g"
							value={ this.props.rgb.g }
							onChange={ this.handleChange }
							dragLabel="true"
							dragMax="255"
							/>
						</div>
						<div style={ styles.single }>
							<EditableInput
							style={{ input: styles.input, label: styles.label }}
							label="b"
							value={ this.props.rgb.b }
							onChange={ this.handleChange }
							dragLabel="true"
							dragMax="255"
							/>
						</div>
						<div style={ styles.alpha }>
							<EditableInput
								style={{ input: styles.input, label: styles.label }}
								label="a"
								value={ this.props.rgb.a }
								arrowOffset={ 0.01 }
								onChange={ this.handleChange }
							/>
						</div>
					</Fragment>
				) }
				{ this.state.view === 'hsl' && (
					<Fragment>
						<div style={ styles.single }>
							<EditableInput
								style={{ input: styles.input, label: styles.label }}
								label="h"
								value={ Math.round(this.props.hsl.h) }
								onChange={ this.handleChange }
								dragLabel="true"
								dragMax="359"
							/>
						</div>
						<div style={ styles.single }>
							<EditableInput
								style={{ input: styles.input, label: styles.label }}
								label="s"
								value={ `${ Math.round( this.props.hsl.s * 100 ) }` }
								onChange={ this.handleChange }
							/>
						</div>
						<div style={ styles.single }>
							<EditableInput
								style={{ input: styles.input, label: styles.label }}
								label="l"
								value={ `${ Math.round(this.props.hsl.l * 100) }` }
								onChange={ this.handleChange }
							/>
						</div>
						<div style={ styles.alpha }>
							<EditableInput
								style={{ input: styles.input, label: styles.label }}
								label="a"
								value={ this.props.hsl.a }
								arrowOffset={ 0.01 }
								onChange={ this.handleChange }
							/>
						</div>
					</Fragment>
				) }
				<div style={ styles.toggle }>
					<div className="toggle-icons" style={ styles.icon } onClick={ this.toggleViews } ref={ (icon) => this.icon = icon }>
						<Dashicon icon="arrow-up-alt2" />
						<Dashicon icon="arrow-down-alt2" />
					</div>
				</div>
			</div>
		);
	}
}
export default PickerFields
