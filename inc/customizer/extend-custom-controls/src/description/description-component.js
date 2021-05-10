import PropTypes from 'prop-types';
import ReactHtmlParser from 'react-html-parser';
import {Fragment} from '@wordpress/element';

const DescriptionComponent = props => {

	let htmlLabel = null;
	let htmlHelp = null;
	let htmlDescription = null;

	if (props.control.params.label) {
		htmlLabel = <span className="customize-control-title">{props.control.params.label}</span>;
	}

	if (props.control.params.help) {
		htmlHelp = <span className="ast-description">{ReactHtmlParser(props.control.params.help)}</span>;
	}

	if (props.control.params.description) {
		htmlDescription =
			<span className="description customize-control-description">{props.control.params.description}</span>;
	}

	return <Fragment>
		<label className="customizer-text">
			{htmlLabel}
			{htmlHelp}
			{htmlDescription}
		</label>
	</Fragment>;
};
DescriptionComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( DescriptionComponent );
