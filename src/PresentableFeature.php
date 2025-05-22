<?php
/**
 * Presentable classes should implement this interface.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultisiteImprovements;

/**
 * Interface PresentableFeature
 */
interface PresentableFeature {


	/**
	 * Hook into WordPress.
	 *
	 * @return FeatureInformation
	 */
	public static function get_feature_information(): FeatureInformation;
}
