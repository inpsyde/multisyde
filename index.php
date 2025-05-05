<?php
/**
 * Plugin Name:       Multisite Improvements
 * Plugin URI:        https://github.com/inpsyde/multisite-improvements
 * Description:       Proof of concept for a Canonical Plugin with improvements for WordPress Multisite
 * Version:           1.0.0
 * Requires at least: 6.7
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require __DIR__ . '/src/class-multisite-improvements.php';

Multisite_Improvements::init();
