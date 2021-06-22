import PropTypes from 'prop-types';
import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { Dashicon, Button, ColorIndicator, TabPanel, __experimentalGradientPicker, ColorPicker, SelectControl, ColorPalette } from '@wordpress/components';
import { MediaUpload } from '@wordpress/media-utils';

const maybeGetColorForVariable = ( color, palette ) => {
	const paletteColors = palette.palette;

	if ( color.includes('var') ) {

		// Get color index from palette for color variable.
		const colorIndex = color.charAt(color.length - 2);
		color = paletteColors[colorIndex];
	}

	return color;
}

const globalIconSVG = () => {
	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width="16"
			height="16"
			viewBox="0 0 16 16"
			fill="none"
		>
			<path
				d="M7.99997 0.499249C12.143 0.499249 15.5015 3.85775 15.5015 8.00075C15.5015 12.143 12.143 15.5015 7.99997 15.5015C3.85697 15.5015 0.498474 12.143 0.498474 8.00075C0.498474 3.85775 3.85697 0.499249 7.99997 0.499249ZM10.2042 11.375H5.79497C6.28397 13.1855 7.13447 14.3765 7.99922 14.3765C8.86397 14.3765 9.71447 13.1855 10.2035 11.375H10.2042ZM4.63172 11.375H2.58872C3.31285 12.5337 4.38885 13.4302 5.65922 13.9332C5.26772 13.3182 4.94447 12.5487 4.70672 11.672L4.63022 11.3757L4.63172 11.375ZM13.4105 11.375H11.369C11.126 12.3762 10.775 13.25 10.3392 13.9332C11.5294 13.4625 12.5509 12.6455 13.2717 11.588L13.4105 11.3757V11.375ZM4.32047 6.5H1.80122L1.79747 6.51275C1.68111 7.00019 1.62246 7.49962 1.62272 8.00075C1.62272 8.79275 1.76747 9.551 2.03147 10.2507H4.41122C4.2301 9.00898 4.19912 7.74992 4.31897 6.50075L4.32047 6.5ZM10.5477 6.5H5.45222C5.32027 7.74899 5.35432 9.00995 5.55347 10.25H10.4465C10.6456 9.00994 10.6796 7.74899 10.5477 6.5ZM14.1987 6.5H11.6802C11.7267 6.98525 11.7515 7.48775 11.7515 8C11.7527 8.75301 11.698 9.50507 11.588 10.25H13.9677C14.2385 9.5308 14.3767 8.76849 14.3757 8C14.3757 7.48325 14.3142 6.98 14.1987 6.5ZM5.65997 2.0675L5.64272 2.0735C4.10779 2.68641 2.86957 3.86953 2.18747 5.375H4.47347C4.70897 4.061 5.11847 2.9165 5.66072 2.0675H5.65997ZM7.99997 1.62425L7.91297 1.628C6.96497 1.715 6.04697 3.2165 5.62247 5.375H10.379C9.95447 3.2225 9.04172 1.72325 8.09522 1.62875L7.99997 1.625V1.62425ZM10.34 2.06675L10.4202 2.198C10.922 3.032 11.303 4.127 11.5265 5.37575H13.8125C13.1614 3.93881 12.0025 2.793 10.5582 2.15825L10.34 2.0675V2.06675Z"
				fill="white"
				fillOpacity="0.7"
			></path>
		</svg>
	);
};

class AstraColorPickerControl extends Component {

	constructor( props ) {
		super( ...arguments );
		this.onChangeComplete = this.onChangeComplete.bind( this );
		this.onPaletteChangeComplete = this.onPaletteChangeComplete.bind( this );
		this.onChangeGradientComplete = this.onChangeGradientComplete.bind( this );
		this.renderImageSettings = this.renderImageSettings.bind( this );
		this.onRemoveImage = this.onRemoveImage.bind( this );
		this.onSelectImage = this.onSelectImage.bind( this );
		this.open = this.open.bind( this );
		this.onColorClearClick = this.onColorClearClick.bind( this );
		this.onColorResetClick = this.onColorResetClick.bind( this );

		this.state = {
			isVisible: false,
			refresh: false,
			color: this.props.color,
			modalCanClose: true,
			backgroundType: this.props.backgroundType,
			supportGradient: ( undefined === __experimentalGradientPicker ? false : true ),
		};
	}

	onResetRefresh() {
		if ( this.state.refresh === true ) {
			this.setState( { refresh: false } );
		} else {
			this.setState( { refresh: true } );
		}
	}

	render() {

		const {
			refresh,
			modalCanClose,
			isVisible,
			supportGradient,
			backgroundType,
		} = this.state

		const {
			allowGradient,
			allowImage
		} = this.props

		var disablePalette =  this.props.disablePalette;
		var enableDeleteIcon = this.props.enableDeleteIcon;

		const toggleVisible = () => {
			if ( refresh === true ) {
				this.setState( { refresh: false } );
			} else {
				this.setState( { refresh: true } );
			}
			this.setState( { isVisible: true } );
		};

		const toggleClose = () => {
			if ( modalCanClose ) {
				if ( isVisible === true ) {
					this.setState( { isVisible: false } );
				}
			}
		};

		const showingGradient = ( allowGradient && supportGradient ? true : false );

		let tabs = [
			{
				name: 'color',
				title: __( 'Color', 'astra' ),
				className: 'astra-color-background',
			},

		];

		if ( showingGradient ) {

			let gradientTab = {
				name: 'gradient',
				title: __( 'Gradient', 'astra' ),
				className: 'astra-image-background',
			};

			tabs.push( gradientTab )
		}

		if ( allowImage ) {

			let imageTab = {
				name: 'image',
				title: __( 'Image', 'astra' ),
				className: 'astra-image-background',
			};

			tabs.push( imageTab )
		}

		let finalpaletteColors = [];
		let globalColorPalette = wp.customize.control( 'astra-settings[global-color-palette]' ).setting.get();

		Object.entries(globalColorPalette.palette).forEach(([ index, color])=>{
			let palettePrefix = astra.customizer.globalPaletteStylePrefix;
			const paletteLables = astra.customizer.globalPaletteLabels;
			let paletteColors = {};
			Object.assign( paletteColors, { name: paletteLables[index], color: 'var('+ palettePrefix + index +')' } );
			finalpaletteColors.push( paletteColors );
		});

		return (
			<>
				<div className={ ( this.props.color && this.props.color.includes('var') ) ? 'color-button-wrap has-global-palette-color' : 'color-button-wrap' } >
					<Button className={ isVisible ? 'astra-color-icon-indicate open' : 'astra-color-icon-indicate' } onClick={ () => { isVisible ? toggleClose() : toggleVisible() } }>
						{ ( 'color' === backgroundType || 'gradient' === backgroundType ) &&
						<ColorIndicator className="astra-advanced-color-indicate" colorValue={ this.props.color } >
							<span className="global-color">{globalIconSVG()}</span>
							</ColorIndicator>
						}
						{ 'image' === backgroundType &&
						<>
							<ColorIndicator className="astra-advanced-color-indicate" colorValue='#ffffff' />
							<Dashicon icon="format-image" />
						</>
						}
					</Button>
				</div>
				<div className={"astra-color-picker-wrap " + (isVisible ? 'picker-open' : '')}>
					<>
						{ isVisible && (
							<div className="astra-popover-color" onClose={ toggleClose }>
								{ 1 < tabs.length &&
								<TabPanel className="astra-popover-tabs astra-background-tabs"
										  activeClass="active-tab"
										  initialTabName={ backgroundType }
										  tabs={ tabs }>
									{
										( tab ) => {
											let tabout;

											if ( tab.name ) {
												if ( 'gradient' === tab.name ) {
													tabout = (
														<>
															<__experimentalGradientPicker
																className="ast-gradient-color-picker"
																value={ this.props.color && this.props.color.includes( 'gradient' ) ? this.props.color : '' }
																onChange={ ( gradient ) => this.onChangeGradientComplete( gradient ) }
															/>
														</>
													);
												}  if ( 'image' === tab.name ) {
													tabout = (
														this.renderImageSettings()
													);
												} else if ( 'color' === tab.name ){
													tabout = (
														<>
															{ refresh && (
																<>
																	<ColorPicker
																		color={ maybeGetColorForVariable(this.props.color, globalColorPalette) }
																		onChangeComplete={ ( color ) => this.onChangeComplete( color ) }
																	/>
																</>
															) }
															{ ! refresh &&  (
																<>
																	<ColorPicker
																		color={ maybeGetColorForVariable(this.props.color, globalColorPalette) }
																		onChangeComplete={ ( color ) => this.onChangeComplete( color ) }
																	/>

																</>
															) }
															{ ! disablePalette && (
																<>
																<ColorPalette
																	colors={ finalpaletteColors }
																	value={ this.props.color }
																	clearable={ false }
																	disableCustomColors={ true }
																	className="ast-color-palette"
																	onChange={ ( color ) => this.onPaletteChangeComplete( color )
																}
																/>
																</>
															) }
															<button type="button" onClick = { () => { this.onColorClearClick() } } className="ast-clear-btn-inside-picker components-button common components-circular-option-picker__clear is-secondary is-small">{ __( 'Clear', 'astra' ) }</button>

														</>
													);
												}
											}
											return <div>{ tabout }</div>;
										}
									}
								</TabPanel>
								}
								{ 1 === tabs.length &&

								<>
									{ refresh && (
										<>
											<ColorPicker
												color={ maybeGetColorForVariable(this.props.color, globalColorPalette) }
												onChangeComplete={ ( color ) => this.onChangeComplete( color ) }
											/>
										</>
									) }
									{ ! refresh &&  (
										<>
											<ColorPicker
												color={ maybeGetColorForVariable(this.props.color, globalColorPalette) }
												onChangeComplete={ ( color ) => this.onChangeComplete( color ) }
											/>

										</>
									) }
									{ ! disablePalette && (
										<>
										<ColorPalette
											colors={ finalpaletteColors }
											value={ this.props.color }
											clearable={ false }
											disableCustomColors={ true }
											className="ast-color-palette"
											onChange={ ( color ) => this.onPaletteChangeComplete( color ) }
										/>

										<button type="button" onClick = { () => { this.onColorClearClick() } } className="ast-clear-btn-inside-picker components-button components-circular-option-picker__clear is-secondary is-small">{ __( 'Clear', 'astra' ) }</button>
										</>
									) }

									{ disablePalette &&
										<button type="button" onClick = { () => { this.onColorResetClick() } } className="ast-reset-btn-inside-picker components-button common components-circular-option-picker__reset is-secondary is-small">{ __( 'Reset', 'astra' ) }</button>
									}
								</>
								}
							</div>
						) }
					</>
				</div>
			</>
		);
	}

	onColorClearClick() {

		if ( this.state.refresh === true ) {
			this.setState( { refresh: false } );
		} else {
			this.setState( { refresh: true } );
		}
		this.props.onChangeComplete( '', 'color' );
		wp.customize.previewer.refresh();
	}

	onColorResetClick() {

		if ( this.state.refresh === true ) {
			this.setState( { refresh: false } );
		} else {
			this.setState( { refresh: true } );
		}
		this.props.onColorResetClick( '', 'color' );

	}

	onChangeGradientComplete( gradient ) {

		this.setState( { backgroundType: 'gradient' } );
		this.props.onChangeComplete( gradient, 'gradient' );
	}

	onChangeComplete( color ) {

		let newColor;
		if ( color.rgb && color.rgb.a && 1 !== color.rgb.a ) {
			newColor = 'rgba(' +  color.rgb.r + ',' +  color.rgb.g + ',' +  color.rgb.b + ',' + color.rgb.a + ')';
		} else {
			newColor = color.hex;
		}
		this.setState( { backgroundType: 'color' } );
		this.props.onChangeComplete( color, 'color' );
	}

	onPaletteChangeComplete( color ) {
		this.setState( { color: color } );
		if ( this.state.refresh === true ) {
			this.setState( { refresh: false } );
		} else {
			this.setState( { refresh: true } );
		}
		this.props.onChangeComplete( color, 'color' );
	}

	onSelectImage( media ) {

		this.setState( { modalCanClose: true } );
		this.setState( { backgroundType: 'image' } );
		this.props.onSelectImage( media, 'image' );
	}
	onRemoveImage() {

		this.setState( { modalCanClose: true } );
		this.props.onSelectImage( '' );
	}

	open( open ) {
		this.setState( { modalCanClose: false } );
		open()
	}

	onChangeImageOptions( tempKey, mainkey, value ) {
		this.setState( { backgroundType: 'image' } );
		this.props.onChangeImageOptions( mainkey, value, 'image' );
	}

	toggleMoreSettings() {

		let parent = event.target.parentElement.parentElement;
		let trigger = parent.querySelector( '.more-settings' );
		let wrapper = parent.querySelector( '.media-position-setting' );

		let dataDirection = trigger.dataset.direction;
		let dataId = trigger.dataset.id;

		if( 'down' === dataDirection ) {
			trigger.setAttribute( 'data-direction', 'up' );
			parent.querySelector('.message').innerHTML = __( "Less Settings" );
			parent.querySelector('.icon').innerHTML = '↑';
		} else {
			trigger.setAttribute( 'data-direction', 'down' );
			parent.querySelector('.message').innerHTML = __( "More Settings" );
			parent.querySelector('.icon').innerHTML = '↓';
		}

		if ( wrapper.classList.contains( 'hide-settings' ) ) {
			wrapper.classList.remove( 'hide-settings' );
		} else {
			wrapper.classList.add( 'hide-settings' );
		}
	}

	renderImageSettings() {

		return (
			<>
				{ ( this.props.media.url || this.props.backgroundImage ) &&

				<img src={ ( this.props.media.url ) ? this.props.media.url : this.props.backgroundImage } />
				}
				<MediaUpload
					title={ __( "Select Background Image", 'astra' )  }
					onSelect={ ( media ) =>  this.onSelectImage( media ) }
					allowedTypes={ [ "image" ] }
					value={ ( this.props.media && this.props.media ? this.props.media :  '' ) }
					render={ ( { open } ) => (
						<Button className="upload-button button-add-media" isDefault onClick={ () => this.open( open ) }>
							{ ( ! this.props.media && ! this.props.backgroundImage ) ? __( "Select Background Image", 'astra' )  : __( "Replace image", 'astra' )  }
						</Button>
					) }
				/>

				{ ( this.props.media || this.props.backgroundImage ) &&
				<>
					<Button className="ast-bg-img-remove" onClick={ this.onRemoveImage } isLink isDestructive>
						{ __( "Remove Image", 'astra' ) }
					</Button>

					<a href="#" className="more-settings" onClick={ this.toggleMoreSettings } data-direction="down" data-id="desktop">
						<span className="message"> { __( "More Settings" ) } </span>
						<span className="icon"> ↓ </span>
					</a>

					<div className="media-position-setting hide-settings">
						<SelectControl
							label={ __( "Image Position" ) }
							value={ this.props.backgroundPosition }
							onChange={ ( value ) => this.onChangeImageOptions( 'backgroundPosition', 'background-position', value  ) }
							options={ [
								{ value: "left top", label:  __( "Left Top", 'astra'  )  },
								{ value: "left center", label:  __( "Left Center", 'astra'  )  },
								{ value: "left bottom", label:  __( "Left Bottom", 'astra'  )  },
								{ value: "right top", label:  __( "Right Top", 'astra'  )  },
								{ value: "right center", label:  __( "Right Center", 'astra'  )  },
								{ value: "right bottom", label:  __( "Right Bottom", 'astra'  )  },
								{ value: "center top", label:  __( "Center Top", 'astra'  )  },
								{ value: "center center", label:  __( "Center Center", 'astra'  )  },
								{ value: "center bottom", label:  __( "Center Bottom", 'astra'  )  },
							] }
						/>
						<SelectControl
							label={ __( "Attachment", 'astra' ) }
							value={ this.props.backgroundAttachment }
							onChange={ ( value ) => this.onChangeImageOptions( 'backgroundAttachment', 'background-attachment', value  ) }
							options={ [
								{ value: "fixed", label:  __( "Fixed", 'astra' )  },
								{ value: "scroll", label:  __( "Scroll", 'astra' )  }
							] }
						/>
						<SelectControl
							label={ __( "Repeat", 'astra' ) }
							value={ this.props.backgroundRepeat }
							onChange={ ( value ) => this.onChangeImageOptions( 'backgroundRepeat', 'background-repeat', value  ) }
							options={ [
								{ value: "no-repeat", label:  __( "No Repeat", 'astra' )  },
								{ value: "repeat", label:  __( "Repeat All", 'astra' )  },
								{ value: "repeat-x", label:  __( "Repeat Horizontally", 'astra' )  },
								{ value: "repeat-y", label:  __( "Repeat Vertically", 'astra' )  }
							] }
						/>
						<SelectControl
							label={ __( "Size", 'astra' ) }
							value={ this.props.backgroundSize }
							onChange={ ( value ) => this.onChangeImageOptions( 'backgroundSize', 'background-size', value  ) }
							options={ [
								{ value: "auto", label:  __( "Auto", 'astra' )  },
								{ value: "cover", label:  __( "Cover", 'astra' )  },
								{ value: "contain", label:  __( "Contain", 'astra' )  }
							] }
						/>
					</div>
				</>
				}
			</>
		)
	}
}

AstraColorPickerControl.propTypes = {
	color: PropTypes.string,
	usePalette: PropTypes.bool,
	palette: PropTypes.string,
	presetColors: PropTypes.object,
	onChangeComplete: PropTypes.func,
	onPaletteChangeComplete: PropTypes.func,
	onChange: PropTypes.func,
	customizer: PropTypes.object
};

export default AstraColorPickerControl;
