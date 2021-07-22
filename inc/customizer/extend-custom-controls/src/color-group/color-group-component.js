import PropTypes from 'prop-types';
import { __ } from '@wordpress/i18n';
import AstraColorPickerControl from '../common/astra-color-picker-control';
import {Dashicon, Tooltip} from '@wordpress/components';
import {useState, useEffect} from 'react';

const ColorGroupComponent = props => {

	let htmlLabel = null;
	let htmlHelp = null;
	let responsiveHtml = null;
	let optionsHtml = null;
	let innerOptionsHtml = null;

	const {
		label,
		help,
		name,
		responsive
	} = props.control.params;

	const linkedSubColors = AstraBuilderCustomizerData.js_configs.sub_controls[name];
	const colorGroup = [],
	colorGroupDefaults = [],
	tooltips = [],
	colorGroupType = [];

	const linkRemoteUpdate = () => {

		document.addEventListener( 'AstRemoteUpdateState', function( e ) {
			if ( e.detail === 'btn-preset' ) {
				Object.entries( linkedSubColors ).map( ( [ key,value ] ) => {
					colorGroup[value.name] = wp.customize.control( value.name ).setting.get();
					colorGroupDefaults[value.name] = value.default;
					tooltips[value.name] = value.title;
					colorGroupType[value.name] = value.control_type;
				});

				setState( colorGroup );
				setFlag( 1 ); // This is used to Re render component on first custom event.
			}
		} );
	}

	linkRemoteUpdate();

	Object.entries( linkedSubColors ).map( ( [ key,value ] ) => {
		colorGroup[value.name] = wp.customize.control( value.name ).setting.get();
		colorGroupDefaults[value.name] = value.default;
		tooltips[value.name] = value.title;
		colorGroupType[value.name] = value.control_type;
	});

	const[ colorGroupState , setState ] = useState(colorGroup);
	const[ flag , setFlag ] = useState(0);

	const handleChangeComplete = ( key, color='', device='', backgroundType='' ) => {
		let updateState = {
			...colorGroupState
		};

		console.log( "in handlechange" );

		let value;

		if (typeof color === 'string') {
			value = color;
		} else if (undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a) {
			value = `rgba(${color.rgb.r},${color.rgb.g},${color.rgb.b},${color.rgb.a})`;
		} else {
			value = color.hex;
		}
		if ( '' !== device ) {
			let newState = {
				...updateState[key]
			};

			if('' !== backgroundType){
				let deviceType = {
					...newState[device]
				};
				deviceType['background-color'] = value;
				deviceType['background-type'] = backgroundType;
				newState[device] = deviceType;
				updateState[key] = newState;
				wp.customize.control( key ).setting.set(newState);
			}else{
				newState[device] = value;
				updateState[key] = newState;
				wp.customize.control( key ).setting.set(newState);
			}
        } else {
			if('' !== backgroundType){
				let newState = {
					...updateState[key]
				};

				newState['background-color'] = value;
				newState['background-type'] = backgroundType;
				updateState[key] = newState;
				wp.customize.control( key ).setting.set(newState);
			}else{
				updateState[key] = value;
				wp.customize.control( key ).setting.set(value);
			}
        }
		setState(updateState);
	};

	const updateValues = (stateValue, dbValue,key) =>{
		wp.customize.control( key ).setting.set(stateValue);
		setState(dbValue);
	}

	const onSelectImage = (key, media, device='', backgroundType) => {
		let updateState = {
			...colorGroupState
		};
		let newState = {
			...updateState[key]
		};
		if ( '' !== device ) {
			let deviceType = {
				...newState[device]
			};
			deviceType['background-image'] = media.url;
			deviceType['background-media'] = media.id;
			deviceType['background-type'] = backgroundType;
			newState[device] = deviceType;
			updateState[key] = newState;
			updateValues(newState,updateState,key);
		}else{
			newState['background-image'] = media.url;
			newState['background-media'] = media.id;
			newState['background-type'] = backgroundType;
			updateState[key] = newState;
			updateValues(newState,updateState,key);
		}
	};

	const onChangeImageOptions = (mainKey, value, device='', backgroundType, key) => {
		let updateState = {
			...colorGroupState
		};
		let newState = {
			...updateState[key]
		};
		if ( '' !== device ) {
			let deviceType = {
				...newState[device]
			};

			deviceType[mainKey] = value;
			deviceType['background-type'] = backgroundType;
			newState[device] = deviceType;
			updateState[key] = newState;
			updateValues(newState,updateState,key);
		}else{
			newState[mainKey] = value;
			newState['background-type'] = backgroundType;
			updateState[key] = newState;
			updateValues(newState,updateState,key);
		}

	};

	const updateBackgroundType = (device,key) => {
		let updateState = {
			...colorGroupState
		};

		if (!updateState[key][device]['background-type']) {
			let newState = {
				...updateState[key]
			};
			let deviceType = {
				...newState[device]
			};

			if (updateState[key][device]['background-color']) {
				deviceType['background-type'] = 'color';
				newState[device] = deviceType;
				updateState[key] = newState;
				wp.customize.control( key ).setting.set(newState);
				setState(updateState);

				if (updateState[key][device]['background-color'].includes('gradient')) {
					deviceType['background-type'] = 'gradient';
					newState[device] = deviceType;
					updateState[key] = newState;
					wp.customize.control( key ).setting.set(newState);
					setState(updateState);
				}
			}

			if (updateState[key][device]['background-image']) {
				deviceType['background-type'] = 'image';
				newState[device] = deviceType;
				updateState[key] = newState;
				wp.customize.control( key ).setting.set(newState);
				setState(updateState);
			}
		}
	}

	Object.entries( colorGroupState ).map( ( [ key,value ] ) => {
		if(colorGroupType[key] === "ast-responsive-background"){
			useEffect(() => {

				let devices = ['desktop', 'mobile', 'tablet'];
				for (let device of devices) {
					updateBackgroundType(device,key);
				}

			}, []);
		}
	})

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
		let multipleGroup = Object.entries( colorGroupState ).length > 2 ? 'ast-multiple-colors-group' :'';

		if (responsive) {
			responsiveHtml = <ul key={'ast-resp-ul'} className={`ast-responsive-btns ${ multipleGroup } `}>
				<li key={'desktop'} className="desktop active">
					<button type="button" className="preview-desktop" data-device="desktop">
						<i className="dashicons dashicons-desktop"></i>
					</button>
				</li>
				<li key={'tablet'} className="tablet">
					<button type="button" className="preview-tablet" data-device="tablet">
						<i className="dashicons dashicons-tablet"></i>
					</button>
				</li>
				<li key={'mobile'} className="mobile">
					<button type="button" className="preview-mobile" data-device="mobile">
						<i className="dashicons dashicons-smartphone"></i>
					</button>
				</li>
			</ul>;
		}
	}

	if (help) {
		htmlHelp = <span className="ast-description">{help}</span>;
	}

	const renderInputHtml = ( device ) => {

		console.log( "in renderInputHTML" );

		if( responsive ){
			innerOptionsHtml = Object.entries( colorGroupState ).map( ( [ key,value ] ) => {
				let tooltip = tooltips[key] || __('Color', 'astra');
				if(colorGroupType[key] === "ast-responsive-background"){
					return (
						<Tooltip key={ key } text={ tooltip } position="top center">
							<div className="color-group-item" id={ key }>
							<AstraColorPickerControl
								color={undefined !== value[device]['background-color'] && value[device]['background-color'] ? value[device]['background-color'] : ''}
								onChangeComplete={(color, backgroundType) => handleChangeComplete(key, color, device, backgroundType)}
								media={undefined !== value[device]['background-media'] && value[device]['background-media'] ? value[device]['background-media'] : ''}
								backgroundImage={undefined !== value[device]['background-image'] && value[device]['background-image'] ? value[device]['background-image'] : ''}
								backgroundAttachment={undefined !== value[device]['background-attachment'] && value[device]['background-attachment'] ? value[device]['background-attachment'] : ''}
								backgroundPosition={undefined !== value[device]['background-position'] && value[device]['background-position'] ? value[device]['background-position'] : ''}
								backgroundRepeat={undefined !== value[device]['background-repeat'] && value[device]['background-repeat'] ? value[device]['background-repeat'] : ''}
								backgroundSize={undefined !== value[device]['background-size'] && value[device]['background-size'] ? value[device]['background-size'] : ''}
								onSelectImage={(media, backgroundType) => onSelectImage(key, media, device, backgroundType)}
								onChangeImageOptions={(mainKey, value, backgroundType) => onChangeImageOptions(mainKey, value, device, backgroundType, key)}
								backgroundType={undefined !== value[device]['background-type'] && value[device]['background-type'] ? value[device]['background-type'] : 'color'}
								allowGradient={true} allowImage={true}/>
							</div>
						</Tooltip>
					);
				}else{
					return (
						<Tooltip key={ key } text={ tooltip } position="top center">
							<div className="color-group-item" id={ key }>
								<AstraColorPickerControl color={value ? value[device] : ''}
								onChangeComplete={(color, backgroundType) => handleChangeComplete(key, color, device)}
								backgroundType={'color'}
								allowGradient={false}
								allowImage={false}/>
							</div>
						</Tooltip>
					);
				}
			});
			return innerOptionsHtml
		}else{
			innerOptionsHtml = Object.entries( colorGroupState ).map( ( [ key,value ] ) => {
				let tooltip = tooltips[key] || __('Color', 'astra');
				if(colorGroupType[key] === "ast-background"){
					return (
						<Tooltip key={ key } text={ tooltip } position="top center">
							<div className="color-group-item" id={ key }>
							<AstraColorPickerControl
								color={undefined !== value['background-color'] && value['background-color'] ? value['background-color'] : ''}
								onChangeComplete={(color, backgroundType) => handleChangeComplete(key, color, backgroundType)}
								media={undefined !== value['background-media'] && value['background-media'] ? value['background-media'] : ''}
								backgroundImage={undefined !== value['background-image'] && value['background-image'] ? value['background-image'] : ''}
								backgroundAttachment={undefined !== value['background-attachment'] && value['background-attachment'] ? value['background-attachment'] : ''}
								backgroundPosition={undefined !== value['background-position'] && value['background-position'] ? value['background-position'] : ''}
								backgroundRepeat={undefined !== value['background-repeat'] && value['background-repeat'] ? value['background-repeat'] : ''}
								backgroundSize={undefined !== value['background-size'] && value['background-size'] ? value['background-size'] : ''}
								onSelectImage={(media, backgroundType) => onSelectImage(key, media, backgroundType)}
								onChangeImageOptions={(mainKey, value, backgroundType) => onChangeImageOptions(mainKey, value, backgroundType, key)}
								backgroundType={undefined !== value['background-type'] && value['background-type'] ? value['background-type'] : 'color'}
								allowGradient={true} allowImage={true}/>
							</div>
						</Tooltip>
					);
				}else{
					return (
						<Tooltip key={ key } text={ tooltip } position="top center">
							<div className="color-group-item" id={ key }>
								<AstraColorPickerControl color={value ? value : ''}
								onChangeComplete={(color, backgroundType) => handleChangeComplete(key, color)}
								backgroundType={'color'}
								allowGradient={false}
								allowImage={false}/>
							</div>
						</Tooltip>
					);
				}
			});
			return innerOptionsHtml
		}
	}

	if (responsive) {
		optionsHtml = <>
		<div className ="ast-color-group-responsive-wrap desktop active">
			{ renderInputHtml('desktop', 'active') }
		</div>
		<div className ="ast-color-group-responsive-wrap tablet">
			{renderInputHtml('tablet')}
		</div>
		<div className ="ast-color-group-responsive-wrap mobile">
			{renderInputHtml('mobile')}
		</div>
		</>;
	} else {
		optionsHtml = <>
			{renderInputHtml()}
		</>;
	}

	const renderResetButton = () => {
		let resetFlag = true;

		for ( let index in colorGroupState ) {
			if ( JSON.stringify( colorGroupState[index] ) !== JSON.stringify( colorGroupDefaults[index] ) ) {
				resetFlag = false;
			}
		}
		const multipleGroup = Object.entries( colorGroupState ).length > 2 ? 'ast-color-multiple-group-reset' :'';

		return <div className={`ast-color-btn-reset-wrap ${ multipleGroup } ast-color-group-reset `}>
			<button
				className="ast-reset-btn components-button components-circular-option-picker__clear is-secondary is-small"
				disabled={ resetFlag } onClick={ e => {
				e.preventDefault();
				let resetState = {
					...colorGroupState
				};
				for ( let index in colorGroupState ) {
					resetState[index] = colorGroupDefaults[index];
					wp.customize.control( index ).setting.set(colorGroupDefaults[index]);
					setState(resetState);
				}
			}}>
			<Dashicon icon='image-rotate'/>
			</button>
		</div>;
	};

	return <div className="ast-control-wrap">
		<div className="ast-toggle-desc-wrap">
			<label className="customizer-text">
				{htmlLabel}
				{htmlHelp}
			</label>
		</div>
			{ responsiveHtml }
			{ renderResetButton() }
		<div className="ast-field-color-group-wrap">
			{optionsHtml}
		</div>
	</div>;
};

ColorGroupComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default ColorGroupComponent;
