<?php
/**
 * Customizer Control: color.
 *
 * Creates a jQuery color control.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       1.4.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field overrides.
 */
if ( ! class_exists( 'Astra_Control_Responsive_Background' ) && class_exists( 'WP_Customize_Control' ) ) :

	/**
	 * Color control (alpha).
	 */
	class Astra_Control_Responsive_Background extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'ast-responsive-background';

		/**
		 * The responsive type.
		 *
		 * @access public
		 * @var string
		 */
		public $responsive = true;

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $suffix = '';

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['default'] = $this->setting->default;
			if ( isset( $this->default ) ) {
				$this->json['default'] = $this->default;
			}

			$this->json['value']  = $this->value();
			$this->json['link']   = $this->get_link();
			$this->json['id']     = $this->id;
			$this->json['label']  = esc_html( $this->label );
			$this->json['suffix'] = $this->suffix;

			$this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {

			wp_localize_script(
				'custom-control-script',
				'astraCustomizerControlBackground',
				array(
					'placeholder'  => __( 'No file selected', 'astra' ),
					'lessSettings' => __( 'Less Settings', 'astra' ),
					'moreSettings' => __( 'More Settings', 'astra' ),
				)
			);
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 *
		 * @access protected
		 */
		protected function content_template() {
			?>
			<label>
				<# if ( data.label ) { #>
					<span class="customize-control-title">{{{ data.label }}}</span>
				<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
			</label>
				<ul class="ast-responsive-btns">
					<li class="desktop">
						<button type="button" class="preview-desktop" data-device="desktop">
							<i class="dashicons dashicons-desktop"></i>
						</button>
					</li>
					<li class="tablet">
						<button type="button" class="preview-tablet" data-device="tablet">
							<i class="dashicons dashicons-tablet"></i>
						</button>
					</li>
					<li class="mobile">
						<button type="button" class="preview-mobile" data-device="mobile">
							<i class="dashicons dashicons-smartphone"></i>
						</button>
					</li>
				</ul>

				<div class="customize-control-content">
						<div class="background-wrapper">

							<!-- Background for Desktop -->
							<div class="background-container desktop active">
								<!-- background-color -->
								<div class="background-color">
									<span class="customize-control-title"><?php esc_html_e( 'Background Color', 'astra' ); ?></span>
									<input type="text" data-name="{{ data.name }}" data-default-color="{{ data.default['desktop']['background-color'] }}" data-id='desktop' data-alpha="true" value="{{ data.value['desktop']['background-color'] }}" class="ast-responsive-bg-color-control"/>
								</div>

								<!-- background-image -->
								<div class="background-image">
									<span class="customize-control-title"><?php esc_html_e( 'Background Image', 'astra' ); ?></span>
									<div class="attachment-media-view background-image-upload">
										<# if ( data.value['desktop']['background-image'] ) { #>
											<div class="thumbnail thumbnail-image" ><img src="{{ data.value['desktop']['background-image'] }}" data-id='desktop' data-name="{{ data.name }}" alt="" /></div>
										<# } else { #>
											<div class="placeholder" ><?php esc_html_e( 'No Image Selected', 'astra' ); ?></div>
										<# } #>
										<div class="actions">
											<button data-name="{{ data.name }}" class="button background-image-upload-remove-button<# if ( ! data.value['desktop']['background-image'] ) { #> hidden <# } #>" data-id='desktop' ><?php esc_html_e( 'Remove', 'astra' ); ?></button>
											<button type="button" data-name="{{ data.name }}" class="button background-image-upload-button"  data-id='desktop' ><?php esc_html_e( 'Select Image', 'astra' ); ?></button>
											<# if ( data.value['desktop']['background-image'] ) { #>
												<a href="#" class="more-settings" data-direction="up" data-id='desktop' ><span class="message"><?php esc_html_e( 'Less Settings', 'astra' ); ?></span> <span class="icon">&uarr;</span></a>
											<# } else { #>
												<a href="#" class="more-settings" data-direction="down" data-id='desktop' ><span class="message"><?php esc_html_e( 'More Settings', 'astra' ); ?></span> <span class="icon">&darr;</span></a>
											<# } #>
										</div>
									</div>
								</div>

								<!-- background-repeat -->
								<div class="background-repeat">
									<select {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-id='desktop' data-name="{{ data.name }}">
										<option value="no-repeat"<# if ( 'no-repeat' === data.value['desktop']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'No Repeat', 'astra' ); ?></option>
										<option value="repeat"<# if ( 'repeat' === data.value['desktop']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat All', 'astra' ); ?></option>
										<option value="repeat-x"<# if ( 'repeat-x' === data.value['desktop']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Horizontally', 'astra' ); ?></option>
										<option value="repeat-y"<# if ( 'repeat-y' === data.value['desktop']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Vertically', 'astra' ); ?></option>
									</select>
								</div>

								<!-- background-position -->
								<div class="background-position">
									<select {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-id='desktop' data-name="{{ data.name }}">
										<option value="left top"<# if ( 'left top' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Top', 'astra' ); ?></option>
										<option value="left center"<# if ( 'left center' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Center', 'astra' ); ?></option>
										<option value="left bottom"<# if ( 'left bottom' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Bottom', 'astra' ); ?></option>
										<option value="right top"<# if ( 'right top' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Top', 'astra' ); ?></option>
										<option value="right center"<# if ( 'right center' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Center', 'astra' ); ?></option>
										<option value="right bottom"<# if ( 'right bottom' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Bottom', 'astra' ); ?></option>
										<option value="center top"<# if ( 'center top' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Top', 'astra' ); ?></option>
										<option value="center center"<# if ( 'center center' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Center', 'astra' ); ?></option>
										<option value="center bottom"<# if ( 'center bottom' === data.value['desktop']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Bottom', 'astra' ); ?></option>
									</select>
								</div>

								<!-- background-size -->
								<div class="background-size">
									<h4><?php esc_html_e( 'Background Size', 'astra' ); ?></h4>
									<div class="buttonset">
										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} class="switch-input screen-reader-text" type="radio" value="cover" data-name="{{{ data.id }}}"  name="_customize-bg-{{{ data.id }}}-desktop-size" data-id='desktop' id="{{{ data.id }}}-desktop-cover" <# if ( 'cover' === data.value['desktop']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'cover' === data.value['desktop']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-desktop-cover"><?php esc_html_e( 'Cover', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="contain" name="_customize-bg-{{{ data.id }}}-desktop-size" data-id='desktop' id="{{{ data.id }}}-desktop-contain" <# if ( 'contain' === data.value['desktop']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'contain' === data.value['desktop']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-desktop-contain"><?php esc_html_e( 'Contain', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="auto" name="_customize-bg-{{{ data.id }}}-desktop-size" data-id='desktop' id="{{{ data.id }}}-desktop-auto" <# if ( 'auto' === data.value['desktop']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'auto' === data.value['desktop']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-desktop-auto"><?php esc_html_e( 'Auto', 'astra' ); ?></label>

									</div>
								</div>

								<!-- background-attachment -->
								<div class="background-attachment">
									<h4><?php esc_html_e( 'Background Attachment', 'astra' ); ?></h4>
									<div class="buttonset">
										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="inherit" name="_customize-bg-{{{ data.id }}}-desktop-attachment" data-id='desktop' id="{{{ data.id }}}-desktop-inherit" <# if ( 'inherit' === data.value['desktop']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'inherit' === data.value['desktop']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-desktop-inherit"><?php esc_html_e( 'Inherit', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="scroll" name="_customize-bg-{{{ data.id }}}-desktop-attachment" data-id='desktop' id="{{{ data.id }}}-desktop-scroll" <# if ( 'scroll' === data.value['desktop']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'scroll' === data.value['desktop']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-desktop-scroll"><?php esc_html_e( 'Scroll', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="fixed" name="_customize-bg-{{{ data.id }}}-desktop-attachment" data-id='desktop' id="{{{ data.id }}}-desktop-fixed" <# if ( 'fixed' === data.value['desktop']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'fixed' === data.value['desktop']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-desktop-fixed"><?php esc_html_e( 'Fixed', 'astra' ); ?></label>

									</div>
								</div>
							</div>

							<!-- Background for Tablet -->
							<div class="background-container tablet">
								<!-- background-color -->
								<div class="background-color">
									<span class="customize-control-title" ><?php esc_html_e( 'Background Color', 'astra' ); ?></span>
									<input data-name="{{ data.name }}" type="text" data-default-color="{{ data.default['tablet']['background-color'] }}" data-id='tablet' data-alpha="true" value="{{ data.value['tablet']['background-color'] }}" class="ast-responsive-bg-color-control"/>
								</div>

								<!-- background-image -->
								<div class="background-image">
									<span class="customize-control-title" ><?php esc_html_e( 'Background Image', 'astra' ); ?></span>
									<div class="attachment-media-view background-image-upload">
										<# if ( data.value['tablet']['background-image'] ) { #>
											<div class="thumbnail thumbnail-image"><img src="{{ data.value['tablet']['background-image'] }}" data-id='tablet' data-name="{{ data.name }}" alt="" /></div>
										<# } else { #>
											<div class="placeholder"><?php esc_html_e( 'No Image Selected', 'astra' ); ?></div>
										<# } #>
										<div class="actions">
											<button data-name="{{ data.name }}" data-id="tablet" class="button background-image-upload-remove-button<# if ( ! data.value['tablet']['background-image'] ) { #> hidden <# } #>" data-id='tablet' ><?php esc_html_e( 'Remove', 'astra' ); ?></button>
											<button type="button" data-name="{{ data.name }}" class="button background-image-upload-button" data-id='tablet' ><?php esc_html_e( 'Select Image', 'astra' ); ?></button>
											<# if ( data.value['tablet']['background-image'] ) { #>
												<a href="#" class="more-settings" data-direction="up" data-id='tablet'><span class="message"><?php esc_html_e( 'Less Settings', 'astra' ); ?></span> <span class="icon">&uarr;</span></a>
											<# } else { #>
												<a href="#" class="more-settings" data-direction="down" data-id='tablet'><span class="message"><?php esc_html_e( 'More Settings', 'astra' ); ?></span> <span class="icon">&darr;</span></a>
											<# } #>
										</div>
									</div>
								</div>

								<!-- background-repeat -->
								<div class="background-repeat">
									<select {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-id='tablet' data-name="{{ data.name }}">
										<option value="no-repeat"<# if ( 'no-repeat' === data.value['tablet']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'No Repeat', 'astra' ); ?></option>
										<option value="repeat"<# if ( 'repeat' === data.value['tablet']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat All', 'astra' ); ?></option>
										<option value="repeat-x"<# if ( 'repeat-x' === data.value['tablet']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Horizontally', 'astra' ); ?></option>
										<option value="repeat-y"<# if ( 'repeat-y' === data.value['tablet']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Vertically', 'astra' ); ?></option>
									</select>
								</div>

								<!-- background-position -->
								<div class="background-position">
									<select {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-id='tablet' data-name="{{ data.name }}">
										<option value="left top"<# if ( 'left top' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Top', 'astra' ); ?></option>
										<option value="left center"<# if ( 'left center' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Center', 'astra' ); ?></option>
										<option value="left bottom"<# if ( 'left bottom' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Bottom', 'astra' ); ?></option>
										<option value="right top"<# if ( 'right top' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Top', 'astra' ); ?></option>
										<option value="right center"<# if ( 'right center' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Center', 'astra' ); ?></option>
										<option value="right bottom"<# if ( 'right bottom' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Bottom', 'astra' ); ?></option>
										<option value="center top"<# if ( 'center top' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Top', 'astra' ); ?></option>
										<option value="center center"<# if ( 'center center' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Center', 'astra' ); ?></option>
										<option value="center bottom"<# if ( 'center bottom' === data.value['tablet']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Bottom', 'astra' ); ?></option>
									</select>
								</div>

								<!-- background-size -->
								<div class="background-size">
									<h4><?php esc_html_e( 'Background Size', 'astra' ); ?></h4>
									<div class="buttonset">
										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="cover" name="_customize-bg-{{{ data.id }}}-tablet-size" data-id='tablet' id="{{{ data.id }}}-tablet-cover" <# if ( 'cover' === data.value['tablet']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'cover' === data.value['tablet']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-tablet-cover"><?php esc_html_e( 'Cover', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="contain" name="_customize-bg-{{{ data.id }}}-tablet-size" data-id='tablet' id="{{{ data.id }}}-tablet-contain" <# if ( 'contain' === data.value['tablet']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'contain' === data.value['tablet']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-tablet-contain"><?php esc_html_e( 'Contain', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="auto" name="_customize-bg-{{{ data.id }}}-tablet-size" data-id='tablet' id="{{{ data.id }}}-tablet-auto" <# if ( 'auto' === data.value['tablet']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'auto' === data.value['tablet']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-tablet-auto"><?php esc_html_e( 'Auto', 'astra' ); ?></label>

									</div>
								</div>

								<!-- background-attachment -->
								<div class="background-attachment">
									<h4><?php esc_html_e( 'Background Attachment', 'astra' ); ?></h4>
									<div class="buttonset">
										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="inherit" name="_customize-bg-{{{ data.id }}}-tablet-attachment" data-id='tablet' id="{{{ data.id }}}-tablet-inherit" <# if ( 'inherit' === data.value['tablet']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'inherit' === data.value['tablet']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-tablet-inherit"><?php esc_html_e( 'Inherit', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="scroll" name="_customize-bg-{{{ data.id }}}-tablet-attachment" data-id='tablet' id="{{{ data.id }}}-tablet-scroll" <# if ( 'scroll' === data.value['tablet']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'scroll' === data.value['tablet']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-tablet-scroll"><?php esc_html_e( 'Scroll', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="fixed" name="_customize-bg-{{{ data.id }}}-tablet-attachment" data-id='tablet' id="{{{ data.id }}}-tablet-fixed" <# if ( 'fixed' === data.value['tablet']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'fixed' === data.value['tablet']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-tablet-fixed"><?php esc_html_e( 'Fixed', 'astra' ); ?></label>

									</div>
								</div>
							</div>

							<!-- Background for Mobile -->
							<div class="background-container mobile">
								<!-- background-color -->
								<div class="background-color">
									<span class="customize-control-title" ><?php esc_html_e( 'Background Color', 'astra' ); ?></span>
									<input type="text" data-name="{{ data.name }}" data-default-color="{{ data.default['mobile']['background-color'] }}" data-id='mobile' data-alpha="true" value="{{ data.value['mobile']['background-color'] }}" class="ast-responsive-bg-color-control"/>
								</div>

								<!-- background-image -->
								<div class="background-image">
									<span class="customize-control-title" ><?php esc_html_e( 'Background Image', 'astra' ); ?></span>
									<div class="attachment-media-view background-image-upload">
										<# if ( data.value['mobile']['background-image'] ) { #>
											<div class="thumbnail thumbnail-image"><img src="{{ data.value['mobile']['background-image'] }}" data-id='mobile' data-name="{{ data.name }}" alt="" /></div>
										<# } else { #>
											<div class="placeholder"><?php esc_html_e( 'No Image Selected', 'astra' ); ?></div>
										<# } #>
										<div class="actions">
											<button data-name="{{ data.name }}" data-id="mobile" class="button background-image-upload-remove-button<# if ( ! data.value['mobile']['background-image'] ) { #> hidden <# } #>" data-id='mobile' ><?php esc_html_e( 'Remove', 'astra' ); ?></button>
											<button type="button" data-name="{{ data.name }}" class="button background-image-upload-button" data-id='mobile' ><?php esc_html_e( 'Select Image', 'astra' ); ?></button>
											<# if ( data.value['mobile']['background-image'] ) { #>
												<a href="#" class="more-settings" data-direction="up" data-id='mobile'><span class="message"><?php esc_html_e( 'Less Settings', 'astra' ); ?></span> <span class="icon">&uarr;</span></a>
											<# } else { #>
												<a href="#" class="more-settings" data-direction="down" data-id='mobile'><span class="message"><?php esc_html_e( 'More Settings', 'astra' ); ?></span> <span class="icon">&darr;</span></a>
											<# } #>
										</div>
									</div>
								</div>

								<!-- background-repeat -->
								<div class="background-repeat">
									<select {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-id='mobile' data-name="{{ data.name }}">
										<option value="no-repeat"<# if ( 'no-repeat' === data.value['mobile']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'No Repeat', 'astra' ); ?></option>
										<option value="repeat"<# if ( 'repeat' === data.value['mobile']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat All', 'astra' ); ?></option>
										<option value="repeat-x"<# if ( 'repeat-x' === data.value['mobile']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Horizontally', 'astra' ); ?></option>
										<option value="repeat-y"<# if ( 'repeat-y' === data.value['mobile']['background-repeat'] ) { #> selected <# } #>><?php esc_html_e( 'Repeat Vertically', 'astra' ); ?></option>
									</select>
								</div>

								<!-- background-position -->
								<div class="background-position">
									<select {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-id='mobile' data-name="{{ data.name }}">
										<option value="left top"<# if ( 'left top' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Top', 'astra' ); ?></option>
										<option value="left center"<# if ( 'left center' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Center', 'astra' ); ?></option>
										<option value="left bottom"<# if ( 'left bottom' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Left Bottom', 'astra' ); ?></option>
										<option value="right top"<# if ( 'right top' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Top', 'astra' ); ?></option>
										<option value="right center"<# if ( 'right center' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Center', 'astra' ); ?></option>
										<option value="right bottom"<# if ( 'right bottom' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Right Bottom', 'astra' ); ?></option>
										<option value="center top"<# if ( 'center top' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Top', 'astra' ); ?></option>
										<option value="center center"<# if ( 'center center' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Center', 'astra' ); ?></option>
										<option value="center bottom"<# if ( 'center bottom' === data.value['mobile']['background-position'] ) { #> selected <# } #>><?php esc_html_e( 'Center Bottom', 'astra' ); ?></option>
									</select>
								</div>

								<!-- background-size -->
								<div class="background-size">
									<h4><?php esc_html_e( 'Background Size', 'astra' ); ?></h4>
									<div class="buttonset">
										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="cover" name="_customize-bg-{{{ data.id }}}-mobile-size"  data-id='mobile' id="{{{ data.id }}}-mobile-cover" <# if ( 'cover' === data.value['mobile']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'cover' === data.value['mobile']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-mobile-cover"><?php esc_html_e( 'Cover', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="contain" name="_customize-bg-{{{ data.id }}}-mobile-size"  data-id='mobile' id="{{{ data.id }}}-mobile-contain" <# if ( 'contain' === data.value['mobile']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'contain' === data.value['mobile']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-mobile-contain"><?php esc_html_e( 'Contain', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="auto" name="_customize-bg-{{{ data.id }}}-mobile-size"  data-id='mobile' id="{{{ data.id }}}-mobile-auto" <# if ( 'auto' === data.value['mobile']['background-size'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'auto' === data.value['mobile']['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-mobile-auto"><?php esc_html_e( 'Auto', 'astra' ); ?></label>

									</div>
								</div>

								<!-- background-attachment -->
								<div class="background-attachment">
									<h4><?php esc_html_e( 'Background Attachment', 'astra' ); ?></h4>
									<div class="buttonset">
										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="inherit" name="_customize-bg-{{{ data.id }}}-mobile-attachment"  data-id='mobile' id="{{{ data.id }}}-mobile-inherit" <# if ( 'inherit' === data.value['mobile']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'inherit' === data.value['mobile']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-mobile-inherit"><?php esc_html_e( 'Inherit', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="scroll" name="_customize-bg-{{{ data.id }}}-mobile-attachment"  data-id='mobile' id="{{{ data.id }}}-mobile-scroll" <# if ( 'scroll' === data.value['mobile']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'scroll' === data.value['mobile']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-mobile-scroll"><?php esc_html_e( 'Scroll', 'astra' ); ?></label>

										<input {{{ data.inputAttrs }}} {{{ data.dataAttrs }}} data-name="{{{ data.id }}}" class="switch-input screen-reader-text" type="radio" value="fixed" name="_customize-bg-{{{ data.id }}}-mobile-attachment"  data-id='mobile' id="{{{ data.id }}}-mobile-fixed" <# if ( 'fixed' === data.value['mobile']['background-attachment'] ) { #> checked="checked" <# } #>>
											<label class="switch-label switch-label-<# if ( 'fixed' === data.value['mobile']['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{{ data.id }}}-mobile-fixed"><?php esc_html_e( 'Fixed', 'astra' ); ?></label>

									</div>
								</div>
							</div>

							<input class="responsive-background-hidden-value" value="{{ JSON.stringify( data.value ) }}" data-name="{{data.name}}" type="hidden" {{{ data.link }}}>
						</div>
				</div>

			<?php
		}

		/**
		 * Render the control's content.
		 *
		 * @see WP_Customize_Control::render_content()
		 */
		protected function render_content() {}
	}

endif;
