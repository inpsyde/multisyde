<?php
/**
 * Permalink Cleanup Feature
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\PermalinkCleanup;

use Syde\MultiSyde\LoadableFeature;

/**
 * Feature Class PermalinkCleanup
 */
final class Feature implements LoadableFeature {

	/**
	 * Adds functionality to their respective hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_filter( 'sanitize_option_permalink_structure', array( __CLASS__, 'remove_blog_prefix' ) );
		add_filter( 'option_permalink_structure', array( __CLASS__, 'remove_blog_prefix' ) );
	}

	/**
	 * Remove /blog prefix from the permalink structure on the main site.
	 *
	 * In WordPress multisite installations, the main site often has /blog
	 * prepended to post permalinks. This function strips that prefix while
	 * leaving other sites untouched.
	 *
	 * @param string $value The permalink structure pattern (e.g., '/blog/%postname%').
	 *
	 * @return string The modified permalink structure with /blog removed,
	 *                or the original value if conditions aren't met.
	 */
	public static function remove_blog_prefix( string $value ): string {
		if ( ! is_multisite() || ! is_main_site() || empty( $value ) ) {
			return $value;
		}

		if ( strpos( $value, '/blog/' ) === 0 ) {
			return substr( $value, 5 );
		}

		if ( '/blog' === $value ) {
			return '';
		}

		return $value;
	}
}
