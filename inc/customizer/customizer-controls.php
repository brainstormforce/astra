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
require $control_dir . '/sortable/class-astra-control-sortable.php';
require $control_dir . '/radio-image/class-astra-control-radio-image.php';
require $control_dir . '/slider/class-astra-control-slider.php';
require $control_dir . '/responsive-slider/class-astra-control-responsive-slider.php';
require $control_dir . '/responsive/class-astra-control-responsive.php';
require $control_dir . '/typography/class-astra-control-typography.php';
require $control_dir . '/font-variant/class-astra-control-font-variant.php';
require $control_dir . '/responsive-spacing/class-astra-control-responsive-spacing.php';
require $control_dir . '/divider/class-astra-control-divider.php';
require $control_dir . '/heading/class-astra-control-heading.php';
require $control_dir . '/hidden/class-astra-control-hidden.php';
require $control_dir . '/link/class-astra-control-link.php';
require $control_dir . '/color/class-astra-control-color.php';
require $control_dir . '/description/class-astra-control-description.php';
require $control_dir . '/background/class-astra-control-background.php';
require $control_dir . '/responsive-color/class-astra-control-responsive-color.php';
require $control_dir . '/responsive-background/class-astra-control-responsive-background.php';
require $control_dir . '/border/class-astra-control-border.php';
require $control_dir . '/customizer-link/class-astra-control-customizer-link.php';
require $control_dir . '/settings-group/class-astra-control-settings-group.php';
require $control_dir . '/select/class-astra-control-select.php';
require $control_dir . '/responsive-select/class-astra-control-responsive-select.php';
// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
