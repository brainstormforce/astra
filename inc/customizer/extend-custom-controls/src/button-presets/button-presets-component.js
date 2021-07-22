import PropTypes from "prop-types";
import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "react";

const ButtonPresetsComponent = (props) => {
	const { title, options } = props.control.params;
	const defaultValue = props.control.params.default;
	let value = props.control.setting.get();
	const option = props.control.params.name;

	const [state, setState] = value ? useState(value) : useState( defaultValue );

	const onChangePreset = (presetKey) => {
		let borderRadius = options[presetKey]["border-radius"];
		let btnBackgroundColor = options[presetKey]["button-bg-color"];
		let borderWidth = options[presetKey]["border-size"];
		let padding = options[presetKey]["button-padding"];
		let btnTextColor = options[presetKey]["button-color"];

		/// Padding
		props.customizer
			.control("astra-settings[theme-button-padding]")
			.setting.set(padding);

		props.customizer
			.control("astra-settings[theme-button-padding]")
			.renderContent();

		// Border Radius.
		props.customizer
			.control("astra-settings[button-radius]")
			.setting.set(borderRadius);

		// Border size.
		props.customizer
			.control("astra-settings[theme-button-border-group-border-size]")
			.setting.set(borderWidth);

		if( '' !== btnBackgroundColor ) {

			let cachedValue = props.customizer.control("astra-settings[button-bg-color]").setting.get();

			// Set button background color cached in window variable while switching to transparent button preset.
			if( 'rgba(0,0,0,0)' == btnBackgroundColor && 'rgba(0,0,0,0)' !== cachedValue ) {
				window.cachedBtnBGColor = cachedValue;
			}

			// Set Button Background color
			props.customizer.control("astra-settings[button-bg-color]").setting.set( btnBackgroundColor );
		} else {

			let cachedValue = window.cachedBtnBGColor;
			let optionValue = props.customizer.control("astra-settings[button-bg-color]").setting.get();

			// Set option value in window cached variable if value is not transparent.
			if( cachedValue != optionValue && 'rgba(0,0,0,0)' !== optionValue ) {
				window.cachedBtnBGColor = optionValue;
				cachedValue  = window.cachedBtnBGColor;
			}

			props.customizer.control("astra-settings[button-bg-color]").setting.set( cachedValue );
		}

		let btnTextOptionVal = props.customizer.control("astra-settings[button-color]").setting.get();

		if( '' == btnTextOptionVal ) {
			props.customizer.control("astra-settings[button-color]").setting.set( btnTextColor );
		}

		// If button text option value is equal to default button color set text color to blank.
		if( options['button_04']['button-color'] == btnTextOptionVal && '' == btnTextColor ) {
			props.customizer.control("astra-settings[button-color]").setting.set( '' );
		}

		setState( presetKey );
		props.control.setting.set( presetKey );

		var event = new CustomEvent("AstRemoteUpdateState", {
			detail: "btn-preset",
		});
		document.dispatchEvent(event);
	};

	const renderBtnPresetHtml = () => {
		let htmlContent = Object.entries(options).map(([key, presetData]) => {
			return (
				<div
					className={
						"ast-btn-style-item " + (state === key ? "active" : "")
					}
					dangerouslySetInnerHTML={{
						__html: window.svgIcons[presetData.src],
					}}
					onClick={() => onChangePreset(key)}
				></div>
			);
		});

		return htmlContent;
	};

	return (
		<>
			<label>
				<span className="customize-control-title">{title}</span>
			</label>
			<div className="ast-btn-preset-wrap">{renderBtnPresetHtml()}</div>
		</>
	);
};

ButtonPresetsComponent.propTypes = {
	control: PropTypes.object.isRequired,
};

export default React.memo(ButtonPresetsComponent);
