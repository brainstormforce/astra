<?php
/**
 * Template part for displaying the a row of the header
 *
 * @package Astra Builder
 */

if ( astra_wp_version_compare( '5.4.99', '>=' ) ) {
	$row = wp_parse_args( $args, array( 'row' => '' ) );
	$row = $row['row'];
} else {
	$row = get_query_var( 'row' );
}

if ( Astra_Builder_Helper::is_row_empty( $row, 'header', 'desktop' ) ) {

	$customizer_editor_row        = 'section-' . esc_attr( $row ) . '-header-builder';
	$is_transparent_header_enable = astra_get_option( 'transparent-header-enable' );

	$row_label = ( 'primary' === $row ) ? 'main' : $row;

	?>
		<div class="ast-<?php echo esc_attr( $row_label ); ?>-header-wrap <?php echo 'primary' === $row ? 'main-header-bar-wrap' : ''; ?>" >
		<div class="<?php echo esc_attr( 'ast-' . $row . '-header-bar ast-' . $row . '-header' ); ?> <?php echo 'primary' === $row ? 'main-header-bar' : ''; ?> site-<?php echo esc_attr( $row ); ?>-header-wrap ast-builder-grid-row-container site-header-focus-item" data-section="<?php echo esc_attr( $customizer_editor_row ); ?>">
			<div class="ast-builder-grid-row-container-inner">
			<?php
			if ( is_customize_preview() ) {
				Astra_Builder_UI_Controller::render_grid_row_customizer_edit_button( 'Header', $row );
			}
			/**
			 * Astra Render before Site Content.
			 */
			do_action( "astra_header_{$row}_container_before" );
			?>
			<div class="site-container site-<?php echo esc_attr( $row ); ?>-header-wrap ast-builder-grid-row-container site-header-focus-item ast-container" data-section="<?php echo esc_attr( $customizer_editor_row ); ?>">
				<div class="site-<?php echo esc_attr( $row ); ?>-header-inner-wrap ast-builder-grid-row <?php echo ( Astra_Builder_Helper::has_side_columns( $row ) ? 'ast-builder-grid-row-has-sides' : 'ast-grid-center-col-layout-only ast-flex' ); ?> <?php echo ( Astra_Builder_Helper::has_center_column( $row ) ? 'ast-grid-center-col-layout' : 'ast-builder-grid-row-no-center' ); ?>">
					<?php if ( Astra_Builder_Helper::has_side_columns( $row ) ) { ?>
						<div class="site-header-<?php echo esc_attr( $row ); ?>-section-left site-header-section ast-flex site-header-section-left">
							<?php
								/**
								 * Astra Render Header Column
								 */
								do_action( 'astra_render_header_column', $row, 'left' );
							if ( Astra_Builder_Helper::has_center_column( $row ) ) {
								?>
										<div class="site-header-<?php echo esc_attr( $row ); ?>-section-left-center site-header-section ast-flex ast-grid-left-center-section">
									<?php
									/**
									 * Astra Render Header Column
									 */
									do_action( 'astra_render_header_column', $row, 'left_center' );
									?>
										</div>
									<?php
							}
							?>
						</div>
						<?php } ?>
						<?php if ( Astra_Builder_Helper::has_center_column( $row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $row ); ?>-section-center site-header-section ast-flex ast-grid-section-center">
								<?php
								/**
								 * Astra Render Header Column
								 */
								do_action( 'astra_render_header_column', $row, 'center' );
								?>
							</div>
						<?php } ?>
						<?php if ( Astra_Builder_Helper::has_side_columns( $row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $row ); ?>-section-right site-header-section ast-flex ast-grid-right-section">
								<?php
								if ( Astra_Builder_Helper::has_center_column( $row ) ) {
									?>
									<div class="site-header-<?php echo esc_attr( $row ); ?>-section-right-center site-header-section ast-flex ast-grid-right-center-section">
										<?php
										/**
										 * Astra Render Header Column
										 */
										do_action( 'astra_render_header_column', $row, 'right_center' );
										?>
									</div>
									<?php
								}
								/**
								 * Astra Render Header Column
								 */
								do_action( 'astra_render_header_column', $row, 'right' );
								?>
							</div>
						<?php } ?>
						</div>
					</div>
					<?php
					/**
					 * Astra Render after Site Content.
					 */
					do_action( "astra_header_{$row}_container_after" );
					?>
				</div>	
			</div>
		</div>
	<?php
}
