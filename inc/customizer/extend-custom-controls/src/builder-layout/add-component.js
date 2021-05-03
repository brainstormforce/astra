import classnames from "classnames"
import {__} from '@wordpress/i18n';
import {useState} from 'react';

const {ButtonGroup, Dashicon, Popover, Button} = wp.components;
const {Fragment} = wp.element;

const AddComponent = props => {

	const [state, setState] = useState({
		isVisible: false,
	});

	const addItem = (item, row, column) => {

		const {
			setList,
			list
		} = props;

		setState(prevState => ({
			...prevState,
			isVisible: false
		}));

		let updateItems = list;
		updateItems.push({
			id: item
		});
		setList(updateItems);
	};

	const {
		controlParams,
		location,
		choices,
		row,
		column,
		id
	} = props;

	const renderItems = (item, row, column) => {
		let available = true;
		controlParams.rows.map(zone => {
			Object.keys(props.settings[zone]).map(area => {
				if (props.settings[zone][area].includes(item)) {
					available = false;
				}
			});
		});

		let itemIncludesMenu = item.includes( 'menu' );

		if ( 'popup' === row && ( ( itemIncludesMenu && 'mobile-menu' !== item ) || 'mobile-trigger' === item ) ) {
			available = false;
		}

		if ( 'popup' !== row && 'mobile-menu' === item ) {
			available = false;
		}

		return <Fragment key={item}>
			{available && <Button isTertiary className={'builder-add-btn'} onClick={() => {
				addItem(item, props.row, props.column);
			}}>
				<span className="add-btn-icon"> <Dashicon
					icon={undefined !== choices[item] && undefined !== choices[item].icon ? choices[item].icon : ''}/> </span>
				<span
					className="add-btn-title">{undefined !== choices[item] && undefined !== choices[item].name ? choices[item].name : ''}</span>
			</Button>}
		</Fragment>;
	};

	const toggleClose = () => {
		if (state.isVisible === true) {
			setState(prevState => ({
				...prevState,
				isVisible: false
			}));
		}
	};

	let droppedCount = 0,
		droppableCount = Object.keys(choices).length;

	if (state.isVisible) {
		controlParams.rows.map(zone => {
			Object.keys(props.settings[zone]).map(area => {
				if( 'astra-settings[header-desktop-items]' === controlParams.group && ! astra.customizer.is_pro && 'popup' === zone ) {
					/*
					 * Reducing Dropped & Droppable component count here because it fails in following case:
					 * When "Toggle for Desktop" option introduced in Astra, this component comes from addon but offcanvas panel comes from theme & already 'mobile-menu' component present in this panel.
					 * That's why the count fails here by 1.
					 */
					droppedCount = droppedCount - props.settings[zone][area].length;
					droppableCount = droppableCount - props.settings[zone][area].length;
				} else {
					droppedCount = droppedCount + props.settings[zone][area].length;
				}
			});
		});
	}

	return <div
		className={classnames('ahfb-builder-add-item', ('astra-settings[header-desktop-items]' === controlParams.group || 'astra-settings[footer-desktop-items]' === controlParams.group) && 'right' === location ? 'center-on-left' : null, ('astra-settings[header-desktop-items]' === controlParams.group || 'astra-settings[footer-desktop-items]' === controlParams.group) && 'left' === location ? 'center-on-right' : null, ('astra-settings[header-desktop-items]' === controlParams.group || 'astra-settings[footer-desktop-items]' === controlParams.group) && 'left_center' === location ? 'right-center-on-right' : null, ('astra-settings[header-desktop-items]' === controlParams.group || 'astra-settings[footer-desktop-items]' === controlParams.group) && 'right_center' === location ? 'left-center-on-left' : null)}
		key={id}>
		{state.isVisible && <Popover position="top" className="ahfb-popover-add-builder" onClose={toggleClose}>
			<div className="ahfb-popover-builder-list">
				<ButtonGroup className="ahfb-radio-container-control">
					{Object.keys(choices).sort().map(item => {
						return renderItems(item, row, column);
					})}
					{droppableCount === droppedCount &&
					<p className="ahfb-all-coponents-used"> {__('Hurray! All Components Are Being Used.', 'astra')} </p>}
				</ButtonGroup>
			</div>
		</Popover>}
		<Button className="ahfb-builder-item-add-icon dashicon dashicons-plus-alt2" onClick={() => {
			setState(prevState => ({
				...prevState,
				isVisible: true
			}));
		}}>
		</Button>
	</div>;
};

export default AddComponent;
