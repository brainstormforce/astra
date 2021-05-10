import PropTypes from 'prop-types';
import {Fragment} from '@wordpress/element';
import {useState} from 'react';
import {ToggleControl} from '@wordpress/components';

const AstToggleControl = props => {

  let htmlTitle = null;

  const [props_value, setPropsValue] = useState(props.control.setting.get());

  if (props.control.params.title) {
    htmlTitle = <span className="toggle-control-label">{props.control.params.title}</span>;
  }

  const updateValues = () => {
		setPropsValue( ! props_value );
		props.control.setting.set( ! props_value );
	};

  return <Fragment>
				<div className="ast-togglecontrol-wrapper">
            <ToggleControl
            label={htmlTitle}
            checked={props_value}
            onChange={() => updateValues()}
            />
        </div>
			</Fragment>;
};

AstToggleControl.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( AstToggleControl );
