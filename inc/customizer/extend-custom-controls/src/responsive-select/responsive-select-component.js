import PropTypes from 'prop-types';
import {Component} from '@wordpress/element';

class ResponsiveSelectComponent extends Component {

	constructor( props ) {

		super( ...arguments );

		let value = this.props.control.setting.get();

		this.state = {
			value
		};

		this.onSelectChange = this.onSelectChange.bind(this);
		this.renderSelectHtml = this.renderSelectHtml.bind(this);
	}

	onSelectChange( event, device ) {
		let updateState = {
			...this.state.value
		}
		updateState[ device ]  = event.target.value;
		this.updateValues( updateState );
	}

	renderSelectHtml( device, active='' ) {

		const choices = this.props.control.params.choices;

		let optionsHtml = Object.entries( choices ).map( ( key ) => {

			let html = (
				<option key={ key[0] } value={ key[0] }>{ key[1] }</option>
			);
			return html;
		} );

		return (
			<div className={ `ast-responsive-select-container ${device} ${active}`}>
				<select className="ast-select-input" data-value={ this.state.value[ device ] } value={ this.state.value[ device ] } onChange={ ( e ) => { this.onSelectChange( e, device ) } } >
					{ optionsHtml }
				</select>
			</div>
		);
	}

	render() {

		const label = this.props.control.params.label;

		let htmlLabel = null;

		if ( label ) {
			htmlLabel = <span className="customize-control-title">{ label }</span>;
		}

		const responsiveHtml = (
			<ul key={ 'ast-resp-ul' } className="ast-responsive-btns">
				<li key={ 'desktop' } className="desktop active">
					<button type="button" className="preview-desktop" data-device="desktop">
						<i className="dashicons dashicons-desktop"></i>
					</button>
				</li>
				<li key={ 'tablet' } className="tablet">
					<button type="button" className="preview-tablet" data-device="tablet">
						<i className="dashicons dashicons-tablet"></i>
					</button>
				</li>
				<li key={ 'mobile' } className="mobile">
					<button type="button" className="preview-mobile" data-device="mobile">
						<i className="dashicons dashicons-smartphone"></i>
					</button>
				</li>
			</ul>
		);

		let selectHtml = (
			<>
				{ this.renderSelectHtml( 'desktop', 'active' ) }
				{ this.renderSelectHtml( 'tablet' ) }
				{ this.renderSelectHtml( 'mobile' ) }
			</>
		);

		return (
			<>
				{ htmlLabel }
				{ responsiveHtml }
				<div className="customize-control-content">
					<div className="ast-responsive-select-wrapper">
						{ selectHtml }
					</div>
				</div>
			</>
		);
	}

	updateValues( updateState ) {
		this.setState( { value : updateState } )
		this.props.control.setting.set( updateState );
	}
}

ResponsiveSelectComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default ResponsiveSelectComponent;
