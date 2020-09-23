/* jshint esversion: 6 */
import PropTypes from 'prop-types';
import { ReactSortable } from "react-sortablejs";

import ItemComponent from './item-component';

const { __ } = wp.i18n;

const { ButtonGroup, Popover, Button, SelectControl } = wp.components;

const { Component, Fragment } = wp.element;
class SocialComponent extends Component {
	constructor() {
		super( ...arguments );
		this.updateValues = this.updateValues.bind( this );
		this.onDragEnd = this.onDragEnd.bind( this );
		this.onDragStart = this.onDragStart.bind( this );
		this.onDragStop = this.onDragStop.bind( this );
		this.removeItem = this.removeItem.bind( this );
		this.saveArrayUpdate = this.saveArrayUpdate.bind( this );
		this.toggleEnableItem = this.toggleEnableItem.bind( this );
		this.onChangeIcon = this.onChangeIcon.bind( this );
		this.onChangeLabel = this.onChangeLabel.bind( this );
		this.onChangeURL = this.onChangeURL.bind( this );
		this.onChangeAttachment = this.onChangeAttachment.bind( this );
		this.onChangeWidth = this.onChangeWidth.bind( this );
		this.onChangeSource = this.onChangeSource.bind( this );
		this.addItem = this.addItem.bind( this );
		let value = this.props.control.setting.get();
		let baseDefault = {
			'items': [
				{
					'id': 'facebook',
					'enabled': true,
					'source': 'icon',
					'url': '',
					'imageid': '',
					'width': 24,
					'icon': 'facebook',
					'label': 'Facebook',
				},
				{
					'id': 'twitter',
					'enabled': true,
					'source': 'icon',
					'url': '',
					'imageid': '',
					'width': 24,
					'icon': 'twitter',
					'label': 'Twitter',
				}
			],
		};
		this.defaultValue = this.props.control.params.default ? {
			...baseDefault,
			...this.props.control.params.default
		} : baseDefault;
		value = value ? {
			...this.defaultValue,
			...value
		} : this.defaultValue;
		let defaultParams = {
			'group' : 'social_item_group',
			'options': [
				{ value: 'facebook', label: __( 'Facebook', 'astra' ) },
				{ value: 'twitter', label: __( 'Twitter', 'astra' ) },
				{ value: 'instagram', label: __( 'Instagram', 'astra' ) },
				{ value: 'youtube', label: __( 'YouTube', 'astra' ) },
				{ value: 'facebook_group', label: __( 'Facebook Group', 'astra' ) },
				{ value: 'vimeo', label: __( 'Vimeo', 'astra' ) },
				{ value: 'pinterest', label: __( 'Pinterest', 'astra' ) },
				{ value: 'linkedin', label: __( 'Linkedin', 'astra' ) },
				{ value: 'medium', label: __( 'Medium', 'astra' ) },
				{ value: 'wordpress', label: __( 'WordPress', 'astra' ) },
				{ value: 'reddit', label: __( 'Reddit', 'astra' ) },
				{ value: 'patreon', label: __( 'Patreon', 'astra' ) },
				{ value: 'github', label: __( 'GitHub', 'astra' ) },
				{ value: 'dribbble', label: __( 'Dribbble', 'astra' ) },
				{ value: 'behance', label: __( 'Behance', 'astra' ) },
				{ value: 'vk', label: __( 'VK', 'astra' ) },
				{ value: 'xing', label: __( 'Xing', 'astra' ) },
				{ value: 'rss', label: __( 'RSS', 'astra' ) },
				{ value: 'email', label: __( 'Email', 'astra' ) },
				{ value: 'phone', label: __( 'Phone', 'astra' ) },
				{ value: 'whatsapp', label: __( 'WhatsApp', 'astra' ) },
				{ value: 'google_reviews', label: __( 'Google Reviews', 'astra' ) },
				{ value: 'telegram', label: __( 'Telegram', 'astra' ) },
				{ value: 'yelp', label: __( 'Yelp', 'astra' ) },
				{ value: 'trip_advisor', label: __( 'Trip Advisor', 'astra' ) },
				{ value: 'imdb', label: __( 'IMDB', 'astra' ) },
			].sort((a,b) => {
				if(a.value < b.value) { return -1; }
				if(a.value > b.value) { return 1; }
				return 0;
			} )
		};

		this.controlParams = this.props.control.params.input_attrs ? {
			...defaultParams,
			...this.props.control.params.input_attrs,
		} : defaultParams;
		let availibleSocialOptions = [];
		this.controlParams.options.map( ( option ) => {
			if ( ! value.items.some( obj => obj.id === option.value ) ) {
				availibleSocialOptions.push( option );
			}
		} );
		this.state = {
			value: value,
			isVisible: false,
			control: ( undefined !== availibleSocialOptions[0] && undefined !== availibleSocialOptions[0].value ? availibleSocialOptions[0].value : '' ),
		};
	}
	onDragStart() {
		var dropzones = document.querySelectorAll( '.ahfb-builder-area' );
		var i;
		for (i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.add( 'ahfb-dragging-dropzones' );
		}
	}
	onDragStop() {
		var dropzones = document.querySelectorAll( '.ahfb-builder-area' );
		var i;
		for (i = 0; i < dropzones.length; ++i) {
			dropzones[i].classList.remove( 'ahfb-dragging-dropzones' );
		}
	}
	saveArrayUpdate( value, index ) {
		let updateState = this.state.value;
		let items = updateState.items;

		const newItems = items.map( ( item, thisIndex ) => {
			if ( index === thisIndex ) {
				item = { ...item, ...value };
			}

			return item;
		} );
		updateState.items = newItems;
		this.setState( { value: updateState } );
		this.updateValues( updateState );
	}
	toggleEnableItem( value, itemIndex ) {
		this.saveArrayUpdate( { enabled: value }, itemIndex );
	}
	onChangeLabel( value, itemIndex ) {
		this.saveArrayUpdate( { label: value }, itemIndex );
	}
	onChangeIcon( value, itemIndex ) {
		this.saveArrayUpdate( { icon: value }, itemIndex );
	}
	onChangeURL( value, itemIndex ) {
		this.saveArrayUpdate( { url: value }, itemIndex );
	}
	onChangeAttachment( value, itemIndex ) {
		this.saveArrayUpdate( { imageid: value }, itemIndex );
	}
	onChangeWidth( value, itemIndex ) {
		this.saveArrayUpdate( { width: value }, itemIndex );
	}
	onChangeSource( value, itemIndex ) {
		this.saveArrayUpdate( { source: value }, itemIndex );
	}
	removeItem( itemIndex ) {
		let updateState = this.state.value;
		let update = updateState.items;
		let updateItems = [];
		{ update.length > 0 && (
			update.map( ( old, index ) => {
				if ( itemIndex !== index ) {
					updateItems.push( old );
				}
			} )
		) };
		updateState.items = updateItems;
		this.setState( { value: updateState } );
		this.updateValues( updateState );
	}
	addItem() {
		const itemControl = this.state.control;
		this.setState( { isVisible: false } );
		if ( itemControl ) {
			let updateState = this.state.value;
			let update = updateState.items;
			const itemLabel = this.controlParams.options.filter(function(o){return o.value === itemControl;} );
			let newItem = {
				'id': itemControl,
				'enabled': true,
				'source': 'icon',
				'url': '',
				'imageid': '',
				'width': 24,
				'icon': itemControl,
				'label': itemLabel[0].label,
			};
			update.push( newItem );
			updateState.items = update;
			let availibleSocialOptions = [];
			this.controlParams.options.map( ( option ) => {
				if ( ! update.some( obj => obj.id === option.value ) ) {
					availibleSocialOptions.push( option );
				}
			} );
			this.setState( { control: ( undefined !== availibleSocialOptions[0] && undefined !== availibleSocialOptions[0].value ? availibleSocialOptions[0].value : '' ) } );
			this.setState( { value: updateState } );
			this.updateValues( updateState );
		}
	}
	onDragEnd( items ) {
		let updateState = this.state.value;
		let update = updateState.items;
		let updateItems = [];
		{ items.length > 0 && (
			items.map( ( item ) => {
				update.filter( obj => {
					if ( obj.id === item.id ) {
						updateItems.push( obj );
					}
				} )
			} )
		) };
		if ( ! this.arraysEqual( update, updateItems ) ) {
			update.items = updateItems;
			updateState.items = updateItems;
			this.setState( { value: updateState } );
			this.updateValues( updateState );
		}
	}
	arraysEqual( a, b ) {
		if (a === b) return true;
		if (a == null || b == null) return false;
		if (a.length != b.length) return false;		
		for (var i = 0; i < a.length; ++i) {
			if (a[i] !== b[i]) return false;
		}
		return true;
	}
	render() {
		const currentList = ( typeof this.state.value != "undefined" && this.state.value.items != null && this.state.value.items.length != null && this.state.value.items.length > 0 ? this.state.value.items : [] );
		let theItems = [];
		{ currentList.length > 0 && (
			currentList.map( ( item ) => {
				theItems.push(
					{
						id: item.id,
					}
				)
			} )
		) };
		const availibleSocialOptions = [];
		this.controlParams.options.map( ( option ) => {
			if ( ! theItems.some( obj => obj.id === option.value ) ) {
				availibleSocialOptions.push( option );
			}
		} )
		const toggleClose = () => {
			if ( this.state.isVisible === true ) {
				this.setState( { isVisible: false } );
			}
		};
		return (
			<div className="ahfb-control-field ahfb-sorter-items">
				<div className="ahfb-sorter-row">
					<ReactSortable animation={100} onStart={ () => this.onDragStop() } onEnd={ () => this.onDragStop() } group={ this.controlParams.group } className={ `ahfb-sorter-drop ahfb-sorter-sortable-panel ahfb-sorter-drop-${ this.controlParams.group }` } handle={ '.ahfb-sorter-item-panel-header' } list={ theItems } setList={ ( newState ) => this.onDragEnd( newState ) } >
						{ currentList.length > 0 && (
							currentList.map( ( item, index ) => {
								return <ItemComponent removeItem={ ( remove ) => this.removeItem( remove ) } toggleEnabled={ ( enable, itemIndex ) => this.toggleEnableItem( enable, itemIndex ) } onChangeLabel={ ( label, itemIndex ) => this.onChangeLabel( label, itemIndex ) } onChangeSource={ ( source, itemIndex ) => this.onChangeSource( source, itemIndex ) } onChangeWidth={ ( width, itemIndex ) => this.onChangeWidth( width, itemIndex ) } onChangeURL={ ( url, itemIndex ) => this.onChangeURL( url, itemIndex ) } onChangeAttachment={ ( imageid, itemIndex ) => this.onChangeAttachment( imageid, itemIndex ) } onChangeIcon={ ( icon, itemIndex ) => this.onChangeIcon( icon, itemIndex ) } key={ item.id } index={ index } item={ item } controlParams={ this.controlParams } />;
							} )
						) }
					</ReactSortable>
				</div>
				{ undefined !== availibleSocialOptions[0] && undefined !== availibleSocialOptions[0].value && (
					<div className="ahfb-social-add-area">
						{<SelectControl
							value={ this.state.control }
							options={ availibleSocialOptions }
							onChange={ value => {
								this.setState( { control: value } );
							} }
						/> }
						{ this.state.isVisible && (
							<Popover position="top right" className="ahfb-popover-color ahfb-popover-social" onClose={ toggleClose }>
								<div className="ahfb-popover-social-list">
									<ButtonGroup className="ahfb-radio-container-control">
										{ availibleSocialOptions.map( ( item, index ) => {
											return (
												<Fragment>
													<Button
														isTertiary
														className={ 'social-radio-btn' }
														onClick={ () => {
															this.setState( { control: availibleSocialOptions[index].value } );
															this.state.control = availibleSocialOptions[index].value;
															this.addItem();
														} }
													>
														{ availibleSocialOptions[index].label && (
															availibleSocialOptions[index].label
														) }
													</Button>
												</Fragment>
											);
										} ) }
									</ButtonGroup>
								</div>
							</Popover>
						) }
						{<Button
							className="ahfb-sorter-add-item"
							isPrimary
							onClick={ () => {
								this.addItem();
							} }
						>
							{ __( 'Add Social Icon', 'astra' ) }
						</Button> }
					</div>
				) }
			</div>
		);
	}
	updateValues( value ) {
		this.props.control.setting.set( {
			...this.props.control.setting.get(),
			...value,
			flag: !this.props.control.setting.get().flag
		} );
	}
}

SocialComponent.propTypes = {
	control: PropTypes.object.isRequired,
};

export default SocialComponent;
