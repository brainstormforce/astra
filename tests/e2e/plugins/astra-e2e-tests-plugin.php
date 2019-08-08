<?php
/**
 * Plugin Name: Astra e2e tests helper
 * Plugin URI:  https://github.com/brainstormforce/astra
 * Description: Demo Plugin that can be installed during E2E tests.
 * Author:      Brainstorm Force
 * Author URI:  https://wpastra.com/
 */

namespace Astra\E2E;

add_action(
    'wp_ajax_clean_site',
    __NAMESPACE__ . '\\clean'
);

add_action(
    'wp_ajax_nopriv_clean_site',
    __NAMESPACE__ . '\\clean'
);

function clean() {
    delete_option( 'astra-settings' );
    remove_theme_mod( 'custom_logo' );
    delete_option( 'site_title' );
    delete_option( 'site_icon' );
    update_option( 'blogdescription', 'Astra Test Enviornment' );

    wp_send_json_success('Done');
}