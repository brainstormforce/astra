import PropTypes from 'prop-types';
import {useState} from 'react';
import { Button } from '@wordpress/components';

const SelectorComponent = props => {

	const [propsValue, setPropsValue] = useState(props.control.setting.get());

	const Icons = window.svgIcons;

	const onValueChange = ( value, device='' ) => {
		let updateState = {
			...propsValue
        };
        if ( '' !== device ) {
            updateState[device] = value;
        } else {
            updateState = value;
        }

		props.control.setting.set(updateState);
		setPropsValue(updateState);
	};

	const renderInputHtml = ( device, active = '', resp = true ) => {

		const {
			choices,
			renderAs
		} = props.control.params;

		if ( ! choices ) {
			return;
		}

		if ( false === resp ) {

			let optionsHtml = Object.entries( choices ).map( ( [value, icon] ) => {

				if ( 'text' !== renderAs ) {

					var html = (
						<div className="ast-alignment-inner-wrap active" key={ value }>
							<Button
								key={ value }
								onClick={ () => onValueChange( value ) }
								aria-pressed = { value === propsValue }
								isPrimary = { value === propsValue }
							>
								<span className="ahfb-icon-set"
									dangerouslySetInnerHTML={ { __html: Icons[ icon ]  } }
								></span>
							</Button>
						</div>
					);
				} else {

					var html = (
						<div className="ast-alignment-inner-wrap active" key={ value }>
							<Button
								key={ value }
								onClick={ () => onValueChange( value ) }
								aria-pressed = { value === propsValue }
								isPrimary = { value === propsValue }
								label = { icon }
							>
								{ icon }
							</Button>
						</div>
					);
				}

				return html;
			} );

			return optionsHtml;
		}

		if ( 'text' !== renderAs ) {

			var optionsHtml = Object.entries( choices ).map( ( [value, icon] ) => {

				let html = (
					<div className={ `ast-alignment-inner-wrap ast-alignment-responsive ${device} ${active}` } key={ value } >
						<Button
							key={ value }
							onClick={ () => onValueChange( value, device ) }
							aria-pressed = { value === propsValue[device] }
							isPrimary = { value === propsValue[device] }
						>
							<span className="ahfb-icon-set"
								dangerouslySetInnerHTML={ { __html: Icons[ icon ]  } }
							></span>
						</Button>
					</div>
				);

				return html;
			} );
		} else {

			var optionsHtml = Object.entries( choices ).map( ( [value, icon] ) => {

				let html = (
					<div className={ `ast-alignment-inner-wrap ast-alignment-responsive ${device} ${active}` } key={ value } >
						<Button
							key={ value }
							onClick={ () => onValueChange( value, device ) }
							aria-pressed = { value === propsValue[device] }
							isPrimary = { value === propsValue[device] }
							label = { icon }
						>
							{ icon }

						</Button>
					</div>
				);

				return html;
			} );

		}

		return optionsHtml;
	};

	const {
		description,
		label,
		responsive
	} = props.control.params;
	let labelHtml = null;
	let responsiveHtml = null;
	let descriptionHtml = null;
	let inputHtml = null;

	let responsiveFlag = ( false === responsive ) ? false : true ;

	if (label) {
		labelHtml = <span className="customize-control-title">{label}</span>;

		if (responsiveFlag) {
			responsiveHtml = <ul key={'ast-resp-ul'} className="ast-responsive-btns">
				<li key={'desktop'} className="desktop active">
					<button type="button" className="preview-desktop" data-device="desktop">
						<i className="dashicons dashicons-desktop"></i>
					</button>
				</li>
				<li key={'tablet'} className="tablet">
					<button type="button" className="preview-tablet" data-device="tablet">
						<i className="dashicons dashicons-tablet"></i>
					</button>
				</li>
				<li key={'mobile'} className="mobile">
					<button type="button" className="preview-mobile" data-device="mobile">
						<i className="dashicons dashicons-smartphone"></i>
					</button>
				</li>
			</ul>;
		}
	}

	if (description) {
		descriptionHtml = <span className="description customize-control-description">{description}</span>;
	}

	if (responsiveFlag) {
		inputHtml = <>
		<div className ="ast-selector-responsive-wrap desktop">
			{ renderInputHtml('desktop', 'active') }
		</div>
		<div className ="ast-selector-responsive-wrap tablet">
			{renderInputHtml('tablet')}
		</div>
		<div className ="ast-selector-responsive-wrap mobile">
			{renderInputHtml('mobile')}
		</div>
		</>;
	} else {
		inputHtml = <>
			{renderInputHtml('desktop', 'active', false)}
		</>;
	}

	return <div>
		<label key={'customizer-text'} className="customizer-text"></label>
		{labelHtml}
		{responsiveHtml}
		{descriptionHtml}
		<div className="input-wrapper ast-alignment-wrapper">
			{inputHtml}
		</div>
	</div>;

};

SelectorComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( SelectorComponent );
