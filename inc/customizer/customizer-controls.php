<?php
/**
 * Astra Theme Customizer Controls.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$control_dir = ASTRA_THEME_DIR . 'inc/customizer/custom-controls';

// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require $control_dir . '/class-astra-customizer-control-base.php';
require $control_dir . '/typography/class-astra-control-typography.php';
require $control_dir . '/description/class-astra-control-description.php';
require $control_dir . '/customizer-link/class-astra-control-customizer-link.php';
require $control_dir . '/font-variant/class-astra-control-font-variant.php';
// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
