<?php
/**
 * Template part for displaying header row.
 *
 * @package Astra Builder
 */

?>
<div id="ast-desktop-header">
	<?php
		astra_main_header_bar_top();

		/**
		 * Astra Top Header
		 */
		do_action( 'astra_above_header' );

		/**
		 * Astra Main Header
		 */
		do_action( 'astra_primary_header' );

		/**
		 * Astra Bottom Header
		 */
		do_action( 'astra_below_header' );

		astra_main_header_bar_bottom();
	?>
</div> <!-- Main Header Bar Wrap -->
<?php
/**
 * Astra Mobile Header
 */
do_action( 'astra_mobile_header' );
?>
