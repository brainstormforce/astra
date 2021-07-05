<?php
/**
 * Search Form for Astra theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.3.0
 */

/**
 * Adding argument checks to avoid rendering search-form markup from other places & to easily use get_search_form() function.
 *
 * @see https://themes.trac.wordpress.org/ticket/101061
 * @since 3.6.1
 */
$astra_search_input_placeholder = isset( $args['input_placeholder'] ) ? $args['input_placeholder'] : astra_default_strings( 'string-search-input-placeholder', false );
$astra_search_show_input_submit = isset( $args['show_input_submit'] ) ? $args['show_input_submit'] : true;
$astra_search_data_attrs        = isset( $args['data_attributes'] ) ? $args['data_attributes'] : '';
$astra_search_input_value       = isset( $args['input_value'] ) ? $args['input_value'] : '';

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'astra' ); ?></span>
		<input type="search" class="search-field" <?php echo esc_html( $astra_search_data_attrs ); ?> placeholder="<?php echo esc_html( $astra_search_input_placeholder ); ?>" value="<?php echo esc_attr( $astra_search_input_value ); ?>" name="s" tabindex="-1">
		<?php if ( class_exists( 'Astra_Icons' ) && Astra_Icons::is_svg_icons() ) { ?>
			<button class="search-submit ast-search-submit" aria-label="<?php echo esc_attr__( 'Search Submit', 'astra' ); ?>">
				<span hidden><?php echo esc_html__( 'Search', 'astra' ); ?></span>
				<i><?php Astra_Icons::get_icons( 'search', true ); ?></i>
			</button>
		<?php } ?>
	</label>
	<?php if ( $astra_search_show_input_submit ) { ?>
		<input type="submit" class="search-submit" value="<?php echo esc_attr__( 'Search', 'astra' ); ?>">
	<?php } ?>
</form>
<?php
