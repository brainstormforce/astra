<?php
/**
 * Astra framework initialization.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Astra Setup.
 */
function astra_setup() {

	$framework_path = ASTRA_THEME_DIR . 'framework/';
	require_once $framework_path . 'common-functions.php';
	require_once $framework_path . 'class-astra-attrs.php';
	require_once $framework_path . 'class-astra-schema-attrs.php';

	$structure_path = $framework_path . 'structure/';
	/* Common Layout Functions */
	require_once $structure_path . 'sidebar.php';
	require_once $structure_path . 'common.php';
	require_once $structure_path . '404.php';
	require_once $structure_path . 'blog.php';
	require_once $structure_path . 'archive.php';
	require_once $structure_path . 'index.php';
	require_once $structure_path . 'layout.php';
	require_once $structure_path . 'loops.php';
	require_once $structure_path . 'page.php';
	require_once $structure_path . 'search.php';
	require_once $structure_path . 'single.php';
	
	/* Execute All loops of hierarchy */
	require_once $structure_path . 'execute-loops.php';
}
add_action( 'astra_init', 'astra_setup' );

/* Setup Actions Start */
do_action( 'astra_before_setup' );

do_action( 'astra_init' );

do_action( 'astra_after_setup' );
/* Setup Actions End */
