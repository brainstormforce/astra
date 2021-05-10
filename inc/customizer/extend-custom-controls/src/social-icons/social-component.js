import PropTypes from 'prop-types';
import {ReactSortable} from "react-sortablejs";
import ItemComponent from './item-component';
import {useEffect, useState} from 'react';

const {__} = wp.i18n;
const { Button, SelectControl} = wp.components;

const SocialComponent = props => {

	let value = props.control.setting.get();
	let baseDefault = {
		'items': [
			{
				'id': 'facebook',
				'enabled': true,
				'url': '',
				'color': '#557dbc',
				'background': 'transparent',
				'icon': 'facebook',
				'label': 'Facebook',
			},
			{
				'id': 'twitter',
				'enabled': true,
				'url': '',
				'color': '#7acdee',
				'background': 'transparent',
				'icon': 'twitter',
				'label': 'Twitter',
			}
		],
	};
	let defaultValue = props.control.params.default ? {
		...baseDefault,
		...props.control.params.default
	} : baseDefault;

	value = value ? {
		...defaultValue,
		...value
	} : defaultValue;

	let defaultParams = {
		'group': 'social_item_group',
		'options': [
			{value: 'facebook', label: __('Facebook', 'astra'), color: '#557dbc', background: 'transparent'},
			{value: 'twitter', label: __('Twitter', 'astra'), color: '#7acdee', background: 'transparent'},
			{value: 'instagram', label: __('Instagram', 'astra'), color: '#8a3ab9', background: 'transparent'},
			{value: 'youtube', label: __('YouTube', 'astra'), color: '#e96651', background: 'transparent'},
			{value: 'facebook_group', label: __('Facebook Group', 'astra'), color: '#3D87FB', background: 'transparent'},
			{value: 'vimeo', label: __('Vimeo', 'astra'), color: '#8ecfde', background: 'transparent'},
			{value: 'pinterest', label: __('Pinterest', 'astra'), color: '#ea575a', background: 'transparent'},
			{value: 'linkedin', label: __('Linkedin', 'astra'), color: '#1c86c6', background: 'transparent'},
			{value: 'medium', label: __('Medium', 'astra'), color: '#292929', background: 'transparent'},
			{value: 'wordpress', label: __('WordPress', 'astra'), color: '#464646', background: 'transparent'},
			{value: 'reddit', label: __('Reddit', 'astra'), color: '#FC471E', background: 'transparent'},
			{value: 'patreon', label: __('Patreon', 'astra'), color: '#e65c4b', background: 'transparent'},
			{value: 'github', label: __('GitHub', 'astra'), color: '#24292e', background: 'transparent'},
			{value: 'dribbble', label: __('Dribbble', 'astra'), color: '#d77ea6', background: 'transparent'},
			{value: 'behance', label: __('Behance', 'astra'), color: '#1B64F6', background: 'transparent'},
			{value: 'vk', label: __('VK', 'astra'), color: '#5382b6', background: 'transparent'},
			{value: 'xing', label: __('Xing', 'astra'), color: '#0A5C5D', background: 'transparent'},
			{value: 'rss', label: __('RSS', 'astra'), color: '#f09124', background: 'transparent'},
			{value: 'email', label: __('Email 1', 'astra'), color: '#ea4335', background: 'transparent'},
			{value: 'phone', label: __('Phone 1', 'astra'), color: 'inherit', background: 'transparent'},
			{value: 'email_2', label: __('Email 2', 'astra'), color: '#ea4335', background: 'transparent'},
			{value: 'phone_2', label: __('Phone 2', 'astra'), color: 'inherit', background: 'transparent'},
			{value: 'whatsapp', label: __('WhatsApp', 'astra'), color: '#5BBA67', background: 'transparent'},
			{value: 'google_reviews', label: __('Google Reviews', 'astra'), color: '#dc4e41', background: 'transparent'},
			{value: 'telegram', label: __('Telegram', 'astra'), color: '#229CCE', background: 'transparent'},
			{value: 'yelp', label: __('Yelp', 'astra'), color: '#af0606', background: 'transparent'},
			{value: 'trip_advisor', label: __('Trip Advisor', 'astra'), color: '#00aa6c', background: 'transparent'},
			{value: 'imdb', label: __('IMDB', 'astra'), color: '#000000', background: 'transparent'},
		].sort((a, b) => {
			if (a.value < b.value) {
				return -1;
			}
			if (a.value > b.value) {
				return 1;
			}
			return 0;
		})
	};

	let controlParams = props.control.params.input_attrs ? {
		...defaultParams,
		...props.control.params.input_attrs,
	} : defaultParams;

	let availibleSocialOptions = [];
	controlParams.options.map((option) => {
		if (!value.items.some(obj => obj.id === option.value)) {
			availibleSocialOptions.push(option);
		}
	});

	const [state, setState] = useState({
		value: value,
		isVisible: false,
		control: (undefined !== availibleSocialOptions[0] && undefined !== availibleSocialOptions[0].value ? availibleSocialOptions[0].value : ''),
		icon : ''
	});

	useEffect( () => {
		// If settings are changed externally.
		setState(prevState => ({
			...prevState,
			value: props.control.setting.get()
		}));


	}, [props]);

	const updateValues = (value) => {
		props.control.setting.set({
			...props.control.setting.get(),
			...value,
			flag: !props.control.setting.get().flag
		});
	};

	const onDragStop = () => {
		let dropzones = document.querySelectorAll('.ahfb-builder-area');
		let i;

		for (i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.remove('ahfb-dragging-dropzones');
		}
	};

	const saveArrayUpdate = (value, index) => {
		let updateState = state.value;
		let items = updateState.items;
		const newItems = items.map((item, thisIndex) => {

			if (index === thisIndex) {
				item = {
					...item,
					...value
				};
			}
			return item;
		});

		updateState.items = newItems;
		setState(prevState => ({
			...prevState,
			value: updateState
		}));
		updateValues(updateState);
	};

	const toggleEnableItem = (value, itemIndex) => {
		saveArrayUpdate({
			enabled: value
		}, itemIndex);
	};

	const onChangeLabel = (value, itemIndex) => {
		saveArrayUpdate({
			label: value
		}, itemIndex);
	};

	const onChangeURL = (value, itemIndex) => {
		saveArrayUpdate({
			url: value
		}, itemIndex);
	};

	const removeItem = (itemIndex) => {

		let updateState = state.value;
		let update = updateState.items;
		let updateItems = [];
		{
			update.length > 0 && update.map((old, index) => {
				if (itemIndex !== index) {
					updateItems.push(old);
				}
			});
		}
		updateState.items = updateItems;
		setState(prevState => ({
			...prevState,
			value: updateState
		}));
		updateValues(updateState);
	};

	const addItem = () => {
		const itemControl = state.control;
		setState(prevState => ({
			...prevState,
			isVisible: false
		}));

		if (itemControl) {
			let updateState = state.value;
			let update = updateState.items;
			let icon = itemControl.replace(/[\d_]+$/g, ''); // Regex to replace numeric chars with empty string.

			const itemLabel = controlParams.options.filter(function (o) {
				return o.value === itemControl;
			});
			let newItem = {
				'id': itemControl,
				'enabled': true,
				'url': '',
				'color': itemLabel[0].color,
				'background': itemLabel[0].background,
				'icon': icon,
				'label': itemLabel[0].label
			};
			update.push(newItem);
			updateState.items = update;
			let availibleSocialOptions = [];
			controlParams.options.map(option => {
				if (!update.some(obj => obj.id === option.value)) {
					availibleSocialOptions.push(option);
				}
			});

			setState(prevState => ({
				...prevState,
				control: (undefined !== availibleSocialOptions[0] && undefined !== availibleSocialOptions[0].value ? availibleSocialOptions[0].value : '')
			}));
			setState(prevState => ({
				...prevState,
				value: updateState
			}));

			updateValues(updateState);
		}
	};

	const onDragEnd = (items) => {
		let updateState = state.value;
		let update = updateState.items;
		let updateItems = [];
		{
			items.length > 0 && items.map(item => {
				update.filter(obj => {
					if (obj.id === item.id) {
						updateItems.push(obj);
					}
				});
			});
		}
		;

		if (!arraysEqual(update, updateItems)) {
			update.items = updateItems;
			updateState.items = updateItems;
			setState(prevState => ({
				...prevState,
				value: updateState
			}));
			updateValues(updateState);
		}
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

	const currentList = typeof state.value != "undefined" && state.value.items != null && state.value.items.length != null && state.value.items.length > 0 ? state.value.items : [];
	let theItems = [];
	{
		currentList.length > 0 && currentList.map(item => {
			theItems.push({
				id: item.id
			});
		});
	}
	;


	const onChangeIcon = ( icon, itemIndex ) => {
		saveArrayUpdate({
			icon: icon
		}, itemIndex);
	};

	return <div className="ahfb-control-field ahfb-sorter-items">
		<div className="ahfb-sorter-row">
			<ReactSortable animation={100} onStart={() => onDragStop()} onEnd={() => onDragStop()}
						   group={controlParams.group}
						   className={`ahfb-sorter-drop ahfb-sorter-sortable-panel ahfb-sorter-drop-${controlParams.group}`}
						   handle={'.ahfb-sorter-item-panel-header'} list={theItems}
						   setList={newState => onDragEnd(newState)}>
				{currentList.length > 0 && currentList.map((item, index) => {
					return <ItemComponent removeItem={remove => removeItem(remove)}
										  toggleEnabled={(enable, itemIndex) => toggleEnableItem(enable, itemIndex)}
										  onChangeLabel={(label, itemIndex) => onChangeLabel(label, itemIndex)}
										  onChangeIcon={( icon, index ) => onChangeIcon( icon, index ) }
										  onChangeURL={(url, itemIndex) => onChangeURL(url, itemIndex)}
										  key={item.id} index={index} item={item} controlParams={controlParams}/>;

				})}
			</ReactSortable>
		</div>
		{undefined !== availibleSocialOptions[0] && undefined !== availibleSocialOptions[0].value &&
		<div className="ahfb-social-add-area">
			{<SelectControl value={state.control} options={availibleSocialOptions} onChange={value => {
				setState(prevState => ({
					...prevState,
					control: value
				}));
			}}/>}
			{<Button
				className="ahfb-sorter-add-item"
				isPrimary
				onClick={() => {
					addItem();
				}}
			>
				{__('Add Social Icon', 'astra')}
			</Button>}

		</div>}
	</div>;

};

SocialComponent.propTypes = {
	control: PropTypes.object.isRequired,
};

export default SocialComponent;
