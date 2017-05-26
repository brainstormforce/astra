<?php
/**
 * View General
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0
 */

?>

<dev class="wrap ast-clear">
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="postbox-container-2" class="postbox-container astra-row">

				<div id="normal-sortables-1" class="astra-col-33">
					 <div class="postbox ">
						<h2 class="ui-sortable-handle"><span><?php esc_html_e( 'Welcome to Astra', 'astra' ); ?></span></h2>
						<div class="inside">
							<p>
								<?php esc_html_e( 'Astra is a very lightweight and beautiful theme made to work with Page Builders.', 'astra' ); ?>
							</p>
							<p>
								<?php esc_html_e( 'Go ahead and start customizing your website.', 'astra' ); ?>
							</p>
							<a class="submit button button-primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'astra' ); ?></a>
						</div>
					 </div>
				</div>

			</div><!-- #postbox-container-2 -->

		</div>
	</div>

</dev>
