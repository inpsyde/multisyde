<?php
/**
 * The Feature Registry provides a way to load and manage all the available features.
 *
 * @package multisite-improvements
 */

namespace Syde\MultisiteImprovements;

use Syde\MultisiteImprovements\Features\SiteActivePlugins;

/**
 * Class FeatureRegistry
 */
final class FeatureRegistry {

	/**
	 * List of all classes containing improvements.
	 *
	 * @var class-string<LoadableFeature>[]
	 */
	private static array $improvements = array(
		SiteActivePlugins::class,
		// Add more improvements here.
	);

	/**
	 * Load all registered improvements.
	 *
	 * @return void
	 */
	public static function load_all(): void {
		foreach ( self::$improvements as $class ) {
			if ( is_subclass_of( $class, LoadableFeature::class ) ) {
				$class::init();
			}
		}
	}

	/**
	 * Get the list of improvements.
	 *
	 * @return class-string<LoadableFeature>[]
	 */
	public static function get_all(): array {
		return self::$improvements;
	}
}
