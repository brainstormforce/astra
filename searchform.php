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

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'astra' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_html( astra_default_strings( 'string-search-input-placeholder', false ) ); ?>" value="" name="s">
		<?php if ( Astra_Icons::is_svg_icons() ) { ?>
			<button class="search-submit">
				<span hidden><?php echo esc_html__( 'Search', 'astra' ); ?></span>
				<i><?php Astra_Icons::get_icons( 'search', true ); ?></i>
			</button>
		<?php } ?>
	</label>
	<input type="submit" class="search-submit" value="Search">
</form>
<?php
