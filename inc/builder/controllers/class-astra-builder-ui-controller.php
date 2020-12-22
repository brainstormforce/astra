<?php
/**
 * Astra Builder UI Controller.
 *
 * @package astra-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Builder_UI_Controller' ) ) {

	/**
	 * Class Astra_Builder_UI_Controller.
	 */
	final class Astra_Builder_UI_Controller {

		/**
		 * Astra SVGs.
		 *
		 * @var ast_svgs
		 */
		private static $ast_svgs = null;

		/**
		 * Get an SVG Icon
		 *
		 * @param string $icon the icon name.
		 * @param bool   $base if the baseline class should be added.
		 */
		public static function fetch_svg_icon( $icon = '', $base = true ) {
			$output = '<span class="ahfb-svg-iconset ast-inline-flex' . ( $base ? ' svg-baseline' : '' ) . '">';

			if ( ! self::$ast_svgs ) {
				ob_start();
				include_once ASTRA_THEME_DIR . 'assets/svg/svgs.json'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				self::$ast_svgs = json_decode( ob_get_clean(), true );
				self::$ast_svgs = apply_filters( 'astra_svg_icons', self::$ast_svgs );
			}

			$output .= isset( self::$ast_svgs[ $icon ] ) ? self::$ast_svgs[ $icon ] : '';
			$output .= '</span>';

			return $output;
		}

		/**
		 * Prepare Social Icon HTML.
		 *
		 * @param string $index The Index of the social icon.
		 * @param string $builder_type the type of the builder.
		 */
		public static function render_social_icon( $index, $builder_type = 'header' ) {
			$items      = astra_get_option( $builder_type . '-social-icons-' . $index );
			$items      = isset( $items['items'] ) ? $items['items'] : array();
			$show_label = astra_get_option( $builder_type . '-social-' . $index . '-label-toggle' );
			$color_type = astra_get_option( $builder_type . '-social-' . $index . '-color-type' );

			echo '<div class="ast-' . esc_attr( $builder_type ) . '-social-' . esc_attr( $index ) . '-wrap ast-' . esc_attr( $builder_type ) . '-social-wrap">';

			if ( is_customize_preview() ) {
				self::render_customizer_edit_button();
			}

			echo '<div class="' . esc_attr( $builder_type ) . '-social-inner-wrap element-social-inner-wrap social-show-label-' . ( $show_label ? 'true' : 'false' ) . ' ast-social-color-type-' . esc_attr( $color_type ) . ' ast-social-element-style-filled">';
			if ( is_array( $items ) && ! empty( $items ) ) {

				foreach ( $items as $item ) {
					if ( $item['enabled'] ) {

						$link = $item['url'];

						switch ( $item['id'] ) {

							case 'phone':
								$link = 'tel:' . $item['url'];
								break;

							case 'email':
								$link = 'mailto:' . $item['url'];
								break;

							case 'whatsapp':
								$link = 'https://api.whatsapp.com/send?phone=' . $item['url'];
								break;
						}

						echo '<a href="' . esc_url( $link ) . '"' . esc_attr( $show_label ? ' aria-label=' . $item['label'] . '' : '' ) . ' ' . ( 'phone' === $item['id'] || 'email' === $item['id'] ? '' : 'target="_blank" rel="noopener noreferrer" ' ) . 'style="--color: ' . esc_attr( ! empty( $item['color'] ) ? $item['color'] : '#3a3a3a' ) . '; --background-color: ' . esc_attr( ! empty( $item['background'] ) ? $item['background'] : 'transparent' ) . ';" class="ast-builder-social-element ast-inline-flex ast-' . esc_attr( $item['id'] ) . ' ' . esc_attr( $builder_type ) . '-social-item">';
						echo self::fetch_svg_icon( $item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						if ( $show_label ) {
							echo '<span class="social-item-label">' . esc_html( $item['label'] ) . '</span>';
						}

						echo '</a>';
					}
				}
			}
			echo '</div>';
			echo '</div>';
		}

		/**
		 * Prepare HTML Markup.
		 *
		 * @param string $index Key of the HTML Control.
		 */
		public static function render_html_markup( $index = 'header-html-1' ) {

			$content = astra_get_option( $index );
			if ( $content || is_customize_preview() ) {
				$link_style = '';
				echo '<div class="ast-header-html inner-link-style-' . esc_attr( $link_style ) . '">';
				if ( is_customize_preview() ) {
					self::render_customizer_edit_button();
				}
				echo '<div class="ast-builder-html-element">';
				$content = str_replace( '[copyright]', '&copy;', $content );
				$content = str_replace( '[current_year]', gmdate( 'Y' ), $content );
				$content = str_replace( '[site_title]', get_bloginfo( 'name' ), $content );
				$content = str_replace( '[theme_author]', '<a href="https://www.wpastra.com/" rel="nofollow noopener" target="_blank">Astra WordPress Theme</a>', $content );
				echo do_shortcode( wpautop( $content ) );
				echo '</div>';
				echo '</div>';
			}

		}

		/**
		 * Prepare divider Markup.
		 *
		 * @param string $index Key of the divider Control.
		 */
		public static function render_divider_markup( $index = 'header-divider-1' ) {

			$layout = astra_get_option( $index . '-layout' );
			?>

			<div class="ast-divider-wrapper ast-divider-layout-<?php echo esc_attr( $layout ); ?>">
				<?php 
				if ( is_customize_preview() ) {
					self::render_customizer_edit_button();
				} 
				?>
				<div class="ast-builder-divider-element"></div>
			</div>

			<?php
		}
		
		/**
		 * Prepare Edit icon inside customizer.
		 */
		public static function render_customizer_edit_button() {
			?>
			<div class="customize-partial-edit-shortcut" data-id="ahfb">
				<button aria-label="<?php esc_attr_e( 'Click to edit this element.', 'astra' ); ?>"
						title="<?php esc_attr_e( 'Click to edit this element.', 'astra' ); ?>"
						class="customize-partial-edit-shortcut-button item-customizer-focus">
					<?php echo self::fetch_svg_icon( 'edit' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</div>
			<?php
		}

		/**
		 * Prepare Special Edit navigatory trigger for Builder Grid Rows in customizer.
		 *
		 * @param string $type Header / Footer row type.
		 * @param string $row_position Above / Primary / Below.
		 *
		 * @since 3.0.0
		 */
		public static function render_grid_row_customizer_edit_button( $type, $row_position ) {

			switch ( $row_position ) {
				case 'primary':
					/* translators: %s: icon term */
					$row_label = sprintf( __( 'Primary %s', 'astra' ), $type );
					break;
				case 'above':
					/* translators: %s: icon term */
					$row_label = sprintf( __( 'Above %s', 'astra' ), $type );
					break;
				case 'below':
					/* translators: %s: icon term */
					$row_label = sprintf( __( 'Below %s', 'astra' ), $type );
					break;
				default:
					$row_label = $type;
					break;
			}

			?>
			<div class="customize-partial-edit-shortcut row-editor-shortcut" data-id="ahfb">
				<button aria-label="<?php esc_attr_e( 'Click to edit this element.', 'astra' ); ?>"	title="<?php esc_attr_e( 'Click to edit this Row.', 'astra' ); ?>" class="item-customizer-focus">
					<?php echo self::fetch_svg_icon( 'edit' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</div>
			<?php
		}

		/**
		 * Render Trigger Markup.
		 *
		 * @since 3.0.0
		 */
		public static function render_mobile_trigger() {

			$icon             = astra_get_option( 'header-trigger-icon' );
			$mobile_label     = astra_get_option( 'mobile-header-menu-label' );
			$toggle_btn_style = astra_get_option( 'mobile-header-toggle-btn-style' );
			$aria_controls    = '';
			if ( ! Astra_Builder_Helper::$is_header_footer_builder_active ) {
				$aria_controls = 'aria-controls="primary-menu"';
			}
			?>
			<div class="ast-button-wrap">
				<button type="button" class="menu-toggle main-header-menu-toggle ast-mobile-menu-trigger-<?php echo esc_attr( $toggle_btn_style ); ?>" <?php echo esc_attr( $aria_controls ); ?> aria-expanded="false">
					<span class="screen-reader-text">Main Menu</span>
					<span class="mobile-menu-toggle-icon">
						<?php
							echo self::fetch_svg_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo self::fetch_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</span>
					<?php
					if ( isset( $mobile_label ) && ! empty( $mobile_label ) ) {
						?>

						<span class="mobile-menu-wrap">
							<span class="mobile-menu"><?php echo esc_html( $mobile_label ); ?></span>
						</span>
						<?php
					}
					?>
				</button>
			</div>
			<?php
		}

		/**
		 * Prepare Button HTML.
		 *
		 * @param string $index The Index of the button.
		 * @param string $builder_type the type of the builder.
		 */
		public static function render_button( $index, $builder_type = 'header' ) {
			if ( is_customize_preview() ) {
				self::render_customizer_edit_button();
			}
			$button_size = astra_get_option(  $builder_type . '-button' . $index . '-size' );
			
			echo '<div class="ast-builder-button-wrap ast-builder-button-size-' . $button_size . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo astra_get_custom_button( $builder_type . '-button' . $index . '-text', $builder_type . '-button' . $index . '-link-option', 'header-button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Site Identity.
		 */
		public static function render_site_identity() {
			?>

			<div class="site-branding">
				<?php
				if ( is_customize_preview() ) {
					self::render_customizer_edit_button();
				}
				?>
				<div
					<?php
					echo astra_attr(
						'site-identity',
						array(
							'class' => 'ast-site-identity',
						)
					);
					?>
				>
					<?php astra_logo(); ?>
				</div>
			</div>
			<!-- .site-branding -->
			<?php
		}

	}
}
