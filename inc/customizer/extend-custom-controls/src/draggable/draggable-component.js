import PropTypes from 'prop-types';
import {ReactSortable} from "react-sortablejs";
import {useState} from 'react';

const {Dashicon, Button} = wp.components;
const {Fragment} = wp.element;



const DraggableComponent = props => {

	const Icons = window.svgIcons;

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

	const onDragStart = () => {
		let dropzones = document.querySelectorAll('.ahfb-builder-area');
		let i;

		for (i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.add('ahfb-dragging-dropzones');
		}
	};

	const onDragStop = () => {
		let dropzones = document.querySelectorAll('.ahfb-builder-area');
		let i;

		for (i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.remove('ahfb-dragging-dropzones');
		}
	};

	const focusPanel = ( item ) => {
		if (props.customizer.section(choices[item].section)) {
			props.customizer.section(choices[item].section).focus();
		}
	};

	const onDragEnd = ( items ) => {
		if (items.length != null && items.length === 0) {
			onUpdate();
		}
	};

	const renderItem = (item, row) => {
		let available = true;
		controlParams.zones.map(zone => {
			Object.keys(state.settings[zone]).map(area => {
				if (state.settings[zone][area].includes(item)) {
					available = false;
				}
			});
		});
		let theitem = [{
			id: item
		}];

		return <Fragment key={item}>
			{available && row === 'available' &&
			<ReactSortable animation={10} onStart={() => onDragStart()} onEnd={() => onDragStop()} group={{
				name: controlParams.group,
				put: false
			}} className={'ahfb-builder-item-start ahfb-move-item'} list={theitem}
						   setList={newState => onDragEnd(newState)}>
				<div className="ahfb-builder-item" data-id={item}
					 data-section={choices[item] && choices[item].section ? choices[item].section : ''} key={item}>
								<span className="ahfb-builder-item-icon ahfb-move-icon">
									dangerouslySetInnerHTML={{ __html: Icons['drag'] }}
								</span>
					{choices[item] && choices[item].name ? choices[item].name : ''}
				</div>
			</ReactSortable>}
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

	return <div className="ahfb-control-field ahfb-available-items">
		<div className="ahfb-available-items-pool-">
			{Object.keys(choices).map(item => {
				return renderItem(item, 'links');
			})}
		</div>
	</div>;

};

DraggableComponent.propTypes = {
	control: PropTypes.object.isRequired,
	customizer: PropTypes.func.isRequired
};

export default React.memo( DraggableComponent );
