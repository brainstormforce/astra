<?php
/**
 * Template for Small Footer Layout 2
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

$section_1 = astra_get_small_footer( 'footer-sml-section-1' );
$section_2 = astra_get_small_footer( 'footer-sml-section-2' );
$sections  = 0;

if ( '' != $section_1 ) {
	$sections++;
}

if ( '' != $section_2 ) {
	$sections++;
}

switch ( $sections ) {

	case '2':
			$section_class = 'ast-small-footer-section-equally ' . astra_attr( 'ast-grid-col-6' );
		break;

	case '1':
	default:
			$section_class = 'ast-small-footer-section-equally ' . astra_attr( 'ast-grid-common-col' );
		break;
}

?>

<div class="ast-small-footer footer-sml-layout-2">
	<div class="ast-footer-overlay">
		<div class="ast-container">
			<div class="ast-small-footer-wrap" >
					<div class="ast-row ast-flex">

					<?php if ( $section_1 ) : ?>
						<div class="ast-small-footer-section ast-small-footer-section-1 <?php echo esc_attr( $section_class ); ?>" >
							<?php
								echo $section_1; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
				<?php endif; ?>

					<?php if ( $section_2 ) : ?>
						<div class="ast-small-footer-section ast-small-footer-section-2 <?php echo esc_attr( $section_class ); ?>" >
							<?php
								echo $section_2; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
				<?php endif; ?>

					</div> <!-- .ast-row.ast-flex -->
			</div><!-- .ast-small-footer-wrap -->
		</div><!-- .ast-container -->
	</div><!-- .ast-footer-overlay -->
</div><!-- .ast-small-footer-->
