<?php
/**
 * The Feature Registry provides a way to load and manage all the available features.
 *
 * @package multisite-improvements
 */

namespace Syde\MultisiteImprovements;

use Syde\MultisiteImprovements\Features\GetSiteBy;
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
		GetSiteBy::class,
		// Add more improvements here.
	);

	/**
	 * Load the loadable improvements.
	 *
	 * @return void
	 */
	public static function load(): void {
		foreach ( self::$improvements as $class_name ) {
			$class_name::init();
		}
	}

	/**
	 * Get a list of presentable improvements.
	 *
	 * @return class-string<PresentableFeature>[]
	 */
	public static function get_presentable_classes(): array {
		return array_filter(
			self::$improvements,
			static function ( string $class_name ) {
				return is_subclass_of( $class_name, PresentableFeature::class );
			},
		);
	}
}
