import {useState} from 'react';

const {__} = wp.i18n;
const {Dashicon, Tooltip, TextControl, Button } = wp.components;
import FontIconPicker from "@fonticonpicker/react-fonticonpicker"
import astIcons from "../../../../../assets/svg/ast-social-icons"
import renderSVG from "../../../../assets/js/ast-render-svg"


let svg_icons = Object.keys( astIcons )

const ItemComponent = props => {

	const Icons = window.svgIcons;

	const [state, setState] = useState({
		open: false,
	});

	const icon = props.item.id.replace(/[\d_]+$/g, ''); // Regex to replace numeric chars with empty string.
	const urlLabel = ( 'phone' === props.item.id || 'phone_2' === props.item.id ) ? __('Number', 'astra') : __('URL', 'astra');

	return <div className="ahfb-sorter-item" data-id={props.item.id} key={props.item.id}>
		<div className="ahfb-sorter-item-panel-header" onClick={() => {
			setState((prevState => ({
				...prevState,
				open: state.open ? false : true
			})))
		}}>
			<Tooltip text={__('Toggle Item Visiblity', 'astra')}>
				<Button className="ahfb-sorter-visiblity">
							<span dangerouslySetInnerHTML={{
								__html: Icons[icon]
							}}/>
				</Button>
			</Tooltip>
			<span className="ahfb-sorter-title">
						{undefined !== props.item.label && '' !== props.item.label ? props.item.label : __('Social Item', 'astra')}
					</span>
			<Button className={`ahfb-sorter-item-expand ${props.item.enabled ? 'item-is-visible' : 'item-is-hidden'}`}
					onClick={e => {
						e.stopPropagation();
						props.toggleEnabled(props.item.enabled ? false : true, props.index);
					}}>
				<Dashicon icon="visibility"/>
			</Button>
			<Button className="ahfb-sorter-item-remove" isDestructive onClick={() => {
				props.removeItem(props.index);
			}}>
				<Dashicon icon="no-alt"/>
			</Button>
		</div>
		{ state.open && <div className="ahfb-sorter-item-panel-content">
			<TextControl label={__('Label', 'astra')} value={props.item.label ? props.item.label : ''}
						 onChange={value => {
							 props.onChangeLabel(value, props.index);
						 }}/>

			<TextControl label={`${urlLabel}`} value={props.item.url ? props.item.url : ''} onChange={value => {
				props.onChangeURL(value, props.index);
			}}/>
			<p className="ast-social-icon-picker-label">{ __( "Icon" ) }</p>
			<FontIconPicker
				icons={svg_icons}
				renderFunc= {renderSVG}
				theme="default"
				value={props.item.icon}
				onChange={ value => { props.onChangeIcon(value, props.index); } }
				isMulti={false}
				noSelectedPlaceholder= { __( "Select Icon" ) }
			/>
		</div>}
	</div>;
};
export default ItemComponent;
