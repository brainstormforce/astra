import PropTypes from 'prop-types';
import RowComponent from './row-component';
import {useEffect, useState} from 'react';

const BuilderComponent = props => {

	let value = props.control.setting.get();

	let baseDefault = {};
	let defaultValue = props.control.params.default ? {
		...baseDefault,
		...props.control.params.default
	} : baseDefault;

	value = value ? {
		...defaultValue,
		...value
	} : defaultValue;

	let defaultParams = {};

	let controlParams = props.control.params.input_attrs ? {
		...defaultParams,
		...props.control.params.input_attrs,
	} : defaultParams;

	let choices = (AstraBuilderCustomizerData && AstraBuilderCustomizerData.choices && AstraBuilderCustomizerData.choices[controlParams.group] ? AstraBuilderCustomizerData.choices[controlParams.group] : []);

	const [state, setState] = useState({
		value: value,
		layout: controlParams.layouts
	});

	const updateValues = (value, row = '') => {

		let setting = props.control.setting;

		// If popup updated, partial refresh contents.
		if ( 'popup' === row  ) {
			let popup_control = props.customizer('astra-settings[header-mobile-popup-items]');
			popup_control.set( ! popup_control.get() );
		}

		setting.set( { ...setting.get(), ...value, flag: !setting.get().flag } );

	};

	const updateRowLayout = () => {

		document.addEventListener('AstraBuilderChangeRowLayout', function (e) {

			if ("astra-settings[footer-desktop-items]" !== controlParams.group) {
				return;
			}

			if ('' === e.detail.type) {
				return;
			}

			let newParams = controlParams;

			if (newParams.layouts[e.detail.type]) {
				newParams.layouts[e.detail.type] = {
					'column': e.detail.columns,
					'layout': e.detail.layout
				};

				setState(prevState => ({
					...prevState,
					layout: newParams.layouts
				}));

				updateValues(newParams);
			}
		});
	};

	const updatePresetSettings = () => {
		document.addEventListener('AstraBuilderPresetSettingsUpdate', function (event) {
			// Load Only Context Area.
			if (controlParams.group === event.detail.id) {
				setState(prevState => ({
					...prevState,
					value: event.detail.grid_layout
				}));
				updateValues(event.detail.grid_layout);
			}
		});
	};

	useEffect( () => {
		updatePresetSettings();
		updateRowLayout();
	}, []);

	const onDragStart = () => {
		let dropzones = document.querySelectorAll('.ahfb-builder-area');
		for ( let i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.add('ahfb-dragging-dropzones');
		}
	};

	const onDragStop = () => {
		let dropzones = document.querySelectorAll('.ahfb-builder-area');
		for (let i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.remove('ahfb-dragging-dropzones');
		}
	};

	const removeItem = (item, row, zone) => {
		let updateState = state.value;
		let update = updateState[row];
		let updateItems = [];
		{
			update[zone].length > 0 && update[zone].map(old => {
				if (item !== old) {
					updateItems.push(old);
				}
			});
		}

		if ('astra-settings[header-desktop-items]' === controlParams.group && row + '_center' === zone && updateItems.length === 0) {
			if (update[row + '_left_center'].length > 0) {
				update[row + '_left_center'].map(move => {
					updateState[row][row + '_left'].push(move);
				});
				updateState[row][row + '_left_center'] = [];
			}

			if (update[row + '_right_center'].length > 0) {
				update[row + '_right_center'].map(move => {
					updateState[row][row + '_right'].push(move);
				});
				updateState[row][row + '_right_center'] = [];
			}
		}

		update[zone] = updateItems;
		updateState[row][zone] = updateItems;

		setState(prevState => ({
			...prevState,
			value: updateState
		}));

		updateValues(updateState, row);
		let event = new CustomEvent('AstraBuilderRemovedBuilderItem', {
			'detail': controlParams.group
		});
		document.dispatchEvent(event);
	};

	const onDragEnd = (row, zone, items) => {
		let updateState = state.value;
		let update = updateState[row];
		let updateItems = [];
		{
			items.length > 0 && items.map(item => {
				updateItems.push(item.id);
			});
		}
		;

		if (!arraysEqual(update[zone], updateItems)) {
			if ('astra-settings[header-desktop-items]' === controlParams.group && row + '_center' === zone && updateItems.length === 0) {
				if (update[row + '_left_center'].length > 0) {
					update[row + '_left_center'].map(move => {
						updateState[row][row + '_left'].push(move);
					});
					updateState[row][row + '_left_center'] = [];
				}

				if (update[row + '_right_center'].length > 0) {
					update[row + '_right_center'].map(move => {
						updateState[row][row + '_right'].push(move);
					});
					updateState[row][row + '_right_center'] = [];
				}
			}

			update[zone] = updateItems;
			updateState[row][zone] = updateItems;
			setState(prevState => ({
				...prevState,
				value: updateState
			}));
			updateValues(updateState, row);
		}
	};

	const onAddItem = (row, zone, items) => {
		onDragEnd(row, zone, items);
		let event = new CustomEvent('AstraBuilderRemovedBuilderItem', {
			'detail': controlParams.group
		});
		document.dispatchEvent(event);
	};

	const arraysEqual = (a, b) => {
		if (a === b) return true;
		if (a == null || b == null) return false;
		if (a.length != b.length) return false;

		for (let i = 0; i < a.length; ++i) {
			if (a[i] !== b[i]) return false;
		}

		return true;
	};

	const focusPanel = (item) => {
		item = 'section-' + item + '-builder';

		if (undefined !== props.customizer.section(item)) {
			props.customizer.section(item).focus();
		}
	};

	const focusItem = (item) => {
		if (undefined !== props.customizer.section(item)) {
			props.customizer.section(item).focus();
		}
	};

	return <div className="ahfb-control-field ahfb-builder-items">
		{controlParams.rows.includes('popup') &&
		<RowComponent showDrop={() => onDragStart()} focusPanel={item => focusPanel(item)}
					  focusItem={item => focusItem(item)}
					  removeItem={(remove, row, zone) => removeItem(remove, row, zone)}
					  onAddItem={(updateRow, updateZone, updateItems) => onAddItem(updateRow, updateZone, updateItems)}
					  hideDrop={() => onDragStop()}
					  onUpdate={(updateRow, updateZone, updateItems) => onDragEnd(updateRow, updateZone, updateItems)}
					  key={'popup'} row={'popup'} controlParams={controlParams} choices={choices}
					  items={state.value['popup']} settings={state.value} layout={state.layout}/>}
		<div className="ahfb-builder-row-items">
			{controlParams.rows.map(row => {
				if ('popup' === row) {
					return;
				}

				return <RowComponent showDrop={() => onDragStart()} focusPanel={item => focusPanel(item)}
									 focusItem={item => focusItem(item)}
									 removeItem={(remove, row, zone) => removeItem(remove, row, zone)}
									 hideDrop={() => onDragStop()}
									 onUpdate={(updateRow, updateZone, updateItems) => onDragEnd(updateRow, updateZone, updateItems)}
									 onAddItem={(updateRow, updateZone, updateItems) => onAddItem(updateRow, updateZone, updateItems)}
									 key={row} row={row} controlParams={controlParams} choices={choices}
									 customizer={props.customizer} items={state.value[row]} settings={state.value}
									 layout={state.layout}/>;
			})}
		</div>
	</div>;

};

BuilderComponent.propTypes = {
	control: PropTypes.object.isRequired,
	customizer: PropTypes.func.isRequired
};

export default React.memo( BuilderComponent ) ;
