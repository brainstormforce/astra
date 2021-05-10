<?php
/**
 * Template part for displaying the Mobile Header
 *
 * @package Astra Builder
 */

$mobile_header_type = astra_get_option( 'mobile-header-type' );

if ( 'full-width' === $mobile_header_type ) {

	$mobile_header_type = 'off-canvas';
}

?>
<div id="ast-mobile-header" class="ast-mobile-header-wrap " data-type="<?php echo esc_attr( $mobile_header_type ); ?>">
	<?php
		do_action( 'astra_mobile_header_bar_top' );

		/**
		 * Astra Top Header
		 */
		do_action( 'astra_mobile_above_header' );

		/**
		 * Astra Main Header
		 */
		do_action( 'astra_mobile_primary_header' );

		/**
		 * Astra Mobile Bottom Header
		 */
		do_action( 'astra_mobile_below_header' );

		astra_main_header_bar_bottom();
	?>
<?php 
if ( 'dropdown' === astra_get_option( 'mobile-header-type' ) || is_customize_preview() ) {
	$content_alignment = astra_get_option( 'header-offcanvas-content-alignment', 'flex-start' );
	$alignment_class   = 'content-align-' . $content_alignment . ' ';
	?>
	<div class="ast-mobile-header-content <?php echo esc_attr( $alignment_class ); ?>">
		<?php do_action( 'astra_mobile_header_content', 'popup', 'content' ); ?>
	</div>
<?php } ?>
</div>
