import PropTypes from 'prop-types';
import {__} from '@wordpress/i18n';
import {useState} from 'react';

const {Dashicon, Button} = wp.components;
const {Fragment} = wp.element;

const DraggableComponent = props => {

	let settings = {};
	let defaultParams = {};

	let controlParams = props.control.params.input_attrs ? {
		...defaultParams,
		...props.control.params.input_attrs,
	} : defaultParams;

	if (props.customizer.control(controlParams.group)) {
		settings = props.customizer.control(controlParams.group).setting.get();
	}

	let choices = (AstraBuilderCustomizerData && AstraBuilderCustomizerData.choices && AstraBuilderCustomizerData.choices[controlParams.group] ? AstraBuilderCustomizerData.choices[controlParams.group] : []);

	const [state, setState] = useState({
		settings: settings,
	});

	const linkRemovingItem = () => {
		document.addEventListener('AstraBuilderRemovedBuilderItem', function (e) {
			if (e.detail === controlParams.group) {
				onUpdate();
			}
		});
	};

	linkRemovingItem();

	const onUpdate = () => {
		if (props.customizer.control(controlParams.group)) {
			const settings = props.customizer.control(controlParams.group).setting.get();
			setState(prevState => ({
				...prevState,
				settings: settings
			}));
		}
	};

	const focusPanel = ( item ) => {
		if (props.customizer.section(choices[item].section)) {
			props.customizer.section(choices[item].section).focus();
		}
	};

	const renderItem = (item, row) => {
		let available = true;
		controlParams.zones.map(zone => {
			if ( state.settings[zone] ) {
				Object.keys(state.settings[zone]).map(area => {
					if (state.settings[zone][area].includes(item)) {
						available = false;
					}
				});
			}
		});

		return <Fragment key={item}>
			{!available && row === 'links' && <div className={'ahfb-builder-item-start'}>
				<Button className="ahfb-builder-item" data-id={item} onClick={() => focusPanel(item)}
						data-section={choices[item] && choices[item].section ? choices[item].section : ''} key={item}>
					{choices[item] && choices[item].name ? choices[item].name : ''}
					<span className="ahfb-builder-item-icon">
									<Dashicon icon="arrow-right-alt2"/>
								</span>
				</Button>
			</div>}
		</Fragment>;
	};

	let droppedCount = 0;
	controlParams.zones.map(zone => {

		if ( state.settings[zone] ) {

			Object.keys(state.settings[zone]).map(area => {
				droppedCount = droppedCount + state.settings[zone][area].length;
			});
		}
	});

	return <div className="ahfb-control-field ahfb-available-items">
		<div className="ast-builder-elements-section">
			{Object.keys(choices).map(item => {
				return renderItem(item, 'links');
			})}
			{!droppedCount &&
				<span className="ast-builder-elements-notice"> {__('Elements used in the builder will be visible here.', 'astra')} </span>
			}
		</div>
	</div>;

};

DraggableComponent.propTypes = {
	control: PropTypes.object.isRequired,
	customizer: PropTypes.func.isRequired
};

export default React.memo( DraggableComponent );
