import {ReactSortable} from "react-sortablejs";
import ItemComponent from './item-component';
import AddComponent from './add-component';

const {Fragment} = wp.element;

const DropComponent = props => {

	const location = props.zone.replace(props.row + '_', '');
	const currentList = typeof props.items != "undefined" && props.items != null && props.items.length != null && props.items.length > 0 ? props.items : [];
	let choices = props.choices;
	let theItems = [];
	{
		currentList.length > 0 && currentList.map((item, key) => {
			if (Object.keys(choices).includes(item)) {
				theItems.push({
					id: item
				});
			} else {
				currentList.splice(key, 1);
			}
		});
	}
	const currentCenterList = typeof props.centerItems != "undefined" && props.centerItems != null && props.centerItems.length != null && props.centerItems.length > 0 ? props.centerItems : [];
	let theCenterItems = [];
	{
		currentCenterList.length > 0 && currentCenterList.map((item, key) => {
			if (Object.keys(choices).includes(item)) {
				theCenterItems.push({
					id: item
				});
			} else {
				currentCenterList.splice(key, 1);
			}
		});
	}

	const sortableGroup = (items, lists, loc) => {
		let add_id_loc = loc.replace("_", "-");
		return <Fragment>
			<ReactSortable animation={100} onStart={() => props.showDrop()} onEnd={() => props.hideDrop()}
						   group={props.controlParams.group}
						   className={'ahfb-builder-drop ahfb-builder-sortable-panel ahfb-builder-drop-' + location + loc}
						   list={items} setList={newState => props.onUpdate(props.row, props.zone + loc, newState)}>
				{lists.length > 0 && lists.map((item, index) => {
					return <ItemComponent removeItem={remove => props.removeItem(remove, props.row, props.zone + loc)}
										  focusItem={focus => props.focusItem(focus)} key={item} index={index}
										  item={item} controlParams={props.controlParams}/>;
				})}
			</ReactSortable>
			<AddComponent row={props.row} list={items} settings={props.settings} column={props.zone + loc}
						  setList={newState => props.onAddItem(props.row, props.zone + loc, newState)} key={location}
						  location={location + loc} id={'add' + add_id_loc + '-' + location}
						  controlParams={props.controlParams} choices={props.choices}/>
		</Fragment>;
	};

	if ('footer' === props.mode) {
		return <div className={`ahfb-builder-area ahfb-builder-area-${location}`} data-location={props.zone}>
			{sortableGroup(theItems, currentList, '')}
		</div>;
	}

	return <div className={`ahfb-builder-area ahfb-builder-area-${location}`} data-location={props.zone}>
		{'astra-settings[header-desktop-items]' === props.controlParams.group && 'right' === location && sortableGroup(theCenterItems, currentCenterList, '_center')}
		{sortableGroup(theItems, currentList, '')}
		{'astra-settings[header-desktop-items]' === props.controlParams.group && 'left' === location && sortableGroup(theCenterItems, currentCenterList, '_center')}
	</div>;
};

export default DropComponent;
