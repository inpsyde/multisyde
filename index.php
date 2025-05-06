<?php
/**
 * Plugin Name:       Multisite Improvements
 * Plugin URI:        https://github.com/inpsyde/multisite-improvements
 * Description:       A WordPress plugin that explores potential enhancements for WordPress Multisite.
 * Version:           1.0.0
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            Syde
 * Author URI:        https://syde.com
 * License:           GPLv2 or later
 * License URI:       /LICENSE
 * Text Domain:       multisite-improvements
 * Domain Path:       /languages/
 *
 * @package multisite-improvements
 */

declare(strict_types=1);

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

Syde\MultisiteImprovements\Loader::init();
