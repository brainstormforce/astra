import PropTypes from 'prop-types';

import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';
import { TextControl } from '@wordpress/components';

class LinkComponent extends Component {

	constructor(props) {

		super( props );

		let value = this.props.control.setting.get()

		this.state = {
			value
		};
		
		this.onUrlChange = this.onUrlChange.bind(this);
		this.onCheckboxChange = this.onCheckboxChange.bind(this);
		this.onRelChange = this.onRelChange.bind(this);
	}
	onUrlChange( value ) {

		const obj = {
			...this.state.value, 
			url: value,
		};
		this.setState( { value : obj } )
		this.props.control.setting.set( obj );
	}
	onCheckboxChange() {
		
		const obj = { 
			...this.state.value,
			new_tab: event.target.checked,
		};
		this.setState( { value : obj } )
		this.props.control.setting.set( obj );
		
	}
	onRelChange ( value ) { 

		const obj = { 
			...this.state.value,
			link_rel: value
		};
		this.setState( { value : obj } )
		this.props.control.setting.set( obj );
		
	}
	render() {

		const {
			value,
			label,
			settings
		} = this.props.control.params

		const {
			url,
			new_tab,
			link_rel
		} = this.state.value

		let name = settings.default;
		name = name.replace( '[', '-' );
		name = name.replace( ']', '' );
		
		let labelHtml = null;

		if ( label ) {
			labelHtml = <label><span className="customize-control-title">{ label }</span></label>
		}

		return (
			
			<Fragment>
				
				{ labelHtml }
				
				<div className="customize-control-content">
					<TextControl
						value={ url }
						className= { 'ast-link-input' }
						onChange={ (value) => {
							this.onUrlChange( value );
						} }
					/>
				</div>
				<div className="customize-control-content ast-link-open-in-new-tab-wrapper">
					<label for="ast-link-open-in-new-tab"> <input type="checkbox" id="ast-link-open-in-new-tab" className="ast-link-open-in-new-tab" name="ast-link-open-in-new-tab" checked={ new_tab } onChange={ () => this.onCheckboxChange() } /> {  __( 'Open in a New Tab' ) }</label>
				</div>
				<div className="customize-control-content">
					<label>
						<span className="customize-control-title">{ __( 'Button Link Rel' ) }</span>
					</label>
					<TextControl
						value={ link_rel }
						className= { 'ast-link-relationship' }
						onChange={ (value) => {
							this.onRelChange( value );
						} }
					/>
				</div>
				<input type="hidden" id={ `_customize-input-${ settings.default }` } className="customize-link-control-data" name={ name } data-customize-setting-link={ settings.default } data-value={ JSON.stringify( value ) }></input>
			</Fragment>
		);
	}
} 

LinkComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default LinkComponent;