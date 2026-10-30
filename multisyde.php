<?php
/**
 * Plugin Name:       MultiSyde
 * Plugin URI:        https://github.com/inpsyde/multisyde
 * Description:       A WordPress plugin that explores potential improvements for WordPress Multisite.
 * Version: 1.2.0+main.c8b4800
 * SHA: c8b48006a798edf6ff8c0a46d54d181955190e77
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            Syde
 * Author URI:        https://syde.com
 * License:           GPLv2 or later
 * License URI:       /LICENSE
 * Text Domain:       multisyde
 * Domain Path:       /languages/
 * Network:           true
 *
 * @package multisyde
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

Syde\MultiSyde\Plugin::init();
