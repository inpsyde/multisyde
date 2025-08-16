<?php
/**
 * Information class for the RetiredSiteStatus feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\RetiredSiteStatus;

use Syde\MultiSyde\Summary;
use Syde\MultiSyde\ShareableInformation;

/**
 * Provides information about the RetiredSiteStatus feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Retired Site Status', 'multisyde' ),
			__(
				'Adds a "Retired" status to sites in the network. This status can be used to mark sites that are no longer active or have been retired, allowing for better management of inactive sites in a multisite network. The retired status can be used to filter and manage sites effectively.',
				'multisyde'
			),
			array(
				'https://core.trac.wordpress.org/ticket/63378',
			)
		);
	}
}
