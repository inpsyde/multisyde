<?php
/**
 * FeatureInformation class
 *
 * @package multisyde
 */

namespace Syde\Multisyde;

/**
 * This class is used to store information about a feature.
 */
final class Summary {

	/**
	 * Title of the feature.
	 *
	 * @var string
	 */
	public string $title;

	/**
	 * Description of the feature.
	 *
	 * @var string
	 */
	public string $description;

	/**
	 * Trac tickets related to the feature.
	 *
	 * @var string[]
	 */
	public array $tickets;

	/**
	 * Constructor for the FeatureInformation class.
	 *
	 * @param string   $title       Title of the feature.
	 * @param string   $description Description of the feature.
	 * @param string[] $tickets     Trac tickets related to the feature.
	 */
	public function __construct( string $title, string $description, array $tickets = array() ) {
		$this->title       = $title;
		$this->description = $description;
		$this->tickets     = $tickets;
	}
}
