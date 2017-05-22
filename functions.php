<?php
/**
 * Astra functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'AST_THEME_VERSION', '1.0.3.1' );
define( 'AST_THEME_SETTINGS', 'ast-settings' );
define( 'AST_THEME_DIR', get_template_directory() . '/' );
define( 'AST_THEME_URI', get_template_directory_uri() . '/' );

/**
 * Update theme
 */
require_once AST_THEME_DIR . 'inc/core/class-ast-theme-update.php';

/**
 * Load theme hooks
 */
require_once AST_THEME_DIR . 'inc/core/class-ast-theme-options.php';
require_once AST_THEME_DIR . 'inc/core/class-theme-strings.php';

/**
 * Fonts Files
 */
if ( is_admin() ) {
	require_once AST_THEME_DIR . 'inc/customizer/class-ast-fonts-data.php';
}

require_once AST_THEME_DIR . 'inc/customizer/class-ast-fonts.php';

require_once AST_THEME_DIR . 'inc/core/common-functions.php';
require_once AST_THEME_DIR . 'inc/core/class-ast-enqueue-scripts.php';
require_once AST_THEME_DIR . 'inc/class-ast-dynamic-css.php';

/**
 * Custom template tags for this theme.
 */
require_once AST_THEME_DIR . 'inc/template-tags.php';

require_once AST_THEME_DIR . 'inc/widgets.php';
require_once AST_THEME_DIR . 'inc/core/theme-hooks.php';
require_once AST_THEME_DIR . 'inc/admin-functions.php';
require_once AST_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once AST_THEME_DIR . 'inc/extras.php';
require_once AST_THEME_DIR . 'inc/blog/blog-config.php';
require_once AST_THEME_DIR . 'inc/blog/blog.php';
require_once AST_THEME_DIR . 'inc/blog/single-blog.php';
/**
 * Markup Files
 */
require_once AST_THEME_DIR . 'inc/template-parts.php';

/**
 * Functions and definitions.
 */
require_once AST_THEME_DIR . 'inc/class-ast-after-setup-theme.php';

// Required files.
require_once AST_THEME_DIR . 'inc/core/class-ast-admin-helper.php';

if ( is_admin() ) {

	/**
	 * Admin Menu Settings
	 */
	require_once AST_THEME_DIR . 'inc/core/class-ast-admin-settings.php';

	/**
	 * Metabox additions.
	 */
	require_once AST_THEME_DIR . 'inc/metabox/class-ast-meta-boxes.php';
}

require_once AST_THEME_DIR . 'inc/metabox/class-ast-meta-box-operations.php';

/**
 * Customizer additions.
 */
require_once AST_THEME_DIR . 'inc/customizer/class-ast-customizer.php';

/**
 * Compatibility
 */
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-jetpack.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-woocommerce.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-cornerstone.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-beaver-builder.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-beaver-themer.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-bb-ultimate-addon.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-contact-form-7.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-visual-composer.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-site-origin.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-elementor.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-gravity-forms.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-bnr-flyout.php';
require_once AST_THEME_DIR . 'inc/compatibility/class-ast-lifter-lms.php';

if ( file_exists( AST_THEME_DIR . 'admin/class-graupi-astra-theme.php' ) ) {
	require_once AST_THEME_DIR . 'admin/class-graupi-astra-theme.php';
}
