import PropTypes from 'prop-types';
import RowComponent from './row-component';
import {useEffect, useState} from 'react';

const BuilderComponent = props => {

	let value = props.control.setting.get();
	let staleValue = {};

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


	const component_track = props.customizer.control('astra-settings[cloned-component-track]').setting;
	const prevItems = [];
	prevItems['revertDrag'] = false;

	const [state, setState] = useState({
		value: value,
		layout: controlParams.layouts,
		isPopup: false,
		prevItems: prevItems
	});

	let contFlag = false;

	if ( props.control.container ) {
		contFlag = props.control.container[0].getAttribute( 'isPopup' );
	}
	if ("astra-settings[header-desktop-items]" === controlParams.group || "astra-settings[header-mobile-items]" === controlParams.group) {
		staleValue = JSON.parse( JSON.stringify(state.value) )
	}

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

		if ( state.prevItems.revertDrag ) {
			let row = state.prevItems.row;
			let zone = state.prevItems.zone;
			let restrictRow = state.prevItems.restrictRow;
			let restrictZone = state.prevItems.restrictZone;
			let updateState = state.value;
			let update = updateState[row];
			let updateItems = state.prevItems.staleValue[row][zone];
			let popupRemoveUpdate = updateState[restrictRow];
			let popupRemoveUpdateItems = state.prevItems.staleValue[restrictRow][restrictZone];

			update[zone] = updateItems;
			popupRemoveUpdate[restrictZone] = popupRemoveUpdateItems;
			updateState[row][zone] = updateItems;
			updateState[restrictRow][restrictZone] = popupRemoveUpdateItems;

			setPopupFlag(true);

			setState(prevState => ({
				...prevState,
				value: updateState
			}));

			updateValues(updateState, row );

			let prevItems = [];
			prevItems['revertDrag'] = false;

			setState(prevState => ({
				...prevState,
				prevItems: prevItems
			}));
		}

		let dropzones = document.querySelectorAll('.ahfb-builder-area');
		for (let i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.remove('ahfb-dragging-dropzones');
		}
	};

	const isCloneEnabled = () => {

		if( ! astra.customizer.is_pro ) {
			return;
		}

		let component_count = component_track.get();

		AstraBuilderCustomizerData.component_limit = parseInt(AstraBuilderCustomizerData.component_limit);

		const customizerBuilderchoices = (AstraBuilderCustomizerData && AstraBuilderCustomizerData.choices && AstraBuilderCustomizerData.choices[controlParams.group] ? AstraBuilderCustomizerData.choices[controlParams.group] : []);

		Object.keys(customizerBuilderchoices).forEach(function( choice) {

			let builderDataChoice = customizerBuilderchoices[choice];

			if( builderDataChoice.hasOwnProperty('builder') && builderDataChoice.hasOwnProperty('type')   ) {

				let isToClone = builderDataChoice.hasOwnProperty('clone') ? builderDataChoice['clone']: true;
				let isToDelete = builderDataChoice.hasOwnProperty('delete') ? builderDataChoice['delete']: true;

				let builderComponentType = builderDataChoice['builder'] + '-' + builderDataChoice['type'];

				if( component_count[builderComponentType] < AstraBuilderCustomizerData.component_limit ) {
					isToClone = true;
				}  else {
					let componentSection = builderDataChoice.section.replace(/[0-9]+/g, ''); // Replace random numeric with empty string.
					let isCloned =  component_count['removed-items'].findIndex((item) => { return item.startsWith(componentSection);} );
					isToClone = isCloned !== -1;
				}

				builderDataChoice['clone'] = isToClone;

				switch (component_count[builderComponentType]) {
					case 1:
						isToDelete = false;
						break;
					case 2:
						isToDelete = ( component_count['removed-items'].indexOf( builderDataChoice.section.replace(/[0-9]+/g, 1 ) ) != -1 ) ? false : true;
						break;
				}

				builderDataChoice['delete'] = isToDelete;
			}

		});

	}

	const prepareElement = function (cloneData, cloneIndex) {

		switch ( cloneData.type ) {

			case 'menu':
				switch (cloneIndex) {
					case 1:
						cloneData.name = 'Primary Menu';
						break;
					case 2:
						cloneData.name = 'Secondary Menu';
						break;
					default:
						cloneData.name = cloneData.type.slice(0, 1).toUpperCase() + cloneData.type.substring(1) + " " + cloneIndex;
						break;
				}
				break;

			default:
				let name = cloneData.name.replace(/[0-9]+/g, ''); // Replace random numeric with empty string.
				cloneData.name = name + ' ' + cloneIndex;
				break;

		}

		cloneData.section = cloneData.section.replace(/[0-9]+/g, cloneIndex); // Replace random numeric with valid clone index.

		return cloneData;
	}

	const cloneItem = ( item, row, zone ) => {

		// Skip clone if already is in progress.
		if( sessionStorage.getItem('astra-builder-clone-in-progress') ) {
			return;
		}

		let component_count = component_track.get(),
			cloneData = Object.assign({},choices[item] ),
			cloneSection = cloneData.section.replace(/[0-9]+/g, ''), // Remove random numeric with empty string.
			cloneIndex,
			removedBuilderItems = component_count['removed-items'],
			cloneSection_index = removedBuilderItems.findIndex(element => element.includes(cloneSection)),
			componentType = cloneData.builder + '-' + cloneData.type;

		let updated_count = {};

		if( cloneSection_index != -1 ) {
			cloneSection = removedBuilderItems[cloneSection_index];
			cloneIndex = parseInt( cloneSection.match(/\d+$/)[0] );
			removedBuilderItems.splice(cloneSection_index, 1);
			updated_count['removed-items'] = removedBuilderItems;

		} else {
			cloneIndex = component_count[ componentType ] + 1;
			cloneSection = cloneData.section.replace(/[0-9]+/g, cloneIndex); // Replace random numeric with valid clone index.
			updated_count[ componentType ] = cloneIndex;
		}

		// Return if limit exceeds.
		if( parseInt(cloneIndex ) > parseInt( AstraBuilderCustomizerData.component_limit ) ) {
			return;
		}

		let cloneTypeId = cloneData.type + '-' + cloneIndex;

		cloneData = prepareElement(cloneData, cloneIndex);

		AstraBuilderCustomizerData.choices[controlParams.group][ cloneTypeId ] = cloneData;

		sessionStorage.setItem('astra-builder-clone-in-progress', true);

		var event = new CustomEvent('AstraBuilderCloneSectionControls', {
			'detail': {
				'clone_to_section': cloneSection,
				'clone_from_section' : choices[item]['section']
			}
		});
		document.dispatchEvent(event);

		component_track.set( { ...component_count, ...updated_count, flag: ! component_count.flag } );

		let updateState = state.value;
		let update = updateState[row];
		let items = update[zone];
		items.push( cloneTypeId );
		let updateItems = [];
		items.forEach(function(item) {
			updateItems.push({
				id: item
			});
		});


		setState(prevState => ({
			...prevState,
			value: updateState
		}));

		updateValues(updateState, row);

	}

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

		setPopupFlag(true);

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

	const setPreviousItems = ( item, restrictRow, restrictZone ) => {

		let prevItems = [];

		prevItems['restrictRow'] = restrictRow;
		prevItems['restrictZone'] = restrictZone;

		for ( const [rowKey, value] of Object.entries(staleValue) ) {
						
			for ( const [zoneKey, zoneValue] of Object.entries(value) ) {
				
				for( let zoneItem of zoneValue ) {
					
					if ( zoneItem === item.id ) {
						prevItems['row'] = rowKey;
						prevItems['zone'] = zoneKey;
						prevItems['revertDrag'] = true;
						prevItems['staleValue'] = staleValue;
					}
				}
			}
		}

		return prevItems;
	}

	const onDragEnd = (row, zone, items) => {

		let updateState = state.value;
		let update = updateState[row];
		let updateItems = [];
		let itemIncludesMenu = false;
		let prevItems = [];

		{
			items.length > 0 && items.map(item => {

				itemIncludesMenu = item.id.includes( 'menu' );

				if ( ( 'popup' === row && ( ( "astra-settings[header-desktop-items]" === controlParams.group && itemIncludesMenu && 'mobile-menu' !== item.id ) || 'mobile-trigger' === item.id ) ) || 'popup' !== row && 'mobile-menu' === item.id ) {


					prevItems = setPreviousItems( item, row, zone );

					setState(prevState => ({
						...prevState,
						prevItems: prevItems
					}));
				} 
				
				updateItems.push(item.id);
			});
		}
		;


		if (!arraysEqual(update[zone], updateItems) ) {

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

			setPopupFlag(true);
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

	const setPopupFlag = (refresh) => {

		let is_popup_flag = false;

		if ( 'astra-settings[header-desktop-items]' === props.control.id ) {
			controlParams.rows.map(row => {
				var rowContents = state.value[row];

				for ( const [key, value] of Object.entries(rowContents) ) {

					if( value.includes('mobile-trigger') ) {
						is_popup_flag = true;
						return;
					}
				}
			});
		}
		if ( 'astra-settings[header-mobile-items]' === props.control.id ) {
			controlParams.rows.map(row => {
				var rowContents = state.value[row];

				for ( const [key, value] of Object.entries(rowContents) ) {

					if( value.includes('mobile-trigger') ) {
						is_popup_flag = true;
						return;
					}
				}
			});
		}

		if ( refresh ) {
			setState(prevState => ({
				...prevState,
				isPopup: is_popup_flag
			}));
		}

		if ( props.control.container ) {
			props.control.container[0].setAttribute( 'isPopup', is_popup_flag );
			contFlag = is_popup_flag;
		}

	}

	setPopupFlag(false);
	isCloneEnabled();

	return <div className="ahfb-control-field ahfb-builder-items">
		{ ( true === state.isPopup || true === contFlag ) && controlParams.rows.includes('popup') &&
		<RowComponent showDrop={() => onDragStart()} focusPanel={item => focusPanel(item)}
					  focusItem={item => focusItem(item)}
					  removeItem={(remove, row, zone) => removeItem(remove, row, zone)}
					  cloneItem={(remove, row, zone) => cloneItem(remove, row, zone)}
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
									 cloneItem={(remove, row, zone) => cloneItem(remove, row, zone)}
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
