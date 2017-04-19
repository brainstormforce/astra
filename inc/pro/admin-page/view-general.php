<?php
/**
 * View General
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0
 */

?>

<form class="wrap ast-clear" method="post" action="" >
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
				 	<div class="postbox ">
						<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Clear Cache', 'astra-theme' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
						<h2 class="ui-sortable-handle"><span><?php _e( 'Clear Cache', 'astra-theme' ); ?></span></h2>
						<div class="inside">
							<p>
								<?php _e( 'A CSS and JavaScript file is dynamically generated and cached each time you create a new layout. Sometimes the cache needs to be refreshed when you migrate your site to another server or update to the latest version. If you are running into any issues, please try clearing the cache by clicking the button below.', 'astra-theme' ); ?>
							</p>
							<?php wp_nonce_field( 'ast-clear-cache', 'ast-clear-cache-nonce' ); ?>
							<input type="submit" class="ast-clear-cache button button-primary" name="ast-clear-cache" value="<?php _e( 'Clear Cache', 'astra-theme' ); ?>">
						</div>
				 	</div>
				</div>
			</div><!-- #postbox-container-2 -->

		</div>
	</div>
	
</form>
