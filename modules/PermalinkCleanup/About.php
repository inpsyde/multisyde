<?php
/**
 * Information class for the PermalinkCleanup feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\PermalinkCleanup;

use Syde\MultiSyde\Summary;
use Syde\MultiSyde\ShareableInformation;

/**
 * Provides information about the PermalinkCleanup feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Permalink Cleanup', 'multisyde' ),
			__( 'Handles permalink structure modifications for multisite installations. Removes /blog prefix from the base permalink structure.', 'multisyde' ),
			array(
				'https://github.com/inpsyde/multisyde/issues/24',
			)
		);
	}
}
