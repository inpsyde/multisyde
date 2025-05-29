<?php
/**
 * Information class for the LastUserLogin feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\Multisyde\Modules\LastUserLogin;

use Syde\Multisyde\Summary;
use Syde\Multisyde\ShareableInformation;

/**
 * Provides information about the LastUserLogin feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Last User Login', 'multisyde' ),
			__( 'Adds a sortable “Last Login” column to the Network Users screen and tracks when users last logged into the network.', 'multisyde' ),
			array()
		);
	}
}
