import PropTypes from 'prop-types';
import {Fragment} from '@wordpress/element';

const HeadingComponent = props => {

  let htmlCaption = null;
  let htmlLabel = null;
  let htmlDescription = null;

  if (props.control.params.caption) {
    htmlCaption = <span className="customize-control-caption">{props.control.params.caption}</span>;
  }

  if (props.control.params.label) {
    htmlLabel = <span className="customize-control-title wp-ui-text-highlight">{props.control.params.label}</span>;
  }

  if (props.control.params.description) {
    htmlDescription = <span className="description customize-control-description">{props.control.params.description}</span>;
  }

  return <Fragment>
				{htmlCaption}
				<div className="ast-heading-wrapper wp-ui-highlight">
					<label className="customizer-text">
						{htmlLabel}
						{htmlDescription}
					</label>
				</div>
			</Fragment>;
};

HeadingComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( HeadingComponent );
