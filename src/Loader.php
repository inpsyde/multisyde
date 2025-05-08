<?php
/**
 * Compatibility & Implementation for WordPress.
 *
 * @package Multisite_Improvements
 */

namespace Syde\MultisiteImprovements;

/**
 * Class Multisite_Improvements
 */
class Loader {

	/**
	 * Hook into WordPress.
	 *
	 * @return bool
	 */
	public static function init(): bool {
		if ( ! function_exists( 'is_multisite' ) ) {
			return false;
		}

		// Load any other functionality here!

		return true;
	}
}
