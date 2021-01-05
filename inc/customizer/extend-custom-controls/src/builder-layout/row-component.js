import DropComponent from './drop-component';

const {__} = wp.i18n;
const {Dashicon, Button} = wp.components;


const RowComponent = props => {

	let centerClass = 'no-center-items';
	let mode = props.controlParams.group.indexOf('header') !== -1 ? 'header' : 'footer';
	let besideItems = [];
	let layout = '';
	let zone_count = 0;
	let rowClass = '';
	let enableRow = true;

	if ('footer' === mode) {
		layout = `ast-grid-row-layout-${props.layout[props.row].layout.desktop}`;
		zone_count = props.layout[props.row].column - 1;
		Object.keys(props.controlParams.zones[props.row]).map((zone, index) => {
			if (zone_count < index) {
				props.items[zone] = [];
			}
		});
	}

	if ('astra-settings[header-desktop-items]' === props.controlParams.group && typeof props.items[props.row + '_center'] != "undefined" && props.items[props.row + '_center'] != null && props.items[props.row + '_center'].length != null && props.items[props.row + '_center'].length > 0) {
		centerClass = 'has-center-items';
	}

	if ('popup' === props.row) {
		centerClass = 'popup-vertical-group';
	}

	if (props.controlParams.hasOwnProperty('status')) {
		switch (props.row) {
			case 'above':
				if (!props.controlParams.status.above) {
					enableRow = false;
					rowClass = 'ahfb-grid-disabled';
				}

				break;

			case 'primary':
				if (!props.controlParams.status.primary) {
					enableRow = false;
					rowClass = 'ahfb-grid-disabled';
				}

				break;

			case 'below':
				if (!props.controlParams.status.below) {
					enableRow = false;
					rowClass = 'ahfb-grid-disabled';
				}

				break;
		}
	}

	return <div className={`ahfb-builder-areas ahfb-builder-mode-${mode} ${centerClass}`} data-row={props.row} data-row-section={'section-' + props.row + '-' + mode + '-builder'}>
		<Button className="ahfb-row-actions" title={ ( props.row === 'popup' ? __( 'Off Canvas', 'astra' ) : ( props.row + ' ' + mode ).charAt(0).toUpperCase() + ( props.row + ' ' + mode ).slice(1).toLowerCase() ) } onClick={() => props.focusPanel(props.row + '-' + mode)}>
			<Dashicon icon="admin-generic"/>
			{ props.row === 'popup' && 
				<>
					{ __('Off Canvas', 'astra') }
				</>
			}
		</Button>
		<div className={`ahfb-builder-group ahfb-builder-group-horizontal ${layout}`} data-setting={props.row}>
			{Object.keys(props.controlParams.zones[props.row]).map((zone, index) => {
				if ('footer' === mode && zone_count < index) {
					return;
				}

				if ((props.row + '_left_center' === zone || props.row + '_right_center' === zone) && 'footer' !== mode) {
					return;
				}

				if ('astra-settings[header-desktop-items]' === props.controlParams.group && props.row + '_left' === zone && 'footer' !== mode) {
					besideItems = props.items[props.row + '_left_center'];
				}

				if ('astra-settings[header-desktop-items]' === props.controlParams.group && props.row + '_right' === zone && 'footer' !== mode) {
					besideItems = props.items[props.row + '_right_center'];
				}

				return enableRow && <DropComponent
					removeItem={(remove, removeRow, removeZone) => props.removeItem(remove, removeRow, removeZone)}
					focusItem={focus => props.focusItem(focus)} hideDrop={() => props.hideDrop()}
					showDrop={() => props.showDrop()}
					onUpdate={(updateRow, updateZone, updateItems) => props.onUpdate(updateRow, updateZone, updateItems)}
					zone={zone} row={props.row} choices={props.choices} key={zone} items={props.items[zone]}
					centerItems={besideItems} controlParams={props.controlParams}
					onAddItem={(updateRow, updateZone, updateItems) => props.onAddItem(updateRow, updateZone, updateItems)}
					settings={props.settings} mode={mode}/>;
			})}
		</div>
	</div>;
};
export default RowComponent;
